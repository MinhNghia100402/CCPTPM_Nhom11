@extends('client.extend')
@section('client_content')
    <main id="main">
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                {{ Breadcrumbs::render('client-footballPitch') }}
                <h2>Tra cứu yêu cầu đặt sân</h2>
            </div>
        </section><!-- End Breadcrumbs -->
        <section class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Nhập mã yêu cầu</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="code" placeholder="code">
                            <button class="btn btn-secondary btn-search-by-code"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            @if ($order && $status)
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="border p-3 mt-4 mt-lg-0 rounded bg-white">
                            <h4 class="header-title mb-3">Thông tin đặt sân
                            </h4>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Trạng thái : </td>
                                            <td><span class="badge bg-{{ $status['bg'] }}">{{ $status['message'] }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Tên sân :</td>
                                            <td>{{ $order->footballPitch->name }} <span class="text-secondary">|
                                                    {{ $order->footballPitch->pitchType->quantity }} người</span></td>
                                        </tr>
                                        <tr>
                                            <td>Mã đặt sân :</td>
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
                                            <td>Email : </td>
                                            <td>{{ $order->email }}</td>
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
                                            <th>Tổng tiền : </th>
                                            <th>{{ $order->total() }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </main><!-- End #main -->
    <script>
        document.querySelector('#header').classList.add('header-inner-pages');
        document.querySelector('.btn-search-by-code').addEventListener('click', function () {
            const search = document.querySelector('#code').value;
            if (search == "") return;
            location.search = "code=" + search;
        });
    </script>
@endsection
