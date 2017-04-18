<?php

/**
 * Zend/zlog.inc.php
 *
 * Defines Zend Framework Logging component.
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
use_unit("Zend/framework/library/Zend/Log.php");
use_unit("Zend/framework/library/Zend/Log/Writer/Stream.php");
use_unit("Zend/framework/library/Zend/Log/Writer/Db.php");
use_unit("Zend/framework/library/Zend/Log/Writer/Mail.php");
use_unit("Zend/framework/library/Zend/Log/Writer/Firebug.php");
use_unit("Zend/framework/library/Zend/Log/Writer/Syslog.php");
use_unit("Zend/framework/library/Zend/Log/Writer/ZendMonitor.php");
use_unit("Zend/framework/library/Zend/Log/Writer/Null.php");
use_unit("Zend/framework/library/Zend/Log/Formatter/Xml.php");
use_unit("Zend/framework/library/Zend/Log/Formatter/Simple.php");
use_unit("Zend/framework/library/Zend/Controller/Request/Http.php");
use_unit("Zend/framework/library/Zend/Controller/Response/Http.php");
use_unit('Zend/framework/library/Zend/Db/Adapter/Pdo/Mysql.php');
use_unit('Zend/framework/library/Zend/Db/Adapter/Pdo/Mssql.php');
use_unit('Zend/framework/library/Zend/Db/Adapter/Pdo/Pgsql.php');

/**
 * Base class for the different log writing classes for ZLog.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html Zend Framework Documentation
 */
class ZWriterOptions extends Persistent
{

    // Owner

   /**
    * Owner.
    *
    * @var      ZLog
    */
   protected $ZWriter = null;

   // Documented in the parent.
   function readOwner()
   {
      return ($this->ZWriter);
   }

   // Constructor

   /**
    * Class constructor.
    *
    * @param    ZLog    $aowner Owner.
    */
   function __construct($aowner)
   {
      parent::__construct();

      $this->ZWriter = $aowner;
   }

   // Active

   /**
    * Whether or not the writer is active.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_active = 'false';

   /**
    * Whether or not the writer is active.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getActive()    {return $this->_active;}

   /**
    * Setter method for $_active.
    *
    * @param    string  $value
    */
   function setActive($value)    {$this->_active = $value;}

   /**
    * Getter for $_active's default value.
    *
    * @return   string  False ('false')
    */
   function defaultActive()    {return 'false';}

   // CRIT Priority

   /**
    * Whether or not critical messages, with Zend_Log::CRIT priority, are enabled for this
    * writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_prioritycrit = 'true';

   /**
    * Whether or not critical messages, with Zend_Log::CRIT priority, are enabled for this
    * writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityCrit()    {return $this->_prioritycrit;}

   /**
    * Setter method for $_prioritycrit.
    *
    * @param    string  $value
    */
   function setPriorityCrit($value)    {$this->_prioritycrit = $value;}

   /**
    * Getter for $_prioritycrit's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityCrit()    {return 'true';}

   // EMERG Priority

   /**
    * Whether or not messages about emergencies (issues that render the system unusable), with
    * Zend_Log::EMERG priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_priorityemerg = 'true';

   /**
    * Whether or not messages about emergencies (issues that render the system unusable), with
    * Zend_Log::EMERG priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityEmerg()    {return $this->_priorityemerg;}

   /**
    * Setter method for $_priorityemerg.
    *
    * @param    string  $value
    */
   function setPriorityEmerg($value)    {$this->_priorityemerg = $value;}

   /**
    * Getter for $_priorityemerg's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityEmerg()    {return 'true';}

   // ALERT Priority

   /**
    * Whether or not alerts (issues that require immediate action), with Zend_Log::ALERT
    * priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_priorityalert = 'true';

   /**
    * Whether or not alerts (issues that require immediate action), with Zend_Log::ALERT
    * priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityAlert()    {return $this->_priorityalert;}

   /**
    * Setter method for $_priorityalert.
    *
    * @param    string  $value
    */
   function setPriorityAlert($value)    {$this->_priorityalert = $value;}

   /**
    * Getter for $_priorityalert's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityAlert()    {return 'true';}

   // ERR Priority

   /**
    * Whether or not error messages, with Zend_Log::ERR priority, are enabled for this
    * writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_priorityerr = 'true';

   /**
    * Whether or not error messages, with Zend_Log::ERR priority, are enabled for this
    * writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityErr()    {return $this->_priorityerr;}

   /**
    * Setter method for $_priorityerr.
    *
    * @param    string  $value
    */
   function setPriorityErr($value)    {$this->_priorityerr = $value;}

