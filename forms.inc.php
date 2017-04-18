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

use_unit("controls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("rtl.inc.php");
use_unit("templateplugins.inc.php");
use_unit("cache.inc.php");
use_unit("jquery/jquery.inc.php");

define('ddLeftToRight','ddLeftToRight');
define('ddRightToLeft','ddRightToLeft');

/**
 * Shutdown function, called by the PHP engine as the last thing to do before shutdown.
 *
 * This function is automatically called by the PHP engine just before shutdown, and it's the right moment to serialize
 * all components as no more user code is going to be executed.
 *
 * This way, the status of all objects in the aplication is stored to be recovered later without user intervention.
 *
 * @see Application::serializeChildren()
 * @link http://www.php.net/manual/en/function.register-shutdown-function.php
 *
 */
function RPCLShutdown()
{
        global $application;

        //This is the moment to store all properties in the session to retrieve them later
        $application->serializeChildren();

        //Uncomment this to get what is stored on the session at the last step of your scripts
        /*
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        */
}

register_shutdown_function("RPCLShutdown");

/**
 * A class to reference all the forms created on your application.
 *
 * The Application class holds a reference to all the forms created in your application
 * because the Owner for them is always the global Application object. This class is also
 * used to switch the language for the whole application by using the Language property.
 *
 * This class is also responsible for session management, if you include a restore_session=1 in
 * your request, the object, when created, will destroy the existing session and will create a
 * new one, so your application will start fresh.
 */
class Application extends Component
{
        protected $_language;

        /**
        * Sets the application language, so all forms in the application will share this setting.
        *
        * If you want to change the Language property for all your forms at once, you can use this
        * property, as forms take this setting into account when switching language.
        *
        * This property is of string type and you can set it to anything you want, provided your
        * language files share the same value and your locale resources also share that setting.
        *
        * <code>
        * <?php
        * global $application;
        * $application->Language="English";
        * //Now, your forms must have a unit.English.xml.php resource file with your translated strings
        * //and also a locale/English/messages.mo to be used for runtime strings
        * ?>
        * </code>
        *
        * @see Page::getLanguage()
        * @link wiki://Internationalization_and_Localization#Components_Localization
        * @link http://www.php.net/gettext
        *
        * @return string
        */
        function getLanguage() { return $this->_language; }
        function setLanguage($value)
        {
            $this->_language=$value;
            $this->switchLanguage($this->_language);
        }

        /**
         * Change the application to the language specified.
         */
        public function switchLanguage($value)
        {
            //This is to allow gettext usage
            if ($value=='(default)') $l='';
            else $l=$value;

            putenv ("LANG=$l");
            putenv ("LANGUAGE=$l");
            putenv ("LC_ALL=$l");
            setlocale(LC_ALL,$l);
            putenv ("LC_MESSAGES=$l");
            $domain="messages";
            bindtextdomain($domain, "./locale");
            textdomain($domain);

        }

        protected $_generatevendorcssextensions=true;

        /**
         * Whether the application should output standard code (false), or if it should try to
         * maximize browser compatibility instead (true).
         *
         * When set to true, browser-specific functions will be added to the CSS code.
         */
        function getGenerateVendorCSSExtensions() { return $this->_generatevendorcssextensions; }
        function setGenerateVendorCSSExtensions($value) { $this->_generatevendorcssextensions=$value; }
        function defaultGenerateVendorCSSExtensions() { return true; }

        // Documented in the parent.
        function __construct($aowner=null)
        {
                parent::__construct($aowner);

                global $startup_functions;

                //Call all startup functions before create the session
                reset($startup_functions);
                while(list($key, $val)=each($startup_functions))
                {
                        $val();
                }

                if(!isset($_SESSION))
                {
                  if (!session_start()) die ("Cannot start session!");
                }

                if (isset($_GET['restore_session']))
                {
                        if (!isset($_POST['xjxr']))
                        {
                            $_SESSION = array();
                            @session_destroy();
                            if (!session_start()) die ("Cannot start session!");
                        }
                }

                //TODO: Check this for security issues
                reset($_GET);
                while (list($k,$v)=each($_GET))
                {
                        if (strpos($k,'.')===false) $_SESSION[$k]=$v;
                }
        }

        /**
        * Performs an auto detection of the language used by the user browser and set the Language property accordingly.
        *
        * This method performs a detection operation trying to guess which language is used by the user depending on the
        * browser headers and information is sent.
        *
        * Can be used to accomodate automatically your application to the right language the user
        * want to get without prompting for it.
        *
        * Valid languages can be found on language/php_language_detection.php on the function called languages() and you can
        * get such list as an array calling that function in your software
        *
        * <code>
        * <?php
        *      function Button1Click($sender, $params)
        *      {
        *       global $application;
        *
        *       $application->autoDetectLanguage();
        *       echo $application->Language;
        *       //This echoes in the browser "Spanish (Traditional Sort)"
        *      }
        * ?>
        * </code>
        *
        * @see languages()
        *
        */
        function autoDetectLanguage()
        {
                use_unit("language/php_language_detection.php");
                $lang=get_languages('data');
                reset($lang);
                while (list($k,$v)=each($lang))
                {
                        if (array_key_exists(2,$v))
                        {
                                $this->Language=$v[2];
                                break;
                        }
                }
        }

}

global $application;

/**
 * Global $application variable
 */
$application=new Application(null);
$application->Name="rpclapp";


/**
 * Base class for controls with scrolling area
 *
 * It doesn't introduce any property/method/event and is reserved for future use.
 */
class ScrollingControl extends FocusControl
{
}

/**
 * Base class for containers.
 *
 * Containers are special components that can own other components.
 *
 * @link wiki://Containers
 */
class CustomPage extends ScrollingControl
{
        function __construct($aowner=null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);
                $this->ControlStyle="csAcceptsControls=1";
        }

        function serialize()
        {
            $toserialize=array();
            reset($this->components->items);
            while (list($k,$v)=each($this->components->items))
            {
                $parent="";
                if ($v->inheritsFrom('Control')) $parent=$v->parent->Name;

                if ($v->Name!='') $toserialize[$v->Name]=array($parent,$v->className());
            }

                global $application;

                $_SESSION['comps.'.$application->Name.'.'.$this->className()]=$toserialize;

                //Store last resource read to prevent reloading again in the next post
                $_SESSION[$this->readNamePath().'._reallastresourceread']=$this->reallastresourceread;
                parent::serialize();
        }

}

/**
 * DataModule class, basically a non visible holder for direct Component descendants
 *
 * You can use this class to create non-visible pages which hold non-visible components to
 * be used on another pages, is a way to centralize common reusable code.
 *
 */
