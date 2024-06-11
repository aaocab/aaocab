<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $ntf_id
 * @property integer $ntf_type
 * @property string $ntf_title
 * @property string $ntf_message
 * @property integer $ntf_message_type
 * @property integer $ntf_coin_value
 * @property integer $ntf_status
 * @property string $ntf_created
 */
class Notification extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'notification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ntf_title, ntf_message, ntf_coin_value', 'required', 'on' => 'insert'),
            array('ntf_type, ntf_message_type, ntf_status, ntf_coin_value', 'numerical', 'integerOnly' => true),
            array('ntf_title', 'length', 'max' => 200),
            array('ntf_message', 'length', 'max' => 1000),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ntf_id, ntf_type, ntf_title, ntf_message, ntf_message_type, ntf_coin_value, ntf_status, ntf_created', 'safe', 'on' => 'search'),
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
            'ntf_id'           => 'Ntf',
            'ntf_type'         => 'Ntf Type',
            'ntf_title'        => 'Notification Title',
            'ntf_message'      => 'Notification Message',
            'ntf_message_type' => 'Ntf Message Type',
            'ntf_coin_value'   => 'Notification Coin Value',
            'ntf_status'       => 'Ntf Status',
            'ntf_created'      => 'Ntf Created',
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
        $criteria->compare('ntf_id', $this->ntf_id);
        $criteria->compare('ntf_type', $this->ntf_type);
        $criteria->compare('ntf_title', $this->ntf_title, true);
        $criteria->compare('ntf_message', $this->ntf_message, true);
        $criteria->compare('ntf_message_type', $this->ntf_message_type);
        $criteria->compare('ntf_coin_value', $this->ntf_coin_value);
        $criteria->compare('ntf_status', $this->ntf_status);
        $criteria->compare('ntf_created', $this->ntf_created, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Notification the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getNtfTypes($key = 0)
    {
        $typesArr = [1 => "All", 2 => "Not taken trip in last 3 months", 3 => "Not taken trip in last 6 months", 4 => "Not taken trip yet"];
        if ($key != 0)
        {
            return $typesArr[$key];
        }
        return $typesArr;
    }

    public function getNtfMessageTypes($key = 0)
    {
        $typesArr = [1 => "Text", 2 => "Html", 3 => "Url"];
        if ($key != 0)
        {
            return $typesArr[$key];
        }
        return $typesArr;
    }

    /**
     * This function is used to send callBack Notification based on Entity Type
     * @param type $refId
     * @param type $message
     */
    public static function callBackSendNotification($refId, $message)
    {
        $scqModel   = ServiceCallQueue::model()->findByPk($refId);
        $entityType = $scqModel->scq_to_be_followed_up_with_entity_type;
        $entityId   = $scqModel->scq_to_be_followed_up_with_entity_id;
        $queueId    = $scqModel->scq_follow_up_queue_type;
//        $queueName  = ServiceCallQueue::getQueueByQueueId($queueId);
        $title      = "Response from aaocab";
        $scqId      = $scqModel->scq_id;
        switch ($entityType)
        {
            case 1:
                $payLoadData = ['UserId' => $entityId, 'EventCode' => Booking::CODE_CONSUMER_NOTIFICATION, 'scqId' => $scqId];
                $success     = AppTokens::callBackNotification($entityId, $payLoadData, $message, $title, $entityType);
                break;
            case 2:
                $payLoadData = ['VendorId' => $entityId, 'EventCode' => Booking::CODE_VENDOR_BROADCAST, 'scqId' => $scqId];
                $success     = AppTokens::callBackNotification($entityId, $payLoadData, $message, $title, $entityType);
                break;
            case 3:
                break;
            case 4:
                break;
            case 5:
                break;
            default:
                break;
        }
    }

}
