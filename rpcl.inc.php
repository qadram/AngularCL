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

if (isset($_GET['css'])) header('Content-type: text/css');

/**
 * Major version of the library. For example, 5 in the case of the version 5.1 of the library.
 */
define('RPCL_VERSION_MAJOR',5);

/**
 * Minor version of the library. For example, 1 in the case of the version 5.1 of the library.
 */
define('RPCL_VERSION_MINOR',1);

/**
 * Full code of the library version. It's composed of the major and minor version numbers, concatenated with a dot. For
 * example, '5.1' when the major version is 5 and the minor version is 1.
 */
define('RPCL_VERSION',RPCL_VERSION_MAJOR.'.'.RPCL_VERSION_MINOR);

        require_once("acl.inc.php");

        if (isset($_GET['XDEBUG_PROFILE'])) setcookie('XDEBUG_PROFILE','1');
        if (isset($_GET['XDEBUG_PROFILE_STOP'])) setcookie('XDEBUG_PROFILE','');

        $scriptfilename='';

        if (isset($_SERVER['SCRIPT_FILENAME'])) $scriptfilename= $_SERVER['SCRIPT_FILENAME'];
        else
        {
                        global $HTTP_SERVER_VARS;

                        $scriptfilename=$HTTP_SERVER_VARS["SCRIPT_NAME"];
        }

        //Defines the PATH where the RPCL resides
        $fs_path=relativePath(realpath(dirname(__FILE__)),dirname(realpath($scriptfilename)));

        //If the script is stored in an UNC like this \\machine\folder, will generate ../../drive:/, must be fixed
        if (strpos($fs_path,'file:')===false)
        {
          $drivepos=strpos($fs_path,':/');
          if ($drivepos!==false)
          {
            $fs_path='file:///'.substr($fs_path,$drivepos-1);
          }
        }


        $http_path=$fs_path;

        //If the rpcl folder is not a subfolder of the RPCL, then it uses rpcl-bin as an alias to find the assets
        if ((substr($fs_path,0,2)=='..') || (strpos($fs_path,'file:')===0))
        {
            if (!array_key_exists('FOR_PREVIEW',$_SERVER)) $http_path='/rpcl-bin';
        }


/**
 * Path to the root directory of the library in the server filesystem. For example, '/srv/http/rpcl'.
 */
define('RPCL_FS_PATH',$fs_path);

$http_path = str_replace(' ','%20',$http_path);
/**
 * Path to the root directory of the library as provided by the server through the HTTP protocol, without the host
 * address and server port. For example, '/rpcl' when the library is available at 'http://<host>:<port>/rpcl'.
 */
define('RPCL_HTTP_PATH',$http_path);

        /**
         * Returns the path to the $targetFolder relative to the $rootFolder.
         *
         * @internal
         *
         * @param string $targetFolder Absolute path to a folder, to be returned as a path relative to the $rootFolder.
         * @param string $rootFolder   Absolute path to the folder the returned path will be relative to.
         * @param string $separator    Folder separation character.
         * @return string Path.
         */
        function relativePath($targetFolder, $rootFolder, $separator = '/')
        {
                $targetFolder=str_replace('\\','/',$targetFolder);
                $rootFolder=str_replace('\\','/',$rootFolder);

                $dirs = explode($separator, $targetFolder);
                $comp = explode($separator, $rootFolder);

                foreach ($comp as $i => $part)
                {
                        if (isset($dirs[$i]) && strtolower($part) == strtolower($dirs[$i]))
                        {
                                unset($dirs[$i], $comp[$i]);
                        }
                        else
                        {
                                //TODO: Check this with UNC
                                //TODO: If the .php file to be executed resides on a UNC, the webserver it doesn't start,
                                //fix or warn users about the correct usage of the library and the location
                                if ((strpos($part,':')) && (strpos($dirs[$i],':')))
                                {
                                        //This fixes the problem with having the code to be run
                                        //and the library in different drives, but it only works with IE
                                        //FF throws a security warning
                                        //TODO: Must fix another way
                                        $result='file:///'.$targetFolder;
                                        return($result);
                                }
                                break;
                        }
                }

                                $result=str_repeat('..' . $separator, count($comp)) . implode($separator, $dirs);

                return($result);
        }

        /**
         * Includes the target unit from the RPCL library folder.
         *
         * This is a helper function, so you don't have to worry about the location of the RPCL library. The target unit
         * is includedusing the require_once() function, so you will get an error if the unit does not exist, and if the
         * unit has been included already, it won't be included again.
         *
         * <code>
         * <?php
         *    use_unit("controls.inc.php");
         *    use_unit("Zend/zcaptcha.inc.php");
         * ?>
         * </code>
         *
         * @link http://www.php.net/manual/en/function.require-once.php
         *
         * @param string $path Unit path relative to the path of the RPCL library folder.
         */
        function use_unit($path)
        {
                $apath=RPCL_FS_PATH;
                if ($apath!="") $apath.="/";
                require_once($apath.$path);
        }


        global $startup_functions;

        $startup_functions=array();

        /**
         * Adds the target function to the list of functions that are called right before the application starts the
         * session is started.
         *
         * Use this function to perform any processing that must be done before the session starts. Some libraries and
         * frameworks, such as the Zend framework, require this kind of functionality, since they require you to run
         * some code before there is a session open.
         *
         * It is allowed to call the same function more than once. On the other hand, you might want to check the
         * content of the global vairable $startup_functions before you register a function; it might have been
         * registered already previously, and you may not want to register it more than once.
         *
         * <code>
         * <?php
         *    function MyStartup()
         *    {
         *         //My custom session start
         *         session_start();
         *    }
         *    register_startup_function("MyStartup");
         * ?>
         * </code>
         *
         * @param string $functionName Name of the function to be called before the session starts.
         */
        function register_startup_function($functionName)
        {
                //Add the function to the list
                global $startup_functions;
                $startup_functions[]=$functionName;
        }
?>