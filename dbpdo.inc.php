<?php

/**
*  This file is part of the RPCL project
*
*  Copyright (c) 2012 Embarcadero Technologies, Inc.
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

/**
 * DBMS-independent wrapper to handle a PDO instance.
 *
 * This class is inherited by DBMS-specific implementations. The factory() static method will provide an instance of the
 * right child class for the given Database object.
 *
 * @see Database
 */
class DBPDO
{

    /**
     * Actual PDO instance wrapped by this class.
     *
     *  @var PDO
     */
    public $pdo;

    protected $database;
    protected $params;
    protected $driveroptions;
    protected $data_cache;

    /**
     * Returns an instance of a DBPDO child class for the DBMS of the target database, or and instance of DBPDO if the
     * DBMS is not supported.
     *
     * @param Database $database
     *
     * @return DBPDO
     */
    public static function factory($database)
    {
        switch($database->Drivername)
        {
            //case 'cubrid':    return new DBCubrid($database);

            //case 'dblib':     return new DBDblib($database);

            case 'interbase': return new DBInterbase($database);

            case 'firebird':  return new DBFirebird($database);

            case 'ibm':       return new DBIbm($database);

            case 'informix':  return new DBInformix($database);

            case 'mysql':     return new DBMysql($database);

            case 'oci':       return new DBOci($database);

            //case 'odbc':      return new DBOdbc($database);

            case 'pgsql':     return new DBPgsql($database);

            case 'sqlite':    return new DBSqlLite($database);

            case 'sqlsrv':    return new DBSqlSrv($database);

            //case '4d':        return new DB4d($database);

            case '': return new DBMysql($database);

            default:            return new DBPDO($database);
        }
    }

    /**
     * Sets the value of an attribute on the PDO instance.
     *
     * @link http://www.php.net/manual/en/pdo.setattribute.php
     */
    function setAttribute($key, $value)
    {
         $this->pdo->setAttribute($key, $value);
    }

    /**
     * Gets the value of an attribute on the PDO instance.
     *
     * @link http://www.php.net/manual/en/pdo.getattribute.php
     */
    function getAttribute($key)
    {
         return  $this->pdo->getAttribute($key);
    }

    /**
     * Class constructor.
     *
     * @param Database $database Database object for which the connection will be established. Database->DriverName
     * must define the DBMS to be used.
     */
    function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Returns the name of the database driver, to be used as prefix for the PDO Data Source Name.
     *
     * @return string
     */
    function DSNDriver()
    {
        return $this->database->Drivername;
    }

    /**
     * Returns the parameters to be used for the PDO Data Source Name.
     *
     * @return string
     */
    function DSNParams()
    {
        $params     = array();
        $dsnparams  = array();

        // base params
        if($this->database->Host != '')
        {
            if($this->database->HostTranslation && strtolower($this->database->Host) == 'localhost')
            {
                $params['host'] = '127.0.0.1';
            }
            else
            {
                $params['host'] = $this->database->Host;
            }
        }

        if($this->database->Databasename != '')
        {
            $params['dbname'] = $this->database->Databasename;
        }

        if($this->database->Port != '')
        {
            $params['port'] = $this->database->Port;
        }

        if($this->database->Charset != '')
        {
            $params['charset'] = $this->database->Charset;
        }


        // custom extra params
        if(is_array($this->database->ConnectionParams))
        {
            $params = array_merge($params, $this->database->ConnectionParams);
        }

         foreach($params as $key => $value)
         {
             $dsnparams[] = "{$key}={$value}";
         }

        return implode(';', $dsnparams);
    }

    /**
     * Returns the PDO Data Source Name to be used to establish the connection.
     *
     * @return string
     */
    function DSN()
    {
        return $this->DSNDriver() . ':' . $this->DSNParams();
    }

