<?php

/**
 * Zend/zopenidproviderstoragedb.inc.php
 * 
 * Defines Zend Framework OpenID Provider Database Storage component.
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
use_unit("Zend/zcommon/zopenidproviderstorage.inc.php");
require_once('Zend/Db/Adapter/Pdo/Mysql.php');
require_once('Zend/Db/Adapter/Pdo/Mssql.php');
require_once('Zend/Db/Adapter/Pdo/Pgsql.php');
require_once("Zend/OpenId/Provider/Storage.php");
require_once("Zend/OpenId.php");

/**
 * Subclass of Zend_OpenId_Provider_Storage to ease its configuration.
 */
class OpenIdProviderStorageDb extends Zend_OpenId_Provider_Storage
{
   /**
    * Database.
    *
    * It should be an instance of a child class of Zend_Db_Adapter.
    *
    * @var      Zend_Db_Adapter
    */
   private $_db;

   /**
    * Table to store users.
    *
    * @var      string
    */
   private $_usersTable;

   /**
    * Table to store websites.
    *
    * @var      string
    */
   private $_sitesTable;

   /**
    * Table to store site-user associations.
    *
    * @var      string
    */
   private $_associationTable;

   /**
    * Class constructor.
    *
    * It initializes the database storage.
    *
    * @param    Zend_Db_Adapter $db                     Value for $_db.
    * @param    string          $users_table            Optional value for $_usersTable,
    *                                                   it defaults to 'users_openid'.
    * @param    string          $sites_table            Optional value for $_sitesTable,
    *                                                   it defaults to 'sites_openid'.
    * @param    string          $association_table      Optional value for $_associationTable,
    *                                                   it defaults to 'association_openid'.
    */
   function __construct($db, $users_table = 'users_openid', $sites_table = 'sites_openid', $association_table = 'association_openid')
   {
      $this->_db = $db;
      $this->_usersTable = $users_table;
      $this->_sitesTable = $sites_table;
      $this->_associationTable = $association_table;

      $tables = $this->_db->listTables();

      // If the associations table doesn't exist, create it
      if( ! in_array($association_table, $tables))
      {
         $this->_db->getConnection()->exec(
         "create table $association_table (" .
         " handle     varchar(256) not null primary key," .
         " macFunc char(16) not null," .
         " secret  varchar(256) not null," .
         " expires timestamp" .
         ")");
      }

      // If the sites table doesn't exist, create it
      if( ! in_array($sites_table, $tables))
      {
         $this->_db->getConnection()->exec(
         "create table $sites_table (" .
         " id      integer not null primary key auto_increment," .
         " site  varchar(2000) not null," .
         " time  timestamp not null default current_timestamp," .
         " trusted varchar(1000) not null," .
         " openid varchar(2000) not null" .
         ")");
      }

      // If the users table doesn't exist, create it
      if( ! in_array($users_table, $tables))
      {
         $this->_db->getConnection()->exec(
         "create table $users_table (" .
         " user_id   integer not null primary key AUTO_INCREMENT," .
         " username varchar(256) not null unique," .
         " password varchar(32) not null," .
         " openid varchar(10000) not null," .
         " usertype varchar(10)," .
         " created timestamp default current_timestamp" .
         ")");
      }
   }

   /**
    * Stores information about session identified by given association handle.
    *
    * @param    string  $handle         Association handle.
    * @param    string  $macFunc        HMAC function ('sha1' or 'sha256').
    * @param    string  $secret         Shared secret.
    * @param    long    $expires        Expiration UNIX time.
    * @return   boolean
    */
   public function addAssociation($handle, $macFunc, $secret, $expires)
   {
      $expires = date('c', $expires);

      $table = $this->_associationTable;

      $secret = base64_encode($secret);

      $this->_db->insert($table, array(
                                       'handle'=>$handle,
                                       'macFunc'=>$macFunc,
                                       'secret'=>$secret,
                                       'expires'=>$expires,
                                       ));
      return true;
   }

