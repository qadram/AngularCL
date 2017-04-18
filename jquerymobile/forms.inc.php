<?php
use_unit("forms.inc.php");
use_unit('jquerymobile/jmobile.common.inc.php');


/**
 * Base class for containers of webpages based on jQuery Mobile (http://jquerymobile.com/).
 *
 * @link wiki://Containers
 */
class CustomMPage extends CustomForm
{
   protected $_theme = "";
   protected $_loadingmessage = "Loading";
   protected $_showloadingmessage = 1;
   protected $_autoinitialize = 1;
   protected $_defaulttransition = "trSlide";
   protected $_isphonegapenabled = 0;
   protected $_pageloaderrormessage = "Error Loading Page";
   protected $_viewportscale=100;
   protected $_cssfile="ccsBasic";
   protected $_customcssfile="";
   protected $_subpageurlkey="ui-page";
   protected $_activepageclass="ui-page-active";
   protected $_activebtnclass="ui-btn-active";
   protected $_minscrollback=150;
   protected $_touchoverflowenabled=1;

   /**
   * Enable smoother page transitions and true fixed toolbars in devices that support both the overflow: and overflow-scrolling: touch; CSS properties.
   */
   function readTouchOverflowEnabled() { return $this->_touchoverflowenabled; }
   function writeTouchOverflowEnabled($value) { $this->_touchoverflowenabled=$value; }
   function defaultTouchOverflowEnabled() { return 1; }

   /**
   * Minimum scroll distance that will be remembered when returning to a page.
   */
   function readMinScrollBack() { return $this->_minscrollback; }
   function writeMinScrollBack($value) { $this->_minscrollback=$value; }
   function defaultMinScrollBack() { return 150; }

   /**
   * The class used for "active" button state, from CSS framework.
   */
   function readActiveBtnClass() { return $this->_activebtnclass; }
   function writeActiveBtnClass($value) { $this->_activebtnclass=$value; }
   function defaultActiveBtnClass() { return "ui-btn-active"; }

   /**
   * The class assigned to page currently in view, and during transitions
   */
   function readActivePageClass() { return $this->_activepageclass; }
   function writeActivePageClass($value) { $this->_activepageclass=$value; }
   function defaultActivePageClass() { return "ui-page-active"; }

   /**
   * The url parameter used for referencing widget-generated sub-pages
   */
   function readSubPageUrlKey() { return $this->_subpageurlkey; }
   function writeSubPageUrlKey($value) { $this->_subpageurlkey=$value; }
   function defaultSubPageUrlKey() { return "ui-page"; }

   /**
    * Custom CSS file to be included from the page.
    */
   function readCustomCssFile() { return $this->_customcssfile; }
   function writeCustomCssFile($value) { $this->_customcssfile=$value; }
   function defaultCustomCssFile() { return ""; }

   /**
   * choose a jquerymobile css File to load
   */
   function readCssFile() { return $this->_cssfile; }
   function writeCssFile($value) { $this->_cssfile=$value; }
   function defaultCssFile() { return "ccsBasic"; }


   /**
   * Indicates the viewport's scale of the page for mobile device's browsers and apps
   * Ranges from 0 to 100
   */
   function readViewportScale() { return $this->_viewportscale; }
   function writeViewportScale($value) { $this->_viewportscale=$value; }
   function defaultViewportScale() { return 100; }

   /**
    * Displays the message displayed when a page fails to load
    */
   function readPageLoadErrorMessage()    {return $this->_pageloaderrormessage;}
   function writePageLoadErrorMessage($value)    {$this->_pageloaderrormessage = $value;}
   function defaultPageLoadErrorMessage()    {return "Error Loading Page";}

   /**
    * Indicates if the app is going to make Ajax calls from a PhoneGap Application
    */
   function readIsPhoneGapEnabled()    {return $this->_isphonegapenabled;}
   function writeIsPhoneGapEnabled($value)    {$this->_isphonegapenabled = $value;}
   function defaultIsPhoneGapEnabled()    {return 0;}

   /**
    * Indicates the default transition to use when loading any page
    */
   function readDefaultTransition()    {return $this->_defaulttransition;}
   function writeDefaultTransition($value)    {$this->_defaulttransition = $value;}
   function defaultDefaultTransition()    {return "trSlide";}

   /**
    * Is set to true the page will not be initialized automatically and will wait for the
    * jQuery.mobile.initializePage();
    * function to get initialized
    */
   function readAutoInitialize()    {return $this->_autoinitialize;}
   function writeAutoInitialize($value)    {$this->_autoinitialize = $value;}
   function defaultAutoInitialize()    {return 1;}

   /**
    * Set it to FALSE if you don't want to dispaly the Loading message
    */
   function readShowLoadingMessage()    {return $this->_showloadingmessage;}
   function writeShowLoadingMessage($value)    {$this->_showloadingmessage = $value;}
   function defaultShowLoadingMessage()    {return 1;}

   /**
    * Change the message displayed when a new page is loading
    */
   function readLoadingMessage()    {return $this->_loadingmessage;}
   function writeLoadingMessage($value)    {$this->_loadingmessage = $value;}
   function defaultLoadingMessage()    {return "Loading";}

   protected $_onmobileinit = "";
   protected $_onpagebeforeshow = "";
   protected $_onpagebeforehide = "";
   protected $_onpageshow = "";
   protected $_onpagehide = "";
   protected $_onpagebeforecreate = "";
   protected $_onpagecreate = "";
   protected $_onpageprecreate="";

   protected $_jsonmobileinit = "";
   protected $_jsonpagebeforeshow = "";
   protected $_jsonpagebeforehide = "";
   protected $_jsonpageshow = "";
   protected $_jsonpagehide = "";
   protected $_jsonpagebeforecreate = "";
   protected $_jsonpagecreate = "";
   protected $_jsonready = "";
   protected $_jsonajaxcallcomplete="";
   protected $_jsonajaxcallerror="";
   protected $_jsonorientationchange="";
   protected $_jsonpageprecreate="";
   protected $_jsonpagebeforeload="";
   protected $_jsonpageload="";
   protected $_jsonpageloadfailed="";
   protected $_jsonpagebeforechange="";
   protected $_jsonpagechange="";
   protected $_jsonpagechangefailed="";
   protected $_jsonpageremove="";
   protected $_jsonscrollstart="";
   protected $_jsonscrollstop="";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";
   protected $_jsonvmousecancel = "";
   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";

