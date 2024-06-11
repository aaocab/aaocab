<?php
$id = '';
if (!isset($email_receipient) && !isset($this->$email_receipient))
{
	$email_receipient = $data['email_recepient'];
}

if (!isset($email_receipient) && !isset($this->$email_receipient))
{
	$email_receipient = $data['email_receipient'];
}

if ($data['userId'] != '')
{
	$id = $data['userId'];
}

if ($data['id'] != '')
{
	$id = $data['id'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Gozo Cabs</title>
    </head>

    <body style="@import url(http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,500,400italic,500italic,700,700italic,900,900italic); margin: 0; padding: 0; background: #fff; font-family: 'Roboto', sans-serif;">

        <div style=" border-bottom:1px solid #C4C4C4;">
            <div style="width:600px; margin:0 auto; padding: 20px 0 15px 0;">
                <img src="http://aaocab.com/images/hotlink-ok/gozo-logo-new.png" style="float:left; padding: 0 0 10px 0;"/>
                <h5 style="color:#4e4200; font-weight:300; font-size:14px; padding: 47px 0 10px 0;  float:right; margin:0;"><!--?php echo date("m-d-Y")?--></h5>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div>
            <div style="width:600px; margin:0 auto; padding: 10px;">
				<?= $content ?>
            </div>
        </div>

        <div style="border-top:1px solid #C4C4C4;">
            <div style="width:600px; margin:0 auto; padding:20px 0;">
                <p style="color: #515151; font-size:12px; font-weight:300; text-align:center; padding:0; margin:0;">
                    This email is sent to <?= $email_receipient ?> because you have used this email address to register with aaocab.
					<!--                    If you haven't done so, please ignore this email.-->
					If you received this email in error or do not wish to receive any further communications, please <a href="http://www.aaocab.com/index/unsubscribeemail/hash/<?= Yii::app()->shortHash->hash($id) ?>/email/<?= $email_receipient ?>" target="_BLANK">unsubscribe here.</a>
                    <a href="http://aaocab.com/" style="color: #3d4f99;">aaocab</a> and the aaocab logo is a copyright of <br/><b>Gozo Technologies Pvt. Ltd.</b>.
                </p>

            </div>
        </div>
    </body>
</html>