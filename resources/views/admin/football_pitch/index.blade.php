@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">
        {{-- Tiêu đề --}}
        <div class="pagetitle">
            <h1>Quản lý sân bóng</h1>
        </div>
        {{ Breadcrumbs::render('footballPitch') }}
        @include('admin.layouts.alert')
        {{-- Body --}}
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sân bóng</h5>
                    @can('checkSuperAdmin', auth()->user())
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                        data-bs-target="#add-football-pitch-type-modal">Thêm sân bóng
                    </button>
                    @endcan
                    <table id="table_football_pitch" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Số người</th>
                                <th>Bắt đầu lúc</th>
                                <th>Kết thúc lúc</th>
                                <th>Giá/giờ</th>
                                <th>Giá/giờ cao điểm</th>
                                <th>Tình trạng</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
       @include('admin.modal.football_pitch.create')
    </main>
@endsection
