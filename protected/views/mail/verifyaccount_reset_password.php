<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$msg = "";
$msg = '<h4 style="text-align:left;margin:0px;">'
				. 'Dear ' . $arr['userName'] . '</h4>' .
                '<br/><br/> You have requested a password change through e-mail
                 verification. If this was not you, ignore this message and nothing will happen.
                 If you requested this verification, visit the following URL to change your
                 password:'.
				'<br/><br/>Please click on this: ' . Filter::shortUrl($arr['link']) . ' to reset your password for email '." ".$arr['email'] .  
				'<br/>Regards,' .
				'<br/>GozoCabs<br/><br/>';
echo $msg;

