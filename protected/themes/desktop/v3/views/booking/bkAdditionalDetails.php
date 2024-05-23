<?php 


$disabled ="";
if (!in_array($model->bkg_status, [15]))
{
	
	$disabled	 = "disabled";
}


$form					 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingadditionalinfo', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */


$cityArr = Cities::model()->getDetailsByCityId($model->bkg_from_city_id);
$isAirport = $cityArr['cty_is_airport'];
?>
<div class="row" style="<?php echo $show; ?>">
<?php
	echo $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']);
	echo $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
	?>
<!--<h5>Additional Details</h5>-->

<div class="col-lg-12">
		<div class="row">
<!--			<div class="col-12 col-lg-6 mb-1 radio-style4">
				<p class="mb5">Personal or business trip?</p>
				<?php echo $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id']); ?>
				<div class="radio inline-block mr-1">
					<input id="BookingAddInfo_bkg_user_trip_type_0" value="1" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type" <?php echo ($model->bkgAddInfo->bkg_user_trip_type == 1) ? " checked " : ""; ?> <?=$disabled?>>
					<label for="BookingAddInfo_bkg_user_trip_type_0">Personal</label>
				</div>
				<div class="radio inline-block">
					<input id="BookingAddInfo_bkg_user_trip_type_1" value="2" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type" <?php echo ($model->bkgAddInfo->bkg_user_trip_type == 2) ? " checked " : ""; ?> <?=$disabled?>>
					<label for="BookingAddInfo_bkg_user_trip_type_1">Business</label>
				</div>

			</div>-->
			<?php
			$readOnly = [];
			if (in_array($model->bkg_flexxi_type, [1, 2]))
			{
				$readOnly = ['readOnly' => 'readOnly'];
			}
			?>
			<div class="col-12 col-lg-6">
				<div class="form-group">
					<label for="helpInputTop">No. of passengers </label>
					<?php
					$scvMapModel = SvcClassVhcCat:: getVctSvcList("object", 0, 0, $model->bkg_vehicle_type_id);
					$scvMapModel->vct_capacity;
					?>
					<select class="form-control" id="BookingAddInfo_bkg_no_person" name="BookingAddInfo[bkg_no_person]" <?=$disabled?>>
						<?php
						for ($i = 0; $i <= $scvMapModel->vct_capacity; $i++)
						{
							?>
							<option value="<?php echo $i ?>" <?php echo ($model->bkgAddInfo->bkg_no_person == $i) ? " selected " : ""; ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
					<?php //echo $form->numberField($model->bkgAddInfo, 'bkg_no_person', ['placeholder' => "0", 'min' => 1, 'max' => $scvMapModel->vct_capacity, 'class' => 'form-control']) ?>
				</div>
			</div>
			<div class="col-12 col-lg-6">
				<?php 
				if($isAirport == 1)
				{
				?>
				<div class="form-group">
					<label>Flight Number</label>
					<input type="text" id="BookingAddInfo_bkg_flight_no" maxlength="10"   name="BookingAddInfo[bkg_flight_no]" class="form-control" value="<?php echo ($model->bkgAddInfo->bkg_flight_no != '') ? $model->bkgAddInfo->bkg_flight_no : ""; ?>" >
				</div>
				<?php } ?>
			</div>
				
			<div class="col-12 col-lg-6">
				<div class="form-group">
					<label>No. of large bags</label>
					<?php
					$vct_Id		 = $model->bkgSvcClassVhcCat->scv_vct_id;
					$scc_Id		 = $model->bkgSvcClassVhcCat->scv_scc_id;
					$sbagRecord	 = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
					$lbag		 = floor($sbagRecord['vcsc_small_bag'] / 2);
					?>
					<select <?=$disabled?> class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag'] ?>);">
						<?php
						for ($i = 0; $i <= $lbag; $i++)
						{
							?>
							<option value="<?php echo $i ?>" <?php echo ($model->bkgAddInfo->bkg_num_large_bag == $i) ? " selected " : ""; ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-12 col-lg-6">
				<div class="form-group">
					<label>No. of small bags</label>
					<?php
					$vct_Id		 = $model->bkgSvcClassVhcCat->scv_vct_id;
					$scc_Id		 = $model->bkgSvcClassVhcCat->scv_scc_id;
					$sbagRecord	 = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
					$lbag		 = floor($sbagRecord['vcsc_small_bag'] / 2);
					?>
					<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]" <?=$disabled?>>
						<?php
						for ($i = 1; $i <= $sbagRecord['vcsc_small_bag']; $i++)
						{
							?>
							<option value="<?php echo $i ?>" <?php echo ($model->bkgAddInfo->bkg_num_small_bag == $i) ? " selected " : ""; ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
				//$scvVctId  = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
				//if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
				//{
					?>
					<div class="col-12">
						<fieldset>
							<div class="checkbox">
								<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_carrier', ['onclick' => 'splRequest()', 'disabled' => $disabled]); ?>
								<label class="font-15" for="BookingAddInfo_bkg_spl_req_carrier">Overhead carrier (to accommodate 4 additional pieces of luggage - INR 150)</label>
							</div>
						</fieldset>
					</div>
					<?
				//}
			?>
			<div class="col-12 mt-1 mb-1">
				<fieldset>
					<label for="Booking_bkg_add_my_trip">Select your journey break</label>
					<?php echo $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['' => 'No journey break required', '0' => '15 Minutes | Free', '30' => '30 Minutes | ₹150', '60' => '60 Minutes | ₹300', '90' => '90 Minutes | ₹450', '120' => '120 Minutes | ₹600', '150' => '150 Minutes | ₹750', '180' => '180 Minutes | ₹900'], ['class' => 'form-control', 'placeholder' => 'Journey Break', 'disabled' => $disabled]) ?>
					<?php echo $form->error($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['class' => 'help-block error']); ?>
				</fieldset>
            </div>
		</div>
</div>

</div>
<?php $this->endWidget(); ?>