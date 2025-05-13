<?php

namespace App\Http\Controllers;

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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::query(); // Iniciar la consulta de usuarios

        // Si hay un término de búsqueda
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            // Filtrar por nombre, correo, teléfono o dirección (puedes añadir más campos si es necesario)
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
        }

        // Paginar los resultados
        $users = $query->paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();

        return view('user.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        // Cifrar la contraseña antes de almacenarla
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']); // Cifrar la contraseña

        // Crear el usuario
        $user = User::create($validated);

        // Asignar los roles "user" y "donor"
        $user->assignRole('user');
        $user->assignRole('donor');

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Usuario no encontrado');
        }

        return view('user.show', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Validación general
        $request->validate([
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
            'password' => 'nullable|string|min:8|confirmed', // Nueva contraseña opcional
        ]);

        // Actualizar la contraseña si se proporciona
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password')); // Cifrar la nueva contraseña
        }

        // Solo sincronizar roles si vienen en el request
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        // Actualizar el perfil si existe
        if ($user->profile) {
            $user->profile->update($request->only([
                'bio', 'document_number', 'birthdate', 'skills', 'interests',
                'availability_days', 'availability_hours', 'location',
                'transport_available', 'experience_level', 'physical_condition',
                'preferred_tasks', 'languages_spoken'
            ]));
        }

        // Actualizar datos del usuario, excluyendo roles y campos del perfil
        $user->update($request->except([
            'roles', 'bio', 'document_number', 'birthdate', 'skills', 'interests',
            'availability_days', 'availability_hours', 'location', 'transport_available',
            'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken'
        ]));

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
        // Comienza la consulta con todos los usuarios (incluyendo los eliminados, si aplica)
        $query = User::withTrashed()->with('profile');
    
        // Verifica si se ha aplicado un filtro de búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
            });
        }
    
        // Ejecuta la consulta para obtener los usuarios filtrados o todos
        $users = $query->get();
    
        // Crear el PDF
        $pdf = new Fpdf();
        $pdf->SetTitle('Detalle de Usuarios');
        $pdf->SetFont('Arial', '', 10);
    
        // Iterar sobre los usuarios y agregar la información al PDF
        foreach ($users as $user) {
            $pdf->AddPage();
    
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Datos del Usuario', 0, 1, 'C');
            $pdf->Ln(2);
    
            $pdf->SetFont('Arial', '', 10);
    
            // Datos básicos del usuario
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
    
            // Datos del perfil si existe
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
    
        // Devolver el PDF generado para su descarga
        $pdf->Output('D', 'detalle_usuarios.pdf');
        exit;
    }
    


}