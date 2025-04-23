<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="donation_id" class="form-label">{{ __('Donation Id') }}</label>
            <input type="text" name="donation_id" class="form-control @error('donation_id') is-invalid @enderror" value="{{ old('donation_id', $donationItem?->donation_id) }}" id="donation_id" placeholder="Donation Id">
            {!! $errors->first('donation_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="donation_type_id" class="form-label">{{ __('Donation Type Id') }}</label>
            <input type="text" name="donation_type_id" class="form-control @error('donation_type_id') is-invalid @enderror" value="{{ old('donation_type_id', $donationItem?->donation_type_id) }}" id="donation_type_id" placeholder="Donation Type Id">
            {!! $errors->first('donation_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="item_name" class="form-label">{{ __('Item Name') }}</label>
            <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror" value="{{ old('item_name', $donationItem?->item_name) }}" id="item_name" placeholder="Item Name">
            {!! $errors->first('item_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
            <input type="text" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $donationItem?->quantity) }}" id="quantity" placeholder="Quantity">
            {!! $errors->first('quantity', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="unit" class="form-label">{{ __('Unit') }}</label>
            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $donationItem?->unit) }}" id="unit" placeholder="Unit">
            {!! $errors->first('unit', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $donationItem?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>