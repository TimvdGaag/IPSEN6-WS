<?php

/**
 * This is the model class for table "device".
 *
 * The followings are the available columns in table 'device':
 * @property integer $id
 * @property string $device_id
 * @property string $registration_id
 * @property integer $user_id
 * @property string $email
 * @property integer $blocked
 * @property integer $recursive
 *
 * The followings are the available model relations:
 * @property User $user
 * @property SystemCallLog[] $systemCallLogs
 */
class Device extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Device the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'device';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('device_id, registration_id, user_id', 'required'),
			array('user_id, blocked, recursive', 'numerical', 'integerOnly'=>true),
			array('device_id, email', 'length', 'max'=>45),
			array('registration_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, device_id, registration_id, user_id, email, blocked, recursive', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'systemCallLogs' => array(self::HAS_MANY, 'SystemCallLog', 'device_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'device_id' => 'Device',
			'registration_id' => 'Registration',
			'user_id' => 'User',
			'email' => 'Email',
			'blocked' => 'Blocked',
			'recursive' => 'Recursive',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('registration_id',$this->registration_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('blocked',$this->blocked);
		$criteria->compare('recursive',$this->recursive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}