<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<style>
    .tabcontent {
        -webkit-animation: fadeEffect 1s;
        animation: fadeEffect 1s; /* Fading effect takes 1 second */
    }

    @-webkit-keyframes fadeEffect {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeEffect {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    ul.tab {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Float the list items side by side */
    ul.tab li {
        float: left;
    }

    /* Style the links inside the list items */
    ul.tab li a {
        display: inline-block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of links on hover */
    ul.tab li a:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    ul.tab li a:focus, .active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
    .table_new table{
        width: 99%;
    }
    .selectize-input {
        min-width: 0px !important;
        width: 30% !important;

    }
    .form-horizontal .form-group{
        margin-left: 0;
        margin-right: 0;
    }
</style>
<div class="container">
	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'setting-form', 'enableClientValidation' => FALSE,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
		),
	));
	/* @var $form TbActiveForm */
	?>
    <div class="row">
		<?php echo CHtml::errorSummary($model); ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <div class="panel panel-heading">Partner ID : <?= $model->agt_agent_id ?></div>
                        <div class="panel-body pt0">

                            <div class="row">
                                <div class="col-sm-4">
									<?= $form->textFieldGroup($model, 'agt_opening_deposit', array('label' => "Account opening deposit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-4">
									<?= $form->numberFieldGroup($model, 'agt_credit_limit', array('label' => "Credit Limit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter credit limit for agent', 'min' => 0)))) ?>
                                </div>
								<div class="col-sm-4">
									<?= $form->numberFieldGroup($patSettingModel, 'pts_rotating_credit_limit', array('label' => "Rotating Credit Limit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter rotating credit limit for agent', 'min' => 0)))) ?>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->textFieldGroup($model, 'agt_effective_credit_limit', array('label' => "Effective Credit Limit", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('readOnly' => 'readOnly')))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->numberFieldGroup($model, 'agt_overdue_days', array('label' => "Payment overdue days (#days gone beyond grace days)", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('readOnly' => 'readOnly')))) ?>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
									<?= $form->numberFieldGroup($model, 'agt_grace_days', array('label' => "Payment cycle terms (aka Payment Grace days)", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array()))) ?>
                                </div>
                                <div class="col-sm-6">
									<?= $form->datePickerGroup($model, 'agt_approved_untill_date', array('label' => 'Approved Untill Date ', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Untill date', 'value' => DateTimeFormat::DateTimeToDatePicker($model->agt_approved_untill_date))), 'prepend' => '<i class="fa fa-calendar"></i>'));
									?>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputName6">Gozo Operating Managers</label>
										<?php
										$operatingArr		 = Admins::model()->findNameList();
										$this->widget('booster.widgets.TbSelect2', array(
											'name'			 => 'arl_operating_managers',
											'model'			 => $AgentRel,
											'data'			 => $operatingArr,
											'value'			 => explode(',', $AgentRel->arl_operating_managers),
											'htmlOptions'	 => array(
												'multiple'		 => 'multiple',
												'placeholder'	 => 'Operating Manager',
												'width'			 => '100%',
												'style'			 => 'width:100%',
											),
										));
										?>

                                    </div>

                                </div>

                                <div class="col-xs-6">
                                    <label class="control-label" for="exampleInputName6">Generate Invoice to Customer / Partner</label>
									<?php
									$generateInvoiceArr	 = PartnerSettings::model()->generateInvoice;
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $patSettingModel,
										'attribute'		 => 'pts_generate_invoice_to',
										'val'			 => $patSettingModel->pts_generate_invoice_to,
										'data'			 => $generateInvoiceArr,
										'htmlOptions'	 => array('style' => 'width:100%', 'multiple' => '', 'placeholder' => 'Select Send Generate Link To')
									));
									?>		  
                                </div>				

                            </div>

                            <div class="row">
                                <div class="col-sm-6">
									<?php $arrRules			 = BookingPref::model()->getCancelChargeRule($model->agt_cancel_rule); ?>
                                    <div class="col-sm-12"><b>Cancellation Rule</b></div>
                                    <div class="col-sm-12">Min Charge : <?= $arrRules['minCharge']['value'] ?></div>
                                    <div class="col-sm-12">Max Charge : <?= $arrRules['maxCharge']['value'] ?></div>
                                    <div class="col-sm-12">Default Charge : <?= $arrRules['defcharge']['value'] ?></div>
                                    <div class="col-sm-12">Penalize Partner : <?= ($arrRules['penalizePartner'] == 1) ? "Yes" : "No" ?></div>
                                    <div class="col-sm-12">Minimum Time : <?= ($arrRules['minimumTime'] / 60) ?> hours</div>
                                    <div class="col-sm-12">No Refund Time : <?= ($arrRules['noRefundTime'] / 60) ?> hours</div>
                                </div>

								<?php
								if ($patSettingModel)
								{
									$partnerRuleCommission = CJSON::decode($patSettingModel->pts_additional_param, false);
									?>
									<div class="col-sm-6">
										<div class="col-sm-12"><b>Commission Rule:</b></div>
										<div class="col-sm-12">Local Booking Count(Last 15Days) : <?= $patSettingModel->pts_local_count; ?></div>
										<div class="col-sm-12">Is Local Rules(Applied) : <?= $partnerRuleCommission->local->isApplied == 1 ? "Yes" : "No" ?></div>
										<div class="col-sm-12">Local Commission Type : <?= ($partnerRuleCommission->local->commissionType == 1 ? "Percentage" : ($partnerRuleCommission->local->commissionType == 2 ? "Fixed" : "NA")) ?></div>
										<div class="col-sm-12">Local Commission Value : <?= $partnerRuleCommission->local->commissionValue ?></div>
										<div class="col-sm-12">OutStation Booking Count(Last 15Days) : <?= $patSettingModel->pts_outstation_count; ?></div>
										<div class="col-sm-12">Is OutStation Rules(Applied) : <?= $partnerRuleCommission->outstation->isApplied == 1 ? "Yes" : "No" ?></div>
										<div class="col-sm-12">OutStation Commission Type : <?= ($partnerRuleCommission->outstation->commissionType == 1 ? "Percentage" : ($partnerRuleCommission->outstation->commissionType == 2 ? "Fixed" : "NA")) ?></div>
										<div class="col-sm-12">OutStation Commission Value : <?= $partnerRuleCommission->outstation->commissionValue ?></div>
									</div>     
								<?php } ?>

                            </div>
							<div class="row">
								<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-10 mb15"><b>Partner Commission Rule Slab:</b></div>
									<div class="col-sm-2 mb15"><a class="btn btn-primary mb10" onclick="addCommission(this);return false;" data-title="add" data-toggle ="ajaxModal" href ="<?php echo Yii::app()->createUrl("aaohome/agent/addPartnerCommission", array("agtId" => $model->agt_id)); ?>">Add Commission</a></div>
									</div>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th scope="col">Booking Type</th>
												<th scope="col">Commission Type</th>
												<th scope="col">Commission Value</th>
												<th scope="col">Booking Count</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$arrPartnerRules = PartnerRuleCommission::model()->getByAgtId($model->agt_id);
											foreach ($arrPartnerRules as $partnerRules)
											{
												?>
												<tr>
													<td> <?= ($partnerRules['prc_booking_type'] == 1) ? "Outstation" : "Local" ?></td>
													<td><?= ($partnerRules['prc_commission_type'] == 1) ? "Percentage" : "Fixed" ?></td>
													<td><?= ($partnerRules['prc_commission_value'] != '') ? $partnerRules['prc_commission_value'] : "-" ?></td>
													<td><?= ($partnerRules['prc_booking_count'] != '') ? $partnerRules['prc_booking_count'] : "-" ?></td>
													<td><a onclick="addCommission(this);return false;" data-title="Edit" data-toggle ="ajaxModal" href ="<?php echo Yii::app()->createUrl("aaohome/agent/addPartnerCommission", array("agtId" => $model->agt_id, "ruleId" => $partnerRules['prc_id'])); ?>"><img src ="/images/icon/vendor/edit_booking.png"></a></td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>

							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center pb10">
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
        </div>
    </div>
    <div id="driver1"></div>
	<?php $this->endWidget(); ?>
</div>
<script>
	function addCommission(obj) {
		try
		{
			$href = $(obj).attr("href");
			jQuery.ajax({type: "GET", url: $href, success: function (data)
				{
					bootbox.dialog({
						message: data,
						size: "bootbox-sm",
						title: "Partner Commission Rule",
						success: function (result) {
							if (result.success) {

							} else {
								alert('Sorry error occured');
							}
						},
						error: function (xhr, status, error) {
							alert('Sorry error occured');
						}
					});
				}});
		} catch (e)
		{
			alert(e);
		}
		return false;
	}
</script>