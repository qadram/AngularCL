<?php
/**
 *  This file is part of the RPCL project
 *
 *  Copyright (c) 2004-2011 Embarcadero Technologies, Inc.
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

use_unit("classes.inc.php");
use_unit("rtl.inc.php");

/**
 * Base class for connection components, that is, components that perform a connection to a data provider (e.g. to a
 * database management system).
 *
 * This class provides the methods used by data access components (Table, Query, etc.) to retrieve data. The class acts
 * like an interface that determines the properties and methods expected by data access components, which must be
 * implemented in child classes, such as Database.
 */
class CustomConnection extends Component
{

    protected $_fstreamedconnected=false;


    // LIFECYCLE

    // Documented in the parent.
    function __construct($aowner=null)
    {
        //Calls inherited constructor
        parent::__construct($aowner);
    }

    // Documented in the parent.
    function loaded()
    {
        parent::loaded();
        if ($this->_fstreamedconnected)
        {
            $this->Connected = true;
        }
    }


    // PROPERTIES

    /**
     * Whether the connection is open ("1") or closed ("0").
     */
    function readConnected() { return "0"; }
    function writeConnected($value)
    {
        if (($this->ControlState & csLoading)==csLoading)
        {
            $this->_fstreamedconnected=$value;
        }
        else
        {
            // prevent to execute when the current is for javascript or css
            if (isset($_GET['css']) || isset($_GET['js'])){

                return;
            }

            if ($value == $this->readConnected())
            {
                // silent is gold...
            }
            else
            {
                if ($value)
                {
                    $this->callEvent("onbeforeconnect",array());
                    $this->DoConnect();
                    $this->callEvent("onafterconnect",array());
                }
                else
                {
                    $this->callEvent("onbeforedisconnect",array());
                    $this->DoDisconnect();
                    $this->callEvent("onafterdisconnect",array());
                }
            }
        }
    }

    protected $_drivername="";
    /**
     * Name of the driver to be used for the connection.
     */
    function readDriverName() { return $this->_drivername; }
    function writeDriverName($value) { $this->_drivername=$value; }
    function defaultDriverName() { return ""; }


    // EVENTS

    protected $_onbeforeconnect = null;
    /**
     * Triggered before the connection is established.
     */
    function readOnBeforeConnect() { return $this->_onbeforeconnect; }
    function writeOnBeforeConnect($value) { $this->_onbeforeconnect=$value; }
    function defaultOnBeforeConnect() { return null; }

    protected $_onafterconnect = null;
    /**
     * Triggered after the connection has been established.
     */
    function readOnAfterConnect() { return $this->_onafterconnect; }
    function writeOnAfterConnect($value) { $this->_onafterconnect=$value; }
    function defaultOnAfterConnect() { return null; }

    protected $_onbeforedisconnect = null;
    /**
     * Triggered before the connection is closed.
     */
    function readOnBeforeDisconnect() { return $this->_onbeforedisconnect; }
    function writeOnBeforeDisconnect($value) { $this->_onbeforedisconnect=$value; }
    function defaultOnBeforeDisconnect() { return null; }

    protected $_onafterdisconnect = null;
    /**
     * Triggered after the connection has been closed.
     */
    function readOnAfterDisconnect() { return $this->_onafterdisconnect; }
    function writeOnAfterDisconnect($value) { $this->_onafterdisconnect=$value; }
    function defaultOnAfterDisconnect() { return null; }


    // METHODS

    /**
     * Opens the conection.
     */
    function Open()
    {
        $this->Connected = true;
    }

    /**
     * Closes the connection.
     */
    function Close()
    {
        $this->Connected = false;
    }

    /**
     * Executes the given query against the data provider.
     *
     * <code>
     * $results = $customConnection->execute("SELECT something FROM somewhere WHERE somethingElse = ".$customConnection->param("number"), array(6));
     * </code>
     *
     * @param string $query  Query.
     * @param array  $params Array of parameters to be passed along with the query.
     *
     * @return mixed The return value depends on the implementation.
     */
    function execute($query, $params = array()){}

    /**
     * Returns a query object for the given $query. You can usually then run the query against the data provider by
     * calling the execute() method on the query object.
     *
     * <code>
     * $selectQuery = $customConnection->prepare("SELECT something FROM somewhere");
     * // Do implementation-specific operations on the query object, such as binding paramenters, etc.
     * $results = $selectQuery->execute();
     * </code>
     *
     * @param string $query
     *
     * @return mixed
     */
    function prepare($query){}

    /**
     * Returns a place holder for a patameter that can be used in a query string. The place holder will later be
     * replaced by the actual parameter when executing the query.
     *
     * <code>
     * $results = $customConnection->execute("SELECT something FROM somewhere WHERE somethingElse = ".$customConnection->param("number"), array(6));
     * </code>
     *
     * @param mixed $input The purpose of this parameter depends on the actual implementation. Some implementations
     *                     might ignore this parameter altogether, and just associate the parameters to the place
     *                     holders in base to their order in the array of parameters.
     *
     * @return string Place holder.
     */
    function param($input){}

