<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/city.js?v=' . $version);
?>
<style type="text/css">
    .table_new table{ width: 99%;}
    .selectize-input {
        min-width: 0px !important;
        width: 30% !important;
        /*  background: #de6a1e !important;
         color: #ffffff !important; */
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<div class="container1">

    <div class="panel panel-border">
        <div class="panel panel-heading">Package Information</div>
        <div class="panel-body">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'package',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                       
					calculateDistance();		
					 if(!hasError){
						 $.ajax({
							 "type":"POST",
							 "dataType":"json",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/package/form')) . '",
							 "data":form.serialize(),
							 "success":function(data1){
							 if(data1.success){
								 var data = "";
								 var isJSON = false;
							  location.href = "' . CHtml::normalizeUrl(Yii::app()->createUrl("admin/package/list")) . '";
								 
								 }else{
								 
							var errors = data1.errors;
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                              });
                              $.fn.yiiactiveform.updateSummary(form, errors);
                            
								 }
							 },
						 });
					 }
				 }'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => 'form-horizontal'
				),
			));
			/* @var $form TbActiveForm */
			?>
			<?=
			$form->errorSummary($model);
			echo CHtml::errorSummary($model)
			?>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<?= $form->textFieldGroup($model, 'pck_name', array('label' => "Package name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Package Name')))) ?>  
				</div>
				
				<div class="col-xs-12 col-sm-6">
					<div class="form-group"><label class="control-label"  >Name Generated</label>
						<div class="form-control" id="pck_desc"></div>							
					</div>
				</div>

                <div class="col-xs-12 col-sm-6">
					<?= $form->textAreaGroup($model, 'pck_desc', array('widgetOptions' => array())) ?>
	            </div>
				<div class="col-xs-12 col-sm-6">
					<?= $form->textAreaGroup($model, 'pck_inclusions', array('widgetOptions' => array())) ?>
				</div>
				<div class="col-xs-12 col-sm-6">
					<?= $form->textAreaGroup($model, 'pck_exclusions', array('widgetOptions' => array())) ?>
				</div>
				<div class="col-xs-12 col-sm-6">
					<?= $form->textAreaGroup($model, 'pck_notes', array('widgetOptions' => array())) ?>
	
				</div>






				<div class="col-sm-12"  id="packageTrip" >
					<?php
					$pckRoutes	 = $model->packageDetails;
					if (empty($pckRoutes))
					{
						$pckRoutes	 = [];
						$pckDelModel = PackageDetails::model();
						$pckRoutes[] = $pckDelModel;
					}
					$scity	 = '';
					$pcity	 = '';
					foreach ($pckRoutes as $pckRoute)
					{
						if ($oldPckRoute == null)
						{
							$oldPckRoute = PackageDetails::model();
						}

						$this->renderPartial('addPackageDetails', ['form' => $form, 'model' => $pckRoute, 'sourceCity' => $oldPckRoute->pcd_from_city, 'previousCity' => $oldPckRoute->pcd_to_city, '', 'index' => 0], false, false);
						$oldPckRoute = $pckRoute;
					}
					?>
					<div id='insertBefore'></div> 
					<div class="row float-right" style="white-space: nowrap">
						<div class="col-xs-12">
							<a class="btn btn-primary addmoreField weight400 font-bold" id="fieldAfter" title="Add More">
								<i class="fa fa-plus"></i></a>
							<a class="btn btn-danger" id="fieldBefore" title="Remove" style="display: none"><i class="fa fa-times"></i></a>
						</div>
					</div>  
				</div>


				<div class="col-sm-4 mt20">
					<label class=""></label>
					<button type="button" class="btn btn-info" id="calculateDistance"  title="Get estimated duration and total KM">Get estimated package <br>duration and total KM</button>
				</div>
				<div class="col-sm-4">
					<label>Package Durations(In minutes) *</label>
					<?= $form->numberFieldGroup($model, 'pck_min_included', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Package Duration')))) ?>  
				</div>
				<div class="col-sm-4">
					<label>Total Km *</label>
					<?= $form->numberFieldGroup($model, 'pck_km_included', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Total Km')))) ?>  
                </div>
                <div class="col-xs-12 text-center">
					<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
				</div>

            </div>
			<?php $this->endWidget(); ?>



        </div>
    </div>


</div>
<script type="text/javascript">
	count = $("INPUT.ctyDrop").length;
	$(document).ready(function () {
//		var date = new Date();
//		date.setDate(date.getDate() + 5);
//		daydt = $.datepicker.formatDate('mm/dd/yy');

		//$('#packageTrip').hide();


		Date.prototype.addDays = function (days) {
			this.setDate(this.getDate() + parseInt(days));
			return this;
		};
		callbackLogin = 'fillUserform';
		var len = $("INPUT.ctyPickup").length;

		if (len > 1)
		{
			setTimeout(function () {
				//disableRows();
				//enableRows();
			}, 200);
		}

		//  $airportRadius = '<? //= $cityRadius      ?>';



	});

	function populateDatarut()
	{

		$scity = $($("INPUT.ctyPickup")[count - 1]).val();
		$tcity = $($("INPUT.ctyDrop")[count - 1]);
		$tcity.select2('val', '').trigger("change");
		if ($scity !== "")
		{

			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					$data2 = data1;

					var placeholder = $tcity.attr('placeholder');
					$tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
							return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
						}});
				}
			});
		}
	}

	$('#fieldAfter').click(function () {

		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		count = len;
		var scity = $(elems[len - 1]).val();
		var pscity = $($("SELECT.ctyPickup")[len - 1]).val();
		var serial = $($("INPUT.sequence")[len - 1]).val();
		var noNight = $($("INPUT.nonight")[len - 1]).val();
		var dropCity = $($("SELECT.ctyDrop")[len - 1]).val();
		var key = $($("SELECT.ctyPickup")[len - 1]).attr('data-key');
		// $("SELECT.ctyDrop")[len - 1].selectize.lock();
		//alert(dropCity);
		if (dropCity == '') {
			alert("Destination City can not be blank");
			return false;
		}

		if (noNight == '') {
			alert("Night can not be blank");
			$($("INPUT.nonight")[len - 1]).first().focus();
			return false;
		}
		messages = {};
		if (pscity == '')
		{
			messages["<?= CHtml::activeId($pckDelModel, "pcd_from_city") ?>"] = [];
			messages["<?= CHtml::activeId($pckDelModel, "pcd_from_city") ?>"].push("Please select source city");
		}
		if (scity == '')
		{
			messages["<?= CHtml::activeId($pckDelModel, "pcd_to_city") ?>"] = [];
			messages["<?= CHtml::activeId($pckDelModel, "pcd_to_city") ?>"].push("Please select your destination");
		}



		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/package/addPackageDetails')) ?>",
			"data": {"scity": scity, "pscity": pscity, "index": count, "serial": serial},
			"async": false,
			"success": function (data1)
			{
				$('#fieldBefore').show();
				$("SELECT.ctyPickup").attr('readonly', true);
				$("SELECT.ctyDrop").attr('readonly', true);
				$('#insertBefore').before(data1);
				$($("SELECT.ctyPickup")[len]).attr('data-key', parseInt(key) + 1);
				$($("SELECT.ctyDrop")[len]).attr('data-key', parseInt(key) + 1);
				// $("SELECT.ctyPickup")[len - 1].selectize.lock();
				//  $("SELECT.ctyDrop")[len - 1].selectize.lock();
				disableRows();

			}
		});
	});

	$('#fieldBefore').click(function () {

		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		$($(".clsRoute")[len - 1]).remove();
		enableRows();


	});

	function disableRow(i) {

		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		var serial = parseInt($($("INPUT.sequence")[i]).val(), 10);
		var noofday = parseInt($($("INPUT.noday")[i]).val(), 10);
		var noofnight = parseInt($($("INPUT.nonight")[i]).val(), 10);



		$($("INPUT.sequence")[i + 1]).val(serial + 1);
		$($("INPUT.noday")[i + 1]).val(noofday + noofnight);
		$($("INPUT.noday")[i]).attr('readonly', true);
		$($("INPUT.nonight")[i]).attr('readonly', true);




	}

	function disableRows() {
		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		if (len > 1)
		{
			$("SELECT.ctyPickup")[0].selectize.lock();
			for (var i = 0; i < len - 1; i++)
			{
				disableRow(i);
			}
			$("SELECT.ctyPickup")[len - 1].selectize.lock();
			$('#fieldBefore').show();
		}
	}
	function enableRow(i) {
		$("SELECT.ctyDrop")[i].selectize.unlock();
		$($("INPUT.datePickup")[i]).attr('readonly', false);
		$($("INPUT.timePickup")[i]).attr('readonly', false);
		$($("INPUT.datePickup")[i]).datepicker(
				{'autoclose': true, 'startDate': $($("INPUT.datePickup")[i]).attr("min"), 'format': 'dd/mm/yyyy', 'language': 'en'}
		);
		$($("INPUT.nonight")[i]).attr('readonly', false);
		$($("INPUT.noday")[i]).attr('readonly', true);

		$($("INPUT.timePickup")[i]).next("span").show();
	}

	function enableRows() {

		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		if (len > 1)
		{
			enableRow(len - 1);
			$("SELECT.ctyPickup")[len - 1].selectize.lock();
		}
		else {
			$("SELECT.ctyPickup")[len - 1].selectize.unlock();
			$("SELECT.ctyDrop")[len - 1].selectize.unlock();
			$("INPUT.datePickup").attr('readonly', false);
			$("INPUT.timePickup").attr('readonly', false);
			$("INPUT.timePickup").next("span").show();
			var min = new Date($("INPUT.datePickup").attr('min'));
			$("INPUT.datePickup").datepicker(
					{'autoclose': true, 'startDate': min, 'format': 'dd/mm/yyyy', 'language': 'en'}
			);
			$("INPUT.nonight").attr('readonly', false);
//			($("INPUT.noday").attr('readonly', true);
			$('#fieldBefore').hide();
			return false;
		}
	}


	function changeDestination(value, obj, cityId) {

		$loadCityId = cityId;
		if (!value.length)
			return;
		obj.disable();
		obj.clearOptions();
		obj.load(function (callback) {
			//  xhr && xhr.abort();
			xhr = $.ajax({
				// url: '<? //CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist'))                                 ?>/source/' + value,
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>/source/' + value,
				dataType: 'json',
				success: function (results)
				{
					obj.enable();
					callback(results);
					obj.setValue($loadCityId);
				},
				error: function () {
					callback();
				}
			});
		});
	}


	function minutesToStr(minutes) {
		var sign = '';
		if (minutes < 0) {
			sign = '-';
		}

		var hours = Math.floor(Math.abs(minutes) / 60);
		var minutes = Math.abs(minutes) % 60;

		return sign + hours + 'hrs ' + minutes + 'min';
	}


	function calculateDistance(a = 0) {

		var len = $("INPUT.nonight").length;
		var lastNight = $($("INPUT.nonight")[len - 1]).val();
//alert(lastNight+" : "+a);

		if ((lastNight != 0 || lastNight == '') && a == 0)
		{
			alert("Last Night Should Be 0");
			return false;
		}
		else
		{
			var elems = $("SELECT.ctyDrop");
			var len = elems.length;
			//   var daybydt;
			count = len;

			$jsonArrMul = [];
			for (var i = 0; i < len; i++)
			{
				var DayNo = $($("INPUT.noday")[i]).val();
				var night = ($($("INPUT.nonight")[i]).val() == "") ? 0 : $($("INPUT.nonight")[i]).val();

				$jsonArrMul.push({
					"pickup_city": $($("SELECT.ctyPickup")[i]).val(),
					"drop_city": $($("SELECT.ctyDrop")[i]).val(),
					"distance": $($("INPUT.distance")[i]).val(),
					"duration": $($("INPUT.duration")[i]).val(),
					"day": DayNo,
					"night": night,
					"pcd_night_serial": night
				});
			}
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/package/getdistduration')) ?>",
				"data": {"jsonArrMul": $jsonArrMul},
				"async": false,
				"success": function (data)
				{

					var distanceVal = 0;
					for (var i = 0; i < len; i++)
					{
						distanceVal += parseInt($($("INPUT.distance")[i]).val()) | 0;
					}
					var preValDistance = $('#Package_pck_km_included').val() | 0;
					var preValDuration = $('#Package_pck_min_included').val() | 0;
					var valDist = Math.max(preValDistance, distanceVal, data.distance);
					var valDur = Math.max(preValDuration, data.duration);
					$('#Package_pck_km_included').val(valDist);
					$('#Package_pck_km_included').attr('min', distanceVal);
					$('#Package_pck_min_included').val(valDur);
					$('#Package_pck_min_included').attr('min', data.duration);

				}
			});


			$.ajax({
				"type": "GET",
				"dataType": "html",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/package/packageTitle')) ?>",
				"data": {"multijson": JSON.stringify($jsonArrMul)},
				"async": false,
				"success": function (data1)
				{
					data = JSON.parse(data1);
//					alert(data.packageName);
					$("#pck_desc").text(data.packageName);

				}
			});
	}

	}
	$('#calculateDistance').click(function () {

		calculateDistance();
	});

	function changeLocation(value, num)
	{
		//alert(value);
		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		count = len;

		var toID = 'pcd_to_city_' + num;
		var pname = $('#' + toID).parent().find('.selectize-control .items .item').text();
		var key = parseInt($('#' + toID).parent().find('SELECT.ctyDrop').attr('data-key'));
		var id = $($('SELECT.ctyDrop')[key + 1]).parent().find('SELECT').attr('id');
		if (toID != id)
		{
			$($('SELECT.ctyPickup')[key + 1]).parent().find('.ctyPickup.single .items .item').attr('data-value', value);
			$($('SELECT.ctyPickup')[key + 1]).parent().find('.ctyPickup.single .items .item').text(pname);
			$($('SELECT.ctyPickup')[key + 1]).parent().find('.ctyPickup.single .items input[type="text"]').val(value);
			$($('SELECT.ctyPickup')[key + 1]).find('OPTION').val(value);
			$($('SELECT.ctyPickup')[key + 1]).find('OPTION').text(pname);
//			updateDistance(num)
			if (id != undefined) {
				var idnum = id.replace('pcd_to_city_', '');

				updateDistance(idnum);
			}
		}

	}
	function updateDistance(num) {

		var fcity = $('#pcd_from_city_' + num).val();
		var tcity = $('#pcd_to_city_' + num).val();
		var objCity = new City();
		if (fcity > 0 && tcity > 0) {
			objCity.getRouteDetailsBetweenCity(fcity, tcity, function (data)
			{
				var routeDetails = data;
				var duration = (parseInt(routeDetails.duration) > 0 ? parseInt(routeDetails.duration) : 0);
				var distance = (parseInt(routeDetails.distance) > 0 ? parseInt(routeDetails.distance) : 0);
//			alert(duration);

				$('#PackageDetails_pcd_trip_distance_' + num).val(distance);
				$('#PackageDetails_pcd_trip_distance_' + num).attr('min', distance);

				$('#PackageDetails_pcd_trip_duration_' + num).val(duration);
				$('#PackageDetails_pcd_trip_duration_' + num).attr('min', duration);


				var elems = $("INPUT.distance");
				var len = elems.length;
				var distanceVal = 0;
				for (var i = 0; i < len; i++)
				{
					distanceVal += parseInt($($("INPUT.distance")[i]).val()) | 0;
				}
				var preValDistance = $('#Package_pck_km_included').val() | 0; 
				var valDist = Math.max(preValDistance, distanceVal); 
				$('#Package_pck_km_included').val(valDist).change();
				$('#Package_pck_km_included').attr('min', distanceVal);
//				$('#Package_pck_min_included').val(duration);
//				$('#Package_pck_min_included').attr('min', duration);

			});
			calculateDistance(1);
		}

	}
	$('#Package_pck_km_included').change(function () {
		var val = $('#Package_pck_km_included').val();
		$('#Package_pck_km_included').attr('value', val);
	});


</script>  
