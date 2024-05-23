<?php

$msg = 'Hi '. $arr['userName'] .
        ',<br/><br/>We have an update for you.<br/>' .
        ',<br/><br/>Your taxi will be picking you up at the below pickup point address.' .
        '<br/><b>Pickup Address: </b>' .$arr['pickup'].
        '<br/><b>Time of pickup: </b>'.date('jS M Y (l) ', strtotime($arr['pickupTime'])) . date('h:i A', strtotime($arr['pickupTime'])) .
        '<br/><b>Booking ID: </b>'.$arr['booking_id'].
        '<br/><b>Number of passengers: </b>'.$arr['seat'].
        '<br/><b>Gender of Primary passenger: </b>'.$arr['gender'].
        '<br/><b>Name of Primary passenger: </b>'.$arr['userName'].
		'<br/><br/>Please be on time. The car will leave the pickup point exactly 15 minutes after the time of pickup and continue on the journey.'.
        '<br/>You will be responsible for missing the trip and the booking will be cancelled with no refund if you miss the trip.<br/>'.
        '<br/>You can contact us at +91 90518-77-000 or email us at info@gozocabs.com for any queries.<br/>' .
        '<br/>Regards,' .
        '<br/>Gozocabs<br/><br/>';

 echo $msg;