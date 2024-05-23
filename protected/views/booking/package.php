
<style>

	#columns {
		column-width: 320px;
		column-gap: 15px;
		/*		width: 90%;
				max-width: 1100px;*/
		overflow: hidden;
		margin: 50px auto;
	}

	div#columns figure {
		background: #fefefe;
		border: 2px solid #fcfcfc;
		border-radius: 15px;
		box-shadow: 0 1px 2px rgba(34, 25, 25, 0.4);
		margin: 0 2px 15px;
		padding: 12px;

		transition: opacity .4s ease-in-out;
		display: inline-block;

	}

	div#columns figure img {
		width: 100%; height: auto;
		border-bottom: 1px solid #ccc; 
		-webkit-transition: 0.4s ease;
		transition: transform 0.4s ease;
	}

	div#columns figure figcaption {
		font-size: 1.9rem;
		color: #444;
		line-height: 1.3;
		text-align: center;
		/*text-decoration: none*/
	}
	div#columns figure figcaption a:hover{		 
		text-decoration: none!important;
	}

	div#columns figure figcaption a  {
		color: #440000!important;
	}
	div#columns figure figcaption a.clickshow  {
		text-decoration:underline
	}

	div#columns small { 
		font-size: 1rem;
		float: right; 
		text-transform: uppercase;
		color: #aaa;
	} 

	div#columns small a { 
		color: #666; 
		text-decoration: none; 
		transition: .4s ease;
	}
	figure img:hover  {
		-webkit-transform: scale(1.08);
		transform: scale(1.08);

	}

	div#columns:hover figure:not(:hover) {
		opacity: 0.8;

	}
	.img-parent{
		overflow:hidden!important;
	}

	@media screen and (max-width: 750px) { 
		#columns { column-gap: 0px; }
		#columns figure { width: 100%; }
	}
	.clickshow{
		cursor:pointer
	}


</style>
<div class="row">
	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
		),
	));
	/* @var $form TbActiveForm */

	$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
		'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
		'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
		'openOnFocus'		 => true, 'preload'			 => false,
		'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
		'addPrecedence'		 => false,];
	?>
	<div class="panel panel-warning">
		<div class="panel panel-body">
			<div class="col-xs-4"> 
				<div class="col-xs-12">Going From</div><br>
				<div class="col-xs-8">
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'from_city',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select City",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
				  populateSourceCityPackage(this, '{$model->from_city}');
								}",
					'load'			 => "js:function(query, callback){
				loadSourceCityPackage(query, callback);
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
			<div class="col-xs-6">
				<div class="col-xs-6 pr0 mr0"><div class="col-xs-12">Min No. of Nights</div><div class="col-xs-5"><? echo $form->numberFieldGroup($model, 'min_nights', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "", 'width' => '10px;', 'min' => 0]), 'groupOptions' => ['class' => 'm0'])); ?></div></div>
				<div class="col-xs-6 pl0 ml0"><div class="col-xs-12">Max No. of Nights</div><div class="col-xs-5"><? echo $form->numberFieldGroup($model, 'max_nights', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "", 'width' => '10px;', 'min' => 0]), 'groupOptions' => ['class' => 'm0'])); ?></div></div>
			</div>
			<div class="col-xs-2 mt20"><input type="submit" class="btn btn-warning" value="SEARCH"></div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
	<div class="col-xs-12   mb20"  id="columns">

		<?php
