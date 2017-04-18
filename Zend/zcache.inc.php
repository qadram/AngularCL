<?php

/**
 * Zend/zcache.inc.php
 *
 * Defines Zend Framework Cache component.
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

// TODO: Review and update the whole file.

/**
 * Include RPCL common file and necessary units.
 */
require_once("rpcl/rpcl.inc.php");
use_unit("Zend/zcommon/zcommon.inc.php");
use_unit("classes.inc.php");
use_unit("cache.inc.php");
use_unit("Zend/framework/library/Zend/Cache.php");

// Frontends

/**
 * Core frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.core Zend Framework Documentation
 *
 * @const       cfCore
 */
define('cfCore', 'cfCore');

/**
 * Output frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.output Zend Framework Documentation
 *
 * @const       cfOutput
 */
define('cfOutput', 'cfOutput');

/**
 * Function frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.function Zend Framework Documentation
 *
 * @const       cfFunction
 */
define('cfFunction', 'cfFunction');

/**
 * Class frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.class Zend Framework Documentation
 *
 * @const       cfClass
 */
define('cfClass', 'cfClass');

/**
 * File frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.file Zend Framework Documentation
 *
 * @const       cfFile
 */
define('cfFile', 'cfFile');

/**
 * Page frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.page Zend Framework Documentation
 *
 * @const       cfPage
 */
define('cfPage', 'cfPage');

// Backends

/**
 * File backend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.file Zend Framework Documentation
 *
 * @const       cbFile
 */
define('cbFile', 'cbFile');

/**
 * SQLite backend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.sqlite Zend Framework Documentation
 *
 * @const       cbSQLite
 */
define('cbSQLite', 'cbSQLite');

/**
 * Memcached backend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.memcached Zend Framework Documentation
 *
 * @const       cbMemcached
 */
define('cbMemcached', 'cbMemcached');

/**
 * APC backend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.apc Zend Framework Documentation
 *
 * @const       cbAPC
 */
define('cbAPC', 'cbAPC');

/**
 * Zend Platform backend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.platform Zend Framework Documentation
 *
 * @const       cbZendPlatform
 */
define('cbZendPlatform', 'cbPlatform');

// Read Control Type

/**
 * CRC32 read control type.
 *
 * @link        http://en.wikipedia.org/wiki/CRC32 Wikipedia
 *
 * @const       rctCRC32
 */
define('rctCRC32', 'rctCRC32');

/**
 * MD5 read control type.
 *
 * @link        http://en.wikipedia.org/wiki/MD5 Wikipedia
 *
 * @const       rctMD5
 */
define('rctMD5', 'rctMD5');

/**
 * Adler-32 read control type.
 *
 * @link        http://en.wikipedia.org/wiki/Adler-32 Wikipedia
 *
 * @const       rctADLER32
 */
define('rctADLER32', 'rctADLER32');

/**
 * strlen() read control type.
 *
 * @link        http://php.net/manual/en/function.strlen.php Wikipedia
 *
 * @const       rctSTRLEN
 */
define('rctSTRLEN', 'rctSTRLEN');

/**
 * Base class for frontend options.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.html Zend Framework Documentation
 */
class ZCacheOptions extends Persistent
{

    // Owner

    /**
     * Owner.
     *
     * @var     ZCache
     */
    protected $ZCache=null;

    // Documented in the parent.
    function readOwner()
    {
         return($this->ZCache);
    }

    // Constructor

    /**
     * Class constructor.
     *
     * @param   ZCache  $aowner Owner.
     */
    function __construct($aowner)
    {
        parent::__construct();

        $this->ZCache=$aowner;
    }
}

/**
 * Options for Function frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.function Zend Framework Documentation
 */
class ZCacheFrontendFunctionOptions extends ZCacheOptions
{

    // Cached by Default

    /**
     * Whether or not to cache function calls by default.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_cachebydefault = "1";

    /**
     * Whether or not to cache function calls by default.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCacheByDefault() { return $this->_cachebydefault; }

    /**
     * Setter method for $_cachebydefault.
     *
     * @param    string  $value
     */
    function setCacheByDefault($value) { $this->_cachebydefault = $value; }

    /**
     * Getter for $_cachebydefault's default value.
     *
     * @return   string  True ('1')
     */
    function defaultCacheByDefault() { return "1"; }

    // Cached Functions

    /**
     * Names of functions to be always cached.
     *
     * @var      array
     */
    protected $_cachedfunctions = array();

    /**
     * Names of functions to be always cached.
     */
    function getCachedFunctions() { return $this->_cachedfunctions; }

    /**
     * Setter method for $_cachedfunctions.
     *
     * @param    array   $value
     */
    function setCachedFunctions($value) { $this->_cachedfunctions = $value; }

    /**
     * Getter for $_cachedfunctions's default value.
     *
     * @return   array   Empty array
     */
    function defaultCachedFunctions() { return array(); }

    // Non-Cached Functions

    /**
     * Names of functions to never be cached.
     *
     * @var      array
     */
    protected $_noncachedfunctions = array();

    /**
     * Names of functions to never be cached.
     */
    function getNonCachedFunctions() { return $this->_noncachedfunctions; }

    /**
     * Setter method for $_noncachedfunctions.
     *
     * @param    array   $value
     */
    function setNonCachedFunctions($value) { $this->_noncachedfunctions = $value; }

    /**
     * Getter for $_noncachedfunctions's default value.
     *
     * @return   array   Empty array
     */
    function defaultNonCachedFunctions() { return array(); }
}

/**
 * Options for Class frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.class Zend Framework Documentation
 */
class ZCacheFrontendClassOptions extends ZCacheOptions
{

    // Cached Entity

    /**
     * Entity to be cached
     *
     * If set to a class name, an abstract class will be cached, and only static calls will be used;
     * if set to an object, its methods will be cached instead.
     *
     * @var      mixed
     */
    protected $_cachedentity="";

    /**
     * Entity to be cached
     *
     * If set to a class name, an abstract class will be cached, and only static calls will be used;
     * if set to an object, its methods will be cached instead.
     */
    function getCachedEntity() { return $this->_cachedentity; }

    /**
     * Setter method for $_cachedentity.
     *
     * @param    mixed   $value
     */
    function setCachedEntity($value) { $this->_cachedentity=$value; }

