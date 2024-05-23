<?php

class IRead
{

	const BASE_URL			 = "http://161.97.161.198:8080/iread/";
	const IREAD_CALLBACK_URL	 = 'http://gozotech1.ddns.net:5180/api/admin/user/asyncResponse/';

	/**
	 * This function is used detecting the face mask
	 * @param type $id
	 * @param type $imageUrl
	 * @return string
	 */
	public static function detectFaceMask($id, $imageUrl)
	{
		if (!$imageUrl)
		{
			return;
		}
		$ireadBaseUrl		 = Config::get('IREAD_BASE_URL');
		$url				 = $ireadBaseUrl . "facemask/";
		$ireadCallBackUrl	 = Config::get('IREAD_CALLBACK_URL');
		$ch					 = curl_init();
		if (!$ch)
		{
			die("Couldn't initialize a cURL handle");
		}
		curl_setopt_array($ch, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => json_encode(['image_url' => $imageUrl, 'doc_id' => $id, 'callback_url' => $ireadCallBackUrl]),
			CURLOPT_HTTPHEADER		 => array('Content-Type: application/json'),
		));
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * This function is used reading the document
	 * @param type $id
	 * @param type $imageUrl
	 * @param type  $doc_type
	 * @return string
	 */
	public static function readOCR($id, $imageUrl, $doc_type)
	{
		if (!$imageUrl || !$doc_type)
		{
			return;
		}

		$ireadBaseUrl		 = Config::get('IREAD_BASE_URL');
		$url				 = $ireadBaseUrl . "ocr/";
		$ireadCallBackUrl	 = Config::get('IREAD_CALLBACK_URL');
		$ch					 = curl_init();
		if (!$ch)
		{
			die("Couldn't initialize a cURL handle");
		}
		curl_setopt_array($ch, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => json_encode(['image_url' => $imageUrl, 'doc_type' => $doc_type, 'doc_id' => $id, 'callback_url' => $ireadCallBackUrl]),
			CURLOPT_HTTPHEADER		 => array('Content-Type: application/json'),
		));
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * Send a HTTP request for Quote request
	 * @param array $data
	 */
	public static function setQuoteRequest($data)
	{
		Logger::profile("setQuoteRequest start");
		try
		{
			$isDataServerAvaliable	 = Config::get('isDataServerAvaliable');
			$rand					 = ((rand(1, 10) % 5) == 0);
			if ($isDataServerAvaliable)
			{
				$url = Config::get('dataServerUrl') . "/quote/";
				IRead::callAPIGET($data, $url);
			}
		}
		catch (Exception $ex)
		{
			
		}
		Logger::profile("setQuoteRequest ends");
	}

	/**
	 * Send a HTTP request, but do not wait for the response
	 * @param string $url The url (including query string)
	 * @param array $data Added to the URL or request body depending on method
	 */
	public static function callAPIGET($data, $url)
	{
		try
		{
			if ($curl = curl_init())
			{
				$body	 = http_build_query($data, null, '&');
				$url	 = $url . "?" . $body;
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
				curl_setopt($curl, CURLOPT_TIMEOUT_MS, 80);
				$out	 = curl_exec($curl);
				curl_close($curl);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	/**
	 * Send a HTTP request for last active stats request
	 * @param array $data
	 */
	public static function setLocationRequest($data)
	{
		Logger::profile("setLocationRequest start");
		try
		{
			$isDataServerAvaliable = Config::get('isDataServerAvaliable');
			if ($isDataServerAvaliable)
			{
				$url = Config::get('dataServerUrl') . "/location/";
				IRead::callAPIGET($data, $url);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
		Logger::profile("setLocationRequest ends");
	}

	/**
	 * Send a HTTP request for Vendor bid request
	 * @param array $data
	 */
	public static function setVendorBidRequest($data)
	{
		Logger::profile("setVendorBidRequest start");
		try
		{
			$isDataServerAvaliable = Config::get('isDataServerAvaliable');
			if ($isDataServerAvaliable)
			{
				$url = Config::get('dataServerUrl') . "/vendorBidRequest/";
				IRead::callAPIGET($data, $url);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
		Logger::profile("setVendorBidRequest ends");
	}

	public static function setRowIdentifierRequest($data)
	{
		Logger::profile("setRowIdentifierRequest start");
		try
		{
			$isDataServerAvaliable = Config::get('isDataServerAvaliable');
			if ($isDataServerAvaliable)
			{
				$url = Config::get('dataServerUrl') . "/rowIdentifierRequest/";
				IRead::callAPIGET($data, $url);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
		Logger::profile("setRowIdentifierRequest ends");
	}

}
