<?php
class FBPost extends CComponent
{

	public $app_id					 = "";
	public $app_secret				 = "";
	public $default_graph_version	 = 'v2.2';
	public $page_id					 = '378270975576028';
	public $page_access_token		 = "EAAZABDQ4ff1wBAPMCHgRZCDVVviHT2nJnmGW8abYxZBc9HZBCgTkFoP4YKKcwIyULTeWli8VugZB88kbnZAsNCQISIxrfqQbMHqzXiZCtT1RZA0N1luR14wjsJjj8EbDXPrwlV69XDW3dbLE0Ci8d9MwH5ZAZBg473wSD58tQaR2NZAeiNOzhZC9kHqp";

	function __construct()
	{

		$config				 = UserOAuth::getConfig();
		/*$fbKeys				 = $config['providers']['Facebook']['keys'];
		$this->app_id		 = $fbKeys['id'];
		$this->app_secret	 = $fbKeys['secret'];*/
//		$this->default_graph_version = 'v2.2';
//		$this->page_id				 = '378270975576028';
//		$this->page_access_token	 = "EAAZABDQ4ff1wBACZAttJCpuNaZBAZC5W5m476yfAscux1OMplQ6sL5swBOvIEkUwKeCeEZA4JPnX0M0TIZAtCB62ZCP1D81t81QOpXZB1XVjTuOzYpu9mAbO4BQoDFgnCWIZCkoRxCHtoLYnD0mtDWtx85zvBYKtB9TrpxBXuKsqNWaWVjMlvUZA2J";
//										
		
		/*
		 * Only For Testing Purpose
		 */
		$this->app_id				  = '411406139782800';
		$this->app_secret	          = '04ebc45876f1d0989facddad3c3681a2'; 
		$this->default_graph_version  = 'v9.0';
		$this->page_id				  = '378270975576028';
		$this->page_access_token	  = "eyJhbGciOiJBinGcj1tWP6Bv94YMJ2fR9A3bheE6r7sYc8r5y9nz8Kz4YMQjrTU/pNu9hUhRTGSxsPAbw+HFEcpAz8o0z+E3hfNb+yTZQLv+De7byVtfSFR6mW0oBJXYhJxoSbnAYrqHdUCykb/Cfk1PBIbJi6IgoXeFpCddpIoVMjxTL5kgtMzC6k0Tdes3qUBBZmWfc0OK/QLDTgiQIDCIgYaP/5vesmgAOGU/DgM+aYLQEXn/HI3Ft3JDyj+cEaNWlvYkND7GCQW5oU31wXuDb+PBva6Dz9MqxWcjHmiW+Nu7qs9VAFkJMZRL0fwFtT7Hf8vx5Lzzl1cgwvQL8aEv4hTet1uuwswV0/tjgzEcPFhsVyFyyQosDcktaLZXM9xBuc2pWiPb";
		
	}

	public function post($message, $link)
	{
		define('FACEBOOK_SDK_V4_SRC_DIR', APPLICATION_PATH . '/extensions/hoauth/vendor/facebook/graph-sdk/src/Facebook/');
		require_once(APPLICATION_PATH . '/extensions/hoauth/vendor/facebook/graph-sdk/src/Facebook/autoload.php');

		$fb = new Facebook\Facebook([
			'app_id'				 => $this->app_id,
			'app_secret'			 => $this->app_secret,
			'default_graph_version'	 => $this->default_graph_version,
		]);

		$linkData		 = [
			'link'		 => $link,
			'message'	 => $message
		];
		$pageAccessToken = $this->page_access_token;
		try
		{
			$pageId		 = $this->page_id;
			$response	 = $fb->post("/" . $pageId . "/feed", $linkData, $pageAccessToken);
		}
		catch (Facebook\Exceptions\FacebookResponseException $e)
		{
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		}
		catch (Facebook\Exceptions\FacebookSDKException $e)
		{
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		$graphNode = $response->getGraphNode();
	}

	public function flashBookingPost($cav_id)
	{
		$cavData	 = CabAvailabilities::getDetails($cav_id);
		$flashurl	 = "http://www.aaocab.com/flashsale";
		$gozourl	 = "http://www.aaocab.com";
		if ($cavData && sizeof($cavData) > 0)
		{
			$fromCity		 = $cavData['cav_from_city_name'];
			$toCity			 = $cavData['cav_to_city_name'];
			$pickupDate		 = $cavData['cav_date_time'];
			$formattedDate	 = date('M d, Y', strtotime($pickupDate));
			$amount			 = $cavData['cav_total_amount'];
			$routeStr		 = "$fromCity -> $toCity";
			$message1		 = "Last minute Flash SALE!! 
Car available for $fromCity -> $toCity on $formattedDate
Book at $flashurl for ₹$amount (inc GST)
#GozoCabs #FlashSale #LastMinuteDeals";
			$message2		 = "Last minute Flash SALE!! 
Car available for Local trip in $fromCity on $formattedDate.
Book your Airport pickup or Day based rental now at $gozourl 
#GozoCabs #FlashSale #LastMinuteDeals";
			if ($cavData['cav_is_local_trip'] == 1)
			{
				$message = $message2;
				$url	 = $gozourl;
			}
			else
			{
				$message = $message1;
				$url	 = $flashurl;
			}

			$this->post($message, $url);
		}
	}

}
