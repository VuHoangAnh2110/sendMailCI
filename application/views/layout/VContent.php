<!-- application/views/email_form.php -->

<div class="p-10">
    <div class="max-w-3xl mx-auto bg-gray-100 p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Trộn thư và gửi email</h1>
        
        <form method="post" id="mailForm" enctype="multipart/form-data" action="send">
            <!-- Ô nhập tiêu đề email -->
            <div class="mb-4">
                <label for="email_subject" class="block text-gray-700 font-semibold mb-2">
                    Tiêu đề email:
                </label>
                <input type="text" id="email_subject" name="email_subject" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3" placeholder="Nhập tiêu đề email">
            </div>

            <!-- Ô nhập tên người gửi -->
            <div class="mb-4">
                <label for="sender_name" class="block text-gray-700 font-semibold mb-2">
                    Tên người gửi:
                </label>
                <input type="text" id="sender_name" name="sender_name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3" placeholder="Nhập tên người gửi">
            </div>
            
            <div class="mb-4">
                <label for="email_content" class="block text-gray-700 font-semibold mb-2">
                    Mẫu nội dung email:
                </label>
                <textarea id="email_content" name="email_content" rows="6" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3" placeholder="Nhập nội dung email với các placeholder như <<name>>, <<date>>..."></textarea>
            </div>
            
            <div class="mb-4">
                <label for="data_file" class="block text-gray-700 font-semibold">
                    Tải file Excel dữ liệu:
                </label>
                <input type="file" id="data_file" name="data_file" accept=".xls,.xlsx" 
                class="w-full text-gray-700 border border-gray-300 rounded-md p-2">
            </div>
            
            <!-- Nút để lưu vào database -->
            <button type="submit" name="action" value="save" class="w-50 bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600">
                Lưu vào database
            </button>

            <!-- Nút để trộn và gửi email -->
            <button type="submit" name="action" value="send" class="w-50 bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-indigo-600">
                Trộn và gửi email
            </button>
        </form>

        <!-- Ô xem trước nội dung sau khi lưu vào database -->
        <div id="preview" class="mt-8 hidden bg-gray-100 p-4 rounded-md shadow-inner">
            <h2 class="text-xl font-bold mb-2">Xem trước nội dung email</h2>
            <p id="preview_content" class="text-gray-700"></p>
        </div>
    </div>
</div>