    /**
     * Returns the given value quoted so it can be safely used in a query string.
     *
     * <code>
     * $result = $customConnection->execute("SELECT something FROM somewhere WHERE somethingElse != ".$customConnection->quoteStr($_POST["userInput"]));
     * </code>
     *
     * @param mixed $imput Value to be quoted.
     *
     * @return string Quoted value.
     */
    function quoteStr($input){}

    /**
     * Begins a transaction against the data provider.
     *
     * After calling this method, further operations on the data at the other point of the connection will be held until
     * a call to completeTrans(), which will perform those operations altogether.
     *
     * <code>
     * $customConnection->beginTrans();
     * $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$customConnection->param("value").")", array("Some value"));
     * $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$customConnection->param("value").")", array("Another value"));
     * $customConnection->completeTrans(); // The execute() operations are not actually executed until this point.
     * </code>
     *
     * @return mixed The return value will depend on the implementation.
     */
    function beginTrans(){}

    /**
     * Finishes a transaction started with beginTrans(), either commiting all the operations on the data provider that
     * were requested since the transaction started ($commit = true, default), or rolling them back so none of them are
     * actually performed against the data provider ($commit = false).
     *
     * <code>
     * $customConnection->beginTrans();
     * $insertResult1 = $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$customConnection->param("value").")", array("Some value"));
     * $insertResult2 = $customConnection->execute("INSERT INTO somewhere (field) VALUES (".$customConnection->param("value").")", array("Another value"));
     * $commit = $insertResult1 && $insertResult2; // Evaluates to false if any of the operations failed.
     * $customConnection->completeTrans($commit);
     * </code>
     *
     * @param bool $commit Whether the operations in the transaction should be commited (true) or rolled back (false).
     *
     * @return mixed The return value will depend on the implementation.
     */
    function completeTrans($commit = true){}

    /**
     * Establishes the connection to the data provider if it was not established already.
     */
    function doConnect(){}

    /**
     * Terminates the connection to the data provider if it was established.
     */
    function doDisconnect(){}

    /**
     * Whether or not there is an ongoing transaction.
     *
     * @return bool
     */
    function inTransaction(){}

}

define('dsInactive'    ,1);
define('dsBrowse'      ,2);
define('dsEdit'        ,3);
define('dsInsert'      ,4);

define('daFail'        ,1);
define('daAbort'       ,2);

/**
 * Class for exceptions related to databases.
 *
 * Rather than creating and raising an instance of thsi exception, consider calling the databaseError() function for a
 * cleaner code.
 */
class EDatabaseError extends Exception { }


/**
 * Raises an EDatabaseError exception.
 *
 * @param string    $message   Error message.
 * @param Component $component Component instance that is raising the exception (optional).
 *
 * @throw EDatabaseError
 */
function databaseError($message, $component=null)
{
    if ((assigned($component)) && ($component->Name != ''))
    {
        throw new EDatabaseError(sprintf('%s: %s', $component->Name, $message));
    }
    else
    {
        throw new EDatabaseError($message);
    }
}


/**
 * This class defines the common methods and properties to work with a collection of data (dataset), no matter how the
 * data is stored.
 *
 * Child classes might encapsulate access to a simple array in memory, a text file in disk, or even a query on a
 * database.
 *
 * @link wiki://DataSet_Components
 */
class DataSet extends Component
{

    protected $_fstreamedactive = false;

    // LIFECYCLE

    // Documented in the parent.
    function loaded()
    {
        parent::loaded();
        $this->writeMasterSource($this->_mastersource);
        $this->Active = $this->_fstreamedactive;
    }

    // Documented in the parent.
    function serialize()
    {
        parent::serialize();

        if ( $owner = $this->readOwner() )
        {
            $prefix = $owner->readNamePath().".".$this->_name.".";

            // restore the state
            $_SESSION[$prefix."State"] = $this->_state;
        }
    }

    // Documented in the parent.
    function unserialize()
    {
        parent::unserialize();

        if ($owner = $this->readOwner())
        {
            $prefix = $owner->readNamePath().".".$this->_name.".";
            if (isset($_SESSION[$prefix."State"]))
            {
                $this->_state = $_SESSION[$prefix."State"];
            }
        }
    }


    // PROPERTIES

    protected $_active = false;
    /**
     * Whether the dataset is open (true) or closed (false). You cannot work on a closed dataset.
     *
     * @return bool
     */
    function readActive()
    {
        return $this->_active;
    }


