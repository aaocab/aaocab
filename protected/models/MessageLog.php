<?php

/**
 * This is the model class for table "message_log".
 *
 * The followings are the available columns in table 'message_log':
 * @property integer $id
 * @property integer $msg_message_id
 * @property string $msg
 * @property integer $msg_ref_id
 * @property integer $msg_ref_type
 * @property integer $msg_driver_visible
 * @property integer $msg_vendor_visible
 * @property integer $msg_customer_visible
 * @property integer $msg_admin_is_read
 * @property integer $msg_vendor_is_read
 * @property string $msg_log
 * @property integer $msg_active
 * @property string $msg_created

 *
 * The followings are the available model relations:
 * @property Booking $msgBkg
 */
class MessageLog extends CActiveRecord
{

    public $msg_entity_id;
    public $msg_entity_type;
    public $refPlatform = ['Consumer' => 1, 'Vendor' => 2, 'Driver' => 3, 'Admin' => 4, 'Agent' => 5];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'message_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('msg_message_id, msg_ref_id, msg_ref_type, msg_driver_visible, msg_vendor_visible, msg_customer_visible, msg_active', 'numerical', 'integerOnly' => true),
            array('msg', 'length', 'max' => 200),
            array('msg_log', 'length', 'max' => 2000),
            array('msg, msg_message_id', 'required', 'on' => 'addMessage'),
            ['msg_vendor_visible', 'validateVendor', 'on' => 'msgVendorValidate'],
            ['msg_vendor_visible', 'validateVendor', 'on' => 'msgVendorValidate'],
            ['msg_vendor_visible', 'validateDriver', 'on' => 'msgDriverValidate'],
            ['msg_vendor_visible', 'validateVendor', 'on' => 'msgAdminValidate'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('msg_message_id, msg', 'required', 'on' => 'insert,update'),
            array('id, msg_message_id, msg, msg_ref_id, msg_ref_type, msg_driver_visible, msg_vendor_visible, msg_customer_visible, msg_admin_is_read, msg_vendor_is_read, msg_log, msg_active, msg_created, msg_entity_id, msg_entity_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array (
			'chatMsg' => array(self::BELONGS_TO, 'ChatMessage', 'msg_message_id'),
		);
    }

    public function validateVendor($attribute, $params)
    {
        $success = true;
        if ($this->msg_vendor_visible != 1 || $this->msg_vendor_visible == NULL)
        {
            $this->addError('msg_vendor_visible', "Please check vendor box before submit.");
            $success = false;
        }
        if ($this->msg == '' || $this->msg == NULL)
        {
            $this->addError('msg', "Msg cannot be blank.");
            $success = false;
        }
        return $success;
    }

    public function validateDriver($attribute, $params)
    {
        $success = true;

        if ($this->msg_driver_visible != 1 || $this->msg_driver_visible == NULL)
        {
            $this->addError('msg_driver_visible', "Please check driver box before submit.");
        }
        if ($this->msg == '' || $this->msg == NULL)
        {
            $this->addError('msg', "Msg cannot be blank.");
            $success = false;
        }
        return $success;
    }

