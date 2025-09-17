@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Edit product</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                @method('PUT')
                @include('admin.products._form')
            </form>
        </div>
    </div>

    {{-- Images --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Images</h5>

            <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                @csrf

                <div class="col-md-5">
                    <label for="image" class="form-label">Image file</label>
                    <input id="image" type="file" name="image" accept="image/*" required class="form-control">
                </div>

                <div class="col-md-5">
                    <label for="alt" class="form-label">Alt text</label>
                    <input id="alt" type="text" name="alt" class="form-control">
                </div>

                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check mb-0 me-2">
                        <input id="is_primary" class="form-check-input" type="checkbox" name="is_primary" value="1">
                        <label class="form-check-label small" for="is_primary">Is primary</label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </form>

            {{-- existing images --}}
            <div class="mt-4">
                @if($product->images->count())
                <div class="row gy-3">
                    @foreach($product->images as $img)
                    <div class="col-6 col-sm-4 col-md-3 text-center">
                        <div class="ratio ratio-1x1 mb-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}" alt="{{ $img->alt ?? '' }}" class="img-fluid rounded">
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <form action="{{ route('admin.products.images.destroy', [$product, $img]) }}" method="POST" onsubmit="return confirm('Delete image?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>

                        <div class="mt-2">
                            @if($img->is_primary)
                            <span class="badge bg-primary">Primary</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No images uploaded yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection