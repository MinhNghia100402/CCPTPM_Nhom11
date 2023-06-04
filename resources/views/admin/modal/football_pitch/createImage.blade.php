{{-- Modal --}}
<div class="modal fade" id="add-football-pitch-detail-modal" tabindex="-1" style="display: none;"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('footballPitchDetail.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-success text-light">
                <h5 class="modal-title text-light">Thêm ảnh cho sân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-3 col-form-label">
                        Tải ảnh lên
                    </label>
                    <div class="col-sm-9">
                        <input type="hidden" name="football_pitch_id" value="{{ $footballPitch->id }}">
                        <input required type="file" name="image" class="form-control">
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