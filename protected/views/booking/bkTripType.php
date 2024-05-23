<style>
	.menu-trip-type-len{
		width: 60%;
	}
</style>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'bookingTrip',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));
/* @var $form TbActiveForm */

// Element Name & Id
$tripTypeElemName	 = CHtml::activeName($model, "bkg_booking_type");
$tripTypeElemId		 = CHtml::activeId($model, "bkg_booking_type");

// Booking Type
$bkgtype = $model->bkg_booking_type;

// Active Class
$arrActive			 = array_fill(1, 4, '');
$arrActive[$bkgtype] = 'active';

#${"active$bkgtype"} = "active";

/* echo "bkg_booking_type == " . $model->bkg_booking_type;
  echo "<br>NAME == " . $tripTypeElemName;
  echo "<br>ID == " . $tripTypeElemId;
  die('<br>FF'); */

?>
<?= $form->errorSummary($model); ?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id11', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash11', 'class' => 'clsHash']); ?>
<input type="hidden" id="step" name="step" value="0">

<div class="row menuTripType">
	<div class="col-xs-12 col-sm-9 col-md-8 col-lg-12">
		<div class="row trip_btn" data-toggle="buttons">
			<div class="col-xs-6 p5">
				<div class="button menu-trip-type-len float-right raised clickable <?= $arrActive[1] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_0" onclick="$jsBookNow.changeTripType(this)" value="1"> <div class="anim"></div><span>One way<br/>Trip</span>
				</div>
			</div>
<!--			<div class="col-xs-6 p5">
				<div class="button raised clickable <?//= $arrActive[2] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?//= $tripTypeElemName ?>" id="<?//= $tripTypeElemId ?>_1" onclick="$jsBookNow.changeTripType(this)" value="2"> <div class="anim"></div><span>Round Trip</span>
				</div>
			</div>-->
			<div class="col-xs-6 p5">
				<div class="button menu-trip-type-len raised clickable <?= $arrActive[3] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_2" onclick="$jsBookNow.changeTripType(this)" value="3"> <div class="anim"></div><span>Round Trip or<br/>Multi City</span>
				</div>
			</div>
			<div class="col-xs-6 p5">
				<div class="button menu-trip-type-len float-right raised clickable <?= $arrActive[4] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_3" onclick="$jsBookNow.changeTripType(this)" value="4"> <div class="anim"></div><span>Airport Transfer</span>
				</div>
			</div>
			<div class="col-xs-6 p5">
				<div class="button menu-trip-type-len raised clickable <?= $arrActive[7] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_6" onclick="$jsBookNow.changeTripType(this)" value="7"> <div class="anim"></div><span>Shuttle</span>
				</div>
			</div>
            <div class="col-xs-6 p5">
				<div class="button menu-trip-type-len float-right raised clickable <?= $arrActive[9] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_8" onclick="$jsBookNow.changeTripType(this)" value="9"> <div class="anim"></div><span>Day Rental</span>
				</div>
			</div>
			<div class="col-xs-6 p5">
				<div class="button menu-trip-type-len raised clickable <?= $arrActive[5] ?>">
					<input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_5" onclick="$jsBookNow.changeTripType(this)" value="5"> <div class="anim"></div><span>Packages</span>
				</div>
			</div>
		</div>
	</div>
<?php
$dboApplicable = Filter::dboApplicable($model);
if ($dboApplicable)
{
?>
	<div class="col-sm-3 col-md-4 col-lg-4 text-right pr40">
    <a href="/terms/doubleback" target="_blank"><img src="/images/doubleback_fares2.jpg?v=0.2" alt="" class="img-responsive"></a>
	</div>
<?php
}
?>
</div>  
<?php $this->endWidget(); ?>

