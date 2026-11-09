@php
    $value = fn (string $key, $default = '') => $data[$key] ?? $default;
    $prefix = $kind . '[' . $index . ']';
    $countries = collect(\App\Support\CountryCatalog::all());
    $countryByName = $countries->first(function ($country) use ($value) {
        return strcasecmp($country['name'], (string) $value('country')) === 0
            || strcasecmp($country['code'], (string) $value('country')) === 0;
    });
    $selectedCountryCode = $value('country_code', $countryByName['code'] ?? 'ID');
    $parsePhone = function ($number) use ($countries) {
        $digits = preg_replace('/\D+/', '', (string) $number);
        $match = $countries->sortByDesc(fn ($country) => strlen(preg_replace('/\D+/', '', $country['calling_code'])))
            ->first(function ($country) use ($digits) {
                $callingCode = preg_replace('/\D+/', '', $country['calling_code']);
                return $digits !== '' && str_starts_with($digits, $callingCode);
            });
        $callingCode = $match['calling_code'] ?? '+62';
        $callingDigits = preg_replace('/\D+/', '', $callingCode);
        $nationalNumber = $match ? substr($digits, strlen($callingDigits)) : $digits;

        return [$callingCode, $nationalNumber];
    };
    [$telephoneCallingCode, $telephoneNumber] = $parsePhone($value('telephone'));
@endphp

