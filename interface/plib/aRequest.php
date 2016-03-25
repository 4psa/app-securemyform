<?php
/**
 * 4PSA - TS\Request\aRequest class 
 *
 * aRequest is an abstract class that defines the 
 * basic operations with HTTP requests
 *  
 * @package TS\Request
 * @copyright  Copyright (c) 2011 4PSA (www.4psa.com). All rights reserved.
 */

/**
 * TS\Request\aRequest
 *
 * aRequest is an abstract class that defines the 
 * basic operations with HTTP requests
 *
 * @package    TS\Request
 * @copyright  Copyright (c) 2011 4PSA (www.4psa.com). All rights reserved.
 */
abstract class aRequest {

	/**
	 * HTTP methods
	 * @var string 
	 */
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_DELETE = 'DELETE';
	const METHOD_PUT = 'PUT';
	
	/**
	 * Encoder for the body of the request
	 * @var string
	 */
	const ENCODER_JSON = 'json';
	const ENCODER_DEFAULT = 'dflt';
	
	/**
	 * Array containing the value from the cookie
	 * @var array
	 */
	protected $_cookies = array();
	
	/**
	 * Headers of the request
	 * @var array
	 */
	protected $_headers = array();
	
	/**
	 * The method used to send this request
	 * @var string
	 */
	protected $_method = null;

	/**
	 * Internal array with parameters
	 * @var array
	 */
	protected $_parameters = array();
	
	/**
	 * Body of the request
	 * @var string
	 */
	protected $_body = null;
	
	/**
	 * Files to be uploaded
	 * @var array
	 */
	protected $_files = array();
	
	/**
	 * Fetches the value of a header that was sent in the request
	 * 
	 * @param string $name the name of the header
	 * 
	 * @return mixed the value of the header
	 */
	public function getHeader($name){
		$name = (string)$name;
		if(isset($this->_headers[$name])) {
			return $this->_headers[$name];
		}
		return null;
	}
	
	/**
	 * Fetches all the headers that were sent in the request
	 * 
	 * @return array array containing all the headers from the request
	 */
	public function getHeaders(){
		return $this->_headers;
	}
	
	/**
	 * Fetches the method used when sending the request
	 * @example: GET, POST, etc...
	 * 
	 * @return string the method used by the request 
	 */
	public function getMethod(){
		return $this->_method;
	}
	
	/**
	 * Fetches the parameters of the request
	 * 
	 * @param array $names the names of the parameters to fetch
	 * 
	 * @return array the parameters of the request
	 */
	public function getParameters(array $names = array()){
		
		$params = array();
		if(!empty($names)) {
			foreach($names as $name) {
				if(isset($this->_parameters[$name])) {
					$params[$name] = $this->_parameters[$name];
				}
			}
		} else {
			$params = $this->_parameters;
		}
		return $params;
	}

	/**
	 * Parameters of the request
	 * 
	 * @param array $params array containing the parameters
	 * 
	 * @return boolean true  
	 */
	public function setParameters($params){
		foreach($params as $name => $value) {
			$this->_parameters[$name] = $value;
		}	
		return true;
	}
	
	/**
	 * Sets the parameters that are send in the body of the request
	 * @param array $params parms to be sent in the body of the request
	 */
	public function setBody($body, $encoder = null) {
		
		/* Init encoder */
		/* Encode body */
		/* Save the body internally */
		$bodyString = null;
		if(is_array($body)) {
			switch($encoder) {
				case self::ENCODER_JSON:
					$bodyString = json_encode($body);
					break;
				default:
					foreach($body as $name => $value) {
  						$bodyString .= $name.'='.$value.'&';
  					}
					$bodyString = trim($bodyString, '&');
					break;
			}
		} else {
			$bodyString = (string)$body;
		}
		$this->_body = $bodyString;
	}
	
	/**
	 * Add fiels to send in the request
	 * @param array $params fiels to be sent in the body of the request
	 */
	public function setFiles(array $files) {
		$this->_files = $files;
		
	}
	/**
	 * Sets the headers for the request 
	 * 
	 * @param string $name the name of the header
	 * @param mixed $value the value of the header
	 * 
	 * @return boolean TRUE
	 */
	public function setHeaders($headers){
		return $this->_headers = $headers;
	}
	
	/**
	 * Set the method of the request
	 * @param string $method the HTTP method
	 */
	public function setMethod($method){
		$this->_method = (string)$method;
	}
	
  /**
     * Makes the query to be used when requesting 
     * @return string
     */
    protected function _buildQuery(){
    	$parameters = $this->getParameters();
    	
    	$query = null;
		foreach($parameters as $name => $value) {
			$query .= '&'.$name.'='.$value;
		}
		$query = trim($query, '&');
		$query = '?'.$query;
		return $query;
    }
   		
	/**
	 * Redirects to the give url
	 * @param string $url the URL where to redirect
	 */
	public function redirect($url){
		
		$method = $this->getMethod();
		if($method == self::METHOD_POST) {
			/* No way to redirect to the third party, unless we use cURL and echo the output using a redirection status
			 * But is this a good approach? */
			$response = $this->_sendPost($url, $encoder);
			return $response;
		} elseif($method == self::METHOD_GET) {
			$msg = $this->_buildQuery();
			$msg = trim($msg, '?');
			
			$pos = strpos($msg, '?');
			if($pos !== 0 || $pos === false) {
				$url .= '?'.$msg;
			}
			
			header('Location:'.$url);
		}
		return true;
	}	
	
	/**
	 * Send POST requests
	 * 
	 * @return array the array with the response
	 */
	abstract protected function _sendPost($url);
	
	/**
	 * Send GET requests
	 * 
	 * @return string the response from the server 
	 */
	abstract protected function _sendGet($url);
	
	/**
	 * Send GET requests
	 * 
	 * @return string the response from the server 
	 */
	abstract protected function _sendPut($url);
	
	
	/**
	 * Send GET requests
	 * 
	 * @return string the response from the server 
	 */
	abstract protected function _sendDelete($url);
	
	
	/**
	 * Send a request to an external URL
	 * 
	 * @return string the response from the server 
	 */
	public function sendRequest($url){
		
		$method = $this->getMethod();
		switch($method) {
			case self::METHOD_GET:
				$ret = $this->_sendGet($url);
				break;
			case self::METHOD_POST:
				$ret = $this->_sendPost($url);
				break;
			case self::METHOD_PUT:
				$ret = $this->_sendPut($url);
				break;
			case self::METHOD_DELETE:
				$ret = $this->_sendDelete($url);
				break;
			default:
				$ret = null;
				break;
		}
		return $ret;
	}
}
?>