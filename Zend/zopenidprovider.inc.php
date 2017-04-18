<?php

/**
 * Zend/zopenidprovider.inc.php
 * 
 * Defines Zend Framework OpenID Provider component.
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
use_unit("Zend/framework/library/Zend/OpenId/Provider.php");
use_unit("Zend/framework/library/Zend/OpenId/Extension/Sreg.php");

/**
 * Component to implement an OpenID server.
 * 
 * @link        http://framework.zend.com/manual/en/zend.openid.provider.html Zend Framework Documentation
 */
class ZOpenIdProvider extends Component
{

   /**
    * Zend Framework OpenID Provider instance.
    *
    * @var      Zend_OpenId_Provider
    */
   protected $_server = null;

   // Trust URL

   /**
    * Trust page URL.
    *
    * @var      string
    */
   protected $_trusturl = '';

   /**
    * Trust page URL.
    */
   function getTrustUrl()    {return $this->_trusturl;}

   /**
    * Setter method for $_trusturl.
    *
    * @param    string  $value
    */
   function setTrustUrl($value)    {$this->_trusturl = $value;}

   /**
    * Getter for $_trusturl's default value.
    *
    * @return   string  Empty string
    */
   function defaultTrustUrl()    {return '';}

   // Login URL

   /**
    * Login page URL.
    *
    * @var      string
    */
   protected $_loginurl = '';

   /**
    * Login page URL.
    */
   function getLoginUrl()    {return $this->_loginurl;}

   /**
    * Setter method for $_loginurl.
    *
    * @param    string  $value
    */
   function setLoginUrl($value)    {$this->_loginurl = $value;}

   /**
    * Getter for $_loginurl's default value.
    *
    * @return   string  Empty string
    */
   function defaultLoginUrl()    {return '';}

   // User

   /**
    * User.
    *
    * @var      Zend_OpenId_Provider_User
    */
   protected $_user = null;

   /**
    * User.
    *
    * @return   Zend_OpenId_Provider_User       $_status
    */
   function getUser()    {return $this->_user;}

   /**
    * Setter method for $_status.
    *
    * @param    Zend_OpenId_Provider_User       $value
    */
   function setUser($value)    {$this->_user = $this->fixupProperty($value);}

   /**
    * Getter for $_status's default value.
    *
    * @return   Zend_OpenId_Provider_User       Null
    */
   function defaultUser()    {return null;}

   // Storage

   /**
    * Storage.
    *
    * @var      Zend_OpenId_Provider_Storage
    */
   protected $_storage = null;

   /**
    * Storage.
    */
   function getStorage()    {return $this->_storage;}

   /**
    * Setter method for $_status.
    *
    * @param    Zend_OpenId_Provider_Storage    $value
    */
   function setStorage($value)    {$this->_storage = $this->fixupProperty($value);}

   /**
    * Getter for $_status's default value.
    *
    * @return   Zend_OpenId_Provider_Storage    Null
    */
   function defaultStorage()    {return null;}

   // Session Timeout

   /**
    * Timeout for association session (in seconds).
    *
    * @var      integer
    */
   protected $_sessionttl = 3600;

   /**
    * Timeout for association session (in seconds).
    */
   function getSessionTTL()    {return $this->_sessionttl;}

   /**
    * Setter method for $_status.
    *
    * @param    integer $value
    */
   function setSessionTTL($value)    {$this->_sessionttl = $value;}

   /**
    * Getter for $_status's default value.
    *
    * @return   integer 1 hour (3600 seconds)
    */
   function defaultSessionTTL()    {return 3600;}

