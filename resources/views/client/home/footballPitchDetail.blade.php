@extends('client.extend')
@section('client_content')
    <main id="main">
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                {{ Breadcrumbs::render('client-footballPitch') }}
                @if ($footballPitch['is_maintenance'])
                    <h2 class="card-title text-danger">Thông tin - {{ $footballPitch->name }} - Đang bảo trì</h2>
                @else
                    <h2>Thông tin - {{ $footballPitch->name }}</h2>
                @endif
            </div>
        </section><!-- End Breadcrumbs -->
        <!-- ======= Portfolio Section ======= -->
        <section id="portfolio-details" class="portfolio-details">
            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-7">
                        <div class="portfolio-details-slider swiper">
                            <div class="swiper-wrapper align-items-center">

                                @if ($footballPitch->images->count() > 0)
                                    @foreach ($footballPitch->images as $item)
                                        <div class="swiper-slide">
                                            <img src="{{ config('app.image') . $item->image }}" alt="">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="swiper-slide">
                                        <img src="{{ config('app.image') . 'images/football_pitches/default_football_pitch.png' }}"
                                            alt="">
                                    </div>
                                @endif
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="portfolio-info">
                            <h3>Thông tin</h3>
                            <ul>
                                <li><strong>Tên</strong>: {{ $footballPitch->name }}</li>
                                <li>
                                    <strong>Tình trạng</strong>:
                                    @if ($footballPitch->is_maintenance)
                                        <span class="badge bg-danger">Đang bảo trì</span>
                                    @else
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    @endif
                                </li>
                                <li><strong>Số người</strong>: {{ $footballPitch->pitchType->quantity }}</li>
                                <li><strong>Giá tiền</strong>: {{ $footballPitch->pricePerHour() }} -
                                    {{ $footballPitch->pricePerPeakHour() }}</li>
                                <li><strong>Thời gian mở - đóng</strong>: {{ $footballPitch->timeStart() }} -
                                    {{ $footballPitch->timeEnd() }}</li>
                                <li><strong>Số lần đặt</strong>: {{ $footballPitch->countOrderSuccess() }}</li>
                                <li><strong>Sân liên kết</strong>: {{ $footballPitch->nameFootBallPitchLink() }}</li>
                                <li>
                                    <button @if ($footballPitch->is_maintenance) disabled @endif class="btn btn-success"
                                        data-bs-toggle="modal" data-bs-target="#orderModal">Đặt
                                        ngay</button>
                                </li>
                                <li>
                                    <button @if ($footballPitch->is_maintenance) disabled @endif class="btn btn-success"
                                        data-bs-toggle="modal" data-bs-target="#findTimeModal">Xem thời gian sân đã đặt</button>
                                </li>
                            </ul>
                        </div>
                        <div class="portfolio-description">
                            <h2>Mô tả</h2>
                            <p>
                                {{ $footballPitch->description }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Portfolio Details Section -->
    </main><!-- End #main -->
    @include('client.modal.order')
    @include('client.modal.findTime')
    <script>
        document.querySelector('#header').classList.add('header-inner-pages');
    </script>
@endsection
