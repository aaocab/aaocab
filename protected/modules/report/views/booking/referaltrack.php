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
						$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
						$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
                        <label  class="control-label">Create Date</label>
                        <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                        </div>
						<?php
						echo $form->hiddenField($model, 'bkg_create_date1');
						echo $form->hiddenField($model, 'bkg_create_date2');
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
		<div style="border-color: 1px #000000 solid; margin-bottom: 30px; margin-top: 20px;">
			<?php
			$checkExportAccess = false;
			if ($roles['rpt_export_roles'] != null)
			{
				$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
			}
			if ($checkExportAccess)
			{
				?>
				<?= CHtml::beginForm(Yii::app()->createUrl('report/booking/referraltrack'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
				<div class="row">
					<div class="col-xs-12">
						<div class="col-xs-12  ">
							<input type="hidden" id="export1" name="export" value="true"/>
							<input type="hidden" id="export_bkg_create_date1" name="export_bkg_create_date1" value="<?= $model->bkg_create_date1 ?>">
							<input type="hidden" id="export_bkg_create_date2" name="export_bkg_create_date2" value="<?= $model->bkg_create_date2 ?>">
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
								array('name'	 => 'brk_beneficiary_id', 'value'	 => function ($data) {
										echo CHtml::link($data['brk_beneficiary_id'], Yii::app()->createUrl("aaohome/user/view", ["id" => $data['brk_beneficiary_id']]), ["target" => "_blank"]);
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 '), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary Id'),
								array('name'	 => 'brk_benefactor_id',
									'value'	 => function ($data) {
										echo CHtml::link($data['brk_benefactor_id'], Yii::app()->createUrl("aaohome/user/view", ["id" => $data['brk_benefactor_id']]), ["target" => "_blank"]);
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Benefactor Id'),
								array('name'	 => 'brk_isfirst_beneficiary',
									'value'	 => function ($data) {
										echo $data['brk_isfirst_beneficiary'] == 1 ? " Yes " : "No";
									}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'							 => array('class' => 'text-right'), 'header'								 => 'Is First Beneficiary'),
								array('name'	 => 'brk_last_benefactor_bkgId',
									'value'	 => function ($data) {
										echo CHtml::link($data['brk_last_benefactor_bkgId'], Yii::app()->createUrl("admin/booking/view/", ["id" => $data['brk_last_benefactor_bkgId']]), ["target" => "_blank"]);
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Last Benefactor BkgId'),
								array('name'	 => 'brk_beneficiary_bkgId',
									'value'	 => function ($data) {
										echo CHtml::link($data['brk_beneficiary_bkgId'], Yii::app()->createUrl("admin/booking/view/", ["id" => $data['brk_beneficiary_bkgId']]), ["target" => "_blank"]);
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary BkgId'),
								array('name'	 => 'brk_beneficiary_bkg_complete_date',
									'value'	 => function ($data) {
										echo $data['brk_beneficiary_bkg_complete_date'] != null ? ( date("d/M/Y", strtotime($data['brk_beneficiary_bkg_complete_date'])) . "<br>" . date("h:i A", strtotime($data['brk_beneficiary_bkg_complete_date']))) : "NA";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary Complete Date'),
								array('name'				 => 'brk_beneficiary_payout_amt',
									'value'				 => $data['brk_beneficiary_payout_amt']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary Payout Amt'),
								array('name'	 => 'brk_beneficiary_payout_date', 'value'	 => function ($data) {
										echo $data['brk_beneficiary_payout_date'] != null ? ( date("d/M/Y", strtotime($data['brk_beneficiary_payout_date'])) . "<br>" . date("h:i A", strtotime($data['brk_beneficiary_payout_date']))) : "NA";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary Payout Date'),
								array('name'	 => 'brk_beneficiary_payout_status',
									'value'	 => function ($data) {
										echo $data['brk_beneficiary_payout_status'] == 1 ? " Yes " : "No";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary Payout Status'),
								array('name'				 => 'brk_benefactor_payout_amt',
									'value'				 => $data['brk_benefactor_payout_amt']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Benefactor Payout Amt'),
								array('name'	 => 'brk_benefactor_payout_date',
									'value'	 => function ($data) {
										echo $data['brk_benefactor_payout_date'] != null ? (date("d/M/Y", strtotime($data['brk_benefactor_payout_date'])) . "<br>" . date("h:i A", strtotime($data['brk_benefactor_payout_date']))) : "NA";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Benefactor Payout Date'),
								array('name'	 => 'brk_benefactor_payout_status', 'value'	 => function ($data) {
										echo $data['brk_benefactor_payout_status'] == 1 ? " Yes " : "No";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Benefactor Payout Status'),
								array('name'	 => 'brk_beneficiarybenefit_received',
									'value'	 => function ($data) {
										echo $data['brk_beneficiarybenefit_received'] == 1 ? " Yes " : "No";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Beneficiary Benefit Received'),
								array('name'	 => 'brk_benefactorbenefit_received',
									'value'	 => function ($data) {
										echo $data['brk_benefactorbenefit_received'] == 1 ? " Yes " : "No";
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Benefactor Benefit Received'),
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
        var start = '<?= ($model->bkg_create_date1 == '') ? date('d/m/Y', strtotime("-1 month", time())) : date('d/m/Y', strtotime($model->bkg_create_date1)); ?>';
        var end = '<?= ($model->bkg_create_date2 == '') ? date('d/m/Y') : date('d/m/Y', strtotime($model->bkg_create_date2)); ?>';
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
            $('#BookingReferralTrack_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#BookingReferralTrack_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Create Date Range');
            $('#BookingReferralTrack_bkg_create_date1').val('');
            $('#BookingReferralTrack_bkg_create_date2').val('');
        });
    })
</script>