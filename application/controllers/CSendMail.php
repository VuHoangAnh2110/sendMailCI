<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Calculation\Information\Value;

class CSendMail extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('MSendMail');
		$this->load->model('MTemplate');
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

// Lưu nội dung thư,... vào database
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
				//Tạo id cho template
				$id_temp = $this->generateId();

				$this->MTemplate->insert_template([
					'id_template' => $id_temp,
					'content' => $this->ReContent($emailContent)
				]);
                foreach ($data as $index => $row) {
                    if ($index == 0) continue; // Bỏ qua dòng tiêu đề nếu có
					if ($row[0] == null) continue;
                    $recipientEmail = $row[0];
				
                    $this->MSendMail->insert_mail([
                        'noi_dung' => $this->createKeyValue1($row),
                        'nguoi_nhan' => $recipientEmail,
                        'nguoi_gui' => 'vhanh2k4@gmail.com',
                        'trang_thai' => 'chưa',
						'id_template' => $id_temp
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

	}

// Lấy các mail chưa gửi trong database và thực hiện gửi 
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
			$template = $this->MTemplate->get_template($mails->id_template);
			$template_content = $template->content;
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
				$message = $this->MergeMail($template_content,$mails->noi_dung);                    //Set email format to HTML
				// $mail->Subject = 'Here is the subject';
				$mail->Body = '<h2>' . $message . '</h2>';
				// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
				if($mail->send()){
					$status = '<h3 style="color:green;"> Mail Sent Successfully ! </h3>';
					$this->MSendMail->update_mail($mails->id_mail, 'thành công');
				}else{
					$status = 'Mail Error: ' . $mail->ErrorInfo;
					$this->MSendMail->update_mail($mails->id_mail, 'lỗi rồi');
				}
				// echo $status;
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}

// Thử nghiệm gửi mail qua hàm exec - chưa thành công
	public function sendMailAsync() {
		$command = 'php sendMailCI/index.php CSend > /dev/null &';
		exec($command, $output, $return_var);
	
		if ($return_var === 0) {
			echo "Email is being sent in the background.";
		} else {
			echo "Failed to execute asynchronous email sending.";
		}
	}
	
// Tạo các placeholder từ nội dung được nhập vào
	public function genPlaceHolder(){
		// Lấy nội dung từ request Ajax
		$emailContent = $this->input->post('email_content');
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
				foreach ($firstRow as $index => $value) {
					$nameValue = $index;
					$listName[$nameValue] = $value;					
				}
			}
		}

		// Kiểm tra chuỗi có chứa <<>> hay không
		preg_match_all('/<<([^>]+)>>/', $emailContent, $matches);
 
		if (!empty($matches[1])) {
			$placeholders = $matches[1]; // Lấy nội dung bên trong <<>>
			$dem = 0;
			// Tạo danh sách HTML các label và select
			$output = '';
			foreach ($placeholders as $placeholder) {
				$dem++;
				$output .= '
					<label class="block text-gray-700 font-medium mb-2">' . htmlspecialchars($placeholder) . '(' . $dem . '):</label>
					<select name= "placeholders[' . htmlspecialchars($placeholder) . ']' . 
					'" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300 p-3 mb-4">';
					foreach ($listName as $key => $val){
						$output .= '<option value="'. htmlspecialchars($key) . '">'. htmlspecialchars($val) .'</option>';
					}
				$output .= '</select>';
			}
 
			// Trả về dữ liệu JSON
			echo json_encode(['status' => 'success', 'html' => $output]);
		}else {
			echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy placeholder nào trong nội dung email.']);
		}
	}

//Tạo id random cho table template
	function generateId() {
		// Tạo 3 chữ cái ngẫu nhiên
		$letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
	
		// Lấy ngày, tháng, năm hiện tại
		$day = date('d'); // Ngày
		$month = date('m'); // Tháng
		$year = date('y'); // Năm
	
		$randomNumber = mt_rand(1, 9); // Số ngẫu nhiên 4 chữ số

		// Ghép các thành phần thành ID
		$Id = $letters . $day . $month . $year . $randomNumber;
	
		return $Id;
	}

	public function ReContent($content){
 		// Định dạng regex để tìm các chuỗi trong <<>>
 			preg_match_all('/<<([^>]+)>>/', $content, $matches);
		if (!empty($matches[0])) {
			$placeholders = $matches[0]; // Mảng chứa các <<...>>
			$count = 1;

			foreach ($placeholders as $placeholder) {
				// Thay thế từng <<>> bằng <<(n)key>>
				$newPlaceholder = str_replace('<<', '<<(' . $count . ')', $placeholder);
				$content = str_replace($placeholder, $newPlaceholder, $content);
				$count++;
			}
		}
	return $content;
	}

//Lấy key: value từ nội dung để lưu datasbase
	// public function createKeyValue($content, $data){
	// 	$selects = $this->input->post('placeholders'); // Các dữ liệu cột được chọn

	// 	// Định dạng regex để tìm các chuỗi trong <<>>
	// 	preg_match_all('/<<([^>]+)>>/', $content, $matches);

	// 	$result = [];
	// 	$out = '';
	// 	if (!empty($matches[1])) {
	// 		// Mảng chứa các từ khóa bên trong <<>>
	// 		$placeholders = $matches[1];

	// 		foreach ($placeholders as $index => $placeholder) {


	// 			$key = '(' . ($index + 1) . ')' . $placeholder;
	// 			$value = isset($placeholder) ? '' : '';
	// 			$result[$key] = $value;

	// 			$out .= '<' . $key . '::' . '>,';
	// 			// Thay thế trong nội dung
	// 			// $content = str_replace('<<' . $placeholder . '>>', '<<' . $key . '>>', $content);
	// 		}
	// 	}
	// 	return $result;
	// }


	public function createKeyValue1($data){
		$selects = $this->input->post('placeholders'); // Các dữ liệu cột được chọn
		$dem = 0;
		$out = '';
		if (!empty($selects)) {
			foreach ($selects as $key => $value){
				$place = '(' . ($dem + 1) . ')' . $key;
				$valueph = isset($value) ? $data[$value] : '';
				$result[$place] = $valueph;

				$out .= '<' . $place . '::' . $valueph . '>,';
				$dem++;
			}
		}
		return $out;
	}

//Hàm trộn nội dung để gửi 
	public function MergeMail($content, $placeholders){
		// Kiểm tra xem nội dung và danh sách key-value có hợp lệ không
		if (empty($content) || empty($placeholders)) {
			return $content; // Trả về nội dung gốc nếu không có gì để thay thế
		}

		// Tách nội dung trộn thành các phần tử riêng biệt
		$entries = explode(",", $placeholders);
		$result = [];

		// Lặp qua từng phần tử và tách key, value
		foreach ($entries as $entry) {
			// Loại bỏ ký tự không cần thiết
			$entry = trim($entry, "<>"); // Loại bỏ ký tự '<' và '>'
			list($key, $value) = explode("::", $entry); // Tách key và value
			$result[$key] = $value;
		}

		foreach ($result as $ke => $val) {
			// Tạo regex tìm placeholder trong nội dung
			$placeholder = preg_quote($ke, '/'); // Đảm bảo ký tự đặc biệt không làm lỗi regex
			$content = preg_replace('/' . $placeholder . '/', htmlspecialchars($val), $content);
		}

		return $content;
	}

}
