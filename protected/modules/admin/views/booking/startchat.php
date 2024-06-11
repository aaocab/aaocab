<div class="row col-md-12 col-xs-12">
	<?php
	$this->renderPartial("/chat/form", ['model' => $model, 'entityId' => $entityId, 'entityType' => $entityType]);
	?>
</div>
<script>
	$(document).ready(function() {
		
		$chat.getChatLogs(0);
		
		// Auto Refresh/ Fetch Messages
		$chat.start();
	});
</script>