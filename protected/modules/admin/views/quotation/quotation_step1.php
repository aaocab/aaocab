<?php
$datacity	 = Cities::model()->getJSON();
?>
<div class="row">
    <div class="col-xs-12 book-panel2">
        <div class="container p0 mt20">
            <div class="col-xs-12">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'quotation-form',
					'action'				 => '' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/quotation/step1')) . '',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data',
					),
				));
				?>
                <div class="table table-bordered">
                    <div >
                        <div class="profile-right-panel p20">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-9">&nbsp;</div>
                                <div class="col-xs-6 col-sm-6 col-md-3">
									<?= CHtml::submitButton('Create Quotation', array('class' => 'btn btn-success btn-lg')); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-5">
                                        <label class="radio-inline">
											<?=
											$form->radioButtonListGroup($model, 'qot_trip_type', array(
												'label'			 => '', 'widgetOptions'	 => array(
													'data' => Quotation::model()->tripList
												), 'inline'		 => true,)
											);
											?>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-7">
                                        <div class="col-xs-12 col-sm-6">
                                            <b>Car Category</b><br>(<i>Select one or more to generate multiple quotes</i>)
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
											<?= $form->checkboxListGroup($model, 'qot_car_type', array('label' => '', 'widgetOptions' => array('data' => VehicleTypes::model()->getCarType()))) ?>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-3 p0 mb20">
                                    <div class="row">
                                        <div class="col-xs-12 mb10">
                                            <label for="inputEmail3" class="col-sm-6 control-label"><b>Customer Name</b></label>
                                            <div class="col-sm-6">
												<?= $form->textFieldGroup($model, 'qot_name', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <label for="inputEmail3" class="col-sm-6 control-label"><b>Passengers</b></label>
                                            <div class="col-sm-6">
												<?= $form->textFieldGroup($model, 'qot_passenger', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'XX', 'style' => 'width:50px')))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3 mb20">
                                    <div class="row">
                                        <div class="col-xs-12 p0 mb10">
                                            <label for="inputEmail3" class="col-sm-3 control-label"><b>Email</b></label>
                                            <div class="col-sm-9">
												<?= $form->textFieldGroup($model, 'qot_email', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 p0">
                                            <label for="inputEmail3" class="col-sm-3 control-label"><b>Luggage</b></label>
                                            <div class="col-sm-9">
												<?= $form->textFieldGroup($model, 'qot_luggage', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'XX', 'style' => 'width:50px')))) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="inputEmail3" class="col-sm-3 control-label"><b>Phone</b></label>
                                    <div class="col-sm-9">
										<?= $form->textFieldGroup($model, 'qot_phone', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <h5 class="m0">Special needs</h5>
                                    <div class="col-xs-12 p0">
                                        <label class="checkbox-inline">
											<?= $form->checkboxListGroup($model, 'qot_special_needs', array('label' => '', 'widgetOptions' => array('data' => Quotation::model()->specialNeeds))) ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-3">
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'qot_pickup_city',
											'val'			 => $model->qot_pickup_city,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($datacity)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Pickup City')
										));
										?>
                                    </div>
                                    <div class="col-xs-12 col-sm-3">
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'qot_drop_city',
											'val'			 => $model->qot_drop_city,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($datacity)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Drop City')
										));
										?>
                                    </div>
                                    <div class="col-xs-12 col-sm-2">
										<?=
										$form->datePickerGroup($model, 'qot_start_date', array('label'			 => '',
											'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
													'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Pickup Date',
													'value'			 => '')),
											'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
                                    </div>
                                    <div class="col-xs-12 col-sm-2">&nbsp;</div>
                                    <div class="col-xs-12 col-sm-2 pickupAddmore">
                                        <div class="btn btn-warning addmore" title="Add Pickup Point">
                                            <i class="fa fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="pickupPointDiv">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-2" style="text-align: center;"><b>Pickup Point</b></div>
                                    <div class="col-xs-12 col-sm-4">
										<?= $form->textFieldGroup($model, 'qot_pickup_point', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Pickup Address')))) ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-6"></div>


                                </div>
                            </div>
                            <div class="row" id="dropPointDiv">
                                <div class="col-xs-12 col-md-12">
                                    <div class="col-xs-12 col-sm-2" style="text-align: center; vertical-align: middle;"><b>Drop Point</b></div>
                                    <div class="col-xs-12 col-sm-4">
										<?= $form->textFieldGroup($model, 'qot_drop_point', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Drop Address')))) ?>
                                    </div> 
                                    <div class="col-xs-12 col-sm-6"></div>
                                </div>

                            </div>
                            <div id="QuotationList"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="quotation_data" id="quotation_data" value="<?= $quotationData; ?>">
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $tripType = <?= $model->qot_trip_type ?>;
    var tripWay = <?= $model->qot_trip_type ?>;

    $(document).ready(function () {
        selectTripType($tripType);
        $("#Quotation_qot_trip_type_0").click(function () {
            var val = $("#Quotation_qot_trip_type_0").val();
            selectTripType(val);
            tripWay = val;
        });
        $("#Quotation_qot_trip_type_1").click(function () {
            var val = $("#Quotation_qot_trip_type_1").val();
            selectTripType(val);
            tripWay = val;
        });
        $("#Quotation_qot_trip_type_2").click(function () {
            var val = $("#Quotation_qot_trip_type_2").val();
            selectTripType(val);
            tripWay = val;
        });

        $('.pickupAddmore').click(function () {
            var pickupCty = $("#Quotation_qot_pickup_city").val();
            var dropCty = $("#Quotation_qot_drop_city").val();
            var startDate = $("#Quotation_qot_start_date").val();
            var pickupPoint = $("#Quotation_qot_pickup_point").val();
            var dropPoint = $("#Quotation_qot_drop_point").val();
            var qotData = $("#quotation_data").val();
            var tripType = tripWay;
            $.ajax({
                "type": "GET",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/quotation/ajaxstep1', ['pickupCty' => pickupCty, 'dropCty' => dropCty, 'pickupPoint' => pickupPoint, 'dropPoint' => dropPoint, 'startDate' => startDate, 'qotData' => qotData, 'tripType' => tripType])) ?>",
                "dataType": "json",
                "data": {"pickupCty": pickupCty, "dropCty": dropCty, "pickupPoint": pickupPoint, "dropPoint": dropPoint, "startDate": startDate, "qotData": qotData, "tripType": tripType},
                success: function (data1) {
                    if (data1.success) {
                        $('#quotation_data').val(data1.quotation);
                        $('#Quotation_qot_pickup_city').select2('val', dropCty);
                        $('#Quotation_qot_drop_city').select2('val', '');
                        $('#Quotation_qot_start_date').val('');
                        $('#Quotation_qot_pickup_point').val(dropPoint);
                        $('#Quotation_qot_drop_point').val('');
                        $('#QuotationList').html(data1.quotationStr);
                        if (data1.quotationCnt > '1') {
                            $("#pickupPointDiv").hide();
                            $("#dropPointDiv").show();
                        } else {
                            $("#pickupPointDiv").show();
                            $("#dropPointDiv").show();
                        }
                        if (tripWay == '1') {
                            $(".pickupAddmore").hide();
                        } else if (tripWay == '2') {
                            $(".pickupAddmore").show();
                        } else if (tripWay == '3') {
                            $(".pickupAddmore").show();
                        }
                        /*
                         $.each(JSON.parse(data1.quotation), function (idx, obj) {
                         $('.showFromCity').text(obj.pickup_cityname);
                         $('.showToCity').text(obj.drop_cityname);
                         $('.showDate').text(obj.date);
                         });
                         */
                    }

                },
                failure: function (response) {
                    alert(response.d);
                }
            });


        });


    });
    function selectTripType(type) {
        if (type == '1') {
            $(".pickupAddmore").show();
        } else if (type == '2') {
            $(".pickupAddmore").show();
        } else if (type == '3') {
            $(".pickupAddmore").show();
        }
    }
    $("#<?= CHtml::activeId($model, "qot_pickup_city") ?>").change(function () {
        var city_id = $(this).val();
        var city_box = "<?= CHtml::activeId($model, "qot_pickup_point") ?>";
        $('#Quotation_qot_pickup_point').val('');
        getCityDetails(city_id, city_box);
    });
    $("#<?= CHtml::activeId($model, "qot_drop_city") ?>").change(function () {
        var city_id = $(this).val();
        var city_box = "<?= CHtml::activeId($model, "qot_drop_point") ?>";
        $('#Quotation_qot_drop_point').val('');
        getCityDetails(city_id, city_box);
    });

    function getCityDetails(cityId, cityBox) {
        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/city/getcitydetails', ['Id' => cityid, 'cityBox' => citybox])) ?>",
            "data": {"Id": cityId, 'cityBox': cityBox},
            success: function (data) {
                var lat = parseFloat(data.lat);
                var long = parseFloat(data.long);
                var radius = parseInt(data.radius) * 1000;
                initAutocomplete(lat, long, radius, cityBox);
            }
        });


    }

    function initAutocomplete(lat, long, radius, citybox) {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        var geolocation = new google.maps.LatLng(lat, long);
        var circle = new google.maps.Circle({
            center: geolocation,
            radius: radius,
        });

        autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */
                        (document.getElementById(citybox)),
                        {
                            types: ['address'],
                            bounds: circle.getBounds(),
                            componentRestrictions: {country: 'in'}
                        });

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', function () {
            var d = autocomplete.getPlace();

        });
    }


    function handleData(route, cabList) {
        alert(route);
        alert(cabList);
        var jsonRoute = JSON.stringify(route);
        var jsonCabList = JSON.stringify(cabList);
        $.ajax({
            type: "POST",
            url: '/admpnl/booking/Quotation2',
            data: '{route: "' + jsonRoute + '", cabList: "' + jsonCabList + '"}',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response) {
                alert(response);
            },
            failure: function (response) {
                alert(response.d);
            }
        });

        //do some stuff
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places"
async defer></script>

