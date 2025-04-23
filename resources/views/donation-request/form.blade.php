<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="applicant_user__id" class="form-label">{{ __('Applicant User  Id') }}</label>
            <input type="text" name="applicant_user__id" class="form-control @error('applicant_user__id') is-invalid @enderror" value="{{ old('applicant_user__id', $donationRequest?->applicant_user__id) }}" id="applicant_user__id" placeholder="Applicant User  Id">
            {!! $errors->first('applicant_user__id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_in_charge_id" class="form-label">{{ __('User In Charge Id') }}</label>
            <input type="text" name="user_in_charge_id" class="form-control @error('user_in_charge_id') is-invalid @enderror" value="{{ old('user_in_charge_id', $donationRequest?->user_in_charge_id) }}" id="user_in_charge_id" placeholder="User In Charge Id">
            {!! $errors->first('user_in_charge_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="donation_id" class="form-label">{{ __('Donation Id') }}</label>
            <input type="text" name="donation_id" class="form-control @error('donation_id') is-invalid @enderror" value="{{ old('donation_id', $donationRequest?->donation_id) }}" id="donation_id" placeholder="Donation Id">
            {!! $errors->first('donation_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="request_date" class="form-label">{{ __('Request Date') }}</label>
            <input type="text" name="request_date" class="form-control @error('request_date') is-invalid @enderror" value="{{ old('request_date', $donationRequest?->request_date) }}" id="request_date" placeholder="Request Date">
            {!! $errors->first('request_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $donationRequest?->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="state" class="form-label">{{ __('State') }}</label>
            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $donationRequest?->state) }}" id="state" placeholder="State">
            {!! $errors->first('state', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>