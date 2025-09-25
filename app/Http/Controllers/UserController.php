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
            'transport_available' => 'nullable|boolean',
            'experience_level' => 'nullable|string|max:255',
            'physical_condition' => 'nullable|string|max:255',
            'preferred_tasks' => 'nullable|string|max:255',
            'languages_spoken' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed', 
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
            'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken'
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

    $pdf = new Fpdf('L', 'mm', 'A4'); // Hoja horizontal
    $pdf->SetTitle('Listado de Usuarios');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Cabecera de tabla
    $pdf->Cell(10, 10, 'N°', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Nombre', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Correo electrónico', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Celular', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Ciudad', 1, 0, 'C');
    $pdf->Cell(30, 10, 'N° Documento', 1, 0, 'C');
    $pdf->Cell(45, 10, 'Rol(es)', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Fecha registro', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $i = 1;
    foreach ($users as $user) {
        $pdf->Cell(10, 8, $i++, 1, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode($user->name), 1, 0);
        $pdf->Cell(60, 8, utf8_decode($user->email), 1, 0);
        $pdf->Cell(30, 8, $user->phone ?? '-', 1, 0);
        $pdf->Cell(35, 8, utf8_decode($user->address ?? 'N/A'), 1, 0);
        $pdf->Cell(30, 8, $user->profile->document_number ?? 'N/A', 1, 0);
        $pdf->Cell(45, 8, utf8_decode($user->roles->pluck('name')->implode(', ') ?: 'Sin rol'), 1, 0);
        $pdf->Cell(35, 8, $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A', 1, 1);
    }

    $pdf->Output('D', 'listado_usuarios.pdf');
    exit;
}



public function printFullInfo($id)
{
    $user = User::withTrashed()
        ->with(['profile', 'eventParticipants.event', 'eventParticipants.eventLocation', 'volunteerVerifications', 'roles'])
        ->findOrFail($id);

    $pdf = new Fpdf('L', 'mm', 'A4');
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, "Información Completa del Usuario: {$user->name}", 0, 1, 'C');
    $pdf->Ln(5);

    // Datos básicos usuario
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 7, 'ID:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, $user->id, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 7, 'Nombre:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, $user->name, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 7, 'Correo electrónico:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, $user->email, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 7, 'Teléfono:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, $user->phone ?? '-', 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 7, 'Dirección:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, $user->address ?? '-', 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 7, 'Correo verificado:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 7, $user->email_verified_at ? 'Sí' : 'No', 0, 1);

    $pdf->Ln(10);

    // Perfil
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Perfil", 0, 1);

    if ($user->profile) {
        $profile = $user->profile;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Número de documento:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->document_number ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Biografía:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 7, $profile->bio ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Fecha de nacimiento:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->birthdate ? $profile->birthdate->format('d/m/Y') : '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Habilidades:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 7, $profile->skills ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Intereses:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 7, $profile->interests ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Días disponibles:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->availability_days ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Horas disponibles:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->availability_hours ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Ubicación:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->location ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Transporte disponible:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->transport_available ? 'Sí' : 'No', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Nivel de experiencia:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, ucfirst($profile->experience_level), 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Condición física:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, ucfirst($profile->physical_condition), 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Tareas preferidas:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 7, $profile->preferred_tasks ?? '-', 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Idiomas:', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, $profile->languages_spoken ?? '-', 0, 1);

    } else {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, 'No hay perfil asociado.', 0, 1);
    }

    $pdf->Ln(10);

    // Eventos donde participa
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Eventos donde participa", 0, 1);

    if ($user->eventParticipants && $user->eventParticipants->count() > 0) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Evento', 1);
        $pdf->Cell(60, 7, 'Ubicación', 1);
        $pdf->Cell(40, 7, 'Estado', 1);
        $pdf->Cell(40, 7, 'Fecha registro', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($user->eventParticipants as $participant) {
            $pdf->Cell(60, 7, $participant->event->name ?? 'N/A', 1);
            $pdf->Cell(60, 7, $participant->eventLocation->name ?? 'N/A', 1);
            $pdf->Cell(40, 7, ucfirst($participant->status), 1);
            $pdf->Cell(40, 7, $participant->registration_date ? $participant->registration_date->format('d/m/Y') : '-', 1);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, 'No participa en eventos.', 0, 1);
    }

    $pdf->Ln(10);

    // Verificaciones voluntario
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Verificaciones de voluntario", 0, 1);

    if ($user->volunteerVerifications && $user->volunteerVerifications->count() > 0) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 7, 'Documento', 1);
        $pdf->Cell(80, 7, 'Nombre documento', 1);
        $pdf->Cell(40, 7, 'Estado', 1);
        $pdf->Cell(50, 7, 'Comentario', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($user->volunteerVerifications as $verif) {
            $pdf->Cell(60, 7, $verif->document_type ?? '-', 1);
            $pdf->Cell(80, 7, $verif->name_document ?? '-', 1);
            $pdf->Cell(40, 7, ucfirst($verif->status), 1);
            $pdf->Cell(50, 7, $verif->coment ?? '-', 1);
            $pdf->Ln();
        }
    } else {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 7, 'No hay verificaciones.', 0, 1);
    }

    $pdf->Output('I', "usuario_{$user->id}_completo.pdf");
    exit;
}




}
