<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Lib_Mailer{
    public function __construct(){
        log_message('Debug', 'PHPMailer class is loades.');
    }

    public function load(){
        require_once 'vendor/autoload.php';
        // require_once APPPATH.'src/Exception.php';
        // require_once APPPATH.'src/PHPMailer.php';
        // require_once APPPATH.'src/SMTP.php';

        $mail = new PHPMailer;
        return $mail;
    }
}