<?php
$link	 = "https://gozo.cab/c/" . $qrCode;
$msg	 = 'Hello ' . $name .
		",<br/><br/>Hope you had a great trip with us! We wanted to remind you that you received 10% cash back on that last trip because you were invited to use Gozo by ($referalName).<br/>" .
		"<br/><br/>But why stop there? Refer a friend to Gozo and get another 10% cash back on that last trip. It's that simple! Share your unique referral code, and we'll credit you with an additional 10% cash back when your friend completes their trip..<br/>.<br/>" .
		"<br/>Ready to earn more cash back? <a href='$link'>Click Here</a><br/>" .
		'<br/>Start sharing it with your friends today.' .
		"<br/>Thanks for choosing Gozo and we can't wait to see you earn even more cash back!" .
		'<br/>-Team Gozo';
echo $msg;