   /**
    * Getter for $_priorityerr's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityErr()    {return 'true';}

   // WARN Priority

   /**
    * Whether or not warnings, with Zend_Log::WARN priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_prioritywarn = 'true';

   /**
    * Whether or not warnings, with Zend_Log::WARN priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityWarn()    {return $this->_prioritywarn;}

   /**
    * Setter method for $_prioritywarn.
    *
    * @param    string  $value
    */
   function setPriorityWarn($value)    {$this->_prioritywarn = $value;}

   /**
    * Getter for $_prioritywarn's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityWarn()    {return 'true';}

   // NOTICE Priority

   /**
    * Whether or not messages about normal but significant conditions, with Zend_Log::NOTICE
    * priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_prioritynotice = 'true';

   /**
    * Whether or not messages about normal but significant conditions, with Zend_Log::NOTICE
    * priority, are enabled for this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityNotice()    {return $this->_prioritynotice;}

   /**
    * Setter method for $_prioritynotice.
    *
    * @param    string  $value
    */
   function setPriorityNotice($value)    {$this->_prioritynotice = $value;}

   /**
    * Getter for $_prioritynotice's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityNotice()    {return 'true';}

   // INFO Priority

   /**
    * Whether or not informational messages, with Zend_Log::INFO priority, are enabled for
    * this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_priorityinfo = 'true';

   /**
    * Whether or not informational messages, with Zend_Log::INFO priority, are enabled for
    * this writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityInfo()    {return $this->_priorityinfo;}

   /**
    * Setter method for $_priorityinfo.
    *
    * @param    string  $value
    */
   function setPriorityInfo($value)    {$this->_priorityinfo = $value;}

   /**
    * Getter for $_priorityinfo's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityInfo()    {return 'true';}

   // DEBUG Priority

   /**
    * Whether or not debug messages, with Zend_Log::DEBUG priority, are enabled for this
    * writer.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_prioritydebug = 'true';

   /**
    * Whether or not debug messages, with Zend_Log::DEBUG priority, are enabled for this
    * writer.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getPriorityDebug()    {return $this->_prioritydebug;}

   /**
    * Setter method for $_prioritydebug.
    *
    * @param    string  $value
    */
   function setPriorityDebug($value)    {$this->_prioritydebug = $value;}

   /**
    * Getter for $_prioritydebug's default value.
    *
    * @return   string  True ('true')
    */
   function defaultPriorityDebug()    {return 'true';}

   /**
    * Returns the list of disabled priorities.
    *
    * @return   array
    */
   function generatePriority()
   {
      $data = array();

      if( ! $this->_priorityalert)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::ALERT, "!=");
      }

      if( ! $this->_prioritycrit)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::CRIT, "!=");
      }

      if( ! $this->_prioritydebug)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::DEBUG, "!=");
      }

      if( ! $this->_priorityemerg)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::EMERG, "!=");
      }

      if( ! $this->_priorityerr)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::ERR, "!=");
      }

      if( ! $this->_priorityinfo)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::INFO, "!=");
      }

      if( ! $this->_prioritynotice)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::NOTICE, "!=");
      }

      if( ! $this->_prioritywarn)
      {
         $data[] = new Zend_Log_Filter_Priority(Zend_Log::WARN, "!=");
      }

      return $data;
   }

}

/**
 * Writer for streams (http://php.net/stream).
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.stream Zend Framework Documentation
 */
class ZWriterStream extends ZWriterOptions
{

   // Stream or URL

   /**
    * Target stream.
    *
    * You can either specify a path to a local file, or a full stream address. Check
    * PHP Documentation (http://php.net/manual/en/intro.stream.php) for additional
    * information.
    *
    * @var      string
    */
   protected $_streamorurl = '';

   /**
    * Target stream.
    *
    * You can either specify a path to a local file, or a full stream address. Check
    * PHP Documentation (http://php.net/manual/en/intro.stream.php) for additional
    * information.
    */
   function getStreamOrUrl()    {return $this->_streamorurl;}

   /**
    * Setter method for $_streamorurl.
    *
    * @param    string  $value
    */
   function setStreamOrUrl($value)    {$this->_streamorurl = $value;}

   /**
    * Getter for $_streamorurl's default value.
    *
    * @return   string  Empty string
    */
   function defaultStreamOrUrl()    {return '';}

   // Access Mode

   /**
    * Type of access to be required for the stream.
    *
    * Check fopen() (http://php.net/manual/en/function.fopen.php)'s second parameter
    * documentation for additional information. Make sure you use an access mode with write
    * permission, though.
    *
    * @var      string
    */
   protected $_modeopen = 'a';

