@csrf

<div class="card mb-4">
	<div class="card-body">

		{{-- Title --}}
		<div class="mb-3">
			<label for="title" class="form-label">Title</label>
			<input
				id="title"
				name="title"
				type="text"
				value="{{ old('title', $product->title ?? '') }}"
				class="form-control @error('title') is-invalid @enderror"
				required
				aria-describedby="titleHelp">
			@error('title')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- Slug --}}
		<div class="mb-3">
			<label for="slug" class="form-label">Slug</label>
			<input
				id="slug"
				name="slug"
				type="text"
				value="{{ old('slug', $product->slug ?? '') }}"
				class="form-control @error('slug') is-invalid @enderror"
				aria-describedby="slugHelp">
			<div id="slugHelp" class="form-text">Unique slug (letters, numbers, hyphens)</div>
			@error('slug')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- SKU --}}
		<div class="mb-3">
			<label for="sku" class="form-label">SKU</label>
			<input
				id="sku"
				name="sku"
				type="text"
				value="{{ old('sku', $product->sku ?? '') }}"
				class="form-control @error('sku') is-invalid @enderror">
			@error('sku')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- Price --}}
		<div class="mb-3">
			<label for="price" class="form-label">Price</label>
			<input
				id="price"
				name="price"
				type="number"
				step="0.01"
				value="{{ old('price', isset($product->price) ? $product->price : '') }}"
				class="form-control @error('price') is-invalid @enderror">
			@error('price')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- Brand --}}
		<div class="mb-3">
			<label for="brand" class="form-label">Brand</label>
			<input
				id="brand"
				name="brand"
				type="text"
				value="{{ old('brand', $product->brand ?? '') }}"
				class="form-control @error('brand') is-invalid @enderror">
			@error('brand')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- Description --}}
		<div class="mb-3">
			<label for="description" class="form-label">Description</label>
			<textarea
				id="description"
				name="description"
				rows="6"
				class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
			@error('description')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- Categories --}}
		@php
		$selectedCategories = old('category_ids', isset($product) ? $product->categories->pluck('id')->toArray() : []);
		@endphp

		<div class="mb-3">
			<label for="category_ids" class="form-label">Categories</label>
			<select
				id="category_ids"
				name="category_ids[]"
				multiple
				size="6"
				class="form-select @error('category_ids') is-invalid @enderror">
				@foreach($categories as $cat)
				<option value="{{ $cat->id }}" @if(in_array($cat->id, (array)$selectedCategories)) selected @endif>
					{{ $cat->name }}
				</option>
				@endforeach
			</select>
			@error('category_ids')
			<div class="invalid-feedback">{{ $message }}</div>
			@enderror
		</div>

		{{-- Active --}}
		<div class="mb-3 form-check">
			<input
				type="checkbox"
				class="form-check-input"
				id="active"
				name="active"
				value="1"
				@if(old('active', $product->active ?? false)) checked @endif
			>
			<label class="form-check-label" for="active">Active</label>
		</div>

		{{-- Submit --}}
		<div class="d-flex">
			<button type="submit" class="btn btn-primary">Save</button>
		</div>

	</div>
</div>

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const titleInput = document.getElementById('title');
		const slugInput = document.getElementById('slug');

		function slugify(value) {
			return String(value).toLowerCase().trim()
				.replace(/[^\w\s-]/g, '') // remove invalid chars
				.replace(/\s+/g, '-') // replace spaces with -
				.replace(/-+/g, '-') // collapse multiple -
				.replace(/^-+|-+$/g, ''); // trim leading/trailing -
		}

		if (titleInput && slugInput) {
			titleInput.addEventListener('blur', function() {
				if (!slugInput.value.trim()) {
					slugInput.value = slugify(titleInput.value);
				}
			});
		}
	});
</script>
@endpush