   /**
    * Normalized event for handling touch or mouse mousecancel events
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Normalized event for handling touchend or mouse click events. On touch devices, this event is dispatched *AFTER* vmouseup.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Normalized event for handling touchend or mouseup events
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Normalized event for handling touchmove or mousemove events
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    *  Normalized event for handling touchstart or mousedown events
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Normalized event for handling touch or mouseover events
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   /**
    * Triggers when a swipe event occurred moving in the right direction.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggers when a swipe event occurred moving in the left direction.
    */
   function readjsOnSwipeLeft()    {return $this->_jsonswipeleft;}
   function writejsOnSwipeLeft($value)    {$this->_jsonswipeleft = $value;}
   function defaultjsOnSwipeLeft()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more.
    */
   function readjsOnSwipe()    {return $this->_jsonswipe;}
   function writejsOnSwipe($value)    {$this->_jsonswipe = $value;}
   function defaultjsOnSwipe()    {return "";}

   /**
    * Triggers after a held complete touch event (close to one second).
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggers after a quick, complete touch event.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}
   /**
   * Triggers when a scroll finishes.
   */
   function readjsOnScrollStop() { return $this->_jsonscrollstop; }
   function writejsOnScrollStop($value) { $this->_jsonscrollstop=$value; }
   function defaultjsOnScrollStop() { return ""; }

   /**
   * Triggers when a scroll begins.
   */
   function readjsOnScrollStart() { return $this->_jsonscrollstart; }
   function writejsOnScrollStart($value) { $this->_jsonscrollstart=$value; }
   function defaultjsOnScrollStart() { return ""; }

   /**
   * This event is triggered just before the framework attempts to remove an external page from the DOM.
   */
   function readjsOnPageRemove() { return $this->_jsonpageremove; }
   function writejsOnPageRemove($value) { $this->_jsonpageremove=$value; }
   function defaultjsOnPageRemove() { return ""; }

   /**
   * This event is triggered when the changePage() request fails to load the page.
   */
   function readjsOnPageChangeFailed() { return $this->_jsonpagechangefailed; }
   function writejsOnPageChangeFailed($value) { $this->_jsonpagechangefailed=$value; }
   function defaultjsOnPageChangeFailed() { return ""; }

   /**
   * This event is triggered after the changePage() request has finished
   * loading the page into the DOM and all page transition animations have completed.
   */
   function readjsOnPageChange() { return $this->_jsonpagechange; }
   function writejsOnPageChange($value) { $this->_jsonpagechange=$value; }
   function defaultjsOnPageChange() { return ""; }

   /**
   * This event is triggered prior to any page loading or transition.
   */
   function readjsOnPageBeforeChange() { return $this->_jsonpagebeforechange; }
   function writejsOnPageBeforeChange($value) { $this->_jsonpagebeforechange=$value; }
   function defaultjsOnPageBeforeChange() { return ""; }

   /**
   * Triggered if the page load request failed.
   */
   function readjsOnPageLoadFailed() { return $this->_jsonpageloadfailed; }
   function writejsOnPageLoadFailed($value) { $this->_jsonpageloadfailed=$value; }
   function defaultjsOnPageLoadFailed() { return ""; }

   /**
   * Triggered after the page is successfully loaded and inserted into the DOM.
   */
   function readjsOnPageLoad() { return $this->_jsonpageload; }
   function writejsOnPageLoad($value) { $this->_jsonpageload=$value; }
   function defaultjsOnPageLoad() { return ""; }

   /**
   * Triggered before any load request is made.
   */
   function readjsOnPageBeforeLoad() { return $this->_jsonpagebeforeload; }
   function writejsOnPageBeforeLoad($value) { $this->_jsonpagebeforeload=$value; }
   function defaultjsOnPageBeforeLoad() { return ""; }

   /**
    * Fired when the document is ready and all the DOM elements are loaded
    */

   function readjsOnReady()    {return $this->_jsonready;}
   function writejsOnReady($value)    {$this->_jsonready = $value;}
   function defaultjsOnReady()    {return "";}

