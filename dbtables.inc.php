<?php

/**
*  This file is part of the RPCL project
*
*  Copyright (c) 2004-2012 Embarcadero Technologies, Inc.
*
*  Checkout AUTHORS file for more information on the developers
*
*  This library is free software; you can redistribute it and/or
*  modify it under the terms of the GNU Lesser General Public
*  License as published by the Free Software Foundation; either
*  version 2.1 of the License, or (at your option) any later version.
*
*  This library is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
*  Lesser General Public License for more details.
*
*  You should have received a copy of the GNU Lesser General Public
*  License along with this library; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307
*  USA
*
*/

use_unit("dbpdo.inc.php");
use_unit("db.inc.php");


/**
 * Component to perform a connection to a database.
 *
 * @link wiki://Database
 */
class Database extends CustomConnection
{

    // DATABASE-SPECIFIC PROPERTIES

    protected $_charset="";
    /**
     * Character encoding.
     *
     * @return string
     */
    function getCharset() { return $this->_charset; }
    function setCharset($value) { $this->_charset=$value; }
    function defaultCharset() { return ""; }

    protected $_databasename="";
    /**
     * Name of the database.
     *
     * @return string
     */
    function getDatabaseName() { return $this->_databasename; }
    function setDatabaseName($value) { $this->_databasename=$value; }
    function defaultDatabaseName() { return ""; }

    protected $_host="";
    /**
     * Address of the server hosting the database.
     *
     * @return string
     */
    function getHost() { return $this->_host; }
    function setHost($value) { $this->_host=$value; }
    function defaultHost() { return ""; }

    protected $_port="";
    /**
     * Port at which the server is providing access to the database.
     *
     * @return string
     */
    function getPort() { return $this->_port; }
    function setPort($value) { $this->_port=$value; }
    function defaultPort() { return ""; }

    protected $_hosttranslation=1;
    /**
     * Whether the Host value should be translated into an IP address (1) or not (0).
     *
     * For example, if you choose "localhost" as Host and set this property to 1, "localhost" will be converted to
     * "127.0.0.1" when establishing the connection to the database server.
     *
     * @return int
     */
    function getHostTranslation() { return $this->_hosttranslation; }
    function setHostTranslation($value) { $this->_hosttranslation=$value; }
    function defaultHostTranslation() { return 1; }

    protected $_username="";
    /**
     * Username to log in with when connecting to the database server. 
     */
    function getUserName() { return $this->_username; }
    function setUserName($value) { $this->_username=$value; }
    function defaultUserName() { return ""; }

    protected $_userpassword="";
    /**
     * Password for the username to log in with when connecting to the database server.
     */
    function getUserPassword() { return $this->_userpassword; }
    function setUserPassword($value) { $this->_userpassword=$value; }
    function defaultUserPassword() { return ""; }

    protected $_dictionary=null;
    /*
     *
     */
    function getDictionary() { return $this->_dictionary; }
    function setDictionary($value) { $this->_dictionary=$value; }
    function defaultDictionary() { return null; }

    protected $_connectionparams=array();
    /**
     * Associative array of key-value pairs to be used as additional parameters on the Data Source Name used when
     * establishing the connection to the database server.
     *
     * @see DBPDO::DNSParams()
     *
     * @return array
     */
    function getConnectionParams() {return $this->_connectionparams; }
    function setConnectionParams($value)
    {
        if(is_array($value))
        {
            $this->_connectionparams = $value;
        }
        else
        {
            $this->_connectionparams = (empty($value)) ? array(): array($value);
        }
    }
    function defaultConnectionParams() { return null; }

    protected $_databaseoptions=array();
    /**
     * Associative array of key-value pairs defining driver-specific options to be passed to the constructor of the
     * underlying PDO instance.
     *
     * @link http://www.php.net/manual/en/pdo.construct.php
     *
     * @return array
     */
    function getDatabaseOptions() { return $this->_databaseoptions; }
    function setDatabaseOptions($value)
    {
        if(is_array($value))
        {
            $this->_databaseoptions = $value;
        }
        else
        {
            $this->_databaseoptions = (empty($value)) ? array(): array($value);
        }
    }
    function defaultDatabaseOptions() { return null; }

    /* ------------------------
     *     PUBLISHED ATTRIBUTTES
     * ------------------------ */

    /**
     * Whether the connection is open (true) or closed (false).
     *
     * @return bool
     */
    function readConnected() {return $this->_connection != null;}
    function getConnected() { return $this->readconnected(); }
    function setConnected($value) { $this->writeconnected($value); }

    /* ------------------------
     *     INHERIT ATTRIBUTTES
     * ------------------------ */

    // Documented in the parent.
    function getDriverName() { return $this->_drivername; }
    function setDriverName($value) { $this->_drivername=$value; }
    function defaultDriverName() { return ""; }


    /* ------------------------
     *     OWN METHODS
     * ------------------------ */

    /*
     *
     */
    function createDictionary()
    {
        // TODO: Implement.
    }

    protected $_connection;
    /**
     * Instance of DBPDO.
     *
     * @see doConnect()
     *
     * @return DBPDO
     */
    function readConnection() {return $this->_connection;}


    /* ------------------------
     *     INHERIT METHODS
     * ------------------------ */

    /**
     * Runs the given query on the database.
     *
     * You can optionally provide an array of parameters to be used in the query. Such parameters will replace
     * the place holders that you defined in the query string using the param() method.
     *
     * <code>
     * $statement = $database->execute("SELECT something FROM somewhere WHERE somethingElse = ".$database->param("number"), array(6));
     * </code>
     *
     * @param string $query  SQL query.
     * @param array  $params Parameters to be used in the SQL query.
     *
     * @return PDOStatement
     */
    function execute($query, $params = array())
    {
        $this->open(); // this prevents execute without connection
        return $this->Connection->execute($query, $params);
    }

    /**
     * Executes the given SQL query for a procedure.
     *
     * @see procedureSQL()
     *
     * @param string $query  SQL query to call a procedure.
     * @param array  $params Array of parameters to be used on the procedure.
     *
     * @return bool
     *
     * @throws DatabaseError Raised when the DBPDO implementation does not support stored procedures.
     */
    function executeProc($query, $params = array())
    {
        $this->checkConnected();
        return $this->Connection->executeProc($query, $params);
    }

    /**
     * Returns a PDO statement for the given query string that you can bind params to and execute.
     *
     * @param string $query         SQL query for the statement.
     * @param array  $driveroptions Driver options to be used in the statement.
     *
     * @return PDOStatement
     */
    function prepare($query, $driveroptions = array())
    {
        $this->checkConnected();
        return $this->Connection->prepare($query, $driveroptions);
    }

