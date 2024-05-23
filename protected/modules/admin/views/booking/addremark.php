
<?php
$scq = ServiceCallQueue::model();

if ($bookModel->bkgTrail->btr_nmi_flag == 1)
{
	$htmlOptions = "return false";
}

if ($bookModel->bkgTrail->bkg_escalation_status == 0)
{
	$style = "display:none";
}
else
{
	$style = "display:block";
}

$escalateArr = BookingTrail::model()->escalation;
if ($isNMIcheckedZone > 0)
{
	$bookModel->bkgTrail->btr_nmi_flag_var = 1;
}
?>
<style>
    span.required {
        color: #F00;
    }

	span.validationMsg {
        color:#006600;
        font-size: 12px;
    }

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }

    div .comments .comment {
        padding:3px;
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }   

    .remarkbox{
        width: 100%; 
        padding: 3px;  
        overflow: auto; 
        line-height: 14px; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }
    .part-formgroup .form-group{ margin-bottom: 0; margin-top: 10px;}
    .part-formgroup2 .form-group{ margin-top: 8px;}
</style>


<style>

    .form-group {
        margin-bottom: 7px;
        margin-top: 15px;

        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .form-horizontal .checkbox-inline{
        padding-top: 0;
    }
    #Booking_chk_user_msg{
        margin-left: 10px
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<script>
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>
<?php
$adminlist	 = Admins::model()->findNameList();
$statuslist	 = Booking::model()->getActiveBookingStatus();
$name		 = Admins::model()->findById($userInfo->userId);
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel" >

                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll">
                        <div class="row">
                            <div class="col-xs-12">
								<?php
								$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'add-remark-form', 'enableClientValidation' => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error',
										'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){
                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/addremarks', ['booking_id' => $bookModel->bkg_id])) . '",
                                                "data":form.serialize(),
                                                        "dataType": "json",
                                                        "success":function(data1){
                                                                if(data1.success)
                                                                {
                                                               // $(".bootbox").modal("hide");
                                                              // $("#BookingLog_blg_desc").val("");
                                                              // $("#BookingLog_blg_reason_text").val("");
                                                              $("#add-remark-form").trigger("reset");
                                                               $("#savedlog").show();
															   $("#remarkSubmit").val(1);
															   $("#remarkVendorDriverSubmit").val(1);
															    delOneminlog("' . $bkid . '");
                                                                
                                                                }
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
										'class' => 'form-horizontal'
									),
								));
								/* @var $form TbActiveForm */
								?>

								<?= $form->hiddenField($logModel, 'blg_booking_id') ?>
                                <!--                                <div class="form-group">
                                                                    <label for="delete"><b>Remark Type: </b></label>
								<?=
								$form->radioButtonListGroup($logModel, 'blg_remark_type', array(
									'label'			 => '', 'widgetOptions'	 => array(
										'data' => BookingLog::model()->markRemarkBad,
									),
									'inline'		 => true,
										)
								);
								?>
                                                                </div>-->
								<?php
								if ($bookModel->bkgBcb->bcb_cab_id != NULL)
								{
									?>
									<!--                                    <div class="form-group">
									<? // $form->checkBox($logModel, 'blg_mark_car', array('label' => ''));  ?>&nbsp;<label for="delete"><b>Mark car bad</b></label>
																		</div>-->
								<?php }
								?>

								<?php
								if ($bookModel->bkgBcb->bcb_driver_id != NULL)
								{
									?>
									<!--                                    <div class="form-group">
									<? // $form->checkBox($logModel, 'blg_mark_driver', array('label' => '')); ?>&nbsp;<label for="delete"><b>Mark driver bad</b></label>
																		</div>-->
								<?php }
								?>
								<?php
								if ($bookModel->bkgUserInfo->bkg_user_id != NULL)
								{
									?>
									<!--									<div class="form-group">
									<? // $form->checkBox($logModel, 'blg_mark_customer', array('label' => '')); ?>&nbsp;<label for="delete"><b>Mark customer bad</b></label>
																											</div>-->
								<?php }
								?>
                                <!--                                <div class="form-group">
                                                                    <label for="delete"><b>Add Remark : </b></label>
							  
                                                                </div>-->



                                <div class="row" >
                                    <div class="col-xs-6"> 
										<?= $form->textAreaGroup($logModel, 'blg_addl_desc', array('label' => 'Additional Instruction to Vendor/Driver :', 'rows' => 10, 'cols' => 50, 'htmlOptions' => array('placeholder' => ''))) ?>
                                    </div>
                                    <div class="col-xs-6 ">
										<a class="btn btn-info btn-sm mb5 mr5 mt40" onclick="sendToVendorDriver(<?= $bkid; ?>);">Send to vendor/driver</a>
										<br /><span class="validationMsg" id="successText"></span>
										<input type="hidden" name="remarkVendorDriverSubmit" id="remarkVendorDriverSubmit">
									</div>
                                </div>


                                <div  class=" col-xs-12 bg bg-light pb20 part-formgroup ">
									<div class="row mt10">
										<div class="col-xs-6 col-md-3">
											<?= $form->checkboxGroup($bookModel->bkgTrail, 'bkg_escalation_status', []) ?>
										</div>
										<div class="col-xs-6 col-md-3 ">
											<?= $form->checkboxGroup($bookModel->bkgPref, 'bkg_account_flag', []) ?>
										</div>
										<div class="col-xs-6 col-md-3 ">
											<?= $form->checkboxGroup($bookModel->bkgPref, 'bkg_penalty_flag', []) ?>
										</div>
