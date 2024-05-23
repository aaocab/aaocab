<?php

/**
 * This is the model class for table "vendor_balance".
 *
 * The followings are the available columns in table 'vendor_balance':
 * @property integer $vbl_id
 * @property integer $vbl_vnd_id
 * @property double $vbl_trip_amt
 * @property double $vbl_tds_amt
 * @property double $vbl_tds_paid
 * @property double $vbl_tds_bal
 * @property string $vbl_create_date
 * @property integer $vbl_active
 */
class VendorBalance extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_balance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vbl_id, vbl_vnd_id, vbl_trip_amt, vbl_tds_amt, vbl_tds_paid, vbl_tds_bal, vbl_create_date', 'required'),
			array('vbl_id, vbl_vnd_id, vbl_active', 'numerical', 'integerOnly' => true),
			array('vbl_trip_amt, vbl_tds_amt, vbl_tds_paid, vbl_tds_bal', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vbl_id, vbl_vnd_id, vbl_trip_amt, vbl_tds_amt, vbl_tds_paid, vbl_tds_bal, vbl_create_date, vbl_active', 'safe', 'on' => 'search'),
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
			'vbl_id'			 => 'Vbl',
			'vbl_vnd_id'		 => 'Vbl Vnd',
			'vbl_trip_amt'		 => 'Vbl Trip Amt',
			'vbl_tds_amt'		 => 'Vbl Tds Amt',
			'vbl_tds_paid'		 => 'Vbl Tds Paid',
			'vbl_tds_bal'		 => 'Vbl Tds Bal',
			'vbl_create_date'	 => 'Vbl Create Date',
			'vbl_active'		 => 'Vbl Active',
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

		$criteria->compare('vbl_id', $this->vbl_id);
		$criteria->compare('vbl_vnd_id', $this->vbl_vnd_id);
		$criteria->compare('vbl_trip_amt', $this->vbl_trip_amt);
		$criteria->compare('vbl_tds_amt', $this->vbl_tds_amt);
		$criteria->compare('vbl_tds_paid', $this->vbl_tds_paid);
		$criteria->compare('vbl_tds_bal', $this->vbl_tds_bal);
		$criteria->compare('vbl_create_date', $this->vbl_create_date, true);
		$criteria->compare('vbl_active', $this->vbl_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorBalance the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getVendorTDS()
	{
		$PreYear	 = $NextYear	 = "";
		if (date('m') >= 4)
		{
			$PreYear	 = date('Y') . "-04-01 00:00:00";
			$NextYear	 = (date('Y') + 1) . "-03-31 23:59:59";
		}
		else
		{
			$PreYear	 = (date('Y') - 1) . "-04-01 00:00:00";
			$NextYear	 = (date('Y')) . "-03-31 23:59:59";
		}
		$transaction = null;
		try
		{
			$transaction = DBUtil::beginTransaction();
			$sql		 = "TRUNCATE table vendor_balance;
							SET @i:=0;
							INSERT into vendor_balance(vbl_id,vbl_vnd_id, vbl_trip_amt, vbl_monthly_trip_amt, vbl_tds_amt, vbl_tds_paid, vbl_tds_month_paid, vbl_create_date, vbl_active)  SELECT @i:=@i+1 AS  vbl_id, temps.* from (
							SELECT
							temp.vnd_ref_code as vbl_vnd_id ,
							sum(temp.vbl_trip_amt) AS vbl_trip_amt ,
							sum(temp.vbl_monthly_trip_amt) AS vbl_monthly_trip_amt,
							sum(temp.vbl_tds_amt) AS vbl_tds_amt,
							sum(temp.vbl_tds_paid) AS vbl_tds_paid, 
							sum(temp.vbl_tds_month_paid) AS vbl_tds_month_paid,
							vbl_create_date,
							vbl_active
							FROM(
							SELECT 
							atd1.adt_trans_ref_id as  vbl_vnd_id,
							dm.vnd_id,
							dm.vnd_ref_code,
							sum(if(atd.adt_ledger_id = 22, atd.adt_amount, 0)) AS vbl_trip_amt,
							sum(if(atd.adt_ledger_id = 22 AND act_date>=NOW(), atd.adt_amount, 0)) AS vbl_monthly_trip_amt,
							sum(if(atd.adt_ledger_id = 22, round(atd.adt_amount)* 0.01, 0)) AS vbl_tds_amt,
							sum(if(atd.adt_ledger_id IN(37,55), (-1 * atd.adt_amount), 0)) AS vbl_tds_paid, 
							sum(if(atd.adt_ledger_id IN(37,55) AND act_date>=NOW(), (-1 * atd.adt_amount), 0)) AS vbl_tds_month_paid, 
							NOW() as vbl_create_date,
							'1' as vbl_active
							FROM  account_trans_details atd
								  INNER JOIN account_transactions act   ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND act.act_active = 1 AND atd.adt_ledger_id IN(22, 37, 55)
								  INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_ledger_id = 14
							INNER JOIN vendors AS dm ON dm.vnd_id = atd1.adt_trans_ref_id
							WHERE    act.act_date BETWEEN $PreYear AND NOW() GROUP BY atd1.adt_trans_ref_id ) temp 
							group by temp.vnd_ref_code
							) temps where temps.vbl_trip_amt>=100000";
			$result		 = DBUtil::command($sql)->execute();
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public static function addVendorTDS()
	{
		$transaction = null;
		$userInfo	 = null;
		$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
		$limit		 = 1000;

		$sql	 = "SELECT vbl_vnd_id,vbl_tds_bal FROM vendor_balance WHERE vbl_tds_bal >= 1 AND vbl_active = 1 ORDER BY vbl_tds_bal DESC  LIMIT 0, $limit ";
		$result	 = DBUtil::queryAll($sql);
		foreach ($result as $val)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sqlMergedVendorId	 = "Select  group_concat(vnd_id) as vnd_id from vendors where 1 and vnd_ref_code={$val['vbl_vnd_id']}";
				$vendorIds			 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();

				$vendorBal = DBUtil::command("SELECT SUM(atd.adt_amount) AS Balance FROM account_transactions act
					INNER JOIN account_trans_details atd  ON atd.adt_trans_id = act.act_id 
					WHERE atd.adt_active = 1 AND act.act_active = 1 AND	act.act_status = 1 AND	act.act_date < NOW() AND atd.adt_ledger_id = 14 AND	atd.adt_trans_ref_id  in ($vendriods)")->queryScalar();
				if ($vendorBal >= 0)
				{
					DBUtil::rollbackTransaction($transaction);
					continue;
				}
				$vndDetails = AccountTransDetails::model()->getRunningBalByVndId($vendorIds, $val['vbl_tds_bal'], (-1 * $vendorBal));
				if (empty($vndDetails['tdsDetails']))
				{
					DBUtil::rollbackTransaction($transaction);
					continue;
				}

				$tdsExact	 = $vndDetails['tdsDetails']['tdsApplicable'] - $vndDetails['tdsDetails']['tdsPaid'];
				$tds		 = round($tdsExact);
				if ($tds <= 3)
				{
					DBUtil::rollbackTransaction($transaction);
					continue;
				}
				$vendorId			 = $val['vbl_vnd_id'];
				$datetime			 = $vndDetails['tdsDetails']['date'];
				$tripVal			 = round($tdsExact * 100);
				$desc				 = date("d-M'y", strtotime($vndDetails['tdsDate']['startDate'])) . " - " . date("d-M'y", strtotime($vndDetails['tdsDetails']['date']));
				$remarks			 = "TDS of ₹" . $tds . " (Trip Purchased: ₹$tripVal) has been deducted for $desc";
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_TDS, (-1 * $tds), $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $tds);
				$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $tds, $vendorId, Accounting::AT_OPERATOR, $remarks, UserInfo::model());
				if (!$success)
				{
					throw new Exception("Unable to deduct TDS for Vendor $vendorId of amount ₹$tds");
				}

				AppTokens::model()->notifyVendor($vendorId, $payLoadData, $remarks, "TDS Notification");

				$sqlUpdate = "UPDATE vendor_balance SET vbl_active=0 WHERE vbl_vnd_id IN ($vendorId)";
				DBUtil::command($sqlUpdate)->execute();
				DBUtil::commitTransaction($transaction);
				echo "Success: Vendor $vendorId - " . $remarks . "\n\n";
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

}
