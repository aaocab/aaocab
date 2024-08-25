<?php
if (isset($organisationSchema) && trim($organisationSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	echo $organisationSchema;
	?>
	</script>
<?php
}
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
<div class="container">
    <div class="row">
        <div class="col-12 mb10">
			<div class="row">
<!--                    <div class="col-md-3"><img src="/images/contact.png" alt="" class="img-fluid"></div>-->
				<div class="col-12">
					<div class="row">
						<div class="col-md-12 text-center mb20"><p class="merriw heading-line mb10">Contact us</p></div>
						



						<div class="col-md-6 text-center mb30">
							<div class="round-3"><img src="/images/bxs-phone3.svg" alt="img" width="48" height="48"></div>
							<!--                                <img src="/images/india-flag.png" alt="India"> <span>(+91) 90518-77-000 (24x7)</b></span><br>
															<img src="/images/worl-icon.png" alt="International"> (+1) 650-741-GOZO-->
							<!--                                <a  href="javascript:void(0)" class="helpline text-warning"> Request a call back</a>-->
							<div class="dropdown-menu-left pb-0">
								<a data-toggle="ajaxModal" id="newbook" rel="popover" data-placement="left" class="btn btn-lg btn-primary btn-sm mt-1 text-uppercase hvr-push" title="New Booking" onClick="return reqCMB(1)" href="<?= Yii::app()->createUrl("scq/newBookingCallBack", array("reftype" => 1)) ?>"><img src="/images/bx-user3.svg" alt="New Booking" width="18" height="18" class="mr10">New Booking</a><br>
								<a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="btn btn-lg btn-primary btn-sm mt-1 text-uppercase hvr-push" title="New Booking" onClick="return reqCMB(2)" href="<?= Yii::app()->createUrl("scq/existingBookingCallBack", array("reftype" => 2)) ?>"><img src="/images/bx-envelope3.svg" alt="Existing Booking" width="18" height="18" class="mr10">Existing Booking</a><br>
								<?php
								if ($isContactVnd === 0 || $isContactVnd > 0)
								{
									?>
									<a data-toggle="ajaxModal" id="vndhelp" rel="popover" data-placement="left" class="btn btn-lg btn-primary btn-sm mt-1 text-uppercase hvr-push" title="New Booking" onClick="return reqCMB(4)" href="<?= Yii::app()->createUrl("scq/existingVendorCallBack", array("reftype" => 4)) ?>"><img src="/images/bx-support.svg" alt="img" width="14" height="14" class="mr50">Vendor Helpline</a><br>
									<?php
								}
								if ($isContactVnd === 0 || $isContactVnd == null)
								{
									?>
									<a data-toggle="ajaxModal" id="attachtaxi" rel="popover" data-placement="left" class="btn btn-lg btn-primary btn-sm mt-1 text-uppercase hvr-push" title="New Booking" onClick="return reqCMB(3)" href="<?= Yii::app()->createUrl("scq/vendorAttachmentCallBack", array("reftype" => 3)) ?>"><img src="/images/bx-message-edit2.svg" alt="Existing Booking" width="18" height="18" class="mr10">Attach Your Taxi</a>
<?php } ?>
								<p class="mt-1 font-16">or call <a href="tel:9051877000" class="color-black">+91 90518 77000</a></p>
							</div>

						</div>

						<div class="col-md-3 text-center mb30">
							<div class="round-3"><img src="/images/bxs-map.svg" alt="img" width="48" height="48"></div>
							<p class="mb0"><b><?= Config::getGozoAddressCity()?></b></p>
								<p><?= Config::getGozoAddress() ?>.</p>
								<p class="mb0"><b><?= Config::getGozoAddressCity(Config::Operation_address)?></b></p>
								<p><?= Config::getGozoAddress(Config::Operation_address) ?>.</p>

						</div>
						<div class="col-md-3 text-center mb30">
							<div class="round-3"><img src="/images/bx-envelope2.svg" alt="img" width="48" height="48"></div>
							<a href="mailto:info@aaocab.com" style="text-decoration: none;" class="color-blue">info@aaocab.com</a>
							<div>
								<a href="http://www.facebook.com/aaocab" target="_blank" class="mt5"><img src="/images/bxl-facebook-circle.svg" alt="img" width="36" height="36" class="mr10"></a><a href="https://twitter.com/aaocab" target="_blank"><img src="/images/bxl-twitter.svg" alt="img" width="36" height="36" class="mr10"></a><a href="http://www.instagram.com/aaocab/" target="_blank"><img src="/images/bxl-instagram-alt.svg" alt="img" width="36" height="36"></a>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<div class="container-fluid p0">
	<div class="row m0">
<!--		<div class="col-12 p0 mt30">
			<iframe src="https://maps.google.com/maps?q=%20%23401%2C%20Signet%20Tower%2C%20DN-2%2C%20Salt%20Lake%20Bypass%2C%20DN%20Block%2C%20Sector%20V%2C%20Bidhannagar%2C%20Kolkata%2C%20West%20Bengal%20700091&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="300" frameborder="0" style="margin-bottom: -4px;" allowfullscreen></iframe>
		</div>-->
	</div>
</div>

