<div class="row g-3">
    <div class="col-md-12">
        {{-- Selector de Creador --}}
        <div class="form-group">
            <label for="creator_id" class="form-label">{{ __('Creator Name') }}</label>
            <select name="creator_id" id="creator_id" class="form-select @error('creator_id') is-invalid @enderror">
                <option value="">{{ __('Select a creator') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('creator_id', $campaign?->creator_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('creator_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="form-group">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $campaign?->name) }}"
                   placeholder="Campaign name"
                   class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="form-group">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" id="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="3"
                      placeholder="Campaign description">{{ old('description', $campaign?->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Fechas --}}
        <div class="form-group">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="date" name="start_date" id="start_date"
                   value="{{ old('start_date', $campaign?->start_date) }}"
                   class="form-control @error('start_date') is-invalid @enderror">
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="date" name="end_date" id="end_date"
                   value="{{ old('end_date', $campaign?->end_date) }}"
                   class="form-control @error('end_date') is-invalid @enderror">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Horas --}}
        <div class="form-group">
            <label for="start_hour" class="form-label">{{ __('Start Hour') }}</label>
            <input type="time" name="start_hour" id="start_hour"
                   value="{{ old('start_hour', $campaign?->start_hour) }}"
                   class="form-control @error('start_hour') is-invalid @enderror">
            @error('start_hour')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_hour" class="form-label">{{ __('End Hour') }}</label>
            <input type="time" name="end_hour" id="end_hour"
                   value="{{ old('end_hour', $campaign?->end_hour) }}"
                   class="form-control @error('end_hour') is-invalid @enderror">
            @error('end_hour')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Botón de envío --}}
    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> {{ __('Submit') }}
        </button>
    </div>
</div>
