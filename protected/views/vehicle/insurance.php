<div id="insurancePanel" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="insurance" class="collapse" style="">
	<div class="row"  >
		<a type="button" href="/vehicle/info" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Vehicle Insurance</div> 
		</a>
	</div>
	<div class="  card-body p5" >
		<?php
		$insuranceDoc = VehicleDocs::getDocPathById($insuranceDocModel->vhd_id);

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
			<div class="col-xs-12 col-md-6 p5">
				<div class="form-group">
					<label class="control-label">Picture of Insurance</label> 
					<br>
					<div class="text-center">
						<img src="<?php echo $insuranceDoc ?>" class="imgInsurance imgHeight">
					</div>
					<?php
					if ($insuranceDocModel->vhd_status == 1)
					{
						echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
					}
					else
					{
						echo $form->fileFieldGroup($vhcModel, 'vhc_insurance_proof', array('label' => 'Add photo', 'widgetOptions' => ['htmlOptions' => ['class' => 'docInsurance']]));
					}
					?>

				</div>
			</div>       
		</div> 
		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'insbtn'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
	$('.docInsurance').change(function () {
		fileValidation(this, 'insbtn');
		previewDoc(this, 'imgInsurance');
	});
</script>