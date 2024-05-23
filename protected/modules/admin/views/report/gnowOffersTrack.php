<?php
$statusList		 = Booking::model()->getBookingStatus();
unset($statusList[1]);
unset($statusList[4]);
unset($statusList[11]);
unset($statusList[12]);
unset($statusList[13]);
unset($statusList[15]);
$userTypeList	 = UserInfo::getUserTypeDesc();
$assignMode		 = BookingCab::model()->bcb_assign_mode;
?>
<style>
    .checkbox{
        display:inline;
    }
</style>
<div class="panel panel-default">
    <div class="panel-body " >
        <div class="row"> 
			<?php
			$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'gnow-form', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="row"> 
                    <div class="col-xs-12  ">
						<?= $form->textFieldGroup($model, 'ntl_ref_id', array('label' => 'Trip ID', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'search by trip')))) ?>
                    </div>
                    <div class="col-xs-12   ">
						<?= $form->textFieldGroup($model, 'bkgId', array('label' => 'Booking ID', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'search by booking')))) ?>
                    </div>
                </div>	
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="row">
                    <div class="col-xs-12   " >
                        <div class="form-group">
                            <label class="control-label">GN activation date range</label>
							<?php
							$daterang		 = "Select Date Range";
							$ntl_date1		 = $model->ntl_date1;
							$ntl_date2		 = $model->ntl_date2;
							if ($ntl_date1 != '' && $ntl_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($ntl_date1)) . " - " . date('F d, Y', strtotime($ntl_date2));
							}
							?>
                            <div id="ntlDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'ntl_date1'); ?>
							<?= $form->hiddenField($model, 'ntl_date2'); ?>
                        </div></div>
                    <div class="col-xs-12   " >
                        <div class="form-group">
                            <label class="control-label">Status </label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkgStatus',
								'val'			 => $model->bkgStatus,
								'data'			 => $statusList,
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => true,
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select Status')
							));
							?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-5">
                <div class="row"> 
                    <div class="col-xs-12  col-md-6 ">
                        <label class="control-label">Type</label>
						<?= $form->checkboxListGroup($model, 'gnowType', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'GN Initiated', 2 => 'GN Converted'), 'htmlOptions' => []))) ?>
                    </div>

					<div class="col-xs-12  col-md-6 ">
                        <label class="control-label">Is Duplicate</label>

						<?= $form->radioButtonListGroup($model, 'isDuplicate', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Yes', 0 => 'No'), 'htmlOptions' => []))) ?>
                    </div>

                    <div class="col-xs-12 col-md-6 ">
                        <label class="control-label">Created By</label>
						<?= $form->checkboxListGroup($model, 'bkgCreateType', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Consumer', 4 => 'Admin'), 'htmlOptions' => ['display' => 'inline']))) ?>
                    </div>
					<div class="col-xs-12 col-md-6 "> 
						<?= $form->checkboxListGroup($model, 'vndSelected', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Vendor Selected Only'), 'htmlOptions' => ['display' => 'inline']))) ?>
                    </div>
					<div class="col-xs-12 col-md-6 "> 
						<?= $form->checkboxListGroup($model, 'transferzSelected', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Transferz Only'), 'htmlOptions' => ['display' => 'inline']))) ?>
                    </div>

                </div>	
            </div>
			<div class="col-xs-12  ">
                <div class="row"> 
					<div class="  col-xs-6 col-sm-4 col-md-2 col-lg-2 text-center mt20 ">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width mt5')); ?></div>
					<?php $this->endWidget(); ?>
				</div>
			</div> </div>
	</div>
	<div class="panel-body panel pt0 pb0" >
		<div class="row"> 
			<div class="col-xs-12 col-md-3 col-sm-6 panel-heading">
				<?php
				echo "#Trips= " . $summaryData['tripCount'];
				?>
			</div>
			<div class="col-xs-12 col-md-3 col-sm-6 panel-heading">
				<?php
				echo "#Notification Sent= " . $summaryData['totalSent'];
				?>
			</div>
			<div class="col-xs-12 col-md-3 col-sm-6 panel-heading">
				<?php
				echo "#Notification Received= " . $summaryData['isReceived'];
				?>
			</div>
			<div class="col-xs-12 col-md-3  col-sm-6 panel-heading">
				<?php
				echo "#Notification Read= " . $summaryData['isRead'];
				?>
			</div>

		</div>
	</div>
</div>
<div class="panel panel-default">
    <div class="panel-body p0" >
		<?php
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
				array('name' => 'tripId', 'value' => '$data["tripId"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Trip ID'),
				array('name'	 => 'bkgId1', 'value'	 =>
					function ($data) {
						$bkgId	 = explode(',', $data['bkgId1'])[0];
						$tagPToP = ($data['bkgType'] == 14) ? ("<h5><span class='label label-info'>Point To Point</span></h5>") : '';
						$tagOW	 = ($data['bkgType'] == 1) ? ("<h5><span class='label label-primary'>One Way</span></h5>") : '';

						echo CHtml::link($bkgId, Yii::app()->createUrl("admin/booking/view", ["id" => $bkgId]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']) . '<br />' . $tagPToP . $tagOW;
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking ID'),
				//	array('name' => 'bkgId2', 'value' => '$data["bkgId2"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking ID (Past)'),
				array('name'	 => 'isDuplicate', 'value'	 =>
					function ($data) {
						$bkgId		 = explode(',', $data['bkgId1'])[0];
						$bookingIds	 = Booking::isDupilcate($bkgId);
						$bookingArr	 = explode(',', $bookingIds);
						if (count($bookingArr) >= 2 && $data['isDuplicateFlag'] == '1')
						{
							echo "Yes<br>";
							for ($i = 0; $i < count($bookingArr); $i++)
							{
								if ($bkgId != $bookingArr[$i])
								{
									echo CHtml::link($bookingArr[$i], Yii::app()->createUrl("admin/booking/view", ["id" => $bookingArr[$i]]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']) . "<br>";
								}
							}
						}
						else
						{
							echo "No";
						}
					},
					'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Is Duplicate'),
				array('name'	 => 'totalSent', 'value'	 =>
					function ($data) {
						$bkgId		 = explode(',', $data['bkgId1'])[0];
						$totalSent	 = $data["totalSent"];
						echo CHtml::link($totalSent, Yii::app()->createUrl("admin/booking/gnowNotificationList", ["id" => $bkgId]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Notification Sent'),
// 				array('name' => 'delivered', 'value' => '$data["delivered"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Notification Delivered'),
				array('name' => 'isReceived', 'value' => '$data["isReceived"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Notification Received'),
				array('name' => 'isRead', 'value' => '$data["isRead"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Notification Read'),
				array('name'	 => 'bidAmts', 'value'	 => function ($data) {
						if ($data["bidAmts"] != NULL)
						{
							$arr = explode(',', $data["bidAmts"]);
							echo implode(', ', substr_replace($arr, '&#x20B9;', 0, 0)) . "<br>";
						} echo '(' . $data["gnowBid"] . ')';
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center '), 'header'			 => 'Offers  <br> (Count)'),
				array('name'	 => 'bidDeny', 'value'	 => function ($data) {
						$bkgId = explode(',', $data['bkgId1'])[0];
						echo (empty($data["bidDeny"])) ? $data["bidDeny"] : CHtml::link($data["bidDeny"], Yii::app()->createUrl("/admin/report/rejectedVendorsOfGnowOffer", ["bkgId" => $bkgId]), ["class" => "", "onclick" => "", 'target' => '_blank']);
					}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Deny'),
				array('name'	 => 'tripVendorAmount', 'value'	 => function ($data) {
						echo Filter::moneyFormatter($data["tripVendorAmount"]);
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Trip Vendor Amount'),
				array('name'	 => 'bkgVendorAmount', 'value'	 => function ($data) {
						echo Filter::moneyFormatter($data["bkgVendorAmount"]);
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Vendor Amount'),
				array('name'	 => 'bkgTotalAmount', 'value'	 => function ($data) {
						echo Filter::moneyFormatter($data["bkgTotalAmount"]);
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Total Amount'),
				array('name'	 => 'bkgStatus1', 'value'	 => function ($data) use ($statusList) {
						echo $statusList[$data["bkgStatus1"]];
						if ($data["bkgStatus1"] == 2)
						{
							echo ($data["bkg_reconfirm_flag"] == 1) ? ' (Reconfirmed)' : ' (Reconfirm Pending)';
						}
					},
					'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center '), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking Status'),
				array('name'	 => 'assignedMode', 'value'	 => function ($data) use ($assignMode) {
						echo ( $data['bkg_assigned_at'] != '' && in_array($data["bkgStatus1"], [3, 5, 6, 7])) ? $assignMode[$data["bkg_assign_mode"]] : 'NA';
					},
					'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Assigned Mode'),
//				array('name'	 => 'gnowAssigned', 'value'	 => function ($data) {
//						echo ($data["gnowAssigned"] == '1') ? 'Yes' : (($data["gnowAssigned"] == '0') ? 'No' : 'NA');
//					},
//					'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Gozo-Now Assigned'),
				array('name'	 => 'createType', 'value'	 => function ($data) {
						echo UserInfo::getUserTypeDesc($data["bkg_create_user_type"]);
					}
					, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Created By'),
				array('name'	 => 'bkg_is_gozonow', 'value'	 => function ($data) {
						echo ($data["bkg_is_gozonow"] == 1) ? 'GN Initiated' : 'GN Converted';
					}
					, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Type'),
				array('name' => 'pickupDate', 'value' => 'DateTimeFormat::DateTimeToLocale($data["pickupDate"])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Pickup Date'),
				array('name' => 'createDate', 'value' => 'DateTimeFormat::DateTimeToLocale($data["createDate"])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Booking Create Date'),
				array('name' => 'ntl_sent_on', 'value' => '($data["ntl_sent_on"])?DateTimeFormat::DateTimeToLocale($data["ntl_sent_on"]): "NA"', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center col-xs-1'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Notification Sent'),
				array('name'	 => 'bvr_created', 'value'	 => function ($data) {
						echo ($data["gnowBid"] > 0) ? DateTimeFormat::DateTimeToLocale($data["bvr_created"]) : $data["bvr_created"];
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center col-xs-1'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Last Offer Received'),
		)));
		?>
    </div>
</div>
<script type="text/javascript">

	var start = '<?= date('d/m/Y'); ?>';
	var end = '<?= date('d/m/Y'); ?>';

	$('#ntlDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 15 Days': [moment().subtract(15, 'days'), moment()],
					'This Month': [moment().subtract(0, 'month').startOf('month'), moment()],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				}
			}, function (start1, end1) {
		$('#NotificationLog_ntl_date1').val(start1.format('YYYY-MM-DD'));
		$('#NotificationLog_ntl_date2').val(end1.format('YYYY-MM-DD'));
		$('#ntlDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#ntlDate').on('cancel.daterangepicker', function (ev, picker) {
		$('#ntlDate span').html('Select Create Date Range');
		$('#NotificationLog_ntl_date1').val('');
		$('#NotificationLog_ntl_date2').val('');
	});
</script>