   /**
    * Type of access to be required for the stream.
    *
    * Check fopen() (http://php.net/manual/en/function.fopen.php)'s second parameter
    * documentation for additional information. Make sure you use an access mode with write
    * permission, though.
    */
   function getModeOpen()    {return $this->_modeopen;}

   /**
    * Setter method for $_modeopen.
    *
    * @param    string  $value
    */
   function setModeOpen($value)    {$this->_modeopen = $value;}

   /**
    * Getter for $_modeopen's default value.
    *
    * @return   string  Empty string
    */
   function defaultModeOpen()    {return 'a';}

   // Simple Formatter Toggle

   /**
    * Whether or not to use simple formatter to define the way messages are logged in the stream.
    *
    * @see $_formattersimpleformat
    *
    * @var      string
    */
   protected $_formattersimpleactive = 'false';

   /**
    * Whether or not to use simple formatter to define the way messages are logged in the stream.
    *
    * @see $_formattersimpleformat
    */
   function getFormatterSimpleActive()    {return $this->_formattersimpleactive;}

   /**
    * Setter method for $_formattersimpleactive.
    *
    * @param    string  $value
    */
   function setFormatterSimpleActive($value)    {$this->_formattersimpleactive = $value;}

   /**
    * Getter for $_formattersimpleactive's default value.
    *
    * @return   string  False ('false')
    */
   function defaultFormatterSimpleActive()    {return 'false';}

   // Simple Formatter Format

   /**
    * Format definition for messages.
    *
    * This property only applies when $_formattersimpleactive is set to true ('true').
    *
    * The string should contain keys surrounded by percent signs which will be replaced with the
    * actual values. Some possible keys to be included are: message, timestamp, priorityName or
    * priority. Default format is: '%timestamp% %priorityName% (%priority%): %message%'.
    *
    * @link     http://framework.zend.com/manual/en/zend.log.formatters.html#zend.log.formatters.simple Zend Documentation
    *
    * @var      string
    */
   protected $_formattersimpleformat = '';

   /**
    * Format definition for messages.
    *
    * This property only applies when $_formattersimpleactive is set to true ('true').
    *
    * The string should contain keys surrounded by percent signs which will be replaced with the
    * actual values. Some possible keys to be included are: message, timestamp, priorityName or
    * priority. Default format is: '%timestamp% %priorityName% (%priority%): %message%'.
    *
    * @link     http://framework.zend.com/manual/en/zend.log.formatters.html#zend.log.formatters.simple Zend Documentation
    */
   function getFormatterSimpleFormat()    {return $this->_formattersimpleformat;}

   /**
    * Setter method for $_formattersimpleformat.
    *
    * @param    string  $value
    */
   function setFormatterSimpleFormat($value)    {$this->_formattersimpleformat = $value;}

   /**
    * Getter for $_formattersimpleformat's default value.
    *
    * @return   string  Empty string
    */
   function defaultFormatterSimpleFormat()    {return '';}

   // XML Formatter Toggle

   /**
    * Whether or not to use XML formatter to define the way messages are logged in the stream.
    *
    * @var      string
    */
   protected $_formatterxmlactive = 'false';

   /**
    * Whether or not to use XML formatter to define the way messages are logged in the stream.
    */
   function getFormatterXmlActive()    {return $this->_formatterxmlactive;}

   /**
    * Setter method for $_formatterxmlactive.
    *
    * @param    string  $value
    */
   function setFormatterXmlActive($value)    {$this->_formatterxmlactive = $value;}

   /**
    * Getter for $_formatterxmlactive's default value.
    *
    * @return   string  False ('false')
    */
   function defaultFormatterXmlActive()    {return 'false';}

   // XML Root Element

   /**
    * Name of the root element for resulting XML code.
    *
    * @var      string
    */
   protected $_formatterxmlrootelement = '';

   /**
    * Name of the root element for resulting XML code.
    */
   function getFormatterXmlRootElement()    {return $this->_formatterxmlrootelement;}

   /**
    * Setter method for $_formatterxmlrootelement.
    *
    * @param    string  $value
    */
   function setFormatterXmlRootElement($value)    {$this->_formatterxmlrootelement = $value;}

   /**
    * Getter for $_formatterxmlrootelement's default value.
    *
    * @return   string  Empty string
    */
   function defaultFormatterXmlRootElement()    {return '';}

   protected $_formatterxmlelements = array();

   /**
    * Names of the child elements for the resulting XML code, as an array with key-value pairs, where each key is the
    * name of a child element to be used in the XML code, and its value is the real name of the element.
    *
    * @link http://framework.zend.com/manual/en/zend.log.formatters.html#zend.log.formatters.xml Zend Documentation
    *
    * @return array
    */
   function getFormatterXmlElements()    {return $this->_formatterxmlelements;}
   function setFormatterXmlElements($value)    {$this->_formatterxmlelements = $value;}
   function defaultFormatterXmlElements()    {return array();}

