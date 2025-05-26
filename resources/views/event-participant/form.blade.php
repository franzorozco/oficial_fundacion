<div class="row p-1">
    <div class="col-md-12">
        {{-- Campaña --}}
        <div class="form-group mb-3">
            <label for="campaign_id" class="form-label">Campaña</label>
            <select name="campaign_id" id="campaign_id" class="form-control @error('campaign_id') is-invalid @enderror">
                <option value="">-- Selecciona una campaña --</option>
                @foreach($campaigns as $id => $name)
                    <option value="{{ $id }}" {{ old('campaign_id', $eventParticipant->campaign_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Evento --}}
        <div class="form-group mb-3">
            <label for="event_id" class="form-label">Evento</label>
            <select name="event_id" id="event_id" class="form-control @error('event_id') is-invalid @enderror">
                <option value="">-- Selecciona un evento --</option>
            </select>
            {!! $errors->first('event_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Ubicación del evento --}}
        <div class="form-group mb-3">
            <label for="event_locations_id" class="form-label">Ubicación</label>
            <select name="event_locations_id" id="event_locations_id" class="form-control @error('event_locations_id') is-invalid @enderror">
                <option value="">-- Selecciona una ubicación --</option>
            </select>
            {!! $errors->first('event_locations_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Participante (usuario) --}}
        <div class="form-group mb-3">
            <label for="user_id" class="form-label">Participante</label>
            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                <option value="">-- Selecciona un usuario --</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ old('user_id', $eventParticipant->user_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Observaciones --}}
        <div class="form-group mb-3">
            <label for="observations" class="form-label">Observaciones</label>
            <textarea name="observations" id="observations" rows="3" class="form-control @error('observations') is-invalid @enderror">{{ old('observations', $eventParticipant->observations ?? '') }}</textarea>
            {!! $errors->first('observations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- Estado --}}
        <div class="form-group mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="registrado" {{ old('status', $eventParticipant->status ?? '') == 'registrado' ? 'selected' : '' }}>Registrado</option>
                <option value="asistido" {{ old('status', $eventParticipant->status ?? '') == 'asistido' ? 'selected' : '' }}>Asistido</option>
                <option value="ausente" {{ old('status', $eventParticipant->status ?? '') == 'ausente' ? 'selected' : '' }}>Ausente</option>
            </select>
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-outline-primary">
            {{ isset($eventParticipant) && $eventParticipant->exists ? 'Actualizar Participación' : 'Registrar Participación' }}
        </button>
    </div>
</div>

{{-- JavaScript --}}
<script type="text/javascript">
    $(document).ready(function() {
        // Al cambiar campaña
        $('#campaign_id').change(function() {
            const campaign_id = $(this).val();

            $('#event_id').empty().append('<option value="">-- Selecciona un evento --</option>');
            $('#event_locations_id').empty().append('<option value="">-- Selecciona una ubicación --</option>');

            if (campaign_id) {
                $.get('/get-events-by-campaign/' + campaign_id, function(data) {
                    $.each(data.events, function(key, value) {
                        $('#event_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            }
        });

        // Al cambiar evento
        $('#event_id').change(function() {
            const event_id = $(this).val();

            $('#event_locations_id').empty().append('<option value="">-- Selecciona una ubicación --</option>');

            if (event_id) {
                $.get('/get-locations-by-event/' + event_id, function(data) {
                    $.each(data.locations, function(key, value) {
                        $('#event_locations_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                });
            }
        });
    });
</script>
