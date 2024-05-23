
<style type="text/css">

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<?
$display = ($ptp_type == 9) ? 'block' : 'none';
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel panel-body panel-default pt0" id="divpmt">

				<?php
				if ($maxrefund > 0)
				{
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'refundForm', 'enableClientValidation' => true,
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
                                    if(data1.success){
                                           if(data1.url!=""){
                                             location.href = data1.url;
                                           }else{
                                              alert(data1.message);
                                              location.reload();
                                           }
										}
                                    else{
										if(data1.message!=""){
											alert(data1.message);
											 return;
										}
                                        settings=form.data(\'settings\');
                                        data2 = data1.error;
                                        $.each (settings.attributes, function (i) {
                                          $.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
                                        });
                                        $.fn.yiiactiveform.updateSummary(form, data2);
                                    }
                                   
                                },
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
							'class'	 => 'form-horizontal',
							'action' => Yii::app()->createUrl('admin/transaction/list'),
						),
					));
					/* @var $form TbActiveForm */
					?>
					<div class="col-xs-12 mt5">
						<div class="row">
							<?php echo CHtml::errorSummary($model); ?>
							<?= $form->hiddenField($model, 'apg_booking_id') ?>
							<div class="col-xs-6">
								<?= $form->numberFieldGroup($model, 'apg_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['value' => $maxrefund, 'class' => 'form-control', 'placeholder' => "Refund Amount", 'min' => 1, 'max' => $maxrefund]))) ?>
							</div> 
							<div class="col-xs-6">
								<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'id' => 'sbtn')); ?>
							</div>
						</div> 
						<div class="row">
							<div class="row">
							 	<div class="col-xs-12 col-sm-6">Refunded to wallet : <?php echo $transArr['refundedToWallet']; ?></div>
							  
								</div>
							</div>
						</div>
						 
					</div>
					<?php
					$this->endWidget();
				}
				else
				{
					echo "Zero amount to be refund";
				}
				?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	$(document).ready(function ()
	{
		showDollar();
	});
	$('#<?= CHtml::activeId($model, "apg_amount") ?>').on('change', function () {

		showDollar();
	});
	$('#<?= CHtml::activeId($model, "apg_amount") ?>').on('blur', function () {

		showDollar();
	});
	function showDollar() {
		$pamount = $('#<?= CHtml::activeId($model, "apg_amount") ?>').val();
		$damount = ($pamount / <?= Yii::app()->params['dollarToRupeeRate'] ?>).toFixed(2);
		$('#dvalue').html('$' + $damount);
	}


	$('#refundForm').on('submit', function () {
		$(this).find('input[type="submit"]').attr('disabled', 'disabled');
	});

</script>
