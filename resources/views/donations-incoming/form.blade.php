<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="external_donor_id" class="form-label">{{ __('External Donor') }}</label>
            <select name="external_donor_id" class="form-control @error('external_donor_id') is-invalid @enderror" id="external_donor_id">
                <option value="" disabled selected>{{ __('Select External Donor') }}</option>
                @foreach($externalDonors as $externalDonor)
                    <option value="{{ $externalDonor->id }}" {{ old('external_donor_id', $donation?->external_donor_id) == $externalDonor->id ? 'selected' : '' }}>
                        {{ $externalDonor->names }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('external_donor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User') }}</label>
            <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" id="user_id">
                <option value="" disabled selected>{{ __('Select User') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $donation?->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="received_by_id" class="form-label">{{ __('Received By') }}</label>
            <select name="received_by_id" class="form-control @error('received_by_id') is-invalid @enderror" id="received_by_id">
                <option value="" disabled selected>{{ __('Select Receiver') }}</option>
                @foreach($receivers as $receiver)
                    <option value="{{ $receiver->id }}" {{ old('received_by_id', $donation?->received_by_id) == $receiver->id ? 'selected' : '' }}>
                        {{ $receiver->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('received_by_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="status_id" class="form-label">{{ __('Status') }}</label>
            <select name="status_id" class="form-control @error('status_id') is-invalid @enderror" id="status_id">
                <option value="" disabled selected>{{ __('Select Status') }}</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ old('status_id', $donation?->status_id) == $status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('status_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="during_campaign_id" class="form-label">{{ __('Campaign') }}</label>
            <select name="during_campaign_id" class="form-control @error('during_campaign_id') is-invalid @enderror" id="during_campaign_id">
                <option value="" disabled selected>{{ __('Select Campaign') }}</option>
                @foreach($campaigns as $campaign)
                    <option value="{{ $campaign->id }}" {{ old('during_campaign_id', $donation?->during_campaign_id) == $campaign->id ? 'selected' : '' }}>
                        {{ $campaign->name }}
                    </option>
                @endforeach
            </select>
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
