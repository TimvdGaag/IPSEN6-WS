<?php

class MessagesController extends Controller
{
//	   public function filters()
//	{
//		return array(
//			'restfilter',
//            'rights', // perform access control for CRUD operations
//		);
//	}
    
    public function actionGet(){
        
        //check if device is registerd
        //return data.
        
        $messages = Messages::model()->findAll();
        
        foreach ($messages as $message){
            var_dump($message->attributes);
        }
        
        exit;
    }
    
    public function actionPost(){
        
         try {
            SystemCallLog::newSystemCallLog($_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'], 1);
            $data = file_get_contents('php://input');
            
            if(strlen($data) < 1)
                throw new CHttpException(500, "Data not set");
            
            $message = json_decode($data);
            
            $newMessage = new Messages('create');
            $newMessage->body = $message->body;
            $newMessage->header = $message->header;
            $newMessage->footer = $message->footer;
            $newMessage->user_id = $message->user_id; // user id corrensponse with the user type 1  = admin, 2= student, 3 = teacher

            if(!$newMessage->save())
                $this->_sendResponse(200,'Message could not be saved');
            
            if(isset($message->to)){
                switch($message->to){
                    case 'all':
                        try{
                            $newMessage->pushMessage(User::All);
                        } catch(CHttpException $e){
                            $this->_sendResponse(200,'Message could not be send'. $e);
                        }
                        break;
                    case 'teachers':
                        try{
                            $newMessage->pushMessage(User::Teachers);
                        } catch(CHttpException $e){
                            $this->_sendResponse(200,'Message could not be send'. $e);
                        }
                        break;
                    case 'students':
                        try{
                            $newMessage->pushMessage(User::Students);
                        } catch(CHttpException $e){
                            $this->_sendResponse(200,'Message could not be send' . $e);
                        }
                        break;
                }
                $this->_sendResponse(200,'Message send to '.$message->to);
            }
            $this->_sendResponse(200,'Message saved');
            
        } catch (CHttpException $e){
            $this->_sendResponse($e->getStatus, $e->getMessages);
        }
        
    }
	
}