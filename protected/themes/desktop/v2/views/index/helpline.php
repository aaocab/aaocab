 
<style>
    .smallwidth > .modal-dialog {

        width: 450px;
        margin: auto
    }
	.list-group-item{
		padding-left: 0;padding-right: 0;
	}
</style>
<?
?>

<div class="row">
    <div class="col-12 booking-no">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4"><i class="fas fa-phone-square"></i> New Booking:</div>
					<div class="col-7"><b><a type="button" class="color-white" onclick="reqCMB(1)"> Request a call back</a></b>
					</div>
                    <div class="col-7 hide">
						<b><a href="tel:+917044452999" class="color-green3-dark">+91 70444-52999</a></b>
					</div>

				</div>
            </li>
            <li class="list-group-item">
                <div class="row"> 
                    <div class="col-4"><i class="fas fa-phone-square"></i> Existing Booking:</div>
					<div class="col-7"><b><a type="button" class="color-green3-dark" onclick="reqCMB(2)"> Request a call back</a></b>
					</div>
                    <div class="col-7 hide"><b><a href="tel:+919051877000" class="color-green3-dark">+91 90518-77000</a></b></div>
                </div>
            </li>
			<?php
			if ($isContactVendor === 0 || $isContactVendor > 0)
			{
				?>
				<li class="list-group-item">
					<div class="row">
						<div class="col-4"><i class="fas fa-phone-square"></i> Vendor Helpline:</div>
						<div class="col-7 "><b><a type="button" class="color-green3-dark" onclick="reqCMB(4)"> Request a call back</a></b>
						</div>
						<div class="col-7 hide"><b><a href="tel:+919051116230" class="color-green3-dark">+91 90511-16230</a></b> <nobr><b><a href="tel:+916289905921" class="color-green3-dark">+91 62899-05921</a></b></nobr>	</div>
					</div>
				</li>
				<?php
			}
			if ($isContactVendor === 0 || $isContactVendor == null)
			{
				?>
				<li class="list-group-item">
					<div class="row">	
						<div class="col-4"><i class="fas fa-phone-square"></i> Attach Your Taxi:</div>
						<div class="col-7  "><b><a type="button" class="color-green3-dark" onclick="reqCMB(3)"> Request a call back</a></b>
						</div>
						<div class="col-7 hide "><b><a href="tel:+919674311190" class="color-green3-dark">+91 96743-11190</a></b></div>
					</div>
				</li>
			<?php } ?>
			<li class="list-group-item p0 pb5"></li>
			<li class="list-group-item ">
                <div class="row pt10">
                    <div class="col-4"><i class="fas fa-phone-square"></i> Contact Number:</div>

                    <div class="col-7  ">
						<b><a href="tel:+919051877000" class="color-green3-dark">+91 90518-77000</a></b>
					</div>

				</div>
				<div class="font-12 text-danger col-10 pt20">*  You may also directly call us on this number</div>
            </li>
        </ul>
    </div>
</div>