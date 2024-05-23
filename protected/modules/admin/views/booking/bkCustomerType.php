<?php
	/* @var $form TbActiveForm */
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'customerTypeForm', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/customerType')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
						if($("#Booking_trip_user").val() == 2)
						{
							$("#partnerType").html(data1);
							admBooking.showB2Btype();
						}
						else
						{
						    $("#customerPhoneDetails").html(data1);
							admBooking.showB2Ctype();
						}
						admBooking.bookingPreference(booking);
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
<?php
	if ($model->trip_user == '')
	{
		$model->trip_user = 1;
	}
	if ($model->bkg_agent_id > 0)
	{
		$model->trip_user = 2;
	}
?>
<?//= $form->textFieldGroup($model, 'trip_user', ['label' => '','widgetOptions' => array('htmlOptions' => array('class' => 'hide', 'value' => $custType))]) ?>
<?= $form->hiddenField($model, 'trip_user',['value' => $custType]); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
                <h3 class="pl15">Who is this booking for</h3>
           <div class="panel-body pt0">
                <div class="row">
					<div class="col-xs-12 text-center">
						<?php $form->error($model, "bkg_user_id")?>
						<button class="btn btn-primary cust-type" type="button" id="b2c">Direct To customer<br>B2C</button>
						<button class="btn btn-primary cust-type" type="button" id="b2b">Corporate or Partner<br>B2B</button></div>
                </div>
				
			</div>
		</div> 
    </div>
</div> 
<?php $this->endWidget(); ?>
<script>
$(document).ready(function () 
{
			toastr.options = {
			  "closeButton": false,
			  "debug": false,
			  "newestOnTop": false,
			  "progressBar": false,
			  "positionClass": "toast-top-right",
			  "preventDuplicates": false,
			  "onclick": null,
			  "showDuration": "300",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			}

});
	$('.cust-type').click(function(event){
		if($(event.currentTarget).attr('id') == 'b2c')
		{
			$('#Booking_trip_user').val(1);
		}
		else
		{
			$('#Booking_trip_user').val(2);
		}
		$('#customerTypeForm').submit();
	});
</script>