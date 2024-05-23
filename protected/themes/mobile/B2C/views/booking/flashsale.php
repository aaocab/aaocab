<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<style>
    .selectize-input{ width: 100%;}
</style>
<div class="row" >
	<div id="flashdiv" class="marginauto float-none">
			<div class="text-center">
				<img class="preload-image responsive-image bottom-5" src="images/flashsale.jpg?v3">
			</div>
		<div class="content-boxed-widget pb5 flash-contant">
			<div class="select-box-1 bottom-10">
				<?php
                $version = Yii::app()->params['siteJSVersion'];
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
				$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
				$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
					'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
					'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
					'addPrecedence'		 => false,];

				//$fromCityArr = Cities::model()->getCityArrByFromBooking();
				$form = $this->beginWidget('CActiveForm', array(
					'id'					 => 'quote_request_search', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
					),
				));
				?>
                                        <em class="color-gray mt20 n">Going From</em>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'cav_from_city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select City",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width'	 => '100%',
								'id'	 => 'cav_from_city'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
					populateSource(this, '{$model->cav_from_city}');
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
                                        </div>
                    <div class="select-box-1 bottom-10">
        <em class="color-gray mt20 n">Going To</em>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'cav_to_cities',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select City",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width'	 => '100%',
								'id'	 => 'cav_to_cities'
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
					populateSource(this, '{$model->cav_to_cities}');
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
                    </div>
					<div class="input-simple-1 has-icon input-blue bottom-10 top-40">
                                            <em class="color-gray mt20 n">Date Of Travel</em>
						<?php
										$this->widget('zii.widgets.jui.CJuiDatePicker',array(
														'name'=>'CabAvailabilities[from_date]',
														'value'	=> $model->from_date,				
														'options'=>array('showAnim'=>'slide','autoclose' => true, 'startDate' => date('Y-m-d H:i:s ', strtotime('+4 hour')), 'dateFormat' => 'dd/mm/yy','minDate'=> 0,'maxDate'=>"+6M"),   
														'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date','readonly'=>'readonly',								
																'class'			 => 'border-radius font-16 datePickup','id'=> 'CabAvailabilities_from_date','style'=>'z-index:100 !important')	
										));
						?>

					</div>
					<div class="content mt20 text-center"> 
<!--						<input type="submit" name="submit" value="Filter" class='btn btn-info' style="margin-top: 28px;">  -->
						<button type="submit" class="uppercase btn-orange shadow-medium">Filter</button>
					</div>
				<?php $this->endWidget(); ?>
			

		</div>
			<div class="content-boxed-widget text-center pb5 flash-contant">
				<?php
				if (!empty($dataProvider))
				{
					//$this->layout = false;
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'sale_grid',
						'responsiveTable'	 => false,
						'dataProvider'		 => $dataProvider,
						'template'			 => "
							<div class='p0'>
								<div class='m0'>
									<div class='pt5 text-left'>{summary}</div>
									<div class='last-column pr0 paginationCustom'>{pager}</div>
									<div class='clear'></div>
								</div>
							</div>
                            <div class='p0'>{items}</div>
                            <div class='p0'>
								<div class='m0'>
									<div class='pt5 text-left'>{summary}</div>
									<div class='last-column pr0 paginationCustom'>{pager}</div>
									<div class='clear'></div>
								</div>
							</div>",
						'itemsCssClass'		 => 'table table-striped mb0 tbflashsale',
						'htmlOptions'		 => array('class' => 'panel panel-info'),
						'columns'			 => array(
							array('name'	 => 'Cab Availability', 'value'	 => function($data)
								{
									?>

									<?php
									if (isset($data))
									{
										$routeRate	 = CabAvailabilities::calculateQuoteRate($data,'',true);
										$str = '';
										$str .= '<div class="content-boxed-widget" style="padding: 10px 10px !important;">';
										$str .= '<div class="mt10 font-icon-list font-11">';
										$str .= '<div class="float-right" style="margin:0!important;">';
										$str .= '<div class="text-center"><strike><span>&#x20B9</span>'.$routeRate->totalAmount.'</strike><br><b class="font16 color-highlight"><span>&#x20B9</span>'. $data['Amount'] .'</b></div><a href="javascript:void(0)" class="uppercase btn-orange shadow-medium line-height14" name="bookButtonNow"   hash="'.Yii::app()->shortHash->hash($data['cavid']).'" onclick="validateFlashSale(this);">Book Now </a></div>';
										$str .= '<span class="font16 color-highlight"><b>'. $data['sourceCity'] .' <i class="fa fa-arrow-right "></i> '. $data['destinationCity'] .'</b></span><br> <span>On: <b>'. date("F d, Y", strtotime($data['start'])).'</b></span><br><span>Must Depart Between: <b>'. date("h:i A", strtotime($data['start'])).' and '. date("h:i A", strtotime($data['expiry'])).'</b></span><br><span>Car available: <b>'. $data['cabModel'] .'</b></span><br><span>Fuel Type: <b>'. $data['fuelType'].'</b></span>';
										$str .= '</div><div class="clear"></div></div>';
										echo $str;
									}
									?>      
									<?
								},
								'sortable'			 => true,
								'htmlOptions'		 => array('class' => 'text-left'),
								'header'			 => ''),
							
						)
					));
				}
				?>
			</div>
	</div>
