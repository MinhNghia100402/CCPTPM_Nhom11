{{-- Modal sửa yêu cầu --}}
<div class="modal fade" id="update-order-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form data-id="0" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="update_info">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Cập nhật yêu cầu</h5> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Họ tên người đặt <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                        <input required type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Số điện thoại <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                        <input required type="number" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Email
                        </label>
                        <div class="col-sm-8">
                        <input type="text" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Tiền cọc <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                        <input required type="number" name="deposit" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Ghi chú
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="note" id="" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"> 
                    <a id="checkout" class="text-light btn btn-info" href="{{ route('admin.checkout', 'id') }}">Thanh toán</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button> 
                    <button type="button" class="btn-update-order btn btn-warning text-light">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>