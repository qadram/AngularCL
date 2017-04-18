<?php

/**
 * Zend/zpubsubhubbubpublisher.inc.php
 * 
 * Defines Zend Framework PubSubHubBub Publisher component.
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

require_once("rpcl/rpcl.inc.php");
use_unit("classes.inc.php");
use_unit("Zend/framework/library/Zend/Feed/Pubsubhubbub/Publisher.php");

/**
 * Component to implement a PubSubHubBub publisher.
 * 
 * @link        http://framework.zend.com/manual/en/zend.feed.pubsubhubbub.introduction.html#zend.feed.pubsubhubbub.zend.feed.pubsubhubbub.publisher Zend Framework Documentation
 */
class ZPubSubHubBubPublisher extends Component
{
   private $_publisher=null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
   }

   
   protected $_hubsurls=array();

   /**
    * Hubs URLs.
    *
    * These URLs will be notified about topics updates through an HTTP POST request containing the
    * URI or the updated topic.
    *
    * @see $_updatetopicurls
    *
    * @return array
    */
   function getHubsUrls() { return $this->_hubsurls; }
   function setHubsUrls($value) { $this->_hubsurls=$value; }
   function defaultHubsUrls() { return array(); }

   
   protected $_updatetopicurls=array();

   /**
    * Topics URLs.
    *
    * These URLs will point to the source feeds (RSS, Atom...) for the topics.
    *
    * @return array
    */
   function getUpdateTopicUrls() { return $this->_updatetopicurls; }
   function setUpdateTopicUrls($value) { $this->_updatetopicurls=$value; }
   function defaultUpdateTopicUrls() { return array(); }

   
   protected $_parameters=array();

   /**
    * Parameters.
    *
    * This key-value pairs will be added to the URL used during the call to Hub servers about topic
    * updates.
    *
    * @return array
    */
   function getParameters() { return $this->_parameters; }
   function setParameters($value) { $this->_parameters=$value; }
   function defaultParameters() { return array(); }

   /**
    * Initialize $_publisher.
    *
    * @internal
    */
   function _createPublisher()
   {
      $this->_publisher=new Zend_Feed_Pubsubhubbub_Publisher;

      if(count($this->_hubsurls)!=0)
         $this->_publisher->addHubUrls($this->_hubsurls);
      if(count($this->_updatetopicurls)!=0)
         $this->_publisher->addUpdatedTopicUrls($this->_updatetopicurls);
      if(count($this->_parameters)!=0)
         $this->_publisher->setParameters($parameters);
   }

   // Documented in the parent.
   function loaded()
   {
      $this->_createPublisher();
   }

   /**
    * Notifies all Hub servers about changes.
    */
   function notifyAll()
   {
      $this->_publisher->notifyAll();
   }

   /**
    * Returns a boolean indicator of whether the notifications to Hub servers were (all) successful.
    *
    * If any notification failed, this method will return false.
    *
    * @return   boolean
    */
   function isSuccess()
   {
      return $this->_publisher->isSuccess();
   }

    /**
    * Returns an array with occurred errors.
    *
    * This arrays will have key-value pairs with the following information:
    * <ul>
    *   <li>response: Zend_Http_Response object from the error.</li>
    *   <li>hubURL: Hub server URL whose notification failed.</li>
    * </ul>
    *
    * @return   array
    */
   function findErrors()
   {
      return $this->_publisher->getErrors();
   }

}

?>