   // Class constructor.

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);

   }

   // Loaded.

   /**
    * Initializes the component after the form file has been read into memory.
    *
    * Do not call the Loaded method. The streaming system calls this method after it
    * loads the component's form from a stream.
    *
    * When the streaming system loads a form or data module from its form file,
    * it first constructs the form component by calling its constructor, then reads
    * its property values from the form file. After reading all the property values
    * for all the components, the streaming system calls the Loaded methods of each
    * component in the order the components were created. This gives the components a
    * chance to initialize any data that depends on the values of other components or
    * other parts of itself.
    *
    * Note: All references to sibling components are resolved by the time Loaded is called.
    * Loaded is the first place that sibling pointers can be used after being streamed in.
    *
    * As for the reading operation, the reader sets the ComponentState in csLoading
    *
    * Warning: Loaded may be called multiple times on inherited forms. It is called every time
    * a level of inheritance is streamed in.
    *
    * @throws Exception These are the possible causes for such an exception: (1) you did not give $_trusturl a value,
    *                   (2) you did not give _loginurl a value, (3) you did not give _user a value, or (4) you did not
    *                   give _storage a value.
    */
   function loaded()
   {

      parent::loaded();

      if ( !isMainPage() ) return;

      $this->setStorage($this->_storage);
      $this->setUser($this->_user);
      if($this->_trusturl != '')
      {
         if($this->_loginurl != '')
         {
            if($this->_user != null)
            {
               if($this->_storage != null)
               {

                  $storage = $this->_storage->CreateStorage();
                  $user = $this->_user->CreateUser();
                  $this->_server = new Zend_OpenId_Provider($this->_loginurl, $this->_trusturl, $user, $storage, $this->_sessionttl);

               }
               else
               {
                  throw new Exception("Storage is necessary to create OpenId Provider");
               }
            }
            else
            {
               throw new Exception("User is necessary to create OpenId Provider");
            }
         }
         else
         {
            throw new Exception('Login URL is necessary to create OpenId Provider');
         }
      }
      else
      {
         throw new Exception('Trust URL is necessary to create OpenId Provider');
      }
   }

   /**
    * Handles calls to this OpenID Provider.
    *
    * You should call this method right away, and expect it to return a string that you should pass
    * back to the OpenID-enabled site. On failure, this method will return a boolean false value
    * instead.
    *
    * @return   boolean|string
    */
   function Execute()
   {
      $sreg = new Zend_OpenId_Extension_Sreg();
      return $this->_server->handle(null, $sreg);
   }

   /**
    * Registers a new user with given URL identifier and password.
    *
    * It returns true in case of success and false if an user with given URL identifier already
    * exists.
    *
    * @param    string  $id             User URL identifier.
    * @param    string  $password       Encrypted user password.
    * @return   boolean
    */
   function register($id, $password)
   {
      return $this->_server->register($id, $password);
   }

   /**
    * Checks whether there is an user with given URL identifier (true) or not (false).
    *
    * @param    string  $id     User URL identifier
    * @return   boolean
    */
   function hasUser($id)
   {
      return $this->_server->hasUser($id);
   }

   /**
    * Logs in user with provided login data.
    *
    * Returns true in case of success, false otherwise.
    *
    * @param    string  $id             User URL identifier.
    * @param    string  $password       Encrypted user password.
    * @return   boolean
    */
   function login($id, $password)
   {
      return $this->_server->login($id, $password);
   }

   /**
    * Logs out user with given URL identifier.
    *
    * It always returns true.
    *
    * @param    string  $id     User URL identifier.
    * @return   boolean
    */
   function logout($id)
   {
      return $this->_server->logout($id);
   }

   /**
    * Returns current user URL identifier.
    *
    * It will return a boolean false instead in case there is no logged-in user.
    *
    * @return   boolean|string
    */
   function returnLoggedInUser()
   {
      return $this->_server->getLoggedInUser();
   }

   /**
    * Returns the URL of the OpenID-enabled website that performed the request.
    *
    * It will return boolean false value if an error occurs.
    *
    * @param    array   $params Request arguments to be added to the URL (through GET or POST).
    * @return   boolean|string
    */
   function returnSiteRoot($params)
   {
      return $this->_server->getSiteRoot($params);
   }

   /**
    * Always allow OpenID-enabled website with given URL to authenticate current user.
    *
    * It returns true on success, false on error.
    *
    * @param    string                                  $root           OpenID-enabled website URL.
    * @param    array|Zend_OpenId_Extension_Sreg        $extensions     Extension or array of extensions.
    * @return   boolean
    */
   function allowSite($root, $extensions = null)
   {
      return $this->_server->allowSite($root, $extensions);
   }

   /**
    * Deny OpenID-enabled website with given URL to authenticate current user.
    *
    * It returns true on success, false on error.
    *
    * @param    string  $root           OpenID-enabled website URL.
    * @return   boolean
    */
   function denySite($root)
   {
      return $this->_server->denySite($root);
   }

   /**
    * Removes OpenID-enabled website with given URL from current user always-allowed sites.
    *
    * Next time the website tries to authenticate user, your provider will ask user for
    * confirmation.
    *
    * Returns true on success and false on error.
    *
    * @param    string  $root           OpenID-enabled website URL.
    * @return   boolean
    */
   function delSite($root)
   {
      return $this->_server->delSite($root);
   }

   /**
    * Returns the list of always-allowed OpenID-enabled websites for current user.
    *
    * It returns boolean false value if there is no logged-in user.
    *
    * @return   array|boolean
    */
   function returnTrustedSites()
   {
      return $this->_server->getTrustedSites();
   }

   /**
    * Prepares information to be sent back to OpenID-enabled website on authentication request,
    * signs it with a shared secret and sends it back through HTTP redirection.
    *
    * @param    array                                   $params         Request arguments to be added to
    *                                                                   the URL (through GET or POST).
    * @param    array|Zend_OpenId_Extension_Sreg        $extensions     Extension or array of extensions.
    * @return   boolean
    */
   function respondToConsumer($params, $extensions = null)
   {
      return $this->_server->respondToConsumer($params, $extensions);
   }
}

?>