<style>
	@media (min-width: 320px) and (max-width: 767px) {
	.btn:not(.md-skip):not(.bs-select-all):not(.bs-deselect-all).btn-lg{ padding: 15px!important;}
	}
</style>

<div class="container mt50">
    <div class="row spot-panel">
        <?php
        $form        = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                     => 'create-trip', 'enableClientValidation' => FALSE,
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
        echo $form->hiddenField($model, 'routes');
       // echo $form->errorSummary($model);
        echo $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]);
        ?>

        <input type="hidden" name="step" value="6">
        <div class="col-xs-12 col-sm-6">
            <!--<div class="row ml5"><h4>Depart date</h4><h4>Depart time</h4></div>-->
			<div class="col-xs-6"><h4>Trip start date</h4></div> <div class="col-xs-5 ml5"><h4>Trip start time</h4></div>
            <div class="col-xs-6">
                <? $strpickdate = ($model->bkg_pickup_date == '') ? date('Y-m-d 06:00:00', strtotime('+1 day')) : $model->bkg_pickup_date; ?>
                <?=
                $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'         => '',
                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strpickdate), 'class' => 'input-group border-gray')), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                ?>
            </div>
			
            <div class="col-xs-5 ml5">		
                <?=
                $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'         => '',
                    'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($strpickdate)), 'class' => 'input-group border-gray'))));
                ?>
            </div>
        </div>
        <? if ($model->bkg_booking_type == 2)
        {
            ?>
            <div class="col-xs-12 col-xsm-6 pull-right">
                <div class="row ml5" ><h4>Trip end Date</h4></div>
                <div class="col-xs-6">                
                    <? 
                    $duration = Route::model()->getRoundtripEstimatedMinimumDurationbyCities($model->bkg_from_city_id, $model->bkg_to_city_id, 30);
                    $strreturndate = ($model->bkg_return_date == '') ? date('Y-m-d H:i:s', strtotime("+$duration minutes", strtotime($strpickdate))): $model->bkg_return_date; ?>
                    <?=
                    $form->datePickerGroup($model, 'bkg_return_date_date', array('label'         => '',
                        'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'value' => DateTimeFormat::DateTimeToDatePicker($strreturndate), 'class' => 'input-group border-gray')), 'prepend'       => '<i class="fa fa-calendar"></i>'));
                    ?>
                </div>
				
				
                <div class="col-xs-5 ml5"> 
					
				
                  <?php 
				 /* echo $form->timePickerGroup($model, 'bkg_return_date_time', array('label'         => '',
                        'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_return_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'value' => date('h:i A', strtotime($strreturndate)), 'class' => 'input-group border-gray'))));
                   */
				  ?>
				<?php					
				     echo $form->hiddenField($model, 'bkg_return_date_time', ['value' => '10:00 PM']);		
				?>
                </div>
            </div>
<? } ?>
        <div class="col-xs-6 text-left"></div>
		<div class="col-xs-4 text-left">
             <div class="help-block error">
						<?php	
						
					     if($model->errors['bkg_return_date_time']) { 							
						?>	 
						<p class="px_error  m20 pl10">
							<?php echo $model->errors['bkg_return_date_time'][0];   ?>
						</p>							 
						<?php	 
						 }
				        ?>
					</div>
		</div>
        <div class="col-xs-12 text-right mt30 pr30">
            <button type="submit" class="pull-left  btn btn-danger btn-lg pl25 pr25 pt30 pb30" name="step6ToStep5"><b> <i class="fa fa-arrow-left"></i> Previous</b></button> <button type="submit" class="btn btn-primary btn-lg pl50 pr50 pt30 pb30"  name="step6submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
        </div>
<?php $this->endWidget(); ?>
    </div>
</div>
<script src="/js2/isotope.js"></script>
<script src="/js2/imagesloaded.js"></script>
<script src="/js2/smoothscroll.js"></script>
<script src="/js2/wow.js"></script>
<script src="/js2/custom.js"></script>
<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>