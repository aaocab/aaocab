

<div class="row mb10">
    <div class="  col-xs-12">

		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'driverForm', 'enableClientValidation' => true,
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
        <div class="row mt10" >
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb10">
				<?=
				$form->textFieldGroup($model, 'obu_bkg_booking_id', array('label'			 => '',
					'htmlOptions'	 => [],
					'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Booking Id']]))
				?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb30" style="min-width: 215px">
				<?
				$daterang	 = "Upload Date Range";
				$createdate1 = ($model->obu_upload_from_date == '') ? '' : $model->obu_upload_from_date;
				$createdate2 = ($model->obu_upload_to_date == '') ? '' : $model->obu_upload_to_date;

				if ($createdate1 != '' && $createdate2 != '')
				{
					$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
				}
				?>
                <label  class="control-label hide">Upload Date Range</label>
                <div id="obuUploadDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?= $daterang ?></span> <b class="caret"></b>
                </div>
				<?
				echo $form->hiddenField($model, 'obu_upload_from_date');
				echo $form->hiddenField($model, 'obu_upload_to_date');
				?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb30" style="min-width: 215px">
				<?
				$daterang	 = "Update Date Range";
				$createdate1 = ($model->obu_updated_from_date == '') ? '' : $model->obu_updated_from_date;
				$createdate2 = ($model->obu_updated_to_date == '') ? '' : $model->obu_updated_to_date;

				if ($createdate1 != '' && $createdate2 != '')
				{
					$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
				}
				?>
                <label  class="control-label hide">Update Date Range</label>
                <div id="obuUpdateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span><?= $daterang ?></span> <b class="caret"></b>
                </div>
				<?
				echo $form->hiddenField($model, 'obu_updated_from_date');
				echo $form->hiddenField($model, 'obu_updated_to_date');
				?>
            </div>


            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="form-group">
					<?php
					$arrJSON1	 = array();
					$arr1		 = OlaBookingUpdate::model()->getStatusListArr();
					foreach ($arr1 as $key => $val)
					{
						$arrJSON1[] = array("id" => $key, "text" => $val);
					}
					$approvedriverlist = CJSON::encode($arrJSON1);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'obu_status',
						'val'			 => $model->obu_status,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($approvedriverlist), 'allowClear' => true),
						'htmlOptions'	 => array('style'			 => 'width:100%',
							'placeholder'	 => 'Status'),
					));
					?>
                </div> 
            </div>
            <div class="col-xs-12 col-md-4 col-lg-12 text-center">
                <button class="btn btn-info  " type="submit"  name="Search" style="width: 185px;">Search</button>
            </div>
        </div>
		<?php $this->endWidget(); ?>

    </div>
</div>


<?php
if (!empty($dataProvider))
{

	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'selectableRows'	 => 2,
		'id'				 => 'driverListGrid',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		//'ajaxType' => 'POST',
		'columns'			 => array(
			array('name' => 'obu_bkg_booking_id', 'value' => '$data["obu_bkg_booking_id"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Booking Id'),
			array('name' => 'obu_uploaded_on', 'value' => 'DateTimeFormat::DateTimeToLocale($data["obu_uplaoded_on"])', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Uploaded on'),
			array('name' => 'obu_updated_on', 'value' => 'DateTimeFormat::DateTimeToLocale($data["obu_updated_on"])', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Updated on'),
			array('name' => 'adm_name', 'value' => '$data["adm_name"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Uploaded by'),
			array('name'	 => 'obu_status', 'value'	 =>
				function ($data) {
					echo ($data["obu_active"] == 0) ? $data["obu_status"] . '. Not processed ' : $data["obu_status"];
				},
				'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Status'),
			array('name'	 => 'obu_old_data', 'value'	 =>
				function ($data) {
					if (trim($data["obu_old_data"]) != '')
					{
						$d2	 = [];
						$d1	 = [];
						$d1	 = explode('::', trim($data["obu_old_data"], '"'));
						echo "<div class='col-xs-6 pl0'>";
						foreach ($d1 as $dval)
						{
							$d2 = explode(',', $dval);
							foreach ($d2 as $key => $value)
							{
								echo $value;
								echo "<br>";
							}
							echo "</div>";
							echo "<div class='col-xs-6  pr0'>";
						}
						echo "</div>";
					}
					else
					{
						echo 'No data';
					}
				}
				, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Data changes'
			),
		)
	));
}
?>
<script type="text/javascript">
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#obuUploadDate').daterangepicker(
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

//                    'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
//                    'Next 7 Days': [moment(), moment().add(6, 'days')],
//                    'Next 30 Days': [moment(), moment().add(29, 'days')],
//                    'This Month': [moment().startOf('month'), moment().endOf('month')],
//                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],

                }
            }, function (start1, end1) {
        $('#OlaBookingUpdate_obu_upload_from_date').val(start1.format('YYYY-MM-DD'));
        $('#OlaBookingUpdate_obu_upload_to_date').val(end1.format('YYYY-MM-DD'));
        $('#obuUploadDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#obuUploadDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#obuUploadDate span').html('Select Upload Date Range');
        $('#OlaBookingUpdate_obu_upload_from_date').val('');
        $('#OlaBookingUpdate_obu_upload_to_date').val('');
    });
    $('#obuUpdateDate').daterangepicker(
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
        $('#OlaBookingUpdate_obu_updated_from_date').val(start1.format('YYYY-MM-DD'));
        $('#OlaBookingUpdate_obu_updated_to_date').val(end1.format('YYYY-MM-DD'));
        $('#obuUpdateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#obuUpdateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#obuUpdateDate span').html('Select Update Date Range');
        $('#OlaBookingUpdate_obu_updated_from_date').val('');
        $('#OlaBookingUpdate_obu_updated_to_date').val('');
    });
</script>