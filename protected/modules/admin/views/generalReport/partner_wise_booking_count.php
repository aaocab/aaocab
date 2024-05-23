<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12">
		<div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'drvAppUsage-form', 'enableClientValidation' => true,
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

                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Create Date Range</label>
							<?php
							$daterang			 = "Select Date Range";
							$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
							}
							?>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_create_date2'); ?>

                        </div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<div class="form-group">
							<label class="control-label">Channel Partner</label>
							<?php
							$dataagents = Agents::model()->getAgentsFromBooking();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_agent_id',
								'val'			 => $model->bkg_agent_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Partner name')
							));
							?>
						</div> 
					</div>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
						<?php $this->endWidget(); ?>
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">  
					<?= CHtml::beginForm(Yii::app()->createUrl('admin/generalReport/partnerWiseCountBooking'), "post", ['style' => "margin-bottom: 10px;"]); ?>
					<input type="hidden" id="export1" name="export1" value="true"/>
					<input type="hidden" id="export_from1" name="export_from1" value="<?= $model->bkg_create_date1 ?>"/>
					<input type="hidden" id="export_to1" name="export_to1" value="<?= $model->bkg_create_date2 ?>"/>
                    <input type="hidden" id="export_agent_id" name="export_agent_id" value="<?= $model->bkg_agent_id ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

					<?php
					echo CHtml::endForm();
					?>
                 </div>
                </div>
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbExtendedGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'fixedHeader'		 => true,
						'headerOffset'		 => 110,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table items table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						'columns'			 =>
						array
							(
							array('name' => 'Partner Name', 'value' => '$data[partnername]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Partner Name'),
//							array('name'	 => 'Booking Count', 'value'	 => function ($data) {
//									echo $data['cnt'] . "(" . $data['total_book_local'] . "/" . $data['total_book_outstation'] . ")";
//								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Booking Count(Local/OutStation)'),
							array('name'	 => 'Booking Count', 'value'	 => function ($data) {
									echo $data['total_book_local'];
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Booking Count(Local)'),
							array('name'	 => 'Booking Count', 'value'	 => function ($data) {
									echo $data['total_book_outstation'];
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Booking Count(OutStation)'),
							array('name'	 => 'Total Served Booking Count', 'value'	 => function ($data) {
									echo $data['total_served_booking'];
								},'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Served Booking Count'),
//							array('name'	 => 'Booking Ids', 'value'	 => function ($data) {
//									$ids	 = explode(',', $data[booking_id]);
//									$output	 = array_map(function ($val) {
//										return CHtml::link($val, Yii::app()->createUrl("admin/booking/view", ["id" => $val]), ["target" => "_blank"]);
//									}, $ids);
//									$result				 = implode(', ', $output);
//									echo $result;
//								}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-2 text-center'), 'header'								 => 'Booking Ids'),
							array('name' => 'Total Amount', 'value' => '$data[totalamount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Total Amount'),
							array('name' => 'Net Base Amount', 'value' => '$data[net_base_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Net Base Amount'),
							array('name' => 'Gozo Amount', 'value' => '$data[gozoamount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Gozo Amount'),
							array('name' => 'Net Base GROSS MARGIN', 'value' => '$data[netgrossmargin]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'GROSS MARGIN (%)(Net Base  Amt)'),
							array('name' => 'Total Base GROSS MARGIN', 'value' => '$data[totalgrossmargin]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'GROSS MARGIN (%)(Total Amt)'),
							array('name' => 'Receivable', 'value' => 
								function ($data) { 
									if($data['accountBalance']>0)
									{
									echo number_format($data['accountBalance']);
									}
									else
									{
										echo 0;
									}
								}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Receivable'),
							array('name' => 'Payable', 'value' => 
								function ($data) { 
									if($data['accountBalance']<0)
									{
									echo number_format((-1*$data['accountBalance']));
									}
									else
									{
										echo 0;
									}
								}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Payable'),
							array('name' => 'Wallet Balance', 'value' => function ($data) { echo number_format($data['pts_wallet_balance']);}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Wallet Balance'),
							array('name' => 'Received Date', 'value' =>
									function ($data) { 
									if($data['lastBookingReceivedDate'] !='')
									{
									echo  date('d-m-Y', strtotime($data['lastBookingReceivedDate']));
									}
								}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Last Booking Received Date'),
						    array('name' => 'Last Payment Received Date', 'value' => 
									function ($data) { 
									$lastPaymentReceivedDate = AccountTransactions::getLastPaymentReceivedDate($data['bkg_agent_id']);
									if($lastPaymentReceivedDate !='')
									{
									echo  date('d-m-Y', strtotime($lastPaymentReceivedDate));
									}
								}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Last Payment Received Date'),
					
						array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{view_bookingids}', //hide{credit_history}{changeAgentType}
						'buttons'			 => array(
							'view_bookingids'	 => array(
								'click'		 => 'function(){
                                                                var href = $(this).attr(\'href\');
                                                                $.ajax({
                                                                       "type": "GET",
                                                                       "dataType": "html",
                                                                       "url": href,
                                                                       "success": function (data)
                                                                       {
                                                                           bootbox.dialog({
                                                                               message: data,
                                                                               className: "bootbox-xs",
                                                                               title: "Booking Ids",
                                                                               size: "large",
                                                                               callback: function () {

                                                                               }
                                                                           });
                                                                       }
                                                                   });
                                                                   return false;
                                                                 }',
								'url'		 => 'Yii::app()->createUrl("admin/generalReport/viewBookingids", array("agtid" => $data["bkg_agent_id"],"date1" => "'.$date1.'","date2" => "'.$date2.'"))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/agent_list/agent_booking_history.png',
								'label'		 => '<i class="fa fa-file"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs booking p0', 'title' => 'View Bookings'),
							),
						)
						))
					));
				}
				?>
			</div>  
			<div class="panel-body">
				<table class="table">
					<tr>
                        <th></th>
						<th><b>Total Booking Count(Local)</b></th>
						<th><b>Total Booking Count(OutStation)</b></th>
						<th><b>Total Served Booking Count</b></th>
						<th><b>Total Amount</b></th>
						<th><b>Total Net Base Amount</b></th>
						<th><b>Total Gozo Amount</b></th>
						<th></th>
						<th></th>
						<th><b>Total Receivable</b></th>
						<th><b>Total Payable</b></th>
						<th><b>Total Wallet Balance</b></th>
					</tr>
					<tr>
						<?php
						foreach ($totalCount as $val)
						{
							?>
							<td><?php echo " "; ?></td>
							<td><?php echo $val['total_book_local']; ?></td>
							<td><?php echo $val['total_book_outstation']; ?></td>
							<td><?php echo $val['total_served_booking']; ?></td>
							<td><?php echo $val['totalamount']; ?></td>
							<td><?php echo $val['net_base_amount']; ?></td>
							<td><?php echo $val['gozoamount']; ?></td>
							<td><?php echo " "; ?></td>
							<td><?php echo " "; ?></td>
							<td><?php echo number_format($val['receivable']); ?></td>
							<td><?php echo number_format(-1 * $val['payable']); ?></td>
							<td><?php echo number_format($val['pts_wallet_balance']); ?></td>
							<?php
						}
						?>
					</tr>
				</table>
			</div>
		</div>  
	</div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
    $('#bkgCreateDate').daterangepicker(
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
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Pickup Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
    });
</script>