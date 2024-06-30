<?php
$version			 = Yii::app()->params['customVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
$time				 = Filter::getExecutionTime();
$GLOBALS['time'][9]	 = $time;
?>
<div class="row" id="louList">
    <div class="col-xs-12 col-sm-12 col-md-12">
		<?php
		if (Yii::app()->user->hasFlash('success'))
		{
			?>
			<div class="alert alert-block alert-success">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php } ?>

		<?php
		$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'lou-form',
			'enableClientValidation' => true,
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
        <div class="well pb20">
            <div class="col-xs-12 col-sm-6 col-md-4"> 
				<?= $form->textFieldGroup($model, 'search', array('label' => 'Search:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Vendor Code,Vendor Name,Email,Phone')))) ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-group">
					<?
					$daterang			 = "Select Lou Approved Date Range";
					$createdate1		 = ($model->vvhc_lou_approve_date1 == '') ? '' : $model->vvhc_lou_approve_date1;
					$createdate2		 = ($model->vvhc_lou_approve_date2 == '') ? '' : $model->vvhc_lou_approve_date2;
					if ($createdate1 != '' && $createdate2 != '')
					{
						$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
					}
					?>
                    <label  class="control-label">Lou Approved Date :</label>
                    <div id="vvhcLouApproveDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span style="min-width: 300px;"><?= $daterang ?></span> <b class="caret"></b>
                    </div>
					<?
					echo $form->hiddenField($model, 'vvhc_lou_approve_date1');
					echo $form->hiddenField($model, 'vvhc_lou_approve_date2');
					?>
                </div>
            </div>
			<div class="col-xs-12 col-sm-6  col-md-2">
                <div class="form-group">
                    <label class="control-label">Lou Status Type</label>
					<?php
					$statusTypesArr = $model->lou_status_types;
					if ($model->vvhc_lou_approved == "")
					{
						$val = 3;
					}
					else
					{
						$val = $model->vvhc_lou_approved;
					}
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'louStatusType',
						'val'			 => $val,
						'data'			 => $statusTypesArr,
						'htmlOptions'	 => array('style'			 => 'width:100%',
							'multiple'		 => 'multiple',
							'placeholder'	 => 'Lou Status Type')
					));
					?>
                </div>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-2"> 
				<?= $form->textFieldGroup($model, 'searchVehicleNumber', array('label' => 'Vehicle Number:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Vehicle Number')))) ?>
            </div>
        </div>
		<div class="col-xs-4 col-md-2 mb15 text-center">
			<button class="btn btn-primary" type="submit" style="width: 150px;" name="Search">Search</button>		
        </div>
		<?php $this->endWidget(); ?>
    </div>

    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'loulist',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'vnd_code', 'value'	 => function($data) {
							echo $data['vnd_code'];
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Vendor Code'),
					array('name'	 => 'vnd_name', 'value'	 => function($data) {
							echo $data['vnd_name'];
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Vendor Name'),
					array('name'	 => 'email', 'value'	 => function($data) {
							echo $data['eml_email_address'];
						}, 'sortable'								 => false, 'headerHtmlOptions'						 => array(), 'header'								 => 'Vendor Email'),
					array('name' => 'mobile', 'value' => '$data[phn_phone_no]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Phone No.'),
					array('name' => 'vehicle_model', 'value' => '$data[vehicle_name]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Name'),
					array('name' => 'vhc_number', 'value' => '$data[vhc_number]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Number'),
					array('name' => 'owner', 'value' => '$data[vhc_owner]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Vehicle Owner'),
					array('name' => 'lou_status', 'value' => '$data[lou_status]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'LOU Status'),
					array('name'	 => 'vvhc_lou_created_date',
						'value'	 => function ($data) {
							echo DateTimeFormat::DateTimeToLocale($data["vvhc_lou_created_date"]);
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Created Date'),
					array('name'	 => 'vvhc_lou_approve_date',
						'value'	 => function($data) {
							echo ($data['vvhc_lou_approved'] != 3) ? (DateTimeFormat::DateTimeToLocale($data["vvhc_lou_approve_date"])) : ("N/A");
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Approved Date'),
					array('name'	 => 'vvhc_lou_approve_name',
						'value'	 =>
						function($data) {
							echo ($data['vvhc_lou_approved'] != 3) ? ($data['vvhc_lou_approve_name']) : ("N/A");
						},
						'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Approved By'),
					array('name'	 => 'vvhc_lou_expire_date',
						'value'	 =>
						function($data) {
							echo ($data['vvhc_lou_approved'] != 3) ? (DateTimeFormat::DateTimeToLocale($data["vvhc_lou_expire_date"])) : ("N/A");
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Expire Date'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{loudetails}',
						'buttons'			 => array(
							'loudetails' => array(
								'visible'	 => '$data[vvhc_id]==NULL? false : true',
								'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
							                                                        message: data,
                                                            title: \'LOU Details\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/vendor/viewloudetails", array(\'vvhc_id\' => $data[vvhc_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Lou List'),
							),
						))
			)));
		}
		else
		{
			echo '<div class="col-xs-12"><div class="table-responsive panel panel-primary compact" id="loulist"><div class="panel-heading"><div class="row m0"><div class="col-xs-12 col-sm-6 pt5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="panel-body"><table class="table table-striped table-bordered mb0 table"><thead><tr><th id="loulist_c0">Vendor Code</th><th id="loulist_c1">Vendor Name</th><th id="loulist_c2">Email</th><th id="loulist_c3">Mobile</th><th id="loulist_c4">Lou Approved By</th><th class="col-xs-1 text-center" style="min-width: 100px;" id="loulist_c5">Action</th></tr></thead><tbody><tr><td colspan="6" class="empty"><span class="empty">No results found.</span></td></tr></tbody></table></div><div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/aaohome/vendor/loulist"></div></div></div>';
		}
		?>
    </div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#vvhcLouApproveDate').on('cancel.daterangepicker', function (ev, picker) {

        $('#vvhcLouApproveDate span').html('Select Lou Approved Date Range');
        $('#VendorVehicle_vvhc_lou_approve_date1').val('');
        $('#VendorVehicle_vvhc_lou_approve_date2').val('');
    });

    $('#vvhcLouApproveDate').daterangepicker(
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
        $('#VendorVehicle_vvhc_lou_approve_date1').val(start1.format('YYYY-MM-DD'));
        $('#VendorVehicle_vvhc_lou_approve_date2').val(end1.format('YYYY-MM-DD'));
        $('#vvhcLouApproveDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#vvhcLouApproveDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#vvhcLouApproveDate span').html('Select Lou Approved Date Range');
        $('#VendorVehicle_vvhc_lou_approve_date1').val('');
        $('#VendorVehicle_vvhc_lou_approve_date2').val('');
    });

    function refreshUsersGrid() {
        $('#loulist').yiiGridView('update');
    }
</script>
