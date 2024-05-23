<section id="section7">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 book-panel2">
                <div class="container p0 mt20">
                    <div class="col-xs-12">
                        <div class="profile-right-panel p20">
                            <h4 class="m0 weight400 mb20">Today's Account Action List</h4>
                            <div class="row">
                                <div class="col-xs-12 col-sm-5 table-responsive">
                                    <h5>Booking flagged for accounts review:</h5>

                                    <div id="bookFlagTable">
                                        <table class="table table-bordered">
                                            <tr class="blue2 white-color">
                                                <td align="center"><b>Booking ID</b></td>
                                                <td align="center"><b>Booking ID</b></td>
                                                <td align="center"><b>Booking ID</b></td>
                                            </tr>
											<?php
											echo "<tr>";
											$ctr = 0;
											if (count($bookModels) > 0)
											{
												foreach ($bookModels as $book)
												{
													if ($ctr % 3 == 0)
													{
														echo "</tr><tr>";
													}
													?>   
													<td align="center"><b><?php echo CHtml::link(trim($book['bkg_booking_id']), Yii::app()->createUrl("admin/booking/view", ["id" => trim($book['bkg_id'])]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]); ?></b></td>
													<?php
													$ctr = ($ctr + 1);
												}
											}
											?>
                                            <tr>
                                                <td colspan="3" class="bookPages"><?php
													// the pagination widget with some options to mess
													$this->widget('booster.widgets.TbPager', array('pages' => $bookingList->pagination));
													?>
                                                </td>
                                            </tr>        
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-7 table-responsive">
                                    <h5>Vendor Collections status:</h5>
                                    <div class="row table table-bordered">
										<?php
										if (!empty($dataProvider))
										{
											$params									 = array_filter($_REQUEST);
											$dataProvider->getPagination()->params	 = $params;
											$dataProvider->getSort()->params		 = $params;
											$this->widget('booster.widgets.TbGridView', array(
												'responsiveTable'	 => true,
												'dataProvider'		 => $dataProvider,
												'id'				 => 'vendorCollectionGrid',
												'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
												'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
												'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
												'columns'			 => array(
													array('name'	 => 'vendor_name', 'value'	 => function($data) {
															echo CHtml::link($data['vendor_name'], Yii::app()->createUrl('admin/vendor/vendoraccount/', ['vnd_id' => $data['vnd_id'], 'ven_from_date' => $GLOBALS['venFromDate'], 'ven_to_date' => $GLOBALS['venToDate']]));
														}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-5'), 'header'			 => 'Vendor Name'),
													array('name'	 => 'vendor_amount', 'value'	 => function($data) {
															if ($data['vendor_amount'] > 0)
															{
																echo '<i class="fa fa-inr"></i>' . round($data['vendor_amount']);
															}
														}, 'sortable'								 => true, 'htmlOptions'							 => array('class' => 'text-right'), 'headerHtmlOptions'						 => array('class' => 'col-xs-2 text-center'), 'header'								 => 'Amount Due'),
													array('name'	 => 'vendor_amount', 'value'	 => function($data) {
															if ($data['vendor_amount'] < 0)
															{
																echo '<i class="fa fa-inr"></i>' . round($data['vendor_amount']);
															}
														}
														, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-right'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Amount Payable'),
													array('name' => '', 'value' => '', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Days'),
													array('name'	 => 'countFlag', 'value'	 => function($data) {
															if ($data['countFlag'] > 0)
															{
																echo $data['countFlag'];
															}
															else
															{
																echo '0';
															}
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'header'			 => 'Problem Booking'),
											)));
										}
										?>  
									</div>
								</div>
							</div>
							<div class="row">
								<?php
								$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'report-find-booking-form',
									'action'				 => '' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/report/bookingaccount')) . '',
									'enableClientValidation' => true,
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
								<div class="col-xs-5"><div class="row">
										<div class="col-xs-12">
											<div class="form-group">
												<div class="controls">
													<?= $form->textAreaGroup($model, 'search', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter booking ID, Customer name, Customer email, Customer phone', 'style' => 'min-height:75px')))) ?>
												</div>
											</div>
											<div class="form-group">
												<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-info', 'value' => 'Find Bookings')); ?>
											</div>
										</div></div></div>
								<?php $this->endWidget(); ?>

								<?php
								$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'report-vendor-form',
									'enableClientValidation' => true,
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
								<div class="col-xs-12 col-sm-6">
									<div class="row">
										<div class="form-group">
											<label for="inputEmail3" class="col-sm-4 control-label">Select Vendor</label>
											<div class="col-sm-8 mb20">
												<?php
												$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
													'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
													'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
													'openOnFocus'		 => true, 'preload'			 => false,
													'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
													'addPrecedence'		 => false,];
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $venModel,
													'attribute'			 => 'apg_trans_ref_id',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Select Vendor",
													'fullWidth'			 => false,
													'htmlOptions'		 => array('width' => '100%'),
													'defaultOptions'	 => $selectizeOptions + array(
												'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$venModel->apg_trans_ref_id}');
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
                                            }",
													),
												));
												?>
												<?= $form->error($venModel, 'apg_trans_ref_id') ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group">
											<label for="inputEmail3" class="col-sm-4 control-label">Generate Invoices</label>
											<div class="col-sm-8">
												<?=
												$form->radioButtonListGroup($venModel, 'ven_date_type', array(
													'label'			 => '', 'widgetOptions'	 => array(
														'data'		 => array('1' => 'This Week', '2' => 'Date Range'), 'onclick'	 => 'chnageDate();'
													), 'inline'		 => true,)
												);
												?>
												<div class="row"><div class="col-xs-6">
														<?= $form->datePickerGroup($venModel, 'ven_from_date', array('label' => '', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
													</div><div class="col-xs-6">
														<?=
														$form->datePickerGroup($venModel, 'ven_to_date', array('label'			 => '',
															'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
														?></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 pl0">
										<div class="text-left mt20">
											<button type='submit' class='btn btn-primary' name="submit" value='1'>Generate PDF</button>
											<button type='submit' class='btn btn-info'  name="submit" value='2'>View Vendor Accounts</button>
										</div>
									</div>
								</div>
								<?php $this->endWidget(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>

    $(".bookPages a").click(function (e) {
        bindPage(e, this);
    });
    function bindPage(e, obj) {
        var href = $(obj).attr("href");
        $.ajax({
            url: href,
            dataType: "html",
            "success": function (data) {
                var html = $(data).find('#bookFlagTable').html();
                $('#bookFlagTable').html(html);
                $(".bookPages a").unbind('click').click(function (e) {
                    bindPage(e, this);
                });
            }
        });
        e.preventDefault();

    }


    $(".vendorPages a").click(function (e) {
        vendorBindPage(e, this);
    });
    function vendorBindPage(e, obj) {
        var href = $(obj).attr("href");
        $.ajax({
            url: href,
            dataType: "html",
            "success": function (data) {
                var html = $(data).find('#vendorAccountsTable').html();
                $('#vendorAccountsTable').html(html);
                $(".vendorPages a").unbind('click').click(function (e) {
                    vendorBindPage(e, this);
                });
            }
        });
        e.preventDefault();

    }

    $(".btn-primary").click(function () {
        var vendorId = ($("#PaymentGateway_apg_trans_ref_id").val());
        if (vendorId == '0' || vendorId == '') {
            bootbox.alert("Please select a vendor for generate PDF.");
            return false;
        } else {
            return true;
        }
    });

    $("#PaymentGateway_ven_date_type_0").click(function () {
        var dateVal = $("#PaymentGateway_ven_date_type_0").val();
        $("#PaymentGateway_ven_from_date").val('<?= DateTimeFormat::DateToLocale($dateFromDate) ?>');
        $("#PaymentGateway_ven_to_date").val('<?= DateTimeFormat::DateToLocale($dateTodate) ?>');
    });

    $("#PaymentGateway_ven_date_type_1").click(function () {
        var dateVal = $("#PaymentGateway_ven_date_type_1").val();
        $("#PaymentGateway_ven_from_date").val('');
        $("#PaymentGateway_ven_to_date").val('');
    });

    function viewVendor() {
        var vendorId = $("#PaymentGateway_apg_trans_ref_id").val();
    }


    $(document).ready(function () {
        var fromDate = <?= $dateFromDate; ?>;
        var toDate = <?= $dateTodate ?>;

    });
</script>
<?php
$version			 = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>