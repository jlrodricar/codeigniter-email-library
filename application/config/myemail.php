<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'IMAP';
$config['smtp_host'] = SMTP_SERVER_NAME;
$config['smtp_port'] = SMTP_PORT;
$config['smtp_user'] = SMTP_USERNAME;
$config['smtp_pass'] = SMTP_PASSWORD;
$config['mailtype'] = 'html';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n";


?>