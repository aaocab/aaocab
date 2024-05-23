<div class="row ml20 mr20">
    <div class="col-xs-2 chat_window-left" id="messageListLeftTable">
		<div class="list-block">
			<ul class="pl0 chat_listview" id="ulMsgListLeftTable">
				<?php echo $messageLeftHtml; ?>
			</ul>
		</div>
    </div>
    <div class="col-xs-10 col-sm-10 table-responsive pr0">
		<?php
		$this->renderPartial("form", ['model' => $model, 'refId' => $refId, 'refType' => $refType, 'entityId' => $entityId, 'entityType' => $entityType]);
		?>
	</div>
</div>
<script>
	$chat.leftPanel = true;
	
	// Auto Refresh/ Fetch Messages
	$(document).ready(function() {
		$chat.start();
	});
</script>