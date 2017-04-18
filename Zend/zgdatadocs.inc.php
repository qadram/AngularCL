<?php

/**
 * Zend/zgdatadocs.inc.php
 * 
 * Defines Zend Framework Google Docs component.
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
use_unit("Zend/framework/library/Zend/Gdata/Docs.php");
use_unit("Zend/framework/library/Zend/Gdata/Docs/Query.php");

// Document Projections

/**
 * Document full projection.
 * 
 * @const       pdFull
 */
define('pdFull', 'pdFull');

/**
 * Document composite projection.
 * 
 * @const       pdComposite
 */
define('pdComposite', 'pdComposite');

/**
 * Document basic projection.
 * 
 * @const       pcBasic
 */
define('pdBasic', 'pdBasic');

/**
 * Component to manage Google Docs service.
 * 
 * @link        http://framework.zend.com/manual/en/zend.gdata.docs.html Zend Framework Documentation
 */
class ZGDataDocs extends Component
{

   // Zend Google Docs

   /**
    * Zend Framework Google Docs instance.
    *
    * @var      Zend_Gdata_Docs
    */
   private $_docs = null;

   // Visibility

   /**
    * Whether user is or not authenticated.
    *
    * It can be either 'public' (not authenticated) or 'private' (authenticated).
    *
    * @var      string
    */
   private $_visibility = 'public';

   // Application Name

   /**
    * Name of your application.
    *
    * @var      string
    */
   protected $_applicationname = '';

   /**
    * Name of your application.
    */
   function getApplicationName()    {return $this->_applicationname;}

   /**
    * Setter method for $_applicationname.
    *
    * @param    string  $value
    */
   function setApplicationName($value)    {$this->_applicationname = $value;}

   /**
    * Getter for $_applicationname's default value.
    *
    * @return   string  Empty string
    */
   function defaultApplicationName()    {return '';}

