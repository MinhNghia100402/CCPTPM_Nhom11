const BASE_URL = location.origin;
const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
const BASE_URL_API = {
    getAllOrder: BASE_URL +  '/api/order_all',
    getFootballPitch: BASE_URL +  '/api/footballPitch',
    getOrder: BASE_URL +  '/api/order',
    postOrder: BASE_URL +  '/api/order',
    showOrder: BASE_URL + "/api/order/",//id
    deleteOrder: BASE_URL + "/api/order/",//id
    updateOrder: BASE_URL + "/api/order/",//id
    maintainFootballPitch: BASE_URL + '/api/footballPitchMaintenance/',//id
    deleteFootballPitch: BASE_URL + '/api/footballPitch/',//id
    getBankInformation: BASE_URL + '/api/bank_information',
    showBankInformation: BASE_URL + '/api/bank_information/',//id
    putBankInformation: BASE_URL + '/api/bank_information/',//id
    deleteBankInformation: BASE_URL + '/api/bank_information/',//id
    changeDisplayBankInformation: BASE_URL + '/api/bank_information_change_display/',//id
};

function getPitchType(url)
{
    $.ajax({
        type: "get",
        url: url,
        success: function (response) {
            $('#update-pitch-type-modal input[name=quantity]').val(response.quantity);
            $('#update-pitch-type-modal textarea[name=description]').html(response.description);
        }
    });
}

