{{-- Modal --}}
<div class="modal fade" id="info-employe-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-light">
                <h5 class="modal-title text-light">Thôn tin nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-tabs-bordered d-flex" role="tablist">
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100 active" id="employe-tab1" data-bs-toggle="tab"
                            data-bs-target="#bordered-justified-employe-tab1" type="button" role="tab"
                            aria-controls="employe-tab1" aria-selected="true">Giấy tờ tùy thân</button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100" id="employe-tab2" data-bs-toggle="tab"
                            data-bs-target="#bordered-justified-employe-tab2" type="button" role="tab"
                            aria-controls="employe-tab2" aria-selected="false" tabindex="-1">Sửa thông tin</button>
                    </li>
                </ul>
                <div class="tab-content pt-2">
                    <div class="tab-pane fade active show" id="bordered-justified-employe-tab1" role="tabpanel"
                        aria-labelledby="employe-tab1">
                        <form action="{{ route('identity_paper.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 mt-2">
                                    <input type="text" placeholder="Tên giấy tờ" name="name"
                                        class="form-control fill-blank">
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input accept="image/*" type="file" name="image"
                                        class="form-control fill-blank">
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button type="button"
                                        class="btn-add-identity-paper btn btn-success btn-block">Thêm</button>
                                </div>
                            </div>
                        </form>
                        <div class="row" id="bordered-justified-employe-tab1-content">
                            {{-- <div class="card col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title text-center">CCCD</h5>
                                    <img src="http://127.0.0.1:8000/storage/images/football_pitches/sb1.jpg"
                                        class="card-img-bottom" alt="...">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bordered-justified-employe-tab2" role="tabpanel"
                        aria-labelledby="employe-tab2">
                        <form action="" method="post">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">
                                    Tên nhân viên
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control">
                                    <div class="text-danger error error-name"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">
                                    Địa chỉ email
                                </label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control">
                                    <div class="text-danger error error-email"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">
                                    Số điện thoại
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" name="phone" class="form-control">
                                    <div class="text-danger error error-phone"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-3 col-form-label">
                                    Địa chỉ
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="address" class="form-control">
                                    <div class="text-danger error error-address"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 mt-2">
                                    <form action="" method="post">
                                        @csrf
                                        @method('put')
                                        <button class="btn-update-employe btn btn-success btn-block"
                                            type="button">Cập nhật thông tin</button>
                                    </form>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <button class="text-light btn btn-info btn-block" type="button">Reset mật
                                        khẩu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        const info_employe_modal = $('#info-employe-modal');
        const tab1_content = $('#bordered-justified-employe-tab1-content');
        const tab2_content = $('#bordered-justified-employe-tab2');
        tab1_content.html('');
        info_employe_modal.on("click", ".btn-add-identity-paper", function() {
            const form = $(this).parents("form");
            const formdata = new FormData(form[0]);
            formdata.append('user_id', info_employe_modal.data('user_id'));
            $.ajax({
                type: "post",
                url: form.attr('action'),
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    form.find('input[name="image"]').val('');
                    form.find('input[name="name"]').val('');
                    fetchIdentityPapers(info_employe_modal[0].dataset.user_id);
                    $.toast({
                        heading: "Thành công",
                        text: response.message,
                        showHideTransition: "plain",
                        icon: 'success',
                        position: "bottom-right",
                    });
                },
                error: function(response) {
                    response = response.responseJSON;
                    $.toast({
                        heading: "Thất bại",
                        text: response.message,
                        showHideTransition: "plain",
                        icon: 'error',
                        position: "bottom-right",
                    });
                },
            });
        });

        function showUser(id) {
            const url = "{{ route('user.showEmploye') }}/" + id;

            $.ajax({
                type: "get",
                url: url,
                success: function(response) {
                    response = response.data;
                    const form = tab2_content.find('form');
                    form.find('input[name="name"]').val(response.name);
                    form.find('input[name="email"]').val(response.email);
                    form.find('input[name="phone"]').val(response.phone);
                    form.find('input[name="address"]').val(response.address);
                }
            });
        }

        tab2_content.on('click', '.btn-update-employe', function() {
            const form = $(this).parents("form");
            $.ajax({
                type: "post",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    info_employe_modal.modal('hide');
                    table_employe.DataTable().ajax.reload();
                    $.toast({
                            heading: "Thành công",
                            text: response.message,
                            showHideTransition: "plain",
                            icon: 'success',
                            position: "bottom-right",
                        });
                },
                error: function(response) {
                    setError(tab2_content.find('.error-name'), response.responseJSON.errors.name);
                    setError(tab2_content.find('.error-email'), response.responseJSON.errors
                        .email);
                    setError(tab2_content.find('.error-phone'), response.responseJSON.errors
                        .phone);
                    setError(tab2_content.find('.error-address'), response.responseJSON.errors
                        .address);
                },
            });
        })
    </script>
@endpush
