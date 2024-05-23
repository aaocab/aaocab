<style>
	.billingCountryCode .item{
		line-height: 3.5 !important;
		padding-left: 5px !important;
	}
	.billingCountryCode input{
		width:50% !important;
	}
</style>



	<div class="content p0 bottom-0  billinfo" id="cardInfo">
		<div class="line-f3 mb10">	
		<b>Billing Information</b>
		</div>
		<div class="content p0 bottom-10 hide" id="cardAsk" style="display: block">
			<div class="mb10 textwrap">
				<div class="fac fac-radio-round fac-green"><span></span>
					<input type="radio" name="cardChk" id="cardChk1" value="ind" onchange="cardCountry(this)">
					<label for="cardChk1" id="cardChkl1">Cards issuing bank in India </label>
				</div>

			</div>
			<div class="mb10 textwrap">
				<div class="fac fac-radio-round fac-green"><span></span>
					<input type="radio" name="cardChk" id="cardChk2" value="int" onchange="cardCountry(this)">
					<label for="cardChk2" id="cardChkl2">Cards issuing bank outside India</label>
				</div>

			</div>
			<div class="line-f3 mb10">*In case of Credit Card, Please enter your Credit Card billing information.</div>
		</div>

	</div>



	<div class="content p0 bottom-0 billinfo" id="name_country_info">
		<div class="input-simple-1 has-icon input-blue bottom-15">
			<?= $form->labelEx($model,'vor_bill_fullname',['label' => 'Full Name']); ?>
			<?= $form->textField($model, 'vor_bill_fullname', ['placeholder' => "Enter Name", 'value' => trim($model->vor_bill_fullname)]) ?>  
			<?php echo $form->error($model, 'vor_bill_fullname',['class' => 'help-block error']); ?>
		</div>


			<div class="input-simple-2 has-icon input-green bottom-15">
				<label class="control-label">Country </label>
				<?php
				/*$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vor_bill_country',
					'useWithBootstrap'	 => false,
					"placeholder"		 => "Country",
					'fullWidth'			 => false,
					'htmlOptions'		 => array(
						'class' => 'billingCountryCode vor_BillingCountry'
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
				));*/
				?>
				<?= $form->textField($model, 'vor_bill_country', ['id'=>'voucher_bill_country_code']) ?>  
				<span class="has-error"><? echo $form->error($model, 'vor_bill_country'); ?></span>
			</div>
	</div>

	<div class="content p0 bottom-0 billinfo" id="contact_info">

			<div class="input-simple-1 has-icon input-blue bottom-15">
				<?= $form->numberField($model, 'vor_bill_contact', ['placeholder' => "Contact Number"]) ?>
				<?= 'Contact Number' ?>
				<?php echo $form->error($model, 'vor_bill_contact',['class' => 'help-block error']); ?>
			</div>


			<div class="input-simple-1 has-icon input-blue bottom-15">
				<?= $form->emailField($model, 'vor_bill_email', ['placeholder' => "Email Address"]) ?> 
				<?= 'Email Address' ?>
				<?php echo $form->error($model, 'vor_bill_email',['class' => 'help-block error']); ?>
			</div>


	</div>

	<div class="content p0 bottom-10">
			<div class="fac fac-radio-round fac-green"><span></span>
				<input id="chkpkpaddLt" type="radio" name="rad1" value="1" checked="checked" onclick="copypickupadd()">
				<label for="chkpkpaddLt">Same as pickup address</label>
			</div><br>
			<div class="fac fac-radio-round fac-green"><span></span>
				<input id="chkdrpaddLt" type="radio" name="rad1" value="1" onclick="copydropadd()">
				<label for="chkdrpaddLt">Same as drop address</label>
			</div><br>
			<div class="fac fac-radio-round fac-green"><span></span>
				<input id="chkotpaddLt" type="radio" name="rad1" value="1" onclick="otheradd()">
				<label for="chkotpaddLt">Other</label>
			</div>
			<div class="clear"></div>
	</div>

	<div class="content p0 bottom-0 billinfo" id="city_state">

			<div class="input-simple-1 has-icon input-blue bottom-20">
				<?= $form->textField($model, 'vor_bill_state',['placeholder' => "State", 'class' => "bill_statedata cardDtls"]) ?> 
				<?= 'State' ?>
				<?php echo $form->error($model, 'vor_bill_state',['class' => 'help-block error']); ?>
			</div>

			<div class="input-simple-1 has-icon input-blue bottom-20">	
				<?= $form->textField($model, 'vor_bill_city', ['placeholder' => "City", 'class' => "bill_citydata cardDtls"]) ?> 
				<?= 'City' ?>
				<?php echo $form->error($model, 'vor_bill_city',['class' => 'help-block error']); ?>
			</div>		
	</div>
	<div class="content p0 bottom-0 billinfo" id="address_zip">
			<div class="input-simple-1 textarea has-icon  bottom-20">
				<?= $form->textArea($model, 'vor_bill_address',['placeholder' => "Address", 'class' => 'textarea-simple-2 cardDtls']) ?> 
				<?= 'Address' ?>
				<?php echo $form->error($model, 'vor_bill_address',['class' => 'help-block error']); ?>
			</div>

			<div class="input-simple-1 textarea has-icon  bottom-20">	
				<?= $form->textField($model, 'vor_bill_postalcode',['placeholder' => "Postal Code", 'class' => 'cardDtls']) ?> 
				<?= 'Postal Code' ?>
				<?php echo $form->error($model, 'vor_bill_postalcode',['class' => 'help-block error']); ?>
			</div>	
			<div class="help-block error BookingUser_bkg_bill_postalcode_em_"   style="display:none;"></div>
	</div>
	<div class="content p0 bottom-0 hide">
		<div class="input-simple-1 input-blue has-icon  bottom-20">
			<label>Bank Code</label>
			<?php
				$bankCodeArr1 = Lookup::getBankList();			
			?>
			<?= $form->dropDownList($model, 'vor_bill_bankcode', $bankCodeArr1, ['class' => 'cardDtls']); ?>
			<?php echo $form->error($model, 'vor_bill_bankcode',['class' => 'help-block error']); ?>
		</div>
	</div>
	


