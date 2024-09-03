<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/promo.js?v=' . $version);

$datefrom				 = ($model->prt_package_valid_from != '') ? $model->prt_package_valid_from : 'now';
$dateto					 = ($model->prt_package_valid_to != '') ? $model->prt_package_valid_to : 'now';
?>

<div class="container">
	<?php
	$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'promotion-form', 'enableClientValidation' => false,
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
//			'class' => 'form-horizontal',
		),
	));
	/* @var $form TbActiveForm */
	?>



    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-border">
				<div class="panel-body pt0">
					<div class="row">
						<div class="col-sm-12">
							<h3> <?php echo $packageModel['pck_auto_name'] ?></h3>
						</div>
						<div class="col-sm-12">
							<?php echo $packageModel['pck_desc'] ?>
						</div>
						<div class="col-sm-12">
							<ul>
								<li>Total Days :   <?php echo $packageModel['pck_no_of_days'] ?></li> 
								<li>Total Nights : <?php echo $packageModel['pck_no_of_nights'] ?></li>
								<li>Total Kms  :   <?php echo $packageModel['pck_km_included'] ?></li> 
							</ul>
						</div> 
					</div>
				</div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12">

			<div class="panel panel-default panel-border">

				<div class="panel-body pt0">
					<div class="row pt20 text-danger" ><?php echo $msg; ?></div>
					<div class="row pt20" >
						<input type="hidden" name="pickupcity" id="pickupcity" value="<?= $packageModel['firstFromCity'] ?>">
						<input type="hidden" name="dropcity" id="dropcity" value="<?= $packageModel['lastToCity'] ?>">
						<input type="hidden" name="tripDistance" id="tripDistance" value="<?= $packageModel['pck_km_included'] ?>">
						<input type="hidden" name="pck_id" id="pck_id" value="<?= $packageModel['pck_id'] ?>">
						<input type="hidden" name="multijsondata" id="multijsondata" value="<?= htmlentities(json_encode($packagemodel->packageJsonData, JSON_UNESCAPED_SLASHES)); ?>">
					</div>

					<div class="row">
						<div class="col-sm-6"><label>From Date</label>
							<?=
							$form->datePickerGroup($model, 'locale_prt_package_valid_from', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>  
						</div>

						<div class="col-sm-6"><label>To Date</label>
							<?=
							$form->datePickerGroup($model, 'locale_prt_package_valid_to', array('label'			 => '',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateto)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>  
						</div>

					</div>
					<div class="row">
						<div class="col-sm-4 form-group"><label>Cab Type</label>

							<?php
							$cartype				 = SvcClassVhcCat::getVctSvcList();
							//unset($cartype[93]);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'prt_package_cab_type',
								'val'			 => $model->prt_package_cab_type,
								'data'			 => $cartype,
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Car Type')
							));
							?>

						</div>
						<div class="col-sm-4 form-group"><label>Trip Type</label>

							<?
							$bookingType			 = Package::model()->getTripType();
							$model->prt_trip_type	 = 2;
