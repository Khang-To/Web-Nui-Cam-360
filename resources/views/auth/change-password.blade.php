@extends('layouts.admin')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Đổi mật khẩu</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.change-password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleField(buttonId, fieldId) {
        const btn = document.getElementById(buttonId);
        const field = document.getElementById(fieldId);
        btn.addEventListener('click', function () {
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.className = 'bi bi-eye-slash';
            } else {
                icon.className = 'bi bi-eye';
            }
        });
    }
    toggleField('toggleCurrentPassword', 'current_password');
    toggleField('toggleNewPassword', 'password');
    toggleField('toggleConfirmPassword', 'password_confirmation');
</script>
@endpush
@endsection
