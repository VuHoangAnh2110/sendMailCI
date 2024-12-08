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

// =====================================================================
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
// =====================================================================

// Bắt các placeholder khi nhập vào input ========================================================
//cách 1:
    $(document).ready(function () {
        $('#genph1').on('click', function () {
            let email = $('#email_content').val();

            // Tìm kiếm các chuỗi nằm trong <<>>
            let placeholders = email.match(/<<([^>]+)>>/g); // Sử dụng regex để tìm tất cả <<...>>
                
            if (placeholders) {
                let $targetDiv = $('#addplace');
                $targetDiv.empty();

                // // Lấy nội dung bên trong <<>> bằng cách loại bỏ ký tự <<
                // let extractedValues = placeholders.map(match => match.replace(/<<|>>/g, '')).join(':');

                placeholders.map(match => {
                    let fieldName = match.replace(/<<|>>/g, ''); // Loại bỏ << và >>
    
                    // Tạo thẻ label và input
                    let $label = $('<label>').text(fieldName + ': ').addClass('block text-gray-700 font-medium mb-2');
                    let $input = $('<input>').attr({
                        type: 'text',
                        name: fieldName,
                        placeholder: `Nhập ${fieldName}`
                    }).addClass('block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4');    
    
                    // Thêm vào div đích
                    $targetDiv.append($label).append($input);
                });
            } else {
               alert('Không tìm thấy chuỗi nào nằm trong << >>.');
            }
        });
    });

// cách 2:
    $(document).ready(function () {
        $('#genph1').on('click', function () {
            let emailContent = $('#email_content').val();

            $.ajax({
                url: 'CSendMail/genPlaceHolder', // Đường dẫn tới controller
                type: 'POST',
                data: { email_content: emailContent },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#addplace').html(response.html); // InnerHTML với kết quả từ server
                    } else {
                        alert(response.message); // Hiển thị lỗi nếu không tìm thấy placeholder
                    }
                },
                error: function () {
                    alert('Đã xảy ra lỗi trong quá trình xử lý.');
                }
            });
        });
    });

// Cách 3: formdata
    $(document).ready(function () {
        $('#genph').on('click', function () {
            var formData = new FormData($('#mailForm')[0]);

            formData.append('data_file', $('#data_file')[0].files[0]);
            formData.append('email_content', $('#email_content').val()); // Nội dung email

            $.ajax({
                url: 'CSendMail/genPlaceHolder', // Đường dẫn tới controller
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var result = JSON.parse(response);

                    if (result.status === 'success') {
                        $('#addplace').html(result.html); // InnerHTML với kết quả từ server
                        ThongBao(result.status, result.msg, result.title);
                    } else {
                        // alert(result.message); // Hiển thị lỗi nếu không tìm thấy placeholder
                        ThongBao(result.status, result.msg, result.title);
                    }
                },
                error: function () {
                    ThongBao('error', 'Đã có lỗi trong khi xử lý!', 'Lỗi');
                }
            });
        });
    });

    $(document).ready(function(){
        $('#btnpreview').on('click', function(){
            var formData = new FormData($('#mailForm')[0]);

            $.ajax({
                url: 'CSendMail/previewMail',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var result = JSON.parse(response);

                    if (result.status === 'success') {
                        $('#preview').show();
                        $('#preview_content').html(result.html); // InnerHTML với kết quả từ server
                        ThongBao(result.status, result.msg, result.title);
                    } else {
                        // alert(result.message);
                        ThongBao(result.status, result.msg, result.title);
                    }
                },
                error: function () {
                    ThongBao('error', 'Đã có lỗi trong khi xử lý!', 'Lỗi');
                }
            })
        });
    });

