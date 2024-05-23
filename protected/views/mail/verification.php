<?

$vlink = '';
if ($arr['vlink'] && $arr['vlink'] != '') {
    $vlink = '<br/><a href="' . $arr['vlink'] . '">' . $arr['vlink'] . '</a>';
}
$msg = "";
$msg = 'Hi ' . $arr['userName'] .
        ',<br/><br/>We have created booking ID '. $arr['bkgId']. ' for you. To confirm your booking request click '.$vlink.' and make your advance payment. '.
        
        '<br/><br/>Booking will automatically be cancelled within 90mins if not reconfirmed.
		Please call us at +91-90518-77-000 or reply to this email if you need any help with this booking.' .
        '<br/><br/>Sincerely,' .
        '<br/>Gozo Cabs';

echo $msg;