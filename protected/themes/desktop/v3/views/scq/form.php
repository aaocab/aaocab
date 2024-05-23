<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/res/app-assets/js/asidebar.jquery.js", CClientScript::POS_HEAD);

$isContactVnd	 = 0;
$userId			 = UserInfo::getUserId();
if ($userId > 0)
{
	$umodel			 = Users::model()->findByPk($userId);
	$contactId		 = $umodel->usr_contact_id;
	$entityType		 = UserInfo::TYPE_VENDOR;
	$vnd			 = ContactProfile::getEntityById($contactId, $entityType);
	$isContactVnd	 = $vnd['id'];
}


?>

<nav class="row justify-center">
			<?php  
				$agentId = \Yii::app()->request->cookies['gozo_agent_id']->value; 
				if($agentId != Config::get('Kayak.partner.id'))
				{
			?>
						<div class="col-11 col-md-4 col-xl-3 ui-nb">
							<a data-toggle="ajaxModal" id="newbook" rel="popover" data-placement="left" class="dropdown-item pl10" title="New Booking" onClick="return reqCMB(1)" href="<?= Yii::app()->createUrl("scq/newBookingCallBack", array("reftype" => 1)) ?>"><img src="/images/img_trans.gif" alt="New Booking" width="1" height="1" class="newBooking-1 mr10" >New Booking</a>
							<a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="dropdown-item pl10" title="New Booking" onClick="return reqCMB(2)" href="<?= Yii::app()->createUrl("scq/existingBookingCallBack", array("reftype" => 2)) ?>"><img src="/images/img_trans.gif" alt="Existing Booking" width="1" height="1" class="envelope-1 mr10" >Existing Booking</a>
							<?php
							if ($isContactVnd === 0 || $isContactVnd > 0)
							{
								?>
								<a data-toggle="ajaxModal" id="vndhelp" rel="popover" data-placement="left" class="dropdown-item pl10" title="New Booking" onClick="return reqCMB(4)" href="<?= Yii::app()->createUrl("scq/existingVendorCallBack", array("reftype" => 4)) ?>"><img src="/images/img_trans.gif" alt="Vendor Helpline" width="1" height="1" class="vendor-1 mr10">Vendor Helpline</a>
								<?php
							}
							if ($isContactVnd === 0 || $isContactVnd == null)
							{
								?>
								<a data-toggle="ajaxModal" id="attachtaxi" rel="popover" data-placement="left" class="dropdown-item pl10" title="New Booking" onClick="return reqCMB(3)" href="<?= Yii::app()->createUrl("scq/vendorAttachmentCallBack", array("reftype" => 3)) ?>"><img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="attach-1 mr10">Attach Your taxi</a>
							<?php } ?>
							<div class="divider p15">
								<div class="divider-text">Call us at</div>
							</div>
							<a id="phonecall" rel="popover" data-placement="left" class="dropdown-item pl10" href="tel:+919051877000"><img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="callus-1 mr10">+91 90518-77000</a>
							<a href="https://wa.me/918017279124?text=Hello,%20I%20need%20to%20book%20a%20cab" target=" _blank" class="dropdown-item pl10"><img src="/images/img_trans.gif" alt="Attach Your taxi" width="1" height="1" class="whatsapp-1 mr10">Whatsapp</a>
						

</div>
			<?php	
				}
				
					?>
</nav>

<script type="text/javascript">
$(document).ready(function()
	{
		var pathname = '<?= $pathname ?>'; 
		if(pathname == 'tempo9' || pathname == 'tempo12' || pathname == 'tempo15')
		{
			$('#newbook').click();
		}
	});
</script>
	