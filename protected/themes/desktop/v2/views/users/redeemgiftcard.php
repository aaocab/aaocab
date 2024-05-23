
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'view-form', 'enableClientValidation' => true,
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
/* @var $form CActiveForm */
?>
<div class="bg-white-box mb30">
    <div class="row">
        <div class="col-12 col-lg-6 mt50">
            <label>Enter 16 digit Gift Card Code</label>
            <input type="text" name="gcc1" id="gcc1" required="true" maxlength="16" class="form-control">
            <div class="help-block error" id="gccl_error" style="display:none;text-align:center;"></div>
            <div class="mt10"><button type="submit" id="btnRedeem" class="btn btn-primary text-uppercase gradient-green-blue border-none" name="btnRedeem">Redeem</button></div>
        </div>
        <div class="col-12 col-lg-4 offset-lg-1">
            <img src="/images/gift_img.png" alt="Gift Card" class="img-fluid">
        </div>
        <?php $this->endWidget(); ?>
        <div class="col-12 text-center mt30">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="bg-success p10 text-white" style="font-size: 18px;">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="bg-danger p10 text-white" style="font-size: 18px;">
                    <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#btnRedeem').click(function ()
    {
        var redeemval = $('#gcc1').val();
        var countRedeemStr = redeemval.length;
        if (countRedeemStr < '16')
        {
            $('#gccl_error').html('Please enter 16 digit valid code');
            $('#gccl_error').css('display', 'block');
            $('#gccl_error').css('color', 'red');
            return false;
        }
    });
</script>
