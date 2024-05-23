<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">

        </div>

		<div class="panel panel-default">
			<div class="panel panel-heading text-center">Registration</div>
			<form class="form form-horizontal" method="POST">

				<div class="panel-body">
<input type="hidden"  name="YII_CSRF_TOKEN" />  
					<?
					foreach ($model as $att => $val)
					{
						?>
						<div class="row mb10">
							<div class="col-xs-6  "><label class="form-l " for="Pay_<?= $att ?>"><?= $att ?></label></div>
							<div class="col-xs-6  ">

								<input type="text" id="Pay_<?= $att ?>" name="Pay[<?= $att ?>]" value="<?= $val ?>"  required="required" placeholder="<?= $att ?>" class="form-control border-radius">
							</div>
						</div>
					<? } ?>
				</div>


				<div class="panel-footer" style="text-align: center">
					<?php echo CHtml::submitButton($isNew, array('class' => 'btn  btn-primary')); ?>
				</div>




			</form> 
		</div>

    </div>
</div>
<?php
$script = "$(document).ready(function(){
	$('input[name=YII_CSRF_TOKEN]').val('" . $this->renderDynamicDelay('Filter::getToken') . "');
});";
Yii::app()->clientScript->registerScript('updateYiiCSRF', $script, CClientScript::POS_END);
?>