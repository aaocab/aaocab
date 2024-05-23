<?php

$msg = 'Dear '. $arr['userName'] .
        ',<br/><br/>We found '.$arr['noOfBookings'].' match for the alert you had created.' .
        '<br/><br/>Your alert criteria:' .
		'<br/>From City: '. $arr['fromCity'].
        '<br/>To City: '.$arr['toCity'].
		'<br/>Date range: between '.date('d/m/Y h:i A', strtotime($arr['fromDate'])).' and '.date('d/m/Y h:i A', strtotime($arr['toDate'])).
		'<br/><br/><a href="'.$arr['url'].'">Click here </a> to look at the bookings that match your alert.' .
		'<br/><br/>Please create your booking immediately as the seats can sell out quickly.<br/>' .
        '<br/>Regards,' .
        '<br/>Gozocabs<br/><br/>';

 echo $msg;