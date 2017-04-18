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

use_unit("system.inc.php");

global $exceptions_enabled;

$exceptions_enabled=true;

global $use_html_entity_decode;

$use_html_entity_decode=true;

global $output_enabled;

$output_enabled=true;

global $checkduplicatenames;

$checkduplicatenames=true;

function typesafeequal($default, $value)
{
    if ($default===$value) return(true);
    else
    {
        if ($default==$value)
        {
                if ((is_scalar($default)) && ($default==0) && (((is_string($value)) && ($value=="0")) || ((is_bool($value)) && ($value==false)))) return(true);
                else
                if ((is_scalar($default)) && ($default!=0) && (is_string($value))) return(true);
                else
                if ((is_scalar($default)) && ($default==1) && (is_bool($value)) && ($value==true)) return(true);
                else
                {
                        $temp=$default;
                        $default=$value;
                        $value=$temp;

                        if ((is_scalar($default)) && ($default==0) && (((is_string($value)) && ($value=="0")) || ((is_bool($value)) && ($value==false)))) return(true);
                        else
                        if ((is_scalar($default)) && ($default!=0) && (is_string($value))) return(true);
                        else
                        if ((is_scalar($default)) && ($default==1) && (is_bool($value)) && ($value==true)) return(true);
                }
        }
    }
    return(false);
}


/**
 * Component it's being loaded
 *
 */
define('csLoading',1);

/**
 * Component it's being edited on the IDE designer
 *
 */
define('csDesigning',2);


 //TODO: Provide a way to show this info using templates so is customizable by final users

/**
 * Common exception handler for RPCL applications.
 *
 * It provides a way to pretty format exceptions and to get an stack trace to find out where the problem is. This
 * function is registered to the PHP engine using set_exception_handler and it receives exceptions as the last step
 * in the exception process.
 *
 *
 * @link http://www.php.net/manual/en/function.set-exception-handler.php
 *
 * @param Exception $exception Exception to raise
 */
function exception_handler($exception)
{
?>
<script type="text/javascript">
function toggleLayer( whichLayer )
{
  var elem;
  if( document.getElementById ) elem = document.getElementById( whichLayer );
  else if( document.all ) elem = document.all[whichLayer];
  else if( document.layers ) elem = document.layers[whichLayer];
  if(elem.style.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined) elem.style.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  elem.style.display = (elem.style.display==''||elem.style.display=='block')?'none':'block';
}
</script>
<?php
        echo "<pre>";
        $tolog="";
        $stacktrace="";
        $stacktrace.="Application raised an exception class <b>".get_class($exception)."</b> with message <b>'".$exception->getMessage()."'</b>\n";
        $msg=strip_tags($stacktrace)."|";
        $stack=array_reverse($exception->getTrace());
        reset($stack);
        $tab="";
        $c="";
        $stacktrace.='<a href="javascript:toggleLayer(\'callstack\');">Click for detailed information</a><div id="callstack" style="display:none;">';
        while (list($k,$v)=each($stack))
        {
                $stacktrace.=$tab.$c."Callstack #$k File: <b><A HREF=\"h5b://$v[file],$v[line]\">".$v['file']."</A></b> Line: <b>".$v['line']."</b>\n";
                $tolog.=$v['line']."@".$v['file'].'@'.$msg;
                $tab.="  ";
                $c="|_";
        }
        echo $stacktrace;
        echo '</div>';
        echo "</pre>";
        error_log($tolog);
}

function call_stack()
{
        echo "<pre>";
        debug_print_backtrace();
        echo "</pre>";
}

set_exception_handler('exception_handler');

/**
 * Exception thrown when a resource is not found on an xml stream
 *
 * This exception is thrown by the stream system when loading an XML resource and
 * the file it doesn't exists or it cannot be found
 *
 */
class EResNotFound extends Exception
{
        /**
         * Construct the exception.
         *
         * @param string  $message Path to the resource that could not be found.
         * @param integer $code    The exception code.
         */
        function __construct($message = null, $code = 0)
        {
                $message=sprintf("Resource not found [%s]", $message);

       // make sure everything is assigned properly
       parent::__construct($message, $code);
        }
}

/**
 * Exception thrown when a component has the same name on the same owner
 *
 * This exception is usually thrown by the Name property when it detects there are
 * two objects that have the same Name
 *
 */
class ENameDuplicated extends Exception
{
        /**
         * Construct the exception.
         *
         * @param string  $message Name of the duplicated component.
         * @param integer $code    The exception code.
         */
        function __construct($message = null, $code = 0)
        {
                $message=sprintf("A component named %s already exists", $message);

       // make sure everything is assigned properly
       parent::__construct($message, $code);
        }
}

/**
 * Exception thrown when trying to assign an object to another
 *
 * This exception is thrown by the assign method when is impossible to assign the objects
 * you are trying to assign
 */
class EAssignError extends Exception
{
        /**
         * Construct the exception.
         *
         * @param string $sourcename Source object.
         * @param string $classname  Target object.
         */
        function __construct($sourcename, $classname)
        {
                $message=sprintf("Cannot assign a %s to a %s", $sourcename, $classname);

               // Makes sure everything is assigned properly
               parent::__construct($message, 0);
        }
}

/**
 * Exception thrown for Collection errors
 *
 * This exception is used by the Collection class when, for example, you are trying
 * to access an item specifying an index out of the bounds of the collection.
 *
 */
class ECollectionError extends Exception
{
        /**
         * Construct the exception.
         *
         * @param string  $message Name of the collection that was being read when the exception was triggered.
         * @param integer $code    The exception code.
         */
        function __construct($message = null, $code = 0)
        {
                $message=sprintf("List index out of bounds (%s)", $message);

       // Makes sure everything is assigned properly
       parent::__construct($message, $code);
        }
}

/**
 * A base class that reads/writes components from/to an xml stream
 *
 * This is an internal class used by the streaming system to load all objects
 * from an XML file. It uses the XML parser to read the file, creates the objects and
 * assign property values
 *
 * @link http://www.php.net/manual/en/ref.xml.php
 * @see Reader
 *
 */
class Filer extends Object
{
        protected $_xmlparser;
        protected $_root;
        protected $_lastread;
        protected $_parents;
        protected $_properties;
        protected $_lastproperty;
        protected $_rootvars;
        public $createobjects=true;
        private $incdata=false;

        /**
         * Initializes the object by setting up a list of parents and the xml parser used to read/write components
         * @param xmlparser $xmlparser xml parser to read/write components
         */
        function __construct($xmlparser)
        {
                //List of parents to provide a stack
                $this->_parents=new Collection();

                //TODO: Develop a TStringList class
                $this->_properties=array();

                //Root members, to initialize them with the right components
                $this->_rootvars=array();

                //Last component read
                $this->_lastread=null;


                //Last property read
                $this->_lastproperty=null;

                //The xml parser
                $this->_xmlparser=$xmlparser;
                xml_set_object($this->_xmlparser, $this);
                xml_set_element_handler($this->_xmlparser, "tagOpen", "tagClose");
                xml_set_character_data_handler($this->_xmlparser, "cData");
   }

        /**
         * Processes the opening tags to select which action to take
         *
         * @param xmlparser $parser xml parser in use
         * @param string    $tag    opening tag
         * @param array     $attributes attributes of the opening tag
         *
         * @see cData(), tagClose(), Component::readControlState()
         *
         * @link http://www.php.net/manual/en/ref.xml.php
         */
        function tagOpen($parser, $tag, $attributes)
        {
                $this->incdata=false;
                switch ($tag)
                {
                        case 'OBJECT': //Reads object parameters
                        $new=true;

                        //Class and name for that component
                        $class=$attributes['CLASS'];
                        $name=$attributes['NAME'];

                        //If there is a root component and it has not been read yet
                        if ((is_object($this->_root)) && (!is_object($this->_lastread)))
                        {
                                //And the class being read matches
                                if (($this->_root->classNameIs($class)) || ($this->_root->inheritsFrom($class)))
                                {
                                        //Reads the root properties and sets the lastread to the root
                                        $new=false;
                                        $this->_lastread=$this->_root;
                                        $this->_lastread->Name=$name;

                                }
                        }

                        //Creates a new object of the class just read
                        if ($new)
                        {
                                //If that class has been declared somewhere
                                if (class_exists($class))
                                {
                                        $this->_lastread=null;

                                        //Creates a new instance of that class
                                        if ($this->createobjects)
                                        {
                                                $this->_lastread=new $class($this->_root);

                                                //Gets the correct reference to the newly created component
                                                $this->_lastread=$this->_root->components->items[count($this->_root->components->items)-1];
                                        }
                                        else
                                        {
                                                if (array_key_exists($name,$this->_rootvars))
                                                {
                                                        $this->_lastread=$this->_rootvars[$name];
                                                }
                                                else
                                                {
                                                        echo "Error reading language resource file, object ($name) not found";
                                                }
                                        }

                                        $this->_lastread->ControlState=csLoading;
                                        $this->_lastread->Name=$name;


                                        //Finds the member of the root object and sets the reference
                                        if (array_key_exists($name,$this->_rootvars))
                                        {
                                                $this->_root->$name=$this->_lastread;
                                        }
                                        //TODO: Decide to dump here an error or not

                                        //Sets the parent
                                        if ($this->_lastread->inheritsfrom("Control"))
                                        {
                                                $this->_lastread->Parent=$this->_parents->items[count($this->_parents->items)-1];
                                        }

                                        //Pushes it onto the stack
                                        $this->_parents->add($this->_lastread);
                                }
                                else
                                {
                                        //TODO: Change this by an exception when possible, there's a bug in PHP 5, because the exception is raised inside an xml reader
                                        echo "Error reading resource file, class ($class) doesn't exists";
                                        //Throws new EResNotFound("Error reading resource file, class ($class) doesn't exists");
                                }
                        }
                        break;

                        case 'PROPERTY':
                        $new=true;
                        $name=$attributes['NAME'];

                        //If reading a property, must be inside an object
                        if (!is_object($this->_lastread))
                        {
                                echo "Error reading resource file, property ($name) doesn't have an object to assign to";
                        }
                        else
                        {
                                $this->_lastproperty=$name;
                                $this->_properties[]=$name;
                        }
                        break;

                        default: echo "Error reading resource file, tag ($tag) not recognized"; break;
                }
        }

