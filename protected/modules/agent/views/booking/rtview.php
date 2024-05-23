<div class="" >
    <?php
    $arr1 = array_values($cabratedata)[0];
    if ($arr1['error'] != 0) {
        ?>
        <div class="panel">            
            <div class="panel-body pt0 pb0">   
                <h3>Some error occurred. Please Try again later</h3>
            </div>
        </div>
        <?
    }
    /* @var $model Booking */
    if ($arr1['error'] == 0) {


        // $arrr = CJSON::decode($model->preData);
        $cityArr = $arrr['cityarr'];
        $cityNameArr = $arrr['cityNameArr'];
        $incArr = [0 => 'Excluded', 1 => 'Included'];

        // $model=  Booking::model()->findByPk(25157);
        //   $cabRate = Rate::model()->getCabDetailsbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'cabrate-form1',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'afterValidate' => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('agent/booking/cabratedetail')) . '",
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
								openTab(data2,4);
								' . //trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail')) . '\');
                'disableTab(3);
							}
							else
							{
								var errors = data2.errors;
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
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
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'htmlOptions' => array(
                //			'onsubmit' => "return false;", /* Disable normal form submit */
                //			'onkeypress' => "validateForm1();",
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form TbActiveForm */
        $form->attributes = $model->attributes;
        ?>
        <?= $form->errorSummary($model); ?>
        <?= CHtml::errorSummary($model); ?>

        <div class="panel">            
            <div class="panel-body p0">   
                <? //= $form->hiddenField($model, 'preData'); ?>
                <input type="hidden" id="step" name="step" value="3">
                <input type="hidden" id="ckm_rate" name="ckm_rate" >
                <?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
                <?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash']); ?>

                <?= $form->hiddenField($model, 'bkg_booking_type'); ?>           
                <?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
                <?= $form->hiddenField($model, "bkg_rate_per_km_extra"); ?>
                <?
//            $predata = $model->preData;
//            $dataa = CJSON::decode($predata);
                ?>
                <div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="m10 p10">
                    <div class="row">
                        <div class="col-xs-8 col-sm-12 col-md-offset-1 col-lg-offset-1 col-md-10 col-lg-10 ml0">
                            <h2 class="mb0"><?
                                //$ct = $model->getTripCitiesListbyId();
                                $ct = implode(' &rarr; ', $quotData['routeDesc']);
//								echo "<pre>";
//								echo $ct;
//								print_r($cabratedata);
//								echo "</pre>";
//								exit;
                                echo $ct;
                                ?> </h2>
                            <p>Estimated Distance: <b> <?= $quotData['tripDistance'] . " Km" ?></b>,
                                Estimated Time: <b><?= $quotData['days']['actualDur'] ?></b></p>
                            <? /*  Estimated Time: <b><?= $model->bkg_trip_duration_day ?></b></p> */ ?>
                            <h5 class="hide" >If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>
                        </div> 
                    </div>
                    <div class="row hide">
                        <div class="col-xs-12 summary-div border-none">
                            <div class="checkbox ml20">      
                                <? //= $form->checkboxGroup($model, 'bkg_tnc', ['label' => 'I Agree to the <a href="#" onclick="opentns()" >Terms and Conditions</a>'])          ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m10">
                    <?
                    foreach ($cabratedata as $key => $val) {
                        $tolltax_value = $val['toll_tax'];
                        $tolltax_flag = $val['tolltax'];
                        $statetax_value = $val['state_tax'];
                        $statetax_flag = $val['statetax'];
                        if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0)) {
                            $taxStr = '<i style="font-size:0.8em">(Toll Tax and State Tax included)</i>';
                        } else if ($tolltax_flag == 0 && $statetax_flag == 0) {
                            $taxStr = '<i style="font-size:0.8em">(Toll Tax and State Tax excluded may be apply later)</i>';
                        }
                        ?>
                        <div class="col-xs-12 col-sm-6 col-md-4 mb20 pl5 pr5">
                            <div class="car_result">
                                <h4 style="height: 40px"><?= $val['cab'] ?></h4>
                                <div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $val['image'] ?>" alt="" ></div>
                                <div class="row pt10 car_bottom">
                                    <div class="col-xs-7">
                                        <h3 class="m0 text-uppercase">Base Fare</h3><?= $taxStr ?>
                                    </div>
                                    <div class="col-xs-5 text-right">
                                        <h3 class="m0 text-uppercase">
                                            <i class="fa fa-inr"></i><?= $val['base_amt']; ?>
                                        </h3>
                                        <span class=" small_text hide">(Approx.)</span>
                                    </div>
                                </div>



                                <? if ($val['discFare'] != '') { ?>
                                    <div class="row pt5">
                                        <h5 class="col-xs-8 text-uppercase text-danger" style="font-size: 18px">Discounted  Fare</h5>
                                        <div class="col-xs-4 text-right text-danger" style="font-size: 18px"><?= $val['discFare']; ?></div>
                                    </div>
                                <? } ?>
                                <div class="row pt5">
                                    <div class="col-xs-5">Model type </div>
                                    <div class="col-xs-7 text-right"><?= $val['cab_model'] ?></div>
                                </div>
                                <div class="row pt5">
                                    <div class="col-xs-5">Capacity</div>
                                    <div class="col-xs-7 text-right"><?= $val['capacity'] ?> Passengers + Driver</div>
                                </div>
                                <div class="row pt5">
                                    <div class="col-xs-5">Luggage Capacity</div>
                                    <div class="col-xs-7 text-right"><?= $val['big_bag_capacity'] ?> big bags + <?= $val['bag_capacity'] ?> small bag</div>
                                </div>
                                <!--
                                  <div class="row pt5">
                                  <div class="col-xs-6">Toll-Tax</div>
                                  <div class="col-xs-6 text-right"><? //= $incArr[$val['tolltax']]   ?></div>
                                  </div>
                                  <div class="row pt5">
                                  <div class="col-xs-6">State-Tax:</div>
                                  <div class="col-xs-6 text-right"><? //= $incArr[$val['statetax']]   ?></div>
                                  </div>
                                  <div class="row pt5">
                                  <div class="col-xs-4">Base Fare </div>
                                  <div class="col-xs-8 text-right"><i class="fa fa-inr"></i><? //= $val['base_amt']   ?></div>
                                  </div>

                                  <div class="row pt5">
                                  <div class="col-xs-6">Driver Allowance:</div>
                                  <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><? //= $val['driverAllowance']   ?></div>
                                  </div>

                                  <div class="row pt5">
                                  <div class="col-xs-6">Service-Tax (<? //= Filter::getServiceTaxRate();   ?>%):</div>
                                  <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><? //= $val['service_tax']   ?></div>
                                  </div>
                                -->
                                <div class="row pt5">
                                    <div class="col-xs-6">KM in Quote</div>
                                    <div class="col-xs-6 text-right"><?= $quotData['quoted_km'] ?> Km</div>
                                </div>
                                <div class="row pt5">
                                    <div class="col-xs-12">Note: Ext. Chrg. After <?= $quotData['quoted_km'] ?> Kms. as applicable<? /* /?>= <i class="fa fa-inr"></i><?= $val['km_rate']; ?>/Km. <? */ ?>.</div>
                                </div>
                                <div class="row mt10">
                                    <div class="col-xs-6 col-xs-offset-3">
                                        <button type="button" value="<?= $val['cab_id'] ?>" kmr="<?= $val['km_rate']; ?>" name="bookButton" class="btn btn-success  border-none  col-xs-12" onclick="validateForm1(this);"><b>Book Now</b></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
                <?
                if ($model->bkg_id > 0) {
                    $rtInfoArr = $model->getRoutesInfobyId();
                } else {
                    $rtInfoArr = $model->getRoutesInfobyCities([$model->bkg_from_city_id, $model->bkg_to_city_id]);
                }


                if (sizeof($rtInfoArr) > 0 && $rtInfoArr[0]['rut_special_remarks']) {
                    ?><div class="row">
                        <div class="col-xs-12 ">
                            <div class="bg bg-info p10 pl0">
                                <ul style="list-style-type: square ;">
                                    <?
                                    foreach ($rtInfoArr as $info) {
                                        ?>
                                        <li>
                                            <?= implode("</li><li>", array_filter(array_map("trim", explode("\n", $info['rut_special_remarks'])))) ?>
                                        </li>
                                    <? }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <? }
                ?>
                <div class="p5">
                    We require exact pickup and drop addresses to be provided for your itinerary before your vehicle and driver can be assigned. Once the pickup and drop addresses are provided, these may cause the above quotation to change.
                </div>
            </div> 
        </div>
        <?php $this->endWidget(); ?>
    <? } ?>
</div>


<div class="hide">
    <? //php print_r($GLOBALS['API']);             ?>
</div>
<script type="text/javascript">
    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
    //  $("#Booking_bkg_tnc").attr('checked', 'checked');

    disableTab(3);
    $(".clsBkgID").val('<?= $model->bkg_id ?>');
    $(".clsHash").val('<?= Yii::app()->shortHash->hash($model->bkg_id) ?>');
    function validateForm1(obj) {

        var vht = $(obj).attr("value");
        var kmr = $(obj).attr("kmr");

        if (vht > 0) {

            $('#<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>').val(vht);
            $('#<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>').val(kmr);
            $('#cabrate-form1').submit();
        }
    }

</script>