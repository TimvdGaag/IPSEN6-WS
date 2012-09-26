<?php


/*
* Copyright: 2011 - Fonq Ecommerce B.V.
*/

/**
* ComSystemIdentity represents the data needed to identity a Communication System.
* It contains the authentication method that checks if the provided
* data can identity the user.
*/
class RestIdentity extends CUserIdentity
{
	/**
	* Authenticates a REST user.
	*/
	private $_id;

    public function authenticate($weblogin = false)
	{
        $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

		if(!$this->username && !$this->password) //if neither username nor password supplied, return false.
			return !$this->errorCode;
        
        if($weblogin){
            $user = SystemClient::model()->findByAttributes( array('auth_key' => $this->password,'name'=> $this->username, 'active' => 1));
            	
            if($user !== null){
                $this->errorCode = self::ERROR_NONE;
                $this->_id = $user->name;
                Yii::app()->user->setState('__uid',$user->id);
                return !$this->errorCode;
            }
		} else {
            $user = SystemClient::model()->findByAttributes( array('auth_key' => $this->username, 'active' => 1));
            	
            if($user !== null){
                $this->errorCode = self::ERROR_NONE;
                $this->_id = $user->name;
                Yii::app()->user->setState('__uid',$user->id);
                // id setten voor app en rights
                // 
                return !$this->errorCode;
            }
        }
	
	}

	public function getId(){
		return $this->_id;
	}
}
?>
