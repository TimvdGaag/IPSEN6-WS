<?php

class RegisterController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionGet($device_id){
        
        
        
    }
    
    public function actionPost(){
        try {
            SystemCallLog::newSystemCallLog($_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'], 1);
            $data = file_get_contents('php://input');
            
            if(strlen($data) < 1)
                $this->_sendResponse(200,'Data_not_set');
            
            $phone = json_decode($data);
			
            if(Device::model()->findByAttributes( array('device_id' => $phone->device_id)))
                $this->_sendResponse(500,'Phone_already_registerd');
			

            $newDevice = new Device('create');
            $newDevice->device_id = substr($phone->device_id ,0 ,45);
            $newDevice->registration_id = $phone->registration_id;
            $newDevice->email = $phone->email;
            $newDevice->user_id = $phone->type; // user id corrensponse with the user type 1  = admin, 2= student, 3 = teacher
			
            if($newDevice->save())
                $this->_sendResponse(200,'Phone_registerd');
            else 
				$this->_sendResponse(400,'Not saved');
				
        } catch (CHttpException $e){
            $this->_sendResponse($e->getStatus, $e->getMessages);
        }
    }
}