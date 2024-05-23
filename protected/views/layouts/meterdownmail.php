<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Gozo Cabs</title>

    </head>

    <body style="@import url(http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,500,400italic,500italic,700,700italic,900,900italic); margin: 0; padding: 0; background: #fff; font-family: 'Roboto', sans-serif;">

        <div style=" border-bottom:1px solid #C4C4C4;">
            <div style="width:600px; margin:0 auto; padding: 20px 0 15px 0;">
                <img src="http://gozocabs.com/images/email_logo_meterdown.png" style="float:left; padding: 0 0 10px 0;"/>
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
                    This email is sent to <?= $this->email_receipient; ?> because you have used this email address to register with Meterdown.
                    If you haven't done so, please ignore this email.
                    Meterdown and the Meterdown logo is a copyright of <br/><b>Gozo Technologies Pvt. Ltd.</b>.
                </p>

            </div>
        </div>
    </body>
</html>