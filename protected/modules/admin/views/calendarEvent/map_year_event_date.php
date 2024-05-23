<div class="panel panel-white"><div class="panel-body">
        <div class="row"> 
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'booking-form', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>
            <div class="col-xs-12 col-sm-4 col-md-3">


                <div class="form-group">
                    <label class="control-label">Select Year:</label>
                    <select  class="yearselect form-control" placeholder="Select Year" name="CalendarEvent[year]" id="CalendarEvent_year"></select>
                </div>


            </div>
            <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5">   
				<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>

            </div>
            <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5"> 
                <a  class="btn btn-primary full-width" href="/admpnl/CalendarEvent/Create" target="_blank">Create Calendar Event</a> 
            </div>
			<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5"> 
                <a  class="btn btn-primary full-width" href="/admpnl/CalendarEvent/90DCalendar" target="_blank">View 90 Day Calendar</a> 
            </div>
			<?php $this->endWidget(); ?>
        </div>
        <div class="row">
            <div class="col-xs-12" style="max-width: 100%; overflow: auto">
                <table class="table table-bordered responsive">
                    <thead>
                        <tr style="color: black;background: whitesmoke">
							<th class="text-center"><u>Event Id</u></th>
                            <th class="text-center"><u>Event Name</u></th>
                            <th class="text-center"><u>Event Type</u></th>                           
                            <th class="text-center"><u>Year -1</u></th>
                            <th class="text-center"><u>Select Year</u></th>
                            <th class="text-center"><u>Year +1</u></th>
							<th class="text-center"><u>Create By</u></th>
							<th class="text-center"><u>Approved By/Rejected By</u></th>
							<th class="text-center"><u>Status </u></th>
                        </tr>
                    </thead>
                    <tbody id="count_booking_row">
						<?php
						foreach ($dataProvider as $row)
						{
							$holidayId		 = $row['hde_id'];
							$prevYear		 = date('Y', strtotime('-1 years', strtotime($year . '-01-01')));
							$prevYearDetails = CalendarEvent::isEventExistForYear($row['hde_id'], $prevYear);
							$datesArr[0]	 = $prevYearDetails['cnt'] > 0 ? $prevYearDetails['cle_dt']."<br/><br/><a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>Map More</a>" : "<a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>NA</a>";

							$curYear		 = date('Y', strtotime($year . '-01-01'));
							$curYearDetails	 = CalendarEvent::isEventExistForYear($row['hde_id'], $curYear);
							$datesArr[1]	 = $curYearDetails['cnt'] > 0 ? "<a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>" . $curYearDetails['cle_dt'] . "</a>"."<br/><br/><a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>Map More</a>" : "<a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>NA</a>";

							$nextYear		 = date('Y', strtotime('+1 years', strtotime($year . '-01-01')));
							$nextYearDetails = CalendarEvent::isEventExistForYear($row['hde_id'], $nextYear);
							$datesArr[2]	 = $nextYearDetails['cnt'] > 0 ? $nextYearDetails['cle_dt']."<br/><br/><a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>Map More</a>" : "<a target='_blank' href='/admpnl/CalendarEvent/MapEvent?eventId=$holidayId'>NA</a>";
							?>
							<tr>
								<td class="text-center"><?= $holidayId ?></td>
								<td class="text-center"><a href="javascript:void()" style="text-decoration: none"  data-toggle="tooltip" data-placement="top" title="<?= $row['hde_description'] ?>"><?= $row['hde_name'] ?></a></td>
								<td class="text-center"><?= CalendarEvent::getEventType($row['hde_calendar_event_type']); ?></td>                                
								<td class="text-center" style="overflow-wrap: break-word;width: 200px;"><?= $datesArr[0] ?></td>
								<td class="text-center" style="overflow-wrap: break-word;width: 200px;"><?= $datesArr[1] ?></td>
								<td class="text-center" style="overflow-wrap: break-word;width: 200px;"><?= $datesArr[2] ?></td>
								<td class="text-center"><?= $row['hde_added_by_uid'] != null ? Admins::model()->findByPk($row['hde_added_by_uid'])->gozen : "System"; ?></td>                                
								<td class="text-center"><?= Admins::model()->findByPk($row['hde_approved_by_uid'])->gozen; ?></td> 
								<td class="text-center">
									<?php
									if ($row['hde_active'] == 1)
									{
										echo '<img src="/images/icon/activation.png" style="cursor:pointer">';
									}
									else if ($row['hde_active'] == 0)
									{
										echo '<img src="/images/icon/customer_cancel.png" style="cursor:pointer">';
									}
									else if ($row['hde_active'] == 2)
									{
										?>
										<a href="javascript:void(0)"  id="holiday_waiting_approval_<?= $holidayId; ?>" onclick="eventAction(<?= $holidayId; ?>, '1');" title="Approved" style=""><img src="/images/icon/unblock.png" style="cursor:pointer"></a>
										<a href="javascript:void(0)"  id="holiday_rejection_<?= $holidayId; ?>" onclick="eventAction(<?= $holidayId; ?>, '0');" title="Reject" style=""><img src="/images/icon/customer_cancel.png" style="cursor:pointer"></a>
											<?php
										}
										?>
								</td>
							</tr>
							<?php
						}
						?>
                    </tbody>
                </table>
            </div></div>
    </div></div>

<script>
    
    let dateDropdown = document.getElementById('CalendarEvent_year');
    let currentYear = 2024;
    let earliestYear = 2015;
    let year = '<?php echo $year ?>';
    while (currentYear >= earliestYear) {
        let dateOption = document.createElement('option');
        dateOption.text = currentYear;
        dateOption.value = currentYear;
        dateDropdown.add(dateOption);
        currentYear -= 1;
    }
    $("#CalendarEvent_year").val(year);
    function eventAction(holidayId, actionType)
    {
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/CalendarEvent/approvedEvent')) ?>",
            "data": {"holidayId": holidayId, "actionType": actionType, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            "success": function (data) {
                if (data.success)
                {
                    location.reload();
                } else
                {
                    bootbox.alert(data.message);
                }
            }
        });
    }

</script>