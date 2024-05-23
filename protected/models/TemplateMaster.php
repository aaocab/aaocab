<?php

use components\Event\EventSchedule;

/**
 * This is the model class for table "template_master".
 *
 * The followings are the available columns in table 'template_master':
 * @property string $tpm_id
 * @property integer $tpm_mem_id
 * @property integer $tpm_entity_type
 * @property string  $tpm_content
 * @property string  $tpm_variables
 * @property integer $tpm_platform
 * @property string $tpm_name
 * @property string $tpm_title
 * @property string $tpm_language
 * @property string $tpm_created_at
 * @property string $tpm_modified_at
 * @property integer $tpm_status
 * @property string $tpm_provider_code
 * @property integer $tpm_provider_type
 */
class TemplateMaster extends CActiveRecord
{

	const SEQ_WHATSAPP		 = "WHATSAPP";
	const SEQ_SMS				 = "SMS";
	const SEQ_EMAIL			 = "EMAIL";
	const SEQ_APP				 = "APP";
	const SEQ_WHATSAPP_CODE	 = 1;
	const SEQ_SMS_CODE		 = 2;
	const SEQ_EMAIL_CODE		 = 3;
	const SEQ_APP_CODE		 = 4;
	const LANG_EN_US			 = "en_US";
	const LANG_HINDI			 = "hi";
	const LANG_TAMIL			 = "ta";
	const LANG_TELEGU			 = "te";
	const LANG_MALAYALAM		 = "ml";
	const LANG_KANNADA		 = "kn";
	const LANG_EN_GB			 = "en_GB";
	const LANG_EN_US_CODE		 = "1";
	const LANG_HINDI_CODE		 = "2";
	const LANG_TAMIL_CODE		 = "3";
	const LANG_TELEGU_CODE	 = "4";
	const LANG_MALAYALAM_CODE	 = "5";
	const LANG_KANNADA_CODE	 = "6";
	const LANG_EN_GB_CODE		 = "7";

