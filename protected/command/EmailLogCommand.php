<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailLogCommand extends BaseCommand
{

	private $email_receipient;

	/**
	 * @deprecated 
	 */
	public function actionInactiveMails()
	{
		$emailLog = new EmailLog();
		$emailLog->sentInactiveMails();
	}

	public function actionFileUploadEmailLog()
	{
		$range_sql	 = "SELECT COUNT(*) FROM `email_log` where `elg_bkg_status` = 1";
		$count		 = DButil::queryScalar($range_sql);
		$limit		 = 100;
		$rCount		 = $count / $limit;
		for ($i = 0; $i < $rCount; $i++)
		{
			if ($i == 0)
			{
				$offset = 0;
			}
			else
			{
				$offset = $limit * $i;
			}

			$sql = "SELECT elg_ref_type, id, elg_type, body, created FROM `email_log` LIMIT $offset, $limit";
			$ids = DBUtil::queryAll($sql);
			foreach ($ids as $data)
			{
				$body		 = $data[body];
				$refType	 = $data[elg_ref_type];
				$uniqueId	 = $data[id];
				$emailType	 = $data[elg_type];

				if ($body != "")
				{
					echo "<br>";
					echo $data[id];
					echo "<br>";
					$date					 = $data['created'];
					$path					 = Yii::app()->basePath;
					$fileName				 = $refType . '_' . $uniqueId . '_' . $emailType . '.gml';
					$mainfoldername			 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'emailText';
					$subFolderDay			 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($date));
					$logPath				 = Filter::WriteFile($subFolderDay, $fileName, $body);
					$dbPath					 = explode("docs", $logPath);
					$model					 = EmailLog::model()->resetScope()->findByPk($uniqueId);
					$model->body			 = trim(strip_tags($body));
					$model->elg_file_path	 = $dbPath[1];
					$model->elg_bkg_status	 = -1;
					$model->update();
				}
			}
			echo "+++++++++++++++++++++++++++";
		}
	}

	public function actionemailLogdataMove()
	{
		echo "==========Start===========";
		while (true)
		{

			$range_sql	 = "SELECT * FROM `email_log2` WHERE status>=0 ORDER BY id DESC LIMIT 0, 50";
			$rows		 = Yii::app()->db->createCommand($range_sql)->queryAll();
			if (count($rows) == 0)
			{
				print_r($rows);
				break;
			}
			foreach ($rows as $row)
			{
				try
				{
					$body		 = $row['body'];
					$refType	 = $row['elg_ref_type'];
					$uniqueId	 = $row['id'];
					$emailType	 = $row['elg_type'];
					$date		 = $row['created'];
					$path		 = Yii::app()->basePath;

					$fileName		 = $refType . '_' . $uniqueId . '_' . $emailType . '.gml';
					$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'mails';
					$subFolderDay	 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($date));
					if (file_exists($subFolderDay . DIRECTORY_SEPARATOR . $fileName))
					{
						echo $sql = "UPDATE `email_log2` SET status=-2 WHERE id={$row['id']}
						";
						Yii::app()->db->createCommand($sql)->execute();
						continue;
					}

					$logPath = Filter::WriteFile($subFolderDay, $fileName, $body, false);

					$dbPath					 = explode("doc", $logPath);
					$emodel					 = new EmailLog();
					$emodel->attributes		 = $row;
					$emodel->elg_id			 = $row['id'];
					$emodel->elg_address	 = $row['address'];
					$emodel->elg_to_name	 = $row['to_name'];
					$emodel->elg_subject	 = $row['subject'];
					$emodel->elg_content	 = Html2Text::convert($row['body']);
					$emodel->elg_created	 = $row['created'];
					$emodel->elg_booking_id	 = $row['booking_id'];
					$emodel->elg_recipient	 = $row['recipient'];
					$emodel->elg_delivered	 = $row['delivered'];
					$emodel->elg_status		 = $row['status'];
					$emodel->elg_status_date = $row['status_date'];
					$emodel->elg_attachments = $row['attachments'];
					$emodel->elg_file_path	 = $dbPath[1];
					$emodel->insert();
					$sql					 = "UPDATE `email_log2` SET status=-2 WHERE id={$row['id']}";
					Yii::app()->db->createCommand($sql)->execute();
				}
				catch (Exception $e)
				{
					if ($e->getCode() == '23000')
					{
						$sql = "UPDATE `email_log2` SET status=-2 WHERE id={$row['id']}";
						Yii::app()->db->createCommand($sql)->execute();
					}
					echo "Row ID: {$row['id']} Code: {$e->getCode()} - Error: {$e->getMessage()}\r\n\r\n";
				}
			}
//			break;
		}
		echo "<br>";
		echo "===========End==============";
	}

}

?>