   // Generator

   /**
    * Returns defined writer.
    *
    * If either $_streamorurl or $_modeopen properties are empty, this method will
    * return a boolean value, false.
    *
    * @return   boolean|Zend_Log_Writer_Stream
    */
   function createWriter()
   {
      if($this->_streamorurl != '' && $this->_modeopen != '')
      {
         $writer = new Zend_Log_Writer_Stream($this->_streamorurl, $this->_modeopen);

         if($this->_formattersimpleactive)
         {
            $formatter = new Zend_Log_Formatter_Simple($this->_formattersimpleformat . PHP_EOL);
            $writer->setFormatter($formatter);
         }

         if($this->_formatterxmlactive)
         {
            if($this->_formatterxmlrootelement == '')
            {
               $root = 'logEntry';
            }
            else
            {
               $root = $this->_formatterxmlrootelement;
            }

            if(count($this->_formatterxmlelements) != 0)
            {
               $elements = array_flip($this->_formatterxmlelements);
            }
            else
            {
               $elements = null;
            }

            $formatter = new Zend_Log_Formatter_Xml($root, $elements);
            $writer->setFormatter($formatter);
         }

         $filters = $this->generatePriority();

         foreach($filters as $filter)
         {
            $writer->addFilter($filter);
         }

         return $writer;
      }
      else
      {
         return false;
      }
   }
}

/**
 * Writer for databases.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.database Zend Framework Documentation
 */
class ZWriterDB extends ZWriterOptions
{

   // Host

   /**
    * Database host address.
    *
    * @var      string
    */
   protected $_host = '';

   /**
    * Database host address.
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
   function defaultHost()    {return '';}

   // Username

   /**
    * Username to access the database with.
    *
    * @var      string
    */
   protected $_username = '';

   /**
    * Username to access the database with.
    */
   function getUsername()    {return $this->_username;}

   /**
    * Setter method for $_username.
    *
    * @param    string  $value
    */
   function setUsername($value)    {$this->_username = $value;}

   /**
    * Getter for $_username's default value.
    *
    * @return   string  Empty string
    */
   function defaultUsername()    {return '';}

   // Password

   /**
    * User password to access the database.
    *
    * @var      string
    */
   protected $_password = '';

   /**
    * User password to access the database.
    */
   function getPassword()    {return $this->_password;}

   /**
    * Setter method for $_password.
    *
    * @param    string  $value
    */
   function setPassword($value)    {$this->_password = $value;}

   /**
    * Getter for $_password's default value.
    *
    * @return   string  Empty string
    */
   function defaultPassword()    {return '';}

   // Database Name

   /**
    * Database name.
    *
    * @var      string
    */
   protected $_databasename = '';

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
   function defaultDatabaseName()    {return '';}

   // Table Name

   /**
    * Name of the table inside the database where log should be stored.
    *
    * @var      string
    */
   protected $_tablename = '';

   /**
    * Name of the table inside the database where log should be stored.
    */
   function getTableName()    {return $this->_tablename;}

   /**
    * Setter method for $_tablename.
    *
    * @param    string  $value
    */
   function setTableName($value)    {$this->_tablename = $value;}

   /**
    * Getter for $_tablename's default value.
    *
    * @return   string  Empty string
    */
   function defaultTableName()    {return '';}

   // Priority Column

   /**
    * Column from $_tablename to store priority data.
    *
    * @var      string
    */
   protected $_columnpriority = '';

   /**
    * Column from $_tablename to store priority data.
    */
   function getColumnPriority()    {return $this->_columnpriority;}

   /**
    * Setter method for $_columnpriority.
    *
    * @param    string  $value
    */
   function setColumnPriority($value)    {$this->_columnpriority = $value;}

   /**
    * Getter for $_columnpriority's default value.
    *
    * @return   string  Empty string
    */
   function defaultColumnPriority()    {return '';}

   // Message Column

   /**
    * Column from $_tablename to store message data.
    *
    * @var      string
    */
   protected $_columnmessage = '';

   /**
    * Column from $_tablename to store message data.
    */
   function getColumnMessage()    {return $this->_columnmessage;}

   /**
    * Setter method for $_columnmessage.
    *
    * @param    string  $value
    */
   function setColumnMessage($value)    {$this->_columnmessage = $value;}

   /**
    * Getter for $_columnmessage's default value.
    *
    * @return   string  Empty string
    */
   function defaultColumnMessage()    {return '';}

   // Timestamp Column

   /**
    * Column from $_tablename to store timestamp.
    *
    * @var      string
    */
   protected $_columntimestamp = '';