    /**
     * Runs the given query on the database.
     *
     * You can optionally provide an array of parameters to be used in the query. Such parameters will replace
     * the place holders that you defined in the query string using the param() method.
     *
     * <code>
     * $statement = $dbpdo->execute("SELECT something FROM somewhere WHERE somethingElse = ".$dbpdo->param("number"), array(6));
     * </code>
     *
     * @param string $query  SQL query.
     * @param array  $params Parameters to be used in the SQL query.
     *
     * @return PDOStatement
     */
    function execute($query, $params = array())
    {
        if(!$this->pdo)
        {
            DatabaseError(_("Error, PDO object is null, connect before execute "));
        }

        // prepare first
        $stmt = $this->pdo->prepare($query);

        if(!$stmt)
        {
            $output = '';
            foreach($this->pdo->errorInfo() as $error) $output .= ', '. $error;
            DatabaseError(_("Error preparing PDOStatement " . $output));
        }

        // bind parameters
        for($i = 0; $i < count($params); $i++)
        {
            $param = $params[$i];
            $paramNum = $i+1;

            // Binding parameters as array we can bind data type, param type and length
            if(is_array($param))
            {
                $arguments = count($param);

                switch($arguments)
                {
                    case 1: $stmt->bindParam($paramNum, $param[0]);
                        break;
                    case 2: $stmt->bindParam($paramNum, $param[0], $param[1]);
                        break;
                    case 3:
                    default: $stmt->bindParam($paramNum, $param[0], $param[1], $param[2]);
                        break;
                }
            }
            else
            {
                // bind parameter as string
                $stmt->bindValue($paramNum, $param);
            }
        }

        // execute the statement
        $result = $stmt->execute();

        if (!$result)
        {
            $output = '';
            foreach($stmt->errorInfo() as $error) $output .= ', '. $error;
            DatabaseError(_("Error executing PDOStatement " . $output));
        }

        return $stmt;
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
        // if has an autoincrement value remove it from the values
        if ( ! empty($autoincrementField) && array_key_exists($autoincrementField,$data))
            unset($data[$autoincrementField]);

        $dataCount = count($data);

        if ( ! $dataCount)
            DatabaseError(_("data to insert can not be empty"));

        $inputs = implode(',', array_fill(0, $dataCount, '?'));
        $fields = implode(',', array_keys($data));
        $values = array_values($data);

        $sql = "insert into $table ($fields) values ($inputs)";

        $this->execute($sql, $values);
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
        $dataCount = count($data);
        $keysCount = count($keys);

        if ( ! $dataCount)
            DatabaseError(_("data to update can not be empty"));

        if ( ! $keysCount)
            DatabaseError(_("keys can not be empty"));

        // generating the set values from $data
        foreach ($data as $field => $value)
        {
            $setPairs[] = "$field=?";
        }

        // generating the where values from $keys
        foreach ($keys as $field => $value)
        {
            $keyPairs[] = "$field=?";
        }

        // generating the set and where clause
        $set   = 'set '   . implode(', ', $setPairs);
        $where = 'where ' . implode(', ', $keyPairs);

        // generating the values to bind
        $values = array_merge(array_values($data), array_values($keys));

        // the query to update
        $sql = "update $table $set $where";

        $this->execute($sql, $values);
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
        return $this->pdo->prepare($query, $driveroptions);
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
        return "?";
    }

    /**
     * Quotes the target value to be safely used inside a query string as a parameter.
     *
     * @param mixed $value          Value to be quoted for safe inclusion in a query string.
     * @param mixed $parameter_type Type of the target value. If the value is not a string, you must provide this
     * parameter with the right value. For example: PDO::PARAM_INT. See: http://php.net/manual/en/pdo.constants.php
     *
     * @return string String with the given value properly quoted.
     */
    function quote($value, $parameter_type = PDO::PARAM_STR)
    {
        return $this->pdo->quote($value, $parameter_type);
    }

