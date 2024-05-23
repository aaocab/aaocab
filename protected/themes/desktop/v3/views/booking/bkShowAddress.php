<?php
$cs					 = Yii::app()->getClientScript();
$jsVer				 = Yii::app()->params['siteJSVersion'];
$cs->registerScriptFile("/js/gozo/city.js?v=$jsVer");
$cs->registerScriptFile('/js/gozo/geocodeMarker.js?v=' . $jsVer);
$cs->registerScriptFile('/js/gozo/placeAutoComplete.js?v=' . $jsVer);
$cs->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$jsVer");
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');

$form	 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingconfirmaddress',
	'action'				 => 'booking/address',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	//'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'enctype'	 => 'multipart/form-data'
	//'onsubmit'	 => 'return saveAddressesByRoutes(this);'
	),
	));
/* @var $form CActiveForm */
?>
<?= $form->hiddenField($model, "bkg_id"); ?>

<div class="card-body" style="">

	<ul class="timeline mb-0" >
<?php

$cnt	 = count($model->bookingRoutes) - 1;

$routeDetails = "";
foreach ($model->bookingRoutes as $v)
{
	$routeDetails .= "<li class='timeline-item active pb5' >" . Cities::model()->getName($v->brt_from_city_id) . "--" . $v->brt_from_location . '</li> ';
}
$routeDetails .= "<li class='timeline-item active pb5' >" . Cities::model()->getName($model->bookingRoutes[$cnt]->brt_to_city_id) . "--" . $v->brt_to_location . '</li> ';

echo $routeDetails;
?>

	</ul>
<div class="col-12 text-right">
							<a href="#" class="color-black p5 editAddress"><img src="/images/bx-edit-alt.svg" alt="img" width="20" height="20" onclick="editAddress();" class="mr-1"></a>
						</div>

	<!-- comment --></div>
<?php $this->endWidget(); ?>
<script>
   function editAddress(callback, showPhone = 0)
		{
			
		//	 alert("yhhh");
			 var booking_id = '<?= $model->bkg_id ?>';
		
				param = {bkgid: booking_id};
		
			$.ajax({type: 'GET',
				url: '<?= Yii::app()->createUrl("booking/editAddress"); ?>',
				data: {bkgid: booking_id},
				//dataType: "json",
				success: function(data)
				{
					addressBox = bootbox.dialog({
							message: data,
							onEscape: function()
							{
								//loginCallback = null;
								if (addressBox == null)
								{
									bootbox.hideAll();
								}
								else
								{
									addressBox.modal('hide');
								}
								addressBox = null;
							}
						});
					
					
					
				}
			});
		}
</script>
