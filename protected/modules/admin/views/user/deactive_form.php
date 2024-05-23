<div class="panel-advancedoptions " >
    <div class="row">
        <div class="col-xs-12">            
            <div class="panel" >
                <div class="panel-body panel-body p0">
                    <div class="panel-scroll1">
						<?= CHtml::beginForm(Yii::app()->createUrl('admin/user/deactive'), "post", ['accept-charset' => "UTF-8", 'id' => "remarkForm", 'onsubmit' => 'return submitRemarkForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
						<?= CHtml::hiddenField("user_id", $model->user_id) ?>
                        <div>
							
                            <div class="col-xs-12">
                                <span class="has-error text-danger" id = 'userError' style="display: none"></span>
                            </div>
                            <div class="form-group">

                                <div class="col-xs-12 mt10" id="reasontext" >
									<?= CHtml::textArea('usr_deactivate_reason', '', ['id' => "usr_deactivate_reason", 'class' => "form-control", 'placeholder' => 'Delete reason for ' . $model->usr_name." ".$model->usr_lname]) ?>
                                </div>
                            </div>
                            <div class="Submit-button text-center" >
                                <button type="submit" class="btn btn-primary mt10" >SUBMIT</button>
                            </div>
                        </div>
						<?= CHtml::endForm() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function submitRemarkForm(obj) {
        var form = $(obj);
        $.ajax({
            "type": "POST",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->request->url) ?>",
            'dataType': "json",
            "data": form.serialize(),
            "success": function (data1) {
                if (data1.success) {
                    $('#userError').hide();
                    bootbox.hideAll();
                    return true;
                } else {
                    $('#userError').text(data1.error);
                    $('#userError').fadeIn(600);
                }
            }
        });
        return false;
    }
</script>
