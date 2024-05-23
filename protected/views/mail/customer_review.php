<div style="width: 95%; padding:10px; overflow: auto;  border: 1px #aaa solid;color: #444;">
    <p style="text-align:center; color: #CC0000;">Replying to this email will send your reply to the customer at <?= $arr['rtg_customer_email']; ?></p>
	<p>A new review is rated by customer for Booking id:<b><?= $arr['booking_id']; ?></b></p>
    <p>Details are as follows :-</p>
    <p>Customer asked to contact Immediately. - <?php echo ($arr['contact_gozo'] > 0) ? 'Yes' : 'No'; ?></p>
    <p>Booking Type : <?php echo ($arr['booking_type']); ?> </p>
	<p>Customer Recommendation : <?php echo $arr['rtg_customer_recommend']; ?></p>
	<p>Overall Rating : <?php echo $arr['rtg_customer_overall']; ?>
		<?php
		if ($arr['rtg_customer_driver'] > 0)
		{
			echo '<p>Driver Experience : ' . $arr['rtg_customer_driver'] . '</p>';
		}
		?>
		<?php
		if ($arr['rtg_customer_csr'] > 0)
		{
			echo '<p>CSR Experience : ' . $arr['rtg_customer_csr'] . '</p>';
		}
		?>
		<?php
		if ($arr['rtg_customer_car'] > 0)
		{
			echo '<p>Car Quality : ' . $arr['rtg_customer_car'] . '</p>';
		}
		?>
	<p>Date : <?= $arr['date']; ?></p>
	<?php
	if (isset($arr))
	{
		?>
		<div style="float: left;">    

			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Car #</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo $arr['vht_model'] . ' - ' . $arr['vhc_number']; ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">&nbsp;</div>
				<div style="width: 65%; float: left; padding: 8px"></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer Name</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo $arr['user_name']; ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer Route</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo $arr['customer_route']; ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer Phone</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo ($arr['bkg_country_code'] . " " . $arr['bkg_contact_no']); ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer Email</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo $arr['bkg_user_email']; ?></div>
			</div>
			
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer Joining Date</div>
				<div style="width: 65%; float: left; padding: 8px"><?php if($arr['first_trip_date']!=''){ echo date('d-m-Y h:i A ', strtotime($arr['first_trip_date'])); } ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer #Trips  (Including)</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo $arr['total_trip']; ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer Last Trip</div>
				<div style="width: 65%; float: left; padding: 8px"><?php if($arr['last_trip_date']!=''){  echo date('d-m-Y h:i A ', strtotime($arr['last_trip_date'])); } ?></div>
			</div>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="width: 26%; float: left; border-right: #e0e0e0 1px solid; padding: 8px;">Customer's Avg Rating (Received)</div>
				<div style="width: 65%; float: left; padding: 8px"><?php echo $arr['user_rating']; ?></div>
			</div>
		</div>
		<?php
	}
	?>
    <p>&nbsp;</p>
	<?php
	if (($arr['rtg_customer_overall'] < 4) && ($arr['rtg_customer_driver'] <> NULL && $arr['rtg_customer_driver'] < 5))
	{
		$drvGoodAttr = RatingAttributes::getAttrByIds(1, $arr['rtg_driver_good_attr'], 1);
		$drvBadAttr	 = RatingAttributes::getAttrByIds(1, $arr['rtg_driver_bad_attr'], 2);
		?>  

		<div style="float: left; width: 99%; margin-bottom: 10px;">
			<b>Driver Experience</b></br>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="border-right: #f3f3f3 1px solid; width: 48%; float: left;">
					<div style="width: 100%; float: left; color: #008000; border-bottom: #f3f3f3 1px solid;  padding: 8px;"><b>What was good?</b></div>
					<div style="width: 100%; float: left; color: #000; padding: 8px;">
						<?php
						$ctr = 1;
						foreach ($drvGoodAttr as $dgood)
						{
							$str = (count($drvGoodAttr)==$ctr) ? 	' .' : ' ,';
							?>
							<span style="float: left; padding: 0 4px 4px 4px;"><?= $dgood; ?>  <?=$str;?></span>
						<?php 
						$ctr++;
						}
						?>
					</div>
				</div>
				<div style="width: 48%; float: left;">
					<div style="width: 100%; float: left; color: #DC143C; border-bottom: #f3f3f3 1px solid; padding: 8px;"><b>What was not?</b></div>
					<div style="width: 100%; float: left; color: #000;">
						<?php
						$ctr=1;
						foreach ($drvBadAttr as $dbad)
						{
							$str = (count($drvBadAttr)==$ctr) ? 	' .' : ' ,';
							?>
							<span style="float: left; padding: 8px;"><?= $dbad; ?>  <?=$str;?></span>
						<?php 
						$ctr++;
						}
						?>
					</div>
				</div>
				<div style="width: 98%; float: left; padding: 8px; border-top: #f3f3f3 1px solid;">
					Driver Comment : <?= $arr['rtg_driver_cmt']; ?>
				</div>
			</div>
		</div>

		<?php
	}
	if (($arr['rtg_customer_overall'] < 4) && ($arr['rtg_customer_car'] <> NULL && $arr['rtg_customer_car'] < 5))
	{
		$carGoodAttr = RatingAttributes::getAttrByIds(3, $arr['rtg_car_good_attr'], 1);
		$carBadAttr	 = RatingAttributes::getAttrByIds(3, $arr['rtg_car_bad_attr'], 2);
	?>
		<div style="float: left; width: 99%; margin-bottom: 10px;">
			<b>Car Experience</b></br>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="border-right: #f3f3f3 1px solid; width: 48%; float: left;">
					<div style="width: 100%; float: left; color: #008000; border-bottom: #f3f3f3 1px solid;  padding: 8px;"><b>What was good?</b></div>
					<div style="width: 100%; float: left; color: #000; padding: 8px;">
						<?php
						$ctr=1;
						foreach ($carGoodAttr as $carg)
						{
							$str = (count($carGoodAttr)==$ctr) ? 	' .' : ' ,';
							?>
							<span style="float: left; padding: 0 4px 4px 4px;"><?= $carg;?> <?=$str;?></span>
						<?php 
						$ctr++;
						}
						?>
					</div>
				</div>
				<div style="width: 48%; float: left;">
					<div style="width: 100%; float: left; color: #DC143C; border-bottom: #f3f3f3 1px solid; padding: 8px;"><b>What was not?</b></div>
					<div style="width: 100%; float: left; color: #000;">
						<?php
						$ctr=1;
						foreach ($carBadAttr as $carb)
						{
							$str = (count($carBadAttr)==$ctr) ? 	' .' : ' ,';
						?>
							<span style="float: left; padding: 8px;"><?= $carb; ?> <?=$str;?></span>
						<?php 
						}
						$ctr++;
						?>
					</div>
				</div>
				<div style="width: 98%; float: left; padding: 8px; border-top: #f3f3f3 1px solid;">
					Car Comment : <?= $arr['rtg_car_cmt']; ?>
				</div>
			</div>
		</div>





		<?php
	}
	if (($arr['rtg_customer_overall'] < 4) && ($arr['rtg_customer_csr'] <> NULL && $arr['rtg_customer_csr'] < 5))
	{
		$csrGoodAttr = RatingAttributes::getAttrByIds(2, $arr['rtg_csr_good_attr'], 1);
		$csrBadAttr	 = RatingAttributes::getAttrByIds(2, $arr['rtg_csr_bad_attr'], 2);
		?>

		<div style="float: left; width: 99%; margin-bottom: 10px;">
			<b>CSR Experience</b></br>
			<div style="border: #e0e0e0 1px solid; width: 100%; float: left;">
				<div style="border-right: #f3f3f3 1px solid; width: 48%; float: left;">
					<div style="width: 100%; float: left; color: #008000; border-bottom: #f3f3f3 1px solid;  padding: 8px;"><b>What was good?</b></div>
					<div style="width: 100%; float: left; color: #000; padding: 8px;">
						<?php
						$ctr=1;
						foreach ($csrGoodAttr as $csrg)
						{
							$str = (count($csrGoodAttr)==$ctr) ? 	' .' : ' ,';
						?>
							<span style="float: left; padding: 0 4px 4px 4px;"><?= $csrg; ?> <?=$str;?></span>
						<?php 
						$ctr++;
						}
						?>
					</div>
				</div>
				<div style="width: 48%; float: left;">
					<div style="width: 100%; float: left; color: #DC143C; border-bottom: #f3f3f3 1px solid; padding: 8px;"><b>What was not?</b></div>
					<div style="width: 100%; float: left; color: #000;">
						<?php
						$ctr=1;
						foreach ($csrBadAttr as $csrb)
						{
							$str = (count($csrBadAttr)==$ctr) ? 	' .' : ' ,';
						?>
							<span style="float: left; padding: 8px;"><?= $csrb; ?> <?=$str;?></span>
						<?php 
						$ctr++;
						}
						?>
					</div>
				</div>
				<div style="width: 98%; float: left; padding: 8px; border-top: #f3f3f3 1px solid;">
					CSR Comment : <?= $arr['rtg_csr_cmt']; ?>
				</div>
			</div>
		</div>




		<?php
	}
	?>
    <p>Website & App : <?= $arr['platform']; ?></p>
    <p>Overall Comment : <?= $arr['rtg_customer_review']; ?></p>
    <br><br>
	<p>OFFICE USE ONLY: V///<?= $arr['vnd_code']; ?>&R#<?= $arr['vendor_rating']; ?>\\\D///<?= $arr['drv_code']; ?>&A#<?= $arr['driver_is_approved']; ?>&R#<?= $arr['driver_rating']; ?>\\\C///<?= $arr['vhc_code']; ?>?&A#<?= $arr['vehicle_is_approved']; ?>&CC#<?= $arr['vhc_is_commercial']; ?>&T#<?= $arr['total_trip_by_car']; ?>\\\BKG-<?= Filter::formatBookingId($arr['bkg_booking_id']); ?></p>