<div class="repeatable-item">
    @if ($value('id'))<input type="hidden" name="{{ $prefix }}[id]" value="{{ $value('id') }}">@endif
    <button type="button" class="repeatable-item__remove" data-remove-row aria-label="Remove row" title="Remove row">
        <i class="fas fa-trash" aria-hidden="true"></i>
    </button>
    <div class="row">
        @if ($kind === 'liable_people')
            <div class="edit-field col-md-4"><label>Name</label><input name="{{ $prefix }}[name]" class="form-control" value="{{ $value('name') }}" maxlength="255" required></div>
            <div class="edit-field col-md-4"><label>Position</label><input name="{{ $prefix }}[position]" class="form-control" value="{{ $value('position') }}" maxlength="255" required></div>
            <div class="edit-field col-md-4"><label>NIK</label><input name="{{ $prefix }}[nik]" class="form-control" value="{{ $value('nik') }}" maxlength="50" required></div>
        @elseif ($kind === 'contacts')
            <div class="edit-field col-md-4"><label>Department</label><input name="{{ $prefix }}[department]" class="form-control" value="{{ $value('department') }}" maxlength="255" required></div>
            <div class="edit-field col-md-4"><label>Position</label><input name="{{ $prefix }}[position]" class="form-control" value="{{ $value('position') }}" maxlength="255" required></div>
            <div class="edit-field col-md-4"><label>Name</label><input name="{{ $prefix }}[name]" class="form-control" value="{{ $value('name') }}" maxlength="255" required></div>
            <div class="edit-field col-md-6"><label>Email</label><input type="email" name="{{ $prefix }}[email]" class="form-control" value="{{ $value('email') }}" maxlength="255" required></div>
            <div class="edit-field col-md-6">
                <label for="{{ $kind }}_telephone_{{ $index }}">Telephone</label>
                <div class="phone-input-group">
                    <select name="{{ $prefix }}[telephone_country_code]" class="form-control phone-country-code" aria-label="Telephone country code" required>
                        @foreach ($countries as $country)
                            <option value="{{ $country['calling_code'] }}" data-country="{{ strtolower($country['code']) }}" {{ $telephoneCallingCode === $country['calling_code'] ? 'selected' : '' }}>{{ $country['code'] }} {{ $country['calling_code'] }}</option>
                        @endforeach
                    </select>
                    <input id="{{ $kind }}_telephone_{{ $index }}" type="tel" name="{{ $prefix }}[telephone]" class="form-control phone-number" value="{{ $telephoneNumber }}" inputmode="numeric" pattern="[0-9]{2,20}" maxlength="20" autocomplete="tel-national" required>
                </div>
            </div>
        @elseif ($kind === 'addresses')
            <div class="edit-field col-12"><label>Address</label><textarea name="{{ $prefix }}[address]" class="form-control" rows="2" required>{{ $value('address') }}</textarea></div>
            <div class="edit-field col-md-4">
                <div class="edit-field__label">Country</div>
                <input type="hidden" name="{{ $prefix }}[country]" value="{{ $countryByName['name'] ?? $value('country', 'Indonesia') }}" data-country-name>
                <select id="address_country_{{ $index }}" name="{{ $prefix }}[country_code]" class="form-control address-country-select" aria-label="Country" required>
                    @foreach ($countries as $country)
                        <option value="{{ $country['code'] }}" data-name="{{ $country['name'] }}" data-country="{{ strtolower($country['code']) }}" {{ $selectedCountryCode === $country['code'] ? 'selected' : '' }}>{{ $country['code'] }} - {{ $country['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="edit-field col-md-4"><div class="edit-field__label">Province</div><select id="address_province_{{ $index }}" name="{{ $prefix }}[province]" class="form-control address-province-select" data-current="{{ $value('province') }}" aria-label="Province" required><option value="{{ $value('province') }}">{{ $value('province') }}</option></select></div>
            <div class="edit-field col-md-4"><div class="edit-field__label">City / Regency</div><select id="address_city_{{ $index }}" name="{{ $prefix }}[city]" class="form-control address-city-select" data-current="{{ $value('city') }}" aria-label="City or regency" required><option value="{{ $value('city') }}">{{ $value('city') }}</option></select></div>
            <div class="edit-field col-md-4"><label>Postal Code</label><input name="{{ $prefix }}[zip_code]" class="form-control" value="{{ $value('zip_code') }}" maxlength="20" required></div>
            <div class="edit-field col-md-4">
                <label for="{{ $kind }}_telephone_{{ $index }}">Telephone</label>
                <div class="phone-input-group">
                    <select name="{{ $prefix }}[telephone_country_code]" class="form-control phone-country-code" aria-label="Telephone country code" required>
                        @foreach ($countries as $country)
                            <option value="{{ $country['calling_code'] }}" data-country="{{ strtolower($country['code']) }}" {{ $telephoneCallingCode === $country['calling_code'] ? 'selected' : '' }}>{{ $country['code'] }} {{ $country['calling_code'] }}</option>
                        @endforeach
                    </select>
                    <input id="{{ $kind }}_telephone_{{ $index }}" type="tel" name="{{ $prefix }}[telephone]" class="form-control phone-number" value="{{ $telephoneNumber }}" inputmode="numeric" pattern="[0-9]{2,20}" maxlength="20" autocomplete="tel-national" required>
                </div>
            </div>
            <div class="edit-field col-md-4"><label>Fax <small class="text-muted">(optional)</small></label><input name="{{ $prefix }}[fax]" class="form-control" value="{{ $value('fax') }}" maxlength="50"></div>
            <div class="edit-field col-md-6"><label>Latitude</label><input type="number" step="any" name="{{ $prefix }}[latitude]" class="form-control" value="{{ $value('latitude') }}"></div>
            <div class="edit-field col-md-6"><label>Longitude</label><input type="number" step="any" name="{{ $prefix }}[longitude]" class="form-control" value="{{ $value('longitude') }}"></div>
            <div class="edit-field col-12 address-map-field">
                <label for="address_map_search_{{ $index }}">Map coordinate</label>
                <div class="input-group address-map-search">
                    <input id="address_map_search_{{ $index }}" type="text" class="form-control" data-coordinate-search value="{{ $value('address') }}" maxlength="500" placeholder="Search a nearby place or complete address">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" data-find-coordinate>
                            <i class="fas fa-search mr-1" aria-hidden="true"></i> Search
                        </button>
                    </div>
                </div>
                <small class="address-map-status text-muted" data-coordinate-status aria-live="polite">Click the map or drag the pin to adjust the location.</small>
                <div class="submission-map-shell" data-map-shell>
                    <button type="button" class="submission-map-fullscreen" data-map-fullscreen aria-label="Open fullscreen map" title="Open fullscreen map">
                        <i class="fas fa-expand" aria-hidden="true"></i>
                        <span>Fullscreen</span>
                    </button>
                    <div class="submission-address-map" data-address-map aria-label="Address map"></div>
                </div>
            </div>
        @elseif ($kind === 'banks')
            <div class="edit-field col-md-4"><label>Bank Name</label><input name="{{ $prefix }}[name]" class="form-control" value="{{ $value('name') }}" maxlength="255" required></div>
            <div class="edit-field col-md-4"><label>Account Name</label><input name="{{ $prefix }}[account_name]" class="form-control" value="{{ $value('account_name') }}" maxlength="255" required></div>
            <div class="edit-field col-md-4"><label>Account Number</label><input name="{{ $prefix }}[account_number]" class="form-control" value="{{ $value('account_number') }}" maxlength="100" required></div>
        @endif
    </div>
</div>
