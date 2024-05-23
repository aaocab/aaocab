<?php

/**
 */
class SelectAddressV2 extends CInputWidget
{

	public $widgetId		 = null;
	public $isMobileView	 = false;
	public $city			 = 0;
	public $isAirport		 = false;
	public $isRailway        = false;
	public $user			 = 0;
	public $selectOptions	 = [];
	public $PACOptions		 = [];
	public $modalId			 = "myModal";
	private $fieldId		 = "";
	public $brtId			 = "";
	//public $isAirport		 = 0;
	public $platform         = 0;
	public $pickLater        = "";

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
		if (isset($this->htmlOptions['platform']))
		{
			$this->platform = $this->htmlOptions['platform'];
		}
		if (isset($this->htmlOptions['pickLater']))
		{
			$this->pickLater = $this->htmlOptions['pickLater'];
		}

		if ($this->widgetId == null)
		{
			$this->widgetId = $id . "_" . rand(100000, 9999999);
		}
		$this->fieldId = CHtml::activeId($this->model, $this->attribute);
$this->brtId;
		$this->registerClientScript();
		 $value	 = CHtml::value($this->model, $this->attribute);
		$text	 = "&nbsp;";
		if ($this->value != '')
		{
			$value = $this->value;
		}

		if ($value != '')
		{
			$obj = json_decode($value);
			if (property_exists($obj, "address"))
			{
				$text = $obj->address;
			}
		}
		else
		{
			$text = "&nbsp;";
		}


		echo "<div class='{$this->widgetId}'>";
		echo "<div class='{$this->fieldId} {$this->htmlOptions['class']}'>{$text}";
		echo '</div>';
		echo CHtml::activeHiddenField($this->model, $this->attribute);
		echo CHtml::error($this->model, $this->attribute) . "</div>";
	}

	/**
	 * Registers the needed JavaScript.
	 */
	public function registerClientScript()
	{
		/* @var $cs CClientScript */
		$cs			 = Yii::app()->getClientScript();
		$jsVer		 = Yii::app()->params['siteJSVersion'];
		$isAirport	 = ($this->isAirport) ? "1" : "0";
		$cs->registerScriptFile("/js/gozo/addressWidget.js?v=$jsVer");
		$cs->registerScript("SelectAddressWidget", <<<JS
				function openSelectAddress(city, widgetId, modalId, isAirport=0)
				{

					let form = $('.'+widgetId);
					$.ajax({
					"type": "GET",
					"url": '/lookup/selectAddressV2',
					"data": {city: city, airport: isAirport, callback: "selectAddressV2_"+widgetId},
				"beforeSend": function()
				{
					
				},
				"complete": function()
				{
					
				},
				"success": function(data2)
					{
						let modalWindow = "#" + modalId;
						$(modalWindow + " .modal-body").html(data2);
						$(modalWindow).modal().show();
						$('.modal-backdrop').last().css("display","block");
						if({$this->platform} == 1 || {$isAirport}==1)
						{
						   $(modalWindow + " .modal-body1").hide();
						   $(modalWindow + " .modal-body").show();
					    }
					}
				});

				}
				function setAddressCity(widgetId, value){
                  
					eval(widgetId + "_cityId = '"+value+"'");
					$("."+widgetId).prop("readOnly", (value == ''));
				}
JS
				, CClientScript::POS_READY);
		$cs->registerScript("selectAddressV2" . $this->fieldId . "_BEGIN", <<<JS
				var {$this->fieldId}_cityId = "{$this->city}";
				if(typeof {$this->fieldId}_oldFAddress == "undefined")
				{
					let {$this->fieldId}_oldFAddress = "";
				}
				if(typeof {$this->fieldId}_oldTAddress == "undefined")
				{
					let {$this->fieldId}_oldTAddress = "";
				}
				
                {$this->fieldId}_oldFAddress  =$('#{$this->brtId}_from_place_old').val();
                {$this->fieldId}_oldTAddress  =$('#{$this->brtId}_to_place_old').val();
				function selectAddressV2_{$this->widgetId}(val){
					
					
	                let {$this->fieldId}_newaddress = val.address;			
                    if({$this->fieldId}_oldFAddress != {$this->fieldId}_newaddress || {$this->fieldId}_oldTAddress != {$this->fieldId}_newaddress)
                        {
                         $('.saveAddress').show();
                        }

						let id = "{$this->fieldId}";
						$('.{$this->widgetId} .{$this->fieldId}').html(val.address);
						$('.{$this->widgetId} #{$this->fieldId}').val(JSON.stringify(val));
						
						if(window.validateAddressDayrental)
						{
							window['validateAddressDayrental']('{$this->widgetId}','{$this->fieldId}');
						}

						

						let modalWindow = "#{$this->modalId}";

						if({$this->platform} == 1)
						{
						   $('#myAddressModal1 .modal-body').hide();
						   $('#myAddressModal1 .modal-body1').show();
						   $(modalWindow).modal('show');
						}
						else
						{
                           $("#myAddressModal1" + " .modal-body").html("");
						   $('#myAddressModal1').modal().hide();
						   $('.modal-backdrop').last().css("display","none");
						   $("body").removeClass('modal-open');
                         }
						 if('{$this->pickLater}' != '')
						 { 
							  if($('#{$this->pickLater}').is(':checked'))
							  {
                                  $('#{$this->pickLater}').click();
                              }
                         }
					 }
JS, CClientScript::POS_BEGIN);
		$cs->registerScript("selectAddressV2" . $this->widgetId . "_READY", <<<JS
				var {$this->widgetId}_cityId = "{$this->city}";
				$('.{$this->widgetId}').prop("readOnly", ({$this->widgetId}_cityId == ''));

				$('.{$this->widgetId} .{$this->fieldId}').unbind("click").on("click", function(){

					let cityId = {$this->widgetId}_cityId;
					if(cityId == "")
					{
						return false;
					}
					openSelectAddress({$this->widgetId}_cityId, "{$this->widgetId}", "{$this->modalId}", "{$isAirport}");
				});
JS, CClientScript::POS_READY);
	}

}