    /**
     * Place holder for a parameter that can be printed in an SQL query string, to be later safely associated to the
     * actual parameter value.
     *
     * @see execute()
     *
     * @param string $value Actual value the place holder will be later replaced with.
     *
     * @return string Place holder to be used for the given value.
     */
    function param($value)
    {
        $this->checkConnected();
        return $this->Connection->param($value);
    }

    /**
     * Quotes the target string to be safely used inside a query string as a parameter.
     *
     * @param mixed $value String to be quoted for safe inclusion in a query string.
     *
     * @return string String with the given value properly quoted.
     */
    function quoteStr($value)
    {
        $this->checkConnected();
        return $this->Connection->quote($value);
    }

    /**
     * Begins a transaction against the data provider.
     *
     * After calling this method, further operations on the data at the other point of the connection will be held until
     * a call to completeTrans(), which will perform those operations altogether.
     *
     * <code>
     * $database->beginTrans();
     * $database->execute("INSERT INTO somewhere (field) VALUES (".$database->param("value").")", array("Some value"));
     * $database->execute("INSERT INTO somewhere (field) VALUES (".$database->param("value").")", array("Another value"));
     * $database->completeTrans(); // The execute() operations are not actually executed until this point.
     * </code>
     *
     * @return bool Returns true on sucecss or false on failure.
     */
    function beginTrans()
    {
        $this->checkConnected();
        return $this->Connection->beginTrans();
    }

    /**
     * Finishes a transaction started with beginTrans(), either commiting all the operations on the data provider that
     * were requested since the transaction started ($commit = true, default), or rolling them back so none of them are
     * actually performed against the data provider ($commit = false).
     *
     * <code>
     * $database->beginTrans();
     * $insertResult1 = $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$database->param("value").")", array("Some value"));
     * $insertResult2 = $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$database->param("value").")", array("Another value"));
     * $commit = $insertResult1 && $insertResult2; // Evaluates to false if any of the operations failed.
     * $database->completeTrans($commit);
     * </code>
     *
     * @param bool $commit Whether the operations in the transaction should be commited (true) or rolled back (false).
     *
     * @return bool Returns true on sucecss or false on failure.
     */
    function completeTrans($commit = true)
    {
        $this->checkConnected();
        return $this->Connection->completeTrans($commit);
    }

    // Documented in the parent.
    function doConnect()
    {
        if (($this->ControlState & csDesigning)!=csDesigning)
        {
            if(!$this->_connection)
            {
                $this->_connection = DBPDO::factory($this);
            }

            return $this->Connection->doConnect();
        }
    }

    // Documented in the parent.
    function doDisconnect()
    {
      if (($this->ControlState & csDesigning)!=csDesigning)
      {
         $connection = $this->_connection;
         $this->_connection = null;
         return $connection->doDisconnect();
      }
    }

    /**
     * Establish the connection in case it is not established already.
     *
     * @internal
     */
    function checkConnected()
    {
        if($this->Connected == false)
        {
            $this->Connected = true;
        }
    }

    // Documented in the parent.
    function inTransaction()
    {
        $this->checkConnected();
        return $this->Connection->inTransaction();
    }

    /**
     * Returns the identifier of the last inserted row. You can provide the name of a target row to get its identifier
     * instead.
     *
     * @link http://php.net/manual/en/pdo.lastinsertid.php
     *
     * @param string $name Optional. The name of a row whose identifier will be returned by this method.
     *
     * @return string
     */
    function lastInsertId($name = NULL)
    {
        $this->checkConnected();
        return $this->Connection->lastInsertId($name);
    }

    /**
     * Returns the names of the columns in the given table.
     *
     * @param string $table The name of the target table.
     *
     * @return array Associative array of key-value pairs where the keys are the column names of the target table, and the
     * values are always NULL.
     */
    function metaFields($table)
    {
        $this->checkConnected();
        return $this->Connection->metaFields($table);
    }

    /**
     * Returns the names of the columns in the given table that are part of the primary key.
     *
     * @param string $table The name of the target table.
     *
     * @return array Associative array of key-value pairs where the keys are the names of the columns that are part of the
     *               primary key, and the values are always PRIMARY.
     */
    function primaryKeys($table)
    {
        $this->checkConnected();
        return $this->Connection->primaryKeys($table);
    }


    // HELPER FUNCTIONS

    /**
     * Whether the database's statements are scrollable (true) or not (false).
     *
     * @return bool True if statements are scrollable, false otherwise.
     */
    function isEnabledScroll()
    {
        return $this->Connection->isEnabledScroll();
    }

    /**
     * Parses the DatabaseOptions array, replacing the values and keys with PDO constants when possible.
     *
     * @param array $options Unparsed options, as an associative array of key-value pairs.
     *
     * @return array Original array of options parsed to be used with the PDO constructor.
     */
    function parseOptions($options)
    {
        $pdoOptions = array();

        if(is_array($options))
        {
            foreach($options as $key => $value)
            {
                // if the pdo constant doesn't exist, get the value instead
                $pdoConstantKey   = defined('PDO::'.$key)   ? constant('PDO::'.$key)   : $key;
                $pdoConstantValue = defined('PDO::'.$value) ? constant('PDO::'.$value) : (int) $value;

                $pdoOptions[$pdoConstantKey] = $pdoConstantValue;
            }
        }

        return $pdoOptions;
    }

    /**
     * Updates some data of a record or set of records in a table.
     *
     * @param string $table The name of the table to update the record from.
     * @param array  $keys  Associative array of field-value pairs with the data that determines which records will be affected by the operation.
     * @param array  $data  Associative array of field-value pairs with the data to set of the affected records.
     */
    function update($table, $data, $keys)
    {
        $this->checkConnected();
        return $this->Connection->update($table, $data, $keys);
    }

    /**
     * Inserts a new record in a table.
     *
     * @param string $table              The name of the table to insert data into.
     * @param array  $data               Associative array of field-value pairs with the data to insert.
     * @param string $autoincrementField Optional parameter to define the table auto increment field.
     */
    function insert($table, $data, $autoincrementField = '')
    {
        $this->checkConnected();
        return $this->Connection->insert($table, $data, $autoincrementField);
    }

