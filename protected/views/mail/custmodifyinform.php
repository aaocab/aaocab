<?

$msg = "";

$payable = "";
$amount = "";
if ($arr['advance'] > 0) {
    $amount.="<br/><b>Advance Received: </b>Rs. " . $arr['advance'];
    $a = $arr['amount'] > 0 ? $arr['amount'] - $arr['advance'] : $arr['amount'];
    $payable = $arr['amount'] > 0 ? "<br/><b>Payable to driver: </b>Rs. " . $a : "";
}
if ($arr['trip_type'] == 1) {
    $amount.="<br/><b>Cost: </b>Rs." . $arr['amount'] . $payable;
}
if ($arr['trip_type'] == 2) {
    $amount.="<br/><b>Rate: </b>Rs." . $arr['rate_per_km'] . "/km";
}
if ($arr['booking_type'] == 1) {
    $returnTime = "";
}
else {
    $returnTime = '<br/><b>Return Time: </b>' . $arr['returnDateTimeFormat'];
}


if ($arr['booking_type'] == 1) {
    $bookingType = "One Way Drop";
    $dropArea = '<br/><b>Drop Area: </b>' . $arr['dropArea'];
    $info = $oneWayInfo;
}
else {
    $bookingType = "Return Trip";
    $dropArea = "";
    if ($arr['rate_per_km'] != null && $arr['rate_per_km'] != "") {
        $info = $returnInfoPerKm;
    }
    else {
        $info = "";
    }
}
$msg = 'Hi ' . $arr['userName'] .
        ',<br/><br/>Thank you for choosing aaocab! '
        . 'We have received your reservation request and your booking is confirmed. You can contact us anytime to inquire your booking status. The details of your reservation request are as follows:<br/>' .
        '<br/><b>Booking ID: </b>' . $arr['bookingId'] .
        '<br/><b>Type: </b> ' . $bookingType .
        '<br/><b>From: </b>' . $arr['fromCity'] .
        '<br/><b>To: </b>' . $arr['toCity'] .
        '<br/><b>Pickup Address: </b>' . $arr['pickupAddress'] .
        '<br/><b>Pickup Time: </b>' . $arr['pickupFormattedMonthDate'] . $arr['pickupTime'] .
        $dropArea .
        $returnTime .
        '<br/><b>Cab: </b>' . $arr['cabType'] .
        '<br/><b>Primary Phone: </b>' . $arr['primaryPhone'] .
        $amount .
        '<br/><br/>You will receive the cab details 3 hours before your scheduled pickup time.<br/>' .
        '<br/>You can contact us at +91 90518-77-000 or email us at info@aaocab.com for any queries.<br/>' .
        '<br/>Regards,' .
        '<br/>aaocab<br/><br/>' .
        '<br/>For updates and promotions, like us on <a href="http://www.facebook.com/aaocab">facebook</a> , follow us on <a href="http://www.twitter.com/aaocab">twitter</a> or <a href="https://plus.google.com/113163564383201478409">google+</a> . Who knows you might get a free ride sometime? ;)<br/><br/>';

$msg.=$info;


echo $msg;
