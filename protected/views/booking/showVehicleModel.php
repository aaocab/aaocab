<div class="row">
    <div class="col-lg-10 col-md-7 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 pb10 new-booking-list" >
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vehicle-form',
			'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		/* @var $form TbActiveForm */
		?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash', 'value' => $model->getHash()]); ?>
<?= $form->hiddenField($model, "bkg_flexxi_type"); ?>    
<?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
<?= $form->hiddenField($model, "bkg_trip_distance"); ?>
<?= $form->hiddenField($model, "bkg_trip_duration"); ?>
<?= $form->hiddenField($model, 'bkg_no_person') ?>
<?= $form->hiddenField($model, 'bkg_num_large_bag') ?>
<?= $form->hiddenField($model, 'bkg_num_small_bag') ?>
<?= $form->hiddenField($model, 'bkg_rate_per_km_extra'); ?>
<?= $form->hiddenField($model, 'bkg_package_id'); ?>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12"> 
						<div class="form-group">
							<div class="col-xs-6">
								<label  class="col-xs-5 text-uppercase">Vehicle Model:</label>
								<?php 
									$carModelsSelectTier = VehicleTypes::model()->getCabByVehicleTypeId($asmmodel);
									$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $model,
												'attribute'		 => 'bkg_vht_id',
												'val'			 => $model->bkg_vht_id,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($carModelsSelectTier)),
												'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select model year', 'id' => 'service_class_area')
											));
								?>
                            </div>
							<div class="col-xs-6 hide bg-navy-4" id="showfare">
								<ul class='list-unstyled'>
									<li>Base Fare: <span class='float-right' id="modelbasefare"><i class='fa fa-inr'></i></span></li>

									<li class='text-danger showDiscount hide'>Discount<sup>*</sup>(Apply
									<span id="promocode"> </span>):
									<span class='float-right' id="discount"><i class='fa fa-inr'></i></span></li>

									<li class='driverallowance hide'>Driver Allowance: 
											<span class='float-right' id="drvAllowance"><i class='fa fa-inr'></i></span></li>
									<li>Toll Tax (<span class="tolltax"></span>): 
											<span class='float-right' id="tollinclude"><i class='fa fa-inr'></i></span></li>
									<li>State Tax (<span class="statetax"></span>): 
											<span class='float-right' id="statetaxinclude"><i class='fa fa-inr'></i></span></li>
									<li>GST:<span class='float-right' id="modelgst"><i class='fa fa-inr'></i></span></li>
									<li>Total Payable: <span class='float-right' id="modeltotal"><i class='fa fa-inr'></i></span></li>
									<li class='text-success coindiscount hide'>Gozo Coins<sup>*</sup>(Apply <span id="coindispromo"></span>): 
											<span class='float-right' id="modelcoindiscount"><i class='fa fa-inr'></i></span></li>
								</ul>
							</div>
						</div>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 text-center pb10">
					<button type="button" value="<?= $scvid ?>" data-cabtype="<?= $arrVehicle['vct_label'] ?>" name="bookButton" class="btn next3-btn mt10" onclick="validateModelForm(this);">
						<b>Book Now</b>
					</button>
					<?//= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30','onclick'=>'return savetime();')); ?>
				</div>
			</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script>
$bkgId = '<?= $bkgid ?>';
$scvId = '<?= $scvid ?>';
$('#service_class_area').change(function () {
	if (this.value != '')
    {
		var asmModelId = $('#service_class_area').val();
	$href = "<?= Yii::app()->createUrl('booking/getQuoteByVehicleModel') ?>";
	jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkgid": $bkgId, "scvId": $scvId, "asmmodelid": asmModelId},
			success: function (data)
			{
				obj = jQuery.parseJSON(data);
				
				$('#modelbasefare').html(obj.baseamount);
				if(obj.discount != ''){$('#discount').html(obj.discount); $(".showDiscount").removeClass("hide");$('#promocode').html(obj.promocode);}
				
				if(obj.tollIncluded > 0){$('.tolltax').html('Included');}
				else{$('.tolltax').html('Excluded');}
				
				if(obj.tollTax > 0 || obj.tollTax == ''){$('#tollinclude').html(obj.tollTax);}
				else{$('#tollinclude').html(0);}
				
				if(obj.stateTaxIncluded > 0){$('.statetax').html('Included');}
				else{$('.statetax').html('Excluded');}
				
				if(obj.stateTax > 0){$('#statetaxinclude').html(obj.tollTax);}
				else{$('#statetaxinclude').html('0');}
				
				if(obj.coinDiscount > 0){
					$("#coindispromo").html(obj.promocode);
					$(".coindiscount").removeClass("hide");
					$('#modelcoindiscount').html(obj.coinDiscount);
				}
				
				$('#modelgst').html(obj.taxgst);
				$('#modeltotal').html(obj.amount);
				if(obj.driverAllowance != '')
				{
					$('#drvAllowance').html(obj.driverAllowance);
					$(".driverallowance").removeClass("hide");
				}
				acctbox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
				$('#showfare').removeClass('hide'); 
			}
		});
    }
});
	function validateModelForm(obj)
    {
		var asmModelId = $('#service_class_area').val();
		data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
        data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
        data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
        data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
        data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
        bookNow.data = data;
        bookNow.validateQuote(obj);
	}
</script>
