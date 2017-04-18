<?php

/**
 * Zend/zoauth.inc.php
 *
 * Defines Zend Framework OAuth component.
 *
 * This file is part of the RPCL project.
 *
 * Copyright (c) 2004-2011 Embarcadero Technologies, Inc.
 *
 * Check out AUTHORS file for a complete list of project contributors.
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307
 * USA
 *
 * @copyright   2004-2011 Embarcadero Technologies, Inc.
 * @license     http://www.gnu.org/licenses/lgpl-2.1.txt LGPL
 *
 */

/**
 * Include RPCL common file and necessary units.
 */
require_once("rpcl/rpcl.inc.php");
use_unit("classes.inc.php");
use_unit("Zend/framework/library/Zend/Oauth/Consumer.php");
use_unit("Zend/framework/library/Zend/Crypt/Rsa.php");
use_unit("Zend/framework/library/Zend/Crypt/Rsa/Key/Private.php");
use_unit("Zend/framework/library/Zend/Crypt/Rsa/Key/Public.php");

// Signature Methods

/**
 * HMAC-SHA1.
 *
 * @const       smHMAC_SHA1
 */
define('smHMAC_SHA1', 'smHMAC_SHA1');

/**
 * HMAC-SHA256.
 *
 * @const       smHMAC_SHA256
 */
define('smHMAC_SHA256', 'smHMAC_SHA256');

/**
 * RSA-SHA1.
 *
 * @const       smRSA_SHA1
 */
define('smRSA_SHA1', 'smRSA_SHA1');

/**
 * PLAINTEXT.
 *
 * @const       smPLAINTEXT
 */
define('smPLAINTEXT', 'smPLAINTEXT');

// Request Methods

/**
 * POST.
 *
 * @const       rmPOST
 */
define('rmPOST', 'rmPOST');

/**
 * GET.
 *
 * @const       rmGET
 */
define('rmGET', 'rmGET');

// Request Scheme

/**
 * Header request scheme.
 *
 * @const       rsSchemeHeader
 */
define('rsSchemeHeader', 'rsSchemeHeader');

/**
 * PostBody request scheme.
 *
 * @const       rsSchemePostBody
 */
define('rsSchemePostBody', 'rsSchemePostBody');

/**
 * QueryString request scheme.
 *
 * @const       rsSchemeQueryString
 */
define('rsSchemeQueryString', 'rsSchemeQueryString');

/**
 * Component to access third-party services through the OAuth protocol.
 *
 * The OAuth protocol was created so you can access the data your user stored in a third-party service without
 * requesting your user to provide its login data (username and password) for that service.
 *
 * To access a service using OAuth, configure your 0Auth component with the necessary data: CallbackUrl, SiteUrl,
 * ConsumerKey, ConsumerSecret. Except CallbackUrl, the values for all those required properties should be providen by
 * the service whose API you are going to access using OAuth.
 *
 * You usually want the CallbackURL to be the URL of the page where you use the component. In that case, you can use the
 * OnBeforeShow event of the ZOAuth component to define the CallbackURL dynamically:
 *
 * <code>
 * function ZOAuth1BeforeLoad($sender, $params)
 * {
 *     $sender->CallbackUrl = str_replace(" ", "%20", "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
 * }
 * </code>
 *
 * Of course, depending on the requirements of the third-party service, you might need to configure additional
 * properties as well.
 *
 * Once your ZOAuth component is configured, it is time to use it to retrieve data. To do so, you will need to call its
 * executeAction() method, which takes as parameters the target URL of the third-party service, and an optional array of
 * URL key-value pairs:
 *
 * <code>
 * $response = $this->ZOAuth1->executeAction("https://targeturl.example.com/api/some_page", array("arg1" => "val1", "arg2" => "val2");
 * $retrievedData = $response->getBody();
 * </code>
 *
 * When you call executeAction(), the OAuth protocol takes places. If your application has already permission to access
 * the target URL (https://targeturl.example.com/api/some_page in the example code above), you will just receive the
 * returned data ($response). Else, the user will be taken (redirection) to the website of the third-party service, and
 * asked to authorize your application to access their data. If he authorizes your application, he will be taken to the
 * CallbackUrl (usually the page containing the ZOAuth component, the same page the user was redirected from).
 *
 * Note: this component requires the OpenSSL PHP extension. To enable it on HTML5 Builder's internal server, see
 * PHP Settings in the documentation.
 *
 * @link http://framework.zend.com/manual/en/zend.oauth.html Zend Framework Documentation
 * @link wiki://PHP_Settings
 *
 * @example ZendFramework/ZOAuth/ZOAuthTwitterProfile/index.php
 */
