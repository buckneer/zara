{{-- resources/views/account/addresses/_form.blade.php --}}
{{-- Partial used by create & edit. Expects $address (may be null) --}}
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
					<label for="name" class="form-label">Contact name</label>
					<input id="name" name="name" value="{{ old('name', $address->name ?? '') }}" class="form-control" maxlength="191" />
				</div>
			</div>

			<div class="col-md-6">
				<div class="mb-3">
					<label for="company" class="form-label">Company</label>
					<input id="company" name="company" value="{{ old('company', $address->company ?? '') }}" class="form-control" maxlength="191" />
				</div>
			</div>

			<div class="col-12">
				<div class="mb-3">
					<label for="line1" class="form-label">Address line 1 <span class="text-danger">*</span></label>
					<input id="line1" name="line1" value="{{ old('line1', $address->line1 ?? '') }}" class="form-control" maxlength="255" required />
				</div>
			</div>

			<div class="col-12">
				<div class="mb-3">
					<label for="line2" class="form-label">Address line 2</label>
					<input id="line2" name="line2" value="{{ old('line2', $address->line2 ?? '') }}" class="form-control" maxlength="255" />
				</div>
			</div>

			<div class="col-md-4">
				<div class="mb-3">
					<label for="city" class="form-label">City <span class="text-danger">*</span></label>
					<input id="city" name="city" value="{{ old('city', $address->city ?? '') }}" class="form-control" maxlength="191" required />
				</div>
			</div>

			<div class="col-md-4">
				<div class="mb-3">
					<label for="state" class="form-label">State / Region</label>
					<input id="state" name="state" value="{{ old('state', $address->state ?? '') }}" class="form-control" maxlength="191" />
				</div>
			</div>

			<div class="col-md-4">
				<div class="mb-3">
					<label for="postal_code" class="form-label">Postal code</label>
					<input id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" class="form-control" maxlength="50" />
				</div>
			</div>

			<div class="col-md-6">
				<div class="mb-3">
					<label for="country" class="form-label">Country <span class="text-danger">*</span></label>
					<input id="country" name="country" value="{{ old('country', $address->country ?? '') }}" class="form-control" maxlength="100" required />
				</div>
			</div>

			<div class="col-md-6">
				<div class="mb-3">
					<label for="phone" class="form-label">Phone</label>
					<input id="phone" name="phone" value="{{ old('phone', $address->phone ?? '') }}" class="form-control" maxlength="50" />
				</div>
			</div>

			<div class="col-12">
				<div class="mb-3">
					<label for="notes" class="form-label">Notes</label>
					<textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes', $address->notes ?? '') }}</textarea>
				</div>
			</div>

			<div class="col-12 text-end">
				<a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
				<button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Save address' }}</button>
			</div>
		</div>
	</div>
</div>