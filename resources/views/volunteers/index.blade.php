@extends('adminlte::page')
@vite(['resources/css/components/volunteers.css'])
@vite(['resources/css/crud.css'])
@section('title', 'Voluntarios')
@section('content_header')
    <h1 class="mb-0">Voluntarios</h1>
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

                {{-- BUSCADOR --}}
                <form method="GET" action="{{ route('volunteers.index') }}">

                    <div class="row g-2 align-items-center">

                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar voluntarios..."
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
                    <div id="extraFilters" class="filters-box mt-3 p-3 d-none">

                        <div class="row g-3 align-items-end">

                            {{-- FECHAS --}}
<div class="col-md-6">
    <label class="form-label">Rango de fechas</label>

    <div class="d-flex gap-2">
        <input type="datetime-local"
            name="start_date"
            class="form-control"
            value="{{ request('start_date') }}">

        <input type="datetime-local"
            name="end_date"
            class="form-control"
            value="{{ request('end_date') }}">
    </div>
</div>

                            {{-- CIUDAD --}}
                            <div class="col-md-3">
                                <h6 class="form-label">Ciudad</h6>
                                <select name="city" class="form-select form-select-sm">
                                    <option value="">Todas</option>
                                    @foreach ($cityList as $city)
                                        <option value="{{ $city }}"
                                            {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- TRANSPORTE --}}
                            <div class="col-md-3">
                                <h6 class="text-muted">Transporte</h6>
                                <select name="transport_available" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('transport_available') == '1' ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ request('transport_available') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- HABILIDADES (MEJORADO 🔥) --}}
                            <div class="col-12">
                                <label class="form-label">Habilidades</label>

                                <div class="scroll-box">
                                    <div class="d-flex flex-wrap gap-2">

                                        @foreach ($skillsList as $skill)
                                            <input type="checkbox"
                                                class="btn-check"
                                                name="skills[]"
                                                value="{{ $skill }}"
                                                id="skill_{{ $loop->index }}"
                                                autocomplete="off"
                                                {{ in_array($skill, (array) request('skills')) ? 'checked' : '' }}>

                                            <label class="btn btn-outline-primary btn-sm px-3"
                                                for="skill_{{ $loop->index }}">
                                                {{ $skill }}
                                            </label>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            {{-- DÍAS DISPONIBLES (MEJORADO 🔥) --}}
                            <div class="col-md-6">
                                <label class="form-label">Días disponibles</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $day)
                                        <input type="checkbox"
                                            class="btn-check"
                                            name="availability_days[]"
                                            value="{{ $day }}"
                                            id="day_{{ $day }}"
                                            autocomplete="off"
                                            {{ in_array($day, (array) request('availability_days')) ? 'checked' : '' }}>

                                        <label class="btn btn-outline-dark btn-sm px-3"
                                            for="day_{{ $day }}">
                                            {{ $day }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- HORAS DISPONIBLES (SCROLL 🔥) --}}
                            <div class="col-md-6">
                                <label class="form-label">Horas disponibles</label>

                                <div class="scroll-box">
                                    <div class="d-flex flex-wrap gap-2">

                                        @foreach ($availabilityHoursList as $hour)

                                            <input type="checkbox"
                                                class="btn-check"
                                                name="availability_hours[]"
                                                value="{{ $hour }}"
                                                id="hour_{{ $loop->index }}"
                                                autocomplete="off"
                                                {{ in_array($hour, (array) request('availability_hours')) ? 'checked' : '' }}>

                                            <label class="btn btn-outline-secondary btn-sm px-3"
                                                for="hour_{{ $loop->index }}">
                                                {{ $hour }}
                                            </label>

                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            {{-- IDIOMAS (MEJORADO 🔥) --}}
                            <div class="col-12">
                                <label class="form-label">Idiomas</label>

                                <div class="scroll-box">
                                    <div class="d-flex flex-wrap gap-2">

                                        @foreach ($languagesList as $lang)

                                            <input type="checkbox"
                                                class="btn-check"
                                                name="languages_spoken[]"
                                                value="{{ $lang }}"
                                                id="lang_{{ $loop->index }}"
                                                autocomplete="off"
                                                {{ in_array($lang, (array) request('languages_spoken')) ? 'checked' : '' }}>

                                            <label class="btn btn-outline-success btn-sm px-3"
                                                for="lang_{{ $loop->index }}">
                                                {{ $lang }}
                                            </label>

                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                {{-- ACCIONES --}}
                <div class="d-flex justify-content-end flex-wrap gap-2">

                    @can('volunteers.crear')
                        <a href="{{ route('volunteers.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Nuevo
                        </a>
                    @endcan

                    @can('volunteers.verEliminados')
                        <a href="{{ route('volunteers.trashed') }}"
                            class="btn btn-outline-danger">
                            Eliminados
                        </a>
                    @endcan

                    @can('volunteers.regenerarPDF')
                        <a href="{{ route('volunteers.pdf', request()->all()) }}"
                            class="btn btn-outline-success">
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
                            <th>Registro</th>
                            <th>Tareas</th>
                            <th>Distribuciones</th>
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
                                <td>{{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                <td><span class="badge bg-primary">{{ $user->tareas_realizadas ?? 0 }}</span></td>
                                <td><span class="badge bg-success">{{ $user->distribuciones_realizadas ?? 0 }}</span></td>

<td class="text-center">
    <div class="d-flex justify-content-center gap-1">

                                        @can('volunteers.ver')
                                            <a class="btn btn-outline-primary btn-sm"
                                                href="{{ route('volunteers.show', $user->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('volunteers.editar')
                                            <a class="btn btn-outline-success btn-sm"
                                                href="{{ route('volunteers.edit', $user->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('volunteers.eliminar')
                                            <form method="POST"
                                                action="{{ route('volunteers.destroy', $user->id) }}">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('¿Eliminar voluntario?')">
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
    const btn = document.getElementById('toggleFiltersBtn');
    const panel = document.getElementById('extraFilters');

    btn.addEventListener('click', () => {
        panel.classList.toggle('d-none');

        btn.innerHTML = panel.classList.contains('d-none')
            ? 'Más filtros <i class="fa fa-chevron-down"></i>'
            : 'Menos filtros <i class="fa fa-chevron-up"></i>';
    });
});
</script>
@endsection 