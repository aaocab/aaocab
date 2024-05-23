<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<?php
if ($link)
{
	?>
	<div id="dialog" title="Verification Link">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<div class="h4 text-center pt10">
					<strong>Your verification Link has been expired </strong>
				</div>
			</div>
		</div>
	</div>
<?php
}
else
{
?>
<div class="col-12 col-md-6 offset-md-3 mt30 mb30 bg-white-box">
    
        <div class="card-heading text-center"><h3 class="font-22">Resets Your Password</h3></div>
        
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
            <div class="row">
                <div class="col-12">
                    <?= CHtml::beginForm("", "post", ['id' => "formId"]); ?>
                    <h5 class="color-green">Dear <?= $username ?>,</h5>    
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
                        <?= CHtml::submitButton('Reset Password', ['name' => "signup", 'class' => "btn-lg btn-block btn text-uppercase gradient-green-blue border-none mt15", 'onclick' => "return validateCheckHandlerss()", 'style' => "height:50px"]) ?>
                    </div>
                    <?= CHtml::endForm() ?>
                </div>
            </div>
</div>
<?php
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        var availableTags = [];
        var front_end_height = $(window).height();
        var footer_height = $(".footer").height();
        var header_height = $(".header").height();
        $(".searchpanel").css({
            "min-height": ((front_end_height - footer_height) - header_height - 11) + "px"
        });
       
        $(".popover-bottom").popover({
            placement: 'bottom'
        });


        $("#myRating").mouseover(
                function (e) {
                    e.stopPropagation();
                    $(".myrates").fadeIn();
                }
        );

        $(".myrates").mouseover(
                function (e) {
                    e.stopPropagation();
                    $(".myrates").show();
                }
        );

        $(document).mouseover(function () {
            $(".userPopup").fadeOut();
            $(".myrates").fadeOut();
        });
        $("#profileDown").mouseover(function (e) {
            e.stopPropagation();
            $(".userPopup").fadeIn();
        });
        $(".userPopup").mouseover(function (e) {
            e.stopPropagation();
            $(".userPopup").show();
        });
    });

    function validateCheckHandlerss() {
        if ($('#txtuserPass').val() == "" && $('#cpassword').val() == "")
        {
            $('#errId').html("All fields are mandatory.");
            return false;
        } else
        {
            if ($('#txtuserPass').val() != $('#cpassword').val())
            {
                $('#errId').html("The new passwords you have entered don't match. Please enter again.");
                return false;
            } else
            {
                return true;
            }
        }


    }

</script>