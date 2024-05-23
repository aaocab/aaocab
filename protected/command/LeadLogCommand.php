<?php

class LeadLogCommand extends BaseCommand
{

	public function actionInsertLeadLogCron()
	{
		echo 'sfsdf';
		$Model = BookingTemp::model()->findAll();
		foreach ($Model as $value)
		{
			$remarkBookingTemp = $value->bkg_follow_up_comment;
			if ($remarkBookingTemp != '')
			{
				$bkg_id				 = $value->bkg_id;
				$remarkBookingTemp	 = json_decode($remarkBookingTemp);
				foreach ($remarkBookingTemp as $value)
				{
					echo 'inside sfsdf';
					$admin_id		 = $value[0];
					$dateTime		 = $value[1];
					$comment		 = $value[2];
					$bkg_info_source = $value[3];
					$followup_status = $value[4];
					$leadLog		 = LeadLog::model()->remarkCron($admin_id, $dateTime, $bkg_id, $followup_status, $comment);
				}
			}
		}
	}

	public function actionUpdateLeadLog()
	{
		echo 'Update started';
		$sql	 = "UPDATE lead_log ll, lead_log_cron llc
                SET ll.blg_desc=CONCAT(ll.blg_desc,'\n Remarks: ',llc.blg_remarks,''),ll.blg_follow_up_status=llc.blg_follow_up_status WHERE
                ll.blg_booking_id=llc.blg_booking_id AND ll.blg_admin_id=llc.blg_admin_id AND ABS(TIMESTAMPDIFF(SECOND,ll.blg_created,llc.blg_created))<2";
		$success = Yii::app()->db->createCommand($sql)->execute();
		echo 'update success' . $success;
	}

}