    function writeActive($value)
    {
        if (($this->ControlState & csLoading) == csLoading)
        {
            $this->_fstreamedactive = $value;
        }
        else
        {
            // prevent to execute when the current is for javascript or css
            if (isset($_GET['css']) || isset($_GET['js'])){

                // emulates the setter
                $this->_active = $value;
                return;
            }

            if ($this->_active != $value)
            {
                try
                {
                    if ($value == true)
                    {
                        // this if for internal use
                        $this->_bof = true;
                        $this->_eof = true;
                        $this->callevent("onbeforeopen",array());
                        
                        $this->internalOpen();

                        // if the state is stored in session, restore it
                        if ($this->_state == dsInactive)
                            $this->_state = dsBrowse;

                        // opened successful
                        $this->_active = true;
                        $this->callevent("onafteropen",array());
                    }
                    else
                    {
                        $this->_active = false;
                        $this->callevent("onbeforeclose",array());

                        $this->internalClose();
                        $this->_state = dsInactive;

                        $this->callevent("onafterclose",array());
                    }

                }catch(Exception $e)
                {
                    $this->State = dsInactive;
                    throw $e;
                }

            }
        }
    }

    protected $_bof = false;
    /**
     * Whether the current record is the first record of the dataset (true) or not (false).
     *
     * @return bool
     */
    function readBOF() { return $this->_bof; }
    function defaultBOF() { return false; }

    protected $_canmodify=true;
    /**
     * Whether the dataset can be modified (true) or it is read-only (false).
     *
     * @return bool
     */
    function readCanModify() { return $this->_canmodify; }
    function writeCanModify($value) { $this->_canmodify=$value; }
    function defaultCanModify() { return true; }


    protected $_eof = false;
    /**
     * Whether the current record is the last record of the dataset (true) or not (false).
     *
     * @return bool
     */
    function readEOF() { return $this->_eof; }
    function defaultEOF() { return false; }

    protected $_fieldbuffer=array();
    /**
     * Associative array of key-value pairs describing the content (field-value) of the current record in the underlying
     * storage system.
     *
     * Any change to the current record that has not been posted yet will not be reflected in this array.
     *
     * @see readFields()
     *
     * @return array
     */
    function readFieldBuffer(){ return $this->_fieldbuffer;}

    /**
     * Number of fields of the current record.
     *
     * @return int
     */
    function readFieldCount() { return 0; }

    /**
     * Associative array of key-value pairs describing the content (field-value) of the current record in the dataset.
     *
     * Any change to the current record will be reflected in this array, even if it has not been posted to the
     * underlying storage system yet.
     *
     * @see readFieldBuffer()
     *
     * @return array
     */
    function readFields() { return array(); }

    protected $_filter="";
    /**
     * Filter to be applied when retrieving records from the data provider. Only the records matching the conditions
     * defined in the filter will be retrieved and available in the dataset.
     */
    function readFilter() { return $this->_filter; }
    function writeFilter($value){$this->_filter=$value;}
    function defaultFilter() { return ""; }

    protected $_limitcount='';
    /**
     * Number of records from the data provider to be provided by the dataset.
     *
     * For example, if this property is set to "10", the dataset will only contain 10 records from the data provider.
     *
     * @see getLimitStart()
     *
     * @return string
     */
    function getLimitCount() { return $this->_limitcount;   }
    function setLimitCount($value) { $this->_limitcount=$value; }
    function defaultLimitCount() { return "10"; }

    protected $_limitstart='';
    /**
     * Number of records from the data provider to be skipped in the dataset, from the start.
     *
     * For example, if this property is set to "10", the first record in the dataset will be the 11th record from the
     * data provider.
     *
     * @see getLimitCount()
     *
     * @return string
     */
    function getLimitStart() { return $this->_limitstart;   }
    function setLimitStart($value) { $this->_limitstart=$value;     }
    function defaultLimitStart() { return "0"; }

    protected $_masterfields=array();
    /**
     * Associative array of key-value pairs, where the 'key' is the name of a field in this dataset, and the 'value' is
     * the name of a field from the MasterSource. This association is used to filter the records retrieved from the data
     * provider into the dataset.
     *
     * Suppose you have two datasets, named MasterDataSet and SlaveDataSet, where SlaveDataSet (this dataset) has
     * MasterDataSet associated to its MasterSource property (MasterSource = $MasterDataSet), and SlaveDataSet's
     * MasterFields property is defined as array('category_id' => 'id').
     *
     * As a result of the configuration above, SlaveDataSet would only contain records whose 'category_id' field has the
     * same value as the 'id' field of the current record of the MasterDataSet.
     *
     * @see readMasterSource()
     *
     * @return array
     */
    function readMasterFields() { return $this->_masterfields; }
    function writeMasterFields($value) { $this->_masterfields=$value; }
    function defaultMasterFields() { return array(); }

    protected $_mastersource=null;
    /**
     * Instance of DataSet to be used to filter the records that this dataset retrieves from the data provider.
     *
     * This property must be used in combination with MasterFields, else it is useless.
     *
     * @see readMasterFields()
     *
     * @return DataSet
     */
    function readMasterSource() { return $this->_mastersource;   }
    function writeMasterSource($value)
    {
            $this->_mastersource=$this->fixupProperty($value);
    }

