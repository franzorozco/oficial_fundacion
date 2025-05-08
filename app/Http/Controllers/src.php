<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
        }

        $users = $query->paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    public function create(): View
    {
        $user = new User();
        $profile = new Profile(); // perfil vacío para el formulario

        return view('user.create', compact('user', 'profile'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make('password'); // o pedir la contraseña en el form

        $user = User::create($data);

        // Crear un perfil vacío asociado al usuario
        $profileData = $request->only([
            'bio', 'document_number', 'birthdate', 'skills', 'interests',
            'availability_days', 'availability_hours', 'location', 'transport_available',
            'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken'
        ]);

        $profileData['user_id'] = $user->id;

        Profile::create($profileData);

        return Redirect::route('users.index')
            ->with('success', 'User and profile created successfully.');
    }

    public function show($id): View
    {
        $user = User::findOrFail($id);
        $profile = $user->profile; // Relación uno a uno

        return view('user.show', compact('user', 'profile'));
    }
  
    
    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $profile = $user->profile ?? new Profile(); // Si no tiene perfil, creamos uno vacío en el formulario

        return view('user.edit', compact('user', 'profile'));
    }

    
    public function update(Request $request, User $user): RedirectResponse
    {
        // Actualizar los datos del usuario
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($data);

        // Actualizar o crear el perfil asociado
        $profileData = $request->only([
            'bio', 'document_number', 'birthdate', 'skills', 'interests',
            'availability_days', 'availability_hours', 'location', 'transport_available',
            'experience_level', 'physical_condition', 'preferred_tasks', 'languages_spoken'
        ]);

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            Profile::create($profileData);
        }

        return Redirect::route('users.index')
            ->with('success', 'User and profile updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        User::findOrFail($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