    /**
     * Returns the SQL code to define the sorting method for the query results.
     *
     * @param string $sql   SQL code to define the sorting method. Required for DBMS that do not comply with the SQL
     *                      standard on how to define the sorting method of the results returned by a query. Leave empty
     *                      otherwise, and use the second and third parameters instead.
     * @param int    $field Name of the field to be used to determine the order of the results. You can provide several
     *                      field names, separated by commas.
     * @param int    $order SQL keyword to determine the order to be applied regarding the value of the field or fields,
     *                      either ascending (ASC) or descending (DESC).
     *
     * @return string SQL code. For example: "ORDER BY fieldName DESC" (20 first results)
     */
    function orderSQL($sql, $field, $order)
    {
        $this->checkConnected();
        return $this->Connection->orderSQL($sql, $field, $order);
    }

    /**
     * Returns the SQL code to apply the given limit to query results.
     *
     * @param string $sql   SQL code to define the limit. Required for DBMS that do not comply with the SQL standard on
     *                      how to define the limit of the results returned by a query. Leave empty otherwise, and use
     *                      the second and third parameters instead.
     * @param int    $count Maximum limit of results.
     * @param int    $start Number of records to be skipped from the start (none by default).
     *
     * @return string SQL code. For example: "LIMIT 0, 20" (20 first results)
     */
    function limitSQL($sql, $count, $start = 0)
    {
        $this->checkConnected();
        return $this->Connection->limitSQL($sql, $count, $start);
    }

    /**
     * Returns an SQL query string to count the results for the given SQL query.
     *
     * @param string $sql SQL query whose results are to be counted.
     *
     * @return string SQL query to get the number of results returned by the given SQL query.
     *
     * @internal
     */
    function countSQL($sql)
    {
        $this->checkConnected();
        return $this->Connection->countSQL($sql);
    }

    /**
     * Provides the number of results returned by the given SQL query.
     *
     * @param string $sql    SQL query whose results are to be counted.
     * @param array  $params Paramaters to be passed to the query, replacing any place holder. See execute().
     *
     * @return int Number of results returned by the query.
     */
    function count($sql, $params = array())
    {
        $this->checkConnected();
        return $this->Connection->count($sql, $params);
    }

    /**
     * Returns the SQL query to run the target procedure with the given parameters.
     *
     * @param string $name   Name of the procedure.
     * @param array  $params Parameters to be passed to the procedure.
     *
     * @return string SQL query to run the procedure.
     */
    function procedureSQL($name, $params)
    {
        $this->checkConnected();
        return $this->Connection->procedureSQL($name, $params);
    }


    /* ------------------------
     *     Published Events
     * ------------------------ */

    // Documented in the parent.
    function getOnAfterConnect() { return $this->readonafterconnect(); }
    function setOnAfterConnect($value) { $this->writeonafterconnect($value); }

    // Documented in the parent.
    function getOnBeforeConnect() { return $this->readonbeforeconnect(); }
    function setOnBeforeConnect($value) { $this->writeonbeforeconnect($value); }

    // Documented in the parent.
    function getOnAfterDisconnect() { return $this->readonafterdisconnect(); }
    function setOnAfterDisconnect($value) { $this->writeonafterdisconnect($value); }

    // Documented in the parent.
    function getOnBeforeDisconnect() { return $this->readonbeforedisconnect(); }
    function setOnBeforeDisconnect($value) { $this->writeonbeforedisconnect($value); }
}


/**
 * Interface that defines properties and methods for a dataset based on database connectivity.
 */
class DBDataSet extends DataSet
{
    public $_stmt             = null;
    public $_isstmtscrollable = false;
    public $_fields           = null;
    public $_keyfields        = null;
    protected $_database      = null;
    protected $_params        = array();

    /**
     * Component to be used to connect to the database server.
     *
     * @return CustomConnection
     */
    function readDatabase() { return $this->_database; }
    function writeDatabase($value) { $this->_database=$this->fixupProperty($value); }
    function defaultDatabase() { return null; }

    /**
     * Parameters for the underlying SQL query.
     *
     * @see CustomConnection::execute()
     *
     * @return array
     */
    function readParams() { return $this->_params; }
    function writeParams($value) { $this->_params=$value; }
    function defaultParams() { return ""; }

    // Documented in the parent.
    function loaded()
    {
        $this->writeDatabase($this->_database);
        parent::loaded();
    }

    // Documented in the parent.
    function readFields()
    {
        return $this->_fields;
    }

    // Documented in the parent.
    function readFieldCount()
    {
        return count($this->_fields);
    }

    // Documented in the parent.
    function readRecordCount()
    {
        if(!$this->_recordcount)
        {
            $query = $this->buildQuery();
            $this->_recordcount = (int) $this->_database->count($query, $this->_params);
        }
        return $this->_recordcount;
    }

    /**
     * Ensure the Database property is assigned a proper value, an instance of a Database component, and raise an
     * exception otherwise.
     *
     * @throws DatabaseError The DAtabase property is empty or is not an object.
     */
    function CheckDatabase()
    {
        if (!is_object($this->_database)) DatabaseError(_("No Database assigned or is not an object"));
    }

    // Documented in the parent.
    function isUnidirectional()
    {
        return !$this->_isstmtscrollable;
    }

    /**
     * Updated the value of the Fields property.
     *
     * @internal
     */
    protected function updateFields()
    {
        if (!is_array($this->_fields))
        {
            if ($this->_tablename!='')
            {
                $this->_fields=$this->Database->MetaFields($this->_tablename);
            }
        }
        $this->_fieldbuffer = array();
    }

    // Documented in the parent.
    function internalClose()
    {
        if($this->_stmt)
        {
            try{
            $this->_stmt->closeCursor();
            }catch(Exception $e){}
            $this->_stmt = null;
        }

        $this->_keyfields   = null;
        $this->_fields      = null;
        $this->_fieldbuffer = null;
        $this->_recordcount = 0;
        $this->_recno       = 0;
    }

    // Documented in the parent.
    function internalOpen()
    {
        if (($this->ControlState & csDesigning)!=csDesigning)
        {
            $this->CheckDatabase();

            $query = $this->buildQuery();

            if (trim($query) == '')
            {
                DatabaseError(_("Missing query to execute"));
            }

            // apply the limit
            $query = $this->_database->limitSQL($query, (int) $this->parseLimitCount(), (int) $this->parseLimitStart());

            $this->_stmt = $this->Database->Execute($query,$this->_params);
            $this->_isstmtscrollable = $this->Database->isEnabledScroll();

            // Some drivers crash when we try to fetch SQL without resulset
            try{
                $this->initDataset();
            }
            catch(Exception $e){}
        }
    }

