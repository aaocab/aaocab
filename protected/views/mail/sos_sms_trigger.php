<?php
$arr['SosUserName']	 = ($arr['emailAddress'] == 'sos@gozocabs.in') ? 'sos@gozocabs.in' : $arr['SosUserName'];
$msg				 = '<h4 style="text-align:left;margin:0px;">Dear ' . $arr['SosUserName'] . ' </h4>' .
		'<br/>' . $arr['msg'] .
		'<br/>Regards,' .
		'<br/>GozoCabs<br/><br/>';


echo $msg;