class ZOAuth extends Component
{
   private $_consumer = null;
   private $_config = null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
   }

   protected $_callbackurl = '';

   /**
    * Callback URL.
    *
    * URI that the third-party web service should request from your server when sending information.
    *
    * @return string
    */
   function getCallbackUrl()    {return $this->_callbackurl;}
   function setCallbackUrl($value)    {$this->_callbackurl = $value;}
   function defaultCallbackUrl()    {return '';}


   protected $_siteurl = '';

   /**
    * Site URL.
    *
    * Base URI of third-party web service OAuth API endpoints. When this property is set, the
    * following properties will get new default values based on this base URL:
    * <ul>
    *   <li>$_requesttokenurl: $_siteurl.'/request_token'.</li>
    *   <li>$_accesstokenurl: $_siteurl.'/access_token'.</li>
    *   <li>$_authorizeurl: $_siteurl.'/authorize'.</li>
    * </ul>
    *
    * If the third-party web service follows this convention for endpoints URL naming, you will not
    * need to manually set those three properties.
    *
    * @return string
    */
   function getSiteUrl()    {return $this->_siteurl;}
   function setSiteUrl($value)    {$this->_siteurl = $value;}
   function defaultSiteUrl()    {return '';}


   protected $_consumerkey = '';

   /**
    * Consumer key.
    *
    * This key is retrieved from the third-party web service when your application is registered for
    * OAuth access.
    *
    * @return string
    */
   function getConsumerKey()    {return $this->_consumerkey;}
   function setConsumerKey($value)    {$this->_consumerkey = $value;}
   function defaultConsumerKey()    {return '';}


   protected $_consumersecret = '';

   /**
    * Consumer secret.
    *
    * This secret is retrieved from the third-party web service when your application is registered
    * for OAuth access.
    *
    * @return string
    */
   function getConsumerSecret()    {return $this->_consumersecret;}
   function setConsumerSecret($value)    {$this->_consumersecret = $value;}
   function defaultConsumerSecret()    {return '';}


   protected $_signaturemethod = smHMAC_SHA1;

   /**
    * Signature method for secure connections.
    *
    * Possible values are: smHMAC_SHA1, smHMAC_SHA256, smPLAINTEXT, or
    * smRSA_SHA1.
    *
    * @return string
    */
   function getSignatureMethod()    {return $this->_signaturemethod;}
   function setSignatureMethod($value)    {$this->_signaturemethod = $value;}
   function defaultSignatureMethod()    {return smHMAC_SHA1;}


   protected $_rsaprivatekey = '';

   /**
    * RSA private key file path.
    *
    * Path to the .pem file with the RSA private key.
    *
    * @return string
    */
   function getRSAPrivateKey()    {return $this->_rsaprivatekey;}
   function setRSAPrivateKey($value)    {$this->_rsaprivatekey = $value;}
   function defaultRSAPrivateKey()    {return '';}


   protected $_rsapublickey = '';

   /**
    * RSA public key file path.
    *
    * Path to the .pem file with the RSA public key.
    *
    * @return string
    */
   function getRSAPublicKey()    {return $this->_rsapublickey;}
   function setRSAPublicKey($value)    {$this->_rsapublickey = $value;}
   function defaultRSAPublicKey()    {return '';}


   protected $_otherparameters = array();
   /**
    * Additional parameters for Zend_Oauth_Consumer::__construct().
    *
    * You can use this array of key-value pairs to set any parameter you want to pass to Zend_Oauth_Consumer::__construct()
    * that you can not set through a property of ZOAuth.
    *
    * @return array
    */
   function getOtherParameters()    {return $this->_otherparameters;}
   function setOtherParameters($value)    {$this->_otherparameters = $value;}
   function defaultOtherParameters()    {return array();}


   protected $_requestscheme = rsSchemeHeader;

   /**
    * Request scheme.
    *
    * Possible values are: rsSchemeHeader, rsSchemePostBody, or
    * rsSchemeQueryString.
    *
    * @return string
    */
   function getRequestScheme()    {return $this->_requestscheme;}
   function setRequestScheme($value)    {$this->_requestscheme = $value;}
   function defaultRequestScheme()    {return rsSchemeHeader;}


   protected $_requestmethod = rmPOST;

   /**
    * Request Method.
    *
    * Possible values are rmPOST and rmGET.
    *
    * @return string
    */
   function getRequestMethod()    {return $this->_requestmethod;}
   function setRequestMethod($value)    {$this->_requestmethod = $value;}
   function defaultRequestMethod()    {return rmPOST;}


   protected $_oauthversion = '1.0';

   /**
    * OAuth protocol version.
    *
    * @link http://en.wikipedia.org/wiki/OAuth Wikipedia
    *
    * @return string
    */
   function getOAuthVersion()    {return $this->_oauthversion;}
   function setOAuthVersion($value)    {$this->_oauthversion = $value;}
   function defaultOAuthVersion()    {return '1.0';}


   protected $_requesttokenurl = '';

   /**
    * Request token URL.
    *
    * You do not need to set this property as long as you set $_siteurl and request token
    * URL is $_siteurl.'/request_token'.
    *
    * @return string
    */
   function getRequestTokenUrl()    {return $this->_requesttokenurl;}
   function setRequestTokenUrl($value)    {$this->_requesttokenurl = $value;}
   function defaultRequestTokenUrl()    {return '';}


   protected $_accesstokenurl = '';

   /**
    * Access token URL.
    *
    * You do not need to set this property as long as you set $_siteurl and access token
    * URL is $_siteurl.'/access_token'.
    *
    * @return string
    */
   function getAccessTokenUrl()    {return $this->_accesstokenurl;}
   function setAccessTokenUrl($value)    {$this->_accesstokenurl = $value;}
   function defaultAccessTokenUrl()    {return '';}


   protected $_authorizeurl = '';

   /**
    * Authorize URL.
    *
    * You do not need to set this property as long as you set $_siteurl and request token
    * URL is $_siteurl.'/authorize'.
    *
    * @return string
    */
   function getAuthorizeUrl()    {return $this->_authorizeurl;}
   function setAuthorizeUrl($value)    {$this->_authorizeurl = $value;}
   function defaultAuthorizeUrl()    {return '';}

   protected $_onbeforeload=null;

   /**
    * Triggered right before the OAuth connection is configured and started.
    *
    * This event may be used to dynamically change the properties of the ZOAuth component right before they are used for
    * the ZOAuth authentication mechanism.
    *
    * For example, you can define the current page as the CallbackURL with the following code:
    *
    * <code>
    * function ZOAuth1BeforeLoad($sender, $params)
    * {
    *     $sender->CallbackUrl = str_replace(" ", "%20", "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
    * }
    * </code>
    */
   function getOnBeforeLoad() { return $this->_onbeforeload; }
   function setOnBeforeLoad($value) { $this->_onbeforeload=$value; }
   function defaultOnBeforeLoad() { return null; }

   protected $_realm = '';

   /**
    * OAuth Realm.
    *
    * @return string
    */
   function getRealm()    {return $this->_realm;}
   function setRealm($value)    {$this->_realm = $value;}
   function defaultRealm()    {return '';}

   // Documented in the parent.
   function loaded()
   {
      if ( !isMainPage() ) return;

      $this->callEvent('onbeforeload',array());

      $this->_config = array();

      $this->_config['callbackUrl'] = $this->_callbackurl;
      if($this->_siteurl != '')
         $this->_config['siteUrl'] = $this->_siteurl;

      if($this->_accesstokenurl != '')
      {
         $this->_config['accessTokenUrl'] = $this->_accesstokenurl;
      }

      if($this->_authorizeurl != '')
      {
         $this->_config['authorizeUrl'] = $this->_authorizeurl;
      }

      if($this->_requesttokenurl != '')
      {
         $this->_config['requestTokenUrl'] = $this->_requesttokenurl;
      }

      $this->_config['consumerKey'] = $this->_consumerkey;
      $this->_config['consumerSecret'] = $this->_consumersecret;

      if($this->_rsaprivatekey != '')
      {

         $fileprivate = file_get_contents($this->_rsaprivatekey);
         $keyprivate = new Zend_Crypt_Rsa_Key_Private($fileprivate);
         $this->_config['rsaPrivateKey'] = $keyprivate;
      }

      if($this->_rsapublickey != '')
      {
         $filepublic = file_get_contents($this->_rsapublickey);
         $keypublic = new Zend_Crypt_Rsa_Key_Public($filepublic);
         $this->_config['rsaPublicKey'] = $keypublic;
      }

      if(is_array($this->_otherparameters))
         $this->_config = array_merge($this->_config, $this->_otherparameters);

      switch($this->_signaturemethod)
      {
         case smHMAC_SHA1:
            $this->_config['signatureMethod'] = 'HMAC-SHA1';
            break;
         case smHMAC_SHA256:
            $this->_config['signatureMethod'] = 'HMAC-SHA256';
            break;
         case smRSA_SHA1:
            $this->_config['signatureMethod'] = 'RSA-SHA1';
            break;
         case smPLAINTEXT:
            $this->_config['signatureMethod'] = 'PLAINTEXT';
            break;
      }

      switch($this->_requestscheme)
      {
         case rsSchemeHeader:
            $this->_config['requestScheme'] = Zend_Oauth::REQUEST_SCHEME_HEADER;
            break;
         case rsSchemePostBody:
            $this->_config['requestScheme'] = Zend_Oauth::REQUEST_SCHEME_POSTBODY;
            break;
         case rsSchemeQueryString:
            $this->_config['requestScheme'] = Zend_Oauth::REQUEST_SCHEME_QUERYSTRING;
            break;
      }

      if($this->_realm != '')
      {
         $this->_config['realm'] = $this->_realm;
      }

      $this->_consumer = new Zend_Oauth_Consumer($this->_config);

      if(isset($_GET['oauth_token']))
      {
         $this->_accessToken();
      }
      else
      {
         $this->_requestToken();
      }

   }

   /**
    * Gets an unauthorized request token from the third-party web service, and redirects user to the
    * OAuth API of that third-party service, so user can authorize the request token.
    *
    * This method is called from loaded(), and you will rarely call it yourself manually.
    *
    * @internal
    */
   function _requestToken()
   {
      $token = $this->_consumer->getRequestToken();


      $owner = $this->readOwner();

      if($owner != null)
      {

         $prefix = $owner->readNamePath() . "." . $this->_name . ".GZOAuth.";
         $_SESSION[$prefix . 'REQUEST_TOKEN'] = serialize($token);
         $this->_consumer->redirect();
      }

   }

   /**
    * Gets an access token from the third-party web service.
    *
    * This method is called from loaded(), and you will rarely call it yourself manually.
    *
    * @internal
    */
   function _accessToken()
   {
      $owner = $this->readOwner();
      if($owner != null)
      {
         $prefix = $owner->readNamePath() . "." . $this->_name . ".GZOAuth.";

         if(isset($_GET['oauth_token']) && isset($_SESSION[$prefix . 'REQUEST_TOKEN']))
         {
            $token = $this->_consumer->getAccessToken($_GET, unserialize($_SESSION[$prefix . 'REQUEST_TOKEN']));

            $_SESSION[$prefix . 'ACCESS_TOKEN'] = serialize($token);

            $_SESSION[$prefix . 'REQUEST_TOKEN'] = null;
         }
      }
   }

   /**
    * Performs an action against the third-party web service OAuth API.
    *
    * This method will only work in an access token has been already retrieved. Else, it will return
    * false.
    *
    * @param    string  $uri            URI to call.
    * @param    array   $parameters     Array with key-value pairs to be used as parameters in the
    *                                   call to the $uri.
    * @return   boolean|Zend_Http_Response
    */
   function executeAction($uri, $parameters = array())
   {
      $owner = $this->readOwner();
      if($owner != null)
      {
         $prefix = $owner->readNamePath() . "." . $this->_name . ".GZOAuth.";

         if(isset($_SESSION[$prefix . 'ACCESS_TOKEN']))
         {
            $token = unserialize($_SESSION[$prefix . 'ACCESS_TOKEN']);

            $client = $token->getHttpClient($this->_config);
            $client->setUri($uri);
            if($this->_requestmethod == rmPOST)
            {
               $client->setMethod(Zend_Http_Client::POST);

               foreach($parameters as $parameter=>$value)
               {
                  $client->setParameterPost($parameter, $value);
               }
            }
            else
            {
               $client->setMethod(Zend_Http_Client::GET);
               foreach($parameters as $parameter=>$value)
               {
                  $client->setParameterGet($parameter, $value);
               }
            }
            $response = $client->request();
            return $response;
         }
         else
         {
            return false;
         }
      }
      else
      {
         return false;
      }
   }
}

?>
