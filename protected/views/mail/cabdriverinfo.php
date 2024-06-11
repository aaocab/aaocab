<?php
$msg = "";
$msg = 'Hi ' . $arr['userName'] .','.
        '<br/><br/>Booking ID '.$arr['booking_id'].' is ALL SET.'.
		'<br/><br/>Your cab and driver are now allocated.'.
		'<br/><br/>See UPDATED details at '.$arr['payment_link'].' subject to T&Cs'.
        '<br/><br/><br/>Regards,' .
        '<br/>aaocab Support' .
        '<br/>+91-90518-77-000<br/><br/><a href="http://www.aaocab.com/refer-friend/"><img src="http://www.aaocab.com/images/refer_friend_email.jpg"/></a>';
echo  $msg;
