<?php

class HttpRequest extends CHttpRequest
{

	public $noCsrfValidationRoutes = array();

	protected function normalizeRequest()
	{

		parent::normalizeRequest();
		if ($this->getIsPostRequest())
		{
			if ($this->enableCsrfValidation && $this->checkPaths() !== false)
				Yii::app()->detachEventHandler('onbeginRequest', array($this, 'validateCsrfToken'));
		}
	}

	private function checkPaths()
	{

		foreach ($this->noCsrfValidationRoutes as $checkPath)
		{
			// allows * in check path
			if (strstr($checkPath, "*"))
			{
				$pos		 = strpos($checkPath, "*");
				$checkPath	 = substr($checkPath, 0, $pos);
				if (strstr($this->pathInfo, $checkPath))
				{
					return true;
				}
			}
			else
			{
				if ($this->pathInfo == $checkPath)
				{
					return true;
				}
			}
		}
		return false;
	}

	public function getJSONObject($mapObject)
	{
		$data		 = $this->rawBody;
		Logger::info("Request: " . $data);
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		if (!$data)
		{
			throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
		}

		$obj = $jsonMapper->map($jsonObj, $mapObject);

		return $obj;
	}

	public function getAuthorizationCode()
	{
		if (isset($_SERVER['HTTP_X_REST_TOKEN']))
		{
			return $_SERVER['HTTP_X_REST_TOKEN'];
		} 

		if (($auth = $this->getAuthorizationHeader()) != '')
		{
			return $auth;
		}

		if (isset($_REQUEST['api']))
		{
			return $_REQUEST['api'];
		}
		return "";
	}

	public function getRestToken()
	{
		if (isset($_SERVER['HTTP_X_REST_TOKEN']))
		{
			return $_SERVER['HTTP_X_REST_TOKEN'];
		} 

		return "";
	}

	public function getAuthorizationHeader($decode = true)
	{
		$header = "";
		if (isset($_SERVER["HTTP_AUTHORIZATION"]))
		{
			$auth_array = explode(" ", $_SERVER["HTTP_AUTHORIZATION"]);
			if (isset($auth_array[1]))
			{
				return ($decode) ? base64_decode($auth_array[1]) : $auth_array[1];
			}
		}
		elseif (isset($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]))
		{
			$auth_array = explode(" ", $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
			if (isset($auth_array[1]))
			{
				return ($decode) ? base64_decode($auth_array[1]) : $auth_array[1];
			}
		}
		else if (function_exists('getallheaders'))
		{
			$headers = getallheaders();
		}
		else if (function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}

		if (isset($headers['Authorization']))
		{
			$auth_array = explode(" ", $headers['Authorization']);
			if (isset($auth_array[1]))
			{
				$header = ($decode) ? base64_decode($auth_array[1]) : $auth_array[1];
			}
		}
		return $header;
	}

	public function downloadFile($filePath, $fileName = "")
	{
		if (!file_exists($filePath))
		{
			return false;
		}
		$mimeType = CFileHelper::getMimeType($filePath);
		if ($fileName == "")
		{
			$fileName = basename($filePath);
		}
		$ext = CFileHelper::getExtension($filePath);
		if ($ext == "")
		{
			$ext		 = CFileHelper::getExtensionByMimeType($filePath);
			$fileName	 .= "." . $ext;
		}
		$content = file_get_contents($filePath);
		$this->sendFile($fileName, $content, $mimeType);
	}

}