    protected $_modified = false;
    /**
     * Whether the current record has been modified (true) or not (false). For new records, this function returns true
     * when any of its fields has been given a value.
     *
     * @return bool
     */
    function readModified() { return $this->_modified; }
    function writeModified($value) { $this->_modified=$value; }
    function defaultModified() { return false; }

    protected $_recno=0;
    /**
     * Number that represents the position of the current record in the dataset.
     *
     * Change this property to navigate to a record in a different position.
     *
     * @return int
     */
    function readRecNo() { return $this->_recno; }
    function writeRecNo($value)
    {
        if ($value != $this->_recno)
        {
            $diff = $value - $this->_recno;
            if ($diff > 0)
            {
                $this->moveBy($diff);
            }
            $this->_recno = $value;
        }
    }
    function defaultRecNo() { return 0; }

    protected $_recordcount = 0;
    /**
     * Total number of records in the dataset.
     *
     * @return int
     */
    function readRecordCount() { return $this->_recordcount; }
    function defaultRecordCount() { return 0; }

    protected $_state = dsInactive;
    /**
     * Current state of the dataset.
     *
     * 'dsInactive' indicates the dataset is closed. 'dsBrowse' is the standard state when the dataset is open. 'dsEdit'
     * indicates that a record of the dataset is being modified. 'dsInsert' indicates that a new record, to be added to
     * the dataset, is being defined.
     *
     * @return int
     */
    function readState() { return $this->_state; }
    function writeState($value) { $this->_state=$value; }
    function defaultState() { return dsInactive; }


    // EVENTS

    protected $_onnewrecord=null;
    /**
     * Triggered once a new record has been added to the dataset (not to the underlying database).
     *
     * The new record might have been added by starting either an append or an insert operation.
     *
     * You may use this event to make some changes to the new record that will not count as a modification, so the
     * record  won't be commited to the underlying database unless it is further modified later, or you call
     * completeTrans().
     *
     * @see append(), insert()
     */
    function readOnNewRecord() { return $this->_onnewrecord; }
    function writeOnNewRecord($value) { $this->_onnewrecord=$value; }
    function defaultOnNewRecord() { return null; }


    // METHODS

    /**
     * Opens the dataset. When a dataset is opened, it gets filled with the records from its data provider.
     *
     * @see close()
     */
    function open()
    {
        $this->Active = true;
    }

    /**
     * Closes the dataset. The dataset will be emptied of records, and you won't be able to work with the dataset until
     * you open it again.
     *
     * @see open()
     */
    function close()
    {
        $this->Active = false;
    }

    /**
     * Begins an insert operation for the current record.
     *
     * In an insert operation, you define a new record by modifying the data in the current record, and then you add
     * that new record to the dataset.
     *
     * For example, imagine you have a dataset with the fields Name and Surname, and for those fields, the current record
     * has the values 'Jane' and 'Doe' respectively. You could use this method to insert a new record in the dataset
     * changing just the Name, and reusing the Surname of the current record:
     *
     * <code>
     * $dataSet->insert();
     * $dataSet->Name = 'John';
     * $dataSet->post();
     * </code>
     *
     * Now the dataset would have both a record for Jane Doe and a record for John Doe.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method. Imagine the
     * following code was used instead of the three statements above:
     *
     * <code>
     * $dataSet->insert();
     * $dataSet->Name = 'John';
     * $dataSet->insert();
     * $dataSet->Name = 'Jack';
     * $dataSet->post();
     * </code>
     *
     * Then the dataset would have records for Jane Doe, John Doe and Jack Doe.
     *
     * You can use this feature to insert records with values in common to the current record using a loop like this:
     *
     * <code>
     * $names = array('John', 'Jake', 'Jennifer', 'James', 'Judy');
     * foreach ($names as $name)
     * {
     *     $dataSet->insert();
     *     $dataSet->Name = $name;
     * }
     * $dataSet->post();
     * </code>
     *
     * @see append(), cancel(), edit(), post()
     */
    function insert()
    {
        $this->beginInsertAppend();
        $this->internalInsert();
        $this->endInsertAppend();
    }

    /**
     * Begins an append operation for the current record.
     *
     * In an append operation, you define a brand new record, and then you add it to the dataset.
     *
     * For example, imagine you have a dataset with the fields Name and Surname. This is how you would add a new record
     * to it:
     *
     * <code>
     * $dataSet->append();
     * $dataSet->Name = 'Jane';
     * $dataSet->Surname = 'Doe';
     * $dataSet->post();
     * </code>
     *
     * Now the dataset would have a new record for Jane Doe.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method. Imagine the
     * following code was used instead of the four statements above:
     *
     * <code>
     * $dataSet->append();
     * $dataSet->Name = 'John';
     * $dataSet->Surname = 'Doe';
     * $dataSet->append();
     * $dataSet->Name = 'Jane';
     * $dataSet->Surname = 'Doe';
     * $dataSet->post();
     * </code>
     *
     * Then the dataset would have two new records, one for John Doe and another one for Jane Doe.
     *
     * You can use this feature to append records to the dataset in a loop like this:
     *
     * <code>
     * $names = array('John' => 'Doe', 'Jane' => 'Doe');
     * foreach ($names as $name => $surname)
     * {
     *     $dataSet->append();
     *     $dataSet->Name = $name;
     *     $dataSet->Surname = $surname;
     * }
     * $dataSet->post();
     * </code>
     *
     * @see cancel(), insert(), post()
     */
    function append()
    {
        $this->beginInsertAppend();
        $this->clearBuffers();
        $this->initRecord();
        $this->internalInsert();
        $this->endInsertAppend();
    }