   /**
    * Column from $_tablename to store timestamp.
    */
   function getColumnTimestamp()    {return $this->_columntimestamp;}

   /**
    * Setter method for $_columntimestamp.
    *
    * @param    string  $value
    */
   function setColumnTimestamp($value)    {$this->_columntimestamp = $value;}

   /**
    * Getter for $_columntimestamp's default value.
    *
    * @return   string  Empty string
    */
   function defaultColumnTimestamp()    {return '';}

   // Priority Name Column

   /**
    * Column from $_tablename to store priority name.
    *
    * @var      string
    */
   protected $_columnpriorityname = '';

   /**
    * Column from $_tablename to store priority name.
    */
   function getColumnPriorityName()    {return $this->_columnpriorityname;}

   /**
    * Setter method for $_columnpriorityname.
    *
    * @param    string  $value
    */
   function setColumnPriorityName($value)    {$this->_columnpriorityname = $value;}

   /**
    * Getter for $_columnpriorityname's default value.
    *
    * @return   string  Empty string
    */
   function defaultColumnPriorityName()    {return '';}

   // Database Driver

   /**
    * Database driver.
    *
    * @var      string
    */
   protected $_drivername = 'mysql';

   /**
    * Database driver.
    */
   function getDrivername()    {return $this->_drivername;}

   /**
    * Setter method for $_drivername.
    *
    * @param    string  $value
    */
   function setDrivername($value)    {$this->_drivername = $value;}

   /**
    * Getter for $_drivername's default value.
    *
    * @return   string  Empty string
    */
   function defaultDrivername()    {return 'mysql';}

   // Port

   /**
    * Database port number.
    *
    * @var      string
    */
   protected $_port = '';

   /**
    * Database port number.
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

   // Generator

   /**
    * Returns defined writer.
    *
    * If $_tablename is empty, this method will return a boolean value, false.
    *
    * @return   boolean|Zend_Log_Writer_Db
    */
   function createWriter()
   {
      $options = array(
                       'host'=>$this->_host,
                       'username'=>$this->_username,
                       'password'=>$this->_password,
                       'dbname'=>$this->_databasename
                       );
      if($this->_port != '')
      {
         $options['port'] = $this->_port;
      }


      switch($this->_drivername)
      {
         case 'mysql':
            $db = new Zend_Db_Adapter_Pdo_Mysql($options);
            break;
         case 'postgre':
            $db = new Zend_Db_Adapter_Pdo_Mssql($options);
            break;
         case 'sqlserver':
            $db = new Zend_Db_Adapter_Pdo_Pgsql($options);
            break;
      }

      $columns = array();

      if($this->_columnpriority != '')
      {
         $columns[$this->_columnpriority] = 'priority';
      }

      if($this->_columnmessage != '')
      {
         $columns[$this->_columnmessage] = 'message';
      }

      if($this->_columntimestamp != '')
      {
         $columns[$this->_columntimestamp] = 'timestamp';
      }

      if($this->_columnpriorityname != '')
      {
         $columns[$this->_columnpriorityname] = 'priorityname';
      }

      if($this->_tablename != '')
      {
         $writer = new Zend_Log_Writer_Db($db, $this->_tablename, $columns);

         $filters = $this->generatePriority();

         foreach($filters as $filter)
         {
            $writer->addFilter($filter);
         }

         return $writer;
      }
      else
      {

         return false;
      }
   }
}

/**
 * Writer for Firebug (http://getfirebug.com/) Firefox extension.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.firebug Zend Framework Documentation
 */
class ZWriterFirebug extends ZWriterOptions
{

   // Default Priority Style

   /**
    * Default style for custom priorities.
    *
    * @var      string
    */
   protected $_defaultprioritystyle = '';

   /**
    * Default style for custom priorities.
    */
   function getDefaultPriorityStyle()    {return $this->_defaultprioritystyle;}

   /**
    * Setter method for $_defaultprioritystyle.
    *
    * @param    string  $value
    */
   function setDefaultPriorityStyle($value)    {$this->_defaultprioritystyle = $value;}

   /**
    * Getter for $_defaultprioritystyle's default value.
    *
    * @return   string  Empty string
    */
   function defaultDefaultPriorityStyle()    {return '';}

   protected $_addprioritystyle = array();
   /**
    * Priority styles.
    *
    * Array containing key-value pairs. Each key is a priority number (integer), and is value is a
    * string with the style code to be applied to that priority.
    *
    * Check Zend Documentation (http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.firebug.priority-styles)
    * for available Firebug logging styles.
    *
    * @return array
    */
   function getAddPriorityStyle()    {return $this->_addprioritystyle;}
   function setAddPriorityStyle($value)    {$this->_addprioritystyle = $value;}
   function defaultAddPriorityStyle()    {return array();}

