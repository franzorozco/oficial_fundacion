<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->input('role'));
            });
        }

        if ($request->filled('email_domain')) {
            $query->where('email', 'like', '%' . $request->input('email_domain'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }
        if ($request->filled('city')) {
            $query->where('address', $request->input('city'));
        }

        \Log::info('Filtro login_activity_value:', ['valor' => $request->input('login_activity_value')]);

        if ($request->filled('login_activity_value')) {
            $loginFilter = $request->input('login_activity_value');

            if ($loginFilter === 'top_10') {
                $topUsersData = DB::table('system_logs')
                    ->select('user_id', DB::raw('COUNT(*) as total_sessions'))
                    ->where('action', 'Inicio de sesión')
                    ->groupBy('user_id')
                    ->orderByDesc('total_sessions')
                    ->limit(10)
                    ->get();

                $topUserIds = $topUsersData->pluck('user_id')->toArray();
                $query->whereIn('id', $topUserIds)
                    ->orderByRaw("FIELD(id, " . implode(',', $topUserIds) . ")");
            } elseif ($loginFilter === 'none') {
                $usersWithLogins = DB::table('system_logs')
                    ->where('action', 'Inicio de sesión')
                    ->distinct()
                    ->pluck('user_id')
                    ->toArray();

                $query->whereNotIn('id', $usersWithLogins);
            }
        }


        $users = $query->paginate()->withQueryString();
        $roles = Role::all();
        $emailDomains = User::selectRaw('SUBSTRING_INDEX(email, "@", -1) as domain')->distinct()->pluck('domain');
        $cityList = User::select('address')->whereNotNull('address')->distinct()->pluck('address');

        return view('user.index', compact('users', 'roles', 'emailDomains', 'cityList'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    public function create(): View
    {
        $user = new User();
        return view('user.create', compact('user'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        
        $user = User::create($validated);
        $user->assignRole('user');
        $user->assignRole('donor');
        $user->profile()->create([
            'address' => $validated['address'],
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,

            'languages_spoken' => $request->input('languages_spoken'),
            'availability_days' => $request->input('availability_days'),
            'availability_hours' => $request->input('availability_hours'),
            'transport_available' => $request->input('transport_available') ?: null,
        ]);

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }


    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        return view('user.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    public function editRol($id): View
    {
        $user = User::find($id);
        $roles = Role::all(); // Obtener todos los roles disponibles
        return view('user.editRol', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validatedData = $request->validate([
            'roles' => 'sometimes|array',
            'roles.*' => 'exists:roles,id',
            'bio' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'skills' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:255',
            'availability_days' => 'nullable|string|max:255',
            'availability_hours' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'transport_available' => 'nullable|string',
            'experience_level' => 'nullable|string|max:255',
            'physical_condition' => 'nullable|string|max:255',
            'preferred_tasks' => 'nullable|string|max:255',
            'languages_spoken' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed', 
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        if (isset($validatedData['roles'])) {
            $user->roles()->sync($validatedData['roles']);
        }

        $profileData = collect($validatedData)->only([
            'bio', 'document_number', 'birthdate', 'skills', 'interests',
            'availability_days', 'availability_hours', 'location', 'transport_available',
            'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken',
            'latitude', 'longitude'
        ])->toArray();

        if (!empty($profileData)) {
            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $user->profile()->create($profileData);
            }
        }

        $userData = collect($validatedData)
            ->except([
                'roles', 'bio', 'document_number', 'birthdate', 'skills', 'interests',
                'availability_days', 'availability_hours', 'location', 'transport_available',
                'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken', 'password', 'password_confirmation'
            ])
            ->toArray();

        if (!empty($userData)) {
            $user->update($userData);
        }

        return Redirect::route('users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully');
    }
    
    public function trashed(Request $request): View
    {
        $query = User::onlyTrashed();
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }
        $users = $query->paginate();
        return view('user.trashed', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.trashed')->with('success', 'User restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('users.trashed')->with('success', 'User permanently deleted.');
    }


    public function search(Request $request)
{
    $query = User::query();

    if ($request->filled('q')) {
        $search = $request->input('q');

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    $users = $query->limit(10)->get();

    return response()->json($users);
}


public function generatePDF(Request $request)
{
    $query = User::withTrashed()->with('profile', 'roles');

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('phone', 'like', "%$search%")
              ->orWhere('address', 'like', "%$search%");
        });
    }

    if ($request->filled('role')) {
        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('id', $request->input('role'));
        });
    }

    if ($request->filled('email_domain')) {
        $query->where('email', 'like', '%' . $request->input('email_domain'));
    }

    if ($request->filled('start_date')) {
        $query->where('created_at', '>=', $request->input('start_date'));
    }

    if ($request->filled('end_date')) {
        $query->where('created_at', '<=', $request->input('end_date'));
    }

    if ($request->filled('city')) {
        $query->where('address', $request->input('city'));
    }

    $users = $query->get();

    $reportData = [
        'generated_by' => Auth::check() ? Auth::user()->name : 'Sistema',
        'generated_email' => Auth::check() ? Auth::user()->email : '-',
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i:s'),
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'total_users' => $users->count(),

        'filters' => [
            'search' => $request->input('search'),
            'role' => $request->input('role'),
            'email_domain' => $request->input('email_domain'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'city' => $request->input('city'),
        ]
    ];

    $pdf = Pdf::loadView('pdf.users', compact('users', 'reportData'))
        ->setPaper('a4', 'landscape');

    return $pdf->stream("listado_usuarios.pdf");
}

public function printFullInfo($id, Request $request)
{
    $user = User::withTrashed()
        ->with([
            'profile',
            'eventParticipants.event',
            'eventParticipants.eventLocation',
            'volunteerVerifications',
            'roles'
        ])
        ->findOrFail($id);

    $reportData = [
        'generated_by' => Auth::check() ? Auth::user()->name : 'Sistema',
        'generated_email' => Auth::check() ? Auth::user()->email : '-',
        'date' => now()->format('d/m/Y'),
        'time' => now()->format('H:i:s'),
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
    ];

    $pdf = Pdf::loadView('pdf.user_full', compact('user', 'reportData'))
        ->setPaper('a4', 'landscape');

    return $pdf->stream("usuario_{$user->id}.pdf");

}
}