    /**
     * Removes the current record from the dataset.
     */
    function delete()
    {
        $this->checkActive();

        if (($this->State==dsInsert))
        {
            $this->Cancel();
        }
        else
        {
            if ($this->_recno == 0)
                DatabaseError(_("Cannot perform this operation on an empty dataset"), $this);

            // event before
            $this->callevent("onbeforedelete",array());

            // try delete
            try
            {
                $this->internalDelete();
                $this->freeFieldBuffers();
                $this->State = dsBrowse;
            }
            catch(Exception $e)
            {
                $this->callEvent('ondeleteerror', array('Exception'=>$e, 'Action'=>'internalDelete'));
            }

            // event after
            $this->callevent("onafterdelete",array());
        }
    }

    /**
     * Begins an edit operation for the current record.
     *
     * In an edit operation, you modify the data in the current record, and then update the dataset with those changes.
     *
     * For example, imagine you have a dataset with the fields Name and Surname, and for those fields, the current record
     * has the values 'Jane' and 'Doe' respectively. You could use this method to change the Name of the record:
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'John';
     * $dataSet->post();
     * </code>
     *
     * Now the dataset record for Jane Doe would be replaced by a record for John Doe.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method.
     *
     * <code>
     * $dataSet->append();
     * $dataSet->Name = 'John';
     * $dataSet->Surname = 'Doe';
     * $dataSet->edit(); // The append operation stops and the new record, for John Doe, is added to the dataset.
     * $dataSet->Name = 'Jane';
     * $dataSet->post(); // The Name of the record, which was already in the dataset, is changed to 'Jane'.
     * </code>
     *
     * @see cancel(), insert(), post()
     */
    function edit()
    {
        if ($this->State == dsEdit || $this->State == dsInsert)
        {
            return;
        }

        $this->checkBrowseMode();
        $this->checkCanModify();
        $this->callevent("onbeforeedit",array());
        $this->checkOperation("internalEdit", "onediterror");
        $this->State = dsEdit;
        $this->callevent("onafteredit",array());
    }

    /**
     * Points the record cursor to the first record in the dataset.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'Jane';
     * $dataSet->first();
     * </code>
     *
     * When you call first(), the Name of the current record is changed to 'Jane' in the dataset, and then the record
     * cursor is moved to the first record.
     */
    function first()
    {
        $this->checkBrowseMode();

        if(!$this->BOF)
        {
            // no directional dataset (reopen)
            if ($this->isUnidirectional())
            {
                $this->Active = false;
                $this->Active = true;
            }
            // direcctional dataset
            else
            {
                $this->clearBuffers();
                $this->internalFirst();
            }

            $this->_eof = false;
            $this->_bof = true;
        }

    }

    /**
     * Points the record cursor to the last record in the dataset.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'Jane';
     * $dataSet->last();
     * </code>
     *
     * When you call last(), the Name of the current record is changed to 'Jane' in the dataset, and then the record
     * cursor is moved to the last record.
     */
    function last()
    {
        $this->checkBidirectional();
        $this->checkBrowseMode();

        if (!$this->EOF)
        {
            $this->clearBuffers();

            // implement in child class
            $this->internalLast();

            $this->_eof = true;
            $this->_bof = false;
        }
    }

    /**
     * Points the record cursor to the next record in the dataset.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'Jane';
     * $dataSet->next();
     * </code>
     *
     * When you call next(), the Name of the current record is changed to 'Jane' in the dataset, and then the record
     * cursor is moved to the next record.
     */
    function next()
    {
        $this->moveBy(1);
    }

    /**
     * Points the record cursor to the previous record in the dataset.
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'Jane';
     * $dataSet->prior();
     * </code>
     *
     * When you call prior(), the Name of the current record is changed to 'Jane' in the dataset, and then the record
     * cursor is moved to the previous record.
     */
    function prior()
    {
        $this->moveBy(-1);
    }

    /**
     * Reloads the dataset with the records from the data provider.
     */
    function refresh()
    {
        $this->Active = false;
        $this->Active = true;
    }

