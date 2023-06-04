<!-- Modal -->
<div class="modal fade" id="orderModalWhenFoundFootballPitch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('order.clientStore') }}">
            @csrf
            <input type="hidden" name="start_at">
            <input type="hidden" name="end_at">
            <input type="hidden" name="football_pitch_id">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="exampleModalLabel">Đặt sân</h5>
                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Họ và tên <span
                                        class="text-danger">(*)</span></label>
                                <div class="col-sm-8">
                                    <input placeholder="tên" required type="text" name="name"
                                        class="form-control"
                                        @if (Auth::check()) value="{{ auth()->user()->name }}" @endif
                                    >
                                    <div class="text-danger error-name alert-danger error error-hide"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Số điện thoại <span
                                        class="text-danger">(*)</span></label>
                                <div class="col-sm-8">
                                    <input placeholder="số điện thoại" required type="number" name="phone"
                                        class="form-control"
                                        @if (Auth::check()) value="{{ auth()->user()->phone }}" @endif
                                        >
                                    <div class="text-danger error-phone alert-danger error error-hide"></div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input placeholder="email" type="email" name="email" class="form-control"
                                    @if (Auth::check()) value="{{ auth()->user()->email }}" @endif
                                    >
                                    <div class="text-danger error-email alert-danger error error-hide"></div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="alert alert-danger alert-main error error-hide"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success btn-order">Đặt sân</button>
                </div>
            </div>
        </form>
    </div>
</div>