//print_r($pmodel);
		foreach ($pmodel as $pck)
		{
			?>
			<figure>
				<figcaption>
                        <a type="button" href="packages/<?=$pck['pck_url']?>"> <?= $pck['pck_name'] ?></a>
                     
<!--					<a type="button"   onclick="showDetails(<?= $pck['pck_id'] ?>)"> <?= $pck['pck_name'] ?></a>-->
				</figcaption>
				<?
				if ($pck['pci_images'] != '')
				{
					?>
					<figure class="mt5 ">
							<div class="img-parent">
							 <a href="packages/<?=$pck['pck_url']?>"><img src="<?= $pck['pci_images'] ?>" class="col-xs-12 p0 clickshow"></a>
                           </div> 
					</figure>

				<? } ?>
				<?php 
				if($pck['prt_package_rate'] != ''){ 
					?>
						<div class="p20" style="font-size: 16px !important;">
						<b>Package Starting rate at </b><span class='float-right pr20'> &#x20b9 <?= $quoteData[$pck['pck_id']]->routeRates->totalAmount; ?></span>
					   </div> 
				<?php } ?>
				<figcaption>
					<div class="row"> 
						<div class="col-xs-12"> 
							<div class="row"> 
								<div class="col-xs-6 text-right pr0"> 
									<div class=" Submit-button btn "> 
										<a href="packages/<?=$pck['pck_url']?>"><button type="button" class="btn btn-warning clickshow" target="_blank">Show Details</button></a>
									</div></div>
								<div class="col-xs-6 text-left  pl0">
									<?php
									$pkid			 = $pck['pck_id'];
									$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
										'id'					 => "book-package-form_$pkid", 'enableClientValidation' => true,
										'clientOptions'			 => array(
										),
										// Please note: When you enable ajax validation, make sure the corresponding
										// controller action is handling ajax validation correctly.
										// See class documentation of CActiveForm for details on this,
										// you need to use the performAjaxValidation()-method described there.
										'enableAjaxValidation'	 => false,
										'errorMessageCssClass'	 => 'help-block',
										'action'				 => '/bknw',
										'htmlOptions'			 => array(
											'class' => 'form-horizontal',
										),
									));
									/* @var $form TbActiveForm */
									$ptimePackage	 = Yii::app()->params['defaultPackagePickupTime'];

									$defaultDate = date("Y-m-d $ptimePackage", strtotime('+7 days'));
									$pdate		 = DateTimeFormat::DateTimeToDatePicker($defaultDate);
									$ptime		 = date('h:i A', strtotime($ptimePackage));
									?>
									<input type="hidden" id="step11" name="step" value="1">
									<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 5, 'id' => 'bkg_booking_type5']); ?>
									<?= $form->hiddenField($model, 'bktyp', ['value' => 5, 'id' => 'bktyp5']); ?>
									<?= $form->hiddenField($model, 'bkg_package_id', ['value' => $pkid]); ?>  
									<?= $form->hiddenField($model, 'bkg_pickup_date_date', ['value' => $pdate]); ?>  
									<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' => $ptime]); ?>  
									<?php if($pck['prt_package_rate'] != '')
										  {
									?>
										<div class="Submit-button btn" style=""> <?php echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary')); ?> </div>
									<?php }else{ ?>
										<div class="Submit-button btn" style=""> <a href="tel:+919051877000" class="btn btn-primary" style="color:#fff !important;white-space: pre-wrap;">Call / Email us to book</a> </div>
									<?php } $this->endWidget(); ?>
								</div>
							</div>
						</div>
					</div>
				</figcaption>

			</figure>
		<?php } ?>

	</div>

	<?php
	if ($pmodel == [])
	{
		?>
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-sm-offset-4">
				<div class="panel panel-default text-center">
					<div class="panel-body">Didn' t find the package you are looking for? Just call us at <b>90518 77000</b> and we will create your package for you</div>
				</div>
			</div>
		</div>

		<?
	}
	?>


	<script type="text/javascript">

		function showDetails(id)
		{
			//alert(id);
			$href = '<?= Yii::app()->createUrl('booking/showPackage', ['pck_id' => '']) ?>' + id;
			jQuery.ajax({type: 'GET', url: $href,
				success: function (data) {

					multicitybootbox = bootbox.dialog({
						message: data,
						size: 'small',
						title: 'Package Info',
						onEscape: function () {						 

						},
					});



				}
			});
		}

		$sourceList = null;
		function populateSourceCityPackage(obj, cityId) {
			obj.load(function (callback) {
				var obj = this;
				if ($sourceList == null) {
					xhr = $.ajax({
						url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
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
		function loadSourceCityPackage(query, callback) {
			$.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
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

	</script>