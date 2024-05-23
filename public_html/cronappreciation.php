<?php
$conn = new mysqli('localhost', 'root', 'anupam', 'gozocabs');
// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}
$getId = $conn->query("SELECT rtg_booking_id FROM ratings WHERE rtg_customer_overall='5'");
$rows = mysqli_fetch_array($getId);
$ext='91';
foreach($rows as $id){
	$model = Booking::model()->findByPk($id);
	$bookingID = $model->bkg_booking_id;
	$vendorNumber = $model->bkgAgent->agt_phone;
	$vendorName = $model->bkgAgent->agt_name;
	$driverNumber= $model->bkgDriver->drv_phone;
	$driverNumber= $model->bkgDriver->drv_name;
	$msgCom = new smsWrapper();
	$msgCom->sendAppreciationMessageVendor($ext,$vendorNumber,'Vendor',$bookingId,$vendorName,$driverName);
	$msgCom->sendAppreciationMessageDriver($ext,$driverNumber,'Driver',$bookingId,$driverName);
}
?>

