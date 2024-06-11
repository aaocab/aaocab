<style>
	.btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg{ padding: 10px;}
</style> 
<?
//$autoAddressJSVer		 = Yii::app()->params['autoAddressJSVer'];
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/autoAddress.js?v=$autoAddressJSVer");
$locFrom				 = [];
$locTo					 = [];
$additionalAddressInfo	 = "Building No./ Society Name";
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
?>

<div class="container mt50">
	<!--    <div class="row mb20">
			<div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
		</div>-->
    <div class="row spot-panel">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'create-trip', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('agent/booking/spot'),
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		/* @var $form TbActiveForm */


		echo $form->hiddenField($model, 'bkg_booking_type');
		?>
		<?= $form->errorSummary($model); ?>
        <input type="hidden" name="step" value="8">
		<?= $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]); ?> 
		<?
		$j		 = 0;
		$cntRt	 = sizeof($model->bookingRoutes);
		
		?>
		<?php
			$this->renderPartial('pickupLocationWidget', ['model' => $model,'spotBooking'=>true], false, false);
		?>	

<!--        <div class="col-xs-12 mt30 pr30">
			<button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step8ToStep7"><b> <i class="fa fa-arrow-left"></i> Previous</b></button><button type="submit" class="  pull-right btn btn-primary btn-lg pl50 pr50 pt30 pb30"  name="step8submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
        </div>-->
<?php $this->endWidget(); ?>
    </div>
</div>
<script src="/js2/isotope.js"></script>
<script src="/js2/imagesloaded.js"></script>
<script src="/js2/smoothscroll.js"></script>
<script src="/js2/wow.js"></script>
<script src="/js2/custom.js"></script>
<?php //$api = Yii::app()->params['googleBrowserApiKey']; 
$api	= Config::getGoogleApiKey('browserapikey');
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<script type="text/javascript">
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
	
    booking_type = '<?= $model->bkg_booking_type ?>';
    transfer_type = '<?= $model->bkg_transfer_type ?>';
    $(document).ready(function () {
        //initializepl(booking_type, transfer_type);
    });

</script>