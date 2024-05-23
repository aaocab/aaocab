<style>
.help-block{
	display:block;
    width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
}
</style>
<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="container mt30">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="bg-white-box">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'cpass-form', 'enableClientValidation' => true,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                                'errorCssClass' => 'has-error'
                            ),
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // See class documentation of CActiveForm for details on this,
                            // you need to use the performAjaxValidation()-method described there.
                            'enableAjaxValidation' => false,
                            'errorMessageCssClass' => 'help-block',
                            'htmlOptions' => array(
                                'class' => 'form-horizontal',
                            ),
                        ));
                        if ($status == 'no') {
                            $form->addError($model->old_password, 'The token must contain letters or digits.');
                        }
                        /* @var $form CActiveForm */
                        ?>

                        <div class="row pb20">
                            <div class="col-12 font-18 mb15"><b>Change Password</b></div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="old_password">Current Password</label>
                                    <?= $form->passwordField($model, 'old_password',[ 'required' => TRUE, 'placeholder' => "Current Password",'class'=>"form-control"]) ?>
									<?php echo $form->error($model, 'old_password', ['class' => 'help-block error']); ?>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <?= $form->passwordField($model, 'new_password',[ 'required' => TRUE, 'placeholder' => "New Password",'class'=>"form-control"]) ?>
									<?php echo $form->error($model, 'new_password', ['class' => 'help-block error']); ?>
                                </div>
                                <div class="form-group">
                                    <label for="repeat_password">Confirm Password</label>
                                    <?= $form->passwordField($model, 'repeat_password', [ 'required' => TRUE, 'placeholder' => "Confirm Password",'class'=>"form-control"]) ?>
									<?php echo $form->error($model, 'repeat_password', ['class' => 'help-block error']); ?>
                                </div>
                                <div id="err" style="margin-bottom: 10px;color: #B80606" ><?= $message ?></div>
                                <div class="text-center pb10">
                                    <input class="btn text-uppercase gradient-green-blue font-14 pt10 pb10 pl30 pr30 border-none mt15"  type="submit" name="changepassword" value="Change Password"/>
                                </div>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                  
                </div>
            </div>
            <div class="col-lg-8 text-right offset-lg-2 mt90">
                <img src="/images/change_img.png" alt="" class="img-fluid">
            </div>
            </div>
        </div>
<script>
