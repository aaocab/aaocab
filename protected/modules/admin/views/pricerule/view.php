<style type="text/css">
    .modal {  overflow-y:auto;}
    .flex {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
    }
    .rounded-margin{ margin: 0 15px;}
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .control-label{
        font-weight: bold
    }   
    .box-design1{ background: #8DCF8A; color: #000; padding: 10px;}
    .box-design1a{ background: #ccffcc; color: #000;}
    .box-design2{ background: #F8A6AC; color: #000;  padding: 10px;}
    .box-design2a{ background: #ffcccc; color: #000; }
    .label-tab label{ margin:0 17%!important}
    .label-tab .form-group{ margin-bottom: 0;}
</style>


<div id="view">
    <div class="row">
        <div class="col-xs-6 col-md-12 col-lg-12 new-booking-list">
            <div class="row p20">
               
                <div class="col-xs-12 main-tab1">
                    <div class="row new-tab-border-b">
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Trip Type:</b></div>
                                    <div class="col-xs-7">
                                        <?= Filter::bookingTypes($model->prr_trip_type);?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Cab Type:</b></div>
                                    <div class="col-xs-7"> 
                                        <?php 
                                        $cabType =  SvcClassVhcCat::model()->getVctSvcList("string", 0, 0,$model->prr_cab_type);
                                        ?>
                                        <?= $cabType?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Rates Per Kilometer:</b></div>
                                    <div class="col-xs-7">
                                        <?= $model->prr_rate_per_km; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Rates(per minute):</b></div>
                                    <div class="col-xs-7"> 
                                         <?= $model->prr_rate_per_minute; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Rates(per km per minute):</b></div>
                                    <div class="col-xs-7" style="display: inline-block" >
                                        <?= $model->prr_rate_per_km_extra ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                 <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Rates(per minute extra):</b></div>
                                    <div class="col-xs-7"> 
                                        <?= $model->prr_rate_per_minute_extra ?>
                                    </div>
                                </div>
                               
                            </div>
                        </div>		
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Minimum Kilometer:</b></div>
                                    <div class="col-xs-7">
                                          <?= $model->prr_min_km ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Minimum Duration:</b></div>
                                    <div class="col-xs-7">
                                      <?= $model->prr_min_duration ?>  
                                    </div>
                                </div>
                            </div>   
                        </div>       
                           
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Minimum Base Amount:</b></div>
                                    <div class="col-xs-7">
                                        <?= $model->prr_min_base_amount ?>  
                                    </div>
                                </div>
                             </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="row new-tab1">
                                        <div class="col-xs-5"><b>Minimum Kilometer Per Day:</b></div>
                                        <div class="col-xs-7">
                                            <?= $model->prr_min_km_day?>  
                                        </div>
                                    </div>
                                </div>	
                        </div>    
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Maximum Kilometer Per Day:</b></div>
                                    <div class="col-xs-7">
                                        <?= $model->prr_max_km_day?> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Day Driver Allowance:</b></div>
                                    <div class="col-xs-7">
                                        <?= $model->prr_day_driver_allowance?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Night Driver Allowance:</b></div>
                                    <div class="col-xs-7">
                                         <?= $model->prr_night_driver_allowance?> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Driver Allowance Kilometer Limit:</b></div>
                                    <div class="col-xs-7">
                                         <?= $model->prr_driver_allowance_km_limit?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Minimum Pick Up Duration:</b></div>
                                    <div class="col-xs-7">
                                       <?= $model->prr_min_pickup_duration?>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Calculation Type:</b></div>
                                    <div class="col-xs-7">
                                       <?php 
                                        $calCulationType = PriceRule::model()->calculation_type[$model->prr_calculation_type];
                                       ?>
                                       <?= $calCulationType?>   
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