</div>

<!--<div class="content-boxed-widget">
	<div class="mt10 font-icon-list font-11">
		<div class="last-column float-right">
			<a href="#" class="uppercase btn-orange shadow-medium line-height14">Book Now for<br><b class="font-16"><span>&#x20B9</span>2321</b></a>
		</div>
		<span class="font16 color-highlight"><b>Kolkata <i class="fa fa-arrow-right "></i> Digha</b></span><br> <span>On: <b>December 05, 2019</b></span><br><span>Must Depart Between: <b>04:00 PM and 10:00 PM</b></span><br><span>Car available: <b>Toyota Innova 7+1 (SUV)</b> &nbsp; &nbsp;   Fuel Type: Diesel +CNG</span>
	</div>
	<div class="clear"></div>
</div>
<div class="content-boxed-widget">
	<div class="mt10 font-icon-list font-11">
		<div class="last-column float-right">
			<a href="#" class="uppercase btn-orange shadow-medium line-height14">Book Now for<br><b class="font-16"><span>&#x20B9</span>2321</b></a>
		</div>
		<span class="font16 color-highlight"><b>Kolkata <i class="fa fa-arrow-right "></i> Digha</b></span><br> <span>On: <b>December 05, 2019</b></span><br><span>Must Depart Between: <b>04:00 PM and 10:00 PM</b></span><br><span>Car available: <b>Toyota Innova 7+1 (SUV)</b> &nbsp; &nbsp;   Fuel Type: Diesel +CNG</span>
	</div>
	<div class="clear"></div>
</div>-->

<div class="content-boxed-widget pb5 bkInfo-contant hide">
	<a href="#" class="p10"><i class="font-18 fas fa-arrow-left color-black-dark link-one flash-back"></i></a>
	<div class="bkInfo-contant-data"></div>
</div>


<script type="text/javascript">
	$sourceList = null;
	//var hyperModel = new HyperLocation();
	function validateFlashSale(obj) {
		var cavhash = $(obj).attr("hash");
		$('#BookingTemp_hash').val(cavhash);

		$href = $baseUrl + "/booking/info";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"cavhash": cavhash, "flashBooking": 1},
			"dataType": "html", "async": false,
			"success": function (data) {
				$('.flash-contant').addClass('hide');
				$('.bkInfo-contant-data').html(data);
				$('.bkInfo-contant').removeClass('hide');
			}
		});

	}
	function populateSource(obj, cityId)
	{
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
					dataType: 'json',
					data: {
						// city: cityId
					},
					//  async: false,
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
	
	$('.flash-back').click(function(){
		$('.flash-contant').removeClass('hide');
		$('.bkInfo-contant-data').html('');
		$('.bkInfo-contant').addClass('hide');
	});

</script>
<? //$api = Yii::app()->params['googleBrowserApiKey']; ?>
<? $api = Config::getGoogleApiKey('browserapikey'); ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>


