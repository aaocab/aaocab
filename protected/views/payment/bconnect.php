<html>
    <head>
        <meta charset="utf-8">
		<script id="context" type="text/javascript" 
		src="<?PHP echo Yii::app()->bconnect->layer_js_url ?>"></script>

    </head>
    <body>
		<?php
		?>
        <h4>Processing ...</h4>
        <form id="checkout_form" name="checkout_form" method="post"  >
			<?php
			foreach ($param_list as $name => $value) :
				?>

				<label for="<?php echo $name ?>"><?php echo $name ?></label>
				<input type="text" name="<?php echo $name ?>" value="<?php echo $value; ?>" ><br>
			<?php endforeach; ?>		 


			<button type="button" value="Submit"  id="btnsbt"> Submit</button>
        </form>
		<input id="pt" type="hidden">

        <script type="text/javascript">
			// self executing function
			$('#btnsbt').on("click", function (event)
			{
//				alert('submitted');
				$.ajax({
					"type": "POST",
					"dataType": "html",
					"url": "<?PHP echo CHtml::normalizeUrl(Yii::app()->createUrl('payment/bconnect')) ?>",
					"data": $("#checkout_form").serialize(),
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
						$('#layerpay').html(data1);
						trigger_layer();
					}
				});
				event.preventDefault();
				// alert(tcity);
			});


		</script>
		<div id="layerpay"></div>
    </body>
</html>