<style type="text/css">
    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?php
if ($error != '')
{
	?>  
	<div class="col-xs-12 text-danger text-center"><?= $error ?></div> 
	<?
}
else
{
	$carType	 = VehicleTypes::model()->getMasterCarType();
	$areatype	 = AreaPriceRule::model()->areatype;
	$area		 = 0;
	$dataCatType = PriceRule::model()->getDefaultJSON();
	?>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto">

			<div class="row">

				<div class="col-xs-12">
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'pricerule-manage-form', 'enableClientValidation' => TRUE,
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
							'class' => 'form-horizontal'
						),
					));
					/* @var $form TbActiveForm */
					?>
					<div class="panel panel-default">
						<div class="panel-body">
							<?php echo CHtml::errorSummary($model); ?>
							<?= $form->hiddenField($model, 'apr_area_id') ?>
							<?= $form->hiddenField($model, 'apr_area_type') ?>

							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 h5 mt20">
									<?= $desc ?>
								</div>

								<div class="col-xs-12 col-sm-6 col-md-6">
									<div class="form-group">
										<label class="control-label">Area Cab Type </label>
										<?
										$returnType					 = "list";
										$vehcleList					 = SvcClassVhcCat::getVctSvcList($returnType);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apr_cab_type',
											'val'			 => $model->apr_cab_type,
											//'asDropDownList' => FALSE,
											"data"			 => $vehcleList,
											//'options'		 => array('data' => new CJavaScriptExpression($vehcleList)),
											'htmlOptions'	 => array('style'			 => 'width:100%', 'placeholder'	 => 'Select Type',
												'class'			 => 'input-group')
										));
										?>
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col-xs-12 col-sm-6 col-md-6  "   >
									<div class="form-group">
										<label class="control-label">One Way</label>
										<?
										if ($model->apr_id > 0)
										{
											$dataCatType = PriceRule::model()->getDefaultJSON($model->apr_cab_type, 1);
										}
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apr_oneway_id',
											'val'			 => $model->apr_oneway_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataCatType)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type', 'class' => 'input-group')
										));
										?>
									</div>    
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6  "   >
									<div class="form-group">
										<label class="control-label">Return </label>

										<?
										if ($model->apr_id > 0)
										{
											$dataCatType = PriceRule::model()->getDefaultJSON($model->apr_cab_type, 2);
										}
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apr_return_id',
											'val'			 => $model->apr_return_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataCatType)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type', 'class' => 'input-group')
										));
										?>
									</div> 
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6  "  >
									<div class="form-group">
										<label class="control-label">Multi Trip </label>

										<?
										if ($model->apr_id > 0)
										{
											$dataCatType = PriceRule::model()->getDefaultJSON($model->apr_cab_type, 3);
										}
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apr_multitrip_id',
											'val'			 => $model->apr_multitrip_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataCatType)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type', 'class' => 'input-group')
										));
										?>
									</div> 
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6  "   >
									<div class="form-group">
										<label class="control-label">Airport </label>

										<?
										if ($model->apr_id > 0)
										{
											$dataCatType = PriceRule::model()->getDefaultJSON($model->apr_cab_type, 4);
										}
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'apr_airport_id',
											'val'			 => $model->apr_airport_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataCatType)),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type', 'class' => 'input-group')
										));
										?>
									</div> 
								</div></div>

						</div>

						<div class="panel-footer" style="text-align: center">
							<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary')); ?>
						</div>


					</div>
					<?php $this->endWidget(); ?>

				</div>
			</div> 
		</div>
	</div>
	<script type="text/javascript">

		$(document).ready(function ()
		{
		});
		$("#AreaPriceRule_apr_cab_type").change(function () {
			changeTripTypeData(1);
			changeTripTypeData(2);
			changeTripTypeData(3);
			changeTripTypeData(4);
		});

		function changeTripTypeData(triptype) {
			$cab = $("#AreaPriceRule_apr_cab_type").val();
			$href = '<?= Yii::app()->createUrl('admin/pricerule/filterdrop') ?>';
			jQuery.ajax({type: 'GET', "dataType": "json", url: $href, data: {"cab": $cab, "ttype": triptype},
				success: function (data1) {
					$data = data1;
					if (triptype == 1) {
						$('#<?= CHtml::activeId($model, "apr_oneway_id") ?>').select2({data: $data, multiple: false});
					}
					if (triptype == 2) {
						$('#<?= CHtml::activeId($model, "apr_return_id") ?>').select2({data: $data, multiple: false});
					}
					if (triptype == 3) {
						$('#<?= CHtml::activeId($model, "apr_multitrip_id") ?>').select2({data: $data, multiple: false});
					}
					if (triptype == 4) {
						$('#<?= CHtml::activeId($model, "apr_airport_id") ?>').select2({data: $data, multiple: false});
					}
				}
			});
		}

	</script>
<? }
?>
