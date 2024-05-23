
<?php
/* @var $model BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/bookingRoute.js?v=$version");
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
									echo $this->widget('zii.widgets.jui.CJuiDatePicker',array(
											'model'=>$model,
											'attribute'=>'[' . $ctr . ']brt_pickup_date_date',
											'options'=> array('autoclose'=> true, 'startDate'=> "js:new Date('$minDate')",'format'=> 'dd/mm/yyyy'),
											'htmlOptions'=> array('required' => true,'min' => $model->brt_min_date, 'placeholder'	=> 'Pickup Date',
											'value'	=> $model->brt_pickup_date_date,'id' => 'brt_pickup_date_date_shuttle',
											'class'	=> 'form-control datePickup border-radius')
										),true);
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
			var estarvtime = '<?php echo date('h:i a',strtotime($estArrTime));?>';
			var arvlcnt = '<?php echo ($arvlcnt - 1);?>';
			//alert(estarvtime);alert(arvlcnt);
			$('.arvlcntcls'+arvlcnt).html(estarvtime);
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
						}
						else
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
							}
							else
							{

								$('.destSource').append($("<option></option>").attr("value", key).text(value));
							}
						});
					}
				});
			}
		}

		
</script>