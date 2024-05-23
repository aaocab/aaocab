<?php
	/* @var $form TbActiveForm */
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'customerPhoneDetailsForm', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/customerInfo')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
						$("#bkErrors").addClass("hide");
						if(data1.indexOf("errors") != -1)
						{
							$("#bkErrors").removeClass("hide");
							data1 = JSON.parse(data1);
							var errors = JSON.parse(data1.errors);
							$.each(errors, function(k,v){
								$("#bkErrors ul").append("<li>" + v + ". (<a href=\'javascript:void(0)\' onclick=\'admBooking.focusErrorElm(\".btn-customerInfo\")\'>Go there</a>)</li>");
								$(document).scrollTop(0);
							});
						}
						else
						{
							$(".btn-customerInfo").addClass("disabled");
							$(".btn-customerInfo").removeClass("btn-info");
							$("#fullContactNumber2").attr("disabled",true);
							$(".selectize-control").addClass("disabled");
							$(".btn-customerPhone").addClass("disabled");
							$("#linkedusers").find(".linkuserbtn").addClass("disabled");
							$(".btn-editCustumerInfo").removeClass("hide");
							$("#bookingType").html(data1);
							$("#bookingType").removeClass("hide");
							$(document).scrollTop($("#bookingType").offset().top);
						}
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
<?= CHtml::hiddenField("jsonData_customerPhone", $data, ['id'=>'jsonData_customerPhone'])?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editCustumerInfo hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
			<h3 class="pl15">Customer Email / Phone</h3>
			<div class="panel-body pt0">
				<div class="row">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-4">
							
							</div>
							<div class="col-sm-4"> 
								<?php //echo $form->textFieldGroup($usrModel, 'bkg_contact_no2', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone', 'id' => 'fullContactNumber2')))) ?>
							<?php echo $form->textFieldGroup($usrModel, 'username', array('label' => "Enter email Id / phone no.", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter email Id / phone no.', 'id' => 'emailphone')))) ?>
	<?php //$form->textField($usrModel, "username", ["class" => "", "required" => true, "placeholder" => "Enter email Id / phone no."]) ?>
				<?php //$form->error($usrModel, "username", ["class" => "text-danger"]) ?>



</div>


							<div class="col-sm-4">
								<div class="form-group" style="float:left;padding-top: 22px;">
									<button type='button' class='btn btn-danger btn-customerPhone pl20 pr20' onclick='admBooking.showlinkedUser()'>Submit</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->renderPartial("bkCustomerInfo", ["usrModel" => $usrModel,'form' => $form ], false, false); ?>
<?php $this->endWidget(); ?>
<script>
	$(".btn-editCustumerInfo").click(function(){
		$('#bookingType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').html('');
        $('#custonerInformation,#bookingType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').addClass('hide');
		$(".btn-customerInfo").removeClass("disabled");
		$(".btn-customerInfo").addClass("btn-info");
		$("#fullContactNumber2").attr("disabled",false);
		$(".selectize-control").removeClass("disabled");
		$(".btn-customerPhone").removeClass("disabled");
		$("#linkedusers").find(".linkuserbtn").removeClass("disabled");
		$(".btn-editCustumerInfo").addClass("hide");
		
		$("#emailphone").attr("disabled",false);
	});
	
	$(".btn-customerInfo").click(function(){
		$("#customerPhoneDetailsForm").submit();
	});
</script>