class DataModule extends CustomPage
{

}

        /**
        * This function dumps an object into the ajaxresponse, so it's added
        * to be updated when the ajax request is returned
        *
        * This function is used by the internal Ajax system to get the code for a component and
        * add it to the Ajax response, so it gets updated when the response is returned.
        *
        * It relays on the dumpForAjax method that may be implemented by a component to specify
        * which javascript code must be executed in order to update such component.
        *
        * If no dumpForAjax method implementation exists, it tries to get the object code and
        * split the javascript and html code to add it to the response, so it gets correctly processed.
        *
        * @see extractjscript()
        *
        * @param object $object Object to be added
        */
        function ajaxDump($object)
        {
            global $ajaxResponse;

            if ($object->methodExists("dumpForAjax"))
            {
                ob_start();
                $object->dumpForAjax();
                $ccontents=ob_get_contents();
                ob_end_clean();
                $ajaxResponse->script($ccontents);
            }
            else
            {
                // the content
                ob_start();
                $object->show();
                $ccontents=ob_get_contents();
                ob_end_clean();

                // the css
                ob_start();
                $_GET['css'] = 1;
                $object->showCSS();
                $csscontents  = '<style type="text/css">';
                $csscontents .= ob_get_contents();
                $csscontents .= '</style>';
                ob_end_clean();
                unset($_GET['css']);

                // the javascript, TODO: change pagecreate when changed in rpcl classes.inc.php
                ob_start();
                $object->dumpJavascript();
                $jscontents  = ob_get_contents();
                ob_end_clean();
                $jscontents .= $object->pagecreate();
                $js=extractjscript($ccontents); // for older compatibility

                // insert HTML, JS and CSS in the response
                $ajaxResponse->assign($object->Name."_outer","innerHTML",$ccontents);
                $ajaxResponse->append($object->Name."_outer","innerHTML",$csscontents);
                $ajaxResponse->script($js[0].$jscontents);
            }
        }

/**
 * Function responsible to dispatch ajax requests to the right events
 *
 * This function is used by the Page when UseAjax is true to process the ajax requests and fire the right
 * events.
 *
 * It also creates the response to update the browser according to the component updates.
 *
 * @see ajaxDump()
 *
 * @param string $owner Name of the owner that owns all the components for this request, usually a Page component
 * @param string $sender Object that produced the ajaxCall
 * @param mixed $params Parameters to be sent to the function to be executed
 * @param string $event Name of the PHP function to execute
 * @param array $postvars Variables from the _POST stream
 * @param array $comps Names of the components to add to the stream to get updated
 * @return string
 */
        function ajaxProcess($owner, $sender, $params, $event, $postvars, $comps)
        {
                global $$owner;

                $_POST=$postvars;
                
                // enables event calling in xajax request. @see Component::callEvent
                if(! defined('XAJAX_POST_PROCESSED'))
                    define('XAJAX_POST_PROCESSED', 1);
				
                //Initializes all components
                $$owner->unserialize();
                $$owner->unserializeChildren();
                $$owner->loadedChildren();
                $$owner->loaded();
                $$owner->preinit();
                $$owner->init();

                global $ajaxResponse;

                $ajaxResponse = new xajaxResponse();

                $$owner->callEvent('onbeforeajaxprocess',array());
                $$owner->$event($$owner->$sender, $params);

                reset($$owner->controls->items);

                unset($comps['isArray']);

                if (count($comps)>=1)
                {
                    reset($comps);

                    while (list($k,$aname)=each($comps))
                    {
                        $v=$$owner->$aname;
                        if (is_object($v))
                        {
                            ajaxDump($v);
                        }
                    }
                }
                else
                {
                    while (list($k,$v)=each($$owner->controls->items))
                    {
                            ajaxDump($v);
                    }
                }

                $$owner->callEvent('onafterajaxprocess',array());
                $$owner->serialize();
                $$owner->serializeChildren();

                return($ajaxResponse);
        }


/**
 * Base class for webpage containers.
 *
 * @link wiki://Containers
 */
class CustomForm extends CustomPage
{
    protected $_showheader=1;
    protected $_showfooter=1;

    protected $_marginwidth="0";
    protected $_marginheight="0";
    protected $_leftmargin="0";
    protected $_topmargin="0";
    protected $_rightmargin="0";
    protected $_bottommargin="0";
    protected $_useajax=0;
    protected $_useajaxdebug=0;
    protected $_templateengine="";
    protected $_templatefilename="";

    protected $_onbeforeshowheader=null;
    protected $_onstartbody=null;
    protected $_onshowheader=null;
    protected $_onaftershowfooter=null;
    protected $_oncreate=null;

    protected $_isform=true;
    protected $_action="";

    protected $_background="";
    protected $_language="(default)";

    protected $_jsonload=null;
    protected $_jsonunload=null;

    public $isclientpage=false;

    /**
     * Triggered once the page is completely loaded, including page contents such as images.
     *
     * @see readjsOnReady()
     */
    function readjsOnLoad() { return $this->_jsonload; }
    function writejsOnLoad($value) { $this->_jsonload=$value; }
    function defaultjsOnLoad() { return null; }

        /**
        * The javascript OnUnload event is called after all nested framesets and
        * frames are finished with unloading their content.
        * @return mixed
        */
        function readjsOnUnload() { return $this->_jsonunload; }
        function writejsOnUnload($value) { $this->_jsonunload=$value; }
        function defaultjsOnUnload() { return null; }


        protected $_icon="";

            /**
            * Specifies the icon to be used on the address bar when loading this page and for bookmarks.
            *
            * @return string
            */
        function readIcon() { return $this->_icon; }
        function writeIcon($value) { $this->_icon=$value; }
        function defaultIcon() { return ""; }



        protected $_encoding='Western European (ISO)     |iso-8859-1';

        /**
        * Specifies the encoding to use for the page.
        *
        * Use this property to specify the encoding to use when generating this page, this
        * encoding is set on the charset of the generated HTML and it's different from the
        * Charset you setup PHP to work on.
        *
        * @link http://www.w3.org/TR/html4/charset.html
        *
        * @return enum
        */
        function readEncoding() { return $this->_encoding; }
        function writeEncoding($value) { $this->_encoding=$value; }
        function defaultEncoding() { return "Western European (ISO)     |iso-8859-1"; }

        protected $_formencoding="";

