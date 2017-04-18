<?php

/**
 * Zend/zopenidconsumer.inc.php
 * 
 * Defines Zend Framework OpenID Consumer component.
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
use_unit("Zend/framework/library/Zend/OpenId/Consumer.php");
use_unit("Zend/framework/library/Zend/OpenId/Extension/Sreg.php");

// Field Status

/**
 * Optional field.
 * 
 * @const       srcOptional
 */
define('srcOptional', 'srcOptional');

/**
 * Required field.
 * 
 * @const       srcRequired
 */
define('srcRequired', 'srcRequired');

/**
 * Disabled field.
 * 
 * @const       srcDisable
 */
define('srcDisable', 'srcDisable');

/**
 * Registration data for ZOpenIdConsumer to request to the OpenID provider.
 */
class ConsumerSimpleRegistrationOptions extends Persistent
{

   // Owner

   /**
    * Owner.
    *
    * @var      ZOpenIdConsumer
    */
   protected $oisro = null;

   // Documented in the parent.
   function readOwner()
   {
      return ($this->oisro);
   }

   // Constructor

   /**
    * Class constructor.
    *
    * @param    ZOpenIdConsumer $aowner Owner.
    */
   function __construct($aowner)
   {
      parent::__construct();

      $this->oisro = $aowner;
   }

   // Nickname

   /**
    * Nickname.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_nickname = srcDisable;

   /**
    * Nickname.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getNickname()    {return $this->_nickname;}

   /**
    * Setter method for $_nickname.
    *
    * @param    string  $value
    */
   function setNickname($value)    {$this->_nickname = $value;}

   /**
    * Getter for $_nickname's default value.
    *
    * @return   string  srcDisable
    */
   function defaultNickname()    {return srcDisable;}

   // Email

   /**
    * Email address.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_email = srcDisable;

   /**
    * Email address.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getEmail()    {return $this->_email;}

   /**
    * Setter method for $_email.
    *
    * @param    string  $value
    */
   function setEmail($value)    {$this->_email = $value;}

   /**
    * Getter for $_email's default value.
    *
    * @return   string  srcDisable
    */
   function defaultEmail()    {return srcDisable;}

   // Fullname.

   /**
    * Full name.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_fullname = srcDisable;

   /**
    * Full name.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getFullName()    {return $this->_fullname;}

   /**
    * Setter method for $_fullname.
    *
    * @param    string  $value
    */
   function setFullName($value)    {$this->_fullname = $value;}

   /**
    * Getter for $_fullname's default value.
    *
    * @return   string  srcDisable
    */
   function defaultFullName()    {return srcDisable;}

   // Birthday

   /**
    * Birthday.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_dateofbirth = srcDisable;

   /**
    * Birthday.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getDateOfBirth()    {return $this->_dateofbirth;}

   /**
    * Setter method for $_dateofbirth.
    *
    * @param    string  $value
    */
   function setDateOfBirth($value)    {$this->_dateofbirth = $value;}

   /**
    * Getter for $_dateofbirth's default value.
    *
    * @return   string  srcDisable
    */
   function defaultDateOfBirth()    {return srcDisable;}

   // Gender

   /**
    * Gender.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_gender = srcDisable;

   /**
    * Gender.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getGender()    {return $this->_gender;}

   /**
    * Setter method for $_gender.
    *
    * @param    string  $value
    */
   function setGender($value)    {$this->_gender = $value;}

   /**
    * Getter for $_gender's default value.
    *
    * @return   string  srcDisable
    */
   function defaultGender()    {return srcDisable;}

   // Postal Code

   /**
    * Postal code.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_postcode = srcDisable;

   /**
    * Postal code.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getPostcode()    {return $this->_postcode;}

   /**
    * Setter method for $_postcode.
    *
    * @param    string  $value
    */
   function setPostcode($value)    {$this->_postcode = $value;}

   /**
    * Getter for $_postcode's default value.
    *
    * @return   string  srcDisable
    */
   function defaultPostcode()    {return srcDisable;}

   // Country

   /**
    * Country.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_country = srcDisable;

   /**
    * Country.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getCountry()    {return $this->_country;}

   /**
    * Setter method for $_country.
    *
    * @param    string  $value
    */
   function setCountry($value)    {$this->_country = $value;}

   /**
    * Getter for $_country's default value.
    *
    * @return   string  srcDisable
    */
   function defaultCountry()    {return srcDisable;}

   // Language

