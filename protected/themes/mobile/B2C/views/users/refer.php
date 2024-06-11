<script src="https://apis.google.com/js/platform.js" async defer></script>
<!-- test url -->
<?php
$this->layout	 = 'column1';
?>
<?
//fb share
$fbshareUrl		 = //mail to outlook
		$mailBody		 = 'Dear Friend,%0D%0DI wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service.%0D
aaocab is India’s leader in long distance taxi travel. Please visit  ' . 'http://www.aaocab.com/invite/' . $refCode . '  to register and get a credit of ' . $amount . ' points towards your future travel needs.%0D
%0D%0D%0D
Regards,%0D
aaocab Team';
?>


<div class="content-boxed-widget p10 mb10 top-10">
	<div class="uppercase"><h3 class="mb0">Refer Friend <span class="pull-right"><img src="/images/refer.svg" alt="Refer Friend" width="25"></span></h3></div>
</div>

<div class="content-boxed-widget box-text-3 mb10">
	<div class="line-height20 text-center uppercase">
		<div class="one-half">
			<a href="<?= Yii::app()->createAbsoluteUrl('users/FbShareTemplate', ['refcode' => $refCode]); ?>" class="button-round button-icon shadow-small regularbold bg-facebook button-s pl40 pr40">
				<i class="fab fa-facebook"></i> Share</a>
		</div>
		<div class="one-half last-column">
			<a href="MAILTO:?subject= Gozo Referral&body= <?= $mailBody ?>" class="button-round button-icon shadow-small regularbold bg-google button-s"><i class="fas fa-envelope"></i> Send Mail</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="font-18 text-center uppercase gray-color mt20 mb20">
		OR
	</div>
	<div class="line-height20 text-center">
		<b class="uppercase">Invite link</b> <i class="fas fa-link"></i><br>
		<a href="#" class="font-12 link-one"><?= 'http://www.aaocab.com/invite/' . $refCode; ?></a>
	</div>
	<div class="font-18 text-center uppercase gray-color mt20 mb20">
		OR
	</div>
	<div class="line-height20 text-center">
		<b class="uppercase">Send Your Referral code</b> <i class="fas fa-paper-plane"></i><br>
		<b class="font-25 color-green3-dark"><?= $refCode ?></b>
	</div>
<div class="line-height20 text-center mt20">
	<img src="<?= Yii::app()->createAbsoluteUrl('images/refer_friend.jpg')?>?v1.2" class="p0">
    </div>
</div>
