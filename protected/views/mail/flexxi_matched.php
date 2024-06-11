<?php

$msg = 'Dear '. $arr['userName'] .
        ',<br/><br/>We have matched your shared taxi request '.$arr['booking_id'].' with other riders.<br/>' .
        ',<br/><br/>A cab will soon be allocated for your journey.' .
		'<br/><br/>You will receive an email with the Driver and cab details upto 4 hours before the pickup time.'.
        '<br/>Also a pickup point address will be provided for you to meet the other riders for your shared ride.<br/>'.
        '<br/>You can contact us at +91 90518-77-000 or email us at info@aaocab.com for any queries.<br/>' .
        '<br/>Regards,' .
        '<br/>aaocab<br/><br/>';

 echo $msg;