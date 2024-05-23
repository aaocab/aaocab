<?php

$msg = 'Dear '. $arr['userName'] .
        ',<br/><br/>Thank you for suggesting us to launch a ₹1 sale from '.$arr['fromCity'].' to '.$arr['toCity'].'.We don not have this route on sale right now, but we will plan on doing this with public demand! Tell your friends to suggest a route as well.<br/>' .
        ',<br/><br/>For your travel from '.$arr['fromCity'].' to '.$arr['toCity'].' use this special code: <b>GO20</b> and get a special rate for your ride. Remember, this code only applies to your email address and is valid for just 24hours.' .
        '<br/>Your friends at Gozo Cabs.' .
        '<br/>Idhar udhar kyu khojo, Just go Gozo!<br/><br/>';
 echo $msg;