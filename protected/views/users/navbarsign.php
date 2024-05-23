<style type="text/css">
    .new-login-box .help-block{
        line-height: 1.2em;
        margin-bottom: 0;
    } 
    .login-panel ul{
        margin-bottom: 2px;
    }
    .login-panel  .forgot_fst a{
        padding: 6px 0!important;
        margin-top: 5px;	
        margin-bottom: 5px;	
        padding-top: 0;
        background-color: #407BF1;
        color: #fff!important;
        font-size: 11px;
    }
    @media (min-width: 1200px) {
        .stop-menu .login-panel li a {
            width: 38% !important;
        }
    }
    @media (min-width: 1023px) and (max-width: 1199px) {
        .stop-menu .modal-lg {
            width: 80% !important;
        }
    }
    @media (min-width: 1022px){
        .stop-menu .modal-lg {
            width: 90%!important;
        }
    }
    @media (min-width: 768px){
        .stop-menu .modal-lg {
            width: 80%;
        }
    }
</style>


<?php

if (Yii::app()->user->isGuest)
{
    $model = new Users('login'); 
	if($_SERVER['REQUEST_URI']!='/win1day')
	{
    ?>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" 
       aria-haspopup="true" aria-expanded="false" id="signinpopup">Sign in</a>
    <?php
	}
}
else
{
    $uname = Yii::app()->user->loadUser()->usr_name;
    ?>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Hello <?= $uname ?><i class="fa fa-user" style="padding-left: 10px"></i><span class="caret"></span></a>
    <div class="dropdown-menu form-group dropdown-list pl20 pr15 pb0 "  >
        <ul class="p0">
            <li><a href="<?= Yii::app()->createUrl('users/view') ?>"><i class="fa fa-user pr10"></i>My Profile</a></li>
            <li><a href="<?= Yii::app()->createUrl('booking/list') ?>"><i class="fa fa-list pr10"></i>Booking list</a></li>
            <li><a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr><i class="fa fa-pencil pr10"></i>Change Password</nobr></a></li>
            <li><a href="<?= Yii::app()->createUrl('users/logout') ?>"><i class="fa fa-sign-out pr10"></i>Log Out</a></li>
        </ul>
    </div>
    <?
}
?>
<script type="text/javascript">
    $('#signinpopup').click(function () {
        var href2 = "<?= Yii::app()->createUrl('users/partialsignin', ['callback' => 'refreshNavbar(data1)']) ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    size: 'large',
                    onEscape: function () {
                        updateLoginClose();
                    },
                });
            }
        });
        return false;
    });
	
	  function updateLoginClose()  {
	    $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href, "dataType": "json",success: function (data1)
            {
				if(data1.usr_mobile==""){
					if(socailTypeLogin=="facebook"){
						socailTypeLogin="";
						signinWithFB();
					}
					else{
						socailTypeLogin="";
						signinWithGoogle();
					}
				}
				else{
					$('#userdiv').hide();
					$('#navbar_sign').html(data1.rNav);
					$('#hideLogin').hide();
					if($("#hideDetails").hasClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0"))
					{
						$("#hideDetails").removeClass("col-xs-12 col-sm-6 col-md-6 marginauto book-panel pb0");
						$("#hideDetails").addClass("col-xs-12 col-sm-9 col-md-7 book-panel pb0");
					}
				    fillUserform2(data1.userData);
					fillUserform13(data1.userData);
				}
               
            }
        });
    }
</script>

