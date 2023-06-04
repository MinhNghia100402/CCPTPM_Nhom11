@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">
        {{-- Tiêu đề --}}
        <div class="pagetitle">
            <h1>Chi tiết sân bóng</h1>
        </div>
        {{ Breadcrumbs::render('footballPitchDetail') }}
        @include('admin.layouts.alert')
        {{-- Body --}}
        <section class="section">
            <div class="card">
                <div class="card-body">
                    {{-- Tiêu đề --}}
                    @if ($footballPitch['is_maintenance'])
                        <h5 class="card-title text-danger">Thông tin - {{ $footballPitch->name }} -  Đang bảo trì</h5>
                    @else
                        <h5 class="card-title text-success">Thông tin - {{ $footballPitch->name }} -  Đang hoạt động</h5>
                    @endif
                    <div class="row">
                        {{-- Ảnh sân bóng --}}
                        <div class="col-md-6 mb-3">
                            <div id="carouselFootballPitch" class="carousel slide" data-bs-ride="carousel">
                                @if ($footballPitchDetails->count() > 0)
                                    <div class="carousel-indicators">
                                        @foreach ($footballPitchDetails as $item)
                                            <button type="button" data-bs-target="#carouselFootballPitch"
                                                data-bs-slide-to="{{ $loop->index }}"
                                                class="@if ($loop->first) active @endif"
                                                aria-label="Slide {{ $loop->index + 1 }}"
                                                aria-current="@if ($loop->first) true @endif">
                                            </button>
                                        @endforeach
                                    </div>
                                    <div class="carousel-inner">
                                        @foreach ($footballPitchDetails as $item)
                                            <div class="carousel-item @if ($loop->first) active @endif">
                                                <img src="{{ config('app.image') . $item->image }}" class="d-block w-100"
                                                    alt="...">
                                            </div>
                                        @endforeach
                                    </div>

                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselFootballPitch" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselFootballPitch" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @else
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="{{ config('app.image') . 'images/football_pitches/default_football_pitch.png' }}"
                                                class="d-block w-100" alt="...">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- Thông tin (Tổng quan, hình ảnh, chỉnh sửa sân) --}}
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body pt-3">
                                    <!-- Các tab -->
                                    <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#football-pitch-overview" aria-selected="true"
                                                role="tab">Tổng quan</button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#football-pitch-images" aria-selected="false" role="tab"
                                                tabindex="-1">Hình ảnh</button>
                                        </li>

                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#football-pitch-edit" aria-selected="false" role="tab"
                                                tabindex="-1">Chỉnh sửa sân bóng</button>
                                        </li>

                                    </ul>
                                    {{-- thông tin tab tổng quan --}}
                                    <div class="tab-content pt-2 profile">

                                        <div class="tab-pane fade profile-overview active show" id="football-pitch-overview"
                                            role="tabpanel">
                                            <h5 class="card-title">Mô tả</h5>
                                            <p class="small fst-italic">{{ $footballPitch->description }}</p>

                                            <h5 class="card-title">Thông tin</h5>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-4 label">Thời gian bắt đầu - kết thúc</div>
                                                <div class="col-lg-6 col-md-8">{{ $footballPitch->timeStart() }} -
                                                    {{ $footballPitch->timeEnd() }}</div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-4 label">Số người</div>
                                                <div class="col-lg-6 col-md-8">{{ $footballPitch->pitchType->quantity }}</div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-4 label">Giá / giờ</div>
                                                <div class="col-lg-6 col-md-8">{{ $footballPitch->pricePerHour() }}</div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-4 label">Giá / giờ cao điểm</div>
                                                <div class="col-lg-6 col-md-8">{{ $footballPitch->pricePerPeakHour() }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-4 label">Sân liên kết</div>
                                                <div class="col-lg-6 col-md-8">{{ $footballPitch->nameFootBallPitchLink() }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-4 label">Tạo lúc</div>
                                                <div class="col-lg-6 col-md-8">{{ $footballPitch->createdAt() }}</div>
                                            </div>

                                        </div>
                                        {{-- thông tin tab hình ảnh --}}
                                        <div class="tab-pane fade profile-edit pt-3" id="football-pitch-images"
                                            role="tabpanel">

                                            <button class="btn btn-success mr-3" data-bs-toggle="modal"
                                                data-bs-target="#add-football-pitch-detail-modal">
                                                Thêm ảnh
                                            </button>
                                            <table class="table">
                                                <tbody>
                                                    @foreach ($footballPitchDetails as $item)
                                                        <tr>
                                                            <td><img class="img-set-wh" src="{{ config('app.image') . $item->image }}"
                                                                    width="100" height="70"  alt="..."></td>
                                                            <td>Thêm lúc: {{ $item->createdAt() }}</td>
                                                            <td>
                                                                <form
                                                                    action="{{ route('footballPitchDetail.destroy', $item->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button class="confirm-btn btn btn-danger">Xóa</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                        {{-- thông tin tab chỉnh sửa --}}
                                        <div class="tab-pane fade pt-3" id="football-pitch-edit" role="tabpanel">
                                            <form action="{{ route('footballPitch.update', $footballPitch->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row mb-3">
                                                    <label for="inputText" class="col-sm-4 col-form-label">
                                                        Tên sân <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input required type="text" name="name"
                                                            class="form-control" value="{{ $footballPitch->name }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputEmail" class="col-sm-6 col-form-label">
                                                        Thời gian mở <span class="text-danger">*</span>
                                                        <select name="time_start" class="form-select"
                                                            aria-label="Default select example">
                                                            @for ($i = 1; $i <= 24; $i++)
                                                                @if (explode(':', $footballPitch->time_start)[0] == $i)
                                                                    <option selected value="{{ $i }}:00:00">
                                                                        {{ $i }}:00:00</option>
                                                                @else
                                                                    <option value="{{ $i }}:00:00">
                                                                        {{ $i }}:00:00</option>
                                                                @endif
                                                            @endfor
                                                        </select>
                                                    </label>
                                                    <label for="inputEmail" class="col-sm-6 col-form-label">
                                                        Thời gian đóng <span class="text-danger">*</span>
                                                        <select name="time_end" class="form-select"
                                                            aria-label="Default select example">
                                                            @for ($i = 1; $i <= 24; $i++)
                                                                @if (explode(':', $footballPitch->time_end)[0] == $i)
                                                                    <option selected value="{{ $i }}:00:00">
                                                                        {{ $i }}:00:00</option>
                                                                @else
                                                                    <option value="{{ $i }}:00:00">
                                                                        {{ $i }}:00:00</option>
                                                                @endif
                                                            @endfor
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputText" class="col-sm-4 col-form-label">
                                                        Loại sân <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <select name="pitch_type_id" class="form-select"
                                                            aria-label="Default select example">
                                                            @foreach ($pitchTypes as $item)
                                                                <option value="{{ $item->id }}"
                                                                    @if ($footballPitch->pitch_type_id == $item->id) selected @endif>
                                                                    {{ $item->quantity }} ({{ $item->description }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputText" class="col-sm-4 col-form-label">
                                                        Giá theo giờ <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input required value="{{ $footballPitch->price_per_hour }}"
                                                            type="number" name="price_per_hour" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputText" class="col-sm-4 col-form-label">
                                                        Giá theo giờ cao điểm <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input required
                                                            value="{{ $footballPitch->price_per_peak_hour }}"
                                                            type="number" name="price_per_peak_hour"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputText" class="col-sm-4 col-form-label">
                                                        Mô tả
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text" name="description" class="form-control">{{ $footballPitch->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputText" class="col-sm-12 col-form-label">
                                                        Nếu sân là sân ghép thì điền - vd: sân 11 gép từ 2 sân 7
                                                    </label>
                                                    <div class="row bm-3">
                                                        <label for="inputText" class="col-sm-6 col-form-label">
                                                            Gép từ sân
                                                            <select name="from_football_pitch_id" class="form-select"
                                                                aria-label="Default select example">
                                                                <option value="" selected></option>
                                                                @foreach ($footballPitches as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if ($item->id == $footballPitch->from_football_pitch_id) selected @endif>
                                                                        {{ $item->name }} (#{{ $item->id }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                        <label for="inputText" class="col-sm-6 col-form-label">
                                                            Với sân
                                                            <select name="to_football_pitch_id" class="form-select"
                                                                aria-label="Default select example">
                                                                <option value="" selected></option>
                                                                @foreach ($footballPitches as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if ($item->id == $footballPitch->to_football_pitch_id) selected @endif>
                                                                        {{ $item->name }} (#{{ $item->id }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                                                </div>
                                            </form>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('admin.modal.football_pitch.createImage')
    </main>
@endsection
