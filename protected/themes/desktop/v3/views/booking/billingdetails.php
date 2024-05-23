
<?php
$model->bkgUserInfo->bkg_bill_country = ($model->bkgUserInfo->bkg_bill_country == '') ? Countries::model()->getCodeFromPhoneCode($model->bkgUserInfo->bkg_country_code) : $model->bkgUserInfo->bkg_bill_country;
if ($model->bkgUserInfo->bkg_bill_contact == '' && $model->bkgUserInfo->bkg_contact_no != '')
{
	$model->bkgUserInfo->bkg_bill_contact = $model->bkgUserInfo->bkg_contact_no;
}
if ($model->bkgUserInfo->bkg_bill_email == '' && $model->bkgUserInfo->bkg_user_email != '')
{
	$model->bkgUserInfo->bkg_bill_email = $model->bkgUserInfo->bkg_user_email;
}
$cityName = $model->bkgUserInfo->bkg_bill_city;
if ($model->bkgUserInfo->bkg_bill_city == '')
{
	$model->bkgUserInfo->bkg_bill_city = $model->bkgFromCity->cty_name;
}
if ($model->bkgUserInfo->bkg_bill_state == '')
{
	$model->bkgUserInfo->bkg_bill_state = $model->bkgFromCity->ctyState->stt_name;
}
if ($model->bkgUserInfo->bkg_bill_address == '')
{
	$model->bkgUserInfo->bkg_bill_address = $model->bkgFromCity->cty_name . ', ' . $model->bkgFromCity->ctyState->stt_name;
}
if ($model->bkgFromCity->cty_is_airport == 1 && $cityName == '')
{
	$model->bkgUserInfo->bkg_bill_city		 = '';
	$model->bkgUserInfo->bkg_bill_state		 = '';
	$model->bkgUserInfo->bkg_bill_address	 = '';
}
?>
<div style="display:none">

	<div class="row billinfo" id="cardInfo">
		<div class="col-12 font-18 text-uppercase"><b>Billing Information</b></div>

		<div class="col-12">
			<div class="row">
				<div class="col-sm-4 textwrap pr5">
					<label class="radio2-style mb0">
						<input type="radio" name="chkaddressopt" id="chkpkpaddLt"  onclick="copypickupadd()" checked="">  Same as pickup address
						<span class="checkmark-2"></span>
					</label>
				</div>
				<div class="col-sm-4 textwrap pr5">
					<label class="radio2-style mb0">
						<input type="radio" name="chkaddressopt" id="chkdrpaddLt"  onclick="copydropadd()"> Same as drop address
						<span class="checkmark-2"></span>
					</label>
				</div>
				<div class="col-sm-4 textwrap pr5">
					<label class="radio2-style mb0">
						<input type="radio" name="chkaddressopt" id="chkother"  onclick="otheradd()"> Other
						<span class="checkmark-2"></span>
					</label>
				</div>
			</div>
        </div>
		<div class="col-12 hide">
			<div class="row">
				<div class="col-sm-4 textwrap pr5">
					<label class="radio2-style mb0">
						<input type="radio" name="cardChk" id="cardChk1" value="ind" onchange="cardCountry(this)"> Cards issuing bank in India
						<span class="checkmark-2"></span>
					</label>
				</div>
				<div class="col-sm-4 textwrap pr5">
					<label class="radio2-style mb0">
						<input type="radio" name="cardChk" id="cardChk2" value="int" onchange="cardCountry(this)"> Cards issuing bank outside India
						<span class="checkmark-2"></span>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div class="row billinfo mb15 mt10" id="name_country_info">
			<div class="col-12 col-md-6 mt5">
				<div class="m0 form-group">
				<label class="control-label" for="BookingUser_bkg_bill_fullname">Full Name</label>
				<?= $form->textField($model->bkgUserInfo, 'bkg_bill_fullname', ['placeholder' => "Enter Name",'value' => trim($model->bkgUserInfo->getUsername()),'class' => 'form-control m0']) ?>  
			    <?php echo $form->error($model->bkgUserInfo,'bkg_bill_fullname',['class' => 'help-block error']);?>
               </div>
			</div>	
			<div class="col-12 col-md-6 mt5">
				<div class="form-group cityinput">
					<label class="control-label">Country </label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model->bkgUserInfo,
						'attribute'			 => 'bkg_bill_country',
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
				obj.setValue('{$model->bkgUserInfo->bkg_bill_country}');
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

					<span class="has-error"><? echo $form->error($model->bkgUserInfo, 'bkg_bill_country'); ?></span>
				</div>
			</div>	
		</div>
	</div>
	<div class="col-12">
		<div class="row billinfo mb15" id="contact_info">

			<div class="col-12 col-sm-6 mt5">
                <div class="m0 form-group">
				<label class="control-label" for="BookingUser_bkg_bill_contact">Contact Number</label>
				<?= $form->numberField($model->bkgUserInfo, 'bkg_bill_contact',['placeholder' => "Contact Number",'class' => 'form-control m0']) ?>
                <?php echo $form->error($model->bkgUserInfo,'bkg_bill_contact',['class'=> 'help-block error']);?> 
                </div>         
			</div>
			<div class="col-12 col-sm-6 mt5">
               <div class="m0 form-group">
				<label class="control-label" for="BookingUser_bkg_bill_email">Email Address</label>
				<?= $form->emailField($model->bkgUserInfo, 'bkg_bill_email', ['placeholder' => "Email Address",'class' => 'form-control m0']) ?>  
                <?php echo $form->error($model->bkgUserInfo,'bkg_bill_email',['class' => 'help-block error']);?>   
               </div>                  
			</div>
		</div>
	</div>
	
	<div class="col-12">
		<div class="row mb15 billinfo" id="address_zip">
			<div class="col-12 col-md-6 mt5">
               <div class="m0 form-group has-success">
				<label class="control-label" for="BookingUser_bkg_bill_address">Address</label>
				<?= $form->textArea($model->bkgUserInfo, 'bkg_bill_address', ['class' => "devClass form-control m0",'placeholder' => "Billing Address"]) ?> 
                <?php echo $form->error($model->bkgUserInfo, 'bkg_bill_address',['class' => 'help-block error']);?>  
               </div>
			</div>
		</div>
	</div>
</div>

