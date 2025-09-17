@csrf

<div class="mb-4">
    <label class="block font-medium">Name</label>
    <input name="name" value="{{ old('name', $category->name ?? '') }}" class="w-full border p-2" required>
    @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block font-medium">Slug</label>
    <input name="slug" id="slug" value="{{ old('slug', $category->slug ?? '') }}" class="w-full border p-2" required>
    <small class="text-gray-600">Unique slug used in URLs</small>
    @error('slug') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block font-medium">Parent category (optional)</label>
    <select name="parent_id" class="w-full border p-2">
        <option value="">— none —</option>
        @foreach($parents as $p)
            <option value="{{ $p->id }}"
                @if(old('parent_id', $category->parent_id ?? '') == $p->id) selected @endif>
                {{ $p->name }}
            </option>
        @endforeach
    </select>
    @error('parent_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block font-medium">Description</label>
    <textarea name="description" class="w-full border p-2" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div>
        <label class="block font-medium">Position</label>
        <input type="number" name="position" value="{{ old('position', $category->position ?? 0) }}" class="w-full border p-2">
        @error('position') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center">
        <label class="inline-flex items-center mt-6">
            <input type="checkbox" name="active" value="1" @if(old('active', $category->active ?? true)) checked @endif>
            <span class="ml-2">Active</span>
        </label>
    </div>
</div>

<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const titleInput = document.querySelector('input[name="name"]');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', function() {
            if (!slugInput.value.trim()) {
                slugInput.value = titleInput.value.toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-');
            }
        });
    }
});
</script>
@endpush