   /**
    * Gets association information for a given association handle.
    *
    * You must also pass by reference the other association variables, which will hold
    * retrieved data.
    *
    * The method returns true if given association was found and did not expire. It will return
    * false otherwise.
    *
    * @param    string  $handle         Association handle.
    * @param    string  $macFunc        HMAC function ('sha1' or 'sha256').
    * @param    string  $secret         Shared secret.
    * @param    long    $expires        Expiration UNIX time.
    * @return   boolean
    */
   public function getAssociation($handle, &$macFunc, &$secret, &$expires)
   {
      $table = $this->_associationTable;

      $this->_db->delete($table, $this->_db->quoteInto('expires < ?', time()));

      $select = $this->_db->select()->from($table, array('handle', 'macFunc', 'secret', 'expires'))->where('handle = ?', $handle);

      $res = $this->_db->fetchRow($select);

      if(is_array($res))
      {
         $macFunc = $res['macFunc'];
         $secret = base64_decode($res['secret']);
         $expires = $res['expires'];
         return true;
      }
      return false;

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
   public function addUser($id, $password)
   {
      $table = $this->_usersTable;

      $user = array(
                    'username'=>$id,
                    'password'=>$password,
                    'openid'=>Zend_OpenId::absoluteUrl($id)
                    );

      if($this->hasUser($id))
         return false;
      else
         $this->_db->insert($table, $user);
      return true;
   }

   /**
    * Checks whether there is an user with given URL identifier (true) or not (false).
    *
    * @param    string  $id     User URL identifier
    * @return   boolean
    */
   public function hasUser($id)
   {
      $table = $this->_usersTable;

      $user = $this->_db->select()->from($table)->where('openid = ?', $id);

      $row = $this->_db->fetchRow($user);
      if($row == null)
      {
         return false;
      }
      else
      {
         return true;
      }
   }

   /**
    * Check if user login data matches an existing user.
    *
    * @param    string  $id             User URL identifier.
    * @param    string  $password       Encrypted user password.
    * @return   boolean
    */
   public function checkUser($id, $password)
   {
      $table = $this->_usersTable;

      $select = $this->_db->select()->from($table)->where('openid = ?', $id)->where('password = ?', $password);

      $row = $this->_db->fetchRow($select);

      if($row != null)
      {

         return true;
      }
      else
      {
         return false;
      }
   }

   /**
    * Returns the list of always-allowed OpenID-enabled websites for user with given URL identifier.
    *
    * It returns boolean false value if user has no always-allowed websites.
    *
    * @param    string  $id     User URL identifier.
    * @return   array|boolean
    */
   public function getTrustedSites($id)
   {
      $table = $this->_sitesTable;

      $sites = $this->_db->select()->from($table)->where('openid = ?', $id);

      $rows = $this->_db->fetchAll($sites);
      if($rows)
      {

         $array=$rows;


         foreach($array as $num=>$arr){
             $array[$arr['site']] = unserialize($arr['trusted']);
         }

         return $array;
      }
      else
      {
          return false;
      }

   }

   /**
    * Stores information about always-allowed-denied website for user with given URL identifier.
    *
    * @param    string                                          $id             User URL identifier.
    * @param    string                                          $site           OpenID-enabled website URL.
    * @param    array|boolean|Zend_OpenId_Extension_Sreg        $trusted        Extension, array of extensions or boolean data.
    * @return   boolean
    */
   public function addSite($id, $site, $trusted)
   {
      $table = $this->_sitesTable;
      if(is_null($trusted))
      {
         $result = $this->_db->select()->from($table, array())->where('site = ?', $site);
         $this->_db->delete($table, $result);
      }
      $time = date('c',time());
      $this->_db->insert($table, array(
                                       'openid'=>$id,
                                       'site'=>$site,
                                       'trusted'=>serialize($trusted),
                                       'time'=>$time));

      return true;
   }
}

/**
 * Interface to generate an instance of OpenIdProviderStorageDB.
 */
class ZOpenIdProviderStorageDb extends ZOpenIdProviderStorage
{

