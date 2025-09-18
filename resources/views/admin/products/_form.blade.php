
@php
    $isEdit = isset($product);
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
    enctype="multipart/form-data" class="zara-form mx-auto py-5" style="max-width:820px;">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="card mb-4 border-0 shadow-none" style="background:transparent;">
        <div class="card-body p-0">

            <div class="mb-4">
                <h2 class="mb-1 display-6 fw-bold" style="letter-spacing: .05em;">
                    {{ $isEdit ? 'Edit Product' : 'New Product' }}</h2>
                <p class="small text-muted mb-0">Black & white minimal form — keep it elegant.</p>
            </div>

            <div class="card p-4" style="background:#fff; border:1px solid #e9e9e9;">
                
                <div class="mb-3">
                    <label for="title" class="form-label text-uppercase small fw-bold">Title</label>
                    <input id="title" name="title" type="text"
                        value="{{ old('title', $product->title ?? '') }}"
                        class="form-control form-control-lg border-0 border-bottom rounded-0 @error('title') is-invalid @enderror"
                        required aria-describedby="titleHelp" style="background:transparent;">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
                <div class="mb-3">
                    <label for="slug" class="form-label text-uppercase small fw-bold">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $product->slug ?? '') }}"
                        class="form-control border-0 border-bottom rounded-0 @error('slug') is-invalid @enderror"
                        aria-describedby="slugHelp" style="background:transparent;">
                    <div id="slugHelp" class="form-text small text-muted">Unique slug (letters, numbers, hyphens)</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label for="sku" class="form-label text-uppercase small fw-bold">SKU</label>
                        <input id="sku" name="sku" type="text"
                            value="{{ old('sku', $product->sku ?? '') }}"
                            class="form-control border-0 border-bottom rounded-0 @error('sku') is-invalid @enderror"
                            style="background:transparent;">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="col-md-6">
                        <label for="price" class="form-label text-uppercase small fw-bold">Price</label>
                        <input id="price" name="price" type="number" step="0.01"
                            value="{{ old('price', isset($product->price) ? $product->price : '') }}"
                            class="form-control border-0 border-bottom rounded-0 @error('price') is-invalid @enderror"
                            style="background:transparent;">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                
                <div class="mb-3 mt-3">
                    <label for="brand" class="form-label text-uppercase small fw-bold">Brand</label>
                    <input id="brand" name="brand" type="text"
                        value="{{ old('brand', $product->brand ?? '') }}"
                        class="form-control border-0 border-bottom rounded-0 @error('brand') is-invalid @enderror"
                        style="background:transparent;">
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
                <div class="mb-3">
                    <label for="description" class="form-label text-uppercase small fw-bold">Description</label>
                    <textarea id="description" name="description" rows="6"
                        class="form-control border-0 border-bottom rounded-0 @error('description') is-invalid @enderror"
                        style="background:transparent;">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                
                @php
                    $selectedCategories = old(
                        'category_ids',
                        isset($product) ? $product->categories->pluck('id')->toArray() : [],
                    );
                @endphp
                <div class="mb-3">
                    <label for="category_ids" class="form-label text-uppercase small fw-bold">Categories</label>
                    <select id="category_ids" name="category_ids[]" multiple size="6"
                        class="form-select rounded-0 border-0 border-bottom @error('category_ids') is-invalid @enderror"
                        style="background:transparent;">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @if (in_array($cat->id, (array) $selectedCategories)) selected @endif>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mt-3">
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1"
                            @if (old('active', $product->active ?? false)) checked @endif>
                        <label class="form-check-label text-uppercase small fw-bold" for="active">Active</label>
                    </div>

                    
                    <div>
                        <button type="submit" class="btn btn-dark text-uppercase fw-bold px-4 py-2">
                            Save
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="card mt-4 p-4" style="background:#fff; border:1px solid #e9e9e9;">
                <h6 class="small text-uppercase fw-bold mb-3">Images</h6>

                
                @if ($isEdit && $product->images->count())
                    <div class="mb-3">
                        <label class="form-label small text-uppercase fw-bold d-block mb-2">Existing Images</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($product->images as $img)
                                @php
                                    // use asset('storage/...') which respects current host/port/subfolder
                                    $imgUrl = asset('storage/' . $img->path);
                                @endphp
                                <div
                                    class="image-thumb position-relative text-center {{ $img->is_primary ? 'primary' : '' }}">
                                    <img src="{{ $imgUrl }}" alt="{{ $img->alt }}"
                                        style="width:140px; height:100px; object-fit:cover;">
                                    <div class="mt-2 small">
                                        <label class="d-block mb-1">
                                            <input type="radio" name="existing_primary"
                                                value="{{ $img->id }}"
                                                {{ old('existing_primary', $product->images->where('is_primary', true)->first()->id ?? null) == $img->id ? 'checked' : '' }}>
                                            <span class="ms-1 small">Primary</span>
                                        </label>
                                        <label class="d-block">
                                            <input type="checkbox" name="delete_image_ids[]"
                                                value="{{ $img->id }}">
                                            <span class="ms-1 small">Delete</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                
                <div class="mb-3">
                    <label for="images" class="form-label text-uppercase small fw-bold">Upload New Images</label>
                    <input id="images" name="images[]" type="file" multiple accept="image/*"
                        class="form-control @error('images.*') is-invalid @enderror" style="background:transparent;">
                    <div class="form-text small text-muted mt-1">Max 5MB per image. First uploaded will be primary by
                        default for new uploads.</div>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    
                    <div id="new-previews" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>

            </div>

        </div>
    </div>
</form>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // slugify behavior
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            function slugify(value) {
                return String(value).toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // spaces -> -
                    .replace(/-+/g, '-') // collapse -
                    .replace(/^-+|-+$/g, ''); // trim -
            }
            if (titleInput && slugInput) {
                titleInput.addEventListener('blur', function() {
                    if (!slugInput.value.trim()) slugInput.value = slugify(titleInput.value);
                });
            }

            // preview selected images
            const imagesInput = document.getElementById('images');
            const previews = document.getElementById('new-previews');

            function clearPreviews() {
                previews.innerHTML = '';
            }

            function createPreview(file, index) {
                const reader = new FileReader();
                const wrap = document.createElement('div');
                wrap.className = 'preview-thumb';
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    const name = document.createElement('div');
                    name.className = 'small mt-1 text-truncate';
                    name.style.maxWidth = '120px';
                    name.textContent = file.name;
                    wrap.appendChild(img);
                    wrap.appendChild(name);

                    // radio to set primary among new uploads (first is default)
                    const primWrap = document.createElement('div');
                    primWrap.className = 'mt-2 small';
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'new_primary_index';
                    radio.value = index;
                    if (index === 0) radio.checked = true;
                    primWrap.appendChild(radio);
                    const label = document.createElement('span');
                    label.className = 'ms-1';
                    label.textContent = 'Primary';
                    primWrap.appendChild(label);
                    wrap.appendChild(primWrap);
                };
                reader.readAsDataURL(file);
                return wrap;
            }

            if (imagesInput) {
                imagesInput.addEventListener('change', function(e) {
                    clearPreviews();
                    const files = Array.from(e.target.files || []);
                    files.forEach((f, i) => {
                        if (!f.type.startsWith('image/')) return;
                        const p = createPreview(f, i);
                        previews.appendChild(p);
                    });
                });
            }
        });
    </script>
@endpush
