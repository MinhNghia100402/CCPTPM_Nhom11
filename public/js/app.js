const DATATABLE_BANK_INFO = $("#table_bank_info");
const DATATABLE_ORDER_UNPAID = $("#table_order_unpaid");
const DATATABLE_ORDER = $("#table_order");
$(document).ready(function () {
    $(document).on("click", ".confirm-btn", function (e) {
        const result = confirm("Bạn có chắc chắn không ?");
        if (!result) {
            e.preventDefault();
        }
    });
    //hien thi modal cap nhat the loai san bong
    $(document).on("click", ".btn-update-pitch-type", function () {
        getPitchType($(this).data("url_get"));
        $("#update-pitch-type-modal form").attr(
            "action",
            $(this).data("url_set")
        );
    });

    //bao tri san bong
    $(document).on("click", ".btn-bao-tri", function () {
        $.ajax({
            type: "post",
            url: BASE_URL_API.maintainFootballPitch + $(this)[0].dataset.id,
            data: {
                is_maintenance: $(this)[0].dataset.value,
                _token: CSRF_TOKEN,
                _method: "PUT",
            },
            dataType: "json",
            success: function (response) {
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
                $("#table_football_pitch").DataTable().ajax.reload();
            },
            error: function (response) {
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //xoa san bong
    $(document).on("click", ".btn-delete-football-pitch", function (e) {
        const result = confirm("Bạn có chắc chắn muốn xóa sân bóng ?");
        if (!result) {
            e.preventDefault();
            return;
        }
        $.ajax({
            type: "post",
            url: BASE_URL_API.deleteFootballPitch + $(this)[0].dataset.id,
            data: {
                _token: CSRF_TOKEN,
                _method: "DELETE",
            },
            dataType: "json",
            success: function (response) {
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
                $("#table_football_pitch").DataTable().ajax.reload();
            },
            error: function (response) {
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //them thong tin ngan hang
    $(document).on("click", ".btn-add-bank-info", function () {
        const form = $(this).parents("form");
        const formdata = new FormData(form[0]);
        $.ajax({
            type: "post",
            url: form.attr("action"),
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                DATATABLE_BANK_INFO.DataTable().ajax.reload();
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
                $("#add-bank-info-modal").modal("hide");
                clearInputModal("#add-bank-info-modal");
            },
            error: function (response) {
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //cap nhat thong tin ngan hang
    $(document).on("click", ".btn-update-bank-info", function () {
        const form = $(this).parents("form");
        const elModal = "#update-bank-info-modal";
        const formdata = new FormData(form[0]);
        $.ajax({
            type: "post",
            url: BASE_URL_API.putBankInformation + $(this)[0].dataset.id,
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                DATATABLE_BANK_INFO.DataTable().ajax.reload();
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
                $(elModal).modal("hide");
            },
            error: function (response) {
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //hien thi 1 thong tin ngan hang
    $(document).on("click", ".btn-edit-bank-info", function () {
        const elModal = "#update-bank-info-modal";
        clearInputModal(elModal);
        document.querySelector(".btn-update-bank-info").dataset.id =
            $(this)[0].dataset.id;
        $.ajax({
            type: "get",
            url: BASE_URL_API.showBankInformation + $(this)[0].dataset.id,
            dataType: "json",
            success: function (response) {
                $(elModal + ' input[name="name"]').val(response.name);
                $(elModal + ' input[name="bank_number"]').val(
                    response.bank_number
                );
                $(elModal + ' input[name="bank"]').val(response.bank);
                $(elModal + ' input[name="note"]').val(response.note);
            },
            error: function (response) {
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //cap nhat hien thi san
    $(document).on("change", ".form-check-input", function () {
        const checkbox = $(this);
        const val = this.checked ? 1 : 0;
        $.ajax({
            type: "post",
            url:
                BASE_URL_API.changeDisplayBankInformation + checkbox.data("id"),
            data: {
                isShow: val,
                _token: CSRF_TOKEN,
                _method: "PUT",
            },
            dataType: "json",
            success: function (response) {
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
            error: function (response) {
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //xoa thong tin ngan hang
    $(document).on("click", ".btn-delete-bank-info", function (e) {
        const result = confirm("Bạn có chắc chắn muốn xóa ?");
        if (!result) {
            e.preventDefault();
            return;
        }
        $.ajax({
            type: "post",
            url: BASE_URL_API.deleteBankInformation + $(this)[0].dataset.id,
            data: {
                _token: CSRF_TOKEN,
                _method: "DELETE",
            },
            dataType: "json",
            success: function (response) {
                DATATABLE_BANK_INFO.DataTable().ajax.reload();
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //xoa yeu cau
    $(document).on("click", ".btn-delete-order", function (e) {
        const result = confirm("Bạn có chắc chắn muốn xóa ?");
        if (!result) {
            e.preventDefault();
            return;
        }
        $.ajax({
            type: "post",
            url: BASE_URL_API.deleteOrder + $(this)[0].dataset.id,
            data: {
                _token: CSRF_TOKEN,
                _method: "DELETE",
            },
            dataType: "json",
            success: function (response) {
                DATATABLE_ORDER_UNPAID.DataTable().ajax.reload();
                DATATABLE_ORDER.DataTable().ajax.reload();
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
            },
        });
    });
    //cap nhat yeu cau
    $(document).on("click", ".btn-update-order", function () {
        const form = $(this).parents("form");
        $.ajax({
            type: "post",
            url: BASE_URL_API.updateOrder + form[0].dataset.id,
            data: form.serialize(),
            dataType: "json",
            success: function (response) {
                $.toast({
                    heading: "Thành công",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: response.status,
                    position: "bottom-right",
                });
                $("#update-order-modal").modal("hide");
                calendar
                    .getEventById(form[0].dataset.id)
                    .setProp("title", response.data.title);
                calendar
                    .getEventById(form[0].dataset.id)
                    .setProp("backgroundColor", "#8fdf82");
                //calendar.removeAllEvents();
                //calendar.refetchEvents();
            },
            error: function (response) {
                $("#update-order-modal").modal("hide");
                response = response.responseJSON;
                $.toast({
                    heading: "Thất bại",
                    text: response.message,
                    showHideTransition: "plain",
                    icon: "error",
                    position: "bottom-right",
                });
            },
        });
    });
});
// let items = document.querySelectorAll(
//     "#recipeCarousel.carousel .carousel-item"
// );

// items.forEach((el) => {
//     const minPerSlide = 4;
//     let next = el.nextElementSibling;
//     for (var i = 1; i < minPerSlide; i++) {
//         if (!next) {
//             // wrap carousel by using first child
//             next = items[0];
//         }
//         let cloneChild = next.cloneNode(true);
//         el.appendChild(cloneChild.children[0]);
//         next = next.nextElementSibling;
//     }
// });

function clearInputModal(selectorModal) {
    const inputs = $(selectorModal).find("input.fill-blank");
    for (let i in inputs) {
        inputs[i].value = "";
    }
}