        /**
        * Specifies the encoding to use the form generated by the Page.
        *
        * Every Page component generates a Form (unless IsForm is false) to allow process events,
        * you can modify this property to set the encoding to a different value.
        *
        * This is useful, for example, to allow you upload data to the server
        *
        * @return enum
        */
        function readFormEncoding() { return $this->_formencoding; }
        function writeFormEncoding($value) { $this->_formencoding=$value; }
        function defaultFormEncoding() { return ""; }



        /**
        * Specifies the background to be used when generating the HTML document.
        *
        * The background should be an image file, and will be used to fill the
        * page background with it. For example: images/mybackground.gif
        *
        * @return string
        */
        function readBackground() { return $this->_background; }
        function writeBackground($value) { $this->_background=$value; }

        /**
        * Specifies the engine to be used to render this page using templates.
        *
        * Valid values for this property are registered Template Plugins, at this time, only
        * Smarty is included.
        *
        * @see getTemplateFilename()
        *
        * @return string
        */
        function readTemplateEngine() { return $this->_templateengine; }
        function writeTemplateEngine($value) { $this->_templateengine=$value; }
        function defaultTemplateEngine() { return ""; }

        /**
        * This property allows you to override the action parameter for the form
        * that is generated by the Page component.
        *
        * Usually, the action for the form is the script that generates the page
        * i.e. "unit1.php", but if you need to override this behaviour, you can
        * use this property for that.
        *
        * This property is useful to create forms that post information to another
        * script for further processing.
        *
        * @return string
        */
        function readAction() { return $this->_action; }
        function writeAction($value) { $this->_action=$value; }
        function defaultAction() { return ""; }

        /**
        * Specifies the name of the template file to be used to render this page.
        *
        * Usually is an HTML file with some placeholders to allow insert information inside.
        * To insert components inside templates, you must add a placeholder with the name
        * of the component you want to insert, i.e. {$Button1}
        *
        * @see getTemplateEngine()
        *
        * @return string
        */
        function readTemplateFilename() { return $this->_templatefilename; }
        function writeTemplateFilename($value) { $this->_templatefilename=$value; }
        function defaultTemplateFilename() { return ""; }

        /**
         * This property allows the Page, if set, to process and handle Ajax requests
         * performed using Component::ajaxCall.
         *
         * If you want to use Ajax with the built-in engine, you need to use ajaxCall and
         * set this property to true, to inform the page that must process any ajax
         * requests. If set to false, ajax calls won't be processed.
         *
         * @link wiki://Server_Page_AJAX
         * @see getUseAjaxDebug(), Component::ajaxCall()
         * @example Ajax/Basic/basicajax.php How to use ajaxCall
         */
        function readUseAjax() { return $this->_useajax; }
        function writeUseAjax($value) { $this->_useajax=$value; }
        function defaultUseAjax() { return 0; }

        /**
         * This property enables a debug window, to show ajax calls information
         *
         * When set to true, ajax calls will make a popup window to be shown with
         * information about all ajax requests. UseAjax must also be set to true.
         *
         * @link wiki://Server_Page_AJAX
         * @see getUseAjax()
         *
         * @return boolean
         */
        function readUseAjaxDebug() { return $this->_useajaxdebug; }
        function writeUseAjaxDebug($value) { $this->_useajaxdebug=$value; }
        function defaultUseAjaxDebug() { return 0; }

        protected $_useajaxuri="";

        /**
         * URI against which the AJAX calls should be performed.
         *
         * You should set this property whenever UseAjax is set to true.
         *
         * @link wiki://Server_Page_AJAX
         */
        function readUseAjaxUri() { return $this->_useajaxuri; }
        function writeUseAjaxUri($value) { $this->_useajaxuri=$value; }
        function defaultUseAjaxUri() { return ""; }


        /**
        * Specifies the language to be used when rendering this page.
        *
        * By setting it to a different value than (default), the Page will look for a file
        * named [language].xml.php to be loaded. That file must contain the properties
        * need to be changed to localize the interface to that specific language
        *
        * Check here to know more:
        * @link wiki://Internationalization_and_Localization#Components_Localization
        * @see Application::getLanguage()
        * @example I18N/index.php How to use Language property to translate interface
        * @example I18N/index.xml.php How to use Language property to translate interface (form, default language)
        * @example I18N/index.French (France).xml.php How to use Language property to translate interface (France resources)
        * @example I18N/index.German (Germany).xml.php How to use Language property to translate interface (German resources)
        * @example I18N/index.Spanish (Traditional Sort).xml.php How to use Language property to translate interface (Spanish resources)
        *
        * @return string
        */
        function readLanguage() { return $this->_language; }
        function writeLanguage($value)
        {
                if ($value!=$this->_language)
                {
                        $this->_language=$value;
                        if ((($this->ControlState & csDesigning) != csDesigning) && (($this->ControlState & csLoading) != csLoading))
                        {
                                $resourcename=$this->lastresourceread;
                                if ($value=='(default)') $l="";
                                else $l=".".$value;

                                $resourcename=str_replace('.php',$l.'.xml.php',$resourcename);

                                global $application;

                                $application->switchLanguage($value);

                                if (file_exists($resourcename))
                                {
                                        $this->readFromResource($resourcename, false, false);
                                }
                        }
                }
        }
        function defaultLanguage() { return "(default)"; }

        // Constructor.
        function __construct($aowner=null)
        {
            // Inherited constructor.
            parent::__construct($aowner);

        }

        protected $_hiddenfields=array();

        /**
         * Array of key-value pairs to be printed to the page as hidden fields.
         */
        function readHiddenFields() { return $this->_hiddenfields; }
        function writeHiddenFields($value) { $this->_hiddenfields=$value; }
        function defaultHiddenFields() { return array(); }

        protected $_cache=null;

        /**
         * The caching component to be used to cache the page.
         *
         * Once you have configured a caching component for the page, remember to set the Cached property to true in
         * order to enable the caching of the page.
         */
        function readCache() { return $this->_cache; }
        function writeCache($value) { $this->_cache=$this->fixupProperty($value); }
        function defaultCache() { return null; }

        protected $_activecontrol=null;

        /**
         * Name of the control in the page that should get the focus once the latter gets loaded.
         */
        function readActiveControl() { return $this->_activecontrol; }
        function writeActiveControl($value)  {
              $this->_activecontrol = $this->fixupProperty($value);
        }
        function defaultActiveControl() { return null; }

        // Documented in the parent.
        function loaded()
        {
            //parent::loaded();

            $this->writeCache($this->_cache);
            $this->writeActiveControl($this->_activecontrol);

            //Once the component has been loaded, calls the oncreate event, if assigned
            $this->callEvent('oncreate',array());
        }

        protected $_jsonsubmit=null;

