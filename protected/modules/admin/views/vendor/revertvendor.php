
<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?
$reasonList = ['' => '< Select a reason >'] + Vendors::model()->getDeleteReasonList();
?>
<div class="panel-advancedoptions" >
    <div class="row"><div class="col-xs-12">            
            <div class="panel" >
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">
                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
							<?= CHtml::beginForm(Yii::app()->createUrl('admin/vendor/revert'), "post", ['accept-charset' => "UTF-8", 'id' => "revertForm", 'onsubmit' => 'return submitRevertForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
							<?= CHtml::hiddenField("vnd_id", $bkid, ['id' => "vnd_id"]) ?>

                            <div class="col-xs-12 mb5">
                                    <label for="delete"><b>Reason for rejection :  </b></label>
                                    <?php echo ($reason!='')?Vendors::model()->getDeleteReasonList($reason):''; ?>
                            </div>
                            <div class="col-xs-12 mb5">
                                    <label for="delete"><b>Reason details :  </b></label>
                                    <?php echo ($reason_other!='')?$reason_other:''; ?>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="delete"><b>Reason for reverting : </b></label> </div>
                                <div class="col-xs-12 mt10" id="reasontext">
                                    <?= CHtml::textField('vnd_delete_other', '', ['id' => "vnd_delete_other", 'class' => "form-control", 'placeholder' => 'Enter reason', 'required' => true])
                                    ?>
                                </div>
                            </div>
                            <div class="Submit-button text-center" >
                                <button type="submit" class="btn btn-primary mt10" >SUBMIT</button>
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
    function submitRevertForm(obj) {
        var form = $(obj);
        $.ajax({
            "type": "POST",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->request->url) ?>",

            'dataType': "json",
            "data": form.serialize(),
            "success": function (data1) {
                if (data1.success)
                {
                    alert("Vendor reverted successfully.");
                } else
                {
                    alert("Error occurred while reverting vendor.");
                }
                bootbox.hideAll();
                location.reload();
            },
        });
        return false;
    }


</script>
