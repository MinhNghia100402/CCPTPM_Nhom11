<!-- Modal -->
<div class="modal fade" id="findFootballPitchAvailableModal" tabindex="-1" aria-labelledby="findFootballPitchAvailableModal" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('order.findFootballPitchNotInOrderByDateTime') }}">
            @csrf
            <input type="hidden" name="start_at">
            <input type="hidden" name="end_at">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title">Tìm sân trống</h5>
                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputDate" class="col-sm-4 col-form-label">Chọn ngày</label>
                        <div class="col-sm-8">
                            <input type="date" class="datepicker form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputTime" class="col-sm-4 col-form-label">Chọn giờ bắt đầu - kết
                            thúc</label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col mb-3">
                                    <input type="time" class="timepicker form-control">
                                </div>
                                <div class="col">
                                    <input type="time" class="timepicker form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div>Kết quả</div>
                        <div class="mt-2 order-time">
                        </div>
                        <div class="alert alert-danger error error-hide"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success btn-find-football-pitch">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>
</div>
