<?php

include_once dirname( __FILE__ ) . '/captcha.php';

$captcha = new Kuteshop_Captcha();
$code    = $captcha->get_and_show_image();

// Lưu code session
session_start();

$_SESSION['ovic_captcha_code'] = $code;
