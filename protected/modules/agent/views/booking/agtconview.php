<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pac-item >.pac-icon-marker{
        display: none !important;
    }
    .pac-item-query{
        padding-left: 3px;
    }  
    .trip_plan table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    /* Zebra striping */
    .trip_plan tr:nth-of-type(odd) { 
        background: #f1f1f1; 
    }
    .trip_plan th { 
        background: #333; 
        color: white; 
        font-weight: bold; 
    }
    .trip_plan td { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .trip_plan th { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    @media (max-width: 767px)
    {

        /* Force table to not be like tables anymore */
        .trip_plan table, thead, tbody, th, td, tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .trip_plan thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .trip_plan tr{ border: 1px solid #ccc; }

        .trip_plan td{ 
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #d5d5d5; 
            position: relative;
            padding-left: 50%; 
        }

        .trip_plan td:before { 
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
        }

        /*
        Label the data
        */
        .trip_plan td:nth-of-type(1):before { content: "From"; }
        .trip_plan td:nth-of-type(2):before { content: "To"; }
        .trip_plan td:nth-of-type(3):before { content: "Departure Date"; }
        .trip_plan td:nth-of-type(4):before { content: "Time"; }
        .trip_plan td:nth-of-type(5):before { content: "Distance"; }
        .trip_plan td:nth-of-type(6):before { content: "Duration"; }
        .trip_plan td:nth-of-type(7):before { content: "Days"; }
    }
    .checkbox-inline {
        padding-top: 0 !important; 
    }
</style>
<?php
//$api				 = Yii::app()->params['googleBrowserApiKey'];
$api				 = Config::getGoogleApiKey('browserapikey');
$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/autoAddress.js?v=$autoAddressJSVer");

//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=' . $api . '&libraries=places&', CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places&', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
/* @var $model BookingTemp */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ccode					 = Countries::model()->getCodeList();
$additionalAddressInfo	 = "Building No./ Society Name";
$addressLabel			 = ($model->bkg_booking_type == 4) ? 'Location' : 'Address';
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$ulmodel				 = new Users('login');
$urmodel				 = new Users('insert');
$locFrom				 = [];
$locTo					 = [];
$autocompleteFrom		 = 'txtpl';
$autocompleteTo			 = 'txtpl';
$locReadonly			 = ['readonly' => 'readonly'];
if ($model->bkg_transfer_type == 1)
{
	$locFrom			 = $locReadonly;
	$autocompleteFrom	 = '';
}
if ($model->bkg_transfer_type == 2)
{
	$locTo			 = $locReadonly;
	$autocompleteTo	 = '';
}

$userdiv = 'none';
?>
<div class="abcdtest container">
	<?php
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'customerinfo1',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                
					$.ajax({
						 "type":"POST",
                        "dataType":"json",
                        "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/validateagtcustinfo')) . '",
							"data":form.serialize(),
							"success":function(data1){				
                                if(data1.success){
                                
                                ajaxsubmitnoerr(data.data);
                               
								}
								else{								
								settings=form.data(\'settings\');
								data2 = data1.errors;
								$.each (settings.attributes, function (i) {
								$.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
								});
								$.fn.yiiactiveform.updateSummary(form, data2);
								} 
                              },
						error: function (xhr, ajaxOptions, thrownError) 
						{
								alert(xhr.status);
								alert(thrownError);
						}
					});
				}
			}'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	/* @var $form TbActiveForm */

	?>
	<div class="row mt20">
    <div class="panel">            
        <div class="panel-body">   
            <input type="hidden" id="step4" name="step" value="cnview">
			<?= $form->hiddenField($model, 'bkg_user_id'); ?> 
			<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']); ?>
			<?= $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash']); ?>
			<?= $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]); ?> 
            <span class="error"><?php echo $form->error($model,"bkg_pickup_address");?></span>
  <span class="error"><?php echo $form->error($model,"bkg_drop_address");?></span>
            <!--            booking summary -->
            <div class="col-xs-12 col-md-6 pull-right " id="booksummaryrefresh">
				<?php echo $this->renderPartial("booksummaryrefresh", ['model' => $model,'invModel' => $invModel]); ?>
            </div>
            <!--            booking summary -->         






            <div class="col-xs-12 col-md-6">
				<?= $form->errorSummary($model); ?>
				<?= CHtml::errorSummary($model); ?>
                <h2 class="font-24 weight600">Traveller's Information</h2>
                <div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
                <div class="row mt10">
                    <div class="col-xs-12 col-sm-5 ">
                        <label for="inputEmail" class="control-label">Primary Passenger Name *:</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="row m0">
                            <div class="col-xs-6 col-sm-6 pr20">
								<?= $form->textFieldGroup($model, 'bkg_user_name', array('label' => '', 'placeholder' => "First Name", 'class' => 'form-control')) ?>
                            </div>
                            <div class="col-xs-6 col-sm-6 pl20">
								<?= $form->textFieldGroup($model, 'bkg_user_lname', array('label' => '', 'placeholder' => "Last Name", 'class' => 'form-control')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 ">
                        <label for="inputEmail" class="control-label">Primary Email address *:</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
						<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email2")]), 'groupOptions' => ['class' => 'ml0 mr0'])) ?>                      
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 ">
                        <label for="inputEmail" class="control-label">Primary Contact Number *:</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="row m0">   
                            <div class="col-xs-5 isd-input">
								<?php
								echo $form->dropDownListGroup($model, 'bkg_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
								?>
                            </div>
                            <div class="col-xs-7 pr0">
								<?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
								<?php echo $form->error($model, 'bkg_country_code'); ?>
								<?php echo $form->error($model, 'bkg_contact_no'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-5">
                        <label for="inputEmail" class="control-label">Alternate Contact Number :</label>
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="row m0">   
                            <div class="col-xs-5 isd-input">
								<?php
								echo $form->dropDownListGroup($model, 'bkg_alt_country_code', array('label' => '', 'class' => 'form-control', 'widgetOptions' => array('data' => $ccode)))
								?>
                            </div>
                            <div class="col-xs-7 pr0">
								<?= $form->textField($model, 'bkg_alternate_contact', array('placeholder' => "Alternate Mobile Number", 'class' => 'form-control')) ?>
								<?php echo $form->error($model, 'bkg_alt_country_code'); ?>
								<?php echo $form->error($model, 'bkg_alternate_contact'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5 col-sm-5 ">
                        <label class="control-label " for="BookingTemp_bkg_flight_no" id="flightlabeldivairport"  style="display: none">Enter Flight Number</label><br>

                        <label class="checkbox-inline pl30" style="padding-top: 11px;" id="chkAirport">
							<?= $form->checkboxGroup($model, 'bkg_flight_chk', ['label' => ' Airport Pickup? ']) ?>
                        </label>
                    </div>
                    <div class="col-xs-7 col-sm-3 ">

                        <div id="othreq" style="display: none"> 
                            <div class="form-group" >
                                <label class="control-label pl0 ml0" for="BookingTemp_bkg_flight_no" id="flightlabeldivoth">Enter Flight Number <br></label>
								<?= $form->textFieldGroup($model, 'bkg_flight_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Flight Number"]), 'groupOptions' => ['class' => 'm0'])) ?>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <b>
                            Please provide the exact pickup and drop addresses for your trip. 
                            The currently quoted amount is quoted from city center to city center. 
                            Exact addresses will help us provide updated more accurate fare quote for your booking. 
                            Distance driven beyond included Kms is billed as applicable. <?php /* /?>at Rs. <?= $model->bkg_rate_per_km_extra ?>/Km</b><?php / */ ?>
                        </b>
                    </div>
                    <div class="col-xs-12 col-md-8 mt10">
                        <h5><b>Journey Details:</b></h5>
                    </div> 
                </div>
				
				<?php
				$this->renderPartial('pickupLocationWidget', ['model' => $model], false, false);
				?>		
            </div>


            <div class="col-xs-12  pt20">
                <div class="row">
                    <div class="col-xs-4 col-sm-3">To be paid by 
                    </div>
                    <div class="col-xs-8  col-sm-4">
                        <label class="checkbox-inline ">
							<?php
							if ($model->agentBkgAmountPay == '')
							{
								$model->agentBkgAmountPay = 2;
							}
							echo $form->radioButtonListGroup($model, 'agentBkgAmountPay', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['onclick' => 'showAgentCreditDiv()'], 'data' => [1 => 'Customer', 2 => 'Agent/Company']), 'inline' => true));
							?>

                        </label>
                    </div>
                </div> 
            </div>
            <div class="col-xs-12 mt20" id="divAgentCredit" style="display: <?= (Yii::app()->user->getCorpCode() != '') ? 'block' : 'none'; ?>">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-lg-4">Amount paid by company for the booking </div>
                    <div class="col-xs-5 col-sm-3 col-md-2">
                        <? // $invModel->bkg_due_amount ?>
						<?php
//                        $restText   = "";
//                        $getBalance = PartnerStats::getBalance($model->bkg_agent_id);
//                        $getBalance['pts_wallet_balance'];
//
//                        if ($getBalance['pts_wallet_balance'] > $invModel->bkg_total_amount)
//                        {
//                            $val = $invModel->bkg_total_amount;
//                        }
//                        else
//                        {
//                            $val      = $invModel->bkg_total_amount;
//                            $rest     = $invModel->bkg_total_amount - $getBalance['pts_wallet_balance'];
//                            $restText = 'Customer has to pay ' . '<i class="fa fa-inr"></i> ' . $rest;
//                            $val      = $invModel->bkg_total_amount - $rest;
//                        }

                        // echo  $model->agentCreditAmount = $invModel->bkg_total_amount;
						$readOnlyCreditAmt = [];
						if (Yii::app()->user->getCorpCode() != '')
						{
							$readOnlyCreditAmt = ['readOnly' => 'readOnly'];
						}
						?>
                         
						<?= $form->numberFieldGroup($model, 'agentCreditAmount', 
                            array('label' => '', 
                                'widgetOptions' => 
                            array('htmlOptions' => 
                                ['class' => 'form-control',
                                   // 'value'=> $val,
                                    'placeholder' => "Agent Advance Credit",
                                    'min' => 0, 
                                    'max' => $model->bkg_total_amount]))) ?>
                        
                    </div> 
<!--                     <div class="col-xs-5 col-sm-offset-5 col-lg-offset-4  pl0" ><?php //echo $restText;?></div>-->
                    <div class="col-xs-5 col-sm-offset-5 col-lg-offset-4  pl0" id="dueAmountDiv"></div>
                </div>
            </div>

             <?php // echo  $value =  $model->bkg_total_amount;?>


            <div class="col-sm-12 mt20">
                <div class="row">
                    <div class="mb0">
                        <div class="col-xs-12 ">Send a booking copy to</div>
                    </div>
                </div>
                <div class="row m0">
                    <div class="col-xs-6 col-sm-3 mr5">
						<?= $form->textFieldGroup($model, 'bkg_copybooking_name', array('label' => "Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
                    </div>
                    <div class="col-xs-6 col-sm-3 mr5"> 
						<?= $form->textFieldGroup($model, 'bkg_copybooking_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                    </div>
                    <div class="col-xs-6 col-sm-2 mr5">

						<?php
						$model->bkg_copybooking_country = '91';
						echo $form->dropDownListGroup($model, 'bkg_copybooking_country', array('label' => 'Country Code', 'widgetOptions' => array('data' => $ccode)))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-3"> 
						<?= $form->textFieldGroup($model, 'bkg_copybooking_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
                    </div>

                </div>

			</div>


            <div class="col-xs-12">
                <div class="row">

                    <div class="col-xs-5"><button type="button" class="btn btn-success" onclick="shownotifyopt()">SET NOTIFICATION DEFAULTS</button></div>
                </div>
                <input type="hidden" name="agentnotifydata" id="agentnotifydata">
            </div>

            <div class="col-xs-12 mt20">

                <button id="connfirmbookbtn" style="height: 50px;"  type="submit" class="btn bg-primary btn-lg  pl30 pr30 text-uppercase white-color" >Proceed</button>
            </div>
        </div> 
    </div>
</div>
	<?php $this->endWidget(); ?>
</div>

<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<script type="text/javascript">
    
 
    
    
    
    
                        booking_type = '<?= $model->bkg_booking_type ?>';
                        transfer_type = '<?= $model->bkg_transfer_type ?>';
                        $(document).ready(function ()
                        {
                           
                           
							var bookingtype = new Array("9", "10", "11");
							if(bookingtype.indexOf(booking_type) >= 0)
							{
								$('.tolltaxincluded').hide();
								$('.statetaxincluded').hide();
							}
                            initializepl(booking_type, transfer_type);
                            bid = '<?= $model->bkg_id ?>';
                            hsh = '<?= $model->hash ?>';
                            $isRunningAjax = false;
                            // showNotificationDiv();
                            showAgentCreditDiv();

                        });
                        $('form').on('focus', 'input[type=number]', function (e)
                        {
                            $(this).on('mousewheel.disableScroll', function (e)
                            {
                                e.preventDefault()
                            })
                            $(this).on("keydown", function (event)
                            {
                                if (event.keyCode === 38 || event.keyCode === 40)
                                {
                                    event.preventDefault();
                                }
                            });
                        });
                        $('form').on('blur', 'input[type=number]', function (e)
                        {
                            $(this).off('mousewheel.disableScroll');
                            $(this).off('keydown');
                        });

                        function opentns()
                        {
                            $href = '<?= Yii::app()->createUrl('index/tns') ?>';
                            jQuery.ajax({type: 'GET', url: $href,
                                success: function (data)
                                {
                                    box = bootbox.dialog({
                                        message: data,
                                        title: '',
                                        size: 'large',
                                        onEscape: function ()
                                        {
                                            box.modal('hide');
                                        }
                                    });
                                }
                            });
                        }

//    function showNotificationDiv()
//    {
//        if ($('#BookingTemp_bkg_trvl_sendupdate_0').is(':checked')) {
//            $('#divUpd').show();
//        }
//        if (!$('#BookingTemp_bkg_trvl_sendupdate_0').is(':checked') || $('#BookingTemp_bkg_trvl_sendupdate_1').is(':checked')) {
//            $('#divUpd').hide();
//
//        }
//
//    }
                        function showAgentCreditDiv()
                        {

                            if ($('#BookingTemp_agentBkgAmountPay_0').is(':checked'))
                            {
                                $('#divAgentCredit').hide();
                            }
                            if ($('#BookingTemp_agentBkgAmountPay_1').is(':checked'))
                            {
                               
                                $('#divAgentCredit').show();
                              
                            }
                        }
                        $('#BookingTemp_agentCreditAmount').blur(function ()
                        {
                            validateAgentCreditAmount();
                        });

                        function validateAgentCreditAmount()
                        {
                            
                            $('#dueAmountDiv').text('');
                            $('#dueAmountDiv').hide();
                            
                            
                            var totalAmount = <?= $invModel->bkg_total_amount ?>;
                            var inputAmount = $('#BookingTemp_agentCreditAmount').val();
                            if(inputAmount>totalAmount)
                            {
                              $('#dueAmountDiv').show();
                              $('#dueAmountDiv').text('Amount exceeding total booking amount');
                            }
                            if(totalAmount>inputAmount)
                            {
                                var rest = totalAmount-inputAmount;
                                var restText = 'Customer has to pay ₹'+rest;
                                  $('#dueAmountDiv').show();
                              $('#dueAmountDiv').text(restText);
                            }
                            
                            
//                            $('#dueAmountDiv').text('');
//                            $('#dueAmountDiv').hide();
//                            var agtcreditamt = $('#BookingTemp_agentCreditAmount').val() - 0;
//
//                            var total_amount = "<?= $model->bkg_total_amount ?>" - 0;
//
//                            if (agtcreditamt < total_amount)
//                            {
//                                var dueamnt =<?= $model->bkg_total_amount ?> - $('#BookingTemp_agentCreditAmount').val();
//                                $('#dueAmountDiv').show();
//                                $('#dueAmountDiv').text('Due amount ₹' + dueamnt + ' will be collected from customer.');
//
//                            }
//                            else if (agtcreditamt > total_amount)
//                            {
//                                $('#dueAmountDiv').show();
//                                $('#dueAmountDiv').text('Amount exceeding total booking amount');
//                                return false;
//                            }
//                            return true;
                        }

                        function submitBooking()
                        {
                            validateAgentCreditAmount();
                            $('#customerinfo1').submit();
//        $.ajax({
//            "type": "POST",
//            "dataType": "json",
//            "url": "<?php //= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/agtfinalbook'))                            ?>",
//            "data": $("#customerinfo").serialize(),
//            "beforeSend": function () {
//                ajaxindicatorstart("");
//            },
//            "complete": function () {
//                ajaxindicatorstop();
//            },
//            "success": function (data2) {
//                if (data2.success) {
//                    location.href = data2.url;
//                } else {
//                    var errors = data2.errors;
//                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
//                    $.each(errors, function (key, value) {
//                        txt += "<li>" + value + "</li>";
//                    });
//                    txt += "</li>";
//                    $("#error_div1").show();
//                    $("#error_div1").html(txt);
//
//                }
//            }
//        });
                            //  }
                        }

                        function ajaxsubmitnoerr(data)
                        {
                            if ($('#BookingTemp_agentBkgAmountPay_1').is(':checked'))
                            {
                                $.ajax({
                                    "type": "GET",
                                    "dataType": "json",
                                    "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/users/creditlimit')) ?>",
                                    "data": {"corpcredit": $('#<?= CHtml::activeId($model, "agentCreditAmount") ?>').val(),"bkg_id":$("#bkg_id4").val()},
                                    "beforeSend": function ()
                                    {
                                        ajaxindicatorstart("");
                                    },
                                    "complete": function ()
                                    {
                                        ajaxindicatorstop();
                                    },
                                    "success": function (data2)
                                    {
                                        if (data2.isRechargeAccount == 1)
                                        {
                                            bootbox.confirm({
                                                size: "medium",
                                                message: "Credit limit exceed. Please recharge your account",
                                                callback: function (result)
                                                {
                                                    if (result)
                                                    {
                                                        var win = window.open('<?= Yii::app()->createUrl("agent/users/recharge"); ?>', '_blank');
                                                        win.focus();
                                                    }
                                                }
                                            });
                                        }
                                        if (data2.isRechargeAccount == 0)
                                        {
                                            $.ajax({
                                                "type": "POST",
                                                "dataType": "html",
                                                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/createquote')) ?>",
                                                "data": $('#customerinfo1').serialize(),
                                                "beforeSend": function ()
                                                {
                                                    ajaxindicatorstart("");
                                                },
                                                "complete": function ()
                                                {
                                                    ajaxindicatorstop();
                                                },
                                                "success": function (data2)
                                                {
                                                    $('.abcdtest').html(data2);
                                                },
                                                "error": function (data2)
                                                {

                                                }
                                            });
                                        }

                                    },
                                    "error": function (xhr, ajaxOptions, thrownError)
                                    {
                                        alert(xhr.status);
                                        alert(thrownError);
                                    }
                                });
                            }
                            else
                            {
                                $.ajax({
                                    "type": "POST",
                                    "dataType": "html",
                                    "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/createquote')) ?>",
                                    "data": $('#customerinfo1').serialize(),
                                    "beforeSend": function ()
                                    {
                                        ajaxindicatorstart("");
                                    },
                                    "complete": function ()
                                    {
                                        ajaxindicatorstop();
                                    },
                                    "success": function (data2)
                                    {
                                        $('.abcdtest').html(data2);
                                    },
                                    "error": function (data2)
                                    {

                                    }
                                });
                            }


                            return false;
                        }





</script>


<script type="text/javascript">

    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
        {
            $("#othreq").show();
        }
        else
        {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_send_email") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_send_email") ?>').is(':checked') && $('#<?= CHtml::activeId($model, "bkg_user_email") ?>').val() == '')
        {
            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please provide email address.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }

    });
    $('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').is(':checked') && $('#<?= CHtml::activeId($model, "bkg_contact_no") ?>').val() == '')
        {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please provide contact number.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }
    });


    function extraAdditionalInfo(infosource)
    {
        $("#source_desc_show").addClass('hide');
        if (infosource == 'Friend')
        {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#<?= CHtml::activeId($model, "bkg_info_source_desc") ?>').attr('placeholder', "Friend's email please");
        }
        else if (infosource == 'Other')
        {
            $("#source_desc_show").removeClass('hide');
            $("#agent_show").addClass('hide');
            $('#<?= CHtml::activeId($model, "bkg_info_source_desc") ?>').attr('placeholder', "");
        }
    }
    function validateBothCheck()
    {
        if (!$('#<?= CHtml::activeId($model, "bkg_send_sms") ?>').is(':checked') && !$('#<?= CHtml::activeId($model, "bkg_send_email") ?>').is(':checked'))
        {

            var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
            txt += "<li>Please check one of the communication media to send notifications.</li>";
            txt += "</ul>";
            $("#error_div").show();
            $("#error_div").html(txt);
        }
    }

    $('form').on('focus', 'input[type=number]', function (e)
    {
        $(this).on('mousewheel.disableScroll', function (e)
        {
            e.preventDefault()
        })
        $(this).on("keydown", function (event)
        {
            if (event.keyCode === 38 || event.keyCode === 40)
            {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e)
    {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });



    $('#<?= CHtml::activeId($model, "bkg_flight_chk") ?>').change(function ()
    {
        if ($('#<?= CHtml::activeId($model, "bkg_flight_chk") ?>').is(':checked'))
        {
            $("#othreq").show();
        }
        else
        {
            $("#othreq").hide();
        }
    });
    $('#<?= CHtml::activeId($model, "bkg_flight_no") ?>').mask('XXXX-XXXXXX', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            },
            'X': {
                pattern: /[0-9A-Za-z]/, optional: true
            },
        },
        placeholder: "__ __ __ ____",
        clearIfNotMatch: true
    }
    );

    function shownotifyopt()
    {
        var agentnotifydata = $('#agentnotifydata').val();
        jQuery.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl('agent/users/bookingmsgdefaults') ?>',
            dataType: 'html',
            data: {"notifydata": agentnotifydata, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data)
            {
                shownotifydiag = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function ()
                    {
                    }
                });
                shownotifydiag.on('hidden.bs.modal', function (e)
                {
                    $('body').addClass('modal-open');
                });
                return true;
            },
            error: function (x)
            {
                alert(x);
            }
        });
    }

    function savenotifyoptions()
    {
        jQuery.ajax({type: 'POST',
            url: '<?= Yii::app()->createUrl('agent/users/bookingmsgdefaults') ?>',
            dataType: 'json',
            data: $('#agent-notification-form').serialize(),
            success: function (data)
            {
                $('#agentnotifydata').val(JSON.stringify(data.data));
                bootbox.hideAll();
                alert('Notification details saved successfully.');
                return false;
            },
            error: function (x)
            {
                alert(x);
            }
        });
        return false;
    }

</script>
