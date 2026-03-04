@extends('layouts.admin')

@section('title', 'Sửa địa điểm')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-warning mb-4">
        <i class="bi bi-pencil-square"></i> Sửa thông tin địa điểm du lịch
    </h3>

    <form action="{{ route('admin.locations.update', $location) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- Tên địa điểm --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Tên địa điểm <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $location->name) }}"
                   >
        </div>

        {{-- Ảnh đại diện --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh đại diện</label>
            <input type="file"
                   name="image"
                   class="form-control"
                   accept="image/*"
                   onchange="previewImage(event)">

            <div class="mt-3">
                <img id="preview"
                     src="{{ $location->image_url ?? asset('images/no-image.jpg') }}"
                     width="200"
                     class="img-thumbnail">
            </div>
        </div>

        {{-- Mô tả ngắn --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Mô tả ngắn <span class="text-danger">*</span></label>
            <textarea name="short_description"
                      class="form-control"
                      rows="3">{{ old('short_description', $location->short_description) }}</textarea>
        </div>

        {{-- Nội dung chi tiết --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nội dung chi tiết <span class="text-danger">*</span></label>
            <textarea name="content" id="editor">
                {{ old('content', $location->content) }}
            </textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu lại
        </button>

        <a href="{{ route('admin.locations.index') }}"
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
