<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="event_id" class="form-label">{{ __('Event Id') }}</label>
            <input type="text" name="event_id" class="form-control @error('event_id') is-invalid @enderror" value="{{ old('event_id', $eventParticipant?->event_id) }}" id="event_id" placeholder="Event Id">
            {!! $errors->first('event_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $eventParticipant?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registration_date" class="form-label">{{ __('Registration Date') }}</label>
            <input type="text" name="registration_date" class="form-control @error('registration_date') is-invalid @enderror" value="{{ old('registration_date', $eventParticipant?->registration_date) }}" id="registration_date" placeholder="Registration Date">
            {!! $errors->first('registration_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="observations" class="form-label">{{ __('Observations') }}</label>
            <input type="text" name="observations" class="form-control @error('observations') is-invalid @enderror" value="{{ old('observations', $eventParticipant?->observations) }}" id="observations" placeholder="Observations">
            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $eventParticipant?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>