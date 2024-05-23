<div id="permitPanel" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="permit" class="collapse" style="">
	<div class="row"  >
		<a type="button" href="/vehicle/info" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Permit</div> 
		</a>
	</div>
	<div class="  card-body p10" >
		<?php
		$permitDoc = VehicleDocs::getDocPathById($permitDocModel->vhd_id);

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

		<div class="row">
			<div class="col-xs-12 col-md-6  ">
				<div class="form-group">
					<label class="control-label">Picture of Permit Certificate</label> 
					<br>
					<div class="text-center">
						<img src="<?php echo $permitDoc ?>" class="imgPermit imgHeight">
					</div>
					<?php
					if ($permitDocModel->vhd_status == 1)
					{
						echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
					}
					else
					{
						echo $form->fileFieldGroup($vhcModel, 'vhc_permits_certificate', array('label' => 'Add photo', 'widgetOptions' => ['htmlOptions' => ['class' => 'docPermit']]));
					}
					?>
				</div>
			</div>       
		</div> 
		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Save', array('class' => 'btn btn-primary', 'id' => 'permitbtn'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
	$('.docPermit').change(function () {
		fileValidation(this, 'permitbtn');
		previewDoc(this, 'imgPermit');
	});
	    
</script>