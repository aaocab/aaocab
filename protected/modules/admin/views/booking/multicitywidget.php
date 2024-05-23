<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');

$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?><style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30%;
    }
    .checkbox label {
        padding-left: 0px;
    }
    .dtpiker {
        position: relative;
        left: 0px;
        top: 0px;
        z-index: 99999!important;
    }
    .tmpiker {
        position: relative;
        left: 0px;
        top: 0px;
        z-index: 99999!important;
    }

    td, th {
        padding: 10px  !important ; 
    }
</style>
<div class="panel pb0">
    <div class="panel-body">
		<?php
		?>
		
        <div class="col-md-12">
			<?php
			if (Yii::app()->user->hasFlash('credits'))
			{
				?>
				<div class="flash-success">
					<div style="text-align: center;"><?php echo Yii::app()->user->getFlash('credits'); ?></div>
				</div>
			<?php } ?>



			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'multicitywidget_form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError)
			{
				if(!hasError)
				{                                      
                                               
				}
                               
			}'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			?>

            <div class="row" style="position: relative">
				<?php
					echo $form->hiddenField($model, 'bkg_booking_type');
				?>
				<input type="hidden" id="multicitysubmit" name="multicitysubmit" value="[]">
				<?php
				//for($xx = 0; $xx < 1; $xx++) {
					$this->renderPartial('multicityform', array('model' => $model, 'form' => $form));
				//}
				?>
				
				<!--<div class="col-xs-1 mt5">
					<button class="btn btn-info addmoreField" id="fieldAfter" title="Add More" onclick="funcAddnew()">
						<i class="fa fa-plus"></i> Add more
					</button>
				</div>-->
            </div>
           
			<div class="row" id='tripTable' style="display: none">
				<div class="col-xs-12 float-none marginauto">

					<div id="tripinfo_div">
						<div class="row"> <div class="col-xs-6"><h3 class="mb10 text-uppercase">Trip Info</h3></div><div class="col-xs-6 pull-right"> <h4 class="pt10 pull-right">Total days for the trip: <span class="text-success"><span id="totdays"></span> days</span></h4></div></div>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
								<th>From</th>
								<th>To</th>
								<th>Date</th>
								<th>Distance</th>
								<th>Duration</th>
								<th>Day</th>
								</thead>
								<tr id='insertTripRow'></tr>
							</table>                         
						</div>
					</div>


					<div class="row" id="return_div" style="display: none">
						<div class="col-sm-6 dtpiker">

							<?=
							$form->datePickerGroup($model, 'bkg_return_date_date', array('label'			 => 'Return Date',
								'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'id' => 'return_date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
						</div>
						<div class="col-sm-6 tmpiker">
							<?=
							$form->timePickerGroup($model, 'bkg_return_date_time', array('label'			 => 'Return Time',
								'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'id' => 'return_time'))));
							?>
						</div>
						<div id="errordivreturn" class="mt5 ml15" style="color:#da4455"></div>
					</div>

				</div>
			</div>
              
            <div class="col-xs-12 text-center mt10" id="multisubmitbtn" style="display: none">
                <button type="button" class="btn btn-success btn-lg pl40 pr40" onclick="savedatamulticity(<?= $model->bkg_booking_type ?>)">SAVE</button>
				<? //= CHtml::submitButton('SAVE', array('class' => 'btn btn-success btn-lg pl40 pr40'));    ?>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function ()
    {
        $('.bootbox').removeAttr('tabindex');

        $jsonArrMulticity = [];
        $count = 1;
        $scity = 0;
        $svalue = 0;
        jQuery('.datepicker').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
        jQuery('.timepicker').timepicker({'defaultTime': false, 'autoclose': true});
        jQuery('#return_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
        jQuery('#return_time').timepicker({'defaultTime': false, 'autoclose': true});
    });

    $('#fieldAfter').unbind("click").bind("click", function () {
        if ($('#from_city').val() != '') {
            if ($('#to_city').val() != '') {
                if ($('#pickup_date').val() != '') {
                    if ($('#pickup_time').val() != '') {
                        $('#tripTable').show();
                        if ($count > 1)
                        {
                            var fromCity = $jsonArrMulticity[($count - 2)].drop_city;
                            var pick_city_name = $jsonArrMulticity[($count - 2)].drop_city_name;
                        } else
                        {
                            var fromCity = $('#from_city').val();
                            var pick_city_name = $('#from_city').select2('data').text;
                        }
                        $jsonArrMul = [];
                        $jsonArrMul.push({
                            "pickup_city": fromCity,
                            "drop_city": $('#to_city').val(),
                            "pickup_city_name": pick_city_name,
                            "drop_city_name": $('#to_city').select2('data').text,

                            "pickup_date": $('#pickup_date').val(),
                            "pickup_time": $('#pickup_time').val(),
                            "date": $('#pickup_date').val() + " " + $('#pickup_time').val(),
                            "duration": 0,
                            "estimated_date": $('#estimated_pickup_date').val(),
                            "distance": 0,
                            "return_date": "",
                            "return_time": "",
                            "day": 0
                        });


                        var start_pickup_date = ($count == 1) ? $('#pickup_date').val() : $jsonArrMulticity[0].pickup_date;
                        var start_pickup_time = ($count == 1) ? $('#pickup_time').val() : $jsonArrMulticity[0].pickup_time;

                        var href = '<?= Yii::app()->createUrl("admin/booking/multicityvalidate"); ?>';
                        $.ajax({
                            url: href, dataType: "json",
                            data: {"multicitydata": $jsonArrMul[0], "booking_type": $('#Booking_bkg_booking_type').val(), "start_pickup_date": start_pickup_date, "start_pickup_time": start_pickup_time},
                            "success": function (data) {
                                if (data.error != 1) {
                                    if ($count == 1)
                                    {
                                        data.validate_success = true;
                                    }
                                    if (data.validate_success)
                                    {
                                        $jsonArrMulticity.push({
                                            "pickup_city": fromCity,
                                            "drop_city": $('#to_city').val(),
                                            "pickup_city_name": pick_city_name,
                                            "drop_city_name": $('#to_city').select2('data').text,
                                            "pickup_date": $('#pickup_date').val(),
                                            "pickup_time": $('#pickup_time').val(),
                                            "date": data.date,
                                            "duration": data.duration,
                                            "estimated_date": $('#estimated_pickup_date').val(),
                                            "distance": data.distance,
                                            "return_date": "",
                                            "return_time": "",
                                            "day": data.day,
                                            "totday": data.totday,
                                            "pickup_cty_lat": data.pickup_cty_lat,
                                            "pickup_cty_long": data.pickup_cty_long,
                                            "drop_cty_lat": data.drop_cty_lat,
                                            "drop_cty_long": data.drop_cty_long,
                                            "pickup_cty_bounds": data.pickup_cty_bounds,
                                            "drop_cty_bounds": data.drop_cty_bounds,
											"pickup_cty_radius": data.pickup_cty_radius,
                                            "drop_cty_radius": data.drop_cty_radius,
											"pickup_cty_is_airport": data.pickup_cty_is_airport,
                                            "drop_cty_is_airport": data.drop_cty_is_airport,
											"pickup_cty_is_poi": data.pickup_cty_is_poi,
                                            "drop_cty_is_poi": data.drop_cty_is_poi,
											"pickup_cty_loc": data.pickup_cty_loc,
                                            "drop_cty_loc": data.drop_cty_loc,
                                        });
                                        $('#estimated_pickup_date').val(data.estimated_date_next);
                                        $('#multicitysubmit').val(JSON.stringify($jsonArrMulticity));
                                        //new details div
                                        $('#insertTripRow').before('<tr class="multicitydetrow">' +
                                                '<td id="fcity0"></td>' +
                                                '<td id="tcity0"> </td>' +
                                                '<td id="fdate0"> </td>' +
                                                '<td id="citydist0"> </td>' +
                                                '<td id="citydura0"> </td>' +
                                                '<td id="noOfDays0"> </td>' +
                                                '</tr>');
                                        $('#fcity0').attr('id', 'fcity' + $count);
                                        $('#tcity0').attr('id', 'tcity' + $count);
                                        $('#fdate0').attr('id', 'fdate' + $count);
                                        $('#citydist0').attr('id', 'citydist' + $count);
                                        $('#citydura0').attr('id', 'citydura' + $count);
                                        $('#noOfDays0').attr('id', 'noOfDays' + $count);
                                        var ptripdate = $('#pickup_date').val();
                                        var ptriptime = $('#pickup_time').val();
                                        var oldtxt = $('#to_city').select2('data').text;
                                        var oldftxt = $('#from_city').select2('data').text;
                                        $('#noOfDays' + $count).text("" + data.day + "");
                                        $('#totdays').text("" + data.totday + "");
                                        if ($count > 1) {

                                            oldftxt = $jsonArrMulticity[($count - 2)].drop_city_name;
                                            $('#tcity' + ($count - 1)).html('<b>' + $jsonArrMulticity[($count - 2)].drop_city_name + "</b>");
                                        }
                                        $('#fcity' + $count).html('<b>' + oldftxt + "</b>");
                                        $('#tcity' + $count).html('<b>' + oldtxt + "</b>");
                                        $('#fdate' + $count).text(ptripdate + " " + ptriptime);
                                        $('#citydist' + $count).text(data.distance);
                                        $('#citydura' + $count).text(data.duration);
                                        //new details div
                                        if ($count == 1) {
                                            $href = '<?= Yii::app()->createUrl('admin/city/json') ?>';
                                            jQuery.ajax({"dataType": "json", url: $href, "async": false,
                                                success: function (data1) {
                                                    $data = data1;
                                                    $('#from_city').select2({data: $data, multiple: false});
                                                },
                                                error: function (xhr, status, error)
                                                {
                                                    console.log(error);
                                                }
                                            });
                                        }
                                        $('#from_city').select2("val", $jsonArrMulticity[($count - 1)].drop_city);
                                        $('#from_city').attr('disabled', true);
                                        if ($('#Booking_bkg_booking_type').val() == 2)
                                        {
                                            $('#to_city').select2("val", $jsonArrMulticity[0].pickup_city);
                                        } else
                                        {
                                            $('#to_city').select2("val", '');
                                        }
                                        $('#pickup_date').val(data.next_pickup_date);
                                        $('#pickup_time').val(data.next_pickup_time);
//                                        $('#return_time').val($jsonArrMulticity[($count - 1)].pickup_time);
//                                        $('#return_date').val($jsonArrMulticity[($count - 1)].pickup_date);
                                        $('#return_time').val(data.next_pickup_time);
                                        $('#return_date').val(data.next_pickup_date);
                                        if ($count > 1)
                                        {
                                            $('#multisubmitbtn').show();
                                        }
                                        $count++;
                                    } else
                                    {
                                        var est_date = $('#estimated_pickup_date').val();
                                        alert('pickup date time must be greater than estimated date time: ' + est_date);
                                    }
                                } else
                                {
                                    alert('Sorry! Your request can not be processed right now!Please try later.' + data.error);
                                }
                            }
                        });

                    } else {
                        alert('Please provide pickup time');
                    }
                } else {
                    alert('Please provide pickup date');
                }
            } else {
                alert('Please choose destination first');
            }
        } else {
            alert('Please choose source first');
        }
    });

    function savedatamulticity(booking_type)
    {
        
		if (booking_type == 2 && $jsonArrMulticity[($count - 2)].drop_city != $jsonArrMulticity[0].pickup_city)
        {
            alert('For round trip source and destination city must be same');
        } else {
            if (booking_type == 2)
            {
                if ($('#return_date').val() == '' && $('#return_time').val() == '')
                {
                    alert('Return date time is mandatory');
                    return;
                } else
                {
                    var d1 = getDateobj($('#return_date').val(), $('#return_time').val());
                    var d2 = getDateobj($jsonArrMulticity[($count - 2)].pickup_date, $jsonArrMulticity[($count - 2)].pickup_time);
                    if (d1 < d2)
                    {
                        alert("return date time can not be less than pickup time");
                        return;
                    }
                    $jsonArrMulticity[($count - 2)].return_date = $('#return_date').val();
                    $jsonArrMulticity[($count - 2)].return_time = $('#return_time').val();
                }
            }
            $('#multicitysubmit').val(JSON.stringify($jsonArrMulticity));
            multicitybootbox.hide();
            multicitybootbox.remove();
			$('body').removeClass('modal-open');
			$('.modal-backdrop').remove();
            var jsonstring = JSON.stringify($jsonArrMulticity);
            updateMulticity(jsonstring, ($count - 2));
            $jsonArrMulticity = [];
            $count = 1;

        }
    }
	
	function funcAddnew() {
		
	}
</script>

