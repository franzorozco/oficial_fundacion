@extends('adminlte::page')

@section('title', __('Create Donation Request'))

@section('content_header')
    <h1>{{ __('Create Donation Request') }}</h1>
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create Donation Request') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('donation-requests.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            <div class="row p-3">
                                <div class="col-md-12">

                                    <!-- Usuario Solicitante -->
                                    <div class="form-group mb-3">
                                        <label for="applicant_user__id" class="form-label">Usuario Solicitante</label>
                                        <select name="applicant_user__id" id="applicant_user__id"
                                            class="form-control @error('applicant_user__id') is-invalid @enderror">
                                            <option value="">Seleccione un usuario solicitante</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('applicant_user__id', $donationRequest->applicant_user__id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('applicant_user__id')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Usuario Encargado -->
                                    <div class="form-group mb-3">
                                        <label for="user_in_charge_id" class="form-label">Usuario Encargado</label>
                                        <select name="user_in_charge_id" id="user_in_charge_id"
                                            class="form-control @error('user_in_charge_id') is-invalid @enderror">
                                            <option value="">Seleccione un usuario encargado</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_in_charge_id', $donationRequest->user_in_charge_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_in_charge_id')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Donación -->
                                    <div class="form-group mb-3">
                                        <label for="donation_id" class="form-label">Donación</label>
                                        <select name="donation_id" id="donation_id"
                                            class="form-control @error('donation_id') is-invalid @enderror">
                                            <option value="">Seleccione una donación</option>
                                            @foreach ($donations as $donation)
                                                <option value="{{ $donation->id }}"
                                                    {{ old('donation_id', $donationRequest->donation_id) == $donation->id ? 'selected' : '' }}>
                                                    {{ $donation->referencia }} - {{ $donation->name_donation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('donation_id')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Fecha de Solicitud -->
                                    <div class="form-group mb-3">
                                        <label for="request_date" class="form-label">Fecha de Solicitud</label>
                                        <input type="date" name="request_date" id="request_date"
                                            class="form-control @error('request_date') is-invalid @enderror"
                                            value="{{ old('request_date', isset($donationRequest->request_date) ? \Carbon\Carbon::parse($donationRequest->request_date)->format('Y-m-d') : '') }}">
                                        @error('request_date')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Notas -->
                                    <div class="form-group mb-3">
                                        <label for="notes" class="form-label">Notas</label>
                                        <input type="text" name="notes" id="notes" placeholder="Notas"
                                            class="form-control @error('notes') is-invalid @enderror"
                                            value="{{ old('notes', $donationRequest->notes) }}">
                                        @error('notes')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                    <!-- Estado -->
                                    <div class="form-group mb-3">
                                        <label for="state" class="form-label">Estado</label>
                                        <select name="state" id="state"
                                            class="form-control @error('state') is-invalid @enderror">
                                            <option value="">Seleccione un estado</option>
                                            @foreach (['pendiente', 'en revision', 'aceptado', 'rechazado', 'finalizado'] as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('state', $donationRequest->state) == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