        /**
         * Takes a UTF-8 string with HTML special characters and returns it in the internal
         * encoding.
         *
         * @internal
         */
        function VCLDecodeUnicode($orgstr)
        {
            if(!function_exists('mb_convert_encoding'))
            {
                return $orgstr;
            }

            $pattern = '/&#([0-9]+);/';
            preg_match_all($pattern, $orgstr, $matches);
            $size = count($matches[0]);
            if( $size <= 0 )
            {
              return $orgstr;
            }

            $rep_tbl = array();
            for($i = 0; $i < $size; $i++)
            {
              $dec_val = intval($matches[1][$i]); // &#yyyyy; -> yyyyy
              $utf8_str = '';
              if( $dec_val >= 0x0001 && $dec_val <= 0x007F )
              {
                $utf8_str .= chr($dec_val);
              }
              else
              {
                if( $dec_val > 0x07FF )
                {
                  $utf8_str .= chr(0xE0 | (($dec_val >> 12) & 0x0F));
                  $utf8_str .= chr(0x80 | (($dec_val >>  6) & 0x3F));
                  $utf8_str .= chr(0x80 | (($dec_val >>  0) & 0x3F));
                }
                else
                {
                  $utf8_str .= chr(0xC0 | (($dec_val >>  6) & 0x1F));
                  $utf8_str .= chr(0x80 | (($dec_val >>  0) & 0x3F));
                }
              }
            $rep_tbl[$matches[0][$i]] = $utf8_str;
          }
          $nwestr = strtr($orgstr, $rep_tbl);
          $internal_str = mb_convert_encoding($nwestr, mb_internal_encoding(), 'UTF-8');
          return $internal_str;
        }

        /**
         * Processes the data for tags
         * @param xmlparser $parser xml parser in use
         * @param string $cdata data to be processed
         *
         * @see tagOpen(), tagClose(), $use_html_entity_decode, safeunserialize()
         *
         * @link http://www.php.net/manual/en/ref.xml.php
         */
        function cData($parser, $cdata)
        {
                global $use_html_entity_decode;

                if ($use_html_entity_decode && strpos( $cdata, '&' ) !== false) {$cdata=html_entity_decode($cdata); $cdata = $this->VCLDecodeUnicode($cdata); }

                //Check if there is an object and a property
                if ((is_object($this->_lastread)) && ($this->_lastproperty!=null))
                {
                        $aroot=$this->_lastread;
                        $aproperty=$this->_lastproperty;

                        if (count($this->_properties)>1)
                        {
                                reset($this->_properties);

                                while (list($k,$v)=each($this->_properties))
                                {
                                        if ($v==$this->_lastproperty)
                                        {
                                                $aproperty=$v;
                                                break;
                                        }
                                        else
                                        {

                                                $am='get' . $v;
                                                $aroot=$aroot->$am();
                                        }
                                }
                        }

                                                $isarray=false;
                        //Getter
                        $method='get'.$aproperty;
                        //If there is a getter
                        if ($aroot->methodExists($method))
                        {
                                        $value=$aroot->$method();
                                        $isarray=is_array($value);

                        }

                        //dData function may be called multiple times by XML parser for one segment
                        if($this->incdata!=false) {
                          $cdata = $value . $cdata;
                        }
                        $this->incdata=true;

                        //Setter
                        $method='set'.$aproperty;

                        $value = null;

                        //If there is a setter
                        if ($aroot->methodExists($method))
                        {
                                //Sets the property
                                $value=$cdata;

                                if ($isarray)
                                {
                                        $value=safeunserialize($value);
                                }

                                $aroot->$method($value);

                        }
                        else
                        {
                                if (($aroot->inheritsFrom('Component')) && (!$aroot->inheritsFrom('Control')) && ($aproperty=='Left'))
                                {

                                }
                                else if (($aroot->inheritsFrom('Component')) && (!$aroot->inheritsFrom('Control')) && ($aproperty=='Top'))
                                {

                                }
                                else $aroot->handleUndefinedProperty($this->_lastproperty,$value);
                        }
                }
        }

        /**
         * Processes tag closing
         * @param xmlparser $parser xml parser in use
         * @param string $tag tag being closed
         *
         * @link http://www.php.net/manual/en/ref.xml.php
         *
         * @see cData(), tagOpen(), Component::readControlState(), Persistent::unserialize(),
         *      Component::loaded(), Component::preinit(), Component::init()
         *
         */
        function tagClose($parser, $tag)
        {
                $this->incdata=false;
                switch($tag)
                {
                        case 'PROPERTY':
                            // Pops last array element
                            array_pop($this->_properties);
                            $this->_lastproperty=null;
                            break;
                        case 'OBJECT':
                                //Pops the parent from the stack
                                $this->_parents->delete(count($this->_parents->items)-1);


                                //Calls the last read component
                                //TODO: Check if the last item from the stack is the right one to call loaded

                                $this->_lastread->ControlState=0;

                                if ($this->createobjects)
                                {
                                //Unserialize
                                if ($this->_lastread->inheritsFrom('CustomPage'))
                                {
                                        $this->_lastread->unserialize();
                                        $this->_lastread->unserializeChildren();
                                }

                                //Loaded
                                if ($this->_lastread->inheritsFrom('CustomPage'))
                                {
                                        $this->_lastread->loadedChildren();
                                        $this->_lastread->loaded();
                                }

                                //PreInit
                                if ($this->_lastread->inheritsFrom('CustomPage'))
                                {
                                        $this->_lastread->preinit();
                                }

                                //Init
                                if ($this->_lastread->inheritsFrom('CustomPage'))
                                {
                                        $this->_lastread->init();
                                }
                                }

                                /*
                                if ($this->_root->inheritsFrom('Page'))
                                {
                                        if (!$this->_root->UseAjax) $this->_lastread->loaded();
                                }
                                else
                                {
                                        $this->_lastread->loaded();
                                }
                                */

                                if (count($this->_parents->items)>=1) $this->_lastread=$this->_parents->items[count($this->_parents->items)-1];
                                else $this->_lastread=null;
                                break;
                }
        }

         /**
         * Root component
         *
         * This property specifies the root component for which all read components
         * are going to be owned.
         *
         * @return object
         */
        function getRoot() { return($this->_root); }
        function setRoot($value)
        {
                //TODO: Check here $value for null
                $this->_root=$value;
                //Gets the vars from the root object to get the pointers for the components
                $this->_rootvars=get_object_vars($this->_root);

                //Clears parents list and sets the root as the first parent
                $this->_parents->clear();
                $this->_parents->add($this->_root);

        }
}

/**
 * A class for reading components from an xml stream
 *
 * Inherits from Filer and provides a method to start the read process which will
 * create the components stored on the XML file and will assign all properties.
 *
 * @see Filer
 * @link http://www.php.net/manual/en/function.xml-parse.php
 */
class Reader extends Filer
{
        /**
         * Reads a component and all its children from a stream
         * @param object $root Root component to read
         * @param string $stream XML stream to read from
         */
        function readRootComponent($root,$stream)
        {
                $this->Root=$root;
                xml_parse($this->_xmlparser, $stream);

                $this->_root->ControlState=0;
        }
}

/**
 * A class for storing and managing a list of objects. This class acts as a wrapper over a PHP array.
 *
 * Collection, which stores an array of items, is often used to maintain lists of objects. Collection introduces
 * properties and methods to:
 *
 * Add or delete the objects in the list.
 *
 * Rearrange the objects in the list.
 *
 * Locate and access objects in the list.
 *
 * Sort the objects in the list.
 *
 * @link http://www.php.net/manual/en/ref.array.php
 */
