<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->library('email');

$config                 = array();
$config['protocol']     = 'smtp';
$config['smtp_host']    = defined('SMTP_HOST') ? SMTP_HOST : 'nyx.mwh.asia';
$config['smtp_port']    = defined('SMTP_PORT') ? SMTP_PORT : '465';
$config['smtp_crypto']  = defined('SMTP_CRYPTO') ? SMTP_CRYPTO : 'ssl';
$config['smtp_user']    = defined('SMTP_USER') ? SMTP_USER : 'admin@peponitravel.com';
$config['smtp_pass']    = defined('SMTP_PASS') ? SMTP_PASS : 'qEhaviu4FDaX';
$config['smtp_timeout'] = '7';
$config['newline']      = "\r\n";
$config['crlf']         = "\r\n";
$config['mailtype']     = 'html';
$config['charset']      = 'utf-8';
$config['validation']   = true;

$this->email->initialize($config);

$this->email->set_newline("\r\n");