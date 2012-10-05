<?php

/**
 * This is the model class for table "Messages".
 *
 * The followings are the available columns in table 'Messages':
 * @property integer $id
 * @property string $header
 * @property string $body
 * @property string $footer
 * @property string $type
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Messages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Messages the static model class
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
		return 'Messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>1),
			array('header, body, footer', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, header, body, footer, type, user_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'header' => 'Header',
			'body' => 'Body',
			'footer' => 'Footer',
			'type' => 'Type',
			'user_id' => 'User',
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
		$criteria->compare('header',$this->header,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('footer',$this->footer,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function pushMessage($type){
        
        SystemCallLog::newSystemCallLog($_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'], 1);
        
        $devices = null;
        
        switch($type){
            case 1:
            case 2:
            case 3:
                $devices = Device::model()->findAllByAttributes(array('user_id' => $type,'blocked' => 0));
            break;
            case 0:
                $devices = Device::model()->findAllByAttributes(array('blocked' => 0));
            break;
            default:
                throw new Exception("type must be set");
            break;
        }
        $succes = true;
        $gcm = new GCMClass();
        $gcm->setDevices($devices);
        $gcm->setMessage($this);
        if(!$gcm->sendToGCM()){
            $response = $gcm->getResponse();
            return false;
        }
        
        //if($succes == false) 
        //    throw new CHttpException(500,"we had an error" .$response);
        
        return true;
    }
}