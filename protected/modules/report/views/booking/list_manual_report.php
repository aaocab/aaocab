<div class='row p15'>
	<?php
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form',
		'enableClientValidation' => true,
		//		'method'				 => 'post',
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */
	?>
	<div class="col-xs-6 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Pickup (Date Range)</label>
			<?php
			$daterang		 = "Select Date Range";
			$pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
			$pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
			if ($pickup_date1 != '' && $pickup_date2 != '')
			{
				$daterang = date('F d, Y', strtotime($pickup_date1)) . " - " . date('F d, Y', strtotime($pickup_date2));
			}
			?>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
		</div>
		<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
		<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>
	</div>

	<div class="col-xs-4 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Assign ( Date Range )</label>
			<?php
			$daterang		 = "Select Date Range";
			$assignedDate1	 = ($model->bkg_assigned_date1 == '') ? '' : $model->bkg_assigned_date1;
			$assignedDate2	 = ($model->bkg_assigned_date2 == '') ? '' : $model->bkg_assigned_date2;
			if ($assignedDate1 != '' && $assignedDate2 != '')
			{
				$daterang = date('F d, Y', strtotime($assignedDate1)) . " - " . date('F d, Y', strtotime($assignedDate2));
			}
			?>
			<div id="bkgAssignDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
		</div>
		<?= $form->hiddenField($model, 'bkg_assigned_date1'); ?>
		<?= $form->hiddenField($model, 'bkg_assigned_date2'); ?>

	</div>
	<div class="col-xs-4 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Team/Team Leader</label>
			<?php
			$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
				'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
				'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
				'openOnFocus'		 => true, 'preload'			 => false,
				'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
				'addPrecedence'		 => false,];
			?> 
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'bkg_admin_id',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Team Leader",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'Booking_bkg_admin_id'
				),
				'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
													populateGozen(this, '{$model->bkg_admin_id}');
														}",
					'load'			 => "js:function(query, callback){
													loadGozen(query, callback);
													}",
					'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
				),
			));
			?>
		</div>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-4">
			<div class="form-group">
				<label class="control-label">Tags</label>
				<?php
				$SubgroupArray2		 = Tags::getListByType(Tags::TYPE_BOOKING);
				$this->widget('booster.widgets.TbSelect2', array(
					'attribute'		 => 'search_tags',
					'model'			 => $model,
					'val'			 => $model->search_tags,
					'data'			 => $SubgroupArray2, 
					'htmlOptions'	 => array(
						'multiple'		 => 'multiple',
						'placeholder'	 => 'Add tags keywords ',
						'style'			 => 'width:100%'
					),
				));
				?>
			</div>
	</div>
</div>

<div class="col-xs-12">

	<div style="display: inline-block; ">
		<?php echo $form->radioButtonListGroup($model, 'is_Assigned', array('label' => '', 'inline' => true, 'widgetOptions' => array('data' => array(0 => 'Auto Allocated', 1 => 'Manual Allocated')), 'groupOptions' => ['htmlOptions' => ["style" => "display: inline-block"]])) ?>
	</div>

	<div style="display: inline-block">
		<?php echo $form->checkboxGroup($model, 'is_Manual', array('label' => 'Manual Assignment')) ?>
	</div>

	<div style="display: inline-block">
		<?php echo $form->checkboxGroup($model, 'is_Critical', array('label' => 'Critical Assignment')) ?>
	</div>
</div>





<div class="col-xs-12 col-sm-2 col-md-2">   
	<label class="control-label"></label>
	<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
</div>
</div>


<?php $this->endWidget(); ?>

