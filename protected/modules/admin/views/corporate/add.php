<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$isNew	 = ($model->isNewRecord) ? 'ADD' : 'EDIT';
?>
<style>
    .help-block{
        color: #F00 !important;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 85% !important;
    }
</style>
<div class="row">
    <div class="col-lg-6 col-md-8 col-sm-10 pb10" style="float: none; margin: auto">
		<?php
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'corporate-add-form', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
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
                    <div class="col-sm-5 col-xs-12 mr10">
						<?= $form->textFieldGroup($model, 'crp_fname', array()) ?>
                    </div>
                    <div class="col-sm-5 col-xs-12">
						<?= $form->textFieldGroup($model, 'crp_lname', array()) ?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-sm-5 col-xs-12 mr10">
						<?= $form->textFieldGroup($model, 'crp_company', array()) ?>
                    </div>
                    <div class="col-sm-5 col-xs-12">
						<?= $form->textFieldGroup($model, 'crp_owner', array()) ?>
                    </div>
                </div>
                <div class="row">
					<div class="col-sm-5 col-xs-12">
						<?= $form->textFieldGroup($model, 'crp_code', array()) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-xs-12 p0">
                        <Label>Country Code *</Label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'crp_country_code',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Code",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '50%'
							),
							'defaultOptions'	 => array(
								'create'			 => false,
								'persist'			 => true,
								'selectOnTab'		 => true,
								'createOnBlur'		 => true,
								'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id',
								'optgroupLabelField' => 'pcode',
								'optgroupField'		 => 'pcode',
								'openOnFocus'		 => true,
								'labelField'		 => 'pcode',
								'valueField'		 => 'pcode',
								'searchField'		 => 'name',
								//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
								'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,
								'onInitialize'		 => "js:function(){
                            this.load(function(callback){
                            var obj=this;                                
                             xhr=$.ajax({
                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                     dataType:'json',                  
                     success:function(results){
                         obj.enable();
                         callback(results.data);
                         obj.setValue('{$model->crp_country_code}');
                     },                    
                     error:function(){
                         callback();
                     }});
                    });
                    }",
								'render'			 => "js:{
                         option: function(item, escape){                      
                                 return '<div><span class=\"\">' + escape(item.name) +'</span></div>';                          
                         },
			 option_create: function(data, escape){
                              return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                        }
		    }",
							),
						));
						?>
                        <span style="color: #F00"> <?= $form->error($model, 'crp_country_code') ?></span>
                    </div>
                    <div class="col-sm-6 col-xs-12 p0">
						<?= $form->textFieldGroup($model, 'crp_contact', array()) ?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-xs-10">
						<?= $form->textFieldGroup($model, 'crp_email', array()) ?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-xs-10">
						<?= $form->textAreaGroup($model, 'crp_address', array()) ?>
                    </div>
                </div>
				<div class="row"> 
                    <div class="col-sm-5 col-xs-12 mr10">
						<?= $form->dropDownListGroup($model, 'crp_discount_type', ['widgetOptions' => ['data' => [1 => 'amount', 2 => 'percentage'], 'htmlOptions' => []]]) ?>
                    </div>
                    <div class="col-sm-5 col-xs-12">
						<?= $form->numberFieldGroup($model, 'crp_discount_amount', ['widgetOptions' => ['htmlOptions' => ['MIN' => 0]]]) ?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-sm-5 col-xs-12 mr10">
						<?= $form->textFieldGroup($model, 'crp_bank_name', array()) ?>
                    </div>
                    <div class="col-sm-5 col-xs-12">
						<?= $form->textFieldGroup($model, 'crp_bank_branch', array()) ?>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col-sm-5 col-xs-12 mr10">
						<?= $form->textFieldGroup($model, 'crp_bank_ifsc', array()) ?>
                    </div>
                    <div class="col-sm-5 col-xs-12">
						<?= $form->textFieldGroup($model, 'crp_bank_account_no', array()) ?>
                    </div>
                </div>    
                <div class="row"> 
                    <div class="col-sm-5 col-xs-12 mr10">
						<?= $form->radioButtonListGroup($model, 'crp_agreement', array('widgetOptions' => array('data' => [1 => 'Yes', 2 => 'No']), 'inline' => true)); ?>  
                    </div>
                    <div class="col-sm-5 col-xs-12">   
						<?
						($model->crp_agreement_date != '') ? date('d/m/Y', strtotime($model->crp_agreement_date)) : '';
						?>

						<?= $form->datePickerGroup($model, 'crp_agreement_date', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array()), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-xs-10">
						<?= $form->fileFieldGroup($model, 'crp_agreement_file', array()) ?>
						<?
						if ($model->crp_agreement_file != '')
						{
							?>
							<a href="<?= $model->crp_agreement_file ?>" target="_blank"><?= basename($model->crp_agreement_file) ?></a>
							<?
						}
						?>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-xs-10">
						<?= $form->fileFieldGroup($model, 'crp_id_proof', array()) ?>
						<?
						if ($model->crp_id_proof != '')
						{
							?>
							<a href="<?= $model->crp_id_proof ?>" target="_blank"><?= basename($model->crp_id_proof) ?></a>
							<?
						}
						?>
                    </div></div>
            </div>
            <div class="panel-footer" style="text-align: center">
				<?php echo CHtml::submitButton($isNew, array('class' => 'btn  btn-primary')); ?>
            </div>   
        </div>
		<?php $this->endWidget(); ?>
    </div>  
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#<?= CHtml::activeId($model, 'crp_contact') ?>').mask('9999999999');
    });
</script>