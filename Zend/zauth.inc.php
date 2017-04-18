<?php

/**
 * Zend/zauth.inc.php
 * 
 * Defines Zend Framework Authentication component.
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

use_unit("Zend/zcommon/zcommon.inc.php");
use_unit("classes.inc.php");

use_unit("Zend/framework/library/Zend/Auth.php");
use_unit("Zend/framework/library/Zend/Auth/Storage/Session.php");

/**
 * Component to implement authentication through dispatchers.
 *
 * With dispatchers, you can easily change the authentication mechanism by changing the dispatcher.
 * 
 * @link        http://framework.zend.com/manual/en/zend.auth.html Zend Framework Documentation
 */
class ZAuth extends Component
{
        // Properties

        /**
         * Component title.
         *
         * It does not affect component behavior.
         *
         * @var string
         */
        protected $_title="Login";

        /**
         * Error message.
         *
         * Message to be returned in case user is not authenticated or authorized for given realm.
         *
         * This message will only be displayed in case $_onlogin is not set.
         *
         * @var string
         */
        protected $_errormessage="Unauthorized";

        /**
         * Username.
         *
         * @var string
         */
        protected $_username="";

        /**
         * Password.
         *
         * @var string
         */
        protected $_userpassword="";

        /**
         * Realm.
         *
         * @var string
         */
        protected $_userrealm="";

        /**
         * Adapter.
         *
         * This is what actually defines the mechanism to be used for authentication.
         *
         * @var ZAuthAdapter
         */
        protected $_authadapter=null;

        /**
         * Event triggered when user could not be authenticated.
         *
         * Is the perfect situation to prompt them for login information.
         *
         * This property should either contain the name of the function to be run when the event is
         * triggered (without brackets), or be set to null.
         *
         * @var string
         */
        protected $_onlogin=null;

        // Password

        /**
         * Password.
         */
        function getUserPassword() { return $this->_userpassword;       }

        /**
         * Setter method for $_userpassword.
         *
         * @param    string  $value
         */
        function setUserPassword($value) { $this->_userpassword=$value; }

        /**
         * Getter for $_userpassword's default value.
         *
         * @return   string  Empty string
         */
        function defaultUserPassword() { return "";    }

        // Username

        /**
         * Username.
         */
        function getUserName() { return $this->_username;       }

        /**
         * Setter method for $_username.
         *
         * @param    string  $value
         */
        function setUserName($value) { $this->_username=$value; }

        /**
         * Getter for $_username's default value.
         *
         * @return   string  Empty string
         */
        function defaultUsername() { return ""; }

        // Error Message

        /**
         * Error message.
         *
         * Message to be returned in case user is not authenticated or authorized for given realm.
         *
         * This message will only be displayed in case $_onlogin is not set.
         */
        function getErrorMessage() { return $this->_errormessage; }

        /**
         * Setter method for $_errormessage.
         *
         * @param    string  $value
         */
        function setErrorMessage($value) { $this->_errormessage=$value; }

        /**
         * Getter for $_errormessage's default value.
         *
         * @return   string  "Unauthorized"
         */
        function defaultErrorMessage() { return "Unauthorized"; }

        // Realm

        /**
         * Realm.
         */
        function getUserRealm() { return $this->_userrealm; }

        /**
         * Setter method for $_userrealm.
         *
         * @param    string  $value
         */
        function setUserRealm($value) { $this->_userrealm=$value; }

        /**
         * Getter for $_userrealm's default value.
         *
         * @return   string  Empty string
         */
        function defaultUserRealm() { return ""; }

        // Title

        /**
         * Component title.
         *
         * It does not affect component behavior.
         */
        function getTitle() { return $this->_title; }

        /**
         * Setter method for $_title.
         *
         * @param    string  $value
         */
        function setTitle($value) { $this->_title=$value; }

        /**
         * Getter for $_title's default value.
         *
         * @return   string  "Login"
         */
        function defaultTitle() { return "Login"; }

        // Adapter

        /**
         * Adapter.
         *
         * This is what actually defines the mechanism to be used for authentication.
         */
        function getAuthAdapter() { return $this->_authadapter; }

        /**
         * Setter method for $_authadapter.
         *
         * @param    ZAuthAdapter       $value
         */
        function setAuthAdapter($value) { $this->_authadapter=$this->fixupProperty($value); }

        /**
         * Getter for $_authadapter's default value.
         *
         * @return   ZAuthAdapter       Null
         */
        function defaultAuthAdapter() { return null; }

        // Documented in the parent.
        function loaded()
        {
            parent::loaded();
            $this->setAuthAdapter($this->_authadapter);
        }

        /**
         * Performs authentication.
         *
         * Returns true or false depending on whether user is authenticated or not.
         *
         * @return      boolean
         * @throws      Exception       No adapter found.
         */
        function Execute()
        {

                if($this->_authadapter==null)
                        throw new Exception('An adapter is needed for authentication to work');

                // Get singleton.
                $auth = Zend_Auth::getInstance();

                // If user had been previously authenticated, no need to continue.
                if ($auth->hasIdentity())
                {
                         return true;
                }

                $result= $this->_authadapter->Authenticate($auth,$this->_username,$this->_userpassword,$this->_userrealm);

                if($result==true)
                {
                        return $result;
                }
                else
                {
                        //Make sure identify is not stored. We must do this because authentication for db's doesn't
                        //use realm, so we could return false from the adapter but the identity be authenticated
                        //because login/passwords matches the one we supply but realm is different
                        $auth->clearIdentity();

                        if ($this->_onlogin!=null)
                        {
                          $this->callEvent("onlogin",null);
                        }
                        else die($this->_errormessage);
                }
        }

        // OnLogin Event

        /**
         * Event triggered when user could not be authenticated.
         *
         * Is the perfect situation to prompt them for login information.
         *
         * This property should either contain the name of the function to be run when the event is
         * triggered (without brackets), or be set to null.
         */
        function getOnLogin() { return $this->_onlogin; }

        /**
         * Setter method for $_onlogin.
         *
         * @param    string  $value
         */
        function setOnLogin($value) { $this->_onlogin=$value; }

        /**
         * Getter for $_onlogin's default value.
         *
         * @return   string  Null
         */
        function defaultOnLogin() { return null; }
}

?>