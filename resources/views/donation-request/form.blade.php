<!-- FORMULARIO CORREGIDO -->
<div class="row padding-1 p-1">
    <div class="col-md-12">
        <!-- Applicant User -->
        <div class="form-group mb-2 mb20">
            <label for="applicant_user__id" class="form-label">{{ __('Applicant User') }}</label>
            <select name="applicant_user__id" class="form-control @error('applicant_user__id') is-invalid @enderror" id="applicant_user__id">
                <option value="">{{ __('Select Applicant User') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('applicant_user__id', $donationRequest->applicant_user__id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('applicant_user__id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- User In Charge -->
        <div class="form-group mb-2 mb20">
            <label for="user_in_charge_id" class="form-label">{{ __('User In Charge') }}</label>
            <select name="user_in_charge_id" class="form-control @error('user_in_charge_id') is-invalid @enderror" id="user_in_charge_id">
                <option value="">{{ __('Select User In Charge') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_in_charge_id', $donationRequest->user_in_charge_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('user_in_charge_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Donation -->
        <div class="form-group mb-2 mb20">
            <label for="donation_id" class="form-label">{{ __('Donation') }}</label>
            <select name="donation_id" class="form-control @error('donation_id') is-invalid @enderror" id="donation_id">
                <option value="">{{ __('Select Donation') }}</option>
                @foreach ($donations as $donation)
                    <option value="{{ $donation->id }}" {{ old('donation_id', $donationRequest->donation_id) == $donation->id ? 'selected' : '' }}>
                        {{ $donation->notes }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('donation_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        <!-- Request Date -->
        <div class="form-group mb-2 mb20">
            <label for="request_date" class="form-label">{{ __('Request Date') }}</label>
            <input type="date" name="request_date" class="form-control @error('request_date') is-invalid @enderror"
                value="{{ old('request_date', isset($donationRequest->request_date) ? \Carbon\Carbon::parse($donationRequest->request_date)->format('Y-m-d') : '') }}"
                id="request_date">
            {!! $errors->first('request_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Notes -->
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $donationRequest->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- State -->
        <div class="form-group mb-2 mb20">
            <label for="state" class="form-label">{{ __('State') }}</label>
            <select name="state" class="form-control @error('state') is-invalid @enderror" id="state">
                <option value="">{{ __('Select State') }}</option>
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
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
