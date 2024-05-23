<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contact
 *
 * @author Suvajit
 */
class ContactCommand extends BaseCommand
{

	/**
	 * Function for merging duplicate drivers with same license number
	 */
	public function actionMerge()
	{
		//Finds the duplicate contact ids based on Licence number
		$dupConIds = "SELECT count(DISTINCT con.ctt_id) AS cnt, 
				GROUP_CONCAT(DISTINCT con.ctt_id SEPARATOR ', ') AS cttIds, 
				GROUP_CONCAT(con.ctt_first_name SEPARATOR ', ') AS cttName, ctt_pan_no, 
				GROUP_CONCAT(document.doc_id SEPARATOR ', ') AS docIds, SUM(if(document.doc_status = 1, 1, 0)) AS approved, 
				MAX(document.doc_approved_at) AS approveDate
		 FROM    contact con 
			LEFT JOIN document ON con.ctt_license_doc_id = document.doc_id AND document.doc_active =1
			
		 WHERE  LENGTH(con.ctt_license_no) > 11 AND con.ctt_active = 1 AND ctt_id NOT IN (SELECT cmg_mrg_ctt_id FROM contact_merged)
		 AND ctt_id NOT IN (SELECT cmr_ctt_mgr_id FROM contact_merge_remarks)
		 GROUP BY con.ctt_license_no
		 HAVING   cnt > 1 AND approved >= 1
		 ORDER BY cnt DESC LIMIT 1000";

		$arrDupConIds = DBUtil::queryAll($dupConIds);

		if (empty($arrDupConIds))
		{
			exit();
		}

		//Todo - Change doc type when prefernce changes
		$source = Document::Document_Licence;

		//Merge contacIds
		foreach ($arrDupConIds as $dupIds)
		{
			$contactIds	 = $dupIds["cttIds"]; //'90079, 18409'; 
			$drList		 = Contact::getDuplicateList($contactIds);

			$primarySet		 = 0;
			$primaryConId	 = 0;
			foreach ($drList as $detail)
			{
				if (!empty($detail["ctt_id"]) && !$primarySet)
				{
					$primaryConId	 = $detail["ctt_id"];
					$primarySet		 = 1;
					continue;
				}

				if (!empty($detail["ctt_id"]))
				{
					$duplicateConId = $detail["ctt_id"];
					goto merge;
				}

				merge:
				$sql	 = "SELECT COUNT(1) FROM contact c1
						INNER JOIN contact c2 ON c1.ctt_id={$primaryConId} AND c2.ctt_id={$duplicateConId} 
							AND (c2.ctt_ref_code IS NULL OR c2.ctt_ref_code<>{$primaryConId})
								AND ((c1.ctt_first_name LIKE CONCAT('%',c2.ctt_first_name,'%') OR c2.ctt_first_name LIKE CONCAT('%',c1.ctt_first_name,'%') OR  SOUNDEX(c1.ctt_first_name)=SOUNDEX(c2.ctt_first_name)) 
										AND c1.ctt_first_name<>''  AND c2.ctt_first_name<>'')
					";
				$count	 = DBUtil::queryScalar($sql);
				if ($count == 0)
				{
					$remarks = "Merge failed. Name not matched";
					ContactMergeRemarks::setManualMerge($primaryConId, $duplicateConId, $remarks);
					echo "$remarks {$primaryConId}::{$duplicateConId}\n";
					continue;
				}
				Contact::mergeIds($primaryConId, $duplicateConId, $source);
			}
		}
	}

	/*
	 * transfering or linking user with contact
	 */

	public function actionUpdateusercontact($startLimit)
	{
		Logger::profile("test:ProcessUser Started");

		while (true)
		{
			$sql		 = " SELECT user_id, usr_email FROM users WHERE usr_contact_id IS NULL AND usr_active > 0 AND usr_email IS NOT NULL AND usr_tmp_status = 0 ORDER BY user_id ASC LIMIT $startLimit, 1000";
			$arUsrData	 = DBUtil::query($sql);
			if ($arUsrData->count() <= 0)
			{
				exit();
			}

			foreach ($arUsrData as $usrData)
			{
				Users::validateAndTransferContact($usrData);
			}
		}

		Logger::profile("test:ProcessUser Ended");
	}

	public function actionProcessbookinguser($startLimit)
	{
		//$sqlCount	 = "SELECT count(1) from booking_user WHERE bkg_contact_id IS NULL AND bkg_user_id IS NOT NULL";
		//$cnt		 = DBUtil::command($sqlCount)->queryScalar();
		//for ($i = 0; $i < $cnt; $i = $i + 250)
		//{
		while (true)
		{
			$sql		 = "SELECT bui_id, bkg_user_email FROM booking_user INNER JOIN booking ON bkg_id = bui_bkg_id WHERE bkg_temp_status = 0 AND bkg_contact_id IS NULL AND bkg_user_id IS NOT NULL AND bkg_status IN(2,3,5,6,7,9) ORDER BY bkg_id DESC LIMIT $startLimit,1000";
			$bkgUserData = DBUtil::query($sql);
			if ($bkgUserData->count() <= 0)
			{
				exit();
			}
			foreach ($bkgUserData as $bkgUsrData)
			{
				$response = BookingUser::createContactFromUser($bkgUsrData);
				echo "\nDone";
				echo $response;
			}
		}
		//}
	}