   // Constructor

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
   }

   // Loaded

   // Documented in the parent.
   function loaded()
   {
      if ( !isMainPage() ) return;
      
      if($this->_onauthentication != null)
      {

         $aux = $this->callEvent('onauthentication', array('service'=>Zend_Gdata_Docs::AUTH_SERVICE_NAME, 'url'=>Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI,'appname'=>$this->_applicationname));

         if($aux)
         {
            $this->_docs = new Zend_Gdata_Docs($aux,$this->_applicationname);
            $this->_visibility = 'private';
         }
         else
         {
            $this->_docs = new Zend_Gdata_Docs(null,$this->_applicationname);
            $this->_visibility = 'public';
         }
      }
      else
      {
         $this->_docs = new Zend_Gdata_Docs(null,$this->_applicationname);
         $this->_visibility = 'public';
      }
   }

   // Documents Projection

   /**
    * Amount and format of the data to be retrieved from the server.
    *
    * @var      string
    */
   protected $_projectiondocuments = pdFull;

   /**
    * Amount and format of the data to be retrieved from the server.
    */
   function getProjectionDocuments()    {return $this->_projectiondocuments;}

   /**
    * Setter method for $_projectiondocuments.
    *
    * @param    string  $value
    */
   function setProjectionDocuments($value)    {$this->_projectiondocuments = $value;}

   /**
    * Getter for $_projectiondocuments's default value.
    *
    * @return   string  pdFull
    */
   function defaultProjectionDocuments()    {return pdFull;}

   // On Authentication

   /**
    * Event triggered for user authentication against Google Docs service.
    * 
    * This event is triggered as soon as this component is loaded.
    *
    * This property should either contain the name of the function to be run when the event is
    * triggered (without brackets), or be set to null.
    *
    * If the name of a function is provided, such a function should expect the following key-value
    * pairs in the parameters array, passed to the function as its second parameter:
    * <ul>
    *   <li>service: Zend_Gdata_Docs::AUTH_SERVICE_NAME.</li>
    *   <li>url: Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI.</li>
    *   <li>appname: $_applicationname.</li>
    * </ul>
    *
    * It is also expected to return a boolean value. If true is returned, component will initialize
    * $_docs and set $_visibility to 'private'. If false is returned,
    * $_docs will also be initialized, but $_visibility will be set to 'public'
    * instead.
    *
    * @var      string
    */
   protected $_onauthentication = null;

   /**
    * Event triggered for user authentication against Google Docs service.
    * 
    * This event is triggered as soon as this component is loaded.
    *
    * This property should either contain the name of the function to be run when the event is
    * triggered (without brackets), or be set to null.
    *
    * If the name of a function is provided, such a function should expect the following key-value
    * pairs in the parameters array, passed to the function as its second parameter:
    * <ul>
    *   <li>service: Zend_Gdata_Docs::AUTH_SERVICE_NAME.</li>
    *   <li>url: Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI.</li>
    *   <li>appname: $_applicationname.</li>
    * </ul>
    *
    * It is also expected to return a boolean value. If true is returned, component will initialize
    * $_docs and set $_visibility to 'private'. If false is returned,
    * $_docs will also be initialized, but $_visibility will be set to 'public'
    * instead.
    */
   function getOnAuthentication()    {return $this->_onauthentication;}

   /**
    * Setter method for $_onauthentication.
    *
    * @param    string  $value
    */
   function setOnAuthentication($value)    {$this->_onauthentication = $value;}

   /**
    * Getter for $_onauthentication's default value.
    *
    * @return   string  Null
    */
   function defaultOnAuthentication()    {return null;}

   /**
    * Retrieves the list of user documents.
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get a list of current
    * user documents.
    *
    * @return   boolean|Zend_Gdata_Docs_DocumentListFeed
    */
   function retrieveDocumentsList()
   {
      if($this->_docs != null)
      {
         return $this->_docs->getDocumentListFeed();
      }
      else
      {
         return false;
      }
   }

   /**
    * Retrieves a document.
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Docs_DocumentListEntry for
    * requested document.
    *
    * @param    string  $id_document    Document identifier.
    * @param    string  $doc_type       Document type.
    * @return   boolean|Zend_GData_Docs_DocumentListEntry
    */
   function retrieveDocument($id_document, $doc_type)
   {
      if($this->_docs!=null)
      {
       return $this->_docs->getDoc($id_document,$doc_type);
      }
      else
      {
          return false;
      }
   }

   /**
    * Creates a new folder.
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_App_Entry for new folder.
    *
    * @param    string  $folderName     Folder name.
    * @return   boolean|Zend_Gdata_App_Entry
    */
   function createFolder($folderName)
   {
      if($this->_docs != null)
      {
         return $this->_docs->createFolder($folderName);
      }
      else
      {
         return false;
      }
   }

   /**
    * Uploads a document to Google Docs.
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_App_Entry for uploaded document.
    *
    * @param    string  $filename       Path to document file.
    * @param    string  $title          Document title.
    * @return   boolean|Zend_Gdata_App_Entry
    */
   function uploadDocument($filename, $title)
   {
      if($this->_docs != null)
      {
         $filenameParts = explode('.', $filename);
         $fileExtension = end($filenameParts);

         $newDocument = $this->_docs->uploadFile($filename, $title, Zend_Gdata_Docs::lookupMimeType($fileExtension));
         return $newDocument;
      }
      else
      {
         return false;
      }
   }

   /**
    * Retrieves user word documents (as opposed to spreadsheets or presentations).
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Docs_DocumentListFeed.
    *
    * @return boolean|Zend_Gdata_Docs_DocumentListFeed
    */
   function retrieveWordDocuments()
   {
      if($this->_docs != null)
      {
         return $this->_docs->getDocumentListFeed(Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI . '/-/document');
      }
      else
      {
         return false;
      }
   }

   /**
    * Retrieves user spreadsheets.
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Docs_DocumentListFeed.
    *
    * @return boolean|Zend_Gdata_Docs_DocumentListFeed
    */
   function retrieveSpreadsheetDocuments()
   {
      if($this->_docs != null)
      {
         return $this->_docs->getDocumentListFeed(Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI . '/-/spreadsheet');
      }
      else
      {
         return false;
      }

   }

   /**
    * Retrieves user presentations.
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Docs_DocumentListFeed.
    *
    * @return boolean|Zend_Gdata_Docs_DocumentListFeed
    */
   function retrievePresentationDocuments()
   {
      if($this->_docs != null)
      {
         return $this->_docs->getDocumentListFeed(Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI . '/-/presentation');
      }
      else
      {
         return false;
      }

   }

   /**
    * Searches documents.
    *
    * Search criteria is defined through the $params array in the call, where the following
    * key-value pairs can be defined:
    * <ul>
    *   <li>title: Document title (string).</li>
    *   <li>titleExact: Whether the title must be or not an exact match (boolean).</li>
    *   <li>query: Text to search within files.</li>
    * </ul>
    * 
    * If $_docs is not set (is null), this method will return a boolean value, false.
    *
    * If everything works, you will get an instance of Zend_Gdata_Docs_DocumentListFeed.
    *
    * @param    array   $params Search criteria.
    * @return boolean|Zend_Gdata_Docs_DocumentListFeed
    */
   function searchDocument($params)
   {
      if($this->_docs != null)
      {
         $query = new Zend_Gdata_Docs_Query();

         $query->setVisibility($this->_visibility);

         $projection = '';
         switch($this->_projectiondocuments)
         {
            case pdFull:
               $projection = 'full';
               break;
            case pdComposite:
               $projection = 'composite';
               break;
            case pdBasic:
               $projection = 'basic';
               break;
         }
         $query->setProjection($projection);

         if(isset($params['title']))
         {
            $query->setTitle($params['title']);
         }

         if(isset($params['titleExact']))
         {
            $query->setTitleExact($params['titleExact']);
         }

         if(isset($params['query']))
         {
            $query->setQuery($params['query']);
         }

         return $this->_docs->getDocumentListFeed($query);
      }
      else
      {
         return false;
      }
   }
}

?>