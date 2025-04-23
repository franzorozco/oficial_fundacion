<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="external_donor_id" class="form-label">{{ __('External Donor Id') }}</label>
            <input type="text" name="external_donor_id" class="form-control @error('external_donor_id') is-invalid @enderror" value="{{ old('external_donor_id', $donation?->external_donor_id) }}" id="external_donor_id" placeholder="External Donor Id">
            {!! $errors->first('external_donor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $donation?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="received_by_id" class="form-label">{{ __('Received By Id') }}</label>
            <input type="text" name="received_by_id" class="form-control @error('received_by_id') is-invalid @enderror" value="{{ old('received_by_id', $donation?->received_by_id) }}" id="received_by_id" placeholder="Received By Id">
            {!! $errors->first('received_by_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status_id" class="form-label">{{ __('Status Id') }}</label>
            <input type="text" name="status_id" class="form-control @error('status_id') is-invalid @enderror" value="{{ old('status_id', $donation?->status_id) }}" id="status_id" placeholder="Status Id">
            {!! $errors->first('status_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="during_campaign_id" class="form-label">{{ __('During Campaign Id') }}</label>
            <input type="text" name="during_campaign_id" class="form-control @error('during_campaign_id') is-invalid @enderror" value="{{ old('during_campaign_id', $donation?->during_campaign_id) }}" id="during_campaign_id" placeholder="During Campaign Id">
            {!! $errors->first('during_campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="donation_date" class="form-label">{{ __('Donation Date') }}</label>
            <input type="text" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror" value="{{ old('donation_date', $donation?->donation_date) }}" id="donation_date" placeholder="Donation Date">
            {!! $errors->first('donation_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $donation?->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>