   // Generator

   /**
    * Returns defined writer.
    *
    * @return   Zend_Log_Writer_Firebug
    */
   function createWriter()
   {
      $writer = new Zend_Log_Writer_Firebug();

      if($this->_defaultprioritystyle != '')
      {
         $writer->setDefaultPriorityStyle($this->_defaultprioritystyle);
      }

      foreach($this->_addprioritystyle as $num=>$style)
      {
         $writer->setPriorityStyle($num, $style);
      }

      $filters = $this->generatePriority();

      foreach($filters as $filter)
      {
         $writer->addFilter($filter);
      }

      return $writer;
   }
}

/**
 * Writer for email.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.mail Zend Framework Documentation
 */
class ZWriterMail extends ZWriterOptions
{

   // ZMail

   /**
    * Instance of ZMail to send the logs with.
    *
    * @var      ZMail
    */
   public $_zmail = null;

   /**
    * Instance of ZMail to send the logs with.
    */
   function getZMail()    {return $this->_zmail;}

   /**
    * Setter method for $_zmail.
    *
    * @param    ZMail   $value
    */
   function setZMail($value)    {$this->_zmail = $value;}

   /**
    * Getter for $_zmail's default value.
    *
    * @return   ZMail   Null
    */
   function defaultZMail()    {return null;}

   // Generator

   /**
    * Returns defined writer.
    *
    * If $_zmail is null, this method will return a boolean value, false.
    *
    * @return   boolean|Zend_Log_Writer_Mail
    */
   function createWriter()
   {
      if(is_object($this->_zmail))
      {
         $mail = $this->_zmail->createMail();

         $writer = new Zend_Log_Writer_Mail($mail);

         $filters = $this->generatePriority();

         foreach($filters as $filter)
         {
            $writer->addFilter($filter);
         }
         return $writer;
      }
      else
      {
         return false;
      }
   }
}

/**
 * Writer for the system log.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.syslog Zend Framework Documentation
 */
class ZWriterSyslog extends ZWriterOptions
{

   // Application

   /**
    * Application name as it will be written to the log.
    *
    * @var      string
    */
   protected $_application = '';

   /**
    * Application name as it will be written to the log.
    */
   function getApplication()    {return $this->_application;}

   /**
    * Setter method for $_application.
    *
    * @param    string  $value
    */
   function setApplication($value)    {$this->_application = $value;}

   /**
    * Getter for $_application's default value.
    *
    * @return   string  Empty string
    */
   function defaultApplication()    {return '';}

   // Facility

   /**
    * Application type as it will be written to the log.
    *
    * @var      string
    */
   protected $_facility = '';

   /**
    * Application type as it will be written to the log.
    */
   function getFacility()    {return $this->_facility;}

   /**
    * Setter method for $_facility.
    *
    * @param    string  $value
    */
   function setFacility($value)    {$this->_facility = $value;}

   /**
    * Getter for $_facility's default value.
    *
    * @return   string  Empty string
    */
   function defaultFacility()    {return '';}

   // Generator

   /**
    * Returns defined writer.
    *
    * @return   Zend_Log_Writer_Syslog
    */
   function createWriter()
   {

      $data = array();
      if($this->_facility != '')
      {

         $data['facility'] = $this->_facility;
      }

      if($this->_application != '')
      {

         $data['application'] = $this->_application;
      }

      $writer = new Zend_Log_Writer_Syslog($data);

      $filters = $this->generatePriority();

      foreach($filters as $filter)
      {
         $writer->addFilter($filter);
      }
      return $writer;
   }

}

/**
 * Writer for Zend Server Monitor.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.zendmonitor Zend Framework Documentation
 */
class ZWriterZendMonitor extends ZWriterOptions
{

   // Generator

   /**
    * Returns the writer.
    *
    * @return   Zend_Log_Writer_ZendMonitor
    */
   function createWriter()
   {
      $writer = new Zend_Log_Writer_ZendMonitor();

      $filters = $this->generatePriority();

      foreach($filters as $filter)
      {
         $writer->addFilter($filter);
      }
      return $writer;
   }
}

/**
 * Null writer, to silence log.
 *
 * Can render useful during testing.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.writers.html#zend.log.writers.null Zend Framework Documentation
 */
class ZWriterNull extends ZWriterOptions
{

   // Generator

   /**
    * Returns the writer.
    *
    * @return   Zend_Log_Writer_Null
    */
   function createWriter()
   {

      $writer = new Zend_Log_Writer_Null();

      $filters = $this->generatePriority();

      foreach($filters as $filter)
      {
         $writer->addFilter($filter);
      }
      return $writer;
   }
}

