<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-box text-primary me-1"></i>
            Información del Ítem
        </h5>
        <small class="text-muted">
            Completa los datos del ítem de la donación
        </small>
    </div>

    <div class="card-body">

        <div class="row g-3">

            {{-- DONATION ID --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-hashtag text-primary me-1"></i>
                    Donación
                </label>
                <input type="text" name="donation_id"
                       class="form-control form-control-lg @error('donation_id') is-invalid @enderror"
                       value="{{ old('donation_id', $donationItem?->donation_id) }}"
                       placeholder="ID de la donación">

                @error('donation_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- TIPO --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-tags text-primary me-1"></i>
                    Tipo de Donación
                </label>
                <select name="donation_type_id"
                        class="form-select form-select-lg @error('donation_type_id') is-invalid @enderror">

                    <option value="">Seleccionar tipo</option>

                    @foreach($types as $type)
                        <option value="{{ $type->id }}"
                            {{ old('donation_type_id', $donationItem?->donation_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

                @error('donation_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- NOMBRE --}}
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fas fa-box-open text-primary me-1"></i>
                    Nombre del Ítem
                </label>
                <input type="text" name="item_name"
                       class="form-control form-control-lg @error('item_name') is-invalid @enderror"
                       value="{{ old('item_name', $donationItem?->item_name) }}"
                       placeholder="Ej: Ropa, alimentos, juguetes...">

                @error('item_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CANTIDAD Y UNIDAD --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-sort-numeric-up text-primary me-1"></i>
                    Cantidad
                </label>
                <input type="number" name="quantity"
                       class="form-control form-control-lg @error('quantity') is-invalid @enderror"
                       value="{{ old('quantity', $donationItem?->quantity) }}"
                       placeholder="Cantidad" min="1">

                @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-balance-scale text-primary me-1"></i>
                    Unidad
                </label>
                <input type="text" name="unit"
                       class="form-control form-control-lg @error('unit') is-invalid @enderror"
                       value="{{ old('unit', $donationItem?->unit) }}"
                       placeholder="Ej: kg, unidades, cajas...">

                @error('unit')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fas fa-align-left text-primary me-1"></i>
                    Descripción
                </label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="3"
                          placeholder="Detalles adicionales del ítem...">{{ old('description', $donationItem?->description) }}</textarea>

                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="card-footer bg-light d-flex justify-content-end gap-2">

        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            Cancelar
        </a>

        <button type="submit" id="submitBtn" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar
        </button>

    </div>

</div>