   /**
    * Select a MobileTheme component to indicate the color variation.
    *
    * MobileTheme components indicate the color variation of a control.
    * Different controls asigned to the same MobileTheme will use the same color variation when rendered.
    *
    * @see MobileTheme
    *
    * @return object returns the MobileTheme assigned
    */
   function readTheme()    {return $this->_theme;}
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val);}
   function defaultTheme()    {return "";}

   function handleUndefinedProperty($propname, $propvalue)
   {
      //Do nothing, so no error is raised
   }

   // PHP events

   /**
   * Event fired before pagecreate fires.
   */
   function readOnPagePreCreate() { return $this->_onpageprecreate; }
   function writeOnPagePreCreate($value) { $this->_onpageprecreate=$value; }
   function defaultOnPagePreCreate() { return ""; }

   /**
    * Fired before jqueryMoblie gets loaded
    */
   function readOnMobileInit()    {return $this->_onmobileinit;}
   function writeOnMobileInit($value)    {$this->_onmobileinit = $value;}
   function defaultOnMobileInit()    {return "";}

   /**
    * Fired on the page being shown, before its transition begins.
    */
   function readOnPageBeforeShow()    {return $this->_onpagebeforeshow;}
   function writeOnPageBeforeShow($value)    {$this->_onpagebeforeshow = $value;}
   function defaultOnPageBeforeShow()    {return "";}

   /**
    * Fired on the page being hidden, before its transition begins.
    */
   function readOnPageBeforeHide()    {return $this->_onpagebeforehide;}
   function writeOnPageBeforeHide($value)    {$this->_onpagebeforehide = $value;}
   function defaultOnPageBeforeHide()    {return "";}

   /**
    * Fired on the page being shown, after its transition completes.
    */
   function readOnPageShow()    {return $this->_onpageshow;}
   function writeOnPageShow($value)    {$this->_onpageshow = $value;}
   function defaultOnPageShow()    {return "";}

   /**
    * Fired on the page being hidden, after its transition completes.
    */
   function readOnPageHide()    {return $this->_onpagehide;}
   function writeOnPageHide($value)    {$this->_onpagehide = $value;}
   function defaultOnPageHide()    {return "";}

   /**
    * Fired on the page being initialized, before initialization occurs.
    */
   function readOnPageBeforeCreate()    {return $this->_onpagebeforecreate;}
   function writeOnPageBeforeCreate($value)    {$this->_onpagebeforecreate = $value;}
   function defaultOnPageBeforeCreate()    {return "";}

   /**
    * Fired on the page being initialized, after initialization occurs.
    */
   function readOnPageCreate()    {return $this->_onpagecreate;}
   function writeOnPageCreate($value)    {$this->_onpagecreate = $value;}
   function defaultOnPageCreate()    {return "";}

   //js events

   /**
   * Event fired before pagecreate fires, before elements get enhanced.
   */
   function readjsOnPagePreCreate() { return $this->_jsonpageprecreate; }
   function writejsOnPagePreCreate($value) { $this->_jsonpageprecreate=$value; }
   function defaultjsOnPagePreCreate() { return ""; }

   /**
   * Fired when the device's orientation changes.
   */

   function readjsOnOrientationChange() { return $this->_jsonorientationchange; }
   function writejsOnOrientationChange($value) { $this->_jsonorientationchange=$value; }
   function defaultjsOnOrientationChange() { return ""; }

   /**
  * Fired when an Ajax is finished (after error or success)
  */
   function readjsOnAjaxCallComplete() { return $this->_jsonajaxcallcomplete; }
   function writejsOnAjaxCallComplete($value) { $this->_jsonajaxcallcomplete=$value; }
   function defaultjsOnAjaxCallComplete() { return ""; }

  /**
  * Fired when an Ajax error occurs
  */
   function readjsOnAjaxCallError() { return $this->_jsonajaxcallerror; }
   function writejsOnAjaxCallError($value) { $this->_jsonajaxcallerror=$value; }
   function defaultjsOnAjaxCallError() { return ""; }

   /**
    * Fired before jqueryMoblie gets loaded
    */
   function readjsOnMobileInit()    {return $this->_jsonmobileinit;}
   function writejsOnMobileInit($value)    {$this->_jsonmobileinit = $value;}
   function defaultjsOnMobileInit()    {return "";}

   /**
    * Fired on the page being shown, before its transition begins.
    */
   function readjsOnPageBeforeShow()    {return $this->_jsonpagebeforeshow;}
   function writejsOnPageBeforeShow($value)    {$this->_jsonpagebeforeshow = $value;}
   function defaultjsOnPageBeforeShow()    {return "";}

   /**
    * Fired on the page being hidden, before its transition begins.
    */
   function readjsOnPageBeforeHide()    {return $this->_jsonpagebeforehide;}
   function writejsOnPageBeforeHide($value)    {$this->_jsonpagebeforehide = $value;}
   function defaultjsOnPageBeforeHide()    {return "";}

   /**
    * Fired on the page being shown, after its transition completes.
    */
   function readjsOnPageShow()    {return $this->_jsonpageshow;}
   function writejsOnPageShow($value)    {$this->_jsonpageshow = $value;}
   function defaultjsOnPageShow()    {return "";}

   /**
    * Fired on the page being hidden, after its transition completes.
    */
   function readjsOnPageHide()    {return $this->_jsonpagehide;}
   function writejsOnPageHide($value)    {$this->_jsonpagehide = $value;}
   function defaultjsOnPageHide()    {return "";}

   /**
    * Fired on the page being initialized, before initialization occurs.
    */
   function readjsOnPageBeforeCreate()    {return $this->_jsonpagebeforecreate;}
   function writejsOnPageBeforeCreate($value)    {$this->_jsonpagebeforecreate = $value;}
   function defaultjsOnPageBeforeCreate()    {return "";}

   /**
    * Fired on the page being initialized, after initialization occurs,jquery scripts may be started here.
    */
   function readjsOnPageCreate()    {return $this->_jsonpagecreate;}
   function writejsOnPageCreate($value)    {$this->_jsonpagecreate = $value;}
   function defaultjsOnPageCreate()    {return "";}

   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      $this->Font->Family = 'Helvetica, Arial, sans-serif';
      $this->Font->Size = '16px';

   }

    function dumpCSSRequest($extraparams='')
    {
      //Prevent this page to dump the css request
    }

    function dumpJSRequest($extraparams='')
    {
      //Prevent this page to dump the js request
    }

    function dumpPageHeaderCode()
    {
      //Prevent this page to dump the page code, this should do inside page
    }

   /**
    * On init we check if any PHP event has been set and we fire it.
    * PHP events binded to MPage events will be executed using an Ajax call to self
    * so they don't make a submit event nor refresh or update visilbe content
    * They are ideal to update databases or perform server side process
    */
   function preinit()
   {
      parent::preinit();

      $key = md5($this->owner->Name . $this->Name . $this->Left . $this->Top . $this->Width . $this->Height);
      $opt = $this->input->opt;
      $event = $this->input->event;

      //check for the id param to update the dataset if any
      if((is_object($opt)) && ($opt->asString() == $key))
      {
         if(is_object($event))
         {
            $this->callEvent($event->asString(), array());
            exit;
         }
      }
   }

   /**
    * If UseAjax is set to true and an Ajax call is made all conntrols included in the MPage
    * are rendered as a JSONP object and coded with Base64 so we don't include any illegal character on the JSONP Object
    */
   function processAjax()
   {
      $callback = $this->input->callback;

      if(is_object($callback))
      {
        $elements = array();
        $css = "";

        // dumps all content
         reset($this->controls->items);
         while(list($k, $v) = each($this->controls->items))
         {
           if($v->canShow())
           {
              ob_start();
              $v->show();
              $output = ob_get_contents();
              ob_end_clean();
              if($this->_encoding!="Unicode (UTF-8)            |utf-8")
                $output=utf8_encode($output);
              $elements[]= "\"{$v->Name}_outer\":\"" . base64_encode($output) . "\"";
           }
         }

         // dumps css
         $_GET['css'] = 1;// hack to not dump code (TODO: fix the showCSS function)
          ob_start();
          $this->showCSS();
          $outputcss = ob_get_contents();
          ob_end_clean();
         if($this->_encoding!="Unicode (UTF-8)            |utf-8")
            $outputcss=utf8_encode($outputcss);
          $css = base64_encode($outputcss);

         header('Access-Control-Allow-Origin: *');
         header("Content-type: application/json");
         echo "{";
          echo '"elements": {'.implode(",",$elements)."}";
          echo ',"css":"'.$css."\"";
         echo "}";
         exit;
      }

   }

   // Documented in the parent.
   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);

      //we have to stablish the MOBILE_RPCL_PATH
      $rpclPath=RPCL_HTTP_PATH;
      if($this->_isphonegapenabled && $rpclPath{0}=="/")
         $rpclPath=substr($rpclPath,1);

      if(!defined('MOBILE_RPCL_PATH'))
        define('MOBILE_RPCL_PATH',$rpclPath);

   }

    /**
     * Checks if the jQuery Mobile file should be dumped in header or at the end of the page
     * because in client pages the mobile init doesn't
     *
     * @return bool
     */
    function shouldIncludeJqueryMobileFileInHeader()
   {
       if($this->isclientpage)
           return ! (bool) $this->_jsonmobileinit;
       else
           return true;
   }

   function dumpHeaderJavascript($return_contents = false)
   {

      ob_start();

      if($this->_showloadingmessage == 0)
         $this->_loadingmessage = '';

      $scale = $this->_viewportscale/100;
      $width = ($this->isFixedSize()) ? "width={$this->_width}," : 'width=device-width,';
      echo "<meta name=\"viewport\" content=\"$width initial-scale=$scale, minimum-scale=$scale, maximum-scale=$scale\">\n";
      echo "<script src=\"" . MOBILE_RPCL_PATH . "/jquerymobile/js/base64.js\"></script>\n";
      echo "<script src=\"" . MOBILE_RPCL_PATH . JQUERY_FILE . "\"></script>\n";
      echo "<script src=\"" . MOBILE_RPCL_PATH . JQUERY_MOBILE_FUNCTIONS . "\"></script>\n";
      echo "<link rel=\"stylesheet\" href=\"".MOBILE_RPCL_PATH.THEME_BASIC."\" />\n";

      if ($this->_cssfile=="cssCustom" && $this->_customcssfile!="")
        echo "<link rel=\"stylesheet\" href=\"$this->_customcssfile\" />\n";

      if(isset($css) && !empty($css))
         echo "<link rel=\"stylesheet\" href=\"$css\" />\n";

      echo "<script>\n";
               //the mobileinit script, to configure jquerymobile
                $this->dumpJSEvent($this->_jsonmobileinit);

                echo "jQuery(document).bind('mobileinit', function(){\n";
                echo "  jQuery.extend(jQuery.mobile , {\n";
                echo "    loadingMessage: '$this->_loadingmessage',\n";
                echo "    pageLoadErrorMessage: '$this->_pageloaderrormessage',\n";
                echo "    autoInitialize: " . ($this->_autoinitialize == 1? "true": "false") . ",\n";
                echo "    defaultPageTransition: '" . transitionValue($this->_defaulttransition) . "',\n";
                echo "    subPageUrlKey: '$this->_subpageurlkey',\n";
                echo "    activePageClass: '$this->_activepageclass',\n";
                echo "    activeBtnClass: '$this->_activebtnclass',\n";
                echo "    minScrollBack: $this->_minscrollback,\n";
                echo "    touchOverflowEnabled:" . ($this->_touchoverflowenabled == 1? "true": "false") . " \n";
                echo "  });\n";
                echo "  jQuery.mobile.page.prototype.options.degradeInputs.range='text';\n";
                echo $this->_jsonmobileinit != ""? $this->_jsonmobileinit . "()\n": "";
                if($this->_jsonorientationchange !="")
                {
                  echo "  jQuery(window).bind('orientationchange',function(){\n";
                  echo $this->_jsonorientationchange."();\n";
                  echo "  });\n";
                }
                echo "});";
      echo "</script>\n";

      if ($this->shouldIncludeJqueryMobileFileInHeader())
          echo "<script src=\"" . MOBILE_RPCL_PATH . JQUERY_MOBILE_FILE . "\"></script>\n";

      echo "<link rel=\"stylesheet\" href=\"" . MOBILE_RPCL_PATH . GENERAL_CSS . "\" />\n";

      if($this->_isphonegapenabled)
      {
        if(!defined('PHONEGAP_JS'))
        {
          echo "<script src=\"" . MOBILE_RPCL_PATH . PHONEGAP_FILE . "\"></script>\n";
          define('PHONEGAP_JS', 1);
        }
        echo "<script src=\"" . MOBILE_RPCL_PATH . PHONEGAP_FUNCTIONS . "\"></script>\n";
      }

      $output = ob_get_contents();
      ob_end_clean();

      if($return_contents)
         return $output;
      else
         echo $output;

   }

   /**
   * Override the javascript output in order to use the custom own
   */
   function dumpJavascript()
   {
      ///TODO: Refactor the header, check a more elegant solution
      if (isset($_GET['js']))
        header("Content-type: application/javascript");
      $this->dumpJavascriptBlock();
   }


   /**
   * Dumps all the javasccript of MPage including its controls
   */
   function dumpJavascriptBlock()
   {
      //get the pageinit and phonegap deviceready events and functions from all components
      $data=$this->pageInit();

      //output phonegap init functions
      if($data['deviceready']!="")
      {
        echo $data['deviceready'];
        
        $data['content'].= " document.addEventListener(\"deviceready\", {$this->_name}DeviceReady, false);\n";
      }

      //bind the pageinit event including the jquery of all components that need to be binded on pageinit
      $this->jqueryEvent('jsonpagecreate',$data['content']);

      //bind the rest
      $this->jqueryEvent('jsonpagebeforeshow');
      $this->jqueryEvent('jsonpagebeforehide');
      $this->jqueryEvent('jsonpageshow');
      $this->jqueryEvent('jsonpagehide');
      $this->jqueryEvent('jsonpagebeforecreate');
      $this->jqueryEvent('jsonorientationchange');
      $this->jqueryEvent('jsonajaxcallerror');
      $this->jqueryEvent('jsonajaxcallcomplete');
      $this->jqueryEvent('jsonready');
      $this->jqueryEvent('jsonpageprecreate');
      $this->jqueryEvent('jsonpagebeforeload');
      $this->jqueryEvent('jsonpageload');
      $this->jqueryEvent('jsonpageloadfailed');
      $this->jqueryEvent('jsonpagebeforechange');
      $this->jqueryEvent('jsonpagechange');
      $this->jqueryEvent('jsonpagechangefailed');
      $this->jqueryEvent('jsonpageremove');
      $this->jqueryEvent('jsonscrollstart');
      $this->jqueryEvent('jsonscrollstop');

      $this->jqueryEvent('jsontap');
      $this->jqueryEvent('jsontaphold');
      $this->jqueryEvent('jsonswipe');
      $this->jqueryEvent('jsonswipeleft');
      $this->jqueryEvent('jsonswiperight');
      $this->jqueryEvent('jsonvmouseover');
      $this->jqueryEvent('jsonvmousedown');
      $this->jqueryEvent('jsonvmousemove');
      $this->jqueryEvent('jsonvmouseup');
      $this->jqueryEvent('jsonvclick');
      $this->jqueryEvent('jsonvmousecancel');

      $this->dumpJSEvent($this->_jsonload);
      $this->dumpJSEvent($this->_jsonreset);
      $this->dumpJSEvent($this->_jsonsubmit);
      $this->dumpJSEvent($this->_jsonunload);

      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);

      $this->dumpJSEvent($this->_jsonpagebeforeshow);
      $this->dumpJSEvent($this->_jsonpagebeforehide);
      $this->dumpJSEvent($this->_jsonpageshow);
      $this->dumpJSEvent($this->_jsonpagehide);
      $this->dumpJSEvent($this->_jsonpagebeforecreate);
      $this->dumpJSEvent($this->_jsonpagecreate);
      $this->dumpJSEvent($this->_jsonorientationchange);
      $this->dumpJSEvent($this->_jsonajaxcallcomplete);
      $this->dumpJSEvent($this->_jsonajaxcallerror);
      $this->dumpJSEvent($this->_jsonready);
      $this->dumpJSEvent($this->_jsonpageprecreate);
      $this->dumpJSEvent($this->_jsonpagebeforeload);
      $this->dumpJSEvent($this->_jsonpageload);
      $this->dumpJSEvent($this->_jsonpageloadfailed);
      $this->dumpJSEvent($this->_jsonpagebeforechange);
      $this->dumpJSEvent($this->_jsonpagechange);
      $this->dumpJSEvent($this->_jsonpagechangefailed);
      $this->dumpJSEvent($this->_jsonpageremove);
      $this->dumpJSEvent($this->_jsonscrollstart);
      $this->dumpJSEvent($this->_jsonscrollstop);

      //we dump the ajax call errror function

      if($this->_jsonajaxcallerror=="" && $this->_useajax==1)
      {
        echo " var {$this->Name}JSAjaxCallError=function(){
          jQuery(\"<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all'><h1>\"+ jQuery.mobile.pageLoadErrorMessage +\"</h1></div>\")
						.css({ \"display\": \"block\", \"opacity\": 0.96, \"top\": jQuery(window).scrollTop() + 100 })
						.appendTo( jQuery.mobile.pageContainer )
						.delay( 800 )
						.fadeOut( 400, function(){
							jQuery(this).remove();
						});
        };\n ";
      }

   }

   /**
   * Dumps the CSS content of all the controls included in MPage
   */
   function dumpChildrenCSS()
   {
      reset($this->components->items);
      while(list($k, $v) = each($this->components->items))
      {
        if(method_exists($v, 'dumpCSS'))
          $v->dumpCSS();
      }
   }

   /**
    * This function creates the code to handle all javascript MPage events
    * If there is a PHP event with the same name we create the code to make the pertinent Ajax call to process it.
    */
   function jqueryEvent($event,$extra="")
   {
      $phpEvent = substr($event, 2);
      $jqEvent = substr($event, 4);

      //change pagecreate event for init event (jquerymobile rc1)

      if($jqEvent=='pagecreate')
        $jqEvent='pageinit';
      if($jqEvent=='pageprecreate')
        $jqEvent='pagecreate';

      if((method_exists($this,"get{$phpEvent}") && $this->$phpEvent!="") || $this->$event!="" || $extra!="")
      {
        echo "jQuery('#{$this->_name}').bind('$jqEvent',function(event){\n";
        //if there is a js event we call it;
        if($this->$event != "")
          echo $this->$event . "(event);\n";

        if($extra != "")
          echo " $extra\n";

        //if there is a php event we'll call ajax to ejecute it
        if(method_exists($this,"get{$phpEvent}") && $this->$phpEvent!="")
        {
          $key = md5($this->owner->Name . $this->Name . $this->Left . $this->Top . $this->Width . $this->Height);

          if($this->_useajax)
            $self = $this->_useajaxuri != ""? $this->_useajaxuri: $_SERVER['PHP_SELF'];
          else
            $self = $_SERVER['PHP_SELF'];

          $url = str_replace(' ', '%20',$self) . "?opt=$key&event=" . $phpEvent . "&time=" . time();
          echo "  jQuery.ajax({\n";
          echo "    url:'$url'\n";
          echo "  });\n";
        }

        echo "});\n";
      }
   }

   /**
   * Retrieves the jquery events and deviceready events that need to be binded on pageinit
   */
   function pageInit()
   {
      $output=array("content"=>"","deviceready"=>"");
      $deviceready=array(0=>"",1=>"");
      //if any children has javascript binded to every event we dump the code here
      reset($this->components->items);
      while(list($k, $v) = each($this->components->items))
      {
          if(($v->inheritsFrom('Control') && $v->canShow()) || !$v->inheritsFrom('Control'))
          {
            //calling all javascript include in pagecreate
            if(method_exists($v, 'pagecreate'))
              $output['content'].= $v->pagecreate();

            if(method_exists($v, 'ondeviceready'))
            {
              if($v->inheritsFrom('CustomMPageEvents'))
                $deviceready[1].= $v->ondeviceready();
              else
                $deviceready[0].= $v->ondeviceready();
            }
          }
      }

      if($deviceready[0]!="" || $deviceready[1]!="")
      {
        $output['deviceready'].= "var {$this->_name}DeviceReady=function(){\n";
        $output['deviceready'].= $deviceready[0];
        $output['deviceready'].= $deviceready[1];
        $output['deviceready'].= "};\n";
      }

      return $output;
   }

   function dumpStartBody($style="", $attributes="", $extraparams="")
   {
      //Get the theme string
      if(($this->ControlState & csDesigning) == csDesigning || $this->_theme == "")
         $theme = "";
      else
         $theme = $this->_theme->themeVal(1);

      // target has to be set to prevent jquerymobile to process forms with ajax when the action relies on the MPage itself
      if($this->_action == "")
         $this->_target = "_self";

      $ajaxuri = ($this->_useajaxuri != "") ? $this->_useajaxuri: str_replace(' ', '%20',$_SERVER['PHP_SELF']);
      $ajax    = ($this->_useajax) ? "data-ajax-url=\"$ajaxuri\"" : "";

      echo "<body $style $attributes>\n";
      echo "<div data-role=\"page\" $theme id=\"{$this->_name}\" $ajax >\n";

      // Unlike Page control, we dump the children header code here (i.e: plugin includes, css..)
      // because the page prints
      $this->dumpChildrenHeaderCode();

      /**
      *  JS and CSS files has to be linked inside the <div data-role='page'> tag so they are processed by jquerymobile
      *  when the page is loaded using Ajax.
      *  If it is a phonegap app and we are not loading dinamic contents using ajax, we load the static css and js files instead of the dinamic ones
      */
      if($this->_isphonegapenabled)
      {
          $css=str_replace(".php",".css",substr(str_replace(' ', '%20',$_SERVER['PHP_SELF']),1));
          $js=str_replace(".php",".js",substr(str_replace(' ', '%20',$_SERVER['PHP_SELF']),1));
		  
		  if ($this->isclientpage)
		  {
			$js=str_replace(".php","_php.js",substr(str_replace(' ', '%20',$_SERVER['PHP_SELF']),1));	
		  }
      }
      else
      {
        // If an ajax request is detected
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
          if(strpos($extraparams, 'nolayouts') !== false)
            $_GET['nolayouts'] = 1;
            
          $_GET['css'] = 1;// hack to not dump code (TODO: fix the showCSS function)

          echo "<style type=\"text/css\">\n";
          $this->showCSS();
          echo "</style>\n";

          unset($_GET['css']);
          if(strpos($extraparams, 'nolayouts') !== false)
            unset($_GET['nolayouts']);
        }
        else
        {
          $css=str_replace(' ', '%20',$_SERVER['PHP_SELF']) . "?css=1";
        }


        $js=str_replace(' ', '%20',$_SERVER['PHP_SELF']) . "?js=1";
      }

      if(isset($css))
      {
        $cssUrl = $css . $extraparams;
        echo "<link id=\"{$this->Name}_css\" rel=\"stylesheet\" href=\"$cssUrl\" />\n";
      }

      //If this is a client page, dump the .js file with the same name
      if ($this->isclientpage)
      {
        echo "<script type=\"text/javascript\" src=\"".str_replace('.php','.js',str_replace(' ', '%20',$_SERVER['PHP_SELF']))."\"></script>\n";
		$extraparams='';
      }
      
      $jsUrl = htmlentities($js . $extraparams);
      echo "<script id=\"{$this->Name}_js\" src=\"$jsUrl\"></script>\n";
   }

   function dumpEndBody()
   {
      echo "</div>\n";

       // Only in a few cases we need to dump this file at the end of the page, this behavior is only affected in the first page
       if (!$this->shouldIncludeJqueryMobileFileInHeader())
            echo "<script src=\"" . MOBILE_RPCL_PATH . JQUERY_MOBILE_FILE . "\"></script>\n";

      echo "</body>\n";
   }

}

