<div class="row">
    <div class="col-12 bg-black p0">
        <img src="/images/flashsale.jpg?v=0.6" alt="" class="img-fluid">
    </div>
</div>
<div class="row bg-gray pt30">
	<div id="flashdiv" class="col-12 col-md-10 offset-md-1">
        <div class="container">
		<div class="row mb20 bg-white-box p20">
			<div class="col-12">
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
				$form		 = $this->beginWidget('CActiveForm', array(
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
					<div class="col-12 col-md-3">
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
					<div class="col-12 col-md-3"> 
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
					<div class="col-12 col-md-3"> 
				    <label class="control-label">Date Of Travel</label>
                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<?php
                            echo $form->widget('zii.widgets.jui.CJuiDatePicker',array(
								'model'=>$model,
								'attribute'=>'from_date',
								'options'=> array('autoclose'=> true, 'startDate'=> date('Y-m-d H:i:s'),'format'=> 'dd/mm/yyyy'),
								'htmlOptions'=> array('required' => true, 'placeholder'	=> 'Date of travel','readonly'=>'readonly',
												'value'			 => $model->from_date,'id' => 'CabAvailabilities_from_date',
												'class'			 => 'form-control input-style')
							),true);
						?> 
                    </div>
					</div>
					<div class="col-12 col-md-2 text-center"> 
						<input type="submit" name="submit" value="Filter" class='btn text-uppercase gradient-green-blue font-20 border-none' style="margin-top: 28px;">  
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>

		</div>
		<div class="row bg-white-box mb30">
			<div class="col-12">
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
									<div class='col-12 col-sm-6 pt5'>{summary}</div>
									<div class='col-12 col-sm-6 pr0 paginationCustom'>{pager}</div>
								</div>
							</div>
                            <div class='panel-body p0'>{items}</div>
                            <div class='panel-footer p0'>
								<div class='row m0'>
									<div class='col-12 col-sm-6 p5'>{summary}</div>
									<div class='col-12 col-sm-6 pr0 paginationCustom'>{pager}</div>
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
										$str .= "<span class='font-22'><b>" . $data['sourceCity'] . " <i class='fa fa-arrow-right ' ></i> " . $data['destinationCity'] . "</b> </span><br> <span>  On: <b>" . date('F d, Y', strtotime($data['start'])) . "</b></span><br>";
										$str .= "<span>Must Depart Between: <b>" . date('h:i A', strtotime($data['start'])) . " and " . date('h:i A', strtotime($data['expiry'])) . "</b></span><br>";
										$str .= "<span>Car available: <b>" . $data['cabModel'] . "</b> &nbsp; &nbsp;  " . (($data['fuelType'] != '') ? " Fuel Type: " . $data['fuelType'] : '') . "</span>";


										echo $str;
									}
									?>      
									<?
								},
								'sortable'			 => true,
								'htmlOptions'		 => array('class' => '', 'style' => 'width:75%'),
								'header'			 => ''),
							array('name'	 => '', 'value'	 => function($data)
								{

									$routeRate	 = CabAvailabilities::calculateQuoteRate($data,'',true);
									$str		 = '';									
									$str		 .= "<strike>"."&#x20B9;" .$routeRate->totalAmount. "</strike><br>";
									$str		 .= "<span class='col-12 pr0' style='font-size:2em;color: #2458aa;'>&#x20B9;<b>" . $data['Amount'] . "</b></span><span class='col-12 pb5 pl5' style='font-size:0.8em; line-height:0.8em'>(inc. GST)</span><br>";
									$str		 .= "<button type='button' name='bookButtonNow'   hash=" . Yii::app()->shortHash->hash($data['cavid']) . " class='btn text-uppercase gradient-green-blue font-16 border-none mt10' onclick='validateFlashSale(this);'> Book Now</button>";
									echo $str;
								},
								'htmlOptions' => array('class' => 'text-center col-5', 'style' => 'width:25%')),
						)
					));
				}
				?>
			</div>
		</div>
            </div>
	</div>
</div>
<div class="modal fade" id="flashSaleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width:80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flashSaleModalLabel">Customer Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="flashSaleModalContent">
                <div class="row"></div>
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
				$('#flashSaleModal').removeClass('fade');
				$('#flashSaleModal').css('display', 'block');
				$('#flashSaleModalContent').html(data);
				$('#flashSaleModal').modal('show');
				
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
<? //$api = Yii::app()->params['googleBrowserApiKey']; 
	 $api = Config::getGoogleApiKey('browserapikey');
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>


