<?php
class RestAuth {
	
	public static $authRealm = "amazing.webservice.nl";
	public static $authPass = "";
	
	public static function login($identityClass)
	{
		// figure out if we need to challenge the user
		if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
			self::send401();
		}
		
		// now, analyze the PHP_AUTH_DIGEST var
		$data = RestUtils::http_digest_parse($_SERVER['PHP_AUTH_DIGEST']);
		
		if (!$data || !isset($data['username'])) {
			self::send401();
		}
		
		$identity = new $identityClass($data['username'], self::$authPass);
		if(!$identity->authenticate() || $identity->errorCode !== UserIdentity::ERROR_NONE) {
			self::send401();
		}

		// so far, everything's good, let's now check the response a bit more...
		$A1 = md5($identity->username . ':' . self::$authRealm . ':' . $identity->password);
		$A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
		$validResponse = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);

		// last check..
		if ($data['response'] != $validResponse) {
			self::send401();
		}
		
		Yii::app()->user->login($identity); //GOOD, login!
		return true;
	}
	
	private static function send401()
	{
		header('WWW-Authenticate: Digest realm="' . self::$authRealm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5(self::$authRealm) . '"');
		header('HTTP/1.1 401 Unauthorized');
		RestUtils::sendResponse(401);
	}
}

?>