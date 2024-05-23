<?php
$contactDetails								 = BookingUser::getUserDetailsById($usrModel->bui_id);
$contactDetails['phn_phone_country_code']	 = $contactDetails['phn_phone_country_code'] ? $contactDetails['phn_phone_country_code'] : $usrModel->bkg_country_code;
?>
<?= $form->hiddenField($usrModel, 'bkg_contact_id'); ?>
<?= $form->hiddenField($usrModel, 'bkg_user_id'); ?>
<?= $form->hiddenField($usrModel, 'bkg_contact_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $contactDetails['phn_phone_no'])))); ?>
<?= $form->hiddenField($usrModel, 'bkg_country_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $contactDetails['phn_phone_country_code'])))); ?>
<?= $form->hiddenField($usrModel, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $contactDetails['eml_email_address'])))); ?>
<?= $form->hiddenField($usrModel, 'bkg_user_fname', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $contactDetails['ctt_first_name'])))); ?>
<?= $form->hiddenField($usrModel, 'bkg_user_lname', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('value' => $contactDetails['ctt_last_name'])))); ?>
<div class="row hide" id="custonerInformation">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<h3 class="pl15">Customer Information</h3>
			<div class="panel-body pt0">
				<div class="row">
					<div class="col-xs-12" id="linkedusers">

					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<button type='button' class='btn btn-info btn-customerInfo pl20 pr20'>Next</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
