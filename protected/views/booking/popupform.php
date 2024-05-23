<script>

    $(document).ready(function () {

	
		
		 $('#log_booking_type').val($('#Booking_bkg_booking_type').val());
        $('#log_from_city_id1').val($('#Booking_bkg_from_city_id').val());
        $('#log_from_city_id2').val($('#Booking_bkg_from_city_id').val());
        $('#log_to_city_id1').val($('#Booking_bkg_to_city_id').val());
        $('#log_to_city_id2').val($('#Booking_bkg_to_city_id').val());
        $('#log_pickup_date_date1').val($('#Booking_bkg_pickup_date_date').val());
        $('#log_pickup_date_date2').val($('#Booking_bkg_pickup_date_date').val());
        $('#log_pickup_date_time1').val($('[name="<?= CHtml::activeName('Booking', 'bkg_pickup_date_time') ?>"]').val());
        $('#log_pickup_date_time2').val($('[name="<?= CHtml::activeName('Booking', 'bkg_pickup_date_time') ?>"]').val());

    });



    $skipPopup = false;
    var vals = 0;
    var t;



    function skipPopup() {
        $skipPopup = true;
    }

    $(window).bind('beforeunload', function () {
        if ($skipPopup)
        {
            return null;
        } else {
            var message = 'Before you leave, Please tell us how can we do better and get Rs 250/- e-cash towards your next booking';
            if (typeof evt == 'undefined') {
                evt = window.event;
            }
            timedCount();
            vals++;
            if (evt) {
                evt.returnValue = message;
                return message;
            }
            trace(evt);
        }
    });
    window.onunload = function () {
        clearTimeout(t);
    };



    function resetTimer() {

        clearTimeout(t);
        t = setTimeout("timedCount()", 15000);
    }

    function timedCount()
    {
        t = setTimeout("timedCount()", 15000);
        if (vals > 0)
        {
            showFeedbackPop();
            clearTimeout(t);
        }
    }

    function showFeedbackPop() {
        target = document.activeElement;
//        var url = target.href;
//       $('#redirect_url1').val(url);
//        $('#redirect_url2').val(url);
        $('#logModal').modal('show');
    }

    window.setTimeout(showFeedbackPop, 15000);
    function callFunction1() {
        $('#phoneNo').removeClass('hidden');
        $('#changedForm').addClass('hidden');
    }
    function callFunction2() {
        $('#phoneNo').addClass('hidden');
        $('#changedForm').removeClass('hidden');
    }
</script>

<div class="modal fade" id="logModal" role="dialog" style="display: none">
    <div class="modal-dialog" style="width: 480px">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
				<?= CHtml::htmlButton('x', ['class' => "close pull-right", 'data-dismiss' => "modal"]) ?>
                <h4> How can we help? </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-7">
                        <label class="form-control  border-none pl0"  for="exitOption1"><input id="exitOption1" type="radio" name="customerfb" value="call" checked onclick="callFunction1()"><span class="ml10">Call me to make a booking</span></label>
                    </div>
					<div class="col-xs-5">
                        <label class="form-control border-none pl0"  for="exitOption2"><input id="exitOption2" type="radio" name="customerfb" value="changed" onclick="callFunction2()"><span class="ml10">Changed my mind</span></label>
                    </div>
                </div>
                <div class="" id="phoneNo" style="margin-top: 10px;margin-bottom: 10px;padding-top: 10px;padding-bottom: 10px;">
					<?= CHtml::beginForm(Yii::app()->createUrl('booking/leavelog'), "post", ['accept-charset' => "UTF-8", 'id' => "phoneForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>


					<?= CHtml::hiddenField('log_booking_type', '', ['id' => "log_booking_type"]) ?>
					<?= CHtml::hiddenField('log_from_city_id1', '', ['id' => "log_from_city_id1"]) ?>
					<?= CHtml::hiddenField('log_to_city_id1', '', ['id' => "log_to_city_id1"]) ?>
					<?= CHtml::hiddenField('log_pickup_date_date1', '', ['id' => "log_pickup_date_date1"]) ?>
					<?= CHtml::hiddenField('log_pickup_date_time1', '', ['id' => "log_pickup_date_time1"]) ?>

                    <div class="form-group">
                        <label for="phone"><b>Phone : </b></label>
						<?= CHtml::numberField("phone1", '', [ 'placeholder' => "Phone", 'class' => "form-control", 'id' => "phone1", 'required' => true]); ?>
                    </div>
                    <div class="Submit-button row text-center">
						<?= CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary", 'onclick' => "skipPopup()"]); ?>
                    </div>
					<?= CHtml::endForm() ?>
                </div>
                <div class="hidden" id="changedForm" style="margin-top: 10px;margin-bottom: 10px;padding-top: 10px;padding-bottom: 10px;">
					<?= CHtml::beginForm(Yii::app()->createUrl('booking/leavelog'), "post", ['accept-charset' => "UTF-8", 'id' => "changedForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>


					<?= CHtml::hiddenField('log_from_city_id2', '', ['id' => "log_from_city_id2"]) ?>
					<?= CHtml::hiddenField('log_to_city_id2', '', ['id' => "log_to_city_id2"]) ?>
					<?= CHtml::hiddenField('log_pickup_date_date2', '', ['id' => "log_pickup_date_date2"]) ?>
					<?= CHtml::hiddenField('log_pickup_date_time2', '', ['id' => "log_pickup_date_time2"]) ?>

                    <div class="form-group">
                        <label for="comment"><b>Comment : </b></label>
						<?= CHtml::textArea("comment2", '', ['class' => "form-control", 'rows' => "3", "cols" => "50", 'placeholder' => "Tell us how can we do better and get Rs 250/- e-cash towards your next booking", 'required' => 'required']) ?>

                    </div>
                    <div class="form-group">
                        <label for="phone"><b>Phone : </b></label>
						<?= CHtml::numberField("phone2", '', [ 'placeholder' => "Phone", 'class' => "form-control", 'id' => "phone2", 'required' => true]); ?>

                    </div>
                    <div class="form-group">
                        <label for="email"><b>Email : </b></label>
						<?= CHtml::emailField("email2", '', [ 'placeholder' => "Email (optional)", 'class' => "form-control", 'id' => "email2"]); ?>

                    </div>
                    <div class="Submit-button row text-center">
						 <?= CHtml::submitButton('SUBMIT', array('class' => 'btn btn-success btn-lg pl40 pr40', 'onclick' => "saveLog()")); ?>
						
                    </div>
					<?= CHtml::endForm() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function saveLog() {
        skipPopup();
		alert($('#Booking_bkg_booking_type').val());
//        $.ajax({
//            "type": "POST",
//            "dataType": "json",
//            "url": "<?//= CHtml::normalizeUrl(Yii::app()->createUrl('booking/finalbook')) ?>/ctype/" + ctype,
//            "data": $("#confirmbook").serialize(),
//            "success": function (data2) {
//                if (data2.success) {
//                    //alert(data2.url);+
//
//                    location.href = data2.url;
////                   
//                } else {
//
//                    var errors = data2.errors;
//                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
//                    $.each(errors, function (key, value) {
//                        txt += "<li>" + value + "</li>";
//                    });
//                    txt += "</li>";
//                    $("#error_div1").show();
//                    $("#error_div1").html(txt);
//
//                }
//            }
//        });
    }
</script>