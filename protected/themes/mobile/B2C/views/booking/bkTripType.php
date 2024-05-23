<?php
$form = $this->beginWidget('CActiveForm', array(
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
/* @var $form CActiveForm */

// Element Name & Id
$tripTypeElemName	 = CHtml::activeName($model, "bkg_booking_type");
$tripTypeElemId		 = CHtml::activeId($model, "bkg_booking_type");

// Booking Type
$bkgtype = $model->bkg_booking_type;

// Active Class
$arrActive = array_fill(1, 5, '');
$arrActive[$bkgtype] = 'active';

#${"active$bkgtype"} = "active";

/*echo "bkg_booking_type == " . $model->bkg_booking_type;
echo "<br>NAME == " . $tripTypeElemName;
echo "<br>ID == " . $tripTypeElemId;
die('<br>FF');*/

?>
<?= $form->errorSummary($model); ?>
<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id11', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash11', 'class' => 'clsHash']); ?>
<input type="hidden" id="step" name="step" value="0">
            <div class="widget-content-bg page-bknw box-tab content-padding mb0"  id="menuTripType">
                <ul data-toggle="buttons">
                    <li><a href="Javascript:void(0)"  class="clickable <?= $arrActive[1]?> devclick default-link" data-check="<?= $tripTypeElemId ?>_0">
                         <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-arrow-right color-blue"></i></h1>
						 <h5 class="center-text color-gray line-height16">One way<br/>Trip</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_0"  value="1" onclick="$jsBookNow.changeTripType($('#<?= $tripTypeElemId ?>_0'))" style="display:none">
                        </a>
                    </li>
<!--                    <li><a href="Javascript:void(0)"  class="clickable <?//= $arrActive[2] ?> devclick default-link" data-check="<?//= $tripTypeElemId ?>_1">
                            <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-exchange-alt"></i></h1>
                            <h5 class="center-text text-uppercase">Round Trip</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?//= $tripTypeElemName ?>" id="<?//= $tripTypeElemId ?>_1" value="2" onclick="$jsBookNow.changeTripType($('#<?//= $tripTypeElemId ?>_1'))" style="display:none"> 
                        </a>
                    </li>-->
                    <li><a href="Javascript:void(0)"  class="clickable <?= $arrActive[3] ?> devclick default-link" data-check="<?= $tripTypeElemId ?>_2">
                            <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-bars color-blue"></i></h1>
                            <h5 class="center-text color-gray line-height16">Round Trip or<br/>Multi City</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_2"  value="3" onclick="$jsBookNow.changeTripType($('#<?= $tripTypeElemId ?>_2'))" style="display:none">
                        </a>
                    </li>
                    <li><a href="Javascript:void(0)"  class="clickable <?= $arrActive[4] ?> devclick default-link" data-check="<?= $tripTypeElemId ?>_3">
                            <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-plane-departure color-blue"></i></h1>
                            <h5 class="center-text color-gray line-height16">Airport Transfer</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_3"  value="4" onclick="$jsBookNow.changeTripType($('#<?= $tripTypeElemId ?>_3'))" style="display:none">
                        </a>
                    </li>
                    <li><a href="Javascript:void(0)"  class="clickable <?= $arrActive[9] ?> devclick default-link" data-check="<?= $tripTypeElemId ?>_9">
                            <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-taxi color-blue"></i></h1>
                            <h5 class="center-text color-gray line-height16">Day Rental</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_9"  value="9" onclick="$jsBookNow.changeTripType($('#<?= $tripTypeElemId ?>_9'))" style="display:none">
                        </a>
                    </li>
					<li><a href="Javascript:void(0)"  class="clickable <?= $arrActive[5] ?> devclick default-link" data-check="<?= $tripTypeElemId ?>_5">
                            <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-box-open color-blue"></i></h1>
                            <h5 class="center-text color-gray line-height16">Packages</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_5"  value="5" onclick="$jsBookNow.changeTripType($('#<?= $tripTypeElemId ?>_5'))" style="display:none">
                        </a>
                    </li>
					<li><a href="Javascript:void(0)"  class="clickable <?= $arrActive[7] ?> devclick default-link" data-check="<?= $tripTypeElemId ?>_7">
                            <h1 class="ultrabold bottom-10 center-text"><i class="fas fa-bus color-blue"></i></h1>
                            <h5 class="center-text color-gray line-height16">Shuttle</h5>
                            <input type="checkbox" class="toggle newtriptype" name="<?= $tripTypeElemName ?>" id="<?= $tripTypeElemId ?>_7"  value="7" onclick="$jsBookNow.changeTripType($('#<?= $tripTypeElemId ?>_7'))" style="display:none">
                        </a>
                    </li>
                </ul>
            
		<div class="clear"></div>
	    </div>

<?php $this->renderPartial("bkBanner", ['model' => $model]); ?>

<input type="hidden" name="stepVal" id="stepVal" value="<?=$step?>">
<?php $this->endWidget();?>
<script>
$(document).ready(function () {
    $('.devclick').bind('click', function() {
        $('.active').removeClass('active')
        $(this).addClass('active');
    });
});
$(".devclick").click(function (e) {
	var checkId =$(this).data('check');
	$( "#"+checkId).trigger( "click" );
    $("#menuRoute").show();
	
});
</script> 