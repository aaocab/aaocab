<?php
//echo $fromDate;
//echo "<br>";
//echo $toDate;
?>
<section id="section7">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 book-panel2">
                <div class="container p0 mt20">
                    <div class="col-xs-12">
                        <div class="profile-right-panel p20">
                            <h4 class="m0 weight400 mb20">Vendor Account</h4>
							<?php
							$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'vendor-account-form', 'enableClientValidation' => true,
								'action'				 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/listvendoraccount')),
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
                                <div class="col-xs-12 mb10">
                                    <div class="col-xs-12 col-sm-4 col-md-3">
                                        Vendor Accounts for period
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
										<?= $form->datePickerGroup($model, 'ven_from_date', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'value' => $fromDate, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date', 'value' => ($fromDate == '') ? '' : $fromDate)), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3"><?=
										$form->datePickerGroup($model, 'ven_to_date', array('label'			 => '',
											'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'value' => $toDate, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date', 'value' => ($toDate == '') ? '' : $toDate)), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
										?>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3">
										<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                                    </div>
                                </div>
                            </div>
							<?php echo $form->hiddenField($model, 'apg_trans_ref_id'); ?>
							<?php $this->endWidget(); ?>
                            <div class="row table table-bordered">
								<?php
								if (!empty($dataProvider))
								{
									$checkContactAccess						 = Yii::app()->user->checkAccess("bookingContactAccess");
									$params									 = array_filter($_REQUEST);
									$GLOBALS['venFromDate']					 = ($params['AccountPaymentGateway']['ven_from_date']);
									$GLOBALS['venToDate']					 = ($params['AccountPaymentGateway']['ven_to_date']);
									$dataProvider->getPagination()->params	 = $params;
									$dataProvider->getSort()->params		 = $params;
									$this->widget('booster.widgets.TbGridView', array(
										'responsiveTable'	 => true,
										'dataProvider'		 => $dataProvider,
										'id'				 => 'vendorAccountGrid',
										'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
										'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
										'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
										'columns'			 => array(
											array('name'	 => 'vnd_name', 'value'	 => function($data) {
													echo CHtml::link($data['vnd_name'], Yii::app()->createUrl('admin/vendor/vendoraccount/', ['vnd_id' => $data['vnd_id'], 'ven_from_date' => $data['venFromDate'], 'ven_to_date' => $data['venToDate']]));
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Name'),
											array('name'	 => 'vendorTotalTrips', 'value'	 => function($data) {
													echo $data['vendorTotalTrips'];
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => '# Trips'),
											array('name'	 => '', 'value'	 => function($data) {
													if ($data['pastDues'] != '')
													{
														echo '<i class="fa fa-inr"></i>' . $data['pastDues'];
													}
													else
													{
														echo '<i class="fa fa-inr"></i>0';
													}
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Past Dues'),
											array('name'	 => 'vendorAmount', 'value'	 => function($data) {
													if ($data['vendorAmount'] > 0)
													{
														echo '<i class="fa fa-inr"></i>' . $data['vendorAmount'];
													}
													else
													{
														echo '<i class="fa fa-inr"></i>0';
													}
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Vendor Amount'),
											array('name'	 => 'current_payable', 'value'	 => function($data) {
//                                                            if ($data['current_payable'] > 0) {
													echo '<i class="fa fa-inr"></i>' . $data['current_payable'];
//                                                            } else {
//                                                                echo '<i class="fa fa-inr"></i>0';
//                                                            }
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Current Payable'),
											array('name'	 => '', 'value'	 => function($data) {
													echo '<i class="fa fa-inr"></i>' . ($data['pastDues'] + $data['current_payable']);
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'NETT')
											
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
</section>