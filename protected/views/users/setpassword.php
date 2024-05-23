<div class="row">
    <div class="col-md-5 col-sm-6 col-xs-12" style="float: none;margin: auto">
        <div class="panel panel-default">
            <div class="panel-heading"><h2>Please enter your full name and set your password to activate your account.</h2></div>
            <div class="panel-body" >
                <div class="row">         

                    <?= CHtml::beginForm("", "post", ['id' => "formId"]); ?>
                    <div class="form-group">
                        <?= CHtml::textField("users[username]", $username, [ 'id' => "unm", 'class' => "form-control", 'placeholder' => "Full name"]) ?>
                        <?= CHtml::hiddenField('id', $id, []) ?>
                    </div>
                    <div class="form-group">
                        <?= CHtml::passwordField("users[password]", '', [ 'id' => "npswd", 'class' => "form-control", 'placeholder' => "New password", 'class' => "form-control"]) ?>

                    </div>
                    <div class="form-group">
                        <?= CHtml::passwordField("users[cpassword]", '', [ 'id' => "cpswd", 'class' => "form-control", 'placeholder' => "Confirm Password", 'class' => "form-control"]) ?>

                    </div>
                    <div class="form-group">
                        <div class="col-md-12"  style="text-align: center">
                            <div  id="err" style="margin-bottom: 10px;color: #B80606" ><?= $status ?></div>        
                            <?= CHtml::submitButton('Submit', ['name' => "sub", 'class' => "btn btn-info", 'onclick' => "return validateCheckHandler2()"]) ?>

                        </div>
                    </div>
                    <?= CHtml::endForm() ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateCheckHandler2()
    {
        if ($('#npswd').val() == "" || $('#cpswd').val() == "")
        {
            $('#err').html("All fields are mandatory.");
            return false;
        }
        else
        {
            if ($('#npswd').val() == $('#cpswd').val())
            {
                return true;
            }
            else
            {
                $('#err').html("The new passwords you have entered don't match. Please enter again.");
                return false;
            }
        }
    }


</script>