<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BookingLogCommand extends BaseCommand
{

	private $eventList;

	public function parseRemark($remark, $model)
	{

		$bookLog = new BookingLog();

		if (CJSON::decode($remark) != '')
		{
			$comment = CJSON::decode($remark);
			foreach ($comment as $cmt)
			{
				$bkgid	 = $model->bkg_id;
				$desc	 = trim($cmt['2']);
				if (CJSON::decode($desc) != '')
				{
					echo "Double Array - $bkgid - $desc";
					$this->parseRemark($desc, $model);
					continue;
				}
				$user_type						 = BookingLog::Admin;
				$user_id						 = trim($cmt['0']);
				$eventid						 = BookingLog::REMARKS_ADDED;
				$oldModel						 = clone $model;
				$created						 = trim($cmt[1]);
				$params['blg_created']			 = $created;
				$params['blg_booking_status']	 = trim($cmt[3]);
				if (!in_array($desc, $this->eventList))
				{

					if (!BookingLog::model()->checkDuplicateRemark($bkgid, $desc, $user_id))
					{
						echo "NEW: $bkgid \t $desc \t $user_type \t $user_id \t $eventid  \n";
						BookingLog::model()->updateDuplicateRemark($bkgid, $desc, $user_id);
						$bookLog->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel, $params);
						echo "\n";
						echo "-------";
						echo $bkgid . "'\t" . $desc . "'\t" . $user_type . "'\t" . $user_id . "'\t" . $eventid . "'\t" . "- serialized";
						echo "\n";
					}
				}
			}
			//print_r($model->getAttributes());
			$model->bkg_remark_check = '1';
			$model->update();
		}
		else
		{
			return;
			/* var model Booking */
			$bkgid					 = $model->bkg_id;
			$desc					 = $model->bkg_remark;
			$user_type				 = BookingLog::Admin;
			$user_id				 = 1;
			$eventid				 = BookingLog::REMARKS_ADDED;
			$oldModel				 = clone $model;
			$created				 = $model->bkg_create_date;
			$params['blg_created']	 = $created;
			BookingLog::model()->updateDuplicateRemark($bkgid, $desc, $user_id);
			$bookLog->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel, $params);
			echo "<pre>";
			echo $bkgid . "'\t" . $desc . "'\t" . $user_type . "'\t" . $user_id . "'\t" . $eventid . "'\t" . "- non serialized";
			$model->bkg_remark_check = '1';
			$model->update();
			echo "non serialized :: --->";
		}
	}

}
