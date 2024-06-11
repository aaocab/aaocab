
<?
if($arr['isWeb']==1){
    $loginInfo =  "<br/><b>Your username: </b><span style='color: #a52a2a'>".$arr['email'] ."</span>"
            . "<br/><b>Your temporary password: </b><span style='color: #a52a2a'>" .$arr['password']."</span>"."<br/><a href='".$arr['loginUrl']."' target='_blank'>Login to Gozo Cabs Agent Portal</a>";
}
if($arr['isWeb']==2){
    $loginInfo = "<br/><b>Your username: </b><span style='color: #a52a2a'>".$arr['email'] ."</span>"
            . "<br/><b>Since you already have an account on aaocab with this email, please use your existing password to login to <a href='".$arr['loginUrl']."' target='_blank'>Gozo Cabs Agent portal</a> and update your agent account information.";
}
 $strCorp = 
        "<br/>We have created your aaocab Agent Account. You can now login and update your profile for faster bookings. <br/>";
if($arr['type']==1){
    $strCorp = 
        "<br/>We have created your aaocab Corporate Account. You can now login and update your profile for faster bookings. <br/>";
}
$msg = '<br><div style="text-align: left">Hello ' . $arr['userName'] ."</div>".
        ",<br/><br/>Welcome to aaocab. We are India's leader for inter-city taxi travel. Our mission is to simplify outstation travel in India and we welcome your suggestions for improvement always.
        <br/>" . $strCorp.
        $loginInfo.
        "<br/><br/>For any assistance, contact us at +91 90518-77-000 or email <a href='mailto:info@aaocab.com'>info@aaocab.com </a><br/>" .
        '<br/>Gozo means delight and joy! We do everything we can to stay true to our name. Call us for any assistance we can offer.<br/>' .
        '<br/>Thank you and welcome to the Gozo family!<br/>' .
        '<br/>-Team Gozo';
echo $msg;
