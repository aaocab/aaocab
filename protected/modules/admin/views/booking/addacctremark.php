
<script>
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>


<div class="panel" >
    <div class="panel-body pt0 pb0">

		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'add-mark-remark-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){
                        $.ajax({
                        "type":"POST",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/addaccountingremark', ['booking_id' => $bookingId, 'blg_remark_type' => $blgRemarkType])) . '",
                        "data":form.serialize(),
                        "dataType": "json",
                        "success":function(data1){
                            if(data1.success){                            
                            accountFlag(data1.bkgid,0);
                            updateGrid(data1.bkgstatus);
                            removeTabCache(data1.bkgstatus);
                            acctbox1.modal("hide");
                               return true;
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
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="row">
			<?= $form->hiddenField($logModel, 'blg_booking_id', ['value' => $model->bkg_id]); ?>
            <div class="col-xs-12">
				<?= $form->textAreaGroup($logModel, 'blg_desc', array('label' => 'Add Remark', 'rows' => 10, 'cols' => 50)) ?>
            </div>
            <div class="Submit-button" style="margin-top: 5px;">
				<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>
