<style>
    #Users_usr_email , #Users_usr_gender{
        border: 1px #434A54 solid;
    }
</style>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin:0;/* <-- Apparently some margin are still there even though it's hidden */
    }

</style>
<?php
$detect		 = Yii::app()->mobileDetect;
// call methodss
$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
$isredirct	 = true;
?>
<section>
   
    <div class="row">
        <div class="col-xs-12">
			<?php echo CHtml::errorSummary($model); ?>
        </div>
        <div class="col-xs-12 text-center">
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


</section>