    /**
     * Getter for $_cachedentity's default value.
     *
     * @return  string  Empty string
     */
    function defaultCachedEntity() { return ""; }

    // Cached by Default

    /**
     * Whether or not to cache calls by default.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_cachebydefault="1";

    /**
     * Whether or not to cache calls by default.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCacheByDefault() { return $this->_cachebydefault; }

    /**
     * Setter method for $_cachebydefault.
     *
     * @param    string $value
     */
    function setCacheByDefault($value) { $this->_cachebydefault=$value; }

    /**
     * Getter for $_cachebydefault's default value.
     *
     * @return   string True ('1')
     */
    function defaultCacheByDefault() { return "1"; }

    // Cached Methods

    /**
     * Names of methods to be always cached.
     *
     * @var      array
     */
    protected $_cachedmethods=array();

    /**
     * Names of methods to be always cached.
     */
    function getCachedMethods() { return $this->_cachedmethods; }

    /**
     * Setter method for $_cachedmethods.
     *
     * @param    array   $value
     */
    function setCachedMethods($value) { $this->_cachedmethods=$value; }

    /**
     * Getter for $_cachedmethods's default value.
     *
     * @return   array   Empty array
     */
    function defaultCachedMethods() { return array(); }

    // Non-Cached Methods

    /**
     * Names of methods to never be cached.
     *
     * @var      array
     */
    protected $_noncachedmethods=array();

    /**
     * Names of methods to never be cached.
     */
    function getNonCachedMethods() { return $this->_noncachedmethods; }

    /**
     * Setter method for $_noncachedmethods.
     *
     * @param    array   $value
     */
    function setNonCachedMethods($value) { $this->_noncachedmethods=$value; }

    /**
     * Getter for $_noncachedmethods's default value.
     *
     * @return   array   Empty array
     */
    function defaultNonCachedMethods() { return array(); }
}

/**
 * Options for File frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.file Zend Framework Documentation
 */
class ZCacheFrontendFileOptions extends ZCacheOptions
{

    // Master File

    /**
     * Complete path to the master file.
     *
     * @var              string
     * @deprecated       Deprecated since version 1.7 of the Zend Framework (see Zend Framework
     *                   documentation for this class).
     */
    protected $_masterfile="";

    /**
     * Complete path to the master file.
     *
     * @deprecated       Deprecated since version 1.7 of the Zend Framework (see Zend Framework
     *                   documentation for this class).
     */
    function getMasterFile() { return $this->_masterfile; }

    /**
     * Setter method for $_masterfile.
     *
     * @param    string  $value
     * @deprecated       Deprecated since version 1.7 of the Zend Framework (see Zend Framework
     *                   documentation for this class).
     */
    function setMasterFile($value) { $this->_masterfile=$value; }

    /**
     * Getter for $_masterfile's default value.
     *
     * @return   string  Empty string
     * @deprecated       Deprecated since version 1.7 of the Zend Framework (see Zend Framework
     *                   documentation for this class).
     */
    function defaultMasterFile() { return ""; }

}

/**
 * Options for Page frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.frontends.html#zend.cache.frontends.page Zend Framework Documentation
 */
class ZCacheFrontendPageOptions extends ZCacheOptions
{

    // HTTP Conditional

    /**
     * Whether the HTTP Conditional system should be used or not.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * HTTP Conditional system is not yet implemented.
     *
     * @var      string
     */
    protected $_httpconditional="0";

    /**
     * Whether the HTTP Conditional system should be used or not.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * HTTP Conditional system is not yet implemented.
     */
    function getHTTPConditional() { return $this->_httpconditional; }

    /**
     * Setter method for $_httpconditional.
     *
     * @param    string  $value
     */
    function setHTTPConditional($value) { $this->_httpconditional=$value; }

    /**
     * Getter for $_httpconditional's default value.
     *
     * @return   string  False ('0')
     */
    function defaultHTTPConditional() { return "0"; }

    // Debug Header

    /**
     * Whether or not to display a debug text before each cached page.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_debugheader="0";

    /**
     * Whether or not to display a debug text before each cached page.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getDebugHeader() { return $this->_debugheader; }

    /**
     * Setter method for $_debugheader.
     *
     * @param    string  $value
     */
    function setDebugHeader($value) { $this->_debugheader=$value; }

    /**
     * Getter for $_debugheader's default value.
     *
     * @return   string  False ('0')
     */
    function defaultDebugHeader() { return "0"; }

    // Enable

    /**
     * Enable or disable caching.
     *
     * It can be set either to true ('1') to enable caching, or to false ('0') to disable it.
     *
     * This property can be useful when debugging cached scripts.
     *
     * @var      string
     */
    protected $_enabled="1";

    /**
     * Enable or disable caching.
     *
     * It can be set either to true ('1') to enable caching, or to false ('0') to disable it.
     *
     * This property can be useful when debugging cached scripts.
     */
    function getEnabled() { return $this->_enabled; }

    /**
     * Setter method for $_enabled.
     *
     * @param    string  $value
     */
    function setEnabled($value) { $this->_enabled=$value; }

    /**
     * Getter for $_enabled's default value.
     *
     * @return   string  True ('1')
     */
    function defaultEnabled() { return "1"; }

    // Cache With GET

    /**
     * Whether or not to cache when there are variables in $_GET.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_cachewithget="0";

    /**
     * Whether or not to cache when there are variables in $_GET.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCacheWithGET() { return $this->_cachewithget; }

    /**
     * Setter method for $_cachewithget.
     *
     * @param    string  $value
     */
    function setCacheWithGET($value) { $this->_cachewithget=$value; }

    /**
     * Getter for $_cachewithget's default value.
     *
     * @return   string  False ('0')
     */
    function defaultCacheWithGET() { return "0"; }

    // Cache With POST

    /**
     * Whether or not to cache when there are variables in $_POST.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_cachewithpost="0";

    /**
     * Whether or not to cache when there are variables in $_POST.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCacheWithPOST() { return $this->_cachewithpost; }

    /**
     * Setter method for $_cachewithpost.
     *
     * @param    string  $value
     */
    function setCacheWithPOST($value) { $this->_cachewithpost=$value; }

    /**
     * Getter for $_cachewithpost's default value.
     *
     * @return   string  False ('0')
     */
    function defaultCacheWithPOST() { return "0"; }

    // Cache With Session