/**
 * Component to generate logs.
 *
 * @link        http://framework.zend.com/manual/en/zend.log.html Zend Framework Documentation
 */
class ZLog extends Component
{

   /**
    * Zend Framework Log instance.
    *
    * @var      Zend_Log
    */
   private $_log = null;

   // Stream Writer

   /**
    * Stream writer instance.
    *
    * @var      ZWriterStream
    */
   protected $_writerstream = null;

   /**
    * Stream writer instance.
    */
   function getWriterStream()    {return $this->_writerstream;}

   /**
    * Setter method for $_writerstream.
    *
    * @param    ZWriterStream   $value
    */
   function setWriterStream($value)    {if(is_object($value))        {$this->_writerstream = $value;}}

   /**
    * Getter for $_writerstream's default value.
    *
    * @return   ZWriterStream   Null
    */
   function defaultWriterStream()    {return null;}

   // Database Writer

   /**
    * Database writer instance.
    *
    * @var      ZWriterDB
    */
   protected $_writerdatabase = null;

   /**
    * Database writer instance.
    */
   function getWriterDatabase()    {return $this->_writerdatabase;}

   /**
    * Setter method for $_writerdatabase.
    *
    * @param    ZWriterDB       $value
    */
   function setWriterDatabase($value)    {if(is_object($value))        {$this->_writerdatabase = $value;}}

   /**
    * Getter for $_writerdatabase's default value.
    *
    * @return   ZWriterDB       Null
    */
   function defaultWriterDatabase()    {return null;}

   // Firebug Writer

   /**
    * Firebug writer instance.
    *
    * @var      ZWriterFirebug
    */
   protected $_writerfirebug = null;

   /**
    * Firebug writer instance.
    */
   function getWriterFirebug()    {return $this->_writerfirebug;}

   /**
    * Setter method for $_writerfirebug.
    *
    * @param    ZWriterFirebug  $value
    */
   function setWriterFirebug($value)    {if(is_object($value))        {$this->_writerfirebug = $value;}}

   /**
    * Getter for $_writerfirebug's default value.
    *
    * @return   ZWriterFirebug  Null
    */
   function defaultWriterFirebug()    {return null;}

   // Email Writer

   /**
    * Email writer instance.
    *
    * @var      ZWriterMail
    */
   protected $_writeremail = null;

   /**
    * Email writer instance.
    */
   function getWriterEmail()    {return $this->_writeremail;}

   /**
    * Setter method for $_writeremail.
    *
    * @param    ZWriterMail     $value
    */
   function setWriterEmail($value)    {if(is_object($value))        {$this->_writeremail = $value;}}

   /**
    * Getter for $_writeremail's default value.
    *
    * @return   ZWriterMail     Null
    */
   function defaultWriterEmail()    {return null;}

   // System Log Writer

   /**
    * System log writer instance.
    *
    * @var      ZWriterSyslog
    */
   protected $_writersyslog = null;

   /**
    * System log writer instance.
    */
   function getWriterSyslog()    {return $this->_writersyslog;}

   /**
    * Setter method for $_writersyslog.
    *
    * @param    ZWriterSyslog   $value
    */
   function setWriterSyslog($value)    {if(is_object($value))        {$this->_writersyslog = $value;}}

   /**
    * Getter for $_writersyslog's default value.
    *
    * @return   ZWriterSyslog   Null
    */
   function defaultWriterSyslog()    {return null;}

   // Zend Server Monitor Writer

   /**
    * Zend server monitor writer instance.
    *
    * @var      ZWriterZendMonitor
    */
   protected $_writerzendmonitor = null;

   /**
    * Zend server monitor writer instance.
    */
   function getWriterZendMonitor()    {return $this->_writerzendmonitor;}

   /**
    * Setter method for $_writerzendmonitor.
    *
    * @param    ZWriterZendMonitor      $value
    */
   function setWriterZendMonitor($value)    {if(is_object($value))        {$this->_writerzendmonitor = $value;}}

   /**
    * Getter for $_writerzendmonitor's default value.
    *
    * @return   ZWriterZendMonitor      Null
    */
   function defaultWriterZendMonitor()    {return null;}

   // Null Writer

   /**
    * Null writer instance.
    *
    * @var      ZWriterNull
    */
   protected $_writernull = null;

   /**
    * Null writer instance.
    */
   function getWriterNull()    {return $this->_writernull;}

   /**
    * Setter method for $_writernull.
    *
    * @param    ZWriterNull     $value
    */
   function setWriterNull($value)    {if(is_object($value))        {$this->_writernull = $value;}}

