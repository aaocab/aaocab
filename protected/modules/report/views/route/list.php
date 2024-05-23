<style type="text/css">
    .yii-selectize ,.selectize-input  {
        min-width: 100px!important;
    }
</style>

<?
$version			 = Yii::app()->params['siteJSVersion'];
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<?php
$datazone			 = Zones::model()->getZoneArrByFromBooking();
?>
<div class="panel-advancedoptions" >
    <div class="row">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'bookingpricefactorsearch-form', 'enableClientValidation' => true,
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



        <div class="col-xs-4 col-sm-4 col-md-3" style="">
            <div class="form-group">
                <label class="control-label">Pickup Date</label>


				<?php
				$daterang			 = "Select Pickup Date Range";
				$bpf_pickup_date1	 = $_REQUEST['BookingPriceFactor']['bpf_pickup_date1'];
				$bpf_pickup_date2	 = $_REQUEST['BookingPriceFactor']['bpf_pickup_date2'];
				if ($bpf_pickup_date1 != '' && $bpf_pickup_date2 != '')
				{
					$daterang = DateTimeFormat::DatePickerToDate($bpf_pickup_date1) . " - " . DateTimeFormat::DatePickerToDate($bpf_pickup_date2);
				}
				if ($bpf_pickup_date1 == '' && $bpf_pickup_date2 == '')
				{
					$date1		 = date('Y-m-d');
					$date2		 = date('Y-m-d', strtotime("+1 days"));
					$daterang	 = $date1 . "-" . $date2;
				}
				?>


                <input class="form-control" placeholder="Bpf Pickup Date1" name="BookingPriceFactor[bpf_pickup_date1]" 
                       id="BookingPriceFactor_bpf_pickup_date1" type="hidden" value="<?= $bpf_pickup_date1; ?>">
                <input class="form-control" placeholder="Bpf Pickup Date2" name="BookingPriceFactor[bpf_pickup_date2]" id="BookingPriceFactor_bpf_pickup_date2" type="hidden" value="<?= $bpf_pickup_date2; ?>">
				<div id="bpfPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                </div>


            </div>

        </div>
		<div class="col-xs-4 col-sm-4 col-md-3" style="">
            <div class="form-group">
				<label>Source Zone</label>
				<?php
				$nsource		 = explode(",", $bkgmodel->sourcezone);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $bkgmodel,
					'attribute'		 => 'sourcezone',
					//'val' => $model->sourcezone,
					'val'			 => $nsource,
					'data'			 => $datazone,
					//'asDropDownList' => FALSE,
					//'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Source Zone')
				));
				?>
            </div>

        </div>
        <div class="col-xs-4 col-sm-4 col-md-3" style="">
            <div class="form-group">
				<label class="control-label">Destination Zone</label>
				<?php
				$ndestination	 = explode(",", $bkgmodel->destinationzone);
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $bkgmodel,
					'attribute'		 => 'destinationzone',
					//'val' => $model->destinationzone,
					'val'			 => $ndestination,
					'data'			 => $datazone,
					//  'asDropDownList' => FALSE,
					// 'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
					'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						'placeholder'	 => 'Destination Zone')
				));
				?>
            </div>

        </div>



		<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?></div>
		<?php
		$this->endWidget();

		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>
			<div class="row">
				<?= CHtml::beginForm(Yii::app()->createUrl('report/route/list'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
				<input type="hidden" id="export" name="export" value="true"/>
				<input type="hidden" id="bpf_pickup_date1" name="bpf_pickup_date1" value="<?= $_REQUEST['BookingPriceFactor']['bpf_pickup_date1']; ?>"/>
				<input type="hidden" id="bpf_pickup_date2" name="bpf_pickup_date2" value="<?= $_REQUEST['BookingPriceFactor']['bpf_pickup_date2']; ?>"/>
				<input type="hidden" id="sourcezone" name="sourcezone" value="<?= $bkgmodel->sourcezone ?>"/>
				<input type="hidden" id="destinationzone" name="destinationzone" value="<?= $bkgmodel->destinationzone ?>"/>
				<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
				<?= CHtml::endForm() ?>
			</div>
			<?php }
		?>



	</div>
	<div class = "row">
		<div class = "col-md-12">
			<div class = "panel" >
				<div class = "panel-body panel-no-padding p0 pt10">
					<div class = "panel-scroll1">
						<div style = "width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$arr = [];
								if (is_array($dataProvider->getPagination()->params))
								{
									$arr = $dataProvider->getPagination()->params;
								}
								$params1							 = $arr + array_filter($_GET + $_POST);
								/* @var $dataProvider CActiveDataProvider */
								$dataProvider->pagination->pageSize	 = 50;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'bookingpricefactorlist',
									'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/bookingpricefactor/list', $params1)),
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'filter'			 => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'pickupDate', 'filter' => false, 'value' => 'date("d/m/Y",strtotime($data[pickupDate]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'DATE'),
										array('name' => 'from_zone', 'filter' => false, 'value' => '$data[from_zone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'From Zone'),
										array('name' => 'to_zone', 'filter' => false, 'value' => '$data[to_zone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'To Zone'),
										array('name' => 'fromCity', 'filter' => false, 'value' => '$data[fromCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'FROM'),
										array('name' => 'toCity', 'filter' => false, 'value' => '$data[toCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'TO'),
										array('name' => 'totalBooking', 'filter' => false, 'value' => '$data[totalBooking]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total Booking'),
										array('name' => 'regular', 'filter' => false, 'value' => '$data[regular]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of regular'),
										array('name' => 'manual', 'filter' => false, 'value' => '$data[manual]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of manual'),
										array('name' => 'manualddbp', 'filter' => false, 'value' => '$data[manualddbp]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of manual+ddbp'),
										array('name' => 'dtbp', 'filter' => false, 'value' => '$data[dtbp]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of dtbp'),
										array('name' => 'countOfroute_route', 'filter' => false, 'value' => '$data[countOfroute_route]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of ddbp route-route'),
										array('name' => 'countOfzone_zone', 'filter' => false, 'value' => '$data[countOfzone_zone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of DDBP zone-zone'),
										array('name' => 'countOfzone_state', 'filter' => false, 'value' => '$data[countOfzone_state]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'count of DDBP zone-state'),
								)));
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {


        //--- changed 1311 --///
        var start = '<?= date('d/m/Y'); ?>';

        var end = '<?= date('d/m/Y'); ?>';


        $('#bpfPickupDate').daterangepicker(
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
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
                        'All upcoming': [moment(), moment().add(11, 'month')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#BookingPriceFactor_bpf_pickup_date1').val(start1.format('DD/MM/YYYY'));
            $('#BookingPriceFactor_bpf_pickup_date2').val(end1.format('DD/MM/YYYY'));
            $('#bpfPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bpfPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bpfPickupDate span').html('Select Pickup Date Range');
            $('#BookingPriceFactor_bpf_pickup_date1').val('');
            $('#BookingPriceFactor_bpf_pickup_date2').val('');
        });

    });

</script>