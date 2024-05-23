<?php
/* @var $buvModel BookingUnregVendor 
   @var $model UnregVendorRequest	
 */
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<style>
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .top-buffer{padding-top: 10px;}
</style>
<div class="col-xs-12 mb10 pl0"><h3><span id="headerTxt">Car Needed From <b><?= $buvModel->buvBkg->bkgFromCity->cty_name; ?></b> To <b><?= $buvModel->buvBkg->bkgToCity->cty_name; ?></b>, Give Your Price Now</span></h3></div>
<div class="row pt20">
    <div class="col-xs-12 join_padding">
		<div id="venjoin3" style="display: none;">

		</div>
        <div class="row">

			<?php
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'bookingRequestForm', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError)
				{
					var formData = new FormData(form[0]);
					if(!hasError){
						$.ajax({
						"type":"POST",
						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('index/bookingRequest', ['buv_id' => $buvId])) . '",
						"data":formData,
						async: false,
						cache: false,
						contentType: false,
						processData: false,

						"success":function(data1)
						{ 

							if(data1.success)
							{

								resetFinish();

							}
							else
							{

								var errors = data1.error;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									try{
										$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
									}
									catch(e){
									}
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
							}},
					});

					}
				}'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => '', 'enctype'	 => 'multipart/form-data'
				),
			));
			/* @var $form TbActiveForm */
			?>

			<div class="col-xs-12 col-sm-5">
					<div id="venjoin11">
						<div class="col-xs-12 ">
							<h3><i class="fa fa-map-signs"></i>Requirements:</h3>
							<div class="col-xs-12 search-cabs-box mb30">
								<div class="row">
									<div class="col-xs-12 col-sm-12">
										<div class="row p10">
											<h5><p><b> Required Cab type : </b> <?= $buvModel->buvBkg->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label. ' ('.$buvModel->buvBkg->bkgSvcClassVhcCat->scc_ServiceClass->scc_label.')'; ?></p>
												<p><b>From City  : </b> <?= $buvModel->buvBkg->bkgFromCity->cty_name; ?></p>
												<p><b>To City  : </b> <?= $buvModel->buvBkg->bkgToCity->cty_name; ?></p>
												<p><b>Pickup Date & Time  : </b> <?= DateTimeFormat::DateTimeToLocale($buvModel->buvBkg->bkg_pickup_date); ?></p>
												<p><b>Trip Distance : </b> <?= $buvModel->buvBkg->bkg_trip_distance . "Kms."; ?></p>
												<p><b>Our Price : </b><i class="fa fa-inr"></i> <?= $buvModel->buvBcb->bcb_vendor_amount; ?></p>
												<?php if($buvModel->buvBkg->bkgInvoice->bkg_is_toll_tax_included==1){?>
												<p><b>*Toll Tax Included</b></p>
												<?php }?>
												<?php if($buvModel->buvBkg->bkgInvoice->bkg_is_state_tax_included==1){?>
												<p><b>*State Tax Included</b></p>
												<?php }?>
												<?php if($buvModel->buvBkg->bkgInvoice->bkg_is_parking_included==1){?>
												<p><b>*Parking Charges Included</b></p>
												<?php }?>
												<?php if($buvModel->buvBkg->bkgInvoice->bkg_night_pickup_included==1||$buvModel->buvBkg->bkgInvoice->bkg_night_drop_included==1){?>
												<p><b>*Night Allowance Included</b></p>
												<?php }?>

												</h5>
										</div>
									</div>
									<div id="editBidAmountDiv" class="row" style="display:block">
										<div class="col-xs-12 col-sm-12 m10">
											<label for="email"><b>What price do you need :</b></label>
											<div class="row">
											<div class="col-xs-11 col-sm-6">
											<?= $form->textFieldGroup($model, 'uvr_bid_amount', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Bid Amount')))) ?>
											<span id="errId" style="color: #F25656"></span>
											</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="col-xs-12 col-sm-7">
					
					
					<div id="venjoin1">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>First name :</b></label>
								<?= $form->textFieldGroup($model, 'uvr_vnd_name', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter First Name')))) ?>

							</div>
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>Last name :</b></label>
								<?= $form->textFieldGroup($model, 'uvr_vnd_lname', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Last Name')))) ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="phone"><b>Phone number :</b></label>
								<div class="row">
									<div class="col-xs-3">
										<?= CHtml::textField("countryCode", '91', ['id' => 'countryCode', 'placeholder' => "Country Code", 'class' => "form-control", 'required' => 'required', 'value' => '91', 'readOnly' => true]) ?>
									</div>
									<div class="col-xs-9 pl0">
										<?= $form->textFieldGroup($model, 'uvr_vnd_phone', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone Number')))) ?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-md-6">
								<label for="email"><b>Email address :</b></label>
								<?= $form->textFieldGroup($model, 'uvr_vnd_email', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email Id')))) ?>
								<span id="errId" style="color: #F25656"></span>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="city"><b>What city do you do most business in :</b></label>
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'uvr_vnd_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%', 'class'	 => 'ctyCheck'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
												  populateSource(this, '{$buvModel->buvBkg->bkg_from_city_id}');
																}",
								'load'			 => "js:function(query, callback){
										loadSource(query, callback);
										}",
								'render'		 => "js:{
											option: function(item, escape){
											return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
											},
											option_create: function(data, escape){
											return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
											}
										}",
									),
								));
								?>
							</div>
						</div>




						<div class="row">
							<div class="col-xs-11 col-md-8 col-lg-4 top-buffer mb20">
								<div class="Submit-button" style="text-align: left" id="vendorSubmitDiv">
									<button type="button" class="btn btn-primary" onclick="vendorRequestJoin1()">Next</button>
								</div>
							</div>
						</div> 
					</div>

					<div id="venjoin2" style="display: none;">


						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>Voter ID No</b></label>
								<?= $form->textFieldGroup($model, 'uvr_vnd_voter_no', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Voter ID No.')))) ?>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="row">
									<div class="col-xs-8">
										<label for="name"><b>Voter ID Front</b></label>
								<?= $form->fileFieldGroup($model, 'uvr_vnd_voter_id_front_path', array('label' => '', 'widgetOptions' => array())); ?>
									</div>
									<?php if($model->uvr_vnd_voter_id_front_path!='' && $model->uvr_vnd_voter_id_front_path!=NULL){?>		
									<div class="col-xs-4 mt30 text-right">
										<span id="spuvr_vnd_voter_id_front_path" style="display: none;">
									<a target="_blank" href=<?=$model->uvr_vnd_voter_id_front_path?>>
									<img src=<?=$model->uvr_vnd_voter_id_front_path?> style="width:50px;height:50px;" title="Uploaded">
								</a></span>
									</div>
									<?php } ?>
								</div>
								
								
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>Pan No</b></label>
								<?= $form->textFieldGroup($model, 'uvr_vnd_pan_no', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter PAN No')))) ?>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="row">
									<div class="col-xs-8">
										<label for="name"><b>Pan Card Front</b></label>
								<?= $form->fileFieldGroup($model, 'uvr_vnd_pan_front_path', array('label' => '', 'widgetOptions' => array())); ?>
									</div>
									<?php if($model->uvr_vnd_pan_front_path!='' && $model->uvr_vnd_pan_front_path!=NULL){?>		
									<div class="col-xs-4 mt30 text-right">
										<span id="spuvr_vnd_pan_front_path" style="display: none;">
								<a target="_blank" href=<?=$model->uvr_vnd_pan_front_path?>>
								<img src=<?=$model->uvr_vnd_pan_front_path?> style="width:50px;height:50px;" title="Uploaded">
								</a></span>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>Licence No</b></label>
								<?= $form->textFieldGroup($model, 'uvr_vnd_license_no', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Licence No')))) ?>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="row">
									<div class="col-xs-8">
										<label for="name"><b>Licence Front</b></label>
								<?= $form->fileFieldGroup($model, 'uvr_vnd_licence_front_path', array('label' => '', 'widgetOptions' => array())); ?>
									</div>
								<?php if($model->uvr_vnd_licence_front_path!='' && $model->uvr_vnd_licence_front_path!=NULL){?>		
								<div class="col-xs-4 mt30 text-right">
								<span id="spuvr_vnd_licence_front_path" style="display: none;">
								<a target="_blank" href=<?=$model->uvr_vnd_licence_front_path?>>
								<img src=<?=$model->uvr_vnd_licence_front_path?> style="width:50px;height:50px;" title="Uploaded">
								</a></span>
								</div>
								<?php } ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>Licence Expiry Date</b></label>
								<?php 
								if ($model->uvr_vnd_license_exp_date)
								{
									$model->uvr_vnd_license_exp_date1 = DateTimeFormat::DateToDatePicker($model->uvr_vnd_license_exp_date);
								}
								?>
								<?=
								$form->datePickerGroup($model, 'uvr_vnd_license_exp_date1', array('label'			 => '', 'widgetOptions'	 => array('options'		 => array('autoclose' => true,'format'	 => 'dd/mm/yyyy'
										), 'htmlOptions'	 => array('placeholder' => 'Licence Expiry Date'
										)), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>          
							</div>
							<div class="col-xs-12 col-md-6">
								<label for="name"><b>What kind of Car Business Owner are you ?</b></label>
								<?= $form->dropDownListGroup($model, 'uvr_vnd_is_driver', ['label' => '', 'widgetOptions' => ['data' => ['0' => 'I am Taxi operator with many cars.', '1' => 'I am Driver cum Owner (DCO).'], 'htmlOptions' => []]]) ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-md-8 col-lg-4 mb20">
								<div class="Submit-button" style="text-align: left" id="vendorSubmitDiv2">
									<?php echo CHtml::submitButton('Finish', array('class' => 'btn btn-primary')); ?>
								</div>
							</div>
						</div>



					</div>

					

					<div id="venjoin4" style="display: none;">
						<div class="row">
							<div class="col-xs-11 col-md-8 ml20">
								<label for="name"><b>Voter ID No</b></label>
								<span id="uvr_vnd_voter_no"><?= $model->uvr_vnd_voter_no; ?></span>
							</div>
						</div>
						
					</div>

					<?= $form->hiddenField($model, 'uvr_buv_id', ['value' => $buvId]); ?>
					<?= $form->hiddenField($model, 'uvr_id'); ?>
					
				</div>
				
			<?php $this->endWidget(); ?>	
		</div>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function () {
        resetStart();
    });


    $sourceList = null;

    function vendorRequestJoin1()
    {

        unregVendorJoinValidationAjax();

    }
    function vendorRequestJoin2()
    {
        unregVendorJoinValidationStep2();
    }
    function unregVendorJoinValidationStep2()
    {
        
        var a = $('#bookingRequestForm').serialize();
        $.ajax({
            "type": 'POST',
            "dataType": 'text',
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/unregvendorvalidation2')) ?>",
            data: $('#bookingRequestForm').serialize(),
            success: function (data)
            {
                if (data > '0')
                {
                    $('#venjoin1').css('display', 'none');
                    $('#venjoin2').css('display', 'none');
                    $('#venjoin3').css('display', 'block');

                } else
                {
                    //console.log(data);
                    var data1 = JSON.parse(data);
                    if ($('#<?= CHtml::activeId($model, "uvr_vnd_license_no") ?>').val() == '')
                    {
                        $('#<?= CHtml::activeId($model, "uvr_vnd_license_no") ?>').parent().addClass('has-error');
                        $('#<?= CHtml::activeId($model, "uvr_vnd_license_no") ?>').parent().find('.help-block').css('display', 'block');
                        $('#<?= CHtml::activeId($model, "uvr_vnd_license_no") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_vnd_license_no[0]);
                    }
                }
            },
            error: function () {
                alert('error');
            }
        });
        event.preventDefault();
    }
    function resetFinish()
    {
        $('#venjoin1').css('display', 'none');
        $('#venjoin2').css('display', 'none');
        $('#venjoin3').css('display', 'block');
        $('#venjoin4').css('display', 'none');
		$('#editBidAmountDiv').hide();
		$('#showBidAmountDiv').show();
		$('#headerTxt').text('Thank you. We will contact you shortly about this trip.');
    }
    function resetStart()
    {
        $('#venjoin1').css('display', 'block');
        $('#venjoin2').css('display', 'none');
        $('#venjoin3').css('display', 'none');
        $('#venjoin4').css('display', 'none');
    }

    function resetExistStatus()
    {
        $('#venjoin1').css('display', 'none');
        $('#venjoin2').css('display', 'none');
        $('#venjoin3').css('display', 'none');
        $('#venjoin4').css('display', 'block');
    }

    function resetNewStatus()
    {
        $('#venjoin1').css('display', 'none');
        $('#venjoin2').css('display', 'block');
        $('#venjoin3').css('display', 'none');
        $('#venjoin4').css('display', 'none');
		$('#headerTxt').text('Thanks for price. Now, lets create your Gozo vendor account!');
    }

    function unregVendorJoinPreFill()
    {
        $href = '<?= Yii::app()->createUrl('index/unregvendorprefill') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            data: {'BuvId': $('#UnregVendorRequest_uvr_buv_id').val(), 'FName': $('#UnregVendorRequest_uvr_vnd_name').val(), 'LName': $('#UnregVendorRequest_uvr_vnd_lname').val(), 'Phone': $('#UnregVendorRequest_uvr_vnd_phone').val(), 'Address': $('#UnregVendorRequest_uvr_vnd_address').val(), 'Email': $('#UnregVendorRequest_uvr_vnd_email').val()},
            success: function (data)
            {

                if (data != '1')
                {
                    resetStart();
                    var data2 = JSON.parse(data);
                    $('#<?= CHtml::activeId($model, "uvr_id") ?>').val(data2.uvr_id);
                    $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').val(data2.uvr_vnd_name);
                    $('#<?= CHtml::activeId($model, "uvr_vnd_lname") ?>').val(data2.uvr_vnd_lname);
                    $('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').val(data2.uvr_vnd_phone);
                    $('#<?= CHtml::activeId($model, "uvr_vnd_email") ?>').val(data2.uvr_vnd_email);
                }
            },
            error: function () {
                alert('error');
            }
        });
    }

    function unregVendorJoinValidationAjax()
    { 
        if (isNaN($('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').val()) == false)
        {
            $href = '<?= Yii::app()->createUrl('index/unregvendorvalidation') ?>';
            jQuery.ajax({type: 'GET', url: $href,
                data: {'BuvId': $('#UnregVendorRequest_uvr_buv_id').val(), 'FName': $('#UnregVendorRequest_uvr_vnd_name').val(), 'LName': $('#UnregVendorRequest_uvr_vnd_lname').val(), 'Phone': $('#UnregVendorRequest_uvr_vnd_phone').val(), 'Address': $('#UnregVendorRequest_uvr_vnd_address').val(), 'Email': $('#UnregVendorRequest_uvr_vnd_email').val(), 'Bidamount': $('#UnregVendorRequest_uvr_bid_amount').val(), 'City': $('#UnregVendorRequest_uvr_vnd_city_id').val()},
                success: function (data)
                {
                    var res = data.split("~");
                    var type = res[0];
                    if (type != '')
                    {
                        if (type == 'existing')
                        {
                            resetNewStatus();
                            var data2 = JSON.parse(res[1]);
							var nowDate = new Date(data2.uvr_vnd_license_exp_date);
							
							//$("#uvr_vnd_license_exp_date").datepicker({ dateFormat: "dd/mm/yy" }).val(data2.uvr_vnd_license_exp_date)                            
							$('#<?= CHtml::activeId($model, "uvr_id") ?>').val(data2.uvr_id);
							$('#<?= CHtml::activeId($model, "uvr_vnd_voter_no") ?>').val(data2.uvr_vnd_voter_no);
                            $('#<?= CHtml::activeId($model, "uvr_vnd_aadhaar_no") ?>').val(data2.uvr_vnd_aadhaar_no);
                            $('#<?= CHtml::activeId($model, "uvr_vnd_pan_no") ?>').val(data2.uvr_vnd_pan_no);
							$('#<?= CHtml::activeId($model, "uvr_vnd_license_no") ?>').val(data2.uvr_vnd_license_no);
							$('#<?= CHtml::activeId($model, "uvr_vnd_license_exp_date") ?>').val(data2.uvr_vnd_license_exp_date2);
							if(data2.uvr_vnd_voter_id_front_path!='')
							{
								$('#spuvr_vnd_voter_id_front_path').show();
								$('#uvr_vnd_voter_id_front_path').val(data2.uvr_vnd_voter_id_front_path)
							}
							if(data2.uvr_vnd_pan_front_path!='')
							{
								$('#spuvr_vnd_pan_front_path').show();
								$('#uvr_vnd_pan_front_path').val(data2.uvr_vnd_pan_front_path)
							}
							if(data2.uvr_vnd_licence_front_path!='')
							{
								$('#spuvr_vnd_licence_front_path').show();
								$('#uvr_vnd_licence_front_path').val(data2.uvr_vnd_licence_front_path)
							}
                        } else if (type == 'new')
                        {
                            resetNewStatus();
                            var id = res[1];
                            $('#<?= CHtml::activeId($model, "uvr_id") ?>').val(id);
                        } else if (type == 'error')
                        {
                            //console.log(data);
                            var data1 = JSON.parse(res[1]);
                            //var data1 = JSON.parse(data);
                            if ($('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').val() == '')
                            {
                                $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').parent().addClass('has-error');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').parent().find('.help-block').css('display', 'block');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_name") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_vnd_name[0]);
                            }
                            if ($('#<?= CHtml::activeId($model, "uvr_vnd_lname") ?>').val() == '')
                            {
                                $('#<?= CHtml::activeId($model, "uvr_vnd_lname") ?>').parent().addClass('has-error');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_lname") ?>').parent().find('.help-block').css('display', 'block');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_lname") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_vnd_lname[0]);
                            }
                            if ($('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').val() == '' || data1.hasOwnProperty('UnregVendorRequest_uvr_vnd_phone'))
                            {
                                $('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').parent().addClass('has-error');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').parent().find('.help-block').css('display', 'block');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_phone") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_vnd_phone[0]);
                            }
                           
                            if ($('#<?= CHtml::activeId($model, "uvr_vnd_city_id") ?>').val() == '')
                            {
                                if ($('#<?= CHtml::activeId($model, "uvr_vnd_city_id") ?>').parent().hasClass('has-error') == false)
                                {
                                    $('#<?= CHtml::activeId($model, "uvr_vnd_city_id") ?>').parent().append('<div class="help-block error"></div>');
                                }
                                $('#<?= CHtml::activeId($model, "uvr_vnd_city_id") ?>').parent().addClass('has-error');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_city_id") ?>').parent().find('.help-block').css('display', 'block');
                                $('#<?= CHtml::activeId($model, "uvr_vnd_city_id") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_vnd_city_id[0]);
                            }

                            if ($('#<?= CHtml::activeId($model, "uvr_bid_amount") ?>').val() == '')
                            {
                                $('#<?= CHtml::activeId($model, "uvr_bid_amount") ?>').parent().addClass('has-error');
                                $('#<?= CHtml::activeId($model, "uvr_bid_amount") ?>').parent().find('.help-block').css('display', 'block');
                                $('#<?= CHtml::activeId($model, "uvr_bid_amount") ?>').parent().find('.help-block').text(data1.UnregVendorRequest_uvr_bid_amount[0]);
                            }
                        }
                    }
                    //alert('success');
                },
                error: function () {
                    alert('error');
                }
            });
        }
    }


    function populateSource(obj, cityId)
    {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 0, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=0&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
</script>