    /**
     * Moves the record cursor the given number of positions. Use negative values to move the cursor backwards.
     *
     * For example, if the current record of the dataset is the 6th:
     *
     * <code>
     * $dataSet->moveBy(2); // The 8th is now the current record.
     * $dataSet->moveBy(-5); // The 3rd is now the current record (8 - 5 = 3).
     * </code>
     *
     * The movement goes as far as it can. If you provide a number that would take the cursor to a position outside of
     * the range of records, the cursor will be moved just until the limit (the first or the last record).
     *
     * For example, if you are in the 10th of the 30 records the dataset has:
     *
     * <code>
     * $dataSet->moveBy(-21); // Takes you to the first record.
     * $dataSet->moveBy(54); // Takes you to the last record.
     * </code>
     *
     * Any unsaved changes to the current record will be saved to the dataset upon calling this method.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'Jane';
     * $dataSet->moveBy(6);
     * </code>
     *
     * When you call moveBy(), the Name of the current record is changed to 'Jane' in the dataset, and then the record
     * cursor is moved the given positions.
     *
     * @param int $positions
     */
    function moveBy($positions)
    {
        $count = $positions;

        // backward
        if ($positions < 0)
        {
            $this->checkBidirectional();
            $this->checkbrowsemode();

            while($count++ && !$this->BOF)
            {
                $this->_eof = false;
                $this->_bof = ! $this->PriorRecord();
                
                // only recno decrements if the prior record is fetched
                if ( ! $this->BOF)
                    $this->_recno--;
            }
            
            $this->freeFieldBuffers();
        }
        // forward
        else if ($positions > 0)
        {
            $this->checkbrowsemode();

            while($count-- && !$this->EOF)
            {
                $this->_bof = false;
                $this->_eof = !$this->NextRecord();
                
                // only recno decrements if the prior record is fetched
                if ( ! $this->EOF)
                    $this->_recno++;
            }
            $this->freeFieldBuffers();
        }
    }

    /**
     * Stops the current operation to modify the dataset, and performs the actual changes.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'John';
     * $dataSet->post(); // It is at this point where the Name is actually changed in the dataset.
     * </code>
     *
     * @see append(), edit(), insert()
     */
    function post()
    {
        $this->checkStateToModify();
        switch ($this->State)
        {
            case dsEdit:
            case dsInsert:
            {
                $this->callevent("onbeforepost",array());
                $this->checkOperation("internalPost", "onposterror");
                $this->freeFieldBuffers();
                $this->State = dsBrowse;
                $this->callevent("onafterpost",array());
                break;
            }
        }
    }

    /**
     * Stops the current operation to modify the dataset without applying any change, effectively rejecting those
     * changes.
     *
     * <code>
     * $dataSet->edit();
     * $dataSet->Name = 'John';
     * $dataSet->cancel(); // The name of the current record is not changed to 'John'.
     * </code>
     *
     * @see append(), edit(), insert()
     */
    function cancel()
    {
        switch($this->State)
        {
            case dsEdit:
            case dsInsert:
            {
                $this->callEvent("onbeforecancel",array());
                $this->internalCancel();
                $this->freeFieldBuffers();
                $this->State = dsBrowse;
                $this->callEvent("onaftercancel",array());

                break;
            }
        }
    }

    /**
     * Calls the given $method that performs an operation on the DataSet.
     *
     * @param string $method Name of the method to be called (e.g. "internalPost").
     * @param string $event  Name of the event to be triggered in case of error (e.g. "onerror").
     *
     * @internal
     */
    function checkOperation($method, $event)
    {
        try
        {
          $this->$method();
        }
        catch (EDatabaseError $exception)
        {
            $action = daFail;
            $action = $this->callEvent($event, array(
                'Exception' => $exception, 
                'Action'    => $action
            ));
            
            if ($action === null || $action === daFail)    
                throw $exception;
            
            if ($action === daAbort)
                Abort();
        }
    }


    // Events

    protected $_onbeforeopen=null;
    /**
     * Triggered right before the dataset is opened.
     */
    function readOnBeforeOpen() { return $this->_onbeforeopen; }
    function writeOnBeforeOpen($value) { $this->_onbeforeopen=$value; }
    function defaultOnBeforeOpen() { return null; }

    protected $_onafteropen=null;
    /**
     * Triggered right after the dataset has been opened.
     */
    function readOnAfterOpen() { return $this->_onafteropen; }
    function writeOnAfterOpen($value) { $this->_onafteropen=$value; }
    function defaultOnAfterOpen() { return null; }

    protected $_onbeforeclose=null;
    /**
     * Triggered right before the dataset has been closed.
     */
    function readOnBeforeClose() { return $this->_onbeforeclose; }
    function writeOnBeforeClose($value) { $this->_onbeforeclose=$value; }
    function defaultOnBeforeClose() { return null; }

    protected $_onafterclose=null;
    /**
     * Triggered right after the dataset has been closed.
     */
    function readOnAfterClose() { return $this->_onafterclose; }
    function writeOnAfterClose($value) { $this->_onafterclose=$value; }
    function defaultOnAfterClose() { return null; }

    protected $_onbeforeinsert=null;
    /**
     * Triggered when an insert or append operation is about to start.
     */
    function readOnBeforeInsert() { return $this->_onbeforeinsert; }
    function writeOnBeforeInsert($value) { $this->_onbeforeinsert=$value; }
    function defaultOnBeforeInsert() { return null; }

