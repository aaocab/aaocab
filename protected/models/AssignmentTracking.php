<?php

/**
 * This is the model class for table "assignment_tracking".
 *
 * The followings are the available columns in table 'assignment_tracking':
 * @property integer $ast_id
 * @property integer $ast_bkg_id
 * @property integer $ast_bcb_id
 * @property string $ast_allocate_date
 * @property integer $ast_allocated_by
 * @property integer $ast_assigned_by
 * @property integer $ast_requested_by
 * @property integer $ast_allocated_to
 * @property double $ast_critical_score
 * @property integer $ast_critical_flag
 * @property integer $ast_net_base_amount
 * @property integer $ast_gozo_amount
 * @property integer $ast_scq_id
 */
class AssignmentTracking extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'assignment_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ast_bkg_id, ast_bcb_id', 'required'),
			array('ast_bkg_id, ast_bcb_id, ast_allocated_by, ast_assigned_by, ast_requested_by, ast_allocated_to, ast_critical_flag, ast_net_base_amount, ast_gozo_amount, ast_scq_id', 'numerical', 'integerOnly'=>true),
			array('ast_critical_score', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ast_id, ast_bkg_id, ast_bcb_id, ast_allocate_date, ast_allocated_by, ast_assigned_by, ast_requested_by, ast_allocated_to, ast_critical_score, ast_critical_flag, ast_net_base_amount, ast_gozo_amount, ast_scq_id', 'safe', 'on'=>'search'),
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
			'ast_id' => 'Ast',
			'ast_bkg_id' => 'Ast Bkg',
			'ast_bcb_id' => 'Ast Bcb',
			'ast_allocate_date' => 'Ast Allocate Date',
			'ast_allocated_by' => 'Ast Allocate By',
			'ast_assigned_by' => 'Ast Assigned By',
			'ast_requested_by' => 'Ast Requested By',
			'ast_allocated_to' => 'Ast Allocated To',
			'ast_critical_score' => 'Ast Critical Score',
			'ast_critical_flag' => 'Ast Critical Flag',
			'ast_net_base_amount' => 'Ast Net Base Amount',
			'ast_gozo_amount' => 'Ast Gozo Amount',
			'ast_scq_id' => 'Ast Scq',
			'ast_vnd_id' => 'Ast Vendor Id'
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

		$criteria->compare('ast_id',$this->ast_id);
		$criteria->compare('ast_bkg_id',$this->ast_bkg_id);
		$criteria->compare('ast_bcb_id',$this->ast_bcb_id);
		$criteria->compare('ast_allocate_date',$this->ast_allocate_date,true);
		$criteria->compare('ast_allocated_by',$this->ast_allocated_by);
		$criteria->compare('ast_assigned_by',$this->ast_assigned_by);
		$criteria->compare('ast_requested_by',$this->ast_requested_by);
		$criteria->compare('ast_allocated_to',$this->ast_allocated_to);
		$criteria->compare('ast_critical_score',$this->ast_critical_score);
		$criteria->compare('ast_critical_flag',$this->ast_critical_flag);
		$criteria->compare('ast_net_base_amount',$this->ast_net_base_amount);
		$criteria->compare('ast_gozo_amount',$this->ast_gozo_amount);
		$criteria->compare('ast_scq_id',$this->ast_scq_id);
		$criteria->compare('ast_vnd_id',$this->ast_vnd_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AssignmentTracking the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 
	 * @param type $bcb_id
	 * @param type $vndId
	 * @param type $assignMode
	 */
	public static function createRequest($bcbId, $userInfo)
	{
		$success	 = true;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$assign_by = "";

			$bcbmodel	 = BookingCab::model()->findByPk($bcbId);
			$bookingIds	 = $bcbmodel->bcb_bkg_id1;
			$bkgIdarr	 = explode(',', $bcbmodel->bcb_bkg_id1);
			foreach ($bkgIdarr as $bkgId)
			{
				$model				 = new AssignmentTracking();
				$model->ast_bkg_id	 = $bkgId;
				$model->ast_bcb_id	 = $bcbId;
				$vendorId			 = $bcbmodel->bcb_vendor_id;

				$bookingmodel = Booking::model()->findByPk($bkgId);

				$model->ast_critical_score	 = $bookingmodel->bkgPref->bkg_critical_score;
				$bprAssignmentData			 = BookingPref::showAssignemntData($bkgId);
				$csrId						 = $bprAssignmentData['bpr_assignment_id'];
				$level						 = $bprAssignmentData['bpr_assignment_level'];
				if ($csrId != 0)
				{
					$model->ast_allocated_to	 = $csrId;
					$model->ast_assignment_level = $level;
					$model->ast_allocate_date	 = $bookingmodel->bkgPref->bpr_assignment_fdate;
					$scqData					 = ServiceCallQueue::getScqIdByBookingId($bkgId, $csrId);
					$model->ast_scq_id			 = $scqData['scq_id'];
					$model->ast_allocated_by	 = $scqData['scq_created_by_uid'];
				}
				if ($userInfo->userType == 4)
				{
					$assign_by				 = $userInfo->userId;
					$model->ast_assigned_by	 = $assign_by;
				}
				$scqManualAssignRequestAdmId = ServiceCallQueue::getScqDetailsManualAssignApproval($bkgId, $vendorId);
				if ($scqManualAssignRequestAdmId != "")
				{
					$model->ast_requested_by = $scqManualAssignRequestAdmId;
				}

				$criticalFlag	 = $bookingmodel->bkgPref->bkg_critical_assignment;
				$manualFlag		 = $bookingmodel->bkgPref->bkg_manual_assignment;
				if ($criticalFlag == 0 && $manualFlag == 0)
				{
					$flag = 0;
				}
				else if ($criticalFlag == 1)
				{
					$flag = 2;
				}
				else
				{
					$flag = 1;
				}
				$model->ast_critical_flag	 = $flag;
				$bookingInvoice				 = BookingInvoice::model()->getByBookingID($bkgId);
				$model->ast_gozo_amount		 = $bookingInvoice->bkg_gozo_amount;
				$model->ast_net_base_amount	 = $bookingInvoice->bkg_base_amount;
				$model->ast_vnd_id			 = $vendorId;
				if (!$model->save())
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
				}				
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($e);
			$success = false;
		}
		return $success;
	}
}
