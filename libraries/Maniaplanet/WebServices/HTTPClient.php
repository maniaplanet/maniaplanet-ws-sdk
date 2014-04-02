<?php
/**
 * Maniaplanet Web Services SDK for PHP
 *
 * @see		    http://code.google.com/p/maniaplanet-ws-sdk/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @author      $Author$:
 * @version     $Revision$:
 * @date        $Date$:
 */

namespace Maniaplanet\WebServices;

/**
 * HTTP client used to requests on the Maniaplanet Web Services API.
 * Service classes of the SDK extends this base class.
 */
abstract class HTTPClient
{
	const VERSION = '5.0.5';

	private static $HTTPStatusCodes = array(
		100 => 'Continue',
		200 => 'Sucess',
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
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
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
		503 => 'Service Unavailable',
	);

	/**
	 * URL of the Web Services API
	 *
	 * @var string
	 */
	protected $APIURL = 'https://ws.maniaplanet.com';

	/**
	 * HTTP username used to authenticate the client via HTTP Basic Authentication
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * HTTP password used to authenticate the client via HTTP Basic Authentication
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * Whether to use HTTP Basic authentication. Enabled by default
	 *
	 * @var bool
	 */
	protected $enableAuth = true;

	/**
	 * Whether to throw exceptions or not. Default is true except in the ManiaHome class.
	 *
	 * @var bool
	 */
	protected $throwExceptions = true;

	/**
	 * Last exception if throwExceptions is set to false
	 *
	 * @var \Maniaplanet\WebServices\Exception
	 */
	public $lastException;

	/**
	 * Content-Type HTTP Header
	 *
	 * @var callback
	 */
	protected $contentType = 'application/json';

	/**
	 * Accept HTTP header
	 *
	 * @var callback
	 */
	protected $accept = 'application/json';

	/**
	 * Callback for serializing data
	 *
	 * @var callback
	 */
	protected $serializeCallback = 'json_encode';

	/**
	 * Callback for unserializing data received by the API
	 */
	protected $unserializeCallback = 'json_decode';
	
	/**
	 * Logging request if it takes more than X ms
	 * 
	 * @var int 
	 */
	protected $slowRequestThreshold;

	/**
	 * Additional headers to be sent with the requests
	 *
	 * @var array[string]
	 */
	protected $headers = array();

	/**
	 * Default constructor. Children classes should, if they need to override it,
	 * keep the same first two parameters (the API credentials) to keep the usage of the SDK simple.
	 *
	 * You can manage your API credentials at http://developers.trackmania.com
	 *
	 * @param string $username API username
	 * @param string $password API password
	 */
	function __construct($username = null, $password = null, $slowRequestThreshold = null)
	{
		if(!function_exists('curl_init'))
		{
			trigger_error('You must activate the CURL PHP extension.', E_USER_ERROR);
		}
		if(!function_exists('json_encode'))
		{
			trigger_error('You must activate the JSON PHP extension.', E_USER_ERROR);
		}

		// If you're using ManiaLib, credentials can be automatically loaded
		if(!$username && !$password && class_exists('\ManiaLib\WebServices\Config'))
		{
			$config = \ManiaLib\WebServices\Config::getInstance();
			$username = $config->username;
			$password = $config->password;
			$slowRequestThreshold = $config->slowRequestThreshold;
		}

		$this->username = $username;
		$this->password = $password;
		$this->slowRequestThreshold = $slowRequestThreshold;
	}

