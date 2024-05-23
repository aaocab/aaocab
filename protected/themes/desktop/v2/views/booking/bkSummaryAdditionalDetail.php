<style>
    .form-group{ margin-bottom: 0;}
</style>
<?php
$fcity					 = Cities::getName($model->bkg_from_city_id);
$tcity					 = Cities::getName($model->bkg_to_city_id);
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$action					 = Yii::app()->request->getParam('action');
$hash					 = Yii::app()->shortHash->hash($model->bkg_id);
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
$scvVctId				 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
?>
<?php
$form					 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingadditionalinfo', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
?>
<div class="row">
    <div class="col-12 mt20">
		<div class="bg-white-box">
			<div class="row">
				<div class="font-20 mb10 col-sm-7 text-uppercase"><b>Special Requests</b></div>				
			</div>
			<!--<div class="main_time border-blueline">-->
			<div class="mb0">  
				<b>Please provide additional information to help us to serve you better.</b>
			</div>
			<?=
			$form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']);
			$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
			?>
			<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
			<div class="row">
				<div class="col-12">
					<div class="row mt10">
						<div class="col-4">
							<label class="checkbox-inline check-box">Senior citizen traveling
								<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl'); ?>
								<span class="checkmark-box"></span>
							</label>
						</div>
						<div class="col-4">
							<label class="checkbox-inline check-box">Kids on board
								<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_kids_trvl'); ?>
								<span class="checkmark-box"></span>
							</label>
						</div>
						<div class="col-4">
							<label class="checkbox-inline check-box">Women traveling
								<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_woman_trvl'); ?>
								<span class="checkmark-box"></span>
							</label>
						</div>

					</div>

					<div class="row mt10">
						<div class="col-6">
							<label class="checkbox-inline check-box">English-speaking driver required
								<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking'); ?>
								<span class="checkmark-box"></span>
							</label>
						</div>
						<div class="col-6">
							<label class="checkbox-inline check-box">Others
								<?= $form->checkBox($model, 'bkg_chk_others'); ?>
								<span class="checkmark-box"></span>
							</label>
						</div>
						<div class="col-6">
							<?
							if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
							{
								?>
								<label class="checkbox-inline check-box">Require vehicle with Carrier
									<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_carrier'); ?>
									<span class="checkmark-box"></span>
								</label>
								<?
							}
							?>
						</div>
					</div>
					<div class="row mt10">
						<div class="col-6"></div>
						<div class="col-6"></div>
					</div>
					<div class="row">
						<div class="col-12 mb20" id="othreq" style="display: <?= $otherExist ?>">
							<?= $form->textArea($model->bkgAddInfo, 'bkg_spl_req_other',  ['placeholder' => "Other Requests", 'class' => 'form-control']) ?>  
						</div>
					</div>
					<div class="row">
						<div class="col-7">
							<label class="checkbox-inline pt0 pr30 check-box">Add a journey break (₹150/30mins)
								<?= $form->checkBox($model, 'bkg_add_my_trip'); ?>
								<span class="checkmark-box"></span>
							</label>
						</div>
						<div class="col-5">
							<?= $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time',['0' => 'Minutes', '30' => '30', '60' => '60', '90' => '90', '120' => '120', '150' => '150', '180' => '180'],['class'=>'form-control','placeholder'=>'Journey Break']) ?>
							<?php echo $form->error($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['class' => 'help-block error']); ?>
							<div id="addmytrip" class="font-10 mt5">First 15min free. Unplanned journey breaks are not allowed for one-way trips</div>
						</div>
					</div> 
				</div>
			</div>
			<!--		</div>
				</div>
				<div class="  main_time border-blueline additionalinfo  mb20">-->
			<div class="font-20 mb10 mt30 text-uppercase"><b>Additional Details</b></div>
			<div class="row mb20">
				<div class="col-sm-4">
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-12">Personal Or Business Trip?</label>
							<div class="col-12">
								<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id']);
								?>
								<input type="hidden" id="request_status" value="">
								<label class="radio2-style mb0">
									<input id="BookingAddInfo_bkg_user_trip_type_0" value="1" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type">Personal	
									<span class="checkmark-2"></span>
								</label>
								<label class="radio2-style mb0">
									<input id="BookingAddInfo_bkg_user_trip_type_1" value="2" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type">Business	
									<span class="checkmark-2"></span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<?php
				$readOnly = [];
				if (in_array($model->bkg_flexxi_type, [1, 2]))
				{
					$readOnly = ['readOnly' => 'readOnly'];
				}
				?>
				<div class="col-sm-4">
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-12">Number of Passengers <span style="color:red;"> * </span></label>
							<div class="col-6">
								<?= $form->numberField($model->bkgAddInfo, 'bkg_no_person',   ['placeholder' => "0", 'min' => 1, 'max' => $bdata['vht_capacity'],'class'=> 'form-control']) ?>                       
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></label>
							<div class="col-6">
								<?php
								$vct_Id		 = $model->bkgSvcClassVhcCat->scv_vct_id;
								$scc_Id		 = $model->bkgSvcClassVhcCat->scv_scc_id;
								$sbagRecord	 = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
								$lbag		 = floor($sbagRecord['vcsc_small_bag'] / 2);
								?>
								<select class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag'] ?>);">
									<?php for ($i = 0; $i <= $lbag; $i++)
									{
										?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
<?php } ?>		
								</select>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></label>
							<div class="col-6">
								<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]">
									<?php for ($i = 1; $i <= $sbagRecord['vcsc_small_bag']; $i++)
									{
										?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
								<?php } ?>		
								</select>	
							</div>	
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row">
						<div class="form-group">
							<label for="inputEmail" class="control-label col-12">How did you hear about Gozo cabs? </label>
							<div class="col-12">
								<?php
								$infosource = ['' => 'Select Infosource'] + $infosource;
                                echo $form->dropDownList($model->bkgAddInfo,"bkg_info_source",$infosource,['class'=> 'form-control','style' => 'width:100%;margin-bottom:10px','placeholder' => 'Select Infosource']);
								?>
							</div>
						</div>
					</div>
				</div>
