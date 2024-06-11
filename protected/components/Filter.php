<?php

function encodeUrl($v)
{
	if (is_array($v))
	{
		return Filter::encodeURLinArray($v);
	}
	else
	{
		return urlencode($v);
	}
}

class Filter
{

	function __construct()
	{
		
	}

	public static function getToken()
	{
		return Yii::app()->request->getCsrfToken();
	}

	public static function writeToConsole($text)
	{
		if (Yii::app() instanceof CConsoleApplication)
		{
			echo "$text";
		}
	}

	/**
	 * Check and mask number if required otherwise return same number
	 * @param $number
	 * @param $agentId
	 * @return $number
	 */
	public static function processCustomerNumber($number, $agentId)
	{
		$maskNumbers = Yii::app()->params['maskNumbers'];

		if (!$maskNumbers)
		{
			goto skipMask;
		}
		$number = self::getCustomerMaskedNumbers($agentId);

		skipMask:
		return $number;
	}

	/**
	 * Check and mask number if required otherwise return same number
	 * @param $number
	 * @param $agentId
	 * @return $number
	 */
	public static function processDriverNumber($number, $agentId)
	{
		$maskNumbers = Yii::app()->params['maskNumbers'];

		if (!$maskNumbers)
		{
			goto skipMask;
		}

		$number = self::getDriverMaskedNumbers($agentId);

		skipMask:
		return $number;
	}

	public static function getDriverMaskedNumbers($agentId)
	{
		if (in_array($agentId, [450, 18190])) //for b2b mmt and ibibo
		{
			$number = Yii::app()->params['customerToDriverforMMT'];
		}
		else
		{
			$number = Yii::app()->params['customerToDriver'];
		}
		return $number;
	}

	public static function getCustomerMaskedNumbers($agentId)
	{
		$number = Yii::app()->params['driverToCustomer'];
		return $number;
	}

	public static function checkTheme()
	{
		$theme = (Yii::app()->mobileDetect->isMobile()) ? "mobile" : "Desktop";
		if ($_REQUEST['amp'] == 1)
		{
			$theme = "amp";
		}
		Filter::checkForMobileTheme();
		return $theme;
	}

	public static function checkForMobileTheme()
	{
		$detect			= Yii::app()->mobileDetect;
		$isMobileDetect = $detect->isMobile();

		if ($isMobileDetect == 1)
		{
			/** @var CWebApplication $app */
			$app = Yii::app();
			if (!defined("IS_MOBILE"))
			{
				define("IS_MOBILE", 1);
			}
			$app->clientScript->scriptMap = Yii::app()->params['script']['mobileB2C'];
			Yii::app()->theme			  = "mobile/B2C";
		}
	}

	public static function cloneObjectArray($objects)
	{
		$arr = [];
		foreach ($objects as $object)
		{
			$arr[] = clone $object;
		}
		return $arr;
	}

	public static function filterArray($my_array, $allowed)
	{
		$filtered = array_filter(
				$my_array, function ($val, $key) use ($allowed) { // N.b. $val, $key not $key, $val
					return isset($allowed[$key]) && (
					$allowed[$key] === true || $allowed[$key] === $val
					);
				}, ARRAY_FILTER_USE_BOTH
		);
		return $filtered;
	}

	public static function removeNull($object)
	{
		$obj = null;
		if (is_object($object))
		{
			$keys = get_object_vars($object);
			foreach ($keys as $key => $value)
			{
				$val = Filter::removeNull($value);
				if ($val !== null)
				{
					if ($obj == null)
					{
						$obj = new stdClass();
					}
					$obj->$key = $val;
				}
				else
				{
					continue;
				}
			}
		}
		else if (is_array($object))
		{
			$obj = array_map(function ($value) {
				return Filter::removeNull($value);
			}, $object);
			$obj = array_filter($obj, function ($value) {
				return ($value !== null);
			});
		}
		else
		{
			$obj = $object;
		}

		return $obj;
	}

	public static function getNestedValues($object)
	{
		if ($object === null)
		{
			return null;
		}

		$obj = [];
		if (is_object($object))
		{
			$keys = get_object_vars($object);
			foreach ($keys as $key => $value)
			{
				$val = Filter::getNestedValues($value);
				if ($val !== null)
				{
					$obj[] = $val;
				}
				else
				{
					continue;
				}
			}
		}
		else if (is_array($object))
		{
			$arr = array_values($object);
			foreach ($arr as $value)
			{
				if (is_object($value) || is_array($value))
				{
					$data = Filter::getNestedValues($value);
					$obj  = array_merge($obj, $data);
				}
				else
				{
					$obj[] = $value;
				}
			}
		}
		else
		{
			$obj[] = $object;
		}
		return $obj;
	}

	public static function calculateAreaByBounds($bounds)
	{
		if ($bounds == '')
		{
			return false;
		}

		$latNE = $bounds->northeast->latitude;
		$lngNE = $bounds->northeast->longitude;
		$latSW = $bounds->southwest->latitude;
		$lngSW = $bounds->southwest->longitude;

		$length = $this->calculateDistance($latNE, $lngNE, $latSW, $lngNE);
		$width	= $this->calculateDistance($latSW, $lngSW, $latSW, $lngNE);

		$area = $length * $width;
		return $area;
	}

