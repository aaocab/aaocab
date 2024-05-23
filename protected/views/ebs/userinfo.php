<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-md-offset-2">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id' => 'payment-form', 'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'errorCssClass' => 'has-error',
					'afterValidate' => 'js:function(form,data,hasError){
                    if(!hasError){
                        return true
                    }
                    }'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation' => false,
				'errorMessageCssClass' => 'help-block',
				'action' => Yii::app()->createUrl('ebs/userinfo'),
				'htmlOptions' => array(
					'noValidate' => 'novalidate',
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			?>
			<div class="panel">            
				<div class="panel-body">  
					<div class="h3 ">
						Payment Details

					</div>
					<div class="h5 ">
						Please enter all the informations

					</div>
					<?= $form->hiddenField($model, "trans_code"); ?>
					<?= $form->hiddenField($model, "trans_bkhash"); ?>
					<?= $form->hiddenField($model, "ebsopt",['value'=>$ebsopt]); ?>
                    <div class="row">
						<div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'ebs_name', array('label' => 'Name', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Name", 'value' => $bkmodel->getUsername()]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
						<div class="col-xs-12 col-sm-6 mt-sm mt30"><b>Amount : </b>
							<?= $model->trans_amount ?> (INR)
						</div>
					</div>
					<div class="row">
						
						<div class="col-xs-12 col-sm-6">
							<?= $form->textAreaGroup($model, 'ebs_address', array('label' => 'Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Address", 'value' => $bkmodel->bkg_pickup_address]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
					</div>
					<div class="row">

						<div class="col-xs-12 col-sm-6">
							<label class="control-label">Country </label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model' => $model,
								'attribute' => 'ebs_country',
								'useWithBootstrap' => true,
								"placeholder" => "Country",
								'fullWidth' => false,
								'htmlOptions' => array(
								),
								'defaultOptions' => array(
									'create' => false,
									'persist' => true,
									'selectOnTab' => true,
									'createOnBlur' => true,
									'dropdownParent' => 'body',
									'optgroupValueField' => 'pcode',
									'optgroupLabelField' => 'pcode',
									'optgroupField' => 'pcode',
									'openOnFocus' => true,
									'labelField' => 'pcode',
									'valueField' => 'pcode',
									'searchField' => 'name',
									//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
									'closeAfterSelect' => true,
									'addPrecedence' => false,
									'onInitialize' => "js:function(){
                                this.load(function(callback){
                                var obj=this;                                
                                xhr=$.ajax({
                                    url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/countryname')) . "',
                                    dataType:'json',        
                                    cache: true,
                                    success:function(results){
                                        obj.enable();
                                        callback(results.data);
                                        obj.setValue('{$model->ebs_country}');
                                    },                    
                                    error:function(){
                                        callback();
                                    }});
                                });
                            }",
									'render' => "js:{
                            option: function(item, escape){  
                            return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            $('#countrycode').val(data.pcode);
                            return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
                            }
                            }",
								),
							));
							?>


						</div>
						<div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'ebs_state', array('label' => 'State', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "State", 'value' => $bkmodel->bkgFromCity->ctyState->stt_name]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'ebs_city', array('label' => 'City', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "City", 'value' => $bkmodel->bkgFromCity->cty_name]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
						<div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'ebs_postal_code', array('label' => 'Postal Code', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Postal Code"]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'ebs_phone', array('label' => 'Contact Number', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Contact Number", 'value' => $bkmodel->bkg_contact_no]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
						<div class="col-xs-12 col-sm-6">
							<?= $form->emailFieldGroup($model, 'ebs_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'value' => $bkmodel->bkg_user_email]), 'groupOptions' => ['class' => 'm0'])) ?>                      
						</div>
					</div>
					<div class="col-xs-12 newButtonLine text-center mt20">
						<input type="submit" value="Proceed" class="btn btn-primary">
					</div>
				</div>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>