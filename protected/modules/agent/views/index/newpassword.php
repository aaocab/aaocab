
<div id="pass-form">
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'newpassword', 'enableClientValidation' => true,
        'action' => Yii::app()->createUrl('agent/index/newpassword', []),
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
            'class' => 'form-horizontal'//, 'enctype' => 'multipart/form-data'
        ),
    ));
    ?>
    <input type="hidden" value="<?= $agt_id ?>" id="agtid" name="agtid" >
    <div class="form-group">
        <span class="form-title"><?= $message ?></span>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">New Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="New Password" name="newPassword" id="newPassword" />
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Repeat Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Repeat Password" name="repeatPassword" id="repeatPassword" />
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary uppercase pull-right">Submit</button>
    </div>
    <?php $this->endWidget(); ?>
    <!-- END FORGOT PASSWORD FORM -->
</div>