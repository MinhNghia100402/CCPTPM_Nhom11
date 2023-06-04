<!-- Modal -->
<div class="modal fade" id="findTimeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('order.findTimeAvailable') }}">
            <input type="hidden" name="football_pitch_id" value="{{ $footballPitch->id }}">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Tìm thời gian đã đặt</h5>
                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputDate" class="col-sm-4 col-form-label">Chọn ngày</label>
                        <div class="col-sm-8">
                            <input name="date" type="date" class="datepicker form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div>Thời gian sân đã đặt</div>
                        <div class="row mt-2 order-time text-center">
                        </div>
                        <div class="alert alert-danger error error-hide"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success btn-find-time">Kiểm tra</button>
                </div>
            </div>
        </form>
    </div>
</div>
