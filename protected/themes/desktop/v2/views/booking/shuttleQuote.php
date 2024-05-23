<style type="text/css">
    .next4-btn{
        background: #f2f2f2;    
        text-transform: uppercase; font-size: 12px; font-weight: bold; border: none; padding: 4px 10px; color: #323232; border: #c5c5c5 1px solid;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        transition:all 0.5s ease-in-out 0s;
        -webkit-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        -moz-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
    }
    .next4-btn:hover{ background: #f13016; color: #fff; border: #b72916 1px solid;}
	.next3-btn{

		text-transform: none!important;
	}

    .next5-btn{
        background: #00a388;    
        /*text-transform: uppercase; */
		font-size: 14px; font-weight: bold; border: none; padding: 7px 15px; color: #fff;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        transition:all 0.5s ease-in-out 0s;
        -webkit-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        -moz-box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
        box-shadow: 0px 7px 8px -2px rgba(194,194,194,0.54);
    }
    .next5-btn:hover{ background: #007d68; color: #fff;}
	.popover-content {		
		width:230px;
		font-size: 12px;
		font-family: arial
	}
	.detailTxt{
		text-decoration: none!important;
		cursor: pointer;
		border-bottom: 2px #1a4ea2 dashed}


    .search-cabs-box2{border: #f36c31 2px solid;}
    .search-cabs-box2 .car-style2{ background: #f36c31 url(../images/car_style_right_2.png) top right no-repeat; position: relative; top: 15px; left: -15px; color: #fff; font-size: 11px; font-weight: bold; padding: 5px 25px 5px 10px; display: table;}
	.subbtn{
		font-size: 0.75em!important;
	}
	.proceed-make-btn{
		display: none;
	}
	.wrap-panel{ font-size: 12px; color: #fff; line-height: 18px; text-align: right; padding:12px 10px;}
	.wrap-panel span{ 
		background: #ef9b08; padding:5px 10px; margin-bottom: 10px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
	@media (max-width: 767px){ 
		.next3-btn{
			padding: 5px 7px;
			font-size: 13px!important;
		}
		.next5-btn{
			padding: 5px 7px;
			font-size: 13px!important;
		}
		.wrap-panel{ 
			word-wrap: break-word; display: flex; flex-wrap: wrap; word-break: keep-all; font-size: 12px; color: #fff; line-height: 18px; padding:5px 10px; background: #ef9b08; text-align: center;
			background: rgba(0,153,242,1);
			background: -moz-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
			background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(0,153,242,1)), color-stop(100%, rgba(26,78,162,1)));
			background: -webkit-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
			background: -o-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
			background: -ms-linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
			background: linear-gradient(45deg, rgba(0,153,242,1) 0%, rgba(26,78,162,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0099f2', endColorstr='#1a4ea2', GradientType=1 );}
	}

	.search-icon-box{
		width:30%
	}
	.sltbtn.disabled{
		background: #c5c5c5!important;
	}

</style>
<?php
#$shuttles				 = $model->getQuote(null, true);
/* @var $model BookingTemp */




$cabData = VehicleTypes::model()->getMasterCarDetails();


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

<div class="panel">            
	<div class="panel-body pt0 pb0 p0">
		<div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="route-page1">

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-offset-1 col-lg-offset-1 col-md-10 col-lg-10 ml0">
					<h2 class="mb0 mt0">
						<?php
						echo $shuttles[0]['fromCity'] . ' &rarr; ' . $shuttles[0]['toCity'];
						?> </h2>
					<?php
					if ($shuttles)
					{
						?>
						<p>Estimated Distance: <b> <?= $model->bkg_trip_distance . " Km" ?></b>,
							Estimated Time: <b><?= Filter::getDurationbyMinute($model->bkg_trip_duration) ?> (+/- 30 mins for traffic)</b></p>
						<?php
					}
					else
					{
						?>
						<br/><p><b>Sorry cab is not available for this route.</b></p>
					<?php } ?>
					<!--<h5 class="hide">If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>-->
				</div> 
			</div>
			<?php
			$i = 0;

			foreach ($shuttles as $key => $shuttle)
			{
				$cab	 = $cabData[$shuttle['vht_car_type']];
                $svcId = 1;
                $vctId = $cab['vct_id'];
                $data = SvcClassVhcCat::getVctSvcList('detail',$svcId,$vctId);
                $vhtId = $data['scv_id'];
				// Fare Breakup Tooltip
				$details = $this->renderPartial("bkFareShuttle", ['shuttle' => $shuttle], true);
				$tolltax_value	 = 1;
				$tolltax_flag	 = 1;
				$statetax_value	 = $shuttle['slt_toll_tax'] | 0;
				$statetax_flag	 = $shuttle['slt_state_tax'] | 0;

				if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
				{
					$taxStr = 'Toll Tax and State Tax not payable by customer';
				}
				else if ($tolltax_flag == 0 && $statetax_flag == 0)
				{
					$taxStr = 'Toll and State taxes extra as applicable';
				}
				?>

				<div class="col-xs-12 search-cabs-box mb30 ">
					<div class="row">
						<div class="col-xs-12 col-sm-2 border-rightnew ">
							<div class="car-style pl10 mr10"><?= $cab['vct_label'] ?></div>
							<div class=" text-right mt10 n"> Shuttle #<b>ST<?= $shuttle['slt_id'] ?></b></div>
							<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt="" >
							</div>

						</div>
						<div class="col-xs-12 col-sm-10">
							<div class="row p10">
								<div class="col-xs-12  col-sm-5 mobile-view-p border-lt">
									<div class="search-icon-box">
										<img src="/images/search_icon_1.png" alt="Capacity" title="Capacity"><br>
										<?= $cab['vct_capacity'] ?> Seats + Driver
									</div>
									<div class="search-icon-box">
										<img src="/images/search_icon_2.png" alt="Luggage Capacity" title="Luggage Capacity"><br>
										1 small backpack
									</div>
									<div class="search-icon-box">
										<img src="/images/search_icon_3.png" alt="AC" title="AC"><br>
										AC
									</div>

									<div class="row">
										<div class="col-xs-12 font11">
											<?= $taxStr ?>
										</div>
									</div>

								</div>


								<div class="col-xs-12 col-sm-4   ">
									<div class="row ">
										<div class="col-xs-12 text-uppercase p15 pb5 mb5 bg-warning" style="width:100%;">
											<div class="row ">
												<div class="col-xs-6">
													Departure Time: </div>
												<div class="col-6 text-right ">
													<strong><?= DateTimeFormat::DateTimeToLocale($shuttle['slt_pickup_datetime']) ?> </strong>
												</div>
											</div>
										</div> 

										<div class="col-xs-5">
											Pickup Point: </div>
										<div class="col-7 text-left">
											<?= $shuttle['slt_pickup_location'] ?>, <?= $shuttle['fromCity'] ?>
										</div>
										<div class="col-xs-5">
											Drop Point: </div>
										<div class="col-7 text-left">
											<?= $shuttle['slt_drop_location'] ?>, <?= $shuttle['toCity'] ?>
										</div> 

										<div class="col-xs-5">
											Seat Available: </div>
										<div class="col-7 text-left">
											<?= $shuttle['available_seat'] ?> 
										</div> 
									</div>
								</div>
								<div class="col-xs-12 col-sm-3 text-center ">
									<div class=" m0  p10 ">
										<span style="font-size: 27px; padding-right: 2px;">&#x20B9;</span>
										<span class="green-color h3"><b><?= $shuttle['slt_price_per_seat'] ?></b><sup>*</sup></span>
										<a data-toggle="popover" data-placement="top" data-html="true" data-content="<?= $details ?>" style="font-size:18px;">
											<i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Fair Breakup" data-placement="botton"></i>
										</a>
									</div>	

									<button type="button" 
											value="<?= $vhtId ?>" 
											id="btn_<?= $shuttle['slt_id'] ?>"
											shuttleid="<?= $shuttle['slt_id'] ?>" 
											seat_count ="1"   
											kms="<?= $shuttle['trip_distance'] ?>" 
											duration="<?= $shuttle['trip_duration'] ?>" 
											name="bookButton" class="btn next3-btn mt10 " onclick="validateForm1(this);">
										<b>Book a seat</b> 
									</button>
								</div>


							</div>
						</div>
					</div>

				</div>
				<?php
			}
			?>
		</div>
	</div>
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
