
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
					<div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Vendors</label>
							<?php
							$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];

							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bcb_vendor_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendor",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bcb_vendor_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                        loadVendor(query, callback);
                        }",
							'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
								),
							));
							?>
                        </div> 
                    </div>

				</div>
				<div class="row"><div class="col-xs-12 col-sm-3 ">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
				</div></div>				
			<?php
			$this->endWidget();
			$checkExportAccess = false;
			if ($roles['rpt_export_roles'] != null)
			{
				$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
			}
			if ($checkExportAccess)
			{
				echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/vendorusagereport'), "post", []);
				?>
				<input type="hidden" id="export" name="export" value="true"/>
				<input type="hidden" id="bkg_pickup_date1" name="bkg_pickup_date1" value="<?php echo $model->bkg_pickup_date1; ?>"/>
				<input type="hidden" id="bkg_pickup_date2" name="bkg_pickup_date2" value="<?php echo $model->bkg_pickup_date2; ?>"/>
				<input type="hidden" id="bcb_vendor_id" name="bcb_vendor_id" value="<?php echo $model->bcb_vendor_id; ?>"/>
				<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
				<?php echo CHtml::endForm(); ?>	
			<?php } ?>
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
					//    'ajaxType' => 'POST',
					'columns'			 => array(
						array('name'	 => 'date', 'value'	 => function ($data) {
								return date("d-m-Y", strtotime($data['date']));
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Date'),
						array('name'	 => 'vnd_name', 'value'	 => function ($data) {
								return $data['vnd_name'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Vendor Name'),
						array('name'	 => 'not_loggedin', 'value'	 => function ($data) {
								$not_loggedin_str = '';
								if (!empty($data['not_loggedin']))
								{
									$notloggedinarr = explode(",", $data['not_loggedin']);
									foreach ($notloggedinarr as $k => $l)
									{
										$notloggedinarr[$k] = CHtml::link(trim($l), Yii::app()->createUrl("admin/booking/view", ["id" => trim($l)]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									}
									$not_loggedin_str						 = implode(", ", $notloggedinarr);
								}
								else
								{
									$not_loggedin_str = 'N/A';
								}
								echo $not_loggedin_str;
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Not Logged In'),
						array('name'	 => 'not_left', 'value'	 => function ($data) {
								$not_left_str = '';
								if (!empty($data['not_left']))
								{
									$not_leftarr = explode(",", $data['not_left']);
									foreach ($not_leftarr as $k => $l)
									{
										$not_leftarr[$k] = CHtml::link(trim($l), Yii::app()->createUrl("admin/booking/view", ["id" => trim($l)]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									}
									$not_left_str = implode(", ", $not_leftarr);
								}
								else
								{
									$not_left_str = "N/A";
								}
								echo $not_left_str;
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Not Left'),
						array('name'	 => 'not_arrived', 'value'	 => function ($data) {
								$not_arrived_str = '';
								if (!empty($data['not_arrived']))
								{
									$not_arrivedarr = explode(",", $data['not_arrived']);
									foreach ($not_arrivedarr as $k => $l)
									{
										$not_arrivedarr[$k] = CHtml::link(trim($l), Yii::app()->createUrl("admin/booking/view", ["id" => trim($l)]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									}
									$not_arrived_str = implode(", ", $not_arrivedarr);
								}
								else
								{
									$not_arrived_str = 'N/A';
								}
								echo $not_arrived_str;
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Not Arrived'),
						array('name'	 => 'not_started', 'value'	 => function ($data) {
								$not_started_str = '';
								if (!empty($data['not_started']))
								{
									$not_startedarr = explode(",", $data['not_started']);
									foreach ($not_startedarr as $k => $l)
									{
										$not_startedarr[$k] = CHtml::link(trim($l), Yii::app()->createUrl("admin/booking/view", ["id" => trim($l)]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									}
									$not_started_str = implode(", ", $not_startedarr);
								}
								else
								{
									$not_started_str = 'N/A';
								}
								echo $not_started_str;
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Not Started'),
						array('name'	 => 'not_ended', 'value'	 => function ($data) {
								$not_ended_str = '';
								if (!empty($data['not_ended']))
								{
									$not_endedarr = explode(",", $data['not_ended']);
									foreach ($not_endedarr as $k => $l)
									{
										$not_endedarr[$k] = CHtml::link(trim($l), Yii::app()->createUrl("admin/booking/view", ["id" => trim($l)]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
									}
									$not_ended_str = implode(", ", $not_endedarr);
								}
								else
								{
									$not_ended_str = 'N/A';
								}
								echo $not_ended_str;
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Not Ended'),
				)));
			}
			?> 
			<BR>
			<?php
			//Second Data grid
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
					//    'ajaxType' => 'POST',
					'columns'			 => array(
						array('name'	 => 'date_range', 'value'	 => function ($data) {
								return $data['date_range'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Date Range'),
						array('name'	 => 'vnd_name', 'value'	 => function ($data) {
								return $data['vnd_name'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Vendor Name'),
						array('name'	 => 'booking_count', 'value'	 => function ($data) {
								//echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
								return $data['booking_count'];
							}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'),
							'header'								 => 'Booking Count'),
						array('name'				 => 'not_loggedin_count', 'value'				 => '$data[not_loggedin_count]', 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Not Logged In Count'),
						array('name'	 => 'left_count', 'value'	 => function ($data) {
								return $data['left_count'];
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Left Count'),
						array('name'	 => 'arrived_count', 'value'	 => function ($data) {
								return $data['arrived_count'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Arrived Count'),
						array('name'	 => 'start_count', 'value'	 => function ($data) {
								return $data['start_count'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Start Count'),
						array('name'	 => 'end_count', 'value'	 => function ($data) {
								return $data['end_count'];
							}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'End Count'),
						array('name'				 => 'arrived_percent', 'value'				 => '$data[arrived_percent]', 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Arrived Percent'),
						array('name'				 => 'start_percent', 'value'				 => '$data[start_percent]', 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Start Percent'),
						array('name'				 => 'end_percent', 'value'				 => '$data[end_percent]', 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'End Percent'),
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
