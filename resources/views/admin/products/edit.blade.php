@extends('layouts.admin')

@section('content')
<div class="container py-8">
    <h1>Edit product</h1>

    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @method('PUT')
        @include('admin.products._form')
    </form>

    {{-- Image upload form (posts to ProductImageController) --}}
    <div class="mt-8">
        <h2>Images</h2>

        <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label>Image file</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            <div>
                <label>Alt text</label>
                <input type="text" name="alt">
            </div>
            <div>
                <label>Is primary</label>
                <input type="checkbox" name="is_primary" value="1">
            </div>
            <button class="mt-2 px-3 py-2 bg-green-600 text-white rounded" type="submit">Upload</button>
        </form>

        {{-- existing images --}}
        <div class="mt-4 flex gap-4">
            @foreach($product->images as $img)
                <div class="text-center">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}" style="width:120px;height:120px;object-fit:cover;">
                    <div class="mt-1">
                        <form action="{{ route('admin.products.images.destroy', [$product, $img]) }}" method="POST" onsubmit="return confirm('Delete image?');">
                            @csrf @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    </div>
                    <div>
                        @if($img->is_primary) <small>Primary</small> @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
