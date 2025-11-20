@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop
@section('css')
@section('css')
<style>
    .chart-box {
        height: 230px;
        padding: 5px;
    }

    .chart-box canvas {
        max-height: 180px !important;
    }

    .card-title {
        font-size: 16px;
        margin-bottom: 2px;
        font-weight: 700;
    }

    .card-text {
        font-size: 11px;
    }
</style>
@stop

@stop


@section('content')

<div class="row">

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Usuarios Registrados por Mes',
            'desc' => 'Cantidad de usuarios nuevos creados cada mes.',
            'id' => 'usersByMonthChart'
        ])
    </div>

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Donaciones por Mes',
            'desc' => 'Número total de donaciones recibidas por mes.',
            'id' => 'donationsByMonthChart'
        ])
    </div>

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Donaciones por Estado',
            'desc' => 'Distribución según estado (pendiente, recibido, etc).',
            'id' => 'donationsByStatusChart'
        ])
    </div>

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Ítems Donados por Tipo',
            'desc' => 'Cantidad de ítems entregados según su tipo.',
            'id' => 'donationItemsByTypeChart'
        ])
    </div>

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Solicitudes por Estado',
            'desc' => 'Estados actuales de solicitudes de donación.',
            'id' => 'requestsByStateChart'
        ])
    </div>

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Participantes por Evento',
            'desc' => 'Personas participando en cada evento.',
            'id' => 'participantsByEventChart'
        ])
    </div>

    <div class="col-md-4 mb-4">
        @include('charts.card', [
            'title' => 'Tareas por Estado',
            'desc' => 'Distribución de tareas según su estado actual.',
            'id' => 'tasksByStateChart'
        ])
    </div>

</div>



@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function createChart(id, type, labels, data, label) {
    new Chart(document.getElementById(id), {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data
            }]
        }
    });
}

createChart('usersByMonthChart', 'line',
    Object.keys(@json($usersByMonth)),
    Object.values(@json($usersByMonth)),
    'Usuarios Registrados por Mes'
);

createChart('donationsByMonthChart', 'bar',
    Object.keys(@json($donationsByMonth)),
    Object.values(@json($donationsByMonth)),
    'Donaciones por Mes'
);

createChart('donationsByStatusChart', 'pie',
    Object.keys(@json($donationsByStatus)),
    Object.values(@json($donationsByStatus)),
    'Donaciones por Estado'
);

createChart('donationItemsByTypeChart', 'doughnut',
    Object.keys(@json($donationItemsByType)),
    Object.values(@json($donationItemsByType)),
    'Ítems por Tipo'
);

createChart('requestsByStateChart', 'pie',
    Object.keys(@json($requestsByState)),
    Object.values(@json($requestsByState)),
    'Solicitudes por Estado'
);

createChart('participantsByEventChart', 'bar',
    Object.keys(@json($participantsByEvent)),
    Object.values(@json($participantsByEvent)),
    'Participantes por Evento'
);

createChart('tasksByStateChart', 'pie',
    Object.keys(@json($tasksByState)),
    Object.values(@json($tasksByState)),
    'Tareas por Estado'
);
</script>
@stop
