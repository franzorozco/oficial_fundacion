<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $volunteerVerification?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_resp_id" class="form-label">{{ __('User Resp Id') }}</label>
            <input type="text" name="user_resp_id" class="form-control @error('user_resp_id') is-invalid @enderror" value="{{ old('user_resp_id', $volunteerVerification?->user_resp_id) }}" id="user_resp_id" placeholder="User Resp Id">
            {!! $errors->first('user_resp_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="document_type" class="form-label">{{ __('Document Type') }}</label>
            <input type="text" name="document_type" class="form-control @error('document_type') is-invalid @enderror" value="{{ old('document_type', $volunteerVerification?->document_type) }}" id="document_type" placeholder="Document Type">
            {!! $errors->first('document_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="document_url" class="form-label">{{ __('Document Url') }}</label>
            <input type="text" name="document_url" class="form-control @error('document_url') is-invalid @enderror" value="{{ old('document_url', $volunteerVerification?->document_url) }}" id="document_url" placeholder="Document Url">
            {!! $errors->first('document_url', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name_document" class="form-label">{{ __('Name Document') }}</label>
            <input type="text" name="name_document" class="form-control @error('name_document') is-invalid @enderror" value="{{ old('name_document', $volunteerVerification?->name_document) }}" id="name_document" placeholder="Name Document">
            {!! $errors->first('name_document', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $volunteerVerification?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="coment" class="form-label">{{ __('Coment') }}</label>
            <input type="text" name="coment" class="form-control @error('coment') is-invalid @enderror" value="{{ old('coment', $volunteerVerification?->coment) }}" id="coment" placeholder="Coment">
            {!! $errors->first('coment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>