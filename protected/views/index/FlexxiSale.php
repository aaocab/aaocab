<style>
	.yellow-color{ color: yellow;}
	.sale_active{ background: #fb540c !important;}
	.big-font{ font-size: 76px; line-height: normal;}
	.route_box{
		-webkit-border-top-right-radius: 100px;
		-moz-border-radius-topright: 100px;
		border-top-right-radius: 100px;
		background: #0c5cbf;
		color: #fff;
		padding: 30px 20px 20px 20px;
		height: 100%;
	}
	.orange-bg2 {
		background: #f77026 none repeat scroll 0 0;
	}
	.route-part{
		-webkit-border-radius: 30px;
		-moz-border-radius: 30px;
		border-radius: 30px;
		background: #efefef;
		-webkit-box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
		-moz-box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
		box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
	}
	.route-pbtn a{
		-webkit-border-bottom-right-radius: 10px;
		-webkit-border-bottom-left-radius: 10px;
		-moz-border-radius-bottomright: 10px;
		-moz-border-radius-bottomleft: 10px;
		border-bottom-right-radius: 10px;
		border-bottom-left-radius: 10px;
		background: #dfdfdf;
		font-size: 30px; font-weight: 700; color: #636363; padding: 20px 55px; text-transform: uppercase; line-height: normal; text-decoration: none;
		-webkit-box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
		-moz-box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
		box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
	}
	.route-pbtn a:hover{ background: #0c5cbf; color: #fff;}
	[type="radio"]:checked,
	[type="radio"]:not(:checked) {
		position: absolute;
		left: -9999px;
	}
	[type="radio"]:checked + label,
	[type="radio"]:not(:checked) + label
	{
		position: relative;
		padding-left: 28px;
		cursor: pointer;
		line-height: 20px;
		display: inline-block;
		color: #666;
	}
	[type="radio"]:checked + label:before,
	[type="radio"]:not(:checked) + label:before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 30px;
		height: 30px;
		border: 1px solid #ddd;
		border-radius: 100%;
		background: #fff;
	}
	[type="radio"]:checked + label:after,
	[type="radio"]:not(:checked) + label:after {
		content: '';
		width: 24px;
		height: 24px;
		background: #f36d33;
		position: absolute;
		top: 3px;
		left: 3px;
		border-radius: 100%;
		-webkit-transition: all 0.2s ease;
		transition: all 0.2s ease;
	}
	[type="radio"]:not(:checked) + label:after {
		opacity: 0;
		-webkit-transform: scale(0);
		transform: scale(0);
	}
	[type="radio"]:checked + label:after {
		opacity: 1;
		-webkit-transform: scale(1);
		transform: scale(1);
	}
	.banner-ani img{ width: 100%;}
	.share_on{ background: #58a39f; padding: 30px 0; font-size: 60px; font-weight: bold;}
	.share_on a{ font-size: 60px; color: #fff; padding: 0 30px;}
	.share_on a:hover{ color: #ffbd2e;}
	.proceed-new-btn2a{
		background: #ff6700;
		background: -moz-linear-gradient(top, #ff6700 0%, #ff4f00 100%);
		background: -webkit-linear-gradient(top, #ff6700 0%,#ff4f00 100%);
		background: linear-gradient(to bottom, #ff6700 0%,#ff4f00 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff6700', endColorstr='#ff4f00',GradientType=0 );
		text-transform: uppercase;
		font-size: 12px;
		font-weight: bold;
		border: none;
		padding: 5px 10px;
		margin-top: 6px;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		transition: all 0.5s ease-in-out 0s;
		border: #fff 1px solid;
	}

	.modal-dialog{ width: 50%!important;}
    .flex2{
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		flex-wrap: wrap;
		width: 100%;
	}
    @media (min-width: 320px) and (max-width: 767px) {
        .modal-dialog{ width: 90%!important; margin: 15px auto;}
    }
    @media (min-width: 768px) and (max-width: 1164px) {
        .route_box{ /*min-height: 350px;*/}
        .route_box .h1{ font-size: 30px;}
        .modal-dialog{ width: 90%!important; margin: 15px auto;}

    }
</style>
<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];


$baseAmount = FlashSale::getFlashBaseAmount();
?>
<div class="row">
	<div class="col-xs-12 text-center">
		<img src="/images/gozo-flexxi-logo.png" alt="Gozo Share" >
	</div>

	<div class="col-xs-12 mt20 mb20 text-center banner-ani">
		<img src="/images/199.jpg" alt="Gozo Share" >
	</div>

	<div class="col-xs-12 mt20 mb20">
		<p class="h2"><b>Early bird gets the ride.</b></p>

		<p>Come early and book your GozoSHARE seat to ride for just ₹<?=$baseAmount;?>/-<br>
			Each week, we select 4 routes across India and will be offering GozoSHARE seats on on those routes for simply a buck!<br>
			Just 1 seat per person and only 1 seat can be booked for ₹<?=$baseAmount;?>/- at a time. There is no contest, no lucky winner to be selected.<br>
			Be there when the seats go on sale. If you're the early bird, you'll catch the ride!</p>

		<p class="h3"><br/><b>Select the date. Then select card and click "BOOK NOW"</b></p>
        <p class="h5">Each day will be on sale. Every date will go on sale 2 days ahead of the travel date.</p>
	</div>



	<div class="col-xs-12 mt mb20" id="here">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'just1Form', 
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'action'				 => array('just199/#here'),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array('autocomplete'	 => 'off',
				'class'			 => '',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<div class="row col-xs-12 mt20 mb20">
			<div class="col-xs-12 col-sm-4 col-md-3 pl0">
				<?php
				$dateList			 = VehicleTypes::model()->getJSON(FlashSale::model()->getDateList());
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'fls_pickup_date',
					'val'			 => $model->fls_pickup_date,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($dateList), 'allowClear' => true),
					'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Date')
				));
				?>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
		</div>
	</div>
	<input type="hidden" id="flsId" name="flsId" value="0" />
	<input type="hidden" id="flsRouteId" name="flsRouteId" value="0" />
	<?php
	$host = Yii::app()->params['host'];
	if (isset($routes) && count($routes) > 0)
	{
		?>
		<div  class="flex2">	
			<?php
			foreach ($routes as $key)
			{
				?>
				<div class="col-xs-12 col-sm-4 col-md-3 mb30" style="cursor: pointer;">
					<?php
					$pickupDate		 = date("d M Y h:i A", strtotime($key['fls_pickup_date']));
					$flsStatus		 = $key['flsStatus'];
					$saleStart		 = date("d M Y h:i A", strtotime($key['fls_sale_start_date']));
					$onClickEvent	 = '';
					$nowDate		 = date('Y-m-d H:i:s');
					//echo $nowDate." - ".$key['fls_pickup_date']."<br>";
					$flashClass		 = '';
					$flashText		 = '';
					if ($flsStatus > 0 || $nowDate > $key['fls_sale_end_date'])
					{
						$flashClass	 = 'orange-color';
						$flashText	 = '<b>Sold Out!</b>';
					}
					else
					{
						if ($key['fls_sale_start_date'] > $nowDate)
						{
							$flashClass	 = 'yellow-color';
							$flashText	 = 'Sale starts: <b>' . $saleStart . '</b>';
						}
						else
						{
							$flashClass	 = 'white-color';
							$flashText	 = '<b>Seats available</b>';
						}
					}
					?>
					<div class="route_box" id="dispcard">	
						<input type="hidden" id="routeId" name="routeID" value=<?= $key['fls_route_id'] ?>>
						<div class="row mb10 pt20">
							 <div class="col-xs-12 h1">
								<?= $key['from_city_name'] ?>
								<div class="text-left mt10 mb10"><i class='fa fa-arrow-right text-center'></i></div>
							<?= $key['to_city_name'] ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 h4"><?= $pickupDate ?>
								<div id="saledate"><span class='col-xs-12 h4 pl0 <?= $flashClass; ?>' align="left"><?= $flashText; ?>
										<?php
										if ($key['fls_sale_start_date'] < $nowDate)
										{
											if ($nowDate < $key['fls_sale_end_date'])
											{
												if ($userModel->user_id != '')
												{
													$hashFlsId = Yii::app()->shortHash->hash($key["fls_id"]);
													?>											
													<a href="https://<?=$host;?>/just199/<?= $hashFlsId; ?>" style="margin-top: 20px; margin-left: 30%;"  class="btn btn-primary proceed-new-btn2a" id="bookNow1">Book Now!</a>
													<?php
												}
												else
												{
													?>
													<button type="button" class="btn btn-primary proceed-new-btn2a" style="margin-top: 20px; margin-left: 30%; " id="bookNow2" onclick="validateForm2(this);">Book Now!</button>	
													<?php
												}
											}
										}
										?></span>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	else
	{
		if ($flsPickupDate == '0')
		{
			?>

			<div class="col-xs-12 mt20 mb20 orange-bg2 pt20 pb20 white-color text-center">
				<p class="h1 mt50 mb20">Don't see your route or date of travel?</p>
				<p class="h4 mb50">Don't worry. Each date goes on sale 2 days ahead. Just Visit the Page 2 days before your travel date.<br>
					We will select 8 new routes each week.If your route didn't go on sale this week, simply tell us below which route you want and we will give you a special coupon code for your travel.</p>
			</div>
			<div class="col-xs-12 mt20 mb20">
				<div class="row">
					<div class="col-xs-11 col-sm-8 col-md-6 float-none marginauto route-part p40 text-center">
						<span class="h3 text-left">
							<div class="row flexxi-sale">
								<div class="col-xs-12 col-sm-5">
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'fls_from_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "From City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->fls_from_city}');
                                                }",
									'load'			 => "js:function(query, callback){
                                loadSourceCity(query, callback);
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
								<div class="col-xs-12 col-sm-2">
									<i class="fa fa-angle-right mr30 ml30"></i>
								</div>
								<div class="col-xs-12 col-sm-5">
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'fls_to_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "To City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->fls_to_city}');
                                                }",
									'load'			 => "js:function(query, callback){
                                loadSourceCity(query, callback);
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


						</span>
					</div>
					<?php
					if ($userModel->user_id != '')
					{
						?>
						<div class="col-xs-11 col-sm-5 col-md-3 float-none marginauto route-pbtn p20 text-center">
							<a href="javascript:void(0)" onclick="Suggest();">Suggest</a>
						</div>
						<?
					}
					else
					{
						?>    
						<div class="col-xs-11 col-sm-5 col-md-3 float-none marginauto route-pbtn p20 text-center">
							<a href="javascript:void(0)" onclick="validateForm2(this);">Suggest</a>
						</div>
					<? } ?>
				</div>
			</div>
			<?php
		}
		else if ($flsPickupDate > 0)
		{
			?>
			<div class="col-xs-12 mt20 mb20 orange-bg2 pt20 pb20 white-color text-center">
				<p class="h3 mt50 mb20">The routes are not decided yet, Please check back soon!</p>
			</div>

			<div class="col-xs-12 mt20 mb20">
				<div class="row">
					<div class="col-xs-11 col-sm-8 col-md-6 float-none marginauto route-part p40 text-center">
						<span class="h3 text-left">
							<div class="row flexxi-sale">
								<div class="col-xs-12 col-sm-5">
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'fls_from_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "From City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->fls_from_city}');
                                                }",
									'load'			 => "js:function(query, callback){
                                loadSourceCity(query, callback);
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
								<div class="col-xs-12 col-sm-2">
									<i class="fa fa-angle-right mr30 ml30"></i>
								</div>
								<div class="col-xs-12 col-sm-5">
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'fls_to_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "To City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->fls_to_city}');
                                                }",
									'load'			 => "js:function(query, callback){
                                loadSourceCity(query, callback);
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


						</span>
					</div>
					<?php
					if ($userModel->user_id != '')
					{
						?>
						<div class="col-xs-11 col-sm-5 col-md-3 float-none marginauto route-pbtn p20 text-center">
							<a href="javascript:void(0)" onclick="Suggest();">Suggest</a>
						</div>
						<?
					}
					else
					{
						?>    
						<div class="col-xs-11 col-sm-5 col-md-3 float-none marginauto route-pbtn p20 text-center">
							<a href="javascript:void(0)" onclick="validateForm2(this);">Suggest</a>
						</div>
					<? } ?>
				</div>
			</div>
			<?php
		}
	}
	?>
	<?php $this->endWidget(); ?>

	<div  class="col-xs-12 mt20 mb20">
		<div class="row text-uppercase">
			<div class="col-xs-12 text-center">
				<img src="/images/go4_icon1.png" alt="" >
				<p class="mt20 h2">Same gender passengers</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="/images/go4_icon3.png" alt="" >
				<p class="mt20 h2">Best Service</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="/images/go4_icon2.png" alt="" >
				<p class="mt20 h2">Nationwide 1000+ Routes</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="/images/go4_icon4.png" alt="" >
				<p class="mt20 h2">Reduce pollution</p>
			</div>
			<div class="col-xs-12 col-sm-6 p20 text-center">
				<img src="/images/go4_icon5.png" alt="" >
				<p class="mt20 h2">24x7 Support</p>
			</div>
			<div class="col-xs-12 text-center">
				<img src="/images/go4_icon6.png" alt="" >
				<p class="mt20 h2">Make Friends</p>
			</div>
		</div>
	</div>
	<!--		<div class="col-xs-12 mt20 mb20 white-color text-center">
				<div class="row share_on">
					<div class="col-xs-12">Share On:<a href="#"><i class="fa fa-facebook-f"></i></a><a href="#"><i class="fa fa-instagram"></i></a><a href="#"><i class="fa fa-twitter"></i></a></div>
				</div>
			</div>-->