        /**
         * Fired when the page is going to be submitted to the form, return false
         * to prevent the form from being posted
         */
        function readjsOnSubmit() { return $this->_jsonsubmit; }
        function writejsOnSubmit($value) { $this->_jsonsubmit=$value; }
        function defaultjsOnSubmit() { return null; }

        protected $_jsonreset=null;

    /**
     * Fired when the page is going to be reset using a reset input button
     */
    function readjsOnReset() { return $this->_jsonreset; }
    function writejsOnReset($value) { $this->_jsonreset=$value; }
    function defaultjsOnReset() { return null; }

    protected $_jsonready=null;

    /**
     * Triggered once the page is completely loaded,  and the DOM (http://en.wikipedia.org/wiki/Document_Object_Model) is
     * ready for client-side scripting, even if some page contents such as images were not loaded yet.
     *
     * @see readjsOnLoad()
     */
    function readjsOnReady() { return $this->_jsonready; }
    function writejsOnReady($value) { $this->_jsonready=$value; }
    function defaultjsOnReady() { return null; }

    protected $_target="";

    /**
     * Where to display the response when the data of the page form is submitted.
     *
     * Supported values are:
     * - _blank. Display the response in a new tab or window.
     * - _self. Display the response in the same frame (default).
     * - _parent. Display the response in the parent frame.
     * - _top. Display the response in the full body of the window.
     *
     * You can additionally set the property to the identifier of an iframe to open the linked document in that iframe.
    */
    function readTarget() { return $this->_target; }
    function writeTarget($value) { $this->_target=$value; }
    function defaultTarget() { return ""; }

    // Documented in the parent.
    function dumpJsEvents()
    {
        parent::dumpJsEvents();

        $this->dumpJSEvent($this->_jsonready);
        $this->dumpJSEvent($this->_jsonsubmit);
        $this->dumpJSEvent($this->_jsonreset);
        $this->dumpJSEvent($this->_jsonload);
        $this->dumpJSEvent($this->_jsonunload);
    }

    /**
    * Dumps the opening form tag
    *
    * This property, depending on the settings of IsForm and ShowHeader properties
    * returns the opening form tag, it also checks for Action property to know if
    * it must point the action for the form to the script itself or to another place.
    *
    * It also dumps code to process page events like OnSubmit and OnReset and sets
    * the form enconding according to the FormEncoding property.-
    *
    * @see readEndForm()
    *
    * @return string
    */
    function readStartForm()
    {
        $result="";
        if (($this->_isform) && ($this->_showheader))
        {
                $action=$this->selfActionForm();

                   if ($this->_action!='') $action=$this->_action;

                   $formevents='';

                   if ($this->_jsonsubmit!="")
                   {
                        $formevents.=" onsubmit=\"return $this->_jsonsubmit();\" ";
                   }

                   if ($this->_jsonreset!="")
                   {
                        $formevents.=" onreset=\"return $this->_jsonreset();\" ";
                   }

                   $enctype = "";
                   if ($this->_formencoding != "")
                   {
                        $enctype = " enctype=\"$this->_formencoding\"";
                   }


               $target='';
               if ($this->_target!='') $target=' target="'.$this->_target.'" ';

               $result='<form id="'.$this->name.'_form" name="'.$this->name.'_form" method="post" '.$formevents.' '.$target.' action="'.$action.'"'.$enctype.'>';
               $result.='<input type="hidden" name="serverevent" value="">';
               $result.='<input type="hidden" name="serverparams" value="">';

               reset($this->_hiddenfields);
               while (list($k,$v)=each($this->_hiddenfields))
               {
                $result.="<input type=\"hidden\" name=\"$k\" value=\"$v\">\n";
               }
        }
        return($result);
    }

    /**
    * This function returns the default value for the action tag in the form.
    * By default it returns $_SERVER['PHP_SELF'] but it ca be overwrited to return anything
    */
    function selfActionForm()
    {
      if (isset($_SERVER['PHP_SELF']))
        return str_replace(' ', '%20',$_SERVER['PHP_SELF']);
      else
        return "";
    }

        /**
        * Returns the ending form tag
        *
        * This property, depending on the settings of IsForm and ShowFooter will
        * dump the ending form tag.
        *
        * @see readStartForm()
        *
        * @return string
        */
    function readEndForm()
    {
        if (($this->_isform) && ($this->_showfooter))
        {
            return("</form>");
        }
    }

    /**
    * Dumps the opening body tag
    *
    */

    function dumpStartBody($style="",$attributes="")
    {
      echo "<body id=\"".$this->Name."\" $style $attributes>\n";
    }

    /**
    * Dumps the closing body tag
    */
    function dumpEndBody()
    {
      echo "</body>\n";
    }
/**
 * Dump the page using a template, it doesn't generate an HTML page.
 *
 * It uses the template and tries to insert components inside it. To make it work you
 * need to assign TemplateEngine and TemplateFilename properties with the right
 * values, check here to know more:
 * @link wiki://Server_Page_Templates
 * @see getTemplateEngine(), getTemplateFilename()
 *
 */
    function dumpUsingTemplate()
    {
        //Check here for templateengine and templatefilename
        if (($this->ControlState & csDesigning) != csDesigning)
        {
                $tclassname=$this->_templateengine;

                $template=new $tclassname($this);
                $template->FileName=$this->_templatefilename;

                $template->initialize();
                if (isset($_GET['restore_session'])) $template->clear();
                $template->assignComponents();
                $this->callEvent("ontemplate",array("template"=>$template));
                $template->dumpTemplate();
        }
    }

    protected $_ontemplate=null;

    /**
    * Fired when the template is about to be rendered.
    *
    * This event is only fired
    * if TemplateEngine and TemplateFilename are correctly set and it provides you
    * with an opportunity to access to the internal template object, check here:
    * @link wiki://Page#OnTemplate
    * @see getTemplateEngine(), getTemplateFilename()
    *
    * @return mixed
    */
    function readOnTemplate() { return $this->_ontemplate; }
    function writeOnTemplate($value) { $this->_ontemplate=$value; }
    function defaultOnTemplate() { return null; }

    protected $_onbeforeajaxprocess=null;

    /**
    * Fired just before the routine specified in ajaxcall is about to be called
    *
    * Use this event to perform any operation just before the routine specified in
    * ajaxcall is going to be called.
    *
    * @see ajaxCall()
    *
    * @return mixed
    */
    function readOnBeforeAjaxProcess() { return $this->_onbeforeajaxprocess; }
    function writeOnBeforeAjaxProcess($value) { $this->_onbeforeajaxprocess=$value; }
    function defaultOnBeforeAjaxProcess() { return null; }

