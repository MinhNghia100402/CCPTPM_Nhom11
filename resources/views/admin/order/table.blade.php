@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">
        {{-- Tiêu đề --}}
        <div class="pagetitle">
            <h1>Quản lý đặt sân</h1>
        </div>
        {{ Breadcrumbs::render('orderTable') }}
        @include('admin.layouts.alert')
        {{-- Body --}}
        <section class="section">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title">Yêu cầu bảng</h5>
                    <!-- Các tab -->
                    <ul class="nav nav-tabs nav-tabs-bordered mb-2" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all" aria-selected="true"
                                role="tab">Tất cả</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#order-unpay"
                                aria-selected="false" role="tab" tabindex="-1">Các sân sắp hết</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#advantage" aria-selected="false"
                                role="tab" tabindex="-1">Tùy chỉnh</button>
                        </li>

                    </ul>

                    <div class="tab-content pt-2">
                        {{-- tat ca --}}
                        <div class="tab-pane fade active show" id="all" role="tabpanel">
                            <table id="table_order" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sân</th>
                                        <th>Bắt đầu lúc</th>
                                        <th>Kết thúc lúc</th>
                                        <th>Cọc</th>
                                        <th>Tổng tiền</th>
                                        <th>Code</th>
                                        <th>Tình trạng</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        {{-- chua thanh toan --}}
                        <div class="tab-pane fade" id="order-unpay" role="tabpanel">
                            <table data-url="{{ route('order.getOrderUnpaid') }}" id="table_order_unpaid" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sân</th>
                                        <th>Bắt đầu lúc</th>
                                        <th>Kết thúc lúc</th>
                                        <th>Cọc</th>
                                        <th>Tổng tiền</th>
                                        <th>Code</th>
                                        <th>Tình trạng</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        {{-- tuy chinh --}}
                        <div class="tab-pane fade" id="advantage" role="tabpanel">
                            <form action="{{ route('order.clearOrderNotUse') }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="confirm-btn btn btn-danger mb-2">Xóa các yêu cầu đặt sân rác</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
    @include('admin.modal.order.updateOrderCalendar')
@endsection
