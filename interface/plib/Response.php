<?php
/**
 * 4PSA Framework - TS\Request\Response class 
 *
 * This handles the response receoved from the Authorization Server 
 *  
 * @package TS\Request
 * @copyright Copyright (c) $DateTime: 2012/08/28 14:24:46 $ 4PSA (www.4psa.com). All rights reserved.
 */


/**
 * TS\Request\Response
 * 
 * This handles the response receoved from the Authorization Server 
 * 
 * @package    TS\Request
 * @copyright  Copyright (c) 2011 4PSA (www.4psa.com). All rights reserved.
 */
class Response {
	
	/**
	 * Available HTTP statuses
	 * @var integer
	 */
	const STATUS_OK = 200;
	const STATUS_FOUND = 302;
	const STATUS_NOT_MODIFIED = 304;
	const STATUS_UNAUTHORIZED = 401;
	const STATUS_BAD_REQUEST = 400;
	const STATUS_FORBIDDEN = 403;
	const STATUS_NOT_FOUND = 404;
	const STATUS_LIMIT_EXCEEDED = 409;
	const STATUS_REQUEST_TOO_LARGE = 413;
	const STATUS_INTERNAL_SERVER_ERROR = 500;
	const STATUS_NOT_IMPLEMENTED = 501;
	
	/**
	 * Status descriptions
	 * @var array
	 */
	private static $_statusDesc = array(
			self::STATUS_FOUND => 'Found',
			self::STATUS_UNAUTHORIZED => 'Unauthorized',
			self::STATUS_OK => 'OK',
			self::STATUS_BAD_REQUEST => 'Bad Request',
			self::STATUS_FORBIDDEN => 'Forbidden',
			self::STATUS_NOT_MODIFIED => 'Not Modified',
			self::STATUS_INTERNAL_SERVER_ERROR => 'Internal Server Error',
			self::STATUS_REQUEST_TOO_LARGE => 'Request Too Large',
			self::STATUS_LIMIT_EXCEEDED => 'Limit Exceeded',
			self::STATUS_NOT_FOUND => 'Not Found',
			self::STATUS_NOT_IMPLEMENTED => 'Not Implemented'
			
	);
	
	/**
	 * HTTP headers
	 * @var array
	 */
	private $_headers = null;
	
	/**
	 * The status of the response 
	 * @var integer
	 */
	private $_status = null;
	
	/**
	 * The content type of the request
	 * taken from the Content-Type header
	 * @var string
	 */
	private $_cntType = null;
	
	/**
	 * Initializes a response object
	 * @param string $status the status of the response
	 * @param array $headers the headers of the response
	 * @param string $body the body of the response
	 */
	public function __construct($status, $headers, $body){
		$this->setStatus($status);
		$this->setHeaders($headers);
		$this->setBody($body);
		
		$type = explode(';', $this->_headers['Content-Type']);
		$this->_cntType = $type[0]; 
	}
	
	/**
	 * Gets the content type of the response
	 * from the Content-Type header
	 * @return string 
	 */
	public function getContentType(){
		return $this->_cntType;
	}
	
	/**
	 * Sets a header that is sent in the response
	 * @param string $name the name of the header
	 * @param string $value the value of the header
	 * @return TRUE if the header was set, FALSE otherwise
	 */
	public function setHeaders($headers){
		foreach($headers as $name => $value) {
			$this->_headers[$name] = $value;
		}
	}
	
	/**
	 * Return the headers of the response 
	 * @param array $names the name of headers
	 * @return array with headers 
	 */
	public function getHeaders(array $names = array()) {
		if(empty($names)) {
			return $this->_headers;
		}
		$values = array();
		foreach($names as $name) {
			if(isset($this->_headers[$name])) {
				$values[$name] = $this->_headers[$name];
			}
		}
		return $values;
	}
	
	/**
	 * Sets the status that is sent in the response
	 * 
	 * @param integer $status the status of the response
	 * 
	 * @return TRUE if the status was sent, FALSE otherwise 
	 */
	public function setStatus($status){
		$this->_status = (int)$status;
	}
	
	/**
	 * Get the status that is sent in the response
	 * 
	 * @return TRUE if the status was sent, FALSE otherwise 
	 */
	public function getStatus(){
		return $this->_status;
	}
	
	
	
	/**
	 * Sets the body of the response
	 * @param string $body the body of the response
	 */
	public function setBody($body){
		$this->_body = $body;
	}
	
	/**
	 * Returns the body of the request
	 * Decodes the body using the format transmitted in the Content-Type header
	 * @param boolean $raw if set to true the body is not decoded
	 * @return string $body the body of the request
	 */
	public function getBody($raw = false){
		if($raw) {
			return $this->_body;
		}
		return $this->_decode($this->_body);
	}

	/**
	 * Decodes the body taking in consideration the value 
	 * of the Content-Type header sent
	 * @todo: Is best to implement a decoder to handle this
	 *  
	 * @param string $body
	 * 
	 * @return mixed the decoded body
	 */
	private function _decode($body) {
		
		/* Remove the charset from the content type header */
		$type = explode(';', $this->_headers['Content-Type']);
		$type = $type[0]; 
		
		switch($type) {
			case 'application/json':
				$ret = json_decode($body, true); 
				break;
			default:
				$ret = $body;
		}
		
		return $ret;
	}

	/**
	 * Returns the description of the HTTP status
	 * @param string $statusCode the HTTP status code
	 */
	public static function getStatusDescript($statusCode){
		return isset(self::$_statusDesc[$statusCode]) ? self::$_statusDesc[$statusCode] : null;
	}
	
	
}
?>