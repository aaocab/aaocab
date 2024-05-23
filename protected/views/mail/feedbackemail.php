<?

$msg = "";
$msg = 'Hi ' . $arr['userName'] .
        ',<br/><br/>We hope your travel was good and you would give us a chance to serve you again in future. 
        <br/>We would love it if you give your valuable feedback/review for the BookingId : "' . $arr['bookingId'] . '" by visiting <a href="' . $arr['reviewlink'] .
        '">' . $arr['reviewlink'] . '</a> <br/><br/>
            Also visit at our
         <a href="https://www.facebook.com/gozocabs">' .
        'Facebook</a> page.' .
        '<br/>Regards,' .
        '<br/>Gozocabs Support' .
        '<br/>+91-90518-77-000<br/><br/>' .
        '<br/>For updates and promotions, like us on <a href="https://www.facebook.com/gozocabs">facebook</a> , follow us on <a href="http://www.twitter.com/gozocabs">twitter</a> or <a href="https://plus.google.com/113163564383201478409">google+</a> . Who knows you might get a free ride sometime? ;)<br/><br/>';
echo $msg;
