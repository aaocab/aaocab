<?php

class VendortransCommand extends BaseCommand
{

	public function actionRefundTDS()
	{
		$sql = "SELECT v2.vnd_ref_code, ledgerId, (v2.vnd_name) as name, vendor_stats.vrs_security_amount,    
						SUM(currentTotal) as tdsPaid, c1.ctt_pan_no, vbl_tds_paid, minAmount, ((SUM(currentTotal)*-1)-vbl_tds_filed) tdsRefund, vendor_balance.*
				FROM account_ledger
				INNER JOIN (SELECT atd.adt_ledger_id, atd.adt_trans_ref_id, MIN(atd1.adt_amount*-1) as minAmount, SUM(atd1.adt_amount) as currentTotal ,
					SUM(IF(atd1.adt_amount<0, atd1.adt_amount,0)) as credit, SUM(IF(atd1.adt_amount>0, atd1.adt_amount,0)) as debit
					FROM account_trans_details atd
					INNER JOIN account_transactions ON act_id=atd.adt_trans_id AND act_active=1 
					INNER JOIN account_ledger ON ledgerId=adt_ledger_id AND atd.adt_ledger_id=14
					INNER JOIN account_trans_details atd1 ON act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 37
					WHERE atd.adt_active=1 AND atd.adt_status=1 AND (act_date BETWEEN  '2019-04-01 00:00:00'  AND '2020-03-31 23:59:59') 
					GROUP BY atd.adt_ledger_id , atd.adt_trans_ref_id
				) a on ledgerId=a.adt_ledger_id
				INNER JOIN vendors v1 ON vnd_id=(a.adt_trans_ref_id)
				INNER JOIN vendors v2 ON v2.vnd_id=v1.vnd_ref_code
				INNER JOIN contact ON v2.vnd_contact_id=contact.ctt_id
				INNER JOIN contact c1 ON contact.ctt_ref_code=c1.ctt_id
				INNER JOIN vendor_balance ON vbl_vnd_id=v2.vnd_ref_code
				INNER JOIN vendor_stats ON v2.vnd_id=vendor_stats.vrs_vnd_id
				WHERE  ledgerId=14
				GROUP BY v2.vnd_id  HAVING tdsRefund<>0
				ORDER BY tdsRefund DESC";

		$res	 = DBUtil::query($sql);
		$count1	 = $res->getRowCount();
		foreach ($res as $row)
		{
			$count1--;
			$sql1		 = "SELECT * FROM vendor_balance WHERE vbl_vnd_id='{$row['vnd_ref_code']}'";
			$rowVbl		 = DBUtil::queryRow($sql1);
			$refundTds	 = ($row['tdsPaid'] * -1) - $rowVbl['vbl_tds_filed'];
			if ($refundTds <= 0)
			{
				continue;
			}

			$sql2 = "SELECT DISTINCT act_id, atd.adt_ledger_id, atd.adt_trans_ref_id, act_amount, atd1.adt_amount
					FROM account_trans_details atd
					INNER JOIN account_transactions ON act_id=atd.adt_trans_id AND act_active=1 
					INNER JOIN account_ledger ON ledgerId=adt_ledger_id AND atd.adt_ledger_id=14
					INNER JOIN account_trans_details atd1 ON act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 37
					INNER JOIN vendors v1 ON vnd_id=(atd.adt_trans_ref_id)
					INNER JOIN vendors v2 ON v2.vnd_id=v1.vnd_ref_code
					INNER JOIN contact ON v2.vnd_contact_id=contact.ctt_id
					INNER JOIN contact c1 ON contact.ctt_ref_code=c1.ctt_id AND v1.vnd_ref_code={$row["vnd_ref_code"]}
					WHERE atd.adt_active=1 AND atd.adt_status=1 AND (act_date BETWEEN  '2019-04-01 00:00:00'  AND '2020-03-31 23:59:59') 
					ORDER BY act_date ASC";

			$rows	 = DBUtil::query($sql2);
			$count	 = $rows->getRowCount();
			foreach ($rows as $row2)
			{
				$tdsAmount = $row2['adt_amount'] * -1;
				if ($tdsAmount <= $refundTds && $tdsAmount > 0 && $refundTds > 0)
				{
					/* 					$sql3 = "UPDATE account_transactions act,
					  account_trans_details atd,
					  account_trans_details atd1
					  SET act_amount=(act_amount-$refundTds),
					  atd.adt_amount=atd.adt_amount-$refundTds,
					  atd1.adt_amount=atd1.adt_amount+$refundTds
					  WHERE act_id=atd.adt_trans_id AND act_active=1 AND act_id = atd1.adt_trans_id
					  AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 37 AND atd.adt_ledger_id=14 AND act_id={$row2['act_id']}
					  ";

					  $status = DBUtil::command($sql3)->execute(); */
					$status = AccountTransactions::remove($row2['act_id']);
					if ($status)
					{
						$refundTds -= $tdsAmount;
						break;
					}
				}
			}
		}
	}

}
