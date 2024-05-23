<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of liveChat
 *
 * @author Suvajit
 */
use GuzzleHttp\Client;

class liveChat extends CApplicationComponent
{

	private $host;
	private $username;
	private $password;
	private $module;
	private $params;
	private $client;

	public function init()
	{
		$basePath = dirname(__FILE__);
	}

	public function __construct()
	{
		$this->client	 = new Client(['http_errors' => false, "verify" => false]);
		//$this->client	 = new Client(['http_errors' => false, "verify" => false]);
		$this->host		 = "https://lh.gozo.cab/index.php/"; //"http://localhost:81/";
		$this->username	 = "admin";
		$this->password	 = "sdrs22590"; //"admin123";
		$this->module	 = "restapi";
		$this->params	 = array();
	}

	/**
	 * Make the request and analyze the result
	 *
	 * @param   string          $type           Request method
	 * @param   string          $endpoint       Api request endpoint
	 * @param   array           $params         Parameters
	 * @return  array|false                     Array with data or error, or False when something went fully wrong
	 */
	public function doRequest($type, $endpoint, $params = null, $contentType = 'application/json')
	{
		$url = $this->host . $this->module . $endpoint;

		$headers = array
			(
			'Accept'		 => 'application/json',
			'Authorization'	 => 'Basic ' . base64_encode($this->username . ":" . $this->password)
		);

		$form_params = $params;
		if (!empty($params) && $contentType === 'application/json')
		{
			$body = json_encode($params);
		}



		switch ($type)
		{
			case 'get':
				$result	 = $this->client->get($url, compact('headers'));
				break;
			case 'post':
				$headers += ['Content-Type' => $contentType];
				if ($contentType === 'application/json')
				{
					$result = $this->client->post($url, compact('headers', 'body'));
				}
				else
				{
					$result = $this->client->post($url, compact('headers', 'form_params'));
				}

				break;
			case 'delete':
				$headers += ['Content-Type' => $contentType];
				$result	 = $this->client->delete($url, compact('headers', 'body'));
				break;
			case 'put':
				$headers += ['Content-Type' => $contentType];
				$result	 = $this->client->put($url, compact('headers', 'body'));
				break;
			default:
				$result	 = null;
				break;
		}

		if ($result->getStatusCode() == 200 || $result->getStatusCode() == 201)
		{
			return array
				(
				'status'	 => true,
				'message'	 => json_decode($result->getBody())
			);
		}

		return array
			(
			'status'	 => false,
			'message'	 => json_decode($result->getBody())
		);
	}

	/**
	 * This function is used for adding department
	 * @param type $deptName
	 * @return type
	 */
	public function addDepartment($deptName)
	{
		$endpoint		 = '/department';
		$jsonData		 = new stdClass();
		$jsonData->Name	 = $deptName;
		return $this->doRequest('post', $endpoint, $jsonData);
	}

	/**
	 * This function is used for adding new user
	 * @param type $jsonData
	 * @return type
	 */
	public function addUser($jsonData)
	{
		$endpoint = "/user";
		return $this->doRequest('post', $endpoint, $jsonData);
	}

	/**
	 * This function is used for chat creation
	 * @param type $jsonData
	 * @return type
	 */
	public function createChat($jsonData)
	{
		$endpoint = "/chat";
		return $this->doRequest('post', $endpoint, $jsonData);
	}

	/**
	 * This function is used for fetching the chat details
	 * @param type $chatId
	 * @param type $lastMsgId
	 * @return type
	 */
	public function fetchMessages($chatId, $lastMsgId)
	{
		$data = array
			(
			'chat_id'				 => $chatId,
			'last_message_id'		 => $lastMsgId,
			'ignore_system_messages' => false,
			'extract_media'			 => false,
			'remove_media'			 => false,
			'as_html'				 => false,
			'file_as_link'			 => false
		);

		$queryParams = http_build_query($data);
		$endPoint	 = "/fetchchatmessages?" . $queryParams;
		return $this->doRequest('get', $endPoint);
	}

	/**
	 * This function is used for adding message as a user
	 * @param type $jsonData
	 * @return type
	 */
	public function addUserMsg($chatId, $msg)
	{
		$endPoint	 = "/addmsguser";
		$data		 = array
			(
			'chat_id'	 => $chatId,
			'msg'		 => $msg
		);
		return $this->doRequest('post', $endPoint, $data, "application/x-www-form-urlencoded");
	}

	public function updateUser($user_id, $password)
	{
		$endPoint			 = "/user/" . $user_id;
		$jsonData			 = new stdClass();
		$jsonData->password	 = $password;
		return $this->doRequest('put', $endPoint, $jsonData);
	}

	public function getUserInfo($email)
	{
		$data		 = array(
			'email' => $email
		);
		$queryParams = http_build_query($data);
		$endPoint	 = "/getuser?" . $queryParams;
		return $this->doRequest('post', $endPoint);
	}

}
