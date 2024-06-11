<?php

class EmailSend
{

	public static function sendEmail($arr)
	{
		$sendEmail = true;
		//False turns off the emails

		if ($sendEmail == true)
		{
			// Create the message
			$message = Swift_Message::newInstance();

			// Give the message a subject
			$message->setSubject($arr['subject']);

			// Set the From address with an associative array
			$message->setFrom(array('info@aaocab.com' => 'Info aaocab'));

			// Set the To addresses with an associative array
			$message->setTo($arr['to_address']);

			// Give it a body
			$message->setBody($arr['body'], 'text/html');
			$message->addPart($arr['body_plain'], 'text/plain');

			$transport	 = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')->setUsername('info@aaocab.com')->setPassword('brazil1#');
			$mailer		 = Swift_Mailer::newInstance($transport);
			$result		 = $mailer->send($message);
		}
	}

}

?>