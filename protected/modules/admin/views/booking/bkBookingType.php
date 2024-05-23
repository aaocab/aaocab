<?php
	/* @var $form TbActiveForm */
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'bookingTypeForm', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/bookingType')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){ 
						$("#bkErrors").addClass("hide");
						$("#bookingRoute").html(data1);
                        admBooking.bookingTypeDetails($("#Booking_bkg_booking_type").val(),false);
						$("#bookingRoute").removeClass("hide");
						$("#paymentChangesData").val("");
						$("#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns").html("");
						$("#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns").addClass("hide");
						$(document).scrollTop($("#bookingRoute").offset().top);
                        },
                     error: function(xhr, status, error){
                      
                         }
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
			'onkeydown' => "return event.key != 'Enter';",
			'class' => '',
		),
	));
?>
<?= CHtml::hiddenField("jsonData_bookingType", $data, ['id'=>'jsonData_bookingType'])?>
<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
                <h3 class="pl20">Local travel or outstation travel?</h3>
           <div class="panel-body pt0">
                 <div class="row">
					 <?php $form->error($model, "bkg_booking_type")?>
					<div class="col-xs-12 col-md-2 text-center" id="local-block">
						<div class="panel panel-default panel-border">
							<div class="panel-body p0">
								<h3 class="text-center text-uppercase mt10">Local</h3>
								<?php echo CHtml::button('Airport', array('class' => 'btn bktype p20 font-16','data-type' => "4")); ?>
								<?php// echo CHtml::button('Railway or Bus', array('class' => 'btn bktype p20 font-16','data-type' => "15")); ?>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 text-center" id="day-rental-block">
						<div class="panel panel-default panel-border">
							<div class="panel-body p0">
								<h3 class="text-center text-uppercase mt10">Day Rental</h3>
                                <?php echo CHtml::button('4hr-40km', array('class' => 'btn bktype p20 font-16','data-type' => "9")); ?>
								<?php echo CHtml::button('8hr-80km', array('class' => 'btn bktype p20 font-16','data-type' => "10")); ?>
								<?php echo CHtml::button('12hr-120km', array('class' => 'btn bktype p20 font-16','data-type' => "11")); ?>
							</div>
						</div>
					</div>
                    <div class="col-xs-12 col-md-4 text-center">
						 <div class="panel panel-default panel-border">
							<div class="panel-body p0">
								<h3 class="text-center text-uppercase mt10">Outstation</h3>
								<?php echo CHtml::button('One way', array('class' => 'btn bktype p20 font-16','data-type' => "1")); ?>
								<?php //echo CHtml::button('Round Trip', array('class' => 'btn bktype p20 font-16','data-type' => "2")); ?>
								<?php echo CHtml::button('Round Trip/Multi City', array('class' => 'btn bktype p20 font-16','data-type' => "3")); ?>
							</div>
						 </div>
					 </div>
					<?php if(Yii::app()->user->checkAccess('customTrip' ,[],false)){?>
					<div class="col-xs-12 col-md-2 text-center">
						<div class="panel panel-default panel-border">
							<div class="panel-body p0">
								<h3 class="text-center text-uppercase mt10">FreeForm</h3>
								<?php echo CHtml::button('Create Your Own Trip', array('class' => 'btn bktype p10 font-14','data-type' => "8")); ?>
                </div>
            </div>
		</div> <?}?>
    </div>
</div> 
		</div> 
    </div>
</div> 
<?php $this->endWidget(); ?>

<script>
	var jsonData = JSON.parse($('#jsonData_bookingType').val());
	$(document).ready(function(){
//		if(jsonData.trip_user == 2)
//		{
//			$('#day-rental-block').addClass('hide');
//			$('#local-block').removeClass('col-xs-12 col-md-2 text-center');
//			$('#local-block').addClass('col-xs-12 col-md-6 text-center');
//		}
//		else
//		{
//			$('#day-rental-block').removeClass('hide');
//			$('#local-block').removeClass('col-xs-12 col-md-6 text-center');
//			$('#local-block').addClass('col-xs-12 col-md-2 text-center');
//		}
	});
	$('.bktype').click(function(event){
		var bkType = $(event.currentTarget).data('type');
		var permission = 0;
		if(bkType == 8)
		{
			<?php if(!Yii::app()->user->checkAccess('customTrip')){?>
				permission = 1;
			<?php } ?>
		}
		if(permission == 0)
		{
			$("#Booking_bkg_booking_type").val(bkType);
			$('.bktype').removeClass('btn-success');
			$(event.currentTarget).toggleClass('btn-success');
			$("#bookingTypeForm").submit();
		}
		else
		{
			alert("You haven't permission to create custom booking. Please contact your admin.");
		}
	});
</script>