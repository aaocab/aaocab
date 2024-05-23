<?php

/**
 * This is the model class for table "partner_reconciliation_data".
 *
 * The followings are the available columns in table 'partner_reconciliation_data':
 * @property integer $prd_id
 * @property integer $prd_prs_id
 * @property integer $prd_bkg_id
 * @property integer $prd_status
 * @property string $prd_recon_remarks
 * @property string $prd_create_date
 * @property string $prd_order_reference_number
 * @property string $prd_doi
 * @property string $prd_start_date
 * @property integer $prd_extra_travelled_km
 * @property integer $prd_total_amount
 * @property double $prd_seller_fare
 * @property integer $prd_gmv
 * @property integer $prd_part_payment_amount_post_markup
 * @property integer $prd_driver_cash_collection
 * @property double $prd_gst
 * @property double $prd_amount_after_tax_deduction
 * @property double $prd_amount_after_toll_deduction
 * @property double $prd_commission_charges
 * @property double $prd_amount_after_commision
 * @property double $prd_amount_to_be_released
 * @property double $prd_tax_on_comission
 * @property double $prd_net_commision
 * @property double $prd_tds
 * @property double $prd_tcs
 * @property string $prd_pnr_payout_status
 * @property string $prd_payout_number
 * @property string $prd_payout_payment_date
 * @property string $prd_remarks
 */
class PartnerReconciliationData extends CActiveRecord
{

