<?php

/**
 * Zend/zauthdb.inc.php
 * 
 * Defines Zend Framework Authentication Database adapter.
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

// Include RPCL common file and necessary units.
require_once("rpcl/rpcl.inc.php");
use_unit("Zend/zcommon/zcommon.inc.php");
use_unit("classes.inc.php");
use_unit("controls.inc.php");
use_unit("extctrls.inc.php");
require_once('Zend/Db/Adapter/Pdo/Mysql.php');
require_once('Zend/Db/Adapter/Pdo/Mssql.php');
require_once('Zend/Db/Adapter/Pdo/Pgsql.php');
require_once('Zend/Auth/Adapter/DbTable.php');

use_unit("Zend/zcommon/zauthadapter.inc.php");

/**
 * Adapter for authentication against a database.
 * 
 * @link        http://framework.zend.com/manual/en/zend.auth.adapter.dbtable.html Zend Framework Documentation
 */
class ZAuthDB extends ZAuthAdapter
{

   // Properties

   /**
    * Name of the driver for the database management system to be used.
    *
    * @var string
    */
   protected $_drivername = "";

   /**
    * Name of the database.
    *
    * @var string
    */
   protected $_databasename = "";

   /**
    * Host name or address.
    *
    * @var string
    */
   protected $_host = "";

   /**
    * Username.
    *
    * @var string
    */
   protected $_username = "";

   /**
    * Password.
    *
    * @var string
    */
   protected $_userpassword = "";

   /**
    * SQL Function to be applied to the password before it is compared to the
    * one stored in the database.
    *
    * The function should be a proper call, where the password user typed should be replaced with an
    * interrogation sign, "?". For example: "MD5(?)".
    *
    * Note these functions are specific to the underlying DBMS. Check the documentation for yours to
    * find out which functions you can use.
    *
    * @var string
    */
   protected $_passwordfunction = "";

   /**
    * User table.
    *
    * Name of the table where login information for users is stored (username, password and realm).
    *
    * @var string
    */
   protected $_usertable = "";

   /**
    * Username field.
    *
    * @var string
    */
   protected $_usernamefieldname = "";

   /**
    * Password field.
    *
    * @var string
    */
   protected $_userpasswordfieldname = "";

   /**
    * Database adapter.
    *
    * Instance of a database adapter, like
    * Zend_Db_Adapter_Pdo_Mysql or Zend_Db_Adapter_Pdo_Pssql.
    *
    * @var string
    */
   protected $_dbadapter = null;

   /**
    * Realm field.
    *
    * @var string
    */
   protected $_realmfieldname = "";

   /**
    * Port.
    *
    * The port for the host where DBMS can be accessed. For example, if your database
    * can be accessed from localhost:5432, "5432" would be the port.
    *
    * @var string
    */
   protected $_port = '';

   // Driver Name

   /**
    * Name of the driver for the database management system to be used.
    */
   function getDriverName()    {return $this->_drivername;}

   /**
    * Setter method for $_drivername.
    *
    * @param    string  $value
    */
   function setDriverName($value)    {$this->_drivername = $value;}

   /**
    * Getter for $_drivername's default value.
    *
    * @return   string  Empty string
    */
   function defaultDriverName()    {return "";}

   // Database Name

   /**
    * Name of the database.
    */
   function getDatabaseName()    {return $this->_databasename;}

   /**
    * Setter method for $_databasename.
    *
    * @param    string  $value
    */
   function setDatabaseName($value)    {$this->_databasename = $value;}

   /**
    * Getter for $_databasename's default value.
    *
    * @return   string  Empty string
    */
   function defaultDatabaseName()    {return "";}

   // Host

   /**
    * Host name or address.
    */
   function getHost()    {return $this->_host;}

   /**
    * Setter method for $_host.
    *
    * @param    string  $value
    */
   function setHost($value)    {$this->_host = $value;}

   /**
    * Getter for $_host's default value.
    *
    * @return   string  Empty string
    */
   function defaultHost()    {return "";}

   // Username

   /**
    * Username.
    */
   function getUserName()    {return $this->_username;}

   /**
    * Setter method for $_username.
    *
    * @param    string  $value
    */
   function setUserName($value)    {$this->_username = $value;}

   /**
    * Getter for $_username's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserName()    {return "";}

   // Password

   /**
    * Password.
    */
   function getUserPassword()    {return $this->_userpassword;}

   /**
    * Setter method for $_userpassword.
    *
    * @param    string  $value
    */
   function setUserPassword($value)    {$this->_userpassword = $value;}

