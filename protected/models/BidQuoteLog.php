<?php

/**
 * This is the model class for table "bid_quote_log".
 *
 * The followings are the available columns in table 'bid_quote_log':
 * @property integer $bql_id
 * @property integer $bql_vqt_id
 * @property string $bql_desc
 * @property string $bql_created
 * @property integer $bql_active
 *
 * The followings are the available model relations:
 * @property VendorQuote $bqlVqt
 */
class BidQuoteLog extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bid_quote_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bql_vqt_id, bql_desc', 'required'),
			array('bql_vqt_id, bql_active', 'numerical', 'integerOnly' => true),
			array('bql_desc', 'length', 'max' => 5000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bql_id, bql_vqt_id, bql_desc, bql_created, bql_active', 'safe', 'on' => 'search'),
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
			'bqlVqt' => array(self::BELONGS_TO, 'VendorQuote', 'bql_vqt_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bql_id'		 => 'Bql',
			'bql_vqt_id'	 => 'Bql Vqt',
			'bql_desc'		 => 'Bql Desc',
			'bql_created'	 => 'Bql Created',
			'bql_active'	 => 'Bql Active',
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

		$criteria->compare('bql_id', $this->bql_id);
		$criteria->compare('bql_vqt_id', $this->bql_vqt_id);
		$criteria->compare('bql_desc', $this->bql_desc, true);
		$criteria->compare('bql_created', $this->bql_created, true);
		$criteria->compare('bql_active', $this->bql_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BidQuoteLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function create($vendorQuote)
	{

		$model				 = new BidQuoteLog();
		$model->bql_vqt_id	 = $vendorQuote->vqt_id;
		$bidStatus			 = ($vendorQuote->vqt_status == 1) ? 'Accepted' : 'Denied';
		$data				 = [
			'amount' => $vendorQuote->vqt_amount,
			'desc'	 => $vendorQuote->vqt_description,
			'status' => $bidStatus
		];
		$model->bql_desc	 = CJSON::encode($data);
		$model->save();
		return $model;
	}

}
