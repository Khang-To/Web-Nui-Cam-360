@extends('layouts.client')

@section('title', $location->name . ' - Mai Tùng House')

@section('content')
<section class="d-flex align-items-center justify-content-center"
         style="height: 50vh; min-height: 400px;
                background: linear-gradient(rgba(27, 94, 32, 0.7), rgba(0, 0, 0, 0.6)),
                            url('{{ $location->image_url }}') center/cover no-repeat;
                margin-top: 0;">
    <div class="container text-center pt-5">
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up">{{ $location->name }}</h1>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="content-detail mt-3" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-dark);" data-aos="fade-up">
                    {!! $location->content !!}
                </div>

                <div class="mt-5 pt-4 border-top d-flex flex-column align-items-center text-center" data-aos="fade-up">
                    <span class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">Khám phá tiếp</span>

                    @if($nextLocation)
                    <a href="{{ route('client.location.detail', $nextLocation->id) }}" class="btn btn-outline-success btn-lg rounded-pill px-5">
                        {{ $nextLocation->name }} <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    @endif

                    <a href="{{ route('client.location.index') }}" class="text-muted text-decoration-none mt-3 small custom-hover-link">
                        <i class="fas fa-th-large me-1"></i> Xem tất cả danh thắng
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .content-detail img {
        max-width: 100%;
        height: auto !important;
        border-radius: 8px;
        margin: 15px 0;
    }

    .custom-hover-link { transition: color 0.3s; }
    .custom-hover-link:hover { color: var(--accent) !important; }
</style>
@endsection
