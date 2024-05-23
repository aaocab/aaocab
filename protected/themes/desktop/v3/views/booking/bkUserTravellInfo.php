<?php 
	if (!Yii::app()->user->isGuest)
	{
		$isUserName = 0;
		if(Yii::app()->user->loadUser()->usr_name == '' || Yii::app()->user->loadUser()->usr_lname == '')
		{
			$isUserName = 1;
		}
	?>
	<div class="btn-group mr-1 mb-1">
		<span class="pt5">Booking for:</span> <div class="dropdown">
			<button type="button" class="btn btn-sm dropdown-toggle pl5 font-14 travellerinfo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="clsusername"><?php echo Yii::app()->user->loadUser()->usr_name.'&nbsp;'.Yii::app()->user->loadUser()->usr_lname; ?></span>
			</button>
		</div>
		<input type="hidden" name="isUserName"  value="<?= $isUserName ?>">
	</div>
<?php }?>

<script type="text/javascript">
	$(".travellerinfo").click(function(){ 
		//debugger;
		var href2 = "<?php echo Yii::app()->createUrl('booking/travellerinfo') ?>";
		var rdata = $("#cabcategory").closest("form").find("INPUT[name=rdata]").val();
		$.ajax({
			"url": href2,
			data: {'rdata': rdata, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			"type": "POST",
			"dataType": "html",
			"success": function(data)
			{	
			   //$('#bkCommonModelHeader').text('Traveller Info');
				$('#bkCommonModelBody').html(data);
				$('#bkCommonModel').modal('show');
			},
			"error": function(xhr, ajaxOptions, thrownError)
			{
				alert(xhr.status);
				alert(thrownError);
			}
		});
		return false;
		
	});
</script>