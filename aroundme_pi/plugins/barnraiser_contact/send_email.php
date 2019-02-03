<?php

// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------

$contact_msg = "";

include ("../../core/config/aroundme_core.config.php");
include ("../../core/inc/functions.inc.php");


// START SESSION
session_name($core_config['node']['php_session_name']);
session_start();


if (isset($_POST['send_email']) && isset($_SESSION['permission']) && $_SESSION['permission'] >= 16) {
	
	// setup mail
	require_once('../../core/class/Mail/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->Host = 		$mail_config['host'];
	$mail->Port = 		$mail_config['port'];
	$mail->Mailer = 	$mail_config['mailer'];
	
	if (isset($mail_config['smtp']['username'])) {
		$mail->SMTPAuth = true;
		$mail->Username = $mail_config['smtp']['username'];
		$mail->Password = $mail_config['smtp']['password'];
	}
			
	$mail->From = 		$mail_config['email_address'];
	$mail->AddReplyTo	($_POST['email'], 'tom');
	$mail->FromName = 	$_SESSION['openid_nickname'];
	$mail->WordWrap = 	$mail_config['wordwrap'];
	$mail->Priority = 			3;
	$mail->Encoding = 			"8bit";
	$mail->CharSet = 			"iso-8859-1";
	$mail->SMTPKeepAlive =      true;
	$mail->IsHTML(true);

	
	
	// email, subject, message
	$email_subject = stripslashes(htmlspecialchars($_POST['subject']));
	
	$mail->Subject = $email_subject;
	
	$email_message = stripslashes(htmlspecialchars($_POST['message']));
	
	$email_message .= "\n\nThis mail was sent from your OpenID account from " . $_SESSION['openid_identity'];
	
	
	// HTML-version of the mail
	$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
	$html .= "<BODY>";
	$html .= utf8_decode(nl2br($email_message));
	$html .= "</BODY></HTML>";
	
	$mail->Body = $html;
	// non - HTML-version of the email
	$mail->AltBody   = utf8_decode($email_message);
	
	$mail->ClearAddresses();
	$mail->AddAddress($_POST['email'], '');
	
	if($mail->Send()) {
		// sent
		$contact_msg = 1;
	}
}

header("Location: " . $_SERVER['HTTP_REFERER'] . "&contact_msg=" . $contact_msg);
exit;

?>