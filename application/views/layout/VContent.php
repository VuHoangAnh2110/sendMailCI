<!-- application/views/email_form.php -->

<div id="content" class="grid grid-col-1 border-gray-900/10">
    <div class="p-5">
        <form method="post" id="mailForm" enctype="multipart/form-data" action="send">
            <div class="grid sm:grid-cols-6 sm:mx-20 p-1">           
                <div class="sm:col-span-4">
                    <div class=" border-red-800 bg-gray-100 rounded-lg shadow-md sm:mr-10 p-5">
                        <h1 class="text-2xl font-bold mb-4 w-[90%]">Trộn thư và gửi email</h1>
                        <!-- Ô nhập tiêu đề email -->
                        <div class="mb-4 w-[90%]">
                            <label for="email_subject" class="block text-gray-700 font-semibold mb-2">
                                Tiêu đề email:
                            </label>
                            <input type="text" id="email_subject" name="email_subject" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3" 
                            placeholder="Nhập tiêu đề email">
                        </div>

                        <!-- Ô nhập tên người gửi -->
                        <div class="mb-4 w-[90%]">
                            <label for="sender_name" class="block text-gray-700 font-semibold mb-2">
                                Tên người gửi:
                            </label>
                            <input type="text" id="sender_name" name="sender_name" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3" 
                            placeholder="Nhập tên người gửi">
                        </div>
                        
                        <div class="mb-4 w-[90%]">
                            <label for="email_content" class="block text-gray-700 font-semibold mb-2">
                                Mẫu nội dung email:
                            </label>
                            <textarea id="email_content" name="email_content" rows="6" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3" 
                            placeholder="Nhập nội dung email với các placeholder như <<name>>, <<date>>..."></textarea>
                        </div>
                        
                        <div class="mb-4 w-[90%]">
                            <label for="data_file" class="block text-gray-700 font-semibold">
                                Tải file Excel dữ liệu:
                            </label>
                            <input type="file" id="data_file" name="data_file" accept=".xls,.xlsx" 
                            class="text-gray-700 border w-full border-gray-300 rounded-md p-2">
                        </div>
                        
                        <div class="mt-5 grid grid-cols-4 gap-5">
                        <!-- Nút để lưu vào database -->
                            <button type="submit" name="action" value="save" 
                            class="col-span-2 w-2/3 bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600">
                                Lưu Database
                            </button>

                        <!-- Nút để trộn và gửi email -->
                            <button type="submit" name="action" value="send" 
                            class="col-span-2 w-2/3 bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-gray-500">
                                Trộn và Gửi
                            </button>

                        <!-- Tìm kiếm và tạo các place holder từ nội dung  -->
                            <button type="button" id="genph"
                            class="col-span-2 w-2/3 bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600">
                                Tạo Placeholder
                            </button>

                        <!-- Xem trước email khi đã trộn  -->
                            <button type="button" id="btnpreview"
                            class="col-span-2 w-2/3 bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-gray-500">
                                Xem Trước
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tự xuất các place holder để người dùng chọn -->
                <div id="placeholders" class="sm:col-span-2 mt-5 sm:mt-0">
                    <div class="bg-gray-100 border-blue-800 rounded-lg shadow-md p-5">
                        <h1 class="text-2xl font-bold mb-4">Select Placeholder</h1>
                        <!-- Add placeholder -->
                        <div id="addplace">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Ô xem trước nội dung sau khi lưu vào database -->
        <div id="preview" class="mt-8 hidden bg-gray-100 p-4 rounded-md shadow-inner mb-10">
            <h2 class="text-xl font-bold mb-2">Xem trước nội dung email</h2>
            <p id="preview_content" class="text-gray-700"></p>
        </div>
    </div>
</div>

