<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SelectServiceConversation
 *
 * @author Suvajit
 */
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Storages\Storage;

class OldUserSelectServiceConversation extends Conversation
{

	protected $userChoice		 = 0;
	protected $bookingId		 = 0;
	protected $reasonId			 = 0;
	protected $reason			 = "";
	protected $bkgCode			 = 0;
	protected $profile			 = "";
	protected $lastLoopIndexBkg	 = 0;
	protected $storeData		 = "";
	protected $arrProfile		 = array
		(
		1	 => "Are you a Customer?",
		2	 => "Are you a Taxi Operator/Driver?",
		3	 => "Are you a Vendor?",
		4	 => "Are you a Travel Agent?"
	);
	protected $arrBkgAction		 = array
		(
		1	 => 'Car Information',
		2	 => 'Driver Information',
		3	 => 'Cancel Booking',
		4	 => 'Where is my driver?',
		5	 => 'Update Luggage',
		6	 => 'Add Special Request',
		7	 => 'Update Pickup & Drop Location',
		8	 => 'Payment Problem',
		9	 => 'Get Invoice',
		10	 => 'Problem with Invoice',
		11	 => 'Review',
		12	 => 'Refund Status',
		13	 => 'Make Payment',
		15	 => 'Get me a agent',
		14	 => 'Go Back',
	);

	public function run()
	{
		$this->validateProfile();
	}

	/**
	 * This function validates the profile for that users
	 * Checks with the system how many does it have
	 */
	public function validateProfile()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$profileData = ContactProfile::getProfileByCttId($userModel->usr_contact_id);

		$profileCount		 = 0;
		$arrAsignedProfile	 = [];
		if (empty($profileData))
		{
			array_push($arrAsignedProfile, 1);
			$profileCount++;
			goto skipProfile;
		}


		if (!empty($profileData["cr_is_consumer"]))
		{
			array_push($arrAsignedProfile, 1);
			$profileCount++;
		}
		if (!empty($profileData["cr_is_driver"]))
		{
			array_push($arrAsignedProfile, 2);
			$profileCount++;
		}

		if (!empty($profileData["cr_is_vendor"]))
		{
			array_push($arrAsignedProfile, 3);
			$profileCount++;
		}

		if (!empty($profileData["cr_is_partner"]))
		{
			array_push($arrAsignedProfile, 4);
			$profileCount++;
		}

