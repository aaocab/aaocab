<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/chat.js?v=' . $version);
?>
<div class="col-xs-12 chat_window-right">
	<div id="topDetails" class="top-details" style="display:none;"></div>

	<div class="col-xs-12 chat_window" id="messageChatTable"></div>
</div>

<div id="messageJoinChatBox" style="display:none; float: left;" class="pl15 mt10">
	<a href="Javascript:void(0)" onclick="$chat.takeChatOwnerShip(1)" class="btn btn-primary">Join This Chat</a>
</div>
<div id="messageTakeOverChatBox" style="display:none; float: left" class="pl15 mt10">
	<a href="Javascript:void(0)" onclick="$chat.takeChatOwnerShip(2)" class="btn btn-primary">Takeover This Chat</a>
</div>

<div class="col-xs-12" id="messageChatBox" style="display:none;">
	<?php
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'chatForm',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError)
				{
					if(!hasError)
					{
						$chat.stop();
						$.ajax({
							"type":"POST",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/chat/index')) . '",
							"data":form.serialize(),
							"dataType": "json",
							"success":function(data1)
							{
								if(data1.success)
								{
									$("#ChatLog_chl_msg").val("");
									$("#ChatLog_chl_driver_visible").attr("checked", false);
									$("#ChatLog_chl_customer_visible").attr("checked", false);

									$chat.getChatLogs(0);
								}
								else
								{
									var errors = data1.errors;
									settings=form.data(\'settings\');
									$.each (settings.attributes, function (i) 
									{
										$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
									});
									$.fn.yiiactiveform.updateSummary(form, errors);
								}
							},
							"complete":function()
							{
								$chat.start();
							}
						});
					}
				}'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		)
	));
	/* @var $form TbActiveForm */
	?>
	<?php echo CHtml::errorSummary($model); ?>
	<?php echo $form->hiddenField($model, 'cht_id', array('value' => $chtId)) ?>
	<?php echo $form->hiddenField($model, 'cht_ref_id', array('value' => $entityId)) ?>
	<?php echo $form->hiddenField($model, 'cht_ref_type', array('value' => $entityType)) ?>

	<div class="row table-responsive m0">            
		<div class="col-xs-12 p0 mt10">
			<div class="form-group ">
				<div class="controls">
					<?= $form->textAreaGroup($model, 'chl_msg', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter message here', 'style' => 'min-height:75px', 'maxlength' => '1000')))); ?>
				</div>
			</div>
			<div class="form-group">Send to : 
				<?= $form->checkBox($model, 'chl_driver_visible', ['label' => 'Driver', 'checked' => false]); ?> Driver
				<?= $form->checkBox($model, 'chl_vendor_visible', ['label' => 'Vendor', 'checked' => true]); ?> Vendor
				<?= $form->checkBox($model, 'chl_customer_visible', ['label' => 'Customer', 'checked' => false]); ?> Customer
			</div>

			<div class="form-group">
				<?php echo CHtml::submitButton('Send', array('class' => 'btn btn-primary', 'id' => 'btnSendChat')); ?>
				<?php echo CHtml::button('End Chat', array('class' => 'btn btn-warning pull-right', 'id' => 'btnEndChat', 'onclick' => '$chat.takeChatOwnerShip(3)')); ?>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<script>
    var chatModel = {
        "entityId": 0,
        "entityType": 0,
        "ownerId": 0,
        "userId": 0,
        "userType": 0,
        "chtId": 0,
       	"chlId": 0,
		"isClicked": 0
    };

    var chatResJson = {
        "jsonData": ""
    }; 
    chatModel.chtId = $("#ChatLog_cht_id").val();
    chatModel.entityId = $("#ChatLog_cht_ref_id").val();
    chatModel.entityType = $("#ChatLog_cht_ref_type").val();

    $chat = new Chat();
    $chat.leftPanel = false;
    $chat.model = chatModel;
    $chat.resJson = chatResJson;

    $('#ChatLog_chl_msg').keypress(function (e) {
        var key = e.which;

        if (!e.shiftKey && key == 13)  // the enter key code
        {
            $('input[id = btnSendChat]').click();
            return false;
        }
    });
</script>