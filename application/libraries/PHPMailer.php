<?php
 class PHPMailer
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
        $objMail = new PHPMailer\PHPMailer\PHPMailer();
        return $objMail;
    }
}
?>