<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
	.social-txt{
		color:#f77026;
		width:100%;
		text-align: center;
	}
	.social-success{
		color:green;
		width:100%;
		text-align: center;
	}
	.social-error{
		color:red;
		width:100%;
		text-align: center;
	}

</style>
<input type="hidden" id="vndHash" value="<?= $hash?>">
<input type="hidden" id="vndId" value="<?= $id?>">
<div class="row flex">
	<h1 class="social-txt hideLogin">Link your Gozo Partner account to your Social account</h1>
    <div class="col-xs-8 col-sm-8 col-md-8 book-panel2 padding_zero hideLogin" style="margin:auto;">
        <div class="panel panel-primary">
            <div class="panel-body">


                <div class="col-xs-12 col-sm-12 col-md-12 float-left marginauto book-panel pb0">
					<div class="panel panel-default border-radius box-shadow1">
						<div class="panel-body p20">
							<div class="col-xs-12 col-md-8 col-md-offset-2 fbook-btn mb10">
								<a class="btn btn-lg btn-social btn-facebook pl15 pr15" onclick="socailSigin('facebook')" ><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Login with Facebook</a>
							</div>

							<div class="col-xs-12 col-md-8 col-md-offset-2 google-btn">
								<a class="btn btn-lg btn-social btn-googleplus pl15 pr15"  onclick="socailSigin('google')" ><img src="/images/google_icon.png"> Login with Google</a>
							</div>
						</div>
					</div>
				</div>



            </div>

        </div>
    </div>
	<h1 class="social-success hide">Your Social account linked successfully with Gozo partner.</h1>
	<h1 class="social-error hide">Sorry!! We had an error linking your social account.</h1>

</div>

<script type="text/javascript">
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }

    function socailSigin(socailSigin)
    {
        socailTypeLogin = socailSigin;
        var href2 = "<?= Yii::app()->createUrl('users/partialsignin') ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                if (data.search("You are already logged in") == -1) {
                    if (socailSigin == "facebook") {
                        signinWithFB();
                    } else {
                        signinWithGoogle();
                    }

                } else {
                    var box = bootbox.dialog({message: data, size: 'large',
                        onEscape: function () {
                            updateLogin();
                        }
                    });
                }
            }
        });
        return false;
    }

    function signinWithFB() {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
        var fbWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

    }
    function signinWithGoogle() {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
        var googleWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

    }
    function updateLogin() {
        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href, "dataType": "json", success: function (data1)
            {
                if (data1.userData.usr_email == "") {
                    if (socailTypeLogin == "facebook") {
                        socailTypeLogin = "";
                        signinWithFB();
                    } else {
                        socailTypeLogin = "";
                        signinWithGoogle();
                    }
                } else {
                   // updateVendor(data1.userData.usr_email)
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

    function updateVendor(email)
    {
		var hash = $('#vndHash').val();
		var vndId = $('#vndId').val();
        $href = '<?= Yii::app()->createUrl('users/linkVendor') ?>';
        jQuery.ajax({type: 'get', url: $href, "dataType": "json", "data": {"email": email, 'hash': hash,'id':vndId}, success: function (data1)
            {
                if (data1.success)
                {
                    $('.social-success').removeClass('hide');
                } else
                {
                    if(data1.isexist)
                    {
                        $('.social-error').text('This user already linked with an another vendor');
                    }
                   $('.social-error').removeClass('hide');
                }
            }
        });
    }
</script>
