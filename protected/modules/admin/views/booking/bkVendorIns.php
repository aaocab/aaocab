<?php
/* @var $form TbActiveForm */
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'vendorInsForm', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
                    $.ajax({
                    "type":"POST",
                    "dataType":"json",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/create')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
                        if(data1.success){
                        alert(data1.message);
                        location.href=data1.url;						 
						if(data1.url1!=""){
						window.open(data1.url1, "_blank");
						}
						return false;
                        } else{
						$("#btnQuote").attr("disabled", false);
                        $("#btnsbmt").attr( "disabled", false );
						$("#btnQuote").html("Create Quote");
						$("#btnsbmt").html("Submit");
						$("#bkErrors").removeClass("hide");
                            var errors = data1.errors;
                            settings=form.data(\'settings\');
                             $.each (settings.attributes, function (i) {
                                $.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
                              });
							$.each(errors, function(k,v){
								$("#bkErrors ul").append("<li>" + v + ".</li>");
								$(document).scrollTop(0);
							});
                              $.fn.yiiactiveform.updateSummary(form, errors);
                            } 
                        },
                     error: function(xhr, status, error){
                       var x= confirm("Network Error Occurred. Do you want to retry?");
                       if(x){
                                $("#booking-form").submit();
                            }
                            else{
								$("#btnQuote").attr("disabled", false);
								$("#btnsbmt").attr( "disabled", false );
								$("#btnQuote").html("Create Quote");
								$("#btnsbmt").html("Submit");
                            }
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
		'class' => '',
	),
		));
?>

<?= CHtml::textField("jsonData_vendorIns", $data, ['id' => 'jsonData_vendorIns']) ?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<h3 class="pl15"> VENDOR/DRIVER Instruction</h3>
			<p></p>
			<div class="panel-body pt0">
				<div class="row">
				     <div class="col-sm-6 mb10">
						<span id="instruction"></span>
					</div>
                  
					<div class="col-sm-6 hide">
						<?= $form->textAreaGroup($model, 'bkg_instruction_to_driver_vendor', array('label' => 'Instructions to Vendor/Driver', 'widgetOptions' => array('htmlOptions' => array('style' => 'min-height:90px', 'placeholder' => 'Add customer requirements in customer special requests section. In this box, write instructions that will be sent to vendor and driver ONLY.', 'readonly')))) ?>
					</div>
					
					<div class="col-sm-6 col-sm-offset-6">
						<?= $form->textAreaGroup($prfModel, 'bkg_pref_req_other', array('label' => 'Other instructions', 'widgetOptions' => array('htmlOptions' => ['placeholder' => '']))) ?>
					</div>   

					<div class="row">

						<div class="col-xs-12 pb10" style="text-align:center;">
							<?php
							if ($createQuote){?>
								<button type="button" class="btn btn-info" style="font-size:1.4em;" id="btnQuote">Create Quote</button>
								<?php }
							elseif ($prfModel->bkg_is_gozonow == 1) { ?>
								<button type="button" class="btn btn-primary btn-sm pl50 pr50 mt10" style="font-size:1.4em;" id="btnQuote">Create Gozo-now Booking</button>
								<?php }
							else { ?>
								<button type="button" class="btn btn-primary btn-lg pl50 pr50" style="font-size:1.4em;" id="btnsbmt">Submit</button>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
<script>
	var jsonData = JSON.parse($("#jsonData_vendorIns").val());
	var isGozoNowClicked = 0;
	$(document).ready(function () {
		admBooking.vendorInstruction(jsonData);
	});

	$('#btnsbmt').click(function () {
		
		if(isGozoNowClicked==1)
		{ 
			submitForm();
		}
		else
		{ 
			$('#bkErrors').addClass('hide');
			$("#btnsbmt").attr("disabled", true);
			$("#btnsbmt").html("<i class=\'fa fa-circle-o-notch fa-spin\'></i>Loading");
			$('#vendorInsForm').submit();
		}
	});

	$('#btnQuote').click(function () {
		submitForm();
	});
	
	
	function submitForm()
	{
		$('#bkErrors').addClass('hide');
		if (jsonData.trip_user == 1)
		{
			$("#btnQuote").attr("disabled", true);
			$("#btnQuote").html("<i class=\'fa fa-circle-o-notch fa-spin\'></i>Loading");
			$('#vendorInsForm').submit();

		}
		else
		{
			alert('You can not select agent on create quote');
		}
	}
	
</script>