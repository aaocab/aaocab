<style type="text/css">
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?php
if ($error != '')
{
    ?>  
    <div class="col-xs-12 text-danger text-center"><?= $error ?></div> 
    <?
}
else
{
    $carType     = SvcClassVhcCat::model()->getVctSvcList();
    $areatype    = AreaPriceRule::model()->areatype;
    $area        = 0;
    $dataCatType = PriceRule::model()->getDefaultJSON();
    $tripType = Booking::model()->getBookingType();
    $zoneType = Zones::model()->getZoneList();
    
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto;">
            <div class="row">
                <div class="upsignwidt">
                    <div class="col-xs-12 col-sm-6 col-md-12">
                        <div class="col-xs-12">
                            <?php
                            $form        = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                'id'                     => 'pricerule-manage-form', 'enableClientValidation' => TRUE,
                                'clientOptions'          => array(
                                    'validateOnSubmit' => true,
                                    'errorCssClass'    => 'has-error'
                                ),
                                // Please note: When you enable ajax validation, make sure the corresponding
                                // controller action is handling ajax validation correctly.
                                // See class documentation of CActiveForm for details on this,
                                // you need to use the performAjaxValidation()-method described there.
                                'enableAjaxValidation'   => false,
                                'errorMessageCssClass'   => 'help-block',
                                'htmlOptions'            => array(
                                    'class' => 'form-horizontal'
                                ),
                            ));
                            /* @var $form TbActiveForm */
                            ?>

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php echo CHtml::errorSummary($model); ?>
                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-xs-12 col-md-6">
                                                <label class="control-label" id="errMsg"> Select Cab Type </label>
                                                <?
                                                $dataCatType = VehicleTypes::model()->getJSON($carType);
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    'attribute'      => 'prr_cab_type',
                                                    'val'            => $model->prr_cab_type,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($dataCatType)),
                                                    'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Cab Type', 'id'=>'PriceRule_prr_cab_type')
                                                ));
                                                ?>
                                                <? echo $form->error($model, 'prr_cab_type'); ?>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <?= $form->textAreaGroup($model, 'prr_cab_desc', array('label' => 'Rule Description', 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
                                                <label class="control-label">Select Trip Type </label>
                                                <?
                                                $dataTripType = VehicleTypes::model()->getJSON($tripType);
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    'attribute'      => 'prr_trip_type',
                                                    'val'            => $model->prr_trip_type,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($dataTripType)),
                                                    'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Trip Type')
                                                ));
                                                ?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->textFieldGroup($model, 'prr_rate_per_km', array('label'=> 'Rates(per km)',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Rates (per km)'))));
						?>
                                                <span class="has-error"><? echo $form->error($model, 'prr_rate_per_km'); ?></span>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
						<?= $form->textFieldGroup($model, 'prr_rate_per_minute', array('label'=> 'Rates(per minute)',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Rates (per minute)'))));
									?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->textFieldGroup($model, 'prr_rate_per_km_extra', array('label'=> 'Rates(per km per minute)',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Rates (per km extra)'))));
									?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_rate_per_minute_extra', array('label'=> 'Rates(per minute extra)',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Rates (per minute extra)'))));
									?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_min_km', array('label'=> 'Minimum Kilometer',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Minimum Kilometer'))));
									?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_min_duration', array('label'=> 'Minimum Duration',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Minimum Duration'))));
									?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_min_base_amount', array('label'=> 'Minimum Base Amount',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>5000, 'placeholder' => 'Minimum Base Amount'))));
									?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_min_km_day', array('label'=> 'Minimum Kilometer Per Day',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Minimum Kilometer Per Day'))));
									?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_max_km_day', array('label'=> 'Maximum Kilometer Per Day',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>500, 'placeholder' => 'Maximum Kilometer Per Day'))));
									?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_day_driver_allowance', array('label'=> 'Day Driver Allowance',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Day Driver Allowance'))));
									?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_night_driver_allowance', array('label'=> 'Night Driver Allowance',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Night Driver Allowance'))));
									?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_driver_allowance_km_limit', array('label'=> 'Driver Allowance Kilometer Limit',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Driver Allowance Kilometer Limit'))));
									?>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
						<?= $form->numberFieldGroup($model, 'prr_min_pickup_duration', array('label'=> 'Minimum Pick Up Duration',
										'widgetOptions'	 => array('htmlOptions' => array('min' => 0, 'max'=>50, 'placeholder' => 'Minimum Pick Up Duration'))));
									?>
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6">
                                                <label class="control-label">Select Night Start Time </label>
                                                <div class="input-group full-width">
                                                    <? if($model->prr_night_start_time !=''){
                                                        $ptimeEnd =   $model->prr_night_start_time;
                                                    } else{
                                                        $ptimeEnd  = date('h:i A', strtotime('6am')); 
                                                    }?>
                                                    <?=$form->timePickerGroup($model, 'prr_night_start_time', array('label'			 => '',
                                                            'widgetOptions'	 => array('options'		 => array('defaultTime'	 => true,
                                                                            'autoclose'		 => true),
                                                                    'htmlOptions'	 => array('required' => true, 'placeholder' => 'Night Start Time',
                                                                            'value'			 => date('h:i A', strtotime($ptimeEnd)),
                                                                            'class'			 => 'form-control pr0 border-radius text text-info')),
                                                            'groupOptions'	 => ['class' => 'm0'],
                                                    ));
                                                    ?> 
                                                </div>    
                                            </div>
                                            <div class="col-xs-12 col-md-6">
                                                <label class="control-label">Select Night End Time </label>
                                                <div class="input-group full-width">
                                                    <? if($model->prr_night_end_time !=''){
                                                         $ptimeEnd =   $model->prr_night_end_time;
                                                    } else{
                                                       $ptimeEnd  = date('h:i A', strtotime('6am')); 
                                                    }?>
                                                    
                                                    <?=
                                                    $form->timePickerGroup($model, 'prr_night_end_time', array('label'			 => '',
                                                            'widgetOptions'	 => array('options'		 => array('defaultTime'	 => true,
                                                                            'autoclose'		 => true),
                                                                    'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Night End Time',
                                                                            'value'			 => date('h:i A', strtotime($ptimeEnd)),
                                                                            'class'			 => 'form-control pr0 border-radius text text-info')),
                                                            'groupOptions'	 => ['class' => 'm0'],
                                                    ));
                                                    ?> 
                                                </div>    
                                            </div>
                                            
                                            <div class="col-xs-12 col-md-6 mb20">
                                                <label class="control-label">Select Calculation Type </label>
                                                <?
                                                $calcType		 = $model->calculation_type;
						$dataJson		 = VehicleTypes::model()->getJSON($calcType);
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'model'          => $model,
                                                    'attribute'      => 'prr_calculation_type',
                                                    'val'            => $model->prr_calculation_type,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression($dataJson)),
                                                    'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Calculation Type')
                                                ));
                                                ?>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="panel-footer" style="text-align: center">
						<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <script type="text/javascript">

       $('#pricerule-manage-form').submit(function (event) {
            var cabType = $('#PriceRule_prr_cab_type').val();
            
            if(cabType == '')
            {
                $("#PriceRule_prr_cab_type_em_").text("Car type cannot be blank");
                $("#PriceRule_prr_cab_type_em_").css({"color": "#a94442", "display": "block"});
                $("#errMsg").css({"color": "#f25656"});
                return false;
            }
            else{
                $("#PriceRule_prr_cab_type_em_").css({"display": "none"});
            }
            return true;
       });

       

    </script>
<? }
?>
