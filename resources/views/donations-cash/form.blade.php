<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="donor_id" class="form-label">{{ __('Donor Id') }}</label>
            <input type="text" name="donor_id" class="form-control @error('donor_id') is-invalid @enderror" value="{{ old('donor_id', $donationsCash?->donor_id) }}" id="donor_id" placeholder="Donor Id">
            {!! $errors->first('donor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="external_donor_id" class="form-label">{{ __('External Donor Id') }}</label>
            <input type="text" name="external_donor_id" class="form-control @error('external_donor_id') is-invalid @enderror" value="{{ old('external_donor_id', $donationsCash?->external_donor_id) }}" id="external_donor_id" placeholder="External Donor Id">
            {!! $errors->first('external_donor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $donationsCash?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="method" class="form-label">{{ __('Method') }}</label>
            <input type="text" name="method" class="form-control @error('method') is-invalid @enderror" value="{{ old('method', $donationsCash?->method) }}" id="method" placeholder="Method">
            {!! $errors->first('method', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="donation_date" class="form-label">{{ __('Donation Date') }}</label>
            <input type="text" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror" value="{{ old('donation_date', $donationsCash?->donation_date) }}" id="donation_date" placeholder="Donation Date">
            {!! $errors->first('donation_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="campaign_id" class="form-label">{{ __('Campaign Id') }}</label>
            <input type="text" name="campaign_id" class="form-control @error('campaign_id') is-invalid @enderror" value="{{ old('campaign_id', $donationsCash?->campaign_id) }}" id="campaign_id" placeholder="Campaign Id">
            {!! $errors->first('campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>