<?php
$GLOBALS['time'][$status . "_81"]	 = [];
?><?php
$dataProvider						 = $provider['data'];
$label								 = '';



if (in_array($status, [6, 7]))
{
//	$count	 = $provider['Oct15']->getTotalItemCount();
//	$label	 = "$count/";
}
if (!empty($dataProvider))
{

	$platformArr		 = Booking::model()->booking_platform;
	$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
	$time				 = Filter::getExecutionTime();

	$GLOBALS['time'][$status . "_81"][1] = $time;
	$params1							 = $dataProvider->getPagination()->params + ['tab' => $status];
	$this->widget('booster.widgets.TbExtendedGridView', array(
		'id'				 => 'bookingTab' . $status,
		'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/list', $params1)),
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'fixedHeader'		 => true,
		'headerOffset'		 => 120,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
						<div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
						<div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
						</div></div>
						<div class='panel-body table-responsive'>{items}</div>
						<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table items table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		// 'ajaxType' => 'POST',
		'columns'			 => array(
			array(
				'class'			 => 'CCheckBoxColumn',
				'header'		 => 'html',
				'id'			 => 'booking_id' . $status,
				'selectableRows' => '{items}',
				'selectableRows' => 2,
				'value'			 => '$data["bkg_id"]',
				'headerTemplate' => '<label>{item}<span></span></label>',
				'visible'		 => ($status == 5),
				'htmlOptions'	 => array('style' => 'width: 20px'),
			),
			array('name'	 => 'bkg_bcb_id', 'value'	 => function($data) {
					if ($data['bkg_status'] >= 1)
					{

						echo CHtml::link($data['bkg_bcb_id'], Yii::app()->createUrl("rcsr/booking/triprelatedbooking", ["tid" => $data['bkg_bcb_id']]), ["class" => "viewRelatedBooking", "onclick" => "return viewRelatedBooking(this)"]);
						if ($data['bcb_trip_type'] == 1)
						{
							echo "<br>";
							echo '<span class="label label-primary">Matched</span>';
						}
					}
				}, 'sortable'							 => true, 'visible'							 => ($status >= 2), 'htmlOptions'						 => array('class' => 'text-center'), 'headerHtmlOptions'					 => array('class' => 'text-center'), 'header'							 => 'Trip Id'),
			array('name'	 => 'bkg_booking_id', 'type'	 => 'raw', 'value'	 => function($data) {
					if ($data['bkg_booking_id'] != '')
					{
						echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("rcsr/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);
						echo "<br>";
						if ($data['bkg_agent_ref_code'] != '')
						{
							echo '(' . $data['bkg_agent_ref_code'] . ')';
						}
						if ($data['bkg_status'] == 1 && $data['bkg_assign_csr'] > 0)
						{
							echo $data['csrName'];
						}
						if ($data['bkg_user_id'] != NULL)
						{
							if (($data['usr_mark_customer_count']) > 0)
							{
								echo '<img src="/images/icon/bad_customer_1.png" width="26" style="cursor:pointer" title="Bad Customer">';
							}
						}
						if ($data['bcb_cab_id'] != NULL)
						{
							if (($data['badCabCount']) > 0)
							{
								echo '<img src="/images/icon/bad_car1.png" width="26" style="cursor:pointer" title="Bad Cab">';
							}
						}
						if ($data['bcb_driver_id'] != NULL)
						{
							if (($data['badDriverCount']) > 0)
							{
								echo '<img src="/images/icon/bad_driver_1.png" width="26" style="cursor:pointer" title="Bad Driver">';
							}
						}
						if ($data['bcb_vendor_id'] != NULL)
						{
							if (($data['badVendorCount']) > 0)
							{
								echo '<img src="/images/icon/bad_vendor_1.png" width="26" style="cursor:pointer" title="Bad Vendor">';
							}
						}
						if ($data['bkg_advance_amount'] != NULL && $data['bkg_advance_amount'] > 0)
						{
							echo '<img src="/images/icon/advance_payment.png" width="26" style="cursor:pointer" title="Advance Payment">';
						}
						if ($data['bkg_tentative_booking'] != NULL && $data['bkg_tentative_booking'] == 1)
						{
							echo '<img src="/images/icon/tentative_booking.png" width="26" style="cursor:pointer" title="Tentative Booking">';
						}
						if ($data['bkg_manual_assignment'] != NULL && $data['bkg_manual_assignment'] == 1)
						{
							echo '<img src="/images/icon/manual_assiement.png" width="26" style="cursor:pointer" title="Manual Assignment">';
						}
						if ($data['bkg_is_related_booking'] != NULL && $data['bkg_is_related_booking'] > 0)
						{
							echo '<img src="/images/icon/related_bookings.png" width="26" style="cursor:pointer" title="Related Booking">';
						}
						if ($data['bkg_agent_id'] != NULL && $data['bkg_agent_id'] > 0)
						{
							$agentsModel = Agents::model()->findByPk($data['bkg_agent_id']);
							if ($agentsModel->agt_type == 1)
							{
								echo '<img src="/images/icon/affiliates.png" width="26" style="cursor:pointer" title="Corporate Booking">';
							}
							else
							{
								$agentname = $agentsModel->agt_fname . ' ' . $agentsModel->agt_lname . " (" . $agentsModel->agt_company . ")";
								echo ' <span class="label label-info" title="' . $agentname . '">partner</span>';
							}
						}

						if ($data['bkg_reconfirm_flag'] != NULL && $data['bkg_reconfirm_flag'] == 1)
						{
							echo '<img src="/images/icon/reconfirmed.png" width="26" style="cursor:pointer" title="Reconfirmed Booking">';
						}
						if ($data['bkg_no_show'] != NULL && $data['bkg_no_show'] == 1)
						{
							echo '<img src="/images/icon/no_show.png" width="26" style="cursor:pointer" title="No Show">';
						}
						else if ($data['bkg_reconfirm_flag'] != NULL && $data['bkg_reconfirm_flag'] == 2)
						{
							echo '<img src="/images/icon/rescheduled_booking.png" width="26" style="cursor:pointer" title="Rescheduled Booking">';
						}

						if ($data['followupStatus'] > 0)
						{
							$ico		 = ($data['followupStatus'] == 1) ? "mark_settled" : "no_response_yet";
							$icoTitle	 = ($data['followupStatus'] == 1) ? "On" : "Pending";
							echo '<img src="/images/icon/' . $ico . '.png" width="26" style="cursor:pointer" title="Followup ' . $icoTitle . '">';
						}
					}
				}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking ID'),
			array('name' => 'bkg_user_name', 'value' => '$data["bkg_user_name"]." ".$data["bkg_user_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
			array('name'		 => 'bkg_contact_no', 'visible'	 => $checkContactAccess,
				'value'		 => function ($data) {
					if ($data['bkg_contact_no'] != '')
					{
						return '+' . $data['bkg_country_code'] . $data['bkg_contact_no'];
					}
				},
				'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Phone'),
			array('name'				 => 'bkg_user_email', 'visible'			 => $checkContactAccess, 'htmlOptions'		 => array('style' => 'word-break: break-all'),
				'value'				 => '$data["bkg_user_email"]', 'sortable'			 => false,
				'headerHtmlOptions'	 => array(), 'header'			 => 'Email'),
			array('name' => 'bkgFromCity.cty_name', 'value' => '$data["fromCities"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From City'),
			array('name' => 'bkgToCity.cty_name', 'value' => '$data["toCities"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To City'),
			array('name'	 => 'bkg_total_amount', 'value'	 => function($data) {
					echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
				}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Amount'),
			array('name'	 => 'bkg_advance_amount', 'value'	 => function($data) {
					if ($data['bkg_advance_amount'] > 0)
					{
						echo '<i class="fa fa-inr"></i>' . round($data['bkg_advance_amount']);
					}
					else
					{
						echo '<i class="fa fa-inr"></i>' . '0';
					}
				}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Advance Paid'),
			array('name'	 => 'bkg_platform',
				'value'	 => function ($data) {
					$sourceArr = Booking::model()->booking_platform;
					return $sourceArr[$data['bkg_platform']];
				}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Source'),
			array('name'	 => 'bkg_user_city', 'type'	 => 'raw',
				'value'	 => function ($data) {
					$ip = '';
					if ($data['bkg_user_ip'] != '')
					{
						$ip		 = $data['bkg_user_ip'];
						$user_ip = (strpos($ip, ' ')) ? $ip : str_replace(',', ', ', $ip);
						$city	 = '';
						if ($data['bkg_user_city'] != '')
						{
							$city = $data['bkg_user_city'] . ', ';
						}
						if ($data['bkg_user_country'] != '')
						{
							$city .= $data['bkg_user_country'] . '<br>';
						}

						return $city . '<span style = "word-break: break-all">(' . $user_ip . ')</span>';
					}
					return $ip;
				},
				'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'User City'),
			array('name'	 => 'bkg_create_date',
				'value'	 => function ($data) {
					return DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
				}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking Date/Time'),
			//   array('name' => 'bkg_pickup_address', 'value' => '$data->bkg_pickup_address', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Pickup Address'),
			array('name'	 => 'bkg_pickup_date',
				'value'	 => function ($data) {
					return DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
				},
				'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Pickup Date/Time'),
			array('name' => 'bkg_vendor_name', 'value' => '$data["vendorName"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
			array('name' => 'bkg_vehicle_id', 'value' => '$data["cabType"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
		//  array('name' => 'Action', 'type' => 'raw',
		//  'value' => 'Booking::model()->getActionButton($data)'
		//   , 'sortable' => false, 'htmlOptions' => array('class' => 'action_box'), 'headerHtmlOptions' => array('style' => 'min-width:150px;'), 'header' => 'Action'),
	)));
}
$time = Filter::getExecutionTime();

$GLOBALS['time'][$status . "_81"][2] = $time;
?>
<script type="text/javascript">

    function viewRelatedBooking(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }

    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['bookingTab<?= $status ?>'] != undefined)
    {
        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['bookingTab<?= $status ?>'].updateSelector);
    }
    $('#bkgCount<?= $status ?>').html("<?= $label . $dataProvider->getTotalItemCount() ?>");
    addTabCache(<?= $status ?>);
<?php
if ($labels != '')
{
	echo 'updateTabLabel(' . json_encode($labels) . ');';
}
?>


    $("#booking_id<?= $status ?>_all").click(function () {
        if (this.checked) {
            $('#bookingTab<?= $status ?> .checker span').addClass('checked');
            $('#bookingTab<?= $status ?> input[name="booking_id[]"]').attr('checked', 'true');
        } else {
            $('#bookingTab<?= $status ?> .checker span').removeClass('checked');
            $('#bookingTab<?= $status ?> input[name="booking_id[]"]').attr('checked', 'false');

        }
    });
</script>
<?php
if ($status == 5)
{
	?>
	<div class="col-xs-12">
		<button type="button" class="btn btn-success" onclick="setMarkComplete();">Mark Complete</button>
	</div>
	<?php
}
$time = Filter::getExecutionTime();

$GLOBALS['time'][$status . "_81"][3] = $time;
?>			