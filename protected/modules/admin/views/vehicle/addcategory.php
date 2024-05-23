<style type="text/css">
    .control-label  {text-align: left!important;}
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0; padding-left: 0;}
    .selectize-input{ width:100%;}
</style>

<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12" >
        <div class="col-xs-12 mb20 flash_msg" style="color:#008a00;text-align: center;">
            <h4><?php echo Yii::app()->user->getFlash('success'); ?></h4>
        </div>
        <div class="col-xs-12 mb20 flash_msg" style="color:#F00;text-align: center">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>  
        <script>
            setTimeout(function () {
                $('.flash_msg').fadeOut();
            }, 3000);
        </script>
    </div>
</div>
<div class="row">
    <div class="col-lg-7 col-lg-offset-2 col-md-7 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 pb10 new-booking-list" >
        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'vehicle-form',
            'enableClientValidation' => TRUE,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error'
            ),
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'htmlOptions' => array(
                'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <?= $form->hiddenField($model, 'vct_id') ?>

                    <div class="text-danger" id="errordiv" style="display: none"></div>
                    <div class="col-xs-6">
                        <div class="row"> 
                            <div class="col-xs-12">
                                <label class="control-label" for="Vendor_vhc_vendor_id1">Category</label>
                                <?php
                                echo $form->textFieldGroup($model, 'vct_label', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Category'))));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Description</label>
                                <?= $form->textAreaGroup($model, 'vct_desc', array('label' => '')) ?>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-xs-6">
                        <div class="row"> 
                            <div class="col-xs-12">
                                <label class="control-label" for="Vendor_vhc_vendor_id1">Capacity</label>
                                <?php
                                echo $form->numberFieldGroup($model, 'vct_capacity', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Capacity'))));
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Image</label>
                                <?= $form->fileFieldGroup($model, 'vct_image', array('label' => '', 'widgetOptions' => array())); ?>
                                <div class="row ">
                                    <div class="col-xs-12 mb15">
                                        <?php
                                        if ($model->vct_image != '') {
                                            ?>
                                            <a href="<?= $model->vct_image ?>" target="_blank"><?= basename($model->vct_image); ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12">
                        <div class="row"> 
                            <div class="row" style="text-align: center">
                                <?php echo CHtml::submitButton('submit', array('class' => 'btn btn-primary')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-10 col-md-offset-0 col-sm-offset-1 col-xs-12 pb10 border border-radius" >
                <div class="row" id='vndlist'>
                </div>
            </div>
        </div>
