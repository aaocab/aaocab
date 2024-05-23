<style>
.dropdown-menu-2 ul li a{ margin: 0 0 10px 0; background: #dde5fe; border-radius: 3px; cursor: pointer;}
</style>
<?php 
		$userId		 = UserInfo::getUserId();
		if ($userId > 0)
		{
			$contactId	 = ContactProfile::getByEntityId($userId);
			$vendor		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$isContactVendor  = $vendor['id'];
		}

?>
	<a href="#" class="dropdown-toggle" style="line-height: 36px;" data-toggle="dropdown" role="button" >Contact<i class="far fa-user" style="padding-left: 10px"></i></a>
	<div class="dropdown-menu form-group dropdown-menu-2 font-13">
		<p class="p10 mb0 pb0">Request a call back</p>
		<ul class="p10">
			<li><a type="javascript:void(0);" class="color-white" onclick="reqCMB(1)"> New Booking</a></li>
            <li><a type="javascript:void(0);" class="color-white" onclick="reqCMB(2)"> Existing Booking</a></li>
			<?php 
				if ($isContactVendor === 0 || $isContactVendor > 0)
				{
			?>
			<li><a type="javascript:void(0);" class="color-white" onclick="reqCMB(4)"> Vendor Helpline</a></li>
			<?php 
				}
				if ($isContactVendor === 0 || $isContactVendor == null)
				{
			?>
			<li><a type="javascript:void(0);" class="color-white" onclick="reqCMB(3)"> Attach Your Taxi</a></li>
			<?php } ?>
			<li>Call us For:</li>
			<li class="mt5"><a href="tel:+919051877000" class="color-white"> +91 90518-77000</a></li>
		</ul>
	</div>

<script type="text/javascript">
	var login = new Login();
	
	$('#signinpopup').click(function () {
		signinPartial('refreshNavbar(data1)');
	});

	function updateLoginClose() {
		$href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
		jQuery.ajax({type: 'get', url: $href, "dataType": "json", success: function (data1)
			{
				if (data1.usr_mobile == "") {
					if (socailTypeLogin == "facebook") {
						socailTypeLogin = "";
						login.signinWithFB('<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>');
					} else {
						socailTypeLogin = "";
						login.signinWithGoogle('<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>');
					}
				} else {
					$('#userdiv').hide();
					$('#navbar_sign').html(data1.rNav);
					$('#hideLogin').hide();
					$('#hideDetails').removeClass('col-12 col-sm-7 col-md-7 float-right marginauto book-panel pb0');
					$('#hideDetails').addClass('col-12 col-sm-12 col-md-9 float-none marginauto book-panel pb0');
					login.fillUserform2(data1.userData);
					login.fillUserform13(data1.userData);
				}

			}
		});
	}
	$('.sinUpPopUp').click(function () {		
		$('.modal').modal('hide'); 		
		var href2 = "<?= Yii::app()->createUrl('users/signup?is_partial=1', ['callback' => 'refreshNavbar(data1)']) ?>";
        $.ajax({
            "url": href2,
			"data": {"desktheme":1},
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
				$('#bkCommonModel2').removeClass('fade');
				$('#bkCommonModel2').css("display" , "block");
                $('#bkCommonModelBody2').html(data);
				$('#bkCommonModel2').modal('show');
            }
        });
        return false;
			
    });
</script>

