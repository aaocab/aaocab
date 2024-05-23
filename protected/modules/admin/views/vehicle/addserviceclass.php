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
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vehicle-form',
			'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
					<?= $form->hiddenField($model, 'scc_id') ?>

                    <div class="text-danger" id="errordiv" style="display: none"></div>
                    <div class="col-xs-6">

                        <div class="row">
                            <div class="col-xs-12">
                                <label>Label</label>								
								<?php
								echo $form->textFieldGroup($model, 'scc_label', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Label'))));
								?>
                            </div>
                        </div>
						
						<div class="row">
							<div class="col-xs-12">
								<div class="col-xs-12 col-md-4">
									<?
									$isScccng			 = ($model->scc_is_cng == 1) ? true : false;
									?>
									<?= $form->checkboxListGroup($model, 'scc_is_cng', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Cng'), 'htmlOptions' => ['checked' => $isScccng]), 'inline' => true)) ?>
								</div>
								<div class="col-xs-12 col-md-6">
									<?
									$ispetrolordiesel	 = ($model->scc_is_petrol_diesel == 1) ? true : false;
									?>
									<?= $form->checkboxListGroup($model, 'scc_is_petrol_diesel', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Petrol or Diesel'), 'htmlOptions' => ['checked' => $ispetrolordiesel]), 'inline' => true)) ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
                                <label>Markup Type</label>
								<?php
								$markupYear			 = ['1' => 'Percentage', '2' => 'Amount'];
								$yearVal		     = Filter::getJSON($markupYear);
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'scc_markup_type',
									'val'			 => $model->scc_markup_type,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($yearVal)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Markup Type', 'id' => 'ServiceClass_scc_markup_type')
								));
								?>
                            </div>
						</div>
                    </div>
                    <div class="col-xs-6">

                        <div class="row">
                            <div class="col-xs-12">
                                <label>Model Year</label>
								<?php
								//$modelyear			 = ServiceClass::model()->getYearsList($model->scc_model_year);

//								$this->widget('booster.widgets.TbSelect2', array(
//									'model'			 => $model,
//									'attribute'		 => 'scc_model_year',
//									'val'			 => $model->scc_model_year,
//									'asDropDownList' => FALSE,
//									'options'		 => array('data' => new CJavaScriptExpression($model->scc_model_year)),
//									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select model year', 'id' => 'ServiceClass_ssc_model_year')
//								));
								
								?>
								<?= $form->textFieldGroup($model, 'scc_model_year', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Model Year')))); ?>
                            </div>
                        </div>
						<div class="row">							 
                            <div class="col-xs-12">
                                <label class="control-label" for="Vendor_vhc_vendor_id1">Description</label>								
								<?= $form->textAreaGroup($model, 'scc_desc', array('label' => '')) ?>
                            </div>                        
						</div>

						<div class="row">
							<div class="col-xs-12">
                                <label>Markup</label>
								 <?= $form->textFieldGroup($model, 'scc_markup', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Markup')))); ?>
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
	</div>
</div>