    protected $_onafterajaxprocess=null;

    /**
    * Fired just after the routine specified in ajaxcall is about to be called
    *
    * Use this event to perform any operation just after the routine specified in
    * ajaxcall is going to be called.
    *
    * @see ajaxCall()
    *
    * @return mixed
    */
    function readOnAfterAjaxProcess() { return $this->_onafterajaxprocess; }
    function writeOnAfterAjaxProcess($value) { $this->_onafterajaxprocess=$value; }
    function defaultOnAfterAjaxProcess() { return null; }



    /**
    * This method is called to setup the Ajax functionality when dumping Page code
    *
    * When generating the page code, if ajax support is enabled, this method dumps
    * the right code to create the xajax object, setup xajax debug support if required
    * and to register the processing function for ajax requests as ajaxProcess(), and
    * finally, processes all the incomming ajax requests
    *
    * @see getUseAjaxDebug(), getUseAjax()
    */
    function processAjax()
    {
        if (($this->ControlState & csDesigning) != csDesigning)
        {
                use_unit("xajax/xajax_core/xajax.inc.php");
                //AJAX support
                global $xajax;

                $xajaxuri=$_SERVER['REQUEST_URI'];

                if ($this->UseAjaxUri!='') $xajaxuri=$this->UseAjaxUri;

                // Instantiate the xajax object.  No parameters defaults requestURI to this page, method to POST, and debug to off
                $xajax = new xajax($xajaxuri);

                if ($this->_useajaxdebug) $xajax->configure('debug',true);

                // Specify the PHP functions to wrap. The JavaScript wrappers will be named xajax_functionname
                $xajax->registerFunction("ajaxProcess");
                $xajax->configure('javascript URI',RPCL_HTTP_PATH.'/xajax/');

                // Process any requests.  Because our requestURI is the same as our html page,
                // this must be called before any headers or HTML output have been sent
                $xajax->processRequest();
                //AJAX support
        }
    }



    /**
    * This method is used internally by the Page component to dump all javascript
    * must be located at the header.
    *
    * This method iterates through all components to dump all children javascript
    * inside the header section of the document.
    *
    * It also dumps common javascript stored in js/common.js and if ajax is enabled for the page
    * with the UseAjax property, it also includes the xajax library.
    *
    * @param boolean $return_contents If true, contents are returned by the function instead being dumped
    * @return string
    */
    function dumpHeaderJavascript($return_contents=false)
    {
        global $output_enabled;

        if ($output_enabled)
        {
                  $sp='';
                  if (!defined('JQUERY'))
                  {
                      $sp.='<script  type="text/javascript" src="'.RPCL_HTTP_PATH.JQUERY_FILE.'"></script>'."\n";
                      define('JQUERY',1);
                  }

                  //If this is a client page, dump the .js file with the same name
                  if ($this->isclientpage)
                  {
                    $sp.="<script type=\"text/javascript\" src=\"".str_replace('.php','.js',str_replace(' ', '%20',$_SERVER['PHP_SELF']))."\"></script>\n";
                  }

                  //TODO: Check this to verify the javascript is isolated properly
                  // ajax js
                  if ($this->_useajax)
                  {
                    if (($this->ControlState & csDesigning) != csDesigning)
                    {
                      global $xajax;
                      $sp=$xajax->getJavascript().$sp;
                    }
                  }

                  if ($return_contents)
                  {
                      return($sp);
                  }
                  else echo $sp;
        }
    }

    /*
     * Dumps only the javascript page needed to initialize the components
     */
    function dumpJavascript()
    {
      ///TODO: Refactor the header, check a more elegant solution
      if (isset($_GET['js']))
        header("Content-type: application/javascript");

      ///TODO: may the next line must be removed
      echo "var $this->Name=new Object(Object);\n\n";



      // document ready event
      echo "$(document).ready(function(event) {\n";

          // first dump the code to call jsOnReady if exist
         if($this->_jsonready != "")
            echo "\t".$this->_jsonready . "(event);\n";

         // second dump the event binding
         echo $this->pageInit()."\n";

       echo "});\n";

      ///TODO: enable this when js wrapper is updated
      //echo $this->bindJSEvent('load');
      //echo $this->bindJSEvent('reset');
      //echo $this->bindJSEvent('submit');
      //echo $this->bindJSEvent('unload');

      // third, dump the javascript events function
      $this->dumpJsEvents();

    }

    /**
     *   Method to execute all javascript needed when the page is loaded.
     *   For example to dump the event bindings
     *
     */
    function pageInit()
    {
      $output = "";
      reset($this->components->items);
      while(list($k, $v) = each($this->components->items))
      {
          if(($v->inheritsFrom('Control') && $v->canShow()) || !$v->inheritsFrom('Control'))
          {
              //calling all javascript include in pagecreate of each control
              $output .= $v->pagecreate();
          }
      }

      echo $output;
    }


    protected $_directionality=ddLeftToRight;

    /**
    * Set the text directionality of the page
    *
    * Use this property to set the directionality of the text inside the page.
    *
    * @return enum(ddLeftToRight,ddRightToLeft)
    */
    function readDirectionality() { return $this->_directionality; }
    function writeDirectionality($value) { $this->_directionality=$value; }
    function defaultDirectionality() { return ddLeftToRight; }

    /**
     * Append the default unit code (px) to the provided string if it has no unit, else return the same value.
     *
     * @internal
     */
    function parseUnit($unit)
    {
      if (is_numeric($unit)) return($unit.'px');
      else return($unit);
    }

    // Documented in the parent.
    function dumpCSS()
    {
      parent::dumpCSS();

      if ($this->_leftmargin!="") echo "margin-left: ".$this->parseUnit($this->_leftmargin).";\n";
      if ($this->_topmargin!="") echo "margin-top: ".$this->parseUnit($this->_topmargin).";\n";
      if ($this->_rightmargin!="") echo "margin-right: ".$this->parseUnit($this->_rightmargin).";\n";
      if ($this->_bottommargin!="") echo "margin-bottom: ".$this->parseUnit($this->_bottommargin).";\n";
      if ($this->color!="") echo "background-color: $this->color; \n";
      if ($this->Background!="") echo "background-image: url($this->Background);\n";
      echo parent::parseCSSCursor();
    }

