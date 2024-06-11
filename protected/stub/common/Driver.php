<?php

namespace Stub\common;

class Driver extends Person
{

	public $id;
	public $code;
	public $firstName;
	public $lastName;
	public $email;
	public $city;
	public $state;
	public $zip;
	public $name;
	public $approveStatus;
	public $overallRating;
	public $markBadCount;
	public $createDate;
	public $createTime;
	public $isEmlVerified;
	public $contactId;
	public $mobile_number;
	public $image;
	public $rating, $totalTrips;

	/** @var \Stub\common\Person $profile */
	//public $profile;

	/** @var \Stub\common\Document $adhaar */
	public $adhaar;

	/** @var \Stub\common\Document $license */
	public $license;
	public $dob;
	public $issueauthstate;
	public $location;
	public $stateLoc;
	public $issueAuth;

	public function init(\Drivers $model = null)
	{
		if ($model == null)
		{
			$model				 = new \Drivers();
			$model->drv_created	 = new \CDbExpression('now()');
		}
		else
		{
			$model->drv_modified = new \CDbExpression('now()');
		}

		$model->drv_name	 = $this->firstName . $this->lastName;
		$model->drv_active	 = 1;

		return $model;
	}

	/**
	 * This function is used for getting the model
	 */
	public function getModel($model = null)
	{
		$drvModel = $this->init($model);
		return $drvModel;
	}

	/**
	 * 
	 * @param \Drivers $model
	 */
	public function setData(\Drivers $model = null)
	{
		if ($model == null)
		{
			$model = new \Drivers();
		}
		$this->id						 = (int) $model->drv_id;
		$this->code						 = $model->drv_code;
		$this->name						 = $model->drv_name;
		$this->approveStatus			 = (int) $model->drv_approved;
		$this->overallRating			 = \DriverStats::fetchRating($this->id);
		$this->markBadCount				 = $model->drv_mark_driver_count;
		$this->createDate				 = date("Y-m-d", strtotime($model->drv_created));
		$this->createTime				 = date("H:i:s", strtotime($model->drv_created));
		$arr							 = \Contact::model()->getContactDetails($model->drv_contact_id);
		$this->email					 = $arr['eml_email_address'];
		$this->primaryContact->code		 = (int) $arr['phn_phone_country_code'];
		$this->primaryContact->number	 = $arr['phn_phone_no'];
	}

	public function setDriverName(\Drivers $model = null)
	{
		if ($model == null)
		{
			$model = new \Drivers();
		}
		$this->name = $model->drv_name;
	}

	public function fillData($row, $tripId = null)
	{


		$this->id			 = (int) $row['drv_id'];
		$this->linkId		 = (int) $row["vdrv_id"];
		$this->name			 = ($row["drv_name"] == "" ? $row['ctt_first_name'] : $row["drv_name"]);
		$this->code			 = $row['drv_code'];
		$this->firstName	 = $row['ctt_first_name'];
		$this->lastName		 = $row["ctt_last_name"];
		$this->approveStatus = (int) $row["drv_approved"];
		$this->markBadCount	 = $row["drv_mark_driver_count"];
		$this->createDate	 = date("Y-m-d", strtotime($row["drv_created"]));
		$this->createTime	 = date("H:i:s", strtotime($row["drv_created"]));
		$this->city			 = $row["ctt_city"];
		$cityModel			 = new \Stub\common\Cities();

		$this->location = $cityModel->getIdName($this->city);

		$this->documentUpload				 = $row["documentUpload"];
		$this->email						 = $row["drv_email"];
		$this->isEmlVerified				 = (int) $row["isEmlVerified"];
		$this->primaryContact->code			 = (int) ($row['drv_phone_code'] == "" ? '91' : $row['drv_phone_code']);
		$this->primaryContact->number		 = $row['drv_phone'];
		$this->primaryContact->isVerified	 = (int) $row["isPhVerified"];
		if ($tripId != null)
		{
			$chkAvailabily		 = \Drivers::checkDrvAvialability($tripId, $this->id);
			$this->isAvailable	 = ($chkAvailabily > 0 ? 0 : 1);
		}
	}

	public function fillContactData($row)
	{
		$this->id		 = $row["cr_is_driver"];
		$this->code		 = $row["drv_code"];
		$this->license	 = $row["ctt_license_no"];
	}