   /**
    * Language.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_language = srcDisable;

   /**
    * Language.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getLanguage()    {return $this->_language;}

   /**
    * Setter method for $_language.
    *
    * @param    string  $value
    */
   function setLanguage($value)    {$this->_language = $value;}

   /**
    * Getter for $_language's default value.
    *
    * @return   string  srcDisable
    */
   function defaultLanguage()    {return srcDisable;}

   // Timezone

   /**
    * Timezone.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    *
    * @var      string
    */
   protected $_timezone = srcDisable;

   /**
    * Timezone.
    *
    * Possible values are: srcDisable, srcOptional, or srcRequired.
    */
   function getTimezone()    {return $this->_timezone;}

   /**
    * Setter method for $_timezone.
    *
    * @param    string  $value
    */
   function setTimezone($value)    {$this->_timezone = $value;}

   /**
    * Getter for $_timezone's default value.
    *
    * @return   string  srcDisable
    */
   function defaultTimezone()    {return srcDisable;}

   /**
    * Returns registration data to request to OpenID provider.
    *
    * If no field is enabled, by setting it to either srcOptional or srcRequired
    * values, this method will return null.
    *
    * @return   Zend_OpenId_Extension_Sreg
    */
   function returnOptions()
   {
      $data = array();

      switch($this->_nickname)
      {
         case srcOptional:
            $data['nickname'] = false;
            break;

         case srcRequired:
            $data['nickname'] = true;
            break;
      }

      switch($this->_email)
      {
         case srcOptional:
            $data['email'] = false;
            break;

         case srcRequired:
            $data['email'] = true;
            break;
      }

      switch($this->_fullname)
      {
         case srcOptional:
            $data['fullname'] = false;
            break;

         case srcRequired:
            $data['fullname'] = true;
            break;
      }

      switch($this->_dateofbirth)
      {
         case srcOptional:
            $data['dob'] = false;
            break;

         case srcRequired:
            $data['dob'] = true;
            break;
      }

      switch($this->_gender)
      {
         case srcOptional:
            $data['gender'] = false;
            break;

         case srcRequired:
            $data['gender'] = true;
            break;
      }

      switch($this->_postcode)
      {
         case srcOptional:
            $data['postcode'] = false;
            break;

         case srcRequired:
            $data['postcode'] = true;
            break;
      }

      switch($this->_country)
      {
         case srcOptional:
            $data['country'] = false;
            break;

         case srcRequired:
            $data['country'] = true;
            break;
      }

      switch($this->_language)
      {
         case srcOptional:
            $data['language'] = false;
            break;

         case srcRequired:
            $data['language'] = true;
            break;
      }

      switch($this->_timezone)
      {
         case srcOptional:
            $data['timezone'] = false;
            break;

         case srcRequired:
            $data['timezone'] = true;
            break;
      }

      if(count($data) != 0)
      {
         return new Zend_OpenId_Extension_Sreg($data, null, 1.1);
      }
      else
      {
         return null;
      }
   }
}

/**
 * Component to authenticate users through OpenID 1.0.
 * 
 * @link        http://framework.zend.com/manual/en/zend.openid.consumer.html Zend Framework Documentation
 */
class ZOpenIdConsumer extends Component
{

