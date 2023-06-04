{{-- Modal sua thong tin ngan hang --}}
<div class="modal fade" id="update-bank-info-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-light">Cập nhật thông tin ngân hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">
                            Tên đầy đủ chủ thẻ <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input required type="text" name="name" class="form-control fill-blank">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">
                            Số tài khoản <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input required type="text" name="bank_number" class="form-control fill-blank">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">
                            Tên ngân hàng <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input required type="text" name="bank" class="form-control fill-blank">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">
                            Ảnh (QR Code)
                        </label>
                        <div class="col-sm-8">
                            <input accept="image/*" type="file" name="image" class="form-control fill-blank">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">
                            Ghi chú
                        </label>
                        <div class="col-sm-8">
                            <input type="text" name="note" class="form-control fill-blank">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="text-light btn-update-bank-info btn btn-warning">Câp nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>