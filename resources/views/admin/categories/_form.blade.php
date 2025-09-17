@csrf

<div class="card mb-4">
    <div class="card-body">
        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $category->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                aria-describedby="nameHelp">
            @error('name')
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
                value="{{ old('slug', $category->slug ?? '') }}"
                class="form-control @error('slug') is-invalid @enderror"
                aria-describedby="slugHelp"
                required>
            <div id="slugHelp" class="form-text">Unique slug used in URLs</div>
            @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Parent category --}}
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent category (optional)</label>
            <select
                id="parent_id"
                name="parent_id"
                class="form-select @error('parent_id') is-invalid @enderror">
                <option value="">— none —</option>
                @foreach($parents as $p)
                <option value="{{ $p->id }}" @if(old('parent_id', $category->parent_id ?? '') == $p->id) selected @endif>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>
            @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description ?? '') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Position + Active (two columns) --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="position" class="form-label">Position</label>
                <input
                    id="position"
                    name="position"
                    type="number"
                    min="0"
                    value="{{ old('position', $category->position ?? 0) }}"
                    class="form-control @error('position') is-invalid @enderror">
                @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 d-flex align-items-center">
                <div class="form-check mt-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="active"
                        name="active"
                        value="1"
                        @if(old('active', $category->active ?? true)) checked @endif
                    >
                    <label class="form-check-label" for="active">Active</label>
                </div>
            </div>
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
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        function slugify(value) {
            return value.toString().toLowerCase().trim()
                // remove invalid chars
                .replace(/[^\w\s-]/g, '')
                // replace whitespace with -
                .replace(/\s+/g, '-')
                // collapse multiple -
                .replace(/-+/g, '-')
                // trim leading/trailing -
                .replace(/^-+|-+$/g, '');
        }

        if (nameInput && slugInput) {
            nameInput.addEventListener('blur', function() {
                // only auto-fill slug when slug is empty
                if (!slugInput.value.trim()) {
                    slugInput.value = slugify(nameInput.value);
                }
            });
        }
    });
</script>
@endpush