class Collection extends Object
{
        //Items array
        public $items;

        function __construct()
        {
                //Initializes the array
                $this->clear();
        }

         /**
         * Inserts a new item at the end of the list.
         *
         * Call Add to insert a new object at the end of the array. Add returns
         * the index of the new item, where the first item in the list has an
         * index of 0.
         *
         * Note: Add always inserts the Item at the end of the array, even if
         * the internal position pointer of the array is at another position
         *
         * @see delete()
         *
         * @param mixed $item Object to add to the list
         * @return integer Number of items in the collection
         */
        function add($item)
        {
                //Sets the array to the end
                end($this->items);

                //Adds the item as the last one
                $this->items[]=$item;

                return($this->count());
        }

         /**
         * Deletes all items from the list
         *
         * Call Clear to empty the array and set the Count to 0. Clear also
         * frees the memory used to store the Items.
         *
         * @see add(), delete()
         *
         */
        function clear()
        {
                $this->items=array();
        }

         /**
         * Removes the item at the position given by the Index parameter.
         *
         * Call Delete to remove the item at a specific position from the list.
         * The index is zero-based, so the first item has an Index value of 0, the second
         * item has an Index value of 1, and so on. Calling Delete moves up all items in
         * the array that follow the deleted item, and reduces the Count.
         *
         * If the item is not in the list, an ECollectionError exception is raised.
         *
         * @see add(), clear(), remove()
         *
         * @param integer $index Index of the item to delete
         */
        function delete($index)
        {
                //Deletes the item from the array so the rest of items are reordered
                if ($index<$this->count())
                {
                        array_splice($this->items, $index, 1);
                }
                else
                {
                        throw new ECollectionError($index);
                }
        }

         /**
         * Returns the index of the first entry in the Items array with a specified value.
         *
         * Call IndexOf to get the index for an item in the array. Specify the item as the Item parameter.
         *
         * The first item in the array has index 0, the second item has index 1, and so on. If an item is
         * not in the list, IndexOf returns -1. If an item appears more than once in the array, IndexOf returns
         * the index of the first appearance.
         *
         * @param object $item Item to find
         * @return integer Index of the item or -1 if not found
         */
        function indexof($item)
        {
                $result=-1;

                reset($this->items);
                while (list($k,$v)=each($this->items))
                {
                        if ($v===$item)
                        {
                                $result=$k;
                                break;
                        }
                }

                return($result);
        }

        /**
         * Deletes the first reference to the Item parameter from the Items array.
         *
         * Call Remove to remove a specific item from the array when its index is unknown.
         * The value returned is the index of the item in the array before it was removed.
         * After an item is removed, all the items that follow it are moved up in index
         * position and the Count is reduced by one.
         *
         * If the array contains more than one copy of the pointer, only the first copy is deleted.
         *
         * @see delete()
         *
         * @param object $item Item to delete from the list
         * @return integer Index of the item removed or -1 if it's not found
         */
        function remove($item)
        {
                //Finds the pointer
                $index=$this->indexof($item);

                //Deletes the index if it exists
                if ($index>=0)
                {
                        $this->delete($index);
                }

                return($index);
        }

        /**
         * Indicates the number of entries in the list that are in use
         *
         * Call Count to determine the number of entries in the Items array.
         *
         * @see $items
         *
         * @return integer
         */
        function count()
        {
                return(count($this->items));
        }

         /**
         * Returns the last element from the collection.
         *
         * Call this method to get the last item added to the list, if the list is empty,
         * this method returns null
         *
         * @see $items
         *
         * @return object
         */
        function last()
        {
                if ($this->count()>=1)
                {
                        return($this->items[count($this->items)-1]);
                }
                else
                {
                        return(null);
                }
        }

}

global $methodCache;

$methodCache = array();

/**
 * A base class for persistent objects which are the ones which provide the required
 * features to be serialized/unserialized easily.
 *
 * If you want to create a component that has persistance capabilities, you can inherit
 * from this class to get all the mecanisms you need. The internal session handling uses
 * properties and methods found on this class to serialize/unserialize components to the session
 * and recover application state.
 *
 * @see serialize(), unserialize()
 *
 */
class Persistent extends Object
{
                /**
                 * Used to serialize/unserialize. It returns the full path to identify this component.
                 *
                 * @see className(), serialize(), unserialize()
                 *
                 * @return string
                 */
        function readNamePath()
        {
                $result=$this->className();

                if ($this->readOwner()!=null)
                {
                        $s=$this->readOwner()->readNamePath();

                        if ($s!="") $result = $s . "." . $result;

                }

                return($result);
        }

        /**
        * Owner of the component.
        *
        * In Persistent, it always returns null. In Component,
        * it returns the owner of the component if assigned.
        *
        * @return Component
        */
        function readOwner()
        {
                return(null);
        }

        /**
         * Assigns the source properties to this object.
         *
         * This method calls the assignTo method of the source component. If
         * $source is null, an assign error is raised.
         *
         * @see assignTo(), assignError()
         *
         * @param Persistent $source Object to get assigned to this object
         */
        function assign($source)
        {
                if ($source!=null) $source->assignTo($this);
                else $this->assignError(null);
        }

        /**
         * Assigns this object to another object.
         *
         * Override this method to implement the code required to copy one object
         * instance into this one.
         *
         * @see assign(), assignError()
         *
         * @param Persistent $dest Object to assign this object to
         */
        function assignTo($dest)
        {
                $dest->assignError($this);
        }

        /**
         * Performs the error reporting for undefined properties.
         *
         * This function should be called whenever there is an attempt to set a value of an
         * undefined property.
         *
         * @internal
         */
        function handleUndefinedProperty($propname, $propvalue)
        {
          echo "Error setting property (".$this->className()."::$propname), doesn't exists";
        }

        /**
         * Raises an assignment error.
         *
         * If $source is not null, the class name will be provided in addition to any other information.
         *
         * @see assign(), assignTo()
         *
         * @internal
         *
         * @param Persistent $source Target component.
         */
        function assignError($source)
        {
                if ($source!=null) $sourcename=$source->className();
                else $sourcename='null';

                throw new EAssignError($sourcename,$this->className());
        }

        /**
         * Stores this object into the session.
         *
         * It uses PHP reflection to get the published properties that will be stored. Object
         * persistance is achieved by iterating through all published properties (the ones for which
         * there is a getter method) and storing that value into the session.
         *
         * Those values are retrieved at the right time to recover the state of the object.
         *
         * To be serialized, objects need their owner to be unique on the session array. If an owner
         * is not set, an exception will be raised.
         *
         * @see readOwner(), readNamePath()
         * @link http://www.php.net/manual/en/language.oop5.reflection.php
         * @link http://www.php.net/manual/en/ref.session.php
         *
         * @throws    Exception       Object owner is not set.
         */
        function serialize()
        {
                $owner=$this->readOwner();

        if ($owner!=null)
        {

                $_SESSION['insession.'.$this->readNamePath()]=1;

                $refclass=new ReflectionClass($this->ClassName());
                $methods=$refclass->getMethods();

                reset($methods);

                while (list($k,$method)=each($methods))
                {
                        $methodname=$method->name;
                        if ($methodname[0] == 's' && $methodname[1] == 'e' && $methodname[2] == 't')   // fast check of: substr($methodname,0,3)=='set'
                        {
                                $propname=substr($methodname, 3);

                                if($propname=='Name')
                                    $propvalue = $this->_name;
                                else
                                    $propvalue=$this->$propname;

                                if (is_object($propvalue))
                                {
                                    if ($propvalue->inheritsFrom('Component'))
                                    {
                                        $apropvalue='';
                                        $aowner=$propvalue->readOwner();
                                        if ($aowner!=null) $apropvalue=$aowner->getName().'.';
                                        $apropvalue.=$propvalue->getName();
                                        $propvalue=$apropvalue;
                                    }
                                    else if ($propvalue->inheritsFrom('Persistent'))
                                    {
                                       $propvalue->serialize();
                                    }
                                }

                                if ((!is_object($propvalue))  && ($this->allowserialize($propname)))
                                {
                                        $defmethod='default'.$propname;

                                        if (method_exists($this,$defmethod))
                                        {
                                            $defvalue=$this->$defmethod();

                                            if (typesafeequal($defvalue,$propvalue))
                                            {
                                                unset($_SESSION[$this->readNamePath().".".$propname]);
                                                continue;
                                            }
                                        }

                                        //TODO: Optimize this
                                        $_SESSION[$this->readNamePath().".".$propname]=$propvalue;
                                }
                        }
                }


                if ($this->inheritsFrom('Component')) $this->serializeChildren();
        }
        else
        {
                global $exceptions_enabled;

                if ($exceptions_enabled)
                {
                        throw new Exception('Cannot serialize a component without an owner');
                }
        }
  }

