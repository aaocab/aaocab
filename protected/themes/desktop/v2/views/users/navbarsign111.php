<?
if (Yii::app()->user->isGuest)
{
    $model = new Users('login');
    if ($_SERVER['REQUEST_URI'] != '/win1day')
    {
	?>
	<a href="#" class="dropdown-toggle pr0 gradient-green-blue btn-sign" data-toggle="dropdown" role="button" 
	   aria-haspopup="true" aria-expanded="false" id="signinpopup">Sign in</a>
	   <?
       }
   }
   else
   {
       $uname = Yii::app()->user->loadUser()->usr_name;
       ?>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Hello <?= $uname ?><i class="far fa-user" style="padding-left: 10px"></i></a>
    <div class="dropdown-menu form-group dropdown-menu-2">
        <ul class="p0">
    	<li><a href="<?= Yii::app()->createUrl('users/view') ?>"><i class="fa fa-user"></i>My Profile</a></li>
    	<li><a href="<?= Yii::app()->createUrl('booking/list') ?>"><i class="fa fa-list"></i>Booking list</a></li>
    	<li><a href="<?= Yii::app()->createUrl('index/index'); ?>"><i class="fa fa-car"></i>New Booking</a></li>
    	<li><a href="<?= Yii::app()->createUrl('booking/list'); ?>"><i class="fas fa-history"></i>Booking History</a></li>
    	<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>"><i class="fa fa-users"></i>Refer friends</a></li>
    	<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><i class="fas fa-coins"></i>Gozo Coins</a></li>
    	<li><a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr><i class="fas fa-lock"></i>Change Password</nobr></a></li>
    	<li><a href="<?= Yii::app()->createUrl('users/logout') ?>"><i class="fa fa-sign-out"></i>Log Out</a></li>
        </ul>
    </div>
    <?
}
?>
<script type="text/javascript">
    var login = new Login();
    $('#signinpopup').click(function () {
        var href2 = "<?= Yii::app()->createUrl('users/partialsignin', ['callback' => 'refreshNavbar(data1)']) ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                $('#bkCommonModelHeader').parent().addClass('hide');
                $('#bkCommonModelBody').html(data);
                $('#bkCommonModel').modal('show');
            }
        });
        return false;
    });

    function updateLoginClose() {
        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href, "dataType": "json", success: function (data1)
            {
                if (data1.usr_mobile == "") {
                    if (socailTypeLogin == "facebook") {
                        socailTypeLogin = "";
                        login.signinWithFB('<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>');
                    }
                    else {
                        socailTypeLogin = "";
                        login.signinWithGoogle('<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>');
                    }
                } else {
                    $('#userdiv').hide();
                    $('#navbar_sign').html(data1.rNav);
                    $('#hideLogin').hide();
                    $('#hideDetails').removeClass('col-xs-12 col-sm-7 col-md-7 float-right marginauto book-panel pb0');
                    $('#hideDetails').addClass('col-xs-12 col-sm-12 col-md-9 float-none marginauto book-panel pb0');
                    login.fillUserform2(data1.userData);
                    login.fillUserform13(data1.userData);
                }

            }
        });
    }
</script>

