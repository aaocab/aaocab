
<?php
$detect		 = Yii::app()->mobileDetect;
// call methodss
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
$isredirct	 = true;
?>

   
    <div class="row">
        <div class="col-12">
			<?php echo CHtml::errorSummary($model); ?>
        </div>
        <div class="col-12 text-center">
			<?php if (Yii::app()->user->hasFlash('success')): ?>
				<div class="alert alert-success" style="padding: 10px">
					<?php echo Yii::app()->user->getFlash('success'); ?>
				</div>
			<?php endif; ?>
        </div>
    </div>
   <div>
	   <?php
			if (!$isMobile)
			{
				$this->renderPartial("summaryOrders", ["model" => $model, 'isredirct' => $isredirct], false);
			}
			?>
	
        </div>