    /**
     * Initializes the dataset.
     *
     * @see internalOpen()
     */
    function initDataset()
    {
        // go to first record
        if($this->NextRecord())
        {
            $this->_recno++;
            $this->_eof = false;
        }

        $this->updateFields();
    }

    // Documented in the parent.
    function internalFirst()
    {
        if($row = $this->_stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_FIRST))
            $this->_fields = $row;

        return $row != false;
    }

    // Documented in the parent.
    function internalLast()
    {
        if($row = $this->_stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_LAST))
            $this->_fields = $row;

        return $row != false;
    }

    // Documented in the parent.
    function NextRecord()
    {
        if($row = $this->_stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            $this->_fields = $row;

        return $row != false;
    }

    // Documented in the parent.
    function PriorRecord()
    {
        if($row = $this->_stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_PRIOR))
            $this->_fields = $row;

        return $row != false;
    }

    /**
     * Returns the value of the given field for the current record on the dataset.
     *
     * @param string $fieldName Name of the field.
     *
     * @return mixed Value of the field for the current record.
     *
     * @throws EPropertyNotFound The requested field name does nto exist.
     */
    function fieldget($fieldName)
    {
        if ($this->Active)
        {
            // check if accessed by position
            if(is_int($fieldName) && is_array($this->_fields))
            {
                $keys = array_keys($this->_fields);
                if(isset($keys[$fieldName]))
                {
                    $fieldName = $keys[$fieldName];
                }
            }

            if (is_array($this->_fieldbuffer) && array_key_exists($fieldName,$this->_fieldbuffer))
            {
                return $this->_fieldbuffer[$fieldName];
            }
            else if (is_array($this->_fields) && array_key_exists($fieldName,$this->_fields))
            {
                return $this->_fields[$fieldName];
            }
        }


        throw new EPropertyNotFound($this->ClassName().'->'.$fieldName);

    }

    // Documented in the parent.
    function readFieldBuffer()
    {
        return array_merge($this->_fields, $this->_fieldbuffer);
    }

    /**
     * Returns the value of the given field for the current record on the dataset.
     *
     * @param string $fieldName Name of the field.
     * @param mixed  $value     New value of the field for the current record.
     *
     * @throws EPropertyNotFound The requested field name does nto exist.
     */
    function fieldset($fieldName, $value)
    {
        if ($this->Active)
        {
            // check if accessed by position
            if (is_int($fieldName) && is_array($this->_fields))
            {
                $keys = array_keys($this->_fields);

                if(isset($keys[$fieldName]))
                {
                    $fieldName = $keys[$fieldName];
                }
            }

            if (is_array($this->_fields) && array_key_exists($fieldName,$this->_fields))
            {
                $this->_fieldbuffer[$fieldName] = $value;

                $this->Modified = true;

                if ($this->State == dsBrowse)
                {
                    $this->State = dsEdit;
                }
                return;
            }
        }

        throw new EPropertyNotFound($this->ClassName().'->'.$fieldName);
    }

    /**
     * Overriden to make it possible to get field values for the current record using the field name as a property on
     * the component.
     *
     * Thanks to this method, for example, you could get the value of the field named PhoneNumber like this:
     *
     * <code>
     * $phoneNumber = $dbdataset->PhoneNumber;
     * </code>
     *
     * Note: Component properties have priority over record fields. If you need to get the value of a field that has the
     * same name as a property of this component, you need to call fieldget() instead.
     *
     * @see fieldget()
     *
     * @param string $name Name of the component property or current record's field to retrieve.
     *
     * @return mixed Value of the component property or current record's field.
     */
    function __get($name)
    {
        try
        {
            //Try to get the property from the object
            return(parent::__get($name));
        }
        catch (EPropertyNotFound $e)
        {
            //If there is no such property, then try to search for a field
            return($this->fieldget($name));
        }
    }

    /**
     * Overriden to make it possible to set field values for the current record using the field name as a property on
     * the component.
     *
     * Thanks to this method, for example, you could set the value of the field named PhoneNumber like this:
     *
     * <code>
     * $dbdataset->PhoneNumber = "555-9292";
     * </code>
     *
     * Note: Component properties have priority over record fields. If you need to set the value of a field that has the
     * same name as a property of this component, you need to call fieldset() instead.
     *
     * @see fieldset()
     *
     * @param string $name  Name of the component property or current record's field to modify.
     * @param mixed  $value Value to be set for the component property or current record's field.
     */
    function __set($name, $value)
    {
        try
        {
            //Try to get the property from the object
             parent::__set($name, $value);
        }
        catch (EPropertyNotFound $e)
        {
            $this->fieldset($name, $value);
        }
    }


    /* -------------------
     *   HELPER FUNCTIONS
     * -------------------*/

    /**
     * Returns the value of the LimitStart property as an integer. If the property is not defined, the method will
     * return -1.
     *
     * @return int
     */
    function parseLimitStart()
    {
        $limitstart = trim($this->_limitstart);

        return ($limitstart != "")
                ? (int) $limitstart
                : -1;
    }

    /**
     * Returns the value of the LimitCount property as an integer. If the property is not defined, the method will
     * return -1.
     *
     * @return int
     */
    function parseLimitCount()
    {
        $limitcount = trim($this->_limitcount);

        return ($limitcount != "")
                ? (int) $limitcount
                : -1;
    }
}


/**
 * Dataset to handle the records in a database table.
 */
class CustomTable extends DBDataSet
{
    protected $_tablename="";
    /**
     * Name of the database table.
     *
     * @return string
     */
    function readTableName() { return $this->_tablename; }
    function writeTableName($value) { $this->_tablename=$value; }
    function defaultTableName() { return ""; }

    protected $_hasautoinc="1";
    /**
     * Whether the first field of the table must me incremented automatically ("1"). Otherwise ("0"), new records will
     * be inserted into the database table as they were defined on the dataset.
     *
     * @return string
     */
    function getHasAutoInc() { return $this->_hasautoinc; }
    function setHasAutoInc($value) { $this->_hasautoinc=$value; }
    function defaultHasAutoInc() { return "1"; }

    protected $_orderfield="";
    /**
     * Name of the field to be used to determine the order of the results. You can provide several field names,
     * separated by commas.
     *
     * @return string
     */
    function readOrderField() { return $this->_orderfield; }
    function writeOrderField($value) { $this->_orderfield=$value; }
    function defaultOrderField() { return ""; }

