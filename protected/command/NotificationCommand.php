<?php

class NotificationCommand extends BaseCommand
{

	public function actionUpdateJobDataDetails()
	{
		$check = Filter::checkProcess("updateJobDataDetails");
		if (!$check)
		{
			return;
		}
		$data = BroadcastNotification::updateJobData();
	}

	public function actionSendBroadcastNotification()
	{
		$check = Filter::checkProcess("sendBroadcastNotification");
		if (!$check)
		{
			return;
		}
		$data = BroadcastNotificationDetails::runBroadcastNotification();
	}

	public function actionSendPendingWhatsapp($whlId = '')
	{
		$check = Filter::checkProcess("sendPendingWhatsapp");
		if (!$check)
		{
			return;
		}
		WhatsappLog::sendPendingMessages($whlId);
	}

}
