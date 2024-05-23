
<style>
	.font-11{ font-size: 11px;}
    .funkyradio label {
        width: 100%;
        border-radius: 3px;
        border: 1px solid #D1D3D4;
        font-weight: normal;
    }
    .funkyradio input[type="radio"]:empty{
        display: none;
    }

    .funkyradio input[type="radio"]:empty ~ label{
        position: relative;
        line-height: 2.5em;
        text-indent: 3.25em;
        margin-top: 0;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .funkyradio input[type="radio"]:empty ~ label:before{
        position: absolute;
        display: block;
        top: 0;
        bottom: 0;
        left: 0;
        content: '';
        width: 2.5em;
        background: #D1D3D4;
        border-radius: 3px 0 0 3px;
    }
    .funkyradio input[type="radio"]:focus ~ label:before{
        box-shadow: 0 0 0 3px #999;
    }
    .funkyradio-default input[type="radio"]:checked ~ label:before{
        color: #333;
        background-color: #FF6700;
    }
	.car_result{ 
		background: #fff; padding: 15px;
		-webkit-box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.14);
		-moz-box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.14);
		box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.14);
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
	}
	.green-color{ color: #00a388;}
	.btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg{ padding: 10px;}
</style>
<div class="container mt50">
    <!--    <div class="row">
            <div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
        </div>-->
    <div class="row spot-panel">
        <?php
		$serviceTaxRate						 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
		$staxrate    = ($serviceTaxRate == 0)? 1 : $serviceTaxRate;
		
        //$cabData = VehicleTypes::model()->getMasterCarDetails();
		$cabData = SvcClassVhcCat::model()->getVctSvcList('allDetail');
        $incArr  = [0 => 'Excluded', 1 => 'Included'];
        $form    = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                     => 'cabrate-form1', 'enableClientValidation' => FALSE,
            'clientOptions'          => array(
                'validateOnSubmit' => true,
                'errorCssClass'    => 'has-error'
            ),
            'enableAjaxValidation'   => false,
            'errorMessageCssClass'   => 'help-block',
            'action'                 => Yii::app()->createUrl('agent/booking/spot'),
            'htmlOptions'            => array(
                'class'   => 'form-horizontal', 'enctype' => 'multipart/form-data'
            ),
        ));
        /* @var $form TbActiveForm */
        echo $form->hiddenField($model, 'bkg_booking_type');
        echo $form->hiddenField($model, 'bkg_from_city_id');
        echo $form->hiddenField($model, 'bkg_to_city_id');
        echo $form->hiddenField($model, 'bkg_vehicle_type_id');
        echo $form->hiddenField($bkgInvoice, 'bkg_rate_per_km_extra');
        ?>
        <?= $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]); ?> 

        <input type="hidden" name="step" value="7">

        <div class="col-xs-12 mt30">

            <?
			
            $i       = 0;
            foreach ($cabratedata as $key => $quoteRate)
            {
                $i++;
                /* @var $routeRates routeRates */
                /* @var $routeDistance routeDistance */
                /* @var $routeDuration routeDuration */
                $routeRates     = $quoteRate->routeRates;
                $routeDistance  = $quoteRate->routeDistance;
                $routeDuration  = $quoteRate->routeDuration;
                $tolltax_value  = $routeRates->tollTaxAmount;
                $cab            = $cabData[$key];
                $tolltax_flag   = $routeRates->isTollIncluded; // $val['tolltax'];
                $statetax_value = $routeRates->stateTax; // $val['state_tax'];
                $statetax_flag  = $routeRates->isStateTaxIncluded; //$val['statetax'];
                if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
                {
                    $taxStr = '<i style="font-size:0.8em">(Toll Tax and State Tax included)</i>';
                }
                else if ($tolltax_flag == 0 && $statetax_flag == 0)
                {
                    $taxStr = '<i style="font-size:0.8em">(Toll Tax and State Tax excluded may be apply later)</i>';
                }
				if($cab['scc_id'] != '4')
				{
                ?>
                <div class="col-xs-12 col-sm-6 col-md-4 mb20" style="min-height: 520px">
                    <div class="car_result funkyradio">
                        <div class="funkyradio-default font-11 text-center">  
                            <input type="radio" style="width: 1.3em; height: 1.3em;" 
                                   id="<? echo "cab_type" . $cab['scv_id'] ?>"  name="cab_type" 
                                   value="<?= $cab['scv_id'] ?>" 
                                   kmr="<?= $routeRates->ratePerKM; ?>" <? echo ($model->bkg_vehicle_type_id != '' && $model->bkg_vehicle_type_id == $cab['scv_id'] ) ? "checked" : "" ?>>
                            <label for="<? echo "cab_type" . $cab['scv_id'] ?>">SELECT <?= strtoupper($cab['label']) ?></label>
                        </div>
                        <div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt="" style="max-width: 100%"></div>
                        <div class="row pt10 car_bottom">
                            <div class="col-xs-7">
                                <h4 class="m0 text-uppercase"><b>Estimated Fare</b></h4><?= $taxStr ?>
                            </div>
                            <div class="col-xs-5 text-right">
                                <h4 class="m0 text-uppercase green-color">
                                    <i class="fa fa-inr" style="font-size: 16px; padding-right: 2px;"></i><b><?= round($routeRates->totalAmount); ?></b>
                                </h4>
                                <span class=" small_text hide">(Approx.)</span>
                            </div>
                        </div>
                        <?
                        if ($routeRates->discFare != '')
                        {
                            ?>
                            <div class="row pt5">
                                <h5 class="col-xs-8 text-uppercase text-danger" style="font-size: 18px">Discounted  Fare</h5>
                                <div class="col-xs-4 text-right text-danger" style="font-size: 18px"><?= $routeRates->discFare; ?></div>
                            </div>
                        <? } ?>
                        <div class="row pt5">
                            <div class="col-xs-4">Model type </div>
                            <div class="col-xs-8 text-right" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;display: inline-block;max-width: 100%;"><span title="<?= $cab['vct_desc'] ?>"><?= $cab['vct_desc'] ?></span></div>
                        </div>
                        <div class="row pt5">
                            <div class="col-xs-5">Capacity</div>
                            <div class="col-xs-7 text-right"><?= $cab['vct_capacity'] ?> Passengers + Driver</div>
                        </div>
						<?php
							$luggageCapacity = Stub\common\LuggageCapacity::init($cab['vct_id'], $cab['scc_id']);
						?>
                        <div class="row pt5">
                            <div class="col-xs-5">Luggage Capacity</div>
                            <div class="col-xs-7 text-right">
								<?=(($luggageCapacity->largeBag !=0)?$luggageCapacity->largeBag. ' big bags /':'') ?>
								<?=(($luggageCapacity->smallBag !=0)?$luggageCapacity->smallBag. ' small bag ':'') ?>
							<!--<?//= $luggageCapacity->largeBag ?> big bags / <?//= $luggageCapacity->smallBag ?> small bag-->
							</div>
                        </div>
                        <div class="row pt5">
                            <div class="col-xs-6">Toll-Tax</div>
                            <div class="col-xs-6 text-right"><?= $incArr[$routeRates->isTollIncluded|0] ?></div>
                        </div>
                        <div class="row pt5">
                            <div class="col-xs-6">State-Tax:</div>
                            <div class="col-xs-6 text-right"><?= $incArr[$routeRates->isStateTaxIncluded|0] ?></div>
                        </div>
                        <div class="row pt5">
                            <div class="col-xs-4">Base Fare </div>
                            <div class="col-xs-8 text-right"><i class="fa fa-inr"></i><?= $routeRates->baseAmount; ?></div>
                        </div>

                        <div class="row pt5">
                            <div class="col-xs-6">Driver Allowance:</div>
                            <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $routeRates->driverAllowance ?></div>
                        </div>
                        <?
                        //$staxrate = Filter::getServiceTaxRate();
                        $taxLabel = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
                        ?>
                        <?
                        if ($cgst > 0)
                        {
                            ?>
                            <div class="row pt5">
                                <div class="col-xs-6">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
                                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['cgst'] / $staxrate) * $routeRates->gst)|0; ?></div>
                            </div>
                        <? } ?>
                        <?
                        if ($sgst > 0)
                        {
                            ?>
                            <div class="row pt5">
                                <div class="col-xs-6">SGST (@<?= Yii::app()->params['sgst'] ?>%):</div>
                                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['sgst'] / $staxrate) * $routeRates->gst)|0; ?></div>
                            </div>
                        <? } ?>
                        <?
                        if ($igst > 0)
                        {
                            ?>
                            <div class="row pt5">
                                <div class="col-xs-6">IGST (@<?= Yii::app()->params['igst'] ?>%):</div>
                                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['igst'] / $staxrate) * $routeRates->gst)|0; ?></div>
                            </div>
                        <? } ?>
                        <?
                        if ($serviceTaxRate != 5)
                        {
                            ?>
                            <div class="row pt5">
                                <div class="col-xs-6"><?= $taxLabel ?> (<?= $serviceTaxRate; ?>%):</div>
                                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $routeRates->gst ?></div>
                            </div>
                        <? } ?>


                        <div class="row pt5">
                            <div class="col-xs-6">KM in Quote</div>
                            <div class="col-xs-6 text-right"><?= $routeDistance->quotedDistance ?> Km</div>
                        </div>
                        <div class="row pt5">
                            <div class="col-xs-6">Ext. Charge After <?= $routeDistance->quotedDistance ?> Kms.</div>
                            <div class="col-xs-6 text-right"><?= $routeRates->ratePerKM ?> Km</div>
                        </div>
                        <!--                        <div class="row pt5">
                                                    <div class="col-xs-12">Note: Ext. Chrg. After <? //= $quotData['quoted_km']  ?> Kms. as applicable<? /* /?>= <i class="fa fa-inr"></i><?= $val['km_rate']; ?>/Km. <? */ ?>.</div>
                                                </div>-->
                    </div>
                </div>
            <? 
				}
			} ?>
        </div>
        <div class="col-xs-12 mt30 ">
            <button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step7ToStep6"><b> <i class="fa fa-arrow-left"></i> Previous</b></button><button type="submit" class="  pull-right btn btn-primary btn-lg pl50 pr50 pt30 pb30"  name="step7submit" onclick="return validateForm1();"><b>Next <i class="fa fa-arrow-right"></i></b></button>
        </div>
        <div class="col-xs-12 text-right mt30 pr30">
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script src="/js2/isotope.js"></script>
<script src="/js2/imagesloaded.js"></script>
<script src="/js2/smoothscroll.js"></script>
<script src="/js2/wow.js"></script>
<script src="/js2/custom.js"></script>
<script type="text/javascript">
                history.pushState(null, null, location.href);
                window.onpopstate = function () {
                    history.go(1);
                };
                function validateForm1(obj) {


                    if ($('input[name=cab_type]').is(':checked')) {
                        var cab = $('input[name=cab_type]:checked', '#cabrate-form1').val();
                        var vht = $('input[name=cab_type]:checked', '#cabrate-form1').attr("value");
                        var kmr = $('input[name=cab_type]:checked', '#cabrate-form1').attr("kmr");
                        if (vht > 0) {
                            $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val(vht);
                            $('#<?= CHtml::activeId($bkgInvoice, "bkg_rate_per_km_extra") ?>').val(kmr);
                            return true;
                        }
                    }
                    alert('Please select Cab!');
                    return false;
                }

</script>