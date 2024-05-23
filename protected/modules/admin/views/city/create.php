
<?
$stateList = CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
if ($model->isNewRecord)
{
	$title	 = "Add";
//CONFIRM
	$js		 = "if($.isFunction(window.refreshCity))
{
window.refreshCity();
}
else
{
window.location.reload();
}
";
}
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
    <div class="<?= $panelCss ?>" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12 hide">
            <h3> <?= ($model->cty_id == '') ? 'Add a new ' : 'Update '; ?> city</h3>
			<?php ?>
        </div>
        <div class="row">           
            <div class="col-xs-12">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'city-manage-form',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError)
			{
				if(!hasError)
				{                                      
                                   checkExisting();            
				}
                               
			}'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));
				/* @var $form TbActiveForm */
				?>


                <div class="panel panel-default">
                    <div class="panel-body">
						<?php echo CHtml::errorSummary($model); ?>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="control-label">State <span class="required">*</span></label>
									<?php
									$dataState	 = VehicleTypes::model()->getJSON($stateList);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'cty_state_id',
										'val'			 => $model->cty_state_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataState)),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select State')
									));
									?>
                                    <div id="errorstate" class="mt0" style="color:#da4455"></div>
                                </div> 
                            </div> 

                            <div class="col-xs-6">
								<?= $form->textFieldGroup($model, 'cty_name', array()) ?>
                                <div id="errorctyname" class="mt10 n" style="color:#da4455"></div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
								<?= $form->textFieldGroup($model, 'cty_county', array()) ?>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-footer" style="text-align: center">
						<? //php echo CHtml::submitButton('Add', array('class' => 'btn btn-primary')); ?>
						<?php echo CHtml::submitButton("Add", array());
						?>
                    </div>

                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#errorctyname").text('');
        $("#errorstate").text('');
        $('.bootbox').removeAttr('tabindex');
    });


    function checkExisting() {
        state = $('#<?= CHtml::activeId($model, "cty_state_id") ?>').val();
        if (state == '') {
            $("#errorstate").text('');
            $("#errorstate").text('Please select a state');
        } else {
            $("#errorstate").text('');
        }
        city = $('#<?= CHtml::activeId($model, "cty_name") ?>').val();
        if (state != '' && city != '') {
            var href = '<?= Yii::app()->createUrl("admin/city/checkcityname"); ?>';
            $.ajax({
                url: href,
                dataType: "json",
                data: {"state": state, "city": city},
                "success": function (data) {
                    if (data) {
                        $("#errorctyname").text('');
                        $("#errorctyname").text('City name is already added');

                    } else {
                        $("#errorctyname").text('');
                        addCity();
                    }

                }
            });
        }
    }
    function addCity()
    {
        var href = '<?= Yii::app()->createUrl("admin/city/ajaxadd"); ?>';
        $.ajax({
            url: href,
            dataType: "json",
            data: {"state": state, "city": city},
            "success": function (data) {
                if (data == 'false')
                {
                    $("#errorctyname").text('');
                    $("#errorctyname").text('City name is already added');
                }
                if (data == 1)
                {
                    alert("city added successfully");
                    window.refreshCity();
                }
            }
        });
    }

</script>