/**
 * Container for a webpage based on jQuery Mobile (http://jquerymobile.com/).
 *
 * @link wiki://MPage
 * @link wiki://Containers
 */
class MPage extends CustomMPage
{

    // Documented in the parent.
    function getActiveControl() { return $this->readactivecontrol(); }
    function setActiveControl($value) { $this->writeactivecontrol($value); }

    // Documented in the parent.
    function getTouchOverflowEnabled() { return $this->readtouchoverflowenabled(); }
    function setTouchOverflowEnabled($value) { $this->writetouchoverflowenabled($value); }

    // Documented in the parent.
    function getHiddenFields() { return $this->readhiddenfields(); }
    function setHiddenFields($value) { $this->writehiddenfields($value); }

    // Documented in the parent.
    function getCssFile() { return $this->readcssfile(); }
    function setCssFile($value) { $this->writecssfile($value); }

    // Documented in the parent.
    function getCustomCssFile() { return $this->readcustomcssfile(); }
    function setCustomCssFile($value) { $this->writecustomcssfile($value); }

    // Documented in the parent.
    function getIsPhoneGapEnabled() { return $this->readisphonegapenabled(); }
    function setIsPhoneGapEnabled($value) { $this->writeisphonegapenabled($value); }

    // Documented in the parent.
    function getjsOnLoad()    {return $this->readjsonload();}
    function setjsOnLoad($value)    {$this->writejsonload($value);}

