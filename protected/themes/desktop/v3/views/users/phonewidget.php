<div class="col-12 mb-1">Mobile Number <span style="color: #F00"> *</span>
    <a href="javascript:void(0);" onclick="addContact(2)" class="ml-1 btn btn-primary btn-sm round mb10 p5 pl10 pr10">
        Add new phone</a><br></div>
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
			
			?>
<div class="row">
			<label class="block col-12">
				<div class=""><?php echo $cpModels[$i]->phn_full_number ?>
					<span class="text-right float-right">
						<a class="btn <?php echo $verifyBorderClass; ?> btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo $cpModels[$i]->phn_is_verified == 0 ? '' : 'disabled' ?>" onclick="verifyContact(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>, 2)"><?php echo $cpModels[$i]->phn_is_verified == 0 ? 'Verify' : '<i class="fa fa-check-circle font-11"></i>Verified' ?></a>
						<a class="btn <?php echo $primaryBorderClass; ?> btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo ($cpModels[$i]->phn_is_primary == 1) ? 'disabled' : '' ?>" onclick="primaryContact(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>, 2)"><?php echo ($cpModels[$i]->phn_is_primary == 1) ? '<i class="fa fa-check-circle font-11"></i>' : '' ?> Primary</a>
						<a class="btn btn-outline-danger btn-sm text-uppercase mr5 mb5 p5 pt5 pb5 font-10 ml5 <?php echo ($cpModels[$i]->phn_is_primary == 1) ? 'hide' : '' ?>" onclick="removeContact(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>, 2)">Remove</a>
					</span>
				</div>
			</label>
		</div>
</div>
		<?php
	}
}
?>