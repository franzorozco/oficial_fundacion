<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="names" class="form-label">{{ __('Names') }}</label>
            <input type="text" name="names" class="form-control @error('names') is-invalid @enderror" value="{{ old('names', $externalDonor?->names) }}" id="names" placeholder="Names">
            {!! $errors->first('names', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="paternal_surname" class="form-label">{{ __('Paternal Surname') }}</label>
            <input type="text" name="paternal_surname" class="form-control @error('paternal_surname') is-invalid @enderror" value="{{ old('paternal_surname', $externalDonor?->paternal_surname) }}" id="paternal_surname" placeholder="Paternal Surname">
            {!! $errors->first('paternal_surname', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="maternal_surname" class="form-label">{{ __('Maternal Surname') }}</label>
            <input type="text" name="maternal_surname" class="form-control @error('maternal_surname') is-invalid @enderror" value="{{ old('maternal_surname', $externalDonor?->maternal_surname) }}" id="maternal_surname" placeholder="Maternal Surname">
            {!! $errors->first('maternal_surname', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $externalDonor?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="phone" class="form-label">{{ __('Phone') }}</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $externalDonor?->phone) }}" id="phone" placeholder="Phone">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="address" class="form-label">{{ __('Address') }}</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $externalDonor?->address) }}" id="address" placeholder="Address">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>