	public function actionVerifyusercontact()
	{
		$sqlCnt	 = "SELECT Count(1)
                    FROM users u
                    INNER JOIN booking_user bu ON bu.bkg_user_id = u.user_id
                    INNER JOIN booking bkg ON bkg.bkg_id = bu.bui_bkg_id
                    WHERE bkg.bkg_status IN (5,6,7) AND u.usr_active = 1 AND bkg.bkg_active=1";
		$cnt	 = DBUtil::queryScalar($sqlCnt);
		for ($i = 0; $i < $cnt; $i = $i + 1000)
		{
			$sql		 = "SELECT u.user_id, 
                            u.usr_name, 
							u.usr_lname, 
							u.usr_email, 
							u.usr_mobile, 
							u.usr_country_code,
                            u.usr_contact_id  
							FROM   users u 
								   INNER JOIN booking_user bu 
										   ON bu.bkg_user_id = u.user_id 
								   INNER JOIN booking bkg 
										   ON bkg.bkg_id = bu.bui_bkg_id 
							WHERE  bkg.bkg_status IN ( 5, 6, 7 ) 
								   AND u.usr_active = 1 
								   AND bkg.bkg_active = 1 
							GROUP  BY u.user_id  LIMIT $i,1000";
			$arUsrData	 = DBUtil::query($sql);
			if (empty($arUsrData))
			{
				exit();
			}

			foreach ($arUsrData as $usrData)
			{
				Users::verifyContactItem($usrData);
			}
		}
	}

	public function actionPhonecorrection()
    {
        $sqlVerifiedPrimary = "UPDATE
									contact_phone
									SET phn_active = 0
								WHERE
									phn_is_primary = 0 AND phn_is_verified = 0 AND phn_active = 1 AND phn_contact_id IN(
									 SELECT
										phn_contact_id
									FROM
										contact_phone
									WHERE
										(
											phn_is_verified = 1 OR phn_is_primary = 1
										) AND phn_active = 1 AND phn_contact_id >0
									GROUP BY
										phn_contact_id
									HAVING
										COUNT(phn_contact_id) > 4
								)";
        $resultexe          = DBUtil::execute($sqlVerifiedPrimary);
        echo $resultexe;
        $sql                = "SELECT GROUP_CONCAT(phn_id ORDER BY phn_id ASC) phn,phn_contact_id FROM contact_phone WHERE phn_is_primary = 0 AND phn_is_verified = 0 AND phn_active = 1 AND phn_contact_id >0 GROUP BY phn_contact_id HAVING count(phn_contact_id)>4";
        $result             = DBUtil::query($sql);
        foreach ($result as $res)
        {
            $original   = explode(',', $res['phn']);
            $sql1       = "UPDATE contact_phone SET phn_active = 0 WHERE phn_contact_id =:contactId AND phn_id<>:phnId";
            $resultexe1 = DBUtil::execute($sql1, ['contactId' => $res['phn_contact_id'], 'phnId' => $original[0]]);
            echo $resultexe1;
        }
    }

    public function actionEmailcorrection()
    {
        $sqlVerifiedPrimary = "UPDATE	contact_email
                                        SET eml_active = 0
                                     WHERE
                                        eml_is_primary = 0 AND eml_is_verified = 0 AND eml_active = 1 AND eml_contact_id IN(
                                            SELECT
                                            eml_contact_id
                                            FROM
                                            contact_email
                                            WHERE
                                            (
                                                eml_is_verified = 1 OR eml_is_primary = 1
                                            ) AND eml_active = 1 AND eml_contact_id >0
									GROUP BY
										eml_contact_id
									HAVING
										COUNT(eml_contact_id) > 4
                                        )";
        $resultexe          = DBUtil::execute($sqlVerifiedPrimary);
        echo $resultexe;
        $sql                = "SELECT GROUP_CONCAT(eml_id ORDER BY eml_id ASC) eml,eml_contact_id FROM contact_email WHERE eml_is_primary = 0 AND eml_is_verified = 0 AND eml_active = 1 AND eml_contact_id >0 GROUP BY eml_contact_id HAVING count(eml_contact_id)>4";
        $result             = DBUtil::query($sql);
        foreach ($result as $res)
        {
            $original   = explode(',', $res['eml']);
            $sql1       = "UPDATE contact_email SET eml_active = 0 WHERE eml_contact_id =:contactId AND eml_id<>:emlId";
            $resultexe1 = DBUtil::execute($sql1, ['contactId' => $res['eml_contact_id'], 'emlId' => $original[0]]);
            echo $resultexe1;
        }
    }
}