//									$dataBookType	 = VehicleTypes::model()->getJSON($bookingType);
//									$this->widget('booster.widgets.TbSelect2', array(
//										'model'			 => $model,
//										'attribute'		 => 'prt_trip_type',
//										'val'			 => $model->prt_trip_type,
//										'asDropDownList' => FALSE,
//										'options'		 => array('data' => new CJavaScriptExpression($dataBookType)),
//										'htmlOptions'	 => array('style' => 'width:100%' )
//									));
							?>
							<div class="form-control"><?= $bookingType[$model->prt_trip_type] ?></div>
						</div>
						<div class="col-sm-4">
							<?= $form->textFieldGroup($model, 'prt_package_rate', array('label' => "Package Rate*", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Package Rate')), 'groupOptions' => ['class' => 'mb0'])) ?>   
							<div id="pckRate"></div>


						</div>
					</div>

					<div class="row">
						<div class="col-sm-4 col-xs-12">

							<?= $form->textFieldGroup($model, 'prt_vendor_amt', array('label' => "Vendor Amount*", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Vendor Amount')), 'groupOptions' => ['class' => 'mb0'])) ?> 
							<div id="pckVndAmt"></div></div>   
						<div class="col-sm-4 col-xs-12"> 
							<?= $form->textFieldGroup($model, 'prt_rate_per_km', array('label' => "Rate per km*", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Rate per km')), 'groupOptions' => ['class' => 'mb0'])) ?>       
							<div id="pckRtPkm"></div> </div>  
						<div class="col-sm-4 col-xs-12"> 
							<?= $form->textFieldGroup($model, 'prt_driver_allowance', array('label' => "Driver Allowance *", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Driver Allowance')), 'groupOptions' => ['class' => 'mb0'])) ?> 
							<div id="pckDrvAlnc"></div> 
						</div>
					</div>

					<div class="row">
						<div class="col-sm-4">
							<div class="input-group p5 pt30">
								<a href="#" data-toggle="tooltip" title="If included is checked then toll+tax its part of the packagr rate.
								   If not included,then when calculating base fare do not subtract toll and DA">Toll & Tax Included </a>
								<span class="checkertolltax">   
									<?php echo $form->checkBox($model, 'prt_isIncluded', array('value' => 1, 'uncheckValue' => 0, 'checked' => ($model->prt_isIncluded == 1) ? true : $model->prt_isIncluded, 'style' => 'margin-top:7px;')); ?>
								</span> 
								<br /><div id="pckIncludedtax"></div> 

							</div>  </div>
						<div class="col-sm-4"><div class="input-group  p5">
								<?= $form->textFieldGroup($model, 'prt_toll_tax', array('label' => "Toll Tax", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Toll Tax')), 'groupOptions' => ['class' => 'mb0'])) ?> 
								<div id="pckTtax"></div> 
							</div>  </div>

						<div class="col-sm-4"><div class="input-group  p5">
								<?= $form->textFieldGroup($model, 'prt_state_tax', array('label' => "State Tax", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter State Tax')), 'groupOptions' => ['class' => 'mb0'])) ?>
								<div id="pckStax"></div>  
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-3">
							<div class="input-group p5 pt30">
								<a href="#" data-toggle="tooltip" title="If included is checked then parking value entered in the
								   field is already part of the package rate.
								   if not included,then when calculating base fare we will not subtract the stated parking charged from rate.">Parking Included </a>
								<span class="checkertolltax">   
									<?php echo $form->checkBox($model, 'prt_isParkingIncluded', array('value' => 1, 'uncheckValue' => 0, 'checked' => ($model->prt_isParkingIncluded == 1) ? true : $model->prt_isParkingIncluded, 'style' => 'margin-top:7px;')); ?>
								</span></div>
						</div>
						<div class="col-sm-2">
							<div class="input-group  p5">
								<?= $form->textFieldGroup($model, 'prt_parking', array('label' => "Parking Charge", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Parking')), 'groupOptions' => ['class' => 'mb0'])) ?> 
							</div>
						</div>
						<div class="col-sm-3">
							<div class="input-group  p5">
								<?= $form->textAreaGroup($model, 'prt_comment', array('label' => "Comment", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Comment')), 'groupOptions' => ['class' => 'mb0'])) ?> 
							</div>
						</div>
						<div class="col-sm-2">
							<div class="input-group  p5">
								<label class="control-label" for="PackageRate_prt_package_rate">Base fare</label>
								<input placeholder="Base fare" class="form-control" name="Package_basefare" id="Package_basefare" type="text" readonly="readonly">


							</div>
						</div>

						<div class="col-sm-2">
							<div class="input-group pr20 pt30">
								<button type="button" class="btn btn-info" id="calBaseFare"  title="Calculate base fare">
									Calculate base fare</button></div>
						</div>

					</div>
				</div>
			</div>




        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 text-center pb10 mr30">
            <button type="button" class="btn btn-info" id="calculateQuote"  title="Suggest System Calculated Value">Suggest System Calculated Value</button>
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
        </div>
    </div>
    <div id="driver1"></div>
	<?php $this->endWidget(); ?>


</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel mb0">
            <div class="panel-body p0">
				<?
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'relatedLead',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
					<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
					</div></div>
					<div class='panel-body'>{items}</div>
					<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  mb0 compact'),
						// 'ajaxType' => 'POST',
						'columns'			 => array(
//							array('name'	 => 'prt_package_cab_type', 'value'	 =>
//								function($data) {
//									$cartype = VehicleTypes::model()->getParentVehicleTypes(1);
//									echo $cartype[$data['prt_package_cab_type']];
//								},
//								'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Cab Type'),
							array('name' => 'cabtype', 'value' => '$data["cabtype"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
							array('name' => 'pck_km_included', 'value' => '$data["pck_km_included"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Km Included'),
							array('name'	 => 'prt_package_rate', 'value'	 =>
								function($data) {

									echo $data['prt_package_rate'] - ($data['prt_driver_allowance'] + $data['prt_state_tax'] + $data['prt_toll_tax']);
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Base Amount '),
							array('name' => 'prt_vendor_amt', 'value' => '$data["prt_vendor_amt"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Vendor Amount'),
							array('name' => 'prt_rate_per_km', 'value' => '$data["prt_rate_per_km"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Rate per km for extra kms'),
							array('name' => 'prt_state_tax', 'value' => '$data["prt_state_tax"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'State Tax'),
							array('name' => 'prt_toll_tax', 'value' => '$data["prt_toll_tax"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Toll Tax'),
							array('name'	 => 'prt_isParkingIncluded', 'value'
								=> function($data) {
									if ($data['prt_isParkingIncluded'] != 0)
									{
										echo $data['prt_parking'];
									}
									else
									{
										echo "Parking not entered";
									}
								}
								, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Parking'),
							array('name'	 => 'prt_package_rate', 'value'	 =>
								function($data) {
									echo $data['prt_package_rate'] + $data['prt_parking'];
								}
								, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Package rate'),
							array('name'	 => 'prt_isIncluded', 'value'
								=> function($data) {
									if ($data['prt_isIncluded'] != 0)
									{
										echo "Tax Included";
									}
									else
									{
										echo "Tax not included";
									}
								}
								, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Tax'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{edit}{delete}',
								'buttons'			 => array(
									'edit'	 => array(
										'url'		 => 'Yii::app()->createUrl("admin/package/addRate", array("prt_id" => $data[prt_id],"pck_id" => $data[prt_pck_id]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\edit_booking.png',
										'label'		 => '<i class="fa fa-edit"></i>',
										'options'	 => array('style' => '', 'class' => 'btn btn-xs conEdit p0', 'title' => 'Edit Package'),
									),
									'delete' => array(
										'click'		 => 'function(){
                                            var con = confirm("Are you sure you want to delete this rate?");
                                            return con;
                                        }',
										'url'		 => 'Yii::app()->createUrl("admin/package/delrate", array("prt_id" => $data[prt_id] ,"pck_id" => $data[prt_pck_id]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\customer_cancel.png',
										'label'		 => '<i class="fa fa-remove"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Rate'),
									),
								))
					)));
				}
				?>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $("#calculateQuote").hide();
        $('#PackageRate_prt_parking').attr('readOnly', 'readOnly');
    });
    $(document).on('click', '#PackageRate_prt_isParkingIncluded', function () {
        if ($('#PackageRate_prt_isParkingIncluded').is(':checked')) {
            $('#PackageRate_prt_parking').removeAttr('readOnly');

        } else {
            $('#PackageRate_prt_isParkingIncluded').val(0);
            $('#PackageRate_prt_parking').attr('readOnly', 'readOnly');
        }
    });
    $("#PackageRate_prt_package_cab_type").change(function () {
        $("#calculateQuote").show("slow");
    });

    $("#calculateQuote").click(function () {
        getAmountbyCitiesnVehicle();

    });

    function getAmountbyCitiesnVehicle() {
        var booking = new Booking();
        var model = {};
        model.fromCity = $("#pickupcity").val();
        model.toCity = $("#dropcity").val();
        model.cabType = $("#PackageRate_prt_package_cab_type").val();
        model.tripDistance = $('#tripDistance').val();
        model.tripType = 5;
        model.multiCityData = $('#multijsondata').val();
        model.bookingType = 5;
        model.isPackageType = 1;
        model.YII_CSRF_TOKEN = $('input[name="YII_CSRF_TOKEN"]').val();
        model.pckageID = $("#pck_id").val();
        model.isCalculate = 1;
        booking.model = model;
        if (model.fromCity != '' && model.toCity != '' && model.cabType != '')
        {
            $(document).on("getQoute", function (event, data) {
                getQoutation(data);
            });
            booking.getQoute();
        }
    }

    function getQoutation(data)
    {
        var qRouteRates = data.data.quoteddata.routeRates;
        var baseamt = parseInt(Math.round(qRouteRates.baseAmount));
        var stax = parseInt(Math.round(qRouteRates.stateTax | 0));
        var ttax = parseInt(Math.round(qRouteRates.tollTaxAmount | 0));
        var da = parseInt(Math.round(qRouteRates.driverAllowance | 0));
        var packageRate = baseamt + stax + ttax + da;
        $("#pckRtPkm").text("Calculated: " + qRouteRates.ratePerKM);
        $('#pckDrvAlnc').text("Calculated: " + qRouteRates.driverAllowance);
        $("#pckTtax").text("Calculated: " + qRouteRates.tollTaxAmount | "Calculated: " + 0);
        $("#pckStax").text("Calculated: " + qRouteRates.stateTax | "Calculated: " + 0);
        $("#pckVndAmt").text("Calculated: " + Math.round(qRouteRates.vendorAmount));
        $("#pckIncludedtax").text("System did not provided tax values");
        $("#pckRate").text("Calculated: " + packageRate);
    }
    $('#calBaseFare').click(function () {
        var basefare;
        var tollstateamt;
        var parkingamt;
        var rate = parseInt(Math.round($("#PackageRate_prt_package_rate").val() | 0));
        var toll = parseInt(Math.round($("#PackageRate_prt_toll_tax").val() | 0));
        var state = parseInt(Math.round($("#PackageRate_prt_state_tax").val() | 0));
        var parking = parseInt(Math.round($("#PackageRate_prt_parking").val() | 0));
        var DA = parseInt(Math.round($("#PackageRate_prt_driver_allowance").val() | 0));
        if ($('#PackageRate_prt_isIncluded').is(':checked')) {
            tollstateamt = toll + state;
        } else {
            tollstateamt = 0;
        }
        if ($('#PackageRate_prt_isParkingIncluded').is(':checked')) {
            parkingamt = parking;
        } else {
            parkingamt = 0;
        }
        basefare = rate - (tollstateamt + parkingamt + DA);
        $("#Package_basefare").val(basefare);

    });

</script>