    /**
     * Whether or not to cache when there are variables in $_SESSION.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_cachewithsession="0";

    /**
     * Whether or not to cache when there are variables in $_SESSION.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCacheWithSESSION() { return $this->_cachewithsession; }

    /**
     * Setter method for $_cachewithsession.
     *
     * @param    string  $value
     */
    function setCacheWithSESSION($value) { $this->_cachewithsession=$value; }

    /**
     * Getter for $_cachewithsession's default value.
     *
     * @return   string  False ('0')
     */
    function defaultCacheWithSESSION() { return "0"; }

    // Cache With Cookie

    /**
     * Whether or not to cache when there are variables in $_COOKIE.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_cachewithcookie="0";

    /**
     * Whether or not to cache when there are variables in $_COOKIE.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCacheWithCOOKIE() { return $this->_cachewithcookie; }

    /**
     * Setter method for $_cachewithcookie.
     *
     * @param    string  $value
     */
    function setCacheWithCOOKIE($value) { $this->_cachewithcookie=$value; }

    /**
     * Getter for $_cachewithcookie's default value.
     *
     * @return   string  False ('0')
     */
    function defaultCacheWithCOOKIE() { return "0"; }

    // ID With GET

    /**
     * Whether or not should the cache id depend on the content of $_GET.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_idwithget="1";

    /**
     * Whether or not should the cache id depend on the content of $_GET.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getIDWithGET() { return $this->_idwithget; }

    /**
     * Setter method for $_idwithget.
     *
     * @param    string  $value
     */
    function setIDWithGET($value) { $this->_idwithget=$value; }

    /**
     * Getter for $_idwithget's default value.
     *
     * @return   string  True ('1')
     */
    function defaultIDWithGET() { return "1"; }

    // ID With POST

    /**
     * Whether or not should the cache id depend on the content of $_POST.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_idwithpost="1";

    /**
     * Whether or not should the cache id depend on the content of $_POST.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getIDWithPOST() { return $this->_idwithpost; }

    /**
     * Setter method for $_idwithpost.
     *
     * @param    string  $value
     */
    function setIDWithPOST($value) { $this->_idwithpost=$value; }

    /**
     * Getter for $_idwithpost's default value.
     *
     * @return   string  True ('1')
     */
    function defaultIDWithPOST() { return "1"; }

    // ID With Session

    /**
     * Whether or not should the cache id depend on the content of $_SESSION.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_idwithsession="1";

    /**
     * Whether or not should the cache id depend on the content of $_SESSION.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getIDWithSESSION() { return $this->_idwithsession; }

    /**
     * Setter method for $_idwithsession.
     *
     * @param    string  $value
     */
    function setIDWithSESSION($value) { $this->_idwithsession=$value; }

    /**
     * Getter for $_idwithsession's default value.
     *
     * @return   string  True ('1')
     */
    function defaultIDWithSESSION() { return "1"; }

    // ID With Files

    /**
     * Whether or not should the cache id depend on the content of $_FILES.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_idwithfiles="1";

    /**
     * Whether or not should the cache id depend on the content of $_FILES.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getIDWithFiles() { return $this->_idwithfiles; }

    /**
     * Setter method for $_idwithfiles.
     *
     * @param    string  $value
     */
    function setIDWithFiles($value) { $this->_idwithfiles=$value; }

    /**
     * Getter for $_idwithfiles's default value.
     *
     * @return   string  True ('1')
     */
    function defaultIDWithFiles() { return "1"; }

    // ID With Cookie

    /**
     * Whether or not should the cache id depend on the content of $_COOKIE.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_idwithcookie="1";

    /**
     * Whether or not should the cache id depend on the content of $_COOKIE.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getIDWithCOOKIE() { return $this->_idwithcookie; }

    /**
     * Setter method for $_idwithcookie.
     *
     * @param    string  $value
     */
    function setIDWithCOOKIE($value) { $this->_idwithcookie=$value; }

    /**
     * Getter for $_idwithcookie's default value.
     *
     * @return   string  True ('1')
     */
    function defaultIDWithCOOKIE() { return "1"; }

    // Regular Expressions

    /**
     * Associative array to set options only for some $_SERVER['REQUEST_URI']
     * values.
     *
     * Keys are (PCRE) regular expressions, while values are associative arrays with options to be
     * set in case the regular expressions matches $_SERVER['REQUEST_URI'].
     *
     * If several regular expressions match $_SERVER['REQUEST_URI'], only the last
     * one will be used.
     *
     * @var      array
     */
    protected $_regexps=array();

    /**
     * Associative array to set options only for some $_SERVER['REQUEST_URI']
     * values.
     *
     * Keys are (PCRE) regular expressions, while values are associative arrays with options to be
     * set in case the regular expressions matches $_SERVER['REQUEST_URI'].
     *
     * If several regular expressions match $_SERVER['REQUEST_URI'], only the last
     * one will be used.
     */
    function getRegExps() { return $this->_regexps; }

    /**
     * Setter method for $_regexps.
     *
     * @param    array  $value
     */
    function setRegExps($value) { $this->_regexps=$value; }

    /**
     * Getter for $_regexps's default value.
     *
     * @return   array  Empty array
     */
    function defaultRegExps() { return array(); }
}

/**
 * Options for Page backend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.sqlite Zend Framework Documentation
 */
class ZCacheBackendSQLiteOptions extends ZCacheOptions
{

    // Database Path

    /**
     * Complete path to the SQLite file.
     *
     * @var      string
     */
    protected $_databasepath="";

    /**
     * Complete path to the SQLite file.
     */
    function getDatabasePath() { return $this->_databasepath; }

    /**
     * Setter method for $_databasepath.
     *
     * @param    string  $value
     */
    function setDatabasePath($value) { $this->_databasepath=$value; }

    /**
     * Getter for $_databasepath's default value.
     *
     * @return   string  Empty string
     */
    function defaultDatabasePath() { return ""; }

    // Vacuum Factor

    /**
     * Automatic-vacuum factor.
     *
     * This property lets you disable or tune the automatic vacuum process for the SQLite database.
     * This process defragments the database file, and makes it smaller.
     *
     * This process is performed randomly 1 time after X calls to delete() or
     * clean(), where X is the value of this properly. You can disable it with 0.
     *
     * @var      integer
     */
    protected $_vacuumfactor=10;

