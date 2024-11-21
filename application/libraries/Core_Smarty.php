<?php
    if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    // Kiểm tra và yêu cầu autoload.php
    if (file_exists(FCPATH . 'vendor/autoload.php')) {
    require_once FCPATH . 'vendor/autoload.php';
    } else {
    throw new Exception('Tệp autoload.php không tồn tại.');
    }

    use Smarty\Smarty; // Sử dụng không gian tên đầy đủ

    if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
    }

class Core_Smarty extends Smarty {

    private $templateExt = "php"; // Phần mở rộng template là "tpl" theo mặc định của Smarty

    function __construct() {
        parent::__construct();

        // Cấu hình thư mục cache và compile
        $this->setCompileDir(APPPATH . 'cache' . DS . "smarty" . DS . "compile" . DS);
        $this->setCacheDir(APPPATH . 'cache' . DS . "smarty" . DS . "cache" . DS);
        $this->setTemplateDir(APPPATH . 'views' . DS);
        
        //Thiết lập các tùy chọn cache (tùy chọn)
        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->setCacheLifetime(120); // Cache trong 2 phút
    }

    public function parse($template, $data = array(), $return = FALSE) {
        // Gán các biến từ mảng $data vào template
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $this->assign($key, $val);
            }
        }

        // Tạo cache_id dựa trên tên template và dữ liệu
        $cache_id = $template . "_" . md5(json_encode($data));
        $compile_id = null;


        //return $this->fetch("$template.{$this->templateExt}", $cache_id, $compile_id, null, !$return, TRUE);

        // Kiểm tra xem có cần trả về nội dung template hay không
        if ($return) {
            return $this->fetch("$template.{$this->templateExt}", $cache_id, $compile_id);
        } else {
            $this->display("$template.{$this->templateExt}", $cache_id, $compile_id);
        }
    }
}

// Khởi tạo Smarty và thay thế parser mặc định của CodeIgniter
    $CI = &get_instance();
    $CI->parser = new Core_Smarty();
    $CI->view = &$CI->parser;

/* End of file Core_smarty.php */