		skipProfile:
		if ($profileCount == 1)
		{
			$profileKey = $arrAsignedProfile[0];
			$this->switchProfile($profileKey, 1);
		}
		else
		{
			$this->askForProfile($arrAsignedProfile);
		}
	}

	public function switchProfile($profileKey, $single = 0)
	{
		switch ($profileKey)
		{
			case 1:
				$this->profile	 = UserInfo::TYPE_CONSUMER;
				$this->getCustomerAction();
				break;
			case 2:
				$this->profile	 = UserInfo::TYPE_DRIVER;
				if ($single)
				{
					$this->say("You are having only driver profile with us");
				}
				$this->getDriverAction();
				break;
			case 3:
				$this->profile = UserInfo::TYPE_VENDOR;
				if ($single)
				{
					$this->say("You are having only vendor profile with us");
				}
				$this->getVendorAction();
				break;
			case 4:
				$this->profile = UserInfo::TYPE_AGENT;
				if ($single)
				{
					$this->say("You are having only agent profile with us");
				}
				$this->endConversation("How would you like to get connected with us. Please select an option from below");
				//$this->getAgentAction();
				break;
		}
	}

	public function askForProfile($arrAsignedProfile)
	{
		$buttonArray	 = [];
		$arrNewProfile	 = array_intersect_key($this->arrProfile, array_flip($arrAsignedProfile));
		foreach ($arrNewProfile as $key => $val)
		{
			$button			 = Button::create($val)->value($key);
			$buttonArray[]	 = $button;
		}
		$this->say($this->getProfileName());
		$dQuestion = Question::create("Looks like you have multiple roles. Select who you are below....")
				->callbackId('select_profile_service')
				->addButtons($buttonArray);
		$this->ask($dQuestion, function(Answer $dAnswer) {
			$profileKey = $dAnswer->getValue();
			$this->switchProfile($profileKey);
		});
	}

	public function getProfileName()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$name		 = $userModel->usr_name . " " . $userModel->usr_lname;
		return "Hi " . $name;
	}

	public function getDriverAction()
	{
		$question = Question::create('What kind of Service you are looking for?')
				->callbackId('select_service')
				->addButtons([
			Button::create('Attach your Taxi')->value(1),
			Button::create('I have an existing booking')->value(2),
			Button::create('My Cab Is Free')->value(3),
			Button::create('Go to Menu')->value(4),
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userChoice = $answer->getValue();
				switch ($userChoice)
				{
					case 1:
						$say = "Please click this link : to attach your taxi <a href='https://www.gozocabs.com/vendor/join'>https://www.gozocabs.com/vendor/join</a>";
						$this->say($say);
						$this->askForVendorPref();
						break;

					case 2:
						$this->say("You have selected <strong>I have an existing booking </strong>");
						$this->endConversation("How would like to get in touch with you?");
						//$this->askForChatLink();
						//$this->askForVendorPref();
						break;
					case 3:
						$say = "Please check the video to get started. Link : <a href='https://youtu.be/LwgNBXNOQ6M'>https://youtu.be/LwgNBXNOQ6M</a>";
						$this->say($say);
						$this->askForVendorPref();
						break;
					case 4:
						$this->validateProfile();
						break;
				}
			}
		});
	}

	public function getVendorAction()
	{
		$question = Question::create('What kind of Service you are looking for?')
				->callbackId('select_service')
				->addButtons([
			Button::create('Attach your Taxi')->value(1),
			Button::create('I have an existing booking')->value(2),
			Button::create('My Cab Is Free')->value(3),
			Button::create('My Account')->value(4),
			Button::create('Go to Menu')->value(5),
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userChoice = $answer->getValue();
				switch ($userChoice)
				{
					case 1:
						$say = "Please click this link : to attach your taxi <a href='https://www.gozocabs.com/vendor/join'>https://www.gozocabs.com/vendor/join</a>";
						$this->say($say);
						$this->askForVendorPref();
						break;

					case 2:
						$this->say("You have selected <strong>I have an existing booking</strong>");
						$this->endConversation("How would like to get in touch with you?");
						//$this->askForChatLink();
						//$this->askForVendorPref();
						break;
					case 3:
						$say = "Please check the video to get started. Link : <a href='https://youtu.be/LwgNBXNOQ6M'>https://youtu.be/LwgNBXNOQ6M</a>";
						$this->say($say);
						$this->askForVendorPref();
						break;
					case 4:
						$this->say("You have selected <strong>My Account Section</strong>");
						$this->askForAccountService();
						break;
					case 5:
						$this->validateProfile();
						break;
				}
			}
		});
	}

	public function askForAccountService()
	{
		$question = Question::create('What kind of Account Service you are looking for?')
				->callbackId('select_service')
				->addButtons([
			Button::create('Withdrawable Amount')->value(1),
			Button::create('Request Payment')->value(2),
			Button::create('Go Back')->value(3)
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userChoice = $answer->getValue();
				switch ($userChoice)
				{
					case 1:
						$userId		 = UserInfo::getUserId();
						$userModel	 = Users::model()->findByPk($userId);
						$profileData = ContactProfile::getProfileByCttId($userModel->usr_contact_id);
						if (!empty($profileData["cr_is_vendor"]))
						{
							$vendorId	 = $profileData["cr_is_vendor"];
							$amt		 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '')['withdrawable_balance'];
							$say		 = "Your withdrawable amount is Rs. $amt";
							$this->say($say);
							$this->askForVendorPref();
						}
						else
						{
							$say = "Having some issue regarding the amount";
							$this->say($say);
							$this->askForChatLink();
						}
						break;
					case 3:
						$this->getVendorAction();
						$this->askForVendorPref();
						break;

					case 2:
						$this->askForChatLink();
						$this->askForVendorPref();
						break;
				}
			}
		});
	}

	public function getCustomerAction()
	{
		$question = Question::create('Please select from the options below...')
				->callbackId('select_service')
				->addButtons([
			Button::create('I need a quote')->value(1),
			Button::create('I need a cab')->value(1),
			Button::create('I have an existing booking')->value(2),
			Button::create('I have some questions')->value(5),
			Button::create('Other')->value(3),
			Button::create('Go Back')->value(4)
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userChoice = $answer->getValue();
				switch ($userChoice)
				{
					case 1:
						$this->say("OK. So lets first show you the quotation and then if you want you can book it");
						$this->askForNewBooking();
						//$this->askForCallMeBack();
						break;
					case 2:
						$this->say("You have selected <strong>I have an existing booking</strong>");
						$this->askForBookingId();
						break;

					case 3:
						$this->say("OK. You've picked Other");
						$this->askForCallMeBack();
						break;
					case 4:
						$this->validateProfile();
						break;
					case 5:
						$this->say("OK. So, You have some questions.");
						$this->askForCustomerQuery();
						break;
				}
			}
		});
	}

	public function askForCustomerQuery()
	{
		$question = Question::create('Type some keywords which is relevent to your query');
		$this->ask($question, function(Answer $answer) {
			$keywords		 = $answer->getValue();
			$findQuestion	 = BotFaq::getKeyBasedData($keywords);

			if (empty($findQuestion))
			{
				$this->say("I didn’t find a question by that keywords. Either the keyword you entered is not matching so please type relevant keywords of format like (cab driver fare)");
			}

			$buttonArray = [];
			foreach ($findQuestion as $val)
			{
				$button			 = Button::create($val["bof_question"])->value($val["bof_id"]);
				$buttonArray[]	 = $button;
			}
			$msg = "";
			if ($buttonArray)
			{
				$msg = "I found  few questions for your query by that keywords, click on it to get the solution";
			}
			else
			{
				$msg = "I countn't find any relevant question for your query. Please type another keyword";
			}
			array_push($buttonArray, Button::create("start over")->value(-6));

			$question = Question::create($msg)
					->callbackId('select_service')
					->addButtons($buttonArray);

			$this->ask($question, function (Answer $answer) {
				if ($answer->isInteractiveMessageReply())
				{
					switch ($answer->getValue())
					{
						case -6:
							$this->validateProfile();
							break;
						default:
							$this->id		 = $answer->getValue();
							$this->answer	 = BotFaq::getColumnValue("bof_answer", $this->id);
							$this->say("Here is your answer for selected question:</br><strong>" . $this->answer . "</strong>");
							$this->endConversation("I think your query has been resolved now. Thanks!! for contacting us..");
							break;
					}
				}
			});
		});
	}

	public function __loadBooking($data)
	{
		$index		 = 0;
		$buttonArray = [];
		foreach ($data as $val)
		{
			$appendHtml	 = "Booking ID :" . $val->bookingId . "\n";
			$appendHtml	 .= "From:" . $val->routeNames[0] . " | To:" . $val->routeNames[1] . "\n";
			$appendHtml	 .= "Leaving on date: " . $val->pickupDate . " |  time: " . $val->pickupTime;

			if ($index < 4)
			{
				$button					 = Button::create($appendHtml)->value($val->bookingId);
				$buttonArray[]			 = $button;
				$this->lastLoopIndexBkg	 = $index;
			}

			$index++;
		}

		return $buttonArray;
	}

	public function askForBookingId()
	{
		$userId			 = UserInfo::getUserId();
		$result			 = Booking::getByUserId($userId, "bk", 0);
		$data			 = $result->getData();
		$this->storeData = $data;
		if (empty($data))
		{
			$this->say("Seems like you haven't booked any ride with us. Let book a ride for you");
			$this->askForNewBooking();
			goto skipAll;
		}

		$buttonArray = $this->__loadBooking($data);

		//array_push($buttonArray, Button::create("Load More")->value(-5));
		array_push($buttonArray, Button::create("Enter Booking Code")->value(-6));
		array_push($buttonArray, Button::create("Go Back")->value(-7));


		$dQuestion = Question::create("I've pulled up all your recent bookings. I'm showing you the most recent 4 items. Please select the one that you need. If the booking you want is not listed, then enter its booking code")
				->callbackId('select_bkg_service')
				->addButtons($buttonArray);
		$this->ask($dQuestion, function(Answer $dAnswer) {

			$reply = $dAnswer->getValue();
			switch ($reply)
			{
				case -6:
					$this->askForCode();
					break;
//				case -5:
//					$this->loadMore
//					break;
				case -7:
					$this->validateProfile();
					break;
				default:
					$this->bkgCode = $dAnswer->getValue();
					$this->validateBookingState();
					break;
			}
		});

		skipAll:
	}

	public function askForCode()
	{
		$dQuestion = Question::create("Please enter your booking code. So that I can validate for you")
				->callbackId('select_bkg_service');
		$this->ask($dQuestion, function(Answer $dAnswer) {

			$reply			 = $dAnswer->getValue();
			$this->bkgCode	 = $dAnswer->getValue();
			$this->validateBookingState();
		});
	}

	/**
	 * This function is used for validating the booking status and switches action
	 * based on it.
	 */
	public function validateBookingState()
	{
		$bkgStatus = (int) Booking::getStatusByCode($this->bkgCode);

		if ($bkgStatus == 0)
		{
			$this->say("Oops! I dont see that booking ID. Or maybe the booking is for someone else. Let me see if I can find you a specialist to help...");
			$this->endConversation();
			goto skipAll;
		}
		$arrBkgActions		 = $this->arrBkgAction;
		$arrKeepBkgAction	 = [];
		switch ($bkgStatus)
		{
			//All are booking status
			case 1:
			case 2:
			case 3:
			case 5:
				$arrKeepBkgAction	 = array(1, 2, 3, 4, 5, 6, 7, 8, 14, 15);
				break;
			case 4:
				$arrKeepBkgAction	 = array(1, 2, 3, 4, 5, 6, 7, 14, 15);
				break;
			case 6:
				$arrKeepBkgAction	 = array(9, 10, 11, 14, 15);
				break;
			case 9:
				$this->say("Oops! Seems like this booking is cancelled.");
				$this->endConversation();
				goto skipAll;
				break;
			case 12:
				$refundStatus		 = PaymentGateway::getStatusByBkgCode($this->bkgCode);
				$this->say($refundStatus->getMessage());
				$this->endConversation();
				goto skipAll;
				break;
			case 15:
				$arrKeepBkgAction	 = array(3, 13, 14, 15);
				break;
		}

		$buttonArray	 = [];
		$arrNewActions	 = array_intersect_key($arrBkgActions, array_flip($arrKeepBkgAction));
		foreach ($arrNewActions as $key => $val)
		{
			$button			 = Button::create($val)->value($key);
			$buttonArray[]	 = $button;
		}

		$dQuestion = Question::create("Please pick one of the options below...")
				->callbackId('select_bkg_service')
				->addButtons($buttonArray);
		$this->ask($dQuestion, function(Answer $dAnswer) {
			$userChoice = $dAnswer->getValue();
			$this->triggerExistingBkgAction($userChoice);
		});

		skipAll:
	}

	public function getInvoice()
	{
		$response = BookingInvoice::getLink($this->bkgCode);
		if ($response->getStatus())
		{
			$this->say("Please <a href='" . $response->getData() . "' downlaod='download'>Click here</a> to get your trip invoice. Please wait till it gets downloaded in your system");
			$this->endConversation();
		}
		else
		{
			$this->say("Oops!!.. I got stuck while genearting the invoice");
		}
	}

	public function getBookingLink()
	{
		$response = Booking::getLink($this->bkgCode);
		if ($response->getStatus())
		{
			$this->say("Please <a href='" . $response->getData() . "' target='blank'>Click here</a> to check or update your details");
			$this->endConversation();
		}
		else
		{
			$this->say("Oops!!.. I got stuck while genearting the invoice");
		}
	}

	public function getReviewLink()
	{
		$response = Booking::getReviewLink($this->bkgCode);
		if ($response->getStatus())
		{
			$this->say("Please <a href='" . $response->getData() . "' target='blank'>Click here</a> to submit your review about your trip. Your review matters a lot to us");
			$this->endConversation();
		}
		else
		{
			$this->say("Oops!!.. I got stuck while genearting the invoice");
		}
	}

	public function triggerExistingBkgAction($userChoice)
	{
		switch ($userChoice)
		{
			case 1:
				//Car Details
				$this->getDetails($this->bkgCode, 3);
				break;
			case 2:
				//Driver info
				$this->getDetails($this->bkgCode, 2);
				break;
			case 3:
				//Cancel Booking
				$this->bookingId = $this->bkgCode;
				$this->askReason();
				break;
			case 4:
			case 5:
			case 6:
			case 7:
			case 13:
				$this->getBookingLink();
				break;
			case 8:
			case 10:
				//Call me back
				//$this->say("You have requested for <strong>Call Back</strong> service");
				$this->askForCallMeBack();
				break;
			case 9:
				$this->getInvoice();
				break;
			case 11:
				$this->getReviewLink();
				break;
			case 14:
				$this->askForBookingId();
				break;
			case 15:
				$this->endConversation("Ok!.. How would you like to get connected. Please choose from below");
				break;
		}
	}

	public function getDetails($bkgCode, $type)
	{
		$data		 = new stdClass();
		$data->id	 = $bkgCode;
		$result		 = Booking::getDetails($data);
		if ($result->getStatus())
		{
			switch ($type)
			{
				case 2://Driver details
					if (!empty($result->getData()->driver->name))
					{
						$appendData	 = "Here is your driver details below:" . "<br/><br/>";
						$appendData	 .= "<strong>Driver Name:&nbsp;</strong>" . $result->getData()->driver->name . "<br/>";
						$appendData	 .= "<strong>Contact No:&nbsp;</strong>" . $result->getData()->driver->contact->code . "-" . $result->getData()->driver->contact->number;
						$this->say($appendData);
					}
					else
					{
						$userId		 = UserInfo::getUserId();
						$userModel	 = Users::model()->findByPk($userId);
						$name		 = $userModel->usr_name;
						$this->say("Hi " . $name . ", Seems like driver is not yet assigned for your trip. We will let you know once the driver is assigned");
					}
					break;

				case 3: //Car details
					if (!empty($result->getData()->car->number))
					{
						$appendData	 = "Here is your assigned car details below:" . "<br/><br/>";
						$appendData	 .= "<strong>Car No:&nbsp;</strong>" . $result->getData()->car->number . "<br/>";
						$this->say($appendData);
					}
					else
					{
						$userId		 = UserInfo::getUserId();
						$userModel	 = Users::model()->findByPk($userId);
						$name		 = $userModel->usr_name;
						$this->say("Hi " . $name . ", Seems like car is not yet assigned for your trip. We will let you know once the car is assigned");
					}
					break;

				default:
					break;
			}
		}
		else
		{
			$this->say($result->getMessage());
		}

		$this->endConversation();
	}

	public function askForCallMeBack()
	{
		$this->bot->startConversation(new CommonSelectServiceConversation($this->bkgCode, $this->profile));
	}

	public function askForNewBooking()
	{
		$this->bot->startConversation(new NewBookingServiceConversation());
	}

	public function askReason()
	{
		$buttonArray = [];
		$rDetail	 = CancelReasons::model()->getListbyUserType(1);
		foreach ($rDetail[0] as $key => $val)
		{
			$button			 = Button::create($val)->value($key);
			$buttonArray[]	 = $button;
		}

		$question = Question::create('Please select the reason for your cancellation.')
				->callbackId('select_service')
				->addButtons($buttonArray);

		$this->ask($question, function (Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$this->reasonId = $answer->getValue();
				//$this->storeInfo();
				$this->askForDescReason();
			}
		});
	}

	public function askForDescReason()
	{
		$question = Question::create('Can you please describe your reason.It will help us to serve you better in future')
				->callbackId('select_service');

		$this->ask($question, function (Answer $answer) {
			$this->reason = $answer->getValue();
			$this->storeInfo();
			$this->callCancelBooking();
		});
	}

	public function callCancelBooking()
	{
		$data	 = $this->storeInfo();
		$result	 = Booking::validateCancelData($data);
		$message = $result->getMessage();
		$this->say($message);
		$this->endConversation();
	}

	public function startOver($question = null)
	{
		if ($question == null)
		{
			$question = "OK. Now thats taken care of. What else can I help you with....";
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
						$this->validateProfile();
						break;
				}
			}
		});
	}

	public function endConversation($question = null)
	{
		if ($question == null)
		{
			$question = "OK. Now thats taken care of. What else can I help you with....";
		}
		$endQuestion = Question::create($question)
				->callbackId('select_end')
				->addButtons([
			Button::create('Schedule a Call Back')->value(1),
			Button::create('Start Live Chat')->value(2),
			//Button::create("Check Other Services")->value(3),
			Button::create("Go To Main Menu")->value(-5),
		]);

		$this->ask($endQuestion, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userInput = $answer->getValue();
				switch ($userInput)
				{
					case -5:
						$this->validateProfile();
						break;
					case 1:
						$this->askForCallMeBack();
						break;
					case 2:
						$this->askForChatLink();
						break;
					case 3:
						$this->askForPreference();
						break;
				}
			}
		});
	}

	public function askForChatLink()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$link		 = Rooms::processData($userModel->usr_contact_id, $this->profile);
		$appendData	 = "Opps!!. There is some issue";
		if (!empty($link))
		{
			$appendData = "Please <a href='" . $link . "' target='blank'>Click here</a> to start a conversation with our executive";
		}
		$this->say($appendData);
	}

	public function askForVendorPref()
	{
		$question = Question::create('What would you prefer for other service check ?')
				->callbackId('select_end')
				->addButtons([
			Button::create("Go to Menu")->value(1)
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userInput = $answer->getValue();
				switch ($userInput)
				{
					case 1:
						if ((int) $this->profile === 3)
						{
							$this->getVendorAction();
						}

						if ((int) $this->profile === 2)
						{
							$this->getDriverAction();
						}
						break;
				}
			}
		});
	}

	public function askForPreference()
	{
		$question = Question::create('What would you prefer for other service check ?')
				->callbackId('select_end')
				->addButtons([
			Button::create("Procced with previous code")->value(1),
			Button::create("Enter new code")->value(2),
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userInput = $answer->getValue();
				switch ($userInput)
				{
					case 1:
						$this->validateBookingState();
						break;
					case 2:
						$this->askForBookingId();
						break;
				}
			}
		});
	}

	public function storeInfo()
	{
		$data			 = new stdClass();
		$data->bookingId = $this->bookingId;
		$data->reasonId	 = $this->reasonId;
		$data->reason	 = $this->reason;
		return $data;
	}

}
