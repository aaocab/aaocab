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


<div class="row"> 
	<?php
	$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'drvAppUsage-form', 'enableClientValidation' => true,
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

	  <div class="col-xs-3">
		<?= $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label' => 'Assignment Date :', 'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date', 'value' => ($model->bkg_pickup_date_date == '') ? DateTimeFormat::DateToDatePicker(date('Y-m-d')) :$model->bkg_pickup_date_date)), 'prepend' => '<i class="fa fa-calendar"></i>'));?>
      </div>
	  <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
	<?php $this->endWidget(); ?>
</div>	

<table class="table table-bordered">
	<thead>
		<tr style="color: black;background: whitesmoke">
			<th class="text-center"><u>Auto-Assigned</u> <?= "(" . round(((($bkgassigned[0]['total_auto_assigned_b2c'] + $bkgassigned[0]['total_auto_assigned_mmt'] + $bkgassigned[0]['total_auto_assigned_ibibo']) / ($bkgassigned[0]['total_assigned_b2c'] + $bkgassigned[0]['total_assigned_mmt'] + $bkgassigned[0]['total_assigned_ibibo'])) * 100), 1) . "%)" ?></th>
			<th class="text-center"><u>Manual-Assigned</u> <?= "(" . round(((($bkgassigned[0]['total_manual_assigned_b2c'] + $bkgassigned[0]['total_manual_assigned_mmt'] + $bkgassigned[0]['total_manual_assigned_ibibo']) / ($bkgassigned[0]['total_assigned_b2c'] + $bkgassigned[0]['total_assigned_mmt'] + $bkgassigned[0]['total_assigned_ibibo'])) * 100), 1) . "%)" ?></th>
			<th class="text-center"><u>Total Assigned</u> </th>									

		</tr>
	</thead>
	<tbody id="count_booking_row">                         
		<tr>
			<td class="text-center">
				<?= $bkgassigned[0]['total_auto_assigned'] ?><br>
				<?= "Profit: {$bkgassigned[0]['autoAssignProfit']}={$bkgassigned[0]['autoAssignProfitCount']}" ?><br>
				<?= "Loss: {$bkgassigned[0]['autoAssignLoss']}={$bkgassigned[0]['autoAssignLossCount']}" ?><br>
				<?= "B2C: " . $bkgassigned[0]['total_auto_assigned_b2c'] ?><br>
				<?= "B2B MMT: " . $bkgassigned[0]['total_auto_assigned_mmt'] ?><br>	
				<?= "B2B IBIBO: " . $bkgassigned[0]['total_auto_assigned_ibibo'] ?><br>	
				<?= "B2B OTHERS: " . $bkgassigned[0]['total_auto_assigned_b2bothers'] ?>
			</td>
			<td class="text-center"><?= $bkgassigned[0]['total_manual_assigned'] ?><br>
				<?= "Profit: {$bkgassigned[0]['manualAssignProfit']}={$bkgassigned[0]['manualAssignProfitCount']}" ?><br>
				<?= "Loss: {$bkgassigned[0]['manualAssignLoss']}={$bkgassigned[0]['manualAssignLossCount']}" ?><br>
				<?= "B2C: " . $bkgassigned[0]['total_manual_assigned_b2c'] ?><br>
				<?= "B2B MMT: " . $bkgassigned[0]['total_manual_assigned_mmt'] ?><br>
				<?= "B2B IBIBO: " . $bkgassigned[0]['total_manual_assigned_ibibo'] ?><br>	
				<?= "B2B OTHERS: " . $bkgassigned[0]['total_manual_assigned_b2bothers'] ?>
			</td>
			<td class="text-center"><?= $bkgassigned[0]['total_assigned'] ?><br>
				<?= "Profit: " . ($bkgassigned[0]['manualAssignProfit'] + $bkgassigned[0]['autoAssignProfit']) . "=" . ($bkgassigned[0]['manualAssignProfitCount'] + $bkgassigned[0]['autoAssignProfitCount']) . "" ?><br>
				<?= "Loss: " . ($bkgassigned[0]['manualAssignLoss'] + $bkgassigned[0]['autoAssignLoss']) . "=" . ($bkgassigned[0]['manualAssignLossCount'] + $bkgassigned[0]['autoAssignLossCount']) . "" ?><br>
				<?= "B2C: " . $bkgassigned[0]['total_assigned_b2c'] ?><br>
				<?= "B2B MMT: " . $bkgassigned[0]['total_assigned_mmt'] ?><br>
				<?= "B2B IBIBO: " . $bkgassigned[0]['total_assigned_ibibo'] ?><br>
				<?= "B2B OTHERS: " . $bkgassigned[0]['total_assigned_b2bothers'] ?>										
			</td>

		</tr>
	</tbody>
</table>

