<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="campaign_id" class="form-label">{{ __('Campaign Id') }}</label>
            <input type="text" name="campaign_id" class="form-control @error('campaign_id') is-invalid @enderror" value="{{ old('campaign_id', $event?->campaign_id) }}" id="campaign_id" placeholder="Campaign Id">
            {!! $errors->first('campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="creator_id" class="form-label">{{ __('Creator Id') }}</label>
            <input type="text" name="creator_id" class="form-control @error('creator_id') is-invalid @enderror" value="{{ old('creator_id', $event?->creator_id) }}" id="creator_id" placeholder="Creator Id">
            {!! $errors->first('creator_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $event?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $event?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="event_date" class="form-label">{{ __('Event Date') }}</label>
            <input type="text" name="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', $event?->event_date) }}" id="event_date" placeholder="Event Date">
            {!! $errors->first('event_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>