<?php
$form									 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookinggstninfo', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */


	$readOnly = ['readOnly' => 'readOnly'];
	$disabled	 = ['disabled' => 'disabled'];
	$hideclass = 'hide';
	if (in_array($model->bkg_status, [1,15]))
	{
		$readOnly = [];	
		$disabled = [];
		$hideclass = '';
	}
?>
<div class="card-body" style="<?php echo $show; ?>">
	<?php
	echo $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']);
	echo $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
	?>
	<!--<h5>Additional Details</h5>-->
	<div class="row">
		<div class="col-lg-9">

			<?php
			$model->bkgUserInfo->bkg_bill_country	 = ($model->bkgUserInfo->bkg_bill_country == '') ? Countries::model()->getCodeFromPhoneCode($model->bkgUserInfo->bkg_country_code) : Countries::getByName($model->bkgUserInfo->bkg_bill_country);
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
				$model->bkgUserInfo->bkg_bill_city = $model->bkgFromCity->cty_id;
			}
			else
			{
				$model->bkgUserInfo->bkg_bill_city = Cities::model()->getByCity($model->bkgUserInfo->bkg_bill_city)->cty_id;
			}
			if ($model->bkgUserInfo->bkg_bill_state == '')
			{
				$model->bkgUserInfo->bkg_bill_state = $model->bkgFromCity->ctyState->stt_id;
			}
			else
			{
				$model->bkgUserInfo->bkg_bill_state = States::getByName($model->bkgUserInfo->bkg_bill_state)->stt_id;
			}
			if ($model->bkgUserInfo->bkg_bill_address == '')
			{
				$model->bkgUserInfo->bkg_bill_address = ($model->bkg_pickup_address!='')?$model->bkg_pickup_address:$model->bkgFromCity->cty_name . ', ' . $model->bkgFromCity->ctyState->stt_name;
			}
			if ($model->bkgFromCity->cty_is_airport == 1 && $cityName == '')
			{
				$model->bkgUserInfo->bkg_bill_city		 = '';
				$model->bkgUserInfo->bkg_bill_state		 = '';
				$model->bkgUserInfo->bkg_bill_address	 = '';
			}
			?>
			<div class="col-12">
				<div class="row billinfo mb15 mt10" id="name_country_info">
					<div class="col-12 col-md-6 mt5">
						<div class="m0 form-group">
							<label class="control-label" for="BookingUser_bkg_bill_fullname">Full Name</label>
							<?= $form->textField($model->bkgUserInfo, 'bkg_bill_fullname', ['placeholder' => "Enter Name", 'value' => ($model->bkgUserInfo->bkg_bill_fullname!='')?trim($model->bkgUserInfo->bkg_bill_fullname):trim($model->bkgUserInfo->getUsername()), 'class' => 'form-control m0']+ $readOnly) ?>  
							<?php echo $form->error($model->bkgUserInfo, 'bkg_bill_fullname', ['class' => 'help-block error']); ?>
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
									'openOnFocus'		 => false,
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
						<div class="form-group">
							<label class="control-label">State </label>
							<?php
							//$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model->bkgUserInfo,
								'attribute'		 => 'bkg_bill_state',
								'val'			 => $model->bkgUserInfo->bkg_bill_state,
								//'asDropDownList' => FALSE,
								'data'			 => States::model()->getStateList1(),
								//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
								'htmlOptions'	 => array('class'			 => 'p0',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')+$disabled
							));
							?>
						</div>
					</div>
					<div class="col-12 col-sm-6 mt5">
						<div class="form-group">
							<label class="control-label">City</label>
							<?php
							$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false];
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model->bkgUserInfo,
								'attribute'			 => 'bkg_bill_city',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Source City",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Booking_fromcity1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->bkgUserInfo->bkg_bill_city}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>
							<span class="has-error"><? echo $form->error($model->bkgUserInfo, 'bkgUserInfo'); ?></span>

						</div>

					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="row billinfo mb15" id="contact_info">

					<div class="col-12 col-sm-6 mt5">
						<div class="m0 form-group">
							<label class="control-label" for="BookingUser_bkg_bill_contact">Contact Number</label>
							<?= $form->numberField($model->bkgUserInfo, 'bkg_bill_contact', ['placeholder' => "Contact Number", 'class' => 'form-control m0']+$readOnly) ?>
							<?php echo $form->error($model->bkgUserInfo, 'bkg_bill_contact', ['class' => 'help-block error']); ?> 
						</div>         
					</div>
					<div class="col-12 col-sm-6 mt5">
						<div class="m0 form-group">
							<label class="control-label" for="BookingUser_bkg_bill_email">Email Address</label>
							<?= $form->emailField($model->bkgUserInfo, 'bkg_bill_email', ['placeholder' => "Email Address", 'class' => 'form-control m0']+$readOnly) ?>  
							<?php echo $form->error($model->bkgUserInfo, 'bkg_bill_email', ['class' => 'help-block error']); ?>   
						</div>                  
					</div>
				</div>
			</div>

			<div class="col-12">
				<div class="row mb15 billinfo" id="address_zip">
					<div class="col-12 col-md-6 mt5">
						<div class="m0 form-group has-success">
							<label class="control-label" for="BookingUser_bkg_bill_address">Address</label>
							<?= $form->textArea($model->bkgUserInfo, 'bkg_bill_address', ['class' => "devClass form-control m0", 'placeholder' => "Billing Address"]+$readOnly) ?> 
							<?php echo $form->error($model->bkgUserInfo, 'bkg_bill_address', ['class' => 'help-block error']); ?>  
						</div>
					</div>
					<div class="col-12 col-md-6 mt5">
						<div class="m0 form-group has-success">
							<label for="inputEmail3" class="control-label text-right">Postal Code</label>
							<div style="width: 220px;"><?= $form->textField($model->bkgUserInfo, 'bkg_bill_postalcode', array('class' => "form-control",'placeholder' => 'Enter Postal Code')+$readOnly) ?></div>
						</div>
					</div>				
				</div>
			</div>
			
	<div class="col-12">
				<div class="row mb15 billinfo" id="address_zip">
					<div class="col-12 col-md-6 mt5">
						<div class="m0 form-group has-success">
							<label class="control-label" for="BookingUser_bkg_bill_address">GSTIN</label>
							<?= $form->textField($model->bkgUserInfo, 'bkg_bill_gst', ['class' => "devClass form-control m0", 'placeholder' => "Enter GST No. (Optional)"]+ $readOnly) ?> 
							<?php echo $form->error($model->bkgUserInfo, 'bkg_bill_gst', ['class' => 'help-block error']); ?>  
						</div>
					</div>
<!--					<div class="col-12 col-md-6 mt5">
						<div class="m0 form-group has-success">
							<label for="inputEmail3" class="control-label text-right">Pin Code</label>
							<?//= $form->textFieldGroup($model->bkgUserInfo, 'bkg_bill_postalcode', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Pin Code')))) ?>  
						</div>
					</div>				-->
				</div>
			</div>

		</div>

	</div>
</div>
<?php $this->endWidget(); ?>
<script>
    $sourceList = null;
    function populateSource(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback)
    {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }
</script>