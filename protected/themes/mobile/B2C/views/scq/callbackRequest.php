<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<style>
	.form-control.input-horizontal    {
        display: inline !important;
		width: auto !important; 
    }
.pl82{ padding-left: 82px!important;}
.form-control{
    display: block;
    width: 100%;
    height: calc(1.4em + 0.94rem + 3.7px);
    padding: 0.47rem 0.8rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.4;
    color: #475F7B;
    background-color: #FFFFFF;
    background-clip: padding-box;
    border: 1px solid #DFE3E7;
    border-radius: 0.267rem;
    transition: border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;
}

</style>

<div class=" container ">
	<div class="panel panel-primary">
		<div class="panel-body">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'nwbkgCallback-form',
				'enableClientValidation' => TRUE,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
//				'action'				 => Yii::app()->createUrl('index/storeCallBackData'),
				'htmlOptions'			 => array(
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			/** @var Users $umodel */
			echo $form->hiddenField($model, 'scq_follow_up_queue_type');
			?>
			<div class="p20 pl30 pt0 border">

				<div class="row mt10">
					<div class="col-sm-12">
						<div class="row">
                                                    <label class="col-sm-4">First Name:</label>
							<div  class="form-control col-sm-6"><?php echo $umodel->usr_name ?></div>
						</div>
					</div>
				</div>
				<div class="row mt15">
					<div class="col-sm-12">
						<div class="row">
                                                    <label class="col-sm-4">Last Name:</label>
							<span  class="form-control col-sm-6"><?php echo $umodel->usr_lname ?></span>
						</div>
					</div>

				</div>
				<div class="row mt15">
					<div class="col-sm-12">
						<div class="row">
                                                    <label class="col-sm-4">Email:</label>
							<span  class="form-control col-sm-6"><?php echo $umodel->usr_email ?></span>
						</div>
					</div>

				</div>
				<div class="row mt15">
					<div class="col-sm-12">
						<div class="row">
                                                    <label class="col-sm-4">Reason:</label>
							<span  class="form-control col-sm-6"><?php echo ServiceCallQueue::getReasonList($refType) ?></span>	
						</div>
					</div>

				</div>
				<?php 
				
				if (in_array($refType, [2, 4]))
				{	
					?>
					<div class="row mt15">
						<div class="col-sm-12 ">
							<div class="row">
								<label class="col-sm-4" for="ServiceCallQueue_scq_related_bkg_id">Booking Id <?php ?><span class="<?php echo ($refType == 2) ? '' : 'hide' ?>" style="color: red;font-size: 15px;">*</span>: </label>
								<?php echo $form->textField($model, 'scq_related_bkg_id', ['class' => 'form-control input-horizontal col-sm-6', 'placeholder' => "Starting with OW/RT/MW"]) ?> 
								<span class="col-sm-12 pr0 " id="bkgValTxt" style="display: none"></span>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php $isPhoneExist = ($primaryPhone != '' && Filter::validatePhoneNumber($primaryPhone)); ?>
				<div class="row mt15">
					<div class="col-12 col-xl-12 mb5 <?=($isPhoneExist) ? "":"hide"?>">
						<div class="row">
							<label class="col-12 col-sm-4">Phone: </label>
							<div class="col-12 col-sm-8">
								<div class="input-group">
									<span  class="form-control"><?php echo "+".$primaryPhone ?></span>
									<div class="input-group-append mt10" id="button-addon2">
										<button class="btn-green pl15 pr15 default-link font-12" type ="button" id="addphoneBtn"><strong>Add new phone</strong></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 mt5 <?=(!$isPhoneExist) ? "":"hide"?>" id="contactNo">
						<div class="row">
							<label class="col-12 col-sm-4">Enter Phone: </label>						 
							<div class="col-12 col-sm-8">
								<?php 
                                        $this->widget('ext.intlphoneinput.IntlPhoneInput', array(
											'model'					 => $model,
											'attribute'				 => 'scq_to_be_followed_up_with_value',
											'codeAttribute'			 => 'countrycode',
											'numberAttribute'		 => 'scq_to_be_followed_up_with_value',
											'options'				 => array(// optional
												'separateDialCode'	 => true,
												'autoHideDialCode'	 => true,
												'initialCountry'	 => 'in'
											),
											'htmlOptions'			 => ['class' => 'form-control phoneno pl82', 'value' => $primaryPhone,'id' => 'fullContactNumber', 'style' => 'width:307px;', 'required' => true],
											'localisedCountryNames'	 => false,
										));
								
								?>
								<span class="col-sm-4 pr0" id="phnValText" style="display: none"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt10">
					<div class="col-sm-12">

						<div class="row mt10">
							<div class="col-xs-12">
								<label>Notes for customer agent <span style="color: red;font-size: 15px;">*</span>:</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<?php echo $form->textArea($model, 'scq_creation_comments', ['class' => "form-control scqnotes", 'style' => "height: auto", 'placeholder' => "Enter Description", "cols" => "50", "rows" => "3"]) ?>
							</div>
						</div>

						<div class="row mt10">
							<div class="text-right">  
								<button type="button" class="btn-2" name="downloadBtn" id="registerCMB">CALL ME BACK</button>
							</div> 
						</div>	 
					</div>

				</div>

			</div>
<!--			<div id="waitTime" class="pl20 pt0"><span style="color: red;font-size: 15px;"><sup>*</sup></span>Expected call back time: 60 minutes</div>-->
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div> 
<script type="text/javascript">
    $('#addphoneBtn').click(function ()
    {
        $('#contactNo').show();
    })
    $('#registerCMB').click(function ()
    {	
        validateBookingid();
    });
    function registerCMB() {
		var obj1 = document.getElementById("ServiceCallQueue_scq_to_be_followed_up_with_value");
        if ($("#ServiceCallQueue_scq_creation_comments").val() == '')
        {
            var obj2 = document.getElementById("ServiceCallQueue_scq_creation_comments");
            obj2.focus();
            return false;
        }
        if ($("#ServiceCallQueue_scq_to_be_followed_up_with_value").val() == '')
        {
            obj1.focus();
            return false;
        }
        $href = "<?php echo Yii::app()->createUrl('scq/storeCallBackData?ismobile=1') ?>";
        jQuery.ajax({type: 'POST',
            url: $href,
            data: $('#nwbkgCallback-form').serialize(),
            "dataType": "html",
            success: function (data)
            {	
                $('#sidebar-right-overcallback').removeClass('fade');
                $('#sidebar-right-overcallback').css("display", "block");
                $('#callmebackmessagebody').html(data);
            }
        });
    }
    function validatePhoneno() {

        var phone = $('#ServiceCallQueue_scq_to_be_followed_up_with_value').val();
        $href = "<?php echo Yii::app()->createUrl('lookup/validatePhone') ?>";
        jQuery.ajax({type: 'GET',
            "url": $href,
            data: {'phone': phone},
            "dataType": "json",
            success: function (data1)
            {
                $('#phnValText').css('color', 'green');
                $('#phnValText').html('<span style="color: red;font-size: 15px;">*</span>Invalid phone number');
                $('#phnValText').show();
                if (!data1.success) {
                    $('#phnValText').css('color', 'red');
                    $("#ServiceCallQueue_scq_to_be_followed_up_with_value").focus();
                    return false;
                }
                registerCMB();
            }
        });

    }
    function validateBookingid() {

        var refid = $('#ServiceCallQueue_scq_related_bkg_id').val();
        var reftype = $('#ServiceCallQueue_scq_follow_up_queue_type').val();
        $href = "<?php echo Yii::app()->createUrl('lookup/validateBooking') ?>";
        jQuery.ajax({type: 'GET',
            "url": $href,
            data: {'refid': refid, 'reftype': reftype},
            "dataType": "json",
            success: function (data1)
            {
                $('#bkgValTxt').css('color', 'green');
                $('#bkgValTxt').html('<i class="fa fa-check"></i>');
                $('#bkgValTxt').show();
                if (!data1.success)
                {
                    data1.flag == 1 ? $('#bkgValTxt').html('<span style="color: red;font-size: 15px;">*</span> Booking id required') : $('#bkgValTxt').html('<i class="fa fa-times"></i> Wrong booking id');
                    $('#bkgValTxt').css('color', 'red');
                    return false;
                }
                validatePhoneno();
            }
        });

    }
</script>
