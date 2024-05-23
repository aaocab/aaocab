<?php
$version = Yii::app()->params['siteJSVersion'];
$cabtype = ($cabtype == null) ? $this->pageRequest->tripCategory : $cabtype;
$tncType = TncPoints::getTncIdsByStep($step);
$tncArr	 = TncPoints::getTypeContent($tncType);
$tncArr1 = json_decode($tncArr, true);
$form	 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingTrip1',
	'enableClientValidation' => true,
	'stateful'				 => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL(['booking/bkgType', "cabsegmentation" => $cabtype]),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
/* @var $form CActiveForm */

// Element Name & Id
$tripTypeElemName = CHtml::activeName($model, "bkg_booking_type");

$tripTypeElemId = CHtml::activeId($model, "bkg_booking_type");

// Booking Type
$bkgtype = $model->bkg_booking_type;

// Active Class
$arrActive			 = array_fill(1, 4, '');
$arrActive[$bkgtype] = 'active';

$cabservice = ($cabtype == 1) ? 'local' : 'outstation';

?>
<?php
echo $form->errorSummary($model, NULL, NULL, ['class' => 'mt10 errorSummary alert alert-danger mb-2']);
?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id11', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash11', 'class' => 'clsHash']); ?>
<?= $form->hiddenField($model, 'bkg_transfer_type'); ?>
<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
<input type="hidden" name="step" value="<?= $step ?>">
<input type="hidden" name="cabType" value="<?= $cabType ?>">
<input type="hidden" name="cabsegmentation" id="cabsegmentation" value="<?= $cabtype ?>">
<div class="row">
	<div class="col-12">
		<div class="alert alert-danger mb-2 text-center hide alertsericetype" role="alert"></div>
		<div class="menuTripType">
			<div class="row">
				<div class="col-12 tab-view">
					<ul class="nav nav-tabs justify-content-center pl10 text-center d-flex" role="tablist">
						<li class="nav-item mr0 flex-fill">
							<a class="cabsegmentation nav-link text-center <?php echo ($cabtype == 1) ? 'active' : '' ?>"  id="local-tab-center" data-value="1" data-toggle="tab" href="#local-center" aria-controls="local-center" role="tab" aria-selected="true">
								Local
							</a>
						</li>
						<li class="nav-item mr0 flex-fill">
							<a class="cabsegmentation nav-link text-center <?php echo ($cabtype == 2) ? 'active' : '' ?>" id="outstation-tab-center" data-value="2" data-toggle="tab" href="#outstation-center" aria-controls="outstation-center" role="tab" aria-selected="false">
								Outstation
							</a>
						</li>
						<li class="nav-item mr0 flex-fill">
							<a class="cabsegmentation nav-link text-center <?php echo ($cabtype == 3) ? 'active' : '' ?>" id="airport-tab-center" data-value="3" data-toggle="tab" href="#airport-center" aria-controls="airport-center" role="tab" aria-selected="false">
								Airport
							</a>
						</li>
					</ul>
					<div class="tab-content pl0">
						<div class="tab-pane <?php echo ($cabtype == 1) ? 'active' : '' ?>" id="local-center" aria-labelledby="local-tab-center" role="tabpanel">
							<div class="row mb-2 radio-style6 justify-center">
<!--								<div class="col-12 col-md-4 col-lg-3 ui-facetune">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('14_1')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/point-to-point2.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Point to point (within-the-city)</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[92] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>-->
								<div class="col-12 col-lg-3 col-md-3 ui-facetune">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('10')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-5.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Daily Rental on hourly basis</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[66] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3 ui-facetune">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_1')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Pick-up from airport</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[64] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3 ui-facetune">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_2')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Drop-off to airport</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[65] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>

								
