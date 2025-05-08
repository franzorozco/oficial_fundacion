<div class="row padding-1 p-1">
    {{-- Primera columna --}}
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
            {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password">
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="phone" class="form-label">{{ __('Phone') }}</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user?->phone) }}" id="phone" placeholder="Phone">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="address" class="form-label">{{ __('Address') }}</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                value="{{ old('address', $user?->address) }}" id="address" placeholder="Address">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="document_number" class="form-label">{{ __('Document Number') }}</label>
            <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                value="{{ old('document_number', $user?->profile?->document_number) }}" id="document_number" placeholder="Document Number">
            {!! $errors->first('document_number', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="birthdate" class="form-label">{{ __('Birthdate') }}</label>
            <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                value="{{ old('birthdate', $user?->profile?->birthdate) }}" id="birthdate">
            {!! $errors->first('birthdate', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="location" class="form-label">{{ __('Location') }}</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                value="{{ old('location', $user?->profile?->location) }}" id="location" placeholder="Location">
            {!! $errors->first('location', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="languages_spoken" class="form-label">{{ __('Languages Spoken') }}</label>
            <input type="text" name="languages_spoken" class="form-control @error('languages_spoken') is-invalid @enderror"
                value="{{ old('languages_spoken', $user?->profile?->languages_spoken) }}" id="languages_spoken" placeholder="E.g., Spanish, English">
            {!! $errors->first('languages_spoken', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="availability_days" class="form-label">{{ __('Availability Days') }}</label>
            <input type="text" name="availability_days" class="form-control @error('availability_days') is-invalid @enderror"
                value="{{ old('availability_days', $user?->profile?->availability_days) }}" id="availability_days" placeholder="E.g., Monday, Wednesday">
            {!! $errors->first('availability_days', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="availability_hours" class="form-label">{{ __('Availability Hours') }}</label>
            <input type="text" name="availability_hours" class="form-control @error('availability_hours') is-invalid @enderror"
                value="{{ old('availability_hours', $user?->profile?->availability_hours) }}" id="availability_hours" placeholder="E.g., 9am-5pm">
            {!! $errors->first('availability_hours', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="transport_available" class="form-label">{{ __('Transport Available') }}</label>
            <select name="transport_available" id="transport_available" class="form-control @error('transport_available') is-invalid @enderror">
                <option value="0" {{ old('transport_available', $user?->profile?->transport_available) == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                <option value="1" {{ old('transport_available', $user?->profile?->transport_available) == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
            </select>
            {!! $errors->first('transport_available', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="experience_level" class="form-label">{{ __('Experience Level') }}</label>
            <select name="experience_level" id="experience_level" class="form-control @error('experience_level') is-invalid @enderror">
                <option value="básico" {{ old('experience_level', $user?->profile?->experience_level) == 'básico' ? 'selected' : '' }}>{{ __('Basic') }}</option>
                <option value="intermedio" {{ old('experience_level', $user?->profile?->experience_level) == 'intermedio' ? 'selected' : '' }}>{{ __('Intermediate') }}</option>
                <option value="avanzado" {{ old('experience_level', $user?->profile?->experience_level) == 'avanzado' ? 'selected' : '' }}>{{ __('Advanced') }}</option>
            </select>
            {!! $errors->first('experience_level', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2">
            <label for="physical_condition" class="form-label">{{ __('Physical Condition') }}</label>
            <select name="physical_condition" id="physical_condition" class="form-control @error('physical_condition') is-invalid @enderror">
                <option value="buena" {{ old('physical_condition', $user?->profile?->physical_condition) == 'buena' ? 'selected' : '' }}>{{ __('Good') }}</option>
                <option value="moderada" {{ old('physical_condition', $user?->profile?->physical_condition) == 'moderada' ? 'selected' : '' }}>{{ __('Moderate') }}</option>
                <option value="limitada" {{ old('physical_condition', $user?->profile?->physical_condition) == 'limitada' ? 'selected' : '' }}>{{ __('Limited') }}</option>
            </select>
            {!! $errors->first('physical_condition', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    {{-- Campos tipo textarea se mantienen en una sola columna --}}
    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="bio" class="form-label">{{ __('Biography') }}</label>
            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" id="bio" placeholder="Biography">{{ old('bio', $user?->profile?->bio) }}</textarea>
            {!! $errors->first('bio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="skills" class="form-label">{{ __('Skills') }}</label>
            <textarea name="skills" class="form-control @error('skills') is-invalid @enderror" id="skills" placeholder="Skills">{{ old('skills', $user?->profile?->skills) }}</textarea>
            {!! $errors->first('skills', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="interests" class="form-label">{{ __('Interests') }}</label>
            <textarea name="interests" class="form-control @error('interests') is-invalid @enderror" id="interests" placeholder="Interests">{{ old('interests', $user?->profile?->interests) }}</textarea>
            {!! $errors->first('interests', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group mb-2">
            <label for="preferred_tasks" class="form-label">{{ __('Preferred Tasks') }}</label>
            <textarea name="preferred_tasks" class="form-control @error('preferred_tasks') is-invalid @enderror" id="preferred_tasks" placeholder="Preferred Tasks">{{ old('preferred_tasks', $user?->profile?->preferred_tasks) }}</textarea>
            {!! $errors->first('preferred_tasks', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
