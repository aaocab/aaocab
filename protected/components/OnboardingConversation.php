<?php

/**
 * Description of OnboardingConversation
 * - This Class is used by the bot for starting the conversion with the user
 * 
 * @author Suvajit
 * @since 12-04-2020
 */
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class OnboardingConversation extends Conversation
{
	protected $contactId = 0;
	protected $isExisting	 = 0; 
	protected  $isLoggedIn	 = 0;

	public function run()
	{
		$this->askForExistence();
	}

	public function askForExistence()
	{
		$userId = UserInfo::getUserId();
		($userId == 0 || $userId == null) ? $this->verifyUser() : $this->existingUser();
	}

	public function verifyUser()
	{
		$this->say("Oops! You need to login so I can access your Gozo Profile. It takes 30seconds, just sign in using Google or Facebook here (https://gozocabs.com/signin). I look forward to helping you.");
	}

	/**
	 * Calls for Existing User
	 */
	public function existingUser()
	{
		$userId = UserInfo::getUserId();
		($userId) ? $this->askService(1, $userId) : $this->say("Oops! You need to login so I can access your Gozo Profile. It takes 30seconds, just sign in using Google or Facebook here (https://gozocabs.com/signin). I look forward to helping you.");
	}

	/**
	 * This function switches the bot conversation mode
	 * @param type $userState
	 */
	public function askService($userState, $userId = null)
	{
		switch ($userState)
		{
			case 0:
				$this->bot->startConversation(new NewUserSelectServiceConversation());
				break;

			case 1:
				$this->bot->startConversation(new OldUserSelectServiceConversation());
				break;
		}
	}
}
