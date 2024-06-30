

<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id' => 'booking-form', 'enableClientValidation' => true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
		'errorCssClass' => 'has-error'
	),
	'enableAjaxValidation' => false,
	'errorMessageCssClass' => 'help-block',
	'htmlOptions' => array(
		'class' => '',
	),
		));
/* @var $form TbActiveForm */
?>

<div class="panel panel-default">
    <div class="panel-body">
<!--        <div class="col-xs-6">
			<?//= $form->datePickerGroup($model, 'blg_created1', array('label' => 'Lead Start', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->blg_created1 == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) : DateTimeFormat::DateToDatePicker($model->blg_created1))), 'prepend' => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        <div class="col-xs-6">
			<?//= $form->datePickerGroup($model, 'blg_created2', array('label' => 'Lead End', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->blg_created2 == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) : DateTimeFormat::DateToDatePicker($model->blg_created2))), 'prepend' => '<i class="fa fa-calendar"></i>'));
			?>
        </div>
        -->
        
         <div class="col-xs-6 col-sm-4 col-lg-3">
                <?
                $daterang = "Select Lead Date Range";
                $createdate1 = ($model->blg_created1 == '') ? '' : $model->blg_created1;
                $createdate2 = ($model->blg_created2 == '') ? '' : $model->blg_created2;
                if ($createdate1 != '' && $createdate2 != '') {
                    $daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
                }
                ?>
                <label  class="control-label">Lead Date Range</label>
                <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?= $daterang ?></span> <b class="caret"></b>
                </div>
                <?
                echo $form->hiddenField($model, 'blg_created1');
                echo $form->hiddenField($model, 'blg_created2');
                ?>
            </div>
        <div class="col-xs-12 pt10">
             <label  class="control-label"></label>
	   <?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary btn-5x pr30 pl30')); ?>
        </div>

    </div>
</div>
<?php
if ($dataProvider != "")
{
	$this->widget('booster.widgets.TbGridView', [
		'id' => 'credits-grid',
		'dataProvider' => $dataProvider,
		'responsiveTable' => true,
		'filter' => $model,
		'ajaxUrl' => Yii::app()->createUrl('aaohome/lead/dailyleadreport', ['blg_created1' => $model->blg_created1, 'blg_created2' => $model->blg_created2]),
		'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
		'itemsCssClass' => 'table table-striped table-bordered mb0',
		'template' => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'columns' => [
			['name' => 'executive', 'value' => '$data->executive', 'headerHtmlOptions' => ['class' => 'col-xs-2']],
			['name' => 'total_converted', 'filter' => false, 'value' => '$data->total_converted', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'converted to booking'],
			['name' => 'total_followed', 'filter' => false, 'value' => '$data->total_followed', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'followed'],
			['name' => 'total_followed_distinct', 'filter' => false, 'value' => '$data->total_followed_distinct', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'unique followed'],
			['name' => 'total_inactive', 'filter' => false, 'value' => '$data->total_inactive', 'headerHtmlOptions' => ['class' => 'col-xs-2'], 'header' => 'inactive'],
			['name' => 'converted_ratio', 'filter' => false, 'value' => '$data->converted_ratio', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'converted ratio'],
			['name' => 'inactive_ratio', 'filter' => false, 'value' => '$data->inactive_ratio', 'headerHtmlOptions' => ['class' => 'col-xs-1'], 'header' => 'inactive ratio'],
		]
	]);
}
?>


<?php $this->endWidget(); ?>

<script type="text/javascript">
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
            $('#LeadLog_blg_created1').val(start1.format('YYYY-MM-DD'));
            $('#LeadLog_blg_created2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Lead Date Range');
            $('#LeadLog_blg_created1').val('');
            $('#LeadLog_blg_created2').val('');
        });

</script>


