<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>


<div class="row">
    <div class="col-xs-12">      
        <div class="panel panel-default">
            <div class="panel-body">
				<!----------------------------------------------------------------------------->
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
				<div class="row"> 


					<div class="col-xs-12 col-sm-8 col-md-8" >
						<div class="form-group">
							<label class="control-label">Teams</label>
							<?php
							$dataTeam	 = Teams::getList();
							unset($dataTeam[22]);
							unset($dataTeam[2]);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $bkgTrail,
								'attribute'		 => 'btr_escalation_assigned_team',
								'data'			 => $dataTeam,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Team(s)')
							));
							?>
						</div> 
					</div>
					<div class="col-xs-12 col-sm-3 col-md-2 text-center mt20 p5" ><?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
                </div>
				<?php $this->endWidget(); ?>
				<!------------------------------------------------------------------------------------------------------------->
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "
                            <div class='panel-heading'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                            </div></div>
                            <div class='panel-body'>{items}</div>
                            <div class='panel-footer'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                            </div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//       'ajaxType' => 'POST',
						'columns'			 => array(
							array('name'	 => 'bkg_booking_id', 'value'	 => function($data) {
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'BookingID'),
							array('name' => 'bkg_pickup_date', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'PIckup Date'),
							array('name'	 => 'bkg_status', 'value'	 => function($data) {
									$arr = Booking::model()->getActiveBookingStatus();
									echo $arr[$data['bkg_status']];
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking Status'),
							array('name' => 'trip_completion_time', 'value' => '$data[trip_completion_time]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Est. Completion time'),
							array('name'	 => 'btr_escalation_level', 'value'	 => function($data) {
									$elarr = BookingTrail::model()->escalation;
									echo $elarr[$data['btr_escalation_level']]['color'];
								},
								'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Escalation Level'),
							array('name'	 => 'btr_escalation_assigned_lead', 'value'	 => function($data) {
									$adminName = Admins::model()->getFullNameById($data['btr_escalation_assigned_lead']);
									echo $adminName;
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Name of Owner'),
							array('name'	 => 'escaltion_usr_id', 'value'	 => function($data) {
									$name = Admins::model()->getFullNameById($data['escaltion_usr_id']);
									echo $name;
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Escalation Raised By'),
							array('name'	 => 'btr_escalation_assigned_team',
								'value'	 => function($data) {
									$array = explode(',', $data['btr_escalation_assigned_team']);
									foreach ($array as $key => $value)
									{
										if ($value)
										{
											$team = Teams::getByID($value);
											echo $key + '1' . '.  ' . $team . "<br />";
										}
										else
										{
											echo "None";
										}
									}
								},
								'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-3'), 'header'			 => 'Teams Effected'),
							array('name' => 'btr_escalation_fdate', 'value' => '$data[btr_escalation_fdate]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'First Escalation set time'),
							array('name' => 'btr_escalation_ldate', 'value' => '$data[btr_escalation_ldate]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Last Escalation set time'),
					)));
				}
				?> 
			</div>  

		</div>  
	</div>

	<script>
        $(document).ready(function () {

            var start = '<?= date('1/m/Y'); ?>';
            var end = '<?= date('d/m/Y', strtotime('-1 month')); ?>';




            $('#bkgPickupDate').daterangepicker(
                    {
                        locale: {
                            format: 'DD/MM/YYYY',
                            cancelLabel: 'Clear'
                        },
                        "showDropdowns": true,
                        "alwaysShowCalendars": true,
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                            'Next 7 Days': [moment(), moment().add(6, 'days')],
                            'Next 15 Days': [moment(), moment().add(15, 'days')],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                        }
                    }, function (start1, end1) {
                $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
                $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
                $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
            });
            $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
                $('#bkgPickupDate span').html('Select Pickup Date Range');
                $('#Booking_bkg_pickup_date1').val('');
                $('#Booking_bkg_pickup_date2').val('');
            });
        });

	</script>
