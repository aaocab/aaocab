<div class="menu-page p10 line-height18">
	<div class="hide">
		<div class="content text-center">						
			<img src="/images/india-flag-round.svg" alt="India" width="80" class="display-inline bottom-10"><br>
			<span class="font-20"><b><a href="tel:+919051877000" class="color-green3-dark">+91-90518-77-000</a></b></span>
		</div>
		<div class="decoration decoration-margins"></div>
		<div class="content text-center">						
			<img src="/images/globe.svg" alt="World" width="50" class="display-inline bottom-10"><br>
			<span class="font-20"><b><a href="tel:+16507414696" class="color-green3-dark">+1-650-741-GOZO</a></b></span>
		</div>

	</div>
	<div class="content text-center bottom-0">						
		<span class="display-inline bottom-10 regularbold"> New Booking:</span>
		<span class="font-20"><b><a href="javascript:void(0);" class="color-green3-dark" onclick="reqCMB(1)"> Request a call back</a></b></span>
		<span class="font-20 hide"><b><a href="tel:+917044452999" class="color-green3-dark">+91 70444-52999</a></b></span>
	</div>
	<div class="decoration  top-10 bottom-10"></div>
	<div class="content text-center bottom-0">						
		<span class="display-inline bottom-10 regularbold"> Existing Booking:</span>
		<span class="font-20"><b><a href="javascript:void(0);" class="color-green3-dark" onclick="reqCMB(2)"> Request a call back</a></b></span>
		<span class="font-20 hide"><b><a href="tel:+919051877000" class="color-green3-dark">+91 90518-77000</a></b></span>
	</div>
	<div class="decoration  top-10 bottom-10"></div>
	<?php
	if ($isContactVendor === 0 || $isContactVendor > 0)
	{
		?>
		<div class="content text-center bottom-0">						
			<span   class="display-inline bottom-10 regularbold"> Vendor Helpline:</span>
			<span class="font-20"><b><a href="javascript:void(0);" class="color-green3-dark" onclick="reqCMB(4)"> Request a call back</a></b></span>
			<span class="font-20 hide"><b><a href="tel:+916289905921" class="color-green3-dark">+91 62899-05921</a></b></span>
		</div>
		<div class="decoration  top-10 bottom-10"></div>
		<?php
	} if ($isContactVendor === 0 || $isContactVendor == null)
	{
		?>
		<div class="content text-center bottom-0">						
			<span   class="display-inline bottom-10 regularbold"> Attach Your Taxi:</span>
			<span class="font-20"><b><a href="javascript:void(0);" class="color-green3-dark" onclick="reqCMB(3)"> Request a call back</a></b></span>
			<span class="font-20 hide"><b><a href="tel:+919674311190" class="color-green3-dark">+91 96743-11190</a></b></span>
		</div>
		<div class="decoration  top-10 bottom-10"></div>
	<?php } ?>
	<div class="decoration  top-5 bottom-5"></div>
	<div class="content text-center bottom-0">						
		<span class="display-inline bottom-10 regularbold"> Contact Number:</span>
		<span class="font-20  "><b><a href="tel:+919051877000" class="color-green3-dark">+91 90518-77000</a></b></span>
		<span class="  text-danger  ">* You may also directly call us on this number</span>
	</div>
</div>   