<div class="container">
    <div class="col-xs-12">        
        <div class="panel panel-default">
            <div class="panel-body">

				<?php
				$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'otpreport-form', 'enableClientValidation' => true,
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
				// @var $form TbActiveForm 
				?>
				<div class="row"> 


					<div class="col-xs-12 col-sm-2" style="">
                        <div class="form-group">
                            <label class="control-label">Entity Type</label>
							<?php
							$data	 = NotificationLog::model()->getJSONAllEntityType();

							$this->widget('booster.widgets.TbSelect2', array(
								'attribute'		 => 'ntl_entity_type',
								'model'			 => $model,
								'value'			 => $model->ntl_entity_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($data)),
								'htmlOptions'	 => array('id'			 => 'ntl_entity_types',
									'style'			 => 'width:100%', 'placeholder'	 => 'Select Entity Type')
							));
							?>
                        </div>
                    </div>

					<div class="col-xs-12 col-sm-2" id='entityId' style="display: none"> 
						<? //= $form->textFieldGroup($model, 'ntl_entity_id', array('widgetOptions' => ['htmlOptions' => []]))  ?>
						<div id="followVnd" style="display:none" class="entityGroup">
							<div class="form-group">
								<?php
								$vndmodel	 = new Vendors();
								echo $form->textFieldGroup($model, 'vndid', array('label' => "Vendor Id Or Code", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Vendor Id Or Code']]))
								?>

							</div>
						</div>

						<div id="followDrv" style="display:none" class="entityGroup">
							<div class="form-group">
								<?php
								$drvmodel	 = new Drivers();
								echo $form->textFieldGroup($model, 'drvid', array('label' => "Driver Id Or Code", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Driver Id Or Code']]))
								?>
							</div>
                        </div>

						<div id="followCust" style="display:none" class="entityGroup">
							<div class="form-group">
								<?php
								$usrmodel	 = new Users();
								echo $form->textFieldGroup($model, 'userid', array('label' => "Customer Id", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Customer Id']]))
								?>
							</div>
						</div>

						<div id="followAdm" style="display:none" class="entityGroup">
							<div class="form-group">
								<?php
								$admmodel	 = new Admins();
								echo $form->textFieldGroup($model, 'admid', array('label' => "Admin Id", 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Enter Admin Id']]))
								?>

							</div>
						</div>
                    </div>


					<div class="col-xs-12 col-sm-2" style="">
                        <div class="form-group">
                            <label class="control-label">Ref Type</label>
							<?php
							$data		 = NotificationLog::model()->getJSONAllRefType();

							$this->widget('booster.widgets.TbSelect2', array(
								'attribute'		 => 'ntl_ref_type',
								'model'			 => $model,
								'value'			 => $model->ntl_ref_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($data)),
								'htmlOptions'	 => array(
									'style'			 => 'width:100%', 'placeholder'	 => 'Select Ref Type')
							));
							?>
                        </div>
                    </div> 
                    <div class="col-xs-12 col-sm-2"> 
						<?= $form->textFieldGroup($model, 'ntl_ref_id', array('widgetOptions' => ['htmlOptions' => []])) ?>
                    </div>
					<div class="col-xs-12 col-md-4"  >
                        <div class="form-group">
							<label class="control-label">Event Code</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'attribute'		 => 'ntl_event_code',
								'model'			 => $model,
								'val'			 => $model->ntl_event_code,
								'data'			 => NotificationLog::eventList(),
								'htmlOptions'	 => array(
									'style'			 => 'width:100%', 'placeholder'	 => 'Select event ')
							));
							?>
						</div> 
                    </div>
				</div>
				<div class="row">
					<div class="col-xs-12 "> 
						<?= $form->textFieldGroup($model, 'ntl_title', array('widgetOptions' => ['htmlOptions' => []])) ?>
                    </div>
				</div>
				<div class="row">
					<div class="col-xs-12 "> 
						<?= $form->textAreaGroup($model, 'ntl_message', array('widgetOptions' => ['htmlOptions' => ['rows' => "6"]])) ?>
                    </div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-3 ">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
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
		 
		var type = $('#ntl_entity_types').val();
		if (type == 1)
		{
			$("#entityId").show("slow");
			$("#followCust").show("slow");
		}
		if (type == 2)
		{
			$("#entityId").show("slow");
			$("#followVnd").show("slow");
		}
		if (type == 3)
		{
			$("#entityId").show("slow");
			$("#followDrv").show("slow");
		}
		if (type == 4)
		{
			$("#entityId").show("slow");
			$("#followAdm").show("slow");
		}
	});

	$('#ntl_entity_types').change(function () {
		$("#entityId").show("slow");
		var type = $('#ntl_entity_types').val();
		if (type == 1)
		{
			 class="entityGroup">
			$("#followVnd").hide("slow");
			$("#followDrv").hide("slow");
			$("#followAdm").hide("slow");
			$("#followCust").show("slow");
		}
		if (type == 2)
		{
			$("#followVnd").show("slow");
			$("#followDrv").hide("slow");
			$("#followAdm").hide("slow");
			$("#followCust").hide("slow");
		}
		if (type == 3)
		{
			$("#followVnd").hide("slow");
			$("#followDrv").show("slow");
			$("#followAdm").hide("slow");
			$("#followCust").hide("slow");
		}
		if (type == 4)
		{
			$("#followVnd").hide("slow");
			$("#followDrv").hide("slow");
			$("#followAdm").show("slow");
			$("#followCust").hide("slow");
		}
	});
 
</script>