<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.min.js"></script>
<link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.css" />
<link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials-theme-flat.css" />
<link href="/res/v2d/css/font-awesome/css/font-awesome.css?v=0.5" rel="stylesheet">
<style>
	#snackbar {
		visibility: hidden;
		min-width: 250px;
		margin-left: -125px;
		background-color: #333;
		color: #fff;
		text-align: center;
		border-radius: 2px;
		padding: 16px;
		position: fixed;
		z-index: 1;
		left: 50%;
		bottom: 30px;
		font-size: 17px;
	}

	#snackbar.show {
		visibility: visible;
		-webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
		animation: fadein 0.5s, fadeout 0.5s 2.5s;
	}

/*	@-webkit-keyframes fadein {
		from {
			bottom: 0;
			opacity: 0;
		}
		to {
			bottom: 30px;
			opacity: 1;
		}
	}

	@keyframes fadein {
		from {
			bottom: 0;
			opacity: 0;
		}
		to {
			bottom: 30px;
			opacity: 1;
		}
	}

	@-webkit-keyframes fadeout {
		from {
			bottom: 30px;
			opacity: 1;
		}
		to {
			bottom: 0;
			opacity: 0;
		}
	}

	@keyframes fadeout {
		from {
			bottom: 30px;
			opacity: 1;
		}
		to {
			bottom: 0;
			opacity: 0;
		}
	}*/
</style>

<?php
Yii::app()->clientScript->registerCssFile("/res/v2d/css/font-awesome/css/font-awesome.css?v=" . Yii::app()->params['sitecssVersion']);
$qrLink		 = Yii::app()->createAbsoluteUrl('rating/downloadQrCode', ['userId' => $model->user_id]);
$mailBody	 = 'Dear Friend,%0D%0DI wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service.%0D
Gozocabs is India’s leader in long distance taxi travel. Please visit https://gozo.cab/c/' . $refCode . ' to register and get a credit of ' . $amount . ' points towards your future travel needs.%0D
%0D%0D
Regards,%0D
Gozocabs Team';
$body		 = 'Dear Friend, I wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service. Gozocabs is India’s leader in long distance taxi travel. Please visit  https://gozo.cab/c/' . $refCode . '  to register and get a credit of ' . $amount . ' points towards your future travel needs';
?>
<div class="row">
	<div class="col-12 col-lg-5 col-xl-4">
		<div class="row">
			<div class="col-12" style="width: 100%; margin: 0 auto 5px auto; text-align: center;">
				<div style="margin: auto; width: 242px; height: 250px; overflow: hidden; position: relative; border: 1px solid #4e4e4e; margin: auto; display: inline-block;">
					<a href="<?= $qrLink ?>"><img src="<?= $qrpath ?>" alt="" style="width: 230px; height: 230px; float: left;"></a>
					<div style="position: absolute;top: 15px;right: -5px;height: 224px;width: 20px;letter-spacing: 1px;font-weight: bold;font-size: 11px;color: #141414;margin: 0 0 10px 0;line-height: 24px;writing-mode: vertical-rl!important;-webkit-writing-mode: vertical-rl!important;transform-origin: 0 0!important;text-align: center;"><?= $qrcode; ?></div>

					<span style="font-size: 13px;">Scan QR code or visit aaocab.com</span>
				</div>
			</div>
			<div class="col-12 text-center mb10">
				<span id="p1"><?= 'https://gozo.cab/c/' . $refCode; ?></span> &nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0)" title="Copy invite link"><img onclick="copyToClipboard('#p1')" src="/images/bx-copy.svg?v=1" alt="click to copy link" width="20" height="20"></a>
				<div id="snackbar">copy to clipboard</div>
			</div>

			<div class="col-12 mb20 text-center">

				<div id="shareIconsCountInside"></div>
				<a href='MAILTO:?subject= Gozo Referral&body= <?= $mailBody ?>'> <button class="btn bg-blue mb-1 font-12" type="button" style="color: #fff; border-radius: 0; padding: 6px 10px 6px 10px;"><i class='fa fa-envelope mr5' style="vertical-align: super;"></i>SEND MAIL</button></a>     
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-7 col-xl-8">
		<div class="row">
			<div class="col-12"><img src="/images/refer_friend.jpg?v=0.7" alt="img" class="img-fluid d-none d-lg-block"></div>
			<div class="col-12"><img src="/images/refer_friend3.jpg?v=0.8" alt="img" class="img-fluid d-lg-none"></div>
			<div class="col-12 mt20"><p>At Gozo, we value our loyal customers and want to reward you for your continued support.</p>
				<p>That's why we've introduced our exciting new "Refer a friend" reward program that offers a host of benefits to our loyal customers. </p>
				<p>If you refer a friend to our services, you'll earn even more cash back on your next trip. Share your unique referral code with your friends, and once every new user you refer completes their travel, we'll credit you up to an additional 10% back in fully redeemable coins on the last trip you took. They get 10% off their first trip too! It's&nbsp;a&nbsp;real&nbsp;win-win!</p>
				<p>So what are you waiting for? Just travel Gozo today, and start earning cash back and exclusive rewards on your bookings. We can't wait to reward you for your referrals!
				</p></div>
		</div>
	</div>
</div>
<script>

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function () {
            x.className = x.className.replace("show", "");
        }, 3000);
    }

    $("#shareIconsCountInside").jsSocials({
        url: "<?= 'https://gozo.cab/c/' . $refCode; ?>",
        text: "<?php echo $body; ?>",
        showCount: true,
        showLabel: true,
        shareIn: "popup",
        shares: ["whatsapp", "facebook", "twitter", "linkedin", "pinterest"]
    });
</script>