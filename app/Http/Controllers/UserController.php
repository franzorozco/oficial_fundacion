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
     * Display a listing of the resource.z
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
        User::create($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

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
        // Validar que los roles sean un array
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id', // Validar que cada rol exista
        ]);

        // Sincronizamos los roles con los roles seleccionados
        $user->roles()->sync($request->roles);

        // Actualizamos el resto de los campos del usuario, excluyendo los roles
        $user->update($request->except('roles'));

        return Redirect::route('users.index')
            ->with('success', 'User roles updated successfully!');
    }


    
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully');
    }
}