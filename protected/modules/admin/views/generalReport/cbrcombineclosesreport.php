<style>
	.table-flex { display: flex; flex-direction: column; }
	.tr-flex { display: flex; }
	.th-flex, .td-flex{ flex-basis: 35%; }
	.thead-flex, .tbody-flex { overflow-y: scroll; }
	.tbody-flex { max-height: 250px; }
</style>
<div class="row">
	<div class="col-xs-12  pb10">
		<a href="/aaohome/generalReport/cbrdetailsreport" target="_blank"> Click To View  CBR Details Report</a>
	</div>
</div>
<div class="row"> 
	<?php
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
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
	$minDate = date('Y-m-d H:i:s', strtotime('-30 days'));
	?>
	<!--<div class="col-xs-12 col-sm-4 col-md-3">-->
		<?php
//		$form->datePickerGroup($booksub, 'date', array('label'			 => 'Filter Date',
//			'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => '01/01/2021', 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Filter By Assign Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
		?>  
	<!--</div>-->
        
         <div class="col-xs-12 col-sm-4 col-md-4" style="">
            <div class="form-group">
                <label class="control-label">Date Range</label>
                <?php
                $daterang = "Select Date Range";
                $bkg_from_date = ($booksub->bkg_from_date == '') ? '' : $booksub->bkg_from_date;
                $bkg_to_date = ($booksub->bkg_to_date == '') ? '' : $booksub->bkg_to_date;
                if ($bkg_from_date != '' && $bkg_to_date != '')
                {
                    $daterang = date('F d, Y', strtotime($bkg_from_date)) . " - " . date('F d, Y', strtotime($bkg_to_date));
                }
                ?>
                <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                </div>
                <?= $form->hiddenField($booksub, 'bkg_from_date'); ?>
                <?= $form->hiddenField($booksub, 'bkg_to_date'); ?>

            </div>
        </div>

	<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
		<?php $this->endWidget(); ?>
</div>
<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
													<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div></div>
											<div class='panel-body table-responsive'>{items}</div>
											<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 =>
		array
			(
			array('name'	 => 'csrName', 'value'	 => function($data) {
					echo CHtml::link($data['csrName'], Yii::app()->createUrl("aaohome/generalReport/cbrdetailsreport", ["csrId" => $data['csrId']]), ['target' => '_blank']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'CSR Name'),
			array('name'	 => 'SOcnt', 'value'	 => function ($data) {
					echo $data['SOcnt'] != null ? $data['SOcnt'] : 0;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'SO Closed Today'),
			array('name'	 => 'CBRcnt', 'value'	 => function ($data) {
					echo $data['CBRcnt'] != null ? $data['CBRcnt'] : 0;
				}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'CBR Closed Today'),
			array('name'	 => 'totalClosedCnt', 'value'	 => function ($data) {
					$cbrcnt	 = $data['CBRcnt'] != null ? $data['CBRcnt'] : 0;
					$socnt	 = $data['SOcnt'] != null ? $data['SOcnt'] : 0;
					echo ($cbrcnt + $socnt);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Total Closed Today'),
			array('name'	 => 'onlineTime', 'value'	 => function ($data) {
					$date1	 = $data['date1'];
					$date2	 = $data['date2'];
					$mintues = AdminOnoff::getTotalOnlineBycsrId($data['csrId'], $date1, $date2);
					$countHr = Filter::getTimeDurationbyMinute($mintues);
					echo $countHr != null ? $countHr : 0;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Total Online Time(Hour)'),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{log}',
				'buttons'			 => array(
					'log'			 => array(
						'click'		 => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {
                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Admin On/Off Log\',
                                                    size: \'medium\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
						'url'		 => 'Yii::app()->createUrl("aaohome/admin/adminLogTime", array("csrId" => $data[csrId],"fromDate"=>$data[date1],"toDate"=>$data[date2],"cbrcomcls"=>"cbrcomcls"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
						'label'		 => '<i class="fa fa-list"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'style'			 => '',
							'class'			 => 'btn btn-xs conshowlog p0',
							'title'			 => 'Admin Log'),
					),
					'htmlOptions'	 => array('class' => 'center'),
				)
			)
		)
	));
}
?>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
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
        $('#BookingSub_bkg_from_date').val(start1.format('YYYY-MM-DD'));
        $('#BookingSub_bkg_to_date').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#BookingSub_bkg_from_date').val('');
        $('#BookingSub_bkg_to_date').val('');
    });
</script>


