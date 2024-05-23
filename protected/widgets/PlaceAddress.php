<?php

/**
 */
class PlaceAddress extends CInputWidget
{

	public $widgetId		 = null;
	public $isMobileView	 = false;
	public $city			 = 0;
	public $user			 = 0;
	public $selectOptions	 = [];
	public $PACOptions		 = [];

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the autocomplete input.
	 */
	public function init()
	{
		list($name, $id) = $this->resolveNameID();
		if (isset($this->htmlOptions['id']))
		{
			$id = $this->htmlOptions['id'];
		}
		else
		{
			$this->htmlOptions['id'] = $id;
		}

		if (isset($this->htmlOptions['name']))
		{
			$name = $this->htmlOptions['name'];
		}

		if ($this->widgetId == null)
		{
			$this->widgetId = $id;
		}
		/** @var BookingRoute $brtModel */
		$brtModel	 = $this->model;
		$bkgModel	 = $brtModel->brtBkg;
		$isGozoNow	 = $bkgModel->bkgPref->bkg_is_gozonow;

		$this->registerClientScript();
		$addresses				 = [];
		$cityModel				 = Cities::model()->findByPk($this->city);
		$PACReadOnly			 = false;
		$value					 = CHtml::resolveValue($this->model, $this->attribute);
		$tripId					 = $bkgModel->bkg_bcb_id;
		$isGozoNowVendorSelected = BookingVendorRequest::getPreferredVendorbyBooking($tripId);

		if ($cityModel->cty_is_airport == 1 
|| (!$isGozoNowVendorSelected && $isGozoNow && $brtModel->brt_to_city_id == $this->city) 
|| ($isGozoNowVendorSelected && $isGozoNow && $brtModel->brt_from_city_id == $this->city)
		)
		{
			$hideSelect		 = "hide";
			$place			 = Stub\common\Place::init($cityModel->cty_lat, $cityModel->cty_long);
			$garage_address	 = ($isGozoNowVendorSelected) ? $brtModel->brt_from_location : $cityModel->cty_garage_address;

			$place->address	 = ($isGozoNow) ? $garage_address : $cityModel->cty_display_name;
			$attribute		 = $this->attribute;

			if (preg_match('/\](\w+(\[.+)?)/', $attribute, $matches))
			{
				$attribute = $matches[1];
			}

			$value					 = $this->model->$attribute = json_encode($place);
			$PACReadOnly			 = true;
			goto skipAddresses;
		}

		$addresses	 = BookingRoute::getUserAddressesByCity($this->city, $this->user);
		$hideSelect	 = "";
		$hidePAC	 = "hide";
		if ($addresses == [])
		{
			$existingAddress = false;
			$hideSelect		 = "hide";
			$hidePAC		 = "";
		}
		skipAddresses:
		echo "<div class='PAWidget PAW{$this->widgetId} mb20'><div class='PAWExisting {$hideSelect}'>";
		$this->widget('ext.yii-selectize.YiiSelectize', array(
			'name'				 => "SELECT_" . $this->widgetId,
			'value'				 => $value,
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Choose from list",
			'fullWidth'			 => true,
			'htmlOptions'		 => array('width' => '50%'
			),
			'defaultOptions'	 => array(
		'valueField'	 => 'id',
		'labelField'	 => 'text',
		'options'		 => $addresses,
		'onInitialize'	 => "js:function(){
			AWObject.init('{$this->widgetId}','{$this->city}').addSelect(this);
												}",
		'onChange'		 => "js:function(value){
			AWObject.get('{$this->widgetId}').setValue(value);
												}",
		'render'		 => "js:{
														option: function(item, escape){                      
														return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
														},
														option_create: function(data, escape){
														return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													   }
													}",
			) + $this->selectOptions,
		));
		echo CHtml::link("Add new address", "javascript:void(0)", ['class' => 'PAWToggleLink PAWAddLink', "data-val" => "1"]);
		echo '</div><div class="PAWNew ' . $hidePAC . '">';
		echo CHtml::link("Select existing address", "javascript:void(0)", ['class' => 'PAWToggleLink PAWExistingLink ' . $hideSelect, "data-val" => "2"]);
		$bounds						 = CJSON::decode($cityModel->cty_bounds);
		$this->PACOptions['bounds']	 = $bounds;
		$this->widget('application.widgets.PlaceAutoComplete', ['name'			 => "PAC_" . $this->widgetId, 'value'			 => $value,
			'htmlOptions'	 => ['class' => "form-control", "autocomplete" => "section-new", "placeholder" => "Type address or closest landmark"],
			"onLoad"		 => "function(event, object){AWObject.init('{$this->widgetId}','{$this->city}').addPAC(object);}",
			"onPlaceChange"	 => "function(event, pacObject){ 
						pacObject.validateAddress(event);
						AWObject.get('{$this->widgetId}').setValue(pacObject.model.valueField.value);
						}",
			"enableOnLoad"	 => !$PACReadOnly
				] + $this->PACOptions);
		echo "</div>" . CHtml::activeHiddenField($this->model, $this->attribute);
		echo CHtml::error($this->model, $this->attribute) . "</div>";
	}

	/**
	 * Registers the needed JavaScript.
	 */
	public function registerClientScript()
	{
		/* @var $cs CClientScript */
		$cs		 = Yii::app()->getClientScript();
		$jsVer	 = Yii::app()->params['siteJSVersion'];
		$cs->registerScriptFile("/js/gozo/addressWidget.js?v=$jsVer");
		$cs->registerScript($this->id, <<<JS
				AWObject.init('{$this->widgetId}', '{$this->city}', true);
JS
				, CClientScript::POS_READY);
	}

}