<? $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other Media') ? '' : 'hide'; ?>
				<div class="col-sm-4">
					<div class="row">
						<div class="form-group <?= $sourceDescShow ?> " id="source_desc_show">
							<label for="inputEmail" class="control-label">&nbsp;</label>
							<div class="col-12 mt20">
<?= $form->textField($model->bkgAddInfo, 'bkg_info_source_desc',['placeholder' => "",'class' => 'form-control']) ?>                      
							</div>
						</div>
					</div>
				</div>
			</div>
			<h3 class="hide">&nbsp;<br>Journey Details: </h3>
			<?
			$j				 = 0;
			$cntRt			 = sizeof($model->bookingRoutes);
			foreach ($model->bookingRoutes as $key => $brtRoute)
			{
				if ($j == 0)
				{
					?>       
					<div class="row hide">
						<div class = "form-group mb15">
							<label for="pickup_address" class="control-label col-12 col-sm-5 pt10">Pickup Pincode for <?= $brtRoute->brtFromCity->cty_name ?></label>
							<div class="col-12 col-sm-7">
		<?= $form->numberField($brtRoute, "brt_from_pincode",['placeholder' => "Pincode (Optional)",'class' => 'form-control']) ?>
							</div>
						</div>
					</div>
					<?
				}
				$j++;
				$opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
				?>
				<div class = "row hide">
					<div class = "form-group mb15">
						<label for="pickup_address" class="control-label col-12 col-sm-5 pt10">Drop Pincode for <?= $brtRoute->brtToCity->cty_name ?></label>
						<div class="col-12 col-sm-7">
		<?= $form->numberField($brtRoute, "brt_to_pincode",['placeholder' => "Pincode (Optional)",'class' => 'form-control']) ?>
						</div>
					</div>
				</div>
				<?
			}
			?>

			<div class="clear"></div>
			<div class="row">	
				<div class="col-7 heading-part mb10">&nbsp;</div>
				<div class="col-5 heading-part mb10 mt15"><b>
						<button type="button" class="btn btn-effect-ripple btn-success" id="additiondetails" >Save Special Requests</button></b>				
				</div>
			</div>
			<div class="row">
				<div class="col-4 heading-part mb10">&nbsp;</div>
				<div class="col-8 heading-part mb10">
					<span id="msg" class="hide" style="font-weight: bold;color: #FF6700;font-size: 12px">SPECIAL REQUESTS SAVED.</span>
				</div>
			</div>
			<div class="clear"></div>

		</div>
    </div>
