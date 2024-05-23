<?php

namespace components\Event;

/**
 * Description  EventSchedule
 *
 * @author Dev
 * 
 * @property integer $entity_type 
 * @property string $entity_id
 * @property string $ref_type
 * @property string $ref_id
 * @property integer $bkg_id
 * @property string $ext
 * @property string $number
 * @property integer $email 
 * @property string $is_button
 * @property string $app_event_code
 * @property string $sms_type
 * @property string $button_url
 * @property string $email_layout
 * @property string $email_reply_to
 * @property string $email_reply_name
 * @property string $email_type
 * @property string $email_user_type
 * @property string $email_ref_type
 * @property string $email_ref_id
 * @property string $email_log_instance
 * @property string $email_delay_time
 */
class EventReceiver
{
	public $entity_type;
	public $entity_id;
	public $ref_type;
	public $ref_id;
	public $bkg_id;
	public $ext;
	public $number;
	public $email;
	public $is_button;
	public $app_event_code;
	public $sms_type;
	public $button_url;
	public $email_layout;
	public $email_reply_to;
	public $email_reply_name;
	public $email_type;
	public $email_user_type;
	public $email_ref_type;
	public $email_ref_id;
	public $email_log_instance;
	public $email_delay_time;
	public $sms_ref_id;



	
	/**
	 * This function is set receiver data
	 * @param string $entityType
	 * @param string $entityId
	 * @param string $refType
	 * @param string $refId
	 * @param string $bkgId
	 * @param string $ext
	 * @param string $number
	 * @param string $email
	 * @param string $isButton
	 * @param string $appEventCode
	 * @param string $smsType
	 * @param string $button_url
	 * @param string $email_layout
	 * @param string $email_reply_to
	 * @param string $email_reply_name
	 * @param string $email_type
	 * @param string $email_user_type
	 * @param string $email_ref_type
	 * @param string $email_ref_id
	 * @param string $email_log_instance
	 * @param string $email_delay_time
	 * @param string $sms_ref_id
	 * @param return object
	 */
	public static function setData($entityType, $entityId, $refType, $refId, $bkgId, $ext, $number, $email, $isButton, $appEventCode, $smsType, $buttonUrl = '', $emailLayout = null, $emailReplyTo = null, $emailReplyName = null, $emailType = null, $emailUserType = null, $emailRefType = null, $emailRefId = null, $emailLogInstance = null, $emailDelayTime = 0, $smsRefId = null)
	{

		$object						 = new \stdClass();
		$object->entity_type		 = $entityType;
		$object->entity_id			 = $entityId;
		$object->ref_type			 = $refType;
		$object->ref_id				 = $refId;
		$object->bkg_id				 = $bkgId;
		$object->ext				 = $ext;
		$object->number				 = $number;
		$object->email				 = $email;
		$object->is_button			 = $isButton;
		$object->app_event_code		 = $appEventCode;
		$object->sms_type			 = $smsType;
		$object->button_url			 = $buttonUrl;
		$object->email_layout		 = $emailLayout;
		$object->email_reply_to		 = $emailReplyTo;
		$object->email_reply_name	 = $emailReplyName;
		$object->email_type			 = $emailType;
		$object->email_user_type	 = $emailUserType;
		$object->email_ref_type		 = $emailRefType;
		$object->email_ref_id		 = $emailRefId;
		$object->email_log_instance	 = $emailLogInstance;
		$object->email_delay_time	 = $emailDelayTime;
		$object->sms_ref_id			 = $smsRefId;
		return $object;
	}

}
