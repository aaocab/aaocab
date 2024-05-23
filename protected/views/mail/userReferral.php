<?php
$link	 = "https://gozo.cab/c/".$qrCode;
$msg = 'Hello ' . $name .
		",<br/><br/>We have a fantastic referral program that rewards you for spreading the word about Gozo's services. Just refer a first-time user using your unique code, and you can get upto 10% back (Rs.$amt) on the trip you just completed . It's that easy!
        <br/>" .
		"<br/>So, why not share your code with your friends or family and earn rewards for every referral? .<br/>" .
		"<br/>Find your unique referral code  in your account settings and start earning today. <a href='$link'>Click Here</a><br/>" .
		'<br/>Got any questions? Reach out to our customer support team.<br/>' .
		'<br/>Thanks for choosing Gozo. We can\'t wait to reward you for your referrals!.<br/>' .
		'<br/>-Team Gozo';
echo $msg;
?>
