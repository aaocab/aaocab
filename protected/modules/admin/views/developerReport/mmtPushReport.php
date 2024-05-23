<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
	<div class="panel-body">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id' => 'mmtReports-form', 'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'errorCssClass' => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation' => false,
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => '',
		),
		));
		/* @var $form TbActiveForm */
		?>
	
		<div class="col-xs-12 col-sm-4 col-md-3">
			<?php
			if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$model->from_date)) {
				$original_date = $model->from_date;
				$timestamp = strtotime($original_date);
				$con_date = date("d/m/Y", $timestamp);
				$model->from_date =$con_date;
			}
			?>
			<?=
			$form->datePickerGroup($model, 'from_date', array('label'			 => 'Filter By Date',
				'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => '01/01/2021', 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Filter By Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
			?>  
		</div>
		 
		<div class="col-xs-12 col-sm-3 mt5"><br>
		<button class="btn btn-primary full-width" type="submit"  name="mmtSearch">Search</button>
		</div>
    <div class="col-xs-12">
		
		
		<table class="table table-bordered">
	<thead>
		<tr style="color: black;background: whitesmoke">
			<th class="text-center" >Event Type</th>
			<th class="text-center" >Request Count</th>
			<th class="text-center" >Success Count</th>
			<th class="text-center" >Failed Count</th>
			<th class="text-center" >Error Percentage</th>
		</tr>

	</thead>
	<tbody>
			<tr>
				<td class="text-center" >Cab Driver Update</td>
				<td class="text-center" ><?php echo $cabDriverUpdate[0][requestCount] != NULL ? $cabDriverUpdate[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $cabDriverUpdate[0][successCount]!= NULL ? $cabDriverUpdate[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $cabDriverUpdate[0][failedCount]!= NULL ? $cabDriverUpdate[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $cabDriverUpdate[0][errorPercent]!= NULL ? $cabDriverUpdate[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >Trip Start</td>
				<td class="text-center" ><?php echo $tripStart[0][requestCount] != NULL ? $tripStart[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $tripStart[0][successCount]!= NULL ? $tripStart[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $tripStart[0][failedCount]!= NULL ? $tripStart[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $tripStart[0][errorPercent]!= NULL ? $tripStart[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >Trip End</td>
				<td class="text-center" ><?php echo $tripEnd[0][requestCount] != NULL ? $tripEnd[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $tripEnd[0][successCount]!= NULL ? $tripEnd[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $tripEnd[0][failedCount]!= NULL ? $tripEnd[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $tripEnd[0][errorPercent]!= NULL ? $tripEnd[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >Left For Pick Up</td>
				<td class="text-center" ><?php echo $leftForPickUp[0][requestCount] != NULL ? $leftForPickUp[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $leftForPickUp[0][successCount]!= NULL ? $leftForPickUp[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $leftForPickUp[0][failedCount]!= NULL ? $leftForPickUp[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $leftForPickUp[0][errorPercent]!= NULL ? $leftForPickUp[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >Cab Driver Reassign</td>
				<td class="text-center" ><?php echo $cabDriverReassign[0][requestCount] != NULL ? $cabDriverReassign[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $cabDriverReassign[0][successCount]!= NULL ? $cabDriverReassign[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $cabDriverReassign[0][failedCount]!= NULL ? $cabDriverReassign[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $cabDriverReassign[0][errorPercent]!= NULL ? $cabDriverReassign[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >No Show</td>
				<td class="text-center" ><?php echo $noShow[0][requestCount] != NULL ? $noShow[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $noShow[0][successCount]!= NULL ? $noShow[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $noShow[0][failedCount]!= NULL ? $noShow[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $noShow[0][errorPercent]!= NULL ? $noShow[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >Arrived</td>
				<td class="text-center" ><?php echo $arrived[0][requestCount] != NULL ? $arrived[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $arrived[0][successCount]!= NULL ? $arrived[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $arrived[0][failedCount]!= NULL ? $arrived[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $arrived[0][errorPercent]!= NULL ? $arrived[0][errorPercent]:0 ; ?></td>
			</tr>
			<tr>
				<td class="text-center" >Update Last Location</td>
				<td class="text-center" ><?php echo $updateLastLocation[0][requestCount] != NULL ? $updateLastLocation[0][requestCount] : 0 ?></td>
				<td class="text-center" ><?php echo $updateLastLocation[0][successCount]!= NULL ? $updateLastLocation[0][successCount] :0 ?></td>
				<td class="text-center" ><?php echo $updateLastLocation[0][failedCount]!= NULL ? $updateLastLocation[0][failedCount] : 0 ?></td>
				<td class="text-center" ><?php echo $updateLastLocation[0][errorPercent]!= NULL ? $updateLastLocation[0][errorPercent]:0 ; ?></td>
			</tr>
	</tbody>
</table>
    </div>
	
	<?php $this->endWidget(); ?>
  </div>
	</div>
</div>