    // Documented in the parent.
    function getjsOnUnload()    {return $this->readjsonunload();}
    function setjsOnUnload($value)    {$this->writejsonunload($value);}

    // Documented in the parent.
    function getLayout()    {return $this->readLayout();}
    function setLayout($value)    {$this->writeLayout($value);}

    // Documented in the parent.
    function getIcon()    {return $this->readicon();}
    function setIcon($value)    {$this->writeicon($value);}

    // Documented in the parent.
    function getEncoding()    {return $this->readencoding();}
    function setEncoding($value)    {$this->writeencoding($value);}

    // Documented in the parent.
    function readFormEncoding()    {return $this->readformencoding();}
    function writeFormEncoding($value)    {$this->writeformencoding($value);}

    // Documented in the parent.
    function getAlignment()    {return $this->readAlignment();}
    function setAlignment($value)    {$this->writeAlignment($value);}

    // Documented in the parent.
    function getColor()    {return $this->readColor();}
    function setColor($value)    {$this->writeColor($value);}

    // Documented in the parent.
    function getShowHint()    {return $this->readShowHint();}
    function setShowHint($value)    {$this->writeShowHint($value);}

    // Documented in the parent.
    function getVisible()    {return $this->readVisible();}
    function setVisible($value)    {$this->writeVisible($value);}

