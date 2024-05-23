<div class="panel-advancedoptions " >
    <div class="row">
        <div class="col-xs-12">            
            <div class="panel" >
                <div class="panel-body panel-body p0">
                    <div class="panel-scroll1">
                        <div>
							<?= CHtml::beginForm(Yii::app()->createUrl('admin/vendor/addremark'), "post", ['accept-charset' => "UTF-8", 'id' => "remarkForm", 'onsubmit' => 'return submitRemarkForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
							<?= CHtml::hiddenField("drv_id", $model->drv_id) ?>
                            <div class="col-xs-12">
                                <span class="has-error text-danger" id = 'drverror' style="display: none"></span>
                            </div>
                            <div class="form-group">

                                <div class="col-xs-12 mt10" id="reasontext" >
									<?= CHtml::textArea('drv_remark', '', ['id' => "drv_remark", 'class' => "form-control", 'placeholder' => 'Add remarks for ' . $model->drv_name]) ?>
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
                    $('#drverror').hide();
                    bootbox.hideAll();
					bootbox.alert('Thank you for adding remark. Your remark successfully added');
					location.reload();
                    return true;
                } else {
                    $('#drverror').text(data1.error);
                    $('#drverror').fadeIn(600);
                }
            }
        });
        return false;
    }
</script>
