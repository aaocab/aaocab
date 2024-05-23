
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'followuplog', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){
                                                $.ajax({
                                                "type":"POST",
                                                "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/scq/reschedule')) . '",
                                                "data":form.serialize(),
                                                        "dataType": "json",
                                                        "success":function(data1){
                                                                if(data1.success)
                                                                {
                                                                      location.reload();
                                                                }
                                                        },
                                                });
                                            }
                                        }'
				),
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
			$form->errorSummary($followUps);
			?>
            <div class="panel-body">
				<div class="row mb10">
					<div class="col-xs-12 col-md-4 mt15">Date & Time:</div>
					<div class="col-xs-12 col-md-4">
						<? $followupdate	 = date('Y-m-d H:i:s', strtotime('+1 hour')); ?>
						<?=
						$form->datePickerGroup($followUps, 'locale_followup_date', array('label'			 => '',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date('Y-m-d H:i:s'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Reminder Date', 'value' => DateTimeFormat::DateTimeToDatePicker($followupdate))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>

					</div>
					<div class="col-xs-12 col-md-4"><?php
						echo $form->timePickerGroup($followUps, 'locale_followup_time', array('label'			 => '',
							'widgetOptions'	 => array('id' => CHtml::activeId($followUps, "locale_followup_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Reminder Time', 'value' => date('h:i A', strtotime($followupdate))))));
						?> </div>
				</div>
				<div class="row mb10">
					<div class="col-xs-12 col-md-4">Follow up by team:</div>
					<div class="col-xs-12 col-md-8"><?php
						$teamarr1	 = Teams::getByLive();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $followUps,
							'attribute'		 => 'scq_to_be_followed_up_by_id',
							'val'			 => explode(',', $followUps->scq_to_be_followed_up_by_id),
							'data'			 => $teamarr1,
							'htmlOptions'	 => array('style'			 => 'width:100%',
								'placeholder'	 => 'Select team(s)', 'id'			 => 'teamID')
						));
						?></div>
				</div>
				<div class="row mb10">
					<div class="col-xs-12 col-md-4">Follow up with:</div>
					<div class="col-xs-12 col-md-6"><?php
						$arr1		 = ServiceCallQueue::FollowupWithList;
						$followwith	 = ($followUps->scq_id != '') ? ($followUps->scq_to_be_followed_up_with_entity_type) : ($followUps->followupWith);
						if ($followUps->scq_to_be_followed_up_with_entity_type == 11)
						{
							$followwith = 6;
						}

						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $followUps,
							'attribute'		 => 'followupWith',
							'val'			 => explode(',', $followwith),
							'data'			 => $arr1,
							'htmlOptions'	 => array('style'			 => 'width:100%',
								'placeholder'	 => 'Follow up with')
						));
						?></div>
					<div class="col-xs-12 col-md-2">
						<div style="display: none" id="hasContact" >
							<a class="" id=""title="" style="" >
								<img src="/images/icon/mark_complete.png" style="cursor:pointer"></a>
						</div>
					</div>
				</div>

				<div class="row mb10">
					<div class="col-xs-12 col-md-4"></div>
					<div class="col-xs-12 col-md-8">

						<div style="display: none" id="noContact">
							<a class="" id=""title=" No contact found.Please change the contact person." style="" >
								<img src="/images/icon/customer_cancel.png" style="cursor:pointer"><span id="noContactText"></span></a>
						</div>
					</div>
				</div>

				<div class="row mb10">
					<div class="col-xs-12 col-md-4"><span id="heading" ></span>Followup instruction:</div>
					<div class="col-xs-12 col-md-8">	<?= $form->textAreaGroup($followUps, 'scq_creation_comments', array('label' => '', 'rows' => 10, 'cols' => 50)) ?></div>
				</div>
				<div class="row  mb10">


					<input name="ServiceCallQueue[scq_prev_or_originating_followup]" id="ServiceCallQueue_scq_prev_or_originating_followup" type="hidden" value="<?= $followUps->scq_id; ?>">
					<div class="col-xs-12 col-md-4"></div>
					<div class="col-xs-12 col-md-4" id="actfollowupBtn">

						<button class="btn btn-info full-width" type="submit"  name="Submit"><span id="heading1">Reschedule</span> Followup</button>	

					</div>
					<div class="col-xs-12 col-md-4"></div>



				</div>

            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
    $('#ServiceCallQueue_followupWith').change(function () {
        var type = $('#ServiceCallQueue_followupWith').val();
        if (type == 6) {
            $("#deactfollowupBtn").hide("slow");
            $("#actfollowupBtn").show();
            $("#noContactText").hide("slow");
            $("#noContact").hide("slow");
            return;
        }
        $href = "<?= Yii::app()->createUrl('admin/contact/existContact') ?>";
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"type": type, "bookingId": <?= $followUps->scq_related_bkg_id; ?>},
            success: function (data)
            {
                var obj = $.parseJSON(data);
                if (obj.success == true && obj.contactID != " ")
                {
                    $("#noContactText").text(" ");
                    $("#actfollowupBtn").show("slow");
                    $("#deactfollowupBtn").hide();
                    $("#hasContact").show("slow");
                    $("#noContact").hide();
                } else {
                    $("#noContactText").show("slow");
                    $("#noContactText").text(obj.msg + " not available for this booking");

                    $("#noContact").show("slow");
                    $("#hasContact").hide();
                    $("#deactfollowupBtn").show("slow");
                    $("#actfollowupBtn").hide();

                }
            }
        });

    });
</script>
