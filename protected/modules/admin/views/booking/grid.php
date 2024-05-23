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
	static::$cabTypeList = BookingController::GetVehicleCache();
	$serviceTypeList	 = ServiceClass::model()->getList('filter');
	$platformArr		 = Booking::model()->booking_platform;
	$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
	$time				 = Filter::getExecutionTime();

	$GLOBALS['time'][$status . "_81"][1] = $time;
	$GLOBALS['isShowPh']				 = Config::model()->getAccess('CUST_PHONE_ADMIN_VISIBLE');
	$params1							 = $dataProvider->getPagination()->params + ['tab' => $status];
	$this->widget('booster.widgets.TbExtendedGridView', array(
		'id'				 => 'bookingTab' . $status,
		'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/list', $params1)),
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
			array('name'	 => 'bkg_bcb_id', 'value'	 => function ($data) {
					if ($data['bkg_status'] >= 1)
					{

						echo CHtml::link($data['bkg_bcb_id'], Yii::app()->createUrl("admin/booking/triprelatedbooking", ["tid" => $data['bkg_bcb_id']]), ["class" => "viewRelatedBooking", "onclick" => "return viewRelatedBooking(this)"]);
						if ($data['bcb_trip_type'] == 1)
						{
							echo "<br>";
							echo '<span class="label label-primary">Matched</span>';
						}
					}
				}, 'sortable'							 => true, 'visible'							 => ($status >= 2), 'htmlOptions'						 => array('class' => 'text-center'), 'headerHtmlOptions'					 => array('class' => 'text-center'), 'header'							 => 'Trip Id'),
			array('name'	 => 'bkg_booking_id', 'type'	 => 'raw', 'value'	 => function ($data) use ($serviceTypeList) {
					if ($data['bkg_booking_id'] != '')
					{
						echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);

						echo "<br>";

						if ($serviceTypeList[$data['scv_scc_id']] == 'Value')
						{
							echo '<img src="/images/icon/Value.png"  style="cursor:pointer" title="Value">';
						}
						if ($serviceTypeList[$data['scv_scc_id']] == 'Value+')
						{
							echo '<img src="/images/icon/Value+.png"  style="cursor:pointer" title="Value+">';
						}
						if ($serviceTypeList[$data['scv_scc_id']] == 'Select')
						{
							echo '<img src="/images/icon/Select.png" style="cursor:pointer" title="Select">';
						}
						if ($serviceTypeList[$data['scv_scc_id']] == 'Select Plus')
						{
							echo '<img src="/images/icon/select+.png" style="cursor:pointer" title="Select">';
						}
						if ($serviceTypeList[$data['scv_scc_id']] == 'Economy')
						{
							echo '<img src="/images/icon/sccid6.png" style="cursor:pointer" title="Economy">';
						}
						echo "<br>";
						if ($data['bkg_is_gozonow'] == 1)
						{
							echo '<h5><span class="label label-info">GOZONOW</span></h5>';
						}
						echo "<br>";
						
						if(($data['bkg_agent_id'] == Config::get('transferz.partner.id')) && ($data['bkg_booking_type'] == 8))
						{	
							echo '(' . $data['bkg_agent_ref_code'] . ')';
						}
						elseif($data['bkg_agent_id'] == Config::get('transferz.partner.id') && ($data['bkg_booking_type'] != 8))
						{
							$transferzCode = TransferzOffers::model()->findByPk($data['bkg_agent_ref_code']);
							echo '(' . $transferzCode->trb_trz_journey_code . ')';
						}
						elseif($data['bkg_agent_ref_code'] != '')
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
							$modelDriver = Drivers::model()->findByPk($data['bcb_driver_id']);
							if ($modelDriver != false && $modelDriver->drv_mark_driver_count > 0)
							{
								echo '<img src="/images/icon/bad_driver_1.png" width="26" style="cursor:pointer" title="Bad Driver">';
							}
						}
						if ($data['bcb_vendor_id'] != NULL)
						{
							$modelVendorStats = VendorStats::model()->getbyVendorId($data['bcb_vendor_id']);
							if ($modelVendorStats != false && $modelVendorStats->vrs_mark_vend_count > 0)
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
						if ($data['bkg_manual_assignment'] != NULL && $data['bkg_manual_assignment'] == 1 && $data['bkg_critical_assignment'] == 0)
						{
							echo '<img src="/images/icon/manual_assiement.png" width="26" style="cursor:pointer" title="Manual Assignment">';
						}
						if ($data['bkg_critical_assignment'] != NULL && $data['bkg_critical_assignment'] == 1)
						{
							echo '<img src="/images/icon/critical_assignment.png" width="26" style="cursor:pointer" title="Critical Assignment">';
						}
						if ($data['bkg_sos_sms_trigger'] == 2)
						{
							echo '<img src="/images/icon/sos.png" width="26" style="cursor:pointer" title="SOS">';
						}
						if ($data['bkg_drv_sos_sms_trigger'] == 2)
						{
							echo '<img src="/images/icon/driver_sos.png" width="26" style="cursor:pointer" title="Driver SOS">';
						}
						if ($data['bkg_is_driver_loggedIn'] != NULL && $data['bkg_is_driver_loggedIn'] == 1)
						{
							echo '<img src="/images/icon/driver_use_app.png" width="26" style="cursor:pointer" title="Driver Logged-In">';
						}

						if ($data['bkg_fs_address_change'] != NULL && $data['bkg_fs_address_change'] == 1 && in_array($data['bkg_flexxi_type'], [1, 2]))
						{
							echo '<img src="/images/icon/address_change.png" width="26" style="cursor:pointer" title="Subscriber Pickup Address settled">';
						}

						if ($data['bkg_fs_address_change'] == 0 && $data['bkg_flexxi_type'] == 2)
						{
							echo '<img src="/images/icon/address.png" width="26" style="cursor:pointer" title="Subscriber Pickup Address Pending">';
						}

						if ($data['bkg_is_related_booking'] != NULL && $data['bkg_is_related_booking'] > 0)
						{
							echo '<img src="/images/icon/related_bookings.png" width="26" style="cursor:pointer" title="Related Booking">';
						}
						if ($data['bkg_duty_slip_required'] == 1)
						{
							echo '<img src="/images/icon/dutySlipOn.png" width="26" style="cursor:pointer" title="Duty Slip Required">';
						}
						if ($data['bkg_penalty_flag'] == 1)
						{
							echo '<img src="/images/icon/add_penalty.png" width="26" style="cursor:pointer" title="Penalty Review Needed">';
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

						if ($data['bkg_is_corporate'] != NULL && $data['bkg_is_corporate'] == 1)
						{
							echo ' <span class="label label-danger">corporate</span>';
						}

						if ($data['bkg_reconfirm_flag'] != NULL && $data['bkg_reconfirm_flag'] == 1)
						{
							echo '<img src="/images/icon/reconfirmed.png" width="26" style="cursor:pointer" title="Reconfirmed Booking">';
						}
						if ($data['bpr_uncommon_route'] != NULL && $data['bpr_uncommon_route'] == 1)
						{
							echo '<img src="/images/icon/uncommon_route.png" width="26" style="cursor:pointer" title="Uncommon Route bookings">';
						}
						if ($data['bpr_vnd_recmnd'] == 1)
						{
							echo '<img src="/images/icon/selfassigned.png" width="26" style="cursor:pointer" title="Self Assigned">';
						}
						if ($data['bkg_is_no_show'] != NULL && $data['bkg_is_no_show'] == 1)
						{
							echo '<img src="/images/icon/no_show.png" width="26" style="cursor:pointer" title="No Show">';
						}
						else if ($data['bkg_reconfirm_flag'] != NULL && $data['bkg_reconfirm_flag'] == 2)
						{
							echo '<img src="/images/icon/rescheduled_booking.png" width="26" style="cursor:pointer" title="Rescheduled Booking">';
						}

						if (($data['bkg_pickup_address'] == NULL && in_array($data['bkg_flexxi_type'], [1, 2]) == false ) || ($data['bkg_pickup_address'] == '' && in_array($data['bkg_flexxi_type'], [1, 2]) == false && $data['bkg_booking_type'] == 1))
						{
							echo '<img src="/images/icon/no_address1.png" width="26" style="cursor:pointer" title="No pickup address">';
						}
						if ($data['biv_refund_approval_status'] == 1)
						{
							echo '<img src="/images/icon/rfnd_app_pend.png" width="26" style="cursor:pointer" title="Refund Approval Pending">';
						}
						if ($data['followupStatus'] > 0)
						{
							$ico		 = ($data['followupStatus'] == 1) ? "mark_settled" : "no_response_yet";
							$icoTitle	 = ($data['followupStatus'] == 1) ? "On" : "Pending";
							echo '<img src="/images/icon/' . $ico . '.png" width="26" style="cursor:pointer" title="Followup ' . $icoTitle . '">';
						}
						if ($data['bkg_promo1_code'] == 'FLATRE1')
						{
							echo '<img src="/images/icon/sale.png" width="26" style="cursor:pointer" title="Flat ?1/- Sale">';
						}
						if ($data['bkg_flexxi_type'] == 1)
						{
							$subscriber = Booking::model()->getSubscriberByPromoterId($data['bkg_id']);
							if ($subscriber != '')
							{
								echo "<br><span style='font-weight: 12px'>" . "(" . $subscriber . ")</span>";
							}
						}
						else if ($data['bkg_flexxi_type'] == 2 && $data['bkg_bcb_id'] != '' && $data['bkg_promo_code'] != 'FLATRE1')
						{
							$code = Booking::model()->getCodeByBcbId($data['bkg_bcb_id'], $data['bkg_id']);
							if ($code != "")
							{
								echo "<br><span style='font-weight: 12px'>" . "(" . $code . ")</span>";
							}
						}
						if ($data['bkg_qr_id'] != NULL && $data['bkg_qr_id'] > 0)
						{
							echo '<br><img src="/images/icon/qr_code.png" width="26" style="cursor:pointer" title="QR Booking">';
						}
					}
				}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center', 'style' => 'word-break: break-word'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Booking ID'),
			array('name'	 => 'bkg_user_fname', 'value'	 => function ($data) use ($serviceTypeList) {
					if ($data['bkg_user_id'] != "" && $data['bkg_agent_id'] != '18190')
					{
						echo "<a href='" . Yii::app()->createUrl("admin/user/view", ["id" => $data['bkg_user_id']]) . "' target='_blank'>" . $data["ctt_first_name"] . " " . $data["ctt_last_name"] . "</a>";
						$rowUcm =  UserCategoryMaster::getByUserId($data['bkg_user_id']);
						if($rowUcm['ucm_id']!='')
						{
							$catCss = UserCategoryMaster::getColorByid($rowUcm['ucm_id']);
							echo '<span class="user-categoty ml5">'."<img src='/images/{$catCss}' alt='' width='25' title='{$rowUcm['ucm_label']}'>".'</span>';
						}
					}
					else
					{
						echo $data["ctt_first_name"] . " " . $data["ctt_last_name"];
					}
					$tagBtnList = '';
					if ($data['bkg_tags'] != '')
					{
						$tagBtnList = '<br>';
						$tagList = Tags::getListByids($data['bkg_tags']);
						foreach ($tagList as $tag)
						{
							if($tag['tag_color']!='')
							{
								 $tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' style='background:".$tag['tag_color']."'>" . $tag['tag_name'] . "</span>";
							}
							else
							{
								$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10'>" . $tag['tag_name'] . "</span>";
							} 
						}
		 			}
					echo $tagBtnList;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Name'),
			array('name'		 => 'bkg_contact_no', 'visible'	 => $checkContactAccess,
				'value'		 => function ($data) {
					$access = Booking::checkLeadContactAccess($data['bkg_status'], $data['bkg_assign_csr'], $GLOBALS["search"], $data['phn_phone_no'], $data['bkg_create_user_type'], $data['bkg_create_user_id']);
					if ($data['phn_phone_no'] != '' && $access)
					{
						return '+' . $data['phn_phone_country_code'] . $data['phn_phone_no'];
					}
				},
				'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Phone'),
			array('name'				 => 'bkg_user_email', 'visible'			 => $checkContactAccess, 'htmlOptions'		 => array('style' => 'word-break: break-all'),
				'value'				 => $data["eml_email_address"], 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Email'),
			array('name'	 => 'bkgFromCity.cty_name', 'value'	 => function ($data) {
					return implode(' - ', json_decode($data['toCityName']));
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Routes'),
			array('name'	 => 'bkg_total_amount', 'value'	 => function ($data) {
					echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
				}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Amount'),
			array('name'	 => 'bkg_advance_amount', 'value'	 => function ($data) {
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
					$sourceArr	 = Booking::model()->booking_platform;
					//$duration = Filter::getTimeDurationbyMinute($data['bkg_trip_duration']);
					echo $sourceArr[$data['bkg_platform']] . '<br/>KM: ' . $data['bkg_trip_distance'];
				}, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Source/ KM'),
//			array('name'	 => 'bkg_user_city', 'type'	 => 'raw',
//				'value'	 => function ($data) {
//					$ip = '';
//					if ($data['bkg_user_ip'] != '')
//					{
//						$ip		 = $data['bkg_user_ip'];
//						$user_ip = (strpos($ip, ' ')) ? $ip : str_replace(',', ', ', $ip);
//						$city	 = '';
//						if ($data['bkg_user_city'] != '')
//						{
//							$city = $data['bkg_user_city'] . ', ';
//						}
//						if ($data['bkg_user_country'] != '')
//						{
//							$city .= $data['bkg_user_country'] . '<br>';
//						}
//
//						return $city . '<span style = "word-break: break-all">(' . $user_ip . ')</span>';
//					}
//					return $ip;
//				},
//				'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'User City'),
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
			array('name'	 => 'bkg_vendor_name', 'type'	 => 'raw',
				'value'	 => function ($data) {
					//$data["vendorName"];
					//$pref = BookingPref::model()->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $data["bkg_id"]]); //$data["bkg_id"]
					$adminName = "Operation Manager";
					if ($data["bpr_assignment_id"] > 0)
					{
						$adminName = Admins::model()->findByPk($data["bpr_assignment_id"])->adm_fname;
					}
					if ($data["bpr_assignment_level"] == 1)
					{
						$prefResult = "Allocated CSR: " . $adminName;
					}
					else if ($data["bpr_assignment_level"] == 3 || $data["bpr_assignment_level"] == 2)
					{
						$prefResult = "Delegated to " . $adminName;
					}
					if ($data["bkg_status"] == 2)
					{
						return $prefResult;
					}
					else
					{
						return $data["vendorName"];
					}
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Vendor Name'),
			array('name'	 => 'bkg_vehicle_id', 'value'	 => function ($data) {
					echo static::$cabTypeList[$data['bkg_vehicle_type_id']]['text'];

//					if ($data['scv_scc_id'] == 4 || $data['scv_scc_id'] == 5)
//					{
//
//						$cabType = Booking::SpecificCabType($data["bkg_id"]);
//						echo '<br>' . $cabType;
//					}

					switch ($data['btk_last_event'])
					{
						case 201:
							echo '<img src="/images/icon/on-way.png" width="26" style="cursor:pointer" title="Left For Pickup">';
							break;
						case 203:
							echo '<img src="/images/icon/arrived.png" width="26" style="cursor:pointer" title="Arrived For Pickup">';
							break;
						case 101:
							echo '<img src="/images/icon/trip_start.png" width="26" style="cursor:pointer" title="Trip Started">';
							break;
						case 102:
							echo '<img src="/images/icon/trip_pause.png" width="26" style="cursor:pointer" title="Trip Paused">';
							break;
						case 103:
							echo '<img src="/images/icon/trip_resume.png" width="26" style="cursor:pointer" title="Trip Resumed">';
							break;
						case 104:
							echo '<img src="/images/icon/trip_stop.png" width="26" style="cursor:pointer" title="Trip Ended">';
							break;
						default :
							echo '';
							break;
					}
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Cab Type'),
			array('name'				 => 'Action', 'type'				 => 'raw',
				'value'				 => 'Booking::model()->getActionButton($data)'
				, 'sortable'			 => false, 'htmlOptions'		 => array('class' => 'action_box'), 'headerHtmlOptions'	 => array('style' => 'min-width:150px;'), 'header'			 => 'Action'),
	)));
}
$time = Filter::getExecutionTime();

$GLOBALS['time'][$status . "_81"][2] = $time;
?>
<script type="text/javascript">

	function viewRelatedBooking(obj)
	{
		var href2 = $(obj).attr("href");
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Booking Details',
					size: 'large',
					onEscape: function ()
					{
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


	$("#booking_id<?= $status ?>_all").click(function ()
	{
		if (this.checked)
		{
			$('#bookingTab<?= $status ?> .checker span').addClass('checked');
			$('#bookingTab<?= $status ?> input[name="booking_id[]"]').attr('checked', 'true');
		} else
		{
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