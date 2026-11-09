@php
    $value = fn (string $key, $default = '') => $data[$key] ?? $default;
    $prefix = $kind . '[' . $index . ']';
@endphp

<div class="repeatable-item">
    @if ($value('id'))<input type="hidden" name="{{ $prefix }}[id]" value="{{ $value('id') }}">@endif
    <button type="button" class="repeatable-item__remove" data-remove-row aria-label="Remove row" title="Remove row">
        <i class="fas fa-trash" aria-hidden="true"></i>
    </button>
    <div class="row">
        @if ($kind === 'liable_people')
            <div class="form-group col-md-4"><label>Name</label><input name="{{ $prefix }}[name]" class="form-control" value="{{ $value('name') }}" maxlength="255" required></div>
            <div class="form-group col-md-4"><label>Position</label><input name="{{ $prefix }}[position]" class="form-control" value="{{ $value('position') }}" maxlength="255" required></div>
            <div class="form-group col-md-4"><label>NIK</label><input name="{{ $prefix }}[nik]" class="form-control" value="{{ $value('nik') }}" maxlength="50" required></div>
        @elseif ($kind === 'contacts')
            <div class="form-group col-md-4"><label>Department</label><input name="{{ $prefix }}[department]" class="form-control" value="{{ $value('department') }}" maxlength="255" required></div>
            <div class="form-group col-md-4"><label>Position</label><input name="{{ $prefix }}[position]" class="form-control" value="{{ $value('position') }}" maxlength="255" required></div>
            <div class="form-group col-md-4"><label>Name</label><input name="{{ $prefix }}[name]" class="form-control" value="{{ $value('name') }}" maxlength="255" required></div>
            <div class="form-group col-md-6"><label>Email</label><input type="email" name="{{ $prefix }}[email]" class="form-control" value="{{ $value('email') }}" maxlength="255" required></div>
            <div class="form-group col-md-6"><label>Telephone</label><input name="{{ $prefix }}[telephone]" class="form-control" value="{{ $value('telephone') }}" maxlength="50" required></div>
        @elseif ($kind === 'addresses')
            <div class="form-group col-12"><label>Address</label><textarea name="{{ $prefix }}[address]" class="form-control" rows="2" required>{{ $value('address') }}</textarea></div>
            <div class="form-group col-md-4"><label>Country</label><input name="{{ $prefix }}[country]" class="form-control" value="{{ $value('country') }}" maxlength="100" required></div>
            <div class="form-group col-md-4"><label>Province</label><input name="{{ $prefix }}[province]" class="form-control" value="{{ $value('province') }}" maxlength="100" required></div>
            <div class="form-group col-md-4"><label>City / Regency</label><input name="{{ $prefix }}[city]" class="form-control" value="{{ $value('city') }}" maxlength="100" required></div>
            <div class="form-group col-md-4"><label>Postal Code</label><input name="{{ $prefix }}[zip_code]" class="form-control" value="{{ $value('zip_code') }}" maxlength="20" required></div>
            <div class="form-group col-md-4"><label>Telephone</label><input name="{{ $prefix }}[telephone]" class="form-control" value="{{ $value('telephone') }}" maxlength="50" required></div>
            <div class="form-group col-md-4"><label>Fax <small class="text-muted">(optional)</small></label><input name="{{ $prefix }}[fax]" class="form-control" value="{{ $value('fax') }}" maxlength="50"></div>
            <div class="form-group col-md-6"><label>Latitude</label><input type="number" step="any" name="{{ $prefix }}[latitude]" class="form-control" value="{{ $value('latitude') }}"></div>
            <div class="form-group col-md-6"><label>Longitude</label><input type="number" step="any" name="{{ $prefix }}[longitude]" class="form-control" value="{{ $value('longitude') }}"></div>
        @elseif ($kind === 'banks')
            <div class="form-group col-md-4"><label>Bank Name</label><input name="{{ $prefix }}[name]" class="form-control" value="{{ $value('name') }}" maxlength="255" required></div>
            <div class="form-group col-md-4"><label>Account Name</label><input name="{{ $prefix }}[account_name]" class="form-control" value="{{ $value('account_name') }}" maxlength="255" required></div>
            <div class="form-group col-md-4"><label>Account Number</label><input name="{{ $prefix }}[account_number]" class="form-control" value="{{ $value('account_number') }}" maxlength="100" required></div>
        @endif
    </div>
</div>
