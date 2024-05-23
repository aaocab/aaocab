<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .panel-body {
        border: 1px #eeeeee solid;
        padding: 15px!important;
    }
    .panel-heading {
        padding: 10px 15px!important;
    }
    .pagination {
        margin: 0;
    }
    .table{
        margin-bottom: 5px;
    }
</style>

<div id="content" class="  " style="width: 100%!important">
    <div class="row ">
        <div id="userView">
            <div class="col-xs-12 col-md-10 col-lg-8" style="float: none; margin: auto">
                <div class="projects">
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php
							$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'transaction-form', 'enableClientValidation' => true,
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

                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<?= $form->datePickerGroup($model, 'trans_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date', 'value' => $qry['trans_date1'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<?= $form->datePickerGroup($model, 'trans_date2', array('label' => 'To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date', 'value' => $qry['trans_date2'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>

                            <div class="col-xs-6 col-sm-2 col-md-2 mt20">   
								<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                            </div>
							<?php $this->endWidget(); ?>
							<div class="col-xs-1">
								<?php
								$checkExportAccess	 = false;
								if ($roles['rpt_export_roles'] != null)
								{
									$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
								}
								if ($checkExportAccess)
								{
									echo CHtml::beginForm(Yii::app()->createUrl('report/financial/AccountList'), "post", []);
									?>
									<input type="hidden" id="trans_date1" name="trans_date1" value="<?= $model->trans_date1 ?>"/>
									<input type="hidden" id="trans_date2" name="trans_date2" value="<?= $model->trans_date2 ?>"/>
									<input type="hidden" id="ledgerId" name="ledgerId" value="<?= $ledgerId ?>"/>
									<input type="hidden" id="export" name="export" value="true"/>
									<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
									<?php echo CHtml::endForm(); ?>	
								<?php } ?>
							</div>	

                        </div>
                    </div>
                </div>

                <div class="projects">
                    <div id="account_tab1">
						<?php
						if (!empty($dataProvider))
						{
							$this->widget('booster.widgets.TbGridView', array(
								'id'				 => 'transaction-grid',
								'responsiveTable'	 => true,
								'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/account/accountlist', $dataProvider->getPagination()->params)),
								'dataProvider'		 => $dataProvider,
								'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'panel panel-primary'),
								'columns'			 => array(
									array('name'	 => 'name',
										'value'	 => function($data) {
											if (in_array($data["ledgerId"], [40]))
											{
												
												echo CHtml::link($data["name"], Yii::app()->createUrl("admin/driver/viewtransaction", $_REQUEST + ["id" => $data["drv_id"]]));
												//echo $data["name"];
											}
										},
										'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:left'), 'header'			 => 'Driver Name'),
									
									array('name'	 => 'trans_openingbalance', 'filter' => false,
										'value'	 => function($data) {
											echo '' . $data['phone'] . '</nobr>';
										}
										, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Phone'),
								
									array('name'	 => 'trans_openingbalance', 'filter' => false,
										'value'	 => function($data) {
											echo '<nobr><i class="fa fa-inr"></i>' . $data['opening'] . '</nobr>';
										}
										, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Opening Balance'),
									
									
									array('name'	 => 'trans_openingbalance', 'filter' => false,
										'value'	 => function($data) {
											echo '<nobr><i class="fa fa-inr"></i>' . $data['debit'] . '</nobr>';
										}
										, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Debit'),
									array('name'	 => 'trans_debit', 'filter' => false, 'value'	 => function($data) {
											echo '<nobr><i class="fa fa-inr"></i>' . $data['credit'] . '</nobr>';
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Credit'),
									array('name'	 => 'trans_amount', 'filter' => false, 'value'	 => function($data) {
											echo '<nobr><i class="fa fa-inr"></i>' . $data['closing'] . '</nobr>';
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'text-align:center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Closing Balance'),
							)));
						}
						?>
                    </div>
                    <div id="account_tab2" style="display: block;" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script type="text/javascript">
    function viewAccountTab()
    {
        alert("ghfgh");
        var x = document.getElementById("account_tab1");
        var y = document.getElementById("account_tab2");
        x.style.display = "block";
        y.style.display = "none";
    }
</script>