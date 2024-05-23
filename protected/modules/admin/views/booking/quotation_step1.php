<?php
$datacity	 = Cities::model()->getJSON();
?>
<div class="row">
    <div class="col-xs-12 book-panel2">
        <div class="container p0 mt20">
            <div class="col-xs-12">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'quotation-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data',
					),
				));
				?>
                <div>
                    <div class="profile-right-panel p20">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-3 mb20">Total Kms:<b></b></div>
                            <div class="col-xs-6 col-sm-6 col-md-3 mb20">#Add Pick/Drops:<b></b> (@Rs:150)</div>
                            <div class="col-xs-6 col-sm-6 col-md-3">Total # of days: <b></b></div>
                            <div class="col-xs-6 col-sm-6 col-md-3">
								<?= CHtml::submitButton('Create Quotation', array('class' => 'btn btn-success btn-lg')); ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-7">
                                <div class="col-xs-12 mb10 p0">
                                    <div class="col-xs-12 col-sm-6 p0">
                                        <label class="radio-inline">
											<?=
											$form->radioButtonListGroup($model, 'qot_trip_type', array(
												'label'			 => '', 'widgetOptions'	 => array(
													'data' => array('1' => 'One Way', '2' => 'Round or Multi-city Trip')
												), 'inline'		 => true,)
											);
											?>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-6" id="startDateDiv">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Start Date</label>
                                            <div class="col-sm-8">
												<?=
												$form->datePickerGroup($model, 'qot_start_date', array('label'			 => '',
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Date & Time', 'value' => '')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 p0">
                                    <div class="col-xs-12 col-sm-6 p0">&nbsp;</div>
                                    <div class="col-xs-12 col-sm-6" id="endDateDiv">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">End Date</label>
                                            <div class="col-sm-8">
												<?=
												$form->datePickerGroup($model, 'qot_end_date', array('label'			 => '',
													'widgetOptions'	 => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Date & Time', 'value' => '')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-5">
                                <div class="col-xs-12 col-sm-3">Car Category<br>(<i>Select one or more to generate multiple quotes</i>)</div>
                                <div class="col-xs-12 col-sm-9">
									<?= $form->checkboxListGroup($model, 'qot_car_type', array('label' => '', 'widgetOptions' => array('data' => VehicleTypes::model()->getCarType()))) ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3 p0 mb20">
                                <div class="row">
                                    <div class="col-xs-12 mb10">
                                        <label for="inputEmail3" class="col-sm-6 control-label">Customer Name</label>
                                        <div class="col-sm-6">
											<?= $form->textFieldGroup($model, 'qot_name', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name', 'id' => 'inputEmail3')))) ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <label for="inputEmail3" class="col-sm-6 control-label">Passengers</label>
                                        <div class="col-sm-6">
											<?= $form->textFieldGroup($model, 'qot_passenger', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'XX', 'id' => 'inputEmail3', 'style' => 'width:50px')))) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 mb20">
                                <div class="row">
                                    <div class="col-xs-12 p0 mb10">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-9">
											<?= $form->textFieldGroup($model, 'qot_email', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email', 'id' => 'inputEmail3')))) ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 p0">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Luggage</label>
                                        <div class="col-sm-9">
											<?= $form->textFieldGroup($model, 'qot_luggage', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'XX', 'id' => 'inputEmail3', 'style' => 'width:50px')))) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                <div class="col-sm-9">
									<?= $form->textFieldGroup($model, 'qot_phone', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone', 'id' => 'inputEmail3')))) ?>
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
                            <div class="col-xs-12 col-md-8">
                                <div class="col-xs-12 col-sm-2">Pickup Address</div>
                                <div class="col-xs-12 col-sm-4">
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
                                <div class="col-xs-12 col-sm-6">
									<?= $form->textFieldGroup($model, 'qot_pickup_point', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Pickup Address')))) ?>
                                </div>

                            </div>
                            <div class="col-xs-12 col-md-8">
                                <div class="col-xs-12 col-sm-2">Drop Address</div>
                                <div class="col-xs-12 col-sm-4">
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
                                <div class="col-xs-12 col-sm-6">
									<?= $form->textFieldGroup($model, 'qot_drop_point', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Drop Address')))) ?>
                                </div>

                            </div>
                            <div class="col-xs-12 col-md-8">
                                <button class="btn btn-warning" type="submit"><i class="fa fa-plus"></i> Add Pickup Point</button>
                            </div>
                        </div>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $tripType = <?= $model->qot_trip_type ?>;
    $(document).ready(function () {
        selectTripType($tripType);
        $("#Quotation_qot_trip_type_0").click(function () {
            var val = $("#Quotation_qot_trip_type_0").val();
            selectTripType(val);
        });
        $("#Quotation_qot_trip_type_1").click(function () {
            var val = $("#Quotation_qot_trip_type_1").val();
            selectTripType(val);
        });
    });
    function selectTripType(type) {
        if (type == '1') {
            $("#startDateDiv").show();
            $("#endDateDiv").hide();
        } else if (type == '2') {
            $("#startDateDiv").show();
            $("#endDateDiv").show();
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
                /** @type {!HTMLInputElement} */(document.getElementById(citybox)),
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
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhybncyDc1ddM2qzn453XqYW8ZQDm7RW8&libraries=places"
async defer></script>

