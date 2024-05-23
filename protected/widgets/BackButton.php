<?php
class BackButton extends CInputWidget
{
	public $widgetId		 = null;
		
	public $reqid			 = '';
	public $pageid			 = 0;

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the autocomplete input.
	 */
	public function init()
	{

		if ($this->widgetId == null)
		{
//			$this->widgetId = $id;
		}
		if ($this->reqid == null)
		{
//			$this->reqid = $reqid;
		}
		if ($this->pageid == null)
		{
//			$this->pageid = $pageid;
		}
		$this->registerClientScript();
		echo '<a href="#" onclick = "backbutton('.$this->reqid.','.$this->pageid.')" class="btn-back"></a>';
	}

	/**
	 * Registers the needed JavaScript.
	 */
	public function registerClientScript()
	{
		/* @var $cs CClientScript */
		$cs		 = Yii::app()->getClientScript();
		$jsVer	 = Yii::app()->params['siteJSVersion'];
		$cs->registerScriptFile("/js/gozo/v3/backButton.js?v=$jsVer");
		$cs->registerScript($this->id, <<<JS
				AWObject.init('{$this->reqid}', '{$this->pageid}', true);
JS
				, CClientScript::POS_READY);
	}

}