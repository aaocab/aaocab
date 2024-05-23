<?php

/**
 * IntlPhoneInput class file.
 *
 * @author Odirlei Santos
 * @version 0.1
 */
class TimePicker extends CInputWidget
{

	/**
	 * Use this property to set jQuery settings
	 * See {@link https://github.com/Bluefieldscom/intl-tel-input/#options}
	 * @var Array
	 */
	public $options;

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
		if ($this->hasModel())
		{
	//		$this->options['defaultTime'] = CHtml::resolveValue($this->model, $this->attribute);
		}
		else
		{
//			$this->options['defaultTime'] = $this->value;
		}
		$this->registerClientScript();

		if ($this->hasModel())
		{
			$value = CHtml::resolveValue($this->model, $this->attribute);
			echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
		}
		else
		{
			$value = $this->value;
			echo CHtml::textField($this->htmlOptions['name'], $this->value, $this->htmlOptions);
		}
	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	private function registerClientScript()
	{
		$assets	 = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		$config	 = $this->config();
		$options = CJSON::encode($config);
		$js		 = "$('#{$this->htmlOptions['id']}').timepicker({$options});";
		/** @var CClientScript $cs */
		$cs			 = Yii::app()->getClientScript();
		$cs->registerScriptFile($assets . '/js/jquery.timepicker.min.js', CClientScript::POS_END);
		$cs->registerCssFile($assets . '/css/jquery.timepicker.min.css');
		$cs->registerScript(__CLASS__ . '#' . $this->htmlOptions['id'], $js);
	}

	/**
	 * jQuery settings
	 * @return Array the options for the Widget
	 */
	private function config()
	{
		// Predefined settings.
		$options = array(
			'dropdown'	 => true,
			'scrollbar'	 => true,
			'minTime'	 => '0',
			'dynamic'	 => false,
			'interval'	 => 15,
			'timeFormat' => "hh:mm p",
			'zindex'	 => 1000,
		);
		// Client options
		if (is_array($this->options))
		{
			foreach ($this->options as $key => $value)
				$options[$key] = $value;
		}

		return $options;
	}

}
