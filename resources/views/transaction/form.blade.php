<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="account_id" class="form-label">{{ __('Account Id') }}</label>
            <input type="text" name="account_id" class="form-control @error('account_id') is-invalid @enderror" value="{{ old('account_id', $transaction?->account_id) }}" id="account_id" placeholder="Account Id">
            {!! $errors->first('account_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="type" class="form-label">{{ __('Type') }}</label>
            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $transaction?->type) }}" id="type" placeholder="Type">
            {!! $errors->first('type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $transaction?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $transaction?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="related_campaign_id" class="form-label">{{ __('Related Campaign Id') }}</label>
            <input type="text" name="related_campaign_id" class="form-control @error('related_campaign_id') is-invalid @enderror" value="{{ old('related_campaign_id', $transaction?->related_campaign_id) }}" id="related_campaign_id" placeholder="Related Campaign Id">
            {!! $errors->first('related_campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="related_payment_id" class="form-label">{{ __('Related Payment Id') }}</label>
            <input type="text" name="related_payment_id" class="form-control @error('related_payment_id') is-invalid @enderror" value="{{ old('related_payment_id', $transaction?->related_payment_id) }}" id="related_payment_id" placeholder="Related Payment Id">
            {!! $errors->first('related_payment_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="transaction_date" class="form-label">{{ __('Transaction Date') }}</label>
            <input type="text" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', $transaction?->transaction_date) }}" id="transaction_date" placeholder="Transaction Date">
            {!! $errors->first('transaction_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="created_by" class="form-label">{{ __('Created By') }}</label>
            <input type="text" name="created_by" class="form-control @error('created_by') is-invalid @enderror" value="{{ old('created_by', $transaction?->created_by) }}" id="created_by" placeholder="Created By">
            {!! $errors->first('created_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>