	public $arrDataStatus = ["1" => "Pending", "2" => "Completed", "3" => "Failed"];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_reconciliation_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prd_prs_id, prd_create_date', 'required'),
			array('prd_prs_id, prd_bkg_id, prd_status, prd_extra_travelled_km, prd_total_amount, prd_gmv, prd_part_payment_amount_post_markup, prd_driver_cash_collection', 'numerical', 'integerOnly' => true),
			array('prd_seller_fare, prd_gst, prd_amount_after_tax_deduction, prd_amount_after_toll_deduction, prd_commission_charges, prd_amount_after_commision, prd_amount_to_be_released, prd_tax_on_comission, prd_net_commision, prd_tds, prd_tcs', 'numerical'),
			array('prd_order_reference_number, prd_pnr_payout_status, prd_payout_number', 'length', 'max' => 100),
			array('prd_remarks, prd_recon_remarks', 'length', 'max' => 2000),
			array('prd_id, prd_prs_id, prd_bkg_id, prd_status, prd_recon_remarks, prd_create_date, prd_order_reference_number, prd_doi, prd_start_date, prd_extra_travelled_km, prd_total_amount, prd_seller_fare, prd_gmv, prd_part_payment_amount_post_markup, prd_driver_cash_collection, prd_gst, prd_amount_after_tax_deduction, prd_amount_after_toll_deduction, prd_commission_charges, prd_amount_after_commision, prd_amount_to_be_released, prd_tax_on_comission, prd_net_commision, prd_tds, prd_tcs, prd_pnr_payout_status, prd_payout_number, prd_payout_payment_date, prd_remarks', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prd_id, prd_prs_id, prd_bkg_id, prd_status, prd_recon_remarks, prd_create_date, prd_order_reference_number, prd_doi, prd_start_date, prd_extra_travelled_km, prd_total_amount, prd_seller_fare, prd_gmv, prd_part_payment_amount_post_markup, prd_driver_cash_collection, prd_gst, prd_amount_after_tax_deduction, prd_amount_after_toll_deduction, prd_commission_charges, prd_amount_after_commision, prd_amount_to_be_released, prd_tax_on_comission, prd_net_commision, prd_tds, prd_tcs, prd_pnr_payout_status, prd_payout_number, prd_payout_payment_date, prd_remarks', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prd_id'								 => 'Prd',
			'prd_prs_id'							 => 'Prd Prs',
			'prd_bkg_id'							 => 'Prd Bkg',
			'prd_status'							 => 'Prd Status',
			'prd_recon_remarks'						 => 'Prd Recon Remarks',
			'prd_create_date'						 => 'Prd Create Date',
			'prd_order_reference_number'			 => 'Prd Order Reference Number',
			'prd_doi'								 => 'Prd Doi',
			'prd_start_date'						 => 'Prd Start Date',
			'prd_extra_travelled_km'				 => 'Prd Extra Travelled Km',
			'prd_total_amount'						 => 'Prd Total Amount',
			'prd_seller_fare'						 => 'Prd Seller Fare',
			'prd_gmv'								 => 'Prd Gmv',
			'prd_part_payment_amount_post_markup'	 => 'Prd Part Payment Amount Post Markup',
			'prd_driver_cash_collection'			 => 'Prd Driver Cash Collection',
			'prd_gst'								 => 'Prd Gst',
			'prd_amount_after_tax_deduction'		 => 'Prd Amount After Tax Deduction',
			'prd_amount_after_toll_deduction'		 => 'Prd Amount After Toll Deduction',
			'prd_commission_charges'				 => 'Prd Commission Charges',
			'prd_amount_after_commision'			 => 'Prd Amount After Commision',
			'prd_amount_to_be_released'				 => 'Prd Amount To Be Released',
			'prd_tax_on_comission'					 => 'Prd Tax On Comission',
			'prd_net_commision'						 => 'Prd Net Commision',
			'prd_tds'								 => 'Prd Tds',
			'prd_tcs'								 => 'Prd Tcs',
			'prd_pnr_payout_status'					 => 'Prd Pnr Payout Status',
			'prd_payout_number'						 => 'Prd Payout Number',
			'prd_payout_payment_date'				 => 'Prd Payout Payment Date',
			'prd_remarks'							 => 'Prd Remarks',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('prd_id', $this->prd_id);
		$criteria->compare('prd_prs_id', $this->prd_prs_id);
		$criteria->compare('prd_bkg_id', $this->prd_bkg_id);
		$criteria->compare('prd_status', $this->prd_status);
		$criteria->compare('prd_recon_remarks', $this->prd_recon_remarks);
		$criteria->compare('prd_create_date', $this->prd_create_date, true);
		$criteria->compare('prd_order_reference_number', $this->prd_order_reference_number, true);
		$criteria->compare('prd_doi', $this->prd_doi, true);
		$criteria->compare('prd_start_date', $this->prd_start_date, true);
		$criteria->compare('prd_extra_travelled_km', $this->prd_extra_travelled_km);
		$criteria->compare('prd_total_amount', $this->prd_total_amount);
		$criteria->compare('prd_seller_fare', $this->prd_seller_fare);
		$criteria->compare('prd_gmv', $this->prd_gmv);
		$criteria->compare('prd_part_payment_amount_post_markup', $this->prd_part_payment_amount_post_markup);
		$criteria->compare('prd_driver_cash_collection', $this->prd_driver_cash_collection);
		$criteria->compare('prd_gst', $this->prd_gst);
		$criteria->compare('prd_amount_after_tax_deduction', $this->prd_amount_after_tax_deduction);
		$criteria->compare('prd_amount_after_toll_deduction', $this->prd_amount_after_toll_deduction);
		$criteria->compare('prd_commission_charges', $this->prd_commission_charges);
		$criteria->compare('prd_amount_after_commision', $this->prd_amount_after_commision);
		$criteria->compare('prd_amount_to_be_released', $this->prd_amount_to_be_released);
		$criteria->compare('prd_tax_on_comission', $this->prd_tax_on_comission);
		$criteria->compare('prd_net_commision', $this->prd_net_commision);
		$criteria->compare('prd_tds', $this->prd_tds);
		$criteria->compare('prd_tcs', $this->prd_tcs);
		$criteria->compare('prd_pnr_payout_status', $this->prd_pnr_payout_status, true);
		$criteria->compare('prd_payout_number', $this->prd_payout_number, true);
		$criteria->compare('prd_payout_payment_date', $this->prd_payout_payment_date, true);
		$criteria->compare('prd_remarks', $this->prd_remarks, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerReconciliationData the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function addPayoutSheet($arrSheet, $file, $fileName)
	{
		$count		 = 0;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$currDate				 = date("Y-m-d H:i:s");
			$prdDOI					 = $prdStartDate			 = $prdPayoutPaymentDate	 = "";

//			$modelSheet					 = new PartnerReconciliationSheet();
//			$modelSheet->prs_title		 = trim($arrSheet['prs_title']);
//			$modelSheet->prs_sheet_type	 = trim($arrSheet['prs_sheet_type']);
//			$modelSheet->prs_filename	 = $fileName;
//			$modelSheet->prs_status		 = 1;
//			$modelSheet->prs_created_by	 = UserInfo::getUserId();
//			$modelSheet->prs_create_date = $currDate;
//			if (!$modelSheet->save())
//			{
//				throw new Exception(json_encode($modelSheet->getErrors()));
//			}

			$modelSheet = PartnerReconciliationSheet::addSheet($arrSheet, $fileName);

			while (($data = fgetcsv($file, 10000, ",")))
			{
				if ($count > 0)
				{
					$model					 = new PartnerReconciliationData();
					$model->prd_prs_id		 = $modelSheet->prs_id;
					$model->prd_status		 = 1;
					$model->prd_create_date	 = $currDate;

					if (trim($data[8]) != '' && DateTime::createFromFormat('d-m-Y', $data[8]))
					{
						$prdDOI = DateTime::createFromFormat('d-m-Y', trim($data[8]))->format('Y-m-d');
					}
					if (trim($data[9]) != '' && DateTime::createFromFormat('d-m-Y', $data[9]))
					{
						$prdStartDate = DateTime::createFromFormat('d-m-Y', trim($data[9]))->format('Y-m-d');
					}
					if (trim($data[53]) != '' && DateTime::createFromFormat('d-m-Y', $data[53]))
					{
						$prdPayoutPaymentDate = DateTime::createFromFormat('d-m-Y', trim($data[53]))->format('Y-m-d');
					}

					$model->prd_order_reference_number			 = (trim($data[2]) != '' ? trim($data[2]) : "");
					$model->prd_doi								 = $prdDOI;
					$model->prd_start_date						 = $prdStartDate;
					$model->prd_extra_travelled_km				 = (is_numeric($data[14]) ? trim($data[14]) : "");
					$model->prd_total_amount					 = (is_numeric($data[34]) ? trim($data[34]) : "");
					$model->prd_seller_fare						 = (is_numeric($data[35]) ? trim($data[35]) : "");
					$model->prd_gmv								 = (is_numeric($data[36]) ? trim($data[36]) : "");
					$model->prd_part_payment_amount_post_markup	 = (is_numeric($data[37]) ? trim($data[37]) : "");
					$model->prd_driver_cash_collection			 = (is_numeric($data[40]) ? trim($data[40]) : "");
					$model->prd_gst								 = (is_numeric($data[41]) ? trim($data[41]) : "");
					$model->prd_amount_after_tax_deduction		 = (is_numeric($data[42]) ? trim($data[42]) : "");
					$model->prd_amount_after_toll_deduction		 = (is_numeric($data[43]) ? trim($data[43]) : "");
					$model->prd_commission_charges				 = (is_numeric($data[44]) ? trim($data[44]) : "");
					$model->prd_amount_after_commision			 = (is_numeric($data[45]) ? trim($data[45]) : "");
					$model->prd_amount_to_be_released			 = (is_numeric($data[46]) ? trim($data[46]) : "");
					$model->prd_tax_on_comission				 = (is_numeric($data[47]) ? trim($data[47]) : "");
					$model->prd_net_commision					 = (is_numeric($data[48]) ? trim($data[48]) : "");
					$model->prd_tds								 = (is_numeric($data[49]) ? trim($data[49]) : "");
					$model->prd_tcs								 = (is_numeric($data[50]) ? trim($data[50]) : "");
					$model->prd_pnr_payout_status				 = (is_numeric($data[51]) ? trim($data[51]) : "");
					$model->prd_payout_number					 = (trim($data[52]) != '' ? trim($data[52]) : "");
					$model->prd_payout_payment_date				 = $prdPayoutPaymentDate;
					$model->prd_remarks							 = (trim($data[55]) != '' ? trim($data[55]) : "");

					if (!$model->save())
					{
						throw new Exception(json_encode($model->getErrors()));
					}
				}
				$count++;
			}

			$modelSheet->prs_row_count = ($count > 0 ? $count - 1 : 0);
			$modelSheet->save();
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
			return false;
		}

		DBUtil::commitTransaction($transaction);
		return $count;
	}

	public static function processData($prsId, $status)
	{
		if ($status == 1)
		{
			$sqlUpdSheet = "UPDATE partner_reconciliation_sheet SET prs_status = 2 WHERE prs_status = 1 AND prs_id = {$prsId}";
			DBUtil::execute($sqlUpdSheet);

			$sqlUpdBkgId = "UPDATE partner_reconciliation_data 
							INNER JOIN booking ON bkg_agent_ref_code = prd_order_reference_number 
							SET prd_bkg_id = bkg_id 
							WHERE prd_prs_id = {$prsId} AND bkg_status IN (2,3,5,6,7,9) AND bkg_agent_id = 18190 ";
			DBUtil::execute($sqlUpdBkgId);

			$sqlUpdData = "UPDATE partner_reconciliation_data SET prd_status = 3, prd_recon_remarks = 'Partner booking id not matched' 
							WHERE prd_status = 1 AND prd_prs_id = {$prsId} AND prd_bkg_id IS NULL";
			DBUtil::execute($sqlUpdData);
		}

		$sqlPayout	 = "SELECT prd_id, 
							IF(ABS(IF(bkg_booking_type IN (9,10,11), prd_total_amount, prd_seller_fare) - bkg_total_amount) > 1, 1, 0) as flgSellerFare, 
							IF(ABS(IFNULL(prd_driver_cash_collection, 0) - bkg_vendor_collected) > 1, 1, 0) as flgCashCollected, 
							IF(ABS(IFNULL(prd_commission_charges, 0) - bkg_partner_commission) > 1, 1, 0) as flgCommission, 
							IF(ABS(IFNULL(prd_tax_on_comission, 0) - (bkg_partner_commission - (bkg_partner_commission / 1.18))) > 1, 1, 0) as flgCommissionTax, 
							IF(ABS(IFNULL(prd_net_commision, 0) - (bkg_partner_commission - (bkg_partner_commission - (bkg_partner_commission / 1.18)))) > 1, 1, 0) as flgNetCommission, 
							IF(ABS(IFNULL(prd_tds, 0) - (bkg_total_amount * 0.01)) > 1, 1, 0) as flgTDS, 
							IF(ABS(IFNULL(prd_amount_after_commision, 0) - (bkg_total_amount - bkg_vendor_collected - bkg_partner_commission)) > 1, 1, 0) as flgAmtAfterComm, 
							IF(ABS(IFNULL(prd_amount_to_be_released, 0) - (bkg_total_amount - bkg_vendor_collected - bkg_partner_commission - (bkg_total_amount * 0.01))) > 1, 1, 0) as flgAmtToRelease, 
							IF(ABS(IFNULL(prd_amount_after_toll_deduction, 0) - (bkg_total_amount - bkg_toll_tax - bkg_state_tax - bkg_airport_entry_fee - bkg_driver_allowance_amount)) > 1, 1, 0) as flgAmtTollDeduction 
							FROM booking bkg 
							INNER JOIN booking_invoice biv ON bkg.bkg_id = biv.biv_bkg_id 
							INNER JOIN partner_reconciliation_data ON bkg.bkg_id = prd_bkg_id 
							WHERE prd_prs_id = {$prsId} AND prd_bkg_id > 0 AND prd_status = 1";
		$resPayout	 = DBUtil::query($sqlPayout, DBUtil::MDB());
		if ($resPayout)
		{
			foreach ($resPayout as $res)
			{
				$prdId = $res['prd_id'];

				$err = "";
				$err .= ($res['flgSellerFare'] == 1) ? "Seller & booking total amount mismatch. " : "";
				$err .= ($res['flgCashCollected'] == 1) ? "Driver cash collected amount mismatch. " : "";
				$err .= ($res['flgCommission'] == 1) ? "Commission amount mismatch. " : "";
				$err .= ($res['flgCommissionTax'] == 1) ? "Tax on commission amount mismatch. " : "";
				$err .= ($res['flgNetCommission'] == 1) ? "Net commission (after TDS) amount mismatch. " : "";
				$err .= ($res['flgTDS'] == 1) ? "TDS amount mismatch. " : "";
				$err .= ($res['flgAmtAfterComm'] == 1) ? "Amount after commission mismatch. " : "";
				$err .= ($res['flgAmtToRelease'] == 1) ? "Amount to be released mismatch. " : "";
				$err .= ($res['flgAmtTollDeduction'] == 1) ? "Amount after Toll deduction mismatch. " : "";

				$sqlUpd = "UPDATE partner_reconciliation_data SET prd_status = 2 WHERE prd_id = {$prdId}";
				if (trim($err) != '')
				{
					$sqlUpd = "UPDATE partner_reconciliation_data SET prd_status = 3, prd_recon_remarks = '{$err}' WHERE prd_id = {$prdId}";
				}
				DBUtil::execute($sqlUpd);
			}

			$sqlUpdSheet = "UPDATE partner_reconciliation_sheet SET prs_status = 3 WHERE prs_status = 2 AND prs_id = {$prsId}";
			DBUtil::execute($sqlUpdSheet);
		}
	}

	public static function exportData($prsId)
	{
		$arrDataStatus = PartnerReconciliationData::model()->arrDataStatus;

		$filename = "ReconciliationData_{$prsId}_" . date('YmdHi') . ".csv";

		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename={$filename}");
		header("Pragma: no-cache");
		header("Expires: 0");

		$foldername = Yii::app()->params['uploadPath'];

		$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
		if (!is_dir($foldername))
		{
			mkdir($foldername);
		}
		if (file_exists($backup_file))
		{
			unlink($backup_file);
		}

		$handle = fopen("php://output", 'w');
		fputcsv($handle, ['Bkg Id', 'Order Reference Number', 'Reconciliation Remarks', 'Reconciliation Status', 'Seller fare', 'Driver cash collection', 'Amount after tax deduction',
			'Amount after toll deduction', 'Commission charges', 'Amount After Commision', 'Amount to be released', 'Tax on Comission 18%', 'Net Commision', 'TDS_1%', 'TCS',
			'Bkg_Total_Amount', 'Bkg_Driver_Cash_Collected',
			'Amt_Toll_Deduction', 'Bkg_Partner_Commission', 'Amt_After_Commission', 'Amt_To_Release', 'Bkg_Tax_On_Commission', 'Net_Commission', 'TDS']);

		$sql	 = "SELECT prd_bkg_id, prd_order_reference_number, prd_recon_remarks, prd_status, 
					prd_seller_fare, prd_driver_cash_collection, prd_amount_after_tax_deduction, 
					prd_amount_after_toll_deduction, prd_commission_charges, prd_amount_after_commision, 
					prd_amount_to_be_released, prd_tax_on_comission, prd_net_commision, prd_tds, prd_tcs, 

					bkg_booking_id, bkg_total_amount, bkg_vendor_collected, bkg_partner_commission, ROUND((bkg_partner_commission / 1.18), 2) NetCommission, 
					ROUND((bkg_partner_commission - ROUND((bkg_partner_commission / 1.18), 2)), 2) TaxOnCommission, 
					ROUND((bkg_total_amount * 0.01), 2) TDS, ROUND((bkg_total_amount - bkg_vendor_collected - bkg_partner_commission), 2) AmtAfterComm, 
					ROUND((bkg_total_amount - bkg_vendor_collected - bkg_partner_commission - ROUND((bkg_total_amount * 0.01), 2)), 2) AmtToRelease, 
					ROUND((bkg_total_amount - bkg_toll_tax - bkg_state_tax - bkg_airport_entry_fee - bkg_driver_allowance_amount), 2) AmtTollDeduction 
					FROM partner_reconciliation_data 
					LEFT JOIN booking bkg ON bkg.bkg_id = prd_bkg_id 
					LEFT JOIN booking_invoice biv ON bkg.bkg_id = biv.biv_bkg_id 
					WHERE prd_prs_id = {$prsId} ";
		$data	 = DBUtil::query($sql, DBUtil::SDB());
		if ($data > 0)
		{
			foreach ($data as $res)
			{
				$rowArray								 = [];
				$rowArray['bkg_booking_id']				 = $res['bkg_booking_id'];
				$rowArray['prd_order_reference_number']	 = $res['prd_order_reference_number'];
				$rowArray['prd_recon_remarks']			 = $res['prd_recon_remarks'];
				$rowArray['prd_recon_status']			 = $arrDataStatus[$res['prd_status']];

				$rowArray['prd_seller_fare']				 = $res['prd_seller_fare'];
				$rowArray['prd_driver_cash_collection']		 = $res['prd_driver_cash_collection'];
				$rowArray['prd_amount_after_tax_deduction']	 = $res['prd_amount_after_tax_deduction'];
				$rowArray['prd_amount_after_toll_deduction'] = $res['prd_amount_after_toll_deduction'];
				$rowArray['prd_commission_charges']			 = $res['prd_commission_charges'];
				$rowArray['prd_amount_after_commision']		 = $res['prd_amount_after_commision'];
				$rowArray['prd_amount_to_be_released']		 = $res['prd_amount_to_be_released'];
				$rowArray['prd_tax_on_comission']			 = $res['prd_tax_on_comission'];
				$rowArray['prd_net_commision']				 = $res['prd_net_commision'];
				$rowArray['prd_tds']						 = $res['prd_tds'];
				$rowArray['prd_tcs']						 = $res['prd_tcs'];

				$rowArray['bkg_total_amount']		 = $res['bkg_total_amount'];
				$rowArray['bkg_vendor_collected']	 = $res['bkg_vendor_collected'];
				$rowArray['AmtTollDeduction']		 = $res['AmtTollDeduction'];
				$rowArray['bkg_partner_commission']	 = $res['bkg_partner_commission'];
				$rowArray['AmtAfterComm']			 = $res['AmtAfterComm'];
				$rowArray['AmtToRelease']			 = $res['AmtToRelease'];
				$rowArray['TaxOnCommission']		 = $res['TaxOnCommission'];
				$rowArray['NetCommission']			 = $res['NetCommission'];
				$rowArray['TDS']					 = $res['TDS'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
		}

		fclose($handle);
		exit;
	}
	
	public function getList()
	{
		$sql			 = "SELECT * FROM partner_reconciliation_data";
		$sqlCount		 = "SELECT COUNT(1) cnt FROM partner_reconciliation_data";
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => ['defaultOrder' => 'prd_id ASC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
	}

}