    /**
     * Begins a transaction against the data provider.
     *
     * After calling this method, further operations on the data at the other point of the connection will be held until
     * a call to completeTrans(), which will perform those operations altogether.
     *
     * <code>
     * $dbpdo->beginTrans();
     * $dbpdo->execute("INSERT INTO somewhere (field) VALUES (".$dbpdo->param("value").")", array("Some value"));
     * $dbpdo->execute("INSERT INTO somewhere (field) VALUES (".$dbpdo->param("value").")", array("Another value"));
     * $dbpdo->completeTrans(); // The execute() operations are not actually executed until this point.
     * </code>
     *
     * @return bool Returns true on sucecss or false on failure.
     */
    function beginTrans()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Finishes a transaction started with beginTrans(), either commiting all the operations on the data provider that
     * were requested since the transaction started ($commit = true, default), or rolling them back so none of them are
     * actually performed against the data provider ($commit = false).
     *
     * <code>
     * $dbpdo->beginTrans();
     * $insertResult1 = $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$dbpdo->param("value").")", array("Some value"));
     * $insertResult2 = $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$dbpdo->param("value").")", array("Another value"));
     * $commit = $insertResult1 && $insertResult2; // Evaluates to false if any of the operations failed.
     * $dbpdo->completeTrans($commit);
     * </code>
     *
     * @param bool $commit Whether the operations in the transaction should be commited (true) or rolled back (false).
     *
     * @return bool Returns true on sucecss or false on failure.
     */
    function completeTrans($commit = true)
    {
        if($commit)
        {
            return $this->pdo->commit();
        }
        else
        {
            return $this->pdo->rollBack();
        }
    }

    /**
     * Establishes the connection to the data provider if it was not established already.
     */
    function doConnect()
    {
        if(!$this->pdo)
        {
            try
            {
                // create PDO connection, with (maybe) database specific options (@see PDO constants)
               $this->pdo = new PDO(
                    $this->DSN(),
                    $this->database->Username,
                    $this->database->Userpassword,
                    $this->database->parseOptions($this->database->DatabaseOptions));

                // force to set Exception error mode
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            }
            catch(Exception $e)
            {
                DatabaseError(_("Can't connect, PDO Exception: ") . $e->getMessage());
            }

        }
    }

    /**
     * Terminates the connection to the data provider if it was established.
     */
    function doDisconnect()
    {
        $this->pdo = null;
    }

    /**
     * Whether or not there is an ongoing transaction.
     *
     * @return bool
     */
    function inTransaction()
    {
        return PDO::inTransaction();
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
        return $this->pdo->lastInsertId($name);
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
        throw new Exception('The metaFields() method is not implemented for this driver.');
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
        throw new Exception('The primaryKeys() method not implemented for this driver.');
    }

    /**
     * Whether the this DBPDO implementation's statements are scrollable (true) or not (false).
     *
     * @return bool True if statements are scrollable, false otherwise.
     */
    function isEnabledScroll()
    {
        return isset($this->_databaseoptions[PDO::ATTR_CURSOR])
                  && $this->_databaseoptions[PDO::ATTR_CURSOR] == PDO::CURSOR_SCROLL;
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
        if ($count > -1)
        {
                if ($start > -1)
                        $limit = $start . ', ' . $count;
                else
                        $limit = $count;

                $limit_sql = 'limit ' . $limit;
        }
        else
        {
                $limit_sql = '';
        }

        return "$sql $limit_sql";
    }

    /**
     * Returns the SQL code to define the sorting method for the query results.
     *
     * @param string $sql   SQL code to define the sorting method. Required for DBMS that do not comply with the SQL
     *                      standard on how to define the sorting method of the results returned by a query. Leave empty
     *                      otherwise, and use the second and third parameters instead.
     * @param int    $field Name of the field to be used to determine the order of the results. You can provide several
     *                      field names, separated by commas.
     * @param int    $order SQL keyword to determine the order to be applied regarding the value of the field or fields.
     *                      either ascending (ASC) or descending (DESC).
     *
     * @return string SQL code. For example: "ORDER BY fieldName DESC" (20 first results)
     */
    function orderSQL($sql, $field, $order)
    {
        return $sql . ((!empty($field)) ? " order by $field $order" : "");
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
        throw new Exception('Procedures are not implemented for this driver.');
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
        return $this->execute($query, $params);
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
        // TODO: improve this to not be "select *" dependent
        return "select count(*) from ($sql) as rpclcountquery";
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
        return (int) $this->execute($this->countSQL($sql), $params)->fetchColumn();
    }

    /**
     * Retuns the version code of the DBMS.
     *
     * @return string Version code.
     */
    public function version()
    {
        if (!isset($this->data_cache['version']))
        {
            // Not all subdrivers support the getAttribute() method
            $this->data_cache['version'] = $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
        }

        return $this->data_cache['version'];
    }

    /**
     * Returns the given parameters as a string to be passed to a procedure call.
     *
     * @param array $params Parameters to be converted into a string.
     *
     * @return string Comma-separated list of parameters. For example: "parameter1, parameter2, parameter3".
     */
    protected function _convertParamsToString($params)
    {
        $inputs = array();

        // create the procedure SQL, when a param starts with @ is treated as variable parameter
        foreach($params as $param)
        {
            if(!is_array($param) && substr($param, 0, 1) === '@')
            {
                $inputs[] = $param;
            }
            else
            {
                $inputs[] = '?';
            }
        }

        return implode(',', $inputs);
    }
}

