<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
#$shuttles				 = $model->getQuote(null, true);
/* @var $model BookingTemp */




//$cabData = VehicleCategory::model()->getList($type = 'list');
$cabData = SvcClassVhcCat::model()->getVctSvcList('allDetail');


/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'cabrate-form1',
	'enableClientValidation' => true,
	'clientOptions'			 => array(),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off'
	),
		));
?>

<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash', 'value' => $model->getHash()]); ?> 
<?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
<?= $form->hiddenField($model, "bkg_trip_distance"); ?>
<?= $form->hiddenField($model, "bkg_trip_duration"); ?> 
<?= $form->hiddenField($model, "bkg_shuttle_id"); ?> 
<?= $form->hiddenField($model, "bkg_shuttle_seat_count"); ?> 

<input type="hidden" id="step2" name="step" value="2">


<div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="route-page1">

	<div class="content-padding content-boxed-widget mb10 p15 list-view-panel">
		<h2 class="mb0">
			<?php
			echo $shuttles[0]['fromCity'] . ' &rarr; ' . $shuttles[0]['toCity'];
			?> </h2>
		<?php
		if ($shuttles)
		{
			?>
			Estimated Distance: <b> <?= $model->bkg_trip_distance . " Km" ?></b>,
			Estimated Time: <b><?= Filter::getDurationbyMinute($model->bkg_trip_duration) ?> (+/- 30 mins for traffic)</b>
			<?php
		}
		else
		{
			?>
			<br/><p><b>Sorry cab is not available for this route.</b></p>
		<?php } ?>
		<!--<h5 class="hide">If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>-->

	</div>
	<?php
	$i = 0;
	$j = 0;
	foreach ($shuttles as $key => $shuttle)
	{
		$j++;
		$cab			 = $cabData[$shuttle['vht_car_type']];
		$svcId			 = 1;
		$vctId			 = $cab['vct_id'];
		$data			 = SvcClassVhcCat::getVctSvcList('detail', $svcId, $vctId);
		$vhtId			 = $data['scv_id'];
		// Fare Breakup Tooltip
		//$details		 = $this->renderPartial("bkFareShuttle", ['shuttle' => $shuttle], true);
		$tolltax_value	 = 1;
		$tolltax_flag	 = 1;
		$statetax_value	 = $shuttle['slt_toll_tax'] | 0;
		$statetax_flag	 = $shuttle['slt_state_tax'] | 0;

		if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
		{
			$taxStr = 'Toll Tax and State Tax included';
		}
		else if ($tolltax_flag == 0 && $statetax_flag == 0)
		{
			$taxStr = 'Toll and State taxes extra as applicable';
		}
		// Fare Breakup Tooltip
		?>

		<div class="content-boxed-widget p0">
				<div class="content-padding p15 pb0 pt10 font-18 uppercase"><b><?= $cab['vct_label'] ?></b></div>
				<div class="content-padding p15 pb0 pt0">Shuttle #<b>ST<?= $shuttle['slt_id'] ?></b></div>
				<div class="text-left"> </div>
				<div class="one-third mt10 p5 bottom-50"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt="" class="preload-image responsive-image mb0"></div>
			<div class="mt10 font-icon-list font-11">
				<ul class="bottom-15">
					<li class="line-height16"><img src="/images/team.svg" width="15" alt="Seats + Driver" class="inline-block mr5"><?= $cab['vct_capacity'] ?> Seats + Driver</li>
					<li class="line-height16"><img src="/images/briefcase.svg" width="15" alt="Seats + Driver" class="inline-block mr5"> 1 Small bag</li>
					<li class="line-height16"><img src="/images/air-conditioner.svg" width="15" alt="Seats + Driver" class="inline-block mr5">AC</li>
					<li class="line-height16"><img src="/images/speedometer.svg" width="15" alt="Seats + Driver" class="inline-block mr5"> <span class="font11"><?= $taxStr ?></span></li>
				</ul>
			</div>
			<div class="content p15 bottom-0 bg-blue-dark">
				<div class="one-half">Departure Time:</div>
				<div class="one-half last-column text-right"><b><?= DateTimeFormat::DateTimeToLocale($shuttle['slt_pickup_datetime']) ?> </b></div>
				<div class="one-half">Pickup Point:</div>
				<div class="one-half last-column text-right"><?= $shuttle['slt_pickup_location'] ?>, <?= $shuttle['fromCity'] ?></div>
				<div class="one-half">Drop Point:</div>
				<div class="one-half last-column text-right"><?= $shuttle['slt_drop_location'] ?>, <?= $shuttle['toCity'] ?></div>
				<div class="one-half">Seat Available:</div>
				<div class="one-half last-column text-right"><?= $shuttle['available_seat'] ?></div>
				<div class="clear"></div>
			</div>


<!--			<div class="content text-center top-20 bottom-10">
				<b class="font-30"><span>&#x20B9</span><?//= $shuttle['slt_price_per_seat'] ?></b><sup class="font-16">*</sup>
				<a data-toggle="popover" data-placement="top" data-html="true" data-content="<?//= $details ?>" style="font-size:18px;" class="inline-block">
					<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fair Breakup" data-placement="botton"></i>
				</a>
			</div>-->

			<div class="content text-center top-20 bottom-10">
				<b class="font-30"><span>&#x20B9</span><?= $shuttle['slt_price_per_seat'] ?></b><sup class="font-16">*</sup>
			</div>

			<div class="accordion accordion-style-1">
				<div class="accordion-path">
					<div class="accordion accordion-style-0 box-text-7">
						<a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-1<?= $j ?>">  Detailed fare breakup<i class="fa fa-plus"></i></a>
						<div class="accordion-content" id="accordion-1<?= $j ?>" style="display: none;">
							<div class="accordion-text">
								<?php echo $this->renderPartial("bkFareShuttle", ['shuttle' => $shuttle], true); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="content text-center pb20">
				<button type="button" 
						value="<?= $vhtId ?>" 
						id="btn_<?= $shuttle['slt_id'] ?>"
						shuttleid="<?= $shuttle['slt_id'] ?>" 
						seat_count ="1"   
						kms="<?= $shuttle['trip_distance'] ?>" 
						duration="<?= $shuttle['trip_duration'] ?>" 
						name="bookButton" class="uppercase btn-green p15 pl20 pr20 mr5 font-14" onclick="validateForm1(this);">
					<b>Book a seat</b> 
				</button>
			</div>
		</div>
		<?php
	}
	?>