	public function getList($dataArr, $tripId = null)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Driver();
			$obj->fillData($row, $tripId);
			$this->dataList[]	 = $obj;
		}
	}

	public function setDriverdata($row)
	{
		$this->name						 = $row['driver_name'];
		$this->primaryContact->code		 = (int) ($row['drv_phone_code'] == "" ? '91' : $row['drv_phone_code']);
		$this->primaryContact->number	 = $row['driver_phone'];
	}

	/**
	 * @param object $model
	 * @param boolean $isMask
	 * @return $this
	 */
	public function setModelData($model, $isMask = false)
	{
		if ($model == null)
		{
			return false;
		}
		$this->name = $model->drv_name;
		//$number					 = ($isMask) ? \Yii::app()->params['customerToDriver'] : (int) $model->drv_phone;
		if (!$model instanceof Booking && isset($model->bkg_id) && $model->bkg_id > 0)
		{
			$bkgModel = \Booking::model()->findByPk($model->bkg_id);
		}
		if ($bkgModel->bkg_status == \Booking::STATUS_PROCESSED && \BookingPref::isDriverDetailsViewable($bkgModel))
		{
			$orignalNumber			 = \Filter::processDriverNumber($model->drv_phone, $bkgModel->bkg_agent_id);
			$number					 = \BookingPref::getDriverNumber($bkgModel, $orignalNumber);
			$this->contact->code	 = strval(91);
			$this->contact->number	 = (int) strval($number);
		}
		if ($bkgModel->bkg_status != \Booking::STATUS_COMPLETED)
		{
			$this->rating = (float)\DriverStats::fetchRating($bkgModel->bkgBcb->bcb_driver_id);
		}
		$this->image = $model->profileImage;
		return $this;
	}

	public function setContactModel()
	{
		$model							 = parent::init();
		$model->drvContact				 = new \Drivers();
		$model->drvContact->drv_name	 = $model->ctt_first_name . "" . $model->ctt_last_name;
		$model->drvContact->drv_active	 = 1;
		$model->drvContact->drv_created	 = new \CDbExpression('now()');
		$model->drvContact->drv_phone	 = $model->contactPhones[0]->phn_phone_no;
		return $model;
	}

	public function setProfileData($driverModel, $contactModel)
	{
		$this->id		 = (int) $driverModel->drv_id;
		$this->contactId = (int) $contactModel->ctt_id;

		$this->firstName = $contactModel->ctt_first_name;
		$this->lastName	 = $contactModel->ctt_last_name;

		$this->profilePic = \AttachmentProcessing::ImagePath($contactModel->ctt_profile_path); //\Users::getImageUrl($contactModel->ctt_profile_path);


		$docModels = \Document::documentType();

		foreach ($docModels as $doc)
		{
			$docModel	 = new \Stub\common\Document();
			$document	 = "Document_" . $doc;

			$source					 = "contact";
			$this->documents[$doc]	 = $docModel->setModelData($contactModel, $document, $agreementModel, $source);
		}

		$this->documents = (object) $this->documents;
		#$this->document#$thiss->police->path ="test";
		$arr			 = $contactModel->getContactDetails($contactModel->ctt_id);

		$this->address	 = $arr['ctt_address'];
		$cityModel		 = new \Stub\common\Cities();

		$this->city						 = $arr['ctt_city'];
		$this->location					 = $cityModel->getIdName($arr['ctt_city']);
		$this->state					 = $arr['ctt_state'];
		$stateModel						 = new \Stub\common\State();
		$this->stateLoc					 = $stateModel->getIdName($arr['ctt_state']);
		$this->email					 = $arr['eml_email_address'];
		$this->primaryContact->code		 = (int) $arr['phn_phone_country_code'];
		$this->primaryContact->number	 = $arr['phn_phone_no'];
		$this->dob						 = $driverModel->drv_dob;
		$this->zip						 = $driverModel->drv_zip;
		if ($contactModel->ctt_license_doc_id == null && $contactModel->ctt_license_no != null)
		{
			$this->documents->Licence->refValue		 = $contactModel->ctt_license_no;
			$this->documents->Licence->expiryDate	 = $contactModel->ctt_license_exp_date;
		}
		$authStateModel			 = new \Stub\common\State();
		$this->issueAuthState	 = $authStateModel->getIdName($contactModel->ctt_dl_issue_authority);
	}

	public function getProfileData($data)
	{

		$this->id						 = $data['id'];
		$this->ctt_id					 = $data['contactId'];
		$this->ctt_first_name			 = $data['firstName'];
		$this->ctt_last_name			 = $data['lastName'];
		$this->ctt_address				 = $data['address'];
		$this->ctt_city					 = $data['location']['code'];
		$this->ctt_state				 = $data['stateLoc']['code'];
		$this->ctt_dl_issue_authority	 = $data['issueAuthState']['code'];
		$this->dob						 = $data['dob'];

		$this->ctt_license_no			 = $data['documents']['Licence']['refValue'];
		$this->ctt_license_issue_date	 = $data['documents']['Licence']['issueDate'];
		$this->ctt_license_exp_date		 = $data['documents']['Licence']['expiryDate'];

		$this->zip = $data['zip'];

		return $this;
	}

	public function setDocumentData($data)
	{
		$model->id			 = $data->drv_id;
		$model->docType		 = $data->doc_type;
		$model->docSubType	 = $data->doc_subtype;
		$model->docName		 = $data->doc_name;
		$model->profilePic	 = $data->pic_name;
		return $model;
	}

	public function setDLData(\Drivers $model = null)
	{

		if ($model == null)
		{
			$model = new \Drivers();
		}
		$entityType				 = \UserInfo::TYPE_DRIVER;
		$drvModel				 = \ContactProfile::getEntityById($this->contactId, $entityType);
		$model->drv_id			 = $drvModel['id'];
		$model->drv_contact_id	 = $this->contactId;
		$documents				 = new \Stub\common\Documents;
		$documents->setContactModel($model->drvContact, $this->documents);

		return $model;
	}

	/**
	 * This function is set profile data
	 * @param driver $driverModel
	 * @param contact $$contactModel
	 * @return $this
	 */
	public function setSpicejetData($driverModel, $contactModel)
	{
		$arr				 = $contactModel->getContactDetails($contactModel->ctt_id);
		$this->id			 = (int) $driverModel->drv_id;
		$this->name			 = $contactModel->ctt_first_name;
		$this->mobile_number = $arr['phn_phone_no'];
		$this->profilePic	 = \AttachmentProcessing::ImagePath($contactModel->ctt_profile_path);
		return $this;
	}

	public function allData($row)
	{

		$this->id	 = (int) $row['drv_id'];
		$this->text	 = $row['ctt_first_name'] . ' ' . $row['ctt_last_name'];
		$this->code	 = $row['drv_code'];
	}

	public function getData($dataArr)
	{

		foreach ($dataArr as $row)
		{

			$obj				 = new \Stub\common\Driver();
			$obj->allData($row);
			$this->dataList[]	 = $obj;
		}
		return $this->dataList;
	}

}
