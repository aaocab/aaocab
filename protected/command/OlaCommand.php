<?php

class OlaCommand extends BaseCommand
{

	public function actionUpdateRateByOla()
	{
		$check = Filter::checkProcess("ola updateRateByOla");
		if (!$check)
		{
			return;
		}
		echo ":: Ola-updateRateByOla Start";
		Logger::create("command.ola.updateRateByOla start", CLogger::LEVEL_PROFILE);
		$command = 1;

		Logger::create("command.ola.updateRateByOla start " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);
		$msg = OlaBookingUpdate::model()->executeUploaded($command);
		Logger::create("command.ola.updateRateByOla end " . Filter::getExecutionTime(), CLogger::LEVEL_PROFILE);


		//echo $msg;
		Logger::create("command.ola.updateRateByOla end", CLogger::LEVEL_PROFILE);
		echo "\n:: Ola-updateRateByOla End";
		exit;
	}

}
