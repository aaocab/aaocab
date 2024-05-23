<div class="list-group hidden-xs">

    <ul>
        <li><a href="<?= Yii::app()->createUrl('users/view'); ?>"><i class="fa fa-home bg-primary p10 mr5"></i>My Profile</a></li>
        <li>
            <a href="#" class="dropdown-toggle"><i class="fa fa-user  bg-primary  p10 mr5"></i>My Bookings <span class="caret"></span></a>
            <ul class="dropdown-barmenu">
                <li><a href="<?= Yii::app()->createUrl('index/index'); ?>"><i class="fa fa-angle-right bg-info p10 mr5"></i> New Booking</a></li>
                <li><a href="<?= Yii::app()->createUrl('booking/list'); ?>"><i class="fa fa-angle-right bg-info p10 mr5"></i> History</a></li>
            </ul>
        </li>
        <li><a href="<?= Yii::app()->createUrl('place/view'); ?>"><i class="fa fa-map-signs  bg-primary  p10 mr5"></i> Favourite places</a></li>
		<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>"><i class="fa fa-users  bg-primary  p10 mr5"></i> Refer friends</a></li>
        
<!--		<li>
             <a href="#" class="dropdown-toggle"><i class="fa fa-money bg-primary  p10 mr5"></i>Credit History <span class="caret"></span></a>
            <ul class="dropdown-barmenu">-->
<!--                <li><a href="<?//= Yii::app()->createUrl('users/refer'); ?>"><i class="fa fa-angle-right bg-info p10 mr5"></i> Refer friends</a></li>-->
<!--                
            </ul>
        </li>-->
        <li>
            <a href="#" class="dropdown-toggle"><i class="fa fa-gift   bg-primary  p10 mr5"></i>My Wallet <span class="caret"></span></a>
            <ul class="dropdown-barmenu">
                <li><a href="<?= Yii::app()->createUrl('users/redeemgiftcard'); ?>"><i class="fa fa-angle-right bg-info p10 mr5"></i>Add Gift card</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><i class="fa fa-angle-right bg-info p10 mr5"></i>Wallet balance</a></li>
			</ul>
        </li>
		
		 <!--<li>
            <a href="#" class="dropdown-toggle"><i class="fa fa-gift   bg-primary  p10 mr5"></i>My Vouchers <span class="caret"></span></a>
            <ul class="dropdown-barmenu">
                <li><a href="<?= Yii::app()->createUrl('voucher/orderHistory'); ?>"><i class="fa fa-angle-right bg-info p10 mr5"></i>Order History</a></li>
			</ul>
        </li>-->
    </ul>
</div> 