var calendar = null;
document.addEventListener("DOMContentLoaded", function () {
    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerEl = document.getElementById("external-events");
    var calendarEl = document.getElementById("calendar");

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
        itemSelector: ".fc-event",
        eventData: function (eventEl) {
            return {
                title: eventEl.innerText,
                extendedProps: {
                    football_pitch_id: eventEl.dataset.football_pitch_id,
                },
                duration: "01:00:00",
            };
        },
    });

    // initialize the calendar
    // -----------------------------------------------------------------

    calendar = new Calendar(calendarEl, {
        initialView: "dayGridMonth",
        timeZone: "Asia/Ho_Chi_Minh",
        nowIndicator: true,
        navLinks: true,
        lazyFetching: false, // false neu muong cap nhat du lieu khi chuyen tab
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listDay",
        },
        buttonText: {
            month: "Tháng",
            week: "Tuần",
            day: "Ngày",
            list: "Danh sách",
            today: "Hôm nay",
        },
        timeZone: "local",
        locale: "vi",
        editable: true,
        eventMaxStack: 7,
        dayMaxEvents: true,
        droppable: true,
        events: BASE_URL_API.getOrder,
        eventReceive: function (info) {
            $.ajax({
                type: "post",
                url: BASE_URL_API.postOrder,
                data: {
                    start_at: info.event.startStr,
                    end_at: info.event.endStr,
                    football_pitch_id:
                        info.event.extendedProps.football_pitch_id,
                    _token: CSRF_TOKEN,
                    _method: "POST",
                },
                dataType: "json",
                success: function (response) {
                    info.event.setProp("id", response.data.id);
                    console.log(info.event);
                    $.toast({
                        heading: "Thành công",
                        text: response.message,
                        showHideTransition: "plain",
                        icon: response.status,
                        position: "bottom-right",
                    });
                },
                error: function (response) {
                    info.revert();
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
        },
        eventClick: function (info) {
            $.ajax({
                type: "get",
                url: BASE_URL_API.showOrder + info.event.id,
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
                    let arr = href.split("/");
                    arr[arr.length - 1] = info.event.id;
                    href = arr.toString().replaceAll(",", "/");
                    btn.href = href;
                    if (response.data.status == 3) {
                        // $("#update-order-modal form")[0].dataset.id =
                        //     info.event.id;
                        // $("#update-order-modal").modal("show");
                        $("#update-order-modal .btn-update-order").hide();
                    }
                    else {
                        $("#update-order-modal .btn-update-order").show();
                    }
                },
            });
            $("#update-order-modal form")[0].dataset.id = info.event.id;
            $("#update-order-modal").modal("show");
        },
        eventChange: function (info) {
            //console.log(info);
            if (
                info.event.startStr != info.oldEvent.startStr ||
                info.event.endStr != info.oldEvent.endStr
            ) {
                let submit = confirm(
                    "Bạn có chắc chắn muốn thay đổi thời gian?"
                );
                if (submit) {
                    $.ajax({
                        type: "post",
                        url: BASE_URL_API.updateOrder + info.event.id,
                        data: {
                            type: "update_time",
                            start_at: info.event.startStr,
                            end_at: info.event.endStr,
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
                            console.log(response.responseJSON);
                            info.revert();
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
                } else {
                    info.revert();
                }
            }
        },
    });
    calendar.render();
});
