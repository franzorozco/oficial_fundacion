<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $profile?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="bio" class="form-label">{{ __('Bio') }}</label>
            <input type="text" name="bio" class="form-control @error('bio') is-invalid @enderror" value="{{ old('bio', $profile?->bio) }}" id="bio" placeholder="Bio">
            {!! $errors->first('bio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="document_number" class="form-label">{{ __('Document Number') }}</label>
            <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror" value="{{ old('document_number', $profile?->document_number) }}" id="document_number" placeholder="Document Number">
            {!! $errors->first('document_number', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="photo" class="form-label">{{ __('Photo') }}</label>
            <input type="text" name="photo" class="form-control @error('photo') is-invalid @enderror" value="{{ old('photo', $profile?->photo) }}" id="photo" placeholder="Photo">
            {!! $errors->first('photo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="birthdate" class="form-label">{{ __('Birthdate') }}</label>
            <input type="text" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate', $profile?->birthdate) }}" id="birthdate" placeholder="Birthdate">
            {!! $errors->first('birthdate', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="skills" class="form-label">{{ __('Skills') }}</label>
            <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror" value="{{ old('skills', $profile?->skills) }}" id="skills" placeholder="Skills">
            {!! $errors->first('skills', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="interests" class="form-label">{{ __('Interests') }}</label>
            <input type="text" name="interests" class="form-control @error('interests') is-invalid @enderror" value="{{ old('interests', $profile?->interests) }}" id="interests" placeholder="Interests">
            {!! $errors->first('interests', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="availability_days" class="form-label">{{ __('Availability Days') }}</label>
            <input type="text" name="availability_days" class="form-control @error('availability_days') is-invalid @enderror" value="{{ old('availability_days', $profile?->availability_days) }}" id="availability_days" placeholder="Availability Days">
            {!! $errors->first('availability_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="availability_hours" class="form-label">{{ __('Availability Hours') }}</label>
            <input type="text" name="availability_hours" class="form-control @error('availability_hours') is-invalid @enderror" value="{{ old('availability_hours', $profile?->availability_hours) }}" id="availability_hours" placeholder="Availability Hours">
            {!! $errors->first('availability_hours', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="location" class="form-label">{{ __('Location') }}</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $profile?->location) }}" id="location" placeholder="Location">
            {!! $errors->first('location', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="transport_available" class="form-label">{{ __('Transport Available') }}</label>
            <input type="text" name="transport_available" class="form-control @error('transport_available') is-invalid @enderror" value="{{ old('transport_available', $profile?->transport_available) }}" id="transport_available" placeholder="Transport Available">
            {!! $errors->first('transport_available', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="experience_level" class="form-label">{{ __('Experience Level') }}</label>
            <input type="text" name="experience_level" class="form-control @error('experience_level') is-invalid @enderror" value="{{ old('experience_level', $profile?->experience_level) }}" id="experience_level" placeholder="Experience Level">
            {!! $errors->first('experience_level', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="physical_condition" class="form-label">{{ __('Physical Condition') }}</label>
            <input type="text" name="physical_condition" class="form-control @error('physical_condition') is-invalid @enderror" value="{{ old('physical_condition', $profile?->physical_condition) }}" id="physical_condition" placeholder="Physical Condition">
            {!! $errors->first('physical_condition', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="preferred_tasks" class="form-label">{{ __('Preferred Tasks') }}</label>
            <input type="text" name="preferred_tasks" class="form-control @error('preferred_tasks') is-invalid @enderror" value="{{ old('preferred_tasks', $profile?->preferred_tasks) }}" id="preferred_tasks" placeholder="Preferred Tasks">
            {!! $errors->first('preferred_tasks', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="languages_spoken" class="form-label">{{ __('Languages Spoken') }}</label>
            <input type="text" name="languages_spoken" class="form-control @error('languages_spoken') is-invalid @enderror" value="{{ old('languages_spoken', $profile?->languages_spoken) }}" id="languages_spoken" placeholder="Languages Spoken">
            {!! $errors->first('languages_spoken', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>