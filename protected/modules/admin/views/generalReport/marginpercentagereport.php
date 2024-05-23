<div class="row m0">
    <div class="col-xs-12">        
        <div class="panel panel-default">
            <div class="panel-body">

				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'otpreport-form', 'enableClientValidation' => true,
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
				// @var $form TbActiveForm 
				?>
				<div class="row"> 

                    <div class="col-xs-12 col-sm-3" style="">
                        <div class="form-group">
                            <label class="control-label">Date Range</label>
							<?php
							$daterang			 = "Select Pickup Date Range";
							$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? date('Y-m-d H:i:s', strtotime("-7 days")) : $model->bkg_pickup_date1;
							$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? date('Y-m-d H:i:s') : $model->bkg_pickup_date2;
							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div>
                    </div>                 
				</div>
				<div class="row"><div class="col-xs-12 col-sm-3 ">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
				</div></div>				
			<?php $this->endWidget(); ?>
			<BR>
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
                                                    <div class='panel-body table-responsive table-bordered'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
					'columns'			 => array(
						array('name'	 => 'date', 'value'	 => function($data) {
								return date("M-Y", strtotime($data['date']));
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Date'),
						array('name'	 => 'totalBooking', 'value'	 => function($data) {
								echo $data['totalBooking'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Total Booking'),
						array('name'	 => 'gozoAmount', 'value'	 => function($data) {
								echo $data['gozoAmount'];
							}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'),
							'header'								 => 'Gozo Amount'),
						array('name'	 => 'netBaseAmount', 'value'	 => function($data) {
								echo $data['netBaseAmount'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Net Base Amount'),
						array('name'	 => 'ManualAssignPercent', 'value'	 => function($data) {
								echo $data['ManualAssignPercent'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Manual Assign Percent'),
						array('name'	 => 'ManualMargin', 'value'	 => function($data) {
								echo $data['ManualMargin'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Manual Margin'),
						array('name'	 => 'ManualGozoAmount', 'value'	 => function($data) {
								echo $data['ManualGozoAmount'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Manual Gozo Amount'),
						array('name'	 => 'AutoAssignPercent', 'value'	 => function($data) {
								echo $data ['AutoAssignPercent'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Auto Assign Percent'),
						array('name'	 => 'AutoMargin', 'value'	 => function($data) {
								echo $data['AutoMargin'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Auto Margin'),
						array('name'	 => 'AutoGozoAmount', 'value'	 => function($data) {
								echo $data['AutoGozoAmount'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Auto Gozo Amount'),
						array('name'	 => 'BidAssignPercent', 'value'	 => function($data) {
								echo $data['BidAssignPercent'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Bid Assign Percent'),
						array('name'	 => 'BidAssignMargin', 'value'	 => function($data) {
								echo $data['BidAssignMargin'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Bid Assign Margin'),
						array('name'	 => 'BidGozoAmount', 'value'	 => function($data) {
								echo $data['BidGozoAmount'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Bid Gozo Amount'),
						array('name'	 => 'DirectAssignPercent', 'value'	 => function($data) {
								echo $data['DirectAssignPercent'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Direct Assign Percent'),
						array('name'	 => 'DirectAssignMargin', 'value'	 => function($data) {
								echo $data['DirectAssignMargin'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Direct Assign Margin'),
						array('name'	 => 'DirectGozoAmount', 'value'	 => function($data) {
								echo $data['DirectGozoAmount'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Direct Gozo Amount'),
						array('name'	 => 'TotalMargin', 'value'	 => function($data) {
								echo $data['TotalMargin'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
							'header'			 => 'Total Margin'),
				)));
			}
			?> 

		</div>  
	</div>  
</div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var start = '<?= date('d/m/Y'); ?>';
        var end = '<?= date('d/m/Y'); ?>';
        $('#bkgPickupDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    maxDate: moment(),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Previous 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Previous 15 Days': [moment().subtract(15, 'days'), moment()]
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#Booking_bkg_pickup_date1').val('');
            $('#Booking_bkg_pickup_date2').val('');
        });
    })
</script>