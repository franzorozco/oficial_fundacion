@extends('adminlte::page')

@section('title', __('Update') . ' User')

@section('content_header')
    <h1>{{ __('Actualizar') }} {{ __('Rol de usuario') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p class="h5">Nombre:</p>
            <p class="form-control">{{ $user->name }}</p>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                @foreach($roles as $role)
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            name="roles[]" 
                            value="{{ $role->id }}" 
                            class="form-check-input mr-1"
                            id="role_{{ $role->id }}"
                            {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }}
                        >
                        <label for="role_{{ $role->id }}" class="form-check-label">
                            {{ $role->name }}
                        </label>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
            </form>

        </div>
    </div> 
@endsection
