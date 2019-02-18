<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// The mail sending protocol.
$config['protocol'] = 'smtp';
// SMTP Server Address for Gmail.
$config['smtp_host'] = "smtp.gmail.com";
// SMTP Port - the port that you is required
$config['smtp_port'] = 587;
// SMTP Username like. (abc@gmail.com)
$config['smtp_user'] = getenv('SENDER_EMAIL', true) ?: getenv('SENDER_EMAIL');
// SMTP Password like (abc***##)
$config['smtp_pass'] = getenv('SENDER_PASSWORD', true) ?: getenv('SENDER_PASSWORD');

$config['_smtp_auth'] = TRUE;
$config['smtp_crypto'] = 'tls';
$config['smtp_timeout'] = 5;
$config['mailtype'] = "html";
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n";
