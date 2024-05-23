<?php
$time	 = Filter::getExecutionTime();
echo "<!--" . $time * 1000 . "-->";
$source	 = Yii::app()->request->getParam('source');
if ($source != '')
{
	$souceName		 = '[' . Admins::model()->getSourceById($source) . ']';
	$this->pageTitle = 'Booking History ' . $souceName;
}
else
{
	$this->pageTitle = 'Booking History';
}

$pageno			 = Yii::app()->request->getParam('page');
//$cityList = CHtml::listData(Cities::model()->findAll('cty_active = :act', array(':act' => '1')), 'cty_id', 'cty_name');
$bookingStatus	 = Booking::model()->getActiveBookingStatus();
//$datacity = Cities::model()->getCityByFromBooking();
$datazone		 = Zones::model()->getZoneArrByFromBooking();
$fromCityArr	 = Cities::model()->getCityArrByFromBooking();
//$toCityArr = Cities::model()->getCityArrByToBooking();
$flagSource		 = Booking::model()->getFlagSouce();
$flagInfo		 = VehicleTypes::model()->getJSON($flagSource);
$time			 = Filter::getExecutionTime();

$GLOBALS['time'][5]		 = $time;
$tab					 = ($tab == "") ? "1" : $tab;
${'tabactive' . $tab}	 = 'active ';
?>
<div class="row <?= $formHide ?>">
    <div class="col-xs-12">
		<?php
		$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'booking-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array('class' => '',),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="row">
            <div class="col-xs-12 col-lg-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4">
						<?= $form->textFieldGroup($model, 'search', array('label' => 'Search', 'htmlOptions' => array('placeholder' => 'search by booking id or other information'))) ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
							<?
							$daterang				 = "Select Booking Date Range";
							$createdate1			 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$createdate2			 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($createdate1 != '' && $createdate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
							}
							?>
                            <label  class="control-label">Booking Date</label>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?
							echo $form->hiddenField($model, 'bkg_create_date1');
							echo $form->hiddenField($model, 'bkg_create_date2');
							?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Pickup Date</label>
							<?php
							$daterang			 = "Select Pickup Date Range";
							$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
							$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div></div>
                </div></div>
            <div class="col-xs-6  col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="control-label">Region </label>
					<?php
					$regionList	 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_region',
						'val'			 => $model->bkg_region,
						//'asDropDownList' => FALSE,
						'data'			 => Vendors::model()->getRegionList(),
						//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
						'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
							'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
					));
					?>
                </div></div>
            <div class="col-xs-6  col-sm-4 col-md-3 col-lg-2 ">
                <div class="form-group">
                    <label class="control-label">Source</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_flag_source',
						'val'			 => $model->bkg_flag_source,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($flagInfo), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Source')
					));
					?></div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="control-label">Channel Partner</label>
					<?php
					$dataagents	 = Agents::model()->getAgentsFromBooking();
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_agent_id',
						'val'			 => $model->bkg_agent_id,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
						'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Partner name')
					));
					?>
                </div> 
            </div>
            <!--            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label class="control-label">Corporate</label>
			<?php
//                    $datacorps = Agents::model()->getCorporateCodes();
//                    $this->widget('booster.widgets.TbSelect2', array(
//                        'model' => $model,
//                        'attribute' => 'corporate_id',
//                        'val' => $model->corporate_id,
//                        'asDropDownList' => FALSE,
//                        'options' => array('data' => new CJavaScriptExpression($datacorps), 'allowClear' => true),
//                        'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Corporate name')
//                    ));
			?>
                            </div>
                        </div>-->

            <div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="control-label">From City</label>

					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'fromcity',
						'val'			 => $model->fromcity,
						'data'			 => $fromCityArr,
						//'asDropDownList' => FALSE,
						//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'From City')
					));
					?>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="control-label">To City</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'tocity',
						'val'			 => $model->tocity,
						'data'			 => $fromCityArr,
						//'asDropDownList' => FALSE,
						//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'To City')
					));
					?>
                </div> 
            </div>
            <div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
                <div class="form-group">
                    <label>Source Zone</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'sourcezone',
						'val'			 => $model->sourcezone,
						'data'			 => $datazone,
						//'asDropDownList' => FALSE,
						//'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Source Zone')
					));
					?>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="control-label">Destination Zone</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'destinationzone',
						'val'			 => $model->destinationzone,
						'data'			 => $datazone,
						//  'asDropDownList' => FALSE,
						// 'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Destination Zone')
					));
					?>
                </div></div>
            <div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="control-label">CAB TYPE</label>
					<?php
					$cartype	 = VehicleTypes::model()->getParentVehicleTypes(1);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkg_vehicle_type_id',
						'val'			 => $model->bkg_vehicle_type_id,
						'data'			 => $cartype,
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Select Car Type')
					));
					?>
                </div>
            </div>

            <div class="col-xs-6 col-sm-4  col-md-3 col-lg-2">
                <div class="form-group">
                    <label class="control-label">Booking Type</label>

					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'bkgtypes',
						'val'			 => $model->bkgtypes,
						'data'			 => $model->booking_type,
						//'asDropDownList' => FALSE,
						//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
						'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
							'placeholder'	 => 'Booking Type')
					));
					?>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4  col-md-3 col-lg-2 mt20">
				<?= $form->checkboxListGroup($model, 'b2cbookings', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'B2C bookings only'), 'htmlOptions' => []))) ?>

            </div>

            <div class="col-xs-12 text-center pb10">  
                <button class="btn btn-primary mt5" type="submit" style="width: 185px;"  name="bookingSearch">Search</button></div>
        </div>

		<?php $this->endWidget(); ?>
    </div>
