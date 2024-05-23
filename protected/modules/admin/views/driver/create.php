<style>
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important; 
    }
</style>
<?
if ($model->isNewRecord)
{
	$title	 = "Add";
//CONFIRM
	$js		 = "if($.isFunction(window.refreshDriver))
{            
window.refreshDriver();
}
else
{
window.location.reload();
}

";
}
//UPDATE
else
{
	$title	 = "Edit";
	$js		 = "	if($.isFunction(window.refreshDriver))
{    
window.refreshDriver();
}
else
{
alert('updated');
}
";
}

Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
if (!Yii::app()->request->isAjaxRequest)
{
	$panelCss	 = "col-sm-9 col-md-7 col-lg-6 ";
	$panelClass	 = " panel-grape";
}
else
{

	$panelHeading = "display: none";
}
?>
<div class="row">

    <div style="text-align:center;" class="col-xs-12 hide">
        <h3> <?= ($model->drv_id == '') ? 'Add a new ' : 'Update '; ?> driver</h3>
		<?php ?>
    </div>
    <div class="row1">           
        <div class="col-xs-12">
            <div class="<?= $panelCss ?>" style="float: none; margin: auto">
				<?php
				$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'driver-register-form',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                            $.ajax({
                            "type":"POST",
                            "dataType":"json",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                            "data":form.serialize(),
                            "success":function(data1){
                                if($.isEmptyObject(data1)){
                                    ' . $js . '
                                }
                                else{
                                      settings=form.data(\'settings\');
                                    $.each (settings.attributes, function (i) {
                                      $.fn.yiiactiveform.updateInput (settings.attributes[i], data1, form);
                                    });
                                    $.fn.yiiactiveform.updateSummary(form, data1);
                                }},
                            });
                            }
                        }'
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
				/* @var $form TbActiveForm */
				?>


                <div class="panel panel-default mb0">
                    <div class="panel-body">
                        <div class="row">
							<?php echo CHtml::errorSummary($model); ?>
                            <div class="col-xs-6">
								<?= $form->textFieldGroup($model, 'drv_name', array('label' => '')) ?>


                            </div> 
                            <div class="col-xs-6">
								<?= $form->textFieldGroup($model, 'drv_username', array('label' => '')) ?>


                            </div> <div class="col-xs-6">
								<?= $form->passwordFieldGroup($model, 'drv_password1', array('label' => '')) ?>

                            </div>
                            <div class="col-xs-6">
                                <div class="col-xs-2 pl0">      
                                    <div class="form-group">
										<?php
										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'drv_country_code',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Code",
											'fullWidth'			 => false,
											'htmlOptions'		 => array(
											),
											'defaultOptions'	 => array(
												'create'			 => false,
												'persist'			 => false,
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
                                              obj.setValue('91');
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
									</div>
								</div>
								<div class='col-xs-9'>
									<?= $form->textFieldGroup($model, 'drv_phone', array('label' => '')) ?>
								</div>  
							</div>
							<div class='col-xs-10 pr0'>
								<?= $form->textFieldGroup($model, 'drv_phone', array('label' => '')) ?>
							</div> 

						</div>
						<div class="col-xs-6">
							<?= $form->textFieldGroup($model, 'drv_username', array('label' => '')) ?>
						</div> 
						<div class="col-xs-6">
							<?= $form->passwordFieldGroup($model, 'drv_password1', array('label' => '')) ?>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<?php
								$data	 = Vendors::model()->getJSON();
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'drv_vendor_id1',
									'val'			 => $model->drv_vendor_id1,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($data)),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
								));
								?>

							</div>  
						</div>

						<div class="col-xs-6">
							<?= $form->emailFieldGroup($model, 'drv_email', array('label' => '')) ?>

						</div>
					</div>
				</div>
				<div class="panel-footer" style="text-align: center">
					<?php echo CHtml::submitButton('Add', array('class' => 'btn btn-primary')); ?>
				</div>

			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>

</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Drivers_drv_phone').mask('9999999999');
    });
</script>