    protected $_order="asc";
    /**
     * SQL keyword to determine the order to be applied regarding the value of the field or fields, either ascending
     * (ASC) or descending (DESC).
     *
     * @return string
     */
    function readOrder() { return $this->_order; }
    function writeOrder($value) { $this->_order=$value; }
    function defaultOrder() { return "asc"; }

    // Documented in the parent.
    function internalDelete()
    {
        $indexes = array();
        $values  = array();
        $keyfields = $this->readKeyfields();

        foreach ($keyfields as $fname => $type)
        {
            $indexes[] = $fname."=?";
            $values[]  = $this->_fields[$fname];
        }

        $where = implode(' and ', $indexes);

        if ($where !='' )
        {
            $query="delete from $this->TableName where $where";
            $this->Database->Execute($query, $values);
        }

    }


    /**
     * Returns the data of the current record's primary key fields.
     *
     * @return array Associative array of field-value pairs for the fields that are part of the table primary key.
     */
    protected function currentRecordKeyValues()
    {
        $keyFields = $this->readKeyfields();
        $keyValues = array();

        foreach($keyFields as $key => $type)
        {
            $keyValues[$key] = $this->_fields[$key];
        }

        return $keyValues;
    }

    /**
     * Commits to the database the data of the current record that has been changed in the dataset.
     */
    protected function updateRecord()
    {
        if(!count($this->_fieldbuffer))
            return;

        // extract the values of the keys, the "update" statement should filter with them
        $keyValues = $this->currentRecordKeyValues();

        try
        {
            $this->Database->update(
                $this->_tablename,
                $this->_fieldbuffer,
                $keyValues
            );

            //TODO: provide a way to resync in parent class (Dataset)
            $this->_fields = array_merge($this->_fields, $this->_fieldbuffer);
        }
        catch (Exception $e)
        {
            $this->_fields = array_merge($this->_fields, $this->_fieldbuffer);
            throw $e;
        }


        //TODO: Handle errors
    }

    /**
     * Inserts the current record into the table, on the database.
     */
    function insertRecord()
    {
        // extract the auto increment field. Only one field can be the autoincrement
        $autoincrementField = '';
        if ($this->HasAutoInc)
        {
            $keyfields = $this->readKeyFields();
            if (is_array($keyfields))
            {
                foreach($keyfields as $fname => $type)
                {
                    $autoincrementField = $fname;// changed from $fname to $key
                    break;
                }
            }
        }

        try
        {
            $this->Database->insert(
                $this->_tablename,
                $this->_fieldbuffer,
                $autoincrementField
            );
        }
        catch (Exception $e)
        {
            //TODO: Handle errors
            throw $e;
        }

        //TODO: provide a way to resync in parent class (Dataset)
        $this->_fields = array_merge($this->_fields, $this->_fieldbuffer);
    }

    // Documented in the parent.
    function internalPost()
    {
        if ($this->State == dsEdit)
        {
            $this->updateRecord();
        }
        else
        {
            $this->insertRecord();
        }
    }

    /**
     * Returns the SQL query to be executed on the database.
     *
     * @return string
     */
    function buildQuery()
    {
        if (($this->ControlState & csDesigning)!=csDesigning)
        {
            if (trim($this->_tablename)=='')
            {
                DatabaseError(_("Missing TableName property"));
            }

            $query="select * from $this->_tablename";


            $conditions = array();
            $masterconditions = array();

            // If the Dataset has a MasterSource (Dataset), generate a filter based on the master fields
            if ($this->MasterSource!="")
            {
                $this->writeMasterSource($this->_mastersource);
                if (is_object($this->_mastersource))
                {
                    if (is_array($this->_masterfields))
                    {
                        $this->_mastersource->DataSet->open();

                        foreach($this->_masterfields as $thisfield => $masterfield)
                        {
                            $masterconditions[] ="$thisfield=".$this->Database->QuoteStr($this->_mastersource->DataSet->Fields[$masterfield]);
                        }

                        if(count($masterconditions))
                        {
                            $conditions[] = '(' . implode(' and ', $masterconditions) . ')';
                        }

                    }
                }
            }

            if(trim($this->_filter) != "")
                $conditions[] = $this->_filter;

            $where = (count($conditions))? ' where ' . implode(' and ', $conditions) : '';

            // where, order and limit
            $query = "$query $where";
            $query = $this->_database->orderSQL($query, $this->_orderfield, $this->_order);

            return $query;
        }
        else return('');
    }

    /**
     * Returns the names of the fields that are part of the primary key of the table.
     *
     * @return array
     */
    function readKeyFields()
    {
        if (!is_array($this->_keyfields) || empty($this->_keyfields))
        {
            if ($this->_tablename!='')
            {
                $this->_keyfields = $this->Database->primaryKeys($this->_tablename);
            }
        }

        return $this->_keyfields;
    }

    /**
     * Prints HTML hidden fields with the key fields of this dataset and theirs values on the current record.
     *
     * @param string $basename Prefix to be used to generate hidden key field names.
     * @param array  $values   Array with the values to generate.
     */
    function dumpHiddenKeyFields($basename, $values=array())
    {
        $values     = $this->_fields;
        $keyfields  = $this->readKeyfields();

        if (is_array($keyfields))
        {
            foreach($keyfields as $fname => $type)
            {
                $avalue=str_replace('"','&quot;',$values[$fname]);
                echo "<input type=\"hidden\" name=\"".$basename."[$fname]\" value=\"$avalue\" />";
            }
        }
    }
}

/**
 * Dataset to handle the records in a database table.
 *
 * @link wiki://Table
 */
class Table extends CustomTable
{
    // Documented in the parent.
    function getMasterSource() { return $this->readmastersource(); }
    function setMasterSource($value) { $this->writemastersource($value); }

    // Documented in the parent.
    function getMasterFields() { return $this->readmasterfields(); }
    function setMasterFields($value) { $this->writemasterfields($value); }

    // Documented in the parent.
    function getTableName() { return $this->readtablename(); }
    function setTableName($value) { $this->writetablename($value); }

    // Documented in the parent.
    function getActive() { return $this->readactive(); }
    function setActive($value) { $this->writeactive($value); }

    // Documented in the parent.
    function getDatabase() { return $this->readdatabase(); }
    function setDatabase($value) { $this->writedatabase($value); }

    // Documented in the parent.
    function getFilter() { return $this->readfilter(); }
    function setFilter($value) { $this->writefilter($value); }

    // Documented in the parent.
    function getOrderField() { return $this->readorderfield(); }
    function setOrderField($value) { $this->writeorderfield($value); }

