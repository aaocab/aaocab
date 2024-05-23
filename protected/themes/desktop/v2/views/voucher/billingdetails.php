<style type="text/css">
    .yii-selectize{  min-width: 100px;}
    .cityinput > .selectize-control>.selectize-input{
		width:100% !important;
    }
	.textwrap a{ white-space:normal !important;
				 word-wrap: break-word; }
</style>
<div>

	<div class="row billinfo" id="cardInfo">
		<div class="col-12 heading-part mb10">
			<b>Billing Information</b>
		</div>

	
		<div class="col-12 text-left mb10">

			<div class="row">
				<div class="col-sm-12 col-md-12 textwrap pr5">

					<input type="radio" name="cardChk" id="cardChk1" value="ind" onchange="cardCountry(this)"> Cards issuing bank in India                 
					<input type="radio" name="cardChk" id="cardChk2" value="int" onchange="cardCountry(this)"> Cards issuing bank outside India
				</div>
			</div>
		</div>
		
	</div>
	<div class="row billinfo" id="name_country_info">
		<div class="col-12 col-md-6 mt5">
		<div class="m0 form-group">
            <label class="control-label" for="VoucherOrder_vor_bill_fullname">Full Name</label>
			<?= $form->textField($model, 'vor_bill_fullname',['placeholder' => "Enter Name", 'value' => trim($model->vor_bill_fullname),'class' => 'form-control']) ?>  
            <?php echo $form->error($model,'vor_bill_fullname',['class' => 'help-block error']);?>
		</div>
        </div>	
		<div class="col-12 col-md-6 mt5 ">
			<div class="form-group cityinput">
				<label class="control-label">Country </label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vor_bill_country',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Country",
					'fullWidth'			 => false,
					'htmlOptions'		 => array(
					),
					'defaultOptions'	 => array(
						'create'			 => false,
						'persist'			 => true,
						'selectOnTab'		 => true,
						'createOnBlur'		 => true,
						'dropdownParent'	 => 'body',
						'optgroupValueField' => 'pcode',
						'optgroupLabelField' => 'pcode',
						'optgroupField'		 => 'pcode',
						'openOnFocus'		 => true,
						'labelField'		 => 'name',
						'valueField'		 => 'pcode',
						'searchField'		 => 'name',
						//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
						'closeAfterSelect'	 => true,
						'addPrecedence'		 => false,
						'onInitialize'		 => "js:function(){
							this.load(function(callback){
							var obj=this;                                
							xhr=$.ajax({
								url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/countryname')) . "',
								dataType:'json',        
								cache: true,
								success:function(results){
								obj.enable();
								callback(results.data);
								obj.setValue('{$model->vor_bill_country}');
								},                    
								error:function(){
								callback();
								}});
							});
							}",
										'render'			 => "js:{
							option: function(item, escape){  
							return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
							},
							option_create: function(data, escape){
							$('#countrycode').val(data.pcode);
							return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
							}
						}",
					),
				));
				?>

				<span class="has-error"><? echo $form->error($model, 'vor_bill_country'); ?></span>
			</div>
		</div>	
	</div>
	<div class="row billinfo" id="contact_info">

		<div class="col-12 col-sm-6 mt5">
		<div class="m0 form-group">
            <label class="control-label" for="VoucherOrder_vor_bill_contact">Contact Number</label>
			<?= $form->numberField($model, 'vor_bill_contact', ['placeholder' => "Contact Number",'class' => 'form-control']) ?> 
            <?php echo $form->error($model,'vor_bill_contact',['class' => 'help-block error']);?>  
        </div>                   
		</div>
		<div class="col-12 col-sm-6 mt5">
		<div class="m0 form-group">
            <label class="control-label" for="VoucherOrder_vor_bill_email">Email Address</label>
			<?= $form->emailField($model, 'vor_bill_email',  ['placeholder' => "Email Address",'class' => 'form-control']) ?> 
            <?php echo $form->error($model,'vor_bill_email',['class' => 'help-block error']);?>
        </div>                     
		</div>
	</div>
	<div class="row billinfo" id="city_state">
		<div class="col-12 col-sm-6 mt5">
        <div class="m0 form-group">
		    <label class="control-label" for="VoucherOrder_vor_bill_state">State</label>
			<?= $form->textField($model, 'vor_bill_state', ['class' => 'devClass form-control','placeholder' => "State"]) ?> 
            <?php echo $form->error($model,'vor_bill_state',['class' => 'help-block error']);?> 
        </div>
		</div>	
		<div class="col-12 col-sm-6 mt5">
        <div class="m0 form-group">
            <label class="control-label" for="VoucherOrder_vor_bill_city">City</label>
			<?= $form->textField($model, 'vor_bill_city', ['class' => 'devClass form-control','placeholder' => "City"]) ?>  
            <?php echo $form->error($model,'vor_bill_city',['class' => 'help-block error']);?>
		</div>
	    </div>
    </div>
	<div class="row billinfo" id="address_zip">
		<div class="col-12 col-md-6 mt5">
		<div class="m0 form-group">
		    <label class="control-label" for="VoucherOrder_vor_bill_address">Address</label>
			<?= $form->textArea($model, 'vor_bill_address', ['class' => "devClass form-control",'placeholder' => "Address"]) ?>  
            <?php echo $form->error($model,'vor_bill_address',['class' => 'help-block error']);?>
        </div>
		</div>
		<div class="col-12 col-sm-6 mt5">
        <div class="m0 form-group">
            <label class="control-label" for="VoucherOrder_vor_bill_postalcode">Postal Code</label>
            <?= $form->textField($model, 'vor_bill_postalcode',['class' => "devClass form-control",'placeholder' => "Postal Code"]) ?>
            <?php echo $form->error($model,'vor_bill_postalcode',['class' => 'help-block error']);?>  
        </div>
		</div> 
	</div>
	
	
</div>
 