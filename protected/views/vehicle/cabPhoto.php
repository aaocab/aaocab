<div id="cabPhotoPanel" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="noPlate" class="collapse" style="">
	<div class="row"  >
		<a type="button" href="/vehicle/info" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Photo of vehicle</div> 
		</a>
	</div>
	<div class="  card-body p5" >
		<?php
		$cabFrontImage	 = VehicleDocs::getDocPathById($cabFrontImageModel->vhd_id);
		$cabRearImage	 = VehicleDocs::getDocPathById($cabRearImageModel->vhd_id);

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
					<label class="control-label">Front picture of your car</label> 
					<br>
					<div class="text-center">
						<img src="<?php echo $cabFrontImage ?>" class="pb5 imgCabFront imgHeight">
					</div> 
					<?php
					if ($cabFrontImageModel->vhd_status == 1)
					{
						echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
					}
					else
					{
						echo $form->fileFieldGroup($vhcModel, 'vhc_car_front', array('label' => 'Add photo', 'widgetOptions' => ['htmlOptions' => ['class' => 'docCabFront']]));
					}
					?>	
				</div>
			</div>       		 
			<div class="col-xs-12 col-md-6   p5">
				<div class="form-group">
					<label class="control-label">Rear picture of your car</label>
					<br>
					<div class="text-center">
						<img src="<?php echo $cabRearImage ?>" class="pb5 imgCabBack imgHeight">
					</div>
					<?php
					if ($cabRearImageModel->vhd_status == 1)
					{
						echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
					}
					else
					{
						echo $form->fileFieldGroup($vhcModel, 'vhc_car_back', array('label' => 'Add photo', 'widgetOptions' => ['htmlOptions' => ['class' => 'docCabBack']]));
					}
					?>
				</div>
			</div>
		</div> 
		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'cabbtn',));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
	$('.docCabFront').change(function () {
		fileValidation(this, 'cabbtn');
		previewDoc(this, 'imgCabFront');
	});
	$('.docCabBack').change(function () {
		fileValidation(this, 'cabbtn');
		previewDoc(this, 'imgCabBack');
	});   
</script>