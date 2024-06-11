<?php
class SeoHead extends CWidget
{
	/**
	 * @property array the configuration for the title. 
	 * @see enableTitle
	 */
	public $title = array('class'=>'application.widgets.SeoTitle');
	/**
	 * @property boolean whether to enable the title.
	 */
	public $enableTitle = true;
	/**
	 * @property array the page http-equivs.
	 */
	public $httpEquivs = array();
	/**
	 * @property string the page meta description.
	 */
	public $defaultDescription;
	/**
	 * @property string the page meta keywords.
	 */
	public $defaultKeywords;
	/**
	 * @property array the page meta properties.
	 */
	public $defaultProperties = array();

	protected $_description;
	protected $_keywords;
	protected $_properties = array();
	protected $_canonical;

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		$behavior = $this->controller->asa('seo');

		if ($behavior !== null && $behavior->metaDescription !== null)
			$this->_description = $behavior->metaDescription;
		else if ($this->defaultDescription !== null)
			$this->_description = $this->defaultDescription;

		if ($behavior !== null && $behavior->metaKeywords !== null)
			$this->_keywords = $behavior->metaKeywords;
		else if ($this->defaultKeywords !== null)
			$this->_keywords = $this->defaultKeywords;

		if ($behavior !== null)
			$this->_properties = CMap::mergeArray($behavior->metaProperties, $this->defaultProperties);
		else
			$this->_properties = $this->defaultProperties;

		if ($behavior !== null && $behavior->canonical !== null)
			$this->_canonical = $behavior->canonical;
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderContent();
	}

	/**
	 * Renders the widget content.
	 */
	protected function renderContent()
	{
		$this->renderTitle();

		foreach ($this->httpEquivs as $name => $content)
			echo '<meta http-equiv="'.$name.'" content="'.$content.'" />';

		if ($this->_description !== null)
		{
			echo CHtml::metaTag($this->_description, 'description');
			echo CHtml::metaTag($this->_description, 'og:description');
		}

		if ($this->_keywords !== null)
			echo CHtml::metaTag($this->_keywords, 'keywords');

		foreach ($this->_properties as $name => $content)
			echo '<meta property="'.$name.'" content="'.$content.'" />'; // we can't use Yii's method for this.

		
		echo CHtml::metaTag("http://www.aaocab.com/images/car-rental.jpg", 'og:image');

		if ($this->_canonical !== null)
			$this->renderCanonical();
	}
	
	/**
	 * Renders the page title.
	 */
	protected function renderTitle()
	{
		if (!$this->enableTitle)
			return;

		$title = array();
		$class = 'application.widgets.SeoTitle';

		if (is_string($this->title))
			$class = $this->title;
		else if (is_array($this->title))
		{
			$title = $this->title;
			if (isset($title['class']))
			{
				$class = $title['class'];
				unset($title['class']);
			}
		}

		$this->widget($class, $title);
	}

	/**
	 * Renders the canonical link tag.
	 */
	protected function renderCanonical()
	{
		$request = Yii::app()->getRequest();
		$url = $request->getUrl();

		// Make sure that we do not create a recursive canonical redirect.
		if ($this->_canonical !== $url && $this->_canonical !== $request->getHostInfo().$url)
			echo '<link rel="canonical" href="'.$this->_canonical.'" />';
	}
}
