@php
    // $category may be null (create) or a Category instance (edit)
    $isEdit = isset($category);
@endphp
@csrf

<form method="POST"
    action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
    enctype="multipart/form-data" class="zara-form mx-auto py-4" style="max-width:760px;">
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="card mb-4 border-0 shadow-none" style="background:transparent;">
        <div class="card-body p-0">

            <div class="card p-4" style="background:#fff; border:1px solid #e9e9e9;">

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label text-uppercase small fw-bold">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}"
                        class="form-control @error('name') is-invalid @enderror" required aria-describedby="nameHelp">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Slug --}}
                <div class="mb-3">
                    <label for="slug" class="form-label text-uppercase small fw-bold">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $category->slug ?? '') }}"
                        class="form-control @error('slug') is-invalid @enderror" aria-describedby="slugHelp" required>
                    <div id="slugHelp" class="form-text small text-muted">Unique slug used in URLs</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Parent category --}}
                <div class="mb-3">
                    <label for="parent_id" class="form-label text-uppercase small fw-bold">Parent category
                        (optional)</label>
                    <select id="parent_id" name="parent_id"
                        class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="">— none —</option>
                        @foreach ($parents as $p)
                            <option value="{{ $p->id }}" @if (old('parent_id', $category->parent_id ?? '') == $p->id) selected @endif>
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
                    <label for="description" class="form-label text-uppercase small fw-bold">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Position + Active (two columns) --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="position" class="form-label text-uppercase small fw-bold">Position</label>
                        <input id="position" name="position" type="number" min="0"
                            value="{{ old('position', $category->position ?? 0) }}"
                            class="form-control @error('position') is-invalid @enderror">
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                @if (old('active', $category->active ?? true)) checked @endif>
                            <label class="form-check-label text-uppercase small fw-bold" for="active">Active</label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark text-uppercase fw-bold px-4 py-2">Save</button>
                </div>

            </div>
        </div>
    </div>
</form>

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
