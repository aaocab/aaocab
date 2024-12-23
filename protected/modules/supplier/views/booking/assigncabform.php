<?php Yii::app()->clientScript->registerPackage("webSupplier");
?>
<div class="app-content content">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
		<div class="content-body">
			<section id="dashboard-analytics">
				<div class="container">

				<div class="row">
				<div class="col-12 col-lg-6 col-xl-6 offset-lg-3 offset-xl-3 mob-view">
				<div class="card">
<div class="card-header pb10"><p class="mb0">Assign cab and driver for trip id: <b><?=$model->bcb_id?></b></p></div>
<div class="card-body">
				<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'assigncabform',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.9
						// See class documentation of CActiveForm for details on thi9s,
						// you need to use the performAjaxValidation()-method describ9ed there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array('class' => '',),
						'action'				 => Yii::app()->createUrl('supplier/booking/assigncab?id='.$model->bcb_id),
					));
					/* @var $form TbActiveForm */
					$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
						'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
						'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
						'openOnFocus'		 => true, 'preload'			 => false,
						'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
						'addPrecedence'		 => false,];

				
				?>
				<div class="row">
				<div class="col-12 text-center"  style="color:#F00;text-align: center">
				<?php
					$errMessage = "";
					foreach ($model->getErrors() as $attribute => $errors)
					{	
						foreach ($errors as $errVal)
						{
							$errVal = json_decode($errVal,true);
							if(is_string($errVal))
							{
								$errMessage .= $errVal;
							}
							foreach ($errVal as $Val)
							{
								$errMessage .= $Val[0].'<br>';
							}
						}
					}
 				echo $errMessage;
				?>
				</div>
				<div class="col-12">
						<div class="form-group">
							<label class="control-label">Select Cab</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bcb_cab_id',
								'val'			 => $model->bcb_cab_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($cabs), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Cab')
							));
							?>
						</div>
				</div>
				<div class="col-12">
						<div class="form-group">
							<label class="control-label">Select Driver</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bcb_driver_id',
								'val'			 => $model->bcb_driver_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($drivers), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Driver','id'=>'bcb_driver_id_id','onchange'=>"fetchPhone(this);")
							));
							?>
						</div>
				</div>
				<div class="col-12">
					<?= $form->textFieldGroup($model, 'bcb_driver_phone', array('label' => "Driver's phone", 'widgetOptions' => array())) ?>
				</div>
				<div class="col-12 text-center">
				<?php echo CHtml::submitButton('submit', array('class' => 'btn btn-primary btn-lg font-16')); ?>
				</div>	
				<?php $this->endWidget(); ?>
				</div>
				</div>
				</div>
</div>
</div>
</div>
			</section>
		</div>
	</div>
</div>
<script>
function fetchPhone(obj) 
{
	let id = $(obj).val();
    $.ajax({
			"type": "GET",
			"url": "<?= Yii::app()->createUrl('supplier/driver/driverphone')?>",
			'dataType': "html",
			"data": {'id':id},
			"success": function (data1) 
			{
				 debugger;
				 let data = JSON.parse(data1);
				if(data.phone)
				{
					$('#BookingCab_bcb_driver_phone').val(data.phone);
				}
			}
		});
}
</script>

