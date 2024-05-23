<?php
$arr_name	 = $_REQUEST['Vendors'];
$arr_phone	 = $_REQUEST['ContactPhone']['phn_phone_no'];
if ($arr_name != '' || $arr_phone != '' || ($arr_name != '' && $arr_phone != ''))
{
	$style_enquirySec = "display:none";
}
else
{
	$style_assignVendorSec = "display:none";
}

/* @var $bkModel Booking */
$js		 = "if($.isFunction(window.refreshVendor))
{
window.refreshVendor();
}
else
{
window.location.reload();
}
";
?>
<script>
	if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['assinvendorgrid'] != undefined) {
		$(document).off('click.yiiGridView', $.fn.yiiGridView.settings['assinvendorgrid'].updateSelector);
	}
</script>
<style type="text/css">
    .checkbox{
        margin-top: 0;margin-bottom: 0;
    }
	span.required {
        color: #F00;
    }
	.form-horizontal .form-group{ margin-left: 0; margin-right: 0;}

</style> 


<div id="enquirySec" style="<?= $style_enquirySec ?>">

	<div class="row">
		<div class="col-xs-12"><h2>Reason for doing Manual Assignment</h2></div>
		<div class="col-xs-12">
			<?php
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'nmi-form', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){
                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/addnmi', ['bkg_id' => $bkid])) . '",
                                                "data":form.serialize(),
                                                            "dataType": "json",
                                                            "success":function(data1){
                                                                if(data1.success){
                                                                  $("#assignVendorSec").show();
                                                                 $("#enquirySec").hide();

                                                                }else{
																	
                                                                   $("#errorMSg").text(data1.error);
																	
                                                                 }
                                                            },
                                                });
                                                }
                                            }'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => 'form-horizontal'
				),
			));
			/* @var $form TbActiveForm */
			?>
			<div class="col-xs-6 col-md-12">
				<label for="delete"><b>Do we need to have more vendors serving this region: </b>
					<span class="required"></span></label>
				<?php
				if ($isNMIcheckedZone > 0)
				{
					echo "<br />" . "NMI already saved.";
				}
				else
				{
					echo $form->radioButtonListGroup($bkModel->bkgTrail, 'btr_nmi_flag', array(
						'label'			 => '', 'widgetOptions'	 => array(
							'data' => [1 => 'Yes', 0 => 'No'],
						), 'groupOptions'	 => ['class' => 'pl20']
							//'inline'		 => true,
							)
					);
				}
				?>
			</div>
			<div class="col-xs-6 col-md-4 mr15">
				<label for="delete"><b>Reason for doing manual assignment: </b>
				</label>
				<?=
				$form->radioButtonListGroup($bkModel->bkgTrail, 'btr_nmi_reason', array(
					'label'			 => '', 'widgetOptions'	 => array(
						'data' => BookingTrail::model()->nmiReason
					), 'groupOptions'	 => ['class' => 'pl20']
						//'inline'		 => true,
						)
				);
				?> 
				<span for="delete" id="errorMSg" class="required"></span>
			</div>
			<div class="col-xs-12"> 
				<div class="Submit-button" >
					<?php echo CHtml::submitButton('Note to booking', array('class' => 'btn btn-warning')); ?>

				</div></div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<div id="assignVendorSec" style="<?= $style_assignVendorSec ?>">
	<div class="col-xs-12"><strong>Vendor List</strong></div>
	<div id="vendor-content" class="panel-advancedoptions" >
		<div class="errorSummary alert alert-block alert-danger" style="display: none"></div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel">
					<div class="panel-body panel-no-padding">
						<div class="row">
							<div class="col-xs-5 col-md-3"><label>Booking ID:&nbsp;</label><strong><?= $bkModel->bkg_booking_id ?></strong></div>
							<div class="col-xs-7 col-md-4"><label>Pickup Date:&nbsp;</label><strong><?= DateTimeFormat::DateTimeToLocale($bkModel->bkg_pickup_date) ?></strong></div>
							<div class="col-xs-12 col-md-5"><label>Route:&nbsp;</label><strong><?= $bkModel->bkgFromCity->cty_name ?> - <?= $bkModel->bkgToCity->cty_name ?></strong></div>
						</div>
						<div class="well p5 m0">
							<?php
							$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'vendorform',
								'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
									'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "dataType":"html",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){
                               
                               $("#vendor-content").parent().html(data1);
                               $("#vendor-content").show();
                                 $("#assignVendorSec").show();
                                 $("#enquirySec").hide();
                                    },
                                });
                                
                                }
                        }'
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
							Logger::create("6");
							/* @var $form TbActiveForm */
							?>
							<div class="row">
								<div class="col-xs-4">
									<?= $form->textFieldGroup($model, 'vnd_name', array('label' => '', 'htmlOptions' => array('placeholder' => 'search by name'))) ?>
								</div>
								<div class="col-xs-4">
									<?= $form->textFieldGroup($phoneModel, 'phn_phone_no', array('label' => '', 'htmlOptions' => array('placeholder' => 'search by phone'))) ?>
								</div>

								<?php
								if ($assignBlocked)
								{
									?>
									<div class="col-xs-2">
										<div class="row">
											<div class="col-xs-12">       
												<?= $form->checkboxGroup($model, 'vndIsBlocked', ['label' => 'Blocked', 'widgetOptions' => ['htmlOptions' => []], 'inline' => true, 'groupOptions' => ['class' => 'm0']]); ?>
											</div>
											<div class="col-xs-12">
												<?= $form->checkboxGroup($model, 'vndIsFreezed', ['label' => 'Freezed', 'widgetOptions' => ['htmlOptions' => []], 'inline' => true, 'groupOptions' => ['class' => 'm0']]); ?>
											</div>
											<div class="col-xs-12">
												<?= $form->checkboxGroup($model, 'vndUnApproved', ['label' => 'Unapproved', 'widgetOptions' => ['htmlOptions' => []], 'inline' => true, 'groupOptions' => ['class' => 'm0']]); ?>
											</div> 
										</div> 
									</div>

									<?php
								}
								?>
								<div class="col-xs-2 pr5">
									<button class="btn btn-primary" type="submit" name="searchVendor" id="searchVendor">Search</button>
								</div>
							</div>

							<?php $this->endWidget(); ?>

						</div>
						<?php
						//echo "bookingId------->".$bkid."<br>";
						//echo "bookingId------->".$bkid2;
						if (!empty($dataProvider))
						{

							$checkNightlyVendorAssignment	 = Booking::checkVendor($bkModel->bkg_pickup_date,$bkModel->bkg_id);
							$checkPreVendorAssignAccess = ((Yii::app()->user->checkAccess('preVendorAssignment')) || $checkNightlyVendorAssignment || (Yii::app()->user->checkAccess('PreAssignVendorWithMargin')) || ($inScq > 0));
							$this->widget('booster.widgets.TbGridView', array(
								'id'				 => 'assinvendorgrid',
								'responsiveTable'	 => true,
								'dataProvider'		 => $dataProvider,
								'template'			 => "<div class='panel-heading'><div class='row m0'>
                    <div class='col-xs-12 col-sm-5 pt5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
                    </div></div>
                    <div class='panel-body'>{items}</div>
                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-5 p5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div></div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
								//'ajaxType' => 'POST',
								'columns'			 => array(
									array('name'	 => 'vnd_name',
										//'value' => '$data["vnd_name"]',
										'value'	 => function ($data) {
											$name	 = '';
											$name	 .= $data["vnd_name"];
											if ($data['vnd_is_freeze'] != 0)
											{
												$name .= ' <span class="label label-primary">Freezed</span>';
											}
											if ($data['vnd_active'] == 2)
											{
												$name .= ' <span class="label label-warning">Blocked</span>';
											}
											if ($data['vnd_active'] == 3)
											{
												$name .= ' <span class="label label-danger">Unapproved</span>';
											}
											echo $name;
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Name'),
									array('name'	 => 'vnd_phone', 'value'	 => function ($data) {

											$arrPhoneByPriority = ContactPhone::getPhoneNo($data['vnd_id'], UserInfo::TYPE_VENDOR);
											echo $arrPhoneByPriority;
										}
										, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Phone'),
									//	array('name' => 'vnd_email', 'value' => '$data["vnd_email"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all'), 'header' => 'Email'),
									array('name' => 'vnd_overall_rating', 'value' => '$data["vnd_overall_rating"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header' => 'Rating'),
									array('name'				 => 'smtScore', 'value'				 => '$data["smtScore"]',
										'sortable'			 => true, 'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'			 => 'SMT Score'),
									array('name'				 => 'cavScore', 'value'				 => '$data["cavScore"]',
										'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'tScore text-center'), 'header'			 => 'Cav Score'),
										array('name'				 => 'dependencyScore', 'value'				 => '$data["dependencyScore"]',
										'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'tScore text-center'), 'header'			 => 'Dependency Score'),
									array('name'	 => 'bvr_bid_amount', 'visible' => $checkBidAmountAccess,'value'	 =>
										function ($data) {
											echo $data['bidding'] == "-1" ? $data["bvr_bid_amount"] . "(Bid denied)" : $data["bvr_bid_amount"];
										},
										'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'			 => 'Bid Amount'),
									array('name'	 => 'bvr_created_at',
										'value'	 => function ($data) {
											echo $data["bvr_bid_amount"]!= ''? DateTimeFormat::DateTimeToLocale($data['bvr_created_at']) : '-';
										}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Bid Date/Time'),
									array('name'	 => 'acptAmount', 'value'	 =>
										function ($data) use ($bkModel) {
											$directAcptAmount = BookingVendorRequest::getDirectAcceptAmount($data['vnd_id'], $bkModel->bkg_bcb_id);
											echo $directAcptAmount;
										},
										'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'			 => 'Suggested amount'),
									array('name'				 => 'Action', 'visible'			 => $checkPreVendorAssignAccess, 'type'				 => 'raw', 'value'				 => 'Vendors::model()->getActionButton($data,' . $bkid . ')', 'sortable'			 => false, 'headerHtmlOptions'	 => array('style' => 'min-width:150px;'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Action'
									)
							)));
						}
						Logger::create("7");
						?>


					</div>
				</div>
			</div>
		</div>
	</div>
</div><!--<?
print_r($GLOBALS['time']);
?>-->
<script>

	function processLink(obj) {
		var url = $(obj).attr("href");
		$.ajax({
			url: url,
			success: function () {},
			dataType: "json"
		});
		return false;
	}
	;
	function valvendor(bkid) {
		$('#btn_' + bkid).removeClass('btn-info');
		$('#btn_' + bkid).addClass('btn-success');
		$('.vbtn').addClass('disabled');
	}

//
	refreshVendor = function () {
		//  box.modal('hide');
		$href = '<?= Yii::app()->createUrl('admin/vendor/json') ?>';
		jQuery.ajax({type: 'POST', "dataType": "json", url: $href,
			success: function (data1) {
				$data = data1;
				$('#<?= CHtml::activeId($model, "bcb_driver_id") ?>').select2({data: $data, multiple: false});
			}
		});
	};

<?php
$loadingPic = CHtml::image(Booster::getBooster()->getAssetsUrl() . '/img/loading.gif');
?>
	function assignCab(obj) {
		$href = $(obj).attr('href');
		var titlestr = 'Add Driver Details';
		jQuery.ajax({type: 'GET',
			url: $href,
			success: function (data)
			{
				cabBox = bootbox.dialog({
					message: data,
					title: titlestr,
					onEscape: function () {
						// user pressed escape
					},
				});
				cabBox.on('hidden.bs.modal', function (e) {
					$('body').addClass('modal-open');
				});

			}
		});
		return false;
	}

	$('#assinvendorgrid .tScore a1').click(function (e) {
		e.preventDefault();
		return showReturnDetails(this);
	});
	function showReturnDetails(obj, type) {
		var span = 8;
		var that = $(obj);
		var status = that.data('status');
		var rowid = that.attr('vendorid');
		var tr = $('#relatedinfo_' + type + '_' + rowid);
		var parent = that.parents('tr').eq(0);

		if (status && status == 'on') {
			return;
		}
		that.data('status', 'on');
		if (tr.length && !tr.is(':visible'))
		{
			tr.slideDown();
			that.data('status', 'off');
			return false;
		} else if (tr.length && tr.is(':visible'))
		{
			tr.slideUp();
			that.data('status', 'off');
			return false;
		}

		if (tr.length)
		{
			tr.find('td').html('<?= $loadingPic ?>');
			if (!tr.is(':visible')) {
				tr.slideDown();
			}
		} else
		{
			var td = $('<td/>').html('<?= $loadingPic ?>').attr({'colspan': span});
			tr = $('<tr/>').prop({'id': 'relatedinfo_' + type + '_' + rowid}).append(td);
			/* we need to maintain zebra styles :) */
			var fake = $('<tr class="hide"/>').append($('<td/>').attr({'colspan': span}));
			parent.after(tr);
			tr.after(fake);
		}
//	var data = $.extend({$data}, {id:rowid});
		$href = that.attr('href');
		$.ajax({
			url: $href,
			success: function (data) {
				tr.find('td').html(data);
				that.data('status', 'off');
			},
			error: function ()
			{
				tr.find('td').html('{$this->ajaxErrorMessage}');
				that.data('status', 'off');
			}
		});

		return false;
	}
</script>
