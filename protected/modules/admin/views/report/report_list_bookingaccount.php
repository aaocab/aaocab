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
													$this->widget('booster.widgets.TbPager', array('pages' => $bookingList->pagination));
													?>
                                                </td>
                                            </tr>        
                                        </table>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-7 table-responsive">
                                    <h5>Vendor Collections status:</h5>
                                    <table class="table table-bordered">
                                        <tr class="blue2 white-color">
                                            <td><b>Vendor Name</b></td>
                                            <td><b>Amount Due</b></td>
                                            <td><b>Amount Payable</b></td>
                                            <td><b>Days</b></td>
                                            <td><b>Problem Booking</b></td>
                                        </tr>
										<?php
										if (count($vendorModels) > 0)
										{
											foreach ($vendorModels as $vendor)
											{
												?>       
												<tr>
													<td><b><?php echo trim($vendor['vendor_name']); ?></b></td>
													<td align="center"><b><?php
															if ($vendor['vendor_amount'] > 0)
															{
																echo '<i class="fa fa-inr"></i>' . round($vendor['vendor_amount']);
															}
															?></b></td>
													<td align="center"><b><?php
															if ($vendor['vendor_amount'] < 0)
															{
																echo '<i class="fa fa-inr"></i>' . round($vendor['vendor_amount']);
															}
															?></b></td>
													<td><b></b></td>
													<td><b><?php
															if ($vendor['countFlag'] > 0)
															{
																echo $vendor['countFlag'];
															}
															else
															{
																echo '0';
															}
															?></b></td>
												</tr>
												<?php
											}
										}
										?>
                                        <tr>
                                            <td colspan="5" class="vendorPages"><?php
												// the pagination widget with some options to mess
												$this->widget('booster.widgets.TbPager', array('pages' => $vendorModels->pagination));
												?>
                                            </td>
                                        </tr>  
                                    </table>
                                </div>
                            </div>
                            <div class="row">
								<?php
								$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
													<?= $form->textAreaGroup($model, 'search', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter booking ID, Customer name, Customer email, Customer phone, Vendor name, Driver name or car', 'style' => 'min-height:75px')))) ?>
												</div>
											</div>
											<div class="form-group">
												<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'value' => 'Find Bookings')); ?>
											</div>
										</div></div></div>
								<?php $this->endWidget(); ?>

								<?php
								$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
												$data	 = Vendors::model()->getJSON('1');

												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $venModel,
													'attribute'		 => 'trans_vendor_id',
													'val'			 => $venModel->trans_vendor_id,
													'asDropDownList' => FALSE,
													'options'		 => array('data' => new CJavaScriptExpression($data)),
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
												));
												?>
												<?= $form->error($venModel, 'trans_vendor_id') ?>
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
														'data' => array('1' => 'This Week', '2' => ''),
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

    $("#VendorTransactions_ven_date_type_0").click(function () {
        var dateVal = this.val();
    })

    $("#VendorTransactions_ven_date_type_1").click(function () {
        var dateVal = this.val();
    })
    function viewVendor() {

        var vendorId = $("#VendorTransactions_trans_vendor_id").val();
    }


</script>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>