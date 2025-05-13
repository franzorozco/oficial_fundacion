<?php

namespace App\Http\Controllers;

use App\Models\Role; // Modelo local con SoftDeletes
use Spatie\Permission\Models\Role as SpatieRole; // Modelo de Spatie solo para crear roles
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
        $roles = Role::whereNull('deleted_at')->paginate();

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
        $rolePermissions = [];

        return view('role.create', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permission' => 'required|array'
        ]);

        // Crear usando modelo de Spatie
        $role = SpatieRole::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($role && $role->id) {
            // Convertir IDs a nombres antes de sincronizar
            $permissionNames = Permission::whereIn('id', $request->permission)->pluck('name');
            $role->syncPermissions($permissionNames);
        } else {
            return back()->with('error', 'No se pudo crear el rol correctamente.');
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado con éxito.');
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
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

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

        // Convertir IDs a nombres antes de sincronizar
        $permissions = $request->input('permission', []);
        $permissionNames = Permission::whereIn('id', $permissions)->pluck('name');
        $role->syncPermissions($permissionNames);

        return Redirect::route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Role $role): RedirectResponse
    {
        $user = auth()->user();

        if ($role->id === $user->roles->first()->id) {
            return Redirect::route('roles.index')
                ->with('error', 'No puedes eliminar tu propio rol.');
        }

        if (in_array($role->name, ['donor', 'volunteer', 'admin'])) {
            return Redirect::route('roles.index')
                ->with('error', 'Este rol no puede ser eliminado.');
        }

        $role->delete();

        return Redirect::route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }

    /**
     * Show a list of trashed (soft deleted) roles.
     */
    public function trashed(): View
    {
        $roles = Role::onlyTrashed()->paginate(10);

        return view('role.trashed', compact('roles'));
    }

    /**
     * Restore a trashed role.
     */
    public function restore($id): RedirectResponse
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return Redirect::route('roles.trashed')
            ->with('success', 'Rol restaurado exitosamente.');
    }
}
