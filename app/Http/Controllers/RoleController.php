<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function index(Request $request): View
    {
        $roles = Role::whereNull('deleted_at')->paginate();

        return view('role.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    public function create(): View
    {
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($perm) {
            return explode('.', $perm->name)[0]; // users.ver → users
        });
        $role = new Role();
        $rolePermissions = [];

        return view('role.create', compact('role', 'groupedPermissions', 'rolePermissions'));
    }




    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permission' => 'required|array'
        ]);

        $role = SpatieRole::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        if (strtolower($request->name) === 'administrador') {
            return back()->with('error', 'Ya existe un rol protegido con ese nombre.');
        }

        if ($role && $role->id) {
            $permissionNames = Permission::whereIn('id', $request->permission)->pluck('name');
            $role->syncPermissions($permissionNames);
        } else {
            return back()->with('error', 'No se pudo crear el rol correctamente.');
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado con éxito.');
    }

    public function show(Role $role): View
    {
        return view('role.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($perm) {
            return explode('.', $perm->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('role.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->name === 'Administrador' && $request->name !== 'Administrador') {
            return Redirect::route('roles.index')
                ->with('error', 'No puedes cambiar el nombre del rol Administrador.');
        }

        $permissions = $request->input('permission', []);
        $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();

        if ($role->name === 'Administrador') {
            // Asegurar que estos permisos siempre estén presentes
            $required = ['roles.verlista', 'roles.editar'];
            foreach ($required as $requiredPermission) {
                if (!in_array($requiredPermission, $permissionNames)) {
                    return Redirect::route('roles.index')
                        ->with('error', 'No puedes quitar permisos críticos del rol Administrador.');
                }
            }
        }

        $role->update(['name' => $request->name]);
        $role->syncPermissions($permissionNames);

        return Redirect::route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'Administrador') {
            return Redirect::route('roles.index')
                ->with('error', 'El rol Administrador no puede ser eliminado.');
        }

        $user = auth()->user();
        if ($role->id === $user->roles->first()->id) {
            return Redirect::route('roles.index')
                ->with('error', 'No puedes eliminar tu propio rol.');
        }

        $role->delete();

        return Redirect::route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }

    public function trashed(): View
    {
        $roles = Role::onlyTrashed()->paginate(10);

        return view('role.trashed', compact('roles'));
    }

    public function restore($id): RedirectResponse
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return Redirect::route('roles.trashed')
            ->with('success', 'Rol restaurado exitosamente.');
    }
}
