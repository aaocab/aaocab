  
<div class="panel ">    

	<div class="panel-body ">  
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'packagedescedit', 'enableClientValidation' => flase,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<?
		foreach ($detailModels as $dModel)
		{
			?>
			<hr>
			<input type="hidden"  name="PackageDetails[<?= $dModel['pcd_id'] ?>][pcd_id]" value="<?= $dModel['pcd_id'] ?>">

			<div class="row">
				<div class="col-xs-12  "> Day <?= $dModel['pcd_day_serial'] ?>: </div>
				<div class="col-xs-12 col-sm-5">From: <?= $dModel['pickup_city_name'] ?></div>
				<div class="col-xs-12 col-sm-5">To: <?= $dModel['drop_city_name'] ?></div> 
				<div class="col-xs-12 col-sm-10">
					<label class="control-label" >Description</label>
					<textarea class="form-control" placeholder="Description" 
							  name="PackageDetails[<?= $dModel['pcd_id'] ?>][pcd_description]" 
							  id="PackageDetails_pcd_description_<?= $dModel['pcd_id'] ?>"><?= $dModel['pcd_description'] ?> </textarea>
				</div>
			</div>
			<?
//			var_dump($dModel);
		}
		?>


		<div class="row">
			<div class="col-xs-12 text-center pb10 mr30 pt20">
				<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			</div>
		</div>

		<?php $this->endWidget(); ?>
	</div>
</div>
