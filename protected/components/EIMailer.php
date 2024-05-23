<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class EIMailer extends YiiMailer
{

	public static $_instance = [];
	public $configParams	 = [];

	public static function getInstance($type)
	{
		$arr = self::$_instance;
		if (array_key_exists($type, $arr))
		{
			$mail = $arr[$type];
		}
		else
		{
			$mail					 = new EIMailer();
			$mail->setTypeParams($type);
			self::$_instance[$type]	 = $mail;
		}
		$mail->clear();
		$mail->clearView();
		$mail->clearLayout();
		return $mail;
	}

	public function setTypeParams($type)
	{
		$mailParam = Yii::app()->params['mail'];
		switch ($type)
		{
			case EmailLog::SEND_ACCOUNT_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail-2'], $mailParam['Vendor']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_SERVICE_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail'], $mailParam['Booking']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_VENDOR_BATCH_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail-1'], $mailParam['AccountServices']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_CONSUMER_BATCH_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail-1'], $mailParam['ConsumerServices']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_METERDOWN_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail'], $mailParam['Meterdown']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_AGENT_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail'], $mailParam['AgentServices']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_DRIVER_EMAIL:
				$params	 = CMap::mergeArray($mailParam['noReplyMail'], $mailParam['AccountServices']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_ADMIN_EMAIL :
				$params	 = CMap::mergeArray($mailParam['noReplyMail'], $mailParam['Booking']);
				$this->setParams($params);
				break;
			case EmailLog::SEND_DAILY_EMAIL :
				$params	 = CMap::mergeArray($mailParam['noReplyMail'], $mailParam['dailyMail']);
				$this->setParams($params);
				break;
			default:
				$params	 = CMap::mergeArray($mailParam['info'], $mailParam['Booking']);
				$this->setParams($params);
				break;
		}
	}

	public function setConfigParams($paramName)
	{
		$params = Yii::app()->params[$paramName];
		$this->setParams($params);
	}

	public function setParams($params)
	{
		if (!is_array($params))
		{
			throw new CException("Mailer params must be an array!");
		}
		$this->configParams = $params;
		foreach ($params as $key => $val)
		{
			$this->$key = $val;
		}
	}

	public function sendMail_old($data_flag = 1)
	{
		$success = true;

		if ($data_flag == 1)
		{
			$this->render();
			$success = false;
			goto skipSend;
		}
		$sendMail = Yii::app()->params['sendMail'];
		if (!$sendMail)
		{
			$this->render();
			goto skipSend;
		}
		$demoMail = Yii::app()->params['demoMail'];
		if ($demoMail != '')
		{
			$this->setTo([$demoMail]);
		}

		$success = $this->send();
		if (!$success)
		{
			Logger::writeToConsole(json_encode($this->configParams));
		}
		skipSend:
		return $success;
	}

	public function sendMail($data_flag = 1)
	{
		$success = true;
		if ($data_flag == 1)
		{
			$this->render();
			$success = false;
			goto skipSend;
		}
		Logger::profile(json_encode(Logger::getBackTrace()));
		$sendMail		 = Yii::app()->params['sendMail'];
		$mail			 = $this->getMailByDomain();
		$demoFromMail	 = Yii::app()->params['demoFromMail'];
		
		if ($demoFromMail != '')
		{
			$fromMail = $demoFromMail . $this->From;
			$this->setFrom([$fromMail]);
		}
		if ($mail != '')
		{
			$this->setTo([$mail]);
		}
		if (!$sendMail)
		{
			$this->render();
			goto skipSend;
		}

		$success = $this->send();
		if (!$success)
		{
			Logger::writeToConsole(json_encode($this->configParams));
		}
		skipSend:
		return $success;
	}

	public function getMailByDomain()
	{
		$demoMail		 = Yii::app()->params['demoMail'];
		$arrDemoDomains	 = Yii::app()->params['demoDomains'];
		$mail			 = $this->to[0][0];
		$finalDemoEmail	 = '';
		if (count($arrDemoDomains) > 0)
		{
			$arr = explode("@", $mail);
			if (in_array($arr[1], $arrDemoDomains))
			{
				$finalDemoEmail = $mail;
			}
		}
		if (!$finalDemoEmail && $demoMail != '')
		{
			$finalDemoEmail = $demoMail;
		}

		return $finalDemoEmail;
	}

	public function sendAccountsEmail($flag = 1)
	{
		$params = Yii::app()->params['mail']['noReplyMail'] + Yii::app()->params['mail']['Accounts'];
		$this->setParams($params);
		return $this->sendMail($flag);
	}

	public function sendServicesEmail($flag = 1)
	{
		$params = Yii::app()->params['mail']['info'] + Yii::app()->params['mail']['Booking'];
		$this->setParams($params);
		return $this->sendMail($flag);
	}

	public function sendBookingEmail($flag = 1)
	{
		$params = Yii::app()->params['mail']['Booking'] + Yii::app()->params['mail']['Booking'];
		$this->setParams($params);
		return $this->sendMail($flag);
	}

	public function sendAgentEmail($flag = 1)
	{
		$params = Yii::app()->params['mail']['noReplyMail'] + Yii::app()->params['mail']['Accounts'];
		$this->setParams($params);
		return $this->sendMail($flag);
	}

	public function sendDriverEmail($flag = 1)
	{
		$params = Yii::app()->params['mail']['noReplyMail'] + Yii::app()->params['mail']['Accounts'];
		$this->setParams($params);
		return $this->sendMail($flag);
	}

	public function sendMeterDownEmail($flag = 1)
	{
		$params = Yii::app()->params['mail']['noReplyMail'] + Yii::app()->params['mail']['Meterdown'];
		$this->setParams($params);
		return $this->sendMail($flag);
	}

	 /** 
	  * 
	  * @param integer $flag
	  * @param integer $refType
	  * @return string
	  */
	public function setPath($flag = 0, $refType = ScheduleEvent::BOOKING_REF_TYPE)
	{

		$this->viewPath = parent::getViewPath();
		if($flag==1)
		{
			switch ($refType)
			{
				case 1 :
				$path = 'application.views.mail.booking';
				break;
				case 2 :
				$path = 'application.views.mail.trip';
				break;
				case 3 :
				$path = 'application.views.mail.vendor';
				break;
				case 4 :
				$path = 'application.views.mail.driver';
				break;
				case 5 :
				$path = 'application.views.mail.customer';
				break;
			}
			$this->viewPath = $path;
		}
	
		return $this->viewPath;
	}

}
