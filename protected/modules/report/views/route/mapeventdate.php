<div class="row">
    <div class="col-xs-12 widget-menu">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'booking-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array('class' => '',),
		));
		/* @var $form TbActiveForm */
		?>



        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
							<?php
							$daterang	 = "Select Event Date Range";
							$fromDate	 = ($model->fromDate == '') ? '' : $model->fromDate;
							$toDate		 = ($model->toDate == '') ? '' : $model->toDate;
							if ($fromDate != '' && $toDate != '')
							{
								$daterang = date('F d, Y', strtotime($fromDate)) . " - " . date('F d, Y', strtotime($toDate));
							}
							?>
                            <label  class="control-label">Event Date</label>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?php
							echo $form->hiddenField($model, 'fromDate');
							echo $form->hiddenField($model, 'toDate');
							echo $form->hiddenField($model, 'eventId');
							?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4" >
                        <button class="btn btn-primary mt20" type="submit" name="bookingSearch">Map Event</button>
                    </div>
                </div>
            </div>
        </div>


		<?php $this->endWidget(); ?>
    </div>
</div>					



<script>
    $(document).ready(function ()
    {


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
            $('#CalendarEvent_fromDate').val(start1.format('YYYY-MM-DD'));
            $('#CalendarEvent_toDate').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Event Date Range');
            $('#CalendarEvent_fromDate').val('');
            $('#CalendarEvent_toDate').val('');
        });
    });

</script>
