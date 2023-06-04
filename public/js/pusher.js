// Enable pusher logging - don't include this in production
Pusher.logToConsole = false;

var pusher = new Pusher("70186e1524c4c44a9000", {
    cluster: "ap1",
});

var channel = pusher.subscribe("quanlysanbong-channel-client");
channel.bind("store-order", function (data) {
    DATATABLE_ORDER_UNPAID.DataTable().ajax.reload();
    DATATABLE_ORDER.DataTable().ajax.reload();
    $.toast({
        heading: "Thông báo",
        text: "Đã có yêu cầu đặt sân mới - code: " + data.order.code,
        showHideTransition: "plain",
        icon: "info",
        position: "top-right",
        stack: 4,
        hideAfter: false,
    });
});
