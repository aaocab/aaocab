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
<div class="container  ">
	<div class="row">
		<div class="col-12">  
			<div class="card">  
				<div  class="list-group-item list-group-item-action pl10"><a href="/operator/register"><i class="bx bx-chevrons-left float-left text-success "></i></a> Operator Type</div> 

				<div id="basicInfoPanel" class="card-body">
					<div class="formBody"><?php
						$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
						?>  <div class="row">
							<div class="col-sm-12">
								<div class=" row btn-group-toggle  " data-toggle="buttons">
									<div class="col-lg-6 col-sm-12  btn btn-dup-outline active  mb10">
										<input type="radio" name="isDCO" value="1" id="isDco_1" checked> 
										I am driver cum operator. I have a single cab.
									</div>
									<div class="col-lg-6 col-sm-12 btn btn-dup-outline mb10">
										<input type="radio" name="isDCO" value="2" id="isDco_2" > 
										I am a travel agent. I manage multiple cabs and drivers.
									</div>
								</div>
							</div>
						</div>
						<div class="" style="text-align: center">
							<?php
							echo CHtml::submitButton('Proceed', array('class' => 'btn btn-primary'));
							?>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>