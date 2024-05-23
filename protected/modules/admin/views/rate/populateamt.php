<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    .font16{ font-size: 16px;}
    .font20{ font-size: 15px;}
    .backgreen {
        background: #F6F6F6;
        color:#999999;
        /*        background: #EAFFFE;
                background: #EAFFFE;*/
    }

    .nowrap {white-space: nowrap}

</style>
<?php
$hide	 = 'backgreen';
'hide';
$sTax	 = Filter::getServiceTaxRate();

$rockBottomMargin	 = Yii::app()->params['rockBottomMargin'];
$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'rate-form', 'enableClientValidation' => true,
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
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
	),
		));
echo CHtml::hiddenField("rut_id", $route);
/* @var $form TbActiveForm */
/* @var $model Rate */
?>
<div class="container">
	<?php
	$rmodel				 = Route::model()->findByPk($route);
	?>
    <h1><?= Cities::getName($rmodel->rut_from_city_id) ?> To <?= Cities::getName($rmodel->rut_to_city_id) ?></h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default panel-border">
                <table class="table table-bordered  " width="100%">
                    <tr>
                        <th class="col-xs-2">Car Type</th>
                        <th class="col-xs-2">Vendor Amount<br>( a )</th>
                        <th class="col-xs-2">Toll Tax<br>( b )</th>
                        <th class="col-xs-2">State Tax<br>( c )</th>
						<th class="col-xs-2">Minimum Markup Percentage</th>
                        <th class="col-xs-2">Base Amount<br/> ( d = a - ( b + c ) ) </th>

                        <th class="<?= $hide ?>">Markup Applied<br/> ( m ) </th>
                        <th class="nowrap <?= $hide ?>">Sell Base Price<br/>( e = d + m% )</th>
                        <th class="nowrap <?= $hide ?>">Service Tax<br/>( f = e x gst% )</th>
                        <th class="nowrap <?= $hide ?>">Total Estm Amount<br>( e + f + b + c )</th>
                    </tr>
					<?php
					foreach ($models as $type => $model)
					{
						/* @var $model Rate */
						?>
						<tr>
							<td style="white-space: nowrap">
								<?= $form->checkBox($model, "[" . $type . "]rte_vehicletype_id", ["value" => $type]) ?>
								<?
								$model->rte_vehicletype_id	 = $type;
								$vht						 = $model->rteVehicletype->vht_VcvCatVhcType->vcv_vct_id;
								$cabDefaultMarkup			 = Quotation::model()->getCabDefaultMarkup($vht);
								echo $model->rteVehicletype->vht_make;
								?> 
								<span id="cabDefaultMarkup<?= $type ?>" style="display: none"><?= $cabDefaultMarkup ?></span>
							</td>
							<td>
								<?= $form->numberFieldGroup($model, "[" . $model->rte_vehicletype_id . "]rte_vendor_amount", array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => $model->getAttributeLabel("rte_vendor_amount"), 'onChange' => 'calvndamt(' . $type . ',' . $vht . ')')), 'groupOptions' => ['class' => 'm0'])) ?>
								<div class="text-danger" id="rtVendor<?= $type ?>" style="display: none"><?= $model->rte_vendor_amount ?></div>
							</td>
							<td>
								<?= $form->numberFieldGroup($model, "[" . $model->rte_vehicletype_id . "]rte_toll_tax", array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Toll Tax', 'onChange' => 'calvndamt(' . $type . ',' . $vht . ')')), 'groupOptions' => ['class' => 'm0'])) ?>
								<div class="text-danger"  id="rtTollTax<?= $type ?>" style="display: none"><?= $model->rte_toll_tax ?></div>
							</td>
							<td>
								<?= $form->numberFieldGroup($model, "[" . $model->rte_vehicletype_id . "]rte_state_tax", array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'State Tax', 'onChange' => 'calvndamt(' . $type . ',' . $vht . ')')), 'groupOptions' => ['class' => 'm0'])) ?>
								<div class="text-danger" id="rtStateTax<?= $type ?>" style="display: none"><?= $model->rte_state_tax ?></div>
							</td>
							<td>
								<?= $form->numberFieldGroup($model, "[" . $model->rte_vehicletype_id . "]rte_minimum_markup", array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Minimum Markup', 'onChange' => 'calvndamt(' . $type . ',' . $vht . ')')), 'groupOptions' => ['class' => 'm0'])) ?>
								<div class="text-danger" id="rtMinMarkup<?= $type ?>" style="display: none"><?= $model->rte_minimum_markup ?></div>
							</td>
							<td>
								<?
								$baseAmt					 = round($model->rte_vendor_amount - (($model->rte_toll_tax | 0) + ($model->rte_state_tax | 0)));
								?>
								<span class="font20" id="rtBase<?= $type ?>"><?= $baseAmt ?></span>
								<div class="text-danger" id="rtBaseOld<?= $type ?>" style="display: none"><?= $baseAmt ?></div>
							</td>
							<td class="<?= $hide ?>">
								<? $markup						 = max([$model->rte_minimum_markup, $cabDefaultMarkup]); ?>
								<span class="font20"  id="rtSMarkup<?= $type ?>"><?= $markup ?></span>

								<div class="text-danger" id="rtSMarkupOld<?= $type ?>" style="display: none"><?= $markup ?></div>
							</td>
							<td class="<?= $hide ?>">
								<?php
								$rockBottomBasePrice		 = round($baseAmt * (1 + ($rockBottomMargin / 100)));
								$sBase						 = round($rockBottomBasePrice * ( 1 + ($markup / 100)));
								//$sBase1 = round($model->rte_vendor_amount * (1 + ($markup / 100)))
								?>
								<span class="font20" id="rtSBase<?= $type ?>"><?= $sBase . ' ' . $sBase1 ?></span>
								<div class="text-danger" id="rtSBaseOld<?= $type ?>" style="display: none"><?= $sBase ?></div>
							</td>
							<td  class="<?= $hide ?>">
								<?php
								$sTax1						 = round($sBase * ($sTax / 100));
								?>
								<span class="font20" id="rtST<?= $type ?>"><?= $sTax1 ?></span>
								<div class="text-danger" id="rtSTOld<?= $type ?>" style="display: none"><?= $sTax1 ?></div>
							</td>

							<td  class="<?= $hide ?>">
								<?
								$totalAmount				 = $sBase + $sTax1 + ($model->rte_toll_tax | 0) + ($model->rte_state_tax | 0);
								?>
								<span class="font20" id="rtTot<?= $type ?>"><?= $totalAmount ?></span>
								<div class="text-danger" id="rtTotOld<?= $type ?>" style="display: none"><?= $totalAmount ?></div>
							</td>
						</tr>
						<?
					}
					?>
                </table>
            </div>
            <div class="row pl20 pr20">
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="checkbox" name="returncheck" id="returncheck"  value="1">
                        Check this to update fares for return route also.
                    </div>
                </div>
            </div>
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    });


    rockBottomMargin = <?= Yii::app()->params['rockBottomMargin'] ?>;


    function calvndamt(type, vht)
    {
        // $elemAmount = $("INPUT[name='Rate[" + type + "][rte_amount]']");
        $vendorAmt = $("INPUT[name='Rate[" + type + "][rte_vendor_amount]']").val() * 1;
        $tollTax = $("INPUT[name='Rate[" + type + "][rte_toll_tax]']").val() * 1;
        $stateTax = $("INPUT[name='Rate[" + type + "][rte_state_tax]']").val() * 1;
        // $minMarkup = $("INPUT[name='Rate[" + type + "][rte_minimum_markup]']").val() * 1;
        $minMarkup = $("#Rate_" + type + "_rte_minimum_markup").val() * 1;
        $baseAmount = $("#rtBase" + type);
        $sellingBaseAmount = $("#rtSBase" + type);
        $defMarkup = $("#rtSMarkup" + type);


        $STax = $("#rtST" + type);
        $rtTot = $("#rtTot" + type);
        // var rtAmount = $("#rtAmount" + type).text();
        var rtVendor = $("#rtVendor" + type).text();
        var rtTollTax = $("#rtTollTax" + type).text();
        var rtStateTax = $("#rtStateTax" + type).text();
        var rtMinMarkup = $("#rtMinMarkup" + type).text();
        var rtBaseOld = $("#rtBaseOld" + type).text();
        var rtSBaseOld = $("#rtSBaseOld" + type).text();
        var rtSTOld = $("#rtSTOld" + type).text();
        var rtTotOld = $("#rtTotOld" + type).text();
        var ST = <?= Filter::getServiceTaxRate() ?>;
        var vMarkup = $("#rtSMarkupOld" + type).text();
        var cabDefaultMarkup = $("#cabDefaultMarkup" + type).text();
        var defMarkup = Math.max(cabDefaultMarkup, $minMarkup);

        //var totalAmount = $elemAmount.val();
        // var venAmount = $elemVendor.val();
        //  var baseAmount = Math.round((venAmount + ($tollTax + $stateTax)) / (1 + (ST / 100)));
        var baseAmount = Math.round($vendorAmt - ($tollTax + $stateTax));

        //var vendor_amt = Math.round((baseAmount + ($tollTax + $stateTax)) * 0.9);
        //  $elemVendor.val(vendor_amt);
        $defMarkup.text(defMarkup);



        rockPerc = Math.round(baseAmount * (1 + (rockBottomMargin / 100)));
        $sBAmount = Math.round(rockPerc * (1 + (defMarkup / 100)));


        // $sBAmount = Math.round(baseAmount * markupApplied * rockPerc);
        $baseAmount.text(baseAmount);
        $sTax = Math.round($sBAmount * (ST / 100));
        $STax.text($sTax);
        $sellingBaseAmount.text($sBAmount);
        $tot = $sBAmount + $sTax + $tollTax + $stateTax;
        $rtTot.text($tot);


        var disVendor = (rtVendor != $vendorAmt) ? "block" : "none";
        var disTollTax = (rtTollTax != $tollTax) ? "block" : "none";
        var disStateTax = (rtStateTax != $stateTax) ? "block" : "none";
        var disMinMarkup = (rtMinMarkup != $minMarkup) ? "block" : "none";
        var disBaseOld = (rtBaseOld != baseAmount) ? "block" : "none";
        var disDefMarkup = (vMarkup != defMarkup) ? "block" : "none";
        var disSBaseOld = (rtSBaseOld != $sBAmount) ? "block" : "none";
        var disSTOld = (rtSTOld != $sTax) ? "block" : "none";
        var disTotOld = (rtTotOld != $tot) ? "block" : "none";
        // $("#rtAmount" + type).css("display", display);
        $("#rtVendor" + type).css("display", disVendor);
        $("#rtStateTax" + type).css("display", disStateTax);
        $("#rtTollTax" + type).css("display", disTollTax);
        $("#rtMinMarkup" + type).css("display", disMinMarkup);
        $("#rtBaseOld" + type).css("display", disBaseOld);
        $("#rtSBaseOld" + type).css("display", disSBaseOld);
        $("#rtSMarkupOld" + type).css("display", disDefMarkup);
        $("#rtSTOld" + type).css("display", disSTOld);
        $("#rtTotOld" + type).css("display", disTotOld);

    }
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
</script>
