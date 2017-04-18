<?php

/**
 * Zend/zrestserver.inc.php
 * 
 * Defines Zend Framework REST Server component.
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
use_unit("Zend/framework/library/Zend/Rest/Server.php");

/**
 * Component to generate a REST server.
 * 
 * @link        http://framework.zend.com/manual/en/zend.rest.server.html Zend Framework Documentation
 */
class ZRestServer extends Component
{
   private $_server = null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
   }
   
   
   protected $_class='';

   /**
    * Class used to generate the REST server.
    *
    * @return string
    */
   function getClass() { return $this->_class; }
   function setClass($value) { $this->_class=$value; }
   function defaultClass() { return ''; }
   
   
   protected $_encoding='UTF-8';

   /**
    * XML character encoding.
    *
    * @return string
    */
   function getEncoding() { return $this->_encoding; }
   function setEncoding($value) { $this->_encoding=$value; }
   function defaultEncoding() { return 'UTF-8'; }
   
   // Documented in the parent.
   function loaded()
   {
      $this->_server = new Zend_Rest_Server();

      if($this->_class != '')
         $this->_server->setClass($this->_class);

      if($this->_encoding!='')
         $this->_server->setEncoding($this->_encoding);

      $this->_server->handle();
   }
}

?>
