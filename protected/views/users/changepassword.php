<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .a-pointer{
        cursor: pointer;
    }
</style>
        <div class="row">
            <div class="col-xs-8 col-sm-6 col-md-4 col-lg-4 book-panel2 float-none marginauto">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <?php
                        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
                        /* @var $form TbActiveForm */
                        ?>

                        <div class="row pb20">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="old_password">Current Password</label>
                                    <?= $form->passwordFieldGroup($model, 'old_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'required' => TRUE, 'placeholder' => "Current Password"]))) ?>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <?= $form->passwordFieldGroup($model, 'new_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'required' => TRUE, 'placeholder' => "New Password"]))) ?>
                                </div>
                                <div class="form-group">
                                    <label for="repeat_password">Confirm Password</label>
                                    <?= $form->passwordFieldGroup($model, 'repeat_password', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'required' => TRUE, 'placeholder' => "Confirm Password"]))) ?>
                                </div>
                                <div id="err" style="margin-bottom: 10px;color: #B80606" ><?= $message ?></div>
                                <div class="text-center pb10">
                                    <input class="btn next-btn text-uppercase"  type="submit" name="changepassword" value="Change Password"/>
                                </div>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
<script>
