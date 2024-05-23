<style type="text/css">
	.btn-dup-outline:active,
	.btn-dup-outline:focus,
	.btn-dup-outline:hover{
		background-color: #e6e6e6;
		color: #000;
	}
	.btn-dup-outline.active{
		background-color: #7cca7c;
		color: #fff;
	}
	.btn-dup-outline{
		border: 1px solid #ccc;
		-webkit-appearance: checkbox; /* Chrome, Safari, Opera */
		-moz-appearance: checkbox;    /* Firefox */
		-ms-appearance: checkbox; 
	}
</style>

<?php
$checked1	 = ($isDCO == 1 || $isDCO == '') ? "checked" : "";
$checked2	 = ($isDCO == 2 || $isDCO === 0) ? "checked" : "";
?>
<div id="optypePanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="optype" class="collapse" style="">
	 <div class="row">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i> Operator Type</div> 
		</a>
	</div>
	<div class="card card-body  ">

		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'operatorInfo_form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){ }'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => '/operator/register',
		));
		?>  
		<input type="hidden" name="formType" value="bi">
		<div class="col-sm-12">
			<div class=" row btn-group-toggle  " data-toggle="buttons">
				<div class="col-lg-6 col-sm-12  btn btn-dup-outline active  mb10">
					<input type="radio" name="isDCO" value="1" id="isDco_1" <?php echo $checked1 ?>> 
					I am driver cum operator. I have a single cab.
				</div>
				<div class="col-lg-6 col-sm-12 btn btn-dup-outline mb10">
					<input type="radio" name="isDCO" value="2" id="isDco_2" <?php echo $checked2 ?>> 
					I am a travel agent. I manage multiple cabs and drivers.
				</div>
			</div>
		</div>

		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
