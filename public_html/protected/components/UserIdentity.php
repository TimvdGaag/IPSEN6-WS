<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
//	public function authenticate()
//	{
//		$users=array(
//			// username => password
//			//'demo'=>'demo',
//			//'admin'=>'admin',
//		);
//		if(!isset($users[$this->username]))
//			$this->errorCode=self::ERROR_USERNAME_INVALID;
//		else if($users[$this->username]!==$this->password)
//			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//		else
//			$this->errorCode=self::ERROR_NONE;
//		return !$this->errorCode;
//	}
	
	public function authenticate()
	{
		$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		
		if(!$this->username && !$this->password) //if neither username nor password supplied, return false.
			return !$this->errorCode;

		//try to find system.
		$user = User::model()->findByAttributes( array('name' => $this->username,'password' => $this->password, 'active' => 1));
		
		//$system = SystemClient::model()->findByAttributes( array('auth_key' => $this->username, 'active' => 1));
		
		if($user !== null){
			$this->errorCode = self::ERROR_NONE;
			$this->_id = $user->id;
			return !$this->errorCode;
		}
	}
	
	public function getId(){
		return $this->_id;
	}
}