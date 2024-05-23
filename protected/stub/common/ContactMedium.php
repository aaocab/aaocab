<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of ContactMedium
 *
 * @author Suvajit
 */
class ContactMedium extends Person
{
    const TYPE_EMAIL = 1;
    const TYPE_PHONE = 2;

    /**
     * 1 - Email
     * 2 - Phone
     */
    public $type;
    public $vndId;

    /**
     * SocialAuth::Eml_*
     */
    public $source;

    public $isVerified;
    public $isPrimary;
    public $contactTempDetails;
    /** @var \Stub\common\ContactMedium $contactTempModel  */
    public $contactTempModel;
    /**
     * This function for initializing the contact model
     * @param \Contact $model
     * @return \Contact
     */
    public function init(\Contact $model = null)
    {
        if ($model == null)
        {
            $model = new \Contact();
        }

        $model->ctt_first_name         = $this->firstName;
        $model->ctt_last_name          = $this->lastName;
        $model->ctt_aadhaar_no         = $this->documents->adhaar->refValue;
        $model->ctt_license_issue_date = empty($this->documents->Licence->issueDate) ? null : $this->documents->Licence->issueDate;
        $model->ctt_license_exp_date   = empty($this->documents->Licence->expiryDate) ? null : $this->documents->Licence->expiryDate;
        $model->ctt_license_no         = empty($this->documents->Licence->refValue) ? null : $this->documents->Licence->refValue;
        $model->ctt_created_date       = new \CDbExpression('now()');

        return $model;
    }

    /**
     * This function returns the contact model
     * @return type
     */
    public function getMedium()
    {
        $model = $this->init();

        $model->vndId = empty($this->vndId) ? 0 : $this->vndId;
        //Contact Details
        $contacts     = [];
        array_push($contacts, $this->getContactEmail());
        array_push($contacts, $this->getContactPhone());

        $model->contactDetails = $contacts;

        //Contact temp Model 
        $contactTempModel          = $this->getTempContactModel();
        $model->contactTempDetails = $contactTempModel->attributes;

        return $model;
    }

    /**
     * This function is used for getting the new driver model
     * @return \Drivers
     */
    public function getDriverModel()
    {
        $driverModel             = new \Drivers();
        $driverModel->drv_name   = $this->firstName . $this->lastName;
        $driverModel->drv_active = 1;

        return $driverModel;
    }

    /**
     * This function is used for getting the contact email model
     * @param type $data
     * @return \ContactPhone
     */
    public function getContactPhone($phoneNo = null, $ext = null, $isConsider = 0)
    {
        $phModel = new \ContactPhone();

        foreach ($phModel as $value)
        {
            $value->mediumType = 2;

            $value->phn_phone_no           = $this->primaryContact->number;
            $value->phn_phone_country_code = $this->primaryContact->code;
            if ($isConsider)
            {
                $value->phn_phone_no           = $phoneNo;
                $value->phn_phone_country_code = $ext;
            }
            if (empty($value->phn_otp))
            {
                $value->phn_otp = rand(1000, 9999);
            }

			$value->phn_is_verified	 = 0;
			$value->phn_is_primary	 = 1;
            $value->phn_active = 1;
        }

        return $value;
    }

/**
     * This function is used for getting contact email
     * @param type $data		-	Received contact details
     * @return \ContactEmail
     */
    public function getContactEmail($email = null, $isConsider = 0, $provider = 1)
    {
        $contactEmailModel = new \ContactEmail();

        foreach ($contactEmailModel as $value)
        {
            $value->mediumType = $provider;

            if ($isConsider)
            {
                $value->eml_email_address = $email;
            }
            else
            {
                $value->eml_email_address = $this->email;
            }

            $value->eml_is_primary = 1;
            if ($provider > 1)
            {
                $value->eml_is_verified = 1;
            }
            else
            {
                $value->eml_is_verified = 0;
            }
            $value->eml_active = 1;
        }

        return $value;
    }


    public function getEmailModel($email, $provider = 1)
    {
        return $this->getContactEmail($email, 1, $provider);
    }

    public function getPhoneModel($phNo, $ext)
    {
        return $this->getContactPhone($phNo, $ext, 1);
    }
}
