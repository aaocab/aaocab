<style>
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .top-buffer{padding-top: 10px;}
    .modal-dialog{ width: 95%!important;}
	.light-orang-bg{ background: #ffe1cc!important}
	.light-blue-bg{ background: #dfecf4!important}
	.proceed-new-btn{ font-size: 13px;}
	.btn-height{ min-height: 300px;}
</style>

<div class="row pt20">
	<div class="col-xs-12 col-md-12">
		<div class="row" >
			<div class="col-xs-12 col-sm-7 join_padding mb20">
				<div class="pt20 new-booking-list main_time border-blueline">
<?php if($model['bkg_status'] !=5) { echo "<h4 style='color:red; margin-left:20px'>Cab and Driver not assigned yet.</h4>" ;} ?> 
					<div class="col-xs-12 mb0 ml5" style="color:#48b9a7;"><h4><b>Driver Details</b></h4></div>
					<div class="row">
						<div class="col-xs-11 col-md-8 ml20">
							<span><h4>Driver name :  <?php echo $model['drv_name']; ?></h4></span>					

							<span><h4>Driver license :  <?php echo $model['ctt_license_no'];   ?></h4></span>						

							<span><h4>Driver state :</b>  <?php echo $model['stt_name']; ?></h4></span>

							<div class="col-xs-12" style="color:#48b9a7; margin-left: -15px;"><h4><b>Cab Details</b></h4></div>

							<span><h4>Cab Registration number :  <?php echo $model['vhc_number']; ?></h4></span>	

							<span><h4>Vehicle model :  <?php echo $model['vht_model']; ?></h4></span>						

							<span><h4>Vehicle year : <?php echo $model['vhc_year']; ?></h4></span>						

							<span><h4>Vehicle owner name :  <?php echo $model['vhc_reg_owner']; ?></h4></span>	
						</div>
					</div>

				</div>
			</div>
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'upload-form', 'enableClientValidation' => FALSE,
				'clientOptions'			 => array(
					'validateOnSubmit' => true
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
				),
			));
			/* @var $form TbActiveForm */
			?>

			<div class="col-xs-12 col-sm-5 join_padding mb20">
				<div class="pt20 new-booking-list main_time border-blueline">
					<div class="col-xs-12 mb10 ml5" style="color:#48b9a7;"><h4>UPLOAD E-PASS</h4></div>
					<?php
					if ($success)
					{
						echo $msg;
						//echo $error;
					}
					?>
					<div class="row">
						<div class="col-xs-11 col-md-8 ml20">

<!--							<input type="file" name="bcb_epass" value="" class="form-control" enctype="multipart/form-data">-->
							<?= $form->fileFieldGroup($modeltrail, 'btr_epass', array('label' => 'E-PASS', 'widgetOptions' => array())); ?>
							<span>(*)Note: Formate should be like (png,jpg,jpeg).</span>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-11 col-md-8 col-lg-4 top-buffer ml20 pt0">
							<div class="Submit-button" style="text-align: left; margin-top: 10px;">
								<!--					<button type="button" class = "btn btn-primary btn-lg pl40 pr40 proceed-new-btn">Submit</button>-->

								<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>

							</div>
						</div>
					</div>
					</br>
					<? if ($modeltrail->btr_epass != '')
					{ ?>
						<img src="<?= Yii::app()->baseUrl ?><?= $modeltrail->btr_epass ?>" width="100%">
<? } ?>
				</div>
			</div>
<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