  /**
  * This method provides an opportunity for the component developer to prevent the
  * serialization/unserialization of a property.
  *
  * Override the filter $propname, and return false if you do not want to allow a
  * property to be serialized.
  *
  * @see serialize()
  *
  * @param string $propname Name of the property
  * @return boolean True if the property can be serialized
  */
  function allowserialize($propname)
  {
    return(true);
  }

  /**
  * This method determines if this object exists in the current session.
  *
  * @see serialize(), readOwner(), readNamePath()
  *
  * @return boolean True if the component exists in the session
  */
  function inSession($name)
  {
    return(isset($_SESSION['insession.'.$this->readNamePath()]));
  }

  /**
   * This method uses PHP reflection to iterate through published properties (those for which there
   * is a getter method) and retrieve those stored by a previous call to serialize().
   *
   * To be unserialized, object need their owner to be unique on the session array. If the owner is
   * not set, an exception will be raised.
   *
   * @see serialize(), readOwner(), readNamePath(), Component::readControlState()
   * @link http://www.php.net/manual/en/language.oop5.reflection.php
   * @link http://www.php.net/manual/en/ref.session.php
   *
   * @throws    Exception       Object owner is not set.
   */
  function unserialize()
  {
        global $methodCache;

        $owner=$this->readOwner();

        if ($owner!=null)
        {
                if ($this->inheritsFrom('Component')) $this->ControlState=csLoading;

                $namepath=$this->readNamePath();

                $myclassName = $this->ClassName();

                if( isset( $methodCache[ $myclassName ] ) )
                {
                    $methods = $methodCache[ $myclassName ];
                }
                else
                {
                    $refclass=new ReflectionClass( $myclassName );
                    $methods=$refclass->getMethods();

                    $methods = array_filter( $methods, 'filterSet' );

                    array_walk( $methods, 'processMethods' );

                    $methodCache[ $myclassName ] = $methods;
                }

                $ourNamePath = $this->readNamePath();

                foreach( $methods as $methodname )
                {
                        $propname=substr($methodname, 3);

                        $fullname = $ourNamePath . '.' . $propname;
                        if (isset($_SESSION[$fullname]))
                        {
                                $this->$methodname($_SESSION[$fullname]);
                        }
                        else
                        {
                            $propname = 'get' . $propname;
                            $ob=$this->$propname();
                            if (is_object($ob))
                            {
                              if ($ob->inheritsFrom('Persistent'))
                              {
                                $ob->unserialize();
                              }
                            }
                        }
                }

                if ($this->inheritsFrom('Component')) $this->ControlState=0;
        }
        else
        {
                global $exceptions_enabled;

                if ($exceptions_enabled)
                {
                        throw new Exception('Cannot unserialize a component without an owner');
                }
        }
  }
}


/**
 * Component is the common ancestor of all component classes.
 *
 * A base class for components that provides owner relationship properties and
 * basic methods for calling events.
 *
 * Non-visible components must inherit from Component and not from Control. The
 * IDE automatically handles the component as iconic.
 *
 * Components are persistent objects that have the following capabilities:
 *
 * IDE integration. The ability to appear on an IDE palette and be manipulated in a form designer.
 *
 * Ownership. The ability to manage other components. If component A owns component B, then A is responsible
 * for destroying B when A is destroyed.
 *
 * Streaming and filing. Enhancements of the persistence features inherited from Persistent.
 *
 * Component does not provide any user interface or display features. These features are provided by
 * two classes that directly descend from Control.
 *
 * Control, in the controls.inc.php unit, is the base class for "visual" components in visual applications.
 *
 * Components that can be visible at runtime are sometimes called "visual components". Other components, which are never
 * visible at runtime, are sometimes called "non-visual components". However it is more common to refer to "visual components" as
 * "controls" and "non-visual components" simply as "components."
 *
 * Do not create instances of Component. Use Component as a base class when declaring non-visual components that
 * can appear on the component palette and be used in the form designer. Properties and methods of Component
 * provide basic behavior that descendant classes inherit as well as behavior that components can override to
 * customize their behavior.
 *
 * @see Persistent
 */
class Component extends Persistent
{
        public $owner;
        public $components;
        public $_name;
        public $lastresourceread="";
        public $reallastresourceread=array();
        public $alreadycreated=false;
        public $_controlstate=0;

        protected $_onbeforeshow=null;
        protected $_onaftershow=null;
        protected $_onshow=null;

        public $_childnames = array();

        public $_tag=0;

        protected $_namepath = '';

        /**
        * Component constructor.
        *
        * It initializes the component, and saves its owner and children components.
        *
        * @see $components, readOwner(), insertComponent()
        *
        * @param        Component       $aowner The owner of this component.
        */
        function __construct($aowner=null)
        {
                //Calls the inherited constructor
                parent::__construct($aowner);

                //List of children
                $this->components=new Collection();

                //Initializes the owner
                $this->owner=null;

                //Initializes the name
                $this->_name="";

                $this->_controlstate=0;

                if ($aowner!=null)
                {
                        //If there is an owner
                        if (is_object($aowner))
                        {
                                //Stores it
                                $this->owner=$aowner;

                                //Adds itself to the list of components from the owner
                                $this->owner->insertComponent($this);
                        }
                        else
                        {
                                throw new Exception("Owner must be an object");
                        }
                }

        }

        /**
        * Initializes the component after the form file has been read into memory.
        *
        * Do not call the Loaded method. The streaming system calls this method after it
        * loads the component's form from a stream.
        *
        * When the streaming system loads a form or data module from its form file,
        * it first constructs the form component by calling its constructor, then reads
        * its property values from the form file. After reading all the property values
        * for all the components, the streaming system calls the Loaded methods of each
        * component in the order the components were created. This gives the components a
        * chance to initialize any data that depends on the values of other components or
        * other parts of itself.
        *
        * Note: All references to sibling components are resolved by the time Loaded is called.
        * Loaded is the first place that sibling pointers can be used after being streamed in.
        *
        * As for the reading operation, the reader sets the ComponentState in csLoading
        *
        * Warning: Loaded may be called multiple times on inherited forms. It is called every time
        * a level of inheritance is streamed in.
        *
        * @see init(), preinit(), loadedChildren()
        * @link wiki://RPCL_Components_Lifecycle
        * @internal
        *
        */
        function loaded()
        {

        }

        /**
         * Iterates through any component in the page, and calls its loaded() method.
         *
         * @see $components, loaded()
         *
         * @internal
         */
        function loadedChildren()
        {
                reset($this->components->items);
                while (list($k,$v)=each($this->components->items))
                {
                        $v->loaded();
                }
        }

        /**
         * Dumps javascript code for an event
         *
         * This method dumps a javascript named $event. This function is called when generating the
         * page code in the header to create all the functions to hold the javascript code
         * written by the user.
         *
         * This method is interesting for you if you are a component developer, as you can use it
         * to generate javascript code for an event
         *
         * @param string $event Name of the event you want to generate
         */
        function dumpJSEvent($event)
        {
                if ($event!=null)
                {
                        if ($this->owner!=null)
                        {
                        if (!defined($this->owner->Name.'_'.$event))
                        {
                                define($this->owner->Name.'_'.$event,1);

                                if ($this->inheritsFrom('CustomPage'))
                                {
                                    if($this->methodExists($event))
                                    {
                                      echo "function $event(event)\n";
                                      echo "{\n\n";
                                      echo "   var event = event || window.event;\n";            //To get the right event object
                                      echo "   var params=null;\n";                               //For Ajax calls

                                      $this->$event($this, array());

                                      echo "\n}\n";
                                      echo "\n";
                                    }
                                }
                                else
                                {
                                    if ($this->owner!=null)
                                    {
                                      if ($this->owner->methodExists($event))
                                      {
                                        echo "function $event(event)\n";
                                        echo "{\n\n";
                                        echo "  var event = event || window.event;\n";            //To get the right event object
                                        echo "  var params=null;\n";                               //For Ajax calls

                                        $this->owner->$event($this, array());
                                        echo "\n}\n";
                                        echo "\n";
                                      }
                                    }
                                }
                        }
                        }
                }
        }

        /**
         * Prints client-side code that should be executed once the DOM is loaded on the user agent.
         *
         * When developing a component, override this method to generate all the JavaScript code your component
         * needs. You should call bindJSEvent() for each client-side event inside of this method, when
         * overriding.
         *
         * @see bindJSEvent()
         *
         * @internal
         */
        function pagecreate()
        {
            // This function is overriden in child components.
        }

        /**
         * Returns the javascript code to bind a js event
         *
         * @param string $event Name of the event you want to bind
         * @param string $element optional parameter to replace element, example: "$('#Media1').siblings()"
         */
        function bindJSEvent($event, $element = null)
        {
          return $this->bindjQueryEvent($event, $element = null);
        }

