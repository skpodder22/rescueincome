<?php

require __DIR__ . '/wp-load.php';
$return ='hello';
//write_txt_file('','check ='.$return);

// $name = 'sudip';
// $email = 'sudipvirtualtest@gmail.com';
// $message = 'Hello test mail';

// //php mailer variables
// $to = 'sudipvirtual@gmail.com';
// $subject = "Some text in subject...";
// $headers = 'From: '. $email . "\r\n" .
//   'Reply-To: ' . $email . "\r\n";


// //Here put your Validation and send mail
$to = "sudipvirtual@gmail.com";
$subject = 'Request to become an instructor';
$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
<title>aiep.org.uk</title>


</head>
<body>
<div id="wrapper" dir="ltr" style="color: #777;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="center" valign="top">
									<div id="template_header_image" style="text-align: center;">
						<p style="margin-top: 0; margin: 0 0 20px 0;">
							<img src alt="aiep.org.uk">
						</p>
					</div>
								<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background: #f2fbff;">
					<thead id="email-header">
					<tr>
						<td align="center" valign="top">
							<h2 class="order-heading" style="background: #00adff; padding: 20px; color: #FFF; margin: 0 0 20px 0; font-weight: lighter; font-size: 24px; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">
								Become an instructor							</h2>
						</td>
					</tr>
					</thead>
					<tbody id="email-body">
					<tr>
						<td align="center" valign="top" style="padding: 0 20px 20px 20px;">


<p style="margin: 0 0 20px 0;">User dsfsd@gmail.com has requested to become an Instructor at
aiep.org.uk</p>

<p style="margin: 0 0 20px 0;">Phone: </p>

<p style="margin: 0 0 20px 0;">Message: I need become an instructor</p>

<p style="margin: 0 0 20px 0;">Please login to aiep.org.uk and access
http://localhost/aiep/wp-admin/users.php?lp-action=pending-request to
manage the requesting.</p>

<p style="margin: 0 0 20px 0;">Accept the requesting:
http://localhost/aiep/wp-admin/users.php?lp-action=accept-request&amp;user_id=30</p>

<p style="margin: 0 0 20px 0;">Deny the requesting:
http://localhost/aiep/wp-admin/users.php?lp-action=deny-request&amp;user_id=30</p>

						</td>
					</tr>
					</tbody>
					<tfoot id="email-footer">
					<tr>
						<td style="text-align: center; padding: 20px; border-top: 1px solid #DDD;">
							LearnPress						</td>
					</tr>
					</tfoot>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>
</html>';
//$headers = 'Content-Type: text/html';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
$headers .= 'From: abc@gmail.com' . "\r\n";


 $sent = wp_mail($to, $subject, $message, $headers);
 var_dump([$sent,$to]);
    if($sent) {
        echo 'send';
    }//message sent!
    else  {
        echo 'no';
    }//message wasn't sent


// 	$msg = "First line of text\nSecond line of text";

// // use wordwrap() if lines are longer than 70 characters
// $msg = wordwrap($msg,70);

// // send email
// $sent2 = mail("sudipvirtual@gmail.com","My subject",$msg);
// if($sent2) {
// 	echo 'send2';
// }//message sent!
// else  {
// 	echo 'no2';
// }

//  do_action(
// 	    'learn-press/become-a-teacher-sent',
// 	    array(
// 	        'bat_email'   => 'sudipvirtual@gmail.com',
// 	        'bat_phone'   => '9804165683',
// 	        'bat_message' => apply_filters( 'learnpress_become_instructor_message', esc_html__( 'I need become an instructor', 'learnpress' ) ),
// 	    )
// 	);

