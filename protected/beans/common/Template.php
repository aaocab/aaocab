<?php

namespace Beans\common;

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description  Template
 *
 * @author Dev
 * 
 * @property integer $id 
 * @property string $name
 * @property string $title
 * @property string $eventId
 * @property string $entityType
 * @property string $content
 * @property string $variables
 * @property string $sender
 * @property string $language
 * @property string $status
 * @property string $dtlId
 * @property string $provider_type
 * @property integer $policy_reminder
 *
 */
class Template
{

	public $id;
	public $name;
	public $title;
	public $eventId;
	public $entityType;
	public $content;
	public $variables;
	public $sender;
	public $language;
	public $status;
	public $dtlId;
	public $provider_type;
	public $policy_reminder;

	public static function setTemplateData($eventId, $sequenceCode)
	{
		$detailsMaster	 = \TemplateMaster::getDetails($eventId, $sequenceCode);
		$obj			 = [];
		foreach ($detailsMaster as $details)
		{
			$object							 = new \stdClass();
			$object->template_id			 = $details['tpm_id'];
			$object->template_content		 = $details['tpm_content'];
			$object->template_name			 = $details['tpm_name'];
			$object->template_title			 = $details['tpm_title'];
			$object->template_variables		 = $details['tpm_variables'];
			$object->template_dtlId			 = $details['tpm_provider_code'];
			$object->template_provider_type	 = $details['tpm_provider_type'];
			$object->template_lang			 = \TemplateMaster::getLangCodeById($details['tpm_language']);
			$obj[]							 = $object;
		}
		return $obj;
	}

}

?>