	/**
	 * Executes a HTTP request on the API.
	 *
	 * The usage of the $ressource and $params parameters is similar to the use
	 * of the sprintf() function. You can PUT sprintf() placeholders in the
	 * $ressource, and the first elements of the $params array will be
	 * urlencode'd and sprintf()'ed in the ressource. The last element of the
	 * $params array will be serialized and used for request body if using
	 * POST or PUT methods.
	 *
	 * Examples:
	 * <code>
	 * $obj->execute('GET', '/stuff/%s/', array('foobar')); // => /stuff/foobar/
	 * $obj->execute('GET', '/stuff/%s/', array('foo bar')); // => /stuff/foo%20bar/
	 * $obj->execute('GET', '/stuff/%s/%d/', array('foobar', 1)); // => /stuff/foobar/1/
	 * $obj->execute('POST', '/stuff/', array($someDataToPost)); // => /stuff/
	 * $obj->execute('POST', '/stuff/%s/', array('foobar', $someDataToPost)); // => /stuff/foobar/
	 * </code>
	 *
	 * @param string $method The HTTP method to use. Only GET, POST, PUT and DELETE are supported for now.
	 * @param string $ressource The ressource (path after the URL + query string)
	 * @param array $params The parameters
	 * @return mixed The unserialized API response
	 * @throws \Maniaplanet\WebServices\Exception
	 */
	protected function execute($method, $ressource, array $params = array())
	{
		$this->lastException = null;

		$url = $this->APIURL.$ressource;

		// If we need a request body, it's the last element of the params array
		// Otherwise it's null
		if($method == 'POST' || $method == 'PUT')
		{
			$data = array_pop($params);
			if($this->serializeCallback)
			{
				$data = call_user_func($this->serializeCallback, $data);
			}
		}
		else
		{
			$data = null;
		}

		// The rest of the params array is urlencode'd and sprintf'ed
		// into the ressource itself
		if($params)
		{
			$params = array_map('urlencode', $params);
			array_unshift($params, $url);
			$url = call_user_func_array('sprintf', $params);
		}

		// Let's prepare the CURL request options and HTTP header
		$options = array();
		$header = array();

		$header['accept'] = 'Accept: '.$this->accept;
		$header['content-type'] = 'Content-type: '.$this->contentType;
		$header = array_merge($header, $this->headers);

		switch($method)
		{
			case 'GET':
				// Nothing to do
				break;

			case 'POST':
				$options[CURLOPT_POST] = true;
				$options[CURLOPT_POSTFIELDS] = $data;
				break;

			case 'PUT':
				$fh = fopen('php://temp', 'rw');
				fwrite($fh, $data);
				rewind($fh);
				$options[CURLOPT_PUT] = true;
				$options[CURLOPT_INFILE] = $fh;
				$options[CURLOPT_INFILESIZE] = strlen($data);
				break;

			case 'DELETE':
				$options[CURLOPT_POST] = true;
				$options[CURLOPT_POSTFIELDS] = '';
				$options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
				break;

			default:
				throw new \InvalidArgumentException('Unsupported HTTP method: '.$method);
		}

		$options[CURLOPT_URL] = $url;
		$options[CURLOPT_HTTPHEADER] = $header;
		if($this->enableAuth && $this->username)
		{
			$options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
			$options[CURLOPT_USERPWD] = $this->username.':'.$this->password;
		}
		$options[CURLOPT_TIMEOUT] = 10;
		$options[CURLOPT_RETURNTRANSFER] = true;
		$options[CURLOPT_USERAGENT] = 'maniaplanet-ws-sdk/'.self::VERSION;

		$options[CURLOPT_SSL_VERIFYHOST] = 0;
		$options[CURLOPT_SSL_VERIFYPEER] = 0;

		try
		{
			$ch = curl_init();
			curl_setopt_array($ch, $options);
			$responseBody = curl_exec($ch);
			$responseBodyRaw = $responseBody;
			$responseInfo = curl_getinfo($ch);
			if($this->slowRequestThreshold)
			{
				$mtime = round($responseInfo['total_time'] * 1000);
				if($mtime > $this->slowRequestThreshold)
				{
					$message = sprintf('%s ms: %s %s', $mtime, $method, $url);
					\ManiaLib\Utils\Logger::info($message);
				}
			}
			$curlError = curl_error($ch);
			$curlErrorNo = curl_errno($ch);
			curl_close($ch);
		}
		catch(\Exception $e)
		{
			if($ch)
			{
				curl_close($ch);
			}
			throw $e;
		}

		if($responseInfo['http_code'] == 200)
		{
			if($responseBody)
			{
				if($this->unserializeCallback)
				{
					$responseBody = call_user_func($this->unserializeCallback, $responseBody);
				}
			}
			return $responseBody;
		}
		else
		{
			$message = $curlError;
			$code = $curlErrorNo;
			$statusCode = $responseInfo['http_code'];
			$statusMessage = null;

			if(array_key_exists($statusCode, self::$HTTPStatusCodes))
			{
				$statusMessage = self::$HTTPStatusCodes[$statusCode];
			}

			if($responseBody)
			{
				if($this->unserializeCallback)
				{
					$responseBody = call_user_func($this->unserializeCallback, $responseBody);
				}
				if(is_object($responseBody))
				{
					if(property_exists($responseBody, 'message'))
					{
						$message = $responseBody->message;
					}
					elseif(property_exists($responseBody, 'error'))
					{
						$message = $responseBody->error;
					}
					if(property_exists($responseBody, 'code'))
					{
						$code = $responseBody->message;
					}
				}
			}

			$exception = new Exception($message, $code, $statusCode, $statusMessage);

			if($this->throwExceptions)
			{
				throw $exception;
			}
			else
			{
				$this->lastException = $exception;
				return false;
			}
		}
	}

}

?>