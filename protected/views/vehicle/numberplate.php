<div id="noPlatePanel" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="noPlate" class="collapse" style="">
	<div class="row"  >
		<a type="button" href="/vehicle/info" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Number Plate</div> 
		</a>
	</div>
	<div class="  card-body p5" >
		<?php
		$frontLicenseDoc = VehicleDocs::getDocPathById($licenceFPlateDocModel->vhd_id);
		$rearLicenseDoc	 = VehicleDocs::getDocPathById($licenceRPlateDocModel->vhd_id);

		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vehicle-number',
			'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => '/vehicle/uploaddoc',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal',
				'enctype'	 => 'multipart/form-data',
			),
		));
		?>  
		<?php echo $form->hiddenField($vhcModel, 'vhc_id') ?>		 

		<div class=" ">

			<div class="col-xs-12 col-md-6   p5">
				<div class="form-group">
					<label class="control-label">Picture of front number plate</label> 
					<br>
					<div class="text-center">
					<img src="<?php echo $frontLicenseDoc ?>" class="pb5 imgPlateFront imgHeight">
					</div>
					<?php
					if ($licenceFPlateDocModel->vhd_status == 1)
					{
						echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
					}
					else
					{
						echo $form->fileFieldGroup($vhcModel, 'vhc_front_plate', array('label' => 'Add photo', 'widgetOptions' => ['htmlOptions' => ['class' => 'docPlateFront']]));
					}
					?>
				</div>
			</div>     

			<div class="col-xs-12 col-md-6    p5">
				<div class="form-group">
					<label class="control-label">Picture of rear number plate</label>
					<br>
					<div class="text-center">
						<img src="<?php echo $rearLicenseDoc ?>" class="pb5 imgPlateBack imgHeight">
					</div>
					<?php
					if ($licenceRPlateDocModel->vhd_status == 1)
					{
						echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
					}
					else
					{
						echo $form->fileFieldGroup($vhcModel, 'vhc_rear_plate', array('label' => 'Add photo', 'widgetOptions' => ['htmlOptions' => ['class' => 'docPlateBack']]));
					}
					?> 
				</div>
			</div>
		</div> 
		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Save', array('class' => 'btn btn-primary','id' => 'numberbtn'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
	$('.docPlateFront').change(function () {
		fileValidation(this, 'numberbtn');
		previewDoc(this, 'imgPlateFront');
	});
	$('.docPlateBack').change(function () {
		fileValidation(this, 'numberbtn');
		previewDoc(this, 'imgPlateBack');
	});   
</script>