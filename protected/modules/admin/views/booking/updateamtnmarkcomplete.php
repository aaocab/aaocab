
<style>

    .form-group {
        margin-bottom: 7px;


        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .modal-dialog{
        width:500px;
    }
	.modal-body{
		padding: 0
	}
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }

    div .comments .comment {
        padding:3px;max-width:200px
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?
?>
<div class="panel-advancedoptions" >
    <div class="row"><div class="col-md-12">


            <div class="panel" >

                <div class="panel panel-body panel-default pt0">
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vendors-register-form1', 'enableClientValidation' => true,
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
							'class'	 => 'form-horizontal',
							'action' => Yii::app()->createUrl('admin/booking/updateamtnmarkcomplete')
						),
					));
					/* @var $form TbActiveForm */
					?>
                    <div class="col-xs-12 mt5">

						<?php echo CHtml::errorSummary($model); ?>
						<?= $form->hiddenField($model, 'bkg_id') ?>

                        <div class="col-xs-12 mt5">
                            <label for="Booking_bkg_amount">Vendor Amount</label>
							<?= $form->textFieldGroup($model, 'bkg_amount', array('label' => '')) ?>

                            <div class="form-group">
								<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                            </div>  
                        </div> 


                    </div>




					<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {



        $("#Booking_bkg_driver_id").change(function () {
            var href = '<?= Yii::app()->createUrl("admin/booking/getdriverdetails"); ?>';
            var drv_id = $(this).val();

            $.ajax({
                url: href,
                dataType: "json",
                data: {"drvid": drv_id},
                "success": function (data) {
                    $("#Booking_bkg_extdriver_contact").val(data.drvContact);

                }

            });
        });
    });


</script>