</div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
		$(".select2-input").prop("readonly",true);		
		$("#FlashSale_fls_pickup_date").change(function () {
		  $('#just1Form').serialize();
		  $('#just1Form').submit();
	   });
    });



    var fbHtml = "";
    var fbFlag = 1;
    function validateForm2(obj)
    {

        fbHtml = "<div class='col-xs-5 fbook-btn mt20 float-none marginauto' id='fbHtml' align='middle'>" +
                "<a href='#' align='middle'><span class='btn btn-xs btn-social btn-facebook pl5 pr5' onclick='openFbDialog();'><i class='fa fa-facebook pr5' style='font-size: 22px;'></i> Login with Facebook</span></a>" +
                "</div>";


        box = bootbox.dialog({
            message: "<div class='panel'><div class='panel panel-body'>" +
                    "<div class='row'>" + fbHtml +
                    "</div></div>",
            title: 'Log In to Continue :',
            size: 'medium',

            onEscape: function ()
            {
                box.modal('hide');

            },

        });
    }

    function openFbDialog()
    {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
        var fbWindow = window.open(href, 'aaocab', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
    }

    function updateLogin()
    {
        $href = '<?= Yii::app()->createUrl('users/refreshuserdata') ?>';
        jQuery.ajax({type: 'get', url: $href,
            "dataType": "json",
            success: function (data1)
            {
                box.hide();
                window.location.reload(true);

            }
        });
    }






    $sourceList = null;
    function populateSourceCity(obj, cityId)
    {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSourceCity(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }



    function Suggest()
    {
        if (isNaN($('#<?= CHtml::activeId($model, "fls_from_city") ?>').val()) == false && isNaN($('#<?= CHtml::activeId($model, "fls_to_city") ?>').val()) == false)
        {
            var fromcity = $('#FlashSale_fls_from_city').val();
            var tocity = $('#FlashSale_fls_to_city').val();
			if(fromcity=='')
			{
				 alert('Please select from city.');
				return false;
			}
			if(tocity=='')
			{
				 alert('Please select to city.');
				return false;
			}
            $href = '<?= Yii::app()->createUrl('index/suggestRoute') ?>';
            jQuery.ajax({dataType: 'text', type: 'GET', url: $href,
                data: {'fromCity': fromcity, 'toCity': tocity},
                success: function (data)
                {
                    var data1 = JSON.parse(data);
					if(data1.success==true)
					{
						$('#FlashSale_fls_from_city').val('');
						$('#FlashSale_fls_to_city').val('');
						alert(data1.message);
						window.location.href = "/just1";
					}
					else
					{
						alert(data1.message);
						return false;
					}
                },
                error: function () {
                    alert('error');
                }
            });
        }
    }
</script>