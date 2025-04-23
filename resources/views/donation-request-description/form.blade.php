<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="donation_request_id" class="form-label">{{ __('Donation Request Id') }}</label>
            <input type="text" name="donation_request_id" class="form-control @error('donation_request_id') is-invalid @enderror" value="{{ old('donation_request_id', $donationRequestDescription?->donation_request_id) }}" id="donation_request_id" placeholder="Donation Request Id">
            {!! $errors->first('donation_request_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="recipient_name" class="form-label">{{ __('Recipient Name') }}</label>
            <input type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror" value="{{ old('recipient_name', $donationRequestDescription?->recipient_name) }}" id="recipient_name" placeholder="Recipient Name">
            {!! $errors->first('recipient_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="recipient_address" class="form-label">{{ __('Recipient Address') }}</label>
            <input type="text" name="recipient_address" class="form-control @error('recipient_address') is-invalid @enderror" value="{{ old('recipient_address', $donationRequestDescription?->recipient_address) }}" id="recipient_address" placeholder="Recipient Address">
            {!! $errors->first('recipient_address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="recipient_contact" class="form-label">{{ __('Recipient Contact') }}</label>
            <input type="text" name="recipient_contact" class="form-control @error('recipient_contact') is-invalid @enderror" value="{{ old('recipient_contact', $donationRequestDescription?->recipient_contact) }}" id="recipient_contact" placeholder="Recipient Contact">
            {!! $errors->first('recipient_contact', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="recipient_type" class="form-label">{{ __('Recipient Type') }}</label>
            <input type="text" name="recipient_type" class="form-control @error('recipient_type') is-invalid @enderror" value="{{ old('recipient_type', $donationRequestDescription?->recipient_type) }}" id="recipient_type" placeholder="Recipient Type">
            {!! $errors->first('recipient_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reason" class="form-label">{{ __('Reason') }}</label>
            <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror" value="{{ old('reason', $donationRequestDescription?->reason) }}" id="reason" placeholder="Reason">
            {!! $errors->first('reason', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="latitude" class="form-label">{{ __('Latitude') }}</label>
            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $donationRequestDescription?->latitude) }}" id="latitude" placeholder="Latitude">
            {!! $errors->first('latitude', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="longitude" class="form-label">{{ __('Longitude') }}</label>
            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $donationRequestDescription?->longitude) }}" id="longitude" placeholder="Longitude">
            {!! $errors->first('longitude', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="extra_instructions" class="form-label">{{ __('Extra Instructions') }}</label>
            <input type="text" name="extra_instructions" class="form-control @error('extra_instructions') is-invalid @enderror" value="{{ old('extra_instructions', $donationRequestDescription?->extra_instructions) }}" id="extra_instructions" placeholder="Extra Instructions">
            {!! $errors->first('extra_instructions', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="supporting_documents" class="form-label">{{ __('Supporting Documents') }}</label>
            <input type="text" name="supporting_documents" class="form-control @error('supporting_documents') is-invalid @enderror" value="{{ old('supporting_documents', $donationRequestDescription?->supporting_documents) }}" id="supporting_documents" placeholder="Supporting Documents">
            {!! $errors->first('supporting_documents', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>