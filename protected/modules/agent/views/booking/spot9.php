<style>
	.btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg{ padding: 10px!important;}
</style> 
<div class="container mt50">
<!--    <div class="row">
        <div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
    </div>-->
    <div class="row spot-panel m0">
        <div class="col-xs-12 col-md-12  float-none marginauto">
            <?
            $form  = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                     => 'create-trip', 'enableClientValidation' => FALSE,
                'clientOptions'          => array(
                    'validateOnSubmit' => true,
                    'errorCssClass'    => 'has-error'
                ),
                'enableAjaxValidation'   => false,
                'errorMessageCssClass'   => 'help-block',
                'action'                 => Yii::app()->createUrl('agent/booking/spot'),
                'htmlOptions'            => array(
                    'class'   => 'form-horizontal', 'enctype' => 'multipart/form-data'
                ),
            ));
            /* @var $form TbActiveForm */
            $ccode = Countries::model()->getCodeList();
            echo $form->hiddenField($model, 'bkg_booking_type');
            ?>
            <input type="hidden" name="step" value="9">
            <?= $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]); ?> 
            <h3 class="mb30">Traveller Info</h3>
            <div class="form-group">
                <label class="col-sm-2" for="inputEmail3">Full Name</label>
                <div class="col-xs-12 col-sm-4 mr5">
                    <?= $form->textFieldGroup($bookingUser, "bkg_user_fname", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => "form-control", 'placeholder' => "Enter First Name"]))) ?>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <?= $form->textFieldGroup($bookingUser, "bkg_user_lname", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => "form-control", 'placeholder' => "Enter Last Name"]))) ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2" for="inputPassword3">Mobile Phone</label>
                <div class="col-xs-2 isd-input mr5">
                    <?php
                    echo $form->dropDownListGroup($bookingUser, 'bkg_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
                    ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->textFieldGroup($bookingUser, "bkg_contact_no", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => "form-control", 'placeholder' => "Enter Mobile Number"]))) ?>
                </div>
                <span style="font-size: 12px;margin-left: 5px">Gozo will send OTP to verify phone</span>
            </div>
            <div class="form-group">
                <label class="col-sm-2" for="inputPassword3">Email</label>
                <div class="col-sm-4">
                    <?= $form->textFieldGroup($bookingUser, "bkg_user_email", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => "form-control", 'placeholder' => "Enter Email Address"]))) ?>         
                </div>
                <span style="font-size: 12px;margin-left: 5px">Gozo will send verification email</span>
            </div>
            <div class="col-xs-12 mt30">
                   <button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step9ToStep8"><b> <i class="fa fa-arrow-left"></i> Previous</b></button><button type="submit" class="pull-right btn btn-primary btn-lg pl50 pr50 pt30 pb30" name="step9submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>