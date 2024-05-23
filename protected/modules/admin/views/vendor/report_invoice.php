<?php
if ($openingAmount['vendor_amount'] > 0)
{
	$openingBalance = $openingAmount['vendor_amount'];
}
else if ($openingAmount['vendor_amount'] < 0)
{
	$openingBalance = $openingAmount['vendor_amount'];
}
else
{
	$openingBalance = 0;
}
$bookingAmount	 = ($dataProvider['total_amount'] > 0) ? $dataProvider['total_amount'] : '0';
$baseAmount		 = ($dataProvider['base_amount'] > 0) ? $dataProvider['base_amount'] : '0';
$vendorAmount	 = ($dataProvider['vendor_amount'] > 0) ? $dataProvider['vendor_amount'] : '0';


if ($dataProvider['service_charge_amount'] > 0)
{
	$serviceChargeAmount = $dataProvider['service_charge_amount'];
}
else if ($dataProvider['service_charge_amount'] < 0)
{
	$serviceChargeAmount = $dataProvider['service_charge_amount'];
}
else
{
	$serviceChargeAmount = '0';
}
$serviceTaxAmount	 = ($dataProvider['service_tax_amount'] > 0) ? $dataProvider['service_tax_amount'] : '0';
$tdsAmount			 = ($dataProvider['total_tds_amount'] > 0) ? $dataProvider['total_tds_amount'] : '0';
$totalAmount		 = ($serviceChargeAmount + $serviceTaxAmount + $tdsAmount);
$paymentAdjustment	 = $adjustAmount['adjust_amount'];
$amountDue			 = ($openingBalance + $totalAmount + $paymentAdjustment);
$totalBooking		 = $dataProvider['total_booking'] > 0 ? $dataProvider['total_booking'] : '0';
?>
<style>
    span.stars, span.stars span {
        display: block;
        background: url(http://localhost:92/images/stars.png) 0 -16px repeat-x;
        width: 80px;
        height: 16px;
    }
    span.stars span {
        background-position: 0 0;
    }
</style>

<section id="section7">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 book-panel2">
                <div class="container p0">
                    <div class="col-xs-12">
						<?php
						if (isset($vendorId) && $vendorId > 0)
						{
							?>
							<div class="profile-right-panel">
								<div class="row">
									<div class="panel panel-default">
										<div class="panel-body">
											<?php
											$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
												'id'					 => 'vendorStatementForm',
												'method'				 => 'POST', 'enableClientValidation' => true,
												'action'				 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/invoice')),
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
											<div class="col-xs-12 mb10">

												<div class="col-xs-12 col-sm-4 col-md-3">

													<?php
//													$vendorData	 = Vendors::model()->getJSON('1');
//													$this->widget('booster.widgets.TbSelect2', array(
//														'model'			 => $model,
//														'attribute'		 => 'vnd_id',
//														'val'			 => $model->vnd_id,
//														'asDropDownList' => FALSE,
//														'options'		 => array('data' => new CJavaScriptExpression($vendorData)),
//														'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//													));
													$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
														'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
														'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
														'openOnFocus'		 => true, 'preload'			 => false,
														'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
														'addPrecedence'		 => false,];
													$this->widget('ext.yii-selectize.YiiSelectize', array(
														'model'				 => $model,
														'attribute'			 => 'vnd_id',
														'useWithBootstrap'	 => true,
														"placeholder"		 => "Select Vendor",
														'fullWidth'			 => false,
														'htmlOptions'		 => array('width' => '100%'),
														'defaultOptions'	 => $selectizeOptions + array(
													'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vnd_id}');
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

												</div>
												<div class="col-xs-12 col-sm-4 col-md-2">
													<?=
													$form->datePickerGroup($model, 'from_date', array('label'			 => '',
														'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
																'startDate'	 => date(),
																'value'		 => $model->from_date, 'format'	 => 'dd/mm/yyyy'),
															'htmlOptions'	 => array('placeholder'	 => 'From Date',
																'value'			 => ($model->from_date == '') ? '' : $model->from_date)),
														'prepend'		 => '<i class="fa fa-calendar"></i>'));
													?>
												</div>
												<div class="col-xs-12 col-sm-4 col-md-2"><?=
													$form->datePickerGroup($model, 'to_date', array('label'			 => '',
														'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
																'startDate'	 => date(),
																'value'		 => $model->to_date,
																'format'	 => 'dd/mm/yyyy'),
															'htmlOptions'	 => array(
																'placeholder'	 => 'To Date',
																'value'			 => ($model->to_date == '') ? '' : $model->to_date)), 'prepend'		 => '<i class="fa fa-calendar"></i>'
													));
													?>
												</div>
												<div class="col-xs-12 col-sm-4 col-md-3">
													<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
												</div>
												<div class="col-xs-12 col-sm-4 col-md-2">
													<?php
													if (count($dataProvider) > 0)
													{
														?>
														<button type='submit' class='btn btn-info' name="submit" value='2'>Generate Invoice</button>
													<?php }
													?>
												</div>

											</div>
											<?php echo CHtml::hiddenField('vnd_id', $model->vnd_id); ?>
											<?php $this->endWidget(); ?>
										</div>
									</div>
								</div>
								<div id="vendorContent">
									<div class="row">
										<div class="panel panel-default">
											<div class="panel-body">
												<div class="row">
													<table border="0" cellpadding="5" cellspacing="5" width="100%" style="margin-bottom: 15px;" class="table table-bordered">
														<tbody>
															<tr>
																<td valign="top" class="" width="25%"><strong>Outstanding Balance as on <?= $model->from_date; ?></strong> </td>
																<td valign="top" class="" width="25%" style="text-align: left"><strong>Amount for the period</strong></td>
																<td valign="top" class="" width="25%" style="text-align: left"><strong>Payments adjustment during the period</strong> </td>
																<td valign="top" class="" width="25%" style="text-align: left"><strong>Outstanding balance as on <?= $model->to_date; ?></strong></td>
															</tr>
															<tr>
																<td style="text-align: right;"><i class="fa fa-inr"></i><?= number_format($openingBalance, 2) ?></td>
																<td style="text-align: right;"><i class="fa fa-inr"></i><?= number_format($totalAmount, 2) ?></td>
																<td style="text-align: right;"><i class="fa fa-inr"></i><?= number_format($paymentAdjustment, 2); ?></td>
																<td style="text-align: right;"><i class="fa fa-inr"></i><?= number_format($amountDue, 2); ?></td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="row">
													<table class="table table-bordered">
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Period</b></td>
															<td style="width:70%"><?php echo $model->from_date . " - " . $model->to_date ?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Opening Balance as on <?= $model->from_date; ?></b></td>
															<td style="width:70%"><i class="fa fa-inr"><?= $openingBalance; ?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Number Of Trips</b></td>
															<td style="width:70%"><?= $totalBooking; ?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Booking Amount</b></td>
															<td style="width:70%"><i class="fa fa-inr"><?= number_format($bookingAmount, 2); ?></i></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Base Amount</b></td>

															<td style="width:70%"><i class="fa fa-inr"><?= number_format($baseAmount, 2); ?></i></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Vendor Amount</b></td>
															<td style="width:70%"><i class="fa fa-inr"><?= number_format($vendorAmount, 2); ?></i></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Gozo Service Charge (A)</b></td>
															<td style="width:70%"><i class="fa fa-inr"></i><?= number_format($serviceChargeAmount, 2); ?> </td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>GST (On Base Amount) (B)</b></td>
															<td style="width:70%"><i class="fa fa-inr"></i><?= number_format($serviceTaxAmount, 2); ?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>TDS ( @1% of Vendor Amount) (C)</b></td>
															<td style="width:70%"><i class="fa fa-inr"></i><?= number_format($tdsAmount, 2); ?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Total Amount (A+B+C)</b></td>

															<td style="width:70%">
																<i class="fa fa-inr"></i><?= number_format($totalAmount, 2); ?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Total Amount In Words</b></td>

															<td style="width:70%"><?php
																$objNum = new NumbersToWords();
																if ($totalAmount > 0)
																{
																	echo "Rupees ";
																	echo $objNum->convertToWords($totalAmount);
																}
																else
																{
																	echo "NIL";
																}
																?></td>
														</tr>
														<tr class="blue2 white-color">
															<td style="width:30%"><b>Amount Due</b></td>
															<td style="width:70%"><i class="fa fa-inr"></i><?= number_format($amountDue, 2) ?></td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						else
						{
							$url = Yii::app()->createAbsoluteUrl('admin/vendor/bulkInvoice');
							?>
							<div class="profile-right-panel">
								<div class="row">
									<div class="panel panel-default">
										<div class="panel-body">Please click on the following URL and select any vendor. 
											<a href="<?= $url; ?>">Bulk Invoices OR Account Statement</a></div>
									</div>
								</div>
							</div>    
						<?php }
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<script>
    $(function () {
	$('span.stars').stars();
    });
    $.fn.stars = function () {
	return $(this).each(function () {
// Get the value
	    var val = parseFloat($(this).html());
// Make sure that the value is in 0 - 5 range, multiply to get width
	    var size = Math.max(0, (Math.min(5, val))) * 16;
// Create stars holder
	    var $span = $('<span />').width(size);
// Replace the numerical value with stars
	    $(this).html($span);
	});
    }

</script>