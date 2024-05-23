<?php

/**
 * Description of CommonSelectServiceConversation
 *
 * @author Suvajit
 */
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Storages\Storage;

class CommonSelectServiceConversation extends Conversation
{

	protected $refTypeId	 = 0;
	protected $refDesc		 = "";
	protected $isLoggedIn	 = 0;
	protected $stBkgCode	 = "";
	protected $stProfile	 = "";
	protected $contactNo	 = "";
	protected $arrContactNo	 = [];
	protected $desc			 = "";

	public function __construct($bkgCode = "", $profile = "", $desc = "")
	{
		$this->stBkgCode = $bkgCode;
		$this->stProfile = $profile;
		$this->desc		 = $desc;
	}

	public function run()
	{
		$this->checkProfile();
	}

	public function askCallMeBack()
	{
		if ($this->stProfile == UserInfo::TYPE_CONSUMER)
		{
			$this->refTypeId = 1;
			$this->askForCallBackDescReason();
		}
		else
		{
			$buttonArray = [];
			$rDetail	 = ServiceCallQueue::getReasonList();
			foreach ($rDetail as $key => $val)
			{
				$button			 = Button::create($val)->value($key);
				$buttonArray[]	 = $button;
			}

			$question = Question::create('Please select your reason. So that i can priortise you')
					->callbackId('select_service')
					->addButtons($buttonArray);

			array_push($buttonArray, Button::create('Go Back')->value(-5));

			$this->ask($question, function (Answer $answer) {
				if ($answer->isInteractiveMessageReply())
				{
					$this->refTypeId = $answer->getValue();
					if (($this->refTypeId == 2) && (empty($this->stBkgCode)))
					{
						$this->stProfile = UserInfo::TYPE_CONSUMER;
						$this->askForBookingCode();
					}
					elseif ($this->refTypeId == -5)
					{
						$this->bot->startConversation(new OldUserSelectServiceConversation());
					}
					else
					{
						if (($this->refTypeId == 1))
						{
							$this->stBkgCode = "";
							$this->stProfile = UserInfo::TYPE_CONSUMER;
						}
						$this->askForCallBackDescReason();
					}
				}
			});
		}
	}

	public function askForBookingCode()
	{
		$question = Question::create("Please provide your booking ID");
		$this->ask($question, function (Answer $answer) {
			$this->stBkgCode = $answer->getValue();
			$this->askForCallBackDescReason();
		});
	}

	public function askForCallBackDescReason()
	{
		$question = Question::create("Provide a short description of your query");
		$this->ask($question, function (Answer $answer) {
			$this->refDesc = $answer->getValue();
			$this->callBackModel();
		});
	}

	public function callBackModel()
	{
		$data			 = new stdClass();
		$data->bkgCode	 = $this->stBkgCode;
		$data->profile	 = $this->stProfile;
		$data->refTypeId = $this->refTypeId;
		$data->refDesc	 = $this->refDesc;

		$response = ServiceCallQueue::processData($data);
		$this->say("I've sent your request to our team and they will be calling you shortly at the phone number specified in your user profile");
		$this->say("Our team will get back to you soon!");
		$this->bot->startConversation(new OldUserSelectServiceConversation());
	}

	public function directRegister()
	{
		$data			 = new stdClass();
		$data->bkgCode	 = $this->stBkgCode;
		$data->profile	 = $this->stProfile;
		$data->refTypeId = 1;
		$data->refDesc	 = $this->desc;

		$response = ServiceCallQueue::processData($data);
		$this->say($response->getMessage());
		$this->say("Our team will get back to you soon!");
		$this->startOver();
	}

