<?php
$statusList	 = Booking::model()->getBookingStatus();
$datazone	 = Zones::model()->getZoneArrByFromBooking();
?>
<style>
    .checkbox{
        display:inline;
    }
</style>
<div class="panel panel-default">
</div>
<div class="panel panel-default">
    <div class="panel-body p0" >
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->pageSize = 1000;
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'requestVendorGrid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row '>
							<div class='col-xs-12 col-sm-5'>{summary}</div>
							<div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body'>{items}</div>
							<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-5 p5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//'ajaxType' => 'POST',
				'columns'			 => array(
					array('name'	 => 'bookingId', 'value'	 => function ($data) {
							if ($data['bookingId'] != null)
							{
								echo CHtml::link($data['bookingId'], Yii::app()->createUrl("admpnl/booking/view", ["id" => $data['bookingId']]), ['target' => '_blank']);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking ID'),
					array('name' => 'createDate', 'value' => '$data["createDate"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Create Date'),
					array('name' => 'pickupDate', 'value' => '$data["pickupDate"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date'),
					array('name' => 'fromCity', 'value' => '$data["fromCity"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'From City'),
					array('name' => 'toCity', 'value' => '$data["toCity"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'To City'),
					array('name' => 'totalBookingAmt', 'value' => '$data["totalBookingAmt"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Amount'),
					array('name' => 'GozoAmount', 'value' => '$data["GozoAmount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Gozo Amount'),
					array('name' => 'Profit', 'value' => '$data["Profit"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Profit'),
					array('name' => 'serviceLabel', 'value' => '$data["serviceLabel"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Service Type'),
					array('name' => 'bookingType', 'value' => '$data["bookingType"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Type'),
					array('name' => 'surgeFactor', 'value' => '$data["surgeFactor"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Surge Factor'),
			)));
		}
		?>
    </div>
</div>
<script type="text/javascript">
</script>