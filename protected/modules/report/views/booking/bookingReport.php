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
						$daterang	 = "Select Pickup Date Range";
						$createdate1 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
						$createdate2 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
                        <label  class="control-label">Pickup Date</label>
                        <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                        </div>
						<?php
						echo $form->hiddenField($model, 'bkg_create_date1');
						echo $form->hiddenField($model, 'bkg_create_date2');
						?>
                    </div>

                </div>
                <div class="row">
                    <div class="  col-xs-12 text-center">
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
                            <button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
                        </div>
                    </div>
                </div>


<?php $this->endWidget(); ?>
            </div>
        </div>
        <div class="row">
            <div style="border-color: 1px #000000 solid; margin-bottom: 30px; margin-top: 20px;">
				<?php
				$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
	<?= CHtml::beginForm(Yii::app()->createUrl('report/booking/bookingReport'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>

					<div class="row">
						<div class="col-xs-12">
							<div class="col-xs-12  ">
								<input type="hidden" id="export1" name="export1" value="true"/>
								<input type="hidden" id="export_bkg_create_date1" name="export_bkg_create_date1" value="<?= $model->bkg_create_date1 ?>">
								<input type="hidden" id="export_bkg_create_date2" name="export_bkg_create_date2" value="<?= $model->bkg_create_date2 ?>">

								<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

							</div>
						</div>
					</div>
	<?= CHtml::endForm() ?>
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
								array('name'				 => 'bkgId',
									'value'				 => $data['bkgId']
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 '), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Id'),
								array('name'				 => 'bookigId',
									'value'				 => $data['bookigId']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Booking Id'),
								array('name'	 => 'pickupDate',
									'value'	 => function ($data) {
										echo date("d/M/Y", strtotime($data[pickupDate])) . "<br>" . date("h:i A", strtotime($data[pickupDate]));
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Pickup Date'),
								array('name'				 => 'baseAmount',
									'value'				 => $data['baseAmount']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Base Amount'),
								array('name'				 => 'netAdvanceAmount',
									'value'				 => $data['netAdvanceAmount']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Net Advance Amount'),
								array('name'				 => 'driverAllowance',
									'value'				 => $data['driverAllowance']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Driver Allowance'),
								array('name'				 => 'totalGmv',
									'value'				 => $data['totalGmv']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Total Gmv'),
								array('name'	 => 'serviceTax', 'value'	 => function ($data) {
										echo $data['serviceTax'] . " <br />" . $data['tollTax'] . " <br />" . $data['stateTax'];
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Service Tax/ TollTax/ StateTax'),
								array('name'				 => 'totalAmount',
									'value'				 => $data['totalAmount']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Total Amount'),
								array('name'				 => 'bkgStatus',
									'value'				 => $data['bkgStatus']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Status'),
								array('name'				 => 'companyName',
									'value'				 => $data['companyName']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Company Name'),
								array('name'				 => 'cancelReason',
									'value'				 => $data['cancelReason']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Cancel Reason'),
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
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#AccountTransDetails_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');
        });

    });

</script>
