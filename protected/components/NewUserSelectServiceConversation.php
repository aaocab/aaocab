<?php

/**
 * Description of SelectServiceConversation
 *
 * @author Suvajit
 */
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class NewUserSelectServiceConversation extends Conversation
{
	public function askService()
    {
        $question = Question::create('What kind of Service you are looking for?')
            ->callbackId('select_service')
            ->addButtons([
                //Button::create('New Booking')->value(1),
                Button::create('Call Me back')->value(2),
				Button::create('Join As Vendor')->value(3),
				Button::create('Join As Agent')->value(4),
				Button::create('Start Chat')->value(5),
            ]);

        $this->ask($question, function(Answer $answer) 
		{
            if ($answer->isInteractiveMessageReply()) 
			{
				$userSelection = $answer->getValue();
                switch ($userSelection)
				{
					case 1:
						//New Booking
						$this->say("You have requested for <strong>New Booking</strong> service");
						$this->askForNewBooking();
						break;

					case 2:
						//Call me back
						$this->say("You have requested for <strong>Call Back</strong> service");
						$this->askForCallMeBack();
						break;
					case 3:
						//Call me back
						$this->say("You have requested for <strong>Join As Vendor</strong> service");
						$this->askForVendorJoin();
						break;
					
					case 4:
						//Call me back
						$this->say("You have requested for <strong>Join As Agent</strong> service");
						$this->askForAgentJoin();
						break;
					case 5:
						$this->say("Please wait for while i create a chat link for you");
						$this->askForChatLink();
						break;
				}
            }
        });
    }

	public function askForChatLink()
	{
		$userId = UserInfo::getUserId();
		$userModel = Users::model()->findByPk($userId);
		$link = Rooms::processData($userModel->usr_contact_id, $this->profile);
		$appendData = "Opps!!. There is some issue";
		if(!empty($link))
		{
			$appendData = "Please <a href='". $link . "' target='blank'>Click here</a> to start a conversation with our executive";
		}
		$this->say($appendData);
	}

	public function askForVendorJoin()
	{
		$appendhtml = "";
		$appendhtml .= "Please <a href='https://www.gozocabs.com/vendor/join' target='blank' >click here</a> to proceed";

		$this->say($appendhtml);
	}

	public function askForAgentJoin()
	{
		$appendhtml = "";
		$appendhtml .= "Please <a href='https://www.gozocabs.com/agent/join' target='blank' >click here</a> to proceed";

		$this->say($appendhtml);
	}

	public function askForCallMeBack()
	{
		$userId = UserInfo::getUserId();
		if(empty($userId))
		{
			$this->say("Oops! I couldn't identify you.Please signin in our system. And write 'DONE' here");
		}
		else
		{
			$this->bot->startConversation(new CommonSelectServiceConversation());
		}
	}

	public function askForNewBooking()
	{
		$userId = UserInfo::getUserId();
		if(empty($userId))
		{
			$this->say("Oops! I couldn't identify you.Please signin in our system. And write 'DONE' here");
		}
		else
		{
			$this->bot->startConversation(new NewBookingServiceConversation());
		}
	}

	public function run()
    {
        $this->askService();
    }
}