</div>

<?php $this->endWidget(); ?>
<script>
    $bkgId = '<?= $model->bkg_id ?>';
    $hash = '<?= $model->getHash() ?>';
    var bookNow = new BookNow();
    var data = {};
    $(document).ready(function ()
    {
        bookNow.bkQuoteReady($bkgId, $hash);
    });
    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>', strtotime($model->bkg_pickup_date)) ?>');
    function validateForm1(obj)
    {
        var shuttleid = $(obj).attr('shuttleid');
        validateFormShuttle(obj);
        data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
        data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
        data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
        data.bkgShuttle = shuttleid;
        bookNow.data = data;
        bookNow.validateQuote(obj);
    }





    function validateFormShuttle(obj) {
        var shuttleid = $(obj).attr('shuttleid');
        var seat_count = $(obj).attr('seat_count');
        var vhtid = $(obj).attr('value');

        if (seat_count > 0) {
            $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val(vhtid);
            $('#<?= CHtml::activeId($model, "bkg_shuttle_id") ?>').val(shuttleid);
            $('#<?= CHtml::activeId($model, "bkg_shuttle_seat_count") ?>').val(seat_count).change();
//			$('#shuttlebookform').submit();
        }
    }
    function getval($obj) {
        var sid = $obj.id;
        var seat_count = $obj.value;
        var shuttleid = $($obj).attr('sltval');
        var vhtid = $($obj).attr('value');
        if (seat_count > 0) {
            $('.seat_count').prop('selectedIndex', '');
            $('#' + sid).prop('selectedIndex', seat_count);
            $('.sltbtn').addClass('disabled');
            $('.sltbtn').attr('seat_count', 0);
            $('#btn_' + shuttleid).removeClass('disabled');
            $('#btn_' + shuttleid).attr('seat_count', seat_count);
            $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val(vhtid);
            $('#<?= CHtml::activeId($model, "bkg_shuttle_id") ?>').val(shuttleid);
            $('#<?= CHtml::activeId($model, "bkg_shuttle_seat_count") ?>').val(seat_count).change();
        }
    }
</script>
