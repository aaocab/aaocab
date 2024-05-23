<?php

namespace Stub\common;

class ShiftRequest
{

    public $status;
    public $loginConfirmDate;
    public $loginConfirmTime;
    public $loggedOutType;

    /** @var \Stub\common\Location $location */
    public $location;

    public function getModel(\AdminOnoff $model = null)
    {
        if ($model == null)
        {
            $model = new \AdminOnoff();
        }
        $model->ado_status   = $this->status;
        $model->ado_time     = \DBUtil::getCurrentTime();
        $model->ado_lat      = $this->location->coordinates->latitude;
        $model->ado_lng      = $this->location->coordinates->longitude;
        $model->ado_admin_id = \UserInfo::getUserId();
        if ($this->loginConfirmDate != '' && $this->loginConfirmTime != '')
        {
            $model->ado_login_confirm_time = \DBUtil::getCurrentTime();
        }
        $model->ado_logged_out_type = $this->loggedOutType;
        return $model;
    }

    public function setModelData($id)
    {
        $this->status = (int) \AdminOnoff::model()->chkShiftPresentStatus($id);
        return $this;
    }

}