<!--										<div class="col-xs-6 col-md-2 ">
											<? //$form->checkboxGroup($bookModel->bkgTrail, 'bkg_followup_active', []) ?>
											<? //echo $form->checkboxGroup($bookModel->bkgTrail, 'follow_ups', []) ?>


										</div>-->
										<div class="col-xs-6 col-md-3">
											<?= $form->checkboxGroup($bookModel->bkgPref, 'bkg_duty_slip_required', []) ?>
										</div>
                                        <div class="col-xs-6 col-md-3">
											<?// $form->checkboxGroup($bookModel->bkgTrail, 'btr_nmi_flag' ,array('widgetOptions' => array('htmlOptions' => ['onclick'=> $htmlOptions]))) ?>
											<?php
											if ($isNMIcheckedZone > 0)
											{
												echo $form->checkboxGroup($bookModel->bkgTrail, 'btr_nmi_flag_var', array('widgetOptions' => array('htmlOptions' => ['onclick' => $htmlOptions])));
											}
											else
											{
												echo $form->checkboxGroup($bookModel->bkgTrail, 'btr_nmi_flag', array('widgetOptions' => array('htmlOptions' => ['onclick' => $htmlOptions])));
											}
											?>

										</div>
									</div>
									<div id="escalate" style="<?= $style ?>" class="bg-info p10 mt20" >
										<div class="row" >
											<div class="col-xs-6 col-md-4">
												<b>Escalation is set by : <?= $name['adm_fname']; ?> <?= $name['adm_lname'] ?></b><br />
												<?php
												$arr		 = $bookModel->bkgTrail->getEscalatiomLevel('color');
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $bookModel->bkgTrail,
													'attribute'		 => 'btr_escalation_level',
													'val'			 => $bookModel->bkgTrail->btr_escalation_level,
													'data'			 => $arr,
													'htmlOptions'	 => array('style'
														=> 'width:100%', 'placeholder'	 => 'Select escalation level')
												));
												?>
											</div>
											<div class="col-xs-6 col-md-4 pt30">

                                                <span id="escalatedescErr"></span>
												<span id="escalatedesc"><?= $escalateArr[$bookModel->bkgTrail->btr_escalation_level]['levelDesc']; ?></span>
											</div>

										</div>

										<div class="row mt20" >
											<div class="col-xs-12 col-md-4">
												<b>Primary person working on solving the escalation </b>
												<?php
												$arr		 = Admins::model()->employeesList();
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $bookModel->bkgTrail,
													'attribute'		 => 'btr_escalation_assigned_lead',
													'val'			 => $bookModel->bkgTrail->btr_escalation_assigned_lead,
													'data'			 => $arr,
													'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select name ')
												));
												?>

											</div>
											<div class="col-xs-6 col-md-4">
												<span id='escalateLeadErr'></span>
												<?= $form->checkboxGroup($bookModel->bkgTrail, 'btr_escalate_info_all', []) ?></div>
										</div>

										<div class="row mt20">
											<div class="col-xs-12 col-md-4">
												<b>Team(s) responsible for solving this escalation </b>
												<?php
												$teamarr	 = Teams::getList();
												unset($teamarr[22]);
												unset($teamarr[2]);
												$this->widget('booster.widgets.TbSelect2', array(
													'model'			 => $bookModel->bkgTrail,
													'attribute'		 => 'btr_escalation_assigned_team',
													'val'			 => explode(',', $bookModel->bkgTrail->btr_escalation_assigned_team),
													'data'			 => $teamarr,
													'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
														'placeholder'	 => 'Select team(s) ')
												));
												?>
											</div>
											<div class="col-xs-12 col-md-4">
												<!--<br />
												<span id='escalateTeamErr'></span>-->
											</div>
										</div>


									</div>
									<!--------------------------====================Followup block=====================---------------------------------->
							
