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
                throw new CHttpException(500, "Data not set");
            
            $phone = json_decode($data);

            if(device::model()->findByAttributes( array('device_id' => $phone->device_id)))
                $this->_sendResponse(200,'Phone_already_registerd');

            $newDevice = new device('create');
            $newDevice->device_id = $phone->device_id;
            $newDevice->registration_id = $phone->registration_id;
            $newDevice->email = $phone->email;
            $newDevice->user_id = $phone->type; // user id corrensponse with the user type 1  = admin, 2= student, 3 = teacher

            if($newDevice->save())
                $this->_sendResponse(200,'Phone_registerd');
            
        } catch (CHttpException $e){
            $this->_sendResponse($e->getStatus, $e->getMessages);
        }
    }
}