<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$cityRadius = Yii::app()->params['airportCityRadius'];
?>
<style>
    .isd-input .selectize-input{
        min-width: 70px ;
        border-radius: 0;
    }
    .form-horizontal .checkbox-inline {
        padding-top: 0;
    }
    input[type="radio"]{
        margin-top: 0;
    }
	td, th {
        padding: 10px  !important ; 
    }
	.fb-btn{
		background: #3B5998;
		text-transform: uppercase;
		font-size: 14px;
		border: none;
		padding: 7px 8px;
		color: #fff;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		transition: all 0.5s ease-in-out 0s;
	}
	@media (max-width: 767px)
    {
	.modal-dialog{ margin-left: auto; margin-right: auto;}
	}
</style>
<div class="col-md-12">
	<?php
	if (Yii::app()->user->hasFlash('credits'))
	{
		?>
		<div class="flash-success">
			<div style="text-align: center;"><?php echo Yii::app()->user->getFlash('credits'); ?></div>
		</div>
	<?php } ?>


	<?php
	// refreshDetails(data2.booking);
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'bookingtime-form',
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){
					$.ajax({
						"type":"POST",

						"dataType":"html",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/route')) . '",
						"data":form.serialize(),
                        "beforeSend": function(){
                            ajaxindicatorstart("");
                        },
                        "complete": function(){
                            ajaxindicatorstop();
                        },
						"success":function(data2){
							var data = "";
							var isJSON = false;
							try {
							
								data = JSON.parse(data2);
								isJSON = true;
							} catch (e) {

							}
							if(!isJSON){
								openTab(data2,3);
								trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/rtview')) . '\');
								disableTab(3);
							}
							else
							{
								var errors = data.errors;
								
								msg =JSON.stringify(errors);
								if(errors)
								{
									
									var x = window.matchMedia("(max-width: 700px)");
									if (x.matches) 
									{
										var result = JSON.parse(msg);
										 for (k in result) {
											bootbox.alert({
												 message: result[k],
												 class: "",
												 callback: function () {
												 }
											})
										 }
									}
								}
								settings=form.data(\'settings\');
								$.each (settings.attributes, function (i) {
									$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
								});
								$.fn.yiiactiveform.updateSummary(form, errors);
								messages = errors;
								content = \'\';
								var summaryAttributes = [];
								for (var i in settings.attributes) {
									if (settings.attributes[i].summary) {
									
										summaryAttributes.push(settings.attributes[i].id);
									}
								}
								
								
								
								displayError(form, messages);
							}             
						},
						error: function (xhr, ajaxOptions, thrownError) 
						{
								alert(xhr.status);
								alert(thrownError);
						}
					});
				}
			}'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'			 => 'form-horizontal',
			'autocomplete'	 => 'off',
		),
	));
	/* @var $form TbActiveForm */
	$models	 = $model->bookingRoutes;
	array_push($models, $model);
	?>
	<?= $form->errorSummary($model);
	?>
	
	<?= $form->hiddenField($model, 'bkg_booking_type'); ?>
	<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id1', 'class' => 'clsBkgID']); ?>           
	<?= $form->hiddenField($model, 'hash', ['id' => 'hash1']); ?>     
	<?= $form->hiddenField($model, 'bkg_package_id', ['id' => 'bkg_package_id1']); ?>     
    <input type="hidden" id="step1" name="step" value="1">
    <div class="row">

		<?
		if (Yii::app()->user->isGuest)
		{
			$uname		 = '';
			$isLoggedin	 = false;
			?>
			<div class="col-xs-12 col-sm-5 col-md-5 float-left marginauto book-panel pb0" id="hideLogin">
				<div class="panel panel-default border-radius box-shadow1">
					<div class="panel-body p15">
						<h4 class="text-center mt0">Login below to get personalized offers</h4>
						<div class="col-xs-12 col-md-8 col-md-offset-2 fbook-btn mb10">
							<a class="btn btn-lg btn-social btn-facebook pl15 pr15" onclick="socailSigin('facebook')" ><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Login with Facebook</a>
						</div>

						<div class="col-xs-12 col-md-8 col-md-offset-2 google-btn">
							<a class="btn btn-lg btn-social btn-googleplus pl15 pr15"  onclick="socailSigin('google')" ><img src="../images/google_icon.png" alt="Gozocabs"> Login with Google</a>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-7 col-md-7 float-right marginauto book-panel pb0" id="hideDetails" >
				<div class="panel panel-default border-radius box-shadow1 box-style">
					<div class="panel-body p20 pb30">
						<span class="m0 h3 "><i class="fa fa-pencil-square-o"></i> 
							Your contact details </span>(these will be used to send you quotes and booking updates)
						<?
						if ($model->bkg_booking_type == 4)
						{
							?>
							<div class="row">
								<div class="col-xs-11 col-sm-11 pl0" style="margin: auto; float: none;">
									<?= $form->radioButtonListGroup($model, 'bkg_transfer_type', array('widgetOptions' => array('data' => Booking::model()->transferTypes, 'htmlOptions' => ['onclick' => 'changeLabelTextobj(this)', 'onchange' => 'changeLabelTextobj(this)']), 'inline' => true)) ?>
								</div>
							</div>
						<? } ?>
						<div class="row m0">
							<div class="col-xs-12 col-sm-6 ptl0 pb0 pl0 pr5">
								<label class="control-label">Phone Number (incl. country code)</label>
								<div class="form-group">   
									<div class="col-xs-3 isd-input pr0">
										<?php
										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'bkg_country_code',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Code",
											'fullWidth'			 => false,
											'htmlOptions'		 => array(
												'id' => 'routePrimaryCountryCode'
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
												'labelField'		 => 'pcode',
												'valueField'		 => 'pcode',
												'searchField'		 => 'name',
												'closeAfterSelect'	 => true,
												'addPrecedence'		 => false,
												'onInitialize'		 => "js:function(){
                                this.load(function(callback){
                                var obj=this;                                
                                xhr=$.ajax({
                                    url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                    dataType:'json',        
                                    cache: true,
                                    success:function(results){
                                        obj.enable();
                                        callback(results.data);
                                        obj.setValue('{$model->bkg_country_code}');
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
                            return '<div>' +'<span class=\"'+data.pcode+'\">' + escape(data.pcode) + '</span></div>';
                            }
                            }",
											),
										));
										?>

									</div>
									<div class="col-xs-9 pl15">
										<?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
										<?= $form->error($model, 'bkg_country_code'); ?>
										<?= $form->error($model, 'bkg_contact_no'); ?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 pl20 pr20">      
								<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email1")]))) ?>                      
							</div>                
						</div>
					</div>
				</div>
			</div>


			<?
		}
		else
		{
			$isLoggedin	 = true;
			$uname		 = Yii::app()->user->loadUser()->usr_name;
			?>

			<div class="col-xs-12 col-sm-12 col-md-9 float-none marginauto book-panel pb0">
				<div class="panel panel-default border-radius box-shadow1 box-style">
					<div class="panel-body p20">
						<span class="m0 h3 "><i class="fa fa-pencil-square-o"></i> 
							Your contact details </span>(these will be used to send you quotes and booking updates)
						<?
						if ($model->bkg_booking_type == 4)
						{
							?>
							<div class="row">
								<div class="col-xs-11 col-sm-11 pl0" style="margin: auto; float: none;">
									<?= $form->radioButtonListGroup($model, 'bkg_transfer_type', array('widgetOptions' => array('data' => Booking::model()->transferTypes, 'htmlOptions' => ['onclick' => 'changeLabelTextobj(this)', 'onchange' => 'changeLabelTextobj(this)']), 'inline' => true)) ?>
								</div>
							</div>
						<? } ?>
						<div class="row m0">
							<div class="col-xs-12 col-sm-6 ptl0 pb0 pl0 pr5">
								<label class="control-label">Phone Number (incl. country code)</label>
								<div class="form-group">   
									<div class="col-xs-3 isd-input pr0">
										<?php
										$this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'bkg_country_code',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Code",
											'fullWidth'			 => false,
											'htmlOptions'		 => array(
												'id' => 'routePrimaryCountryCode'
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
												'labelField'		 => 'pcode',
												'valueField'		 => 'pcode',
												'searchField'		 => 'name',
												'closeAfterSelect'	 => true,
												'addPrecedence'		 => false,
												'onInitialize'		 => "js:function(){
                                this.load(function(callback){
                                var obj=this;                                
                                xhr=$.ajax({
                                    url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
                                    dataType:'json',        
                                    cache: true,
                                    success:function(results){
                                        obj.enable();
                                        callback(results.data);
                                        obj.setValue('{$model->bkg_country_code}');
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
                            return '<div>' +'<span class=\"'+data.pcode+'\">' + escape(data.pcode) + '</span></div>';
                            }
                            }",
											),
										));
										?>

									</div>
									<div class="col-xs-9 pl15">
										<?= $form->textField($model, 'bkg_contact_no', array('placeholder' => "Primary Mobile Number", 'class' => 'form-control')) ?>
										<?= $form->error($model, 'bkg_country_code'); ?>
										<?= $form->error($model, 'bkg_contact_no'); ?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 pl20 pr20">      
								<?= $form->emailFieldGroup($model, 'bkg_user_email', array('label' => 'Email Address', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Email Address", 'id' => CHtml::activeId($model, "bkg_user_email1")]))) ?>                      
							</div>                
						</div>
					</div>
				</div>
			</div>


		<? } ?>
    </div>

	<?
	if ($model->bkg_booking_type == 5)
	{
		$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
		?>
		<div class="row">
			<div class="col-xs-6 col-lg-4">
				<label>Starting Date</label>
				<?=
				$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
					'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
							'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
							'value'			 => $pdate, 'id'			 => 'BookingTemp_bkg_pickup_date_date5',
							'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
				?>
			</div>
			<div class="col-xs-6 col-lg-4">
				<label>Default Time</label>
				<div class="form-control"><?=$model->bkg_pickup_date_time?></div>
			</div>
		</div>
		<div class="h4">Package Route Info</div>
		<div class="table">
			<table class="table-bordered table-responsive" border="1"  width="100%" id="packagetb">
				<tr>
						<th>From</th>
						<th>To</th>						 
						<th>Pickup Date</th>
						<th>Distance (in Km)</th>
						<th>Duration (in Min)</th>
						<th>No of Days</th>
						<th>No of Nights</th>
					</tr>
				<?
				$diffdays	 = 0;
				$nightCount	 = 0;
				if ($model->preData != '')
				{
					$arrmulticitydata = $model->preData;
					foreach ($arrmulticitydata as $key => $value)
					{
						$nightCount = $nightCount + $value->nightcount;

						if ($key == 0)
						{
							$diffdays = 1;
						}
						else
						{
							$date1		 = new DateTime(date('Y-m-d', strtotime($arrmulticitydata[0]->date)));
							$date2		 = new DateTime(date('Y-m-d', strtotime($value->date)));
							$difference	 = $date1->diff($date2);
							$diffdays	 = ($difference->d + 1);
						}
						?>
					
						<tr class="packagerow" >
							<td id="fcitycreate_<?= $key ?>"><b><?= $value->pickup_city_name ?></b></td>
							<td id="tcitycreate_<?= $key ?>"><b><?= $value->drop_city_name ?> </b></td>
						 
							<td id="fdatecreate_<?= $key ?>"><?= DateTimeFormat::DateTimeToDatePicker($value->date) ?></td>
							<td id="fdistcreate_<?= $key ?>"><?= number_format($value->distance); ?></td>
							<td id="fduracreate_<?= $key ?>"><?= number_format($value->duration); ?></td>
							<td id="noOfDayCount_<?= $key ?>"><? echo $value->daycount; ?> </td>
							<td id="noOfNightCount_<?= $key ?>"><? echo $value->nightcount; ?> </td>
						</tr>
						<?
						$last_date = date('Y-m-d H:i:s', strtotime($value->date . '+ ' . $value->duration . ' minute'));
					}
				}
				?> 
			</table>
		</div>
		<?
	}
	else
	{
		?>
		<div class="row">
			<div class="col-xs-12">
				<?php
				$brtRoutes = $model->bookingRoutes;
				/* @var $model Booking */
				if ($model->bkg_id > 0)
				{
					//$brtRoutes = BookingRoute::model()->getAllByBkgid($model->bkg_id);
				}
				if (empty($brtRoutes))
				{
					$brtRoutes						 = [];
					$brtModel						 = BookingRoute::model();
					$defaultDate					 = date('Y-m-d H:i:s', strtotime('+7 days 6am'));
					$defaultRDate					 = date('Y-m-d H:i:s', strtotime('+8 days 10pm'));
					$mindate						 = date('Y-m-d', strtotime('+4 hours'));
					$brtModel->brt_min_date			 = $mindate;
					$brtModel->brt_from_city_id		 = $model->bkg_from_city_id;
					$tcity							 = $model->bkg_to_city_id;
//				$brtModel->brt_to_city_id = $model->bkg_to_city_id;
					$brtModel->brt_pickup_date_date	 = $model->bkg_pickup_date_date;
					$brtModel->brt_pickup_date_time	 = $model->bkg_pickup_date_time;
					$pdate							 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
					$ptime							 = ($brtModel->brt_pickup_date_time == '') ? date('h:i A', strtotime('6am')) : $brtModel->brt_pickup_date_time;
					$rdate							 = ($brtModel->brt_return_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultRDate) : $brtModel->brt_return_date_date;
					$rtime							 = ($brtModel->brt_return_date_time == '') ? date('h:i A', strtotime('10pm')) : $brtModel->brt_return_date_time;
					$brtModel->brt_pickup_date_date	 = $pdate;
					$brtModel->brt_pickup_date_time	 = $ptime;
					$brtModel->brt_return_date_date	 = $rdate;
					$brtModel->brt_return_date_time	 = $rtime;
					$brtRoutes[]					 = $brtModel;
				}
				$scity	 = '';
				$pcity	 = '';
				foreach ($brtRoutes as $brtRoute)
				{


					if ($oldRoute == null)
					{
						$oldRoute = BookingRoute::model();
					}
					$form->error($brtRoute, 'brt_from_city_id');
					$form->error($brtRoute, 'brt_to_city_id');
					$form->error($brtRoute, 'brt_pickup_date_date');
					$form->error($brtRoute, 'brt_pickup_date_time');
					$brtRoute->populateMinDate($oldRoute->brt_from_city_id, $oldRoute->brt_pickup_date_date, $oldRoute->brt_pickup_date_time);
					$this->renderPartial('addroute', ['model' => $brtRoute, 'sourceCity' => $oldRoute->brt_to_city_id, 'previousCity' => $oldRoute->brt_from_city_id, 'btype' => $model->bkg_booking_type, 'index' => 0], false, false);
					if ($model->bkg_booking_type > 0)
					{
						break;
					}
					$oldRoute = $brtRoute;
				}
				?>
				<span id='insertBefore'></span> 
			</div>
		</div>
		<?
		if ($model->bkg_booking_type == 3)
		{
			?>
			<div class="row float-right clsMulti" style="white-space: nowrap">
				<div class="col-xs-12">
					<a class="btn btn-primary addmoreField weight400 font-bold" id="fieldAfter" title="Add More">
						<i class="fa fa-plus"></i></a>
					<a class="btn btn-danger" id="fieldBefore" title="Remove" style="display: none"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<?
		}
	}
	?>

	<div class="col-xs-12 text-center mt10 mb10" >
		
		<?= CHtml::submitButton('NEXT', array('class' => 'btn next3-btn pl40 pr40', 'id' => 'btnSubmit')); ?>
		
	</div>
	<?php $this->endWidget(); ?>
</div>


<script>
	var count = $("INPUT.ctyDrop").length;
	socailTypeLogin = "";
	var btype;
	$(document).ready(function ()
	{
		btype = '<?= $model->bkg_booking_type ?>';

		callbackLogin = 'fillUserform';
		if(btype==1)
		{
			
			if($("#BookingTemp_bkg_contact_no").val()!="" && $("#BookingTemp_bkg_contact_no").val()!="" )
			{
				setTimeout(function () {
				$( "#btnSubmit" ).click();

				}, 200);
			}
		}
		var len = $("INPUT.ctyPickup").length;
		if (len > 1)
		{
			setTimeout(function () {
				//disableRows();
				//enableRows();
			}, 200);
		}

		$airportRadius = '<?= $cityRadius ?>';

		$('#<?= CHtml::activeId($model, "brt_pickup_date_time") ?>').val('<?= date('h:i A', strtotime('+4 hour')) ?>');
		//     populateData();
	});
	$('.nav-tabs a[href="#menu1"] span').text('<?= Booking::model()->getBookingType($model->bkg_booking_type) ?> TRIP');
	$('.nav-tabs li[id="l11"] a[href="#menu1"] span').html('<i class="<?= Booking::model()->getBookingTypeicon($model->bkg_booking_type) ?>"></i>');


	function populateDatarut()
	{


		$scity = $($("INPUT.ctyPickup")[count - 1]).val();
		$tcity = $($("INPUT.ctyDrop")[count - 1]);

		$tcity.select2('val', '').trigger("change");
		if ($scity !== "")
		{

			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getnearest')) ?>",
				"data": {"source": $scity},
				"async": false,
				"success": function (data1)
				{
					$data2 = data1;

					var placeholder = $tcity.attr('placeholder');
					$tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
							return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
						}});
				}
			});
		}
	}




	$('#fieldAfter').click(function () {


		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		count = len;

		var scity = $(elems[len - 1]).val();
		var pscity = $($("SELECT.ctyPickup")[len - 1]).val();
		if (len > 1)
		{
			var pscity = $($("SELECT.ctyDrop")[len - 2]).val();
		}
		var pdate = $($("INPUT.datePickup")[len - 1]).val();
		var ptime = $($("INPUT.timePickup")[len - 1]).val();
		messages = {};
		if (pdate == "") {
			messages["<?= CHtml::activeId($brtModel, "brt_pickup_date_date") ?>"] = [];
			messages["<?= CHtml::activeId($brtModel, "brt_pickup_date_date") ?>"].push("Please enter pickup date");
		}
		if (pscity == '')
		{
			messages["<?= CHtml::activeId($brtModel, "brt_from_city_id") ?>"] = [];
			messages["<?= CHtml::activeId($brtModel, "brt_from_city_id") ?>"].push("Please select source city");
		}
		if (scity == '')
		{
			messages["<?= CHtml::activeId($brtModel, "brt_to_city_id") ?>"] = [];
			messages["<?= CHtml::activeId($brtModel, "brt_to_city_id") ?>"].push("Please select your destination");
		}
		//  alert(JSON.stringify(messages));
		if (!displayError($("#bookingtime-form"), messages))
		{
			return false;
		}

		$.ajax({
			"type": "GET",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/addroute')) ?>",
			"data": {"scity": scity, "pscity": pscity, "pdate": pdate, "ptime": ptime, "index": count, "btype": btype},
			"async": false,
			"success": function (data1)
			{

				$('#fieldBefore').show();
				$("SELECT.ctyPickup").attr('readonly', true);
				$("SELECT.ctyDrop").attr('readonly', true);
				$("INPUT.datePickup").attr('readonly', true);
				$("INPUT.timePickup").attr('readonly', true);
				$("INPUT.datePickup").datepicker("remove");
				$("INPUT.timePickup").next("span").hide();
				$('#insertBefore').before(data1);
				$("SELECT.ctyPickup").attr('readonly', true);
				disableRows();
				changeDestination(scity, $dest_city, null);
			}
		});
	});

	$('#fieldBefore').click(function () {
		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		$($(".clsRoute")[len - 1]).remove();
		enableRows();
	});

	function disableRow(i) {
		$("SELECT.ctyDrop")[i].selectize.lock();
		$($("INPUT.datePickup")[i]).attr('readonly', true);
		$($("INPUT.timePickup")[i]).attr('readonly', true);
		$($("INPUT.datePickup")[i]).datepicker("remove");
		$($("INPUT.timePickup")[i]).next("span").hide();
	}

	function disableRows() {
		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		if (len > 1)
		{
			$("SELECT.ctyPickup")[0].selectize.lock();
			for (var i = 0; i < len - 1; i++)
			{
				disableRow(i);
			}
			// $("SELECT.ctyPickup")[len - 1].selectize.lock();
			$('#fieldBefore').show();
		}
	}
	function enableRow(i) {
		$("SELECT.ctyDrop")[i].selectize.unlock();
		$($("INPUT.datePickup")[i]).attr('readonly', false);
		$($("INPUT.timePickup")[i]).attr('readonly', false);
		$($("INPUT.datePickup")[i]).datepicker(
				{'autoclose': true, 'startDate': $($("INPUT.datePickup")[i]).attr("min"), 'format': 'dd/mm/yyyy', 'language': 'en'}
		);
		$($("INPUT.timePickup")[i]).next("span").show();
	}

	function enableRows() {
		var elems = $("SELECT.ctyDrop");
		var len = elems.length;
		if (len > 1)
		{
			enableRow(len - 1);
			$("SELECT.ctyPickup")[len - 1].selectize.lock();
		}
		else {
			$("SELECT.ctyPickup")[len - 1].selectize.unlock();
			$("SELECT.ctyDrop")[len - 1].selectize.unlock();
			$("INPUT.datePickup").attr('readonly', false);
			$("INPUT.timePickup").attr('readonly', false);
			$("INPUT.timePickup").next("span").show();
			var min = new Date($("INPUT.datePickup").attr('min'));
			$("INPUT.datePickup").datepicker(
					{'autoclose': true, 'startDate': min, 'format': 'dd/mm/yyyy', 'language': 'en'}
			);
			$('#fieldBefore').hide();
			return false;
		}
	}
	function displayError(form, messages) {
		settings = form.data('settings');
		content = "";
		for (var key in messages) {
			$.each(messages[key], function (j, message) {
				content = content + '<li>' + message + '</li>';
			});
		}
		$('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
		return (content == "");
	}
//////////
	function resetTransferSelects() {

		// $("INPUT.ctyPickup").select2('val', '').trigger("change");
		$($("INPUT.ctyPickup")[count - 1]).select2('val', '').trigger("change");
		$($("INPUT.ctyDrop")[count - 1]).select2('val', '').trigger("change");
		$($("INPUT.ctyPickup")[count - 1]).val('').change();
		$($("INPUT.ctyDrop")[count - 1]).val('').change();

		$($("INPUT.ctyPickup")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
				return "Please, first choose your destination airport.<br><i>If you need any help, please call now</i> (+91) 90518-77-000";
			}}, null, false);
		$($("INPUT.ctyDrop")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
				return "Please, first choose your pickup airport.<br><i>If you need any help, please call now</i> (+91) 90518-77-000";
			}}, null, false);


	}

	$('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').click(function () {

		resetTransferSelects();
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked')) {
			transferOpt1();
			// populateDataTrP();

		}
	});
	$('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').click(function () {

		resetTransferSelects();
		if ($('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked')) {
			transferOpt2();
			//populateDataTrD();
		}
	});
	function populateDataTrP() {

		$scity = $($("INPUT.ctyPickup")[count - 1]);
		$tcity = $($("INPUT.ctyDrop")[count - 1]);

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>",
			"success": function (data1)
			{
				$data2 = data1;
				var placeholder = $scity.attr('placeholder');
				$scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
						return "Can't find the source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
					}}).on('change', function (e)
				{
					populateDataTrOthersF();
				});

			}
		});
	}

	function populateDataTrD() {

		$scity = $($("INPUT.ctyPickup")[count - 1]);
		$tcity = $($("INPUT.ctyDrop")[count - 1]);

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>",
			"success": function (data1)
			{
				$data2 = data1;
				var placeholder = $tcity.attr('placeholder');
				$tcity.select2({data: $data2,
					placeholder: placeholder,
					formatNoMatches: function (term) {
						return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
					}}).on('change', function (e) {
					populateDataTrOthersT();
				});
			}
		});
	}
	function populateDataTrOthersF()
	{

		var $scityVal = $($("INPUT.ctyPickup")[count - 1]).val();
		var $fcityVal = $('<?= $model->bkg_from_city_id ?>');
		var $tcity = $($("INPUT.ctyDrop")[count - 1]);



		//  $tcity.select2('val', '').trigger("change");
		if (($scityVal > 0 || $fcityVal > 0) && $('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').is(':checked'))
		{
			$scityVal = ($scityVal > 0) ? $scityVal : $fcityVal;
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>",
				"data": {"source": $scityVal},
				"async": false,
				"success": function (data1)
				{
					$data2 = data1;
					var placeholder = $tcity.attr('placeholder');
					$tcity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
							return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
						}});
				}
			});
		}
	}


	function populateDataTrOthersT()
	{
		var $scity = $("INPUT.ctyPickup");
		var $dcityVal = $($("INPUT.ctyDrop")[count - 1]).val();
		var $tcityVal = $('<?= $model->bkg_to_city_id ?>');
		// $scity.select2('val', '').trigger("change");
		if (($tcityVal > 0 || $dcityVal > 0) && $('#<?= CHtml::activeId($model, "bkg_transfer_type_1") ?>').is(':checked'))
		{

			$tcityVal = ($dcityVal > 0) ? $dcityVal : $tcityVal;

			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>",
				"data": {"source": $tcityVal},
				"async": false,
				"success": function (data1)
				{
					$data2 = data1;
					var placeholder = $scity.attr('placeholder');
					$scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
							return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
						}});
				}
			});
		}
	}



	function transferFunctions() {
		if ('<?= $model->bkg_transfer_type ?>' > 0) {
			if ('<?= $model->bkg_transfer_type ?>' == 1) {
				alert('t1');
			}
			if ('<?= $model->bkg_transfer_type ?>' == 2) {
				alert('t2');
			}
		}
		else {
			alert('other');
			$scity = $("INPUT.ctyPickup");
			$tcity = $($("INPUT.ctyDrop")[count - 1]);
			toCity = [];
			var placeholder1 = $scity.attr('placeholder');
			var placeholder2 = $tcity.attr('placeholder');
			$scity.select2({data: function () {
					return {results: toCity};
				}, placeholder: placeholder1, formatNoMatches: function (term) {
					return "Can't find the Source?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
				}});
			$tcity.select2({data: [], placeholder: placeholder2, formatNoMatches: function (term) {
					return "Can't find the Destination?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
				}});
		}
	}
	function populateDataEmpty() {

		$scity = $($("INPUT.ctyPickup")[count - 1]);

		$.ajax({
			"type": "GET",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getempty')) ?>",
			"success": function (data1)
			{
				$data2 = data1;
				var placeholder = $tcity.attr('placeholder');
				$scity.select2({data: $data2, placeholder: placeholder, formatNoMatches: function (term) {
						return "Can't find the location?<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
					}});

			}
		});
	}


	function transferOpt1() {
		var pTrcity = $($("INPUT.ctyPickup")[count - 1]).val();
		var dTrcity = $($("INPUT.ctyDrop")[count - 1]).val();

		changeLabelText('1');
		if (pTrcity > 0) {
			setTimeout(function () {
				resetTransferSelects();
				populateDataTrP();
				setTimeout(function () {
					$($("INPUT.ctyPickup")[count - 1]).val(pTrcity).trigger('change');
					if (dTrcity > 0) {
						$($("INPUT.ctyDrop")[count - 1]).val(dTrcity).trigger('change');
					}

				}, 400);

			}, 400);

		}
		else {
			setTimeout(function () {
				resetTransferSelects();
				populateDataTrP();
				$("#dlabel11").text('Pickup Airport');
			}, 300);

		}
	}
	function transferOpt2() {
		if ($($("INPUT.ctyDrop")[count - 1]).val() != '' || '<?= $model->bkg_to_city_id ?>' != '') {
			var toCity = '<?= $model->bkg_to_city_id ?>';
			var fromCity = $($("INPUT.ctyPickup")[count - 1]).val();

			changeLabelText('2');
			setTimeout(function () {
				resetTransferSelects();
				populateDataTrD();
				setTimeout(function () {
					$($("INPUT.ctyDrop")[count - 1]).val(toCity).trigger('change');
					if (fromCity != '') {
						$($("INPUT.ctyPickup")[count - 1]).val(fromCity).trigger('change');
					}
				}, 400);
			}, 400);

		}
		else {

			setTimeout(function () {
				resetTransferSelects();
				populateDataTrD();
			}, 300);

		}
	}


	if ('<?= $model->bkg_booking_type ?>' == 4) {

		if ('<?= $model->bkg_transfer_type ?>' > 0) {


			if ('<?= $model->bkg_transfer_type ?>' == 1) {
				transferOpt1();

			}
			if ('<?= $model->bkg_transfer_type ?>' == 2) {
				transferOpt2();
			}
		}
		else {
			$('#<?= CHtml::activeId($model, "bkg_transfer_type_0") ?>').attr('checked', 'checked');
			transferOpt1();
//            setTimeout(function () {
//                resetTransferSelects();
//                $($("INPUT.ctyPickup")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
//                        return "Please choose your transfer type to proceed.<br><i>For help, Call now</i> (+91) 90518-77-000";
//                    }}, null, false);
//                $($("INPUT.ctyDrop")[count - 1]).select2({'data': [], formatNoMatches: function (term) {
//                        return "Please choose your transfer type to proceed.<br><i>For help, Call now</i> (+91) 90518-77-000";
//                    }}, null, false);
//            }, 500);
		}
	}
	else {
		$($("INPUT.ctyPickup")[count - 1]).change(function () {
			populateDatarut();
		});

	}
	function changeLabelTextobj(trobj) {
		var trval = trobj.value;
		changeLabelText(trval);
	}
	function changeLabelText(trvalue) {
		if (trvalue == '0') {
			$('#trslabel').text('Pickup Point');
			$('#trdlabel').text('Drop Point');
		}
		if (trvalue == '1') {
			$('#trslabel').text('Pickup Airport');
			$('#trdlabel').text('Drop Point');
		}
		if (trvalue == '2') {
			$('#trslabel').text('Pickup Point');
			$('#trdlabel').text('Destination Airport');
		}
	}

	function populateSource(obj, cityId) {
		$loadCityId = cityId;
		obj.load(function (callback) {
			var obj = this;
			if ($sourceList == null) {
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>',
					dataType: 'json',
					data: {
						city: cityId
					},
					//  async: false,
					success: function (results) {
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue($loadCityId);
					},
					error: function () {
						callback();
					}
				});
			}
			else {
				obj.enable();
				callback($sourceList);
				obj.setValue($loadCityId);
			}
		});
	}
	function loadSource(query, callback) {
		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			error: function () {
				callback();
			},
			success: function (res) {
				callback(res);
			}
		});
	}
	function changeDestination(value, obj, cityId) {
		$loadCityId = cityId;
		if (!value.length)
			return;
		obj.disable();
		obj.clearOptions();
		obj.load(function (callback) {
			//  xhr && xhr.abort();
			xhr = $.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
				dataType: 'json',
				success: function (results)
				{
					obj.enable();
					callback(results);
					obj.setValue($loadCityId);
				},
				error: function () {
					callback();
				}
			});
		});
	}

	function signinWithFB() {
		var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
		var fbWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

	}
	function signinWithGoogle() {
		var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
		var googleWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');

	}
	function updateLogin() {
		$href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
		jQuery.ajax({type: 'get', url: $href, "dataType": "json", success: function (data1)
			{
				if (data1.usr_mobile == "") {
					if (socailTypeLogin == "facebook") {
						socailTypeLogin = "";
						signinWithFB();
					}
					else {
						socailTypeLogin = "";
						signinWithGoogle();
					}
				}
				else {
					$('#userdiv').hide();
					$('#navbar_sign').html(data1.rNav);
					$('#hideLogin').hide();
					$('#hideDetails').removeClass('col-xs-12 col-sm-7 col-md-7 float-right marginauto book-panel pb0');
					$('#hideDetails').addClass('col-xs-12 col-sm-12 col-md-9 float-none marginauto book-panel pb0');
					fillUserform2(data1.userData);
					fillUserform13(data1.userData);
				}

			}
		});
	}
	function fillUserform2(data) {

		if ($('#BookingTemp_bkg_user_name').val() == '' && $('#BookingTemp_bkg_user_lname').val() == '')
		{
			$('#BookingTemp_bkg_user_name').val(data.usr_name);
			$('#BookingTemp_bkg_user_lname').val(data.usr_lname);
		}
		if (data['usr_mobile'] != '') {
			if ($('#BookingTemp_bkg_contact_no').val() == '') {
				$('#BookingTemp_bkg_contact_no').val(data.usr_mobile);
			}
			else if ($('#BookingTemp_bkg_contact_no').val() != '' && $('#BookingTemp_bkg_contact_no').val() != data.usr_mobile) {
				$('#BookingTemp_bkg_alternate_contact').val(data.usr_mobile);
			}
		}
		if (data.usr_email != '') {
			if ($('#BookingTemp_bkg_user_email1').val() == '') {
				$('#BookingTemp_bkg_user_email1').val(data.usr_email);
			}
			if ($('#BookingTemp_bkg_user_email2').val() == '') {
				$('#BookingTemp_bkg_user_email2').val(data.usr_email);
			}
		}

	}
	function fillUserform13(data) {
		if ($('#Booking_bkg_user_name').val() == '' && $('#Booking_bkg_user_lname').val() == '')
		{
			$('#Booking_bkg_user_name').val(data.usr_name);
			$('#Booking_bkg_user_lname').val(data.usr_lname);
		}
		if (data.usr_mobile != '') {
			if ($('#Booking_bkg_contact_no').val() == '') {
				$('#Booking_bkg_contact_no').val(data.usr_mobile);
			}
			else if ($('#Booking_bkg_contact_no').val() != '' && $('#Booking_bkg_contact_no').val() != data.usr_mobile) {
				$('#Booking_bkg_alternate_contact').val(data.usr_mobile);
			}
		}
		if (data.usr_email != '') {
			if ($('#Booking_bkg_user_email1').val() == '') {
				$('#Booking_bkg_user_email1').val(data.usr_email);
			}
			if ($('#Booking_bkg_user_email2').val() == '') {
				$('#Booking_bkg_user_email2').val(data.usr_email);
			}
		}


	}

	function socailSigin(socailSigin)
	{
		socailTypeLogin = socailSigin;
		var href2 = "<?= Yii::app()->createUrl('users/partialsignin') ?>";
		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data) {
				if (data.search("You are already logged in") == -1) {
					if (socailSigin == "facebook") {
						signinWithFB();
					}
					else {
						signinWithGoogle();
					}

				}
				else {
					var box = bootbox.dialog({message: data, size: 'large',
						onEscape: function () {
							updateLogin();
						}
					});
				}
			}
		});
		return false;
	}


	$("#BookingTemp_bkg_pickup_date_date5").change(function ()
	{
<?php
if ($model->bkg_booking_type == 5)
{
	?>
			assignPackageDt();
<? } ?>

	});



	function assignPackageDt()
	{


		// alert (currentDate);
		var date = $('#BookingTemp_bkg_pickup_date_date5').val();
		var pckageID = $("#bkg_package_id1").val();
		 
		$href = '<?= Yii::app()->createUrl('booking/getPackageDetail') ?>';
		jQuery.ajax({
			type: 'GET',
			url: $href,
			dataType: 'json',
			data: {"pckageID": pckageID, "pickupDt": date},
			success: function (data)
			{
				 
				var packageDel = data.multijsondata;
				 
//				var html = "";
//				var upTb = '<table class="table-bordered11" border="1" cellpadding="10" width="100%" id="packagetb">\n\
//                    <thead><tr><th>From</th><th>To</th><th>From Location</th><th>To Location</th><th>Date</th><th>Distance</th><th>Duration</th><th>No of s</th><th>No of Nights</th></tr></thead><tbody>';
//				var downTb = '</tbody></table>';
//				var count = packageDel.length;
//				var lastRow = count - 1;
				$.each(packageDel, function (key, value)
				{
//					alert('#fdatecreate_'+key + ' :: '+value.pickup_date);
					$('#fdatecreate_'+key).html(value.pickup_date);
				});
//					var sl = key + 1;
//					// var packagedelID = $.trim(value['pcd_pck_id']);
//					if (key == 0)
//					{
//						var firstFromLocaion = fromlocation;
//					}
//					else
//					{
//						firstFromLocaion = value['pickup_address'];
//					}
//					if (key == lastRow)
//					{
//						var lastToLocaion = tolocation;
//					}
//					else
//					{
//						lastToLocaion = value['drop_address'];
//					}
//					var pdate = value['pickup_date'] + ' ' + value['pickup_time'];
//					html = html + '<tr class="packagerow">\n\
//			<td id="fcitycreate' + sl + '"><b>' + value['pickup_city_name'] + '</b></td>\n\
//<td id="tcitycreate' + sl + '"><b>' + value['drop_city_name'] + '</b></td>\n\
//<td id="fcitylocation' + sl + '">' + firstFromLocaion + '</td>\n\
//<td id="tcitylocation' + sl + '">' + lastToLocaion + ' </td>\n\
//<td id="fdatecreate' + sl + '">' + pdate + ' </td>\n\
//<td id="fdistcreate' + sl + '">' + value['distance'] + '</td>\n\
//<td id="fduracreate' + sl + '">' + value['duration'] + '</td>\n\
//<td id="noOfDayCount' + sl + '">' + value['pcd_day_serial'] + '</td>\n\
//<td id="noOfNightCount' + sl + '">' + value['pcd_night_serial'] + '</td>\n\
//</tr>';
//				});
//				var lastNightSerial = packageDel[(count - 1)].pcd_night_serial;
//				var retunDate = packageDel[(count - 1)].date;
//				getDropTiming(lastNightSerial, retunDate);
//				$('#return_date').val(packageDel[(count - 1)].date);
//				var str = packageDel[(count - 1)].date;
//				var ret = str.split(" ");
//				var time = ret[1];
//				$('#return_time').val(time);
//				$('#packagetb').html(upTb + html + downTb);
//				$('#packageJson').val(JSON.stringify(data.packageModel));
//				$('#multicityjsondata').val(JSON.stringify(data.multijsondata));
//				return;
			}
		});
	}


</script>