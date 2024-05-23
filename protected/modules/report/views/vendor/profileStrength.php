<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<div class="row" >
    <div class="col-xs-12">
		<div class="row">
			<?php
			/* @var $model Vendors */
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'vndCompensation', 'enableClientValidation' => true,
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
			<div class="col-xs-12 col-sm-3">
				<div class="form-group">
					<label class="control-label">Vendors</label>
					<?php
					$data				 = Vendors::model()->getJSONAllVendorsbyQuery('', '', '1');

					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'vnd_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Vendor",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
									  populateVendor(this, '{$model->vnd_id}');
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
			<div class="col-xs-12 col-sm-4 col-md-2"> 
				<div class="form-group">
					<label class="control-label">Vendor Status</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'vnd_active',
						'val'			 => $model->vnd_active,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression(VehicleTypes::model()->getJSON([1 => 'Approved', 3 => 'Pending', 2 => 'Inactive'])), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Status')
					));
					?>
				</div>
			</div>

			<div class="  col-xs-6 col-sm-6 col-md-2 col-lg-1 mr20 mt20"  >
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
			<?php $this->endWidget(); ?>
			<div class="col-xs-12 col-sm-4 col-md-4 form-group ">
				<?php
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/profileStrength'), "post", []);
					?>
					<input type="hidden" id="export" name="export" value="true"/>
					<input type="hidden" id="vnd_id" name="vnd_id" value="<?php echo $model->vnd_id; ?>"/>
					<input type="hidden" id="vnd_id" name="vnd_active" value="<?php echo $model->vnd_active; ?>"/>
					<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
					<?php echo CHtml::endForm(); ?>	
				<?php } ?>
			</div>
		</div>

        <div class="row">
            <div class="col-xs-12">
                <div class="  table table-bordered">
					<?php
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'vendorListGrid',
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table  table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
							'columns'			 => array(
								array('name' => 'vnd_name', 'filter' => CHtml::activeTextField($model, 'vnd_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_name'))), 
								  'value'	 => function ($data) {
										echo $data['vnd_name']. "<br>";
										if ($data['vnp_is_freeze'] == 1)
										{
											echo ' <span class="label label-danger">Freeze</span>';
										}
										if ($data['vnp_cod_freeze'] == 1)
										{
											echo ' <span class="label label-info">COD Freeze</span>';
										}
										if ($data['vnp_credit_limit_freeze'] == 1)
										{
											echo ' <span class="label label-warning">Credit Limit Freeze</span>';
										}
										if ($data['vnp_low_rating_freeze'] == 1)
										{
											echo ' <span class="label label-warning">Low Rating Freeze</span>';
										}
										if ($data['vnp_doc_pending_freeze'] == 1)
										{
											echo ' <span class="label label-warning">Doc Pending Freeze</span>';
										}
										if ($data['vnp_manual_freeze'] == 1)
										{
											echo ' <span class="label label-primary">Manual Freeze</span>';
										}
								   },
									'sortable' => True, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('vnd_name')),
								array('name'	 => 'vnd_active', 'value'	 =>
									function ($data) {
										if ($data['vnd_active'] == 1)
										{
											echo "Approved";
										}
										else if ($data['vnd_active'] == 2)
										{
											echo "Inactive";
										}
										else if($data['vnd_active'] == 3)
										{
											echo "Pending";
										}
									},
									'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Status'),
								array('name' => 'vrs_sticky_score', 'value' => '$data[vrs_sticky_score]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Sticky Score'),
								array('name' => 'vrs_trust_score', 'value' => '$data[vrs_trust_score]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Trust Score'),
								array('name' => 'vrs_dependency', 'value' => '$data[vrs_dependency]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Dependency Score'),
								array('name' => 'vrs_security_amount', 'value' => '$data[vrs_security_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Security Amount'),
								array('name' => 'vrs_vnd_overall_rating', 'value' => '$data[vrs_vnd_overall_rating]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Overall Rating'),
								array('name' => 'vrs_vnd_total_trip', 'value' => '$data[vrs_vnd_total_trip]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Trip'),
								array('name' => 'vrs_tot_bid', 'value' => '$data[vrs_tot_bid]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Bid'),
								array('name' => 'vrs_count_driver', 'value' => '$data[vrs_count_driver]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Count Driver'),
								array('name' => 'vrs_count_car', 'value' => '$data[vrs_count_car]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Count Car'),
								array('name' => 'vrs_approve_driver_count', 'value' => '$data[vrs_approve_driver_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Approve Driver Count'),
								array('name' => 'vrs_approve_car_count', 'value' => '$data[vrs_approve_car_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Approve Car Count'),
								array('name' => 'vrs_docs_score', 'value' => '$data[vrs_docs_score]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Docs Score'),
								array('name' => 'vrs_no_of_star', 'value' => '$data[vrs_no_of_star]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'No Of Star'),
								array('name' => 'vrs_denied_duty_cnt', 'value' => '$data[vrs_denied_duty_cnt]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Denied Duty Count'),
								array('name' => 'vrs_total_trips', 'value' => '$data[vrs_total_trips]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Trips'),
								array('name' => 'vrs_locked_amount', 'value' => '$data[vrs_locked_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Locked Amount'),
								array('name' => 'vrs_withdrawable_balance', 'value' => '$data[vrs_withdrawable_balance]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Withdrawable Balance'),
								array('name' => 'vrs_last_bkg_cmpleted', 'value' => '$data[vrs_last_bkg_cmpleted]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Last Booking Completed'),
								array('name' => 'vrs_total_completed_days_30', 'value' => '$data[vrs_total_completed_days_30]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Completed 30days'),
								array('name' => 'vrs_total_vehicle_30', 'value' => '$data[vrs_total_vehicle_30]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total vehicle 30days'),
								array('name' => 'vrs_penalty_count', 'value' => '$data[vrs_penalty_count]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Penalty Count'),
								array('name' => 'vrs_total_booking', 'value' => '$data[vrs_total_booking]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total booking'),
								array('name' => 'vrs_margin', 'value' => '$data[vrs_margin]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor Margin'),
								array('name' => 'vrs_bid_win_percentage', 'value' => '$data[vrs_bid_win_percentage]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Bid Win Percentage'),
						)));
					}
					?> 
                </div>
            </div>  
        </div> 
    </div>  
</div>  


