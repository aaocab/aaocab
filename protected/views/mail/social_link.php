<?php

$msg = '<h4 style="text-align:left;margin:0px;">Dear '. $arr['userName'].'</h4>' .
        '<br/><br/>You have still not linked your social account (Google or Facebook) with your registered Gozo Partner account.' .
        '<br/><br/>Today, we have updated the Gozo Partner app and login to the Gozo Partner app now requires login with Google or Facebook.' .
		'<br/><br/>Please click here to link your Gozo Partner account and your social account.'.
        '<br/><a href="https://www.gozocabs.com/vndsl/'.$arr['vndId'].'/'.$arr['hash'].'">https://www.gozocabs.com/vndsl/'.$arr['vndId'].'/'.$arr['hash'].'</a>'.
        '<br/>Once complete, you can use the Gozo Partner app using your social login.<br/>' .
        '<br/>Regards,' .
        '<br/>GozoCabs<br/><br/>';

 echo $msg;