   // Variables.

   /**
    * Storage Database.
    *
    * It should be an instance of a child class of Zend_Db_Adapter.
    *
    * @var      Zend_Db_Adapter
    *
    * @internal
    */
   protected $_storage = '';

   /**
    * Database driver name.
    *
    * Possible values are: 'mysql', 'sqlserver', or 'postgre'.
    *
    * @var      string
    */
   protected $_drivername = "";

   /**
    * Database name.
    *
    * @var      string
    */
   protected $_databasename = "";

   /**
    * Database server address.
    *
    * @var      string
    */
   protected $_host = "";

   /**
    * Username to access database.
    *
    * @var      string
    */
   protected $_username = "";

   /**
    * User password.
    *
    * @var      string
    */
   protected $_userpassword = "";

   /**
    * Database adapter.
    *
    * @var      string
    */
   protected $_dbadapter = null;        // TODO: consider deleting this variable, it seems it is not used anywhere.

   /**
    * Table to store site-user associations.
    *
    * @var      string
    */
   protected $_associationtable = "association_openid";

   /**
    * Table to store websites.
    *
    * @var      string
    */
   protected $_sitestable = "sites_openid";

   /**
    * Table to store users.
    *
    * @var      string
    */
   protected $_userstable = "users_openid";

   /**
    * Server port where database is reachable.
    *
    * @var      string
    */
   protected $_port = '';

   // Driver Name

   /**
    * Database driver name.
    *
    * Possible values are: 'mysql', 'sqlserver', or 'postgre'.
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
    * Database name.
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
    * Database server address.
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
    * Username to access database.
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
    * User password.
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

   // Association Table

   /**
    * Table to store site-user associations.
    */
   function getAssociationTable()    {return $this->_associationtable;}

   /**
    * Setter method for $_associationtable.
    *
    * @param    string  $value
    */
   function setAssociationTable($value)    {$this->_associationtable = $value;}

   /**
    * Getter for $_associationtable's default value.
    *
    * @return   string  'association'
    */
   function defaultAssociationTable()    {return "association_openid";}

   // Sites Table

   /**
    * Table to store websites.
    */
   function getSitesTable()    {return $this->_sitestable;}

   /**
    * Setter method for $_discoverytable.
    *
    * @param    string  $value
    */
   function setSitesTable($value)    {$this->_sitestable = $value;}

   /**
    * Getter for $_discoverytable's default value.
    *
    * @return   string  'discovery'
    */
   function defaultSitesTable()    {return "sites_openid";}

   // Users Table

   /**
    * Table to store users.
    */
   function getUsersTable()    {return $this->_userstable;}

   /**
    * Setter method for $_noncetable.
    *
    * @param    string  $value
    */
   function setUsersTable($value)    {$this->_userstable = $value;}

   /**
    * Getter for $_noncetable's default value.
    *
    * @return   string  'nonce'
    */
   function defaultUsersTable()    {return "users_openid";}

   // Port

   /**
    * Server port where database is reachable.
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

   // Class constructor.

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);

   }

   /**
   * Returns an instance of OpenIdProviderStorageDB generated from provided settings.
   *
   * @return    OpenIdProviderStorageDB
   */
   public function CreateStorage()
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
            $this->_storage = new Zend_Db_Adapter_Pdo_Mysql($options);
            break;
         case 'sqlserver':
            $this->_storage = new Zend_Db_Adapter_Pdo_Mssql($options);
            break;
         case 'postgre':
            $this->_storage = new Zend_Db_Adapter_Pdo_Pssql($options);
            break;
      }

      $openIdStorage = new OpenIdProviderStorageDb($this->_storage, $this->_userstable, $this->_sitestable, $this->_associationtable);
      return $openIdStorage;
   }
}

?>