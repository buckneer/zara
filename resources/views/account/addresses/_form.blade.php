

@php
    // $address may be null (create) or an Address instance (edit)
    $isEdit = isset($address);
@endphp

<div class="zara-form">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label text-uppercase small fw-bold">Contact name</label>
                        <input id="name" name="name" value="{{ old('name', $address->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror" maxlength="191" />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company" class="form-label text-uppercase small fw-bold">Company</label>
                        <input id="company" name="company" value="{{ old('company', $address->company ?? '') }}"
                            class="form-control @error('company') is-invalid @enderror" maxlength="191" />
                        @error('company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="line1" class="form-label text-uppercase small fw-bold">Address line 1 <span
                                class="text-danger">*</span></label>
                        <input id="line1" name="line1" value="{{ old('line1', $address->line1 ?? '') }}"
                            class="form-control @error('line1') is-invalid @enderror" maxlength="255" required />
                        @error('line1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="line2" class="form-label text-uppercase small fw-bold">Address line 2</label>
                        <input id="line2" name="line2" value="{{ old('line2', $address->line2 ?? '') }}"
                            class="form-control @error('line2') is-invalid @enderror" maxlength="255" />
                        @error('line2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="city" class="form-label text-uppercase small fw-bold">City <span
                                class="text-danger">*</span></label>
                        <input id="city" name="city" value="{{ old('city', $address->city ?? '') }}"
                            class="form-control @error('city') is-invalid @enderror" maxlength="191" required />
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="state" class="form-label text-uppercase small fw-bold">State / Region</label>
                        <input id="state" name="state" value="{{ old('state', $address->state ?? '') }}"
                            class="form-control @error('state') is-invalid @enderror" maxlength="191" />
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="postal_code" class="form-label text-uppercase small fw-bold">Postal code</label>
                        <input id="postal_code" name="postal_code"
                            value="{{ old('postal_code', $address->postal_code ?? '') }}"
                            class="form-control @error('postal_code') is-invalid @enderror" maxlength="50" />
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="country" class="form-label text-uppercase small fw-bold">Country <span
                                class="text-danger">*</span></label>
                        <input id="country" name="country" value="{{ old('country', $address->country ?? '') }}"
                            class="form-control @error('country') is-invalid @enderror" maxlength="100" required />
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label text-uppercase small fw-bold">Phone</label>
                        <input id="phone" name="phone" value="{{ old('phone', $address->phone ?? '') }}"
                            class="form-control @error('phone') is-invalid @enderror" maxlength="50" />
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="notes" class="form-label text-uppercase small fw-bold">Notes</label>
                        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $address->notes ?? '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 text-end">
                    <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-dark text-uppercase fw-bold px-4 py-2">
                        {{ $buttonText ?? ($isEdit ? 'Save changes' : 'Save address') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
