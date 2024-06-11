<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewBookingServiceConversation
 *
 * @author Suvajit
 */
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class NewBookingServiceConversation extends Conversation
{

	protected $fromCity, $toCity, $pickupDate			 = "";
	protected $journeyType			 = 0;
	protected $isLoggedIn			 = 0;
	protected $tripType				 = 0;
	protected $startDate, $startTime, $startDateRound, $startTimeRound, $selToCityName, $selFromCityName, $personCount			 = "";
	protected $selFromCityNameLat, $selFromCityNameLong, $selToCityNameLat, $selToCityNameLong	 = "";
	protected $routeData			 = [];
	protected $quotedData			 = "";
	protected $cabRate				 = "";
	protected $totalAmount			 = "";
	protected $cabType, $selCabId, $airportName			 = "";
	protected $bookFlag, $multiCity			 = 0;
	protected $dayRentalException	 = "";
	protected $arrTime				 = array
		(
		"08:00 AM"		 => "08:00 AM",
		"09:00 AM"		 => "09:00 AM",
		"10:00 AM"		 => "10:00 AM",
		"11:00 AM"		 => "11:00 AM",
		"01:00 PM"		 => "01:00 PM",
		"03:00 PM"		 => "03:00 PM",
		"05:00 PM"		 => "05:00 PM",
		"07:00 PM"		 => "07:00 PM",
		"Custom Time"	 => -5
	);

	public function run()
	{
		$this->BookingType();
	}

	/**
	 * @todo - Suvajit - Will be done new flow
	 */
	public function askForService()
	{
		$question = Question::create('Please select from below')
				->callbackId('select_new_booking_service')
				->addButtons([
			Button::create('Book Trip')->value(1),
			Button::create('Go Back')->value(3)
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$action = $answer->getValue();
				switch ($action)
				{
					case 1:
						$this->say("You have selected <strong>Book Trip</strong>");
						$this->BookingType();
						break;

//					case 2:
//						$this->say("You have selected <strong>I have some questions</strong>");
//						$this->say("Please <a href='http://www.aaocab.com/faq#faq1' target='_blank'>Click here</a> to give wings to your thoughts n enjoy the services of gozo");
//						$this->endConversation();
//						break;

					case 3:
						$this->say("You have selected <strong>Go Back</strong>");
						$this->bot->startConversation(new OldUserSelectServiceConversation());
						break;
				}
			}
		});
	}

	public function askBookingService()
	{
		$question = Question::create('Type the name of the city you are going from');
		$this->ask($question, function(Answer $answer) {
			$cityName	 = explode(' ', $answer->getValue(), 2)[0];
			$drCity		 = Cities::model()->getSourceCities($cityName);
			switch ($this->tripType)
			{
				case 1:
					$serviceType = "One Way";
					break;

				case 2:
					$serviceType = "Round Trip";
					break;

				case 3:
					$serviceType = "Airport Transfer";
					break;
				case 5:
					$serviceType = "Day Rentals";
					break;
				default:
					break;
			}
			if (empty($drCity))
			{
				$this->say("I didn’t find a city by that name. Either the place you entered is too far away from your source city for a<strong>" . $serviceType . "</strong>service or maybe we are not serving to your destination on that date.");
			}

			$buttonArray = [];
			foreach ($drCity as $val)
			{
				$button			 = Button::create($val["cty_name"])->value($val["cty_id"]);
				$buttonArray[]	 = $button;
			}
			$msg = "";
			if ($buttonArray)
			{
				$msg = "I found a few places by that name, pick the closet one";
			}
			array_push($buttonArray, Button::create("Change city")->value(-5));
			array_push($buttonArray, Button::create("Go Back")->value(-6));

			$question = Question::create($msg)
					->callbackId('select_service')
					->addButtons($buttonArray);

			$this->ask($question, function (Answer $answer) {
				if ($answer->isInteractiveMessageReply())
				{
					switch ($answer->getValue())
					{
						case -5:
							$this->askBookingService();
							break;
						case -6:
							$this->BookingType();
							break;
						default:
							$this->fromCity				 = $answer->getValue();
							$this->selFromCityName		 = Cities::getColumnValue("cty_display_name", $this->fromCity);
							$this->selFromCityNameLat	 = Cities::getColumnValue("cty_lat", $this->fromCity);
							$this->selFromCityNameLong	 = Cities::getColumnValue("cty_long", $this->fromCity);
							$this->say("OK you are traveling from <strong>" . $this->selFromCityName . "</strong>");
							$this->askForDestination();
							break;
					}
				}
			});
		});
	}

	public function askForDestination()
	{
		$question = Question::create('Type the name of the city you are going to');
		$this->ask($question, function(Answer $answer) {
			$cityName	 = explode(' ', $answer->getValue(), 2)[0];
			$drCity		 = Cities::model()->getNearestcityList($this->fromCity, $cityName, "", 1);
			switch ($this->tripType)
			{
				case 1:
					$serviceType = "One Way";
					break;

				case 2:
					$serviceType = "Round Trip";
					break;

				case 3:
					$serviceType = "Airport Transfer";
					break;
				case 5:
					$serviceType = "Day Rentals";
					break;
				default:
					$break;
			}
			if (empty($drCity))
			{
				$this->say("I didn’t find a city by that name. Either the place you entered is too far away from your source city for a <strong>" . $serviceType . "</strong> service or maybe we are not serving to your destination on that date.");
			}
			$buttonArray = [];
			foreach ($drCity as $val)
			{
				$button			 = Button::create($val["text"])->value($val["id"]);
				$buttonArray[]	 = $button;
			}
			$msg = "";
			if ($buttonArray)
			{
				$msg = "I found a few places by that name, pick the closet one";
			}
			array_push($buttonArray, Button::create("Change city")->value(-5));
			array_push($buttonArray, Button::create("Go Back")->value(-6));

			$question = Question::create($msg)
					->callbackId('select_service')
					->addButtons($buttonArray);

			$this->ask($question, function (Answer $answer) {
				if ($answer->isInteractiveMessageReply())
				{
					switch ($answer->getValue())
					{
						case -5:
							$this->askForDestination();
							break;
						case -6:
							$this->BookingType();
							break;
						default:
							$this->toCity = $answer->getValue();

							if ($this->toCity == $this->fromCity)
							{
								$this->say("Seems like you have selected the same city again");
								$this->askForDestination();
							}
							else
							{
								$this->selToCityName	 = Cities::getColumnValue("cty_display_name", $this->toCity);
								$this->selToCityNameLat	 = Cities::getColumnValue("cty_lat", $this->toCity);
								$this->selToCityNameLong = Cities::getColumnValue("cty_long", $this->toCity);
								$this->say("You have selected <strong>" . $this->selToCityName . "</strong> as your destination city");
								$this->askForDate();
							}

							break;
					}
				}
			});
		});
	}

	public function __getDates()
	{
		$today		 = date('d/m/Y');
		$tomorrow	 = date("d/m/Y", strtotime("+1 day"));
		$plus3Days	 = date("d/m/Y", strtotime("+3 day"));
		$plus7Days	 = date("d/m/Y", strtotime("+7 day"));
		$plus15Days	 = date("d/m/Y", strtotime("+15 day"));

		return array
			(
			"15 days from now"	 => $plus15Days,
			"7 days from now"	 => $plus7Days,
			"3 days from now"	 => $plus3Days,
			"Tomorrow"			 => $tomorrow,
			"Today"				 => $today,
			"Other Date"		 => -5
		);
	}

	public function __getDatesRound()
	{
		$prevDate	 = $this->startDate;
		$today		 = date("d/m/Y", strtotime($prevDate));
		$tomorrow	 = date("d/m/Y", strtotime($prevDate . "+1 day"));
		$plus3Days	 = date("d/m/Y", strtotime($prevDate . " +3 day"));
		$plus7Days	 = date("d/m/Y", strtotime($prevDate . " +7 day"));
		$plus15Days	 = date("d/m/Y", strtotime($prevDate . " +15 day"));

		return array
			(
			"Same Day return"	 => $today,
			"Next Day return"	 => $tomorrow,
			"3 days Later"		 => $plus3Days,
			"7 days Later"		 => $plus7Days,
			"15 days Later"		 => $plus15Days,
			"Other Date"		 => -5
		);
	}

	public function askForCustomDate()
	{
		$question = Question::create('Please enter the date of the journey in <strong>dd/mm/yyyy</strong> fromat');
		$this->ask($question, function (Answer $answer) {
			$date			 = str_replace('/', '-', $answer->getValue());
			$this->startDate = date('Y-m-d', strtotime($date));
			if (!empty($this->startDate))
			{
				$this->askForTime();
			}
		});
	}

	public function askForDate()
	{
		$buttonArray = [];
		$dates		 = $this->__getDates();
		foreach ($dates as $arrKey => $arrVal)
		{
			$button			 = Button::create($arrKey)->value($arrVal);
			$buttonArray[]	 = $button;
		}

		$arrDateQuestion = Question::create("OK. Pick your date from some of the common choices below. You can choose other and just type in your exact date too")
				->callbackId('select_service')
				->addButtons($buttonArray);
		$this->ask($arrDateQuestion, function(Answer $dateAnswer) {
			$selValue = $dateAnswer->getValue();
			if ($selValue == -5)
			{
				$this->askForCustomDate();
			}
			else
			{
				$this->say("You have selected <strong>" . $dateAnswer->getText() . "</strong> as your journey date");
				$date			 = str_replace('/', '-', $dateAnswer->getValue());
				$this->startDate = date('Y-m-d', strtotime($date));
				if (!empty($this->startDate))
				{
					$this->askForTime();
				}
			}
		});
	}

	public function askForCustomTime()
	{
		$question = Question::create('Tell me the start time of your journey. Make sure start time is atleast 2hours or later from now. Type time in <strong>04:30 PM</strong> format....');
		$this->ask($question, function (Answer $answer) {

			$this->startTime = date("H:i:s", strtotime($answer->getValue()));
			if (!empty($this->startTime))
			{
				switch ($this->tripType)
				{
					case 3:
					case 6:
						$this->endConversation("Select how you want to get connected for the booking");
						break;

					case 2:
						$this->askForRoundTripDetails();
						break;

					default:
						$this->askForPassengerCount();
						break;
				}
			}
		});
	}

	public function askForTime()
	{
		$currentDate = date('Y-m-d');
		if ($this->startDate == $currentDate)
		{
			$this->say("As you are traveling today. Please add your prefered time");
			$this->askForCustomTime();
		}
		else
		{
			$buttonArray = [];
			foreach ($this->arrTime as $arrKey => $arrVal)
			{
				$button			 = Button::create($arrKey)->value($arrVal);
				$buttonArray[]	 = $button;
			}

			$arrTimeQuestion = Question::create("Great. Pick from the most common travel times below. You can also choose Custom time and type in your preferred time. ")
					->callbackId('select_service')
					->addButtons($buttonArray);
			$this->ask($arrTimeQuestion, function(Answer $timeAnswer) {
				$selValue = $timeAnswer->getValue();
				if ($selValue == -5)
				{
					$this->askForCustomTime();
				}
				else
				{
					$this->say("You have selected <strong>" . $timeAnswer->getText() . "</strong> as your journey time");
					$this->startTime = date("H:i:s", strtotime($timeAnswer->getValue()));
					if (!empty($this->startTime))
					{
						switch ($this->tripType)
						{
							case 3:
							case 6:
								$this->endConversation("Select how you want to get connected for the booking");
								break;

							case 2:
								$this->askForRoundTripDetails();
								break;

							default:
								$this->askForPassengerCount();
								break;
						}
					}
				}
			});
		}
	}

	public function askForRoundTripDetails()
	{
		$buttonArray = [];
		$dates		 = $this->__getDatesRound();
		foreach ($dates as $arrKey => $arrVal)
		{
			$button			 = Button::create($arrKey)->value($arrVal);
			$buttonArray[]	 = $button;
		}

		$question = Question::create('When will you return from ' . $this->selToCityName . '? Pick from common choices below. You can also choose another date... Please enter the date of the journey in <strong>dd/mm/yyyy</strong> fromat')
				->callbackId('select_service')
				->addButtons($buttonArray);
		$this->ask($question, function (Answer $answer) {
			if ($answer->getValue() == -5)
			{
				$preQuestion = Question::create('Ok! Please enter your prefered return date of the journey in dd/mm/yyyy fromat');
				$this->ask($preQuestion, function (Answer $preAnswer) {
					$this->say("You have selected <strong>Custom Date</strong>");
					$this->__handleRoundDate($preAnswer->getValue());
				});
			}
			else
			{
				$this->say("You have selected " . $answer->getValue());
				$this->__handleRoundDate($answer->getValue());
			}
		});
	}

	public function __handleRoundDate($rDate)
	{
		$date					 = str_replace('/', '-', $rDate);
		$this->startDateRound	 = date('Y-m-d', strtotime($date));
		if (!empty($this->startDateRound))
		{
			if ($this->startDate == $this->startDateRound)
			{
				$question = Question::create('As you are returning on the same day.  from ' . $this->selToCityName . '? .. Please enter the start time of the journey like <strong>04:30 PM</strong> fromat');
				$this->ask($question, function (Answer $answer) {
					$this->startTimeRound = date("H:i:s", strtotime($answer->getValue()));
					if (!empty($this->startTimeRound))
					{
						$this->askForPassengerCount();
					}
				});
			}
			else
			{
				$buttonArray = [];
				foreach ($this->arrTime as $arrKey => $arrVal)
				{
					$button			 = Button::create($arrKey)->value($arrVal);
					$buttonArray[]	 = $button;
				}

				$arrTimeQuestion = Question::create("Great. Pick from the most common travel times below. You can also choose Custom time and type in your preferred time. ")
						->callbackId('select_service')
						->addButtons($buttonArray);
				$this->ask($arrTimeQuestion, function (Answer $answer) {
					if ($answer->getValue() == -5)
					{
						$this->say("You have selected <strong>Custom Time</strong>");
						$question = Question::create('When do you prefer to start from ' . $this->selToCityName . '? .. Please enter the start time of the journey like <strong>04:30 PM</strong> fromat');
						$this->ask($question, function (Answer $answer) {
							$this->startTimeRound = date("H:i:s", strtotime($answer->getValue()));
							if (!empty($this->startTimeRound))
							{
								$this->askForPassengerCount();
							}
						});
					}
					else
					{
						$this->say("You have selected " . $answer->getValue());
						$this->startTimeRound = date("H:i:s", strtotime($answer->getValue()));
						if (!empty($this->startTimeRound))
						{
							$this->askForPassengerCount();
						}
					}
				});
			}
		}
	}

	public function askForPassengerCount()
	{
		$question = Question::create('How many people will be traveling in the car? Do not count the driver...');
		$this->ask($question, function (Answer $answer) {
			$this->personCount = $answer->getValue();
			if (!empty($this->personCount))
			{
				if ($this->personCount > 6)
				{
					$this->endConversation("Looks like you need a tempo traveller");
				}
				else
				{
					$this->getQuote();
				}
			}
		});
	}

	public function getQuote()
	{
		$jsonData	 = $this->__mapGetQuoteJson();
		$string		 = json_encode($jsonData);
		$quoteData	 = BookingTemp::getQuoteForBot($jsonData);
		if (!$quoteData->getStatus())
		{
			$errors = $quoteData->getErrors();
			if (empty($errors))
			{
				$this->say("Oops!.. There was a problem in generating you quote");
				$this->endConversation("Get connected with us!");
				goto skipAll;
			}
			else
			{
				foreach ($errors as $key => $value)
				{
					$this->say($value);
				}
				$this->BookingType();
				goto skipAll;
			}
		}

		$data				 = $quoteData->getData();
		$this->quotedData	 = $data;
		$estimatedDuration	 = $data->estimatedDuration;
		$quotedDistance		 = $data->quotedDistance;
		$cabRate			 = $data->cabRate;
		$this->cabRate		 = $cabRate;

		$lastCategory	 = "";
		$arrCategories	 = [];
		foreach ($cabRate as $val)
		{
			if ($lastCategory == $val->cab->category)
			{
				continue;
			}

			if ((int) $this->personCount <= 4 && $val->cab->seatingCapacity <= 7)
			{
				array_push($arrCategories, $val->cab->category);
			}
			else if ((int) $this->personCount > 4 && ($val->cab->seatingCapacity > 4 && $val->cab->seatingCapacity <= 7 ))
			{
				array_push($arrCategories, $val->cab->category);
			}
			$lastCategory = $val->cab->category;
		}

		$uniqueCategories	 = array_filter(array_unique($arrCategories));
		$buttonArray		 = [];
		foreach ($uniqueCategories as $key => $val)
		{
			$button			 = Button::create($val)->value($val);
			$buttonArray[]	 = $button;
		}

		array_push($buttonArray, Button::create("Go Back")->value(-6));

		$question = Question::create('Quoted Distance = ' . $quotedDistance . "KM | Estimated Duration = " . intdiv($estimatedDuration, 60) . 'hrs ' . ($estimatedDuration % 60) . "mins. Please select the cab category type below so I can give you the fare for your journey")
				->callbackId('select_cab')
				->addButtons($buttonArray);
		$this->ask($question, function(Answer $answer) {
			$this->cabType = $answer->getValue();
			if ($this->cabType == -6)
			{
				$this->BookingType();
			}
			else
			{
				$this->selectServiceClass();
			}
		});

		skipAll:
	}

	public function selectServiceClass()
	{
		$cabRate	 = $this->cabRate;
		$buttonArray = [];
		foreach ($cabRate as $val)
		{
			if ($val->cab->category == $this->cabType)
			{
				if (!in_array($val->cab->id, [27, 28, 29]))
				{
					$btnValue		 = $val->cab->id . "|" . $val->discountedFare->totalAmount;
					$button			 = Button::create($val->cab->sClass . "(Rs " . $val->discountedFare->totalAmount . ")")->value($btnValue);
					$buttonArray[]	 = $button;
				}
			}
		}

		array_push($buttonArray, Button::create("Go Back")->value(-6));

		$question = Question::create('Pick a class and I will show the fare breakup')
				->callbackId('select_class_cab')
				->addButtons($buttonArray);
		$this->ask($question, function(Answer $answer) {
			$selValue			 = explode("|", $answer->getValue());
			$this->selCabId		 = $selValue[0];
			$this->totalAmount	 = $selValue[1];
			if ($this->selCabId == -6)
			{
				$this->BookingType();
			}
			else
			{
				$this->showBreakUp();
			}
		});
	}

	/**
	 * This function is used for displaying the price break up
	 */
	public function showBreakUp()
	{
		$cabRate	 = $this->cabRate;
		$buttonArray = [];
		$appentHtml	 = "";
		foreach ($cabRate as $val)
		{
			if ($val->cab->id == $this->selCabId)
			{
				$appentHtml	 .= "<strong>BaseFare:</strong> Rs " . $val->discountedFare->baseFare . "<br />";
				$appentHtml	 .= "<strong>Discount:</strong> Rs " . $val->discountedFare->discount . "<br />";
				$appentHtml	 .= "<strong>Extra Per KM:</strong> Rs " . $val->discountedFare->extraPerKmRate . "<br />";
				$appentHtml	 .= "<strong>Driver Allowance:</strong> Rs " . $val->fare->driverAllowance ?? 0 . "<br />";
				$appentHtml	 .= "<br/><strong>GST:</strong> Rs " . $val->discountedFare->gst . "<br />";
				$appentHtml	 .= "<strong>Total Amount:</strong> Rs " . $val->discountedFare->totalAmount . "<br />";
			}
		}

		$this->say($appentHtml);
		$question = Question::create('So, how you want me to procced')
				->callbackId('select_new_booking_service')
				->addButtons([
			//Button::create('Send me the Quote')->value(1),
			Button::create('Book Now')->value(2),
			//Button::create('Get an agent')->value(3),
			Button::create('Go Back')->value(4),
			Button::create('Start Over')->value(-6),
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				switch ($answer->getValue())
				{
					case 1:
						$this->createBooking();
						//$this->endConversation();
						break;
					case 2:
						$this->createBooking(1);
						//$this->endConversation();
						break;
					case 3:
						$this->endConversation("Select your preference of agent");
						break;
					case 4:
						$this->selectServiceClass();
						break;
					case -6:
						$this->bot->startConversation(new OldUserSelectServiceConversation());
						break;
				}
			}
		});
	}

	/**
	 * This function is used for creating bookings
	 */
	public function createBooking($show = 0)
	{
		$jsonObj	 = $this->__mapCreateBooking();
		$string		 = json_encode($jsonObj); // For debug -  to see json gets generated in proper format
		$response	 = Booking::createByBot($jsonObj);
		if (!$response->getStatus())
		{
			$this->say("Seems like there is an issue with the booking.");
			$this->endConversation("Please connect with our agent for the confirmation");
			goto skipAll;
		}
		$data			 = $response->getData();
		$this->bookFlag	 = 1;
		if ($show)
		{
			$this->say("OK! Your trip has been booked successfully. Your booking ID is " . $data->bookingId . " I've also sent you an email with the quotation for this booking. That email includes all the terms and conditions for the booking and tells you about what charges are included in the service and what may need to be paid for seperately.
            I've locked this price for a limited time, so you should confirm the booking by simply paying for it before prices go up again.");
			$linkResponse = Booking::getLink($data->bookingId);
			$this->say("Please <a href='" . $linkResponse->getData() . "' target='blank'>TAP HERE to view details & pay</a> for your booking.");
		}
		else
		{
			$this->say("Great!!. Your Quotation ID is " . $data->bookingId . " I've emailed you the quote. The email contains important details like what is vs not included in your quote. We will lock this price for you for sometime. If you pay while the price is still locked, the quotation will be converted into a confirmed booking.");
			$linkResponse = Booking::getLink($data->bookingId);
		}

		$this->endConversation();
		skipAll:
	}

	/**
	 * This function is used for getting the quote for the data
	 * @return \stdClass
	 */
	public function __mapGetQuoteJson()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);


		$objRoute	 = new stdClass();
		$jsonData	 = new stdClass();

		$arrRoute = [];
		switch ($this->tripType)
		{
			case 1:

				$objRoute->destination->address					 = $this->selToCityName;
				$objRoute->destination->code					 = (int) $this->toCity;
				$objRoute->destination->coordinates->latitude	 = floatval($this->selToCityNameLat);
				$objRoute->destination->coordinates->longitude	 = floatval($this->selToCityNameLong);
				$objRoute->source->address						 = $this->selFromCityName;
				$objRoute->source->code							 = (int) $this->fromCity;
				$objRoute->source->coordinates->latitude		 = floatval($this->selFromCityNameLat);
				$objRoute->source->coordinates->longitude		 = floatval($this->selFromCityNameLong);
				$objRoute->startDate							 = $this->startDate;
				$objRoute->startTime							 = $this->startTime;

				array_push($arrRoute, $objRoute);
				$jsonData->Itinerary->cabType = [];
				break;

			case 2:
				$objRoute->destination->address					 = $this->selToCityName;
				$objRoute->destination->code					 = (int) $this->toCity;
				$objRoute->destination->coordinates->latitude	 = floatval($this->selToCityNameLat);
				$objRoute->destination->coordinates->longitude	 = floatval($this->selToCityNameLong);
				$objRoute->source->address						 = $this->selFromCityName;
				$objRoute->source->code							 = (int) $this->fromCity;
				$objRoute->source->coordinates->latitude		 = floatval($this->selFromCityNameLat);
				$objRoute->source->coordinates->longitude		 = floatval($this->selFromCityNameLong);
				$objRoute->startDate							 = $this->startDate;
				$objRoute->startTime							 = $this->startTime;

				array_push($arrRoute, $objRoute);
				$objRoute										 = new stdClass();
				$objRoute->destination->address					 = $this->selFromCityName;
				$objRoute->destination->code					 = (int) $this->fromCity;
				$objRoute->destination->coordinates->latitude	 = floatval($this->selFromCityNameLat);
				$objRoute->destination->coordinates->longitude	 = floatval($this->selFromCityNameLong);
				$objRoute->source->address						 = $this->selToCityName;
				$objRoute->source->code							 = (int) $this->toCity;
				$objRoute->source->coordinates->latitude		 = floatval($this->selToCityNameLat);
				$objRoute->source->coordinates->longitude		 = floatval($this->selToCityNameLong);
				$objRoute->startDate							 = $this->startDateRound;
				$objRoute->startTime							 = $this->startTimeRound;
				array_push($arrRoute, $objRoute);

				$jsonData->Itinerary->cabType = [];

				break;
			case 9:
			case 10:
			case 11:
				$objRoute->destination->address					 = $this->selFromCityName;
				$objRoute->destination->code					 = (int) $this->fromCity;
				$objRoute->destination->coordinates->latitude	 = floatval($this->selFromCityNameLat);
				$objRoute->destination->coordinates->longitude	 = floatval($this->selFromCityNameLong);
				$objRoute->source->address						 = $this->selFromCityName;
				$objRoute->source->code							 = (int) $this->fromCity;
				$objRoute->source->coordinates->latitude		 = floatval($this->selFromCityNameLat);
				$objRoute->source->coordinates->longitude		 = floatval($this->selFromCityNameLong);
				$objRoute->startDate							 = $this->startDate;
				$objRoute->startTime							 = $this->startTime;

				array_push($arrRoute, $objRoute);
				$jsonData->Itinerary->cabType = [1, 2, 3];
				break;

			default:
				break;
		}

		$this->routeData = $arrRoute;

		$jsonData->userInfo->email					 = $userModel->usr_email ? $userModel->usr_email : '';
		$jsonData->userInfo->primaryContact->code	 = $userModel->usr_country_code;
		$jsonData->userInfo->primaryContact->number	 = $userModel->usr_mobile ? $userModel->usr_mobile : '';

		$jsonData->Itinerary->routes	 = $arrRoute; //[$objRoute];
		$jsonData->Itinerary->tripType	 = $this->tripType;

		return $jsonData;
	}

	/**
	 * This function is used for creating the booking
	 * @return \stdClass
	 */
	public function __mapCreateBooking()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);

		$jsonData					 = new stdClass();
		$jsonData->tnc				 = "";
		$jsonData->referenceId		 = "";
		$jsonData->sendEmail		 = 1;
		$jsonData->sendSms			 = 1;
		$jsonData->tripType			 = $this->tripType;
		$jsonData->additionalInfo	 = new stdClass();

		$objRoute	 = new stdClass();
		$arrRoute	 = [];
		switch ($this->tripType)
		{
			case 1:

				$objRoute->destination->address					 = "";
				$objRoute->destination->code					 = (int) $this->toCity;
				$objRoute->destination->coordinates->latitude	 = "";
				$objRoute->destination->coordinates->longitude	 = "";
				$objRoute->source->address						 = "";
				$objRoute->source->code							 = (int) $this->fromCity;
				$objRoute->source->coordinates->latitude		 = "";
				$objRoute->source->coordinates->longitude		 = "";
				$objRoute->startDate							 = $this->startDate;
				$objRoute->startTime							 = $this->startTime;

				array_push($arrRoute, $objRoute);
				$jsonData->Itinerary->cabType = [];
				break;

			case 2:
				$objRoute->destination->address					 = "";
				$objRoute->destination->code					 = (int) $this->toCity;
				$objRoute->destination->coordinates->latitude	 = "";
				$objRoute->destination->coordinates->longitude	 = "";
				$objRoute->source->address						 = "";
				$objRoute->source->code							 = (int) $this->fromCity;
				$objRoute->source->coordinates->latitude		 = "";
				$objRoute->source->coordinates->longitude		 = "";
				$objRoute->startDate							 = $this->startDate;
				$objRoute->startTime							 = $this->startTime;

				array_push($arrRoute, $objRoute);
				$objRoute										 = new stdClass();
				$objRoute->destination->address					 = "";
				$objRoute->destination->code					 = (int) $this->fromCity;
				$objRoute->destination->coordinates->latitude	 = "";
				$objRoute->destination->coordinates->longitude	 = "";
				$objRoute->source->address						 = "";
				$objRoute->source->code							 = (int) $this->toCity;
				$objRoute->source->coordinates->latitude		 = "";
				$objRoute->source->coordinates->longitude		 = "";
				$objRoute->startDate							 = $this->startDateRound;
				$objRoute->startTime							 = $this->startTimeRound;
				array_push($arrRoute, $objRoute);

				$jsonData->Itinerary->cabType = [];

				break;
			case 9:
			case 10:
			case 11:
				$objRoute->destination->address					 = "";
				$objRoute->destination->code					 = (int) $this->fromCity;
				$objRoute->destination->coordinates->latitude	 = "";
				$objRoute->destination->coordinates->longitude	 = "";
				$objRoute->source->address						 = "";
				$objRoute->source->code							 = (int) $this->fromCity;
				$objRoute->source->coordinates->latitude		 = "";
				$objRoute->source->coordinates->longitude		 = "";
				$objRoute->startDate							 = $this->startDate;
				$objRoute->startTime							 = $this->startTime;

				array_push($arrRoute, $objRoute);
				$jsonData->Itinerary->cabType = [1, 2, 3];
				break;

			default:
				break;
		}

		//$jsonData->routes							 = $this->routeData;
		$jsonData->routes							 = $arrRoute;
		$jsonData->cabType							 = $this->selCabId;
		$jsonData->fare->advanceReceived			 = 0;
		$jsonData->fare->totalAmount				 = $this->totalAmount;
		$jsonData->platform->deviceName				 = $_SERVER['HTTP_USER_AGENT'];
		$jsonData->platform->ip						 = $_SERVER['REMOTE_ADDR'];
		$jsonData->apkVersion						 = "bot";
		$jsonData->traveller->firstName				 = $userModel->usr_name;
		$jsonData->traveller->lastName				 = $userModel->usr_lname;
		$jsonData->traveller->email					 = $userModel->usr_email ? $userModel->usr_email : '';
		$jsonData->traveller->primaryContact->code	 = $userModel->usr_country_code;
		$jsonData->traveller->primaryContact->number = $userModel->usr_mobile ? $userModel->usr_mobile : '';
		return $jsonData;
	}

	public function BookingType()
	{
		$question = Question::create('Please select your trip type')
				->callbackId('select_new_booking_service')
				->addButtons([
			Button::create('One Way')->value(1),
			Button::create('Round Trip')->value(2),
			Button::create('Airport Transfer')->value(3),
			Button::create('Day Rental')->value(5),
			Button::create('Other')->value(6),
			Button::create('Go Back')->value(-4),
		]);

		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$this->tripType = $answer->getValue();
				switch ($this->tripType)
				{
					case 1:
						$this->say("OK. I'm going to help you with a One way journey...");
						$this->askBookingService();
						break;
					case 2:

						$this->say("OK. I'm going to help you with a Round Trip journey...");
						$this->askForRoundTripPreference();
						break;
					case 3:
						$this->say("You have selected <strong>Airport Transfer</strong> as your trip type");
						$this->askForAirPort();
						break;
					case 5:
						$this->say("OK, Lets find you a Day Rental...");
						$this->askForRental();
						break;
					case 6:
						$this->endConversation("Ok!.How would like us to get connected");
						break;
					default:
						$this->askForService();
						break;
				}
			}
		});
	}

	public function askForRoundTripPreference()
	{
		$endQuestion = Question::create("Ok!.. So, which type of round trip journey you want?")
				->callbackId('select_end')
				->addButtons([
			Button::create('Round Trip - City A to B, B to A')->value(1),
			Button::create('MultiCity - City A to B to C')->value(2),
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
					case 1:
						$this->say("Ok!.Let me help you with a round trip");
						$this->askBookingService();
						break;
					case 2:
						$this->say("Ok!.I have a scheduled a call back as you need a multicity booking");
						$this->multiCity = 1;
						$this->endConversation();
						break;
				}
			}
		});
	}

	public function getRentalCities()
	{
		$question = Question::create('Please enter your city name for day rental');
		$this->ask($question, function(Answer $rAnswer) {
			$cityName					 = $rAnswer->getValue();
			$this->dayRentalException	 = $cityName;
			$drCity						 = json_decode(Cities::model()->getJSONSourceCitiesDR($cityName));
			if (empty($drCity))
			{
				$this->say("Seems like our service is currently ont available in your city");
				$this->endConversation("How would you like to get in touch with us");
			}

			$buttonArray = [];
			foreach ($drCity as $val)
			{
				$button			 = Button::create($val->text)->value($val->id);
				$buttonArray[]	 = $button;
			}

			array_push($buttonArray, Button::create("Go Back")->value(-6));
			$cQuestion = Question::create("I found a few places by that name, pick the closet one")
					->callbackId('select_service')
					->addButtons($buttonArray);
			$this->ask($cQuestion, function(Answer $rcAnswer) {
				if ($rcAnswer->isInteractiveMessageReply())
				{
					switch ($rcAnswer->getValue())
					{
						case -6:
							$this->BookingType();
							break;
						default:
							$this->fromCity			 = $rcAnswer->getValue();
							$this->selFromCityName	 = Cities::getColumnValue("cty_display_name", $this->fromCity);
							$this->say("You have selected <strong>" . $this->selFromCityName . "</strong> for your day rental");
							$this->askForDate();
							break;
					}
				}
			});
		});
	}

