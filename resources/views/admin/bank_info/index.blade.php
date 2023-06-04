@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">
        {{-- Tiêu đề --}}
        <div class="pagetitle">
            <h1>Quản lý thông tin chuyển khoản</h1>
        </div>
        {{ Breadcrumbs::render('bankInformation') }}
        {{-- @include('admin.layouts.alert') --}}
        {{-- Body --}}
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Thông tin ngân hàng</h5>
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                        data-bs-target="#add-bank-info-modal">Thêm tài khoản ngân hàng
                    </button>
                    <table id="table_bank_info" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Chủ tài khoản</th>
                                <th>Số tài khoản</th>
                                <th>Tên ngân hàng</th>
                                <th>Ghi chú</th>
                                <th>Hiển thị</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- end card body-->
            </div>
            </div>
            @include('admin.modal.bank_info.create')
            @include('admin.modal.bank_info.update')
        </section>
    </main>
@endsection
