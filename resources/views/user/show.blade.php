@extends('adminlte::page')

@push('css')
<style>
    .profile-card {
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: 2rem auto;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .profile-photo {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #007bff;
    }

    .profile-name {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 0;
    }

    .profile-section {
        margin-bottom: 2rem;
    }

    .profile-section h5 {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .profile-info {
        margin-bottom: 0.5rem;
    }

    .profile-info strong {
        width: 200px;
        display: inline-block;
        color: #495057;
    }

    .btn-back {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('title', $user->name ?? __('Show') . ' ' . __('User'))

@section('content_header')
    <h1>{{ __('Perfil de usuario') }} </h1>
@endsection

@section('content')
<div class="container">
    <div class="profile-card">
        {{-- Header con foto y nombre --}}
        <div class="profile-header">
            
        <img src="{{ $user && $user->profile && $user->profile->photo && file_exists(storage_path('app/public/' . $user->profile->photo)) 
    ? asset('storage/' . $user->profile->photo) 
    : asset('storage/users/user_default.jpg') }}"
alt="Profile Photo" class="profile-photo">



            <div>
                <h2 class="profile-name">{{ $user->name }}</h2>
                <div class="text-muted">{{ '@' . Str::slug($user->name) }}</div>
            </div>
        </div>

        {{-- Información básica de usuario --}}
        <div class="profile-section">
            <h5>{{ __('Información de Usuario') }}</h5>
            <div class="profile-info"><strong>{{ __('Name') }}:</strong> {{ $user->name }}</div>
            <div class="profile-info"><strong>{{ __('Email') }}:</strong> {{ $user->email }}</div>
            <div class="profile-info"><strong>{{ __('Phone') }}:</strong> {{ $user->phone ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Address') }}:</strong> {{ $user->address ?? 'N/A' }}</div>
        </div>

        {{-- Información del perfil --}}
        <div class="profile-section">
            <h5>{{ __('Información del Perfil') }}</h5>
            <div class="profile-info"><strong>{{ __('Bio') }}:</strong> {{ $user->profile->bio ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Document Number') }}:</strong> {{ $user->profile->document_number ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Birthdate') }}:</strong> {{ $user->profile->birthdate ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Skills') }}:</strong> {{ $user->profile->skills ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Interests') }}:</strong> {{ $user->profile->interests ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Availability Days') }}:</strong> {{ $user->profile->availability_days ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Availability Hours') }}:</strong> {{ $user->profile->availability_hours ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Location') }}:</strong> {{ $user->profile->location ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Transport Available') }}:</strong>
                {{ optional($user->profile)->transport_available ? 'Yes' : 'No' }}
            </div>
            <div class="profile-info"><strong>{{ __('Experience Level') }}:</strong> {{ $user->profile->experience_level ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Physical Condition') }}:</strong> {{ $user->profile->physical_condition ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Preferred Tasks') }}:</strong> {{ $user->profile->preferred_tasks ?? 'N/A' }}</div>
            <div class="profile-info"><strong>{{ __('Languages Spoken') }}:</strong> {{ $user->profile->languages_spoken ?? 'N/A' }}</div>
        </div>
    </div>
</div>
@endsection


