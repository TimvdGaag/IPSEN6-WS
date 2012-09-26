<?php
/**
 * holds the rest class
 *
 * @internal
 * @package Default
 * @author Tim van der Gaag
 * @email tim.vandergaag@fonq.nl
 * @company Fonq.nl B.V.
 * @copyright Fonq.nl B.V.
 */

/**
 * REST class
 *
 * @internal
 * @author Tim.vandergaag
 * @email tim.vandergaag@fonq.nl
 * @company Fonq.nl B.V.
 * @copyright 2012 Fonq.nl B.V.
 */
class REST extends CComponent{
	
	/** apiKey
	 * @var string */
	public $apiKey;
	/** server url
	 * @var string */
	public $server;
	/** curl
	 * @var CurlHandle */
	public $curl;
	/** response body
	* @var string */
	public $responseBody;
	/** response info
	 * @var array */
	public $responseInfo;
	/** user class
	 * @var Api\User|ApiLite\User */
	public $user;
	/** password
	 * @var string */
	public $password;
	/** action url
	 * @var string */
	public $action;
	/** accept type header
	 * @var string */
	private $acceptType = 'application/json';
    
    public $headers;
	/**
	 * Creat new REST instance
	 * 
	 * @api
	 * @param string $server server_url
	 * @since version 1.0
	 */
	public function __construct($server){
		$this->server = $server;
		$this->curl = curl_init();
	}
	
	/**
	 * destruct REST instance
	 * 
	 * @api
	 * @since version 1.0
	 * @return void
	 */
	public function __destruct(){
		curl_close($this->curl);
	}
	
	/**
	 * sends a post to the server
	 * 
	 * @param Mixed $item postable item
	 * @api
	 * @since version 1.0
	 * @return void 
	 */
	public function post($item){
        
		if(!is_string($item)){
			$jsonobject = addslashes(json_encode($item));
		}
		
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $jsonobject);		

		$this->execute();
	}
	
	public function checkBOM(){
        // check if file is utf-8 without bom
	}

	/**
	 * make a get request to the server
	 * 
	 * @api
	 * @return void
	 * @since version 1.0
	 */
	public function get(){
		$this->execute();
	}
	
	/**
	 * set the action that should be preformed
	 * 
	 * @api
	 * @param string $action action
	 * @since version 1.0
	 */
	public function setAction($action){
		$this->action = $action;
	}
	
	/**
	 * execute statment
	 * 
	 * @internal
	 * @since version 1.0
	 */
	private function execute(){
	
		$this->setCurlOpts();
		$this->responseBody = curl_exec($this->curl);
		$this->responseInfo = curl_getinfo($this->curl);

	}
	
    public function setHeaders($headers = array ('Accept: application/json')){
        $this->headers = $headers;
    }
    
	/**
	 * set curl opts
	 * 
	 * @internal
	 * @return void
	 */
	private function setCurlOpts(){
        
        if(empty($this->headers))
            $this->setHeaders();

		//curl_setopt($this->curl, CURLOPT_TIMEOUT, 10); 
		curl_setopt($this->curl, CURLOPT_URL, $this->server); 
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true ); 
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

		
		//curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);  
		//curl_setopt($this->curl, CURLOPT_USERPWD, ":" ); 
		//curl_setopt($this->curl, CURLOPT_USERPWD, $this->password.":" ); 
		
	}
}
?>
