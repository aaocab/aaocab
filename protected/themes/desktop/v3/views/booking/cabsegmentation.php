<?php
$tncType = TncPoints::getTncIdsByStep($step);
$tncArr	 = TncPoints::getTypeContent($tncType);
$form	 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabSegment',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/tripType'),
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'onSubmit'	 => 'return selectTripType(this);'
	),
		));
/* @var $form CActiveForm */

$cabSegmentation = $this->pageRequest->tripCategory;
?>
<div class="menuTripType">
	<div class="alert alert-danger mb-2 text-center hide alertcab" role="alert"></div>

	<div class="row">
		<div class="col-12 text-center style-widget-1"><p class="merriw heading-line">Do you need a cab for local travel or outstation travel?</p></div>
		<div class="col-12 col-lg-8 offset-lg-2">
			<div class="row mb-2 radio-style6 justify-center">
				<div class="col-12 col-md-5 col-lg-4">
					<div class="widget-content-box">
						<img src="/images/img-2022/g-icon-1.png" class="img-fluid img-no" alt="">
						<div class="radio mt5">
							<div class="mb-0 label-text">Local travel</div>
							<input id="cabsegmentation_0" value="1" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
							<label for="cabsegmentation_0"></label>
							<input type="hidden" id="contenttype" value="85">
						</div>
					</div>
				</div>
				<div class="col-12 col-md-5 col-lg-4 radio">
					<div class="widget-content-box">
						<img src="/images/img-2022/g-icon-2.png" class="img-fluid img-no" alt="">
						<div class="radio mt5">
							<div class="mb-0 label-text">Outstation travel</div>
							<input id="cabsegmentation_1" value="2" type="radio" name="cabsegmentation" class="bkg_user_trip_type">
							<label for="cabsegmentation_1"></label>
							<input type="hidden" id="contenttype" value="84">
						</div>
					</div>
				</div>
			</div>
		</div>
<!--<div class="col-12 tab-view">
	<ul class="nav nav-tabs justify-content-center pl5 text-center" role="tablist">
		<li class="nav-item">
			<a class="nav-link" id="home-tab-center" data-toggle="tab" href="#home-center" aria-controls="home-center" role="tab" aria-selected="true">
				Local travel
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active" id="service-tab-center" data-toggle="tab" href="#service-center" aria-controls="service-center" role="tab" aria-selected="false">
				Outstation travel
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane" id="home-center" aria-labelledby="home-tab-center" role="tabpanel">
			<div class="row mb-2 radio-style6 justify-center">
				<div class="col-12 col-md-4 col-lg-2 ui-facetune">
					<div class="ui-box d-flex">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="#">
								<img src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no">
								<div class="ui-text-facetune mt5">
									<div class="mb-0">Pick-up from airport</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="#"><i class='bx bx-info-circle font-30'></i></a></div>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-2 ui-facetune">
					<div class="ui-box d-flex">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="#">
								<img src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no">
								<div class="ui-text-facetune mt5">
									<div class="mb-0">Drop-off to airport</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="#"><i class='bx bx-info-circle font-30'></i></a></div>
					</div>
				</div>
				<div class="col-12 col-lg-3 col-md-3 ui-facetune">
					<div class="ui-box d-flex">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="#">
								<img src="/images/img-2022/g-icon-5.png" alt="" class="img-fluid img-no">
								<div class="ui-text-facetune mt5">
									<div class="mb-0">Daily Rental on hourly basis</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="#"><i class='bx bx-info-circle font-30'></i></a></div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane active" id="service-center" aria-labelledby="service-tab-center" role="tabpanel">
			<div class="row mb-2 radio-style6 justify-center">
				<div class="col-12 col-md-4 col-lg-2">
					<div class="ui-box d-flex">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="#">
								<img src="/images/img-2022/g-icon-7.png" alt="" class="img-fluid img-no">
								<div class="ui-text-facetune mt5">
									<div class="mb-0">One-way trip</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-info-circle font-30'></i></a></div>
					</div>
</div>
				<div class="col-12 col-md-4 col-lg-2">
<div class="ui-box d-flex">
					<div class="ui-inner-facetune flex-grow-1">
						<a href="#">
							<img src="/images/img-2022/g-icon-8.png" alt="" class="img-fluid img-no">
							<div class="ui-text-facetune mt5">
								<div class="mb-0">Round trip</div>
							</div>
						</a>
					</div>
