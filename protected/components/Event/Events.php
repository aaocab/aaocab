<?php

namespace components\Event;

/**
 * Description  Event
 *
 * @author Dev
 * 
 * @property integer $event_id 
 * @property string $event_name
 * @property string $event_desc
 * @property string $event_sequence
 * @property string $event_schedule_sequence
 * @property integer $event_module
 * @property string $event_status
 *
 */
class Events
{

	public $event_id;
	public $event_name;
	public $event_desc;
	public $event_sequence;
	public $event_schedule_sequence;
	public $event_module;
	public $event_status;

	/** @var \Beans\common\Template[] $template */
	public $template;

	/**
	 * This function is get template related data for any particular event
	 * @param string $eventId
	 * @param string $platform
	 * @param array $langCode
	 * @return Object class
	 */
	public function getEventData($eventId, $platform = null, $langCode = [\TemplateMaster::LANG_EN_US_CODE,\TemplateMaster::LANG_EN_GB_CODE])
	{
		$platformCode = \MessageEventMaster::getPlatformCode($eventId, $platform);
		return self::populatePlatformData($eventId, $platformCode, $langCode);
	}

	/**
	 * This function is populate Platform based data  for any given event
	 * @param string $eventId
	 * @param string $platformCode
	 * @param array $langCode
	 * @return Object class
	 */
	public static function populatePlatformData($eventId, $platformCode, $langCode = [\TemplateMaster::LANG_EN_US_CODE,\TemplateMaster::LANG_EN_GB_CODE])
	{
		$obj = [];
		foreach ($platformCode as $code)
		{
			$detailsMaster	 = \TemplateMaster::getDetails($eventId, $code['tpm_platform']);
			$objMaster		 = [];
			foreach ($detailsMaster as $details)
			{
				if (in_array($details['tpm_language'], $langCode))
				{
					$object								 = new \Beans\common\Template;
					$object->id							 = $details['tpm_id'];
					$object->content					 = $details['tpm_content'];
					$object->variables					 = $details['tpm_variables'];
					$object->filename					 = $details['tpm_filename'];
					$object->name						 = $details['tpm_name'];
					$object->title						 = $details['tpm_title'];
					$object->dtlId						 = $details['tpm_provider_code'];
					$object->provider_type				 = $details['tpm_provider_type'];
					$object->language					 = \TemplateMaster::getLangCodeById($details['tpm_language']);
					$objMaster[$details['tpm_language']] = $object;
				}
			}
			$obj[$code['tpm_platform']] = $objMaster;
		}
		return $obj;
	}

	/**
	 * This function is populate event based data for any given event id
	 * @param string $eventId
	 * @param class $this
	 */
	public function populateEventData($eventId)
	{
		$result							 = \MessageEventMaster::getDetails($eventId);
		$this->event_id					 = (int) $result['mem_id'];
		$this->event_name				 = $result['mem_name'];
		$this->event_desc				 = $result['mem_desc'];
		$this->event_sequence			 = $result['mem_sequence'];
		$this->event_schedule_sequence	 = $result['mem_schedule_sequence'];
		$this->event_module				 = $result['mem_module'];
		$this->event_status				 = (int) $result['mem_status'];
	}

	public static function getEventDetails($eventId, $platform = null)
	{
		$obj			 = new Events();
		$obj->template	 = $obj->getEventData($eventId, $platform);
		$obj->populateEventData($eventId);
		$objData		 = \Filter::removeNull($obj);
		return $objData;
	}

}
