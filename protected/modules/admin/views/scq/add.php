
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<?php
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'followuplog', 'enableClientValidation' => true,
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
			<?php
			echo
			$form->errorSummary($model);
			$followupdate		 = date('Y-m-d H:i:s', strtotime('+1 hour'));
			?>
            <div class="panel-body">
                <div class="row mt10" >

                    <div class="col-xs-12 col-md-4">
						<?php
						$eventList			 = FollowupType::getJSON(array('3' => "Followup Completed", '4' => "Followup Reschedule"), 1);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'event_id',
							'val'			 => $model->event_id,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($eventList), 'allowClear' => true),
							'htmlOptions'	 => array('class' => '', 'style' => 'width: 100%', 'placeholder' => 'Select Events')
						));
						?>
                    </div>

					<div class="col-xs-12 col-md-4" id="isfollowdt" style="display: none">
						<?=
						$form->datePickerGroup($model, 'locale_followup_date', array('label'			 => '',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date('Y-m-d H:i:s'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Reminder Date', 'value' => DateTimeFormat::DateTimeToDatePicker($followupdate))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>

                    </div>
                    <div class="col-xs-12 col-md-4"  id="isfollowtime" style="display: none">
						<?php
						echo $form->timePickerGroup($model, 'locale_followup_time', array('label'			 => '',
							'widgetOptions'	 => array('id' => CHtml::activeId($model, "locale_followup_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Reminder Time', 'value' => date('h:i A', strtotime($followupdate))))));
						?>
                    </div>
				</div>

				<div class="row mt10" >
					<div class="col-xs-12 col-md-6"  id="isfollowupWith" style="display: none">
						<?php
						$model->followupWith = 3;
						echo $form->radioButtonListGroup($model, 'followupWith', array('label' => '', 'widgetOptions' => array('data' => array(1 => ' Followup By Me', 2 => ' Followup By Team', 3 => ' No Change. Reschedule SR in same queue'), 'htmlOptions' => []), 'inline' => true));
						?>

						<div  id="gozoTeam" style="display: none"><?php
							$teamarr1			 = Teams::getByLive();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'followupWithTeam',
								'val'			 => explode(',', $model->followupWithTeam),
								'data'			 => $teamarr1,
								'htmlOptions'	 => array('style'			 => 'width:100%',
									'placeholder'	 => 'Select team(s)')
							));
							?></div>
                    </div>
					<div class="col-xs-12 col-md-4 ">
						<?php
						echo $form->textAreaGroup($model, 'scq_disposition_comments', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Summarize how you disposed/solved the call in 1-2 sentences. Do not just say Done or solved'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Summarize how you disposed/solved the call in 1-2 sentences. Do not just say Done or solved']]))
						?>
                    </div>
					<?php
					if ($flag == 1)
					{
						if ($scqModel->scq_follow_up_queue_type != 23)
						{
							?>
							<div class="col-xs-12 col-md-4 ">
								<?php
								echo $form->textAreaGroup($model, 'scq_notification', array('label'			 => '',
									'htmlOptions'	 => array('placeholder' => 'Please write the message to send notification'),
									'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Please write the message to send notification']]))
								?>
							</div>
							<?php
						}
						elseif ($scqModel->scq_follow_up_queue_type == 23)
						{
							?>
							<div class="col-xs-12 col-md-4 ">
								<?php
								echo $form->textAreaGroup($model, 'scq_notification', array('label'			 => '',
									'htmlOptions'	 => array('placeholder' => 'Your payment has been processed .It will be credited into your bank account within 1-2 business day'),
									'widgetOptions'	 => ['htmlOptions' => ['value' => 'Your payment has been processed .It will be credited into your bank account within 1-2 business day']]))
								?>
							</div
							<?php
						}
					}
					?>
                    <div class="col-xs-12 col-md-2 ">
                        <button class="btn btn-info full-width followups" type="button"  name="Submit">Submit</button>
                    </div>

                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#isfollowdt").hide("slow");
    $("#isfollowtime").hide("slow");
    $("#isfollowupWith").hide("slow");
    $('#ServiceCallQueue_event_id').change(function () {
        if ($('#ServiceCallQueue_event_id').val() != 4)
        {
            $("#isfollowdt").hide("slow");
            $("#isfollowtime").hide("slow");
            $("#isfollowupWith").hide("slow");
        } else
        {
            $("#isfollowdt").show("slow");
            $("#isfollowtime").show("slow");
            $("#isfollowupWith").show("slow");
        }

    });

    $('#ServiceCallQueue_followupWith_1').click(function () {
        $("#gozoTeam").show("slow");

    });

    $('#ServiceCallQueue_followupWith_0').click(function () {
        $("#gozoTeam").hide("slow");
    });

    $("#ServiceCallQueue_locale_followup_date").attr('readonly', 'readonly');
    $("#ServiceCallQueue_locale_followup_time").attr('readonly', 'readonly');
    $('.followups').click(function () {
        var eventId = $("#ServiceCallQueue_event_id").val();
        var remark = $("#ServiceCallQueue_scq_disposition_comments").val();
        var notification = $("#ServiceCallQueue_scq_notification").val();
        if (eventId == 0)
        {
            bootbox.alert("Please select event");
            return;
        }
        if (remark == "")
        {
            bootbox.alert("Please enter remark");
            return;
        }
        if (notification == "")
        {
            bootbox.alert("Please enter notification message");
            return;
        }
        $("#followuplog").submit();
    });
</script>

