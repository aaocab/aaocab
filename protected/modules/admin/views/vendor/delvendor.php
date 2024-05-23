
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
							<?= CHtml::beginForm(Yii::app()->createUrl('admin/vendor/del'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'onsubmit' => 'return submitDelForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
							<?= CHtml::hiddenField("vnd_id", $bkid, ['id' => "vnd_id"]) ?>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="delete"><b>Reason for deletion : </b></label>
									<?= CHtml::dropDownList('vnd_delete_reason', '', $reasonList, ['id' => "vnd_delete_reason", 'class' => "form-control", 'required' => true])
									?>
<!-- <textarea class="form-control" rows="3" cols="50" name="bkreason" id="bkreason" placeholder="Please write message" required="true"></textarea>           -->
                                </div>
                                <div class="col-xs-12 mt10" id="reasontext" style="display: none">
									<?= CHtml::textField('vnd_delete_other', '', ['id' => "vnd_delete_other", 'class' => "form-control", 'placeholder' => 'Enter reason'])
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
    function submitDelForm(obj) {
        var form = $(obj);
        $.ajax({
            "type": "POST",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->request->url) ?>",

            'dataType': "json",
            "data": form.serialize(),
            "success": function (data1) {
                if (data1.success)
                {
                    alert("Vendor deleted successfully.");
                } else
                {
                    alert("Error occurred while deleting vendor.");
                }
                bootbox.hideAll();
                location.reload();
            },
        });
        return false;
    }

    $("#vnd_delete_reason").change(function () {
        if ($(this).val() == 4 || $(this).val() == 5) {
            $("#reasontext").show();
            $("#vnd_delete_other").attr("placeholder", "Enter reason");
            if ($(this).val() == 4) {
                $("#vnd_delete_other").attr("placeholder", "Enter duplicate vendor name or vendor code");
            }
            $("#vnd_delete_other").attr('required', 'required');
        } else {
            $("#reasontext").hide();
            $("#vnd_delete_other").removeAttr('required');
        }
    });

</script>
