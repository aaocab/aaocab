   
<div class="row"> 
	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */
	?>

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

        </div>
    </div>

    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
		<?php $this->endWidget(); ?>
</div>
<?php
if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'id'				 => 'booking-list',
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		//'filter' => $model,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
                        <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                        </div></div>
                        <div class='panel-body'>{items}</div>
                        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		'columns'			 => array(
			['name' => 'bcb_id', 'value' => '$data["bcb_id"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Trip ID'],
			['name'	 => 'booking_id', 'value'	 => function($data) {
					$arr	 = explode(",", $data['booking_id']);
					$arr2	 = [];
					foreach ($arr as $val)
					{
						$arr1	 = explode("-", $val);
						$arr2[]	 = CHtml::link($arr1[0], Yii::app()->createUrl("admin/booking/view", ["id" => $arr1[1]]), ["class" => "viewBooking", "target" => "_BLANK"]);
					}
					echo implode(", ", $arr2);
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Booking ID'],
			['name' => 'vendor_name', 'value' => '$data["vendor_name"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('class' => ''), 'header' => 'Vendor Name'],
			['name' => 'agent_name', 'value' => '$data["agent_name"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Partner Name'],
			['name' => 'routename', 'value' => '$data["routename"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Route Name'],
			['name' => 'quoted_vendor_amount', 'value' => '$data["quoted_vendor_amount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Quoted Vendor Amount'],
			['name' => 'vendor_amount', 'value' => '$data["vendor_amount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Vendor Amount'],
			['name' => 'gozoUnmatchedAmount', 'value' => '$data["gozoUnmatchedAmount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Quoted Gozo Amount'],
			['name' => 'gozoAmount', 'value' => '$data["gozoAmount"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Gozo Amount'],
			['name' => 'pickupDate', 'value' => '$data["pickupDate"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Pickup Date'],
	)));
}
?>
<script>
    $(document).ready(function ()
    {
        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';

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
                        "Yesterday": [moment().startOf('day').subtract(1, "day"), moment().endOf('day').subtract(1, "day")],
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        "Last 7 Days": [moment().startOf('day').subtract(6, "day"), moment().endOf('day')],
                        "Last 30 Days": [moment().startOf('day').subtract(30, "day"), moment().endOf('day')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        // 'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
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

</script>   