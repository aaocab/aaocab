<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row m0">
    <div class="col-xs-12"> 
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="row"> 
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'lead-performance-form', 'enableClientValidation' => true,
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

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?= $form->datePickerGroup($model, 'bkg_from_date', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?></div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?=
						$form->datePickerGroup($model, 'bkg_to_date', array('label'			 => 'To Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>  
                    </div>

                    <div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
                <div class="row" style="margin-top: 10px">  
					<div class="col-xs-12 col-sm-7 col-md-5">       
                        <table class="table table-bordered">
                            <thead>
                                <tr style="color: black;background: whitesmoke">
                                    <th><u>Status</u></th>
                                    <th><u>North</u></th>
                                    <th><u>South</u></th>
                                    <th><u>West</u></th>
									<th><u>East</u></th>
									<th><u>Central</u></th>
									<th><u>North-East</u></th>
                                </tr>
                            </thead>
                            <tbody id="count_booking_row">                         

								<?php
									foreach($countReports as $countReport)
									{
									?>
									
									<tr>
										<td><?= $countReport['status'] ?></td>
										<td><?= $countReport['north'] ?></td>
										<td><?= $countReport['south'] + (isset($countReport['kerela']) ? $countReport['kerela'] : 0) ?></td>
										<td><?= $countReport['west'] ?></td>
										<td><?= $countReport['east'] ?></td>
										<td><?= $countReport['central'] ?></td>
										<td><?= $countReport['northeast'] ?></td>
									</tr>
								<?php } ?>
                            </tbody>
                        </table>
                    </div>
				</div>
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//    'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'name', 'value' => '$data[name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Admin Name'),
							array('name' => 'totQuote', 'value' => '$data[totQuote]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Quote Created'),
							array('name' => 'qtUn', 'value' => '$data[qtUn]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Quotes converted to Unverified'),
							array('name' => 'qtNew', 'value' => '$data[qtNew]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Quotes converted to New '),
							//array('name' => 'minutesOnPhone', 'value' => '$data[minutesOnPhone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Minutes on Phone'),
							array('name' => 'totBook', 'value' => '$data[totBook]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking Created'),
							array('name' => 'activeBooking', 'value' => '$data[activeBooking]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Active Booking Count'),
							array('name' => 'srvBook', 'value' => '$data[srvBook]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Served Count'),
							array('name' => 'marginPercent', 'value' => '$data[marginPercent]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Margin'),
							//array('name' => 'incomingCalls', 'value' => '$data[incomingCalls]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Incoming Calls'),
							array('name' => 'uniqueLead', 'value' => '$data[uniqueLead]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Outbound Followup(Leads)'),
							//array('name' => 'totalCallMade', 'value' => '$data[totalCallMade]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Call Made'),
							array('name' => 'totAmount', 'value' => '$data[totAmount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Total Amount'),
							array('name' => 'gozoAmount', 'value' => '$data[gozoAmount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Gozo Profit(Active Bookings)'),
							array('name' => 'served_gozo_amount', 'value' => '$data[servedGozoAmount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Gozo Profit(Already Served)'),
							array('name' => 'converRatio', 'value' => '$data[converRatio]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Conversion Ratio'),
							array('name' => 'qtRatio', 'value' => '$data[qtRatio]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Quote Ratio'),
							array('name' => 'newRatio', 'value' => '$data[newRatio]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'New Ratio'),
							//array('name' => 'remarks', 'value' => '$data[remarks]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Action Taken'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>



