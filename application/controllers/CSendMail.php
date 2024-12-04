<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CSendMail extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('MSendMail');
        $this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Lib_Mailer');

    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$data = array(
			'title' => 'Send Mail',
            'base_url'=> base_url(),

		);
		
		// print_r($_SESSION) ;
		// echo $this->session->userdata('sender_name');
		// return;

		$data1['data'] = $data;
		$this->load->view('layout/VLayout', $data1);
	}

	public function Save_DB(){
		$emailContent = $this->input->post('email_content');
        $action = $this->input->post('action');  // Xác định nút được nhấn

        // Kiểm tra nếu có file Excel tải lên
        if (isset($_FILES['data_file']) && $_FILES['data_file']['error'] == UPLOAD_ERR_OK) {
            $filePath = $_FILES['data_file']['tmp_name'];
            $reader = new Xlsx();
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

			$subject = $this->input->post('email_subject');
			$Sender = $this->input->post('sender_name');
			// Lưu các biến vào session
			$this->session->set_userdata('subject', $subject);
			$this->session->set_userdata('sender_name', $Sender);

            if ($action == 'save') {
                // Lưu vào database
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Bỏ qua dòng tiêu đề nếu có

                    $recipientEmail = $row[0];

                    $this->MSendMail->insert_mail([
                        'noi_dung' => $emailContent,
                        'nguoi_nhan' => $recipientEmail,
                        'nguoi_gui' => 'vhanh2k4@gmail.com',
                        'trang_thai' => 'chưa'
                    ]);
                }
                $this->session->set_flashdata('success', 'Đã lưu các email vào cơ sở dữ liệu.');
				redirect(base_url());
				echo json_encode(['type' => 'success', 'msg' => 'Thành công ', 'title' => 'OK!']);
			}
		} else {
			$this->session->set_flashdata('error', 'Có lỗi khi tải file Excel.');
			echo json_encode(['type' => 'error', 'msg' => 'Lỗi: gi do', 'title' => 'Lỗi!']);
		}

		if ($action == 'send'){
			$this->sendMail();
			// exec("php index.php CSendMail/sendMail > /dev/null &");
			// $this->sendMailAsync();
			redirect(base_url());
		}

		// if ($action == 'gen'){
		// 	$this->genPlaceHolder();
		// }
	}

	public function sendMail(){
		// ===============================
		  // Bật tùy chọn cho phép script tiếp tục chạy khi người dùng đóng trình duyệt
		  ignore_user_abort(true);
		  // Tắt bộ đệm đầu ra nếu cần để tiết kiệm tài nguyên
		  ob_end_clean();
		// ===============================

		$mail = $this->lib_mailer->load();
		$data = $this->MSendMail->get_pending_emails();
	
		$sender_name = $this->session->userdata('sender_name'); // Lấy giá trị sender_name từ session
        $subject = $this->session->userdata('subject');  // Lấy giá trị subject từ session

		$mail->CharSet = 'UTF-8'; // Đặt mã hóa ký tự thành UTF-8
		//Server settings
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = 'anhhv2k4king@gmail.com';                     //SMTP username
		$mail->Password   = 'ijnv nsdh cycz wmsv';                        //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		$mail->SMTPKeepAlive = true; // add it to keep the SMTP connection open after each email sent

		foreach($data as $mails){
			try {
				$mail->clearAddresses();
				$mail->clearAttachments();
				//Recipients
				$mail->setFrom('anhhv2k4king@gmail.com', $sender_name);   //Add a recipient
				$mail->addAddress($mails->nguoi_nhan);               //Name is optional
				$mail->addReplyTo('anhhv2k4king@gmail.com', $sender_name);
	
				//Attachments      
				$mail->addAttachment(APPPATH . 'assets/loppy.jpg', 'new.jpg');    //Optional name
	
				//Content
				$mail->isHTML(true);   
				$mail->Subject = $subject;
				$message = $mails->noi_dung;                               //Set email format to HTML
				// $mail->Subject = 'Here is the subject';
				$mail->Body = '<h2>' . $message . '</h2>';
				// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
				if($mail->send()){
					$status = '<h3 style="color:green;"> Mail Sent Successfully ! </h3>';
					$this->MSendMail->update_mail($mails->id, 'thành công');
				}else{
					$status = 'Mail Error: ' . $mail->ErrorInfo;
					$this->MSendMail->update_mail($mails->id, 'lỗi rồi');
				}
				// echo $status;
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}

	public function sendMailAsync() {
		$command = 'php sendMailCI/index.php CSend > /dev/null &';
		exec($command, $output, $return_var);
	
		if ($return_var === 0) {
			echo "Email is being sent in the background.";
		} else {
			echo "Failed to execute asynchronous email sending.";
		}
	}
	

	public function genPlaceHolder(){
		 // Lấy nội dung từ request Ajax
		 $emailContent = $this->input->post('email_content');
		 $action = $this->input->post('action');  // Xác định nút được nhấn
		 $listName = [];

		 // Kiểm tra nếu có file Excel tải lên
		 if (isset($_FILES['data_file']) && $_FILES['data_file']['error'] == UPLOAD_ERR_OK) {
			 $filePath = $_FILES['data_file']['tmp_name'];
			 $reader = new Xlsx();
			 $spreadsheet = $reader->load($filePath);
			 $sheet = $spreadsheet->getActiveSheet();
			 $data = $sheet->toArray();
 
			 if (!empty($data) && isset($data[0])) {
				$firstRow = $data[0];
				 // Lưu vào database
				 foreach ($firstRow as $index => $value) {
					$nameValue = "cot_".($index+1);
					$listName[$nameValue] = $value;					
				 }
			 }
		 }

		 // Kiểm tra chuỗi có chứa <<>> hay không
		 preg_match_all('/<<([^>]+)>>/', $emailContent, $matches);
 
		 if (!empty($matches[1])) {
			 $placeholders = $matches[1]; // Lấy nội dung bên trong <<>>
 
			 // Tạo danh sách HTML các label và select
			 $output = '';
			 foreach ($placeholders as $placeholder) {
				 $output .= '
					 <label class="block text-gray-700 font-medium mb-2">' . htmlspecialchars($placeholder) . ':</label>
					 <select name="' . htmlspecialchars($placeholder) . '" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3 mb-4">
					';
					foreach ($listName as $key => $val){
						$output .= '<option value="'. htmlspecialchars($key) . '">'. htmlspecialchars($val) .'</option>';
					}
					$output .= '</select>';
			 }
 
			 // Trả về dữ liệu JSON
			 echo json_encode(['status' => 'success', 'html' => $output]);
		 } else {
			 echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy placeholder nào trong nội dung email.']);
		 }
	}

}
