<div class="row" >
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'bookingReport', 'enableClientValidation' => true,
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
				?>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 form-group ">
						<?php
						$daterang	 = "Select Create Date Range";
						$createdate1 = ($model->create_date1 == '') ? '' : $model->create_date1;
						$createdate2 = ($model->create_date2 == '') ? '' : $model->create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
                        <label  class="control-label">Create Date</label>
                        <div id="DcoInterestedTracking" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                        </div>
						<?php
						echo $form->hiddenField($model, 'create_date1');
						echo $form->hiddenField($model, 'create_date2');
						?>
                    </div>
					<div class="  col-xs-12 col-sm-4 col-md-4 form-group text-center">
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
                            <button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
                        </div>
                    </div>
                </div>
				<?php $this->endWidget(); ?>
            </div>
        </div>
		<div style="border-color: #000000 solid; margin-bottom: 30px; margin-top: 20px;">
			<?php
			$checkExportAccess = false;
			if ($roles['rpt_export_roles'] != null)
			{
				$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
			}
			if ($checkExportAccess)
			{
				?>
				<?= CHtml::beginForm(Yii::app()->createUrl('report/notification/InterestedDCOTracking/'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-12  ">
							<input type="hidden" id="export" name="export" value="true"/>
							<input type="hidden" id="export_created_date1" name="export_create_date1" value="<?= $model->create_date1 ?>">
							<input type="hidden" id="export_created_date2" name="export_create_date2" value="<?= $model->create_date2 ?>">
							<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
						</div>
					</div>
				</div>
				<?= CHtml::endForm() ?>
			<?php } ?>
		</div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table table-bordered">
					<?php
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbExtendedGridView', array(
							'id'				 => 'vendorListGrid',
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table  table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
							'columns'			 => array(
								array('name'	 => 'date', 'value'	 => function ($data) {
										echo date("d/M/Y", strtotime($data['date']));
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 '), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Date'),
								array('name' => 'sentCount', 'value' => $data['sentCount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Sent'),
								array('name' => 'deliveredCount', 'value' => $data['deliveredCount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Delivered'),
								array('name' => 'readCount', 'value' => $data['readCount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Read'),
								array('name' => 'clickedCount', 'value' => $data['clickedCount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Link Opened'),
								array('name' => 'downloadCount', 'value' => $data['downloadCount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Download Count'),
								array('name' => 'loginCount', 'value' => $data['loginCount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Logged In'),
						)));
					}
					?> 
				</div>
            </div>  
        </div> 
    </div>  
</div>  
<script type="text/javascript">
    $(document).ready(function ()
    {
        var start = '<?= ($model->create_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->create_date1)); ?>';
        var end = '<?= ($model->create_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->create_date2)); ?>';
        $('#DcoInterestedTracking').daterangepicker(
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
            $('#DcoInterestedTracking_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#DcoInterestedTracking_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#DcoInterestedTracking span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#DcoInterestedTracking').on('cancel.daterangepicker', function (ev, picker) {
            $('#DcoInterestedTracking span').html('Select Create Date Range');
            $('#DcoInterestedTracking_create_date1').val('');
            $('#DcoInterestedTracking_create_date2').val('');
        });
    })
</script>