//	public function getRentalCities()
//	{
//		$question = Question::create('Please enter your city name for day rental?');
//		$this->ask($question, function(Answer $answer) {
//			$cityName = $answer->getValue();
//			$this->dayRentalException = $cityName;
//			$drCity = json_decode(Cities::model()->getJSONSourceCitiesDR($cityName));
//			$buttonArray = [];
//
//			if(empty($drCity))
//			{
//				$this->say("Seems like our service is currently ont available in your city");
//				$this->endConversation("How would you like to get in touch with us");
//				goto skipAll;
//			}
//
//			foreach ($drCity as $val)
//			{
//				$button = Button::create($val["cty_name"])->value($val["cty_id"]);
//				$buttonArray[] = $button;
//			}
//
//			array_push($buttonArray, Button::create("Go Back")->value(-6));
//
//			$question = Question::create('Please select the exact city name from below')
//			->callbackId('select_service')
//			->addButtons($buttonArray);
//
//			$this->ask($question, function (Answer $answer) 
//			{
//				if ($answer->isInteractiveMessageReply()) 
//				{
//					switch ($answer->getValue())
//					{
//						case -6:
//							$this->BookingType();
//							break;
//						default:
//							$this->fromCity = $answer->getValue();
//							$this->selFromCityName = Cities::getColumnValue("cty_display_name", $this->fromCity);
//							$this->say("You have selected <strong>" . $this->selFromCityName . "</strong> for your day rental");
//							$this->askForDestination();
//							break;
//					}
//				}
//			});
//		});
//		skipAll:
//	}

	public function askForRental()
	{
		$buttonArray = [];
		$rDetail	 = Booking::model()->rental_types;
		foreach ($rDetail as $key => $val)
		{
			$button			 = Button::create($val)->value($key);
			$buttonArray[]	 = $button;
		}

		$question = Question::create('Please select your travel travel time in the city')
				->callbackId('select_rental_service')
				->addButtons($buttonArray);

		$this->ask($question, function (Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$this->tripType = $answer->getValue();
				$this->getRentalCities();
			}
		});
	}

	public function askForAirPort()
	{
		$question = Question::create('Tell me the airport name');
		$this->ask($question, function (Answer $answer) {

			$cityName	 = explode(' ', $answer->getValue(), 2)[0];
			$drCity		 = Cities::model()->getJSONAirportCitiesAll($cityName, 1);
			if (empty($drCity))
			{
				$this->say("Seems like we dont know any airport with this name. can you give me a few more hints");
				$this->askForAirPort();
			}
			else
			{
				$buttonArray = [];
				foreach ($drCity as $val)
				{
					$button			 = Button::create($val["text"])->value($val["id"]);
					$buttonArray[]	 = $button;
				}

				array_push($buttonArray, Button::create("Go Back")->value(-6));
				$cQuestion = Question::create("I found a few airports by that name, pick the closet one")
						->callbackId('select_service')
						->addButtons($buttonArray);
				$this->ask($cQuestion, function(Answer $rcAnswer) {
					if ($rcAnswer->isInteractiveMessageReply())
					{
						switch ($rcAnswer->getValue())
						{
							case -6:
								$this->BookingType();
								break;
							default:
								$this->airportName = $rcAnswer->getValue();
								$this->askForDate();
								break;
						}
					}
				});
			}
		});
	}

	public function callBackModel()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$name		 = $userModel->usr_name . " " . $userModel->usr_lname;

		$refDesc = "";
		$refDesc .= "Hi I am Sonia-TheBot, I have been requested by " . $name . " for a booking";
		$refDesc .= ", Source City = " . $this->selFromCityName;
		$refDesc .= ", Destination City = " . $this->selFromCityName;
		$refDesc .= ", expected journey = " . $this->startTime;

		$data			 = new stdClass();
		$data->refTypeId = Callback::NEW_BOOKING_CALLBACK;
		$data->refDesc	 = $this->refDesc;

		$response			 = Callback::processData($data);
		$this->say($response->getMessage());
		$this->isLoggedIn	 = ($userId) ? 1 : 0;
		$this->endConversation();
	}

	public function endConversation($question = null)
	{
		if ($question == null)
		{
			$question = 'OK. Now thats taken care of. What else can I help you with....';
		}

		if ($this->airportName !== "")
		{
			$refDesc .= ", Airport transfer = " . $this->airportName;
			$refDesc .= ", Booking Type = Airport Transfer";
			$refDesc .= ", expected journey time = " . $this->startTime;
			$refDesc .= ", expected journey date = " . $this->startDate;
			$this->askForCallMeBack($refDesc);
			goto skipAll;
		}

		if ($this->multiCity)
		{
			$refDesc .= ", Wants a multicity trip";
			$refDesc .= ", Booking Type = Multi City";
			$this->askForCallMeBack($refDesc);
			goto skipAll;
		}



		if ($this->bookFlag)
		{
			$question = Question::create($question)
					->callbackId('select_end')
					->addButtons([
				Button::create('Start Over')->value(-4)
			]);
		}
		else
		{
			$question = Question::create($question)
					->callbackId('select_end')
					->addButtons([
				Button::create('Schedule a Call Back')->value(1),
				Button::create('Start Live Chat')->value(2),
				//Button::create("Check Other Services")->value(3),
				Button::create('Start Over')->value(-4)
			]);
		}



		$this->ask($question, function(Answer $answer) {
			if ($answer->isInteractiveMessageReply())
			{
				$userInput = $answer->getValue();
				switch ($userInput)
				{
					case -4:
						$this->bot->startConversation(new OldUserSelectServiceConversation());
						break;
					case 1:
						$userId		 = UserInfo::getUserId();
						$userModel	 = Users::model()->findByPk($userId);
						$name		 = $userModel->usr_name . " " . $userModel->usr_lname;
						$refDesc	 = "";
						$refDesc	 .= "Hi I am Sonia-TheBot, I have been requested by " . $name . " for a booking";

						switch ($this->tripType)
						{
							case 1:
								$refDesc .= ", Booking Type = One way";
								$refDesc .= ", Source City = " . $this->selFromCityName;
								$refDesc .= ", Destination City = " . $this->selFromCityName;
								$refDesc .= ", expected journey time = " . $this->startTime;
								$refDesc .= ", expected journey date = " . $this->startDate;
								break;



							case 9:
							case 10:
							case 11:

								if (!empty($this->dayRentalException))
								{
									$refDesc .= ", Source City = " . $this->dayRentalException;
									$refDesc .= ", Booking Type = Day Rental";
									$refDesc .= ", This city is out of our service list. But he wants a service";
									$refDesc .= ", expected journey date = " . $this->startDate;
								}
								else
								{
									$refDesc .= ", Source City = " . $this->selFromCityName;
									$refDesc .= ", Booking Type = Day Rental";
									$refDesc .= ", expected journey time = " . $this->startTime;
									$refDesc .= ", expected journey date = " . $this->startDate;
								}

								break;
							default:
								break;
						}

						$this->askForCallMeBack($refDesc);
						$this->bot->startConversation(new OldUserSelectServiceConversation());
						break;
					case 2:
						$this->askForChatLink();
						break;
					case 3:
						$this->bot->startConversation(new OldUserSelectServiceConversation());
						break;
				}
			}
		});

		skipAll:
	}

	public function askForChatLink()
	{
		$userId		 = UserInfo::getUserId();
		$userModel	 = Users::model()->findByPk($userId);
		$link		 = Rooms::processData($userModel->usr_contact_id, UserInfo::TYPE_CONSUMER);
		$appendData	 = "Opps!!. There is some issue";
		if (!empty($link))
		{
			$appendData = "Please <a href='" . $link . "' target='blank'>Click here</a> to start a conversation with our executive";
		}
		$this->say($appendData);
	}

	public function askForCallMeBack($desc)
	{
		$this->bot->startConversation(new CommonSelectServiceConversation("", UserInfo::TYPE_CONSUMER, $desc));
	}

}
