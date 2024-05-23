<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<style>
.disabled{ pointer-events: none; color: #D9DCE0; border: #D9DCE0 1px solid; border-radius: 2px; display: inline-block!important;}
.input-simple-1.has-icon i{ position: relative; height: 0; line-height: 0; color: #D9DCE0;}
</style>
<div class="col-12 mb-1">Email ID <span style="color: #F00"> *</span> <a href="javascript:void(0);" onclick="addContact(1)" class="ml-1"><u>Add new email</u></a><br></div>
<?php
$ceModels = $contactModel->contactEmails;
if ($ceModels == [])
{
	$ceModels[] = new ContactEmail();
}
$isPrimaryEmail	 = "";
$email			 = "";
for ($i = 0; $i < count($ceModels); $i++)
{
	if ($ceModels[$i]->eml_email_address != "")
	{
		?>
		<div class="col-12">
			<?php
			$btnEmail			 = $i == 0 ? 'btn btn-success btn-xs emailPrimary ' : 'btn btn-primary btn-xs emailPrimary ';
			?>

			<?php
			echo $form->hiddenField($ceModels[$i], "[$i]eml_email_address", array('type' => "hidden", 'class' => "email_address", 'value' => $ceModels[$i]->eml_email_address));
			echo $form->hiddenField($ceModels[$i], "[$i]eml_is_primary", array('type' => "hidden", 'class' => "email_primary", 'value' => count($ceModels) == 1 ? 1 : $ceModels[$i]->eml_is_primary));
			echo $form->hiddenField($ceModels[$i], "[$i]eml_type", array('type' => "hidden", 'class' => "email_type", 'value' => count($ceModels) == 1 ? 1 : $ceModels[$i]->eml_type));
			$verifyBorderClass	 = ($ceModels[$i]->eml_is_verified == 0) ? 'btn-outline-primary' : 'btn btn-outline-light';
			$primaryBorderClass	 = ($ceModels[$i]->eml_is_primary == 1) ? 'btn btn-outline-light' : 'btn-outline-success';
			$emailClass			 = "eml_email_address".$i;
			?>
			<label>
				<span class="<?= $emailClass ?>"><?php echo $ceModels[$i]->eml_email_address ?></span>
				<a href="javascript:void(0);" style="height: 25px!important; line-height: 24px!important;" class="btn <?php echo $verifyBorderClass; ?> btn-sm button button-blue text-uppercase button-xxs pl10 pr10 <?php echo $ceModels[$i]->eml_is_verified == 0 ? '' : 'disabled' ?>" onclick="verifyContact('<?php echo $ceModels[$i]->eml_email_address ?>', <?php echo $ceModels[$i]->eml_contact_id ?>, 1)"><?php echo $ceModels[$i]->eml_is_verified == 0 ? 'Verify' : '<i class="fa fa-check-circle font-10"></i>Verified' ?></a>
				<a href="javascript:void(0);" style="height: 25px!important; line-height: 24px!important;" class="btn <?php echo $primaryBorderClass; ?> btn-sm text-uppercase button-xxs mr10 mb5 font-10 pl10 pr10 <?php echo ($ceModels[$i]->eml_is_primary == 1) ? 'disabled' : '' ?>" onclick="primaryContact('<?php echo $ceModels[$i]->eml_email_address ?>', <?php echo $ceModels[$i]->eml_contact_id ?>, 1)" class="<?php echo $btnEmail; ?>"><?php echo ($ceModels[$i]->eml_is_primary == 1)? '<i class="fa fa-check-circle font-10"></i>':'' ?> Primary</a>
				<a href="javascript:void(0);" style="height: 25px!important; line-height: 24px!important;" class="btn btn-outline-danger btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo ($ceModels[$i]->eml_is_primary == 1) ? 'hide' : '' ?>" onclick="removeContact('<?php echo $ceModels[$i]->eml_email_address ?>',<?php echo $ceModels[$i]->eml_contact_id ?>, 1)">Remove</a>
			</label>
		</div>
		<?php
	}
}
?>