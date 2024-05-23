<?php

class NewsletterCommand extends BaseCommand
{

	public function actionBookGozoAgain()
	{
		/* @var $modelsub BookingSub */
		Logger::create("command.newsletter.bookGozoAgain start", CLogger::LEVEL_PROFILE);
		$modelsub	 = new BookingSub();
		$gozoMails	 = $modelsub->bookGozoAgain(1);
		$emailCom	 = new emailWrapper();
		if (count($gozoMails) > 0)
		{
			foreach ($gozoMails as $gozo)
			{
				$emailCom->remainderBookCab($gozo['user_name'], $gozo['user_email'], $gozo['user_id']);
			}
		}
		Logger::create("command.newsletter.bookGozoAgain end", CLogger::LEVEL_PROFILE);
	}

}
