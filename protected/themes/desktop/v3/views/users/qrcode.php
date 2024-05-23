<?php 
$qrLink   = Yii::app()->createAbsoluteUrl('rating/downloadQrCode', ['userId' => $models->user_id]);
if($errMsg != '' )
{
	echo '<div style="margin: auto;">' . $errMsg . '</div>';
}
elseif($qrLink != '' && $qrpath != '' && $qrcode != '')
{
?>
<div style="margin: auto;">
    <div style="font-family: 'Roboto', sans-serif; color: #404040; font-size: 16px; width: 344px; height: 542px; border: 4px solid #f36e32; margin: 0 auto 20px auto; background: #f36e32; padding: 10px; line-height: 18px;">
        <div style="width: 100%; font-size: 40px; font-weight: bold; color: #fff; text-align: center; margin:0 0 15px 0; line-height: 24px;">Need a cab?</div>
<div style="background: #fff; padding: 5px;">
        <div style="width: 100%; text-align: center;">
            <p style="font-size: 12px; font-weight: bold; margin-bottom: 5px;">Chauffeur driven AC cab at the best possible prices</p>
            <p style="font-size: 14px;"><b>Local - Airport transfers & Daily rentals<br>
                    Outstation - One-way, Round Trips & more</b></p>
        </div>
        <div style="width: 100%; margin: 0 auto 15px auto; text-align: center;">
            <div style="margin: auto; width: 242px; height: 250px; overflow: hidden; position: relative; border: 1px solid #4e4e4e; margin: auto; display: inline-block;">
                <a href="<?= $qrLink ?>"><img src="<?= $qrpath ?>" alt="" style="width: 230px; height: 230px; float: left;"></a>
                <div style="position: absolute;top: 15px;right: -5px;height: 224px;width: 20px;letter-spacing: 1px;font-weight: bold;font-size: 11px;color: #141414;margin: 0 0 10px 0;line-height: 24px;writing-mode: vertical-rl!important;-webkit-writing-mode: vertical-rl!important;transform-origin: 0 0!important;text-align: center;"><?= $qrcode; ?></div>

                <span style="font-size: 13px;">Scan QR code or visit gozocabs.com</span>
            </div>
        </div>
</div>
<div style="position: relative; float: left; width: 100%; background: #fff; padding:0 10px 10px 10px;">
<div style="width: 15%; flex: 0 0 15%; float: left;"><img src="/images/google-5-Stars.jpg" alt="" width="100%" style="margin-top: 15px;"></div>
        <div style="width: 68%; flex: 0 0 68%; float: left; text-align: center; margin: auto;"><img src="/images/gozo-white-cabs.svg" alt="" width="85%"></div>
        <div style="width: 15%; flex: 0 0 15%; float: left; text-align: right; margin-top: 15px"><img src="/images/tripadvisor-certificato.jpg" alt=""  width="100%"></div>
</div>
        <div style="width: 100%; margin: 5px 0; float: left; position: relative; color: #fff;">
            <p style="margin: 0; text-align: center; font-size: 12px;"><b>90+ million kilometers each year<br>Easy | Reliable | Affordable | Safe | Everywhere in India</b></p>
        </div>
    </div>
</div>
<?php
}
else
{
	echo '<div style="margin: auto;">QR Code not associated with your profile</div>';
}
?>