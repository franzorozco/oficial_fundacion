@extends('adminlte::page')

@section('title', __('Create Donation Item'))

@section('content_header')
    <h1>{{ __('Create Donation Item') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('New Donation Item') }}</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('donation-items.store') }}" role="form" enctype="multipart/form-data">
                @csrf

                @include('donation-item.form')

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> {{ __('Save') }}
                    </button>
                    <a href="{{ route('donation-items.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    const fields = {
        donation_id: document.querySelector('[name="donation_id"]'),
        donation_type_id: document.querySelector('[name="donation_type_id"]'),
        item_name: document.querySelector('[name="item_name"]'),
        quantity: document.querySelector('[name="quantity"]'),
        unit: document.querySelector('[name="unit"]'),
        description: document.querySelector('[name="description"]'),
    };

    function showError(input, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');

        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.classList.add('invalid-feedback');
            input.parentNode.appendChild(feedback);
        }

        feedback.innerText = message;
    }

    function showSuccess(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    }

    function validateDonationId() {
        const value = fields.donation_id.value.trim();

        if (value === '') {
            showError(fields.donation_id, 'El ID es obligatorio');
            return false;
        }

        if (isNaN(value)) {
            showError(fields.donation_id, 'Debe ser numérico');
            return false;
        }

        showSuccess(fields.donation_id);
        return true;
    }

    function validateType() {
        if (fields.donation_type_id.value === '') {
            showError(fields.donation_type_id, 'Selecciona un tipo');
            return false;
        }
        showSuccess(fields.donation_type_id);
        return true;
    }

    function validateItemName() {
        const value = fields.item_name.value.trim();

        if (value.length < 3) {
            showError(fields.item_name, 'Mínimo 3 caracteres');
            return false;
        }

        showSuccess(fields.item_name);
        return true;
    }

    function validateQuantity() {
        const value = fields.quantity.value;

        if (value === '' || value <= 0) {
            showError(fields.quantity, 'Debe ser mayor a 0');
            return false;
        }

        showSuccess(fields.quantity);
        return true;
    }

    function validateUnit() {
        const value = fields.unit.value.trim();

        if (value === '') {
            showError(fields.unit, 'Campo obligatorio');
            return false;
        }

        showSuccess(fields.unit);
        return true;
    }

    function validateDescription() {
        const value = fields.description.value.trim();

        if (value.length > 0 && value.length < 5) {
            showError(fields.description, 'Mínimo 5 caracteres si se usa');
            return false;
        }

        showSuccess(fields.description);
        return true;
    }

    function validateForm() {
        return (
            validateDonationId() &
            validateType() &
            validateItemName() &
            validateQuantity() &
            validateUnit() &
            validateDescription()
        );
    }

    function toggleSubmit() {
        const isValid = validateForm();

        submitBtn.disabled = !isValid;

        if (isValid) {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        } else {
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        }
    }

    // Eventos en tiempo real
    Object.values(fields).forEach(field => {
        field.addEventListener('input', toggleSubmit);
        field.addEventListener('change', toggleSubmit);
    });

    // Validación inicial
    toggleSubmit();

    // Bloqueo final (extra seguridad)
    form.addEventListener('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('⚠️ Corrige los errores antes de continuar');
        }
    });

});
</script>