/**
 * Wrapper to handle a PDO instance using the InterBase driver.
 *
 * @see Database
 */
class DBInterbase extends DBPDO
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'firebird';
    }

    // Documented in the parent.
    function procedureSQL($name, $params)
    {
        $inputString = $this->_convertParamsToString($params);

        return "select * from $name($inputString)";
    }

    // Documented in the parent.
    function beginTrans()
    {
        // The driver for Interbase and Firebird DBMS requires that the autocommit PDO attribute is disabled for the
        // transaction to start.
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
        return parent::beginTrans();
    }

    // Documented in the parent.
    function completeTrans($commit = true)
    {
        parent::completeTrans($commit);
        return $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,1);
    }

    // Documented in the parent.
    function metaFields($tablename)
    {
        $sql = 'SELECT RDB$FIELD_NAME AFIELDNAME
                FROM RDB$RELATION_FIELDS
                WHERE RDB$RELATION_NAME=\''. strtoupper ($tablename). '\'';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $keys = array();
        foreach($indexes as $index)
        {
            $keys[trim($index['AFIELDNAME'])] = NULL;
        }

        return $keys;
    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {
        $sql = 'SELECT S.RDB$FIELD_NAME AFIELDNAME
                FROM RDB$INDICES I JOIN RDB$INDEX_SEGMENTS S ON I.RDB$INDEX_NAME=S.RDB$INDEX_NAME
                WHERE I.RDB$RELATION_NAME=\''.$tablename.'\'
                ORDER BY I.RDB$INDEX_NAME,S.RDB$FIELD_POSITION';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting indexes to 'field' => 'type'
        $keys = array();
        foreach($indexes as $index)
        {
            $keys[trim($index['AFIELDNAME'])] = 'PRIMARY';
        }

        return $keys;
    }

    // Documented in the parent.
    function limitSQL($sql, $count, $start = 0)
    {
        if($count > -1) {

            // first row to interbase is 1, not 0
            $start;
            if($start > 0)
            {
                $from = $start + 1;
                $to   = $start + $count;
                $sql  = "$sql ROWS $from TO $to";
            }
            else
                $sql  = "$sql ROWS $count ";
        }

        return $sql;
    }

    // Documented in the parent.
    function countSQL($sql)
    {
        // TODO: improve this to not be "select *" dependent
        return str_ireplace("select *", "select count(*)", $sql);
    }
}

/**
 * Wrapper to handle a PDO instance using the Firebird driver.
 *
 * @see Database
 */