<!--									<div id="scq"  class="row bg-warning p10 mt20" style="color: #4E5E6A;">

									</div>-->

									<!--------------------------====================END Followup block=====================---------------------------------->



                                    <div class="row mt10">
                                        <div class="col-xs-3 mt10"> 
                                            <label for="delete"><b>Reason for viewing booking: </b>
<!--                                            <span class="required">Required</span>-->
											</label>
                                        </div>
                                        <div class="col-xs-6 "> 
											<?=
											$form->radioButtonListGroup($logModel, 'blg_reason_view', array(
												'label'			 => '', 'widgetOptions'	 => array(
													'data' => BookingLog::model()->reasonViewBooking,
												),
												'inline'		 => true,
													)
											);
											?>
                                        </div>
                                        <div class="col-xs-3 part-formgroup2"><?=
											$form->textFieldGroup($logModel, 'blg_reason_text', array('label'			 => false, 'widgetOptions'	 =>
												array('htmlOptions' =>
													array('readonly'	 => 'readonly',
														'plceholder' => 'Reason',
											))))
											?></div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-3 mt10"> 
                                            <label for="delete"><b>Received query from: </b>
<!--                                       <span class="required">Required</span>-->
											</label>
                                        </div>
                                        <div class="col-xs-9"> 
											<?=
											$form->radioButtonListGroup($logModel, 'blg_query_from', array(
												'label'			 => '', 'widgetOptions'	 => array(
													'data' => BookingLog::model()->receivedQueryFrom,
												),
												'inline'		 => true,
													)
											);
											?>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3 mt10"> 
                                            <label for="delete"><b>Received query via: </b>
