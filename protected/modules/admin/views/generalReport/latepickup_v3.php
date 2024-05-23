<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-2">
        <h3><?=$title?></h3>
    </div>
</div>

<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
													<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div></div>
											<div class='panel-body table-responsive'>{items}</div>
											<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 =>
		array
			(
			array('name'	 => 'Trip ID', 'value'	 => function ($data)
				{
					echo CHtml::link($data['bkg_bcb_id'], Yii::app()->createUrl("admin/booking/triprelatedbooking", ["tid" => $data['bkg_bcb_id']]), ["class" => "viewRelatedBooking", "onclick" => "return viewRelatedBooking(this)"]);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Trip ID'),
			array('name'	 => 'Booking ID', 'value'	 => function ($data)
				{

					echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking ID'),
			array('name'	 => 'bkg_pickup_date', 'value'	 => function($data)
				{
					echo date('d/m/Y H:i:s', strtotime($data['bkg_pickup_date'])) . "</br>";

					$nowhr			 = date('Y-m-d H:i:s', strtotime('+0 min'));
					$pickupDateTime	 = date('Y-m-d H:i:s', strtotime($data['bkg_pickup_date']));
					if ($nowhr > $pickupDateTime)
					{
						$diff_time = ROUND((strtotime(date("Y-m-d H:i:s")) - strtotime($data['bkg_pickup_date'])) / (60), 2);
						echo "<span = style='color:#FF0000'>-" . $diff_time . "min" . "</span>";
					}
					else
					{
						$diff_time = ROUND((strtotime($data['bkg_pickup_date']) - strtotime(date("Y-m-d H:i:s"))) / (60), 2);
						echo $diff_time . "min";
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Pickup Date'),
			array('name'	 => 'Contact', 'value'	 => function ($data)
				{

					echo CHtml::link($data['drv_code'], Yii::app()->createUrl("admin/driver/view", ["id" => $data['drv_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);


					//return CHtml::image($path, $data['drv_name'], ['style' => 'width: 50px']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'DriverInfo'),
			array('name'	 => 'Contact', 'value'	 => function($data)
				{
					echo CHtml::link($data['vnd_code'], Yii::app()->createUrl("admin/vendor/profile", ["id" => $data['vnd_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'VendorInfo'),
			array('name'	 => 'Pickup Coordinate', 'value'	 => function($data)
				{
					$bmodel = Booking::model()->findByPk($data['bkg_id']);


					$coordinateC = $bmodel->bookingRoutes[0]->brt_from_latitude . ',' . $bmodel->bookingRoutes[0]->brt_from_longitude;
					$coordinateD = $data['drv_last_loc_lat'] . ',' . $data['drv_last_loc_long'];
					echo "<a target='_blank' href='https://google.com/maps/dir/?api=1&origin=$coordinateD&destination=$coordinateC'>" . "Click to show" . "</a></br>" . date('d/m/Y H:i:s', strtotime($data['drv_last_loc_date']));
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Driver Location'),
			
array('name'	 => 'Last State', 'value'	 => function($data)
				{
$arr =BookingLog::model()->eventList();
					echo $arr[$data['btk_last_event']];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Current Substatus'),

array('name'	 => 'Distance', 'value'	 => function($data)
				{
					$bmodel			 = Booking::model()->findByPk($data['bkg_id']);
					$place			 = new \Stub\common\Place();
					$sourcePlace	 = $place->init($bmodel->bookingRoutes[0]->brt_from_latitude, $bmodel->bookingRoutes[0]->brt_from_longitude);
					$destPlace		 = $place->init($data['drv_last_loc_lat'], $data['drv_last_loc_long']);
					$dmxModel		 = \DistanceMatrix::getByCoordinates($sourcePlace, $destPlace);
					$distanceLeft	 = $dmxModel->dmx_distance;
					if ($bmodel->bookingRoutes[0]->brt_from_latitude && $data['drv_last_loc_lat'])
					{
						if($distanceLeft < 25)
						{
							$estimatedTime = $distanceLeft * 5;
						}
						elseif($distanceLeft > 25) 
						{
							$estimatedTime = $distanceLeft * 2;
						}
						echo "Estimated distance " . $distanceLeft . "Km </br>";
						echo "Estimated time " . $estimatedTime . "min to travel";
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Time and distance to travel'),
			array('name'	 => 'Distance', 'value'	 => function($data)
				{
					$delay			 = "N/A";
					$bmodel			 = Booking::model()->findByPk($data['bkg_id']);
					$place			 = new \Stub\common\Place();
					$sourcePlace	 = $place->init($bmodel->bookingRoutes[0]->brt_from_latitude, $bmodel->bookingRoutes[0]->brt_from_longitude);
					$destPlace		 = $place->init($data['drv_last_loc_lat'], $data['drv_last_loc_long']);
					$dmxModel		 = \DistanceMatrix::getByCoordinates($sourcePlace, $destPlace);
					$distanceLeft	 = $dmxModel->dmx_distance;
					if ($data['drv_last_loc_lat'] != "" && $bmodel->bookingRoutes[0]->brt_from_latitude != "")
					{
						if ($bmodel->bookingRoutes[0]->brt_from_latitude && $data['drv_last_loc_lat'])
						{
							$travelTime = ($distanceLeft * 10);
						}


						$nowhr			 = date('Y-m-d H:i:s', strtotime('+0 min'));
						$pickupDateTime	 = date('Y-m-d H:i:s', strtotime($data['bkg_pickup_date']));
						if ($nowhr > $pickupDateTime)
						{
							$diff_time	 = ROUND((strtotime(date("Y-m-d H:i:s")) - strtotime($data['bkg_pickup_date'])) / (60), 2);
							$lateTime	 = $diff_time;
							if ($lateTime > 0)
							{
								$delay = $lateTime + $travelTime . "min";
							}
//							if ($travelTime - $lateTime > 0)
//							{
//								$delay = $travelTime - $lateTime . "min";
//							}
							else
							{
								$delay = "No delay";
							}
						}
						else
						{
							$diff_time	 = ROUND((strtotime($data['bkg_pickup_date']) - strtotime(date("Y-m-d H:i:s"))) / (60), 2);
							$lateTime	 = $diff_time;
							if ($travelTime - $lateTime > 0)
							{
								$delay = $travelTime - $lateTime . "min";
							}
							else
							{
								$delay = "No delay";
							}
						}
					}
					echo $delay;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Delay vs Plan(in minutes)'),
		)
	));
}
?>


