<?php

class CacheDependency extends CCacheDependency
{

	const Type_Cities				 = "cities";
	const Type_RoutePages			 = "routePages";
	const Type_Pages				 = "pages";
	const Type_Package			 = "package";
	const Type_Routes				 = "routes";
	const Type_Rates				 = "rates";
	const Type_CabTypes			 = "cabtypes";
	const Type_PriceRule			 = "pricerule";
	const Type_Vendor				 = "vendor";
	const Type_TransactionStats	 = "TransactionStats";
	const Type_Surge				 = "surge";
	const Type_Vehicle			 = "vehicle";
	const Type_Promo				 = "promo";
	const Type_HTMLPages			 = "HomePage";
	const Type_Zones				 = "zones";
	const Type_ServiceClass		 = "serviceclass";
	const Type_DashBoard			 = "dashboard";
	const Type_AdminAccess		 = "checkAdminAccess";
	const Type_Report_DashBoard	 = "reportDashboard";
	const Type_PartnerRule     = "partnerRule";


	private static $prefix	 = 'cacheDependency-';

	public static function setPrefix($prefix)
	{
		self::$prefix = $prefix;
	}

	public static function buildCacheId($cacheId)
	{
		return self::$prefix . $cacheId;
	}

	/**
	 * @var string the id of the cache whose value is to check
	 * if the dependency has changed.
	 * @see CCache::set
	 */
	public $cacheId = null;

	/**
	 * Constructor.
	 * @param string $id the id of the cache
	 */
	public function __construct($id = null)
	{
		$this->cacheId = $id;
	}

	/**
	 * Generates the data needed to determine if dependency has been changed.
	 * This method returns the value of the cache.
	 * @return mixed the data needed to determine if dependency has been changed.
	 */
	protected function generateDependentData()
	{
		if ($this->cacheId !== null)
			return Yii::app()->cache->get(self::buildCacheId($this->cacheId));
		else
			throw new CException(Yii::t('yii', 'CacheDependency.cacheId cannot be empty.'));
	}

}
