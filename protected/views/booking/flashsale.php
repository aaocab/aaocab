<style>
	.paginationCustom   ul.pagination,.paginationCustom  ul{
		margin: 0!important;
	}
	table.tbflashsale th{
		display: none
	} 
	table.tbflashsale td{
		vertical-align: middle!important;
	} 
	.yii-selectize {
		min-width: 50px;

    }	
	#flashdiv .form-group{margin-left: 0!important;margin-right: 0!important;}
	.btnGozo{

		color: #fff;
		background-color: #f36c31;
		border-color: #eea236;
	}
	.pac-container{
		z-index: 1250;
	}
</style>
<div class="row" >
	<div id="flashdiv" class="col-sm-10 col-xs-12 marginauto float-none">
		<div class="row">
			<div class=" col-md-10 pb20 col-xs-12 marginauto float-none ">
				<div class="text-center">
					<img class="" src="/images/flashsale.jpg?v2"></div>
			</div>
		</div>
		<div class="row mb20 " >
			<div class="col-xs-12">
				<?php
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $version);
				$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
				Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
				$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
					'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
					'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
					'addPrecedence'		 => false,];

				//$fromCityArr = Cities::model()->getCityArrByFromBooking();
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
				<div class="row ">
					<div class="col-xs-6 col-md-4">
						<div class="form-group">
							<label class="control-label">Source</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'cav_from_city',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Source City",
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
					</div>
					<div class="col-xs-6 col-md-4"> 
						<div class="form-group">
							<label class="control-label">Destination</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'cav_to_cities',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Destination City",
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
					</div>
					<div class="col-xs-9 col-sm-6 col-md-3"> 
						<label class="control-label">Date Of Travel</label>
						<?
//			$jdate		 = ($model->from_date == '') ? DateTimeFormat::DateTimeToDatePicker(date('Y-m-d H:i:s')) : $model->from_date;
						echo $form->datePickerGroup($model, 'from_date', array('label'			 => '',
							'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date('Y-m-d H:i:s'),
									'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => 'Date of travel',
									'value'			 => $model->from_date,
									'class'			 => 'col-xs-12')),
							'groupOptions'	 => ['class' => ''],
							'prepend'		 => '<i class = "fa fa-calendar"></i>'));
						?> 
					</div>
					<div class="col-xs-3 col-sm-6 col-md-1 text-center"> 
						<input type="submit" name="submit" value="Filter" class='btn btn-info' style="margin-top: 28px;">  
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>

		</div>
		<div class="row">
			<div class=" col-md-10  col-xs-12 marginauto float-none ">
				<?php
				if (!empty($dataProvider))
				{
					//$this->layout = false;
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'sale_grid',
						'responsiveTable'	 => false,
						'dataProvider'		 => $dataProvider,
						'template'			 => "
							<div class='panel-heading p0'>
								<div class='row m0'>
									<div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
									<div class='col-xs-12 col-sm-6 pr0 paginationCustom'>{pager}</div>
								</div>
							</div>
                            <div class='panel-body p0'>{items}</div>
                            <div class='panel-footer p0'>
								<div class='row m0'>
									<div class='col-xs-12 col-sm-6 p5'>{summary}</div>
									<div class='col-xs-12 col-sm-6 pr0 paginationCustom'>{pager}</div>
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
										$str = '';
										$str .= "<span class='font16'><b>" . $data['sourceCity'] . " <i class='fa fa-arrow-right ' ></i> " . $data['destinationCity'] . "</b> </span><br> <span>  On: <b>" . date('F d, Y', strtotime($data['start'])) . "</b></span><br>";
										$str .= "<span>Must Depart Between: <b>" . date('h:i A', strtotime($data['start'])) . " and " . date('h:i A', strtotime($data['expiry'])) . "</b></span><br>";
										$str .= "<span>Car available: <b>" . $data['cabModel'] . "</b> &nbsp; &nbsp;  " . (($data['fuelType'] != '') ? " Fuel Type: " . $data['fuelType'] : '') . "</span>";


										echo $str;
									}
									?>      
									<?
								},
								'sortable'			 => true,
								'htmlOptions'		 => array('class' => 'col-xs-9	'),
								'header'			 => ''),
							array('name'	 => 'btnn', 'value'	 => function($data)
								{

									$routeRate	 = CabAvailabilities::calculateQuoteRate($data,'',true);
									$str		 = '';									
									$str		 .= "<strike>"."<i class='fa fa-inr'></i>" .$routeRate->totalAmount. "</strike><br>";
									$str		 .= "<span class='col-xs-12' style='font-size:1.4em;color: #2458aa;'><i class='fa fa-inr'></i><b>" . $data['Amount'] . "</b></span><span class='col-xs-12 pb5' style='font-size:0.8em; line-height:0.8em'>(inc. GST)</span>";
									$str		 .= "<button type='button' name='bookButtonNow'   hash=" . Yii::app()->shortHash->hash($data['cavid']) . " class='btn btnGozo col-xs-offset-2 col-xs-8  ' onclick='validateFlashSale(this);'> Book Now</button>";
									echo $str;
								},
								'htmlOptions' => array('class' => 'text-center col-xs-3')),
						)
					));
				}
				?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$sourceList = null;
	function validateFlashSale(obj) {
		var cavhash = $(obj).attr("hash");
		$('#BookingTemp_hash').val(cavhash);

		$href = "/booking/info";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"cavhash": cavhash, "flashBooking": 1},
			"dataType": "html", "async": false,
			"success": function (data) {
				var box = bootbox.dialog({
					message: data,
					title: 'Customer Info',
					size: '',
					onEscape: function () {
						box.remove();
					},
				});
				if ($('body').hasClass("modal-open"))
				{
					box.on('hidden.bs.modal', function (e) {
						$('body').addClass('modal-open');
					});
				}

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

</script>
<? $api = Yii::app()->params['googleBrowserApiKey']; ?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>