	public $lang_code = ["1" => "en_US", "2" => "hi", "3" => "ta", "4" => "te", "5" => "ml", "6" => "kn", "7" => "en_GB"];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'template_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('tpm_mem_id, tpm_content, tpm_name, tpm_created_at, tpm_modified_at', 'required'),
			array('tpm_mem_id, tpm_entity_type, tpm_status', 'numerical', 'integerOnly' => true),
			array('tpm_name,tpm_title', 'length', 'max' => 255),
			array('tpm_language', 'length', 'max' => 100),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('tpm_id, tpm_mem_id, tpm_entity_type, tpm_content, tpm_name,tpm_title, tpm_language, tpm_created_at, tpm_modified_at, tpm_status,tpm_variables,tpm_platform,tpm_provider_code,tpm_provider_type', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tpm_id'			 => 'Template Id',
			'tpm_mem_id'		 => 'Message Event Id Mapped with Message event master',
			'tpm_entity_type'	 => '1=>Customer,2=>Vendor,3 =>Driver,4=>agent',
			'tpm_content'		 => 'Content',
			'tpm_name'			 => 'Name',
			'tpm_title'			 => 'Template title',
			'tpm_language'		 => ' "1" => "en_US", "2" => "hi", "3" => "ta", "4" => "te", "5" => "ml", "6" => "kn"',
			'tpm_created_at'	 => 'when this row was created',
			'tpm_modified_at'	 => 'Modified At',
			'tpm_status'		 => '1=> active,0=>inactive ',
			'tpm_provider_code'	 => 'Template Provider Code',
			'tpm_provider_type'	 => 'EVOLGENCE=>1,SMSCOUNTRY=>2,SMSONEX=>3,SMARTSMS=>4',
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

		$criteria = new CDbCriteria;

		$criteria->compare('tpm_id', $this->tpm_id, true);
		$criteria->compare('tpm_mem_id', $this->tpm_mem_id);
		$criteria->compare('tpm_entity_type', $this->tpm_entity_type);
		$criteria->compare('tpm_content', $this->tpm_content, true);
		$criteria->compare('tpm_name', $this->tpm_name, true);
		$criteria->compare('tpm_title', $this->tpm_title, true);
		$criteria->compare('tpm_language', $this->tpm_language, true);
		$criteria->compare('tpm_created_at', $this->tpm_created_at, true);
		$criteria->compare('tpm_modified_at', $this->tpm_modified_at, true);
		$criteria->compare('tpm_status', $this->tpm_status);
		$criteria->compare('tpm_provider_code', $this->tpm_provider_code);
		$criteria->compare('tpm_provider_type', $this->tpm_provider_type);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TemplateMaster the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to get all the details regarding event/Template master by event id
	 * @param string $eventId
	 * @param string $platforms
	 * @return queryRow
	 */
	public static function getDetails($eventId, $platforms)
	{
		$platform			 = is_string($platforms) ? $platforms : strval($platforms);
		DBUtil::getINStatement($platform, $bindString, $params);
		$params['eventId']	 = $eventId;
		$sql				 = "SELECT 
			mem_id,
			tpm_platform,
			tpm_id,
			tpm_entity_type,
			tpm_content,
			tpm_name,
			tpm_title,
			tpm_filename,
			tpm_language,
			tpm_provider_code,
			tpm_variables,
			tpm_provider_type,
			mem_name,
			mem_sequence,
			mem_schedule_sequence
			FROM template_master
			INNER JOIN message_event_master ON message_event_master.mem_id=template_master.tpm_mem_id
			WHERE 1
			AND template_master.tpm_platform IN ({$bindString})
			AND message_event_master.mem_id=:eventId
			AND message_event_master.mem_status=1 
			AND template_master.tpm_status=1 ORDER BY tpm_platform asc,tpm_language asc ";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used to send notifications  for the event Cab/Driver Assigned
	 * @param string $tripId
	 * @return boolean
	 */
	public static function notifyAssignVendor($tripId)
	{
		$success = false;
		if ($tripId == '')
		{
			goto end;
		}
		$model		 = BookingCab::model()->findByPk($tripId);
		$endTime	 = BookingCab::getEndDateTime($model->bcb_id);
		$details	 = EventMaster::eventDetailsById(5);
		$eventId	 = $details['mem_id'];
		$sequence	 = json_decode($details['mem_sequence'], $associative = true);
		foreach ($sequence as $seq)
		{
			$success = TemplateMaster::processAssignedCabSequence($seq, $model->bcb_id, $model->bcb_vendor_id, $eventId, $endTime);
		}
		end:
		return $success;
	}

	/**
	 * This function is used get all the details related to particular  template id
	 * @param string $templateName
	 * @param string $fieldName
	 * @return string
	 */
	public static function findByTemplateName($templateName, $fieldName = '')
	{
		$sql = "SELECT * FROM template_master WHERE tpm_status = 1 AND tpm_name = '{$templateName}'";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		if ($row)
		{
			return (($fieldName != '' && isset($row[$fieldName])) ? $row[$fieldName] : $row);
		}

		return false;
	}

	/**
	 * This function is used replace the parameter with actual value
	 * @param string $message
	 * @param array $params
	 * @return string
	 */
	public static function getMessage($message, $params)
	{
		if (preg_match_all("~\{\{\s*(.*?)\s*\}\}~", $message, $arr))
		{
			foreach ($arr[1] as $row)
			{
				$message = str_replace('{{' . $row . '}}', $params[$row - 1], $message);
			}
		}
		return $message;
	}

	/**
	 * This function is used to get  full language name
	 * @param type $langCode
	 * @return string
	 */
	public static function languageByLangCode($langCode)
	{
		$lang = "";
		switch ($langCode)
		{
			case TemplateMaster::LANG_EN_US:
				$lang	 = "English(USA)";
				break;
			case TemplateMaster::LANG_TAMIL:
				$lang	 = "Tamil";
				break;
			case TemplateMaster::LANG_HINDI:
				$lang	 = "Hindi";
				break;
			case TemplateMaster::LANG_TELEGU:
				$lang	 = "Telugu";
				break;
			case TemplateMaster::LANG_KANNADA:
				$lang	 = "Kannada";
				break;
			case TemplateMaster::LANG_MALAYALAM:
				$lang	 = "Malayalam";
				break;
			case TemplateMaster::LANG_EN_GB:
				$lang	 = "English(GB)";
				break;
			default:
				$lang	 = "NA";
				break;
		}
		return $lang;
	}

	/**
	 * This function is used to get language code by  language id
	 * @param type $id
	 * @return string
	 */
	public static function getLangCodeById($id)
	{
		$lang = "";
		switch ($id)
		{
			case '1':
				$lang	 = TemplateMaster::LANG_EN_US;
				break;
			case '2':
				$lang	 = TemplateMaster::LANG_HINDI;
				break;
			case '3':
				$lang	 = TemplateMaster::LANG_TAMIL;
				break;
			case '4':
				$lang	 = TemplateMaster::LANG_TELEGU;
				break;
			case '5':
				$lang	 = TemplateMaster::LANG_MALAYALAM;
				break;
			case '6':
				$lang	 = TemplateMaster::LANG_KANNADA;
				break;
			case '7':
				$lang	 = TemplateMaster::LANG_EN_GB;
				break;
			default:
				$lang	 = TemplateMaster::LANG_ENUS;
				break;
		}
		return $lang;
	}

	/**
	 * This function is used replace the text with the value fo the array  
	  $string	 = "Hello ##FirstName## ##LastName## ##FirstName## ##FirstName## welcome";
	  $matches = array('FirstName' => 'John','LastName'	 => 'Smith'	);
	 * @param type $message
	 * @param array $params
	 * @return string
	 */
	public static function replaceVariablesInTemplate($message, $params)
	{
		$result = preg_replace_callback('/##(.*?)##/', function ($preg) use ($params) {
			return isset($params[$preg[1]]) ? $params[$preg[1]] : $preg[0];
		}, $message);
		return $result;
	}

	public static function getWhatsppVariables($variablesObj, $contentParams)
	{
		$params = [];
		foreach ($variablesObj as $value)
		{
			if (array_key_exists($value, $contentParams))
			{
				$params[] = $contentParams[$value];
			}
		}
		return $params;
	}

	public static function prepareTemplate($obj, $contentParams)
	{
		$message = TemplateMaster::replaceVariablesInTemplate($obj->content, $contentParams);
		return $message;
	}

}