    // Documented in the parent.
    function getOrder() { return $this->readorder(); }
    function setOrder($value) { $this->writeorder($value); }

    /*  -----------------------------------
     *      PUBLISHED EVENTS
     *  ----------------------------------- */

    // Documented in the parent.
    function getOnBeforeOpen() { return $this->readonbeforeopen(); }
    function setOnBeforeOpen($value) { $this->writeonbeforeopen($value); }

    // Documented in the parent.
    function getOnAfterOpen() { return $this->readonafteropen(); }
    function setOnAfterOpen($value) { $this->writeonafteropen($value); }

    // Documented in the parent.
    function getOnBeforeClose() { return $this->readonbeforeclose(); }
    function setOnBeforeClose($value) { $this->writeonbeforeclose($value); }

    // Documented in the parent.
    function getOnAfterClose() { return $this->readonafterclose(); }
    function setOnAfterClose($value) { $this->writeonafterclose($value); }

    // Documented in the parent.
    function getOnBeforeInsert() { return $this->readonbeforeinsert(); }
    function setOnBeforeInsert($value) { $this->writeonbeforeinsert($value); }

    // Documented in the parent.
    function getOnAfterInsert() { return $this->readonafterinsert(); }
    function setOnAfterInsert($value) { $this->writeonafterinsert($value); }

    // Documented in the parent.
    function getOnBeforeEdit() { return $this->readonbeforeedit(); }
    function setOnBeforeEdit($value) { $this->writeonbeforeedit($value); }

    // Documented in the parent.
    function getOnAfterEdit() { return $this->readonafteredit(); }
    function setOnAfterEdit($value) { $this->writeonafteredit($value); }

    // Documented in the parent.
    function getOnBeforePost() { return $this->readonbeforepost(); }
    function setOnBeforePost($value) { $this->writeonbeforepost($value); }

    // Documented in the parent.
    function getOnAfterPost() { return $this->readonafterpost(); }
    function setOnAfterPost($value) { $this->writeonafterpost($value); }

    // Documented in the parent.
    function getOnBeforeCancel() { return $this->readonbeforecancel(); }
    function setOnBeforeCancel($value) { $this->writeonbeforecancel($value); }

    // Documented in the parent.
    function getOnAfterCancel() { return $this->readonaftercancel(); }
    function setOnAfterCancel($value) { $this->writeonaftercancel($value); }

    // Documented in the parent.
    function getOnBeforeDelete() { return $this->readonbeforedelete(); }
    function setOnBeforeDelete($value) { $this->writeonbeforedelete($value); }

    // Documented in the parent.
    function getOnAfterDelete() { return $this->readonafterdelete(); }
    function setOnAfterDelete($value) { $this->writeonafterdelete($value); }

    // Documented in the parent.
    function getOnDeleteError() { return $this->readondeleteerror(); }
    function setOnDeleteError($value) { $this->writeondeleteerror($value); }

    // Documented in the parent.
    function getOnPostError() { return $this->readonposterror(); }
    function setOnPostError($value) { $this->writeonposterror($value); }

    // Documented in the parent.
    function getOnNewRecord() { return $this->readonnewrecord(); }
    function setOnNewRecord($value) { $this->writeonnewrecord($value); }

}


/**
 * Dataset to handle the records returned by a custom query on a database.
 */
class CustomQuery extends CustomTable
{
    protected $_sql=array();
    /**
     * SQL statement defining the query the component will perform agains a database when its open() method is called.
     *
     * This property may contain only one complete SQL statement at a time. In general, multiple "batch" statements are
     * not allowed unless a particular server supports them.
     *
     * @return array
     */
    function readSQL() { return $this->_sql;     }
    function writeSQL($value)
    {
        //If it's not an array
        if (!is_array($value))
        {
            //Check for a serialized array
            $clean=@unserialize($value);
        }
        else
        {
            $clean=$value;
        }

        if ($clean === false)
        {
            $this->_sql=$value;
        }
        else
        {
            $this->_sql=$clean;
        }
    }
    function defaultSQL() { return array();     }

    /**
     * Sends the query to the server prior to its execution, for optimization purposes.
     *
     * Call this method to have a remote database server allocate resources for the query and to perform additional
     * optimizations.
     *
     * If the query will only be executed once, the application does not need to explicitly call this method. Executing
     * an unprepared query generates these calls automatically. However, if the same query is to be executed repeatedly,
     * it is more efficient to prevent these automatic calls by calling this method explicitly.
     *
     * Note: When you change the text of a query at runtime, the query is automatically closed and unprepared.
     */
    function Prepare()
    {
        $this->Database->Prepare($this->buildQuery());
    }

    // Documented in the parent.
    function buildQuery()
    {
        if (($this->ControlState & csDesigning)!=csDesigning)
        {
            if (is_array($this->_sql))
            {
                if (!empty($this->_sql))
                {
                    $use_query_string=true;
                    $query = implode(' ',$this->_sql);
                }
            }
            else
            {
                if ($this->_sql!="")
                {
                    $query = $this->_sql;
                }
            }

            //filter, order and limit
            if ( trim($this->_filter) != "" )
                $query .= " where ".$this->_filter;
            $query = $this->_database->orderSQL($query, $this->_orderfield, $this->_order);

            return $query;
        }
        else
            return '';
    }


}


/**
 * Dataset to handle the records returned by a custom query on a database.
 *
 * @link wiki://Query
 */
class Query extends CustomQuery
{
    // Documented in the parent.
    function getSQL() { return $this->readsql(); }
    function setSQL($value) { $this->writesql($value); }

    // Documented in the parent.
    function getParams() { return $this->readparams(); }
    function setParams($value) { $this->writeparams($value); }

    // Documented in the parent.
    function getTableName() { return $this->readtablename(); }
    function setTableName($value) { $this->writetablename($value); }

    // Documented in the parent.
    function getActive() { return $this->readactive(); }
    function setActive($value) { $this->writeactive($value); }

    // Documented in the parent.
    function getDatabase() { return $this->readdatabase(); }
    function setDatabase($value) { $this->writedatabase($value); }

    // Documented in the parent.
    function getFilter() { return $this->readfilter(); }
    function setFilter($value) { $this->writefilter($value); }

    // Documented in the parent.
    function getOrderField() { return $this->readorderfield(); }
    function setOrderField($value) { $this->writeorderfield($value); }

    // Documented in the parent.
    function getOrder() { return $this->readorder(); }
    function setOrder($value) { $this->writeorder($value); }

    // Documented in the parent.
    function getOnBeforeOpen() { return $this->readonbeforeopen(); }
    function setOnBeforeOpen($value) { $this->writeonbeforeopen($value); }