class DBFirebird extends DBInterbase
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'firebird';
    }

    // Documented in the parent.
    function limitSQL($sql, $count, $start = 0)
    {
        $limit = "";

        if($count > 0)
            $limit .= " FIRST $count ";

        if($start > 0)
            $limit .= " SKIP $start ";

        $sql = str_ireplace("select", "select $limit", $sql);

        return $sql;
    }
}

/**
 * Wrapper to handle a PDO instance using the IBM driver.
 *
 * @see Database
 */
class DBIbm extends DBPDO
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'ibm';
    }

    // Documented in the parent.
    function procedureSQL($name, $params)
    {
        $inputString = $this->_convertParamsToString($params);

        return "call $name($inputString)";
    }

    // Documented in the parent.
    function DSNParams()
    {
        $params    = array();
        $dsnparams = array();

        // base params
        if($this->database->Host != '')
        {
            if($this->database->HostTranslation)
            {
               $params['hostname'] = '127.0.0.1';
            }
            else
            {
                $params['hostname'] = $this->database->Host;
            }
        }

        if($this->database->Databasename != '')
        {
            $params['database'] = $this->database->Databasename;
        }

        if($this->database->Port != '')
        {
            $params['port'] = $this->database->Port;
        }

        // custom extra params
        if(is_array($this->database->ConnectionParams))
        {
            $params = array_merge($params, $this->database->ConnectionParams);
        }


         foreach($params as $key => $value)
         {
             $dsnparams[] = "{$key}={$value}";
         }

        return implode(';', $dsnparams);
    }

    // Documented in the parent.
    function metaFields($tablename)
    {
        $parts  = explode('.', $tablename);
        $schema = current($parts);
        $table  = end($parts);

        if(count($parts) == 1)
            $conditions = "TABNAME='$table'";
        else
            $conditions = "TABNAME='$table' AND TABSCHEMA='$schema'";


       $sql = "SELECT COLNAME from SYSCAT.COLUMNS where $conditions";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting table_fields from '0' => 'columnName' to 'colunName' => NULL
        $keys = array();
        foreach ($table_fields as $row)
        {
            $keys[$row['COLNAME']] = NULL;
        }
        return $keys;

    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {
        $parts  = explode('.', $tablename);
        $schema = current($parts);
        $table  = end($parts);

        if(count($parts) == 1)
            $conditions = "a.table_name='$table'";
        else
            $conditions = "a.table_name='$table' AND a.table_schema='$schema'";

        $sql = "SELECT COLUMN_NAME
            FROM dba_constraints a,dba_cons_columns b
            WHERE $conditions
            AND a.constraint_name=b.constraint_name
            AND a.constraint_type ='P';";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $keys = array();
        foreach ($table_fields as $row)
        {
            $keys[$row['COLUMN_NAME']] = 'PRIMARY';
        }
        return $keys;

    }

    // Documented in the parent.
    function limitSQL($sql, $count, $start = 0)
    {
        if($count > -1)
        {
            $start = ($start > -1)? $start : 0;
            $sql = " SELECT rpcl2.*
              FROM (
                  SELECT ROW_NUMBER() OVER() AS \"RPCL_DB_ROWNUM\", rpcl1.*
                  FROM (
                      " . $sql . "
                  ) rpcl1
              ) rpcl2
              WHERE rpcl2.RPCL_DB_ROWNUM BETWEEN " . ($start+1) . " AND " . ($start+$count);

        }

        return $sql;
    }


}

/**
 * Wrapper to handle a PDO instance using the Informix driver.
 *
 * @see Database
 */
