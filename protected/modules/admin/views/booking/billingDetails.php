<style>
.main-tabbill {
    border: #a7effa 1px solid;
    background: #d5faff;
    overflow: hidden;
    position: relative;
  }
</style>
<div class="main-tabbill">
	<div class="col-xs-12 col-sm-6 p0">
		<div class="main-tabbill">
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Name:</b></div>
				<div class="col-xs-6 text-right"><?= $model->bkgUserInfo->bkg_bill_fullname ?></div>
			</div>
			<?php
			$billingAdrs = "";
			if ($model->bkgUserInfo->bkg_bill_address != "")
			{
				$billingAdrs = $model->bkgUserInfo->bkg_bill_address;
			}
			if ($model->bkgUserInfo->bkg_bill_city != '' && $billingAdrs != '')
			{
				$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_city;
			}
			if ($model->bkgUserInfo->bkg_bill_state != '' && $billingAdrs != '')
			{
				$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_state;
			}
			if ($model->bkgUserInfo->bkg_bill_country != '' && $billingAdrs != '')
			{
				$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_country;
			}
			if ($model->bkgUserInfo->bkg_bill_postalcode != '' && $billingAdrs != '')
			{
				$billingAdrs .= ", " . $model->bkgUserInfo->bkg_bill_postalcode;
			}
			?>
			<div class="row p5 new-tab2">
				<div class="col-xs-3"><b>Address:</b></div>
				<div class="col-xs-9 text-right"><?= $billingAdrs; ?></div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 p0">
		<div class="main-tabbill">
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>GSTN:</b></div>
				<div class="col-xs-6 text-right"><?= $model->bkgUserInfo->bkg_bill_gst ?></div>
			</div>
			<div class="row p5 new-tab2">-</div>
		</div>
	</div>
</div>