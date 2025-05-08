<div class="row padding-1 p-1">
    <div class="col-md-12">
        <!-- Donor -->
        <div class="form-group mb-2 mb20">
            <label for="donor_id" class="form-label">{{ __('Donor') }}</label>
            <select name="donor_id" class="form-control @error('donor_id') is-invalid @enderror" id="donor_id">
                <option value="">Select Donor</option>
                @foreach ($donors as $donor)
                    <option value="{{ $donor->id }}" {{ old('donor_id', $donationsCash->donor_id) == $donor->id ? 'selected' : '' }}>
                        {{ $donor->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('donor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- External Donor -->
        <div class="form-group mb-2 mb20">
            <label for="external_donor_id" class="form-label">{{ __('External Donor') }}</label>
            <select name="external_donor_id" class="form-control @error('external_donor_id') is-invalid @enderror" id="external_donor_id">
                <option value="">Select External Donor</option>
                @foreach ($externalDonors as $externalDonor)
                    <option value="{{ $externalDonor->id }}" {{ old('external_donor_id', $donationsCash->external_donor_id) == $externalDonor->id ? 'selected' : '' }}>
                        {{ $externalDonor->names  }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('external_donor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        <!-- Amount -->
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount', $donationsCash->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Method -->
        <div class="form-group mb-2 mb20">
            <label for="method" class="form-label">{{ __('Method') }}</label>
            <select name="method" class="form-control @error('method') is-invalid @enderror" id="method">
                <option value="">Select Method</option>
                @php
                    $methods = [
                        'efectivo',
                        'transferencia bancaria',
                        'transferencia internacional',
                        'QR tigo money',
                        'QR banco',
                        'Yape',
                        'cheque',
                        'donación directa',
                        'plataforma digital',
                        'Depósito',
                    ];
                @endphp
                @foreach ($methods as $method)
                    <option value="{{ $method }}" {{ old('method', $donationsCash->method) == $method ? 'selected' : '' }}>
                        {{ ucfirst($method) }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('method', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>



        <!-- Donation Date -->
        <div class="form-group mb-2 mb20">
            <label for="donation_date" class="form-label">{{ __('Donation Date') }}</label>
            <input type="date" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror"
                   value="{{ old('donation_date', isset($donationsCash->donation_date) ? \Carbon\Carbon::parse($donationsCash->donation_date)->format('Y-m-d') : '') }}" id="donation_date">
            {!! $errors->first('donation_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Campaign -->
        <div class="form-group mb-2 mb20">
            <label for="campaign_id" class="form-label">{{ __('Campaign') }}</label>
            <select name="campaign_id" class="form-control @error('campaign_id') is-invalid @enderror" id="campaign_id">
                <option value="">Select Campaign</option>
                @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->id }}" {{ old('campaign_id', $donationsCash->campaign_id) == $campaign->id ? 'selected' : '' }}>
                        {{ $campaign->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('campaign_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
