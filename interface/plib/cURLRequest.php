<?php
/**
 * 4PSA -TS\Request\cURLRequest class 
 *
 * aRequest is an abstract class that defines the way to send 
 * requests using cURL
 *  
 * @package TS\Request
 * @copyright  Copyright (c) 2011 4PSA (www.4psa.com). All rights reserved.
 */

/**
 * Require external files
 */
require_once('aRequest.php');
require_once('Response.php');

/**
 *TS\Request\cURLRequest
 *
 * aRequest is an abstract class that defines the way to send 
 * requests using cURL
 *
 * @package    TS\Request
 * @copyright  Copyright (c) 2011 4PSA (www.4psa.com). All rights reserved.
 */
class cURLRequest extends aRequest {
	
	/**
	 * Sends a POST request
	 * @param string $url the URL where to send the request
	 * @param string $encoder the encoder for the body if the request
	 * @seeTS\Request\aRequest::_sendPost()
	 */
	protected function _sendPost($url, $encoder = null){
		
		$result = $this->_runPostRequest($url);
		return $result;
	}
	
	/**
	 * Sends a GET request
	 * @param string $url the URL where to send the request
	 * @seeTS\Request\aRequest::_sendPost()
	 */
	protected function _sendGet($url){
		
		$result = $this->_runGetRequest($url);
		return $result;
	}
	
	
	/**
	 * Sends a GET request
	 * @param string $url the URL where to send the request
	 * @seeTS\Request\aRequest::_sendPost()
	 */
	protected function _sendPut($url){
		
		$result = $this->_runPutRequest($url);
		return $result;
	}
	
	
	/**
	 * Sends a GET request
	 * @param string $url the URL where to send the request
	 * @seeTS\Request\aRequest::_sendPost()
	 */
	protected function _sendDelete($url){
		
		$result = $this->_runDeleteRequest($url);
		return $result;
	}
	
	
	
    /**
     * Initialize the cURL tool 
     * @param string $url the URL where to send the request
     * @return 
     */
    private function _initRequest($url){
    	
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		
	
		
		
		/* We must send as string when using the application content type */
		$postData = $this->_body;
		//print_r("__POST_DATA__\n");
		//print_r($postData);
		//print_r("\n__POST_DATA__\n");
		
		$files = $this->_files;
		if(!empty($files)) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			$files['request'] = $postData;
			curl_setopt($ch, CURLOPT_POSTFIELDS, $files);
		} else{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}
		
		$headers = $this->getHeaders();

		$_headers = array();
		foreach($headers as $name => $value) {
			$_headers[] = $name.':'.$value;
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
		return $ch;
    }
    
     /**
     * Run a POST request using cURL
     * @param string $url the URL where to send the request
     * @returnTS\Request\Response 
     */
    private function _runGetRequest($url) {
    	
    	$url .= $this->_buildQuery();
    	
    	$ch = $this->_initRequest($url);
    	
    	curl_setopt($ch, CURLOPT_HTTPGET, true);
    	$response = $this->_getResponse($ch);
    	return $response;
    }
    
    /**
     * Run a POST request using cURL
     * @param string $encoder the encoder for the body if the request 
     * @returnTS\Request\Response 
     */
  	private function _runPostRequest($url, $encoder = null) {
  		
  		$url .= $this->_buildQuery();
  		
		$ch = $this->_initRequest($url, self::METHOD_POST);
		if(empty($this->_files)) {
			curl_setopt($ch, CURLOPT_POST, true);
		}
		$response = $this->_getResponse($ch);
    	return $response;	
    }
    
    
   /**
     * Run a POST request using cURL
     * @param string $encoder the encoder for the body if the request 
     * @returnTS\Request\Response 
     */
  	private function _runPutRequest($url, $encoder = null) {
  		
  		$url .= $this->_buildQuery();
		$ch = $this->_initRequest($url);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		$response = $this->_getResponse($ch);
    	return $response;	
    }
    
    
   /**
     * Run a POST request using cURL
     * @param string $encoder the encoder for the body if the request 
     * @returnTS\Request\Response 
     */
  	private function _runDeleteRequest($url, $encoder = null) {
  		
  		$url .= $this->_buildQuery();
		$ch = $this->_initRequest($url);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		$response = $this->_getResponse($ch);
    	return $response;	
    }
    
    /**
     * Fetches a response instance
     * @param string $ch cURL resource
     * @returnTS\Request\Response
     * @throws Exception
     */
	private function _getResponse($ch) {
		$respData = curl_exec($ch);
	    $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	    $respStatus = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $errorNum = curl_errno($ch);
	    $error = curl_error($ch);
	    curl_close($ch);
	    if ($errorNum != CURLE_OK) {
	    	throw new \Exception('HTTP Error: (' . $respStatus . ') ' . $error);
	    }
	   
	    // Parse out the raw response into usable bits
	    $rawHeaders = substr($respData, 0, $respHeaderSize);
	    $responseBody = substr($respData, $respHeaderSize);
	    $responseHeaderLines = explode("\r\n", $rawHeaders);
	    $responseHeaders = array();
	    $header = null;
	    foreach ($responseHeaderLines as $headerLine) {
	    	if ($headerLine && strpos($headerLine, ':') !== false) {
	        	list($header, $value) = explode(': ', $headerLine, 2);
	        	if (isset($responseHeaders[$header])) {
	          		$responseHeaders[$header] .= "\n" . $value;
	        	} else {
	          		$responseHeaders[$header] = $value;
	        	}
	      	}
	    }
    	
		$response = new Response($respStatus, $responseHeaders, $responseBody);

		return $response;
	}
    
	
	
}