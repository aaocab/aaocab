<?php
$apiURL = 'http://cabs-internal.makemytrip.com/updateCabDriverDetail';
$jsonData = file_get_contents('php://input');
$ch = curl_init($apiURL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'auth-id: GOZO',
    'auth-token: f2c8adff-5f4b-4e54-98fe-678129329ad9')
);
$jsonResponse = curl_exec($ch);
echo $jsonResponse;
?>
