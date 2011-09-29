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

namespace Maniaplanet\WebServices\ManiaConnect;

/**
 * OAuth2 client implementation
 */
abstract class Client extends \Maniaplanet\WebServices\HTTPClient
{
	/**
	 * OAuth2 Authorization endpoint URL
	 * 
	 * @var string
	 */
	const AUTHORIZATION_URL = 'https://ws.maniaplanet.com/oauth2/authorize/';
	/**
	 * Logout URL
	 * 
	 * @var string
	 */
	const LOGOUT_URL = 'https://ws.maniaplanet.com/oauth2/authorize/logout/';
	/**
	 * Path for the OAuth2 Token Endpoint on our API
	 * 
	 * @var string
	 */
	const TOKEN_PATH = '/oauth2/token/';

	/**
	 * An implementation of the Peristance interface to store data (such as 
	 * access tokens) between requests. Default implementation is using PHP
	 * session but you can easily write your own if needed.
	 * 
	 * @param \Maniaplanet\WebServices\ManiaConnect\Persistance
	 */
	static protected $persistance;

	static function setPersistance(Persistance $object)
	{
		if(self::$persistance)
		{
			throw new \Maniaplanet\WebServices\Exception(
				'You must set the persistance object before instanciating the '.
				'services.');
		}
		self::$persistance = $object;
	}

	function __construct($username = null, $password = null)
	{
		parent::__construct($username, $password);

		// Default persistance is using the PHP sessions
		if(!self::$persistance)
		{
			self::$persistance = new Session();
		}

		self::$persistance->init();
	}

	/**
	 * When a user is not authentified, you need to create a link to the URL 
	 * returned by this method.
	 * 
	 * @param string $scope Space-separated list of scopes. Leave empty if you just need the basic one
	 * @param string $redirectURI Where to redirect the user after auth. Leave empty for the current URI
	 * @return string Login URL
	 */
	function getLoginURL($scope = null, $redirectURI = null)
	{
		$redirectURI = $redirectURI ? : $this->getCurrentURI();
		self::$persistance->setVariable('redirect_uri', $redirectURI);
		return $this->getAuthorizationURL($redirectURI, $scope);
	}

	/**
	 * If you want to place a "logout" button, you can use this link to log the 
	 * user out of the player page too. Don't forget to empty your sessions .
	 * 
	 * @see \Maniaplanet\WebServices\ManiaConnect\Client::logout()
	 * @param string $redirectURI Where to redirect the user after he logged out. Leave empty for current URI
	 * @return string Logout URL
	 */
	function getLogoutURL($redirectURI = null)
	{
		$redirectURI = $redirectURI ? : $this->getCurrentURI();
		return self::LOGOUT_URL.'?'.http_build_query(array('redirect_uri' => $redirectURI),
				'', '&');
	}

	/**
	 * Destroys the persistance. Call that when you want to log a 
	 * user out, or implement your own way of logging out.
	 */
	function logout()
	{
		self::$persistance->destroy();
	}

	/**
	 * Returns the Current URL.
	 * 
	 * @return string The current URL.
	 * @see http://code.google.com/p/oauth2-php
	 * @author Originally written by Naitik Shah <naitik@facebook.com>.
	 * @author Update to draft v10 by Edison Wong <hswong3i@pantarei-design.com>
	 */
	protected function getCurrentURI()
	{
		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://'
				: 'http://';
		$current_uri = $protocol.$_SERVER['HTTP_HOST'].$this->getRequestURI();
		$parts = parse_url($current_uri);

		$query = '';
		if(!empty($parts['query']))
		{
			$params = array();
			parse_str($parts['query'], $params);
			$params = array_filter($params);
			if(!empty($params))
			{
				$query = '?'.http_build_query($params, '', '&');
			}
		}

		// Use port if non default.
		$port = isset($parts['port']) &&
			(($protocol === 'http://' && $parts['port'] !== 80) || ($protocol === 'https://' && $parts['port'] !== 443))
				? ':'.$parts['port'] : '';

		// Rebuild.
		return $protocol.$parts['host'].$port.$parts['path'].$query;
	}

