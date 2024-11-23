	<!-- Controller này để test hàm exec() 
	Hiện tại chưa có tác dụng
	-->

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CSend extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('MSendMail');
        $this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Lib_Mailer');
    }
    
    public function index(){
        
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
}