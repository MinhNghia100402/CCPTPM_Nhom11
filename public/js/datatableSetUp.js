//datatable san bong
$(document).ready(function () {
    const ROLE = $('meta[name="rl"]').attr("content");
    const URL_IMAGE = $('meta[name="url_image"]').attr("content");
    $("#table_football_pitch").DataTable({
        ajax: BASE_URL_API.getFootballPitch,
        columns: [
            {
                data: "id",
            },
            {
                data: "name",
            },
            {
                data: "pitch_type.quantity",
            },
            {
                data: "time_start",
            },
            {
                data: "time_end",
            },
            {
                data: "price_per_hour",
            },
            {
                data: "price_per_peak_hour",
            },
            {
                data: "is_maintenance",
                render: function (data, type, row) {
                    if (!data) {
                        return `<div class="text-success">Hoạt động</div>`;
                    }
                    return `<div class="text-danger">Bảo trì</div>`;
                },
            },
            {
                data: "id",
                render: function (data, type, row) {
                    const btn_xem = `<a href="${location.origin}/admin/footballPitchDetail/${data}" class="btn btn-info"><i class="bi bi-eye-fill"></i></a>`;
                    return btn_xem;
                },
                orderable: false,
            },
            {
                data: "id",
                render: function (data, type, row) {
                    if (ROLE != 2) return "";
                    const value = row.is_maintenance ? 0 : 1;
                    const btn_bao_tri = row.is_maintenance
                        ? `<button type="button" data-value="${value}" data-id="${data}" class="btn btn-warning btn-bao-tri"><i class="bi bi-toggle-off"></i></button>`
                        : `<button type="button" data-value="${value}" data-id="${data}" class="btn btn-success btn-bao-tri"><i class="bi bi-toggle-on"></i></button>`;
                    return btn_bao_tri;
                },
                orderable: false,
            },
            {
                data: "id",
                render: function (data, type, row) {
                    if (ROLE != 2) return "";
                    const btn_delete = `<button data-id="${data}" class="btn-delete-football-pitch btn btn-danger"><i class="bi bi-trash-fill"></i></button>`;
                    return btn_delete;
                },
                orderable: false,
            },
        ],
    });
    //table yeu cau
    $("#table_order").DataTable({
        ajax: BASE_URL_API.getAllOrder,
        columns: [
            {
                data: "id",
            },
            {
                data: "football_pitch.name",
            },
            {
                data: "start_at",
            },
            {
                data: "end_at",
            },
            {
                data: "deposit",
            },
            {
                data: "total",
            },
            {
                data: "code",
                orderable: false,
            },
            {
                data: "status",
                render: function (data, type, row) {
                    switch (data) {
                        case 0:
                            return `<div class="text-danger">Hủy</div>`;
                        case 1:
                            return `<div class="text-warning">Chờ</div>`;
                        case 2:
                            return `<div class="text-running">Đang đá</div>`;
                        case 3:
                            return `<div class="text-success">Đã thanh toán</div>`;
                    }
                },
            },
            {
                data: "id",
                render: function (data, type, row) {
                    const btn_xem = `<button onCLick="showModal(${data})" class="btn btn-info"><i class="bi bi-eye-fill"></i></button>`;
                    return btn_xem;
                },
                orderable: false,
            },
            {
                data: "id",
                render: function (data, type, row) {
                    if (ROLE != 2) return "";
                    const btn = `<button data-id="${data}" class="btn btn-danger btn-delete-order"><i class="bi bi-trash-fill"></i></button>`;
                    return btn;
                },
                orderable: false,
            },
        ],
    });
    //table thong tin ngan hang
    $("#table_bank_info").DataTable({
        ajax: BASE_URL_API.getBankInformation,
        columns: [
            {
                data: "id",
            },
            {
                data: "image",
                render: function (data, type, row) {
                    if (!data) {
                        return ``;
                    }
                    const btn = `<img width=70 src="${
                        URL_IMAGE + data
                    }" alt="photo">`;
                    return btn;
                },
                orderable: false,
            },
            {
                data: "name",
            },
            {
                data: "bank_number",
                orderable: false,
            },
            {
                data: "bank",
                orderable: false,
            },
            {
                data: "note",
                orderable: false,
            },
            {
                data: "isShow",
                render: function (data, type, row) {
                    const checked = data ? "checked" : "";
                    const btn = `<input data-id="${row.id}" value="${data}" class="form-check-input" type="checkbox" ${checked}>`;
                    return btn;
                },
                orderable: false,
            },
            {
                data: "id",
                render: function (data, type, row) {
                    const btn = `<button data-id="${data}" class="btn-edit-bank-info btn btn-warning" data-bs-toggle="modal"
                    data-bs-target="#update-bank-info-modal"><i class="bi bi-pencil-square"></i></button>`;
                    return btn;
                },
                orderable: false,
            },
            {
                data: "id",
                render: function (data, type, row) {
                    const btn = `<button data-id="${data}" class="btn btn-danger btn-delete-bank-info"><i class="bi bi-trash-fill"></i></button>`;
                    return btn;
                },
                orderable: false,
            },
        ],
    });
    //table yeu cau chua thanh toan
    const table_order_unpaid = $("#table_order_unpaid");
    table_order_unpaid.DataTable({
        ajax: table_order_unpaid[0].dataset.url,
        columns: [
            {
                data: "id",
            },
            {
                data: "football_pitch.name",
            },
            {
                data: "start_at",
            },
            {
                data: "end_at",
            },
            {
                data: "deposit",
            },
            {
                data: "total",
            },
            {
                data: "code",
                orderable: false,
            },
            {
                data: "status",
                render: function (data, type, row) {
                    switch (data) {
                        case 0:
                            return `<div class="text-danger">Hủy</div>`;
                        case 1:
                            return `<div class="text-warning">Chờ</div>`;
                        case 2:
                            return `<div class="text-running">Đang đá</div>`;
                        case 3:
                            return `<div class="text-success">Đã thanh toán</div>`;
                    }
                },
            },
            {
                data: "id",
                render: function (data, type, row) {
                    const btn_xem = `<button onCLick="showModal(${data})" class="btn btn-info"><i class="bi bi-eye-fill"></i></button>`;
                    return btn_xem;
                },
                orderable: false,
            },
            {
                data: "id",
                render: function (data, type, row) {
                    if (ROLE != 2) return "";
                    const btn = `<button data-id="${data}" class="btn btn-danger btn-delete-bank-info"><i class="bi bi-trash-fill"></i></button>`;
                    return btn;
                },
                orderable: false,
            },
        ],
    });
});
function showModal(id) {
    $.ajax({
        type: "get",
        url: BASE_URL_API.showOrder + id,
        success: function (response) {
            $("#update-order-modal input[name='name']").val(
                response.data.name
            );
            $("#update-order-modal input[name='email']").val(
                response.data.email
            );
            $("#update-order-modal input[name='phone']").val(
                response.data.phone
            );
            $("#update-order-modal input[name='deposit']").val(
                response.data.deposit
            );
            $("#update-order-modal textarea[name='note']").html(
                response.data.note
            );
            let btn = $("#update-order-modal #checkout")[0];
            let href = btn.href;
            let arr = href.split('/');
            arr[arr.length - 1] = id;
            href = arr.toString().replaceAll(',', '/');
            btn.href = href;
            //console.log(response);
        },
    });
    $("#update-order-modal form")[0].dataset.id = id;
    $("#update-order-modal").modal("show");
}