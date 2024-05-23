<script id="context" type="text/javascript" 
src="<?PHP echo Yii::app()->bconnect->layer_js_url ?>"></script>
<script type="text/javascript">

	function trigger_layer() {
		Layer.checkout(
				{
					token: "<?php echo $data['token'] ?>",
					accesskey: "<?php echo $data['accesskey']; ?>"
				},
				function (response) {
					processPayment(response);
				},
				function (err) {
					alert(err.message);
				}
		);
	}
	function processPayment(responseval) {
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": "<?PHP echo CHtml::normalizeUrl(Yii::app()->createUrl('payment/bkrsp')) ?>",
			"data": {'response': JSON.stringify(responseval)},
			"beforeSend": function ()
			{
				ajaxindicatorstart("");
			},
			"complete": function ()
			{
				ajaxindicatorstop();
			},
			"success": function (data1)
			{
				alert(data1);
			}
		});

	}
</script>