    // Documented in the parent.
    function getOnAfterOpen() { return $this->readonafteropen(); }
    function setOnAfterOpen($value) { $this->writeonafteropen($value); }

    // Documented in the parent.
    function getOnBeforeClose() { return $this->readonbeforeclose(); }
    function setOnBeforeClose($value) { $this->writeonbeforeclose($value); }

    // Documented in the parent.
    function getOnAfterClose() { return $this->readonafterclose(); }
    function setOnAfterClose($value) { $this->writeonafterclose($value); }

    // Documented in the parent.
    function getOnBeforeInsert() { return $this->readonbeforeinsert(); }
    function setOnBeforeInsert($value) { $this->writeonbeforeinsert($value); }

    // Documented in the parent.
    function getOnAfterInsert() { return $this->readonafterinsert(); }
    function setOnAfterInsert($value) { $this->writeonafterinsert($value); }

    // Documented in the parent.
    function getOnBeforeEdit() { return $this->readonbeforeedit(); }
    function setOnBeforeEdit($value) { $this->writeonbeforeedit($value); }

    // Documented in the parent.
    function getOnAfterEdit() { return $this->readonafteredit(); }
    function setOnAfterEdit($value) { $this->writeonafteredit($value); }

    // Documented in the parent.
    function getOnBeforePost() { return $this->readonbeforepost(); }
    function setOnBeforePost($value) { $this->writeonbeforepost($value); }

    // Documented in the parent.
    function getOnAfterPost() { return $this->readonafterpost(); }
    function setOnAfterPost($value) { $this->writeonafterpost($value); }

    // Documented in the parent.
    function getOnBeforeCancel() { return $this->readonbeforecancel(); }
    function setOnBeforeCancel($value) { $this->writeonbeforecancel($value); }

    // Documented in the parent.
    function getOnAfterCancel() { return $this->readonaftercancel(); }
    function setOnAfterCancel($value) { $this->writeonaftercancel($value); }

    // Documented in the parent.
    function getOnBeforeDelete() { return $this->readonbeforedelete(); }
    function setOnBeforeDelete($value) { $this->writeonbeforedelete($value); }

    // Documented in the parent.
    function getOnAfterDelete() { return $this->readonafterdelete(); }
    function setOnAfterDelete($value) { $this->writeonafterdelete($value); }

    // Documented in the parent.
    function getOnDeleteError() { return $this->readondeleteerror(); }
    function setOnDeleteError($value) { $this->writeondeleteerror($value); }

    // Documented in the parent.
    function getOnPostError() { return $this->readonposterror(); }
    function setOnPostError($value) { $this->writeonposterror($value); }

}


/**
 * Component to execute a procedure stored in a database.
 *
 * A stored procedure is a grouped set of statements, stored as part of a database server's metadata (just like tables,
 * indexes, and domains), that performs a frequently repeated, database-related task on the server and passes the
 * results to the client.
 *
 * You can run a stored procedure by providing its name and calling Execute() on it.
 *
 * Many stored procedures require a series of input arguments, or parameters, that are used during the processing.
 * This component provides a Params property so you can provide those parameters before executing the procedure.
 *
 * Depending on server implementation, a stored procedure may (1) return either a single set of values, or a resultset
 * similar to the resultset returned by a query, (2) require you to call a query to fetch the results produced by the
 * procedure, or (3) return nothing, but change the value of the passed parameters.
 *
 * In the first case, you can simple execute the procedure for the component to be filled with the results.
 *
 * In the second case, you must use the FetchQuery property to define the SQL query that must be executed right after
 * the procedure call, in other to retireve the records to fill the component with.
 *
 * In the third case, you can call retrieveParam() after executing the procedure to get the values your parameters ended
 * up with, after being modifed (or not) by the procedure.
 *
 * Note: Not all database servers support stored procedures. See a specific server's documentation to determine if it
 * supports stored procedures.
 */
class StoredProc extends CustomQuery
{
    /**
     * Array to store the references to the params binded in the last execution
     */
    protected $_lastparams;

    protected $_storedprocname="";
    /**
     * Name of the procedure.
     *
     * If it does not match the name of an existing procedure on the server, an exception will be raised at runtime.
     *
     * @return string
     */
    function getStoredProcName() { return $this->_storedprocname; }
    function setStoredProcName($value) { $this->_storedprocname=$value; }
    function defaultStoredProcName() { return ""; }

    protected $_fetchquery="";
    /**
     * Use this property to specify the query to fetch the results from the stored procedure call.
     *
     * Some servers, such as MySQL, use CALL to execute stored procedures on the server. If your procedures produce
     * results in variables, you can use this property to specify the select query to fetch those results.
     *
     * @return string
     */
    function getFetchQuery() { return $this->_fetchquery; }
    function setFetchQuery($value) { $this->_fetchquery=$value; }
    function defaultFetchQuery() { return ""; }

    // Documented in the parent.
    function getActive() { return $this->readactive(); }
    function setActive($value) { $this->writeactive($value); }

    // Documented in the parent.
    function getDatabase() { return $this->readdatabase(); }
    function setDatabase($value) { $this->writedatabase($value); }

    // Documented in the parent.
    function getFilter() { return $this->readfilter(); }
    function setFilter($value) { $this->writefilter($value); }

    // Documented in the parent.
    function getOrderField() { return $this->readorderfield(); }
    function setOrderField($value) { $this->writeorderfield($value); }

    // Documented in the parent.
    function getOrder() { return $this->readorder(); }
    function setOrder($value) { $this->writeorder($value); }

    // Documented in the parent.
    function getParams() { return $this->readparams(); }
    function setParams($value) { $this->writeparams($value); }


    // Documented in the parent.
    function Prepare()
    {
        //TODO:Handle Binding variables
        //$this->Database->Prepare($this->buildQuery());
    }

    /**
     * Executes the procedure, and checks whether it returns a resultset or not, to use one method or another.
     *
     * Before calling this method: (1) Provide any input parameters in the Params property and (2) call Prepare() to
     * bind the parameters to the call for the procedure.
     */
    function Execute()
    {
        if (in_array($this->Database->Drivername, $this->_noFetchDBs))
        {
            $this->Database->Execute($this->buildQuery());
        }
        else
        {
            $this->Close();
            $this->Open();
        }
    }