    // Documented in the parent.
    function dumpAdditionalCSS()
    {
        // generate the css needed by the 'inline' class. This class is setted in outers div when the pages uses a template
        if($this->_templateengine)
        {
            echo "{$this->readCSSDescriptor()} .inline{\n";
            echo "  position: relative;\n";
            echo "  display: inline;\n";
            echo "}\n";
        }
      
      // generates css to the inner form
      if($this->_isform)
      {
          echo "#$this->Name #$this->Name"."_form {\n";
          echo "margin-bottom: 0px;\n";
          echo "}\n";
      }

      if ($this->_generatetable)
      {

        $alignment="";
        switch ($this->_alignment)
        {
                case agNone: $alignment=""; break;
                case agLeft: $alignment=""; break;
                case agCenter: $alignment="margin-left: auto;\nmargin-right: auto;\n"; break;
                case agRight: $alignment="margin-left: auto;\nmargin-right: 0;\n"; break;
        }

        echo "#$this->Name #$this->Name"."_table {\n";
        echo $alignment;
        if ($this->Color!="") echo "background-color: $this->Color;\n";
        /*
        $width='';
        if (($this->ControlState & csDesigning) != csDesigning)
        {
            if (($this->Layout->Type==GRIDBAG_LAYOUT) || ($this->Layout->Type==ROW_LAYOUT) || ($this->Layout->Type==COL_LAYOUT))
            {
                $width="100%";
            }
        }

        if (($width=='') && ($this->Width!='')) $width=$this->Width.'px;';

        if ($width!='') echo "width:$width\n";
        if ($this->Height!="") echo "height:".$this->Height."px;\n";
        */

        echo $this->_readCSSSize();
        echo "border-width:0;\n";
        echo "border-collapse:separate;\n";
        echo "}\n";

        echo "#$this->Name #$this->Name"."_table > tbody > tr > td, #$this->Name #$this->Name"."_table > tbody > th {\n";
        echo "padding:0;\n";
        echo "vertical-align:top;\n";
        echo "}\n";



      }


      if (($this->ControlState & csDesigning) != csDesigning)
      {
        // Dump Layout CSS
        $this->Layout->dumpLayoutContents(array(), true);

        // Dump Layers CSS
        reset($this->controls->items);
        while (list($k,$v)=each($this->controls->items))
        {
              if (($v->Visible) && ($v->IsLayer))
              {
                      $v->showCSS();
              }
        }

        reset($this->components->items);
        while (list($k,$v)=each($this->components->items))
        {
            if (($v->inheritsFrom('Component')) && (!$v->inheritsFrom('Control')))
            {
                $v->showCSS();
            }
        }

      }

    }

    /**
     * Prints the HTML code to request the CSS file with the code for the current page.
     *
     * This method should be called at least once in the header HTML element.
     *
     * @internal
     */
    function dumpCSSRequest($extraparams='')
    {

        $url = htmlentities(str_replace(' ', '%20',$_SERVER['PHP_SELF']), ENT_QUOTES, 'UTF-8') . "?css=1" . htmlentities($extraparams);
		echo "<link href=\"{$url}\" rel=\"stylesheet\" type=\"text/css\"/>\n";

    }

    /**
     * Prints the HTML code to request the JavaScript file with the code for the current page.
     *
     * This method should be called at least once in the header HTML element.
     *
     * @internal
     */
    function dumpJSRequest($extraparams='')
    {
		$url = htmlentities(str_replace(' ', '%20',$_SERVER['PHP_SELF']), ENT_QUOTES, 'UTF-8') . "?js=1" . htmlentities($extraparams);
		echo "<script type=\"text/javascript\" src=\"{$url}\"></script>\n";
    }

    /**
     * Prints the code needed by the page and it's components to work propertly,
     *
     * This method should be called at least once in the header HTML element.
     *
     * @internal
     */
    function dumpPageHeaderCode()
    {
      $this->dumpChildrenHeaderCode();
    }

    /**
     * Prints the code to be included on the 'head' section of the HTML document, including CSS and JavaScript requests.
     *
     * @internal
     */
    function dumpHeaderInitCode($extraparams='')
    {
        $this->dumpCSSRequest($extraparams);
        $this->dumpHeaderJavascript();
        $this->dumpJSRequest($extraparams);
        $this->dumpPageHeaderCode();
    }

    // Documented in the parent.
    function dumpContents()
    {
        global $scriptfilename;

        acl_addresource(basename($scriptfilename));
        //TODO: Provide an opportunity to process acl failouts
        if (!acl_isallowed(basename($scriptfilename), "Show")) return;

        //TODO: XHTML support
        //TODO: Isolate all elements of a page into properties
        //Calls beforeshowheader event, if any
        $this->callEvent('onshow',array());

        if ($this->_templateengine!="")
        {
                if ($this->_useajax) $this->processAjax();
                $this->dumpUsingTemplate();
                return;
        }

        if ($this->_useajax) $this->processAjax();

        $dtd="<!DOCTYPE html>";
        $extra="";


        //Calls beforeshowheader event, if any
        $this->callEvent('onbeforeshowheader',array());

        //Removed as it inteferes with DOCTYPE
        //echo "<!-- $this->name begin -->\n";
        //If must dump the header
        $allowshowheader=$this->_showheader;

        if ($allowshowheader)
        {
                if ($dtd!="") echo "$dtd\n";

                if ($this->_directionality==ddLeftToRight) $extra.=" DIR=ltr ";
                else $extra.=" DIR=rtl ";

                if ($this->_generatedocument)
                {
                    echo "<html $extra>\n";
                    echo "<head>\n";
                }
                if ($this->Icon!="")
                {
                        echo "<link rel=\"shortcut icon\" href=\"$this->Icon\" type=\"image/x-icon\" />\n";
                }

                $this->callEvent('onshowheader',array());

                $title=$this->Caption;
                if ($this->_generatedocument)
                {
                    echo "<title>$title</title>\n";
                }
                $cs=explode('|',$this->_encoding);
                echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$cs[1]\">\n";

                $this->dumpHeaderInitCode();

                if ($this->_generatedocument)
                {
                    echo "</head>\n";
                }
                else echo "\n";

                $attr="";

                $st="";

                // add the defined JS events to the body
                if ($this->_jsonload!=null) $attr.=" onload=\"return $this->_jsonload(event)\" ";
                if ($this->_jsonunload!=null) $attr.=" onunload=\"return $this->_jsonunload(event)\" ";

          if ($this->_generatedocument)
          {
            $this->dumpStartBody($st,$attr);
          }
        }
        else
        {
                $this->dumpHeaderJavascript();
                $this->dumpPageHeaderCode();
        }


        echo $this->readStartForm();

        $this->dumpChildrenFormItems();

        $this->callEvent('onstartbody',array());

        //Dump children controls
        $this->dumpChildren();

        if (($this->_isform) && ($this->_showfooter))
        {
               echo $this->readEndForm();
        }

        $this->callEvent('onaftershowfooter',array());

        if ($this->_showfooter)
        {
          if ($this->_generatedocument)
   	      {
              $this->dumpEndBody();
              echo "</html>\n";
		  }
        }
        echo "<!-- $this->name end -->\n";
    }


