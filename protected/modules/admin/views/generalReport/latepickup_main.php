<style>
	.table-flex { display: flex; flex-direction: column; }
	.tr-flex { display: flex; }
	.th-flex, .td-flex{ flex-basis: 35%; }
	.thead-flex, .tbody-flex { overflow-y: scroll; }
	.tbody-flex { max-height: 250px; }
</style>

<?php 
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'urgentPickUp-report', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => '',
	),
		));
/* @var $form TbActiveForm */
?>
        
        <div class="row"> 
            
            <div class="col-xs-12 col-sm-4 col-md-4" style="">
                <div class="form-group">
                    <label class="control-label">Date Range</label>
                    <?php
                    $daterang			 = "Select Date Range";
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
                <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
            </div>
        </div>
<?php

$this->renderPartial('latepickup_v3', $latepickupList['latepickup_v3'], false);
echo '<hr>';
$this->renderPartial('latepickup_v2', $latepickupList['latepickup_v2'], false);
echo '<hr>';
$this->renderPartial('latepickup_v1', $latepickupList['latepickup_v1'], false);
?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    function viewRelatedBooking(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function ()
                    {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }


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
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#BookingSub_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#BookingSub_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#BookingSub_bkg_pickup_date1').val('');
        $('#BookingSub_bkg_pickup_date2').val('');
    });
</script>