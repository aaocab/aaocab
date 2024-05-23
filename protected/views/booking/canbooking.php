<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?
$rDetail = CancelReasons::model()->getListbyUserType(1);
$reasonList = ['' => '< Select a reason >'] + $rDetail[0];
$reasonPHList = $rDetail[1];
$jsReasonPHList=  json_encode($reasonPHList);
?>
<div class="panel-advancedoptions">
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel">
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">
                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
							<?= CHtml::beginForm(Yii::app()->createUrl('booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "cancelForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
							<?= CHtml::hiddenField("bk_id", $bkid, ['id' => "bk_id"]) ?>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="delete"><b>Reason for cancellation : </b></label>
									<? //= CHtml::textArea('bkreason', '', ['id' => "bkreason", 'placeholder' => "Please write message", 'class' => "form-control", 'rows' => "3", 'cols' => "50"]) ?>
									<? //= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + Booking::model()->getCancelReasonListForCustomer('cancel'), ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
									<?= CHtml::dropDownList('bkreason', '', $reasonList, ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                                </div>
                                <div class="col-xs-12 mt10" id="reasontext" style="display: none">
									<?= CHtml::textArea('bkreasontext', '', ['id' => "bkreasontext", 'class' => "form-control", 'placeholder' => 'Description'])
									?>
                                </div>
                            </div>
                            <div class="Submit-button text-center" >
								<? echo CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary mt10"]); ?>
                            </div>
							<?= CHtml::endForm() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#bkreason").change(function () {
            $("#reasontext").show();
            $("#bkreasontext").attr('required', 'required');
        });
    });

    $('input[type="submit"]').click(function () {
        if ($('#bkreason').val() != '' && $('#bkreasontext').val() != '')
        {
			$('input[type="submit"]').css('pointer-events', 'none');
			bootbox.hideAll();
            
        }

    });
	
</script>