</div>					
<?php
$time		 = Filter::getExecutionTime();

$GLOBALS['time'][6]	 = $time;
$GLOBALS['time'][7]	 = [];
?>
<div class="row">
    <div class="col-xs-12" id="bkgList">
        <ul class="nav nav-tabs <?= $formHide ?>" id="myTab">
			<?php
			$i					 = 0;
//  $bgcolor = ['warning', 'success', 'info', 'danger'];
			$bgcolor			 = 'default';

			foreach ($dataProvider as $bid => $provider)
			{
				$label = '';
				if (in_array($bid, [6, 7]))
				{
					/* @var $provider[] CActiveDataProvider */
					//           $count = $provider['Oct15']->getTotalItemCount();
					//           $label = "$count/";
				}
				unset($params['Booking_page']);
				$params			 = $provider['data']->getPagination()->params;
				$params['tab']	 = $bid;
				$tabUrl			 = "data-url=\"" . Yii::app()->createUrl('rcsr/booking/list', $params) . '"';
				?>
				<li class='<?= ${"tabactive" . $bid} ?> '><a data-toggle="tabajax"  <?= $tabUrl ?> class="bg-white" href="#sec<?= $bid ?>"><?= $provider["label"] ?> <span id="bkgCount<?= $bid ?>" class="font-bold" style="font-size: 1.2em">(<?= $label . $provider['data']->getTotalItemCount() ?>)</span></a></li>
				<?
				$i				 = ($i == 3) ? 0 : $i + 1;

				$time = Filter::getExecutionTime();

				$GLOBALS['time'][7][$bid] = $time;
			}
			$GLOBALS['time'][8] = [];
			?>
        </ul>
        <div class="tab-content p0">
			<?php
			foreach ($dataProvider as $bid => $provider)
			{
				$tabUrl = "";
				?>
				<div id="<?= 'sec' . $bid ?>" tabid="<?= $bid ?>" class="tab-pane <?= ${'tabactive' . $bid} ?>">
					<?php
					if (in_array($bid, $tabFilter))
					{
						$this->renderPartial("grid", ['status' => $bid, 'provider' => $provider]);
					}
					$time = Filter::getExecutionTime();

					$GLOBALS['time'][8][$bid] = $time;
					?>
				</div>
			<? } ?>
        </div>
    </div>
</div>
<?php
$time = Filter::getExecutionTime();

echo "<!-- time: " . $time * 1000 . "-->";
?>
<script>
    $(document).ready(function ()
    {
        $('#myTab a[data-toggle="tabajax"]').click(function (e)
        {
            e.preventDefault();

            var url = $(this).attr("data-url");
            var href = this.hash;
            var pane = $(this);
            if ($tabCache.indexOf($(href).attr('id')) > -1)
            {
                pane.tab('show');
                return;
            }
            // ajax load from data-url
            $(href).load(url, function (result)
            {
                pane.tab('show');
                addTabCache($(this).attr('tabid'));
            });
        });

        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';


        $('#bkgCreateDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Booking Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });


        $('#bkgPickupDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgPickupDate span').html('Select Pickup Date Range');
            $('#Booking_bkg_pickup_date1').val('');
            $('#Booking_bkg_pickup_date2').val('');
        });

    });

    var checkCounter = 0;
    var checked = [];
    function setMarkComplete() {
        checked = [];
        $('#bookingTab5 input[name="booking_id5[]"]').each(function (i) {
            if (this.checked) {
                checked.push(this.value);
            }
        });
        if (checked.length == 0) {
            bootbox.alert("Please select a booking for mark complete.");
            return false;
        }
        if (checked.length > 0) {
            var j = 0;
            var checked1 = [];
            while (j < 10 && checkCounter < checked.length) {
                checked1.push(checked[checkCounter]);
                j++;
                checkCounter++;
            }
            markCompleteAjax(checked1);
        }

    }


    function markCompleteAjax(checkedIds) {
        ajaxindicatorstart("Processing " + checkCounter.toString() + " of " + checked.length.toString() + "");
        var href = '<?= Yii::app()->createUrl("rcsr/booking/setcompletebooking"); ?>';
        $.ajax({
            'type': 'GET',
            'url': href,
            'dataType': 'json',
            global: false,
            data: {"bkIds": checkedIds.toString()},
            success: function (data) {
                if (data.success) {
                    if (checkCounter >= checked.length)
                    {
                        ajaxindicatorstop();
                        checkCounter = 0;
                        updateGrid(5);
                        removeTabCache(6);
                    } else
                    {
                        setMarkComplete();
                    }
                } else {
                    ajaxindicatorstop();
                    checkCounter = 0;
                    alert("Sorry error occured");
                }
            },
            error: function (xhr, status, error) {
                ajaxindicatorstop();
                checkCounter = 0;
                alert(xhr.error);
            }
        });

    }
</script>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

Yii::app()->getClientScript()->registerScript("refreshCGrid", "refreshGrid = function(){

$.fn.yiiGridView.update('bookingTab6');
$('#bookingTab5').yiiGridView('update');
};
");
		$time						 = Filter::getExecutionTime();

$GLOBALS['time'][9]	 = $time;
?>