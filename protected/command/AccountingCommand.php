<?php

class AccountingCommand extends BaseCommand
{

	public function actionUpdateSecurityDeposit()
	{
		$records = AccountTransactions::getSecurityDepositMismatch();

		foreach ($records as $rows)
		{
			try
			{
				$model = VendorStats::model()->getbyVendorId($records['vndId']);
				if ($model)
				{
					$model->vrs_security_amount	 = ($records['accDeposit'] * -1);
					$success					 = $model->save();
					if (!$success)
					{
						throw new Exception("update security deposit failed for vendor ID: {$records['vndId']}");
					}
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}
	
	public function actionCalculateSD()
	{
		$check = Filter::checkProcess("accounting calculateSD");
		if (!$check)
		{
			return;
		}
		
		$data = Vendors::model()->getCollectionList(30);
		foreach ($data as $d)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$vndID		 = $d['vnd_id'];
				$totTrans	 = $d['totTrans'];
				
				Logger::writeToConsole("vnd_id: {$vndID}, totTrans: {$totTrans}");
				
				//if ($totTrans < 0)
				//{
					$sdAmt = Vendors::getSD($vndID);
					
					Logger::writeToConsole("sdAmt: {$sdAmt}");
					
					if ($sdAmt > 0)
					{
						$model										 = Vendors::model()->resetScope()->findByPk($vndID);
						$modelVendStats								 = $model->vendorStats;
						$modelVendStats->vrs_security_amount		 = $modelVendStats->vrs_security_amount + $sdAmt;
						$modelVendStats->vrs_security_receive_date	 = new CDbExpression('NOW()');
						$modelVendStats->setAttribute('vrs_vnd_id', $model->vnd_id);
						if ($modelVendStats->save())
						{
							$desc = 'Security deposit ' . $sdAmt . " transfered from vendor account";
							
							Logger::writeToConsole("Desc: {$desc}");
							VendorsLog::model()->createLog($model->vnd_id, $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SECURITY_DEPOSIT, false, false);
						}
					}
				//}
				
				Logger::writeToConsole("DONE");
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($e);
			}
		}
	}
	
//	public function actionProcessReconciliationSheet()
//	{
//		$sqlSheet = "SELECT * FROM partner_reconciliation_sheet WHERE prs_status IN (1,2) ORDER BY prs_id ASC LIMIT 0,1";
//		$rowSheet = DBUtil::queryRow($sqlSheet, DBUtil::SDB());
//		if ($rowSheet)
//		{
//			$prsId		 = $rowSheet['prs_id'];
//			$sheetType	 = $rowSheet['prs_sheet_type'];
//			$status		 = $rowSheet['prs_status'];
//			
//			if($status == 1)
//			{
//				$sqlUpdSheet = "UPDATE partner_reconciliation_sheet SET prs_status = 2 WHERE prs_status = 1 AND prs_id = {$prsId}";
//				DBUtil::execute($sqlUpdSheet);
//				
//				$sqlUpdBkgId = "UPDATE partner_reconciliation_data 
//							INNER JOIN booking ON bkg_agent_ref_code = prd_order_reference_number 
//							SET prd_bkg_id = bkg_id 
//							WHERE prd_prs_id = {$prsId} AND bkg_status IN (2,3,5,6,7,9) AND bkg_agent_id = 18190 ";
//				DBUtil::execute($sqlUpdBkgId);
//			}
//			
//			
//		}
//	}

}
	