    public function validateCustomer($attribute, $params)
    {
        $success = true;

        if ($this->msg_customer_visible != 1 || $this->msg_customer_visible == NULL)
        {
            $this->addError('msg_customer_visible', "Please check customer box before submit.");
        }
        if ($this->msg == '' || $this->msg == NULL)
        {
            $this->addError('msg', "Msg cannot be blank.");
            $success = false;
        }
        return $success;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'msg_message_id' => 'Msg Message ID',
            'msg' => 'Msg',
            'msg_ref_id' => 'Msg Ref',
            'msg_ref_type' => 'Msg Ref Type',
            'msg_driver_visible' => 'Msg Driver Visible',
            'msg_vendor_visible' => 'Msg Vendor Visible',
            'msg_customer_visible' => 'Msg Customer Visible',
            'msg_admin_is_read' => 'Msg Admin Is Read',
            'msg_vendor_is_read' => 'Msg Vendor Is Read',
            'msg_active' => 'Msg Active',
            'msg_created' => 'Msg Created',
            'msg_log' => 'Msg Log',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('msg', $this->msg, true);
        $criteria->compare('msg_ref_id', $this->msg_ref_id);
        $criteria->compare('msg_ref_type', $this->msg_ref_type);
        $criteria->compare('msg_driver_visible', $this->msg_driver_visible);
        $criteria->compare('msg_vendor_visible', $this->msg_vendor_visible);
        $criteria->compare('msg_customer_visible', $this->msg_customer_visible);
        $criteria->compare('msg_log', $this->msg_log);
        $criteria->compare('msg_active', $this->msg_active);
        $criteria->compare('msg_created', $this->msg_created, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MessageLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getPlatform($platform = '')
    {
        $arr = $this->refPlatform;
        if ($platform == '')
        {
            $platform = 1;
        }
        return $arr[$platform];
    }

	/**
	 * getCurrentMessageById
	 */
    public function getCurrentMessageById($id)
    {
        $sql = "SELECT
                booking.bkg_booking_id,
                booking_cab.bcb_vendor_id,
                UPPER
                (
                    CASE 
					WHEN msg_ref_type = 1 THEN 'Consumer' 
					WHEN msg_ref_type = 2 THEN 'Vendor' 
					WHEN msg_ref_type = 3 THEN 'Driver' 
					WHEN msg_ref_type = 4 THEN 'Csr' 
					WHEN msg_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_type,
                (
                    CASE 
					WHEN msg_ref_type = 1 THEN CONCAT(users.usr_name,' ', users.usr_lname) 
					WHEN msg_ref_type = 2 THEN IF(vendors.vnd_owner != '', vendors.vnd_owner, vendors.vnd_name) 
					WHEN msg_ref_type = 3 THEN drv_name 
					WHEN msg_ref_type = 4 THEN CONCAT(admins.adm_fname, ' ', admins.adm_lname) 
					WHEN msg_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_name,
                message_log.msg,
                message_log.msg_created
                FROM
                `chat_message`
                INNER JOIN `message_log` ON `message_log`.msg_message_id = chat_message.message_id AND message_log.msg_active = 1 
                LEFT JOIN `booking` ON booking.bkg_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 0 AND booking.bkg_active = 1
                LEFT JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
				LEFT JOIN `users` ON users.user_id = message_log.msg_ref_id AND message_log.msg_ref_type = 1
                LEFT JOIN `vendors` ON vendors.vnd_id = message_log.msg_ref_id AND message_log.msg_ref_type = 2
                LEFT JOIN `drivers` ON drv_id = message_log.msg_ref_id AND message_log.msg_ref_type = 3
				LEFT JOIN `admins` ON admins.adm_id = message_log.msg_ref_id AND message_log.msg_ref_type = 4
                WHERE message_log.id = $id";
        $row = DBUtil::queryRow($sql);
        $row['msg_driver_visible'] = ($row['msg_driver_visible'] == 1) ? TRUE : FALSE;
        $row['msg_vendor_visible'] = ($row['msg_vendor_visible'] == 1) ? TRUE : FALSE;
        $row['msg_customer_visible'] = ($row['msg_customer_visible'] == 1) ? TRUE : FALSE;
        $row['msg_created'] = date("j-M-y, g:i:s A", strtotime($row['msg_created']));
        $row['msg_bkg_id'] = $row['msg_entity_id'];
        return $row;
    }

    public function validateArray()
    {
        return [1 => 'msgCustomerValidate', 2 => 'msgVendorValidate', 3 => 'msgDriverValidate', 4 => 'msgAdminValidate'];
    }

    public function setValidate($type)
    {
        $list = $this->validateArray();
        return $list[$type];
    }

    /**
	 * addMessage
	 */
    public function addMessage($entity_id, $message, $userInfo, $is_driver = 0, $is_vendor = 0, $is_customer = 0, $entity_type = 0, $arrData = array())
    {
        $success = true;
        $errors = '';
        $id = 0;
        $user_id = $userInfo->userId;
        $user_type = $userInfo->userType;

        if (isset($user_type))
        {
            $scenario = $this->setValidate($user_type);
        }
		
		// Admin Read Count
		$ownerId = $user_id;
		$msgAdminIsRead = 1;
		$msgUnreadCountAdmin = 0;
		if(is_array($arrData) && count($arrData) > 0) {
			if($arrData['source'] == 'vendor') {
				$msgAdminIsRead = 0;
				$msgUnreadCountAdmin = 1;
				$ownerId = 0;
			}
		}

        $sql1 = "SELECT message_id FROM `chat_message` WHERE msg_entity_id = $entity_id AND msg_entity_type = $entity_type";
        $message_id = DBUtil::command($sql1)->queryScalar();
        if (!$message_id)
        {
            $objMessages = new ChatMessage();
            $objMessages->msg_entity_id = $entity_id;
            $objMessages->msg_entity_type = $entity_type;
            $objMessages->message_start_date = date("Y-m-d H:i:s");
            $objMessages->message_last_date = date("Y-m-d H:i:s");
            $objMessages->message_owner_id = $ownerId;
            $objMessages->message_unread_count_for_admin = $msgUnreadCountAdmin;
            $objMessages->message_active = 1;

            if ($objMessages->save())
            {
                $message_id = $objMessages->message_id;
            }
        }
        else
        {
            $chatModel = ChatMessage::model()->findByPk($message_id);
            $chatModel->message_last_date = date("Y-m-d H:i:s");
            $chatModel->message_unread_count_for_admin = $chatModel->message_unread_count_for_admin + $msgUnreadCountAdmin;
			
			if($ownerId > 0) {
				$chatModel->message_owner_id = $ownerId;
			}
			
            $chatModel->save();
        }

        $model = new MessageLog();
        $model->scenario = $scenario;
        $model->msg_message_id = $message_id;
        $model->msg = $message;
        $model->msg_ref_id = $user_id;
        $model->msg_ref_type = $user_type;
        $model->msg_driver_visible = $is_driver;
        $model->msg_vendor_visible = $is_vendor;
        $model->msg_customer_visible = $is_customer;
        $model->msg_admin_is_read = $msgAdminIsRead;

        if ($model->validate())
        {
            if ($model->save())
            {
                if ($model->msg_vendor_visible == 1 && $model->msg_entity_type == 0 && $model->msg_ref_type != 2)
                {
                    $notifyCom = new notificationWrapper();
                    $notifyCom->sendVndMessagingForBooking($model->id);
                }
                $success = true;
                $id = $model->id;
            }
        }
        else
        {
            $success = false;
            $errors = $model->getErrors();
        }

        $return = ['success' => $success, 'errors' => $errors, 'id' => $id];
        return $return;
    }

	/**
	 * setUpdateToggle
	 */
    public function setUpdateToggle($id, $type)
    {
        $success = false;
        $msg_entity_type = 0;
        // 1:Consumer ; 2:Vendor ; 3:Driver ; 4:Admin ; 5:Agent	
        $model = MessageLog::model()->findByPk($id);
        $oldData = $model->attributes;
        switch ($type)
        {
            case 1:
                $model->msg_customer_visible = ($model->msg_customer_visible > 0) ? 0 : 1;
                break;
            case 2:
                $model->msg_vendor_visible = ($model->msg_vendor_visible > 0) ? 0 : 1;
                break;
            case 3:
                $model->msg_driver_visible = ($model->msg_driver_visible > 0) ? 0 : 1;
                break;
        }
        $newData = $model->attributes;
        if ($model->scenario == 'update')
        {
            $model->msg_log = $this->addLog($oldData, $newData, $id);
        }
        if ($model->validate())
        {
            if ($model->save())
            {
                if ($model->msg_vendor_visible == 1 && $type == 2)
                {
                    $notifyCom = new notificationWrapper();
                    $notifyCom->sendVndMessagingForBooking($model->id);
                }
                $msg_entity_type = $model->chatMsg->msg_entity_type;
                $success = true;
            }
        }
        else
        {
            $errors = $model->getErrors();
        }
        return ['success' => $success, 'errors' => $errors, 'type' => $msg_entity_type];
    }

	/**
	 * getMessageLeftHtml
	 */
    public function getMessageLeftHtml()
    {
        $messageLeftHtml = '';
		$activeMsgList = $this->getActiveMessageByBkg();
        $ctr = 0;
        foreach ($activeMsgList as $active)
        {
            #$activeMsgList[$ctr]['msg_max_created'] = (date('Y-m-d') == date("Y-m-d", strtotime($active['msg_max_created']))) ? date("G:i", strtotime($active['msg_max_created'])) : date("j F", strtotime($active['msg_max_created']));
            #$ctr++;
			
			$createdDate = (date('Y-m-d') == date("Y-m-d", strtotime($active['msg_max_created']))) ? date("G:i", strtotime($active['msg_max_created'])) : date("j F", strtotime($active['msg_max_created']));
			
			$classAttReq = '';
			if(trim($active['owner_name']) == '') {
				$active['owner_name'] = 'Waiting for reply !!!';
				$classAttReq = 'att_req';
			}
			
			$var = $active['bkg_booking_id'] . " (" . $active['message_unread_count_for_admin'] . ") " . "<br> " . $active['owner_name'] . "<br> " . " ( " . $createdDate . " )";
            $messageLeftHtml .= '<li id=left_' . $active['msg_entity_id'] . ' onclick="setMessageBox(' . $active['msg_entity_id'] . ', ' . $active['msg_entity_type'] . ')" style="cursor:pointer;" class="'.$classAttReq.'">' . $var . '</li>';
        }
        /*$messageLeftHtml = '';
        if (count($activeMsgList) > 0)
        {
            foreach ($activeMsgList as $active)
            {
                if ($active['message_owner_id'] > 0)
                {
                    $persondata = $this->getPersonname($active['message_owner_id'], $active['msg_entity_id']);
                }
                else
                {
                    $persondata[0]['ref_name'] = "Waiting for reply";
                }
                $var = $active['bkg_booking_id'] . " (" . $active['message_unread_count_for_admin'] . ") " . "<br> " . $persondata[0]['ref_name'] . "<br> " . " ( " . $active['msg_max_created'] . " )";
                $messageLeftHtml .= '<li id=left_' . $active['msg_entity_id'] . ' onclick="setMessageBox(' . $active['msg_entity_id'] . ', ' . $active['msg_entity_type'] . ')" style="cursor:pointer;">' . $var . '</li>';
            }
        }*/

        return $messageLeftHtml;
    }

    /*public function getMessagedata_NR()
    {
        $activeMsgList = $this->getActiveMessageByBkg();
        $ctr = 0;
        foreach ($activeMsgList as $active)
        {
            $activeMsgList[$ctr]['msg_max_created'] = (date('Y-m-d') == date("Y-m-d", strtotime($active['msg_max_created']))) ? date("G:i", strtotime($active['msg_max_created'])) : date("j F", strtotime($active['msg_max_created']));
            $ctr++;
        }
        $messageLeftHtml = '';
        if (count($activeMsgList) > 0)
        {
            $messageLeftHtml .= "<div><ul class=\"pl0 chat_listview\">";
            foreach ($activeMsgList as $active)
            {
                $var = $active['entity_id'] . "<br> " . $active['entity_name'] . " ( " . $active['msg_max_created'] . " )";
                $messageLeftHtml .= '<li id=' . $active['msg_entity_id'] . ' onclick="setMessageBox(' . $active['msg_entity_id'] . ', ' . $active['msg_entity_type'] . ')" style="cursor:pointer;">' . $var . '</li>';
            }
            $messageLeftHtml .= "</ul></div>";
        }

        return $messageLeftHtml;
    }*/

	/**
	 * getMessageJsonByBkg
	 */
    public function getMessageJsonByBkg($entity_id, $entity_type = 0, $isVendor = 0, $isDriver = 0, $isConsumer = 0)
    {
        $msgList = '';
        if ($entity_id > 0)
        {
            $msgList = $this->getMessagesByBkg($entity_id, $entity_type, $isVendor, $isDriver, $isConsumer);

            if (count($msgList) > 0)
            {
                $ctr = 0;
                foreach ($msgList as $msg)
                {
                    $msgAppear = ($msg['msg_ref_type'] != 4) ? '&nbsp<span class="label label-primary">Admin</span>' : '';
                    $msgAppear .= ($msg['msg_vendor_visible'] == 1) ? '&nbsp<span class="label label-info" onclick="updateToggleButton(' . $msg['msg_entity_id'] . ', ' . $this->getPlatform('Vendor') . ', ' . $msg['id'] . ')" style="cursor:pointer">[x] Vendor</span>' : '&nbsp;<span class="label label-default" onclick="updateToggleButton(' . $msg['msg_entity_id'] . ', ' . $this->getPlatform('Vendor') . ', ' . $msg['id'] . ')" style="cursor:pointer">[ ] <i>Vendor</i></span>';
                    $msgAppear .= ($msg['msg_driver_visible'] == 1) ? '&nbsp<span class="label label-warning" onclick="updateToggleButton(' . $msg['msg_entity_id'] . ',' . $this->getPlatform('Driver') . ', ' . $msg['id'] . ')" style="cursor:pointer">[x] Driver</span>' : '&nbsp;<span class="label label-default" onclick="updateToggleButton(' . $msg['msg_entity_id'] . ',' . $this->getPlatform('Driver') . ',' . $msg['id'] . ')" style="cursor:pointer">[ ] <i>Driver</i></span>';
                    $msgAppear .= ($msg['msg_customer_visible'] == 1) ? '&nbsp<span class="label label-success" onclick="updateToggleButton(' . $msg['msg_entity_id'] . ',' . $this->getPlatform('Consumer') . ', ' . $msg['id'] . ')" style="cursor:pointer">[x] Customer</span>' : '&nbsp;<span class="label label-default" onclick="updateToggleButton(' . $msg['msg_entity_id'] . ',' . $this->getPlatform('Consumer') . ',' . $msg['id'] . ')" style="cursor:pointer">[ ] <i>Customer</i></span>';
                    $created = (date('Y-m-d') == date("Y-m-d", strtotime($msg['msg_created']))) ? date("G:i", strtotime($msg['msg_created'])) : date("j F", strtotime($msg['msg_created']));
                    $msgList[$ctr]['ref_name'] = $msg['ref_name'];
                    $msgList[$ctr]['msg'] = $msg['msg'];
                    //$msgList[$ctr]['msg_admin_is_read']	 = $msg['msg_admin_is_read'];
                    $msgList[$ctr]['msg_created'] = date("d/m/Y h:i A", strtotime($msg['msg_created']));
                    $msgList[$ctr]['created'] = $created;
                    $msgList[$ctr]['msg_appear'] = addslashes($msgAppear);

                    $msgAppear = '';
                    $ctr++;
                }
            }
        }

        $leftMsgList = $this->getActiveMessageByBkg();


        $ctr = 0;
        foreach ($leftMsgList as $active)
        {
            $leftMsgList[$ctr]['created'] = (date('Y-m-d') == date("Y-m-d", strtotime($active['msg_max_created']))) ? date("G:i", strtotime($active['msg_max_created'])) : date("j F", strtotime($active['msg_max_created']));
            /*if ($active['message_owner_id'] > 0)
            {
                $persondata = $this->getPersonname($active['message_owner_id'], $active['msg_entity_id']);
            }
            else
            {
                $persondata[0]['ref_name'] = "Waiting for reply";
            }*/

            $leftMsgList[$ctr]['owner_name'] = $active['owner_name'];
            $ctr++;
        }

        return ['list' => $msgList, 'listLeftPanel' => $leftMsgList];
    }

    /*public function getMessageHtmlByBkg_NR($entity_id, $entity_type = 0, $isVendor = 0, $isDriver = 0, $isConsumer = 0)
    {
        $msgList = $this->getMessagesByBkg($entity_id, $entity_type, $isVendor, $isDriver, $isConsumer);

        $messageHtml = '<div>';
        if (count($msgList) > 0)
        {
            $msgAppear = '';
            foreach ($msgList as $msg)
            {
                $created = (date('Y-m-d') == date("Y-m-d", strtotime($msg['msg_created']))) ? date("G:i", strtotime($msg['msg_created'])) : date("j F", strtotime($msg['msg_created']));
                $msgAppear .= ($msg['msg_ref_type'] != 4) ? '&nbsp<span class="label label-primary">Admin</span>' : '';
                $msgAppear .= ($msg['msg_vendor_visible'] == 1) ? '&nbsp<span class="label label-info" onclick="updateToggleButton(' . $entity_id . ', ' . $this->getPlatform('Vendor') . ', ' . $msg['id'] . ')" style="cursor:pointer">[x] Vendor</span>' : '&nbsp;<span class="label label-default" onclick="updateToggleButton(' . $entity_id . ', ' . $this->getPlatform('Vendor') . ', ' . $msg['id'] . ')" style="cursor:pointer">[ ] <i>Vendor</i></span>';
                $msgAppear .= ($msg['msg_driver_visible'] == 1) ? '&nbsp<span class="label label-warning" onclick="updateToggleButton(' . $entity_id . ',' . $this->getPlatform('Driver') . ', ' . $msg['id'] . ')" style="cursor:pointer">[x] Driver</span>' : '&nbsp;<span class="label label-default" onclick="updateToggleButton(' . $entity_id . ',' . $this->getPlatform('Driver') . ',' . $msg['id'] . ')" style="cursor:pointer">[ ] <i>Driver</i></span>';
                $msgAppear .= ($msg['msg_customer_visible'] == 1) ? '&nbsp<span class="label label-success" onclick="updateToggleButton(' . $entity_id . ',' . $this->getPlatform('Consumer') . ', ' . $msg['id'] . ')" style="cursor:pointer">[x] Customer</span>' : '&nbsp;<span class="label label-default" onclick="updateToggleButton(' . $entity_id . ',' . $this->getPlatform('Consumer') . ',' . $msg['id'] . ')" style="cursor:pointer">[ ] <i>Customer</i></span>';
                $msgList['ref_name'] = $msg['ref_name'];
                $msgList['msg'] = $msg['msg'];
                $msgList['msg_created'] = date("d/m/Y h:i A", strtotime($msg['msg_created']));
                $messageHtml .= "<div style=\"float:none;\" class=\"col-xs-12\">
                                                    " . $msg['ref_name'] . " ( " . $msg['ref_sent_by'] . " @" . $created . " ) " . " sent to " . "$msgAppear<br>
                                                    " . $msg['msg'] . "<br><br>
                                                    
                                                    </div>";
                $msgAppear = '';
            }
        }
        $messageHtml .= '</div>';
        return $messageHtml;
    }*/

	/**
	 * getMessagesByBkg
	 */
    public function getMessagesByBkg($entity_id, $entity_type = 0, $isVendor = 0, $isDriver = 0, $isConsumer = 0)
    {
        $sql = "SELECT
				message_log.id,
				chat_message.msg_entity_id,
				chat_message.msg_entity_type,
				CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname) as customer_name,
				message_log.msg,
				message_log.msg_ref_type,
				message_log.msg_driver_visible,
				message_log.msg_vendor_visible,
				message_log.msg_customer_visible,                    
				UPPER(
					CASE 
					WHEN msg_ref_type = 1 THEN 'Consumer' 
					WHEN msg_ref_type = 2 THEN 'Vendor' 
					WHEN msg_ref_type = 3 THEN 'Driver' 
					WHEN msg_ref_type = 4 THEN 'Csr' 
					WHEN msg_ref_type = 5 THEN 'Agent'
					END
				) AS ref_type,
                (
                    CASE 
					WHEN msg_ref_type = 1 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
                    WHEN msg_ref_type = 2 THEN IF(vendors.vnd_owner != '', vendors.vnd_owner, vendors.vnd_name) 
                    WHEN msg_ref_type = 3 THEN drv_name 
                    WHEN msg_ref_type = 4 THEN CONCAT(admins.adm_fname, ' ', admins.adm_lname) 
					WHEN msg_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_name,
                DATE_FORMAT(message_log.msg_created, '%d-%b-%y, %l:%i:%s %p') as msg_created,
                (
                    CASE WHEN msg_ref_type = 1 THEN '4'
                         WHEN msg_ref_type = 2 THEN '1,4' 
                         WHEN msg_ref_type = 3 THEN '1,2,4' 
                         WHEN msg_ref_type = 4 THEN '1,2,3'
                    END
                ) as ref_next,
                (
                    CASE 
					WHEN msg_ref_type = 1 THEN 'Customer' 
                    WHEN msg_ref_type = 2 THEN 'Vendor' 
                    WHEN msg_ref_type = 3 THEN 'Driver' 
                    WHEN msg_ref_type = 4 THEN 'Admin' 
                    WHEN msg_ref_type = 5 THEN 'Agent'
                    END
                ) as ref_sent_by, 
				(
                    CASE 
					WHEN msg_ref_type = 1 THEN 'Customer' 
                    WHEN msg_ref_type = 2 THEN 'Vendor' 
                    WHEN msg_ref_type = 3 THEN 'Driver' 
                    WHEN msg_ref_type = 4 THEN 'Patner Support' 
                    WHEN msg_ref_type = 5 THEN 'Agent'
                    END
                ) as display_name,
				booking_cab.bcb_vendor_id, vendors.vnd_id, message_log.msg_admin_is_read
                FROM 				
				`chat_message`
				JOIN `message_log` ON  message_log.msg_message_id = chat_message.message_id  
                LEFT JOIN `booking` ON booking.bkg_id = chat_message.msg_entity_id AND booking.bkg_active = 1
                LEFT JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
                LEFT JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
                LEFT JOIN `users` ON users.user_id = message_log.msg_ref_id AND message_log.msg_ref_type = 1
				LEFT JOIN `vendors` ON vendors.vnd_id = message_log.msg_ref_id AND message_log.msg_ref_type = 2
                LEFT JOIN `drivers` ON drivers.drv_id = message_log.msg_ref_id AND message_log.msg_ref_type = 3
				LEFT JOIN `admins` ON admins.adm_id = message_log.msg_ref_id AND message_log.msg_ref_type = 4
                WHERE chat_message.msg_entity_id = $entity_id AND chat_message.msg_entity_type = $entity_type 
				AND message_log.msg_active = 1 AND chat_message.message_active = 1";

        $sql .= ($isDriver > 0 && $entity_type == 0) ? " AND message_log.msg_driver_visible=" . $isDriver : '';
        $sql .= ($isVendor > 0 && $entity_type == 0) ? " AND message_log.msg_vendor_visible=" . $isVendor . " AND (bcb_vendor_id=vnd_id OR vnd_id IS NULL)" : '';
		
        //$sql .= ($isVendor > 0) ? " AND message_log.msg_customer_visible=" . $isConsumer : '';
        
		$sql .= " ORDER BY message_log.msg_created ASC";
		
        return DBUtil::queryAll($sql);
    }

	/**
	 * 
	 * getActiveMessageByBkg
	 */
    public function getActiveMessageByBkg()
    {
        /*$sql = "SELECT 
                    chat_message.msg_entity_id, 
                    chat_message.msg_entity_type, 
                    chat_message.message_unread_count_for_admin,
                    chat_message.message_owner_id,                                
                    (
                      CASE 
					  WHEN chat_message.msg_entity_type = 0 THEN booking.bkg_booking_id 
					  WHEN chat_message.msg_entity_type = 1 THEN users.user_id 
					  WHEN chat_message.msg_entity_type = 2 THEN vendors.vnd_id 
					  WHEN chat_message.msg_entity_type = 3 THEN drivers.drv_id 
					  END
                    ) AS entity_id, 
                    (
                      CASE 
						WHEN chat_message.msg_entity_type = 0 THEN CONCAT(booking_user.bkg_user_fname, ' ', booking_user.bkg_user_lname) 
						WHEN chat_message.msg_entity_type = 1 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
						WHEN chat_message.msg_entity_type = 2 THEN vendors.vnd_name 
						WHEN chat_message.msg_entity_type = 3 THEN drivers.drv_name 
						END
                    ) AS entity_name, 
                    booking.bkg_booking_id, 
                    CONCAT(booking_user.bkg_user_fname, ' ', booking_user.bkg_user_lname) AS customer_name, 
                    COUNT(1) AS cnt, 
                    MAX(message_log.msg_created) AS msg_max_created 
              FROM 				
                    `chat_message`
                    INNER JOIN `message_log` ON  message_log.msg_message_id = chat_message.message_id 
                    LEFT JOIN `booking` ON booking.bkg_id = chat_message.msg_entity_id 
                    LEFT JOIN `booking_user` ON booking_user.bui_bkg_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 0 AND booking.bkg_active = 1 
                    LEFT JOIN `users` ON users.user_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 1 AND users.usr_active = 1 
                    LEFT JOIN `vendors` ON vendors.vnd_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 2 AND vendors.vnd_active = 1 
                    LEFT JOIN `drivers` ON drivers.drv_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 3 AND drivers.drv_active = 1 
              WHERE 
                    message_log.msg_active = 1 AND chat_message.message_active = 1 
              GROUP BY 
                    chat_message.msg_entity_id, 
                    chat_message.msg_entity_type 
              ORDER BY 
                    msg_max_created DESC";*/
		
		$sql = "SELECT 
                    chat_message.msg_entity_id, 
                    chat_message.msg_entity_type, 
                    chat_message.message_unread_count_for_admin,
                    chat_message.message_owner_id, 
                    (
                      CASE 
					  WHEN chat_message.msg_entity_type = 0 THEN booking.bkg_booking_id 
					  WHEN chat_message.msg_entity_type = 1 THEN users.user_id 
					  WHEN chat_message.msg_entity_type = 2 THEN vendors.vnd_id 
					  WHEN chat_message.msg_entity_type = 3 THEN drv_id 
					  END
                    ) AS entity_id, 
                    (
                      CASE 
						WHEN chat_message.msg_entity_type = 0 THEN booking.bkg_booking_id 
						WHEN chat_message.msg_entity_type = 1 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
						WHEN chat_message.msg_entity_type = 2 THEN vendors.vnd_name 
						WHEN chat_message.msg_entity_type = 3 THEN drv_name 
						END
                    ) AS entity_name, 
                    booking.bkg_booking_id, 
                    message_unread_count_for_admin AS cnt, 
					message_last_date AS msg_max_created, 
					CONCAT(IFNULL(adm_fname, ''), ' ', IFNULL(adm_lname, '')) AS owner_name 
              FROM 
                    `chat_message` 
                    LEFT JOIN `booking` ON booking.bkg_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 0 AND booking.bkg_active = 1 
                    LEFT JOIN `users` ON users.user_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 1 AND users.usr_active = 1 
                    LEFT JOIN `vendors` ON vendors.vnd_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 2 AND vendors.vnd_active = 1 
                    LEFT JOIN `drivers` ON drivers.drv_id = chat_message.msg_entity_id AND chat_message.msg_entity_type = 3 AND drivers.drv_active = 1  
					LEFT JOIN `admins` ON admins.adm_id = chat_message.message_owner_id 
              WHERE 
                    chat_message.message_active = 1 
              ORDER BY 
                    message_last_date DESC";

        return DBUtil::queryAll($sql);
    }

	/**
	 * addLog
	 */
    public function addLog($oldData, $newData, $id)
    {
        $model = MessageLog::model()->findByPk($id);
        if ($oldData)
        {
            $getDifference = array_diff_assoc($oldData, $newData);
            $remark = $model->msg_log;
            $dt = date('Y-m-d H:i:s');
            $user = Yii::app()->user->getId();
            //if ($remark) {
            if (is_string($remark))
            {
                $newcomm = CJSON::decode($remark);
            }
            else if (is_array($remark))
            {
                $newcomm = $remark;
            }
            if ($newcomm == false)
            {
                $newcomm = array();
            }
            if (count($getDifference) > 0)
            {
                while (count($newcomm) >= 50)
                {
                    array_pop($newcomm);
                }
                array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));

                $log = CJSON::encode($newcomm);
                return $log;
            }
        }
        return $remark;
    }

	/**
	 * makeAdminread
	 */
    public function makeAdminread($entityId, $entityType)
    {
        $sql = "SELECT message_id, message_unread_count_for_admin as val FROM `chat_message` WHERE msg_entity_id = $entityId AND msg_entity_type = $entityType";
        $data = DBUtil::queryAll($sql);

        if ($data[0]['val'] > 0)
        {
            $sql1 = " UPDATE chat_message SET message_unread_count_for_admin = 0 WHERE msg_entity_id = $entityId AND msg_entity_type = $entityType";
            DBUtil::command($sql1)->execute();

            $sql2 = " UPDATE message_log SET msg_admin_is_read = 1 WHERE msg_admin_is_read = 0 AND msg_message_id = '" . $data[0]['message_id'] . "'";
            DBUtil::command($sql2)->execute();
        }
    }
}
