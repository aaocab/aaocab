<style type="text/css">
.docform div.row{
background-color: #EEEEFE;
padding :3px 10px;

}
.docform{
margin-bottom:  20px  
}
</style>

<?php
$carDocType		 = VehicleDocs::model()->doctypeTxt;
$driverDocType	 = DriverDocs::model()->doctype;
$dbField		 = [
1	 => 'vhc_insurance_proof',
2	 => 'vhc_front_plate',
3	 => 'vhc_rear_plate',
4	 => 'vhc_pollution_certificate',
5	 => 'vhc_reg_certificate',
6	 => 'vhc_permits_certificate',
7	 => 'vhc_fitness_certificate'
];

$dbDriverField	 = [
1	 => [1 => 'drv_voter_id_img_path', 2 => 'drv_voter_id_img_path2'],
2	 => [1 => 'drv_pan_img_path', 2 => 'drv_pan_img_path2'],
3	 => [1 => 'drv_aadhaar_img_path', 2 => 'drv_aadhaar_img_path2'],
4	 => [1 => 'drv_licence_path', 2 => 'drv_licence_path2'],
5	 => [0 => 'drv_police_certificate']
];
$docFacetype	 = [1 => ' Front', 2 => ' Back'];
?>
<div class="col-lg-offset-1 col-lg-7 col-sm-8 mt20" style="float: none; margin: auto">
<div class="row">
<?
if ($uploadsuccess)
{
if (count($visibleDriverUpload) == 0 || count($visibleVehicleUpload) == 0)
{
	?>
	<div class="col-xs-12 text-success text-center h4">Document Uploaded Successfully</div><?
}
if (count($visibleDriverUpload) == 0 && count($visibleVehicleUpload) == 0)
{
	?>
	<div class="col-xs-12 text-primary text-center h4 ">You will receive <i class="fa fa-inr"></i> 
		150.00 after approval of your uploaded documents.</div><?
}
}
    ?>
<div class="col-xs-12">
<?
if (count($visibleDriverUpload) > 0 || count($visibleVehicleUpload) > 0)
{
?>
<div class="panel panel-default">
<div class="panel-body">
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'UploadDocs-manage-form', 'enableClientValidation' => TRUE,
	'clientOptions'			 => array(
	'validateOnSubmit'	     => true,
	'errorCssClass'		     => 'has-error'),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
	'class'		             => 'form-horizontal',
	'enctype'	             => 'multipart/form-data',
	),
));
/* @var $form TbActiveForm */
?>
<? //= $form->hiddenField($dmodel, 'drv_id');  ?>
<? //= $form->hiddenField($vmodel, 'vhc_id');   ?>
<div class=" row ">
<?
if (count($visibleDriverUpload) > 0)
{
?>
<div class="col-md-6">
<div class="row">
	<div class="col-xs-12 h4">Driver  Documents</div>
</div>
	<?php
	foreach ($visibleDriverUpload as $doctype => $value1)
	{
	?>
	<div class="docform">
	<div class="row m0 ">
	<div class="col-xs-12">
			<?
			
		   if ($doctype == 1)
			  {
				?>
				<?= $form->textFieldGroup($dmodel, 'drv_voter_id', array('label' => ' Voter Id ')) ?>
				<?
				if ($errors['drv_voter_id'][0])
				  {
				?>
				<div class="text-danger row pl0 mb10"><?= $errors['drv_voter_id'][0] ?></div>
                <?				 
				  }
			  }
		        if ($doctype == 2)
				{
				?>
				<?= $form->textFieldGroup($dmodel, 'drv_pan_no', array('label' => ' PAN Card Number')) ?>
				<?
					if ($errors['drv_pan_no'][0])
					{
					?>
					<div class="text-danger row pl0 mb10"><?= $errors['drv_pan_no'][0] ?></div>
				<?  }

				} if($doctype == 3){?>
					<?= $form->textFieldGroup($dmodel, 'drv_aadhaar_no', array('label' => ' Aadhaar Number')) ?>
					<?
					if ($errors['drv_aadhaar_no'][0])
					{
					?>
					<div class="text-danger row pl0 mb10"><?= $errors['drv_aadhaar_no'][0] ?></div>
				<?  }}
				if ($doctype == 4)
			{
			$dmodel->drv_lic_exp_date = '';
			echo $form->datePickerGroup($dmodel, 'drv_lic_exp_date', array('label' => 'Driving License Expiry Date',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 
						'startDate' => '+1d', 'format' => 'dd/mm/yyyy'),
						'htmlOptions' => array('placeholder' => 'Driver Licence Expiry Date',
						'value' => '', 'class' => 'input-group border-gray full-width')), 
				        'prepend'		 => '<i class="fa fa-calendar"></i>'));
			if ($errors['drv_lic_exp_date'][0])
			{
			?>
			<div class="text-danger row pl0 mb10"><?= $errors['drv_lic_exp_date'][0] ?></div>
		  <?}?>
			<?= $form->textFieldGroup($dmodel, 'drv_lic_number', array('label' => ' Driving License Number')) ?>
			<?
				if ($errors['drv_lic_number'][0])
				{
					?>
				<div class="text-danger row pl0 mb10"><?= $errors['drv_lic_number'][0] ?></div>
					<?
				}
		    }
				?>
			    </div>
                  <? foreach ($value1 as $drv_subtype)
	            {
		        $fieldname = $dbDriverField[$doctype][$drv_subtype];
                  ?>
					<div class="col-xs-12">
					<?= $form->fileFieldGroup($dmodel, $fieldname, 
					array('label' => $driverDocType[$doctype] . $docFacetype[$drv_subtype].' Image', 'widgetOptions' => array())); ?>
					<?
					if ($errors[$fieldname][0])
					{
					?>
					<div class="text-danger row pl0 mb10"><?= $errors[$fieldname][0] ?></div>
				  <? } ?>
				</div>
		     <? } ?>
          </div>
         </div>
	        <? } ?>