    function dumpChildrenFormItems($return_contents=false)
    {
        $result=parent::dumpChildrenFormItems($return_contents);

        // fixup to allow initialization of visual stuff in case
        // if non-visual Q lib classes are used
        if ($return_contents)
        {
          ob_start();
        }

        if ($return_contents)
        {
          $result.=ob_get_contents();
          ob_end_clean();
        }
        return($result);
    }

    protected $_generatetable="1";

    /**
    * Set this option to false to prevent the page to generate the table that surrounds
    * all the contents
    *
    * @return boolean
    */
    function readGenerateTable() { return $this->_generatetable; }
    function writeGenerateTable($value) { $this->_generatetable=$value; }
    function defaultGenerateTable() { return "1"; }



    /**
     * Dump al children controls
     *
     */
    function dumpChildren()
    {
        if ($this->_generatetable)
        {
            echo "\n<table id=\"".$this->Name."_table\"><tr><td>\n";
        }

        if (($this->ControlState & csDesigning) != csDesigning)
        {
                $this->Layout->dumpLayoutContents(array(), false);
        }

        if ($this->_generatetable)
        {
            echo "</td></tr></table>\n";
        }

        reset($this->controls->items);
        while (list($k,$v)=each($this->controls->items))
        {
                if (($v->Visible) && ($v->IsLayer))
                {
                        $v->show();
                }
        }

    }


        /**
        * Sets or retrieves the height of the top margin of the object.
        *
        * Use this property to specify the margin at the top of the page.
        *
        * @see getLeftMargin(), getBottomMargin(), getRightMargin()
        * @return integer
        */
    function readTopMargin() { return $this->_topmargin; }
    function writeTopMargin($value) { $this->_topmargin=$value; }
    function defaultTopMargin() { return 0; }

        /**
        * Sets or retrieves the width of the left margin of the object.
        *
        * Use this property to specify the margin at the left of the page.
        *
        * @see getTopMargin(), getBottomMargin(), getRightMargin()
        * @return integer
        */
    function readLeftMargin() { return $this->_leftmargin; }
    function writeLeftMargin($value) { $this->_leftmargin=$value; }
    function defaultLeftMargin() { return 0; }

        /**
        * Sets or retrieves the height of the bottom margin of the object.
        *
        * Use this property to specify the margin at the bottom of the page.
        *
        * @see getTopMargin(), getLeftMargin(), getRightMargin()
        * @return integer
        */
    function readBottomMargin() { return $this->_bottommargin; }
    function writeBottomMargin($value) { $this->_bottommargin=$value; }
    function defaultBottomMargin() { return 0; }

        /**
        * Sets or retrieves the width of the right margin of the object.
        *
        * Use this property to specify the margin at the right of the page.
        *
        * @see getTopMargin(), getLeftMargin(), getBottomMargin()
        * @return integer
        */
    function readRightMargin() { return $this->_rightmargin; }
    function writeRightMargin($value) { $this->_rightmargin=$value; }
    function defaultRightMargin() { return 0; }

        /**
        * If false, the form doesn't dump any header code.
        *
        * This property  is useful, for example if you want to include your form inside
        * another form, so it doesn't generate a full HTML document.
        *
        * When the Page generates the HTML document, it starts
        * from top to bottom, first dumps the header, after that, the body and at the end,
        * the footer. By setting this property to false, you tell the Page to don't generate
        * the footer and also, events for the footer won't be generated.
        *
        * @see getIsForm(), getShowFooter()
        * @return boolean
        */
    function readShowHeader() { return $this->_showheader; }
    function writeShowHeader($value) { $this->_showheader=$value; }
    function defaultShowHeader() { return 1; }

        /**
        * If false, the form doesn't generate any <form> tag, but events won't be processed
        *
        * To allow RPCL process events, there must be a form on the html document
        * that allows the document to be posted to the server, but, for example, if you want
        * to include your page into another page, you should set this property to false
        * to prevent generate nested <form> tags, as that is not allowed by HTML
        *
        * @see getShowHeader(), getShowFooter()
        * @return boolean
        */
    function readIsForm() { return $this->_isform; }
    function writeIsForm($value) { $this->_isform=$value; }
    function defaultIsForm() { return 1; }

    protected $_generatedocument="1";

    /**
     * Whether the generated page should contain the HTML structure of head and body ("1"), or if only the body
     * content should be generated instead ("0").
     *
     * You will usually want to leave this property set to true.
     */
    function readGenerateDocument() { return $this->_generatedocument; }
    function writeGenerateDocument($value) { $this->_generatedocument=$value; }
    function defaultGenerateDocument() { return "1"; }


        /**
        * If false, the form doesn't dump any footer code.
        *
        * This property is useful, for example if you want to include your form inside another
        * form, so it doesn't generate a full HTML document.
        *
        * When the Page generates the HTML document, it starts
        * from top to bottom, first dumps the header, after that, the body and at the end,
        * the footer. By setting this property to false, you tell the Page to don't generate
        * the footer and also, events for the footer won't be generated.
        *
        * @see getShowHeader(), getIsForm()
        * @return boolean
        */
    function readShowFooter() { return $this->_showfooter; }
    function writeShowFooter($value) { $this->_showfooter=$value; }
    function defaultShowFooter() { return 1; }

        /**
        * Fired before the page is going to render the header, this is useful to add
        * contents on that document location
        */
    function readOnBeforeShowHeader() { return $this->_onbeforeshowheader; }
    function writeOnBeforeShowHeader($value) { $this->_onbeforeshowheader=$value; }
    function defaultOnBeforeShowHeader() { return null; }

        /**
        * Fired after show the footer, which should be the last oportunity for you to
        * add code to the html document
        */
    function readOnAfterShowFooter() { return $this->_onaftershowfooter; }
    function writeOnAfterShowFooter($value) { $this->_onaftershowfooter=$value; }
    function defaultOnAfterShowFooter() { return null; }

        /**
        * Fired when showing the header, this event is the right place if you want to
        * add CSS styles or Javascript scripts to your HTML using code, as the code you
        * dump in this event, will be placed inside the HTML header
        */
    function readOnShowHeader() { return $this->_onshowheader; }
    function writeOnShowHeader($value) { $this->_onshowheader=$value; }
    function defaultOnShowHeader() { return null; }

