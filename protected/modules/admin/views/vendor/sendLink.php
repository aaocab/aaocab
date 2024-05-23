<div class="panel-advancedoptions " >
    <div class="row">
        <div class="col-xs-12">            
            <div class="panel" >
                <div class="panel-body panel-body p0">
                    <div class="panel-scroll1">
                        <div>
							<?= CHtml::beginForm(Yii::app()->createUrl('admin/vendor/sendLink'), "post", ['accept-charset' => "UTF-8", 'id' => "remarkForm", 'onsubmit' => 'return submitRemarkForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
							<?= CHtml::hiddenField("vnd_id", $model->vnd_id) ?>
                            <div class="col-xs-12">
                                <span class="has-error text-danger" id = 'vnderror' style="display: none"></span>
                            </div>
							<div class="form-group">
                                <div class="col-xs-12 mt10" >
								<label><b>Subject/Title</b></label>
									<?php echo CHtml::activeTextField($model,'vnd_subject',array('required' => 'required', 'style' => 'width:100%'));?>
                                </div>
                            </div>
							<div class="form-group">
                                <div class="col-xs-12 mt10" >
								<label><b>Message</b></label>
									<?php echo CHtml::activeTextArea($model,'vnd_message',array('required' => 'required', 'style' => 'width:100%;height:100px'));?>
                                </div>
                            </div>
<!--							<div class="form-group">
                                <div class="col-xs-6 mt10" >
									<?#= CHtml::activeCheckBox($model, 'vnd_sms', array()) ?><b>&nbsp;SMS</b>
                                </div>
                            </div>-->
							<div class="form-group">
                                <div class="col-xs-6 mt10" >
									<?= CHtml::activeCheckBox($model, 'vnd_email', array()) ?><b>&nbsp;Email</b>
                                </div>
                            </div>
							<div class="form-group">
                                <div class="col-xs-6 mt10" >
									<?= CHtml::activeCheckBox($model, 'vnd_notification', array()) ?><b>&nbsp;Notification</b>
                                </div>
                            </div>
                            <div class="Submit-button text-center mt10" >
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
					$('#vnderror').hide();
					bootbox.hideAll();
					return true;
				} else {
					$('#vnderror').text(data1.error);
					$('#vnderror').fadeIn(600);
				}
			}
		});
		return false;
	}
</script>
