<?php

namespace Stub\common;

class Preference
{
    // Booking Trail
    public $level, $activityFirstDate, $activityFirstTime, $activityLastDate, $activityLastTime, $activityCompletionDate, $activityCompletionTime, $activityReasonId, $activityReason, $activityValue, $activityCode;
	//Booking Pref
	public $activityCriticalScore;
    /** @var \Stub\common\Teams $teamList*/
    public $teamList;
	
	/** @var \Stub\common\Person $assignedUser*/
    public $assignedUser;
	
	

    /** @var Booking Trail $model */
    public function setData($model, $criType)
	{
		switch ((int) $criType)
		{
			case 37:
			$this->level = (int) $model['btr_escalation_level'];
			$rows =  explode(",",$model['tea_id']);
			foreach ($rows as $row)
			{
				$obj	 = new \Stub\common\Teams();
				$obj->setData($row);
				$this->teamList[]	 = $obj;
			}
			$this->activityFirstDate  = date("Y-m-d", strtotime($model['btr_escalation_fdate']));
			$this->activityFirstTime = date("H:i:s", strtotime($model['btr_escalation_fdate']));
			$this->activityLastDate   = date("Y-m-d", strtotime($model['btr_escalation_ldate']));
			$this->activityLastTime = date("H:i:s", strtotime($model['btr_escalation_ldate']));
			$this->assignedUser	= new \Stub\common\Person();
			$this->assignedUser->setAdminData($model['btr_escalation_assigned_lead']);
			$this->activityCompletionDate = date("Y-m-d", strtotime($model['completion_time']));
		    $this->activityCompletionTime = date("H:i:s", strtotime($model['completion_time']));
			break;

			
			case 225:
			$this->activityFirstDate      = date("Y-m-d", strtotime($model['btr_manual_assign_date']));
			$this->activityFirstTime     = date("H:i:s", strtotime($model['btr_manual_assign_date']));
			$this->activityCriticalScore  = (float)$model['bkg_critical_score'];
			$this->activityCompletionDate = date("Y-m-d", strtotime($model['completion_time']));
		    $this->activityCompletionTime = date("H:i:s", strtotime($model['completion_time']));
			break;
		
		   case 224:
			$this->level = (int)$model['bpr_assignment_level'];
			$this->assignedUser	= new \Stub\common\Person();
			$this->assignedUser->setAdminData($model['bpr_assignment_id']);
			$this->activityFirstDate    = date("Y-m-d", strtotime($model['bpr_assignment_fdate']));
			$this->activityFirstTime   = date("H:i:s", strtotime($model['bpr_assignment_fdate']));
			$this->activityLastDate     = date("Y-m-d", strtotime($model['bpr_assignment_ldate']));
			$this->activityLastTime     = date("H:i:s", strtotime($model['bpr_assignment_ldate']));
			$this->activityCompletionDate = date("Y-m-d", strtotime($model['completion_time']));
		    $this->activityCompletionTime = date("H:i:s", strtotime($model['completion_time']));
			break;	
			
			case 226:
			$this->activityFirstDate      = date("Y-m-d", strtotime($model['btr_critical_assign_date']));
			$this->activityFirstTime     = date("H:i:s", strtotime($model['btr_critical_assign_date']));
			$this->activityCriticalScore  = (float)$model['bkg_critical_score'];
			$this->activityCompletionDate = date("Y-m-d", strtotime($model['completion_time']));
		    $this->activityCompletionTime = date("H:i:s", strtotime($model['completion_time']));
			break;
		
		    case 251:
			$this->activityCompletionDate = date("Y-m-d", strtotime($model['completion_time']));
		        $this->activityCompletionTime = date("H:i:s", strtotime($model['completion_time']));
			break;
		    case 252:
			$this->activityFirstDate      = date("Y-m-d", strtotime($model['btr_auto_cancel_create_date']));
			$this->activityFirstTime     = date("H:i:s", strtotime($model['btr_auto_cancel_create_date']));
			$this->activityCompletionDate = date("Y-m-d", strtotime($model['completion_time']));
		    $this->activityCompletionTime = date("H:i:s", strtotime($model['completion_time']));
			$this->activityReasonId       = (int)$model['btr_auto_cancel_reason_id'];
			$cModel =\CancelReasons::model()->getById($model['btr_auto_cancel_reason_id']); 
			$this->activityReason       =  $cModel['cnr_reason'];
			$this->activityValue        = (int)$model['btr_auto_cancel_value'];
			break;
			
		}
		return $this;
	}
	

}