</div>
<?php
$capacity		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_capacity;
$bagCapacity	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_small_bag_capacity;
$bigBagCapacity	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_big_bag_capacity;
?>
<?php $this->endWidget(); ?>
<script>
    function luggage_info(largebag, vcatid, sccid, smallbag)
    {
        var largebag = largebag;
        var vcatid = vcatid;
        var sccid = sccid;
        var smallbag = smallbag;
        var sbag = Math.floor(smallbag - (largebag * 2));
        $("#BookingAddInfo_bkg_num_small_bag").empty();
        for (var i = 0; i <= sbag; i++)
        {
            var id = i;
            var name = i;
            $("#BookingAddInfo_bkg_num_small_bag").append("<option value='" + id + "'>" + name + "</option>");
        }
    }
    $(document).ready(function () {
        $("#BookingAddInfo_bkg_info_source").change(function () {
            var infosource = $("#BookingAddInfo_bkg_info_source").val();
            extraAdditionalInfo(infosource);
        });
        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
        {
            $("#addmytrip").show();
        }

    });
    $('#additiondetails').click(function (event) {
        $(".error").css('color', 'rgb(212, 103, 103)');
        var noPerson = $('#BookingAddInfo_bkg_no_person').val();
        var smallBag = $('#BookingAddInfo_bkg_num_small_bag').val();
        var bigBag = $('#BookingAddInfo_bkg_num_large_bag').val();
        var vhCapacity = '<?= $capacity ?>';
        var smallbagCapacity = '<?= $bagCapacity ?>';
        var bigbagCapacity = '<?= $bigBagCapacity ?>';
        var href = '<?= Yii::app()->createUrl('booking/summaryadditionalinfo') ?>';
        var bkgid = $('#bkg_id').val();
        var hash = '<?= $hash ?>';
        var noPassenger = $('#BookingAddInfo_bkg_no_person').val();
        var noLargeBag = $('#BookingAddInfo_bkg_num_large_bag').val();
        var noSmallBag = $('#BookingAddInfo_bkg_num_small_bag').val();
        var fromPincode = $('#BookingRoute_brt_from_pincode').val();
        var toPincode = $('#BookingRoute_brt_to_pincode').val();
        var infosource = $('#BookingAddInfo_bkg_info_source').val();

        var tripType = $('input[name="BookingAddInfo[bkg_user_trip_type]"]:checked').val();

        var seniorCitizen = $('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl').is(":checked");
        var kidsTravel = $('#BookingAddInfo_bkg_spl_req_kids_trvl').is(":checked");
        var womanTravel = $('#BookingAddInfo_bkg_spl_req_woman_trvl').is(":checked");
        var carrierReq = $('#BookingAddInfo_bkg_spl_req_carrier').is(":checked");
        var engSepeakingDriver = $('#BookingAddInfo_bkg_spl_req_driver_english_speaking').is(":checked");
        var othersInfo = $('#Booking_bkg_chk_others').is(":checked");
        var addTrip = $('#Booking_bkg_add_my_trip').is(":checked");
        var discountAmount = $('.discountAmount').html();
        var walletUsed = $('.walletUsed').html();
        var creditUsed = $('.creditUsed').html();

        //var additionalInfo = $('#bookingadditionalinfo').serialize();
        if (othersInfo == true)
        {
            var splreq = $.trim($("#BookingAddInfo_bkg_spl_req_other").val());
        }
        if (addTrip == true)
        {
            var breakTime = $('#BookingAddInfo_bkg_spl_req_lunch_break_time').val();
            if (breakTime == 0)
            {
                $('#BookingAddInfo_bkg_spl_req_lunch_break_time_em_').html('Please select journey break time');
                $("#BookingAddInfo_bkg_spl_req_lunch_break_time_em_").css('display', 'block');
                return false;
            }
        }
        if (noPassenger <= 0) {
            alert('Please Enter number of Passenger');
            return false;
        } else if (noLargeBag < 0) {
            alert('Please Enter number of large bag you want to take');
            return false;
        } else if (noSmallBag < 0) {
            alert('Please Enter number of small bag you want to take');
            return false;
        }
        if ((infosource == '5') || (infosource == '6')) {
            var infosourcedesc = $('#BookingAddInfo_bkg_info_source_desc').val();
        }
        if (seniorCitizen == true)
        {
            seniorCitizen = 1;
        } else {
            seniorCitizen = 0;
        }

        if (kidsTravel == true)
        {
            kidsTravel = 1;
        } else {
            kidsTravel = 0;
        }

        if (womanTravel == true)
        {
            womanTravel = 1;
        } else {
            womanTravel = 0;
        }

        if (carrierReq == true)
        {
            carrierReq = 1;
        } else {
            carrierReq = 0;
        }

        if (engSepeakingDriver == true)
        {
            engSepeakingDriver = 1;
        } else {
            engSepeakingDriver = 0;
        }
        if (creditUsed > 0)
        {
            discountAmount = 0;
        }
        //debugger;
        jQuery.ajax({type: 'GET',
            url: href,
            data: {id: bkgid, hash: hash, BookingAddInfo: {bkg_spl_req_senior_citizen_trvl: seniorCitizen, bkg_spl_req_kids_trvl: kidsTravel,
                    bkg_spl_req_woman_trvl: womanTravel, bkg_spl_req_carrier: carrierReq, bkg_spl_req_driver_english_speaking: engSepeakingDriver,
                    bkg_spl_req_other: splreq, bkg_spl_req_lunch_break_time: breakTime,
                    bkg_user_trip_type: tripType, bkg_no_person: noPassenger, bkg_num_large_bag: noLargeBag, bkg_num_small_bag: noSmallBag,
                    bkg_info_source: infosource, bkg_info_source_desc: infosourcedesc}, BookingRoute: {"<?= $brtRoute->brt_id ?>": {brt_from_pincode: fromPincode, brt_to_pincode: toPincode}}, discountamount: discountAmount, walletused: walletUsed, creditUsed: creditUsed},
            success: function (data)
            {
                obj = jQuery.parseJSON(data);
				if (obj.success != true) {
                    if ((parseInt(noPerson) > parseInt(vhCapacity)) && parseInt(vhCapacity) != '')
                    {
                        $('#BookingAddInfo_bkg_no_person_em_').html('Your selected cab can accomodate ' + vhCapacity + ' passengers');
                        $("#BookingAddInfo_bkg_no_person_em_").css('display', 'block');
                    }
                    if ((parseInt(smallBag) > parseInt(smallbagCapacity)) && parseInt(smallbagCapacity) != '')
                    {
                        $('#BookingAddInfo_bkg_num_small_bag_em_').html('The selected cab can accomodate ' + smallbagCapacity + ' small bags');
                        $("#BookingAddInfo_bkg_num_small_bag_em_").css('display', 'block');
                    }
                    if ((parseInt(bigBag) > parseInt(bigbagCapacity)) && parseInt(bigbagCapacity) != '')
                    {
                        $('#BookingAddInfo_bkg_num_large_bag_em_').html('The selected cab can accomodate ' + bigbagCapacity + ' big bags');
                        $("#BookingAddInfo_bkg_num_large_bag_em_").css('display', 'block');
                    }
                    event.preventDefault();
                } else
                {
					$('.etcAmount').html(obj.totalAmount);
                    $('.taxAmount').text(obj.servicetax).change();

                    $('.bkgamtdetails111').html(obj.totalAmount - obj.walletAmount - obj.creditUsed);



                    $('#max_amount').val(obj.dueAmount).change();
                    if (obj.additionalAmount != '' && obj.additionalAmount != '0') {
                        $(".additionalcharge").removeClass("hide");
                        $('.extracharge').html('&#x20B9;' + obj.additionalAmount);
                        //$('.extrachargeremark').text(obj.additionalAmountremarks).change();
                    }
                    //$('.additionalinfo').hide();
                    $('#additiondetails').hide();
                    $("#msg").removeClass("hide");
                    $("#request_status").val('1');
                    $('.additionalinfoadd').text();
                    $(".additionalinfoadd").removeClass("hide");
                    //$('.payBoxMinAmount').text(obj.minPay);
                    var totalAmount = obj.dueAmount - obj.walletAmount - obj.creditUsed;
					var minAmount = obj.minPay;
					$('#BookingInvoice_partialPayment').attr('max', totalAmount);
                    $('#BookingInvoice_partialPayment').attr('min', minAmount);
                    $('.payBoxMinAmount').text(minAmount);
                    //obj.minPay = minAmount;
                    //alert(minAmount);
                    $('.payBoxDueAmount').text(obj.dueAmount - obj.walletAmount - obj.creditUsed);
                    $('.payBoxTotalAmount').text(obj.totalAmount);
                    $.each($('input[name="payChk"]'), function (key, val) {
                        if ($(val).is(':checked') == true)
                        {
                            if ($(val).attr('id') == 'minPayChk')
                            {
                                $('.payBoxBtnAmount').text($('.payBoxMinAmount').text());
                                // $('#BookingInvoice_partialPayment').val(obj.minPay);
                                $('#BookingInvoice_partialPayment').val(minAmount);

                            } else
                            {
                                $('.payBoxBtnAmount').text($('.payBoxDueAmount').text());
                                $('#BookingInvoice_partialPayment').val(totalAmount);
                                $('#BookingInvoice_partialPayment').attr('max', totalAmount);
                            }

                        }
                    });
                    $(".additionalinfo").css("border", "1px solid blue");
                    $('html, body').animate({scrollTop: 0}, 'slow');
                }
                return false;

            }

        });
    });
    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == '5') {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
        } else if (infosource == '6') {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
        }
    }
    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        } else {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').change(function () {
        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
        {
            $("#addmytrip").show();
        } else {
            $("#addmytrip").hide();
        }
    });

    $('select[name="BookingAddInfo[bkg_spl_req_lunch_break_time]"]').change(function (event) {
        var journeyBreakTime = $(event.currentTarget).val();
        brakCharges = journeyBreakTime * 5;
        //alert(brakCharges);
        if (brakCharges != 0) {
            $(".heading-journeybreak").removeClass('hide');
            $("#journeybreak").html(journeyBreakTime + " minutes break during journey (Rs." + brakCharges + "/-).");
            $('#additiondetails').removeClass("hide");
        } else {
            $(".heading-journeybreak").addClass('hide');
            $("#journeybreak").html('');
        }
    });

    $('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl,#BookingAddInfo_bkg_spl_req_kids_trvl,#BookingAddInfo_bkg_spl_req_woman_trvl,#BookingAddInfo_bkg_spl_req_driver_english_speaking,#Booking_bkg_chk_others,#Booking_bkg_add_my_trip,#BookingAddInfo_bkg_user_trip_type_0,#BookingAddInfo_bkg_user_trip_type_1,#BookingAddInfo_bkg_info_source').click(function () {
        $('#additiondetails').removeClass("hide");
    })

    $('#BookingAddInfo_bkg_no_person,#BookingAddInfo_bkg_num_large_bag,#BookingAddInfo_bkg_num_small_bag').focus(function () {
        $('#additiondetails').removeClass("hide");
    });
</script>