        /**
        * Fired just right after dump the <body> tag, so you can add anything you may need
        * there
        */
    function readOnStartBody() { return $this->_onstartbody; }
    function writeOnStartBody($value) { $this->_onstartbody=$value; }
    function defaultOnStartBody() { return null; }

    /**
    * Fired when the page is created and all components have been loaded, this is
    * the right event to perform initialization stuff, the other event for this is
    * OnBeforeShow
    */
    function readOnCreate() { return $this->_oncreate; }
    function writeOnCreate($value) { $this->_oncreate=$value; }
    function defaultOnCreate() { return null; }

}

/**
 * Container to encapsulate a webpage.
 *
 * This class is meant to own all the components responsible for the interface and the logic
 * of a webpage, which this class is responsible to generate from its children.
 *
 * @link wiki://Containers
 */
class Page extends CustomForm
{
        function getjsOnReady() { return $this->readjsonready(); }
        function setjsOnReady($value) { $this->writejsonready($value); }

        function getHiddenFields() { return $this->readhiddenfields(); }
        function setHiddenFields($value) { $this->writehiddenfields($value); }

        function getLayout() { return $this->readLayout(); }
        function setLayout($value) { $this->writeLayout($value); }

        function getIcon() { return $this->readicon(); }
        function setIcon($value) { $this->writeicon($value); }

        function getEncoding() { return $this->readencoding(); }
        function setEncoding($value) { $this->writeencoding($value); }

        function getAlignment() { return $this->readAlignment(); }
        function setAlignment($value) { $this->writeAlignment($value); }

        function getColor() { return $this->readColor(); }
        function setColor($value) { $this->writeColor($value); }

        function getShowHint() { return $this->readShowHint(); }
        function setShowHint($value) { $this->writeShowHint($value); }

        function getVisible() { return $this->readVisible(); }
        function setVisible($value) { $this->writeVisible($value); }

        function getCaption() { return $this->readCaption(); }
        function setCaption($value) { $this->writeCaption($value); }

        function getFont() { return $this->readFont(); }
        function setFont($value) { $this->writeFont($value); }

        function getBackground() { return $this->readbackground(); }
        function setBackground($value) { $this->writebackground($value); }

        function getTemplateEngine() { return $this->readtemplateengine(); }
        function setTemplateEngine($value) { $this->writetemplateengine($value); }

        function getAction() { return $this->readaction(); }
        function setAction($value) { $this->writeaction($value); }

        function getTemplateFilename() { return $this->readtemplatefilename(); }
        function setTemplateFilename($value) { $this->writetemplatefilename($value); }

        function getUseAjax() { return $this->readuseajax(); }
        function setUseAjax($value) { $this->writeuseajax($value); }

        function getUseAjaxDebug() { return $this->readuseajaxdebug(); }
        function setUseAjaxDebug($value) { $this->writeuseajaxdebug($value); }

        function getUseAjaxUri() { return $this->readuseajaxuri(); }
        function setUseAjaxUri($value) { $this->writeuseajaxuri($value); }

        function getLanguage() { return $this->readlanguage(); }
        function setLanguage($value){$this->writelanguage($value);}

        function getCache() { return $this->readcache(); }
        function setCache($value) { $this->writecache($value); }

        function getActiveControl() { return $this->readActiveControl(); }
        function setActiveControl($value) { $this->writeActiveControl($value); }

        function getTarget() { return $this->readtarget(); }
        function setTarget($value) { $this->writetarget($value); }


        function getDirectionality() { return $this->readdirectionality(); }
        function setDirectionality($value) { $this->writedirectionality($value); }

        function getGenerateTable() { return $this->readgeneratetable(); }
        function setGenerateTable($value) { $this->writegeneratetable($value); }

        function getTopMargin() { return $this->readtopmargin(); }
        function setTopMargin($value) { $this->writetopmargin($value); }

        function getLeftMargin() { return $this->readleftmargin(); }
        function setLeftMargin($value) { $this->writeleftmargin($value); }

        function getBottomMargin() { return $this->readbottommargin(); }
        function setBottomMargin($value) { $this->writebottommargin($value); }

        function getRightMargin() { return $this->readrightmargin(); }
        function setRightMargin($value) { $this->writerightmargin($value); }

        function getShowHeader() { return $this->readshowheader(); }
        function setShowHeader($value) { $this->writeshowheader($value); }

        function getIsForm() { return $this->readisform(); }
        function setIsForm($value) { $this->writeisform($value); }

        function getGenerateDocument() { return $this->readgeneratedocument(); }
        function setGenerateDocument($value) { $this->writegeneratedocument($value); }

        function getShowFooter() { return $this->readshowfooter(); }
        function setShowFooter($value) { $this->writeshowfooter($value); }


        function getjsOnLoad() { return $this->readjsonload(); }
        function setjsOnLoad($value) { $this->writejsonload($value); }

        function getjsOnUnload() { return $this->readjsonunload(); }
        function setjsOnUnload($value) { $this->writejsonunload($value); }

        function getjsOnSubmit() { return $this->readjsonsubmit(); }
        function setjsOnSubmit($value) { $this->writejsonsubmit($value); }

        function getjsOnReset() { return $this->readjsonreset(); }
        function setjsOnReset($value) { $this->writejsonreset($value); }
        function getOnBeforeShowHeader() { return $this->readonbeforeshowheader(); }
        function setOnBeforeShowHeader($value) { $this->writeonbeforeshowheader($value); }

        function getOnAfterShowFooter() { return $this->readonaftershowfooter(); }
        function setOnAfterShowFooter($value) { $this->writeonaftershowfooter($value); }

        function getOnShowHeader() { return $this->readonshowheader(); }
        function setOnShowHeader($value) { $this->writeonshowheader($value); }

        function getOnStartBody() { return $this->readonstartbody(); }
        function setOnStartBody($value) { $this->writeonstartbody($value); }

        function getOnCreate() { return $this->readoncreate(); }
        function setOnCreate($value) { $this->writeoncreate($value); }

        function getOnTemplate() { return $this->readontemplate(); }
        function setOnTemplate($value) { $this->writeontemplate($value); }

        function getOnBeforeAjaxProcess() { return $this->readonbeforeajaxprocess(); }
        function setOnBeforeAjaxProcess($value) { $this->writeonbeforeajaxprocess($value); }

        function getOnAfterAjaxProcess() { return $this->readonafterajaxprocess(); }
        function setOnAfterAjaxProcess($value) { $this->writeonafterajaxprocess($value); }



}