<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 mt5">
	<?php
	$checkExportAccess	 = false;
	if ($roles['rpt_export_roles'] != null)
	{
		$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
	}
	if ($checkExportAccess)
	{
		echo CHtml::beginForm(Yii::app()->createUrl('report/booking/manualReport'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
		?>

		<input type="hidden" id="pickup_date1" name="pickup_date1" value="<?= $model->bkg_pickup_date1; ?>"/>
		<input type="hidden" id="pickup_date2" name="pickup_date2" value="<?= $model->bkg_pickup_date2; ?>"/>

		<input type="hidden" id="assigned_date1" name="assigned_date1" value="<?= $model->bkg_assigned_date1; ?>"/>
		<input type="hidden" id="assigned_date2" name="assigned_date2" value="<?= $model->bkg_assigned_date2; ?>"/>

		<input type="hidden" id="is_Assigned" name="is_Assigned" value="<?= $model->is_Assigned; ?>"/>
		<input type="hidden" id="is_Manual" name="is_Manual" value="<?= $model->is_Manual; ?>"/>
		<input type="hidden" id="is_Critical" name="is_Critical" value="<?= $model->is_Critical; ?>"/>
		<input type="hidden" id="admin_id" name="admin_id" value="<?= $model->bkg_admin_id; ?>"/>
		<input type="hidden" id="searchTags" name="searchTags" value="<?php echo ($model->search_tags!='')?implode(',', $model->search_tags):""; ?>"/>
		<input type="hidden" id="export" name="export" value="true"/>
		<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>
		<?php
		echo CHtml::endForm();
	}
	?>
</div>
</div>


<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'bcb_id', 'value'	 => function ($data) {

							echo ($data['bcb_id']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Trip Id'),
					array('name'	 => 'bkg_id', 'value'	 => function ($data) {

							echo CHtml::link($data["bkg_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "target" => "_blank"]);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Booking Id'),
					array('name'	 => 'bkg_create_date', 'value'	 => function ($data) {

							echo ($data['bkg_create_date']);
						}, 'sortable'								 => true,
						'headerHtmlOptions'						 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'							 => array('class' => 'text-center'),
						'header'								 => 'Create Date'),
					array('name'	 => 'bkg_pickup_date', 'value'	 => function ($data) {

							echo ($data['bkg_pickup_date']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Pickup Date'),
					array('name'	 => 'vnd_name', 'value'	 => function ($data) {

							echo CHtml::link($data["vnd_name"], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "target" => "_blank"]);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Vendor Name'),
					array('name'	 => 'drv_name', 'value'	 => function ($data) {

							echo CHtml::link($data["drv_name"], Yii::app()->createUrl("admin/driver/view", ["id" => $data['drv_id']]), ["class" => "", "target" => "_blank"]);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Driver Name'),
					array('name'	 => 'vhc_number', 'value'	 => function ($data) {

							echo CHtml::link($data["vhc_number"], Yii::app()->createUrl("admin/vehicle/view", ["id" => $data['vhc_id']]), ["class" => "", "target" => "_blank"]);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Vehicle Number'),
					array('name'	 => 'bkg_assigned_at', 'value'	 => function ($data) {

							echo ($data['bkg_assigned_at']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Assign AT'),
					array('name'	 => 'assign_csr', 'value'	 => function ($data) {

							echo ($data['assign_csr']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Assign By'),
					array('name'	 => 'bkg_gozo_amount', 'value'	 => function ($data) {
							echo Filter::moneyFormatter($data['bkg_gozo_amount']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Profit+/-'),
					array('name'	 => 'bid_count', 'value'	 => function ($data) {
							echo ($data['bid_count']);
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'Bid Count'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{log}',
						'buttons'			 => array(
							'log' => array(
								'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Bid Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("report/booking/showbidlog", array("bkgId" => $data["bkg_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle'	 => 'ajaxModal',
									'style'			 => '',
									'class'			 => 'btn btn-xs conshowlog p0',
									'title'			 => 'Show Bid Log'),
							),
						))
			)));
		}
		?>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        var pickupStart = '<?= ($model->bkg_pickup_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_pickup_date1)); ?>';
        var pickupEnd = '<?= ($model->bkg_pickup_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_pickup_date2)); ?>';

        var assignStart = '<?= ($model->bkg_assigned_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_assigned_date1)); ?>';
        var assignEnd = '<?= ($model->bkg_assigned_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_assigned_date2)); ?>';


        $('#bkgPickupDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: pickupStart,
                    endDate: pickupEnd,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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


        $('#bkgAssignDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: assignStart,
                    endDate: assignEnd,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_assigned_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_assigned_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgAssignDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgAssignDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgAssignDate span').html('Select  Date Range');
            $('#Booking_bkg_assigned_date1').val('');
            $('#Booking_bkg_assigned_date2').val('');
        });
		
		
		

    });
	
	$gozenList = null;
	function populateGozen(obj, gozen)
	{
		$followUp.populateGozen(obj, gozen);
	}

	function loadGozen(query, callback)
	{
		$followUp.loadGozen(query, callback);
	}	

</script>