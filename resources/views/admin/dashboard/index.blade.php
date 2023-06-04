@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            {{ Breadcrumbs::render('dashboard') }}
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Lọc</h6>
                                        </li>

                                        <li><a class="dropdown-item" onclick="searchUrl('order_filter', 'today')"
                                                href="javascript:void(0)">Hôm nay</a></li>
                                        <li><a class="dropdown-item" onclick="searchUrl('order_filter', 'this_month')"
                                                href="javascript:void(0)">Tháng này</a></li>
                                        <li><a class="dropdown-item" onclick="searchUrl('order_filter', 'this_year')"
                                                href="javascript:void(0)">Năm này</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Sân đã đặt
                                        @if ($order['sale']['filter'] == 'today')
                                            <span>| Hôm nay</span>
                                        @elseif ($order['sale']['filter'] == 'this_month')
                                            <span>| Tháng này</span>
                                        @else
                                            <span>| Năm này</span>
                                        @endif
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $order['sale']['value'] }}</h6>
                                            @if ($order['sale']['grow'] == 'increase')
                                                <span
                                                    class="text-success small pt-1 fw-bold">{{ $order['sale']['percent'] }}%</span>
                                                <span class="text-muted small pt-2 ps-1">Tăng</span>
                                            @else
                                                <span
                                                    class="text-danger small pt-1 fw-bold">{{ $order['sale']['percent'] }}%</span>
                                                <span class="text-muted small pt-2 ps-1">Giảm</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Doanh thu Card -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Lọc</h6>
                                        </li>

                                        <li><a class="dropdown-item" onclick="searchUrl('order_revenue_filter', 'today')"
                                                href="javascript:void(0)">Hôm nay</a></li>
                                        <li><a class="dropdown-item"
                                                onclick="searchUrl('order_revenue_filter', 'this_month')"
                                                href="javascript:void(0)">Tháng này</a></li>
                                        <li><a class="dropdown-item"
                                                onclick="searchUrl('order_revenue_filter', 'this_year')"
                                                href="javascript:void(0)">Năm này</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Doanh thu
                                        @if ($order['revenue']['filter'] == 'today')
                                            <span>| Hôm nay</span>
                                        @elseif ($order['revenue']['filter'] == 'this_month')
                                            <span>| Tháng này</span>
                                        @else
                                            <span>| Năm này</span>
                                        @endif
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $order['revenue']['value'] }}</h6>
                                            @if ($order['revenue']['grow'] == 'increase')
                                                <span
                                                    class="text-success small pt-1 fw-bold">{{ $order['revenue']['percent'] }}%</span>
                                                <span class="text-muted small pt-2 ps-1">Tăng</span>
                                            @else
                                                <span
                                                    class="text-danger small pt-1 fw-bold">{{ $order['revenue']['percent'] }}%</span>
                                                <span class="text-muted small pt-2 ps-1">Giảm</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Doanh thu Card -->

                        <!-- Khach hang moi -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card customers-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Lọc</h6>
                                        </li>

                                        <li><a class="dropdown-item" onclick="searchUrl('new_customer_filter', 'today')"
                                                href="javascript:void(0)">Hôm nay</a></li>
                                        <li><a class="dropdown-item"
                                                onclick="searchUrl('new_customer_filter', 'this_month')"
                                                href="javascript:void(0)">Tháng này</a></li>
                                        <li><a class="dropdown-item" onclick="searchUrl('new_customer_filter', 'this_year')"
                                                href="javascript:void(0)">Năm này</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Khách hàng mới
                                        @if ($order['new_customer']['filter'] == 'today')
                                            <span>| Hôm nay</span>
                                        @elseif ($order['new_customer']['filter'] == 'this_month')
                                            <span>| Tháng này</span>
                                        @else
                                            <span>| Năm này</span>
                                        @endif
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-plus-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $order['new_customer']['value'] }}</h6>
                                            @if ($order['new_customer']['grow'] == 'increase')
                                                <span
                                                    class="text-success small pt-1 fw-bold">{{ $order['new_customer']['percent'] }}%</span>
                                                <span class="text-muted small pt-2 ps-1">Tăng</span>
                                            @else
                                                <span
                                                    class="text-danger small pt-1 fw-bold">{{ $order['new_customer']['percent'] }}%</span>
                                                <span class="text-muted small pt-2 ps-1">Giảm</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Khach hang moi Card -->

                        <!-- Khach hang moi -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">Yêu cầu đặt sân đang chờ duyệt hôm nay
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-diagram-3-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $order['order_wait_today']['value'] }} đơn</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Khach hang moi Card -->

                        <!-- Reports -->
                        <div class="col-12">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Top nhân viên xuất sắc tháng</h5>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Tên nhân viên</th>
                                                <th scope="col">Doanh số</th>
                                                <th scope="col">Tỉ lệ</th>
                                                <th scope="col">Hiệu suất</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topCustomers as $item)
                                                <tr>
                                                    <th scope="row">{{ $item['user']['id'] }}</th>
                                                    <td>{{ $item['user']['name'] }}</td>
                                                    <td>{{ $item['total'] }}%</td>
                                                    <td>{{ $item['percent'] }}%</td>
                                                    <td>
                                                        @if ($item['grow'] == 'increase')
                                                            <span class="badge bg-success">Tăng</span>
                                                        @else
                                                            <span class="badge bg-danger">Giảm</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div><!-- End Reports -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">Yêu cầu đặt sân được cập nhật gần đây</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Mã code</th>
                                            <th scope="col">Tổng tiền</th>
                                            <th scope="col">Bởi</th>
                                            <th scope="col">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderRecentUpdated as $item)
                                            <tr>
                                                <th scope="row">{{ $item->id }}</th>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->total() }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <span class="badge bg-danger">Hủy</span>
                                                    @elseif ($item->status == 1)
                                                        <span class="badge bg-warning">Chờ Xác nhận</span>
                                                    @elseif ($item->status == 2)
                                                        <span class="badge bg-success">Đang hoạt động</span>
                                                    @else
                                                        <span class="badge bg-primary">Đã thanh toán</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->
                </div><!-- End Left side columns -->

                <!-- Right side columns -->
                <div class="col-lg-4">

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Lọc</h6>
                                </li>

                                <li><a class="dropdown-item" onclick="searchUrl('top_order_filter', 'today')"
                                        href="javascript:void(0)">Hôm nay</a></li>
                                <li><a class="dropdown-item" onclick="searchUrl('top_order_filter', 'this_month')"
                                        href="javascript:void(0)">Tháng này</a></li>
                                <li><a class="dropdown-item" onclick="searchUrl('top_order_filter', 'this_year')"
                                        href="javascript:void(0)">Năm này</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Sân được đặt nhiều
                                @if ($order['top_order']['filter'] == 'today')
                                    <span>| Hôm nay</span>
                                @elseif ($order['top_order']['filter'] == 'this_month')
                                    <span>| Tháng này</span>
                                @else
                                    <span>| Năm này</span>
                                @endif
                            </h5>

                            <div class="activity">

                                @foreach ($order['top_order']['value'] as $item)
                                    <div class="activity-item d-flex">
                                        <div class="activite-label">{{ $item['count'] }} Lần</div>
                                        <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                        <div class="activity-content">
                                            {{ $item->footballPitch->name }} |
                                            {{ $item->footballPitch->pricePerHour() }} -
                                            {{ $item->footballPitch->pricePerPeakHour() }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div><!-- End Recent Activity -->

                    <!-- News & Updates Traffic -->
                    {{-- <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

                            <div class="news">
                                <div class="post-item clearfix">
                                    <img src="assets/img/news-1.jpg" alt="">
                                    <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                                    <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/news-2.jpg" alt="">
                                    <h4><a href="#">Quidem autem et impedit</a></h4>
                                    <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/news-3.jpg" alt="">
                                    <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                                    <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/news-4.jpg" alt="">
                                    <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                                    <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="assets/img/news-5.jpg" alt="">
                                    <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                                    <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...
                                    </p>
                                </div>

                            </div><!-- End sidebar recent posts-->

                        </div>
                    </div><!-- End News & Updates --> --}}

                </div><!-- End Right side columns -->

            </div>
        </section>

    </main><!-- End #main -->
@endsection
<script>
    function searchUrl(key, value) {
        const url = new URL(location.href);
        if (url.searchParams.get(key)) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.append(key, value);
        }

        location.search = url.search;
    }
</script>