        /**
         * Returns the javascript code to bind a js event with JQuery
         *
         * @param string $event Name of the event you want to bind in jQuery
         * @param string $element optional parameter to replace element, example: "$('#Media1').siblings()"
         */
        function bindjQueryEvent($event, $element = null)
        {
          $jsEvent="json$event";

            $output = "";

            if($this->$jsEvent != "")
            {
               if(!$element)
                $element="$('#{$this->_name}')";

                $output = "\t$element.on('$event', {$this->$jsEvent});\n";
            }

           return $output;
        }

        /**
         * Resolves the right reference to an object property
         *
         * This method returns the right object (or the input string if object not found) for an object name.
         * Use on the loaded method for object properties to find the right reference.
         *
         * When properties are loaded from the stream, object properties are set with the Name of the component to
         * which they must link. Those properties are strings on that moment, to get the right reference to the object,
         * you can use this method to make the link.
         *
         * If you pass the className and want to use the object in design time,
         * maybe you want to use csRenderAlso=className in constructor
         *
         * @param mixed $value string or object to set the property to
         * @param string $csDesignClass the class type to check with
         *
         * @link wiki://Component_Writer%27s_Guide_::_Adding_properties
         *
         * @see Component::readControlState(), loaded(), init()
         */
        function fixupProperty($value, $csDesignClass = '')
        {

              if (($this->ControlState & csDesigning)!=csDesigning)
              {
                      if (!empty($value))
                      {
                              if (!is_object($value))
                              {
                              		if ($this->inheritsFrom('CustomPage')) $form=$this;
                                   else $form=$this->owner;

                                      if (strpos($value,'.'))
                                      {
                                              $pieces=explode('.',$value);
                                              if (count($pieces)==2)
                                              {
                                                      $form=$pieces[0];
                                                      $value=$pieces[1];
                                              }
                                              else if (count($pieces)==3)
                                              {
                                                      $form=$pieces[1];
                                                      $value=$pieces[2];
                                              }

                                             global $$form;

                                              $form=$$form;
                                      }
                                      if (is_object($form->$value))
                                      {
                                            $value=$form->$value;
                                      }
                              }
                      }
              }
              else
              {

                // design mode:  go to check
                if($csDesignClass != '' && $value != "")
                {
                  try
                  {
                    // verify if the object exists in the page and it's class
                    if(get_class($this->Owner->$value) == $csDesignClass)
                      return $value;
                    else
                      return "";
                  }
                  catch(Exception $e)
                  {
                    return "";
                  }
                }

              }

              return($value);
        }



        /**
         * Unserializes all children by calling unserialize for all the components
         *
         * This method iterates the components property and calls the unserialize()
         * method of each one, to recover the state of all published properties from
         * the session.
         *
         * This is used to ensure the persistance of the status of the application.
         *
         * @see unserialize()
         *
         */
        function unserializeChildren()
        {
                reset($this->components->items);
                while (list($k,$v)=each($this->components->items))
                {
                        $v->unserialize();
                }
        }


        /**
         * Calls a server event.
         *
         * This method provides you an easy way to fire an
         * event in your component and check if it is assigned.
         *
         * Example: $this->callEvent($this->_onclick, array());
         *
         * You can send any params you want the user of your component to receive.
         * This method is useful if you are a component developer and you can use it
         * to fire your server events easily, as it performs any check is needed to
         * call the right event.
         *
         * @link wiki://Component_Events
         *
         * @param string $event Name of the event to call
         * @param mixed $params Parameters to send to the event handler
         * @return mixed Calling event result, if the event handler returns something
         */
        function callEvent($event,$params)
        {
                //Don't execute any event if we are requesting the CSS or the JS
                if ((isset($_GET['css'])) || (isset($_GET['js']))) return;

                //Prevent calling events if the current request is a xajax call and the POST hasn't been processed.
                // @see ajaxProcess function in forms.inc.php
                if(isset($_POST['xjxr']) && isset($_POST['xjxargs']) && isset($_POST['xjxfun']))
                {
                    if(! defined('XAJAX_POST_PROCESSED'))
                        return;
                }

                //Prevent event executions for this component
                if (!$this->inheritsFrom('CustomPage'))
                {
                  if (!acl_isallowed($this->className().'::'.$this->Name, "Execute")) return;
                }

                $result=null;
                $ievent="_".$event;

                if ($this->$ievent!=null)
                {
                        $event=$this->$ievent;
                        if (!$this->owner->classNameIs('application'))
                        {
                                return($this->owner->$event($this,$params));
                        }
                        else return($this->$event($this,$params));
                }
                return($result);
        }

        /**
         * Returns the javascript code to generate an ajax call.
         *
         * This method returns the js event attribute to call the server using Ajax.
         * Use xajax to handle everything related to ajax. This method is useful for you if
         * you are a component developer and want to implement ajax easily in your component.
         *
         * @see ajaxCall()
         *
         * @param string $jsevent  javascript event
         * @param string $phpevent php event to call
         * @return string Event attribute to add to your tag
         */
        function generateAjaxEvent($jsevent, $phpevent)
        {
                $result=" $jsevent=\"xajax_ajaxProcess('".$this->owner->Name."','".$this->Name."',null,'$phpevent',xajax.getFormValues('".$this->owner->Name."'))\" ";

                return($result);
        }

        //TODO: Fully implement the parameter exchange.
        /**
         * Dumps the JavaScript code to make an AJAX call to the server-side version of the script.
         *
         * Print the output of this method in your JavaScript events to generate the required
         * JavaScript code to perform an AJAX call to a PHP method on the server-side version of the
         * script, and update the client-side version with the data resulted from the execution of
         * that PHP method.
         *
         * By default, all components are updated on the client after executing the PHP method on
         * the server. You can save bandwidth by passing the name of the components involved on the
         * PHP method on the third parameter.
         *
         * @link wiki://RPCL_AJAX#Usage
         *
         * @see Page::getUseAjax()
         * @see Page::getUseAjaxDebug()
         * @example Ajax/Basic/basicajax.php How to use ajaxCall
         *
         * @param  string $phpEventhandlers     PHP event handler to be called on the server-side script.
         * @param  array  $phpParameters        Parameters to be passed to the PHP event handler.
         * @param  array  $involvedComponents   Names of the components involved on the execution of the PHP event handler.
         * @return string                       JavaScript code to perform the AJAX call.
         */
        function ajaxCall($phpEventhandlers, $phpParameters=array(), $involvedComponents=array())
        {
                $jcomps="";

                reset($involvedComponents);
                while(list($key, $val)=each($involvedComponents))
                {
                    if ($jcomps!='') $jcomps.=',';
                    $jcomps.='"'.$val.'"';
                }

                $jcomps="[$jcomps]";

                $result =" xajax_ajaxProcess('".$this->owner->Name."','".$this->Name."',params,'$phpEventhandlers',xajax.getFormValues('".$this->owner->Name."'),$jcomps);\n ";

                return($result);
        }

        /**
         * Method called before init().
         *
         * The streaming system calls this method before calling init(), so if you need to
         * perform a process before, this is the right method to override.
         *
         * @see $components
         * @see init()
         * @see loaded()
         * @internal
         *
         */
        function preinit()
        {
                //Calls children's init recursively
                reset($this->components->items);
                while (list($k,$v)=each($this->components->items))
                {
                        $v->preinit();
                }
        }

        /**
         * Initializes a component
         *
         * This method is the right method to override if you want to fire events
         * after a server request, because all components have been loaded, all properties
         * have been fixed up and no output has been dump yet.
         *
         * @see $components, preinit(), loaded()
         */
        function init()
        {
                //Copy components to comps, so you can alter components properties
                //on init() methods
                $comps=$this->components->items;
                //Calls children's init recursively
                reset($comps);
                while (list($k,$v)=each($comps))
                {
                        $v->init();
                }
        }

        /**
        * Updates the field on the dataset attached, if any
        *
        * Checks if there is any datafield attached to the component.
        * If so, sets the dataset in edit state and all the fields with
        * the appropiate values so the dataset is able to update the
        * right record.
        *
        * Properties for data-aware components must be named
        *
        * DataField
        *
        * DataSource
        *
        * This is for basic single-field data-aware controls. For more
        * complicated controls like DBGrid, each component must create
        * its own mechanism to update information in the database.
        *
        * @link wiki://RPCL_Components_Data-Awareness
        * @see hasValidDataField()
        * @param mixed $value Value to use to update the DataField
        *
        *
        */
        function updateDataField($value)
        {
                if ($this->_datafield!="")
                {
                        if ($this->_datasource!=null)
                        {
                                if ($this->_datasource->Dataset!=null)
                                {
                                        //Checks for the index fields
                                        $keyfields=$this->Name."_key";
                                        $keys=$this->input->$keyfields;
                                        // Checks if the keys were posted
                                        if (is_object($keys))
                                        {
                                                $fname=$this->DataField;

                                                //Sets to Edit State
                                                $this->_datasource->Dataset->edit();


                                                $values=$keys->asStringArray();

                                                //Sets the key values
                                                reset($values);
                                                while (list($k,$v)=each($values))
                                                {
                                                        $this->_datasource->Dataset->fieldset($k,$v);
                                                }

                                                //Sets the field value
                                                $this->_datasource->Dataset->fieldset($fname,$value);
                                        }
                                        else $this->_datasource->Dataset->fieldset($this->_datafield,$value);
                                }
                        }
                }
        }

