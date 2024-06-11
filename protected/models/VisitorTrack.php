<?php

/**
 * This is the model class for table "visitor_track".
 *
 * The followings are the available columns in table 'visitor_track':
 * @property string $vtr_id
 * @property string $vtr_register_id
 * @property string $vtr_visit_url
 * @property string $vtr_visit_event
 * @property string $vtr_visit_data
 * @property string $vtr_visit_date
 */
class VisitorTrack extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'visitor_track';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vtr_register_id, vtr_visit_url,vtr_visit_data', 'required'),
			array('vtr_register_id, vtr_visit_url, vtr_visit_event', 'length', 'max' => 1000),
			array('vtr_visit_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
	array('vtr_id, vtr_register_id, vtr_visit_url, vtr_visit_event, vtr_visit_data, vtr_visit_date ,vtr_referal_url', 'safe', 'on' => 'search'),
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
			'vtr_id'			 => 'Vtr',
			'vtr_register_id'	 => 'Vtr Register',
			'vtr_visit_url'		 => 'Vtr Visit Url',
			'vtr_visit_event'	 => 'Vtr Visit Event',
			'vtr_visit_data'	 => 'Vtr Visit Data',
			'vtr_visit_date'	 => 'Vtr Visit Date',
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

		$criteria->compare('vtr_id', $this->vtr_id, true);
		$criteria->compare('vtr_register_id', $this->vtr_register_id, true);
		$criteria->compare('vtr_visit_url', $this->vtr_visit_url, true);
		$criteria->compare('vtr_visit_event', $this->vtr_visit_event, true);
		$criteria->compare('vtr_visit_data', $this->vtr_visit_data, true);
		$criteria->compare('vtr_visit_date', $this->vtr_visit_date, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VisitorTrack the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function track($postParams = "", $method = "", $url = "", $pageID = "")
	{
		try
		{
          

                $param = Filter::parseTrackingParams();
                if($param['source'])
                {
                 $model = new UsersSourceTracking();
                 $model->add();
                }
 
//            $referrer  = $_SERVER['HTTP_REFERER'];
//            $pReferrer = parse_url($referrer);
//            $refHost   = $pReferrer["host"];
//            if ($refHost)
//            {
//
//                $hostArray = array("aaocab", "gozo.cab");
//
//                if (!in_array($refHost, $hostArray))
//                {
//                    $model = new UsersSourceTracking();
//                    $model->add();
//                }
//            }
            $visitorCookie = Yii::app()->request->cookies['gvid'];
			if ($visitorCookie)
			{
				$visitorId	 = $visitorCookie->value;
				$userId		 = (Yii::app()->user->getId()) ? Yii::app()->user->getId() : 0;

				$model					 = new VisitorTrack();
				$model->vtr_register_id	 = $visitorId;
				$model->vtr_visit_url	 = Yii::app()->request->url;
				$model->vtr_referal_url	 = $_SERVER['HTTP_REFERER'];
				$model->vtr_friendly_url = $url;
				$model->vtr_visit_event	 = '';
				$model->vtr_visit_data	 = $postParams;
				$model->vtr_visit_date	 = new CDbExpression('NOW()');
				$model->vtr_user_id		 = $userId;
				$model->vtr_visit_method = $method;
				$model->vtr_session_id	 = Yii::app()->getSession()->getSessionId();
				$model->vtr_page_id		 = $pageID;
				//$model->vtr_ip_address	 = \Filter::getUserIP();

				if ($model->save())
				{
					return true;
				}
			}
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
	}

	/**
	 * Function for archiving visitor
	 */
	public function archiveData($archiveDB, $upperLimit = 100000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(vtr_id) AS vtr_id FROM (SELECT vtr_id FROM visitor_track WHERE 1 AND vtr_visit_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 month), ' 00:00:00') ORDER BY vtr_id  LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{

					$transaction = DBUtil::beginTransaction();
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql		 = "INSERT INTO " . $archiveDB . ".visitor_track (SELECT * FROM visitor_track WHERE vtr_id IN ($bindString))";
					$rows		 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						$sql = "DELETE FROM `visitor_track` WHERE vtr_id IN ($bindString)";
						DBUtil::execute($sql, $params);
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
					}
				}

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	public static function getReferralDomain()
	{
		$refdomain = [	
			0 => 'bookairportcab.com',
			1 => 'prespicejet.bookairportcab.com',
			2 => 'spicejet.bookairportcab.com',
			3 => 'ixigo.bookairportcab.com',
			4 => 'vistara.bookairportcab.com',
			5 => 'cleartrip.bookairportcab.com'
		];
		return $refdomain;
	}
	public function listByVisitor($paramArray)
	{
		$where = '';
		$refdomain = VisitorTrack::getReferralDomain();
		if ($paramArray['vtr_visit_date'] != "")
		{
			$visitdate	= $paramArray['vtr_visit_date'];
			$where .= " AND (vtr_visit_date BETWEEN '{$visitdate} 00:00:00' AND '{$visitdate} 23:59:59') ";
		}
		if($paramArray['vtr_referal_url'] != '')
		{
			$domainName = $refdomain[$paramArray['vtr_referal_url']];
			$where .= " AND vtr_referal_url LIKE '%{$domainName}%'";
		}
		
		$sqlCount = "SELECT * FROM `visitor_track` WHERE 1 AND vtr_page_id = '1017' $where ";
		$sql = $sqlCount . " ORDER BY vtr_visit_date DESC ";

		$count			 = DBUtil::command("SELECT COUNT(1) FROM ($sqlCount) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;

	}
    

}
