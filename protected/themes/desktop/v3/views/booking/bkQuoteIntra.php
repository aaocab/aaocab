<?php
/** @var BookFormRequest $objPage */
$objPage	 = $this->pageRequest;
/** @var Stub\common\Booking $objBooking */
$objBooking	 = $objPage->booking;

$tncIds				 = TncPoints::getTncIdsByStep($step);
$tncArr				 = TncPoints::getTypeContent($tncIds);
$serviceTypeDesc	 = Config::get('booking.service.type.description');
$objServiceTypeDesc	 = json_decode($serviceTypeDesc);
$objPrefCategory	 = Booking::getPrefferedTripData($this->pageRequest);
$nextStep			 = 13;
/** @var CActiveForm $form */
$form				 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabcategory',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/intraCatQuotes'),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));


?>
<div class="container mb-2">
	<div class="alert alert-danger mb-2 text-center  alertcab" role="alert"></div>
	<div class="col-12 text-center mb-2 style-widget-1"><p class="merriw heading-line">Select your preferred cab </p></div>
	<div class="row" style="display: flex; flex-wrap: wrap; justify-content:center;">
		<?php
//		echo "<pre>";
//print_r($minIntraQuote);

		/** @var Stub\common\CabRate $cabRate */
		foreach ($minIntraQuote as $cabRate)
		{


			$objFare = $cabRate->fare;
			if ($cabRate->discountedFare != null)
			{
				$objFare = $cabRate->discountedFare;
			}


			$cabCategory = $cabRate->cab->cabCategory->scvVehicleId;
			if (!$cabCategory)
			{
				continue;
			}

			$selected = ($selectedValue == $cabCategory) ? "checked" : "";
			if ($firstItem == '')
			{
				$firstItem = $cabCategory;
			}

			$vctModel		 = VehicleCategory::model()->findByPk($cabRate->cab->cabCategory->scvVehicleId);
			?>
			<div class="col-xl-3 col-md-6 col-sm-12 flex2 cb-none ct-1 ct-2">
				<div class="card text-center pt-1">
					
					<span class="text-center mt-3"> <img src="<?= "/" . $vctModel->vct_image ?>" width="150" class="img-fluid" alt="singleminded"></span>
					<div class="card-header text-center p10 pb0" style="display: inline-block;">
						<p class="text-center heading-line-2 mb0 text-uppercase"><?= $cabRate->cab->cabCategory->scvmodel//$vctModel->vct_label ?></p>
					</div>
					<div class="card-body p10 pt0">
						<div class="d-flex mb-1">

						
	<div class="col-12 p0 pt5 pb10"><span class="mr15" data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><?php echo $cabRate->cab->bagCapacity; ?><img src="/images/bxs-shopping-bag.svg" alt="img" width="14" height="14"></span><span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->seatingCapacity . " passengers"; ?>"><?php echo $cabRate->cab->seatingCapacity; ?><img src="/images/bxs-group.svg" alt="img" width="14" height="14"></span>
<img src="/images/bxs-tachometer.svg" alt="img" width="14" height="14" class="ml5"><span><?= $objPage->quote->quotedDistance ?> km</span></i> </div>
						</div>
						<p class="weight400 mb5 lineheight18 bk-docs"><?php echo $objServiceTypeDesc->$cabCategory; ?></p>


						
					<p class="mb0">
									<span class="font-20 weight600">
										<?php echo Filter::moneyFormatter($cabRate->fare->minBaseFare) . ' - ' . Filter::moneyFormatter($cabRate->fare->maxBaseFare); ?>
									</span>
								</p>
					
								<div class="radio-style3">
										<div class="radio">
											<input id="cabcategory<?= $cabCategory ?>" value="<?= $cabCategory ?>" type="radio" name="cabcategory" class="cabcategory">
											<input type="hidden" id="cabclass<?= $cabCategory ?>" name="cabclass<?= $cabCategory ?>" value="<?= $cabRate->cab->cabCategory->scvVehicleServiceClass ?>" class="vehicletype">
											<input type="hidden" id="cabid<?= $cabCategory ?>" name="cabid<?= $cabCategory ?>" value="<?= $cabRate->cab->cabCategory->id ?>" class="">


											<label for="cabcategory<?= $cabCategory ?>"></label>
										</div>
									</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-6 p0 col-sm-12 flex2 cs-none ct-1 ct-4">
				<div class="card mb-2">
					<div class="radio-style8">
						<div class="radio">
							<input id="1cabcategory<?= $cabCategory ?>" value="<?= $cabCategory ?>" type="radio" name="cabcategory" class="">
							<label for="1cabcategory<?= $cabCategory ?>">
								<div class="row m0">
									<div class="col-12 ct-5">
										<p class="heading-line-2 text-uppercase"><?= $vctModel->vct_label ?></p>
									</div>
									
									<div class="col-6 p0"><span class="text-center"> <img src="<?= "/" . $vctModel->vct_image ?>" width="150" class="img-fluid" alt="singleminded"></span></div>





									<div class="col-6 p0 text-right">
<?php echo $cabRate->cab->bagCapacity; ?><img src="/images/bxs-shopping-bag.svg" alt="img" width="13" height="13"></span>&nbsp;&nbsp;<span class="font-13"><?php echo $cabRate->cab->seatingCapacity; ?><img src="/images/bxs-group.svg" alt="img" width="13" height="13"><span class="font-24"></span> <img src="/images/bxs-tachometer.svg" alt="img" width="13" height="13" class="ml5"><?= $objPage->quote->quotedDistance ?> km
										<p class="mb-0 "><span class="font-18 weight600">	<?php echo Filter::moneyFormatter($cabRate->fare->minBaseFare) . ' - ' . Filter::moneyFormatter($cabRate->fare->maxBaseFare); ?></span></p>
									
									
									</div>
								</div>                                                          <div class="col-12 text-center p0 mt-1 mb0"><p class="mb-0 lineheight18 bk-docs"><?php echo $objServiceTypeDesc->$cabCategory; ?></p></div>
							</label>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		<div class="col-12 cc-1">
			<div class="row m0 justify-center cc-2">
				<div class="col-xl-12 text-center">
					<!--				<button type="button" class="btn btn-primary mr-1 mb-1 text-uppercase showcabdetails">NEXT</button>-->
					<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
					<input type="button" value="Go back" step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
					<input type="hidden" name="pageID" id="pageID" value="7">
					<input type="submit" value="Next" name="yt0" id="servicetypebtn" class="btn btn-primary pl-5 pr-5 showcabdetails">
					<input type="hidden" name="step" value="<?= $pageid ?>">
				</div>
				<div class="col-12 col-lg-10 offset-lg-1 mt10">
					<div class="row">
						<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
						<div class="col-10 col-lg-10 d-lg-none d-xl-none"><marquee class="cabcontent" direction="up" height="50px" scrollamount="1"></marquee></div>
						<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="row mb-1">

			</div>
		</div>
	</div>
</div>
<?php

$this->endWidget();

if (!$minIntraQuote)
{
	?>
	<div class="row justify-center">
		<div class="col-12">
			<div class="row mb-1 justify-center">
				<div class="col-12 col-md-6 col-xl-5 text-center mt-2">
					<div class="alert border-primary alert-dismissible mb-2 font-16 line-height24">
						Sorry, there is no cab available for that date and time you have requested. Our contact center team can help plan your trip.
					</div>

				</div>
			</div>
		</div>
		<div class="col-12 col-md-10 col-xl-8 text-center mt-2">
			<a type="button" class="btn btn-primary btn-float font-12 pl10 pr10 hvr-push" onClick="return reqCMB(1)">Tap here to request a call back from our team</a>
		</div>
	</div>
<?php } ?>

<script type="text/javascript">
	$(document).ready(function ()
	{
		$(".alertcab").hide();
		step = <?= $step ?>;
		tabURL = "<?= $this->getURL($objPage->getQuoteURL()) ?>";
		pageTitle = "";
		tabHead = "<?= $this->pageRequest->getItineraryDesc() ?>";
		toggleStep(step, 6, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
<?php
if ($minIntraQuote)
{
	?>
			var isMobile = '<?php echo Yii::app()->mobileDetect->isMobile(); ?>';
			if (isMobile == 1)
			{
				$('#1cabcategory<?= $gozoRecomends ?>').click();
			} else
			{
				$('#cabcategory<?= $gozoRecomends ?>').click();
			}
<?php } ?>
		showBack(null);
	});

	$("input[type=radio][name='cabcategory']").change(function ()
	{
		var val = $("input[name='cabcategory']:checked").val();

		if (val == 1)
		{
			var defaultVal = 74;
		} else if (val == 2)
		{
			var defaultVal = 75;
		} else if (val == 3)
		{
			var defaultVal = 76;
		} else if (val == 4)
		{
			var defaultVal = 77;
		}

		var tncval = JSON.parse('<?= $tncArr ?>');
		$('.cabcontent').html(tncval[defaultVal]);
		$('.roundimage').removeClass('hide');
	});
	$('form#cabcategory').on('submit', function ()
	{
	
		if ($('input[name="cabcategory"]:checked').length == 0)
		{
			$(".alertcab").show();
			$(".alertcab").html('Please Choose atleast one');
			return false;
		}
		checkIntraQuotes();
		return false;
	});

	function checkIntraQuotes()
	{
		
		var form = $("form#cabcategory");
		//alert(form.serialize());
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/intraCatQuotes')) ?>",
			"data": form.serialize(),
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			"success": function (data2)
			{
		
				var data = "";
				var isJSON = false;
				try
				{
			
					data = JSON.parse(data2);
					isJSON = true;
					//window.sessionStorage.setItem('rdata', data.data.rdata);
						location.href = data.url;
						return;
					
				} catch (e)
				{

				}
				if (!isJSON)
				{
					$("#tab<?= $nextStep ?>").html(data2);
				} else
				{
					if (data.success)
					{
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					msg = JSON.stringify(errors);
					settings = form.data('settings');
					$.each(settings.attributes, function (i)
					{
						$.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
					});
					$.fn.yiiactiveform.updateSummary(form, errors);
					messages = errors;
					content = '';
					var summaryAttributes = [];
					for (var i in settings.attributes)
					{
						if (settings.attributes[i].summary)
						{
							summaryAttributes.push(settings.attributes[i].id);
						}
					}
					displayFormError(form, messages);
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				alert(xhr.status);
				alert(thrownError);
			}
		});
		return false;
	}
</script>