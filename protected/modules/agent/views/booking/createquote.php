<style type="text/css">
    .page-wrapper-row 
    {
        margin-top:-52px; 
    }
</style>
<?
$version = Yii::app()->params['siteJSVersion'];
$api	 = Yii::app()->params['googleBrowserApiKey'];
$bType	 = Booking::model()->getBookingType(0, 'Trip');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
//	'disabled';
?>
<div class="container">
    <div class="portlet light"><div class="portlet-body pt0">

			<?php
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'bookingtime-form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                                    
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/createquote')) . '",
						"data":form.serialize(),
                        "beforeSend": function(){
                            ajaxindicatorstart("");
                        },
                        "complete": function(){
                            ajaxindicatorstop();
                        },
						"success":function(data2){                         
							var data = "";
							var isJSON = false;
							try {
								data = JSON.parse(data2);
                               
								isJSON = true;
							} catch (e) {
							}
							if(!isJSON){
							                           
							}
							else
							{
								var errors = data.errors;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
								messages = errors;
								content = \'\';
								var summaryAttributes = [];
								for (var i in settings.attributes) {
									if (settings.attributes[i].summary) {
										summaryAttributes.push(settings.attributes[i].id);
									}
								}
								displayError(form, messages);
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
			<?= $form->errorSummary($model); ?>
			<?= CHtml::errorSummary($model); ?>
			<input type="hidden" id="step" name="step" value="cquote">
			<?= $form->hiddenField($model, 'bkg_booking_type', ['id' => 'bkg_booking_type', 'value' => '']); ?>

			<div class="row text-center mb10 mt10">
				<div class="col-xs-12 label-menu">
					<div class="label-menu-list" data-toggle="buttons">
						<label class="btn btn-primary p10 mb10 label-menu-panel <?= $active1 ?>" onclick="showroute('1')">
							<input  type="radio" name="BookingTemp[bkg_booking_type1]" value="1" id="BookingTemp_bkg_booking_type_0" <?= $checked1 ?> > One way Trip
						</label>
<!--						<label class="btn btn-primary p10 <?//= $active2 ?>" onclick="showroute('2')">
							<input type="radio" name="BookingTemp[bkg_booking_type1]" value="2" id="BookingTemp_bkg_booking_type_1" <?//= $checked2 ?> > Round Trip
						</label>-->
						<label class="btn btn-primary p10 mb10 label-menu-panel <?= $active3 ?>" onclick="showroute('3')">
							<input type="radio" name="BookingTemp[bkg_booking_type1]" id="BookingTemp_bkg_booking_type_2" value="3" <?= $checked3 ?> > Round Trip or Multi City
						</label>
						<label class="btn btn-primary p10 mb10 label-menu-panel <?= $active4 ?>" onclick="showroute('4')">
							<input type="radio" name="BookingTemp[bkg_booking_type1]" id="BookingTemp_bkg_booking_type_3" value="4" <?= $checked4 ?> > Airport Transfer
						</label>
	                    <label class="btn btn-primary p10 mb10 label-menu-panel <?= $active5 ?>" onclick="showroute('5')">
							<input type="radio" name="BookingTemp[bkg_booking_type1]" id="BookingTemp_bkg_booking_type_5" value="5" <?= $checked5 ?> > Day Rental
						</label>
					</div>
				</div>
				<?
				//=
				//$form->radioButtonListGroup($model, 'bkg_booking_type', ['label' => '', 'widgetOptions' => array('data' => $bType,'htmlOption'=>['class'=>'btn-group btn-group-justified',]), 'inline' => true]);
				?>
			</div>
			<div class="alert alert-block alert-danger" id="diverror" style="display: none">
			</div>
			<div class="row" id="routedata" style="display: none">

			</div>
<div class="row">
			<div class="col-xs-12" id="subbtn" style="display: none">
				<div class="text-center mb20 mt20" id="btnsub" style="display: block">
					<?= CHtml::submitButton('Create Quote', array('class' => 'btn btn-success btn-lg pl40 pr40', 'id' => "quoteBtn")); ?>
				</div>
			</div>
</div>
			<?php $this->endWidget(); ?>

			<div class="pb40" id="cabdatadata" style="display: none">
			</div>
        </div></div></div>



<?php
//$this->renderPartial('popupform', ['model' => $model]);
?>
<script type="text/javascript">
	var errors;
	var errorArr;
	var demoP;
	$(document).ready(function () {
		$('#diverror').html('');
		$('#cabdatadata').html('');
	});

	$('#bookingtime-form').submit(function (event) {
	
		$('#diverror').html('');
		$('#diverror').hide();
//         $('#routedata').html('');
		$('#cabdatadata').html('');
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/createquote')) ?>",
			"data": $('#bookingtime-form').serialize(),
			"success": function (data1)
			{
				var data3 = "";
				var isJSON = false;
				try {
					data3 = JSON.parse(data1);
					isJSON = true;
					if (!data3.success) {
						errors = data3.errors;
						$('#diverror').html('');
						demoP = '';
						Object.values(errors).forEach(function (errorstr) {
							demoP = demoP + errorstr + "<br>";
						});
						$('#diverror').show();
						$('#diverror').html(demoP);
					}
				} catch (e) {

				}
				if (!isJSON) {
					$('#cabdatadata').html(data1);
					$('#cabdatadata').show();
				}
			}
		});
		event.preventDefault();
		//}
	});

	function showroute(obj) {
		ajaxindicatorstart("");
		$('#diverror').hide();
		$('#diverror').html('');
//        $('#routedata').hide();
//        $('#routedata').html('');
		var bktype = obj;
		// alert(obj);
		$('#bkg_booking_type').val(bktype);
		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/agtroute')) ?>",
			"data": {"bktype": bktype},
			"async": false,
			"success": function (data1)
			{
				$('#routedata').html(data1);
				$('#subbtn').show();
				$('#quoteBtn').removeClass("");
				if (bktype == 3) {
					$('#quoteBtn').addClass("");
				}
				$('#routedata').show();
//                if (bktype == 3) {
//                    $('#addroutedata').show();
//                } else {
//                    $('#addroutedata').hide();
//                }
			}
		});
		ajaxindicatorstop("");
	}

	function disableTab(tabNo) {
	}

</script>
