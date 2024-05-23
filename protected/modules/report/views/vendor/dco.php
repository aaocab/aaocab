<div class="row" >
    <div class="col-xs-12">
		<div class="row">
			<?php
			/* @var $model Vendors */
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'vndDCO', 'enableClientValidation' => true,
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
					<label class="control-label">Vendor Register Date Range</label>
					<?php
					$daterang			 = "Select Vendor Register Date Range";
					$vnd_create_date1	 = ($model->vnd_create_date1 == '') ? "" : $model->vnd_create_date1;
					$vnd_create_date2	 = ($model->vnd_create_date2 == '') ? "" : $model->vnd_create_date2;
					if ($vnd_create_date1 != '' && $vnd_create_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($vnd_create_date1)) . " - " . date('F d, Y', strtotime($vnd_create_date2));
					}
					?>
					<div id="vndCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'vnd_create_date1'); ?>
					<?= $form->hiddenField($model, 'vnd_create_date2'); ?>

				</div>
			</div>




			<div class="col-xs-12 col-sm-4 col-md-4" style="">
				<div class="form-group">
					<label class="control-label">App Login Date Range</label>
					<?php
					$daterang			 = "Select Date Range";
					$bkg_create_date1	 = ($model->bkg_create_date1 == '') ? "" : $model->bkg_create_date1;
					$bkg_create_date2	 = ($model->bkg_create_date2 == '') ? "" : $model->bkg_create_date2;
					if ($bkg_create_date1 != '' && $bkg_create_date2 != '')
					{
						$daterang = date('F d, Y', strtotime($bkg_create_date1)) . " - " . date('F d, Y', strtotime($bkg_create_date2));
					}
					?>
					<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?= $form->hiddenField($model, 'bkg_create_date1'); ?>
					<?= $form->hiddenField($model, 'bkg_create_date2'); ?>

				</div>
			</div>

			<div class="col-xs-12 col-sm-3 col-md-2 pt15">
				<?php echo $form->checkboxGroup($model, 'vnd_registered_platform', ['label' => 'DCO APP(Registered)', 'widgetOptions' => ['htmlOptions' => ['value' => 1]]]);
				?>
			</div>
			<div class="  col-xs-6 col-sm-6 col-md-2 col-lg-1 mr15 mt20"  >
				<button class="btn btn-primary" type="submit" style="width: 125px;" >Search</button> 
			</div>
			<?php $this->endWidget(); ?>
			<div class="col-xs-12 col-sm-4 col-md-4 form-group ">
				<?php
				$checkExportAccess = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/dco'), "post", []);
					?>
					<input type="hidden" id="export" name="export" value="true"/>
					<input type="hidden" id="fromDate" name="fromDate" value="<?php echo $model->vnd_create_date1; ?>"/>
					<input type="hidden" id="toDate" name="toDate" value="<?php echo $model->vnd_create_date2; ?>"/>
					<input type="hidden" id="bkgFromDate" name="bkgFromDate" value="<?php echo $model->bkg_create_date1; ?>"/>
					<input type="hidden" id="bkgToDate" name="bkgToDate" value="<?php echo $model->bkg_create_date2; ?>"/>
					<input type="hidden" id="registered_platform" name="registered_platform" value="<?php echo $model->vnd_registered_platform; ?>"/>
					<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
					<?php echo CHtml::endForm(); ?>	
				<?php } ?>
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
						$this->widget('booster.widgets.TbGridView', array(
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
								array('name'	 => 'vnd_id', 'value'	 => function ($data) {
										echo CHtml::link($data["VendorName"], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "target" => "_blank"]);
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Operator Name'),
								array('name'	 => 'vnd_registered_platform', 'value'	 => function ($data) {
										echo $data['vnd_registered_platform'] == 1 ? " Yes" : " No";
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'DCO App(Registered)'),
								array('name'	 => 'apt_id', 'value'	 => function ($data) {
										echo $data['apt_id'] > 0 ? " Yes " : " NO ";
									}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-2'), 'header'								 => 'DCO App(Login)'),
								array('name' => 'VendorCreateDate', 'value' => 'date("Y-m-d", strtotime($data[VendorCreateDate]))', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor Create Date'),
								array('name' => 'VendorAge', 'value' => '$data[VendorAge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor Age (Days)'),
								array('name' => 'VendorStatus', 'value' => '$data[VendorStatus]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Vendor Status'),
								array('name' => 'CODFreeze', 'value' => '$data[CODFreeze]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'COD Freeze'),
								array('name' => 'CreditLimitFreeze', 'value' => '$data[CreditLimitFreeze]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Credit Limit Freeze'),
								array('name' => 'LowRatingFreeze', 'value' => '$data[LowRatingFreeze]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Low Rating Freeze'),
								array('name' => 'DOCPendingFreeze', 'value' => '$data[DOCPendingFreeze]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Document Pending Freeze'),
								array('name' => 'ManualFreeze', 'value' => '$data[ManualFreeze]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Manual Freeze'),
								array('name'	 => 'BiddingAccept', 'value'	 => function ($data) {
										echo $data['BiddingAccept'] >= 1 ? $data['BiddingAccept'] : 0;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Bidding Accept'),
								array('name'	 => 'TotalBid', 'value'	 => function ($data) {
										echo $data['TotalBid'] >= 1 ? $data['TotalBid'] : 0;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Total Bid'),
								array('name'	 => 'TotalServed', 'value'	 =>
									function ($data) {
										echo $data['TotalServed'] >= 1 ? $data['TotalServed'] : 0;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Total Served'),
								array('name'	 => 'DcoServedCnt', 'value'	 =>
									function ($data) {
										echo $data['DcoServedCnt'] >= 1 ? $data['DcoServedCnt'] : 0;
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Dco Served Count'),
						)));
					}
					?> 
                </div>
            </div>  
        </div> 
    </div>  
</div>  


<script>
    var start = '<?= ($model->vnd_create_date1 == '') ? date('d/m/Y', strtotime("-1 day", time())) : date('d/m/Y', strtotime($model->vnd_create_date1)); ?>';
    var end = '<?= ($model->vnd_create_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->vnd_create_date2)); ?>';
    $('#vndCreateDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'},
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
        $('#Vendors_vnd_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Vendors_vnd_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#vndCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#vndCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#vndCreateDate span').html('Select Create Date Range');
        $('#Vendors_vnd_create_date1').val('');
        $('#Vendors_vnd_create_date2').val('');
    });

    var bkgStart = '<?= ($model->bkg_create_date1 == '') ? date('d/m/Y', strtotime("-1 day", time())) : date('d/m/Y', strtotime($model->bkg_create_date1)); ?>';
    var bkgEnd = '<?= ($model->bkg_create_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_create_date2)); ?>';
    $('#bkgCreateDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'},
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: bkgStart,
                endDate: bkgEnd,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Vendors_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Vendors_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgCreateDate span').html('Select Login Date Range');
        $('#Vendors_bkg_create_date1').val('');
        $('#Vendors_bkg_create_date2').val('');
    });
</script>