<?php

/**
 * IntlPhoneInput class file.
 *
 * @author Odirlei Santos
 * @version 0.1
 */
class IntlPhoneInput extends CInputWidget
{

	/**
	 * Use this property to set jQuery settings
	 * See {@link https://github.com/Bluefieldscom/intl-tel-input/#options}
	 * @var Array
	 */
	public $options;
	public $codeAttribute;
	public $numberAttribute;

	/**
	 * Use this property to update the data to only show localised country names.
	 * @var Boolean
	 */
	public $localisedCountryNames = true;

	/**
	 * Use this property to get the current number formatted to the [E.164 standard]
	 * See {@link http://en.wikipedia.org/wiki/E.164}
	 * @var Boolean
	 */
	public $E164 = true;

	/**
	 * Enable formatting/validation etc. by specifying the path to the included "utils.js" script
	 * @var String
	 */
	private $utilsScript;
	private $numberId;
	private $codeId;

	/**
	 * Executes the widget.
	 * This method registers all needed client scripts and renders
	 * the text field.
	 */
	public function run()
	{
		list($name, $id) = $this->resolveNameID();
		if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']	 = $id;
		if (!isset($this->htmlOptions['name']))
			$this->htmlOptions['name']	 = $name;

		$this->registerClientScript();

		if ($this->hasModel())
		{
			$value = $this->model->{$this->attribute};
			echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
		}
		else
		{
			$value = $this->value;
			echo CHtml::textField($this->htmlOptions['name'], $this->value, $this->htmlOptions);
		}
		$htmlOptions = $this->htmlOptions;
		//	unset($htmlOptions['id'], $htmlOptions['name']);
	}

	private function renderNumberInput()
	{
		if ($this->numberAttribute != '')
		{
			echo CHtml::activeHiddenField($this->model, $this->numberAttribute);
			$this->numberId					 = CHtml::activeId($this->model, $this->numberAttribute);
			$this->model->{$this->attribute} = '+' . $this->model->{$this->codeAttribute} . $this->model->{$this->numberAttribute};
		}
	}

	private function renderCodeInput()
	{
		if ($this->codeAttribute != '')
		{
			echo CHtml::activeHiddenField($this->model, $this->codeAttribute);
			$this->codeId = CHtml::activeId($this->model, $this->codeAttribute);
		}
	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	private function registerClientScript()
	{
		$assets				 = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		$lib				 = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		$this->utilsScript	 = $lib . '/js/utils.js';
		$this->renderCodeInput();
		$this->renderNumberInput();
		$config				 = $this->config();
		$options			 = CJavaScript::encode($config);
		$js					 = "\$(document).ready(function(){
				\$iti{$this->htmlOptions['id']} = \$('input#{$this->htmlOptions['id']}').intlTelInput($options);
				window.intlTelInputGlobals.loadUtils('{$this->utilsScript}');
			});
		";

		$clone	 = 'val()';
		if ($this->E164 === true)
			$clone	 = 'intlTelInput(\'getNumber\')';
		$js		 .= "						
				
				jQuery('#{$this->htmlOptions['id']}').change(function() {
						var code = \$iti{$this->htmlOptions['id']}.intlTelInput('getSelectedCountryData').dialCode;
						jQuery('#{$this->codeId}').val(code);
						var number = \$iti{$this->htmlOptions['id']}.intlTelInput('getNumber',intlTelInputUtils.numberFormat.E164);
						number = number.replace(\"+\"+code, \"\");
						jQuery('#{$this->numberId}').val(number);
				});
				var input = document.querySelector('#{$this->htmlOptions['id']}');
				input.addEventListener(\"countrychange\", function() {
				var code = \$iti{$this->htmlOptions['id']}.intlTelInput('getSelectedCountryData').dialCode;
				jQuery('#{$this->codeId}').val(code);
});
";

		if ($this->localisedCountryNames === true)
		{
			$js .= "var countryData =window.intlTelInputGlobals.getCountryData();
						$.each(countryData, function(i, country) {
								country.name = country.name.replace(/.+\((.+)\)/,'$1');
						});";
		}
		
		// Add other JavaScript methods to $js.
		// See https://github.com/Bluefieldscom/intl-tel-input#public-methods
		// See https://github.com/Bluefieldscom/intl-tel-input#static-methods

		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile($assets . '/css/intlTelInput.css');
		$cs->registerScriptFile($assets . '/js/intlTelInput-jquery.js');
		$cs->registerScript(__CLASS__ . '#' . $this->htmlOptions['id'], $js);
		$cs->registerCss("intPhone", '
									.iti-flag {background-image: url("' . $assets . '/img/flags.png");}

									@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
									  .iti-flag {background-image: url("' . $assets . '/img/flags@2x.png");}
									}

									.intl-tel-input {
									  display: block;
									}
									.intl-tel-input .selected-flag {
									  z-index: 4;
									}
									.intl-tel-input .country-list {
									  z-index: 5;
									}
								');
	}

	/**
	 * jQuery settings
	 * See {@link https://github.com/Bluefieldscom/intl-tel-input/#options}
	 * @return Array the options for the Widget
	 */
	private function config()
	{
		// Predefined settings.
		$options = array(
			'defaultCountry'	 => 'auto',
			'numberType'		 => 'MOBILE',
			'preferredCountries' => array('IN'),
			'responsiveDropdown' => true,
		);
		// Client options
		if (is_array($this->options))
		{
			foreach ($this->options as $key => $value)
				$options[$key] = $value;
		}
		// Specifies/overwrites the path to the included "utils.js" script
		$options['utilsScript'] = $this->utilsScript;
		return $options;
	}

}
