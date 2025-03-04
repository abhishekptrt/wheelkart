<?php
/**
 * Copyright (c) 2007-2011, Servigistics, Inc.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *  - Neither the name of Servigistics, Inc. nor the names of
 *    its contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2007-2011 Servigistics, Inc. (http://servigistics.com)
 * @license http://solr-php-client.googlecode.com/svn/trunk/COPYING New BSD
 * @version $Id$
 *
 * @package Apache
 * @subpackage Solr
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>, Donovan Jimenez <djimenez@conduit-it.com>
 */

// Require Apache_Solr_HttpTransport_Abstract
require_once(dirname(__FILE__) . '/Abstract.php');

/**
 * A Curl based HTTP transport. Uses a single curl session for all requests.
 */
class Apache_Solr_HttpTransport_Curl extends Apache_Solr_HttpTransport_Abstract
{
	/**
	 * SVN Revision meta data for this class
	 */
	const SVN_REVISION = '$Revision:$';

	/**
	 * SVN ID meta data for this class
	 */
	const SVN_ID = '$Id$';

	/**
	 * Curl Session Handle
	 *
	 * @var resource
	 */
	private $_curl;

	/**
	 * Initializes a curl session
	 */
	public function __construct()
	{
		// initialize a CURL session
		$this->_curl = curl_init();

		// set common options that will not be changed during the session
		curl_setopt_array($this->_curl, array(
			// return the response body from curl_exec
			CURLOPT_RETURNTRANSFER => true,

			// get the output as binary data
			CURLOPT_BINARYTRANSFER => true,

			// we do not need the headers in the output, we get everything we need from curl_getinfo
			CURLOPT_HEADER => false
		));
	}

	/**
	 * Closes a curl session
	 */
	function __destruct()
	{
		// close our curl session
		curl_close($this->_curl);
	}

	public function performGetRequest($url, $timeout = false)
	{
		// check the timeout value
		if ($timeout === false || $timeout <= 0.0)
		{
			// use the default timeout
			$timeout = $this->getDefaultTimeout();
		}

		// set curl GET options
		curl_setopt_array($this->_curl, array(
			// make sure we're returning the body
			CURLOPT_NOBODY => false,

			// make sure we're GET
			CURLOPT_HTTPGET => true,

			// set the URL
			CURLOPT_URL => $url,

			// set the timeout
			CURLOPT_TIMEOUT => $timeout
		));

		// make the request
		$responseBody = curl_exec($this->_curl);

		// get info from the transfer
		$statusCode = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($this->_curl, CURLINFO_CONTENT_TYPE);

		return new Apache_Solr_HttpTransport_Response($statusCode, $contentType, $responseBody);
	}

	public function performHeadRequest($url, $timeout = false)
	{
		// check the timeout value
		if ($timeout === false || $timeout <= 0.0)
		{
			// use the default timeout
			$timeout = $this->getDefaultTimeout();
		}

		// set curl HEAD options
		curl_setopt_array($this->_curl, array(
			// this both sets the method to HEAD and says not to return a body
			CURLOPT_NOBODY => true,

			// set the URL
			CURLOPT_URL => $url,

			// set the timeout
			CURLOPT_TIMEOUT => $timeout
		));

		// make the request
		$responseBody = curl_exec($this->_curl);

		// get info from the transfer
		$statusCode = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($this->_curl, CURLINFO_CONTENT_TYPE);

		return new Apache_Solr_HttpTransport_Response($statusCode, $contentType, $responseBody);
	}

	public function performPostRequest($url, $postData, $contentType, $timeout = false)
	{
		// check the timeout value
		if ($timeout === false || $timeout <= 0.0)
		{
			// use the default timeout
			$timeout = $this->getDefaultTimeout();
		}

		// set curl POST options
		curl_setopt_array($this->_curl, array(
			// make sure we're returning the body
			CURLOPT_NOBODY => false,

			// make sure we're POST
			CURLOPT_POST => true,

			// set the URL
			CURLOPT_URL => $url,

			// set the post data
			CURLOPT_POSTFIELDS => $postData,

			// set the content type
			CURLOPT_HTTPHEADER => array("Content-Type: {$contentType}"),

			// set the timeout
			CURLOPT_TIMEOUT => $timeout
		));

		// make the request
		$responseBody = curl_exec($this->_curl);

		// get info from the transfer
		$statusCode = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($this->_curl, CURLINFO_CONTENT_TYPE);

		return new Apache_Solr_HttpTransport_Response($statusCode, $contentType, $responseBody);
	}
}