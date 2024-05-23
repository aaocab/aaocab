
<?php
/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'catTierForm',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL('booking/moreTierQuotes'),
	'htmlOptions'			 => array(
		"onsubmit"		 => "return checkServiceClass();",
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));

$objBooking		 = $this->pageRequest->booking;
$cabCategory	 = $objBooking->cab->categoryId;
$serviceClass	 = $objBooking->cab->cabCategory->scvVehicleServiceClass;
$svcSelectModel	 = SvcClassVhcCat::model()->getVctSvcList('', $serviceClass, $cabCategory, 0);
$modelQuotes	 = $this->pageRequest->sortModels($cabCategory, $serviceClass);
?>

<div class="container mb-2">
	<div class="alert alert-danger mb-2 text-center hide alertcab" role="alert"></div>
	<div class="row">
		<div class="col-12 text-center">
			<p class="merriw heading-line font-24">Choose your specific car model</p>
		</div>
		<div class="col-12 mt-1 mb-2 p0">
			<div class="row">
				<?php
				foreach ($modelQuotes as $rate)
				{
					if($rate->cab->cabCategory->scvVehicleModel > 0)
					{
					?>
					<div class="col-12 mb-1">
						<div class="radio-style4 value-widget">
							<div class="radio">
								<input id="cabmodel<?= $rate->cab->id ?>" value="<?= $rate->cab->id ?>" type="radio" name="cabmodel" class="cabmodel">
								<label for="cabmodel<?= $rate->cab->id ?>" class="d-flex"> <span class="flex-grow-1"><?php echo $rate->cab->cabCategory->scvmodel; ?></span><span class="font-16 ml5 d-flex weight500"><?php echo Filter::moneyFormatter($rate->fare->baseFare); ?></span></label>
							</div>
						</div>
					</div>
				<?php 
					}
					} ?>
			</div>
		</div>
		<div class="col-12 cc-1 justify-center col-xl-8 offset-xl-2 hide">
			<div class="row m0 cc-2">
<!--				<div class="col-2 col-lg-2"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
				<div class="col-10 col-lg-10 cabsegmentation"></div>-->
				<div class="col-12 text-center pb-1 mt-1">

					<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
<!--					<input type="submit" value="Next" name="yt0" id="serviceModelbtn" class="btn btn-primary pl-5 pr-5 servicetypebtn">-->
					<input type="hidden" name="pageID" value="<?= $step ?>" id="pageID">
					<input type="hidden" name="step" value="<?= $pageid ?>">
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
		tabURL = window.location.href;
		pageTitle = "";
		tabHead = "<?= $this->pageRequest->getCabServiceClassDesc() ?>";
		toggleStep(step, 9, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
		$('.scvServiceClass4').change(function()
		{
			$("#scvSclass").val(4);
		});
		$('.scvServiceClass').change(function()
		{
			$("#scvSclass").val("");
		});

	});
	
	$("input[name='cabmodel']").change(function(){
		checkServiceClass();
	});

	function checkServiceClass()
	{	
		if ($('input[name="cabmodel"]:checked').length == 0)
		{
			$(".alertcab").html('Please Choose atleast one');
			$(".alertcab").show();
			return false;
		}

		var form = $("form#catTierForm");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl($this->getURL('booking/moreTierQuotes')) ?>",
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
			{	//debugger;
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
					//$("#tab13").html(data2);
//					$('#bkCommonModelBody').html(data2);
//					$('#bkCommonModel').modal('show');
					$('#myAddressModal .modal-body1').html(data2);
					$('#myAddressModal').modal('show');
					$('#myAddressModal .modal-body1').show();
					$('#myAddressModal .modal-body').hide();
				}
				else
				{
					if (data.success)
					{
						window.sessionStorage.setItem('rdata', data.data.rdata);
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					msg = JSON.stringify(errors);
					settings = form.data('settings');
					$.each(settings.attributes, function(i)
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
			error: function(xhr, ajaxOptions, thrownError)
			{
				handleException(xhr, function(){
					checkServiceClass();
				});
				//alert(xhr.status);
				//alert(thrownError);
			}
		});
		return false;
	}

	function submitModel()
	{


	}
	
</script>