</div>		
<?
}
if (count($visibleVehicleUpload) > 0)
{
?>
<div class="col-md-6  ">
<div class="row">
	<div class="col-xs-12 h4">Cab Documents</div>
</div>
<?php
foreach ($visibleVehicleUpload as $value)
{
	?>
	<div class="docform ">
		<div class="row m0">
			<?
			if ($value == 1)
			{
				?>
				<div class="col-xs-12">
				<?
				$vmodel->vhc_insurance_exp_date = '';
				echo $form->datePickerGroup($vmodel, 'vhc_insurance_exp_date',
				array('label'	     => 'Insurance Expiry Date',
					'widgetOptions'	 => array('options' => array('autoclose' => true,
					'startDate'      => date(), 'format' => 'dd/mm/yyyy'),
					'htmlOptions'    => array('placeholder' => 'Insurance Expiry Date','value' => '', 'class' => 'input-group border-gray full-width')), 
					'prepend'		 => '<i class="fa fa-calendar"></i>'));
				?>
					<?
					if ($errors['vhc_insurance_exp_date'][0])
					{
					?>
				<div class="text-danger row pl0 mb10"><?= $errors['vhc_insurance_exp_date'][0] ?></div>
				 <? } ?>
				</div>
				<?
			}
			if ($value == 4)
			{
				?>
				<div class="col-xs-12">
					<?
					$vmodel->vhc_pollution_exp_date = '';
					echo $form->datePickerGroup($vmodel, 'vhc_pollution_exp_date', array('label' => 'PUC Expiry Date',
					'widgetOptions'	 => array('options' => array('autoclose' => true,
					'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'PUC Expiry Date',
					'value' => '', 'class' => 'input-group border-gray full-width')), 
					'prepend'		 => '<i class="fa fa-calendar"></i>'));
					if ($errors['vhc_pollution_exp_date'][0])
					{
						?>
						<div class="text-danger row pl0 mb10"><?= $errors['vhc_pollution_exp_date'][0] ?></div>
					<? } ?>
				</div>

				<?
			}
			if ($value == 5)
			{
				?>
				<div class="col-xs-12"><?
				$vmodel->vhc_dop = '';
					echo $form->datePickerGroup($vmodel, 'vhc_dop', array('label'=> 'Date Of Purchase',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 
						'startDate' => date(), 'format' => 'dd/mm/yyyy'), 
						'htmlOptions' => array('placeholder' => 'Date Of Purchase ',
						'value' => '', 'class' => 'input-group border-gray full-width')), 
						'prepend'		 => '<i class="fa fa-calendar"></i>'));
					if ($errors['vhc_dop'][0])
					{
						?>
						<div class="text-danger row pl0 mb10"><?= $errors['vhc_dop'][0] ?></div>
									<?
					}
					$vmodel->vhc_tax_exp_date = '';
					echo $form->datePickerGroup($vmodel, 'vhc_tax_exp_date', array('label'=> 'Tax Paid Upto Date',
						'widgetOptions'	 => array('options' => array('autoclose' => true,
						'startDate' => date(), 'format' => 'dd/mm/yyyy'),
						'htmlOptions' => array('placeholder' => 'Tax Paid Upto Date',
						'value' => '', 'class' => 'input-group border-gray full-width')), 
						'prepend'		 => '<i class="fa fa-calendar"></i>'));
					if ($errors['vhc_tax_exp_date'][0])
					{
						?>
						<div class="text-danger row pl0 mb10"><?= $errors['vhc_tax_exp_date'][0] ?></div>
						<?
					}
					$vmodel->vhc_reg_exp_date = '';
					echo $form->datePickerGroup($vmodel, 'vhc_reg_exp_date', array('label' => 'Registration End Date ',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 
						'startDate' => date(), 'format' => 'dd/mm/yyyy'),
						'htmlOptions' => array('placeholder' => 'Registration End Date ', 
						'value' => '', 'class' => 'input-group border-gray full-width')),
						'prepend'		 => '<i class="fa fa-calendar"></i>'));
					if ($errors['vhc_reg_exp_date'][0])
					{
					?>
						<div class="text-danger row pl0 mb10"><?= $errors['vhc_reg_exp_date'][0] ?></div>
				 <? } ?>

				</div>

				 <?
			}
			if ($value == 6)
			{
				?>
				<div class="col-xs-12"><?
					$vmodel->vhc_commercial_exp_date = '';
					echo $form->datePickerGroup($vmodel, 'vhc_commercial_exp_date', array('label' => 'Permit End Date',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 
						'startDate' => date(), 'format' => 'dd/mm/yyyy'),
						'htmlOptions' => array('placeholder' => 'Permit End Date',
						'value' => '', 'class' => 'input-group border-gray full-width')),
						'prepend'		 => '<i class="fa fa-calendar"></i>'));
					if ($errors['vhc_commercial_exp_date'][0])
					{
						?>
						<div class="text-danger row pl0 mb10"><?= $errors['vhc_commercial_exp_date'][0] ?></div>
				<?  } ?>
				</div>

				<?
			}
			if ($value == 7)
			{
				?>
				<div class="col-xs-12"><?
					$vmodel->vhc_fitness_cert_end_date = '';
					echo $form->datePickerGroup($vmodel, 'vhc_fitness_cert_end_date', array('label' => 'Fitness Expiry Date',
					'widgetOptions'	 => array('options' => array('autoclose' => true,
					'startDate'      => date(), 'format' => 'dd/mm/yyyy'), 
					'htmlOptions'    => array('placeholder' => 'Fitness Expiry Date',
					'value'          => '', 'class' => 'input-group border-gray full-width')), 
					'prepend'		 => '<i class="fa fa-calendar"></i>'));
					?>
					<?
					if ($errors['vhc_fitness_cert_end_date'][0])
					{
						?>
						<div class="text-danger row pl0 mb10"><?= $errors['vhc_fitness_cert_end_date'][0] ?></div>
				 <? } ?>
				</div>

	     <?  } ?>
			<div class="col-xs-12">
				<?= $form->fileFieldGroup($vmodel, $dbField[$value], array('label' =>$carDocType[$value].' Image', 
				'widgetOptions' => array())); ?>
				<?
				if ($errors[$dbField[$value]][0])
				{
				  ?>							
					<div class="text-danger row pl0 mb10">	<?= $errors[$dbField[$value]][0] ?></div>
			 <? } ?>
			</div>

		</div>
	</div>
<? } ?>
</div>
<? } ?>
</div>
</div>
<div class="row " >
<div class="col-xs-12  pb20 text-center">
	<?php echo CHtml::submitButton('Upload', array('class' => 'btn btn-primary')); ?>
</div>
</div>						
</div>			
	<?php $this->endWidget(); ?>
	<?
    }
 else
   {
	?>
	<div class="col-xs-12 h4 text-center">
		No Document To Upload 					
	</div>
<? } ?>
</div>
</div>
</div>