   private $_consumer = null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->_simpleregistrationoptions = new ConsumerSimpleRegistrationOptions($this);
   }
   
   
   protected $_nexturl = '';

   /**
    * URL that performs the third step of OpenID authentication.
    *
    * Check Zend Framework Documentation (http://framework.zend.com/manual/en/zend.openid.consumer.html#zend.openid.consumer.authentication)
    * for additional information.
    *
    * @return string
    */
   function getNextUrl()    {return $this->_nexturl;}
   function setNextUrl($value)    {$this->_nexturl = $value;}
   function defaultNextUrl()    {return '';}
   
   
   protected $_onidentify = null;

   /**
    * Event to set a valid OpenID identity (username) for authentication.
    * 
    * This event is triggered upon component load.
    *
    * This property should either contain the name of the function to be run when the event is
    * triggered (without brackets), or be set to null.
    *
    * The event should then return the identity user will log in with on their OpenID provider.
    *
    * @return string
    */
   function getOnIdentify()    {return $this->_onidentify;}
   function setOnIdentify($value)    {$this->_onidentify = $value;}
   function defaultOnIdentify()    {return null;}
   
   
   protected $_immediatelogin = 'true';

   /**
    * Whether to call Zend_OpenId_Consumer::login() ('true') when logging in or to call
    * Zend_OpenId_Consumer::check() ('false') instead.
    *
    * @return string
    */
   function getImmediateLogin()    {return $this->_immediatelogin;}
   function setImmediateLogin($value)    {$this->_immediatelogin = $value;}
   function defaultImmediateLogin()    {return 'true';}
   
   
   protected $_status = '';

   /**
    * Authentication status.
    *
    * Possible values are: 'cancel', 'valid', or 'invalid'.
    *
    * @return string
    */
   function readStatus()    {return $this->_status;}
   function writeStatus($value)    {$this->_status = $value;}
   function defaultStatus()    {return '';}
   
   
   protected $_simpleregistrationoptions = null;

   /**
    * Fields to request from the OpenID provider.
    *
    * @return ConsumerSimpleRegistrationOptions
    */
   function getSimpleRegistrationOptions()    {return $this->_simpleregistrationoptions;}
   function setSimpleRegistrationOptions($value)    {if(is_object($value))        {$this->_simpleregistrationoptions = $value;}}
   function defaultSimpleRegistrationOptions()    {return null;}
   
   
   protected $_simpleregistrationdata = array();

   /**
    * Fields to request from the OpenID provider.
    *
    * @return   array
    */
   function readSimpleRegistrationData()    {return $this->_simpleregistrationdata;}
   function writeSimpleRegistrationData($value)    {$this->_simpleregistrationdata = $value;}
   function defaultSimpleRegistrationData()    {return array();}
   
   
   protected $_openidconsumerstorage = null;

   /**
    * OpenID Consumer Storage.
    *
    * @return   ZOpenIdConsumerStorage
    */
   function getOpenIdConsumerStorage()    {return $this->_openidconsumerstorage;}
   function setOpenIdConsumerStorage($value)    {$this->_openidconsumerstorage = $this->fixupProperty($value);}
   function defaultOpenIdConsumerStorage()    {return null;}


   /**
    * Initializes the component.
    *
    * @throws    Exception      These are the possible causes for such an exception:
    *                           <ul>
    *                             <li>OpenID provider identifier was not defined.</li>
    *                             <li>You must define $_onidentify event.</li>
    *                             <li>OpenID consumer storage was not defined.</li>
    *                           </ul>
    * @internal
    */
   function loaded()
   {
      parent::loaded();

      if (!isMainPage()) return;
      
      $this->setOpenIdConsumerStorage($this->_openidconsumerstorage);

      if($this->_openidconsumerstorage != null)
      {

         if($this->_onidentify != null)
         {
            $name = $this->callEvent('onidentify', array());
            $options = $this->_simpleregistrationoptions->returnOptions();

            if($name != '')
            {
               $storage = $this->_openidconsumerstorage->CreateStorage();
               $this->_consumer = new Zend_OpenId_Consumer($storage);
               if( ! isset($_GET['openid_mode']))
               {
                  if($this->_immediatelogin == 'true' || $this->_immediatelogin == 1)
                  {
                     print_r($this->_consumer->login($name, $this->_nexturl, null, $options));
                  }
                  else
                  {
                     print_r($this->_consumer->check($name, $this->_nexturl, null, $options));
                  }
               }
               else
               {
                  switch($_GET['openid_mode'])
                  {
                     case 'cancel':
                        $this->_status = 'cancel';
                        break;
                     case 'id_res':
                        if(isset($_GET['openid_user_setup_url']))
                        {
                           $url = $_GET['openid_user_setup_url'];
                           Zend_OpenId::redirect($url);
                        }

                        if($this->_consumer->verify($_GET, $id, $options))
                        {
                           $this->_status = 'valid';

                           if(isset($options))
                           {
                              $this->_simpleregistrationdata = $options->getProperties();
                           }
                        }
                        else
                        {
                           $this->_status = 'invalid';
                        }

                        break;
                     case 'setup_needed':
                        $url = $_GET['openid_user_setup_url'];
                        Zend_OpenId::redirect($url);
                        break;
                  }
               }
            }
            else
            {
               throw new Exception('Define your id of OpenId provider');
            }
         }
         else
         {
            throw new Exception('OnIdentify is needed for OpenId Consumer to work');
         }
      }
      else
      {
         throw new Exception('An storage is needed for OpenId Consumer to work');
      }
   }

   /**
    * Returns any error occurred during OpenID authentication.
    *
    * @return   array
    */
   function returnError()
   {
      return $this->_consumer->getError();
   }
}

?>