	/**
	 * Since $_SERVER['REQUEST_URI'] is only available on Apache, we
	 * generate an equivalent using other environment variables.
	 * 
	 * @see http://code.google.com/p/oauth2-php
	 * @author Originally written by Naitik Shah <naitik@facebook.com>.
	 * @author Update to draft v10 by Edison Wong <hswong3i@pantarei-design.com>
	 */
	protected function getRequestURI()
	{
		if(isset($_SERVER['REQUEST_URI']))
		{
			$uri = $_SERVER['REQUEST_URI'];
		}
		else
		{
			if(isset($_SERVER['argv']))
			{
				$uri = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['argv'][0];
			}
			elseif(isset($_SERVER['QUERY_STRING']))
			{
				$uri = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
			}
			else
			{
				$uri = $_SERVER['SCRIPT_NAME'];
			}
		}
		// Prevent multiple slashes to avoid cross site requests
		$uri = '/'.ltrim($uri, '/');

		return $uri;
	}

	private function getAuthorizationURL($redirectURI, $scope = 'basic')
	{
		$params = http_build_query(array(
			'client_id' => $this->username,
			'redirect_uri' => $redirectURI,
			'scope' => $scope,
			'response_type' => 'code',
			), '', '&');
		return self::AUTHORIZATION_URL.'?'.$params;
	}

	/**
	 * Tries to get an access token. 
	 * If one is found in the session, it returns it.
	 * If a code is found in the request, it tries to exchange it for an access
	 * token on the OAuth2 Token Endpoint
	 * Else it returns false
	 * 
	 * @return string An OAuth2 access token, or false if none is found
	 */
	protected function getAccessToken()
	{
		$token = self::$persistance->getVariable('access_token');
		if($token)
		{
			return $token;
		}
		if(isset($_REQUEST['code']))
		{
			$code = $_REQUEST['code'];
			if($code)
			{
				$accessToken = $this->getAccessTokenFromCode($code,
					self::$persistance->getVariable('redirect_uri'));
				self::$persistance->setVariable('access_token', $accessToken);
				return $accessToken;
			}
		}
	}

	private function getAccessTokenFromCode($authorizationCode, $redirectURI)
	{
		$contentType = $this->contentType;
		$serializeCallback = $this->serializeCallback;
		$this->contentType = 'application/x-www-form-urlencoded';
		$this->serializeCallback = null;

		$params = http_build_query(array(
			'client_id' => $this->username,
			'client_secret' => $this->password,
			'redirect_uri' => $redirectURI,
			'grant_type' => 'authorization_code',
			'code' => $authorizationCode,
			), '', '&');

		try
		{
			$response = $this->execute('POST', self::TOKEN_PATH, array($params));
		}
		catch(\Maniaplanet\WebServices\Exception $e)
		{
			switch($e->getMessage())
			{
				case 'invalid_request':
					$message =
						'invalid_request: The request is missing a required '.
						'parameter, includes an unsupported parameter or '.
						'parameter value, or is otherwise malformed.';
					break;

				case 'invalid_client':
					$message =
						'invalid_client: Application authentication failed. ';
					break;

				case 'invalid_grant':
					$message =
						'invalid_grant: The provided access grant is invalid, '.
						'expired, or revoked (e.g. invalid assertion, expired '.
						'authorization token, bad end-user password credentials, '.
						'or mismatching authorization code and redirection URI).';
					break;

				default:
					throw $e;
			}

			throw new \Maniaplanet\WebServices\Exception($message, $e->getCode(),
				$e->getHTTPStatusCode(), $e->getHTTPStatusMessage());
		}


		$this->contentType = $contentType;
		$this->serializeCallback = $serializeCallback;

		self::$persistance->deleteVariable('redirect_uri');
		self::$persistance->deleteVariable('code');

		return $response->access_token;
	}

	/**
	 * Executes an request on the API with an OAuth2 access token.
	 * It works just like its parent execute() method.
	 * 
	 * @see \Maniaplanet\WebServices\HTTPClient::execute()
	 */
	protected function executeOAuth2($method, $ressource, array $params = array())
	{
		$this->headers = array(sprintf('Authorization: Bearer %s',
				self::$persistance->getVariable('access_token')));
		// We don't need auth since we are using an access token
		$this->enableAuth = false;
		try
		{
			$result = $this->execute($method, $ressource, $params);
			$this->enableAuth = true;
			return $result;
		}
		catch(Exception $e)
		{
			$this->enableAuth = true;
			throw $e;
		}
	}

}

?>