<?php

$conn = new mysqli('localhost', 'root', 'sdrs22590', 'imp');
// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}
include 'ImapMailbox.php';
include 'PHPMailer/PHPMailerAutoload.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function sendEmail($param)
{

}

define('GMAIL_EMAIL', 'impind.leads@gmail.com');
define('GMAIL_PASSWORD', 'Leadsnew1');
define('ATTACHMENTS_DIR', dirname(__FILE__) . '/attachments');
$mailbox = new ImapMailbox('{imap.gmail.com:993/imap/ssl}INBOX', GMAIL_EMAIL, GMAIL_PASSWORD, ATTACHMENTS_DIR, 'utf-8');
$mails = array();
// Get some mail
$mailsIds = $mailbox->searchMailBox('UNSEEN FROM "action@ifttt.com"');
if (!$mailsIds)
{
	die('Mailbox is empty');
}
$i = 0;
$res = $conn->query("SELECT * FROM emails WHERE eml_active=1 AND eml_sent=0 order by eml_id");
foreach ($mailsIds as $mailId)
{

	if ($i >= 10)
	{
		break;
	}
	/* @var $mail IncomingMail */
	$mail = $mailbox->getMail($mailId);
	$html = $mail->textPlain;
	if (strstr($html, '#NOFORWARD#') !== FALSE)
	{
		continue;
	}
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$aNodes = $dom->getElementsByTagName('a');
	foreach ($aNodes as $aNode)
	{
		/* @var $aNode DOMElement */
		$href = $aNode->getAttribute('href');
		$host = parse_url($href, PHP_URL_HOST);
		if ($host != 'email.ifttt.com')
		{
			continue;
		}
		$dom1 = new DOMDocument();
		$dom1->loadHTMLFile($href);
		$metaNodes = $dom1->getElementsByTagName('meta');
		$host = '';
		foreach ($metaNodes as $metaNode)
		{
			if ($metaNode->getAttribute('property') == 'og:url')
			{
				$href = $metaNode->getAttribute('content');
				$host = parse_url($href, PHP_URL_HOST);
				break;
			}
		}
		if ($host == '')
		{
			break;
		}

		$replyNode = $dom1->getElementById('replylink');
		$replyLink = $host . $replyNode->getAttribute('href');
		$dom2 = new DOMDocument();
		$dom2->loadHTMLFile('http://' . $replyLink);
		$divNodes = $dom2->getElementsByTagName('div');
		foreach ($divNodes as $divNode)
		{
			if ($divNode->getAttribute('class') == 'anonemail')
			{
				$email = $divNode->nodeValue;
				$resExist = $conn->query("SELECT * FROM cron_log WHERE crl_email='" . $email . "'");
				if (mysqli_num_rows($resExist) > 0)
				{
					$mailbox->markMailAsRead($mailId);
					continue;
				}
				$attempt = 0;
				while (true) {
					$row = $res->fetch_assoc();
					if (!$row)
					{
						$conn->query("UPDATE emails SET eml_sent=0 WHERE eml_active=1");
						$res = $conn->query("SELECT * FROM emails WHERE eml_active=1 AND eml_sent=0 ORDER BY eml_id");
						$row = $res->fetch_assoc();
					}

					$smtp = new PHPMailer;
					$smtp->isSMTP();

					switch ($row['eml_type']) {
						case 1:
							$shost = 'smtp.gmail.com';
							$sport = 587;
							$ssecure = 'tls';
							break;
						case 2:
							$shost = 'smtp.mail.yahoo.com';
							$sport = 465;
							$ssecure = 'ssl';
							break;
						case 3:
							$shost = 'smtp-mail.outlook.com';
							$sport = 587;
							$ssecure = 'tls';
							break;
						default:
							$shost = 'smtp.mail.yahoo.com';
							$sport = 465;
							$ssecure = 'ssl';
							break;
					}

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
					$smtp->SMTPDebug = 2;

//Ask for HTML-friendly debug output
					$smtp->Debugoutput = 'html';

//Set the hostname of the mail server
					$smtp->Host = $shost;

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
					$smtp->Port = $sport;

//Set the encryption system to use - ssl (deprecated) or tls
					$smtp->SMTPSecure = $ssecure;

//Whether to use SMTP authentication
					$smtp->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
					$smtp->Username = $row['eml_email'];

//Password to use for SMTP authentication
					$smtp->Password = $row['eml_password'];
					$name = $row['eml_fname'] . ' ' . $row['eml_lname'];
//Set who the message is to be sent from
					$smtp->setFrom($row['eml_email'], $name);

//Set an alternative reply-to address
					$smtp->addBCC('impind.web@gmail.com');
//Set who the message is to be sent to
					$smtp->addAddress($email);

//Set the subject line
					$smtp->Subject = 'RE: ' . $mail->subject;
					$content = '<p>Hi,</p>
<p>Can we generate leads and business opportunities for you? We are offering this at no upfront cost to you.</p>
<p>Simply sign up at <a href="http://www.impind.com/prosignup">www.impind.com/prosignup</a> and we can send you a note anytime we find a customer who is looking for a professional like you.</p>
<p>Please take a look <a href="http://www.impind.com/whyimpind/provider">www.impind.com/whyimpind/provider</a> for more details on why you should join Impind.</p>
<p>Write or call us if you have any questions. Our phone number is 1-512-410-6098 <em>[24x24 IMPIND] </em></p>
<p>Regards, %name%</p>
<p>PS: I&rsquo;m emailing a few pros like yourself whose listings I found on CL. Some people have posted multiple listings on CL and its hard for me to distinguish if the same or different person(s) have posted the listing. If you are one of those with many duplicate listings you can put this keyword &ldquo;#NOFORWARD#&rdquo; in your listing so I don&rsquo;t email you more than once.&nbsp;</p>
<p>&nbsp;</p>
';
					$message = str_replace('%name%', $row['eml_fname'], $content);
					$smtp->msgHTML($message);
					$attempt++;
					if ($smtp->send())
					{
						$attempt = 0;
						$mailbox->markMailAsRead($mailId);
						$conn->query("UPDATE emails SET eml_sent=1 WHERE eml_id=" . $row['eml_id']);
						$conn->query("INSERT into cron_log (crl_email) VALUES ('" . $email . "')");
					}
					else
					{
						if ($attempt > 5)
						{
							break;
						}
						continue;
					}
					$i++;
					break;
				}
			}
		}
		break;
	}
}