    // Documented in the parent.
    function getCaption()    {return $this->readCaption();}
    function setCaption($value)    {$this->writeCaption($value);}

    // Documented in the parent.
    function getFont()    {return $this->readFont();}
    function setFont($value)    {$this->writeFont($value);}

    // Documented in the parent.
    function getBackground()    {return $this->readbackground();}
    function setBackground($value)    {$this->writebackground($value);}

    // Documented in the parent.
    function getTemplateEngine()    {return $this->readtemplateengine();}
    function setTemplateEngine($value)    {$this->writetemplateengine($value);}

    // Documented in the parent.
    function getAction()    {return $this->readaction();}
    function setAction($value)    {$this->writeaction($value);}

    // Documented in the parent.
    function getTemplateFilename()    {return $this->readtemplatefilename();}
    function setTemplateFilename($value)    {$this->writetemplatefilename($value);}

    // Documented in the parent.
    function getViewportScale() { return $this->readviewportscale(); }
    function setViewportScale($value) { $this->writeviewportscale($value); }

    // Documented in the parent.
    function getUseAjax()    {return $this->readuseajax();}
    function setUseAjax($value)    {$this->writeuseajax($value);}

    // Documented in the parent.
    function getUseAjaxUri()    {return $this->readuseajaxuri();}
    function setUseAjaxUri($value)    {$this->writeuseajaxuri($value);}

