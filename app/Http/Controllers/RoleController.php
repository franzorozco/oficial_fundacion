<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Solo roles que NO estén soft-deleted
        $roles = Role::whereNull('deleted_at')
                     ->paginate();

        return view('role.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $role = new Role();
        $permissions = Permission::all();

        return view('role.create', compact('role', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = Role::create([
            'name'       => $validated['name'],
            'guard_name' => 'web',
        ]);

        $permissions = $request->input('permission', []);
        $role->permissions()->sync($permissions);

        return Redirect::route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        $permissions     = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Recoge los permisos del rol

        return view('role.edit', compact('role', 'permissions', 'rolePermissions'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();

        $role->update([
            'name' => $validated['name'],
        ]);

        $permissions = $request->input('permission', []);
        $role->permissions()->sync($permissions);

        return Redirect::route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
{
    // Verificar si el rol es el del usuario actual
    $user = auth()->user();
    if ($role->id === $user->roles->first()->id) {
        return Redirect::route('roles.index')
            ->with('error', 'No puedes eliminar tu propio rol.');
    }

    // Verificar si el rol es "donor" o "volunteer"
    if (in_array($role->name, ['donor', 'volunteer', 'admin'])) {
        return Redirect::route('roles.index')
            ->with('error', 'Este rol no puede ser eliminado.');
    }

    // Si pasa las verificaciones, realizar el soft-delete
    $role->delete();

    return Redirect::route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }

    public function trashed(Request $request): View
    {
        $roles = Role::onlyTrashed()->paginate();

        return view('role.trashed', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return Redirect::route('roles.trashed')->with('success', 'Rol restaurado exitosamente.');
        
    }

}
