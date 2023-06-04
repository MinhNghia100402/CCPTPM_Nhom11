@extends('client.extend')
@section('client_content')
    <main id="main">
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                {{ Breadcrumbs::render('client-order-by-me') }}
                <h2>Yêu cầu đặt sân của tôi</h2>
            </div>
        </section><!-- End Breadcrumbs -->
        <section class="container">
            @include('client.layouts.alert')
            <div class="">
                @foreach ($orders as $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-2">
                                    <span class="fw-bold text-uppercase">Trạng thái:</span>
                                    @if ($item->status == 0)
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @elseif ($item->status == 1)
                                        <span class="badge bg-warning">Chờ xác nhận</span>
                                    @elseif ($item->status == 2)
                                        <span class="badge bg-success">Thành công</span>
                                    @else
                                        <span class="badge bg-primary">Đã thanh toán</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="fw-bold text-uppercase">Tên sân:</span>
                                    <span>{{ $item->footballPitch->name }} | {{ $item->footballPitch->pitchType->quantity }}
                                        người</span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="fw-bold text-uppercase">Tổng tiền:</span>
                                    <span>{{ $item->total() }}</span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="fw-bold text-uppercase">Cọc tiền:</span>
                                    <span>{{ $item->deposit() }}</span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="fw-bold text-uppercase">Mã code:</span>
                                    <span>{{ $item->code }}</span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="fw-bold text-uppercase">Thời gian bắt đầu:</span>
                                    <div class="col">
                                        <input type="time" value="{{ explode(' ', $item->start_at)[1] }}" disabled
                                            class="form-control custom-input">
                                        <input type="date" value="{{ explode(' ', $item->start_at)[0] }}" disabled
                                            class="form-control custom-input">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <span class="fw-bold text-uppercase">Thời gian kết thúc:</span>
                                    <div class="col">
                                        <input type="time" value="{{ explode(' ', $item->end_at)[1] }}" disabled
                                            class="form-control custom-input">
                                        <input type="date" value="{{ explode(' ', $item->end_at)[0] }}" disabled
                                            class="form-control custom-input">
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="fw-bold text-uppercase">Tổng giờ:</span>
                                    <span>{{ $item->totalTime() }}</span>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <span class="fw-bold text-uppercase">Đặt lúc:</span>
                                    <span>{{ $item->createdAt() }}</span>
                                </div>
                                @if ($item->status == 1)
                                    <div class="col-md-2 mb-2 text-center">
                                        <form action="{{ route('order.cancelOrder', $item->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            <button class="confirm-btn btn btn-danger">Hủy sân</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $orders->links() }}
        </section>
    </main><!-- End #main -->
    <script>
        document.querySelector('#header').classList.add('header-inner-pages');
    </script>
@endsection
