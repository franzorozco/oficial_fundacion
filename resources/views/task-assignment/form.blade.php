<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="task_id" class="form-label">{{ __('Id de Tarea') }}</label>
            <input type="text" name="task_id" class="form-control @error('task_id') is-invalid @enderror" value="{{ old('task_id', $taskAssignment?->task_id) }}" id="task_id" placeholder="Id de Tarea">
            {!! $errors->first('task_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="donation_request_id" class="form-label">{{ __('Id de Solicitud de Donación') }}</label>
            <input type="text" name="donation_request_id" class="form-control @error('donation_request_id') is-invalid @enderror" value="{{ old('donation_request_id', $taskAssignment?->donation_request_id) }}" id="donation_request_id" placeholder="Id de Solicitud de Donación">
            {!! $errors->first('donation_request_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('Id de Usuario') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $taskAssignment?->user_id) }}" id="user_id" placeholder="Id de Usuario">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="supervisor" class="form-label">{{ __('Supervisor') }}</label>
            <input type="text" name="supervisor" class="form-control @error('supervisor') is-invalid @enderror" value="{{ old('supervisor', $taskAssignment?->supervisor) }}" id="supervisor" placeholder="Supervisor">
            {!! $errors->first('supervisor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $taskAssignment?->status) }}" id="status" placeholder="Estado">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="assigned_at" class="form-label">{{ __('Asignado En') }}</label>
            <input type="text" name="assigned_at" class="form-control @error('assigned_at') is-invalid @enderror" value="{{ old('assigned_at', $taskAssignment?->assigned_at) }}" id="assigned_at" placeholder="Asignado En">
            {!! $errors->first('assigned_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notas') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $taskAssignment?->notes) }}" id="notes" placeholder="Notas">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
    </div>
</div>
