<?php

/**
 * This is the model class for table "vendor_boost".
 *
 * The followings are the available columns in table 'vendor_boost':
 * @property integer $vbt_id
 * @property integer $vbt_vendor_id
 * @property string $vbt_vhc_id
 * @property string $vbt_mailing_address
 * @property string $vbt_sticker_sent_date
 * @property integer $vbt_sticker_received
 * @property string $vbt_sticker_received_date
 * @property string $vbt_tracking_number
 * @property integer $vbt_delivered_courier
 */
class VendorBoost extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_boost';
	}
        public $search;
        public $searchVehicleNumber;
        public $vbt_send_date;
	public $vbt_receive_date;
	public $vbt_send_time;
	public $vbt_receive_time;
	public $searchSentStickerToVendor;
        public $stickerReceivedTypes = ['0' => 'Pending', '1' => 'Received', '2' => 'Not Received'];
	public static $deliveredCourierArr = ['0' => 'No', '1' => 'Yes'];
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vbt_vendor_id, vbt_vhc_id, vbt_mailing_address', 'required', 'on' => 'addBoost'),
			array('vbt_vendor_id, vbt_sticker_send_count, vbt_sticker_received, vbt_delivered_courier', 'numerical', 'integerOnly'=>true),
			array('vbt_vhc_id', 'length', 'max'=>150),
			array('vbt_sticker_sent_date, vbt_sticker_received_date', 'safe'),
			array('vbt_mailing_address', 'required', 'on' => 'addBoost'),
			['vbt_sticker_send_count', 'validateEdit', 'on' => 'updateBoost'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vbt_id, vbt_vendor_id, vbt_vhc_id, vbt_mailing_address, vbt_sticker_sent_date, vbt_sticker_received, vbt_sticker_received_date, vbt_tracking_number, vbt_delivered_courier', 'safe', 'on'=>'search'),
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
			'vbt_id' => 'Vbt',
			'vbt_vendor_id' => 'Vbt Vendor',
			'vbt_vhc_id' => 'Vbt Vhc',
			'vbt_mailing_address' => 'Vbt Mailing Address',
			'vbt_sticker_sent_date' => 'Vbt Sticker Sent Date',
			'vbt_sticker_send_count'=>'Vbt Sticker Send Count',
			'vbt_sticker_received' => '0=>Pending, 1=>Received, 2=> Not Received',
			'vbt_sticker_received_date' => 'Sticker Received Date',
			'vbt_tracking_number' => 'Sticker Tracking Number',
			'vbt_delivered_courier' => '0=>Off, 1=>On',
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

		$criteria=new CDbCriteria;

		$criteria->compare('vbt_id',$this->vbt_id);
		$criteria->compare('vbt_vendor_id',$this->vbt_vendor_id);
		$criteria->compare('vbt_vhc_id',$this->vbt_vhc_id,true);
		$criteria->compare('vbt_mailing_address',$this->vbt_mailing_address,true);
		$criteria->compare('vbt_sticker_sent_date',$this->vbt_sticker_sent_date,true);
		$criteria->compare('vbt_sticker_send_count',$this->vbt_sticker_send_count);
		$criteria->compare('vbt_sticker_received',$this->vbt_sticker_received);
		$criteria->compare('vbt_sticker_received_date',$this->vbt_sticker_received_date,true);
		$criteria->compare('vbt_tracking_number',$this->vbt_tracking_number,true);
		$criteria->compare('vbt_delivered_courier',$this->vbt_delivered_courier);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorBoost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public function getList($search = false, $searchVhcCode = false, $searchSentStickerToVendor = '')
        {
            $where = "";
            if ($search != '')
            {
                $where .=  "AND ((vnd.vnd_code LIKE '%" . trim($search) . "%' ) OR (vnd.vnd_name LIKE '%" . trim($search) . "%'))";
            }
            if ($searchVhcCode != '')
            {
                $search_txt	 = trim($searchVhcCode);
                $tsearch_txt	 = strtolower(str_replace(' ', '', $search_txt));
                $where .= " AND (REPLACE(LOWER(vhc.vhc_code),' ', '')  LIKE '%$tsearch_txt%') ";
            }
			if($searchSentStickerToVendor !='')
			{
				if($searchSentStickerToVendor == 1){
				    	$searchStickerStatus = " AND vbt.vbt_sticker_send_count > 0";
				}
			}
            $sql = "SELECT DISTINCT(vbt.vbt_id),
                           vbt.vbt_vendor_id,
                           vbt.vbt_vhc_id, 
                           vbt.vbt_mailing_address,
                           vbt.vbt_sticker_sent_date, 
						   vbt_sticker_send_count,
                           vbt.vbt_sticker_received, 
                           vbt.vbt_sticker_received_date,
						   vbt.vbt_tracking_number,
						   vbt.vbt_delivered_courier,
                           vnd.vnd_name,
			               vnd.vnd_code,
						   vnd.vnd_id,
                           vhc.vhc_number,
						   vhc.vhc_code,
							cnt.ctt_first_name,
							cnt.ctt_last_name,
							cnt.ctt_business_name
                           FROM vendor_boost vbt                 
                           INNER JOIN vendors vnd ON vbt.vbt_vendor_id = vnd.vnd_id AND vnd.vnd_active > 0
                           INNER JOIN vehicles vhc ON vhc.vhc_id = vbt.vbt_vhc_id AND vhc.vhc_active = 1
						   INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status = 1
						   INNER JOIN contact cnt ON cnt.ctt_id = cp.cr_contact_id AND cnt.ctt_id = cnt.ctt_ref_code AND cnt.ctt_active = 1
                           $where $searchStickerStatus ORDER BY vbt.vbt_id DESC";
						  
            $arr		 = array();
            $data		 = DBUtil::queryRow("SELECT COUNT(DISTINCT(vbt.vbt_id)) AS count 
                           FROM vendor_boost vbt 
                           INNER JOIN vendors vnd ON vbt.vbt_vendor_id = vnd.vnd_id AND vnd.vnd_active > 0
                           INNER JOIN vehicles vhc ON vhc.vhc_id = vbt.vbt_vhc_id AND vhc.vhc_active = 1
                           $where $searchStickerStatus", DBUtil::SDB());

            $dataprovider	 = new CSqlDataProvider($sql, [
                    'totalItemCount' => $data['count'],
                    'db'	     => DBUtil::SDB(),
                    'sort'	     => ['attributes'	 => ['vbt_id'],
                    'defaultOrder'   => 'vbt_id DESC'], 'pagination'	 => ['pageSize' => 50],
            ]);
            $arr[0]		     = $dataprovider;
            $arr[1]		     = $data;
            return $arr;
       }
	   public function validateField($attribute, $params)
	   {
			if ($this->vbt_mailing_address == '')
			{
				$this->addError('vbt_mailing_address', 'Please add mailing address');
				return false;
			}
			if ($this->vbt_vhc_id == '')
			{
				$this->addError('vbt_vhc_id', 'Please add cab list');
				return false;
			}
	   }
	    public function validateEdit($attribute, $params)
	   {
			$success= true;
			if ($this->vbt_sticker_send_count == '' || $this->vbt_sticker_send_count ==0 || $this->vbt_sticker_send_count<0)
			{
				$this->addError($attribute, 'Please Enter No. of Stickers Sent greater than 0');
				$success =  false;
			}
			if ($this->vbt_tracking_number == '')
			{
				$this->addError('vbt_tracking_number', 'Please Enter Tracking Number.');
				$success = false;
			}
			if ($this->vbt_sticker_sent_date == '')
			{
				$this->addError('vbt_sticker_sent_date', 'Please Enter Send Date.');
				$success = false;
			}
			
			if($this->vbt_sticker_received_date !='')
			{ 
				if ((strtotime($this->vbt_sticker_received_date)) <= (strtotime($this->vbt_sticker_sent_date)))
				{
					$this->addError('vbt_sticker_received_date', 'Please Enter Sticker Received Date greater then Sent Date.');
					$success =  false;
				}
			}
			return $success;
	   }
}
