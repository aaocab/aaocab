<style>
    .dlgComments .dijitDialogPaneContent {
        overflow: auto;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }
    div .comments .comment {
        padding:3px;
        max-width:200px
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
    .remarkbox {
        width: 100%;
        padding: 3px;
        overflow: auto;
        line-height: 14px;
        font: normal arial;
        border-radius: 5px;
        -moz-border-radius: 5px;
        border: 1px #aaa solid;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
            <div class="panel" >
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll">
                        <div class="row">
                            <div class="col-xs-12">
								<?php
								$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'add-account-form', 'enableClientValidation' => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error',
										'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                            $.ajax({
                            "type":"POST",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/report/accountedit', ['bkgId' => $bookModel->bkg_id])) . '",
                            "data":form.serialize(),
							"dataType": "json",
                                                        "success":function(data1){
                                                            if(data1.success){
                                                                 bootbox.hideAll();
                                                                 
                                                                     refreshAccGrid();
                                                                     
                                                            }else{
                                                                  alert(\'Sorry error occured\');
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
								<div class="form-group">
									<label for="inputEmail" class="control-label col-xs-3"><b>Total Amount:</b></label>
									<div class="col-xs-9">
										<?= $form->textField($bookModel, 'bkg_amount', array('label' => '', 'class' => 'form-control')) ?>
									</div>
								</div>

								<div class="form-group">
									<label for="inputEmail" class="control-label col-xs-3"><b>Vendor Amount:</b></label>
									<div class="col-xs-9">
										<?= $form->textField($bookModel, 'bkg_vendor_amount', array('label' => '', 'class' => 'form-control')) ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail" class="control-label col-xs-3"><b>Advance Amt:</b></label>
									<div class="col-xs-9">
										<?= $form->textField($bookModel, 'bkg_advance_amount', array('label' => '', 'class' => 'form-control')) ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail" class="control-label col-xs-3"><b>Amount Due:</b></b></label>
									<div class="col-xs-9">
										<?= $form->textField($bookModel, 'bkg_amount_due', array('label' => '', 'class' => 'form-control')) ?>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail" class="control-label col-xs-3"><b>Add Remark:</label>
									<div class="col-xs-9">
										<?= $form->textAreaGroup($bookModel, 'bkg_message', array('label' => '', 'rows' => 10, 'cols' => 50, 'class' => 'form-control')) ?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-offset-3 col-xs-9"> <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?> </div>
								</div>
								<?php $this->endWidget(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $totalAmount = <?= $bookModel->bkg_amount ?>;
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');

        $("#Booking_bkg_amount").blur(function () {
            calculateDueAmount();
        });

        $("#Booking_bkg_advance_amount").blur(function () {
            calculateDueAmount();
        });
    });
    function calculateDueAmount() {
        var totalAmount = parseInt($("#Booking_bkg_amount").val());
        var advAmount = parseInt($("#Booking_bkg_advance_amount").val());
        advAmount = isNaN(advAmount) ? 0 : advAmount;
        var dueAmount = parseInt(totalAmount - advAmount);
        $("#Booking_bkg_amount_due").val(dueAmount);
    }
</script>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
