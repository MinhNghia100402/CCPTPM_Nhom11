@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">
        {{-- Tiêu đề --}}
        <div class="pagetitle">
            <h1>Thanh toán</h1>
        </div>
        {{ Breadcrumbs::render('orderCheckout') }}
        @include('admin.layouts.alert')
        {{-- Body --}}

        <section class="section">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-lg-6">
                    <div class="border p-3 mt-4 mt-lg-0 rounded bg-white">
                        <h4 class="header-title mb-3">Thông tin thanh toán - <span
                                class="{{ 'text-' . $arr['status'] }}">{{ $arr['message'] }}</span></h4>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td>Tên sân :</td>
                                        <td>{{ $order->footballPitch->name }} <span class="text-secondary">|
                                                {{ $order->footballPitch->pitchType->quantity }} người</span></td>
                                    </tr>
                                    <tr>
                                        <td>Mã yêu cầu :</td>
                                        <td>{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Người đặt :</td>
                                        <td>{{ $order->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Số điện thoại : </td>
                                        <td>{{ $order->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Thời gian đặt :</td>
                                        <td>{{ $order->start_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Thời gian kết thúc : </td>
                                        <td>{{ $order->end_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tổng thời gian : </td>
                                        <td>{{ $order->totalTime() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ghi chú : </td>
                                        <td>{{ $order->note }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền : </td>
                                        <td>{{ $order->total() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tiền cọc : </td>
                                        <td>-{{ $order->deposit() }}</td>
                                    </tr>
                                    <tr>
                                        <th>Thành tiền :</th>
                                        <th>{{ $order->finalTotal() }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                    @if (!$isCheckout)
                        <form action="{{ route('order.paid', $order->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mt-3 text-center">
                                <button class="btn btn-success" type="submit">Thanh toán</button>
                            </div>
                        </form>
                    @endif
                </div>
                <div class="col-3"></div>
            </div>
        </section>
    </main>
@endsection
