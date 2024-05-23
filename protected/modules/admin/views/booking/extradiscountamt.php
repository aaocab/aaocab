<style type="text/css">
    .form-group {
        margin-bottom: 0;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .error{
        color:#ff0000;
    }

    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }

    .bg-warning{
        color: #333333;
    }


    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.2;
        text-align: center;
    }

    .form-control{
        border: 1px solid #a5a5a5;
        text-align: center;

    }

    .modal-title{
        text-align: center;
        font-size: 1.5em;
        font-weight: 400;

    }
</style>
<div class="row">
    <div class="col-xs-12 text-center h3 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Booking Id: <b><?= $bookingId ?></b></span></label>
    </div>
</div>
<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'pickuptime-form',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){				
								if(!checkValidation())
								{
								   return false;
								}
								
							}
                    }'
					),
					'enableAjaxValidation' => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
		/* @var $form TbActiveForm */
		?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
            <div class="col-xs-12"> 
				<?=$form->hiddenField($model, 'biv_bkg_id')?>
				<div class="form-group">
					<label  class="col-xs-4 col-sm-4 control-label">Discount Amount:</label>
						<div class="form-group col-xs-4 col-sm-4 ">
							   <?= $form->textFieldGroup($model, 'bkg_extra_discount_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'style' => 'text-align:left', 'placeholder' => "Enter Amount"]))) ?>
						</div>	
				</div>
				<div class="form-group">
					<label  class="col-xs-4 col-sm-4 control-label">Reason:</label>
						<div class="form-group col-xs-8 col-sm-8 pt15">
							 <?= $form->textAreaGroup($model, 'bkg_extra_discount_reason', array('label'	 => '', 'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Reason for giving discount']]))?>
						</div>	
				</div>
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-xs-12 text-center pb10">
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30','onclick'=>'return saveExtraDiscountAmount();')); ?>
		</div>
    </div>
</div> 
<?php $this->endWidget(); ?>
<script>

	function saveExtraDiscountAmount()
	{
		if($('#BookingInvoice_bkg_extra_discount_reason').val() == '')
		{
			alert("Please provide the reason for giving discount");
			return false;
		} 
		var href = '<?= Yii::app()->createUrl("admin/booking/ExtraDiscountAmount"); ?>';
		$.ajax({
			"url": href,
			"type": "GET",
			"dataType": "json",
			"data": {"bkgid": $('#BookingInvoice_biv_bkg_id').val(), "disamount": $('#BookingInvoice_bkg_extra_discount_amount').val(), "disreason": $('#BookingInvoice_bkg_extra_discount_reason').val()},
			"success": function (data1)
			{
				if(data1.success)
				{
					alert("One-Time Price Adjust Successfully.");	
					location.reload();					 
				}
				else
				{
					alert(data1.message);	
				}

			}
		});
		return false;
	}

</script>