   /**
    * Getter for $_userpassword's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserPassword()    {return "";}

   // Password Function

   /**
    * SQL Function to be applied to the password before it is compared to the
    * one stored in the database.
    *
    * The function should be a proper call, where the password user typed should be replaced with an
    * interrogation sign, "?". For example: "MD5(?)".
    *
    * Note these functions are specific to the underlying DBMS. Check the documentation for yours to
    * find out which functions you can use.
    */
   function getPasswordFunction()    {return $this->_passwordfunction;}

   /**
    * Setter method for $_passwordfunction.
    *
    * @param    string  $value
    */
   function setPasswordFunction($value)    {$this->_passwordfunction = $value;}

   /**
    * Getter for $_passwordfunction's default value.
    *
    * @return   string  Empty string
    */
   function defaultPasswordFunction()    {return "";}

   // User Table

   /**
    * User table.
    *
    * Name of the table where login information for users is stored (username, password and realm).
    */
   function getUserTable()    {return $this->_usertable;}

   /**
    * Setter method for $_usertable.
    *
    * @param    string  $value
    */
   function setUserTable($value)    {$this->_usertable = $value;}

   /**
    * Getter for $_usertable's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserTable()    {return "";}

   // Username Field

   /**
    * Username field.
    */
   function getUserNameFieldName()    {return $this->_usernamefieldname;}

   /**
    * Setter method for $_usernamefieldname.
    *
    * @param    string  $value
    */
   function setUserNameFieldName($value)    {$this->_usernamefieldname = $value;}

   /**
    * Getter for $_usernamefieldname's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserNameFieldName()    {return "";}

   // Password Field

   /**
    * Password field.
    */
   function getUserPasswordFieldName()    {return $this->_userpasswordfieldname;}

   /**
    * Setter method for $_userpasswordfieldname.
    *
    * @param    string  $value
    */
   function setUserPasswordFieldName($value)    {$this->_userpasswordfieldname = $value;}

   /**
    * Getter for $_userpasswordfieldname's default value.
    *
    * @return   string  Empty string
    */
   function defaultuserPasswordFieldName()    {return "";}

   // Realm Field

   /**
    * Realm field.
    */
   function getUserRealmFieldName()    {return $this->_realmfieldname;}

   /**
    * Setter method for $_realmfieldname.
    *
    * @param    string  $value
    */
   function setUserRealmFieldName($value)    {$this->_realmfieldname = $value;}

   /**
    * Getter for $_realmfieldname's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserRealmFieldName()    {return "";}

   // Port

   /**
    * The port for the host where DBMS can be accessed. For example, if your database
    * can be accessed from localhost:5432, "5432" would be the port.
    */
   function getPort()    {return $this->_port;}

   /**
    * Setter method for $_port.
    *
    * @param    string  $value
    */
   function setPort($value)    {$this->_port = $value;}

   /**
    * Getter for $_port's default value.
    *
    * @return   string  Empty string
    */
   function defaultPort()    {return '';}

   // Documented in the parent.
   function __construct($aowner = null)
   {
      // Calls inherited constructor.
      parent::__construct($aowner);
   }

   /**
    * Create a database adapter with provided information.
    *
    * @return   Zend_Db_Adapter_Abstract
    */
   protected function CreateAdapter()
   {
      $options = array(
                       'host'=>$this->_host,
                       'username'=>$this->_username,
                       'password'=>$this->_userpassword,
                       'dbname'=>$this->_databasename
                       );
      if($this->_port!='')
      {
          $options['port']=$this->_port;
      }
      switch($this->_drivername)
      {
         case 'mysql':
            $this->_dbadapter = new Zend_Db_Adapter_Pdo_Mysql($options);
            break;
         case 'sqlserver':
            $this->_dbadapter = new Zend_Db_Adapter_Pdo_Mssql($options);
            break;
         case 'postgre':
            $this->_dbadapter = new Zend_Db_Adapter_Pdo_Pssql($options);
            break;
      }

      $authAdapter = new Zend_Auth_Adapter_DbTable($this->_dbadapter, $this->_usertable, $this->_usernamefieldname, $this->_userpasswordfieldname, $this->_passwordfunction);

      return $authAdapter;
   }

   // Documented in the parent.
   function Authenticate($auth, $username, $password, $realm)
   {

      if($username != "")
      {
         // Get database adapter.
         $authAdapter = $this->CreateAdapter();

         // Set it up.
         $authAdapter->setIdentity($username)
         ->setCredential($password);

         $result = $auth->authenticate($authAdapter);

         if($result->IsValid())
         {
            $data = $authAdapter->getResultRowObject(array($this->_realmfieldname));

            if($realm == $data->{$this->_realmfieldname})
            {
               return true;
            }
            else
            {
               return false;
            }
         }
         else
            return false;
      }
      else
         return false;
   }

}


?>