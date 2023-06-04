@extends('admin.extend')
@section('admin_content')
    <main id="main" class="main">
        {{-- Tiêu đề --}}
        <div class="pagetitle">
            <h1>Quản lý loại sân</h1>
        </div>
        {{ Breadcrumbs::render('pitchType') }}
        @include('admin.layouts.alert')
        {{-- Body --}}
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Loại Sân</h5>
                    @can('checkSuperAdmin', auth()->user())
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" 
                        data-bs-target="#add-pitch-type-modal">Thêm thể loại
                    </button>
                    @endcan
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Số người</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Tạo lúc</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pitchTypes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
                                    @can('checkSuperAdmin', auth()->user())
                                    <td>
                                        <button data-url_set="{{ route('pitchType.update', $item->id) }}" data-url_get="{{ route('pitchType.show', $item->id) }}" type="button" class="btn-update-pitch-type btn btn-warning" 
                                            data-bs-toggle="modal" data-bs-target="#update-pitch-type-modal">Sửa
                                        </button>
                                    </td>
                                    <td>
                                        <form action="{{ route('pitchType.destroy', $item->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="confirm-btn btn btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        {{-- modal thêm --}}
        <div class="modal fade" id="add-pitch-type-modal" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('pitchType.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title text-light">Thêm loại sân bóng</h5> 
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">
                                    <font style="vertical-align: inherit;">Số người</font>
                                </label>
                                <div class="col-sm-10">
                                <input max="127" required type="number" name="quantity" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">
                                    <font style="vertical-align: inherit;">Mô tả</font>
                                </label>
                                <div class="col-sm-10">
                                <textarea type="text" name="description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button> 
                            <button type="submit" class="btn btn-success">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Modal sửa --}}
        <div class="modal fade" id="update-pitch-type-modal" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        @csrf
                        @method('put')
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Cập nhật loại sân bóng</h5> 
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">
                                    <font style="vertical-align: inherit;">Số người</font>
                                </label>
                                <div class="col-sm-10">
                                <input max="127" required type="number" name="quantity" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">
                                    <font style="vertical-align: inherit;">Mô tả</font>
                                </label>
                                <div class="col-sm-10">
                                <textarea type="text" name="description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button> 
                            <button type="submit" class="btn btn-warning">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection