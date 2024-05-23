<?php
$msg = '<h4 style="text-align:left;margin:0px;">Dear '.$arr['full_name'].',</h4>' .
		'<br/><br/>Congratulations! Your vendor account is approved.' .
        '<br/><br/>Please <a href="'.$arr['app_link'].'" target="_blank">download the Gozo partner app.</a> You must have at least 1 car and at least 1 driver approved before you can accept bookings.' .
        '<br/>See <a href="'.$arr['video_link'].'" target="_blank">this video</a> on how to use the Gozo partner app.<br/>' .
		'<br/>The <a href="'.$arr['driver_app_link'].'" target="_blank">Gozo driver app</a> must be used for all trips on the Gozo platform. Failure to use the driver app may lead to cancellation of your vendor account.'.
        '<br/><br/>Regards,' .
        '<br/>Team Gozo<br/><br/>';
 echo $msg;