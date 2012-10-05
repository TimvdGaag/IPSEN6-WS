<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GCMClass
 *
 * @author Tim
 */
class GCMClass extends CComponent {
    
    private $_rest;
    public $message;
    public $devices;
    public $response;
    public $responseInfo;
    private $_item;
    private $_key = "AIzaSyD1MYUvt27nRivSBcKhZq2jl1TVjcyMSCY";

    public function __construct(){
        $this->_rest = new REST("https://android.googleapis.com/gcm/send");
    }
    
    public function setDevices( $devices){
        $this->devices = $devices;
    }
    public function setMessage( $message){
        $this->message = $message;
    }
    public function getResponse(){
        return $this->response;
    }
    
    public function sendToGCM(){
        if($this->message == null)
            throw new Exception("messages is not set");
        
//        if($this->devices == null)
//            throw new Exception("devices is not set");
        
        $this->createDataItem();
        $this->setHeaders();
        
        $this->_rest->post($this->_item);
        $curlInfo = $this->_rest->getCurlInfo();
        $this->response = $this->_rest->responseBody;
        $this->responseInfo = $this->_rest->responseInfo;
        
//        if(YII_DEBUG){
//            echo '<pre>';
//            var_dump($curlInfo);
//            var_dump($this->responseInfo);
//            var_dump($this->response);
//            echo '</pre>';
//            exit;
//        }
        
        if($this->responseInfo->http_code != 200 ){
            return false;
        }
        
        return true;
    }
    
    public function createDataItem(){
        
//        $this->_item = new stdClass();
//        $this->_item->registration_ids = $this->buildRegistrationIdString();
//        $this->_item->data = new stdClass;
//        $this->_item->data->header = "test h";
//        $this->_item->data->body = "test b";
//        $this->_item->data->footer = "test f";
        
        $this->_item =array();
        $this->_item['registration_ids'] = $this->buildRegistrationIdString();
        $this->_item['data'] = array();
        $this->_item['data']['header'] = $this->message->header;
        $this->_item['data']['body'] = $this->message->body;
        $this->_item['data']['footer'] = $this->message->footer;
        
    }

    public function setHeaders(){
        $headers  = array ('Authorization: key='.$this->_key,'Content-Type: application/json','Accept: application/json');
        $this->_rest->setHeaders($headers);
    }
    
    private function buildRegistrationIdString(){
        
        $registration_ids = array();
        foreach($this->devices as $device)
            $registration_ids[] = $device->registration_id;
        
        return $registration_ids;
    }
}

?>
