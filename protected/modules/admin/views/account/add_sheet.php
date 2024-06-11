<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$arrSheetType = $model->arrSheetType;
?>
<style>
    .form-horizontal .checkbox-inline{
        padding-top: 0;
        padding-left: 0!important;
    }  
    .tt-suggestion {
        font-size: 1.2em;
        line-height: 0.7em;
        padding: 0;
        margin:  0;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}

</style>

<div class="row">
    <div class="col-md-6 col-sm-8 pt20 new-booking-list" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">

        </div>
        <div class="row">
            <div class="upsignwidt">
                <div class="col-xs-12 pb5">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vehicle-type-register-form', 'enableClientValidation' => TRUE,
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
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data'
						),
					));
					/* @var $form TbActiveForm */
					?>
                    <div class="panel panel-default">
                        <div class="panel-body">
							<div class="row">
								<div class="col-xs-12 col-md-6 text-center text-color-red">
									<?php (($success > 0) ? "Success Imported" : ($success === false ? "Error in Importing" : ""));?>
								</div>
                            </div>
							<div class="row">
                                <div class="col-xs-12 col-md-6">
									<?= $form->textFieldGroup($model, 'prs_title', array('label' => 'Title', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Title')))); ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<?= $form->dropDownListGroup($model, 'prs_sheet_type', array('label' => 'Sheet Type','widgetOptions' => array('data' => $arrSheetType)));?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6">
									<?php
										$daterang	 = "Pickup Date Range";
										$pickupdate1 = ($model->prs_pickup_from_date == '') ? '' : $model->prs_pickup_from_date;
										$pickupdate2 = ($model->prs_pickup_to_date == '') ? '' : $model->prs_pickup_to_date;
										if ($pickupdate1 != '' && $pickupdate2 != '')
										{
											$daterang = date('F d, Y', strtotime($pickupdate1)) . " - " . date('F d, Y', strtotime($pickupdate2));
										}
										?>
										<label  class="control-label">Pickup Date Range</label>
										<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
											<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
											<span><?= $daterang ?></span> <b class="caret"></b>
										</div>
										<?php
										echo $form->hiddenField($model, 'prs_pickup_from_date');
										echo $form->hiddenField($model, 'prs_pickup_to_date');
									?>
								</div>
								<div class="col-xs-12 col-md-6">
									<label class="control-label mb5">Choose File</label>
									<input type="file" name="file" id="file" accept=".csv" required="required" />
								</div>
							</div>
							<div class="row pt20">
								<div class="col-xs-12 col-md-12" style="text-align: center">
									<?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary')); ?>
								</div>
							</div>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
    });

    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
	
	$('#vehicle-type-register-form').submit(function()
	{
		var cabType = $('#vehicle_car_type').val();
		if(cabType == '')
		{
			 $("#VehicleTypes_carType_em_").text("Car type cannot be blank");
			 $("#VehicleTypes_carType_em_").css({"color": "#a94442", "display": "block"});
			 return false;
		}
		else{
			$("#VehicleTypes_carType_em_").css({"display": "none"});
		}
		return true;
	});
	
	var start = '<?= date('d/m/Y', strtotime($model->prs_pickup_from_date)); ?>';
	var end = '<?= date('d/m/Y', strtotime($model->prs_pickup_to_date)); ?>';
	
	$('#bkgPickupDate').daterangepicker({
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
				'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			}
		},
		function(start1, end1)
		{
			$('#PartnerReconciliationSheet_prs_pickup_from_date').val(start1.format('YYYY-MM-DD'));
			$('#PartnerReconciliationSheet_prs_pickup_to_date').val(end1.format('YYYY-MM-DD'));

			$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#bkgPickupDate').on('cancel.daterangepicker', function(ev, picker)
		{
			$('#bkgPickupDate span').html('Select Date Range');
			$('#PartnerReconciliationSheet_prs_pickup_from_date').val('');
			$('#PartnerReconciliationSheet_prs_pickup_to_date').val('');

		});
</script>