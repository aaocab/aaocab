<div class="sidenav">
	<a href="<?= Yii::app()->createUrl('users/view'); ?>"><i class="fa fa-home p10 mr5"></i>My Profile</a>
	<a href="<?= Yii::app()->createUrl('users/GetQRCode'); ?>"><i class="fa fa-qrcode p10 mr5"></i>QR Code</a>
	<button class="dropdown-btn"><i class="fa fa-user p10 mr5"></i>My Bookings <i class="fa fa-caret-down mt10"></i>
	</button>
	<div class="dropdown-container">
		<a href="<?= Yii::app()->createUrl('index/index'); ?>"><i class="fa fa-angle-right p10 mr5"></i>New Booking</a>
		<a href="<?= Yii::app()->createUrl('booking/list'); ?>"><i class="fa fa-angle-right p10 mr5"></i>History</a>
	</div>
	<a href="<?= Yii::app()->createUrl('place/view'); ?>"><i class="fa fa-map-signs p10 mr5"></i>Favourite places</a>
	<a href="<?= Yii::app()->createUrl('users/refer'); ?>"><i class="fa fa-users p10 mr5"></i>Refer friends</a>

    <a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><i class="fa fa-rupee-sign p10 mr5"></i>Check balances</a>

    <a href="<?= Yii::app()->createUrl('voucher/orders'); ?>"><i class="fa fa-home p10 mr5"></i>Voucher History</a>
    <a href="<?= Yii::app()->createUrl('voucher/redeem'); ?>"><i class="fa fa-rupee-sign p10 mr5"></i>Redeem Voucher</a>

</div>
<script>
	var dropdown = document.getElementsByClassName("dropdown-btn");
	var i;

	for (i = 0; i < dropdown.length; i++) {
		dropdown[i].addEventListener("click", function () {
			this.classList.toggle("active");
			var dropdownContent = this.nextElementSibling;
			if (dropdownContent.style.display === "block") {
				dropdownContent.style.display = "none";
			} else {
				dropdownContent.style.display = "block";
			}
		});
	}
</script>