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
	 * Local array to store variables for the setVariable()/getVariable() methods.
	 * 
	 * @var array
	 */
	protected $vars;

	function __construct($username = null, $password = null)
	{
		parent::__construct($username, $password);
		if(!session_id())
		{
			if(!session_start())
			{
				throw new \Maniaplanet\WebServices\Exception('Failed to start session');
			}
		}
		$this->vars = array();
		// State is for CSRF attacks
		// See http://en.wikipedia.org/wiki/Cross-site_request_forgery
		if(!$this->getVariable('state'))
		{
			$this->setVariable('state', md5(pack('N3', mt_rand(), mt_rand(), mt_rand())));
		}
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
		$this->setVariable('redirect_uri', $redirectURI);
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
		return self::LOGOUT_URL.'?'.http_build_query(array('redirect_uri' => $redirectURI), '', '&');
	}

	/**
	 * Empties the session and the class vars. Call that when you want to log a 
	 * user out, or implement your own way of logging out.
	 */
	function logout()
	{
		session_destroy();
		$this->vars = array();
	}

	protected function getVariable($name, $default = false)
	{
		$key = $this->getVariableKey($name);
		if(!array_key_exists($key, $this->vars))
		{
			if(!array_key_exists($key, $_SESSION))
			{
				return $default;
			}
			$this->vars[$key] = unserialize($_SESSION[$key]);
		}
		return $this->vars[$key];
	}

	protected function setVariable($name, $value)
	{
		$key = $this->getVariableKey($name);
		$this->vars[$key] = $value;
		$_SESSION[$key] = serialize($value);
	}
	
	protected function deleteVariable($name)
	{
		$key = $this->getVariableKey($name);
		unset($this->vars[$key]);
		unset($_SESSION[$key]);
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
		// Prevent multiple slashes to avoid cross site requests via the Form API.
		$uri = '/'.ltrim($uri, '/');

		return $uri;
	}

	/**
	 * Generates an almost safe key to store stuff in the PHP session without
	 * collisioning with other variables.
	 */
	private function getVariableKey($name)
	{
		return md5(sprintf('m:%s:%s', $this->username, $name));
	}

	private function getAuthorizationURL($redirectURI, $scope = 'basic')
	{
		$params = http_build_query(array(
			'client_id' => $this->username,
			'redirect_uri' => $redirectURI,
			'scope' => $scope,
			'response_type' => 'code',
			'state' => $this->getVariable('state'), // CSRF protection
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
		$token = $this->getVariable('access_token');
		if($token)
		{
			return $token;
		}
		if(isset($_REQUEST['code']))
		{
			if(isset($_GET['state']) && $this->getVariable('state') && $_GET['state'] != $this->getVariable('state'))
			{
				throw new \Maniaplanet\WebServices\Exception('CSRF attack protection failed');
			}
			$this->deleteVariable('state');
			$code = $_REQUEST['code'];
			if($code)
			{
				$accessToken = $this->getAccessTokenFromCode($code,
					$this->getVariable('redirect_uri'));
				$this->setVariable('access_token', $accessToken);
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

		// FIXME OAuth2 Handle known errors
		$response = $this->execute('POST', self::TOKEN_PATH, array($params));

		$this->contentType = $contentType;
		$this->serializeCallback = $serializeCallback;

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
				$this->getVariable('access_token')));
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