    /**
     * Automatic-vacuum factor.
     *
     * This property lets you disable or tune the automatic vacuum process for the SQLite database.
     * This process defragments the database file, and makes it smaller.
     *
     * This process is performed randomly 1 time after X calls to delete() or
     * clean(), where X is the value of this properly. You can disable it with 0.
     */
    function getVacuumFactor() { return $this->_vacuumfactor; }

    /**
     * Setter method for $_vacuumfactor.
     *
     * @param    integer        $value
     */
    function setVacuumFactor($value) { $this->_vacuumfactor=$value; }

    /**
     * Getter for $_vacuumfactor's default value.
     *
     * @return   integer        10
     */
    function defaultVacuumFactor() { return 10; }
}

/**
 * Options for Memcached frontend.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.memcached Zend Framework Documentation
 */
class ZCacheBackendMemcachedOptions extends ZCacheOptions
{

    protected $_servers=array();

    /**
     * Array of memcached servers.
     *
     * Check the upstream documentation
     * (http://framework.zend.com/manual/en/zend.cache.backends.html#zend.cache.backends.memcached) for additional information.
     *
     * @return array
     */
    function getServers() { return $this->_servers; }
    function setServers($value) { $this->_servers=$value; }
    function defaultServers() { return array(); }


    // Compression

    /**
     * Whether to use on-the-fly compression or not.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * @var      string
     */
    protected $_compression="0";

    /**
     * Whether to use on-the-fly compression or not.
     *
     * It can be set either to true ('1') or to false ('0').
     */
    function getCompression() { return $this->_compression; }

    /**
     * Setter method for $_compression.
     *
     * @param    string  $value
     */
    function setCompression($value) { $this->_compression=$value; }

    /**
     * Getter for $_compression's default value.
     *
     * @return   string  False ('0')
     */
    function defaultCompression() { return "0"; }
}

/**
 * Component to cache data.
 *
 * @link        http://framework.zend.com/manual/en/zend.cache.html Zend Framework Documentation
 */
class ZCache extends Cache
{

    // Zend Cache

    /**
     * Zend Framework Cache instance.
     *
     * @var     Zend_Cache
     */
    public $zend_cache=null;

    /**
     * Start caching from current position.
     *
     * This cache will have an identifier generated from component's path and given cache type.
     *
     * If data for given identifier had been already cached, such data will be used (for example, if
     * a function was cached it will run), and this method will return true. Else, this method will
     * return false and do nothing else.
     *
     * @see     finishCache(), start()
     *
     * @param   Component       $control        Control to be cached. Is only used to generate a
     *                                          unique identifier for cached data.
     * @param   string          $cachetype      Type of cached data. Descriptive identifier for the
     *                                          data to be cached.
     * @return  boolean
     */
    function initCache($control, $cachetype)
    {
        $id=$control->readNamePath().'_'.$cachetype;
        $id=str_replace('.','_',$id);
        return($this->start($id));
    }

    /**
     * Stop caching.
     *
     * @see     initCache(), end()
     */
    function finishCache()
    {
        $this->end();
    }

    /**
     * Return data cached with given ID.
     *
     * If data for given identifier has been already cached, such data will be returned. Else, your
     * next call to save() method will automatically save whatever data you pass it with
     * the ID you passed to this method (as long as you do not manually specify it in the call).
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @see     save()
     *
     * @param   string  $id                     ID for cached data.
     * @param   boolean $doNotTestCacheValidity Set to true to skip cache validity check.
     * @param   boolean $doNotUnserialize       Set to true to skip unserialization.
     * @return  boolean
     */
    function load($id, $doNotTestCacheValidity = false, $doNotUnserialize = false)
    {
        return($this->zend_cache->load($id, $doNotTestCacheValidity, $doNotUnserialize));
    }

    /**
     * Caches passed data.
     *
     * You do not need to pass an ID for the cached data if you have just called load(), its
     * ID will be used by default.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @see load()
     *
     * @param   mixed           $data                   Data to be cached.
     * @param   string          $id                     ID for cached data.
     * @param   array           $tags                   Tags for cache record.
     * @param   boolean|integer $specificLifetime       False to disable, or an amount of seconds.
     *
     * @return  boolean
     */
    function save($data, $id = null, $tags = array(), $specificLifetime = false)
    {
        return($this->zend_cache->save($data, $id, $tags, $specificLifetime));
    }

    /**
     * Start caching from current position.
     *
     * This cache will have an identifier you must pass in the call.
     *
     * If data for given identifier had been already cached, such data will be used (for example, if
     * a function was cached it will run), and this method will return true. Else, this method will
     * return false and do nothing else.
     *
     * @see     initCache(), end()
     *
     * @param   string  $id                     Cache identifier.
     * @param   boolean $doNotTestCacheValidity Set to true to skip cache validity check.
     * @return  boolean
     */
    function start($id, $doNotTestCacheValidity = false)
    {
        return($this->zend_cache->start($id, $doNotTestCacheValidity));
    }

    /**
     * Stop caching.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @see     finishCache(), start()
     *
     * @param   array           $tags                   Optional tags for cache record.
     * @param   boolean|integer $specificLifetime       False to disable (default), or an amount of
     *                                                  seconds.
     * @return  boolean
     */
    function end($tags = array(), $specificLifetime = false)
    {
        return($this->zend_cache->end($tags, $specificLifetime));
    }

    /**
     * Removes a particular cache.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @param   string  $id     ID of the cache to be removed.
     * @return  boolean
     */
    function remove($id)
    {
        return($this->zend_cache->remove($id));
    }

    /**
     * Removes all caches.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @return  boolean
     */
    function cleanAll()
    {
        return($this->zend_cache->clean(Zend_Cache::CLEANING_MODE_ALL));
    }

    /**
     * Removes outdated caches.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @return  boolean
     */
    function cleanOld()
    {
        return($this->zend_cache->clean(Zend_Cache::CLEANING_MODE_OLD));
    }

