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
			?>
<div class="row">
			<label class="block col-12">
				<div class=""><?php echo $ceModels[$i]->eml_email_address ?>
<span class="text-right float-right">
				<a class="btn <?php echo $verifyBorderClass; ?> btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo $ceModels[$i]->eml_is_verified == 0 ? '' : 'disabled' ?>" onclick="verifyContact('<?php echo $ceModels[$i]->eml_email_address ?>', <?php echo $ceModels[$i]->eml_contact_id ?>, 1)"><?php echo $ceModels[$i]->eml_is_verified == 0 ? 'Verify' : '<i class="fa fa-check-circle font-11"></i>Verified' ?></a>
				<a class="btn <?php echo $primaryBorderClass; ?> btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo ($ceModels[$i]->eml_is_primary == 1) ? 'disabled' : '' ?>" onclick="primaryContact('<?php echo $ceModels[$i]->eml_email_address ?>', <?php echo $ceModels[$i]->eml_contact_id ?>, 1)" class="<?php echo $btnEmail; ?>"><?php echo ($ceModels[$i]->eml_is_primary == 1)? '<i class="fa fa-check-circle font-11"></i>':'' ?> Primary</a> 
				<a class="btn btn-outline-danger btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo ($ceModels[$i]->eml_is_primary == 1) ? 'hide' : '' ?>" onclick="removeContact('<?php echo $ceModels[$i]->eml_email_address ?>',<?php echo $ceModels[$i]->eml_contact_id ?>, 1)">Remove</a>
</span>
</div>
</div>
			</label>
		</div>
		<?php
	}
}
?>