	public static function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = "K")
	{
		if (($lat1 == $lat2) && ($lon1 == $lon2))
		{
			return 0;
		}
		else
		{
			$deltaLat	 = $lat2 - $lat1;
			$deltaLng	 = $lon2 - $lon1;
			$earthRadius = 6371 * 1000; // 3959 in miles 6371 in meters.
			$alpha		 = $deltaLat / 2;
			$beta		 = $deltaLng / 2;
			$a			 = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(deg2rad($beta)) * sin(deg2rad($beta));
			$c			 = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$distance	 = $earthRadius * $c;
			if ($unit == "K")
			{
				return $distance / 1000;
			}
			else if ($unit == "M")
			{
				return ($distance * 0.00062137);
			}
			else
			{
				return $distance;
			}
			return $distance;
		}
	}

	public static function encodeURLinArray($arr)
	{
		$arr1 = array_map('encodeUrl', $arr);
		return $arr;
	}

	public static function removeCommonWords($input)
	{

		// EEEEEEK Stop words
		$commonWords = array('a', 'able', 'about', 'above', 'abroad', 'according', 'accordingly', 'across', 'actually', 'adj', 'after', 'afterwards', 'again', 'against', 'ago', 'ahead', 'ain\'t', 'all', 'allow', 'allows', 'almost', 'alone', 'along', 'alongside', 'already', 'also', 'although', 'always', 'am', 'amid', 'amidst', 'among', 'amongst', 'an', 'and', 'another', 'any', 'anybody', 'anyhow', 'anyone', 'anything', 'anyway', 'anyways', 'anywhere', 'apart', 'appear', 'appreciate', 'appropriate', 'are', 'aren\'t', 'around', 'as', 'a\'s', 'aside', 'ask', 'asking', 'associated', 'at', 'available', 'away', 'awfully', 'b', 'back', 'backward', 'backwards', 'be', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'begin', 'behind', 'being', 'believe', 'below', 'beside', 'besides', 'best', 'better', 'between', 'beyond', 'both', 'brief', 'but', 'by', 'c', 'came', 'can', 'cannot', 'cant', 'can\'t', 'caption', 'cause', 'causes', 'certain', 'certainly', 'changes', 'clearly', 'c\'mon', 'co', 'co.', 'com', 'come', 'comes', 'concerning', 'consequently', 'consider', 'considering', 'contain', 'containing', 'contains', 'corresponding', 'could', 'couldn\'t', 'course', 'c\'s', 'currently', 'd', 'dare', 'daren\'t', 'definitely', 'described', 'despite', 'did', 'didn\'t', 'different', 'directly', 'do', 'does', 'doesn\'t', 'doing', 'done', 'don\'t', 'down', 'downwards', 'during', 'e', 'each', 'edu', 'eg', 'eight', 'eighty', 'either', 'else', 'elsewhere', 'end', 'ending', 'enough', 'entirely', 'especially', 'et', 'etc', 'even', 'ever', 'evermore', 'every', 'everybody', 'everyone', 'everything', 'everywhere', 'ex', 'exactly', 'example', 'except', 'f', 'fairly', 'far', 'farther', 'few', 'fewer', 'fifth', 'first', 'five', 'followed', 'following', 'follows', 'for', 'forever', 'former', 'formerly', 'forth', 'forward', 'found', 'four', 'from', 'further', 'furthermore', 'g', 'get', 'gets', 'getting', 'given', 'gives', 'go', 'goes', 'going', 'gone', 'got', 'gotten', 'greetings', 'h', 'had', 'hadn\'t', 'half', 'happens', 'hardly', 'has', 'hasn\'t', 'have', 'haven\'t', 'having', 'he', 'he\'d', 'he\'ll', 'hello', 'help', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'here\'s', 'hereupon', 'hers', 'herself', 'he\'s', 'hi', 'him', 'himself', 'his', 'hither', 'hopefully', 'how', 'howbeit', 'however', 'hundred', 'i', 'i\'d', 'ie', 'if', 'ignored', 'i\'ll', 'i\'m', 'immediate', 'in', 'inasmuch', 'inc', 'inc.', 'indeed', 'indicate', 'indicated', 'indicates', 'inner', 'inside', 'insofar', 'instead', 'into', 'inward', 'is', 'isn\'t', 'it', 'it\'d', 'it\'ll', 'its', 'it\'s', 'itself', 'i\'ve', 'j', 'just', 'k', 'keep', 'keeps', 'kept', 'know', 'known', 'knows', 'l', 'last', 'lately', 'later', 'latter', 'latterly', 'least', 'less', 'lest', 'let', 'let\'s', 'like', 'liked', 'likely', 'likewise', 'little', 'look', 'looking', 'looks', 'low', 'lower', 'ltd', 'm', 'made', 'mainly', 'make', 'makes', 'many', 'may', 'maybe', 'mayn\'t', 'me', 'mean', 'meantime', 'meanwhile', 'merely', 'might', 'mightn\'t', 'mine', 'minus', 'miss', 'more', 'moreover', 'most', 'mostly', 'mr', 'mrs', 'much', 'must', 'mustn\'t', 'my', 'myself', 'n', 'name', 'namely', 'nd', 'near', 'nearly', 'necessary', 'need', 'needn\'t', 'needs', 'neither', 'never', 'neverf', 'neverless', 'nevertheless', 'new', 'next', 'nine', 'ninety', 'no', 'nobody', 'non', 'none', 'nonetheless', 'noone', 'no-one', 'nor', 'normally', 'not', 'nothing', 'notwithstanding', 'novel', 'now', 'nowhere', 'o', 'obviously', 'of', 'off', 'often', 'oh', 'ok', 'okay', 'old', 'on', 'once', 'one', 'ones', 'one\'s', 'only', 'onto', 'opposite', 'or', 'other', 'others', 'otherwise', 'ought', 'oughtn\'t', 'our', 'ours', 'ourselves', 'out', 'outside', 'over', 'overall', 'own', 'p', 'particular', 'particularly', 'past', 'per', 'perhaps', 'placed', 'please', 'plus', 'possible', 'presumably', 'probably', 'provided', 'provides', 'q', 'que', 'quite', 'qv', 'r', 'rather', 'rd', 're', 'really', 'reasonably', 'recent', 'recently', 'regarding', 'regardless', 'regards', 'relatively', 'respectively', 'right', 'round', 's', 'said', 'same', 'saw', 'say', 'saying', 'says', 'second', 'secondly', 'see', 'seeing', 'seem', 'seemed', 'seeming', 'seems', 'seen', 'self', 'selves', 'sensible', 'sent', 'serious', 'seriously', 'seven', 'several', 'shall', 'shan\'t', 'she', 'she\'d', 'she\'ll', 'she\'s', 'should', 'shouldn\'t', 'since', 'six', 'so', 'some', 'somebody', 'someday', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhat', 'somewhere', 'soon', 'sorry', 'specified', 'specify', 'specifying', 'still', 'sub', 'such', 'sup', 'sure', 't', 'take', 'taken', 'taking', 'tell', 'tends', 'th', 'than', 'thank', 'thanks', 'thanx', 'that', 'that\'ll', 'thats', 'that\'s', 'that\'ve', 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'there\'d', 'therefore', 'therein', 'there\'ll', 'there\'re', 'theres', 'there\'s', 'thereupon', 'there\'ve', 'these', 'they', 'they\'d', 'they\'ll', 'they\'re', 'they\'ve', 'thing', 'things', 'think', 'third', 'thirty', 'this', 'thorough', 'thoroughly', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'till', 'to', 'together', 'too', 'took', 'toward', 'towards', 'tried', 'tries', 'truly', 'try', 'trying', 't\'s', 'twice', 'two', 'u', 'un', 'under', 'underneath', 'undoing', 'unfortunately', 'unless', 'unlike', 'unlikely', 'until', 'unto', 'up', 'upon', 'upwards', 'us', 'use', 'used', 'useful', 'uses', 'using', 'usually', 'v', 'value', 'various', 'versus', 'very', 'via', 'viz', 'vs', 'w', 'want', 'wants', 'was', 'wasn\'t', 'way', 'we', 'we\'d', 'welcome', 'well', 'we\'ll', 'went', 'were', 'we\'re', 'weren\'t', 'we\'ve', 'what', 'whatever', 'what\'ll', 'what\'s', 'what\'ve', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'where\'s', 'whereupon', 'wherever', 'whether', 'which', 'whichever', 'while', 'whilst', 'whither', 'who', 'who\'d', 'whoever', 'whole', 'who\'ll', 'whom', 'whomever', 'who\'s', 'whose', 'why', 'will', 'willing', 'wish', 'with', 'within', 'without', 'wonder', 'won\'t', 'would', 'wouldn\'t', 'x', 'y', 'yes', 'yet', 'you', 'you\'d', 'you\'ll', 'your', 'you\'re', 'yours', 'yourself', 'yourselves', 'you\'ve', 'z', 'zero');

		return preg_replace('/\b(' . implode('|', $commonWords) . ')\b/', '', $input);
	}

	function convertNumberToWord($num = false)
	{
		$num = str_replace(array(',', ' '), '', trim($num));
		if (!$num)
		{
			return false;
		}
		$num		= (int) $num;
		$words		= array();
		$list1		= array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
			'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
		);
		$list2		= array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
		$list3		= array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
			'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
			'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
		);
		$num_length = strlen($num);
		$levels		= (int) (($num_length + 2) / 3);
		$max_length = $levels * 3;
		$num		= substr('00' . $num, -$max_length);
		$num_levels = str_split($num, 3);
		for ($i = 0; $i < count($num_levels); $i++)
		{
			$levels--;
			$hundreds = (int) ($num_levels[$i] / 100);
			$hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : ' ' ) . ' ' : '');
			$tens	  = (int) ($num_levels[$i] % 100);
			$singles  = '';
			if ($tens < 20)
			{
				$tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
			}
			else
			{
				$tens	 = (int) ($tens / 10);
				$tens	 = ' ' . $list2[$tens] . ' ';
				$singles = (int) ($num_levels[$i] % 10);
				$singles = ' ' . $list1[$singles] . ' ';
			}
			$words[] = $hundreds . $tens . $singles . ( ( $levels && (int) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
		} //end for loop
		$commas = count($words);
		if ($commas > 1)
		{
			$commas = $commas - 1;
		}
		return implode(' ', $words);
	}

	public function dateCount($fromDate, $toDate)
	{
		$startTimeStamp = strtotime($fromDate);
		$endTimeStamp	= strtotime($toDate);
		$timeDiff		= abs($endTimeStamp - $startTimeStamp);
		$numberDays		= $timeDiff / 86400;  // 86400 seconds in one day
		// and you might want to convert to integer
		$numberDays		= intval($numberDays);
		return $numberDays;
	}

	public function utf8_to_unicode($str)
	{
		$unicode	= array();
		$values		= array();
		$lookingFor = 1;
		for ($i = 0; $i < strlen($str); $i++)
		{
			$thisValue = ord($str[$i]);
			if ($thisValue < 128)
			{
				$number	   = dechex($thisValue);
				$unicode[] = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
			}
			else
			{
				if (count($values) == 0)
					$lookingFor = ( $thisValue < 224 ) ? 2 : 3;
				$values[]	= $thisValue;
				if (count($values) == $lookingFor)
				{
					$number		= ( $lookingFor == 3 ) ?
							( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
							( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64
							);
					$number		= dechex($number);
					$unicode[]	= (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
					$values		= array();
					$lookingFor = 1;
				} // if
			} // if
		}
		return implode("", $unicode);
	}

	public function getMonthAlphabetic($num, $m, $y)
	{
		echo "aaaaaaaaaa";
		exit();
		echo $num, $m, $y;
		exit();
		switch ($num)
		{
			case '1':
				$year  = date('Y', strtotime(date("Y-m-01", strtotime("-1 Months"))));
				$month = date('n', strtotime(date("Y-m-01", strtotime("-1 Months"))));
				return ($year == $y && $month == $m) ? 'Month -1' : '';
				break;
			case '2':
				$year  = date('Y', strtotime(date("Y-m-01", strtotime("-2 Months"))));
				$month = date('n', strtotime(date("Y-m-01", strtotime("-2 Months"))));
				return ($year == $y && $month == $m) ? 'Month -2' : '';
				break;
			case '3':
				$year  = date('Y', strtotime(date("Y-m-01", strtotime("-3 Months"))));
				$month = date('n', strtotime(date("Y-m-01", strtotime("-3 Months"))));
				return ($year == $y && $month == $m) ? 'Month -3' : '';
				break;
			case '0':
				$year  = date('Y', strtotime(date("Y-m-d")));
				$month = date('n', strtotime(date("Y-m-d")));
				return ($year == $y && $month == $m) ? 'MTD' : '';
				break;
		}
	}

	public static function getServiceTax($amount, $partnerId, $tripType)
	{
		//$tax_rate	 = Filter::getServiceTaxRate();
		$tax_rate = BookingInvoice::getGstTaxRate($partnerId, $tripType);
		$tax	  = round($amount * $tax_rate * 0.01);
		return $tax;
	}

	public static function getServiceTaxRate()
	{
		$tax_rate = (date('Y-m-d') < '2017-07-01') ? Yii::app()->params['serviceTaxRate'] : Yii::app()->params['gst'];
		return $tax_rate;
	}

	public static function getServiceTaxType()
	{
		$tax_rate_type = (date('Y-m-d') < '2017-07-01') ? 1 : 2;
		return $tax_rate_type;
	}

	public function getDurationbyMinute($minutes, $type = 0)
	{
		$minutes = $type == 0 ? $minutes - ($minutes % 15) : $minutes;
		$sec	 = $minutes * 60;
		$days	 = floor($sec / 86400);
		$hours	 = floor(($sec - ($days * 86400)) / 3600);
		$thours	 = floor($sec / 3600);
		$min	 = $minutes - ($thours * 60);
		$dur	 = '';
		if ($days > 0)
		{
			$dur .= $days . " days ";
		}
		if ($hours > 0)
		{
			$dur .= $hours . " hrs ";
		}
		if ($min > 0)
		{
			$dur .= $type == 0 ? $min . " mins" : round($min, 2) . " mins";
		}
		return $dur;
	}

	public static function getTimeDurationbyMinute($minutes)
	{
		$hours = intdiv($minutes, 60);
		$min   = ($minutes % 60);
		if ($hours > 0)
		{
			$dur .= ($hours > 1) ? $hours . " hrs " : $hours . " hr ";
		}
		if ($min > 0)
		{
			$dur .= ($min > 1) ? $min . " mins" : $min . " min";
		}
		return $dur;
	}

	public static function getTripDayByRoute($bookingID)
	{
		$bookingRouteModel = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bookingID]);
		$cntRut			   = count($bookingRouteModel);
		if ($cntRut > 0)
		{
			$rutInfo  = [];
			$diffdays = 0;
			foreach ($bookingRouteModel as $key => $bookingRoute)
			{
				if ($key == 0)
				{
					$diffdays = 1;
				}
				else
				{
					$date1		= new DateTime(date('Y-m-d', strtotime($bookingRouteModel[0]->brt_pickup_datetime)));
					$date2		= new DateTime(date('Y-m-d', strtotime($bookingRoute->brt_pickup_datetime)));
					$difference = $date1->diff($date2);
					$diffdays	= ($difference->d + 1);
				}
				$rutInfo[] = ['diffdays' => $diffdays];
			}
			return $rutInfo[$cntRut - 1]['diffdays'];
		}
	}

	public static function getExecutionTime()
	{
		list($usec, $sec) = explode(" ", microtime());
		$time1 = ((float) $usec + (float) $sec);
		$time  = round(($time1 - TIME) * 1000, 3);
		return $time;
	}

	public static function getExecutionTimeDiff($desc = 'time')
	{
		list($usec, $sec) = explode(" ", microtime());
		$time1 = ((float) $usec + (float) $sec);
		$time  = round(($time1 - TIME) * 1000, 3);
		if (otime == 'otime')
		{
			define('otime', $time);
		}
		$otime = otime;
		echo "$desc:<br>" . ($time - $otime);
		echo "<br><br>";
	}

	public static function getLogCategory()
	{
		$category = "application";
		if (isset($GLOBALS['logCategory']))
		{
			$category = $GLOBALS['logCategory'];
		}
		return $category;
	}

	public static function setLogCategory($param)
	{
		$GLOBALS['logCategory'] = $param;
	}

	public static function createLog($desc, $logLevel = CLogger::LEVEL_TRACE)
	{
		$time = Filter::getExecutionTime();
		Yii::log("[$time] $desc", $logLevel, Filter::getLogCategory());
	}

	public static function round_up($value, $precision)
	{
		$pow = pow(10, $precision);
		return ( ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value)) ) / $pow;
	}

	public static function round_half_up($amt)
	{
		return round($amt, 0, PHP_ROUND_HALF_UP);
	}

	public static function getTimeDropArr($startTime = '00:00', $allowNight = true)
	{
		$arrTime  = array();
		$endTime  = 24 * 60;
		$endTime  = ($allowNight) ? $endTime : 15 * 60;
		$interval = 30;
		$d		  = date('H:i', strtotime($startTime));
		for ($i = 0; $i < $endTime; $i += $interval)
		{
			//$dateID			 = date('H:i:s', strtotime($d . ' +' . $i . 'MINUTE'));
			$dateID			  = date('h:iA', strtotime($d . ' +' . $i . 'MINUTE'));
			$dateVal		  = date('h:i A', strtotime($d . ' +' . $i . 'MINUTE'));
			$arrTime[$dateID] = $dateVal;
		}
		return $arrTime;
	}

	public function getTimeDropArrJson($startTime = '00:00')
	{
		$arrTime = Filter::getTimeDropArr($startTime);
		foreach ($arrTime as $k => $v)
		{
			$arrTime[] = array("id" => $k, "text" => $v);
		}
		$data = CJSON::encode($arrTime);
		return $data;
	}

	public function file_get_contents_curl($url)
	{
		$ch	  = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public static function beginTransaction()
	{
		$trans	  = null;
		$isActive = Yii::app()->db->getCurrentTransaction();
		if (!$isActive)
		{
			$trans = Yii::app()->db->beginTransaction();
		}
		return $trans;
	}

	public static function commitTransaction($transaction)
	{
		$success = false;
		if ($transaction != null)
		{
			$transaction->commit();
			$success = true;
		}
		return $success;
	}

	public static function rollbackTransaction($transaction)
	{
		$success = false;
		if ($transaction != null)
		{
			$transaction->rollback();
			$success = true;
		}
		return $success;
	}

	public static function GetPGObject($ptpId)
	{
		$obj = null;
		switch ($ptpId)
		{
			case PaymentType::TYPE_PAYTM:
				$obj		= Yii::app()->paytm;
				break;
			case PaymentType::TYPE_PAYUMONEY:
				$obj		= Yii::app()->payu;
				break;
			case PaymentType::TYPE_EBS:
				$RETURN_URL = Yii::app()->createAbsoluteUrl("/ebs/response");
				$obj		= new EbsPayment($RETURN_URL);
				break;
			case PaymentType::TYPE_FREECHARGE:
				$obj		= Yii::app()->freecharge;
				break;
			case PaymentType::TYPE_MOBIKWIK:
				$obj		= Yii::app()->mobikwik;
				break;
			case PaymentType::TYPE_LAZYPAY:
				$obj		= Yii::app()->lazypay;
				break;
			case PaymentType::TYPE_EPAYLATER:
				$obj		= Yii::app()->epaylater;
				break;
			case PaymentType::TYPE_PAYNIMO;
				$obj		= new Paynimo();
				break;
			case PaymentType::TYPE_INTERNATIONAL_CARD://BTREE
				$obj		= 'bTree';
				break;
			case PaymentType::TYPE_RAZORPAY:
				$obj		= Yii::app()->razorpay;
				break;
			case PaymentType::TYPE_EASEBUZZ:
				$obj		= Yii::app()->easebuzz;
				break;
			default:
				break;
		}
		return $obj;
	}

	public static function getPartnerObject($agtId)
	{
		$obj = null;
		switch ($agtId)
		{
			case 18190:
				$obj = new GoMmt();
				break;
			case Config::get('spicejet.partner.id'):
				$obj = new Spicejet();
				break;
			case Config::get('transferz.partner.id'):
				$obj = new Transferz();
				break;
			case 3936 || 12074 || 21937 || 13749 || 30242 || 22310 || 22311 || 30228 || 35968 || 35777 || 42596 || 36004 || 410145 || 36052 || 435251:
				$obj = new ChannelPartner();
				break;
			default:
				break;
		}
		return $obj;
	}

	public static function checkAssignmentAccess($regionId)
	{
		$arr = [
			1 => "assignUnapprovedNorth",
			4 => "assignUnapprovedSouth",
			2 => "assignUnapprovedWest",
			5 => "assignUnapprovedEast",
			3 => "assignUnapprovedCentral",
			6 => "assignUnapprovedNorthEast",
			7 => "assignUnapprovedSouth",
		];

		$operation = $arr[$regionId];

		$webUser = UserInfo::getInstance()->getUser();
		if ($webUser instanceof AdminWebUser)
		{
			return $webUser->checkAccess($operation);
		}
		else if (UserInfo::getInstance()->getUserType() == UserInfo::TYPE_SYSTEM)
		{
			return true;
		}
		else if (UserInfo::getInstance()->getUserType() == UserInfo::TYPE_CONSUMER)
		{
			return true;
		}
		return false;
	}

	public static function getTimeDiff($fromDateTime, $toDateTime = null)
	{
		try
		{
			if ($toDateTime == null)
			{
				$toDateTime = Yii::app()->db->createCommand()->select(new CDbExpression("now()"))->queryScalar();
			}

			$sql			  = "SELECT TIMESTAMPDIFF(MINUTE, :toDate , :fromDate) as diff";
			$cdb			  = Yii::app()->db->createCommand($sql);
			$arr[":fromDate"] = $fromDateTime;
			$arr[":toDate"]	  = $toDateTime;
			$diff			  = $cdb->queryScalar($arr);
		}
		catch (Exception $e)
		{
			$diff = null;
		}
		return $diff;
	}

	public static function getTimeDiffinSeconds($fromDateTime, $toDateTime = null)
	{
		try
		{
			if ($toDateTime == null)
			{
				$toDateTime = Yii::app()->db->createCommand()->select(new CDbExpression("now()"))->queryScalar();
			}

			$sql			  = "SELECT TIMESTAMPDIFF(SECOND, :toDate , :fromDate) as diff";
			$cdb			  = Yii::app()->db->createCommand($sql);
			$arr[":fromDate"] = $fromDateTime;
			$arr[":toDate"]	  = $toDateTime;
			$diff			  = $cdb->queryScalar($arr);
		}
		catch (Exception $e)
		{
			$diff = null;
		}
		return $diff;
	}

	public static function getDaysCount($fromDate, $toDate)
	{
		$date1		= new DateTime(date('Y-m-d', strtotime($fromDate)));
		$date2		= new DateTime(date('Y-m-d', strtotime($toDate)));
		$difference = $date1->diff($date2);
		$diffdays	= ($difference->d + 1);
		return $diffdays;
	}

	public static function getCodeById($Id = 0, $type = 'vendor')
	{
		$success	 = false;
		$arr		 = [
			'0' => 'Q',
			'1' => 'C',
			'2' => 'F',
			'3' => 'M',
			'4' => 'U',
			'5' => 'H',
			'6' => 'B',
			'7' => 'L',
			'8' => 'A',
			'9' => 'R'];
		$returnArray = ['success' => $success, 'code' => $code];
		if ($Id > 0 && $type != '')
		{
			$setCode = '';
			foreach (str_split(str_pad($Id, 6, 0, STR_PAD_LEFT)) as $v)
			{
				$setCode .= $arr[$v];
			}
			switch ($type)
			{
				case 'vendor':
					$code	 = 'V-' . $setCode;
					$isExist = Vendors::model()->findByCode($code);
					$success = ($isExist == 1) ? 0 : 1;
					break;
				case 'driver':
					$code	 = 'D-' . $setCode;
					$isExist = Drivers::model()->findByCode($code);
					$success = ($isExist == 1) ? 0 : 1;
					break;
				case 'car':
					$code	 = 'C-' . $setCode;
					$model	 = Vehicles::model()->findByCode($code);
					$success = ($isExist == 1) ? 0 : 1;
					break;
				case 'agent':
					$code	 = 'A-' . $setCode;
					$model	 = Agents::model()->findByCode($code);
					$success = ($isExist == 1) ? 0 : 1;
					break;
				default:
					$code	 = '';
					break;
			}
			$returnArray = ['success' => $success, 'code' => $code];
		}
		return $returnArray;
	}

	public static function writeLog($content, $traceLog = true, $fileName = null)
	{
		if (!$traceLog)
		{
			return;
		}
		if ($fileName == null)
		{
			$fileName = Yii::app()->runtimePath . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . 'trace.log';
		}

		$append = true;
		if (!file_exists($fileName))
		{
			mkdir(dirname($fileName), 0775, true);
		}

		$logPath = $fileName;
		$mode	 = (!file_exists($logPath)) ? 'w' : 'a';
		if (file_exists($logPath) && $append)
		{
			$prefix = "\r\n";
		}
		$logfile = fopen($logPath, $mode);
		$res	 = fwrite($logfile, $prefix . $content);
		fclose($logfile);
	}

	public static function WriteFile($path, $fileName, $content, $append = true, $appendPrefix = false)
	{
		$res = false;
		try
		{
			if (!file_exists($path))
			{
				mkdir($path, 0775, true);
			}

			$logPath = $path . DIRECTORY_SEPARATOR . $fileName;
			$mode	 = (!file_exists($logPath) || !$append) ? 'w' : 'a';
			$prefix	 = "";
			if ($appendPrefix !== false)
			{
				$prefix = $appendPrefix;
			}
			if (file_exists($logPath) && $append && $appendPrefix === false)
			{
				$prefix = "\r\n\r\n===== (MMT_CURL_HTTPCODE: {$GLOBALS['MMT_CURL_HTTPCODE']}) (MMT_CURL_ERRNO: {$GLOBALS['MMT_CURL_ERRNO']}) =======\r\n\r\n";
			}
			$logfile = fopen($logPath, $mode);
			$res	 = fwrite($logfile, $prefix . $content);
			fclose($logfile);
			Logger::info("EmailLog Write Done: $logPath");
			return $logPath;
		}
		catch (Exception $ex)
		{
			Logger::create("EmailLog Write: {$ex->getMessage()}", CLogger::LEVEL_ERROR);
			throw $ex;
			return $res;
		}
	}

	public static function createFolderPrefix($date = '', $includeHour = false)
	{
		if ($date == '')
		{
			$date = time();
		}
		$year  = date('Y', $date);
		$month = date('m', $date);
		$today = date('d', $date);
		$hour  = date('H', $date);

		$subFolderYear	= $year;
		$subFolderMonth = $subFolderYear . DIRECTORY_SEPARATOR . $month;
		$subFolderDay	= $subFolderMonth . DIRECTORY_SEPARATOR . $today;
		if ($includeHour)
		{
			$subFolderDay = $subFolderDay . DIRECTORY_SEPARATOR . $hour;
		}
		return $subFolderDay;
	}

	public static function ObjectArrayToArrayList($arrObject, $key, $value)
	{
		$arr = [];
		foreach ($arrObject as $object)
		{
			$arr[$object->{$key}] = $object->{$value};
		}
		return $arr;
	}

	public static function timeToSeconds($time)
	{
		$dt		 = new DateTime("1970-01-01 $time", new DateTimeZone('UTC'));
		$seconds = (int) $dt->getTimestamp();
		return $seconds;
	}

	public static function calculateMedian($arr)
	{
		$count	= count($arr); //total numbers in array
		$median = 0;
		if ($count == 0)
		{
			goto result;
		}
		sort($arr);
		$arr = array_values($arr);
		// find the middle value, or the lowest middle value
		if ($count % 2)
		{
			$middleval = floor(($count + 1) / 2);
			$median	   = $arr[$middleval - 1];
		}
		else
		{
			$middleval = floor(($count) / 2);
			$low	   = $arr[$middleval - 1];
			$high	   = $arr[$middleval];
			$median	   = (($low + $high) / 2);
		}
		result:
		return $median;
	}

	public static function checkProcess($processName, $maxTime = 0)
	{
		$success = false;
		exec("ps -eAo \"%p,%t,%a\" | grep -i '$processName' | grep -v grep", $pids);
		if (empty($pids) || count($pids) < 2)
		{
			print "$processName not running!\n";
			$success = true;
		}
		else
		{
			print_r($pids);
			foreach ($pids as $pid)
			{
				$arr	 = explode(",", $pid);
				$time	 = trim($arr[1]);
				$arrTime = explode(":", $time);
				if (count($arrTime) <= 2)
				{
					$time = "00:" . $time;
				}

				if (Filter::timeToSeconds($time) > $maxTime && $maxTime > 0)
				{
					exec("kill {$arr[0]}");
					print_r($arr);
					$success = true;
				}
			}
			print "$processName already running...\n";
		}
		return $success;
	}

	public static function getGstin($date)
	{
		$date	   = date("Y/m/d", strtotime($date));
		$dateCheck = date("Y/m/d", strtotime("2019/03/31"));
		$gstin	   = ($date > $dateCheck) ? "06AAFCG0222J1Z0" : "04AAFCG0222J1Z4";
		return $gstin;
	}

	public static function checkImage($type)
	{
		$mime_types = ['image/jpeg', 'image/pipeg', 'image/bmp', 'image/png', 'image/svg+xml', 'image/gif'];
		return (in_array($type, $mime_types));
	}

	public static function resizeImage($sourceFilePath, $maxWidth, $destinationFilePath, $maxHeight = 0)
	{
		$gis  = getimagesize($sourceFilePath);
		$type = $gis[2];
		switch ($type)
		{
			case "1": $imorig = imagecreatefromgif($sourceFilePath);
				break;
			case "2": $imorig = imagecreatefromjpeg($sourceFilePath);
				break;
			case "3": $imorig = imagecreatefrompng($sourceFilePath);
				break;
			default: $imorig = imagecreatefromjpeg($sourceFilePath);
		}
		$x	 = imagesx($imorig);
		$y	 = imagesy($imorig);
		$woh = (!$maxHeight) ? $gis[0] : $gis[1];
		if ($woh <= $maxWidth)
		{
			$aw = $x;
			$ah = $y;
		}
		else
		{
			if (!$maxHeight)
			{
				$aw = $maxWidth;
				$ah = $maxWidth * $y / $x;
			}
			else
			{
				$aw = $maxWidth * $x / $y;
				$ah = $maxWidth;
			}
		}
		$im = imagecreatetruecolor($aw, $ah);
		if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y))
		{
			if (imagejpeg($im, $destinationFilePath))
			{
				Yii::log("Image Resampled: " . $destinationFilePath, CLogger::LEVEL_INFO, 'system.api.images');

				return true;
			}
			else
			{
				return false;
			}
		}
	}

	public static function getDBDateTime()
	{
		$now = Yii::app()->db->createCommand()->select(new CDbExpression("now()"))->queryScalar();
		return $now;
	}

	public static function addWorkingMinutes($minutes, $startTime = null)
	{
		if ($startTime == null)
		{
			$startTime = new CDbExpression("NOW()");
		}
		$time = "'$startTime'";
		if ($startTime instanceof CDbExpression)
		{
			$time = $startTime->expression;
		}
		$sql = "SELECT addWorkingMinutes(:minutes, $time) FROM dual";
		$res = Yii::app()->db->createCommand($sql)->queryScalar(['minutes' => $minutes]);
		return $res;
	}

	public static function subWorkingMinutes($minutes, $startTime = null)
	{
		if ($startTime == null)
		{
			$startTime = new CDbExpression("NOW()");
		}
		$time = "'$startTime'";
		if ($startTime instanceof CDbExpression)
		{
			$time = $startTime->expression;
		}
		$sql = "SELECT subWorkingMinutes(:minutes, $time) FROM dual";
		$res = Yii::app()->db->createCommand($sql)->queryScalar(['minutes' => $minutes]);
		return $res;
	}

	public static function CalcWorkingHour($fromDate, $toDate)
	{
		$fromTime = "'$fromDate'";
		$toTime	  = "'$toDate'";
		if ($fromDate instanceof CDbExpression)
		{
			$fromTime = $fromDate->expression;
		}
		if ($toDate instanceof CDbExpression)
		{
			$toTime = $toDate->expression;
		}
		$sql = "SELECT CalcWorkingHour($fromTime, $toTime) FROM dual";
		$res = Yii::app()->db->createCommand($sql)->queryScalar();
		return $res;
	}

	public static function CalcWorkingMinutes($fromDate, $toDate)
	{
		$fromTime = "'$fromDate'";
		$toTime	  = "'$toDate'";
		if ($fromDate instanceof CDbExpression)
		{
			$fromTime = $fromDate->expression;
		}
		if ($toDate instanceof CDbExpression)
		{
			$toTime = $toDate->expression;
		}
		$sql = "SELECT CalcWorkingMinutes($fromTime, $toTime) FROM dual";
		$res = DBUtil::queryScalar($sql);
		return $res;
	}

	public static function getDateFormatted($date)
	{
		return date('d/m/Y h:i A', strtotime($date));
	}

	public static function viewPath()
	{
		$viewpath = "protected/views/";
		return $viewpath;
	}

	/**
	 * Validates a given latitude $lat
	 *
	 * @param float|int|string $lat Latitude
	 * @return bool `true` if $lat is valid, `false` if not
	 */
	public static function validateLatitude($lat)
	{
		return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat);
	}

	/**
	 * Validates a given longitude $long
	 *
	 * @param float|int|string $long Longitude
	 * @return bool `true` if $long is valid, `false` if not
	 */
	public static function validateLongitude($long)
	{
		return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long);
	}

	/**
	 * Validates a given coordinate
	 *
	 * @param float|int|string $lat Latitude
	 * @param float|int|string $long Longitude
	 * @return bool `true` if the coordinate is valid, `false` if not
	 */
	public static function validateLatLong($lat, $long)
	{
		return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $lat . ',' . $long);
	}

	public static function getCoordinates($lat, $long)
	{
		$success = Filter::validateLatLong($lat, $long);
		if (!$success)
		{
			return false;
		}
		return $coordinates = trim($lat) . ',' . trim($long);
	}

	public static function strReplace($found)
	{
		$found = str_replace(" ", '', $found);
		$found = str_replace(".", '', $found);
		return $found;
	}

	/**
	 *
	 * @param integer $number
	 * @return string
	 */
	public static function maskPhoneNumber($number)
	{
		$mask_number = str_repeat("*", strlen($number) - 4) . substr($number, -4);
		return $mask_number;
	}

	/**
	 *
	 * @param string $email
	 * @return string
	 */
	public static function maskEmalAddress($email)
	{
		$em			= explode("@", $email);
		$name		= implode(array_slice($em, 0, count($em) - 1), '@');
		$len		= floor(strlen($name) / 2);
		$mask_email = substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
		return $mask_email;
	}

	function convertToObject($array)
	{
		$object = new \stdClass();
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$value = Filter::convertToObject($value);
			}
			$object->$key = $value;
		}
		return $object;
	}

	public static function checkLatLongBound($lat, $long, $nwLat, $nwLong, $swLat, $swLong, $precession = 0.05)
	{
		if ((($nwLat + $precession) >= $lat && $lat >= ($swLat - $precession)) && (($nwLong + $precession) >= $long && $long >= ($swLong - $precession)))
		{
			return true;
		}
		return false;
	}

	public static function isJSON($value)
	{
		json_decode($value);
		if (json_last_error() !== JSON_ERROR_NONE || $value == "")
		{
			return false;
		}
		return true;
	}

	public static function checkBoundsWithinBounds($largeBounds, $smallBounds, $precession = 0.02)
	{
		if (!Filter::isJSON($largeBounds) || !Filter::isJSON($smallBounds))
		{
			return false;
		}

		$objLargeBounds = json_decode($largeBounds);
		$neLat			= $objLargeBounds->northeast->lat;
		$neLong			= $objLargeBounds->northeast->lng;
		$swLat			= $objLargeBounds->southwest->lat;
		$swLong			= $objLargeBounds->southwest->lng;

		$objSmallBounds = json_decode($smallBounds);

		$northEastLat  = $objSmallBounds->northeast->lat;
		$northEastLong = $objSmallBounds->northeast->lng;

		$southEastLat  = $objSmallBounds->southwest->lat;
		$southEastLong = $objSmallBounds->northeast->lng;

		$northWestLat  = $objSmallBounds->northeast->lat;
		$northWestLong = $objSmallBounds->southwest->lng;

		$southWestLat  = $objSmallBounds->southwest->lat;
		$southWestLong = $objSmallBounds->southwest->lng;

		$check = self::checkLatLongBound($northEastLat, $northEastLong, $neLat, $neLong, $swLat, $swLong, $precession);
		$check = $check && self::checkLatLongBound($southEastLat, $southEastLong, $neLat, $neLong, $swLat, $swLong, $precession);
		$check = $check && self::checkLatLongBound($northWestLat, $northWestLong, $neLat, $neLong, $swLat, $swLong, $precession);
		$check = $check && self::checkLatLongBound($southWestLat, $southWestLong, $neLat, $neLong, $swLat, $swLong, $precession);

		return $check;
	}

	public static function dboApplicable($model)
	{
		$pickupDateTime = $model->bkg_pickup_date;
		if (empty($pickupDateTime))
		{
			$pickupDateTime = $model->bookingRoutes[0]->brt_pickup_datetime;
		}
		$dboApplicable = false;
		$sdoSettings   = Config::get('dbo.b2c.settings');
		if (!empty($sdoSettings))
		{
			$vehicleTypeId = $model->bkg_vehicle_type_id;
			if (!empty($pickupDateTime) && !empty($vehicleTypeId))
			{
				$result			= CJSON::decode($sdoSettings);
				$currentCabType = SvcClassVhcCat::getClassById($vehicleTypeId);
				$settingsData	= $result[$currentCabType];
				if (!empty($settingsData))
				{
					$dboEnabled = $settingsData['enabled'];
					if ($dboEnabled == 1)
					{
						$dboStartDate	   = strtotime($settingsData['startDate']);
						$dboEndDate		   = strtotime($settingsData['endDate']);
						$dboPickupDateTime = strtotime($pickupDateTime);
						$currDateTime	   = self::getDBDateTime();
						$workingHRDiff	   = self::CalcWorkingHour($currDateTime, $pickupDateTime);
						if ($workingHRDiff >= 42 && $dboEndDate >= $dboPickupDateTime && $dboStartDate <= $dboPickupDateTime)
						{
							$dboApplicable = true;
						}
					}
				}
			}
		}
		return $dboApplicable;
	}

	public static function removeEmptyKeysFromArray($arr)
	{
		foreach ($arr as $key => $value)
		{
			if (is_array($value))
			{
				Filter::removeEmptyKeysFromArray($value);
			}
			if (empty($value))
				unset($arr[$key]);
		}
		return $arr;
	}

	public static function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false)
	{
		$temp = array();
		foreach ($arr as $key => $value)
		{
			$groupValue = $value[$group];
			if (!$preserveGroupKey)
			{
				unset($arr[$key][$group]);
			}
			if (!array_key_exists($groupValue, $temp))
			{
				$temp[$groupValue] = array();
			}

			if (!$preserveSubArrays)
			{
				$data = count($arr[$key]) == 1 ? array_pop($arr[$key]) : $arr[$key];
			}
			else
			{
				$data = $arr[$key];
			}
			$temp[$groupValue][] = $data;
		}
		return $temp;
	}

	public static function scheduleTimeInterval()
	{
		$arr = ['15' => '15 Minutes', '30' => '30 Minutes', '45' => '45 Minutes', '60' => '1 Hour', '75' => '75 Minutes', '90' => '1.5 Hours', '120' => '2 Hours'];
		return $arr;
	}

	public static function scheduleTimePrePost()
	{
//		$arr = [0 => 'Pre-Pone', 1 => 'Post-Pone'];
		$arr = [1 => 'Post-Pone'];
		return $arr;
	}

	public static function bookingTypePrefixes($distinct = false, $value = false)
	{
		if ($distinct)
		{
			$arr = ['1' => 'OW', '2' => 'RT', '3' => 'RT', '4' => 'AT', '5' => 'PT', '7' => 'SH', '8' => 'CT', '9' => 'DR_4-40', '10' => 'DR_8-80', '16' => 'DR_10-100', '11' => 'DR_12-120', '15' => 'LT'];
		}
		else
		{
			$arr = ['1' => 'OW', '2' => 'RT', '3' => 'RT', '4' => 'AT', '5' => 'PT', '7' => 'SH', '8' => 'CT', '9' => 'DR', '10' => 'DR', '16' => 'DR', '11' => 'DR', '15' => 'LT'];
		}
		if ($value)
		{
			return $arr[$value];
		}
		return $arr;
	}

	public static function bookingTypes($bkgType = 0, $distinct = false)
	{
		if ($distinct)
		{
			$arr = ['1'	 => 'One Way', '2'	 => 'Round Trip', '3'	 => 'Multi Trip', '4'	 => 'Airport Transfer',
				'5'	 => 'Package', '6'	 => 'Flexxi', '7'	 => 'Shuttle', '8'	 => 'Custome Trip', '9'	 => 'Day Rental(4hr-40km)', '10' => 'Day Rental(8hr-80km)', '16' => 'Day Rental(10hr-100km)', '11' => 'Day Rental(12hr-120km)', '15' => 'Local Transfer'];
		}
		else
		{
			$arr = ['1'	 => 'One Way', '2'	 => 'Round/Multi Trip', '3'	 => 'Round/Multi Trip', '4'	 => 'Airport Transfer',
				'5'	 => 'Package', '6'	 => 'Flexxi', '7'	 => 'Shuttle', '8'	 => 'Custome Trip', '9'	 => 'Day Rental(4hr-40km)', '10' => 'Day Rental(8hr-80km)', '16' => 'Day Rental(10hr-100km)', '11' => 'Day Rental(12hr-120km)'];
		}
		if ($bkgType > 0)
		{
			return $arr[$bkgType];
		}
		return $arr;
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public static function ServiceImageUrl($images)
	{
		$var = explode('/', $images);
		$img = IMAGE_URL . '/' . $var[1] . '/' . $var[2];
		return $img;
	}

	public static function getCountryList()
	{

		$sql   = "SELECT `id`,`country_code`,`country_name`,`country_phonecode` FROM `countries`
			WHERE 1 GROUP BY country_code ORDER BY country_order DESC, country_name ASC";
		$model = DBUtil::query($sql);

		$arrService = array();
		$i			= 1;

		/* @var  Services  */
		/* @var  Services  */
		foreach ($model as $val)
		{
			$arrService[$val['country_code']] = array('id' => $val ['id'], 'name' => $val['country_name'] . " (" . $val['country_code'] . ")", 'pcode' => $val['country_code'], 'order' => "$i");
			$i++;
		}
		return $arrService;
	}

	/**
	 * Input : abc, abc, abc
	 * Output: 'abc', 'abc', 'abc'
	 * @param type $string
	 * @return type
	 */
	public static function addQuotes($string)
	{
		return "'" . implode("','", explode(",", $string)) . "'";
	}

	public static function getCovidInstructions($requestedBy = 1)
	{
		$arr = [];
		if ($requestedBy == 1)
		{
			$arr = [["msg" => "Wash your hands often."],
				["msg" => "Avoid close contact"],
				["msg" => "Cover your mouth and nose with a face mask"],
				["msg" => "Cover your coughs and sneezes"],
				["msg" => "Clean and disinfect frequently touched surfaces daily"]];
		}
		if ($requestedBy == 2)
		{
			$arr = [["msg" => "Wash your hands often."],
				["msg" => "Avoid close contact"],
				["msg" => "Cover your mouth and nose with a face mask"],
				["msg" => "Cover your coughs and sneezes"],
				["msg" => "Clean and disinfect frequently touched surfaces daily"]];
		}
		return $arr;
	}

	public function DialLead()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL			   => "http://192.168.1.5/agc/api.php?source=test&user=100&pass=100&agent_user=100&function=external_dial&value=&phone_code=91&search=YES&preview=NO&focus=YES",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING	   => "",
			CURLOPT_MAXREDIRS	   => 10,
			CURLOPT_TIMEOUT		   => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
		));
		$response = curl_exec($curl);
		curl_close($curl);
		echo $response;
	}

	/**
	 *
	 * @return orderNumber
	 */
	public static function getOrderNumber()
	{
		$length_of_string = 8;
		// String of all alphanumeric character
		$str_result		  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		// Shufle the $str_result and returns substring
		// of specified length
		$orderNumber	  = substr(str_shuffle($str_result), 0, $length_of_string);
		return $orderNumber;
	}

	/**
	 *
	 * @param integer $lengthOfString
	 * @return string
	 */
	public static function getRandomCode($lengthOfString = 12)
	{
		// String of all alphanumeric character
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		// Shufle the $str_result and returns substring
		// of specified length
		$code		= substr(str_shuffle($str_result), 0, $lengthOfString);
		return $code;
	}

	/**
	 * This will parse phone number and return PhoneNumberUtil object
	 *
	 * @param string $code it will store the country code after parsing number
	 * @param string $number it will store the phone number without country code after parsing number
	 * @return libphonenumber\PhoneNumber
	 * @throws Exception
	 */
	public static function parsePhoneNumber($phone, &$code, &$number)
	{
		$phoneUtil		= libphonenumber\PhoneNumberUtil::getInstance();
		$objPhoneNumber = $phoneUtil->parse($phone, "IN");
		$isValid		= $phoneUtil->isValidNumber($objPhoneNumber);
		$code			= $objPhoneNumber->getCountryCode();
		$number			= str_replace(" ", "", $objPhoneNumber->getNationalNumber());
		return $objPhoneNumber;
	}

	/**
	 * Validate the phone number using libphonenumber
	 *
	 * @param string $phone
	 * @return boolean
	 * @throws Exception
	 */
	public static function validatePhoneNumber($phone)
	{
		$isValid = false;
		try
		{
			$phoneUtil		= libphonenumber\PhoneNumberUtil::getInstance();
			$objPhoneNumber = $phoneUtil->parse($phone, "IN");
			$isValid		= $phoneUtil->isValidNumber($objPhoneNumber);
		}
		catch (Exception $exc)
		{
			Logger::trace(Logger::getExceptionString($exc));
		}

		return $isValid;
	}

	/**
	 * Getting valid phone number (process phone number with country code)
	 * @param type $number
	 * @param type $code
	 * @return boolean|string
	 */
	public static function processPhoneNumber($number, $code = '')
	{
		$number		= trim($number);
		$code		= trim($code);
		$isPlus		= (substr($number, 0, 1) == "+" || substr($number, 0, 2) == "00");
		$isCodePlus = (strlen($code) >= 1 && substr($code, 0, 1) == "+");
		$number		= ltrim(ltrim($number, "+"), "0");
		$code		= ltrim(ltrim($code, "+"), "0");

		if ($isPlus)
		{
			$phone	 = "+" . $number;
			$isValid = Filter::validatePhoneNumber($phone);
		}
		if ($code == "" && !$isValid)
		{
			$phone	 = "+91" . $number;
			$isValid = Filter::validatePhoneNumber($phone);
		}

		if ($code == "" && !$isValid)
		{
			$phone	 = "+" . $number;
			$isValid = Filter::validatePhoneNumber($phone);
		}

		if (!$isValid)
		{
			$phone	 = "+" . $code . $number;
			$isValid = Filter::validatePhoneNumber($phone);
		}

		if (!$isValid)
		{
			return false;
		}

		return $phone;
	}

	/**
	 * Converts given IDN to the punycode.
	 * @param string $value IDN to be converted.
	 * @return string resulting punycode.
	 * @since 1.1.13
	 */
	public static function encodeIDN($value)
	{
		if (preg_match_all('/^(.*)@(.*)$/', $value, $matches))
		{
			if (function_exists('idn_to_ascii'))
			{
				$value = $matches[1][0] . '@';
				if (defined('IDNA_NONTRANSITIONAL_TO_ASCII') && defined('INTL_IDNA_VARIANT_UTS46'))
				{
					$value .= idn_to_ascii($matches[2][0], IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
				}
				else
				{
					$value .= idn_to_ascii($matches[2][0]);
				}
			}
			else
			{
				require_once(Yii::getPathOfAlias('system.vendors.Net_IDNA2.Net') . DIRECTORY_SEPARATOR . 'IDNA2.php');
				$idna  = new Net_IDNA2();
				$value = $matches[1][0] . '@' . @$idna->encode($matches[2][0]);
			}
		}
		return $value;
	}

	/**
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function validateEmail($value, $checkMX = false, $checkPort = false)
	{
		$pattern = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
		if (is_string($value))
		{
			$value = static::encodeIDN($value);
		}
		// make sure string length is limited to avoid DOS attacks
		$valid = is_string($value) && strlen($value) <= 254 && (preg_match($pattern, $value));

		if ($valid)
		{
			$domain = rtrim(substr($value, strpos($value, '@') + 1), '>');
		}
		if ($valid && $checkMX && function_exists('checkdnsrr'))
		{
			$valid = checkdnsrr($domain, 'MX');
		}
		if ($valid && $checkPort && function_exists('fsockopen') && function_exists('dns_get_record'))
		{
			$valid = static::checkMxPorts($domain);
		}

		return $valid;
	}

	public static function checkMxPorts($domain)
	{
		$records = dns_get_record($domain, DNS_MX);
		if ($records === false || empty($records))
		{
			return false;
		}
		$timeout = is_int($this->timeout) ? $this->timeout : ((int) ini_get('default_socket_timeout'));
		usort($records, array($this, 'mxSort'));
		foreach ($records as $record)
		{
			$handle = @fsockopen($record['target'], 25, $errno, $errstr, $timeout);
			if ($handle !== false)
			{
				fclose($handle);
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * @return boolean
	 */
	public static function isWorkingHour()
	{
		$hours = date('G', strtotime(DBUtil::getCurrentTime()));
		if ($hours >= 8 && $hours < 22)
		{
			return true;
		}
		return false;
	}

	/**
	 * This function is used to generate short url
	 * @param string $url
	 * @param string $timeOut
	 * @return string
	 */
	public static function shortUrl($url, $timeOut = 2000)
	{
		if (strtolower($_SERVER['SERVER_NAME']) == 'localhost' || strtolower($_SERVER['SERVER_NAME']) == 'gozotech1.ddns.net' || strtolower($_SERVER['SERVER_NAME']) == 'gozotech.ddns.net' || (substr($_SERVER['SERVER_NAME'], 0, strlen("192.168.1.")) == "192.168.1."))
		{
			return $url;
		}
		else
		{
			$apiUrl	  = "http://c.gozo.cab/yourls-api.php?signature=e881178cb0&action=shorturl&format=json&url=";
			$apiUrl	  .= $url;
			$ch		  = curl_init();
			curl_setopt($ch, CURLOPT_URL, $apiUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeOut);
			$output	  = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$err	  = curl_error($ch);
			if (!$output)
			{
				$shortUrl = $url;
			}
			else
			{
				$data	  = json_decode($output);
				$shortUrl = $data->shorturl;
			}
			curl_close($ch);
			return $shortUrl;
		}
	}

	/**
	 * This function is used to generate extended folder structure in S3 server
	 * @param int $id
	 * @param int $char
	 * @return string
	 */
	public static function s3FolderPath($id, $needChar = 8)
	{
		if (!$id)
		{
			return false;
		}
		$expectedStr  = str_pad($id, $needChar, "0", STR_PAD_LEFT);
		$arr		  = str_split($expectedStr, 2);
		$extendedPath = implode(DIRECTORY_SEPARATOR, $arr);
		return $extendedPath;
	}

	/**
	 * This function is used to generate path for document server
	 * @param int $server
	 * @param string $baseFolfer //document for Booking
	 * @return int   $ID //Id for document Type  ex:bookingId
	 * @return string   $bkgCrtDate //Create date for booking  ex:2012-09-14 21:30:00
	 */
	public static function getBookingFilePath($server, $baseFolder, $ID, $bkgCrtDate)
	{
		$dateDirectory = date('Y', strtotime($bkgCrtDate)) . DIRECTORY_SEPARATOR . date('m', strtotime($bkgCrtDate)) . DIRECTORY_SEPARATOR . date('d', strtotime($bkgCrtDate));
		$dirFinal	   = DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $server . DIRECTORY_SEPARATOR . $baseFolder . DIRECTORY_SEPARATOR . $dateDirectory . DIRECTORY_SEPARATOR . $ID . DIRECTORY_SEPARATOR;
		return $dirFinal;
	}

	public static function generateOtp()
	{
		$otp = strtolower(rand(1001, 9999));
		return $otp;
	}

	/**
	 * This function is used to identify platform
	 * @param int $userId
	 * @return int
	 */
	public static function getPlatform($userId)
	{
		$platform = null;
		switch ($userId)
		{
			case Config::get('spicejet.partner.id'):
				$platform = Booking::Platform_Spicejet;
				break;
			case 18190:
				$platform = Booking::Platform_GOMMT;
				break;
			default:
				$platform = Booking::Platform_CPAPI;
				break;
		}
		return $platform;
	}

	/**
	 * Function for checking the data for spam, return true if found spam
	 * @param $dataToCheck
	 * @return boolean
	 */
	public static function checkSpam($dataToCheck)
	{
		$spam				= false;
		$arrBlockedSpamData = array();
		$blockedSpamData	= Config::get("blocked.spam.data");
		if (trim($blockedSpamData) != '')
		{
			$arrBlockedSpamData = explode(',', $blockedSpamData);
		}
		if (trim($dataToCheck) != '' && count($arrBlockedSpamData) > 0)
		{
			foreach ($arrBlockedSpamData as $blockedData)
			{
				if (trim($blockedData) != '')
				{
					$pos = strpos(trim($dataToCheck), trim($blockedData));
					if ($pos !== false)
					{
						$spam = true;

						// Logging
						$ser = $_SERVER;
						if (function_exists('getallheaders'))
						{
							$headers = getallheaders();
						}
						else if (function_exists('apache_request_headers'))
						{
							$headers = apache_request_headers();
						}
						$arr['SERVER']	= $ser;
						$arr['HEADERS'] = $headers;
						$req			= json_encode($arr);
						Logger::error($req);

						break;
					}
				}
			}
		}
		return $spam;
	}

	/**
	 * This function checks whether GozoNow is enabled eith the given view
	 * @return bool
	 */
	public static function checkGozoNowEnabled()
	{
		return Config::checkGozoNowEnabled();
		$isGozoNowEnabled	  = Config::get("booking.gozoNow.isEnabled");
		$isMobileViewEnabled  = Config::get("booking.gozoNow.isMobileViewEnabled");
		$isDesktopViewEnabled = Config::get("booking.gozoNow.isDesktopViewEnabled");
		$isMobileDetect		  = Yii::app()->mobileDetect->isMobile();
		$checkGozoNowEnabled  = ($isGozoNowEnabled && (($isMobileDetect && $isMobileViewEnabled) || (!$isMobileDetect && $isDesktopViewEnabled ) ) ) ? true : false;
		return $checkGozoNowEnabled;
	}

	public static function moneyFormatter($number)
	{
		$explrestunits = "";
		if (strlen($number) > 3)
		{
			$lastthree = substr($number, strlen($number) - 3, strlen($number));
			$restunits = substr($number, 0, strlen($number) - 3); // extracts the last three digits
			$restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit   = str_split($restunits, 2);
			for ($i = 0; $i < sizeof($expunit); $i++)
			{
				// creates each of the 2's group and adds a comma to the end
				if ($i == 0)
				{
					if ($expunit[$i] == 0)
					{
						continue;
					}
					$explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
				}
				else
				{
					$explrestunits .= $expunit[$i] . ",";
				}
			}
			$thecash = $explrestunits . $lastthree;
		}
		else
		{
			$thecash = $number;
		}

		if ($number < 0 && $thecash > 0)
		{
			$thecash = '-' . $thecash;
		}
		return '₹' . '' . $thecash;
	}

	/**
	 * Encrypts (but does not authenticate) a message
	 *
	 * @param string $message - plaintext message
	 * @param string $key - encryption key (raw binary expected)
	 * @param boolean $encode - set to TRUE to return a base64-encoded
	 * @return string (raw binary)
	 */
	public static function encrypt($message, $key = null, $encode = true)
	{
		if ($key == null)
		{
			$key = Config::getSecurityKey();
		}
		$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
		$nonce	   = openssl_random_pseudo_bytes($nonceSize);

		$ciphertext = openssl_encrypt($message, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $nonce);

		// Now let's pack the IV and the ciphertext together
		// Naively, we can just concatenate
		if ($encode)
		{
			return base64_encode($nonce . $ciphertext);
		}
		return $nonce . $ciphertext;
	}

	/**
	 * Decrypts (but does not verify) a message
	 *
	 * @param string $message - ciphertext message
	 * @param string $key - encryption key (raw binary expected)
	 * @param boolean $encoded - are we expecting an encoded string?
	 * @return string
	 */
	public static function decrypt($message, $key = null, $encoded = true)
	{
		if ($key == null)
		{
			$key = Config::getSecurityKey();
		}
		if ($encoded)
		{
			$message = base64_decode($message, true);
			if ($message === false)
			{
				throw new Exception('Encryption failure');
			}
		}

		$nonceSize	= openssl_cipher_iv_length('aes-256-ctr');
		$nonce		= mb_substr($message, 0, $nonceSize, '8bit');
		$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

		$plaintext = openssl_decrypt($ciphertext, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $nonce);

		return $plaintext;
	}

	/**
	 * calculate calendar days 
	 * 
	 * @param \DateTime $dateTime
	 * @param int $minuteInterval
	 * @return \DateTime
	 */
	public static function roundUpToMinuteInterval(\DateTime $dateTime, $minuteInterval = 15)
	{
		return $dateTime->setTime(
						$dateTime->format('H'),
						ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
						0
		);
	}

	public function getTravelDays($fromDate, $toDate)
	{
		$night		  = 0;
		$fromData2	  = strtotime($fromDate);
		$toDate2	  = strtotime($toDate);
		$startTime	  = date('H', $fromData2);
		$endTime	  = date('H', $toDate2);
		$startDate	  = new DateTime(date('Y-m-d', $fromData2));
		$endDate	  = new DateTime(date('Y-m-d', $toDate2));
		$interval	  = $startDate->diff($endDate);
		$calendarDays = $interval->format('%a');
		$calendarDays++;
		$night		  += $calendarDays;
		if ($endTime <= 22)
		{
			$night--;
		}
		if ($startTime <= 5)
		{
			$night++;
		}

		$seconds = $toDate2 - $fromData2;
		$minutes = date(round($seconds / 60), strtotime(30));
		$minutes = $minutes - ($minutes % 15);
		$sec	 = $minutes * 60;
		$days	 = floor($sec / 86400);
		$hours	 = floor(($sec - ($days * 86400)) / 3600);
		$thours	 = floor($sec / 3600);
		$min	 = $minutes - ($thours * 60);
		$dur	 = '';
		if ($days > 0)
		{
			$dur .= $days . " days ";
		}
		if ($hours > 0)
		{
			$dur .= $hours . " hrs ";
		}
		if ($min > 0)
		{
			$dur .= $min . " mins";
		}

		return ['gozoDuration' => $calendarDays, 'calendarDays' => $calendarDays, 'totalNight' => $night, 'totalMin' => $minutes, 'actualDur' => $dur];
	}

	public static function getRowsAndColumns($rows, $columns, $valOfField = null)
	{
		$objDB	   = DBUtil::MDB();
		$arrIDs	   = [];
		$fields	   = [];
		$sqlValues = [];

		foreach ($rows as $row)
		{
			$data = [];
			foreach ($columns as $col)
			{
				/** @var CDbColumnSchema $col */
				$fields[$col->rawName] = $col->rawName;
				$value				   = $row[$col->name];

				if ($value === null)
				{
					$data[] = "NULL";
				}
				else
				{
					$data[] = $objDB->quoteValue($col->typecast($value));
				}

				if ($valOfField && $valOfField != null && $col->name == $valOfField)
				{
					$arrIDs[] = $value;
				}
			}
			$sqlValues[] = "(" . implode(", ", $data) . ") ";
		}

		return ['fields' => $fields, 'sqlValues' => $sqlValues, 'arrIDs' => $arrIDs];
	}

	public static function customerDataShow($pickuptime)
	{
		$showCustomer			= 0;
		$minutesToPickup		= Filter::getTimeDiff($pickuptime);
		$workingMinutesToPickup = Filter::CalcWorkingMinutes(Filter::getDBDateTime(), $pickuptime);

		if ($minutesToPickup < 60 || $workingMinutesToPickup < 120)
		{
			$showCustomer = 1;
		}
		return $showCustomer;
	}

	public static function showCustomerNumber($model)
	{
		
	}

	public static function getUserIP()
	{
		// Get real visitor IP behind CloudFlare network
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
		{
			$_SERVER['REMOTE_ADDR']	   = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_X_REAL_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$realIP	 = @$_SERVER['HTTP_X_REAL_IP'];
		$client	 = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote	 = $_SERVER['REMOTE_ADDR'];

		if (filter_var($realIP, FILTER_VALIDATE_IP))
		{
			$real_ip_adress = $realIP;
		}
		elseif (filter_var($client, FILTER_VALIDATE_IP))
		{
			$real_ip_adress = $client;
		}
		elseif (filter_var($forward, FILTER_VALIDATE_IP))
		{
			$real_ip_adress = trim(explode(',', $forward)[0]);
		}
		else
		{
			$real_ip_adress = $remote;
		}

		if ($real_ip_adress == '::1')
		{
			$real_ip_adress = gethostbyname(gethostname());
		}
		return $real_ip_adress;
	}

	public static function utf8_uri_encode($utf8_string, $length = 0)
	{
		$unicode		= '';
		$values			= array();
		$num_octets		= 1;
		$unicode_length = 0;

		$string_length = strlen($utf8_string);
		for ($i = 0; $i < $string_length; $i++)
		{

			$value = ord($utf8_string[$i]);

			if ($value < 128)
			{
				if ($length && ( $unicode_length >= $length ))
					break;
				$unicode .= chr($value);
				$unicode_length++;
			}
			else
			{
				if (count($values) == 0)
					$num_octets = ( $value < 224 ) ? 2 : 3;

				$values[] = $value;

				if ($length && ( $unicode_length + ($num_octets * 3) ) > $length)
					break;
				if (count($values) == $num_octets)
				{
					if ($num_octets == 3)
					{
						$unicode		.= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
						$unicode_length += 9;
					}
					else
					{
						$unicode		.= '%' . dechex($values[0]) . '%' . dechex($values[1]);
						$unicode_length += 6;
					}

					$values		= array();
					$num_octets = 1;
				}
			}
		}

		return $unicode;
	}

	public static function seems_utf8($str)
	{
		$length = strlen($str);
		for ($i = 0; $i < $length; $i++)
		{
			$c = ord($str[$i]);
			if ($c < 0x80)
				$n = 0;# 0bbbbbbb
			elseif (($c & 0xE0) == 0xC0)
				$n = 1;# 110bbbbb
			elseif (($c & 0xF0) == 0xE0)
				$n = 2;# 1110bbbb
			elseif (($c & 0xF8) == 0xF0)
				$n = 3;# 11110bbb
			elseif (($c & 0xFC) == 0xF8)
				$n = 4;# 111110bb
			elseif (($c & 0xFE) == 0xFC)
				$n = 5;# 1111110b
			else
				return false;# Does not match any model
			for ($j = 0; $j < $n; $j++)
			{ # n bytes matching 10bbbbbb follow ?
				if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
					return false;
			}
		}
		return true;
	}

	public static function sanitize($title)
	{
		$title = strip_tags($title);
		// Preserve escaped octets.
		$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
		// Remove percent signs that are not part of an octet.
		$title = str_replace('%', '', $title);
		// Restore octets.
		$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

		if (Filter::seems_utf8($title))
		{
			if (function_exists('mb_strtolower'))
			{
				$title = mb_strtolower($title, 'UTF-8');
			}
			$title = Filter::utf8_uri_encode($title, 200);
		}

		$title = strtolower($title);
		$title = preg_replace('/&.+?;/', '', $title); // kill entities
		$title = str_replace('.', '-', $title);
		$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
		$title = preg_replace('/\s+/', '-', $title);
		$title = preg_replace('|-+|', '-', $title);
		$title = trim($title, '-');
		return $title;
	}

	/**
	 * 
	 * @param type $server
	 * @param type $baseFolder
	 * @param type $ID
	 * @return string
	 */
	public static function generateQRCodeFilePath($server, $baseFolder, $ID)
	{
		$dirFinal = DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $server . DIRECTORY_SEPARATOR . $baseFolder . DIRECTORY_SEPARATOR . $ID . DIRECTORY_SEPARATOR;
		return $dirFinal;
	}

	public static function getAddonTypes($key = 0)
	{
		$arr = [1 => 'cancellation policy', 2 => 'Select Models', 3 => 'Extra KM', 4 => 'Extra Minutes', 5 => 'Insurance'];
		if ($key > 0)
		{
			return $arr[$key];
		}
		return $arr;
	}

	public static function getAdvanceRuleArr($rule = 0)
	{
		$arr = [
			1 => ["type" => 1, "value" => 30, "min" => 0, "max" => 0], //non-refundable cancel+noshow
			2 => ["type" => 1, "value" => 30, "min" => 0, "max" => 0], //standard cancel+noshow
			3 => ["type" => 1, "value" => 50, "min" => 0, "max" => 1000], //flexi cancel+noshow
			4 => ["type" => 1, "value" => 50, "min" => 0, "max" => 0], //super flexi cancel+noshow
		];
		if ($rule > 0)
		{
			return $arr[$rule];
		}
	}

	public static function createFolderPath($fullFolderPath)
	{
		if (is_dir($fullFolderPath))
		{
			return true;
		}

		$checkFolderdir = mkdir($fullFolderPath, 0755, true);
		if ($checkFolderdir)
		{
			chmod($fullFolderPath, 0755);
			return true;
		}

		return false;
	}

	public static function getStartAndEndDate($date)
	{
		$year				  = date('Y', strtotime($date));
		$week				  = date("W", strtotime($date));
		$dateTime			  = new DateTime();
		$dateTime->setISODate($year, $week);
		$result['start_date'] = $dateTime->format('Y-m-d');
		$dateTime->modify('+6 days');
		$result['end_date']	  = $dateTime->format('Y-m-d');
		return $result;
	}

	public static function getHeader($key)
	{
		if (function_exists('getallheaders'))
		{
			$headers = getallheaders();
		}
		else if (function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}
		return $headers[$key];
	}

	public static function cidr_match($ip, $range)
	{
		list ($subnet, $bits) = explode('/', $range);
		if ($bits === null)
		{

			$bits = 32;
		}
		$ip		= ip2long($ip);
		$subnet = ip2long($subnet);
		$mask	= -1 << (32 - $bits);
		$subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
		return ($ip & $mask) == $subnet;
	}

	/**
	 * 
	 */
	public static function checkIpAllowed($allowedIPs)
	{
		$success = false;
		$ip		 = Filter::getUserIP();
		if (!is_array($allowedIPs))
		{
			$ipAddresses = array_filter(explode(",", $allowedIPs));
		}
		else
		{
			$ipAddresses = $allowedIPs;
		}
		foreach ($ipAddresses as $ipVal)
		{
			$success = self::cidr_match($ip, $ipVal);
			if ($success)
			{
				break;
			}
		}
		return $success;
	}

	/**
	 * 
	 * @param string $data
	 * @return JSON
	 */
	public static function decryptedJsonObj($data)
	{
		$jsonData = \Filter::decrypt($data);

		$jsonMapper = new \JsonMapper();
		$jsonObj	= \CJSON::decode($jsonData, false);
		$jsonObj	= \Filter::removeNull($jsonObj);

		return $jsonObj;
	}

	public static function checkACL($accessSpecifier)
	{
		$success	   = false;
		$roleSpecifier = explode(",", $accessSpecifier);
		foreach ($roleSpecifier as $role)
		{
			$checkAccess = Yii::app()->user->checkAccess($role);
			if ($checkAccess)
			{
				$success = true;
				goto skipCs;
			}
		}
		skipCs:
		return $success;
	}

	public static function guidv4($data = null)
	{
		return Ramsey::init();
//		$data = $data ?? random_bytes(16);
//		assert(strlen($data) == 16);
//
//		// Set version to 0100
//		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
//		// Set bits 6-7 to 10
//		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
//
//		// Output the 36 character UUID.
//		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	public static function setVisitorCookie()
	{
		$sessionid = Yii::app()->getSession()->getSessionId();
		$date	   = date('Y-m-d H:i:s');
		$vistorId  = md5($sessionid . $date . SERVER_ID);

		$visitorCookie			 = new CHttpCookie('gvid', $vistorId);
		//$visitorCookie->domain	 = Yii::app()->params['domain'];
		$visitorCookie->sameSite = CHttpCookie::SAME_SITE_STRICT;
		$visitorCookie->httpOnly = true;
		$visitorCookie->expire	 = time() + (60 * 24 * 365 * 2);
		Yii::app()->request->getCookies()->add($visitorCookie->name, $visitorCookie);
	}

	/**
	 * 
	 * @param integer $bucket
	 * @return string
	 */
	public static function getBucket($bucket)
	{
		if ($bucket >= 0 AND $bucket < 12)
		{
			$value = '00-12';
		}
		else if ($bucket >= 12 AND $bucket < 24)
		{
			$value = '12-24';
		}
		else if ($bucket >= 24 AND $bucket < 96)
		{
			$value = 'D02-D04';
		}
		else if ($bucket >= 96 AND $bucket < 240)
		{
			$value = 'D04-D10';
		}
		else if ($bucket >= 240)
		{
			$value = 'D10+';
		}
		return $value;
	}

	/**
	 * this function is used to unset a particular key from array and return the remove array without that key
	 * 
	 * @param array $bucket
	 * @param string $keyToUnset
	 * @return string
	 */
	public static function unsetInnermostKey(&$array, $keyToUnset)
	{
		foreach ($array as &$value)
		{
			if (is_array($value))
			{
				self::unsetInnermostKey($value, $keyToUnset);
			}
		}

		if (array_key_exists($keyToUnset, $array))
		{
			unset($array[$keyToUnset]);
		}
	}

	/**
	 * This function is used to get Bkpn url for any given booking Id
	 * @param int $bkgId
	 * @return boolean|string
	 */
	public static function getBkpnURL($bkgId)
	{
		if ($bkgId > 0)
		{
			$hashBkgId = Yii::app()->shortHash->hash($bkgId);
			$bkpnUrl   = Yii::app()->params['fullBaseURL'] . '/bkpn/' . $bkgId . '/' . $hashBkgId;
			return $bkpnUrl;
		}
		return false;
	}

	/*	 * *
	 * this function is user for bkg_extra_charge_details field as lookup
	 */

	public static function getExtraChargeDetails($id)
	{
		$arr = [
			1 => ['id' => 1, 'desc' => 'reschedule charge', 'value' => 50], //take 50% of cancel charge
			2 => ['id' => 2, 'desc' => 'reschedule charge within 1 hour of pickup', 'value' => 100],
			3 => ['id' => 3, 'desc' => 'other charges', 'value' => 30]
		];
		if ($id > 0)
		{
			return $arr[$id];
		}
		return $arr;
	}

	/**
	 * 
	 * @param string $bookingId
	 * @return string
	 */
	public static function formatBookingId($bookingId)
	{
		$isTFR	   = strpos($bookingId, 'TFR');
		$pos	   = ($isTFR === false ? 4 : 5);
		$bookingId = substr($bookingId, 0, $pos) . '-' . substr($bookingId, $pos, strlen($bookingId));
		return $bookingId;
	}

	public static function setReferrer($refSource = null)
	{
		if ($refSource == 'wa')
		{
			$objCookie								  = new CHttpCookie('bkgSource', 'whatsapp');
			$objCookie->expire						  = time() + 86400;
			Yii::app()->request->cookies['bkgSource'] = $objCookie;
		}
		if ($refSource == 'wah')
		{
			$objCookie								  = new CHttpCookie('bkgSource', 'whatsapp-hawaii');
			$objCookie->expire						  = time() + 86400;
			Yii::app()->request->cookies['bkgSource'] = $objCookie;
		}
//		else
//		{
//			Yii::app()->request->cookies['bkgSource']->expire = time() - 1;
//			unset(Yii::app()->request->cookies['bkgSource']);
//		}
	}

	public static function addParametersToUrl(string $url, array $newParams)
	{
		$url = parse_url($url);
		parse_str($url['query'] ?? '', $existingParams);

		$newQuery = array_merge($existingParams, $newParams);

		$newUrl = '';

		if ($url['scheme'])
		{
			$newUrl .= $url['scheme'] . ':';
		}

		if ($url['host'])
		{
			$newUrl .= '//' . $url['host'];
		}

		if ($url['path'])
		{
			$newUrl .= $url['path'];
		}

		if ($newQuery)
		{
			$newUrl .= '?' . http_build_query($newQuery);
		}

		if (isset($url['fragment']))
		{
			$newUrl .= '#' . $url['fragment'];
		}

		return $newUrl;
	}

	public static function addGLParam($url)
	{
		if (isset($_GET["_gl"]))
		{
			$url = self::addParametersToUrl($url, ["_gl" => $_GET["_gl"]]);
		}
		return $url;
	}

	/**
	 * 
	 * @param type $pickTime
	 * @param type $bkgModel
	 * @return type
	 */
	public static function getDboConfirmEndTime($pickTime, $bkgModel = null)
	{
		$dboconfirmEndTime = '';
		$dboSettings	   = Config::get('dbo.settings');
		$data			   = CJSON::decode($dboSettings);
		if ($pickTime >= $data['dboStartDate'] && $pickTime <= $data['dboEndDate'])
		{
			$checkDboApplicable = Booking::checkDboApplicable($pickTime, $bkgModel);
			if ($checkDboApplicable)
			{
				//$minutes			 = ($data['minworkinghour'] * 60) + $data['gracetimeminutes'];
				//$params			 = ['minutes' => $minutes];
				//$sql				 = "SELECT SubWorkingMinutes(:minutes, '$pickTime') FROM dual";
				//$res				 = DBUtil::command($sql)->queryScalar($params);
				//$dboconfirmEndTime = date('Y-m-d h:s:i', ($res));
				$dboconfirmEndTime = date('Y-m-d H:s:i', (strtotime($pickTime . "- " . $data['minworkinghour'] . "HOUR")));
			}
		}
		return $dboconfirmEndTime;
	}

	public static function buildOriginHostURL($s, $use_forwarded_host = false)
	{
		$ssl	  = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on' );
		$sp		  = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . ( ( $ssl ) ? 's' : '' );
		$port	  = $s['SERVER_PORT'];
		$port	  = ( (!$ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
		$host	  = ( $use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST']) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null );
		$host	  = isset($host) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}

	public static function getOriginURL($s = null, $use_forwarded_host = false)
	{
		if ($s == null)
		{
			$s = $_SERVER;
		}
		return self::buildOriginHostURL($s, $use_forwarded_host) . $s['REQUEST_URI'];
	}

	public static function parseTrackingParams()
	{
		$hosts	  = ["aaocab.in", "gozo.cab", "aaocab.com", "gozo.taxi", "gozocab.com"];
		$trkParam = [];
		$url	  = self::getOriginURL();
		$pUrl	  = parse_url($url);
		$hosts[]  = $pUrl["host"];

		$referrer  = $_SERVER['HTTP_REFERER'];
		$pReferrer = parse_url($referrer);
		$refHost   = $pReferrer["host"];
		$isSelf	   = false;

		foreach ($hosts as $host)
		{
			if (stripos($refHost, $host))
			{
				$isSelf = true;
			}
		}

		if ($isSelf)
		{
			return false;
		}

		parse_str($pUrl['query'], $pQuery);
		//	$trkParam[""]

		$trkParam ["referrer"] = $referrer;
		if ($pQuery["utm_source"] != '')
		{
			$trkParam["source"] = $pQuery["utm_source"];
		}
		if ($pQuery["utm_medium"] != '')
		{
			$trkParam["medium"] = $pQuery["utm_medium"];
		}
		if ($pQuery["utm_campaign"] != '')
		{
			$trkParam["campaign"] = $pQuery["utm_campaign"];
		}
		if ($pQuery["utm_content"] != '')
		{
			$trkParam["content"] = $pQuery["utm_content"];
		}
		if ($pQuery["utm_term"] != '')
		{
			$trkParam["term"] = $pQuery["utm_term"];
		}
		if ($pQuery["url"] != '')
		{
			$trkParam["url"] = $pQuery["url"];
		}
		if ($trkParam["source"] != '')
		{
			goto result;
		}
		$trkParam["source"] = $refHost;

		result:

		return $trkParam;
	}

	public static function getDecryptData($data)
	{
		$objMCrypt = new MCrypt('');
		return $objMCrypt->decrypt($data);
	}

	public static function checkIOSDevice()
	{
		$userAgent	= $_SERVER['HTTP_USER_AGENT'];
		$arrDevices = ['iPod', 'iPhone', 'iPad', 'iOS'];
		foreach ($arrDevices as $device)
		{
			if (strpos($userAgent, $device) !== false)
			{
				return true;
			}
		}
		return false;
	}
}
