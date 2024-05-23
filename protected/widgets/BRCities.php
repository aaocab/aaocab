<?php

/**
 *
 * @property int $ Description
 */
class BRCities extends YiiSelectize
{

	const TYPE_SOURCE      = 1;
    const TYPE_DESTINATION = 2;

    public $type              = self::TYPE_SOURCE;
    public $widgetId          = null;
    public $enable            = true;
    public $airportOnly       = false;
    public $isCookieActive    = true;
    public $cookieSource      = 0;
    public $cookieDestination = 0;
  
    /**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the autocomplete input.
	 */
	public function init()
	{
        
		if ($this->hasModel())
		{
			$value = CHtml::resolveValue($this->model, $this->attribute);
		}
		else if ($this->name !== null)
		{
			$value = $this->value;
		}
		$enable		 = CJavaScript::encode($this->enable);
		$airportOnly = CJavaScript::encode($this->airportOnly);
        
       // && ($this->cookieDestination!= '' || $this->cookieDestination!=0)
        
		if ($this->type == self::TYPE_DESTINATION)
		{
             if($this->isCookieActive && ($value == "") )
            {
                $value= $this->cookieDestination;
            }
			$htmlOptions = ["class" => "ctySelect ctyDrop"];
			$option		 = ['onInitialize'	 => "js:function(){
					BRObject.init('{$this->widgetId}').airportDestination={$airportOnly};
					BRObject.init('{$this->widgetId}').initDestination(this, '{$value}',{$enable});
						}",
				'load'			 => "js:function(query, callback){
										let obj = BRObject.init('{$this->widgetId}');
										obj.load(callback, query, obj.model.destination);
									}",
				'onChange'		 => "js:function(value) {
											let obj = BRObject.init('{$this->widgetId}');
												obj.changeDestination(value);
											}",
				'onLoad'		 => "js:function(data) {

						let obj = BRObject.init('{$this->widgetId}');
						obj.onLoad(data, obj.model.destination);
					}",
			];
		}
		else
		{//&& ($this->cookieSource!= '' || $this->cookieSource!=0)
            if($this->isCookieActive  && $value == "")
            {
                $value= $this->cookieSource;
            }
			$htmlOptions = ["class" => "ctySelect ctyPickup"];
			$option		 = ['onInitialize'	 => "js:function(){
								BRObject.init('{$this->widgetId}').airportSource={$airportOnly};
							BRObject.init('{$this->widgetId}').initSource(this, '{$value}', {$enable});}",
				'load'			 => "js:function(query, callback){
                   
										let obj = BRObject.init('{$this->widgetId}');
												obj.load(callback, query, obj.model.source);
											}",
				'onChange'		 => "js:function(value) {
                   
											let obj = BRObject.init('{$this->widgetId}');
												obj.changeSource(value);;
											}",
				'onLoad'		 => "js:function(data) {

						let obj = BRObject.init('{$this->widgetId}');
						obj.onLoad(data, obj.model.source);
					}"
			];
		}
		
		$this->fullWidth = true;

		$defaultOptions			 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
			'fullWidth'			 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'loadThrottle'		 => 300,
			'addPrecedence'		 => false, 'maxOptions'		 => 6, 'deselectBehavior'	 => 'top', 'sortField'			 => "js:{field:'index','direction':'asc'}",
			'render'			 => "js:{option: function(item, escape){
										return '<div><span class=\"\"><i class=\"bx bxs-map p5 mr5\"></i>' + escape(item.text) +'</span></div>';
									},
									option_create: function(data, escape){
										return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
									}}"] + $option;
		$this->defaultOptions	 = array_merge_recursive($defaultOptions, $this->defaultOptions);
		if (!isset($this->htmlOptions['class']))
		{
			$this->htmlOptions['class'] = '';
		}
		$this->htmlOptions["class"]	 .= $htmlOptions["class"];
		$this->htmlOptions			 = $htmlOptions + $this->htmlOptions;
		
		parent::init();
	}

}
