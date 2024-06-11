<?php
$request = Yii::app()->request;
if ($request->cookies->contains('itineraryCookie'))
{ 
   // echo"<pre>";
   // $var = Yii::app()->request->cookies['itineraryCookie']->value;
   // print_r($var->source->city->id);
   // echo"</pre>";
   
}
/** @var BookFormRequest $objPage */
$objPage	 = ($pageRequest!="")?$pageRequest:$this->pageRequest;
/** @var Stub\common\Booking $objBooking */
$objBooking	 = $objPage->booking;

//echo "<pre>";
//$var1 = $objPage->booking->routes;
//$oneway = $var1[0];
//$oneway->source->code;
//echo "</pre>";

$tncIds	 = TncPoints::getTncIdsByStep($step);
$tncArr	 = TncPoints::getTypeContent($tncIds);

if ($model == null)
{
	$model = $this->pageRequest->booking->getLeadModel();
}
?>
<?php
/** @var BookingTemp $model */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingItinerary',
	'enableClientValidation' => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL(['booking/itinerary', "bkgType" => $model->bkg_booking_type]),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
?>
<?php echo $form->hiddenField($model, 'bkg_booking_type'); ?>
<?php //echo $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id1', 'class' => 'clsBkgID']); ?>
<?php echo $form->hiddenField($model, 'hash', ['id' => 'hash1', 'class' => 'clsHash']); ?>
<?php echo $form->hiddenField($model, 'bkg_package_id', ['id' => 'bkg_package_id1']); ?>
<?php echo $form->hiddenField($model, 'bktyp', ['value' => 0, 'id' => 'bktyp1']); ?>
<?php echo $form->hiddenField($model, 'stepOver'); ?>
<?php echo $form->hiddenField($model, 'bkg_transfer_type'); ?>
<input type="hidden" name="step" value="<?= $step ?>">
<input type="hidden" name="userPhone" value="<?= $phone ?>">
<input type="hidden" name="rid" value="<?= $rid; ?>" id="rid">

<?php
echo $form->errorSummary($model, NULL, NULL, ['class' => 'mt10 errorSummary alert alert-danger mb-2']);
foreach($model->bookingRoutes as $key=>$brtR){
if($key==0){
continue;
}
echo $form->errorSummary($brtR, NULL, NULL, ['class' => 'mt10 errorSummary alert alert-danger mb-2']);
}
?>
<div id="error_div" style="display:none" class="mt10 alert alert-block alert-danger"></div>
<div id="bkgItinerary">
	<?php
	if ($model->bkg_booking_type != 5) // Package
	{
		Logger::profile("Initiating additinerary view");
		$brtFromCityId	 = 0;
		$brtToCityId	 = 0;
		if (trim($model->bkg_route_data) == '')
		{
			$model->bkg_route_data = 0; // For ajax load only
		}
		$brtReturn	 = clone($model);
		$brtRoutes	 = $model->bookingRoutes;

		if ($model->bkg_booking_type == 2)
		{
			$brtRoutes[0]->brt_return_date_date	 = $brtReturn['bookingRoutes'][0]->brt_return_date_date;
			$brtRoutes[0]->brt_return_date_time	 = '10:00PM'; // return time always 10 pm
			if($brtRoutes[0]->brt_return_date_date == null && $brtRoutes[1]->brt_pickup_datetime!=null){
		       $returnDate	 = date('Y-m-d H:i:s', strtotime($brtRoutes[1]->brt_pickup_datetime . ' +' . $brtRoutes[1]->brt_trip_duration . ' minute'));
               $brtRoutes[0]->brt_return_date_date  = DateTimeFormat::DateTimeToDatePicker($returnDate);
			   $brtRoutes[0]->brt_return_date_time  = DateTimeFormat::DateTimeToTimePicker($returnDate);
			}
		}

		if (count($brtRoutes) == 0)
		{
			goto skipRoutes;
		}
		//$brtRoute = array_pop(array_values($brtRoutes));
		$brtRoute = $brtRoutes[0];
		DateTimeFormat::concatDateTime($brtRoute->brt_pickup_date_date, $brtRoute->brt_pickup_date_time, $pickupTime);
		$pickupDate = strtotime($pickupTime);	
		$currentDateTime = Filter::getDBDateTime();
		$curDate = strtotime($currentDateTime);
		if($pickupDate < $curDate)
		{
			$brtRoute->brt_pickup_date_date = date('d/m/Y', $curDate + 86400);
		}

		if ($model->bkg_booking_type == 4 || $model->bkg_booking_type == 1 || $model->bkg_booking_type == 14)
		{
			$brtFromCityId	 = $brtRoute->brt_from_city_id;
			$brtToCityId	 = $brtRoute->brt_to_city_id;
		}
		$form->error($brtRoute, 'brt_from_city_id');
		$form->error($brtRoute, 'brt_to_city_id');
		$form->error($brtRoute, 'brt_pickup_date_date');
		$form->error($brtRoute, 'brt_pickup_date_time');
		if($model->bkg_id==null && $model->bkg_booking_type==3)
		{
			$brtRoute->brt_to_city_id = null;
		}

		$this->renderFile($this->getViewFile('additinerary'), ['model' => $brtRoute, 'bmodel' => $model, 'sourceCity' => '', 'previousCity' => '', 'btype' => $model->bkg_booking_type, 'index' => 0, 'bkgTempModel' => $model, 'form' => $form, 'tncArr' => $tncArr,'isAgent'=>$isAgent,'cookieActive'=>true]);
		Logger::profile("additinerary view rendered");
		skipRoutes:
	}
	?>
