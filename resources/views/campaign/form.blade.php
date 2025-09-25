                        <div class="row g-3">
                            <div class="col-md-12">
                                {{-- Selector de Creador --}}
                                <div class="form-group">
                                    <label for="creator_id" class="form-label">{{ __('Creator Name') }}</label>
                                    <select name="creator_id" id="creator_id" class="form-select @error('creator_id') is-invalid @enderror">
                                        <option value="">{{ __('Select a creator') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('creator_id', $campaign?->creator_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('creator_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nombre --}}
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $campaign?->name) }}"
                                        placeholder="Campaign name"
                                        class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Descripción --}}
                                <div class="form-group">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="description"
                                            class="form-control @error('description') is-invalid @enderror"
                                            rows="3"
                                            placeholder="Campaign description">{{ old('description', $campaign?->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Fechas --}}
                                <div class="form-group">
                                    <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ old('start_date', $campaign?->start_date ? $campaign->start_date->format('Y-m-d') : '') }}"
                                        class="form-control @error('start_date') is-invalid @enderror">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                                    <input type="date" name="end_date" id="end_date"
                                        value="{{ old('end_date', $campaign?->end_date ? $campaign->end_date->format('Y-m-d') : '') }}"
                                        class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                                                
 
                                {{-- Mostrar públicamente --}}
                                <div class="form-group mt-2">
                                    <label for="show_cam" class="form-label">{{ __('¿Mostrar públicamente?') }}</label>
                                    <select name="show_cam" id="show_cam" class="form-select @error('show_cam') is-invalid @enderror">
                                        <option value="1" {{ old('show_cam', $campaign?->show_cam) == true ? 'selected' : '' }}>{{ __('Sí') }}</option>
                                        <option value="0" {{ old('show_cam', $campaign?->show_cam) == false ? 'selected' : '' }}>{{ __('No') }}</option>
                                    </select>
                                    @error('show_cam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Observaciones --}}
                                <div class="form-group mt-2">
                                    <label for="observations" class="form-label">{{ __('Observaciones') }}</label>
                                    <textarea name="observations" id="observations"
                                            class="form-control @error('observations') is-invalid @enderror"
                                            rows="2"
                                            placeholder="Observaciones de la campaña">{{ old('observations', $campaign?->observations) }}</textarea>
                                    @error('observations')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Foto de la campaña --}}
                                <div class="form-group mt-2">
                                    <label for="foto" class="form-label">{{ __('Foto de la campaña') }}</label>
                                    <input type="file" name="foto" id="foto"
                                        accept="image/*"
                                        class="form-control @error('foto') is-invalid @enderror">
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Checkbox para habilitar recursos económicos --}}
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" value="1" id="has_funds_checkbox">
                                    <label class="form-check-label" for="has_funds_checkbox">
                                        {{ __('¿Esta campaña tiene recursos económicos?') }}
                                    </label>
                                </div>
                                
                                {{-- Recursos económicos para la campaña --}}
                                <div id="financial_section" class="mt-3" style="display: none;">
                                    <div class="form-group mt-2">
                                        <label for="financial_account_id_1" class="form-label">{{ __('Cuenta financiera') }}</label>
                                        <select name="financial_account_id_1" id="financial_account_id_1"
                                                class="form-select @error('financial_account_id_1') is-invalid @enderror">
                                            <option value="">{{ __('Seleccione una cuenta') }}</option>
                                            @foreach($financialAccounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ old('financial_account_id_1', $campaign?->financial_account_id_1) == $account->id ? 'selected' : '' }}>
                                                    {{ $account->name }} - Saldo: Bs {{ number_format($account->balance, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('financial_account_id_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </dis   div>

                                    <div class="form-group mt-2">
                                        <label for="fund_amount">{{ __('Monto a asignar (Bs)') }}</label>
                                        <input type="number" name="fund_amount" id="fund_amount"
                                                value="{{ old('fund_amount') }}"
                                                step="0.01" min="0"
                                                class="form-control @error('fund_amount') is-invalid @enderror">

                                        @error('fund_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            


                            {{-- Botón de envío --}}
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> {{ __('Submit') }}
                                    </button>
                                </div>
                            <script>

                                document.addEventListener('DOMContentLoaded', function () {
                                    const checkbox = document.getElementById('has_funds_checkbox');
                                    const financialSection = document.getElementById('financial_section');

                                    function toggleFinancialSection() {
                                        financialSection.style.display = checkbox.checked ? 'block' : 'none';
                                    }
                                    toggleFinancialSection();
                                    checkbox.addEventListener('change', toggleFinancialSection);
                                });
                            </script>
                        </div>