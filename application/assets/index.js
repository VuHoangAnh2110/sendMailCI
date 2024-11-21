// Thong báo toastr
$(document).ready(function(){
    $('#button').on('click', function(){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr["success"]("Đã có thông báo xịn <3", "Test!")
    });
});


function ThongBao(type, msg, title){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "2000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr[type](msg, title);
};

function add(){
    let formData = new FormData($('#mailForm')[0]);
    $.ajax({
        url: 'send',
        method: 'POST',
        data: formData,
        contentType: false, // Không đặt contentType
        processData: false, // Không xử lý dữ liệu
        success: function(response) {
            const result = JSON.parse(response);
            $('#mailForm')[0].reset(); 
            ThongBao(result.type, result.msg, result.title);
        },
        error: function() {
            ThongBao("error", "Đã xảy ra lỗi khi gửi dữ liệu!", "Lỗi");
        }
    });
    return false; // Ngăn chặn việc tải lại trang
}