    // Documented in the parent.
    function getLanguage()    {return $this->readlanguage();}
    function setLanguage($value)    {$this->writelanguage($value);}

    // Documented in the parent.
    function getCache()    {return $this->readcache();}
    function setCache($value)    {$this->writecache($value);}

    // Documented in the parent.
    function getjsOnSubmit()    {return $this->readjsonsubmit();}
    function setjsOnSubmit($value)    {$this->writejsonsubmit($value);}

    // Documented in the parent.
    function getjsOnReset()    {return $this->readjsonreset();}
    function setjsOnReset($value)    {$this->writejsonreset($value);}

    // Documented in the parent.
    function getTarget()    {return $this->readtarget();}
    function setTarget($value)    {$this->writetarget($value);}

    // Documented in the parent.
    function getOnTemplate()    {return $this->readontemplate();}
    function setOnTemplate($value)    {$this->writeontemplate($value);}

    // Documented in the parent.
    function getOnBeforeAjaxProcess()    {return $this->readonbeforeajaxprocess();}
    function setOnBeforeAjaxProcess($value)    {$this->writeonbeforeajaxprocess($value);}

    // Documented in the parent.
    function getOnAfterAjaxProcess()    {return $this->readonafterajaxprocess();}
    function setOnAfterAjaxProcess($value)    {$this->writeonafterajaxprocess($value);}

    // Documented in the parent.
    function getDirectionality()    {return $this->readdirectionality();}
    function setDirectionality($value)    {$this->writedirectionality($value);}

    // Documented in the parent.
    function getGenerateTable()    {return $this->readgeneratetable();}
    function setGenerateTable($value)    {$this->writegeneratetable($value);}

    // Documented in the parent.
    function getTopMargin()    {return $this->readtopmargin();}
    function setTopMargin($value)    {$this->writetopmargin($value);}

    // Documented in the parent.
    function getLeftMargin()    {return $this->readleftmargin();}
    function setLeftMargin($value)    {$this->writeleftmargin($value);}

    // Documented in the parent.
    function getBottomMargin()    {return $this->readbottommargin();}
    function setBottomMargin($value)    {$this->writebottommargin($value);}

    // Documented in the parent.
    function getRightMargin()    {return $this->readrightmargin();}
    function setRightMargin($value)    {$this->writerightmargin($value);}

    // Documented in the parent.
    function getShowHeader()    {return $this->readshowheader();}
    function setShowHeader($value)    {$this->writeshowheader($value);}

    // Documented in the parent.
    function getIsForm()    {return $this->readisform();}
    function setIsForm($value)    {$this->writeisform($value);}

    // Documented in the parent.
    function getGenerateDocument()    {return $this->readgeneratedocument();}
    function setGenerateDocument($value)    {$this->writegeneratedocument($value);}

    // Documented in the parent.
    function getShowFooter()    {return $this->readshowfooter();}
    function setShowFooter($value)    {$this->writeshowfooter($value);}

    // Documented in the parent.
    function getOnBeforeShowHeader()    {return $this->readonbeforeshowheader();}
    function setOnBeforeShowHeader($value)    {$this->writeonbeforeshowheader($value);}

    // Documented in the parent.
    function getOnAfterShowFooter()    {return $this->readonaftershowfooter();}
    function setOnAfterShowFooter($value)    {$this->writeonaftershowfooter($value);}

    // Documented in the parent.
    function getOnShowHeader()    {return $this->readonshowheader();}
    function setOnShowHeader($value)    {$this->writeonshowheader($value);}

    // Documented in the parent.
    function getOnStartBody()    {return $this->readonstartbody();}
    function setOnStartBody($value)    {$this->writeonstartbody($value);}

    // Documented in the parent.
    function getOnCreate()    {return $this->readoncreate();}
    function setOnCreate($value)    {$this->writeoncreate($value);}

    // Documented in the parent.
    function getTheme()    {return $this->readtheme();}
    function setTheme($value)    {$this->writetheme($value);}

    // Documented in the parent.
    function getOnMobileInit()    {return $this->readonmobileinit();}
    function setOnMobileInit($value)    {$this->writeonmobileinit($value);}

    // Documented in the parent.
    function getOnPageBeforeShow()    {return $this->readonpagebeforeshow();}
    function setOnPageBeforeShow($value)    {$this->writeonpagebeforeshow($value);}

    // Documented in the parent.
    function getOnPageBeforeHide()    {return $this->readonpagebeforehide();}
    function setOnPageBeforeHide($value)    {$this->writeonpagebeforehide($value);}

    // Documented in the parent.
    function getOnPageShow()    {return $this->readonpageshow();}
    function setOnPageShow($value)    {$this->writeonpageshow($value);}

    // Documented in the parent.
    function getOnPageHide()    {return $this->readonpagehide();}
    function setOnPageHide($value)    {$this->writeonpagehide($value);}

    // Documented in the parent.
    function getOnPageBeforeCreate()    {return $this->readonpagebeforecreate();}
    function setOnPageBeforeCreate($value)    {$this->writeonpagebeforecreate($value);}

    // Documented in the parent.
    function getOnPagePreCreate() { return $this->readonpageprecreate(); }
    function setOnPagePreCreate($value) { $this->writeonpageprecreate($value); }

