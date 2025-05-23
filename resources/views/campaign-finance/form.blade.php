<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="campaign_id" class="form-label">{{ __('Campaign') }}</label>
            <select name="campaign_id" class="form-control @error('campaign_id') is-invalid @enderror" id="campaign_id">
                @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->id }}" {{ old('campaign_id', $campaignFinance?->campaign_id) == $campaign->id ? 'selected' : '' }}>
                        {{ $campaign->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="manager_id" class="form-label">{{ __('Manager') }}</label>
            <select name="manager_id" class="form-control @error('manager_id') is-invalid @enderror" id="manager_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('manager_id', $campaignFinance?->manager_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('manager_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="income" class="form-label">{{ __('Income') }}</label>
            <input type="text" name="income" class="form-control @error('income') is-invalid @enderror" value="{{ old('income', $campaignFinance?->income) }}" id="income" placeholder="Income">
            {!! $errors->first('income', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="expenses" class="form-label">{{ __('Expenses') }}</label>
            <input type="text" name="expenses" class="form-control @error('expenses') is-invalid @enderror" value="{{ old('expenses', $campaignFinance?->expenses) }}" id="expenses" placeholder="Expenses">
            {!! $errors->first('expenses', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="net_balance" class="form-label">{{ __('Net Balance') }}</label>
            <input type="text" name="net_balance" class="form-control @error('net_balance') is-invalid @enderror" value="{{ old('net_balance', $campaignFinance?->net_balance) }}" id="net_balance" placeholder="Net Balance">
            {!! $errors->first('net_balance', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
