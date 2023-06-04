@extends('admin.master')
@section('content')
<main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">Quản lý sân bóng</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Đăng nhập với tư cách quản trị viên</h5>
                    <p class="text-center small">Nhập địa chỉ email và mật khẩu để đăng nhập</p>
                  </div>

                  <form class="row g-3 needs-validation" action="{{ route('admin.processLogin') }}" method="post">
                    @csrf
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" value="{{ old('email') }}" name="email" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Địa chỉ email</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Mật khẩu</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Mật khẩu</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Ghi nhớ đăng nhập</label>
                      </div>
                    </div>
                    @if (session()->has('message'))
                      <div class="alert alert-danger">
                        {{ session()->get('message') }}
                      </div>
                    @endif
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by TrinhXuanSon
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>
@endsection