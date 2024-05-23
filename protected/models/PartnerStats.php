<?php

/**
 * This is the model class for table "partner_stats".
 *
 * The followings are the available columns in table 'partner_stats':
 * @property integer $pts_id
 * @property integer $pts_agt_id
 * @property string $pts_created_date
 * @property integer $pts_max_7days_quotes
 * @property integer $pts_min_7days_quotes
 * @property integer $pts_total_7days_quotes
 * @property double $pts_median_7days_quotes
 * @property integer $pts_max_7days_booking
 * @property integer $pts_min_7days_booking
 * @property integer $pts_total_7days_booking
 * @property double $pts_median_7days_booking
 * @property integer $pts_24hours_quotes
 * @property integer $pts_24hours_booking
 * @property double $pts_ledger_balance
 * @property double $pts_wallet_balance
 */
class PartnerStats extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pts_agt_id, pts_max_7days_quotes, pts_min_7days_quotes, pts_total_7days_quotes, pts_max_7days_booking, pts_min_7days_booking, pts_total_7days_booking, pts_24hours_quotes, pts_24hours_booking', 'numerical', 'integerOnly'=>true),
			array('pts_median_7days_quotes, pts_median_7days_booking, pts_ledger_balance, pts_wallet_balance', 'numerical'),
			array('pts_created_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pts_id, pts_agt_id, pts_created_date, pts_max_7days_quotes, pts_min_7days_quotes, pts_total_7days_quotes, pts_median_7days_quotes, pts_max_7days_booking, pts_min_7days_booking, pts_total_7days_booking, pts_median_7days_booking, pts_24hours_quotes, pts_24hours_booking, pts_ledger_balance, pts_wallet_balance', 'safe', 'on'=>'search'),
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
			'pts_id' => 'Pts',
			'pts_agt_id' => 'Pts Agt',
			'pts_created_date' => 'Pts Created Date',
			'pts_max_7days_quotes' => 'Pts Max 7days Quotes',
			'pts_min_7days_quotes' => 'Pts Min 7days Quotes',
			'pts_total_7days_quotes' => 'Pts Total 7days Quotes',
			'pts_median_7days_quotes' => 'Pts Median 7days Quotes',
			'pts_max_7days_booking' => 'Pts Max 7days Booking',
			'pts_min_7days_booking' => 'Pts Min 7days Booking',
			'pts_total_7days_booking' => 'Pts Total 7days Booking',
			'pts_median_7days_booking' => 'Pts Median 7days Booking',
			'pts_24hours_quotes' => 'Pts 24hours Quotes',
			'pts_24hours_booking' => 'Pts 24hours Booking',
			'pts_ledger_balance' => 'Pts Ledger Balance',
			'pts_wallet_balance' => 'Pts Wallet Balance',
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

		$criteria=new CDbCriteria;

		$criteria->compare('pts_id',$this->pts_id);
		$criteria->compare('pts_agt_id',$this->pts_agt_id);
		$criteria->compare('pts_created_date',$this->pts_created_date,true);
		$criteria->compare('pts_max_7days_quotes',$this->pts_max_7days_quotes);
		$criteria->compare('pts_min_7days_quotes',$this->pts_min_7days_quotes);
		$criteria->compare('pts_total_7days_quotes',$this->pts_total_7days_quotes);
		$criteria->compare('pts_median_7days_quotes',$this->pts_median_7days_quotes);
		$criteria->compare('pts_max_7days_booking',$this->pts_max_7days_booking);
		$criteria->compare('pts_min_7days_booking',$this->pts_min_7days_booking);
		$criteria->compare('pts_total_7days_booking',$this->pts_total_7days_booking);
		$criteria->compare('pts_median_7days_booking',$this->pts_median_7days_booking);
		$criteria->compare('pts_24hours_quotes',$this->pts_24hours_quotes);
		$criteria->compare('pts_24hours_booking',$this->pts_24hours_booking);
		$criteria->compare('pts_ledger_balance',$this->pts_ledger_balance);
		$criteria->compare('pts_wallet_balance',$this->pts_wallet_balance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerStats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getbyPartnerId($agtid)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('pts_agt_id', $agtid);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public static function UpdateLedgerBalance($defaultDays = 3, $intervalOption='DAY')
	{
		$param	 = ['days' => $defaultDays];
		$sql	 = "SELECT DISTINCT adt.adt_trans_ref_id		
					FROM account_trans_details adt
					INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 15
					WHERE
						adt.adt_modified BETWEEN DATE_SUB(NOW(), INTERVAL :days {$intervalOption}) AND NOW() AND adt.adt_type = 3
						AND act.act_active = 1 AND adt.adt_active = 1 AND adt.adt_trans_ref_id IS NOT NULL";
		
		Logger::writeToConsole($sql);
		
		$result = DBUtil::query($sql, DBUtil::SDB(), $param);
		
		foreach ($result as $row)
		{
			try
			{
				$agtid = $row['adt_trans_ref_id'];
				
				Logger::writeToConsole("AgtId: " . $agtid);
				
				$param = ['agtid' => $agtid];
				$sql1	 = "SELECT IFNULL(sum(adt_amount),0) outstanding
							FROM   account_trans_details 
							INNER JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
							WHERE  account_trans_details.adt_active = 1 AND account_transactions.act_active=1 AND account_transactions.act_date >= '2021-04-01 00:00:00' 
								AND account_trans_details.adt_status = 1
								AND account_trans_details.adt_ledger_id = 15 AND account_trans_details.adt_type = 3
								AND account_trans_details.adt_trans_ref_id =:agtid";
				
				Logger::writeToConsole($sql1);
				
				$result1	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), $param);
				
				Logger::writeToConsole("Result1: " . $result1);

				$patnerStatsModel = PartnerStats::model()->getbyPartnerId($agtid);
				if (!$patnerStatsModel)
				{
					$patnerStatsModel				 = new PartnerStats();
					$patnerStatsModel->pts_agt_id	 = $agtid;
				}
				$patnerStatsModel->pts_ledger_balance = $result1 | 0;
				$patnerStatsModel->save();
				
				Logger::writeToConsole("UpdateLedgerBalance SAVED ");
			}
			catch (Exception $e)
			{
				Logger::exception($e);
			}
		}
	}
	
	public static function UpdateWalletBalance($defaultDays = 3, $intervalOption = 'DAY')
	{
		$param	 = ['days' => $defaultDays];
		$sql	 = "SELECT DISTINCT adt.adt_trans_ref_id		
					FROM account_trans_details adt
					INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 49
					WHERE
						adt.adt_modified BETWEEN DATE_SUB(NOW(), INTERVAL :days {$intervalOption}) AND NOW()
						AND act.act_active = 1 AND adt.adt_active = 1 AND adt.adt_trans_ref_id IS NOT NULL";
		
		Logger::writeToConsole($sql);
						
		$result = DBUtil::query($sql, DBUtil::SDB(), $param);
		
		foreach ($result as $row)
		{
			try
			{
				$agtid = $row['adt_trans_ref_id'];
				
				Logger::writeToConsole("AgtId: " . $agtid);
				
				$param = ['agtid' => $agtid];
				$sql1	 = "SELECT IFNULL(sum(adt_amount) * -1,0) outstanding
							FROM   account_trans_details 
							INNER JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
							WHERE  account_trans_details.adt_active = 1 AND account_transactions.act_active=1
								AND account_trans_details.adt_status = 1
								AND account_trans_details.adt_ledger_id = 49
								AND account_trans_details.adt_trans_ref_id =:agtid";
				
				Logger::writeToConsole($sql1);
				
				$result1	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), $param);
				
				Logger::writeToConsole("Result1: " . $result1);

				$patnerStatsModel = PartnerStats::model()->getbyPartnerId($agtid);
				if (!$patnerStatsModel)
				{
					$patnerStatsModel				 = new PartnerStats();
					$patnerStatsModel->pts_agt_id	 = $agtid;
				}
				$patnerStatsModel->pts_wallet_balance = $result1 | 0;
				$patnerStatsModel->save();
				
				Logger::writeToConsole("UpdateWalletBalance SAVED ");
			}
			catch (Exception $e)
			{
				Logger::exception($e);
			}
		}
	
	}

	public static function getBalance($agtId)
	{
		$param	 = ['agtId' => $agtId];
		$sql	 = "SELECT * FROM partner_stats WHERE pts_agt_id =:agtId";
		$result = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}
}