        /**
        * Returns true if a valid data field is attached to the component
        *
        * Use this method if you want to know if there is a valid data field attached
        * to the component. For that, datafield property must be assigned, datasource must be
        * assigned also, and datasource must have a dataset assigned.
        *
        * @link wiki://RPCL_Components_Data-Awareness
        *
        * @see updateDataField()
        *
        * @return boolean True if the component has a valid data field attached
        *
        */
        function hasValidDataField()
        {
                $result=false;

                if ($this->_datafield!="")
                {
                        if ($this->_datasource!=null)
                        {
                                if ($this->_datasource->Dataset!=null)
                                {
                                        $result=true;
                                }
                        }
                }

                return($result);
        }

        /**
        * This property returns the value of the datafield if any.
        *
        * Use this property to get the value to the attached datafield, if any.
        * If not datatafield assigned, this property returns false.
        *
        * @link wiki://RPCL_Components_Data-Awareness
        *
        * @see hasValidDataField()
        *
        * @return mixed Value for the data field attached
        */
        function readDataFieldValue()
        {
                $result=false;
                if ($this->hasValidDataField())
                {
                        $fname=$this->DataField;
                        $value=$this->_datasource->Dataset->fieldget($fname);
                        $result=$value;
                }
                return($result);
        }

        /**
        * Dumps hidden field values for the key record
        *
        * This function dumps out the key fields for the current row. This is useful
        * for sending information about the current register.
        *
        * Use this method when developing data-aware components to set a mark to
        * an specific register on the attached dataset. This can be useful to get those
        * values when the form is posted to the server.
        *
        * @link wiki://RPCL_Components_Data-Awareness
        *
        * @see Dataset::dumpHiddenKeyFields()
        * @param boolean $force If true, hidden keys will be dumped, no matter the state of the dataset
        *
        */
        function dumpHiddenKeyFields($force=false)
        {
                if ($this->_datasource!=null)
                {
                        if ($this->_datasource->Dataset!=null)
                        {
                                if (($this->_datasource->Dataset->State!=dsInsert) || ($force))
                                {
                                    //Dumps the key values for this record so updating is possible in the future
                                    $this->_datasource->Dataset->dumpHiddenKeyFields($this->Name."_key");
                                }
                        }
                }
        }

        /**
         * Serializes all children
         *
         * This method iterates through all the children and calls serialize for each one.
         * Serializing stores published properties on the session with an specific format
         * so it can restore them on the next request, that way, recovering application state
         * and emulating desktop applications.
         *
         * @see $components, serialize()
         *
         */
        function serializeChildren()
        {
                //Calls children's serialize recursively
                reset($this->components->items);
                while (list($k,$v)=each($this->components->items))
                {
                        $v->serialize();
                }
        }

        /**
         * Dumps JavaScript code required by the component.
         *
         * When developing a component, override this method to dump all the JavaScript code required for your
         * component to work.
         *
         * @see dumpHeaderCode()
         *
         * @internal
         */
        function dumpJavascript()
        {
                //Do nothing yet
        }

        /**
         * Dumps code in the header of the final webpage required by the component.
         *
         * When developing a component, override this method to dump all the code you need to place in the header in
         * order to get your component to work.
         *
         * For example:
         *
         * <code>
         * function dumpJavascript() {
         *   parent::dumpJavascript(); // You must call the parent function at the beginning.
         *   echo "function sayHello() { alert('Hello!); }\n\n";
         * }
         * </code>
         *
         * @see dumpJavascript()
         *
         * @internal
         */
        function dumpHeaderCode()
        {
                // Do nothing yet.
        }

        /**
         * Dumps the JavaScript code for all the children components.
         *
         * @see $components, dumpJavascript()
         */
        function dumpChildrenJavascript()
        {
                //Iterates through components, dumping all javascript
                $this->dumpJavascript();
                reset($this->components->items);
                while (list($k,$v)=each($this->components->items))
                {
                        if ($v->inheritsFrom('Control'))
                        {
                                if ($v->canShow())
                                {
                                        $v->dumpJavascript();
                                }
                        }
                        else $v->dumpJavascript();
                }
        }

        /**
         * Dumps the header code for all the children
         *
         * @see $components, dumpHeaderCode()
         * @link http://www.php.net/manual/en/ref.outcontrol.php
         *
         * @param boolean $return_contents If true, code is returned instead be dumped
         * @return string Children header code if $return_contents is true
         */
        function dumpChildrenHeaderCode($return_contents=false)
        {
                //Iterates through components, dumping all javascript
                reset($this->components->items);

                if ($return_contents) ob_start();
                while (list($k,$v)=each($this->components->items))
                {
                        if ($v->inheritsFrom('Control'))
                        {
                                if ($v->canShow())
                                {
                                        $v->dumpHeaderCode();
                                }
                        }
                        else $v->dumpHeaderCode();
                }
                if ($return_contents)
                {
                        $contents=ob_get_contents();
                        ob_end_clean();
                        return($contents);
                }
        }

        /**
        * Dumps code just after the form tag, useful to dump hidden fields for
        * state retrieving for non visible components
        *
        * This method is useful for component developers and is not intended
        * to be called by an application developer.
        *
        * Component developers may override this method to provide specific code
        *
        */
        function dumpFormItems()
        {
                //Override
        }

        /**
        * This method is called by the page just after dumping the starting form
        * tag.
        *
        * Provides an opportunity for a component developer to dump hidden fields
        * (or other stuff) on that section of the page. Is also used by templates
        * to get that code.
        *
        * @param boolean $return_contents If true, the form items will be returned as string
        * @return mixed If $return_contents is true, it will return a string
        */
        function dumpChildrenFormItems($return_contents=false)
        {
                //Iterates through components, dumping all form items
                reset($this->components->items);

                if ($return_contents) ob_start();
                while (list($k,$v)=each($this->components->items))
                {
                        if ($v->inheritsFrom('Control'))
                        {
                                if ($v->canShow())
                                {
                                        $v->dumpFormItems();
                                }
                        }
                        else $v->dumpFormItems();
                }
                if ($return_contents)
                {
                        $contents=ob_get_contents();
                        ob_end_clean();
                        return($contents);
                }
        }

        /**
         * Loads this component from a string.
         *
         * @see readFromResource()
         *
         * @param string $filename  xml file name
         * @param boolean $inherited specifies if we are going to read an inherited resource
         * @param boolean $storelastresource If true, the component stores the name of the last resource read
         */
        function loadResource($filename, $inherited=false, $storelastresource=true)
        {
           global $application;

           //$start=microtime(true);

           if (!isset($_SESSION['comps.'.$this->readNamePath()]) && !$inherited)
           {
                   if ($this->inheritsFrom('Page'))
                   {
                        if ($this->classParent()!='Page')
                        {
                                $varname=$this->classParent();
                                global $$varname;
                                $baseobject=$$varname;
                                $this->loadResource($baseobject->lastresourceread, true);
                        }
                   }
           }

           if ($storelastresource)
           {
            $this->lastresourceread=$filename;
           }
           //TODO: Check here for the path to the resource file
           //$resourcename=basename($filename);
           $resourcename=$filename;
           $l="";

           if ((($application->Language!='')) || (($this->inheritsFrom('Page')) && ($this->Language!='(default)')))
           {
                $resourcename=str_replace('.php',$l.'.xml.php',$filename);
                $this->readFromResource($resourcename);

                $l=".".$application->Language;
                $resourcename=str_replace('.php',$l.'.xml.php',$filename);
                if (file_exists($resourcename))
                {
                        $this->readFromResource($resourcename, false, false);
                }
           }
           else
           {
                   $resourcename=str_replace('.php',$l.'.xml.php',$resourcename);
                   $this->readFromResource($resourcename);
           }
           //$finish=microtime(true);
           //echo 'loading:'.($finish-$start);
        }

