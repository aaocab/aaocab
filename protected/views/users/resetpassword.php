<div class="col-xs-12 col-sm-6 float-none marginauto mt20 ">
    <hr>
    <div class="panel panel-white panel-border" style="border:1px solid #a9a0a0">
        <div class="panel-heading mb0 pb0" style="text-align: center"><h3><u>Reset Your Password</u></h3></div>
        <div class="panel-body ">
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
                <div class="col-xs-12 col-sm-10 col-md-7 float-none marginauto">
                    <?= CHtml::beginForm("", "post", ['id' => "formId"]); ?>
                    <h5 style="color: #0000aa">Dear <?= $username ?>,</h5>
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

                    </div>
                    <?= CHtml::endForm() ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
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