    // Documented in the parent.
    function internalOpen()
    {
        if (($this->ControlState & csDesigning)!=csDesigning)
        {
            $this->CheckDatabase();

            $query = $this->buildQuery();

            if (trim($query)=='')
            {
                DatabaseError(_("Missing query to execute"));
            }

            // apply the limit
            $query = $this->_database->limitSQL($query, (int) $this->parseLimitCount(), (int) $this->parseLimitStart());

            // prepare the procedure
            $stmt = $this->Database->prepare($query);

            // bind parameters (IN and OUT)
            $this->bindParams($stmt);
            
            // execute procedure
            $stmt->execute();
            
            // Execute the second query if exists to get the database results
            if ($this->_fetchquery !='')
            {
                // close the previous recordset
                try{$stmt->closeCursor();}
                catch (Exception $e){}

                $this->_stmt = $this->Database->Execute($this->_fetchquery);
                $this->_isstmtscrollable = $this->Database->isEnabledScroll();
                $this->initDataset();
            }
            else
            {
                // continue with the current recordset
                $this->_stmt = $stmt;

                try{
                    $this->initDataset();
                }catch (Exception $e){}
            }
            
            // free the recordset
            $stmt = null;
            unset($stmt);
        }
    }


    // Documented in the parent.
    function initDataset()
    {
        // go to first record
        if($this->NextRecord())
        {
            $this->_recno++;
            $this->_eof = false;
        }

        $this->updateFields();
    }

    /**
     * Binds the parameters defined in the Params property to the given stored procedure call statement.
     *
     * @param PDOStatement $stmt Statement to run the stored procedure, to which the parameters should be binded.
     */

    protected function bindParams(&$stmt)
    {
        $params = $this->_params;

        // create a stdClass to store the params (usefurl for OUT or INOUT parameters)
        $this->_lastparams = array();
        


        // bind parameters
        if(count($params))
        {
            $paramNum = 1;

            foreach($params as $key => $param)
            {
                /**
                 * Binding parameters as array we can bind data type, param type and length
                 */
                if(is_array($param))
                {
                    // storing the param in the class, for example $this->_lastparams->balance
                    $this->_lastparams[$key] = $param[0];

                    switch(count($param))
                    {
                        // call bindParam with num, data type
                        case 1: $stmt->bindParam($paramNum, $this->_lastparams[$key]);
                                break;
                                
                        // call bindParam with num, data type, pdo type param (IN, OUT, INOUT)
                        case 2: $stmt->bindParam($paramNum, $this->_lastparams[$key], $param[1]);
                                break;
                        
                        // call bindParam with num, data type, pdo type param (IN, OUT, INOUT), length
                        case 3:
                        default:$stmt->bindParam($paramNum, $this->_lastparams[$key], $param[1], $param[2]);
                                break;
                    }

                    // increment for the next param
                    $paramNum++;
                }
                else
                {
                    $this->_lastparams[$key] = $param;

                    // bind parameter as string, skip variables
                    if(substr($param, 0, 1) !== '@')
                    {
                        $stmt->bindParam($paramNum, $this->_lastparams[$key]);
                        $paramNum++;
                    }

                }

            }
        }
    }

    /**
     * Returns the value of the specified parameter, or NULL is the target parameter is not defined.
     *
     * @param string $name Name of the target parameter.
     *
     * @return mixed The value of the parameter, NULL if the parameter is undefined.
     
    function retrieveParam($position)
    {
        return (isset($this->_lastparams[$position]))? $this->_lastparams[$position] : NULL;
    }
    */
    
    // Documented in the parent.
    function buildQuery()
    {
        if (($this->ControlState & csDesigning)!=csDesigning)
        {
            $query = $this->Database->procedureSQL($this->_storedprocname, $this->_params);
            $query = $this->Database->orderSQL($query, $this->_orderfield, $this->_order);

            return $query;
        }
        else return '';
    }

    // Documented in the parent.
    function getOnBeforeOpen() { return $this->readonbeforeopen(); }
    function setOnBeforeOpen($value) { $this->writeonbeforeopen($value); }

    // Documented in the parent.
    function getOnAfterOpen() { return $this->readonafteropen(); }
    function setOnAfterOpen($value) { $this->writeonafteropen($value); }

    // Documented in the parent.
    function getOnBeforeClose() { return $this->readonbeforeclose(); }
    function setOnBeforeClose($value) { $this->writeonbeforeclose($value); }

    // Documented in the parent.
    function getOnAfterClose() { return $this->readonafterclose(); }
    function setOnAfterClose($value) { $this->writeonafterclose($value); }

    // Documented in the parent.
    function getOnBeforeInsert() { return $this->readonbeforeinsert(); }
    function setOnBeforeInsert($value) { $this->writeonbeforeinsert($value); }

    // Documented in the parent.
    function getOnAfterInsert() { return $this->readonafterinsert(); }
    function setOnAfterInsert($value) { $this->writeonafterinsert($value); }

    // Documented in the parent.
    function getOnBeforeEdit() { return $this->readonbeforeedit(); }
    function setOnBeforeEdit($value) { $this->writeonbeforeedit($value); }

    // Documented in the parent.
    function getOnAfterEdit() { return $this->readonafteredit(); }
    function setOnAfterEdit($value) { $this->writeonafteredit($value); }

    // Documented in the parent.
    function getOnBeforePost() { return $this->readonbeforepost(); }
    function setOnBeforePost($value) { $this->writeonbeforepost($value); }

    // Documented in the parent.
    function getOnAfterPost() { return $this->readonafterpost(); }
    function setOnAfterPost($value) { $this->writeonafterpost($value); }

    // Documented in the parent.
    function getOnBeforeCancel() { return $this->readonbeforecancel(); }
    function setOnBeforeCancel($value) { $this->writeonbeforecancel($value); }

    // Documented in the parent.
    function getOnAfterCancel() { return $this->readonaftercancel(); }
    function setOnAfterCancel($value) { $this->writeonaftercancel($value); }

    // Documented in the parent.
    function getOnBeforeDelete() { return $this->readonbeforedelete(); }
    function setOnBeforeDelete($value) { $this->writeonbeforedelete($value); }

    // Documented in the parent.
    function getOnAfterDelete() { return $this->readonafterdelete(); }
    function setOnAfterDelete($value) { $this->writeonafterdelete($value); }

    // Documented in the parent.
    function getOnDeleteError() { return $this->readondeleteerror(); }
    function setOnDeleteError($value) { $this->writeondeleteerror($value); }

    // Documented in the parent.
    function getOnPostError() { return $this->readonposterror(); }
    function setOnPostError($value) { $this->writeonposterror($value); }
}


?>