        /**
         * Reads a component from a resource file
         *
         * @see Reader, Filer
         *
         * @param string $filename Filename of the resource file
         * @param boolean $createobjects Specifies if create the objects found or just read properties
         */
        function readFromResource($filename="", $createobjects=true)
        {
                $readfromfile=true;

                global $application;

                $path=$application->Name.'.'.$this->className();

                //If there are components in the session, don't read from the resource file
                if (isset($_SESSION['comps.'.$path]))
                {
                        $readfromfile=false;
                }

                //Recover last resources read from the session
                if (isset($_SESSION[$path.'._reallastresourceread']))
                {
                        $this->reallastresourceread=$_SESSION[$path.'._reallastresourceread'];
                }

                //If there is a file to read and has not been read yet
                if (($filename!="") && (!in_array($filename,$this->reallastresourceread)))
                {
                        $this->reallastresourceread[]=$filename;
                        $readfromfile=true;
                }

                if ((!$readfromfile) && (!$this->alreadycreated))
                {
                        $this->alreadycreated=true;
                    global $checkduplicatenames;
                    $form_fields=get_object_vars($this);

                    $checkduplicatenames=false;

                    $this->Name=$this->className();
                    $components=$_SESSION['comps.'.$application->Name.'.'.$this->className()];

                    reset($components);
                    while(list($name, $classparts)=each($components))
                    {
                        $class=$classparts[1];
                        $parent=$classparts[0];
                        $var=new $class($this);
                        //$var->setTag($name);
                        $var->setName($name);
                        if (array_key_exists($name,$form_fields)) $this->$name=$var;
                        if ($parent!='')
                        {
                            if ($parent==$this->Name) $var->parent=$this;
                            else
                            {
                                $var->parent=$var->fixupProperty($parent);
                            }
                        }
                    }

                    $this->unserialize();
                    $this->unserializeChildren();
                    $this->loadedChildren();
                    $this->loaded();
                    $this->preinit();
                    $this->init();
                }
                else
                {
                //Default filename
                if ($filename=="") $filename=strtolower($this->className()).".xml.php";

                if ($filename!="")
                {
                        if (file_exists($filename))
                        {
                                //Reads the component from an xml stream
                                $xml=xml_parser_create("UTF-8");
                                $reader=new Reader($xml);
                                $filelines=file($filename);

                                array_shift($filelines);
                                array_pop($filelines);

                                $file=implode('',$filelines);

                                $reader->createobjects=$createobjects;

                                $reader->readRootComponent($this, $file);
                        }
                        else
                        {
                                global $exceptions_enabled;

                                if ($exceptions_enabled) throw new EResNotFound($filename);
                        }
                }
                }
        }

         /**
         * Inserts a component into the component's collection
         *
         * @see $components, readOwner()
         *
         * @param object $acomponent Component to insert
         */
        function insertComponent($acomponent)
        {
                //Adds a component to the components list
                $acomponent->owner=$this;

                $this->_childnames[ $acomponent->_name ] = $acomponent;

                $this->components->add($acomponent);
        }

        /**
         * Removes a component from the component's collection
         *
         * @see $components, readOwner()
         *
         * @param object $acomponent Component to remove
         */
        function removeComponent($acomponent)
        {
                //Removes a component from the component's list
                $this->components->remove($acomponent);

                unset( $this->_childnames[ $acomponent->_name ] );
        }


        /**
        * Lists all the components owned by this component.
        *
        * Use Components to access any of the components owned by this component,
        * such as the components owned by a form. The Components property is most
        * useful when referring to owned components by number rather than name.
        * It is also used internally for iterative processing of all owned components.
        *
        * Index ranges from 0 to ComponentCount minus 1.
        *
        * @see $components, readOwner()
        *
        *
        * @return Collection
        */
        function readComponents() { return $this->components; }

        /**
        * Indicates the number of components owned by the component.
        *
        * Use ComponentCount to find or verify the number of components owned by
        * a component, or when iterating through the Components list to perform
        * some action on all owned components. ComponentCount is used internally
        * for such iterative procedures.
        *
        * Note: The ComponentCount of a component contains the same number of items
        * as in the Components list for that component, and is always 1 more than the
        * highest Components index, because the first Components index is always 0.
        *
        *
        * @see $components, readOwner()
        *
        * @return integer
        */
        function readComponentCount() { return $this->components->count(); }

        /**
         * A flag indicating the state of the control.
         *
         * Its possible values are:
         * - csDesigning. The component is being rendered on the Designer (as opposed to a web browser).
         * - csLoading. The component is being loaded from a resource file or the PHP session.
         *
         * You can for example use this property to render a component differently at design time:
         *
         * <code>
         * if (($this->ControlState & csDesigning)==csDesigning)
         * {
         *    //Write the design-time code here
         * }
         * </code>
         *
         * @link wiki://RPCL_Components_Control_States
         * @link wiki://RPCL_Components_HTML5_Builder_Integration#Conditional_Rendering
         *
         * @internal
         *
         * @return integer
         */
        function readControlState() { return $this->_controlstate; }
        function writeControlState($value) { $this->_controlstate=$value; }

        /**
        * Specifies the path to uniquely identify a component, qualified by the owner when required.
        *
        *
        * @see readOwner()
        *
        * @return string
        */
        function readNamePath()
        {
                $result='';

                if ($this->Name!='')
                {
                    $result=$this->Name;

                    if ($this->readOwner()!=null)
                    {
                            $s=$this->readOwner()->readNamePath();
                            if ($s!="")
                            {
                                $result = $s . "." . $result;
                            }

                    }
                }
                else
                {
                    $result=$this->className();

                    if ($this->readOwner()!=null)
                    {
                            $s=$this->readOwner()->readNamePath();
                            if ($s!="")
                            {
                                $result = $s . "." . $result;
                            }

                    }
                }

                return($result);

            //return($this->_name);
        }

        /**
        * Indicates the component that is responsible for streaming and freeing this component.
        *
        * Use Owner to find the owner of a component. The Owner of a component is responsible
        * for two things:
        *
        *
        * The memory for the owned component is freed when its owner's memory is freed.
        * This means that when a form is destroyed, all the components on the form are also destroyed.
        *
        *
        * The Owner is responsible for loading and saving the published properties of its owned controls.
        *
        * By default, a form owns all components that are on it. In turn, the form is owned by the
        * application. Thus when the application shuts down and its memory is freed, the memory for all
        * forms (and all their owned components) is also freed. When a form is loaded into memory,
        * it loads all of the components that are on it.
        *
        *
        * The owner of a component is determined by the parameter passed to the constructor when the
        * component is created. For components created in the form designer, the form is automatically
        * assigned as the Owner.
        *
        * Warning: If a component has an Owner other than a form or data module, it will not be saved or
        * loaded with its Owner.
        *
        * @link wiki://Developer%27s_Guide_::_Owner_Parent
        * @see readComponents(), readComponentCount()
        *
        * @return       Component
        */
        function readOwner() { return($this->owner); }

        //Published properties

        /**
        * Specifies the name for the component. The name is used as an identifier and should be unique.
        *
        * Use Name to change the name of a component to reflect its purpose in the current application.
        * By default, the IDE assigns sequential names based on the type of the component, such as 'Button1', 'Button2',
        * and so on.
        *
        * @see readOwner(), ENameDuplicated
        *
        * @return string
        */
        function getName() { return $this->_name; }
        function setName($value)
        {
                global $checkduplicatenames;

                if ($checkduplicatenames)
                {
                    //TODO: If there is an owner, checks that there are no other components with the same name
                    if ($value!=$this->_name)
                    {
                        if ($this->owner!=null)
                            {
                                if (!$this->owner->classNameIs('application'))
                                    {
                                            if( isset( $this->owner->_childnames[ $value ] ) )
                                                throw new ENameDuplicated($value);

                                            if( $this->_name )
                                                unset( $this->owner->_childnames[ $this->_name ] );

                                            $this->_name=$value;
                                    }
                                    else $this->_name=$value;
                            }
                            else $this->_name=$value;
                    }
                }
                else
                {
                    $this->_name=$value;
                }

                if( $this->owner != null )
                    $this->owner->_childnames[ $value ] = $this;
        }
        function defaultName() { return(""); }

        /**
        * A versatile property of every Component that can be used in any way you want
        *
        * Tag has no predefined meaning. The Tag property is provided for the convenience
        * of developers. It can be used for storing an additional value.
        *
        * <code>
        * <?php
        *      function Button1Click($sender, $params)
        *      {
        *               //All three buttons OnClick event is assigned to this
        *               //event handler, and to check which one has been pressed
        *               //you can use $sender and Tag property
        *               switch($sender->Tag)
        *               {
        *                       case 1: echo "Button 1 clicked!"; break;
        *                       case 2: echo "Button 2 clicked!"; break;
        *                       case 3: echo "Button 3 clicked!"; break;
        *               }
        *      }
        *
        * ?>
        * </code>
        * @return mixed
        */
        function getTag() { return $this->_tag; }
        function setTag($value) { $this->_tag=$value; }
        function defaultTag() { return 0; }

