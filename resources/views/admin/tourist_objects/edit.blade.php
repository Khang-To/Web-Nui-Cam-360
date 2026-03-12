@extends('layouts.admin')

@section('title', 'Sửa đối tượng tham quan')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-warning mb-4">
        <i class="bi bi-pencil-square"></i> Sửa thông tin đối tượng tham quan
    </h3>

    <form action="{{ route('admin.tourist_objects.update', $touristObject) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- Tên đối tượng --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Tên đối tượng tham quan <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $touristObject->name) }}"
                   placeholder="VD: Tượng phật Di Lặc, Hồ Thủy Liêm...">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Địa điểm --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Địa điểm <span class="text-danger">*</span></label>
            <select name="location_id"
                    class="form-control @error('location_id') is-invalid @enderror">
                <option value="">-- Chọn địa điểm --</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('location_id', $touristObject->location_id) == $location->id)>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
            @error('location_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh đại diện --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh đại diện</label>
            <input type="file"
                   name="image"
                   class="form-control @error('image') is-invalid @enderror"
                   accept="image/*"
                   onchange="previewImage(event)">

            <div class="mt-3">
                <img id="preview"
                     src="{{ $touristObject->image_url ?? asset('images/no-image.jpg') }}"
                     width="200"
                     class="img-thumbnail">
            </div>
            @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Mô tả chi tiết <span class="text-danger">*</span></label>
            <textarea name="description"
                      id="editor"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $touristObject->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu lại
        </button>

        <a href="{{ route('admin.tourist_objects.index') }}"
           class="btn btn-secondary">Quay lại</a>

    </form>

</div>
@endsection


@push('scripts')

{{-- Preview ảnh --}}
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

{{-- TinyMCE LOCAL --}}
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    tinymce.init({
        selector: '#editor',
        height: 500,

        license_key: 'gpl',

        plugins: [
            'image', 'link', 'lists', 'table', 'code', 'autoresize'
        ],

        toolbar: 'undo redo | blocks | bold italic underline | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist | image table | code',

        menubar: true,

        automatic_uploads: true,

        images_upload_handler: function (blobInfo, progress) {

            return new Promise((resolve, reject) => {

                let xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('admin.upload.editor.image') }}");
                xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function () {
                    if (xhr.status !== 200) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }

                    let json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.url !== 'string') {
                        reject('Invalid JSON');
                        return;
                    }

                    resolve(json.url);
                };

                xhr.onerror = function () {
                    reject('Image upload failed');
                };

                let formData = new FormData();
                formData.append('upload', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            });
        }
    });

});
</script>

@endpush