	public function startOver($question = null)
	{
		if ($question == null)
		{
			$question = "OK. Now thats done, What else can I help you with....";
		}
		$endQuestion = Question::create($question)
				->callbackId('select_end')
				->addButtons([
			//Button::create('Schedule a Call Back')->value(1),
			//Button::create('Start Live Chat')->value(2),
			//Button::create("Check Other Services")->value(3),
			Button::create("Start over")->value(-5),
		]);

		$this->ask($endQuestion, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userInput = $answer->getValue();
				switch ($userInput)
				{
					case -5:
						$this->bot->startConversation(new OldUserSelectServiceConversation());
						break;
				}
			}
		});
	}

	public function checkProfile()
	{
		$buttonArray	 = [];
		$userId			 = UserInfo::getUserId();
		$userModel		 = Users::model()->findByPk($userId);
		$this->contactId = $userModel->usr_contact_id;
		if (!$this->contactId)
		{
			$this->contactId = Users::createByUser($userModel);
		}
		$isVerified = ContactPhone::isVerified($this->contactId);
		if ($isVerified)
		{
			if (!empty($this->desc))
			{
				$this->directRegister();
			}
			else
			{
				$this->askCallMeBack();
			}

			goto skipAll;
		}

		$response = ContactPhone::getAllNumbers($this->contactId);
		if (!$response->getStatus())
		{
			$question = 'Oops!! Your phone number is missing. Please add your phone number';
			$this->getNew($question, $this->contactId);
		}
		else
		{
			$this->arrContactNo = $response->getData();
			foreach ($response->getData() as $key => $val)
			{
				$button			 = Button::create($val)->value($key);
				$buttonArray[]	 = $button;
			}

			array_push($buttonArray, Button::create("Add new")->value(-1));

			$question = Question::create('Seems like your contact is not verified with us. I have the below numbers associated to your profile, Please select a number or provide a new one ')
					->callbackId('select_service')
					->addButtons($buttonArray);

			$this->ask($question, function (Answer $answer) {
				if ($answer->isInteractiveMessageReply())
				{
					$caseId = $answer->getValue();
					switch ($caseId)
					{
						case -1:
							$question = "Ok, Please provide your new number";
							$this->getNew($question, $this->contactId);
							break;

						default:
							foreach ($this->arrContactNo as $key => $val)
							{
								if ((int) $caseId === (int) $key)
								{
									$this->contactNo = $val;
								}
							}
							$this->processPhoneData(0);
							break;
					}
				}
			});
		}
		skipAll:
	}

	public function getNew($question)
	{
		$dQuestion = Question::create($question);
		$this->ask($dQuestion, function(Answer $dAnswer) {
			$this->contactNo = $dAnswer->getValue();
			$this->processPhoneData(1);
		});
	}

	public function processPhoneData($isNew)
	{
		switch ($isNew)
		{
			case 1:
				$arrPhone		 = [];
				$cModel			 = new Stub\common\ContactMedium();
				array_push($arrPhone, $cModel->getPhoneModel($this->contactNo, "91"));
				$phoneResponse	 = ContactPhone::savePhones($arrPhone, $this->contactId);
				if ($phoneResponse->getData())
				{
					$phoneData	 = $phoneResponse->getData();
					$isOtpSend	 = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $this->contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_CONSUMER, 0, $phoneData["otp"], $phoneData["ext"]);
					$message	 = "Oops!!.. I faced a problem updating your data";
					if ($isOtpSend)
					{
						$message = "I have added your phone number and sent a verification URL on it. Please tap that link";
					}
					$this->askForDone();
					$this->say($message);
				}
				break;

			default:
				$response = ContactPhone::resendVerificationLink($this->contactNo, $this->contactId);
				if ($response->getStatus())
				{
					$message = "I have sent a verifcation link on the selected number";
					$this->say($message);
					$this->askForDone();
				}
				else
				{
					$message = "Oops!!.. I faced a problem while sending you the verification link. Is that a mobile number?";
					$this->say($message);
				}
				break;
		}
	}

	public function askForDone()
	{
		$dQuestion = Question::create("Once verified. Please tap 'Done' so I can create your call back request.")
				->addButtons([
			Button::create('Done')->value(1),
		]);
		$this->ask($dQuestion, function(Answer $dAnswer) {
			if ($dAnswer->getValue())
			{
				$this->checkProfile();
			}
		});
	}

}