        /**
         * Dumps the code for the control to the output.
         *
         * Shows control contents. You can use this method to show the contents of
         * the control, optionally, by setting $return_contents to true. You can get the
         * contents of the control returned  instead of having it dumped to the output.
         *
         * This method also checks if the global var has $output_enabled. You can globally
         * disable output of controls by setting that var to false.
         *
         * This method is also responsible to call beforeshow and aftershow events
         *
         * @see $output_enabled, callEvent(), readOnBeforeShow(), dumpContents(), readOnAfterShow()
         *
         * @param boolean $return_contents return contents as string or dumps to output
         * @return mixed If $return_contents is true, it will return the control contents, void otherwise
         */
        function show($return_contents=false)
        {
                acl_addresource($this);
                if (!$this->inheritsFrom('Page'))
                {
                  if (!acl_isallowed($this->className().'::'.$this->Name, "Show")) return;
                }

                global $output_enabled;

                //TODO: Check this, as show is being called twice (or three times) for ?css=1 and ?js=1
                if ($output_enabled)
                {
                        $this->callEvent('onbeforeshow',array());
                        //A call to show, will dump out control code
                        if ($return_contents) ob_start();

                        if (!$this->beginCache('contents'))
                        {
                          //If the CSS is requested, we call showCSS instead dumpContents
                          if (isset($_GET['css'])) $this->showCSS();
                          else
                          {
                            //If the javascript is requested, dump all the Javascript
                            if (isset($_GET['js']))
                            {
                              $this->dumpChildrenJavascript();
                            }
                            else $this->dumpContents();
                          }
                          $this->endCache();
                        }

                        if ($return_contents)
                        {
                                $contents=ob_get_contents();
                                ob_end_clean();
                        }
                        $this->callEvent('onaftershow',array());

                        if ($return_contents)
                        {
                                return($contents);
                        }
                }
        }

        /**
         * Renders the control.
         *
         * {@internal This is one of the main methods in the RPCL, as it renders controls, that is,
         * prints the resulting code for them. When writting controls, override this method and
         * use it to print your control.}}
         *
         * <code>
         * <?php
         *      function dumpContents()
         *      {
         *          echo "<table width=\"100%\"><tr><td>Hello, component!</td></tr></table>";
         *      }
         * ?>
         * </code>
         *
         * @see show(), getVisible()
         *
         */
        function dumpContents()
        {
                //Inherits and fills this method to show your control.
        }

        /**
         * Dumps CSS code required by the component.
         *
         * When developing a component, override this method to dump all the CSS code required for your component to
         * work. For example:
         *
         * <code>
         * function dumpCSS() {
         *   echo "  font-weight: bold;\n";
         *   parent::dumpCSS(); // You must call the parent function at the end.
         * }
         * </code>
         *
         * The code you print with this method will be appended to a CSS selector identifying the main HTML element
         * of your component (the one where you configured the id attribute with the value of the Name property of
         * your component), using the id of its top-level container followed by that of the component itself.
         *
         * For the example above, that would be:
         *
         * <code>
         * #ContainerName #ComponentName {
         *   font-weight: bold;
         * }
         * </code>
         *
         * @internal
         */
        function dumpCSS()
        {
        }

        /**
         * Dumps additional CSS code required by the component.
         *
         * Overriding dumpCSS() might not be enought in certain situations, and for those there is this
         * method, which lets you print additional CSS code with more flexibility.
         *
         * Unlike with dumpCSS(), what you print in this method will not be placed inside a CSS selector, so
         * you can manually specify the selector or selectors you want.
         *
         * Tip: You will usually want to use CSS selectors based on your component's main selector, which is returned
         * by the readCSSDescriptor() method.
         *
         * For example, you could override it this way:
         *
         * <code>
         * function dumpAdditionalCSS() {
         *   parent::dumpAdditionalCSS(); // You must call the parent function at the beginning.
         *
         *   echo $this->readCSSDescriptor()." blockquote {\n";
         *   echo "  font-family: monospace;\n";
         *   echo "}\n\n";
         *
         *   echo $this->readCSSDescriptor()."_table li {\n";
         *   echo "  margin: 5px;\n";
         *   echo "}\n\n";
         * }
         * </code>
         *
         * The resulting CSS code would be:
         *
         * <code>
         * #ContainerName #ComponentName blockquote {
         *   font-family: monospace;
         * }
         * #ContainerName #ComponentName_table li {
         *   margin: 5px;
         * }
         * </code>
         *
         * @internal
         */
        function dumpAdditionalCSS()
        {

        }

        /**
         * Dumps the CSS for the component, qualifying it by the owner name, so you can mix pages in the same page
         */
        function showCSS()
        {
          ob_start();
          $this->dumpCSS();
          $css=ob_get_contents();
          ob_end_clean();
          if ($css!='')
          {
            echo $this->readCSSDescriptor()." {\n";
            echo $css;
            echo "}\n";
          }
          $this->dumpAdditionalCSS();
        }

        /**
        * Returns the CSS descriptor for the component
        *
        * @return string The CSS descriptor for this component
        */
        function readCSSDescriptor()
        {
          global $application;

          $ownername='';
          if ($this->owner!=$application)
          {
            if ($this->owner!=null)
            {
              $ownername='#'.$this->owner->Name.' ';
            }
          }

          return($ownername.'#'.$this->Name);
        }

        /**
        * Fires the Event before showing the control.
        * @return mixed
        */
        function getOnBeforeShow() { return $this->_onbeforeshow; }
        function setOnBeforeShow($value) { $this->_onbeforeshow=$value; }
        function defaultOnBeforeShow() { return null; }

        /**
        * Fires the Event after showing the control.
        * @return mixed
        */
        function getOnAfterShow() { return $this->_onaftershow; }
        function setOnAfterShow($value) { $this->_onaftershow=$value; }
        function defaultOnAfterShow() { return null; }

        /**
        * Fires the Event at the same moment the control is shown. Some controls can
        * prevent the control from being shown when this event is attached.
        * @return mixed
        */
        function getOnShow() { return $this->_onshow; }
        function setOnShow($value) { $this->_onshow=$value; }
        function defaultOnShow() { return null; }

        /**
         * Start caching from here.
         *
         * Call this method at the beginning of a section of your code that you want to add to the cache.
         *
         * @see endCache()
         */
        function beginCache($type)
        {
        	$result=false;
			if (($this->ControlState & csDesigning) != csDesigning)
            {
        	if ($this->_cached==true)
            {
            	if ($this->owner!=null)
            	{
                	if ($this->owner->inheritsFrom('Page'))
                    {
                    	if ($this->owner->Cache!=null)
                    	{
                        	return($this->owner->Cache->initCache($this, $type));
                        }
                    }
                    else if ($this->inheritsFrom('Page'))
                    {
                    	if ($this->Cache!=null)
                    	{
                        	return($this->Cache->initCache($this, $type));
                        }
                    }
                }
            }
            }
            return($result);
        }

        /**
         * Stop caching at this point.
         *
         * Call this method at the end of a section of your code that you want to add to the cache.
         *
         * @see beginCache()
         */
        function endCache()
        {
			if (($this->ControlState & csDesigning) != csDesigning)
            {
        	if ($this->_cached==true)
            {
            	if ($this->owner!=null)
            	{
                	if ($this->owner->inheritsFrom('Page'))
                    {
                    	if ($this->owner->Cache!=null)
                    	{
                        	$this->owner->Cache->finishCache();
                        }
                    }
                    else if ($this->inheritsFrom('Page'))
                    {
                    	if ($this->Cache!=null)
                    	{
                        	$this->Cache->finishCache();
                        }
                    }
                }
            }
            }
        }


    protected $_cached="0";

    /**
     * Whether the caching feature should be enabled (true, "1") or not (false, "0").
     */
    function getCached() { return $this->_cached; }
    function setCached($value) { $this->_cached=$value; }
    function defaultCached() { return "0"; }

}

/**
* Helper function to filter the array of methods for an specific component, not to be called directly
*
* @param string $method Method name to filter
* @return boolean true if method starts with 'set'
*/
function filterSet( $method )
{
    $methodname = $method->name;

    return ($methodname[0] == 's' && $methodname[1] == 'e' && $methodname[2] == 't');
}

/**
* Helper function to filter the array of methods for an specific component, not to be called directly
*
* @param object $method Method to filter
* @param mixed $key Not used
*/
function processMethods( &$method, $key )
{
    $method = $method->name;
}

/*
* Helper function to check if we are calling the main page
*/
function isMainPage()
{
    return (!isset($_GET['css']) && !isset($_GET['js']) );
}

/**
 * Abstract class to group generic functions for CSS animations.
 *
 * @internal
 */
abstract class AnimationCSS
{

    /**
     * Returns CSS code to assign the given $animationCode to the target CSS $class using the vendor extensions and
     * standard code. That is:
     *
     * <code>
     * .$class {
     *     -moz-animation: $animationCode
     *     -o-animation: $animationCode
     *     ...
     *     animation: $animationCode
     * }
     * </code>
     *
     * @internal
     *
     * @returns string
     */
    public static function getStandardAnimation($class, $animationCode) {

        $prefix_browsers = array("webkit","moz", "o", "ms");

        //standard
        $result = ".{$class} {\n";

        $cont_browsers = count($prefix_browsers);
        for ($i=0; $i<$cont_browsers; $i++)
        {
            $browser = $prefix_browsers[$i];

            $result .= "-$browser-animation: " . $animationCode;

        }
        $result .= "animation: $animationCode ";

        $result .= "}\n\n";

        return $result;
    }
}

?>