<style type="text/css">
    .btnSubmit{
        width:150px;text-transform: uppercase;padding:10px;margin-top:20px;
    }
    #boost-edit-form .form-group.has-error .form-control {
        width:97%!important;
    }
    .hide{
        display :block;
    }
     .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>

<div class="row">
    <div class="col-xs-8" style="float: none; margin: auto;">
    <?php echo CHtml::errorSummary($model); ?>
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'payment-form', 'enableClientValidation' => TRUE,
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
    /* @var $form TbActiveForm */
    ?>
	<?php
		echo $form->hiddenField($model, 'bkg_id');
	?>
    <div class="col-xs-12 col-md-12">
        <div class="panel panel-default panel-border">
            <div class="panel-body">
                <div class="row mb10 mt10">
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($drvStatModel, 'drv_last_loc_lat', array('label' => 'Driver Latitude', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver Lattitude')))) ?>
							
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($drvStatModel, 'drv_last_loc_long', array('label' => 'Driver Longitude', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver Longitude')))) ?>
						
                        </div>
                    </div>
                </div>

               <div class="row mb10 mt10">
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_km', array('label' => 'Extra KM', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra KM')))) ?>
						</div>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_km_charge', array('label' => 'Extra Charge', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra Charge')))) ?>
						</div>
                    </div>
                </div>

				<div class="row mb10 mt10">
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_min', array('label' => 'Extra Min', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra Min')))) ?>
						</div>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_extra_per_min_charge', array('label' => 'Extra per min charge', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Extra per min charge')))) ?>
						</div>
                    </div>
                </div>

				<div class="row mb10 mt10">
                    <div class="col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_total_amount', array('label' => 'Total Amount', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Total Amount')))) ?>
						</div>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->textFieldGroup($model->bkgInvoice, 'bkg_advance_amount', array('label' => 'Paid Online', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Paid Online')))) ?>
						</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 text-center panel-footer">
                <input type="submit" value="Submit" name="yt0" id="paymentSubmit" class="btn btn-primary pl30 pr30 btnSubmit">
            </div>
        </div>
    </div>
    <?php $this->endWidget();?>
   </div>
</div>
   