class DBInformix extends DBPDO
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'informix';
    }

    // Documented in the parent.
    function DSNParams()
    {
        $params     = array();
        $dsnparams  = array();

        // base params
        if($this->database->Host != '')
        {
            if($this->database->HostTranslation && strtolower($this->database->Host) == 'localhost')
            {
                $params['host'] = '127.0.0.1';
            }
            else
            {
                $params['host'] = $this->database->Host;
            }
        }

        if($this->database->Databasename != '')
        {
            $params['database'] = $this->database->Databasename;
        }

        if($this->database->Port != '')
        {
            $params['service'] = $this->database->Port;
        }

        // custom extra params
        if(is_array($this->database->ConnectionParams))
        {
            $params = array_merge($params, $this->database->ConnectionParams);
        }

         foreach($params as $key => $value)
         {
             $dsnparams[] = "{$key}={$value}";
         }

        return implode(';', $dsnparams);
    }

    // Documented in the parent.
    function metaFields($tablename)
    {
        $sql =
        "select c.colname
        from syscolumns c, systables t,outer sysdefaults d
        where c.tabid=t.tabid and d.tabid=t.tabid and d.colno=c.colno
        and tabname='$tablename' order by c.colno";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['colname']] = NULL;
        }

        return $keys;
    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {
        $sql =
        "select colname
            from systables a, sysconstraints b, sysindexes c , syscolumns d
            where a.tabname = '$tablename'
            and a.tabid = b.tabid
            and a.tabid = c.tabid
            and a.tabid = d.tabid
            and b.constrtype ='P'
            and b.idxname = c.idxname
            and (
            colno = part1 or
            colno = part2 or
            colno = part3 or
            colno = part4 or
            colno = part5 or
            colno = part6 or
            colno = part7 or
            colno = part8 or
            colno = part9 or
            colno = part10 or
            colno = part11 or
            colno = part12 or
            colno = part13 or
            colno = part14 or
            colno = part15 or
            colno = part16
            )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting indexes to 'field' => 'type'
        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['colname']] = 'PRIMARY';
        }

        return $keys;
    }
}

/**
 * Wrapper to handle a PDO instance using the MySQL driver.
 *
 * @see Database
 */
class DBMysql extends DBPDO {

    // Documented in the parent.
    function DSNDriver()
    {
        return 'mysql';
    }

    // Documented in the parent.
    function procedureSQL($name, $params)
    {
        $inputString = $this->_convertParamsToString($params);

        return "call $name($inputString)";
    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {
        $sql = "SHOW INDEX FROM $tablename where Key_name = 'PRIMARY'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting indexes to 'field' => 'type'
        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['Column_name']] = $index['Key_name'];
        }

        return $keys;
    }

    // Documented in the parent.
    function metaFields($tablename)
    {
        $stmt = $this->pdo->prepare("DESCRIBE $tablename");
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // converting table_fields from '0' => 'columnName' to 'colunName' => NULL
        $keys = array();
        foreach ($table_fields as $key => $value)
        {
            $keys[$value] = NULL;
        }
        return $keys;
    }

}

/**
 * Wrapper to handle a PDO instance using the Oracle OCI driver.
 *
 * @see Database
 */
class DBOci extends DBPDO
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'oci';
    }

    // Documented in the parent.
    function procedureSQL($name, $params)
    {
        $inputString = $this->_convertParamsToString($params);

        return "begin $name($inputString); end;";
    }

    // Documented in the parent.
    function metaFields($tablename)
    {
        // split to get the schema
        $parts  = explode('.', $tablename);
        $schema = current($parts);
        $table  = end($parts);

        if(count($parts) == 1)
            $conditions = "TABLE_NAME='$table'";
        else
            $conditions = "TABLE_NAME='$table' AND OWNER='$schema'";

        $sql = "SELECT COLUMN_NAME from all_tab_columns where $conditions";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['COLUMN_NAME']] = NULL;
        }

        return $keys;
    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {
        // split to get the schema
        $parts  = explode('.', $tablename);
        $schema = current($parts);
        $table  = end($parts);

        if(count($parts) == 1)
            $conditions = "cols.TABLE_NAME='$table'";
        else
            $conditions = "cols.TABLE_NAME='$table' AND cols.OWNER='$schema'";

        $sql =
            "SELECT cols.COLUMN_NAME
            FROM all_constraints cons, all_cons_columns cols
            WHERE $conditions
            AND cons.constraint_type = 'P'
            AND cons.constraint_name = cols.constraint_name
            AND cons.owner = cols.owner
            ORDER BY cols.table_name, cols.position";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting indexes to 'field' => 'type'
        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['COLUMN_NAME']] = 'PRIMARY';
        }

        return $keys;
    }

    // Documented in the parent.
    function limitSQL($sql, $count, $start = 0)
    {
        if($count > -1)
        {
            $start = ($start > -1)? $start : 0;
            $sql = "SELECT rpcl2.*
                FROM (
                    SELECT rpcl1.*, ROWNUM AS \"RPCL_DB_ROWNUM\"
                    FROM (
                        " . $sql . "
                    ) rpcl1
                ) rpcl2
                WHERE rpcl2.\"RPCL_DB_ROWNUM\" BETWEEN " . ($start+1) . " AND " . ($start+$count);
        }

        return $sql;
    }

    // Documented in the parent.
    function countSQL($sql)
    {
        // Oracle works without a general alias.
        return "select count(*) from ({$sql})";
    }
}

