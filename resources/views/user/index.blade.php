@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Usuarios</h1>
    </div>
@stop

@section('content')

    {{-- ALERTA --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
        </div>
    @endif

    <div class="card shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white">

            <div class="d-flex flex-column gap-3">

                {{-- FILTROS PRINCIPALES --}}
                <form method="GET" action="{{ route('users.index') }}" id="filterForm">

                    <div class="row g-2 align-items-center">

                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar usuarios..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-auto">
                            <button class="btn btn-secondary">
                                <i class="fa fa-search"></i> Filtrar
                            </button>
                        </div>

                        <div class="col-auto">
                            <button type="button" id="toggleFiltersBtn"
                                class="btn btn-outline-primary">
                                Más filtros <i class="fa fa-chevron-down"></i>
                            </button>
                        </div>

                    </div>

                    {{-- FILTROS AVANZADOS --}}
                    <div id="extraFilters" class="card mt-3 p-3 bg-light d-none">

                        <div class="row g-3">

    {{-- GRUPO: FILTROS PRINCIPALES --}}
    <div class="col-12">
        <div class="p-3 border rounded bg-white shadow-sm">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Rol</label>
                    <select name="role" class="form-select">
                        <option value="">Todos</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ request('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Dominio email</label>
                    <select name="email_domain" class="form-select">
                        <option value="">Todos</option>
                        @foreach ($emailDomains as $domain)
                            <option value="{{ $domain }}"
                                {{ request('email_domain') == $domain ? 'selected' : '' }}>
                                {{ $domain }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ciudad</label>
                    <select name="city" class="form-select">
                        <option value="">Todas</option>
                        @foreach ($cityList as $city)
                            <option value="{{ $city }}"
                                {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- GRUPO: FECHAS --}}
    <div class="col-12">
        <div class="p-3 border rounded bg-white shadow-sm">
            <label class="form-label fw-bold">Rango de fechas</label>

            <div class="row g-2">
                <div class="col-md-3">
                    <input type="datetime-local" name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-md-3">
                    <input type="datetime-local" name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}">
                </div>
            </div>
        </div>
    </div>

    {{-- GRUPO: ACTIVIDAD --}}
    <div class="col-12">
        <div class="p-3 border rounded bg-white shadow-sm">

            <label class="form-label fw-bold">Actividad de login</label>

            <input type="text"
                class="form-control mb-3"
                id="login_activity"
                readonly
                placeholder="Selecciona una opción">

            <div class="d-flex flex-wrap gap-2">

                @php
                    $loginOptions = [
                        'top_10' => 'Top 10 activos',
                        'none' => 'Nunca iniciaron sesión',
                    ];
                @endphp

                @foreach ($loginOptions as $key => $label)
                    <button type="button"
                        class="btn btn-outline-dark btn-sm login-btn px-3"
                        data-value="{{ $key }}">
                        {{ $label }}
                    </button>
                @endforeach

            </div>

            <input type="hidden" name="login_activity_value"
                id="login_activity_value"
                value="{{ request('login_activity_value') }}">

        </div>
    </div>

</div>

                    </div>
                </form>

                {{-- ACCIONES --}}
                <div class="d-flex flex-wrap gap-2 justify-content-end">

                    @can('users.crear')
                        <a href="{{ route('users.create') }}"
                            class="btn btn-primary">
                            <i class="fa fa-plus"></i> Nuevo
                        </a>
                    @endcan

                    @can('users.verEliminados')
                        <a href="{{ route('users.trashed') }}"
                            class="btn btn-outline-danger">
                            Eliminados
                        </a>
                    @endcan

                    @can('users.regenerarPDF')
                        <a href="{{ route('users.pdf', request()->only([
                            'search','role','email_domain','start_date','end_date','city','login_activity_value'
                        ])) }}"
                            class="btn btn-outline-success"
                            target="_blank">
                            PDF
                        </a>
                    @endcan

                </div>

            </div>

        </div>

        {{-- TABLA --}}
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Ciudad</th>
                            <th>Documento</th>
                            <th>Rol</th>
                            <th>Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address ?? 'N/A' }}</td>
                                <td>{{ $user->profile->document_number ?? 'N/A' }}</td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $user->roles->pluck('name')->implode(', ') ?: 'Sin rol' }}
                                    </span>
                                </td>

                                <td>{{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        @can('users.ver')
                                            <a class="btn btn-outline-primary btn-sm"
                                                href="{{ route('users.show', $user->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('users.editar')
                                            <a class="btn btn-outline-success btn-sm"
                                                href="{{ route('users.edit', $user->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('users.editarRol')
                                            <a class="btn btn-outline-warning btn-sm"
                                                href="{{ route('users.editRol', $user->id) }}">
                                                <i class="fa fa-user-tag"></i>
                                            </a>
                                        @endcan

                                        @can('users.imprimir')
                                            <a class="btn btn-outline-info btn-sm"
                                                href="{{ route('users.printFullInfo', $user->id) }}"
                                                target="_blank">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        @endcan

                                        @can('users.eliminar')
                                            <form method="POST"
                                                action="{{ route('users.destroy', $user->id) }}">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('¿Eliminar usuario?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

    </div>

    <div class="mt-3">
        {!! $users->withQueryString()->links() !!}
    </div>

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const toggleBtn = document.getElementById('toggleFiltersBtn');
    const extraFilters = document.getElementById('extraFilters');

    toggleBtn.addEventListener('click', () => {
        extraFilters.classList.toggle('d-none');
        toggleBtn.innerHTML = extraFilters.classList.contains('d-none')
            ? 'Más filtros <i class="fa fa-chevron-down"></i>'
            : 'Menos filtros <i class="fa fa-chevron-up"></i>';
    });

    const buttons = document.querySelectorAll('.login-btn');
    const input = document.getElementById('login_activity');
    const hidden = document.getElementById('login_activity_value');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            input.value = btn.textContent;
            hidden.value = btn.dataset.value;
        });
    });

});
</script>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginButtons = document.querySelectorAll('.login-btn');
        const loginInput = document.getElementById('login_activity');
        const hiddenInput = document.getElementById('login_activity_value');

        loginButtons.forEach(button => {
            button.addEventListener('click', function () {
                const value = this.getAttribute('data-value');
                const label = this.textContent;

                hiddenInput.value = value;
                loginInput.value = label;
            });
        });

        const toggleBtn = document.getElementById('toggleFiltersBtn');
        const extraFilters = document.getElementById('extraFilters');

        toggleBtn.addEventListener('click', () => {
            if (extraFilters.style.display === 'none' || extraFilters.style.display === '') {
                extraFilters.style.display = 'flex';
                toggleBtn.innerHTML = 'Menos filtros &#x25B2;';
            } else {
                extraFilters.style.display = 'none';
                toggleBtn.innerHTML = 'Más filtros &#x25BC;';
            }
        });
    });
</script>
@endsection