    protected $_onafterinsert=null;
    /**
     * Triggered after an insert or append operation has started.
     */
    function readOnAfterInsert() { return $this->_onafterinsert; }
    function writeOnAfterInsert($value) { $this->_onafterinsert=$value; }
    function defaultOnAfterInsert() { return null; }

    protected $_onbeforeedit=null;
    /**
     * Triggered when an edit operation is about to start.
     */
    function readOnBeforeEdit() { return $this->_onbeforeedit; }
    function writeOnBeforeEdit($value) { $this->_onbeforeedit=$value; }
    function defaultOnBeforeEdit() { return null; }

    protected $_onafteredit=null;
    /**
     * Triggered after an edit operation has started.
     */
    function readOnAfterEdit() { return $this->_onafteredit; }
    function writeOnAfterEdit($value) { $this->_onafteredit=$value; }
    function defaultOnAfterEdit() { return null; }

    protected $_onbeforedelete=null;
    /**
     * Triggered when the current record is about to be deleted.
     */
    function readOnBeforeDelete() { return $this->_onbeforedelete; }
    function writeOnBeforeDelete($value) { $this->_onbeforedelete=$value; }
    function defaultOnBeforeDelete() { return null; }

    protected $_onafterdelete=null;
    /**
     * Triggered after a successful deletion of what was the current record.
     */
    function readOnAfterDelete() { return $this->_onafterdelete; }
    function writeOnAfterDelete($value) { $this->_onafterdelete=$value; }
    function defaultOnAfterDelete() { return null; }

    protected $_onbeforecancel=null;
    /**
     * Triggered when the unsaved changes to the current record are about to be reverted.
     */
    function readOnBeforeCancel() { return $this->_onbeforecancel; }
    function writeOnBeforeCancel($value) { $this->_onbeforecancel=$value; }
    function defaultOnBeforeCancel() { return null; }

    protected $_onaftercancel=null;
    /**
     * Triggered once the unsaved changes to the current record have been successfully reverted.
     */
    function readOnAfterCancel() { return $this->_onaftercancel; }
    function writeOnAfterCancel($value) { $this->_onaftercancel=$value; }
    function defaultOnAfterCancel() { return null; }

    protected $_onbeforepost=null;
    /**
     * Triggered when the unsaved changes to the current record are about to be saved to the dataset.
     */
    function readOnBeforePost() { return $this->_onbeforepost; }
    function writeOnBeforePost($value) { $this->_onbeforepost=$value; }
    function defaultOnBeforePost() { return null; }

    protected $_onafterpost=null;
    /**
     * Triggered once the unsaved changes to the current record have been successfully saved to the dataset.
     */
    function readOnAfterPost() { return $this->_onafterpost; }
    function writeOnAfterPost($value) { $this->_onafterpost=$value; }
    function defaultOnAfterPost() { return null; }

    protected $_ondeleteerror=null;
    /**
     * Triggered whenever an error occurs while deleting the current record.
     */
    function readOnDeleteError() { return $this->_ondeleteerror; }
    function writeOnDeleteError($value) { $this->_ondeleteerror=$value; }
    function defaultOnDeleteError() { return null; }

    protected $_onposterror=null;
    /**
     * Triggered whenever an error occurs while saving to the dataset the unsaved changes to the current record.
     */
    function readOnPostError() { return $this->_onposterror; }
    function writeOnPostError($value) { $this->_onposterror=$value; }
    function defaultOnPostError() { return null; }


    // METHODS TO BE INHERITED

    /**
     * Fills the dataset with the records from its data provider.
     *
     * @see internalClose(), open()
     *
     * @internal
     */
    function internalOpen(){}

    /**
     * Empties the dataset from any data.
     *
     * @see internalOpen(), close()
     *
     * @internal
     */
    function internalClose(){}

    /**
     * Takes the dataset into insert/append mode.
     *
     * @see insert(), append()
     *
     * @internal
     */
    function internalInsert(){}

    /**
     * Takes the dataset into edit mode.
     *
     * @see edit()
     *
     * @internal
     */
    function internalEdit(){}

    /**
     * Deletes the current record from the data provider.
     *
     * @see delete()
     *
     * @internal
     */
    function internalDelete(){}

    /**
     * Points the record cursor to the first record in the data provider.
     *
     * @see first()
     *
     * @internal
     */
    function internalFirst(){}

    /**
     * Points the record cursor to the last record in the data provider.
     *
     * @see last()
     *
     * @internal
     */
    function internalLast(){}

    /**
     * Moves the record cursor the given number of positions in the data provider. Negative values move the cursor
     * backwards.
     *
     * @see moveBy()
     *
     * @param int $positions
     *
     * @internal
     */
    function internalMoveBy($positions){}

    /**
     * Posts to the data provider the changes made to the dataset.
     *
     * @see post()
     *
     * @internal
     */
    function internalPost(){}

