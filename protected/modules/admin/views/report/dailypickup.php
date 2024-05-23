<?
if ($error == 1)
{
	?>
	<div class="row m0 mt20" id="passwordDiv">
		<form name="dailypickup" method="POST" action="<?= Yii::app()->request->url ?>">
			<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
			<div class="col-xs-offset-4 col-xs-4">   
				<div class="form-group row text-center">
					<input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
				</div>
				<div class="Submit-button row text-center">
					<button type="submit" class="btn btn-primary">SUBMIT</button>
				</div>
			</div>
		</form>
	</div>
<? } ?>
<?
if ($error == 2)
{
	?>
	<div class="row m0 mt20"   >
		<div class="col-xs-offset-4 col-xs-4 h3 text-center">
			Wrong Password 
		</div>
	</div>
<? } ?>
<?
if ($error == 0)
{
	?>


	<?
	$style	 = "padding:5px; ";
	$str	 = '';
	$str	 .= '<table BORDER="1px" style="width:100%;border-collapse: collapse ;" >';

	$str .= '<tr>';
	$str .= '<td style=" border-top-left-radius: 10px;" ></td>';
	$str .= '<th style="' . $style . '" >Category </th>';
	$str .= '<th style="' . $style . '" > Previous Month  </th>';
	$str .= '<th style="' . $style . '" > This Month </th>';
	$str .= '<th style="' . $style . '" > Previous Week  </th>';
	$str .= '<th style="' . $style . '" > This Week </th>';
	$str .= '<th style="' . $style . '" > Today - 2 </th>';
	$str .= '<th style="' . $style . '" > Yesterday </th>';
	$str .= '<th style="' . $style . '" > Today </th>';
	$str .= '</tr>';
	$str .= '<tr id="res1" >';
	$str .= '</tr>';
	$str .= '<tr id="res2">';
	$str .= '</tr>';
	$str .= '<tr id="res3">';
	$str .= '</tr>';
	$str .= '<tr id="res4">';
	$str .= '</tr>';
	$str .= '<tr id="res5">';
	$str .= '</tr>';
	$str .= '<tr id="res6">';
	$str .= '</tr>';

	$str .= '</table>';
	echo $str;


	?>
	<script type="text/javascript">
		getData(1);
		getData(2);
		getData(3);
		getData(4);
		getData(5);
		getData(6);



		function getData(repId) {
			var href = '<?= Yii::app()->createUrl("admin/report/getdailypickupdata"); ?>';
			$.ajax({
				url: href,

				"type": "POST",
				data: {"repId": repId, "YII_CSRF_TOKEN": "<?= Yii::app()->request->csrfToken ?>"},
				"success": function (data) {
					$("#res" + repId).replaceWith(data);
					//				if (repId < 6) {
					//					getData(repId + 1);
					//				}else{
					//					 
					//				}
				}
			});
		}

	</script>
	<?
}
list($usec, $sec) = explode(" ", microtime());

$time1 = ((float) $usec + (float) $sec);
//echo $time2	 = $time1 - $time;
?>