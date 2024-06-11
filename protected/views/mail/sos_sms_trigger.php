<?php
$arr['SosUserName']	 = ($arr['emailAddress'] == 'sos@aaocab.in') ? 'sos@aaocab.in' : $arr['SosUserName'];
$msg				 = '<h4 style="text-align:left;margin:0px;">Dear ' . $arr['SosUserName'] . ' </h4>' .
		'<br/>' . $arr['msg'] .
		'<br/>Regards,' .
		'<br/>aaocab<br/><br/>';


echo $msg;