    /**
     * Cancels the posting to the data provider of the changes made to the dataset.
     *
     * @see cancel()
     *
     * @internal
     */
    function internalCancel(){}

    /**
     * Updates the field buffer, the buffer containing the unsaved changes to the current record.
     *
     * This method is called whenever the record cursor is moved to a different record, so the unsaved changes to the
     * previously pointed record are ignored and replaced by those of the currently pointed record.
     *
     * @internal
     */
    function updateFieldBuffer(){}

    /**
     * Points the record cursor to the next record.
     *
     * @return bool Returns false on EOF, true otherwise.
     */
    function NextRecord()
    {
        return true;
    }

    /**
     * Points the record cursor to the previous record.
     *
     * @return bool Returns false on BOF, true otherwise.
     */
    function PriorRecord()
    {
        return true;
    }


    // HELPER FUNCTIONS

    /**
     * Raises an exception if the dataset is not bidirectional, but unidirectional.
     *
     * @throws EDatabaseError
     *
     * @internal
     */
    function checkBidirectional()
    {
        if($this->isUnidirectional())
        {
            DatabaseError(_("Operation not allowed on a unidirectional dataset"), $this);
        }
    }

    /**
     * Returns true if the dataset is unidirectional, false otherwise.
     *
     * @return bool
     *
     * @internal
     */
    function isUnidirectional()
    {
        return true;
    }

    /**
     * Raises an exception if the dataset is not open.
     *
     * @throws EDatabaseError
     *
     * @internal
     */
    function checkActive()
    {
        if ($this->State == dsInactive)
        {
            DatabaseError(_("Cannot perform this operation on a closed dataset"), $this);
        }
    }

    /**
     * Raises an exception if the dataset is read-only.
     *
     * @throws EDatabaseError
     *
     * @internal
     */
    function checkCanModify()
    {
        if (!$this->CanModify)
        {
            DatabaseError(_("Cannot modify a read-only dataset", $this));
        }
    }

    /**
     * Raises an exception if the dataset is not in append/edit/insert mode.
     *
     * @throws EDatabaseError
     *
     * @internal
     */
    function checkStateToModify()
    {
        if (($this->State!=dsEdit) && ($this->State!=dsInsert))
        {
            DatabaseError(_("Dataset not in edit or insert mode"), $this);
        }
    }

    /**
     * If the dataset is open and the dataset is in append/edit/insert mode, it posts any unsaved changes to the data
     * provider, and takes the dataset back into browse mode.
     *
     * If the dataset is closed, it raises an exception.
     *
     * @throws EDatabaseError
     *
     * @internal
     */
    function checkBrowseMode()
    {
        $this->checkActive();

        switch($this->State)
        {
            case dsEdit:
            case dsInsert:
            {
                if ($this->Modified)
                {
                    $this->post();
                }
                else
                {
                    $this->cancel();
                }

                break;
            }
        }
    }

    /**
     * Prepares the dataset to enter insert/append mode.
     */
    function beginInsertAppend()
    {
        $this->checkBrowseMode();
        $this->checkCanModify();
        $this->callEvent('onbeforeinsert', array());
        $this->State = dsInsert;
    }

    /**
     * Performs some steps that are needed after setting the dataset to either the append or the insert modes.
     *
     * @internal
     */
    function endInsertAppend()
    {
        try
        {
            $this->callEvent('onnewrecord',array());
        }
        catch (Exception $e)
        {
            $this->freeFieldBuffers();
            $this->State = dsBrowse;
            throw $e;
        }
        $this->_modified = false;
        $this->callEvent('onafterinsert',array());
    }


    /**
     * Empties the buffer for the current record.
     *
     * @internal
     */
    function clearBuffers()
    {
        // clear the fieldbuffer
        $this->_fieldbuffer = array();
    }


    /**
     * Empties (sets to NULL) the value of any field in the current record.
     *
     * @internal
     */
    function initRecord()
    {
        // clear the field data
        if (is_array($this->_fields))
        {
            foreach($this->_fields as $key => $value)
            {
                $this->_fields[$key] = NULL;
            }
        }
    }

    /**
     * Empties the buffer for the current record.
     *
     * @internal
     */
    function freeFieldBuffers()
    {
        $this->_fieldbuffer = array();
    }


}


/**
 * Component to associate a dataset to one or more data-aware components.
 *
 * Data-aware components use this component as a bridge between them and the dataset component they are going to
 * retrieve data from.
 *
 * @link wiki://Server_Database_Connections
 */
class Datasource extends Component
{
        protected $_dataset;

        // Documented in the parent.
        function __construct($aowner=null)
        {
            //Calls inherited constructor
            parent::__construct($aowner);
        }

        function loaded()
        {
            parent::loaded();
            $this->setDataSet($this->_dataset);
        }

        /**
         * Dataset.
         */
        function getDataSet() { return $this->_dataset; }
        function setDataSet($value)
        {
            $this->_dataset=$this->fixupProperty($value);
        }
}


