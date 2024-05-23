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
		<div class="col-xs-12 heading-part mb10">
			<b>Billing Information</b>
		</div>

		
		<div class="col-xs-12 text-left mb10">

			<div class="row">
				<div class="col-sm-12 col-md-12 textwrap pr5">

					<input type="radio" name="cardChk" id="cardChk1" value="ind" onchange="cardCountry(this)"> Cards issuing bank in India                 
					<input type="radio" name="cardChk" id="cardChk2" value="int" onchange="cardCountry(this)"> Cards issuing bank outside India
				</div>
			</div>
		</div>
		
	</div>
	<div class="row billinfo" id="name_country_info">
		<div class="col-xs-12 col-md-6 mt5">
			<?= $form->textFieldGroup($model, 'vor_bill_fullname', array('label' => 'Full Name', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Enter Name", 'value' => trim($model->vor_bill_fullname)]), 'groupOptions' => ['class' => 'm0'])) ?>  
		</div>	
		<div class="col-xs-12 col-md-6 mt5 ">
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

		<div class="col-xs-12 col-sm-6 mt5">
			<?= $form->numberFieldGroup($model, 'vor_bill_contact', array('label' => 'Contact Number', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Contact Number"]), 'groupOptions' => ['class' => 'm0'])) ?>                      
		</div>
		<div class="col-xs-12 col-sm-6 mt5">
			<?= $form->emailFieldGroup($model, 'vor_bill_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address"]), 'groupOptions' => ['class' => 'm0'])) ?>                      
		</div>
	</div>
	<div class="row billinfo" id="city_state">
		<div class="col-xs-12 col-sm-6 mt5">
			<?= $form->textFieldGroup($model, 'vor_bill_state', array('label' => 'State', 'widgetOptions' => array('htmlOptions' => ['class' => "devClass"], ['placeholder' => "State"]), 'groupOptions' => ['class' => 'm0'])) ?>  
		</div>	
		<div class="col-xs-12 col-sm-6 mt5">
			<?= $form->textFieldGroup($model, 'vor_bill_city', array('label' => 'City', 'widgetOptions' => array('htmlOptions' => ['class' => "devClass"], ['placeholder' => "City"]), 'groupOptions' => ['class' => 'm0'])) ?>  
		</div>
	</div>
	<div class="row billinfo" id="address_zip">
		<div class="col-xs-12 col-md-6 mt5">
			<?= $form->textAreaGroup($model, 'vor_bill_address', array('label' => 'Address', 'widgetOptions' => array('htmlOptions' => ['class' => "devClass"], ['placeholder' => "Address"]), 'groupOptions' => ['class' => 'm0'])) ?>  
		</div>
		<div class="col-xs-12 col-sm-6 mt5">
			<?= $form->textFieldGroup($model, 'vor_bill_postalcode', array('label' => 'Postal Code', 'widgetOptions' => array('htmlOptions' => ['class' => "devClass"], ['placeholder' => "Postal Code"]), 'groupOptions' => ['class' => 'm0'])) ?>  
		</div> 
	</div>
	<div class="row billinfo hide">
		<div class="col-xs-12 col-md-6 mt5">
			<label>Bank Code</label>
			<?php
			$bankCodeArr1	 = Lookup::getBankList();
			?>
			<?= $form->dropDownList($model, 'vor_bill_bankcode', $bankCodeArr1, ['class' => 'form-control m0', 'required']); ?>
			<?php echo $form->error($model, 'vor_bill_bankcode', ['class' => 'help-block error']); ?>
		</div>
	</div>
	
</div>
 