<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingSform',
	'enableClientValidation' => FALSE,
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
/* @var $form CActiveForm */

$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="content-boxed-widget login-box-container">
	<div class="select-box-1 bottom-30">
		<em class="color-gray mt20 n">Going From</em>
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
	<div class="one-half input-simple-1 has-icon input-blue bottom-30">		
		<em class="color-gray">Min No. of Nights</em>	
		<? echo $form->numberField($model, 'min_nights', ['min' => 0, 'numerical', 'integerOnly' => true]); ?>

	</div>
	<div class="one-half last-column input-simple-1 has-icon input-blue bottom-30">
		<em class="color-gray">Max No. of Nights</em>				
		<? echo $form->numberField($model, 'max_nights', ['min' => 0, 'numerical', 'integerOnly' => true]); ?>
	</div>
	<div class="clear"></div>
	<div class="content mb10 mt0 text-center"> 
		<button type="submit" class="btn-submit-orange">Search</button>
	</div>
</div>
<?php $this->endWidget(); ?>
<div class="top-0" id="columns">

	<?php
//print_r($pmodel);
	foreach ($pmodel as $pck)
	{
			?>
			<div class="content-boxed-widget login-box-container pb20">
				<h1 class="font-18 color-black text-center">
					<a type="button" href="packages/<?= $pck['pck_url'] ?>" class="color-black"> <?= $pck['pck_name'] ?></a>

		<!--					<a type="button"   onclick="showDetails(<?= $pck['pck_id'] ?>)"> <?= $pck['pck_name'] ?></a>-->
				</h1>
				<?
				if ($pck['pci_images'] != '')
				{
					?>
					<div class="img-parent bottom-10">
						<a href="packages/<?= $pck['pck_url'] ?>"><img src="<?= $pck['pci_images'] ?>" class="preload-image responsive-image bottom-5 clickshow" height="120"></a>
					</div>

				<? } ?>
				<p class="bottom-0"><b><?= $pck['pck_auto_name'] ?>:</b></p>
				<p class="bottom-10"><?= $pck['pck_desc'] ?></p>
				<?php 
				if($pck['prt_package_rate'] != ''){ 
					?>
				<p class="font-16"><b>Package Starting rate at </b><span> &#x20b9 <?= $quoteData[$pck['pck_id']]->routeRates->totalAmount; ?></span></p>
				<?php } ?>
				<div class="one-half text-center pr0">
					<a href="packages/<?= $pck['pck_url'] ?>"><button type="button" class="uppercase btn-orange shadow-medium line-height14 p10" target="_blank">Show Details</button></a></div>
				<div class="one-half last-column">
					<?php
					$pkid			 = $pck['pck_id'];
					$form			 = $this->beginWidget('CActiveForm', array(
						'id'					 => "book-package-form_$pkid", 'enableClientValidation' => true,
						'clientOptions'			 => array(),
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
					/* @var $form CActiveForm */
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
					<?php 
						if($pck['prt_package_rate'] != ''){
					?>
						<button type="submit" class="uppercase btn-green shadow-medium p15">Book Package</button>
					<?php } else { ?>
						<a href="#" data-menu="phonr-hover1" class="uppercase btn-green shadow-medium text-center">Call / Email us to book</a>
					<?php } $this->endWidget(); ?>
				</div>
				<div class="clear"></div>

			</div>
		<?php
	} ?>

</div>

<?php
if ($pmodel == [])
{
	?>
	<div class="content-boxed-widget">Didn' t find the package you are looking for? Just call us at <b>90518 77000</b> and we will create your package for you</div>

	<?
}
?>


<script type="text/javascript">

    function showDetails(id)
    {
        //alert(id);
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {

                multicitybootbox = bootbox.dialog({
                    message: data,
                    size: 'small',
                    title: 'Package Info',
                    onEscape: function ()
                    {

                    },
                });



            }
        });
    }

    $sourceList = null;
    function populateSourceCityPackage(obj, cityId)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
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
            }
            else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSourceCityPackage(query, callback)
    {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
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