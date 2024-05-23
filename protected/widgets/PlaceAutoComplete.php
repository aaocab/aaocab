<?php

/**
 */
class PlaceAutoComplete extends CInputWidget
{

	const API_URL = '//maps.googleapis.com/maps/api/js?';

	public $widgetId			 = null;
	public $textArea			 = false;
	public $libraries			 = 'places';
	public $sensor				 = true;
	public $language			 = 'en-US';
	public $autocompleteOptions	 = [
		'types'					 => [],
		'fields'				 => ['address_components', 'geometry', 'place_id', 'formatted_address'],
		'strictBounds'			 => 1,
		'componentRestrictions'	 => ['country' => 'IN']
	];
	public $bounds				 = null;
	private $elementId			 = null;
	public $onPlaceChange		 = '';
	public $showMarker			 = true;
	public $enableOnLoad		 = true;
	public $isMobileView		 = null;
	public $onLoad				 = null;

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
		
//		if($this->isMobileView == null)
//		{
//			$mobileDetect = new Mobile_Detect();
//			$this->isMobileView = ($mobileDetect->isMobile() || $mobileDetect->isTablet());
//		}

		$elementName	 = "txt_" . $this->widgetId . "_" . CHtml::$count++;
		$this->elementId = CHtml::getIdByName($elementName);


		$htmlOptions = ['class' => "hdn{$this->widgetId}"];
		echo '<div class="input-group input-simple-1">';
		if ($this->hasModel())
		{
			$value = CHtml::resolveValue($this->model, $this->attribute);
			if ($value instanceof \Stub\common\Place)
			{
				$this->model->{$this->attribute} = json_encode(Filter::removeNull($value));
			}

			echo CHtml::activeHiddenField($this->model, $this->attribute, $htmlOptions);
		}
		else if ($this->name !== null)
		{
			echo CHtml::hiddenField($name, $this->value, $htmlOptions);
			$value = $this->value;
		}

		$this->registerClientScript();
		if (is_string($value) && $value != "")
		{
			$obj = @json_decode($value);
			if (json_last_error() === JSON_ERROR_NONE)
			{
				$jmapObj					 = new JsonMapper();
				$jmapObj->bStrictNullTypes	 = false;
				$value						 = $jmapObj->map($obj, new Stub\common\Place);
			}
		}

		if ($value instanceof \Stub\common\Place)
		{
			$value = $value->address;
		}


		$field				 = $this->textArea ? 'textArea' : 'textField';
		$options			 = $this->htmlOptions;
		unset($options['id']);
		$options['class']	 .= " pacText txt{$this->widgetId}";
		echo CHtml::$field($elementName, $value, $options);
		//if ($this->showMarker && !$this->isMobileView)
		//{

			echo '<div class="input-group-append input-group-addon">
				<span class="pl5 pacGml_' . $this->widgetId . '" data-toggle="tooltip" data-original-title="Select exact location on map"><img src="/images/locator_icon4.png" alt="Precise location"></span>
			  </div>';
		//}

		echo '</div>';
	}

	/**
	 * Registers the needed JavaScript.
	 */
	public function registerClientScript()
	{
		$place		 = \Stub\common\Place::init(0, 0);
		$jsPlaceObj	 = json_encode($place);
		$elementId	 = $this->elementId;
		if ($this->bounds != null)
		{
			$bounds								 = $this->bounds;
            $expandBound = 0.1;
            $northEastLat = $bounds['northeast']['lat'];
            $northEastLon = $bounds['northeast']['lng'];
            $southWestLat = $bounds['southwest']['lat'];
            $southWestLon = $bounds['southwest']['lng'];
            
            $northEastBoundLat = $northEastLat + $expandBound;
			$northEastBoundLon = $northEastLon + $expandBound;
			$southWestBoundLat = $southWestLat - $expandBound;
			$southWestBoundLon = $southWestLon - $expandBound;

			$this->autocompleteOptions['bounds'] = new CJavaScriptExpression("new google.maps.LatLngBounds(
				new google.maps.LatLng({$southWestBoundLat}, {$southWestBoundLon}),
                new google.maps.LatLng({$northEastBoundLat}, {$northEastBoundLon}))
                ");
		}

		$scriptOptions	 = CJavaScript::encode($this->autocompleteOptions);
		$jsObject		 = "pacObject_{$this->widgetId}";
		if ($this->enableOnLoad)
		{
			$loadScript = "{$jsObject}.initControl({$scriptOptions});";
		}
		else
		{
			$loadScript = "{$jsObject}.disable();";
		}
		//$mobileScript = "";
		//if($this->isMobileView)
		//{
			$mobileScript = "{$jsObject}.bindOnClick();";
		//}

		$value	 = CHtml::resolveValue($this->model, $this->attribute);
		/* @var $cs CClientScript */
		$cs		 = Yii::app()->getClientScript();
		$jsVer	 = Yii::app()->params['siteJSVersion'];
		$cs->registerScriptFile("/js/gozo/city.js?v=$jsVer");
		$cs->registerScriptFile('/js/gozo/geocodeMarker.js?v=' . $jsVer);
		$cs->registerScriptFile('/js/gozo/placeAutoComplete.js?v=' . $jsVer);
		$cs->registerScript($this->id, <<<JS
			var {$jsObject} = new placeAutoComplete('{$elementId}', '{$this->htmlOptions['id']}', '{$this->widgetId}');
			{$jsObject}.onLoad({$this->onLoad});			
			$loadScript
			{$jsObject}.onPlaceChange({$this->onPlaceChange});
			{$jsObject}.model.placeObj = JSON.stringify({$jsPlaceObj});
			$(".pacGml_{$this->widgetId}").on("click", function(){
				{$jsObject}.openGeocodeMarker();
			});
			{$jsObject}.setValue('$value', true);
			{$mobileScript}
JS
				, CClientScript::POS_READY);
	}

}
