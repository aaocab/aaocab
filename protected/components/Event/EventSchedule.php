<?php

namespace components\Event;

/**
 * Description  EventSchedule
 *
 * @author Dev
 * 
 * @property integer $ref_type 
 * @property string $ref_id
 * @property string $event_id
 * @property string $schedule_time
 * @property integer $addtional_data
 * @property string $remarks
 * @property string $event_sequence
 *
 */
class EventSchedule
{

	public $ref_type;
	public $ref_id;
	public $event_id;
	public $event_status;
	public $schedule_time;
	public $addtional_data;
	public $remarks;
	public $event_sequence;
	public $event_schedule;

	/**
	 * This function is set  event Schedule 
	 * @param string $refId
	 * @param string $refType
	 * @param string $eventId
	 * @param string $remarks
	 * @param string $isSchedule
	 * @param string $additionalData
	 * @param string $time
	 * @param string $sequence
	 * @param return object
	 */
	public static function setData($refId, $refType, $eventId, $remarks, $isSchedule, $additionalData = null, $time = 10, $sequence = null)
	{
		$object					 = new \stdClass();
		$object->ref_id			 = $refId;
		$object->ref_type		 = $refType;
		$object->event_id		 = $eventId;
		$object->remarks		 = $remarks;
		$object->event_schedule	 = $isSchedule;
		$object->addtional_data	 = $additionalData;
		$object->schedule_time	 = $time;
		$object->event_sequence	 = $sequence;
		$object->event_status	 = 0;
		return $object;
	}

	/**
	 * This function is used to check if it is matched the platform or not and return the matched time 
	 * @param string $strSequence
	 * @param string $matched
	 * @param return int
	 */
	public static function isEventSchedule($strSequence, $matched)
	{
		$return	 = 0;
		$arr	 = json_decode($strSequence, true);
		foreach ($arr as $val)
		{
			if ($val['type'] == $matched)
			{
				$return = $val['time'];
				break;
			}
		}
		return $return;
	}

}