/**
 * Wrapper to handle a PDO instance using the PostgreSQL driver.
 *
 * @see Database
 */
class DBPgsql extends DBPDO
{

    // Documented in the parent.
    function metaFields($tablename)
    {

        $sql = "select distinct column_name from information_schema.columns where table_name = '$tablename'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        $raw_column_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($raw_column_data as $outer_key => $array){
            foreach($array as $inner_key => $value){
                if (!(int)$inner_key)
                {
                    $table_fields[] = $value;
                }
            }
        }

        // converting table_fields from '0' => 'columnName' to 'colunName' => NULL
        $keys = array();
        foreach ($table_fields as $key => $value)
        {
            $keys[$value] = NULL;
        }

        return $keys;
    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {

        $sql = "SELECT
                  pg_attribute.attname
                FROM pg_index, pg_class, pg_attribute
                WHERE
                  pg_class.oid = '$tablename'::regclass AND
                  indrelid = pg_class.oid AND
                  pg_attribute.attrelid = pg_class.oid AND
                  pg_attribute.attnum = any(pg_index.indkey)
                  AND indisprimary";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // converting indexes to 'field' => 'type'
        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['attname']] = 'PRIMARY';
        }

        return $keys;
    }

    // Documented in the parent.
    function procedureSQL($name, $params)
    {
        $inputString = $this->_convertParamsToString($params);

        return "select * from $name($inputString)";
    }

    // Documented in the parent.
    function limitSQL($sql, $count, $start = 0)
    {
        if ($count > -1)
        {
            $limit_sql = "limit $count";
            if ($start > -1)
                $limit_sql .= " OFFSET $start";
        }
        else
        {
            $limit_sql = '';
        }

        return "$sql $limit_sql";
    }
}

/**
 * Wrapper to handle a PDO instance using the SQLite driver.
 *
 * @see Database
 */
class DBSqlLite extends DBPDO
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'sqlite';
    }

    // Documented in the parent.
    function executeProc($query, $params = array())
    {
        DatabaseError(_("SQLite does not support stored procedures."));
    }

    // Documented in the parent.
    function DSNParams()
    {
        return $this->database->Databasename;
    }

    // Documented in the parent.
    function metaFields($tablename)
    {

       $sql = "pragma table_info('$tablename')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting table_fields from '0' => 'columnName' to 'colunName' => NULL
        $keys = array();
        foreach ($table_fields as $row)
        {
            $keys[$row['name']] = NULL;
        }
        return $keys;

    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {

        $sql = "pragma table_info('$tablename')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $keys = array();
        foreach ($table_fields as $row)
        {
            if($row['pk'])
                $keys[$row['name']] = 'PRIMARY';
        }
        return $keys;

    }
}

/**
 * Wrapper to handle a PDO instance using the Micorsoft SQL Server driver.
 *
 * @see Database
 */
