<?php 
$disabled ="";
if (!in_array($model->bkg_status, [15, 2]))
{
	
	$disabled	 = "disabled";
}
$trackevent	 = ['101'	 => 'Trip started', '102'	 => 'Trip paused',
			'103'	 => 'Trip resumed', '104'	 => 'Trip completed',
			'201'	 => 'Driver on the way to pickup point', '202'	 => 'Not Going For Pickup', '203'	 => 'Driver arrived at pickup point',
			'204'	 => 'NoShow', '205'	 => 'Wait', '206'	 => 'NoShow Reset', '301'	 => 'SOS Start',
			'302'	 => 'SOS Resolved'];

$form					 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingadditionalinfo', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
?>
<div class="card-body" style="<?php echo $show; ?>">
<?php
	echo $form->hiddenField($bookingModel, 'bkg_id', ['id' => 'bkg_id4']);
	echo $form->hiddenField($bookingModel, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
	$drvStat      = DriverStats::model()->getLastLocation($bookingModel->bkgBcb->bcb_driver_id);
    $lastLocation =  $bookingModel->bkgTrack->btk_last_coordinates;
	?>

	<ul class="timeline mb-0" >
				<?php
				$tripStatus = [];
				foreach ($trackLogmodel as $value)
				{
					$tripClass =  BookingTrackLog::getClassByTripEvent($value['btl_event_type_id']);
					$coordinates = (explode(",",$value['btl_coordinates']));
					$latLong = round($coordinates[0],4).','.round($coordinates[1],4); 
					array_push($tripStatus, $value['btl_event_type_id']);
					?> 
					<li class="timeline-item active pb5 <?= $tripClass ?>">
						<h6 class="timeline-title weight500 font-14"><?php echo $trackevent[$value['btl_event_type_id']]; ?></h6>
						<div class="ml20 mt5 font-12" style="word-wrap: break-word; word-break: break-all;">
						<img src="/images/bx-calendar.svg" alt="img" width="12" height="12"> <?php echo date("d/m/Y h:i A",strtotime($value['btl_sync_time'])) ?> <a href="https://maps.google.com/?q=<?php echo $value['btl_coordinates']; ?>" target="_blank" class="color-black"><img src="/images/bxs-map.svg" alt="img" width="12" height="12" class="ml-1"><?= $latLong?></a>
						<?php if($value['btl_event_type_id'] == 201){ ?>
<!--						<i class="bx bxs-user ml-1 font-12"></i>-->
						<?php

							}
							?>

						</div>
					</li>
					<?php
				}
				
				if($bookingModel->bkg_status == 5 && !in_array(104, $tripStatus))
				{
				?>
				<a href="https://maps.google.com/?q=<?php echo $lastLocation; ?>" target="_blank" class="btn btn-sm btn-primary mb-1 mt-3"><span>Show current location of driver</span></a>
				<?php
              
                
                
                } ?>	
	</ul>
</div>
<?php $this->endWidget(); ?>