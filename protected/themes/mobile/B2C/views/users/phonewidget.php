<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="col-12 mb-1">Mobile Number <span style="color: #F00"> *</span> <a href="javascript:void(0);" onclick="addContact(2)" class="ml-1"><u>Add new phone</u></a><br></div>
<?php
$cpModels = $contactModel->contactPhones;
if ($cpModels == [])
{
	$cpModels[] = new ContactPhone();
}
$isPrimaryPhone	 = "";
$phoneno		 = "";
for ($i = 0; $i < count($cpModels); $i++)
{
	if ($cpModels[$i]->phn_phone_no != "")
	{
		?>
		<div class="col-12">
			<?php
			$btnPhone			 = $i == 0 ? 'btn btn-success btn-xs phonePrimary ' : 'btn btn-primary btn-xs phonePrimary ';
			?>

			<?php
			echo $form->hiddenField($cpModels[$i], "[$i]phn_phone_no", array('type' => "hidden", 'class' => "phone_address", 'value' => $cpModels[$i]->phn_phone_no));
			echo $form->hiddenField($cpModels[$i], "[$i]phn_is_primary", array('type' => "hidden", 'class' => "phone_primary", 'value' => count($cpModels) == 1 ? 1 : $cpModels[$i]->phn_is_primary));
			echo $form->hiddenField($cpModels[$i], "[$i]phn_type", array('type' => "hidden", 'class' => "phone_type", 'value' => count($cpModels) == 1 ? 1 : $cpModels[$i]->phn_type));
			$verifyBorderClass	 = ($cpModels[$i]->phn_is_verified == 0) ? 'btn-outline-primary' : 'btn btn-outline-light';
			$primaryBorderClass	 = ($cpModels[$i]->phn_is_primary == 1) ? 'btn btn-outline-light' : 'btn-outline-success';
			$phoneClass			 = "fullContactNumber".$i;
			?>
			<label>
				<div class="<?= $phoneClass ?>"><?php echo $cpModels[$i]->phn_full_number ?></div>
				<a href="javascript:void(0);" style="height: 25px!important; line-height: 24px!important;" class="btn <?php echo $verifyBorderClass; ?> btn-sm button button-blue text-uppercase button-xxs pl10 pr10 <?php echo $cpModels[$i]->phn_is_verified == 0 ? '' : 'disabled' ?>" onclick="verifyContact(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>, 2)"><?php echo $cpModels[$i]->phn_is_verified == 0 ? 'Verify' : '<i class="fa fa-check-circle font-11"></i>Verified' ?></a>
				<a href="javascript:void(0);" style="height: 25px!important; line-height: 24px!important;" class="btn <?php echo $primaryBorderClass; ?> btn-sm text-uppercase button-xxs mr10 mb5 font-10 pl10 pr10 <?php echo ($cpModels[$i]->phn_is_primary == 1) ? 'disabled' : '' ?>" onclick="primaryContact(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>, 2)"><?php echo ($cpModels[$i]->phn_is_primary == 1) ? '<i class="fa fa-check-circle font-11"></i>' : '' ?> Primary</a> 
				<a href="javascript:void(0);" style="height: 25px!important; line-height: 24px!important;" class="btn btn-outline-danger btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo ($cpModels[$i]->phn_is_primary == 1) ? 'hide' : '' ?>" onclick="removeContact(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>, 2)">Remove</a>
			</label>
		</div>
		<?php
	}
}
?>