<!--								<div class="col-12 col-md-4 col-lg-2 ui-facetune p5">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('15_1')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-10.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune mt5">
													<div class="mb-0">Pick-up from Railway/Bus Terminal</div>
							</div>
											</a>
						</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[96] ?>"><i class='bx bx-info-circle font-30'></i></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-2 ui-facetune p5">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('15_2')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-11.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune mt5">
													<div class="mb-0">Drop-off to Railway/Bus Terminal</div>
												</div>
											</a>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[97] ?>"><i class='bx bx-info-circle font-30'></i></a></div>
									</div>
								</div>-->
							</div>
						</div>
						<div class="tab-pane <?php echo ($cabtype == 2) ? 'active' : '' ?>" id="outstation-center" aria-labelledby="outstation-tab-center" role="tabpanel">
							<div class="row mb-2 radio-style6 justify-center">
								<div class="col-12 col-md-4 col-lg-3">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('1')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-7.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">One-way trip</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[61] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('2')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-8.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Round trip</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[63] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1"  onclick="submitServiceType('3')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-6.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Multi-city multi-day trip</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[62] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane <?php echo ($cabtype == 3) ? 'active' : '' ?>" id="airport-center" aria-labelledby="airport-tab-center" role="tabpanel">
							<div class="row mb-2 radio-style6 justify-center">
								<div class="col-12 col-md-4 col-lg-3 ui-facetune">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_1')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Pick-up from airport (Local)</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[64] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3 ui-facetune">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_2')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Drop-off to airport (Local)</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[65] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1"  onclick="submitServiceType('1_1')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Pick-up from airport (Outstation)</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[82] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
								<div class="col-12 col-md-4 col-lg-3">
									<div class="ui-box d-flex">
										<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('1_2')">
											<a href="javascript:void(0);">
												<img src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no">
												<div class="ui-text-facetune">
													<div class="mb-0">Drop-off to airport (Outstation)</div>
												</div>
											</a>
<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
										</div>
										<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $tncArr1[83] ?>"><img src="/images/bx-info-circle.svg" alt="img" width="30" height="30"></a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

<!--				<div class="col-12 text-center"><a href="https://www.gozocabs.com/terms/doubleback" target="_blank"><img src="/images/double-back-banner.webp?v=0.3" alt="Img" class="img-fluid"></a></div>-->

				<div class="col-12 cc-3">
					<div class="row m0 cc-2 justify-center">
						<div class="col-xl-12 text-center">
							<input type="hidden" name="pageID" value="5" id="pageID">
							<input type="hidden" name="rid" value="<?= $rid; ?>" id="rid">
						</div>

					</div>
				</div>


			</div>
		</div>

	</div>
</div>


<?php $this->endWidget(); ?>
<script type="text/javascript">

	$(document).ready(function()
	{
		step = <?= $step ?>;
		tabURL = "<?= Filter::addGLParam($this->getURL(["booking/bkgType", "cabsegmentation" => $cabtype]))?>";
		tabHead = "<?= $this->pageRequest->getTripTypeDesc() ?>";
		pageTitle = "Gozocabs: " + tabHead;
		toggleStep(step, 1, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
		showBack();
<?php
if ($sdata != '')
{
	echo "setData('" . $sdata . "');";
}
?>
	});
	function submitServiceType(transfertype)
	{
       // alert(transfertype);
		if (transfertype.indexOf('_') > -1)
		{
			arrytrnsfrtype = transfertype.split('_');
			$('#BookingTemp_bkg_transfer_type').val(arrytrnsfrtype[1]);
		}
		$('#BookingTemp_bkg_booking_type').val(transfertype);
		$(".alertsericetype").html('');
		$(".alertsericetype").hide();
		checkItinerary();
		return false;
	}
	function checkItinerary()
	{
		
		var form = $("form#bookingTrip1");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/bkgType')) ?>",
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
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					messages = errors;
					displayError(form, messages);
				}
			},
			error: function(xhr, ajaxOptions, thrownError)
			{
				handleException(xhr);
			}
		});
	}

	$(".cabsegmentation").click(function()
	{
		var currvalue = $(this).data('value');
		$("#cabsegmentation").val(currvalue).trigger('change');
		$("input[type=hidden][name='cabType']").val(currvalue).trigger('change');
	});
</script>
