<?php
/* @var $model BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/bookingRoute.js?v=$autoAddressJSVer");

$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
if ($sourceCity == "")
{
	$cityList	 = Cities::model()->getJSONAirportCitiesAll();
	$pcityList	 = $cityList;
}
else
{
	$model->brt_from_city_id = $sourceCity;
	$cmodel					 = Cities::model()->getDetails($sourceCity);
	$sourceCityName			 = $cmodel->cty_name . ', ' . $cmodel->ctyState->stt_name;
	$pcityList				 = Cities::model()->getJSONNearestAll($previousCity);
}
if ($model->brt_from_city_id != '')
{
	$cityList = Cities::model()->getJSONNearestAll($model->brt_from_city_id);
}
$sourceDivClass	 = 'col';
$dateDivClass	 = 'col';
if ($btype == 2)
{
	$sourceDivClass	 = 'col-md-6';
	$dateDivClass	 = 'col-md-12';
}
if ($btype == 3)
{
	$mcitiesDiv = "  col-md-4";
}
//echo $model->estArrTime[$index];
$ctr = rand(0, 99) . date('mdhis');
?>
<?php
if ($btype == 7)
{

	$minDate = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
	?>
	<div class="container search-panel-2 clsRoute">
		<div class="row mt30 ">
			<div class="col-12">
				<div class="bg-white-box">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-4 p5">

							<div class="form-group mr0 ml0">
								<label class="control-label">Depart date</label>
								<div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
									<?php
									echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
										'model'			 => $model,
										'attribute'		 => '[' . $ctr . ']brt_pickup_date_date',
										'options'		 => array('autoclose' => true, 'startDate' => "js:new Date('$minDate')", 'format' => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('required'		 => true, 'min'			 => $model->brt_min_date, 'placeholder'	 => 'Pickup Date',
											'value'			 => $model->brt_pickup_date_date, 'id'			 => 'brt_pickup_date_date_shuttle',
											'class'			 => 'form-control datePickup border-radius')
											), true);
									?>
								</div>
							</div>

							<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[<?= $ctr ?>][brt_pickup_date_time]"  value="<?= $model->brt_pickup_date_time ?>" >

						</div> 
						<div class="col-12 col-sm-6 col-md-4 p5  " >
							<div class="form-group col-xs-12">

								<label class="control-label" id='trslabel'>Going From</label><br>
								<select class="form-control inputSource " name="BookingRoute[<?= $ctr ?>][brt_from_city_id]"  
										id="<?= 'brt_from_city_id_' . $ctr ?>" onchange="populateDropCity()">
								</select>


								<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-md-4 p5  ">
							<div class="form-group">
								<label class="control-label" id='trdlabel'>Going To</label><br>
								<select class="form-control destSource " name="BookingRoute[<?= $ctr ?>][brt_to_city_id]"  
										id="<?= 'brt_to_city_id_' . $ctr ?>"  >
								</select>
							</div>
						</div>

						<div class="col-md-4 text-center mt10 mb10" >
							<?= CHtml::submitButton('NEXT', array('class' => 'btn-orange pl30 pr30 devbtnstep2', 'id' => 'btnSubmit')); ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
else if ($btype != 4 && $btype != 7 && $btype != 9 && $btype != 10 && $btype != 11)
{
	?>
	<div class="container search-panel-2 mt15 clsRoute">
		<div class="bg-white-box">
			<div class="row"><div class="col-lg-6 col-md-12">
					<div class="row mb15">
						<div class="<?= $sourceDivClass ?>" >
							<div class="input-group">
								<label class="control-label" id='trslabel' style="width:100%;">Going From</label>
								<?php
								$widgetId = $ctr . "_" . random_int(99999, 10000000);
								$this->widget('application.widgets.BRCities', array(
									'type'				 => 1,
									'enable'			 => ($index == 0),
									'widgetId'			 => $widgetId,
									'model'				 => $model,
									'attribute'			 => '[' . $ctr . ']brt_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
								));
								?>
								<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
							</div>
						</div>
						<div class="<?= $sourceDivClass ?>">
							<div class="input-group">
								<label class="control-label" id='trdlabel'>Going To</label><br>
								<?php
								$this->widget('application.widgets.BRCities', array(
									'type'				 => 2,
									'widgetId'			 => $widgetId,
									'model'				 => $model,
									'attribute'			 => '[' . $ctr . ']brt_to_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
								));
								?>
							</div>
						</div>
					</div></div>
				<div class="<?= $dateDivClass ?>">
					<?php
					if ($btype == 2)
					{
						?>
						<div class="row">
							<label class="col-md-6 control-label"><b>Trip start information</b></label>
							<label class="col-md-6 control-label"><b>Trip End information</b></label>
						</div>
						<?php
					}
					?>
					<div class="row search-panel-3 <?= ($btype == 2) ? 'pt0' : '' ?>">
						<div class="col-md-4">
							<div class="form-group ">
								<label class="control-label pl10">Depart date</label>
								<div class="input-group"><span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
									<?php
									echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
										'model'			 => $model,
										'attribute'		 => '[' . $ctr . ']brt_pickup_date_date',
										'options'		 => array('autoclose' => true, 'startDate' => "js:new Date('$model->brt_min_date')", 'format' => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('required'		 => true, 'min'			 => $model->brt_min_date, 'placeholder'	 => 'Pickup Date',
											'value'			 => $model->brt_pickup_date_date, 'id'			 => 'brt_pickup_date_date_' . date('mdhis'),
											'class'			 => 'form-control datePickup border-radius')
											), true);
									?>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label pl10">Depart time</label>
							<div class="input-group"><span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-clock"></i></span></span>
								<?php
								$this->widget('ext.timepicker.TimePicker', array(
									'model'			 => $model,
									'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
									'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
									'options'		 => ['widgetOptions' => array('options' => array())],
									'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
								));
								?>
							</div>
						</div>
						<?php $arvlcnt = ($arvlcnt == '' || $arvlcnt == 0) ? 1 : $arvlcnt; ?>
						<input type="hidden" name="prevarvtime" id="prevarvtime"  value="<?php echo $model->arrival_time; ?>">
						<input type="hidden" name="arvlcnt" id="arvlcnt"  value="<?php echo $arvlcnt; ?>">
						<?php //if($estArrTime != '' || $model->arrival_time !=''){ ?>
						<div class="col-md-4 estarvtime hide">
							<label class="control-label pl10">Arrival time<?php //echo $arvlcnt;   ?></label>
							<div class="input-group"><span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-clock"></i></span></span>
								<span class="form-control arvlcntcls<?php echo $arvlcnt; ?>" style="padding-top: 7px;">	
									<?php
									if ($model->arrival_time != '')
									{
										echo date('h:i a', strtotime($model->arrival_time));
									}
									else
									{
										echo date('h:i a', strtotime($estArrTime));
									}
									?>
								</span>
							</div>
						</div>
						<?php //}?>
					</div>
					<?php
					if ($btype == 2)
					{
						?>
						<div class="col-md-6 mb5">
							<div class="form-group">
								<label class="control-label">Return date</label>
								<div class="input-group"><span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
									<?php
									echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
										'model'			 => $model,
										'attribute'		 => '[' . $ctr . ']brt_return_date_date',
										'options'		 => array('autoclose' => true, 'startDate' => "js:new Date('$model->brt_min_date')", 'format' => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('required'		 => true, 'min'			 => $model->brt_min_date, 'placeholder'	 => 'Return Date',
											'value'			 => $model->brt_return_date_date, 'id'			 => 'brt_return_date_date_' . date('mdhis'),
											'class'			 => 'form-control dateReturn border-radius')
											), true);
									?>
								</div>
							</div>
						</div>
						<div class="col-md-6 p0">
							<label class="control-label">Return time</label>
							<div class="input-group"><span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-clock"></i></span></span>
										<?php
										$this->widget('ext.timepicker.TimePicker', array(
											'model'			 => $model,
											'id'			 => 'brt_return_date_time' . date('mdhis'),
											'attribute'		 => '[' . $ctr . ']brt_return_date_time',
											'options'		 => ['widgetOptions' => array('options' => array())],
											'htmlOptions'	 => array('required' => true, 'placeholder' => 'Return Time', 'class' => 'form-control border-radius timeReturn text text-info col-xs-12')
										));
										?> </div>
						</div>
					<? } ?>


				</div>
			</div>
		</div>
	</div>
	<?php
}
else if ($btype == 9 || $btype == 10 || $btype == 11)
{
	$this->renderPartial('bkTypeDayRental', ['model' => $model, 'btype' => $btype, 'pcityList' => $pcityList, 'cityList' => $cityList, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'form' => $form, 'selectizeOptions' => $selectizeOptions], false, false);
}
else if ($btype == 4)
{
	$this->renderPartial('bkTypeAirportTransfer', ['brtRoute' => $model, 'pcityList' => $pcityList, 'cityList' => $cityList, 'btype' => $btype, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'form' => $form, 'selectizeOptions' => $selectizeOptions], false, false);
}
?>
<script>
    $sourceList = null;
    $loadCityId = 0;
    $(document).ready(function ()
    {
        populateShuttleSource();
        bttype = '<?= $btype ?>';
        tocity = '<?= $model->brt_from_city_id ?>';
        if (bttype == 9 || bttype == 10 || bttype == 11)
        {
            $('.arrivecity').children('option').remove();
            $('.arrivecity').addClass('hide');
            $('.arrivecity').append('<option value="' + tocity + '"> ' + tocity + ' </option>');

        }
        var estarvtime = '<?php echo date('h:i a', strtotime($estArrTime)); ?>';
        var arvlcnt = '<?php echo ($arvlcnt - 1); ?>';
        //alert(estarvtime);alert(arvlcnt);
        $('.arvlcntcls' + arvlcnt).html(estarvtime);
    });
    $('#brt_pickup_date_date_shuttle').change(function ()
    {
        $('.destSource').val('');
        populateShuttleSource();

    });



    function populateShuttleSource()
    {
        fromCity = '<?= $model->brt_from_city_id ?>';
        dateVal = $('#brt_pickup_date_date_shuttle').val();
        $('.inputSource').val('');
        $('.destSource').html('');

        $.ajax({
            "type": "POST",
            dataType: 'json',
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getpickupcitylist')) ?>",
            data: {
                'dateVal': dateVal
            },
            "async": false,
            "success": function (data1)
            {

                $('.inputSource').children('option').remove();
                $(".inputSource").append('<option value="">Select Pickup City</option>');
                $.each(data1, function (key, value)
                {
                    if (fromCity != '' && fromCity == key)
                    {
                        $('.inputSource').append($("<option></option>").attr("value", key).attr("selected", "selected").text(value));
                    } else
                    {
                        $('.inputSource').append($("<option></option>").attr("value", key).text(value));
                    }

                });
            }

        });
        if (fromCity != '')
        {
            populateDropCity();
        }
    }
    function populateDropCity()
    {
        toCity = '<?= $model->brt_to_city_id ?>';
        dateVal = $('#brt_pickup_date_date_shuttle').val();
        fcityVal = $('.inputSource').val();
        $('.destSource').val('');
        if ($('#BookingTemp_bkg_booking_type').val() == 7 && fcityVal > 0)
        {
            $.ajax({
                "type": "POST",
                dataType: 'json',
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdropcitylist')) ?>",
                data: {
                    'dateVal': dateVal, 'fcityVal': fcityVal
                },
                "async": false,
                "success": function (data1)
                {

                    $('.destSource').children('option').remove();
                    $(".destSource").append('<option value="">Select Drop City</option>');
                    $.each(data1, function (key, value)
                    {
                        if (toCity != '' && toCity == key)
                        {
                            $('.destSource').append($("<option></option>").attr("value", key).attr("selected", "selected").text(value));
                        } else
                        {

                            $('.destSource').append($("<option></option>").attr("value", key).text(value));
                        }
                    });
                }
            });
        }
    }

    function getDestination()
    {
        var toCity = $('#brt_from_city_id_' + '<?= $ctr ?>').val();
        $('.arrivecity').children('option').remove();
        $('.arrivecity').addClass('hide');
        $('.arrivecity').append('<option value="' + toCity + '"> ' + toCity + ' </option>');
    }
</script>