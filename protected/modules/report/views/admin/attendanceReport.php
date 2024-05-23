<div class="row" >
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">

				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'attendanceReport', 'enableClientValidation' => true,
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


                <div class="row">

                    <div class="col-xs-12 col-sm-4 col-md-4 form-group ">
						<?php
						$daterang	 = "Select Attedance Date Range";
						$createdate1 = ($model->ats_create_date1 == '') ? '' : $model->ats_create_date1;
						$createdate2 = ($model->ats_create_date2 == '') ? '' : $model->ats_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
                        <label  class="control-label">Attendance Date</label>
                        <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                        </div>
						<?php
						echo $form->hiddenField($model, 'ats_create_date1');
						echo $form->hiddenField($model, 'ats_create_date2');
						?>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <label class="control-label">Filter By Gozen</label>
						<?php
						$csrSearch			 = Admins::model()->employeesList(1);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'csrSearch',
							'val'			 => $model->csrSearch,
							'data'			 => $csrSearch,
							'htmlOptions'	 => array('style' => 'width:100%', 'multiple' => 'multiple', 'placeholder' => 'Filter By Gozen')
						));
						?> 
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Teams</label>
							<?php
							$fetchList			 = Teams::getList();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'teamList',
								'val'			 => $model->teamList,
								'data'			 => [-1 => 'All'] + $fetchList,
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Teams')
							));
							?>
                        </div> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 text-center">
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
                            <button class="btn btn-primary full-width" type="submit"  name="AttedanceSearch">Search</button>
                        </div>
						<?php $this->endWidget(); ?>
						<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20">

							<?php
							$checkExportAccess	 = false;
							if ($roles['rpt_export_roles'] != null)
							{
								$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
							}
							if ($checkExportAccess)
							{
								?>
								<?php
								echo CHtml::beginForm(Yii::app()->createUrl('report/admin/AttendanceReport'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
								?>
								<input type="hidden" id="exportcreatedate1" name="exportcreatedate1" value="<?= $model->ats_create_date1 ?>"/>
								<input type="hidden" id="exportcreatedate2" name="exportcreatedate2" value="<?= $model->ats_create_date2 ?>"/>
								<input type="hidden" id="csrSearch" name="csrSearch" value="<?= implode(',', $model->csrSearch) ?>"/>
								<input type="hidden" id="teamList" name="teamList" value="<?= $model->teamList ?>"/>
								<input type="hidden" id="export" name="export" value="true"/>
								<button class="btn btn-default" type="submit" style="width: 185px;">Export</button>

								<?php echo CHtml::endForm(); ?>
							<?php } ?>
						</div>
                    </div>

                </div>




            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="  table table-bordered">
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
								array('name'	 => 'gozen', 'value'	 => function ($data) {
										if ($data['teamsId'] != null)
										{
											$teamsName = $data['teamsList'];
										}
										else
										{
											$teamList	 = Teams::getMultipleTeamid($data['adm_id']);
											$teamsName	 = "";
											foreach ($teamList as $team)
											{
												$teamsName .= $team['tea_name'] . ",";
											}
											$teamsName			 = rtrim($teamsName, ",");
										}
										echo $data['gozen'] . "<br>(<b>" . $teamsName . "</b>)";
									},
									'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Gozen'),
								array('name'	 => 'fromDate',
									'value'	 => function ($data) {
										echo date("d/M/Y", strtotime($data['fromDate'])) . "<br>" . date("h:i A", strtotime($data['fromDate']));
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'From Date'),
								array('name'	 => 'toDate',
									'value'	 => function ($data) {
										echo date("d/M/Y", strtotime($data['toDate'])) . "<br>" . date("h:i A", strtotime($data['toDate']));
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'To Date'),
								array('name'	 => 'totalHrs',
									'value'	 => function ($data) {
										echo round($data['totalHrs'], 2);
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Total Hours'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{view}',
									'buttons'			 => array(
										'view'			 => array(
											'click'		 => 'function(){
                                                                $href = $(this).attr(\'href\');
                                                                jQuery.ajax({type: \'GET\',
                                                                url: $href,
                                                                success: function (data)
                                                                {
                                                                    var box = bootbox.dialog({
                                                                        message: data,
                                                                        title: \' Attendance Details Report: \',
                                                                        size: \'large\',
                                                                        onEscape: function () {

                                                                            // user pressed escape
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                            return false;
                                                         }',
											'url'		 => 'Yii::app()->createUrl("report/admin/AttendanceDetailsReport", array("id" => $data[adm_id],"fromDate" => date("Y-m-d", strtotime($data[fromDate])),"toDate" => date("Y-m-d", strtotime($data[toDate]))))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\show_log.png',
											'label'		 => '<i class="fas fa-eye"></i>',
											'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJobView p0', 'title' => 'View Attendance Details'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
						)));
					}
					?> 
                </div>
            </div>  
        </div> 
    </div>  
</div>  

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    function refreshVendorGrid()
    {
        $('#vendorListGrid').yiiGridView('update');
    }

</script>

<script type="text/javascript">

    $(document).ready(function () {
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
            $('#AttendanceStats_ats_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#AttendanceStats_ats_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#AttendanceStats_ats_create_date1').val('');
            $('#AttendanceStats_ats_create_date2').val('');
        });

    });

</script>
