<?php

$msg = "";
switch ($arr['templateStyle'])
{
	case Contact::NEW_CON_TEMPLATE:
//		$msg = '<h4 style="text-align:left;margin:0px;">'
//				. 'Dear ' . $arr['userName'] . '</h4>' .
//				'<br/><br/>Please click on this: ' . $arr['link'] . ' to confirm your email address.' .
//				'<br/>Once complete, you can use the email address to activate your account.<br/>' .
//				'<br/>Regards,' .
//				'<br/>aaocab<br/><br/>';
		$msg = '<p>Dear '.$arr['userName'].'</p>' .
			'<p>Thank you for signing up with aaocab. We are thrilled to have you as a new member of our platform. To ensure the security and validity of your email address, we kindly request you to verify your email ID.</p>'.
			'<p>To complete the email verification process, please click on the following link:</p>'.
			'<p><a href='.$arr['link'].'>'.$arr['link'].'</a></p>'.
			'<p>By clicking on the link above, you will be directed to a secure verification page on our website. If the link is not clickable, please copy and paste it into your web browsers address bar.</p>'.
			'<p>We encourage you to verify your email ID at your earliest convenience to avoid any interruptions in accessing your account and enjoying our services.</p>'.
			'<p>Verifying your email ID is an essential step to activate your account fully. It helps us ensure that we have accurate contact information and enables us to deliver important updates, notifications, and relevant information directly to your inbox.</p>'.
			'<p>If you encounter any issues or have any questions regarding the email verification process, please dont hesitate to reach out to our dedicated support team at info@aaocab.com. We are available to assist you and provide the necessary guidance to complete the verification smoothly.</p>'.
			'<p>Thank you for your cooperation in verifying your email ID. We are excited to have you as a member of our community and look forward to providing you with a rewarding experience.</p>'.
			'<p>Best regards,<br/>aaocab Team</p>';

		break;

	case Contact::NOTIFY_OLD_CON_TEMPLATE:

		$userType	 = ($arr['userType'] == '2') ? 'Vendor' : 'Driver';
		$msg		 = '<h4 style="text-align:left;margin:0px;">'
				. 'Dear ' . $arr['userName'] . '</h4>' .
				'<br/>Your email address <strong> ' . $arr['email'] . ' </strong>  is being added by <strong>' . $arr['vndName'] . '  </strong> as a  ' . $userType . ' to Gozo Cabs.' .				
				'<br/>To allow click here >> ' . $arr['link'] . '<br/>' .
				'<br/>Regards,' .
				'<br/>aaocab<br/><br/>';

		break;

	case Contact::MODIFY_CON_TEMPLATE:
		$msg = '<h4 style="text-align:left;margin:0px;">'
				. 'Dear ' . $arr['userName'] . '</h4>' .
				'<br/><br/>Please click on this: ' . $arr['link'] . ' to modify your email address.' .
				'<br/>Regards,' .
				'<br/>aaocab<br/><br/>';

		break;
	default:
		break;
}

echo $msg;
