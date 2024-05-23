<style>
	.selectize-part .selectize-input{ width: 100%!important;}

</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/cityRouteWidget.js?v=' . $version);

$ptime = date('h:i A', strtotime('6am'));
$timeArr = Filter::getTimeDropArr($ptime);

$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
	<div class="row">
		<div class="col-md-3">Pickup City1</div>
		<div class="col-md-2">Pickup Date</div>
		<div class="col-md-2">Pickup Time</div>
		<div class="col-md-1">Action</div>
		<div class="col-md-4">Estimated Arrival Time</div>
		
	</div>
	<div class="tripDataset selectize-part">
		
	</div>
	<div class="row">
		<div class="col-md-2">
			<input type="button" name="add" value="Add" onclick="$CRWidget.addNewRow()" />
			<input type="button" name="save" value="Save" onclick="$CRWidget.validateRoutes()" />
		</div>
	</div>
</div>
<script>
	var timeArr = JSON.parse('<?php echo json_encode($timeArr) ?>');
	$CRWidget = new CityRouteWidget();
</script>