<?php 
if ($redirectBy == 'page')
{
    $version = Yii::app()->params['siteJSVersion'];
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/userLogin.js?v=' . $version);
}
?>
<div class="p20 changePassword">
        <p class="merriw font-20 weight500 mb20">Reset Your Password </p>
            <div class="row">
                <?
                if ($status == 'error') {
                    echo "<span style='color:#ff0000;'>Password didn't match.</span>";
                } elseif ($status == "inv") {
                    echo "<span style='color:#ff0000;'>Invalid Link</span>";
                } else {
                    
                }
                ?>
            </div>
            <div class="row ">
                <div class="col-12">
                    <?= CHtml::beginForm("", "post", ['id' => "formId"]); ?>
                    <p class="font-16 mb5 ">Dear <span class="username"><?= $username ?></span>,</p>
                    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id ?>">      
                    <label class="mb10">Please enter new password</label>
                    <div class="form-group">
                        <?= CHtml::passwordField("txtuserPass", '', [ 'id' => "txtuserPass", 'class' => "form-control", 'placeholder' => "New password", 'validation' => "blank|Please enter your password", 'style' => "height:50px"]) ?>

                        <div e_rel="txtuserPass"></div>
                    </div>
                    <div class="form-group">
                        <?= CHtml::passwordField("cpassword", '', [ 'id' => "cpassword", 'class' => "form-control", 'placeholder' => "Confirm password", 'validation' => "blank|Please confirm your password", 'style' => "height:50px"]) ?>

                        <div e_rel="cpassword"></div>
                    </div>
                    <div class="form-group">                                
                        <div id="errId" style="color: #B80606"></div>
                    </div>  
                    <div class="form-group" style="text-align: center">  
                        <?= CHtml::submitButton('Reset Password', ['name' => "signup", 'class' => "btn btn-lg btn-primary btn-block", 'onclick' => "return validateCheckHandlerss()", 'style' => "height:50px"]) ?>
                        <input type="hidden" id="key" name="key" value="<?=$_REQUEST['key']?>">
                        <input type="hidden" id="uid" name="uid" value="<?=$_REQUEST['uid']?>">
                    </div>
                    <?= CHtml::endForm() ?>
                </div>
            </div>
     
    <hr>
</div>

   <div class=" col-12 mt-3 mb-3 text-center  accepted hide"><img src="/images/img-2022/check.svg" width="150" alt="">
		<h2 class="merriw weight600 mt-1 font-20">Change password successfully</h2>
		<p class="mb10">Redirecting in <span id ="timer">5</span> second</p>
		<div class="btn btn-success text-center" onclick="closeBox()">OK</div></div>
<script type="text/javascript">
    $(document).ready(function () {
    
       
        
    });
        function closeBox()
        {
            var type = '<?php echo $redirectBy; ?>';
            if(type!="")
            {
        location.href = '<?= Yii::app()->getBaseUrl(true) ?>';        
            }else{
        bootbox.hideAll();
           }
            

        }
    function validateCheckHandlerss() {
       
        if ($('#txtuserPass').val() == "" && $('#cpassword').val() == "")
        {
            $('#errId').html("All fields are mandatory.");
            return false;
        } else
        {
             var pass1 = $('#txtuserPass').val();
              var cpass1 = $('#cpassword').val();
             
            if(pass1.length < 8 && cpass1.length < 8)
            {
                  $('#errId').html("Password length must be greater than 7 characters.");
                return false;
            }
            if ($('#txtuserPass').val() != $('#cpassword').val())
            {
                $('#errId').html("The new passwords you have entered don't match. Please enter again.");
                return false;
            } else
            {
                $('#formId').submit(function(event) {
	            event.preventDefault();
                 $jsUserLogin = new userLogin();
                 $jsUserLogin.setPassword();
                });
               
              
               // return true;
            }
        }


    }
  
var initial = 5000;
	var count = initial;
	var counter; //10 will  run it every 100th of a second
	var initialMillis;
	function timer()
	{
		if (count <= 0)
		{
			clearInterval(counter);
			return;
		}
		var current = Date.now();
		count = count - (current - initialMillis);
		initialMillis = current;
		displayCount(count);
	}

	function displayCount(count)
	{
		var res = count / 1000;
		if (document.getElementById("timer") !== null)
		{
			document.getElementById("timer").innerHTML = res.toPrecision(1);
		}
	}


	displayCount(initial);

</script>