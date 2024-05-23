 
<div class = "panel ">
	<div class = "  text-center h3 m0"><b><?= $resultset[0]['pck_name']; ?></b>
		<div class="col-xs-12 h5 text-center ">
			<?= $resultset[0]['pck_auto_name']; ?> 
		</div>
	</div>
	<div class = "panel-body ">
		<div class="row">
			<div class="col-xs-12 text-center h4">
				<b><?= $resultset[0]['pck_desc']; ?></b> 
			</div>
			<?php
			if ($toSubmit)
			{
				$form = $this->beginWidget('CActiveForm', array(
					'id'					 => 'book-package-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'action'				 => '/bknw',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));

				/* @var $form CActiveForm */
				$ptimePackage = Yii::app()->params['defaultPackagePickupTime'];

				$defaultDate = date("Y-m-d $ptimePackage", strtotime('+7 days'));
				$pdate		 = DateTimeFormat::DateTimeToDatePicker($defaultDate);
				$ptime		 = date('h:i A', strtotime($ptimePackage));
				?>
				<input type="hidden" id="step11" name="step" value="1">
				<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 5, 'id' => 'bkg_booking_type5']); ?>
				<?= $form->hiddenField($model, 'bktyp', ['value' => 5, 'id' => 'bktyp5']); ?>
				<?= $form->hiddenField($model, 'bkg_package_id', ['value' => $resultset[0]['pcd_pck_id']]); ?>  
				<?= $form->hiddenField($model, 'bkg_pickup_date_date', ['value' => $pdate]); ?>  
				<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' => $ptime]); ?>  


				<div class="col-xs-12   text-right">
					<div class="Submit-button " style="margin-top: 5px;"> <?php echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary')); ?> </div>
				</div>
			<? } ?>
			<div class = " pt5 text-justify">			
				<?
				foreach ($resultset as $pack)
				{
					?>
					<div class="col-xs-12 h4">
						<br>

						<div class="pb5">
							<b>Day  <?= $pack['pcd_day_serial'] ?>: <?= rtrim($pack['fcity'] . ', ' . $pack['pcd_from_location'], ', ') . " To " . rtrim($pack['tcity'] . ', ' . $pack['pcd_to_location'], ', '); ?></b>
						</div>
						<div class="row">
							<div class="col-xs-12 pl40"> <?= str_replace('\n', '<br>', $pack['pcd_description']); ?></div>
						</div>
					</div>
					<?
				}
				?>
			</div>
			<? if ($toSubmit)
			{
				?>

				<div class="col-xs-12 text-center  ">
					<div class="Submit-button " style="margin-top: 5px;"> <?php echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary')); ?> </div>

				</div>
				<?php
				$this->endWidget();
			}
			?>
		</div>
	</div>
</div>




