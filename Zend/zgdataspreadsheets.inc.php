<?php

/**
 * Zend/zgdataphotos.inc.php
 * 
 * Defines Zend Framework Google Spreadsheets component.
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
use_unit("Zend/framework/library/Zend/Gdata/Spreadsheets.php");

/**
 * Component to manage the Google Spreadsheets service.
 * 
 * @link        http://framework.zend.com/manual/en/zend.gdata.spreadsheets.html Zend Framework Documentation
 */
class ZGDataSpreadsheets extends Component
{
   private $_spreadsheet = null;
   private $_visibility = 'public';

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
   }
   
   
   protected $_applicationname = '';

   /**
    * Name of your application.
    *
    * @return string
    */
   function getApplicationName()    {return $this->_applicationname;}
   function setApplicationName($value)    {$this->_applicationname = $value;}
   function defaultApplicationName()    {return '';}
   
   
   protected $_onauthentication = null;

   /**
    * Triggered for user authentication against Google Spreadsheets service.
    * 
    * This event is triggered as soon as this component is loaded.
    *
    * The params array for the event handler will contain the following information:
    * <ul>
    *   <li>service: Zend_gdata_spreadsheets::AUTH_SERVICE_NAME.</li>
    *   <li>url: Zend_gdata_spreadsheets::SPREADSHEETS_FEED_URI.</li>
    *   <li>appname: $_applicationname.</li>
    * </ul>
    *
    * The event handler should return a boolean value. If true is returned, the component will initialize
    * $_spreadsheet and set $_visibility to 'private'. If false is returned,
    * $_spreadsheet will also be initialized, but $_visibility will be set to
    * 'public' instead.
    *
    * @return string
    */
   function getOnAuthentication()    {return $this->_onauthentication;}
   function setOnAuthentication($value)    {$this->_onauthentication = $value;}
   function defaultOnAuthentication()    {return null;}
   
   // Documented in the parent.
   function loaded()
   {
      if ( !isMainPage() ) return;
      
      if($this->_onauthentication != null)
      {
         $aux = $this->callEvent('onauthentication', array('service'=>Zend_gdata_spreadsheets::AUTH_SERVICE_NAME, 'url'=>Zend_gdata_spreadsheets::SPREADSHEETS_FEED_URI,'appname'=>$this->_applicationname));

         if($aux)
         {
            $this->_spreadsheet = new Zend_Gdata_Spreadsheets($aux,$this->_applicationname);
            $this->_visibility = 'private';

         }
         else
         {
            $this->_spreadsheet = new Zend_Gdata_Spreadsheets(null,$this->_applicationnames);
            $this->_visibility = 'public';
         }
      }
      else
      {
         $this->_spreadsheet = new Zend_Gdata_Spreadsheets(null,$this->_applicationname);
         $this->_visibility = 'public';
      }

   }

   /**
    * Retrieves the complete list of spreadsheets available for current user.
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_SpreadsheetEntry
    * for all spreadsheets available for current user.
    *
    * @return   boolean|Zend_Gdata_Spreadsheets_SpreadsheetEntry
    */
   function retrieveSpreadsheetsList()
   {
      if($this->_spreadsheet != null)
      {
         return $this->_spreadsheet->getSpreadsheetFeed();
      }
   }

   /**
    * Retrieves all worksheets from a spreadsheet.
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_WorksheetEntry
    * for all worksheets from given spreadsheet.
    *
    * @param    string|Zend_Gdata_Spreadsheets_SpreadsheetEntry $spreadsheet    Spreadsheet identifier or object.
    * @return   boolean|Zend_Gdata_Spreadsheets_WorksheetEntry
    */
   function retrieveWorksheets($spreadsheet)
   {
      if($this->_spreadsheet != null)
      {
         if($spreadsheet instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry)
         {
            return $this->_spreadsheet->getWorksheetFeed($spreadsheet);
         }
         else
         {
            $newQuery = new Zend_Gdata_Spreadsheets_DocumentQuery();

            if( ! strpos($spreadsheet, '/'))
            {
               $newQuery->setSpreadsheetKey($spreadsheet);
            }
            else
            {
               $id_parts = explode('/', $spreadsheet);
               $id = end($id_parts);
               $newQuery->setSpreadsheetKey($id);
            }

            return $this->_spreadsheet->getWorksheetFeed($newQuery);
         }
      }
      else
      {
         return false;
      }

   }

   /**
    * Creates a new worksheet.
    *
    * Only parameter is a key-value array with the following optional pairs:
    * <ul>
    *   <li>title: Worksheet title (string).</li>
    *   <li>rowCount: Amount of rows (integer).</li>
    *   <li>colCount: Amount of columns (integer).</li>
    *   <li>spreadsheet: Spreadsheet identifier or object (string or Zend_Gdata_Spreadsheets_SpreadsheetEntry).</li>
    * </ul>
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_WorksheetEntry
    * for your new worksheet.
    *
    * @param    array   $params Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_WorksheetEntry
    */
   function createWorksheet($params)
   {
      if($this->_spreadsheet != null)
      {
         $newWorksheet = new Zend_Gdata_Spreadsheets_WorksheetEntry();

         if(isset($params['title']))
         {
            $newWorksheet->setTitle($this->_spreadsheet->newTitle($params['title']));
         }

         if(isset($params['rowCount']))
         {
            $newWorksheet->setRowCount($this->_spreadsheet->newRowCount($params['rowCount']));
         }

         if(isset($params['colCount']))
         {
            $newWorksheet->setColumnCount($this->_spreadsheet->newColCount($params['colCount']));
         }

         $url = '';
         if(isset($params['spreadsheet']))
         {
            if($params['spreadsheet'] instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry)
            {
               $url = $params['spreadsheet']->getLink(Zend_Gdata_spreadsheets::WORKSHEETS_FEED_LINK_URI)->href;
            }
            else
            {
               $id_parts = explode('/', $params['spreadsheet']);
               $id = end($id_parts);
               $url = 'https://spreadsheets.google.com/feeds/worksheets/' . $id . '/private/full';
            }
         }
         return $this->_spreadsheet->insertEntry($newWorksheet, $url);
      }
      else
      {
         return false;
      }
   }

   /**
    * Modifies a worksheet.
    *
    * Only parameter is a key-value array with the following pairs:
    * <ul>
    *   <li>title: Worksheet title (string).</li>
    *   <li>rowCount: Amount of rows (integer).</li>
    *   <li>colCount: Amount of columns (integer).</li>
    *   <li>worksheet: Worksheet identifier or object (string or Zend_Gdata_Spreadsheets_WorksheetEntry).</li>
    * </ul>
    *
    * All pairs are optional, but worksheet.
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_WorksheetEntry
    * for your modified worksheet.
    *
    * @param    array   $params Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_WorksheetEntry
    */
   function modifyWorksheet($params)
   {
      if($this->_spreadsheet != null)
      {
         if(isset($params['worksheet']))
         {
            $worksheet_text = $params['worksheet'];
            if($worksheet_text instanceof Zend_Gdata_Spreadsheets_WorksheetEntry)
            {
               $worksheet = $worksheet_text;
            }
            else
            {
               $worksheet = $this->_spreadsheet->GetWorksheetEntry($worksheet_text);
            }

            if(isset($params['title']))
            {
               $worksheet->setTitle($this->_spreadsheet->newTitle($params['title']));
            }

            if(isset($params['rowCount']))
            {
               $worksheet->setRowCount($this->_spreadsheet->newRowCount($params['rowCount']));
            }

            if(isset($params['colCount']))
            {
               $worksheet->setColumnCount($this->_spreadsheet->newColCount($params['colCount']));
            }

            $worksheet->save();

            return $worksheet;
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

   /**
    * Deletes a worksheet.
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    * If it is set, it will return nothing (void).
    *
    * @param    string|Zend_Gdata_Spreadsheets_WorksheetEntry   $worksheet      Worksheet identifier or object.
    * @return   boolean|void
    */
   function deleteWorksheet($worksheet)
   {
      if($this->_spreadsheet != null)
      {
         if($worksheet instanceof Zend_Gdata_Spreadsheets_WorksheetEntry)
         {
            return $worksheet->delete();
         }
         else
         {
            $aux = $this->_spreadsheet->getWorksheetEntry($worksheet);
            return $aux->delete();
         }
      }
      else
      {
         return false;
      }
   }

   /**
    * Retrieves the list of rows from a worksheet, without the headers.
    *
    * Only parameter is a key-value array with the following optional pairs:
    * <ul>
    *   <li>spreadsheet: Spreadsheet identifier or object (string or Zend_Gdata_Spreadsheets_SpreadsheetEntry).</li>
    *   <li>worksheet: Worksheet identifier or object (string or Zend_Gdata_Spreadsheets_WorksheetEntry).</li>
    *   <li>orderby: Column that will define the order in which columns will be retrieved
    *   (string). It can be either the position of the column, or the name prefixed with 'column:'.
    *   For example: '3' or 'column:LastName'.</li>
    *   <li>reverse: Whether or not to reverse the order on which rows are retrieved (boolean).</li>
    *   <li>query: Structured query on the full text in the worksheet (string). It must
    *   follow this syntax: [columnName][binaryOperator][value]. Where [columnName] is the name of
    *   a column, and [value] the value to consider. Check Google Documentation (http://code.google.com/intl/es-ES/apis/spreadsheets/data/2.0/reference.html#ListParameters)
    *   for a list of binary operators available to replace [binaryOperator] in the query.</li>
    * </ul>
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_ListEntry for
    * matching rows.
    *
    * @param    array   $params Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_ListEntry
    */
   function retrieveListRows($params)
   {
      if($this->_spreadsheet != null)
      {
         $query = new Zend_Gdata_Spreadsheets_ListQuery();
         if(isset($params['spreadsheet']))
         {
            $aux = $params['spreadsheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_spreadsheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_spreadsheet = end($id_parts);
               }
               else
               {
                  $id_spreadsheet = $aux;
               }
            }
            $query->setSpreadsheetKey($id_spreadsheet);
         }

         if(isset($params['worksheet']))
         {
            $aux = $params['worksheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_WorksheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_worksheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_worksheet = end($id_parts);
               }
               else
               {
                  $id_worksheet = $aux;
               }
            }

            $query->setWorksheetId($id_worksheet);
         }

         if(isset($params['orderby']))
         {
            $query->setOrderBy($params['orderby']);
         }

         if(isset($params['reverse']))
         {
            $query->setReverse($params['reverse']);
         }

         if(isset($params['query']))
         {
            $query->setQuery($params['query']);
         }

         $query->setVisibility($this->_visibility);

         return $this->_spreadsheet->getListFeed($query);
      }
      else
      {
         return false;
      }
   }

   /**
    * Adds a new row to a worksheet.
    *
    * Only parameter is a key-value array with the following pairs:
    * <ul>
    *   <li>spreadsheet: Spreadsheet identifier or object (string or Zend_Gdata_Spreadsheets_SpreadsheetEntry).</li>
    *   <li>worksheet: Worksheet identifier or object (string or Zend_Gdata_Spreadsheets_WorksheetEntry).</li>
    *   <li>row: New row data (array of key-value pairs, items type depends on columns type, and the key will be the column name, in lowercase and without whitespaces).</li>
    * </ul>
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_ListEntry for
    * the new row.
    *
    * @param    array   $params Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_ListEntry
    */
   function addRow($params)
   {
      if($this->_spreadsheet != null)
      {
         if(isset($params['spreadsheet']))
         {
            $aux = $params['spreadsheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_spreadsheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_spreadsheet = end($id_parts);
               }
               else
               {
                  $id_spreadsheet = $aux;
               }
            }
         }

         if(isset($params['worksheet']))
         {
            $aux = $params['worksheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_WorksheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_worksheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_worksheet = end($id_parts);
               }
               else
               {
                  $id_worksheet = $aux;
               }
            }
         }

         $row = '';

         if(isset($params['row']) && is_array($params['row']))
         {
            $row = $params['row'];
         }

         if($row != '' && $id_spreadsheet != '')
         {
            return $this->_spreadsheet->insertRow($row, $id_spreadsheet, $id_worksheet);
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

   /**
    * Modifies a row.
    *
    * Second parameter, $rowData, will be an array of key-value pairs. Items type depends on
    * columns type. Keys will be column names, in lowercase and without whitespaces.
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_ListEntry for
    * the new row.
    *
    * @param    string|Zend_Gdata_Spreadsheets_ListEntry        $row            Row identifier or object.
    * @param    array                                           $rowData        Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_ListEntry
    */
   function modifyRow($row, $rowData)
   {
      if($this->_spreadsheet != null)
      {
         if($row instanceof Zend_Gdata_Spreadsheets_ListEntry)
         {
            $row_object = $row;
         }
         else
         {
            $row_object = new Zend_Gdata_Spreadsheets_ListEntry($row);
         }
         return $this->_spreadsheet->updateRow($row_object, $rowData);
      }
      else
      {
         return false;
      }
   }

   /**
    * Deletes a row.
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    * If it is set, it will return nothing (void).
    *
    * @param    string|Zend_Gdata_Spreadsheets_ListEntry        $row            Row identifier or object.
    * @return   boolean|void
    */
   function deleteRow($row)
   {
      if($this->_spreadsheet != null)
      {
         if($row instanceof Zend_Gdata_Spreadsheets_ListEntry)
         {
            $row_object = $row;
         }
         else
         {
            $row_object = new Zend_Gdata_Spreadsheets_ListEntry($row);
         }

         return $row_object->delete();
      }
      else
      {
         return false;
      }
   }

   /**
    * Retrieves cells from a worksheet.
    *
    * Only parameter is a key-value array with the following pairs:
    * <ul>
    *   <li>spreadsheet: Spreadsheet identifier or object (string or Zend_Gdata_Spreadsheets_SpreadsheetEntry).</li>
    *   <li>worksheet: Worksheet identifier or object (string or Zend_Gdata_Spreadsheets_WorksheetEntry).</li>
    *   <li>min-row: Minimum row number (integer).</li>
    *   <li>max-row: Maximum row number (integer).</li>
    *   <li>min-col: Minimum column number (integer).</li>
    *   <li>max-col: Maximum column number (integer).</li>
    *   <li>range: Cell code or colon-separated range of cells (string). For example: 'A1' or 'D1:F3'.
    *   Check Google Documentation (http://code.google.com/intl/es-ES/apis/spreadsheets/data/2.0/reference.html#CellParameters)
    *   for additional information.</li>
    *   <li>return-empty: Whether or not to retrieve also empty cells (boolean).</li>
    * </ul>
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_CellEntry for
    * matching cells.
    *
    * @param    array   $params Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_CellEntry
    */
   function retrieveListCells($params)
   {
      if($this->_spreadsheet != null)
      {

         $query = new Zend_Gdata_Spreadsheets_CellQuery();
         if(isset($params['spreadsheet']))
         {
            $aux = $params['spreadsheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_spreadsheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_spreadsheet = end($id_parts);
               }
               else
               {
                  $id_spreadsheet = $aux;
               }
            }
            $query->setSpreadsheetKey($id_spreadsheet);
         }
         if(isset($params['worksheet']))
         {
            $aux = $params['worksheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_WorksheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_worksheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_worksheet = end($id_parts);
               }
               else
               {
                  $id_worksheet = $aux;
               }
            }
            $query->setWorksheetId($id_worksheet);
         }

         if(isset($params['min-row']))
         {
            $query->setMinRow($param['min-row']);
         }

         if(isset($params['max-row']))
         {
            $query->setMaxRow($params['max-row']);
         }

         if(isset($params['min-col']))
         {

            $query->setMinCol($params['min-col']);
         }

         if(isset($params['max-col']))
         {
            $query->setMaxCol($params['max-col']);
         }

         if(isset($params['range']))
         {
            $query->setRange($params['range']);
         }

         if(isset($params['return-empty']))
         {
            $query->setReturnEmpty($params['return-empty']);
         }

         $feed = $this->_spreadsheet->getCellFeed($query);
         return $feed;
      }
      else
      {
         return false;
      }
   }

   /**
    * Modifies the value in a cell.
    *
    * Only parameter is a key-value array with the following pairs:
    * <ul>
    *   <li>spreadsheet: Spreadsheet identifier or object (string or Zend_Gdata_Spreadsheets_SpreadsheetEntry).</li>
    *   <li>worksheet: Worksheet identifier or object (string or Zend_Gdata_Spreadsheets_WorksheetEntry).</li>
    *   <li>row: Row number (integer).</li>
    *   <li>col: Column number (integer).</li>
    *   <li>value: New value for the cell.</li>
    * </ul>
    * 
    * If $_spreadsheet is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Spreadsheets_CellEntry for
    * modified cell.
    *
    * @param    array   $params Parameters.
    * @return   boolean|Zend_Gdata_Spreadsheets_CellEntry
    */
   function modifyCell($params)
   {
      if($this->_spreadsheet != null)
      {
         $row = '';
         $col = '';
         $value = '';
         $id_spreadsheet = '';
         $id_worksheet = '';

         if(isset($params['row']))
         {
            $row = $params['row'];
         }

         if(isset($params['col']))
         {
            $col = $params['col'];
         }

         if(isset($params['value']))
         {
            $value = $params['value'];
         }

         if(isset($params['spreadsheet']))
         {
            $aux = $params['spreadsheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_spreadsheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_spreadsheet = end($id_parts);
               }
               else
               {
                  $id_spreadsheet = $aux;
               }
            }
         }
         if(isset($params['worksheet']))
         {
            $aux = $params['worksheet'];
            if($aux instanceof Zend_Gdata_Spreadsheets_WorksheetEntry)
            {
               $id_aux = $aux->id->text;
               $id_parts = explode('/', $id_aux);
               $id_worksheet = end($id_parts);
            }
            else
            {
               if(strpos($aux, '/'))
               {
                  $id_parts = explode('/', $aux);
                  $id_worksheet = end($id_parts);
               }
               else
               {
                  $id_worksheet = $aux;
               }
            }
         }

         if($id_spreadsheet != '' && $row != '' && $col != '')
         {
            return $this->_spreadsheet->updateCell($row, $col, $value, $id_spreadsheet, $id_worksheet);
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