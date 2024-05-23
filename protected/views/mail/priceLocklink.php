<?
$msg = 'Dear  ' . $arr['userName'] .
        ",<br/><br/>Price lock for your booking ".$arr['bookingCode']." has recently expired. .
        <br/>" .
        "<br/>If your travel plans are confirmed, we strongly recommend that you <a href = '".$arr['paymentLink']."'>make payment</a> and confirm your booking. <br/>" .
        '<br/><br/>We are unable to promise your the price you had locked, but we will try out best to get you a traveling at a great price.<br/>' .
        '<br/>Please Hurry! Prices will be going up as the travel time gets closer<br/>' .
		'<br/>Yours truly,<br/>' .
        '<br/>-Team Gozo';
echo $msg;

