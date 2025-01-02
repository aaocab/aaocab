
<?php
$vtypeList		 = VehicleTypes::model()->getVehicleTypeList1();
$vtypeListJson	 = VehicleTypes::model()->getJSON(array_filter($vtypeList));

$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style>
	.pagination {
		margin: 0px 0 !important;
	}
</style>
<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-body">
			<section>
				<div class="container-fluid">
					<div class="row d-flex justify-content-center">
						<div class="col-12 mob-view">
							<div class="card">
								<div class="card-body">
									<?php

									$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
										'id'					 => 'cablistfilter',
										'enableClientValidation' => true,
										'clientOptions'			 => array(
											'validateOnSubmit'	 => true,
											'errorCssClass'		 => 'has-error',
										),
										// Please note: When you enable ajax validation, make sure the corresponding
										// controller action is handling ajax validation correctly.
										// See class documentation of CActiveForm for details on this,
										// you need to use the performAjaxValidation()-method described there.
										'enableAjaxValidation'	 => false,
										'errorMessageCssClass'	 => 'help-block',
										'htmlOptions'			 => array('class' => '',),
										'action'				 => Yii::app()->createUrl('supplier/cab/list'),
									));
									/* @var $form TbActiveForm */
									?>
									<div class="row">
										<div class="col-12 col-lg-4 col-xl-3">
											<?= $form->textFieldGroup($model, 'vhc_number', array('label' => 'Cab Number', 'htmlOptions' => array('placeholder' => 'Search By Cab Number/model/type'))) ?>
										</div>

										<div class="col-12 col-lg-2 col-xl-3">
											<div class="form-group">
												<label class="control-label">Cab Model</label>
												<?php
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $model,
													'attribute'		 => 'vhc_type_id',
													'val'			 => $model->vhc_type_id,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($vtypeListJson), 'allowClear' => true),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vehicle Model')
												));
												?>
											</div>
										</div>
										 <div class="col-xs-6 col-sm-4">
											<div class="form-group">
												<label class="control-label">Approved Status</label>
													<?php
													$this->widget('booster.widgets.TbSelect2', array(
														'model'			 => $model,
														'attribute'		 => 'vhc_approved',
														'val'			 => $model->vhc_approved,
														'asDropDownList' => FALSE,
														'options'		 => array('data' => new CJavaScriptExpression(Vehicles::model()->getApproveStatus()), 'allowClear' => true),
														'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'approved status')
													));
													?>
											</div>	
										</div>
										<div class="col-12 text-right">
											<button type="submit" class="btn btn-primary round position-relative">Search</button>
										</div>

									</div>
									<?php $this->endWidget(); ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-xl-12 mob-view">
<!--							<div class="col-12 pb10 text-right"><a href="<?= Yii::app()->createUrl('supplier/cab/add') ?>"><button type="button" class="btn btn-secondary m5 position-relative"><i class='bx bx-plus'></i> Add New</button></a></div>-->
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'selectableRows'	 => 2,
									'id'				 => 'vehicleListGrid',
									'template'			 => "<div class='card-heading mt20'><div class='row m0'>
										<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6'>{pager}</div>
										</div></div>
										<div class='card-body'>{items}</div>
										<div class='card-footer'><div class='row m0'><div class='col-xs-12 col-sm-6'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'card table-responsive panel panel-default compact'),
									//'ajaxType' => 'POST',
									'columns'			 => array(
										array('name' => 'vhc_number', 'value' => '$data["vhc_number"]', 'header' => 'Cab number'),
										array('name' => 'vht_car_type', 'value' => '$data[\'vht_car_type\']', 'header' => 'Cab type'),
										array('name' => 'vht_make', 'value' => '$data["vht_make"]." ".$data["vht_model"]', 'header' => 'Cab model'),
//										array('name'	 => 'vhc_dop', 'value'	 => function($data) {
//												$date			 = new DateTime($data['vhc_dop']);
//												$formattedDate	 = $date->format('d/m/Y h:i A');
//												echo $formattedDate;
//											}, 'header' => 'Date of purchase'),
										array('name' => 'vhc_year', 'value' => '$data["vhc_year"]', 'header' => 'Year'),
										array('name' => 'vhc_approved', 'value' => function($data)
																{
												if ($data['vhc_approved'] == 1)
												{
													echo ' <span class="text-success">Approved</span>';
												}
												if ($data['vhc_approved'] == 0)
												{
													echo ' <span class="text-default ">Not Verified</span>';
												}
												if ($data['vhc_approved'] == 2)
												{
													echo ' <span class="text-warning ">Pending approval</span>';
												}
												if ($data['vhc_approved'] == 4)
												{
													echo ' <span class="text-warning ">Approved but papers expired</span>';
												}
												if ($data['vhc_approved'] == 3)
												{
													echo ' <span class="text-danger ">Rejected</span>';
												}
											}, 'header' => 'Approval'),
//										array(
//											'header'			 => 'Action',
//											'class'				 => 'CButtonColumn',
//											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
//											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
//											'template'			 => '{edit}{detach}',
//											'buttons'			 => array(
//												'edit'			 => array(
//													'url'		 => 'Yii::app()->createUrl("supplier/cab/add", array("veditid"=>$data[vhc_id]))',
//													'imageUrl'	 => false,
//													'label'		 => '<img src="/images/supplier/icon-8.svg" alt="img">',
//													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'p5 btnedit', 'title' => 'Edit'),
//												),
//												'detach'		 => array(
//													'url'		 => 'Yii::app()->createUrl("supplier/cab/detach", array("id"=>$data[vhc_id]))',
//													'imageUrl'	 => false,
//													'label'		 => '<img src="/images/supplier/icon-7.svg" alt="img">',
//													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'p5 btndetach', 'title' => 'Detach'),
//												),
//												'htmlOptions'	 => array('class' => 'center', 'style' => 'margin-left: 4px;'),
//											))
									)
										)
								);
							}
							?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';

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
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Booking Date Range');
        $('#Booking_bkg_create_date1').val('');
        $('#Booking_bkg_create_date2').val('');
    });


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
                ranges: {
                    'Today': [moment(), moment()],
                    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                    'Next 7 Days': [moment(), moment().add(6, 'days')],
                    'Next 15 Days': [moment(), moment().add(15, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
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
</script>
