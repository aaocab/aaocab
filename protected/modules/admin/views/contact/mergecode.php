<div class="panel-advancedoptions " >
    <div class="row">
        <div class="col-xs-12">            
            <div class="panel" >
                <div class="panel-body panel-body p0">
                    <div class="panel-scroll1">
                        <div>
							<?= CHtml::beginForm(Yii::app()->createUrl('admin/contact/mergecode'), "post", ['accept-charset' => "UTF-8", 'id' => "mergeCodeForm", 'onsubmit' => 'return submitContactForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
							<?= CHtml::hiddenField("ctt_id", $model->ctt_id) ?>
                            <div class="col-xs-12">
                                <span class="has-error text-danger" id='mergeerror' style="display: none"></span>
                            </div>
							<div class="form-group">
								<div class="col-xs-12 mt10" id="reasontext">
									<b>Merge This Contact ID: <?php echo $model->ctt_id; ?></b>
								</div>
							</div>
                            <div class="form-group">
                                <div class="col-xs-12 mt10" id="reasontext" >
									<?=CHtml::textField('ctt_ref_code', '', ['id' => "ctt_ref_code", 'class' => "form-control", 'placeholder' => 'Enter Primary Contact ID']) ?>
                                </div>
                            </div>
                            <div class="Submit-button text-center" >
                                <button type="submit" class="btn btn-primary mt10">SUBMIT</button>
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
    function submitContactForm(obj) {
        var form = $(obj);
        $.ajax({
            "type": "POST",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->request->url) ?>",
            'dataType': "json",
            "data": form.serialize(),
            "success": function (data1) {
                if (data1.success) {
                    $('#mergeerror').hide();
                    bootbox.hideAll();
                    return true;
                } else {
                    $('#mergeerror').text(data1.error);
                    $('#mergeerror').fadeIn(600);
                }
            }
        });
        return false;
    }
</script>
