<?php

/**
 * SeoControllerBehavior class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package ext.seo.components
 */
class SeoControllerBehavior extends CBehavior
{

	/**
	 * @property string the page meta description.
	 */
	public $metaDescription;

	/**
	 * @property string the page meta keywords.
	 */
	public $metaKeywords;

	/**
	 * @property array the page meta properties.
	 */
	public $metaProperties = array();

	/**
	 * @property string the canonical URL.
	 */
	public $canonical;

	/**
	 * Adds a meta property to the current page.
	 * @param string $name the property name
	 * @param string $content the property content
	 */
	public function addMetaProperty($name, $content)
	{
		$this->metaProperties[$name] = $content;
	}

	public function setRouteTags($rut_id)
	{
		$model		 = Route::model()->findByPk($rut_id);
		$fromCity	 = $model->rutFromCity->cty_name;
		$toCity		 = $model->rutToCity->cty_name;
		
		$arrPrices = $model->getRoutePrices();
		$obj		 = ['prices' => $arrPrices, 'fromCity' => $fromCity, 'toCity' => $toCity];

		$this->getOwner()->pageTitle = "{$fromCity} to {$toCity} Cab. Book outstation one-way, round trip, airport transfers or hourly rentals.";

		$rates						 = [];
		$rates[]					 = "{$arrPrices[1]['cab']}: ₹{$arrPrices[1]['base_amt']}";
		$rates[]					 = "{$arrPrices[2]['cab']}: ₹{$arrPrices[2]['base_amt']}";
		$rates[]					 = "{$arrPrices[3]['cab']}: ₹{$arrPrices[3]['base_amt']}";
		$this->metaDescription		 = "Book One way cab from {$fromCity} to {$toCity}. Or get a round trip cab or on a multi-city tour. Best price offer (" . implode(", ", $rates) . "). CALL +91-9051877000";
	    $this->metaKeywords = str_replace("#ROUTES#", "{$fromCity} to {$toCity}", 
				"#ROUTES# outstation cab, cab from #ROUTES#, hire cab from #ROUTES#, hire cab from #ROUTES#, rent cab from #ROUTES#, book cab from #ROUTES#, #ROUTES# cab service, "
				. "#ROUTES# Sedan, #ROUTES# Dzire, #ROUTES# Innova, #ROUTES# SUV, #ROUTES# Packages, #ROUTES# One way, {$fromCity} innova, #ROUTES# car hire, {$fromCity} car rental, "
				. "{$fromCity} outstation cab, {$fromCity} online cab booking, {$fromCity} car rental service, #ROUTES# lowest cab fares");
	}

}
