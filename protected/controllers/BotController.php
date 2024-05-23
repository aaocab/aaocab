<?php

include_once(dirname(__FILE__) . '/BaseController.php');

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\DoctrineCache;
use Doctrine\Common\Cache\FilesystemCache;
use BotMan\BotMan\Storages\Storage;

class BotController extends BaseController
{

	public $isLoggedIn = 0;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
		);
	}

	public function actionBot()
	{
		$config				 = [];
		$doctrineCacheDriver = new FilesystemCache(PUBLIC_PATH . "/assets/bot");
		DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
		$botman				 = BotManFactory::create($config, new DoctrineCache($doctrineCacheDriver));

		// Give the bot something to listen for.
		$botman->hears("(.*)", function (BotMan $bot) {
			$bot->startConversation(new OnboardingConversation());
		});

		$botman->hears("done", function (BotMan $bot) {
			$bot->startConversation(new OnboardingConversation());
		});

		$botman->fallback(function($bot) {
			$bot->reply('Sorry, I did not designed to understand these commands. Please type Hi : ...');
		});

		// Start listening
		$botman->listen();
		die();
	}

	/**
	 * This function is used for processing the departments
	 * @return int
	 */
	public function actionCreateDepartments()
	{
		//$drDetails = Departments::getList(0);
		$drDetails = Teams::getCdtList();
		if (empty($drDetails))
		{
			return 0;
		}

		foreach ($drDetails as $dptValue)
		{
			$dptName	 = $dptValue["tea_name"] . "(" . $dptValue["dpt_name"] . "/" . $dptValue["cat_name"] . ")";
			$response	 = Departments::processChatServer($dptValue["cdt_id"], $dptName);
			if ($response)
			{
				echo "Proceced" . $dptName;
				echo "\n";
			}
		}
		exit();
	}

	/**
	 * This function is used for creating the admin users in the chat server
	 * @return int
	 */
	public function actionCreateAdminUsers()
	{
		$drDetails = Admins::getAllByCDT();
		if (empty($drDetails))
		{
			return 0;
		}

		foreach ($drDetails as $admValue)
		{
			$response = ProsodyUsers::processData($admValue);
			if ($response)
			{
				echo "Admin Id:" . $admValue["adm_id"] . " added successfully. and admin table updated with chat Id: $response";
				echo "\n";
			}
		}
		exit();
	}

	/**
	 * This function is used for fetching the messages
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function actionFetch()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data			 = Yii::app()->request->rawBody;
			$receivedData	 = CJSON::decode($data, false);
			if (empty($receivedData))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			};

			$chatId		 = $receivedData->chatRoomId;
			$lastMsgId	 = $receivedData->lastMsgId ?? 0;
			$response	 = Yii::app()->liveChat->fetchMessages($chatId, $lastMsgId);
			if ($response["status"])
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response["message"]->result->messages);
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		skipAll:
		echo json_encode($returnSet);
		exit;
	}

	/**
	 * This function is used for adding new messages
	 * @throws Exception
	 */
	public function actionAddMsg()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data			 = Yii::app()->request->rawBody;
			$receivedData	 = CJSON::decode($data, false);
			if (empty($receivedData))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			};
			$chatId		 = $receivedData->chatId;
			$msg		 = $receivedData->msg;
			$response	 = Yii::app()->liveChat->addUserMsg($chatId, $msg);
			if ($response["status"])
			{
				$returnSet->setData($response["message"]->result->msg);
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}

		skipAll:
		echo json_encode($returnSet);
		exit;
	}

	public function actionLiveChat()
	{
		$this->renderPartial('liveChat', array(), false, true);
	}

    /**
	 * This function is used for updating the users password..
	 */
	public function actionUpdatePassword()
	{
		$returnSet = new ReturnSet();
		try
		{
			$password	 = Yii::app()->request->getParam('password');
			$email		 = Yii::app()->request->getParam('email');
			$response	 = Yii::app()->liveChat->getUserInfo($email);
			$userId		 = $response["message"]->result->id;
			$response	 = Yii::app()->liveChat->updateUser($userId, $password);
			if ($response["status"])
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response["message"]->result);
			}
			else
			{
				$returnSet->setData($response["message"]->result);
			}
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		echo json_encode($returnSet);
		exit;
	}

}

?>