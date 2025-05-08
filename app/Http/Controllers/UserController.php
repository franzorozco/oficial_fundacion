<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

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


}
