@extends('client.extend')
@section('client_content')
    <main id="main">
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                {{ Breadcrumbs::render('client-profile') }}
                <h2>Thông tin cá nhân</h2>
            </div>
        </section><!-- End Breadcrumbs -->
        <section class="container section profile">
            <div class="row justify-content-center">
                @include('client.layouts.alert')
                <div class="row">
                    {{-- Thông tin (Tổng quan, hình ảnh, chỉnh sửa sân) --}}
                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Các tab -->
                            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview"
                                        aria-selected="true" role="tab">Thông tin</button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit"
                                        aria-selected="false" role="tab" tabindex="-1">Chỉnh sửa thông tin</button>
                                </li>

                                @if (!auth()->user()->provider_id)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"
                                        aria-selected="false" role="tab" tabindex="-1">Đổi mật khẩu</button>
                                </li>
                                @endif
                            </ul>
                            {{-- thông tin tab tổng quan --}}
                            <div class="tab-content pt-2 profile">

                                <div class="tab-pane fade profile-overview active show" id="profile-overview"
                                    role="tabpanel">

                                    <h5 class="card-title">Thông tin</h5>

                                    <table class="table table centered">
                                        <tbody>
                                            <tr></tr>
                                            <tr>
                                                <td class="label">Họ và tên</td>
                                                <td>{{ $user->name }}</td>
                                            </tr>

                                            <tr>
                                                <td class="label">Số điện thoại</td>
                                                <td>{{ $user->phone }}</td>
                                            </tr>

                                            <tr>
                                                <td class="label">Địa chỉ email</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>

                                            <tr>
                                                <td class="label">Địa chỉ</td>
                                                <td>{{ $user->address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                                {{-- thông tin tab chỉnh sửa --}}
                                <div class="tab-pane fade pt-3" id="profile-edit" role="tabpanel">
                                    <form action="{{ route('user.update', $user->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Họ và tên <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="text" name="name" class="form-control"
                                                    value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Số điện thoại <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="number" name="phone" class="form-control"
                                                    value="{{ $user->phone }}">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Địa chỉ email <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="email" name="email" class="form-control"
                                                    value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Địa chỉ <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="text" name="address" class="form-control"
                                                    value="{{ $user->address }}">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success">Cập nhật</button>
                                        </div>
                                    </form>

                                </div>
                                @if (!auth()->user()->provider_id)
                                {{-- thông tin tab doi mat khau --}}
                                <div class="tab-pane fade pt-3" id="profile-change-password" role="tabpanel">
                                    <form action="{{ route('user.changePassword', $user->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Mật khẩu cũ <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="password" name="old_password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Mật khẩu mới <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="password" name="new_password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="inputText" class="col-sm-4 col-form-label">
                                                Nhập lại mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input required type="password" name="confirm_password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn-change-password btn btn-success">Đổi mật khẩu</button>
                                        </div>
                                    </form>

                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main><!-- End #main -->
    <script>
        document.querySelector('#header').classList.add('header-inner-pages');
    </script>
@endsection
