@csrf

<div class="mb-4">
	<label class="block font-medium">Title</label>
	<input name="title" value="{{ old('title', $product->title ?? '') }}" class="w-full border p-2" id="title">
</div>

<div class="mb-4">
	<label class="block font-medium">Slug</label>
	<input name="slug" value="{{ old('slug', $product->slug ?? '') }}" class="w-full border p-2" id="slug">
	<small class="text-gray-600">Unique slug (letters, numbers, hyphens)</small>
</div>

<div class="mb-4">
	<label class="block">SKU</label>
	<input name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="w-full border p-2">
</div>

<div class="mb-4">
	<label class="block">Price</label>
	<input name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full border p-2">
</div>

<div class="mb-4">
	<label class="block">Brand</label>
	<input name="brand" value="{{ old('brand', $product->brand ?? '') }}" class="w-full border p-2">
</div>

<div class="mb-4">
	<label class="block">Description</label>
	<textarea name="description" class="w-full border p-2" rows="6">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-4">
	<label class="block">Categories</label>
	<select name="category_ids[]" multiple class="w-full border p-2">
		@foreach($categories as $cat)
		<option value="{{ $cat->id }}"
			@if(in_array($cat->id, old('category_ids', isset($product) ? $product->categories->pluck('id')->toArray() : []))) selected @endif>
			{{ $cat->name }}
		</option>
		@endforeach
	</select>
</div>

<div class="mb-4">
	<label class="inline-flex items-center">
		<input type="checkbox" name="active" value="1" @if(old('active', $product->active ?? false)) checked @endif>
		<span class="ml-2">Active</span>
	</label>
</div>

<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

{{-- JS helper: auto-fill slug from title if empty --}}
@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const title = document.getElementById('title');
		const slug = document.getElementById('slug');
		if (title && slug) {
			title.addEventListener('blur', function() {
				if (!slug.value) {
					slug.value = title.value.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
				}
			});
		}
	});
</script>
@endpush