class DBSqlSrv extends DBPDO
{

    // Documented in the parent.
    function DSNDriver()
    {
        return 'sqlsrv';
    }

    // Documented in the parent.
    function procedureSQL($name, $params)
    {
        $inputString = $this->_convertParamsToString($params);

        return "EXECUTE $name $inputString";
    }

    // Documented in the parent.
    function DSNParams()
    {
        $params     = array();
        $dsnparams  = array();

        // base params
        if($this->database->Host != '')
        {
            if($this->database->HostTranslation && strtolower($this->database->Host) == 'localhost')
            {
                $params['Server'] = '127.0.0.1';
            }
            else
            {
                $params['Server'] = $this->database->Host;
            }

            if($this->database->Port != '')
            {
                $params['Server'] .= ",".$this->database->Port;
            }
        }

        if($this->database->Databasename != '')
        {
            $params['Database'] = $this->database->Databasename;
        }


        // custom extra params
        if(is_array($this->database->ConnectionParams))
        {
            $params = array_merge($params, $this->database->ConnectionParams);
        }

         foreach($params as $key => $value)
         {
             $dsnparams[] = "{$key}={$value}";
         }

        return implode(';', $dsnparams);
    }

    // Documented in the parent.
    function metaFields($tablename)
    {
       $tablename = end(explode('.', $tablename));

       $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$tablename'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting table_fields from '0' => 'columnName' to 'colunName' => NULL
        $keys = array();
        foreach ($table_fields as $row)
        {
            $keys[$row['COLUMN_NAME']] = NULL;
        }
        return $keys;
    }

    // Documented in the parent.
    function primaryKeys($tablename)
    {
        $tablename = end(explode('.', $tablename));

        $sql = "exec sp_pkeys @table_name = '$tablename'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // converting indexes to 'field' => 'type'
        $keys = array();
        foreach($indexes as $index)
        {
            $keys[$index['COLUMN_NAME']] = 'PRIMARY';
        }

        return $keys;

    }

    // Documented in the parent.
    function countSQL($sql)
    {
        // we need to strip order by clause
        $orderby = stristr($sql, 'ORDER BY');

        if($orderby !== false)
            $sql = trim(substr($sql, 0, strrpos($sql, $orderby)));

        return "select count(*) from ($sql) as rpclcountquery";
    }

    // Documented in the parent.
    function limitSQL($sql, $count, $start = 0)
    {

        // As of SQL Server 2012 (11.0.*) OFFSET is supported
        if (version_compare($this->version(), '11', '>='))
        {
            if($start > -1)
                $sql .= ' OFFSET '.(int) $start.' ROWS';

            if($count > -1)
                $sql .= ' FETCH NEXT '.$count.' ROWS ONLY';

            return $sql;
        }


        // An ORDER BY clause is required for ROW_NUMBER() to work
        $orderby = stristr($sql, 'ORDER BY');

        if ($start > -1 && !empty($orderby))
        {
            if($count > -1)
            {
                $limit = $start + $count;

                // We have to strip the ORDER BY clause
                $sql = trim(substr($sql, 0, strrpos($sql, $orderby)));

                // Get the fields to select from our subquery, so that we can avoid rpcl_rownum appearing in the actual results
                $select = '*';

                $sql = "SELECT * FROM ("
                    .preg_replace('/^(SELECT( DISTINCT)?)/i', '\\1 ROW_NUMBER() OVER('.trim($orderby).') AS RPCL_DB_ROWNUM, ', $sql)
                    .") rpcl_subquery"
                    ." WHERE RPCL_DB_ROWNUM BETWEEN ".($start + 1).' AND '.$limit;

                return $sql;
            }
            else
            {
                // count should exists
                return $sql;
            }

        }
        else if($count > -1)
        {
            return preg_replace('/(^\SELECT (DISTINCT)?)/i','\\1 TOP '.$count.' ', $sql);
        }
        else
        {
            return $sql;
        }



    }

}





?>
