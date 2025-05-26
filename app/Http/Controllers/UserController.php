<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use FPDF;

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


        // Filtro por actividad de inicio de sesión
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
        
        // Crea usuario con los campos que tiene User
        $user = User::create($validated);
        $user->assignRole('user');
        $user->assignRole('donor');

        // Ahora crea o actualiza perfil con la info adicional
        $user->profile()->create([
            'address' => $validated['address'],
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            // Otros campos de profile que quieras guardar
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
        // Validar datos recibidos (puedes ajustar reglas según necesidad)
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
            'transport_available' => 'nullable|boolean',
            'experience_level' => 'nullable|string|max:255',
            'physical_condition' => 'nullable|string|max:255',
            'preferred_tasks' => 'nullable|string|max:255',
            'languages_spoken' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed', // confirmación con password_confirmation
            // Puedes añadir otras validaciones que necesites para otros campos de User
        ]);

        // Actualizar password solo si viene y está lleno
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Actualizar roles si vienen en el request
        if (isset($validatedData['roles'])) {
            $user->roles()->sync($validatedData['roles']);
        }

        // Actualizar datos del perfil si existe, o crear si no existe
        $profileData = collect($validatedData)->only([
            'bio', 'document_number', 'birthdate', 'skills', 'interests',
            'availability_days', 'availability_hours', 'location', 'transport_available',
            'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken'
        ])->toArray();

        if (!empty($profileData)) {
            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                // Crear perfil si no existe
                $user->profile()->create($profileData);
            }
        }

        // Actualizar datos del usuario (excluyendo campos del perfil, roles y password)
        // Puedes añadir aquí otros campos que permita actualizar directamente
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
        $query = User::onlyTrashed(); // Solo usuarios eliminados
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
        $pdf = new Fpdf();
        $pdf->SetTitle('Detalle de Usuarios');
        $pdf->SetFont('Arial', '', 10);
        foreach ($users as $user) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Datos del Usuario', 0, 1, 'C');
            $pdf->Ln(2);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 7, 'ID:', 0, 0); $pdf->Cell(0, 7, $user->id, 0, 1);
            $pdf->Cell(50, 7, 'Nombre:', 0, 0); $pdf->Cell(0, 7, $user->name, 0, 1);
            $pdf->Cell(50, 7, 'Correo:', 0, 0); $pdf->Cell(0, 7, $user->email, 0, 1);
            $pdf->Cell(50, 7, 'Telefono:', 0, 0); $pdf->Cell(0, 7, $user->phone ?? '-', 0, 1);
            $pdf->Cell(50, 7, 'Direccion:', 0, 0); $pdf->Cell(0, 7, $user->address ?? '-', 0, 1);
            $pdf->Cell(50, 7, 'Email verificado:', 0, 0); $pdf->Cell(0, 7, $user->email_verified_at ?? '-', 0, 1);
            $pdf->Cell(50, 7, 'Fecha de creación:', 0, 0); $pdf->Cell(0, 7, $user->created_at, 0, 1);
            $pdf->Cell(50, 7, 'Fecha de actualización:', 0, 0); $pdf->Cell(0, 7, $user->updated_at, 0, 1);
            $pdf->Cell(50, 7, 'Eliminado:', 0, 0); $pdf->Cell(0, 7, $user->deleted_at ?? 'No', 0, 1);
            $pdf->Ln(5);
            if ($user->profile) {
                $profile = $user->profile;
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Perfil del Usuario', 0, 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(50, 7, 'Bio:', 0, 0); $pdf->MultiCell(0, 7, $profile->bio ?? '-');
                $pdf->Cell(50, 7, 'Documento:', 0, 0); $pdf->Cell(0, 7, $profile->document_number ?? '-', 0, 1);
                $pdf->Cell(50, 7, 'Fecha de nacimiento:', 0, 0); $pdf->Cell(0, 7, $profile->birthdate ?? '-', 0, 1);
                $pdf->Cell(50, 7, 'Habilidades:', 0, 0); $pdf->MultiCell(0, 7, $profile->skills ?? '-');
                $pdf->Cell(50, 7, 'Intereses:', 0, 0); $pdf->MultiCell(0, 7, $profile->interests ?? '-');
                $pdf->Cell(50, 7, 'Disponibilidad (días):', 0, 0); $pdf->Cell(0, 7, $profile->availability_days ?? '-', 0, 1);
                $pdf->Cell(50, 7, 'Disponibilidad (horas):', 0, 0); $pdf->Cell(0, 7, $profile->availability_hours ?? '-', 0, 1);
                $pdf->Cell(50, 7, 'Ubicación:', 0, 0); $pdf->Cell(0, 7, $profile->location ?? '-', 0, 1);
                $pdf->Cell(50, 7, '¿Transporte disponible?:', 0, 0); $pdf->Cell(0, 7, $profile->transport_available ? 'Sí' : 'No', 0, 1);
                $pdf->Cell(50, 7, 'Nivel de experiencia:', 0, 0); $pdf->Cell(0, 7, ucfirst($profile->experience_level), 0, 1);
                $pdf->Cell(50, 7, 'Condición física:', 0, 0); $pdf->Cell(0, 7, ucfirst($profile->physical_condition), 0, 1);
                $pdf->Cell(50, 7, 'Tareas preferidas:', 0, 0); $pdf->MultiCell(0, 7, $profile->preferred_tasks ?? '-');
                $pdf->Cell(50, 7, 'Idiomas:', 0, 0); $pdf->Cell(0, 7, $profile->languages_spoken ?? '-', 0, 1);
            } else {
                $pdf->Cell(0, 10, 'Sin perfil asociado.', 0, 1);
            }
            $pdf->Ln(10);
        }
        $pdf->Output('D', 'detalle_usuarios.pdf');
        exit;
    }
}