</div>
<?php
if ($model->bkg_booking_type == 3)
{
	?>
	<div class="row">
		<div class="col-12 mt30">
			<div class="row cc-2 m0">
				<div class="col-12">
<?php } ?>
				<div class="row justify-center">
					<div class="<?= ($model->bkg_booking_type == 2) ? 'col-md-2' : 'col-md-6' ?> text-center mb10 ml0 mr0 <?= ($model->bkg_booking_type == 3) ? 'bkRouteNxtBtn' : '' ?>" >
						<?php
						if ($model->bkg_booking_type == 3)
						{
							?>
							<?= CHtml::submitButton('Thats my journey plan. Next step', array('class' => 'btn btn-primary devbtnstep2', 'id' => 'multiwaybtn')); ?>
<?php } ?>
					</div>
				</div>
				<input type="hidden" name="pageID" id="pageID" value="6">
				<?php $this->endWidget(); ?>
				<?php
				if ($model->bkg_booking_type == 3)
				{
					?>
<!--					<div class="row">
						<div class="col-12 col-xl-6 offset-xl-3">
							<div class="row">
								<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
								<div class="col-10 col-lg-10 d-lg-none d-xl-none"><marquee class="cabcontent" direction="up" height="50px" scrollamount="1"></marquee></div>
								<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"></div>
							</div>
						</div>
					</div>-->
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<script type="text/javascript">
    
	var hyperModel = null;
	$sourceList = null;
	
	$skipLogin = parseInt('<?php echo (json_decode(Config::get('quote.guest'))->enabled==1)?0:2;?>');
	$(document).ready(function()
	{	
		$jsBooking = new Booking();
		hyperModel = new HyperLocation();
		step = <?= $step ?>;
		tabURL = "<?= Filter::addGLParam($this->getURL($this->pageRequest->getItineraryURLParams())) ?>";
		tabHead = "<?= $this->pageRequest->getBkgTypeDesc() ?>";
		pageTitle = "aaocab: " + tabHead;
		
		
		
		toggleStep(step, 4, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
		$("form#bookingItinerary").unbind("submit").on("submit", function()
		{
			checkCatQuotes();
			return false;
		}); 
		showBack();
		bindFocus();
   
<?php
if ($sdata != '')
{
	echo "setData('" . mysql_escape_string($sdata) . "');";
}
?>

	});
	$(window).on("load", function(){
			bindFocus();
	});
	
	function populateAirportList(obj, cityId)
	{
     // debugger;
      
		obj.load(function(callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					//  async: false,
					success: function(results)
					{
                        var airport ='<?= $brtRoute->airport ?>';
                        if($("#cityByCookie").val() != '')
                        {
                            airport =$("#cityByCookie").val();
                        }
						$sourceList = results;
						obj.enable();
						callback($sourceList);
                     //   alert('<?= $brtRoute->airport ?>');
						obj.setValue(airport);
					},
					error: function()
					{
						callback();
					}
				});
			}
			else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue('<?= $brtRoute->airport ?>');
			}
		});
	}

	function checkCatQuotes(phone = null)
	{	
		$("#error_div").html("");
		$("#error_div").hide();
		$('#bktyp1').val(3);
		if(phone!=undefined && phone != null)
		{
			$('INPUT[name=userPhone]').val(phone.phone);
		}
		var currFromCtyId = $('SELECT.ctyPickup').val();
		var currToCtyId = $('SELECT.ctyDrop').val();

		if ($(".datePickup").val() == '' || $(".timePickup").val() == '')
		{
			$("#error_div").html("Please select pickup date/time");
			$("#error_div").show();
			return false;
		}
		var form = $("form#bookingItinerary");
		
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/itinerary')) ?>",
			"data": form.serialize()+ '&skipLogin=' + $skipLogin,
			"beforeSend": function()
			{
				blockForm(form);
			},
			"complete": function()
			{
				unBlockForm(form);
			},
			"success": function(data2)
			{	
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				}
				catch (e)
				{

				}
				if (!isJSON)
				{
					$("#tab6").html(data2);
				}
				else
				{
					if (data.success)
					{
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					msg = JSON.stringify(errors);
					messages = errors;
					
					displayFormError(form, messages);
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403" || xhr.status == "401")
				{
					showLogin(function(returnVal)
					{
						checkCatQuotes(returnVal);
					}, 1);
				}
			}
		});
		return false;
	}

	function loadAirportSource(query, callback)
	{
		//debugger;
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			error: function()
			{
				callback();
			},
			success: function(res)
			{
				callback(res);
			}
		});
	}

 function populateCityList(obj, cityId)
	{
		obj.load(function(callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				
				xhr = $.ajax({
			       url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					success: function(results)
					{
						
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue('<?= $brtRoute->airport ?>');
					},
					error: function()
					{
						callback();
					}
				});
			}
			else
			{
				
				obj.enable();
				callback($sourceList);
				obj.setValue('<?= $brtRoute->airport ?>');
			}
		});
	}
 function loadSource (query, callback)
    {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    };
	
	function populateRailwayList(obj, cityId)
	{	
		obj.load(function(callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getrailwaybuscities')) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					//  async: false,
					success: function(results)
					{
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue('<?= $brtRoute->railway ?>');
					},
					error: function()
					{
						callback();
					}
				});
			}
			else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue('<?= $brtRoute->railway ?>');
			}
		});
	}
	
	function loadRailwaySource(query, callback)
	{	
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getrailwaybuscities')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			error: function()
			{
				callback();
			},
			success: function(res)
			{
				callback(res);
			}
		});
	}
</script>