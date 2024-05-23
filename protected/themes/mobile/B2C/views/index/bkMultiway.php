<div class="tab-item devSecondaryTab3" id="tab-pill-4a" style="display: none;">
    <div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab" style="width: calc(30% - 5px);">One-Way</a>
		<!--        <a href="#" data-sub-tab="tab-pill-3a" class="sub-tab" style="width: calc(33.33% - 5px);">Round Trip</a>-->
        <a href="#" data-sub-tab="tab-pill-4a" class="sub-tab active-tab-pill-button active" style="width: calc(40% - 5px);">Round Trip</a>
		<a href="#" data-sub-tab="tab-pill-8a" class="sub-tab" style="width: calc(30% - 5px);">Packages</a>
    </div>
	<?php
	$form		 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'bookingMform',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
						var url = "' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '";
						return $jsBookNow.validateTrip(form,url);
                        

                        }
                        }'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'action'				 => Yii::app()->createUrl('booking/booknow'),
		'htmlOptions'			 => array(
			'class' => 'form-horizontal',
		),
	));
	/* @var $form CActiveForm */
	?>
    <div class="select-box-1 bottom-20">

        <em class="color-gray mt20 n">From</em>
        <div id='bkt'>
			<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 3, 'id' => 'bkg_booking_type3']); ?>
			<?= $form->hiddenField($model, 'bktyp', ['value' => 3, 'id' => 'bktyp3']); ?>
            <input type="hidden" id="step23" name="step2" value="2">
            <input type="hidden" id="step13" name="step" value="1">

        </div>
		<?php
		$widgetId	 = $ctr . "_" . random_int(99999, 10000000);
		$this->widget('application.widgets.BRCities', array(
			'type'				 => 1,
			'enable'			 => ($index == 0),
			'widgetId'			 => $widgetId,
			'model'				 => $brtModel,
			'attribute'			 => 'brt_from_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Pick up city",
			'htmlOptions'		 => array('width' => '50%', 'id' => 'bkg_from_city_id_1'),
			'defaultOptions'	 => [
				'onFocus' => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                                       }",
			]
		));
		?>
        <span class="has-error"><?php echo $form->error($model, 'bkg_from_city_id'); ?></span>
    </div>
    <div class="select-box-1 bottom-10">

        <em class="color-gray mt20 n">To</em>
		<?php
		$this->widget('application.widgets.BRCities', array(
			'type'				 => 2,
			'enable'			 => ($index == 0),
			'widgetId'			 => $widgetId,
			'model'				 => $brtModel,
			'attribute'			 => 'brt_to_city_id',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Drop-off city",
			'htmlOptions'		 => array('id' => 'bkg_to_city_id_1', 'width' => '50%'),
			'defaultOptions'	 => [
				'onFocus' => "js:function() {
						$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                                       }",
			]
		));
		?>
    </div>
    <div class="input-simple-1 has-icon input-blue bottom-10">

        <em class="color-gray">Pick up date</em>

		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'BookingRoute[brt_pickup_date_date]',
				'value'	=> $pdate,				
				'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => $minDate, 'dateFormat' => 'dd/mm/yy','minDate'=> 0,'maxDate'=>"+6M"),   
				'htmlOptions' => array('required' => true, 'placeholder'  => 'Add a date','readonly'=>'readonly',								
				'class'	 => 'border-radius font-16','id'=> 'Booking_bkg_pickup_date_date_1','style'=>'z-index:100 !important;font-size:16px;font-weight:bold')	
			));
		?>

    </div>
    <div class="input-simple-1 has-icon input-blue bottom-20">

        <em class="color-gray">Pick up time</em>
		<?php
		$this->widget('ext.timepicker.TimePicker', array(
			'model'			 => $brtModel,
			'id'			 => 'brt_pickup_date_time_3' . date('mdhis'),
			'attribute'		 => 'brt_pickup_date_time',
			'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
			'htmlOptions'	 => array('required' => true, 'placeholder' => 'Add a time', 'class' => 'timePickup font-16', 'readonly' => 'readonly','style'=>'font-size:16px;font-weight:bold')
		));
		?> 
        <span class="has-error"><? echo $form->error($model, 'bkg_pickup_date_date_1'); ?></span>
        <span class="has-error"><? echo $form->error($model, 'bkg_pickup_date_time_1'); ?></span>
    </div>
    <div class="clear"></div>

    <div class="content text-center bottom-10 mt0">
        <!--                                    <a href="#" class="button shadow-medium button-full button-round button-orange-3d button-orange uppercase ultrabold">Button</a>-->
        <button type="submit" class="btn-submit-orange">Add more city</button>
    </div>
	<?php $this->endWidget(); ?>
</div>