<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<div class="content-boxed-widget login-box-container" id="contentpadding<?= $id ?>">
	<h4 class="text-center mb0">Login with</h4>
	<div class="social-log mt10 mb20 text-center">
		<div class="">
			<!--<a  href="javascript:void(0);" class="social-log-f" onclick="socailSigin('facebook')"><i class="fab fa-facebook-f"></i></a>-->
                    <a  href="javascript:void(0);" onclick="socailSigin('google')"><img src="/images/btn_google_signin_light_normal_web.png?v=0.1" alt="Login with Google"></a>
                    <a href="javascript:void(0);" data-menu="menu-list-modal<?= $id ?>"><img src="/images/email_gozo.png?v=0.1" alt=""></a>

		</div>
	</div>
</div>

<div id="menu-list-modal<?= $id ?>" data-selected="menu-components" data-width="300" data-height="325" class="menu-box menu-modal">
    <div class="menu-title"><a href="#" class="menu-hide mt15 n" id="menubox<?= $id ?>"><i class="fa fa-times"></i></a>
        <h1>Log In</h1>
    </div>
    <div class="menu-page">
		<div style="color:#B80606; text-align: center;" id="errmsg_login<?= $id ?>" class="hide"><span>You have entered an invalid email address or a password. Please enter correct details!</span></div>
        <div class="page-login page-login-full">
            <div class="page-login-field top-30">
                <i class="fa fa-user color-highlight"></i>
                <input type="text" placeholder="Username" name="username" id="username<?= $id ?>">
                <em>(required)</em>
            </div>
            <div class="page-login-field bottom-30">
                <i class="fa fa-lock color-highlight"></i>
                <input type="password" name="password" id="password<?= $id ?>" placeholder="Password">
                <em>(required)</em>
            </div>

            <a href="#" id="userloginmodal<?= $id ?>" class="button bg-highlight button-full button-rounded button-sm uppercase ultrabold shadow-small">LOGIN</a>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
		var idkey = '<?= $id ?>';
		$('#userloginmodal' + idkey).click(function () {
			var href = '<?= Yii::app()->createUrl('users/Signin') ?>';
			var username = $('#username' + idkey).val();
			var pass = $('#password' + idkey).val();
			jQuery.ajax({'type': 'GET', 'url': href, 'dataType': 'html',
				'data': {usr_email: username, usr_password: pass},
				success: function (data)
				{ 
					if (data != '') {					
						$('#menu-list-modal' + idkey).removeClass('menu-box-active');
						$('#menu-hider').removeClass('menu-hider-active');
						var data1 = JSON.parse(data)
						$(".clsUserId").val(data1.user_id);
						$(".loggiUser").html("Hi,&nbsp;"+data1.usr_name);									
						$jsLogin.fillUserform2(data1);					
						$jsLogin.fillUserform13(data1);					
						$("#errmsg_login" + idkey).addClass("hide");
						$('.login-box-container').css("display", "none");
					} else
					{
						$("#errmsg_login" + idkey).removeClass("hide");
					}

				}
			});
		});
		$('#menubox' + idkey).click(function () {
			$('#menu-list-modal' + idkey).removeClass('menu-box-active');
			$('#menu-hider').removeClass('menu-hider-active');
		});				
	});
    
</script>