<div class="face-info"><a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-info-circle font-30'></i></a></div>
</div>
				</div>
				<div class="col-12 col-md-4 col-lg-2">
<div class="ui-box d-flex">
					<div class="ui-inner-facetune flex-grow-1">
						<a href="#">
							<img src="/images/img-2022/g-icon-6.png" alt="" class="img-fluid img-no">
							<div class="ui-text-facetune mt5">
								<div class="mb-0">Multi-city multi-day trip</div>
							</div>
						</a>
					</div>
<div class="face-info"><a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-info-circle font-30'></i></a></div>
</div>
				</div>
				<div class="col-12 col-md-4 col-lg-2">
<div class="ui-box d-flex">
					<div class="ui-inner-facetune flex-grow-1">
						<a href="#">
							<img src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no">
							<div class="ui-text-facetune mt5">
								<div class="mb-0">Pick-up from airport</div>
							</div>
						</a>
					</div>
<div class="face-info"><a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-info-circle font-30'></i></a></div>
</div>
				</div>
				<div class="col-12 col-md-4 col-lg-2">
<div class="ui-box d-flex">
					<div class="ui-inner-facetune flex-grow-1">
						<a href="#">
							<img src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no">
							<div class="ui-text-facetune mt5">
								<div class="mb-0">Drop-off to airport</div>
							</div>
						</a>
					</div>
<div class="face-info"><a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-info-circle font-30'></i></a></div>
</div>
				</div>
			</div>
		</div>
	</div>
</div>-->
		<div class="col-12 cc-1">
			<div class="row m0 cc-2">
				<div class="col-12 text-center mb-1">
					<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
					<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
					<input type="submit" value="Next" name="yt0" id="cabsegmentation" class="btn btn-primary pl-5 pr-5">
					<input type="hidden" name="pageID" value="4" id="pageID">
					<input type="hidden" name="rid" value="<?= $rid; ?>" id="rid">
				</div>
				<div class="col-12 col-xl-8 offset-xl-2">
					<div class="row mb-1">
						<div class="col-2 col-lg-2"><div class="round-2 hide"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
						<div class="col-10 col-lg-10 cabsegmentation mt5"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
<script>
	$(document).ready(function()
	{	
		step = <?= $step ?>;
		tabURL = "<?= $this->getURL("booking/tripType") ?>";
		tabHead = "<?= $this->pageRequest->getTripTypeDesc() ?>";
		pageTitle = "aaocab: " + tabHead;
		toggleStep(step, 1, tabURL, pageTitle, tabHead, false, <?= $this->pageRequest->step ?>);
		selectTripCategory('<?= ($cabSegmentation) ?>');
		showBack();
	});

	function selectTripCategory(value = '')
	{	
		if (value != null && value != '')
		{
			var elem = $('#cabsegmentation_' + value);
			if (elem.length > 0)
			{
				elem.click();
			}
	}
	}

	$('input[type=radio][name=cabsegmentation]').change(function()
	{
		$(".alertcab").html('');
		$(".alertcab").hide();
		var tncval = JSON.parse('<?= $tncArr ?>');
		var val = $("input[name='cabsegmentation']:checked").val();

		var contentType = $(this).closest('div').find(':hidden');
		var defaultVal = contentType[1].value;
		$('.cabsegmentation').html(tncval[defaultVal]);
		$('.round-2').removeClass('hide');
	});

	$('#cabsegmentation').unbind("click").on('click', function()
	{
		if ($('input[name="cabsegmentation"]:checked').length == 0)
		{
			$(".alertcab").html('Please Choose atleast one');
			$(".alertcab").show();
		}
		else
		{
			$('#cabSegment').submit();
		}
		return false;
	});

	function selectTripType(obj)
	{
		var form = $(obj);
		var url = form.prop("action");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": url,
			"data": form.serialize(),
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
					$("#tab5").html(data2);
				}
				else
				{
					if (data.success)
					{
						$(".correctotp").hide();
						location.href = data.data.url;
						return;
					}
					else
					{
						var msg = "<ul class='m-0'>";
						data.errors.forEach(function(val)
						{
							msg += "<li>" + val + "</li>";
						});
						msg += "</ul>";
						$(".correctotp").html(msg);
						$(".correctotp").show();
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				var msg = "<ul class='list-style-circle'><li>" + xhr.status + ": " + thrownError + "</li></ul>";
				$(".correctotp").html(msg);
				$(".correctotp").show();
			}
		});
		return false;
	}
</script>