<!--                                                 <span class="required" >Required</span>-->
											</label>
                                        </div>
                                        <div class="col-xs-6"> 
											<?=
											$form->radioButtonListGroup($logModel, 'blg_query_via', array(
												'label'			 => '', 'widgetOptions'	 => array(
													'data' => BookingLog::model()->receivedQueryVia,
												),
												'inline'		 => true,
													)
											);
											?>
                                        </div>
										<div class="col-xs-3 mt10">
											<input  plceholder="Titcket" class="form-control"  name="BookingLog[titcket_no]" id="BookingLog_titcket_no" type="text">
										</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-2  mt20"> 
                                            <label for="delete"><b>Add Remark : </b>
											</label>
                                        </div>
                                        <div class="col-xs-4"> 
											<?= $form->textAreaGroup($logModel, 'blg_desc', array('label' => '', 'rows' => 10, 'cols' => 50)) ?>
                                        </div>
                                        <div class="col-xs-6 mt20"> 
											<div class="Submit-button" >
												<?php echo CHtml::submitButton('Add to booking log', array('class' => 'btn btn-warning', 'onclick' => 'validateEscalation()')); ?>

												<span class="validationMsg" id="savedlog">Saved to booking log</span>
												<input type="hidden" name="remarkSubmit" id="remarkSubmit">
											</div>
                                        </div>
                                    </div>

                                </div>
                                <!------------------------------------------>
								<?php $this->endWidget(); ?>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

 
 
        $('#BookingTrail_btr_nmi_flag_var').attr('disabled', 'disabled');
        $('#BookingTrail_btr_escalation_level').change(function () {
            var id = $('#BookingTrail_btr_escalation_level').val();
            $href = "<?= Yii::app()->createUrl('admin/booking/getEscalationDesc') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"lbl_id": id},
                success: function (data)
                {
                    $("#escalatedescErr").hide();
                    $("#escalatedesc").html(data);

                    if (id != 1 && id != 2)
                    {
                        $('#BookingTrail_btr_escalation_assigned_team').select2("val", 0);
                    } else {
                        $('#BookingTrail_btr_escalation_assigned_team').select2("val", 0);
                    }
                }
            });
        });
        $('#BookingTrail_btr_escalation_assigned_lead').change(function () {

            $("#escalateLeadErr").hide();
        });

        $("#savedlog").hide();
        $("#remarkSubmit").val(0);
        $("#remarkVendorDriverSubmit").val(0);
        $('#BookingLog_titcket_no').attr("readonly", "readonly");


      

        $("#BookingLog_reasondesc_investigation").hide();
        $("#BookingLog_reasondesc_other").hide();

        $("#BookingLog_blg_reason_view_2").click(function () {
            $("#BookingLog_reasondesc_investigation").hide();
            $("#BookingLog_reasondesc_other").show();
        });

        $("#BookingLog_blg_reason_view_1").click(function () {
            $("#BookingLog_reasondesc_investigation").show();
            $("#BookingLog_reasondesc_other").hide();
        });
        $("#BookingLog_blg_reason_view_0").click(function () {
            $("#BookingLog_reasondesc_investigation").hide();
            $("#BookingLog_reasondesc_other").hide();
        });



        $('#BookingLog_blg_reason_view_0').change(function () {
            $('#BookingLog_blg_reason_text').attr("readonly", "readonly");
        });

        $('#BookingLog_blg_reason_view_1').change(function () {
            $('#BookingLog_blg_reason_text').removeAttr("readonly");
            $("#BookingLog_blg_reason_text").attr("placeholder", "Investigating Issue").placeholder();
        });

        $('#BookingLog_blg_reason_view_2').change(function () {
            $('#BookingLog_blg_reason_text').removeAttr("readonly");
            $("#BookingLog_blg_reason_text").attr("placeholder", "Reason").placeholder();
        });

        $("#BookingLog_blg_query_via_1").click(function () {
            $('#BookingLog_titcket_no').removeAttr("readonly");
            $("#BookingLog_titcket_no").attr("placeholder", "Support ticket no").placeholder();
        });

        $("#BookingLog_blg_query_via_0").click(function () {
            $('#BookingLog_titcket_no').attr("readonly", "readonly");
        });

        $("#BookingLog_blg_query_via_2").click(function () {
            $('#BookingLog_titcket_no').attr("readonly", "readonly");
        });

        $("#BookingLog_blg_query_via_3").click(function () {
            $('#BookingLog_titcket_no').attr("readonly", "readonly");
        });
        $("#BookingLog_blg_query_via_4").click(function () {
            $('#BookingLog_titcket_no').attr("readonly", "readonly");
        });

        $('#BookingTrail_bkg_escalation_status').click(function () {
            var status = this.checked;
            if (status == true) {
                $("#escalate").show();
            } else {
                $('#BookingTrail_btr_escalation_level').select2("val", '');
                $('#BookingTrail_btr_escalation_assigned_lead').select2("val", '');
                $('#BookingTrail_btr_escalation_assigned_team').select2("val", '');
                $("#escalate").hide();

            }
        });
    });

    function sendToVendorDriver(booking_id)
    {
        var description = $("#BookingLog_blg_addl_desc").val();
        if (description == "")
        {
            alert('Please add instructions for driver/vendor.');
            return false;
        }
        $href = "<?= Yii::app()->createUrl('admin/booking/sendVendorDriver') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": booking_id, "description": description},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.success == true)
                {
                    $('#BookingLog_blg_addl_desc').val(" ");
                    $("#successText").text("Instruction sent to driver/vendor");
                    $("#remarkVendorDriverSubmit").val(1);
                }
            }
        });
    }
    function validateEscalation()
    {
        if ($('#BookingTrail_bkg_escalation_status').prop('checked'))
        {
            var escalation_level = $('#BookingTrail_btr_escalation_level').val();
            var escalation_teamLead = $('#BookingTrail_btr_escalation_assigned_lead').val();
            if (escalation_level == '')
            {
                $("#escalatedescErr").text("Choose escalation level");
                $("#escalatedescErr").css({"color": "yellow", "font-weight": "bold"});
                alert('Please Choose escalation level');
                return false;
            }
            if (escalation_teamLead == '')
            {
                $("#escalateLeadErr").text("Assign Team Lead");
                $("#escalateLeadErr").css({"color": "yellow", "font-weight": "bold"});
                alert('Please Assign Team Lead');
                return false;
            }
        }
        return true;
    }

   



   
                   
</script>