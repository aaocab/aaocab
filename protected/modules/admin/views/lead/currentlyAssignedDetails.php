<?php 
//echo "<pre>";
//print_r($model); 
//echo "</pre>";
?>
<div class="row">
<div class="col-xs-12">
<br>
<p><b>Booking Id:</b>  <?php echo $model['bkg_booking_id']?></p>
<p><b>User Name:</b>  <?php echo $model['bkg_user_name'];?>&nbsp; <?php echo $model['bkg_user_lname'];?></p>
<p><b>Route_name:</b> <?php echo $model['route_name'];?></p>
<p><b>From city/To city:</b> <?php echo $model['from_city'];?>&nbsp; <?php echo $model['to_city'];?></p>
<p><b>User Phone:</b> <?php echo $model['bkg_country_code'];?>&nbsp; <?php echo $model['bkg_contact_no'];?></p>
<p><b>User Email:</b> <?php echo $model['bkg_user_email'];?></p>
<p><b>Booking Type:</b> <?php echo $model['booking_type'];?></p>
<p><b>Pickup date:</b> <?php echo $model['pick_date'];?></p>
<p><b>Return date:</b> <?php echo $model['return_date'];?></p>
<p><b>Created date:</b> <?php echo $model['bkg_create_date'];?></p>
</div>
</div>