    /**
     * Removes caches matching all given tags.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @param   array   $tags   Tags caches to be deleted must have.
     * @return  boolean
     */
    function cleanMatching($tags)
    {
        return($this->zend_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags));
    }

    /**
     * Removes caches matching none of given tags.
     *
     * For example, if given tags are "tagA" and "tagB", and a cache has "tagB" but not "tagA", such
     * cache will not be removed.
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @param   array   $tags   Tags caches to be deleted must have.
     * @return  boolean
     */
    function cleanNotMatching($tags)
    {
        return($this->zend_cache->clean(Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG, $tags));
    }

    // TODO: Include a method for removing caches matching any given tag. Check
    // http://framework.zend.com/manual/en/zend.cache.theory.html#zend.cache.clean for additional
    // information.

    /**
     * Cache calls to given function.
     *
     * For example, if you want to cache a call like thisFunction("parameter1", 2, false), you would
     * use this methid like this: call("thisFunction", array("parameter1", 2, false)).
     *
     * Returns true if everything goes OK, false otherwise.
     *
     * @param   string          $name                   Function name.
     * @param   array           $parameters             Array of parameters to be passed to the
     *                                                  cached function.
     * @param   array           $tags                   Optional tags for cache record.
     * @param   boolean|integer $specificLifetime       False to disable (default), or an amount of
     *                                                  seconds.
     * @return  boolean
     */
    function call($name, $parameters = array(), $tags = array(), $specificLifetime = false)
    {
        return($this->zend_cache->call($name, $parameters, $tags, $specificLifetime));
    }

    // Documented in the parent.
    function preinit()
    {
                    $frontend='Output';
        $backend='File';

                    $frontendOptions=array();
        $backendOptions=array();

        //Frontend common properties
        $frontendOptions['caching']=$this->_enabled;
        $frontendOptions['cache_id_prefix']=$this->_prefix;
        $frontendOptions['lifetime']=$this->_lifetime;
        $frontendOptions['logging']=$this->_logging;
        $frontendOptions['write_control']=$this->_checkwrite;
        $frontendOptions['automatic_serialization']=$this->_serialization;
        $frontendOptions['automatic_cleaning_factor']=$this->_cleaningfactor;
        $frontendOptions['ignore_user_abort']=$this->_ignoreuserabort;

        //Backend common properties
        $backendOptions['cache_dir']=$this->_cachedir;
        $backendOptions['file_locking']=$this->_filelocking;
        $backendOptions['read_control']=$this->_checkread;

        //Convert read control type
        $rct='crc32';
        switch ($this->_readcontroltype) {
            case rctCRC32:  $rct='crc32'; break;
            case rctADLER32:  $rct='adler32'; break;
            case rctMD5:  $rct='md5'; break;
            case rctSTRLEN:  $rct='strlen'; break;
        }
        $backendOptions['read_control_type']=$rct;
        $backendOptions['hashed_directory_level']=$this->_hasheddirectorylevel;
        $backendOptions['hashed_directory_umask']=$this->_hasheddirectoryumask;
        $backendOptions['file_name_prefix']=$this->_filenameprefix;
        $backendOptions['cache_file_umask']=$this->_cachefileumask;
        $backendOptions['metadatas_array_max_size']=$this->_metadatasize;

        switch ($this->_frontend)
        {
            case cfCore:
                            $frontend='Core';
            break;

            case cfOutput:
                            $frontend='Output';
            break;

            case cfFunction:
                            $frontend='Function';
            break;

            case cfClass:
            $frontend='Class';
            break;

            case cfFile:
            $frontend='File';
            break;

            case cfPage:
            $frontend='Page';
            break;
        }

        switch ($this->_backend)
        {
            case cbFile:
                            $backend='File';
            break;

            case cbSQLite:
                            $backend='Sqlite';
            break;

            case cbMemcached:
            $backend='Memcached';
            break;

            case cbAPC:
            $backend='Apc';
            break;

            case cbZendPlatform:
            $backend='Zend Platform';
            break;
        }

        $this->zend_cache=Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions);

        //Clean all cache when session is restored
        if (isset($_GET['restore_session']))
        {
            if (!isset($_POST['xajax']))
            {
                    $this->cleanAll();
            }
        }
    }

    /**
     * Set $_cachedir to a guessed temporal-folder path.
     */
    function guessTempFolder()
    {
    if ( preg_match( "/^WIN/i", PHP_OS ) )
    {
        if ( isset( $_ENV['TMP'] ) )
        {
        $this->_cachedir= $_ENV['TMP'];
        }
        elseif( isset( $_ENV['TEMP'] ) )
        {
        $this->_cachedir = $_ENV['TEMP'];
        }
        else
        {
        $tmpfolder=getenv('TMP');
        if (($tmpfolder===false) || ($tmpfolder==''))
        {
            $tmpfolder=getenv('TEMP');
            if (($tmpfolder===false) || ($tmpfolder==''))
            {
            $tmpfolder='/tmp/';
            }
        }
        $this->_cachedir= $tmpfolder;
        }
    }
    else
    {
        $this->_cachedir= '/tmp/';
    }
    }

    // Constructor

    // Documented in the parent.
    function __construct($aowner = null)
    {
        // Call inherited constructor.
        parent::__construct($aowner);

        // Frontend properties.
        $this->_frontendfunctionoptions= new ZCacheFrontendFunctionOptions($this);
        $this->_frontendclassoptions= new ZCacheFrontendClassOptions($this);
        $this->_frontendfileoptions= new ZCacheFrontendFileOptions($this);
        $this->_frontendpageoptions= new ZCacheFrontendPageOptions($this);

        // Backend properties.
        $this->_backendsqliteoptions=new ZCacheBackendSQLiteOptions($this);
        $this->_backendmemcachedoptions=new ZCacheBackendMemcachedOptions($this);

        // Temporal folder.
        $this->guessTempFolder();
    }

    // Frontend

    /**
     * Frontend.
     *
     * Possible values are: cfCore, cfOutput, cfFunction, cfClass,
     * cfFile, or cfPage.
     *
     * @var      string
     */
    protected $_frontend = cfOutput;

    /**
     * Frontend.
     *
     * Possible values are: cfCore, cfOutput, cfFunction, cfClass,
     * cfFile, or cfPage.
     */
    function getFrontend() { return $this->_frontend; }

    /**
     * Setter method for $_frontend.
     *
     * @param    string  $value
     */
    function setFrontend($value) { $this->_frontend = $value; }

    /**
     * Getter for $_frontend's default value.
     *
     * @return   string  cfOutput
     */
    function defaultFrontend() { return cfOutput; }

    // Backend

    /**
     * Backend.
     *
     * Possible values are: cbFile, cbSQLite, cbMemcached, cbAPC, or
     * cbZendPlatform.
     *
     * @var      string
     */
    protected $_backend = cbFile;

    /**
     * Backend.
     *
     * Possible values are: cbFile, cbSQLite, cbMemcached, cbAPC, or
     * cbZendPlatform.
     */
    function getBackend() { return $this->_backend; }

    /**
     * Setter method for $_backend.
     *
     * @param    string  $value
     */
    function setBackend($value) { $this->_backend = $value; }

    /**
     * Getter for $_backend's default value.
     *
     * @return   string  cbFile
     */
    function defaultBackend() { return cbFile; }

    // FRONTEND PROPERTIES  //

    // Enable

    /**
     * Enable or disable caching.
     *
     * It can be set either to true ('1') to enable caching, or to false ('0') to disable it.
     *
     * This property can be useful when debugging cached scripts.
     *
     * @var      string
     */
    protected $_enabled = "1";

    /**
     * Enable or disable caching.
     *
     * It can be set either to true ('1') to enable caching, or to false ('0') to disable it.
     *
     * This property can be useful when debugging cached scripts.
     */
    function getEnabled() { return $this->_enabled; }

    /**
     * Setter method for $_enabled.
     *
     * @param    string  $value
     */
    function setEnabled($value) { $this->_enabled = $value; }

    /**
     * Getter for $_enabled's default value.
     *
     * @return   string  True ('1')
     */
    function defaultEnabled() { return "1"; }

    // Prefix

    /**
     * A prefix for all cache IDs.
     *
     * This prefix creates a namespace in the cache, so multiple applications or websites can use a
     * shared cache. Each one can use a different prefix, so the same ID can be used to cache
     * different data in the different applications or websites.
     *
     * @var      string
     */
    protected $_prefix = "";

    /**
     * A prefix for all cache IDs.
     *
     * This prefix creates a namespace in the cache, so multiple applications or websites can use a
     * shared cache. Each one can use a different prefix, so the same ID can be used to cache
     * different data in the different applications or websites.
     */
    function getPrefix() { return $this->_prefix; }

    /**
     * Setter method for $_prefix.
     *
     * @param    string  $value
     */
    function setPrefix($value) { $this->_prefix = $value; }

    /**
     * Getter for $_prefix's default value.
     *
     * @return   string  Empty string
     */
    function defaultPrefix() { return ""; }

    // Lifetime

    /**
     * Cache lifetime (in seconds).
     *
     * Set it to null if you want caches to be valid forever.
     *
     * @var      integer
     */
    protected $_lifetime = 3600;

    /**
     * Cache lifetime (in seconds).
     *
     * Set it to null if you want caches to be valid forever.
     */
    function getLifetime() { return $this->_lifetime; }

    /**
     * Setter method for $_lifetime.
     *
     * @param    integer        $value
     */
    function setLifetime($value) { $this->_lifetime = $value; }

    /**
     * Getter for $_lifetime's default value.
     *
     * @return   integer        3600
     */
    function defaultLifetime() { return 3600; }

    // Logging

    /**
     * Whether or not to use logging through ZLog.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * Using logging will decrease performance.
     *
     * @var      string
     */
    protected $_logging = "0";

    /**
     * Whether or not to use logging through ZLog.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * Using logging will decrease performance.
     */
    function getLogging() { return $this->_logging; }

    /**
     * Setter method for $_logging.
     *
     * @param    string  $value
     */
    function setLogging($value) { $this->_logging = $value; }

    /**
     * Getter for $_logging's default value.
     *
     * @return   string  False ('0')
     */
    function defaultLogging() { return "0"; }

    // Check Write

    /**
     * Whether or not to control data integrity after writting data to cache.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, cache writting will be a bit slower, since right after writting it, data will be
     * read to make sure it is not corrupted.
     *
     * @var      string
     */
    protected $_checkwrite = "1";

    /**
    /**
     * Whether or not to control data integrity after writting data to cache.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, cache writting will be a bit slower, since right after writting it, data will be
     * read to make sure it is not corrupted.
     */
    function getCheckWrite() { return $this->_checkwrite; }

    /**
     * Setter method for $_checkwrite.
     *
     * @param    string  $value
     */
    function setCheckWrite($value) { $this->_checkwrite = $value; }

    /**
     * Getter for $_checkwrite's default value.
     *
     * @return   string  True ('1')
     */
    function defaultCheckWrite() { return "1"; }

    // Serialization

    /**
     * Whether or not to use automatic serialization.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * It can be used to directly save data other than strings. It decreases performance, though.
     *
     * @var      string
     */
    protected $_serialization = "0";

    /**
     * Whether or not to use automatic serialization.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * It can be used to directly save data other than strings. It decreases performance, though.
     */
    function getSerialization() { return $this->_serialization; }

    /**
     * Setter method for $_serialization.
     *
     * @param    string  $value
     */
    function setSerialization($value) { $this->_serialization = $value; }

    /**
     * Getter for $_serialization's default value.
     *
     * @return   string  False ('0')
     */
    function defaultSerialization() { return "0"; }

    // Cleaning Factor

    /**
     * Automatic-cleaning factor.
     *
     * This property lets you disable or tune the automatic cleaning process (garbage collector) for
     * the cached data.
     *
     * This process is performed randomly 1 time after X write operations, where X is the value of
     * this properly. You can disable it with 0.
     *
     * @var      integer
     */
    protected $_cleaningfactor = 10;

    /**
     * Automatic-cleaning factor.
     *
     * This property lets you disable or tune the automatic cleaning process (garbage collector) for
     * the cached data.
     *
     * This process is performed randomly 1 time after X write operations, where X is the value of
     * this properly. You can disable it with 0.
     */
    function getCleaningFactor() { return $this->_cleaningfactor; }

    /**
     * Setter method for $_cleaningfactor.
     *
     * @param    integer        $value
     */
    function setCleaningFactor($value) { $this->_cleaningfactor = $value; }

    /**
     * Getter for $_cleaningfactor's default value.
     *
     * @return   integer        10
     */
    function defaultCleaningFactor() { return 10; }

    // Ignore User Abort

    /**
     * Whether or not to ignore user abort for save().
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, ignore_user_abort PHP flag will be set inside save() to avoid
     * cache corruptions in some cases.
     *
     * @var      string
     */
    protected $_ignoreuserabort = "0";

    /**
     * Whether or not to ignore user abort for save().
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, ignore_user_abort PHP flag will be set inside save() to avoid
     * cache corruptions in some cases.
     */
    function getIgnoreUserAbort() { return $this->_ignoreuserabort; }

    /**
     * Setter method for $_ignoreuserabort.
     *
     * @param    string  $value
     */
    function setIgnoreUserAbort($value) { $this->_ignoreuserabort = $value; }

    /**
     * Getter for $_ignoreuserabort's default value.
     *
     * @return   string  False ('0')
     */
    function defaultIgnoreUserAbort() { return "0"; }

    // BACKEND PROPERTIES  //

    // Cache Directory

    /**
     * System directory to store cache files.
     *
     * @var      string
     */
    protected $_cachedir = '/tmp/';

    /**
     * System directory to store cache files.
     */
    function getCacheDir() { return $this->_cachedir; }

    /**
     * Setter method for $_cachedir.
     *
     * @param    string  $value
     */
    function setCacheDir($value) { $this->_cachedir = $value; }

    /**
     * Getter for $_cachedir's default value.
     *
     * @return   string  '/tmp/'
     */
    function defaultCacheDir() { return '/tmp/'; }

    // File Locking

    /**
     * Whether or not to enable file_locking.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, it can avoid cache corruption under some (bad) circumstances. It does not help on
     * multithread webservers or on NFS filesystems, though.
     *
     * @var      string
     */
    protected $_filelocking = "1";

    /**
     * Whether or not to enable file_locking.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, it can avoid cache corruption under some (bad) circumstances. It does not help on
     * multithread webservers or on NFS filesystems, though.
     */
    function getFileLocking() { return $this->_filelocking; }

    /**
     * Setter method for $_filelocking.
     *
     * @param    string  $value
     */
    function setFileLocking($value) { $this->_filelocking = $value; }

    /**
     * Getter for $_filelocking's default value.
     *
     * @return   string  True ('1')
     */
    function defaultFileLocking() { return "1"; }

    // Check Read

    /**
     * Whether or not to control data integrity when reading.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, a control key is embedded in the cache file and this key is compared with the one
     * calculated after reading the cached data.
     *
     * @var      string
     */
    protected $_checkread = "1";

    /**
     * Whether or not to control data integrity when reading.
     *
     * It can be set either to true ('1') or to false ('0').
     *
     * If enabled, a control key is embedded in the cache file and this key is compared with the one
     * calculated after reading the cached data.
     */
    function getCheckRead() { return $this->_checkread; }

    /**
     * Setter method for $_checkread.
     *
     * @param    string  $value
     */
    function setCheckRead($value) { $this->_checkread = $value; }

    /**
     * Getter for $_checkread's default value.
     *
     * @return   string  True ('1')
     */
    function defaultCheckRead() { return "1"; }

    // Read Control Type

    /**
     * Type of read control to be used.
     *
     * This property will only apply if $_checkread is set to true.
     *
     * Possible values are: rctCRC32, rctMD5, rctADLER32, or
     * rctSTRLEN.
     *
     * @var      string
     */
    protected $_readcontroltype = rctCRC32;

    /**
     * Type of read control to be used.
     *
     * This property will only apply if $_checkread is set to true.
     *
     * Possible values are: rctCRC32, rctMD5, rctADLER32, or
     * rctSTRLEN.
     */
    function getReadControlType() { return $this->_readcontroltype; }

    /**
     * Setter method for $_readcontroltype.
     *
     * @param    string  $value
     */
    function setReadControlType($value) { $this->_readcontroltype = $value; }

    /**
     * Getter for $_readcontroltype's default value.
     *
     * @return   string  rctCRC32
     */
    function defaultReadControlType() { return rctCRC32; }

    // Hashed Directory Level

    /**
     * Hashed directory structure level.
     *
     * 0 means no hashed directory structure, 1 means "one level of directory", 2 means "two levels
     * of directory", and so on.
     *
     * This option can speed up the cache process when you have thousands of cache files. Before you
     * set a value for this option, you should perform some benchmarks to decide which value fits
     * your application or website better.
     *
     * @see     $_hasheddirectoryumask
     *
     * @var      integer
     */
    protected $_hasheddirectorylevel = 0;

    /**
     * Hashed directory structure level.
     *
     * 0 means no hashed directory structure, 1 means "one level of directory", 2 means "two levels
     * of directory", and so on.
     *
     * This option can speed up the cache process when you have thousands of cache files. Before you
     * set a value for this option, you should perform some benchmarks to decide which value fits
     * your application or website better.
     *
     * @see     $_hasheddirectoryumask
     */
    function getHashedDirectoryLevel() { return $this->_hasheddirectorylevel; }

    /**
     * Setter method for $_logging.
     *
     * @param    integer        $value
     */
    function setHashedDirectoryLevel($value) { $this->_hasheddirectorylevel = $value; }

    /**
     * Getter for $_logging's default value.
     *
     * @return   integer        0
     */
    function defaultHashedDirectoryLevel() { return 0; }

    // Hashed Directory Umask

    /**
     * Umask for the hashed directory structure.
     *
     * @link    http://en.wikipedia.org/wiki/Umask Wikipedia
     * @see     $_hasheddirectorylevel
     *
     * @var     string
     */
    protected $_hasheddirectoryumask = '700';

    /**
     * Umask for the hashed directory structure.
     *
     * @link    http://en.wikipedia.org/wiki/Umask Wikipedia
     * @see     $_hasheddirectorylevel
     */
    function getHashedDirectoryUmask() { return $this->_hasheddirectoryumask; }

    /**
     * Setter method for $_hasheddirectorylevel.
     *
     * @param    string  $value
     */
    function setHashedDirectoryUmask($value) { $this->_hasheddirectoryumask = $value; }

    /**
     * Getter for $_hasheddirectorylevel's default value.
     *
     * @return   string  '700'
     */
    function defaultHashedDirectoryUmask() { return '700'; }

    // Filename Prefix

    /**
     * Prefix to be used in cache files name.
     *
     * Be careful with this option, since a too generic value in a system cache directory (like
     * /tmp) can cause disasters when cleaning the cache.
     *
     * @var      string
     */
    protected $_filenameprefix = 'zend_cache';

    /**
     * Prefix to be used in cache files name.
     *
     * Be careful with this option, since a too generic value in a system cache directory (like
     * /tmp) can cause disasters when cleaning the cache.
     */
    function getFileNamePrefix() { return $this->_filenameprefix; }

    /**
     * Setter method for $_filenameprefix.
     *
     * @param    string  $value
     */
    function setFileNamePrefix($value) { $this->_filenameprefix = $value; }

    /**
     * Getter for $_filenameprefix's default value.
     *
     * @return   string  'zend_cache'
     */
    function defaultFileNamePrefix() { return 'zend_cache'; }

    // Cache File Umaks

    /**
     * Umask for cache files.
     *
     * @link    http://en.wikipedia.org/wiki/Umask Wikipedia
     *
     * @var     string
     */
    protected $_cachefileumask = '700';

    /**
     * Umask for cache files.
     *
     * @link    http://en.wikipedia.org/wiki/Umask Wikipedia
     */
    function getCacheFileUmask() { return $this->_cachefileumask; }

    /**
     * Setter method for $_cachefileumask.
     *
     * @param    string  $value
     */
    function setCacheFileUmask($value) { $this->_cachefileumask = $value; }

    /**
     * Getter for $_logging's default value.
     *
     * @return   string  '700'
     */
    function defaultCacheFileUmask() { return '700'; }

    // Metadata Size

    /**
     * Internal maximum size for metadata arrays.
     *
     * Do not change this property unless you know what you are doing.
     *
     * @var      integer
     */
    protected $_metadatasize = 100;

    /**
     * Internal maximum size for metadata arrays.
     *
     * Do not change this property unless you know what you are doing.
     */
    function getMetadataSize() { return $this->_metadatasize; }

    /**
     * Setter method for $_metadatasize.
     *
     * @param    integer        $value
     */
    function setMetadataSize($value) { $this->_metadatasize = $value; }

    /**
     * Getter for $_metadatasize's default value.
     *
     * @return   integer        100
     */
    function defaultMetadataSize() { return 100; }

    // PROPERTIES FOR SPECIFIC FRONTENDS //

    // Frontend Function Options

    /**
     * Options for Function frontend.
     *
     * @var      ZCacheFrontendFunctionOptions
     */
    protected $_frontendfunctionoptions = null;

    /**
     * Options for Function frontend.
     */
    function getFrontendFunctionOptions() { return $this->_frontendfunctionoptions; }

    /**
     * Setter method for $_frontendfunctionoptions.
     *
     * @param    ZCacheFrontendFunctionOptions  $value
     */
    function setFrontendFunctionOptions($value) { $this->_frontendfunctionoptions = $value; }

    /**
     * Getter for $_frontendfunctionoptions's default value.
     *
     * @return   ZCacheFrontendFunctionOptions  Null
     */
    function defaultFrontendFunctionOptions() { return null; }

    // Frontend Class Options

    /**
     * Options for Class frontend.
     *
     * @var      ZCacheFrontendClassOptions
     */
    protected $_frontendclassoptions=null;

    /**
     * Options for Class frontend.
     */
    function getFrontendClassOptions() { return $this->_frontendclassoptions; }

    /**
     * Setter method for $_frontendclassoptions.
     *
     * @param    ZCacheFrontendClassOptions     $value
     */
    function setFrontendClassOptions($value) { $this->_frontendclassoptions=$value; }

    /**
     * Getter for $_frontendclassoptions's default value.
     *
     * @return   ZCacheFrontendClassOptions     Null
     */
    function defaultFrontendClassOptions() { return null; }

    // Frontend File Options

    /**
     * Options for File frontend.
     *
     * @var      ZCacheFrontendFileOptions
     */
    protected $_frontendfileoptions=null;

    /**
     * Options for File frontend.
     */
    function getFrontendFileOptions() { return $this->_frontendfileoptions; }

    /**
     * Setter method for $_frontendfileoptions.
     *
     * @param    ZCacheFrontendFileOptions      $value
     */
    function setFrontendFileOptions($value) { $this->_frontendfileoptions=$value; }

    /**
     * Getter for $_frontendfileoptions's default value.
     *
     * @return   ZCacheFrontendFileOptions      Null
     */
    function defaultFrontendFileOptions() { return null; }

    // Frontend Page Options

    /**
     * Options for Page frontend.
     *
     * @var      ZCacheFrontendPageOptions
     */
    protected $_frontendpageoptions=null;

    /**
     * Options for Page frontend.
     */
    function getFrontendPageOptions() { return $this->_frontendpageoptions; }

    /**
     * Setter method for $_frontendpageoptions.
     *
     * @param    ZCacheFrontendPageOptions      $value
     */
    function setFrontendPageOptions($value) { $this->_frontendpageoptions=$value; }

    /**
     * Getter for $_frontendpageoptions's default value.
     *
     * @return   ZCacheFrontendPageOptions      Null
     */
    function defaultFrontendPageOptions() { return null; }

    // Backend SQLite Options

    /**
     * Options for SQLite frontend.
     *
     * @var      ZCacheBackendSQLiteOptions
     */
    protected $_backendsqliteoptions=null;

    /**
     * Options for SQLite frontend.
     */
    function getBackendSQLiteOptions() { return $this->_backendsqliteoptions; }

    /**
     * Setter method for $_backendsqliteoptions.
     *
     * @param    ZCacheBackendSQLiteOptions     $value
     */
    function setBackendSQLiteOptions($value) { $this->_backendsqliteoptions=$value; }

    /**
     * Getter for $_backendsqliteoptions's default value.
     *
     * @return   ZCacheBackendSQLiteOptions     Null
     */
    function defaultBackendSQLiteOptions() { return null; }

    // Backend Memcached Options

    /**
     * Options for Memcached frontend.
     *
     * @var      ZCacheBackendMemcachedOptions
     */
    protected $_backendmemcachedoptions=null;

    /**
     * Options for Memcached frontend.
     */
    function getBackendMemcachedOptions() { return $this->_backendmemcachedoptions; }

    /**
     * Setter method for $_backendmemcachedoptions.
     *
     * @param    ZCacheBackendMemcachedOptions  $value
     */
    function setBackendMemcachedOptions($value) { $this->_backendmemcachedoptions=$value; }

    /**
     * Getter for $_backendmemcachedoptions's default value.
     *
     * @return   ZCacheBackendMemcachedOptions  Null
     */
    function defaultBackendMemcachedOptions() { return null; }

}

?>