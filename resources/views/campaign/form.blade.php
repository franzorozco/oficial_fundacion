<div class="row padding-1 p-1">
    <div class="col-md-12">
        

        <div class="form-group mb-2 mb20">
            <label for="creator_id" class="form-label">{{ __('Creator name') }}</label>
            <select name="creator_id" class="form-control @error('creator_id') is-invalid @enderror" id="creator_id">
                <option value="">{{ __('Select a creator') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('creator_id', $campaign?->creator_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('creator_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $campaign?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $campaign?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="text" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $campaign?->start_date) }}" id="start_date" placeholder="Start Date">
            {!! $errors->first('start_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="text" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $campaign?->end_date) }}" id="end_date" placeholder="End Date">
            {!! $errors->first('end_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_hour" class="form-label">{{ __('Start Hour') }}</label>
            <input type="text" name="start_hour" class="form-control @error('start_hour') is-invalid @enderror" value="{{ old('start_hour', $campaign?->start_hour) }}" id="start_hour" placeholder="Start Hour">
            {!! $errors->first('start_hour', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_hour" class="form-label">{{ __('End Hour') }}</label>
            <input type="text" name="end_hour" class="form-control @error('end_hour') is-invalid @enderror" value="{{ old('end_hour', $campaign?->end_hour) }}" id="end_hour" placeholder="End Hour">
            {!! $errors->first('end_hour', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>