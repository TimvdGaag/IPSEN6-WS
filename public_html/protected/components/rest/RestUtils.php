<?php
class RestUtils {

	public static function sendResponse($status = 200, $body = '', $content_type = 'text/html', $charset = "utf-8") {
		
		$showSignature = false;
		
		
		$status_header = 'HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status);
		// set the status
		header($status_header);
		// set the content type
		if(preg_match("#(text)|(json)|(xml)|(html)#", $content_type) && $charset)
			$content_type .= "; charset=$charset";
		header('Content-type: ' . $content_type);

		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
			Yii::app()->end();
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';

			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of different status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}

			// this should be templatized in a real-world solution
			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
						<html>
							<head>
								<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'">
								<title>' . $status . ' ' . self::getStatusCodeMessage($status) . '</title>
							</head>
							<body>
								<h1>' . self::getStatusCodeMessage($status) . '</h1>
								<p>' . $message . '</p>{?signature}
							</body>
						</html>';

			
			$signature = '';
			if($showSignature) {
				// servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
				$signature = (!isset($_SERVER['SERVER_SIGNATURE']) || $_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
				$signature = "\r\n<hr />\r\n<address>$signature</address>";
			}
			$body = str_replace("{?signature}", $signature, $body);
			
			echo $body;
			Yii::app()->end();
		}
	}

	public static function getStatusCodeMessage($status) {
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	
	// function to parse the http auth header
	public static function http_digest_parse($txt) {

		// protect against missing data
		$needed_parts = array(
			'nonce'=>1,
			'nc'=>1,
			'cnonce'=>1,
			'qop'=>1,
			'username'=>1,
			'uri'=>1,
			'response'=>1
			);
		$data = array();

		preg_match_all('/(\w+)=([\'"])?([^\'",]+)/i', $txt, $matches, PREG_SET_ORDER);
		
		foreach ($matches as $m) {
			$data[$m[1]] = $m[3] ? $m[3] : $m[4];
			unset($needed_parts[$m[1]]);
		}
		
		//fix empty username (send OK without entering data)
		if(isset($needed_parts['username'])) {
			$data['username'] = '';	
			unset($needed_parts['username']);
		}
		
		if(count($needed_parts) > 0) {
			RestUtils::sendResponse (401, 'Invalid auth header. ' . print_r($needed_parts, true));
		}
		
		return $data;
	}
	
}

?>