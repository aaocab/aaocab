<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
	.table-bordered>tbody>tr>td{
		border-color: #999 !important;
	}
	.bg-light-green{
		background-color: #e6ffe6;
		color: #444;

    }
	.bg-light-red{
		background-color: #ffe6e6;
		color: #444;
    }
</style>
<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row" >

    <div class="col-xs-12">

        <div class="row">
            <div class="col-xs-12 col-lg-8 col-lg-offset-2">

				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vendorCollectionList', 'enableClientValidation' => true,
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
                    <div class="col-xs-12 col-sm-3 col-md-5">
                        <div class="form-group mb0">
                            <label class="control-label">Vendor</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'vendor_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendor",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->vendor_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                        loadVendor(query, callback);
                        }",
							'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
								),
							));
							?>

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-7 form-group ">
						<?php
						$daterang			 = "Select Transaction Date Range";
						$createdate1		 = ($model->trans_create_date1 == '') ? '' : $model->trans_create_date1;
						$createdate2		 = ($model->trans_create_date2 == '') ? '' : $model->trans_create_date2;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
                        <label  class="control-label">Transaction Date</label>
                        <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                        </div>
						<?php
						echo $form->hiddenField($model, 'trans_create_date1');
						echo $form->hiddenField($model, 'trans_create_date2');
						?>
                    </div>
					<div class="col-xs-12 col-sm-3 col-md-5 form-group ">
						<?= $form->textFieldGroup($model, 'bkg_id', array('label' => 'Booking Id', 'htmlOptions' => array('placeholder' => 'Search By Booking Id'))) ?>
					</div>
					<div class="col-xs-12 col-sm-3 col-md-7 form-group ">
						<?php
						$daterang	 = "Select Removal Date Range";
						$removedate1 = ($model->trans_remove_date1 == '') ? '' : $model->trans_remove_date1;
						$removedate2 = ($model->trans_remove_date2 == '') ? '' : $model->trans_remove_date2;
						if ($removedate1 != '' && $removedate2 != '')
						{
							$daterang = date('F d, Y', strtotime($removedate1)) . " - " . date('F d, Y', strtotime($removedate2));
						}
						?>
                        <label  class="control-label">Removal Date</label>
                        <div id="bkgRemoveDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                        </div>
						<?php
						echo $form->hiddenField($model, 'trans_remove_date1');
						echo $form->hiddenField($model, 'trans_remove_date2');
						?>
                    </div>

                </div>
                <div class="row">
                    <div class="  col-xs-12 text-center">
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center" style="padding: 4px;">
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
				$checkExportAccess = Yii::app()->user->checkAccess("exportPenaltyReport");
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/financial/penaltyReport'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>

					<div class="row">
						<div class="col-xs-12">
							<div class="col-xs-12  ">
								<input type="hidden" id="export1" name="export1" value="true"/>
								<input type="hidden" id="export_trans_create_date1" name="export_trans_create_date1" value="<?= $model->trans_create_date1 ?>">
								<input type="hidden" id="export_trans_create_date2" name="export_trans_create_date2" value="<?= $model->trans_create_date2 ?>">
								<input type="hidden" id="export_trans_remove_date1" name="export_trans_remove_date1" value="<?= $model->trans_remove_date1 ?>">
								<input type="hidden" id="export_trans_remove_date2" name="export_trans_remove_date2" value="<?= $model->trans_remove_date2 ?>">
								<input type="hidden" id="export_bkg_id" name="export_bkg_id" value="<?= $model->bkg_id ?>">
								<input type="hidden" id="export_vendor_id" name="export_vendor_id" value="<?= $model->vendor_id; ?>">

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
							'id'					 => 'vendorListGrid',
							'responsiveTable'		 => true,
							'dataProvider'			 => $dataProvider,
							'template'				 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'			 => 'table  table-bordered dataTable mb0',
							'rowCssClassExpression'	 => '($data["act_active"]==1 ) ? "bg bg-light-green" : "bg bg-light-red"',
							'htmlOptions'			 => array('class' => 'panel panel-primary compact'),
							//       'ajaxType' => 'POST',
							'columns'				 => array(
								array('name'				 => 'act_created',
									'value'				 => $data['act_created']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Create Date'),
								array('name'				 => 'act_date',
									'value'				 => $data['act_date']
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Transaction Date'),
								array('name'	 => 'adt_addt_params',
									'value'	 => function ($data) {
										$ptype		 = CJSON::decode($data['adt_addt_params']);
										$penaltyType = $ptype['penaltyType'];
										$pModel		 = PenaltyRules::getValueByPenaltyType($penaltyType);
										echo $pModel['plt_desc'];
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Transaction Type'),
								array('name'	 => 'adt_amount',
									'value'	 => function ($data) {
										if ($data['act_active'] == 1)
										{
											echo $data['ledgerAmt'];
										}
										else
										{
											echo $data['ledgerAmt1'];
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Ledger entry<br>(+ Credit to Gozo or - Credit to vendor)'),
								array('name'	 => 'adt_trans_ref_id',
									'value'	 => function ($data) {
										echo $data['adt_trans_ref_id'];
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Booking ID'),
								array('name'	 => 'vnd_code',
									'value'	 => function ($data) {
										echo $data['vnd_code'];
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Vendor Code'),
								array('name'	 => 'adt_remarks',
									'value'	 => function ($data) {
										if ($data['act_active'] == 1)
										{
											echo $data['adt_remarks'];
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Comments'),
								array('name'	 => 'act_user_id',
									'value'	 => function ($data) {
										if ($data['act_active'] == 1)
										{
											if ($data['adm_id'] != '')
											{
												echo $data['adm_fname'] . '' . $data['adm_lname'];
											}
											else
											{
												echo "System";
											}
										}
										else
										{
											echo "";
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Applied by'),
								array('name'	 => 'adt_modified',
									'value'	 => function ($data) {
										if ($data['act_active'] == 0)
										{
											echo $data['adt_modified'];
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Penalty Removal date'),
								array('name'	 => 'adt_remarks',
									'value'	 => function ($data) {
										if ($data['act_active'] == 0)
										{
											if ($data['blg_desc'] != '')
											{
												echo $data['blg_desc'];
											}
											else
											{
												echo $data['adt_remarks'];
											}
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Removal Comments'),
								array('name'	 => 'act_user_id',
									'value'	 => function ($data) {
										if ($data['act_active'] == 0)
										{
											if ($data['adm_id'] != '')
											{
												echo $data['adm_fname'] . '' . $data['adm_lname'];
											}
											else
											{
												echo "System";
											}
										}
										else
										{
											echo "";
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Removed by'),
								array('name'	 => 'act_active',
									'value'	 => function ($data) {
										if ($data['act_active'] == 1)
										{
											echo "Applied";
										}
										else
										{
											echo "Removed";
										}
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Status'),
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
            $('#AccountTransDetails_trans_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#AccountTransDetails_trans_create_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Transaction Date Range');
            $('#AccountTransDetails_trans_create_date1').val('');
            $('#AccountTransDetails_trans_create_date2').val('');
        });

        $('#bkgRemoveDate').daterangepicker(
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
            $('#AccountTransDetails_trans_remove_date1').val(start1.format('YYYY-MM-DD'));
            $('#AccountTransDetails_trans_remove_date2').val(end1.format('YYYY-MM-DD'));
            $('#bkgRemoveDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgRemoveDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgRemoveDate span').html('Select Removal Date Range');
            $('#AccountTransDetails_trans_remove_date1').val('');
            $('#AccountTransDetails_trans_remove_date2').val('');
        });

    });

</script>
