 {{-- modal thêm --}}
 <div class="modal fade" id="add-football-pitch-type-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('footballPitch.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title text-light">Thêm sân bóng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Tên sân <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input required type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-6 col-form-label">
                            Thời gian mở <span class="text-danger">*</span>
                            <select name="time_start" class="form-select" aria-label="Default select example">
                                <option value="1:00:00" selected>1:00:00</option>
                                <script>
                                    for (i = 2; i <= 24; i++) {
                                        document.write('<option value="' + i + ':00:00">' + i + ':00:00</option>');
                                    }
                                </script>
                            </select>
                        </label>
                        <label for="inputEmail" class="col-sm-6 col-form-label">
                            Thời gian đóng <span class="text-danger">*</span>
                            <select name="time_end" class="form-select" aria-label="Default select example">
                                <option value="1:00:00" selected>1:00:00</option>
                                <script>
                                    for (i = 2; i <= 24; i++) {
                                        document.write('<option value="' + i + ':00:00">' + i + ':00:00</option>');
                                    }
                                </script>
                            </select>
                        </label>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Loại sân <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <select name="pitch_type_id" class="form-select" aria-label="Default select example">
                                @foreach ($pitchTypes as $item)
                                    <option value="{{ $item->id }}"
                                        @if ($loop->first) selected @endif>{{ $item->quantity }}
                                        ({{ $item->description }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Giá theo giờ <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input required value="500000" type="number" name="price_per_hour"
                                class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Giá theo giờ cao điểm <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-8">
                            <input required value="600000" type="number" name="price_per_peak_hour"
                                class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">
                            Mô tả
                        </label>
                        <div class="col-sm-8">
                            <textarea type="text" name="description" class="form-control"></textarea>
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
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                            (#{{ $item['id'] }})</option>
                                    @endforeach
                                </select>
                            </label>
                            <label for="inputText" class="col-sm-6 col-form-label">
                                Với sân
                                <select name="to_football_pitch_id" class="form-select"
                                    aria-label="Default select example">
                                    <option value="" selected></option>
                                    @foreach ($footballPitches as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                            (#{{ $item['id'] }})</option>
                                    @endforeach
                                </select>
                            </label>
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