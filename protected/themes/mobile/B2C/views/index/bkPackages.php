<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="tab-item devSecondaryTab3" id="tab-pill-8a" style="display: none;">
    <div class="inner-tab">
        <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab" style="width: calc(30% - 5px);">One-Way</a>
<!--        <a href="#" data-sub-tab="tab-pill-3a" class="sub-tab" style="width: calc(33.33% - 5px);">Round Trip</a>-->
        <a href="#" data-sub-tab="tab-pill-4a" class="sub-tab" style="width: calc(40% - 5px);">Round Trip</a>
		<a href="#" data-sub-tab="tab-pill-8a" class="sub-tab active-tab-pill-button active" style="width: calc(30% - 5px);">Packages</a>
    </div>
	<?
    $form = $this->beginWidget('CActiveForm', array(
        'id'                     => 'bookingSform',
        'enableClientValidation' => true,
        'clientOptions'          => array(
            'validateOnSubmit' => true,
            'errorCssClass'    => 'has-error'
        ),
        'enableAjaxValidation'   => false,
        'errorMessageCssClass'   => 'help-block',
        'action'				 => array('/packages'),
        'htmlOptions'            => array(
            'class' => 'form-horizontal',
        ),
    ));
    /* @var $form CActiveForm */
    ?>
    <!--	<div class="inner-tab devSecondaryTab1">
                    <a href="#" class="active">Full Cab</a><a href="#">Shared Cab</a>
                    <a href="#" data-sub-tab="tab-pill-1a" class="sub-tab active-tab-pill-button active">Full Cab</a>
                    <a href="#" data-sub-tab="tab-pill-1b" class="sub-tab">Shared Cab</a>
            </div>-->
    <div class=" select-box-1 bottom-30">
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
										$('.selectize-control INPUT').attr('autocomplete','new-password');                            
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

		    $model->min_nights	 = 0;
		    $model->max_nights	 = 10;
			  
		?>
	</div>
    <div class="one-half input-simple-1 has-icon input-blue bottom-30">		
        <em class="color-gray">Min No. of Nights</em>	
        <div class="col-xs-6"><? echo $form->numberField($model, 'min_nights', ['min' => 0,'numerical','integerOnly'=>true]); ?></div>

    </div>
    <div class="one-half last-column input-simple-1 has-icon input-blue bottom-30">
        <em class="color-gray">Max No. of Nights</em>				
        <div class="col-xs-6"><? echo $form->numberField($model, 'max_nights',['min' => 0,'numerical','integerOnly'=>true]); ?></div>
    </div>
    <div class="clear"></div>
    <div class="content mb10 mt0 text-center">                                    
        <button type="submit" class="btn-submit-orange">Search</button>
    </div>
<?php $this->endWidget(); ?>			
</div>

<script>
    $sourceList22 = null;
	function populateSourceCityPackage(obj, cityId)
	{
		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList22 == null)
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
						$sourceList22 = results;
						obj.enable();
						callback($sourceList22);
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
				callback($sourceList22);
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