    // Documented in the parent.
    function getOnPageCreate()    {return $this->readonpagecreate();}
    function setOnPageCreate($value)    {$this->writeonpagecreate($value);}

    // Documented in the parent.
    function getjsOnMobileInit()    {return $this->readjsonmobileinit();}
    function setjsOnMobileInit($value)    {$this->writejsonmobileinit($value);}

    // Documented in the parent.
    function getjsOnPageBeforeShow()    {return $this->readjsonpagebeforeshow();}
    function setjsOnPageBeforeShow($value)    {$this->writejsonpagebeforeshow($value);}

    // Documented in the parent.
    function getjsOnPageBeforeHide()    {return $this->readjsonpagebeforehide();}
    function setjsOnPageBeforeHide($value)    {$this->writejsonpagebeforehide($value);}

    // Documented in the parent.
    function getjsOnPageShow()    {return $this->readjsonpageshow();}
    function setjsOnPageShow($value)    {$this->writejsonpageshow($value);}

    // Documented in the parent.
    function getjsOnPageHide()    {return $this->readjsonpagehide();}
    function setjsOnPageHide($value)    {$this->writejsonpagehide($value);}

    // Documented in the parent.
    function getjsOnPageBeforeCreate()    {return $this->readjsonpagebeforecreate();}
    function setjsOnPageBeforeCreate($value)    {$this->writejsonpagebeforecreate($value);}

    // Documented in the parent.
    function getjsOnPageCreate()    {return $this->readjsonpagecreate();}
    function setjsOnPageCreate($value)    {$this->writejsonpagecreate($value);}

    // Documented in the parent.
    function getjsOnPagePreCreate() { return $this->readjsonpageprecreate(); }
    function setjsOnPagePreCreate($value) { $this->writejsonpageprecreate($value); }

    // Documented in the parent.
    function getLoadingMessage()    {return $this->readloadingmessage();}
    function setLoadingMessage($value)    {$this->writeloadingmessage($value);}

    // Documented in the parent.
    function getShowLoadingMessage()    {return $this->readshowloadingmessage();}
    function setShowLoadingMessage($value)    {$this->writeshowloadingmessage($value);}

    // Documented in the parent.
    function getAutoInitialize()    {return $this->readautoinitialize();}
    function setAutoInitialize($value)    {$this->writeautoinitialize($value);}

    // Documented in the parent.
    function getDefaultTransition()    {return $this->readdefaulttransition();}
    function setDefaultTransition($value)    {$this->writedefaulttransition($value);}

    // Documented in the parent.
    function getPageLoadErrorMessage()    {return $this->readpageloaderrormessage();}
    function setPageLoadErrorMessage($value)    {$this->writepageloaderrormessage($value);}

    // Documented in the parent.
    function getjsOnAjaxCallComplete() { return $this->readjsonajaxcallcomplete(); }
    function setjsOnAjaxCallComplete($value) { $this->writejsonajaxcallcomplete($value); }

    // Documented in the parent.
    function getjsOnAjaxCallError() { return $this->readjsonajaxcallerror(); }
    function setjsOnAjaxCallError($value) { $this->writejsonajaxcallerror($value); }

    // Documented in the parent.
    function getjsOnOrientationChange() { return $this->readjsonorientationchange(); }
    function setjsOnOrientationChange($value) { $this->writejsonorientationchange($value); }

    // Documented in the parent.
    function getjsOnPageRemove() { return $this->readjsonpageremove(); }
    function setjsOnPageRemove($value) { $this->writejsonpageremove($value); }

    // Documented in the parent.
    function getjsOnPageChangeFailed() { return $this->readjsonpagechangefailed(); }
    function setjsOnPageChangeFailed($value) { $this->writejsonpagechangefailed($value); }

    // Documented in the parent.
    function getjsOnPageChange() { return $this->readjsonpagechange(); }
    function setjsOnPageChange($value) { $this->writejsonpagechange($value); }

    // Documented in the parent.
    function getjsOnPageBeforeChange() { return $this->readjsonpagebeforechange(); }
    function setjsOnPageBeforeChange($value) { $this->writejsonpagebeforechange($value); }

    // Documented in the parent.
    function getjsOnPageLoadFailed() { return $this->readjsonpageloadfailed(); }
    function setjsOnPageLoadFailed($value) { $this->writejsonpageloadfailed($value); }

    // Documented in the parent.
    function getjsOnPageLoad() { return $this->readjsonpageload(); }
    function setjsOnPageLoad($value) { $this->writejsonpageload($value); }

    // Documented in the parent.
    function getjsOnPageBeforeLoad() { return $this->readjsonpagebeforeload(); }
    function setjsOnPageBeforeLoad($value) { $this->writejsonpagebeforeload($value); }

    // Documented in the parent.
    function getjsOnScrollStart() { return $this->readjsonscrollstart(); }
    function setjsOnScrollStart($value) { $this->writejsonscrollstart($value); }

    // Documented in the parent.
    function getjsOnScrollStop() { return $this->readjsonscrollstop(); }
    function setjsOnScrollStop($value) { $this->writejsonscrollstop($value); }

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsonswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsonswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsonswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsonswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsonswipe();}
    function setjsOnSwipe($value)    {$this->writejsonswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsontaphold();}
    function setjsOnTapHold($value)    {$this->writejsontaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsontap();}
    function setjsOnTap($value)    {$this->writejsontap($value);}

    // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsonvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsonvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsonvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsonvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsonvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsonvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsonvclick();}
    function setjsOnVClick($value)    {$this->writejsonvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsonvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsonvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsonvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsonvmouseup($value);}

    // Documented in the parent.
    function getSubPageUrlKey() { return $this->readsubpageurlkey(); }
    function setSubPageUrlKey($value) { $this->writesubpageurlkey($value); }

    // Documented in the parent.
    function getActivePageClass() { return $this->readactivepageclass(); }
    function setActivePageClass($value) { $this->writeactivepageclass($value); }

    // Documented in the parent.
    function getActiveBtnClass() { return $this->readactivebtnclass(); }
    function setActiveBtnClass($value) { $this->writeactivebtnclass($value); }

    // Documented in the parent.
    function getMinScrollBack() { return $this->readminscrollback(); }
    function setMinScrollBack($value) { $this->writeminscrollback($value); }
}
?>
