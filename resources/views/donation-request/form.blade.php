<!-- FORMULARIO CORREGIDO -->
<div class="row padding-1 p-1">
    <div class="col-md-12">

        <!-- Referencia -->
        @if($donationRequest->referencia)
        <div class="form-group mb-2 mb20">
            <label for="referencia" class="form-label">Referencia</label>
            <input type="text" class="form-control" id="referencia" value="{{ $donationRequest->referencia }}" readonly>
        </div>
        @endif

        <!-- Usuario Solicitante -->
        <div class="form-group mb-2 mb20">
            <label for="applicant_user__id" class="form-label">Usuario Solicitante</label>
            <select name="applicant_user__id" class="form-control @error('applicant_user__id') is-invalid @enderror" id="applicant_user__id">
                <option value="">Seleccione un usuario solicitante</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('applicant_user__id', $donationRequest->applicant_user__id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('applicant_user__id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Usuario Encargado -->
        <div class="form-group mb-2 mb20">
            <label for="user_in_charge_id" class="form-label">Usuario Encargado</label>
            <select name="user_in_charge_id" class="form-control @error('user_in_charge_id') is-invalid @enderror" id="user_in_charge_id">
                <option value="">Seleccione un usuario encargado</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_in_charge_id', $donationRequest->user_in_charge_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('user_in_charge_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Donación -->
        <div class="form-group mb-2 mb20">
            <label for="donation_id" class="form-label">Donación</label>
            <select name="donation_id" class="form-control @error('donation_id') is-invalid @enderror" id="donation_id">
                <option value="">Seleccione una donación</option>
                @foreach ($donations as $donation)
                    <option value="{{ $donation->id }}" {{ old('donation_id', $donationRequest->donation_id) == $donation->id ? 'selected' : '' }}>
                        {{ $donation->referencia }} - {{ $donation->name_donation }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('donation_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Fecha de Solicitud -->
        <div class="form-group mb-2 mb20">
            <label for="request_date" class="form-label">Fecha de Solicitud</label>
            <input type="date" name="request_date" class="form-control @error('request_date') is-invalid @enderror"
                value="{{ old('request_date', isset($donationRequest->request_date) ? \Carbon\Carbon::parse($donationRequest->request_date)->format('Y-m-d') : '') }}"
                id="request_date">
            {!! $errors->first('request_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Notas -->
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">Notas</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $donationRequest->notes) }}" id="notes" placeholder="Notas">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Estado -->
        <div class="form-group mb-2 mb20">
            <label for="state" class="form-label">Estado</label>
            <select name="state" class="form-control @error('state') is-invalid @enderror" id="state">
                <option value="">Seleccione un estado</option>
                @foreach (['pendiente', 'en revision', 'aceptado', 'rechazado', 'finalizado'] as $status)
                    <option value="{{ $status }}" {{ old('state', $donationRequest->state) == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('state', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</div>