   /**
    * Getter for $_writernull's default value.
    *
    * @return   ZWriterNull     Null
    */
   function defaultWriterNull()    {return null;}

   protected $_addpriority = array();
   /**
    * Custom priorities.
    *
    * The array contains key-value pairs. Each key is the name of a custom priority, and its value
    * is the numeric (integer) value associated to it.
    *
    * Existing priorities can not be overwritten.
    *
    * @return array
    */
   function getAddPriority()    {return $this->_addpriority;}
   function setAddPriority($value)    {$this->_addpriority = $value;}
   function defaultAddPriority()    {return array();}

   // Class constructor.

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->_writerstream = new ZWriterStream($this);
      $this->_writerdatabase = new ZWriterDB($this);
      $this->_writerfirebug = new ZWriterFirebug($this);
      $this->_writeremail = new ZWriterMail($this);
      $this->_writersyslog = new ZWriterSyslog($this);
      $this->_writerzendmonitor = new ZWriterZendMonitor($this);
      $this->_writernull = new ZWriterNull($this);

   }

   // Loaded

   // Documented in the parent.
   function loaded()
   {
      parent::loaded();

      $this->_writeremail->setZMail($this->fixupProperty($this->_writeremail->getZMail()));
      $this->_createLog();
   }

   /**
    * Generator for $_log.
    *
    * Generates a Zend Framework Log from defined properties or defaults, and saves it to
    * $_log.
    *
    * @throws   Zend_Log_Exception      Some custom priorities overwrite
    *                                   existing ones.
    */
   function _createLog()
   {
      $this->_log = new Zend_Log();

      foreach($this->_addpriority as $num=>$priority)
      {
         $this->_log->addPriority($priority, $num);
      }
      if($this->_writerstream->getActive() != 'false')
      {
         $this->_log->addWriter($this->_writerstream->createWriter());
      }

      if($this->_writerdatabase->getActive() != 'false')
      {
         $this->_log->addWriter($this->_writerdatabase->createWriter());
      }

      if($this->_writerfirebug->getActive() != 'false')
      {

         $this->_log->addWriter($this->_writerfirebug->createWriter());
      }

      if($this->_writeremail->getActive() != 'false')
      {
         $this->_log->addWriter($this->_writeremail->createWriter());
      }

      if($this->_writersyslog->getActive() != 'false')
      {
         $this->_log->addWriter($this->_writersyslog->createWriter());
      }

      if($this->_writerzendmonitor->getActive() != 'false')
      {
         $this->_log->addWriter($this->_writerzendmonitor->createWriter());
      }

      if($this->_writernull->getActive() != 'false')
      {
         $this->_log->addWriter($this->_writernull->createWriter());
      }
   }

   /**
    * Sends a log entry with EMERG priority.
    *
    * @param    string  $text   Log message.
    */
   function emerg($text)
   {
      $this->message($text, Zend_Log::EMERG);
   }

   /**
    * Sends a log entry with ALERT priority.
    *
    * @param    string  $text   Log message.
    */
   function alert($text)
   {
      $this->message($text, Zend_Log::ALERT);
   }

   /**
    * Sends a log entry with CRIT priority.
    *
    * @param    string  $text   Log message.
    */
   function crit($text)
   {
      $this->message($text, Zend_Log::CRIT);
   }

   /**
    * Sends a log entry with ERR priority.
    *
    * @param    string  $text   Log message.
    */
   function err($text)
   {
      $this->message($text, Zend_Log::ERR);
   }

   /**
    * Sends a log entry with WARN priority.
    *
    * @param    string  $text   Log message.
    */
   function warn($text)
   {
      $this->message($text, Zend_Log::WARN);
   }

   /**
    * Sends a log entry with NOTICE priority.
    *
    * @param    string  $text   Log message.
    */
   function notice($text)
   {
      $this->message($text, Zend_Log::NOTICE);
   }

   /**
    * Sends a log entry with INFO priority.
    *
    * @param    string  $text   Log message.
    */
   function info($text)
   {
      $this->message($text, Zend_Log::INFO);
   }

   /**
    * Sends a log entry with DEBUG priority.
    *
    * @param    string  $text   Log message.
    */
   function debug($text)
   {
      $this->message($text, Zend_Log::DEBUG);
   }

   /**
    * Sends a log entry.
    *
    * @param    string  $text           Log message.
    * @param    integer $priority       Log priority.
    */
   function message($text, $priority)
   {
      $request = new Zend_Controller_Request_Http();
      $response = new Zend_Controller_Response_Http();
      $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
      $channel->setRequest($request);
      $channel->setResponse($response);
      ob_start();
      $this->_log->log($text, $priority);
      $channel->flush();
      $response->sendHeaders();
   }
}

?>
