<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <div id="footer" class="fixed bottom-0 left-0 w-full bg-green-200 bg-opacity-100 border-t-2 p-2 flex justify-between items-center">
            <div class="text-sm ml-10">
                Địa điểm: 96 Định Công - Hoàng Mai - Hà Nội
                <br>
                Tel: 0373 ***856
            </div>

            <!-- Thông báo xử lý ở đây - toastr -->
            <div>
                <div>
                <!-- test -->
                    <button type="button" id="button" class="p-1 text-sm bg-red-400 rounded-lg" >
                        Demo Toastr
                    </button>
                </div>
                <h4 class="text-sm flex justify-center items-center mr-20"> 2024 © by Vu Hoang Anh </h4>                      
            </div>
            
        </div>
    </div>
   
    <script src="{$base_url}application/assets/toastr/jquery.js"></script>
    <script src="{$base_url}application/assets/toastr/toastr.min.js"></script>
    <script src="{$base_url}application/assets/index.js"></script>

    
    
</body>
</html>