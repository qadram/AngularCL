<?php
require_once("rpcl/rpcl.inc.php");
//Includes
use_unit("controls.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("classes.inc.php");
use_unit('checklst.inc.php');
use_unit('html5.inc.php');
use_unit('jquerymobile/jmobile.common.inc.php');

/**
 * Base class that generates a basic mobile container.
 */

class CustomMPanel extends CustomPanel
{
   protected $_theme = "";
   protected $_role = "none";
   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";


   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}
   /**
    * Indicates the Role of the control.
    * The role indicates the way that container is going to be rendered
    */
   function readRole()    {return $this->_role;}
   function writeRole($value)    {$this->_role = $value;}
   function defaultRole()    {return "none";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 300;
      $this->_height = 300;
      $this->_divwrap = 1;

      $this->_layout->Type = "ABS_XY_LAYOUT";
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   /**
    * Helper function that returns the string data-role attribute.
    * The role is needed to indicate the way the component is going to be rendered.
    *
    * @return string
    */
   function roleVal()
   {
      switch($this->_role)
      {
         case "rNone":
            $role = "none";
            break;
         case "rPage":
            $role = "page";
            break;
         case "rContent":
            $role = "content";
            break;
         case "rHeader":
            $role = "header";
            break;
         case "rFooter":
            $role = "footer";
            break;
         case "rNavBar":
            $role = "navbar";
            break;
         case "rControlGroup":
            $role = "controlgroup";
            break;
         case "rCollapsible":
            $role = "collapsible";
            break;
         case "rCollapsibleSet":
            $role = "collapsible-set";
            break;
         case "rFieldcontain":
            $role = "fieldcontain";
            break;

         default:
            $role = "none";
            break;
      }

      return "data-role=\"$role\"";
   }

   // Documented in the parent.
   function dumpContents()
   {
      $this->DumpFormatedContent();
   }

   /**
    * Helper function that renders a basic div structure with the assigned Role and Theme.
    *
    * @param string $attributes         Extra attributes to be added at the main DIV tag.
    * @param string $headerContent      Text to be dumped before the container's contents and right after the opening DIV tag.
    * @param string $footerContent      Text to be dumped after the container's content and right before the closing DIV tag.
    *
    * @internal
    */
   function DumpFormatedContent($attributes = array(), $headerContent = "", $footerContent = "", $top = 0, $left = 0)
   {
      //Get the theme string
      $theme = "";
      if($this->_theme!="")
      {
        $RealTheme=RealTheme($this);
        $theme = $RealTheme->themeVal(1);
      }
       $attributes['id'] = $this->Name;

      JQMDesignStart($this, $top, $left);

      $attributesString = "";
      foreach($attributes as $i=>$v)
      {
         $attributesString .= " $i=\"$v\"";
      }

      echo "<div " . $this->roleVal() . " $theme $attributesString >";
      if($headerContent != "")
         echo $headerContent;

      $this->dumpLayoutContents();

      if($footerContent != "")
         echo $footerContent;
      echo "</div>\n";
      JQMDesignEnd($this);
   }
}

/**
 * Base class for MControl component
 */
class CustomMControl extends Control
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_onclick = "";
   protected $_ondblclick = "";

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   /**
    * Triggered by a double click in the control with the mouse pointer.
    */
   function readOnDblClick()    {return $this->_ondblclick;}
   function writeOnDblClick($value)    {$this->_ondblclick = $value;}
   function defaultOnDblClick()    {return "";}

   /**
    * Triggered by a single click in the control with the mouse pointer.
    */
   function readOnClick()    {return $this->_onclick;}
   function writeOnClick($value)    {$this->_onclick = $value;}
   function defaultOnClick()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 300;
      $this->_height = 60;
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#$this->_name li')", $this, 'click');
      $output .= bindEvents("jQuery('#$this->_name li')", $this, 'dblclick');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'mouseup');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'mousedown');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name li')", $this, 'vmousecancel');

      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function init()
   {
      parent::init();

      $submitEventValue = $this->input->{$this->readJSWrapperHiddenFieldName()};

      if(is_object($submitEventValue))
      {
         // check if the a click event has been fired
         if($this->_onclick != null && $submitEventValue->asString() == $this->readJSWrapperSubmitEventValue($this->_onclick))
         {
            $this->callEvent('onclick', array());
         }
         // check if the a double-click event has been fired
         if($this->_ondblclick != null && $submitEventValue->asString() == $this->readJSWrapperSubmitEventValue($this->_ondblclick))
         {
            $this->callEvent('ondblclick', array());
         }
      }
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();
      MHeader($this);
   }

   function dumpFormItems()
   {
      // add a hidden field so we can determine which event for the label was fired
      if($this->_onclick != null || $this->_ondblclick != null)
      {
         $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
         echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
      }
   }
}

/**
 * This class is a Mobile Control
 * Includes all the mobile specific events and also the Theme and Enhancement properties
 *
 */
class MControl extends CustomMControl
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readtheme();}
   function setTheme($value)    {$this->writetheme($value);}

   function getFont()    {return $this->readfont();}
   function setFont($value)    {$this->writefont($value);}

   function getParentFont()    {return $this->readparentfont();}
   function setParentFont($value)    {$this->writeparentfont($value);}

   function getColor()    {return $this->readcolor();}
   function setColor($value)    {$this->writecolor($value);}

   function getParentColor()    {return $this->readparentcolor();}
   function setParentColor($value)    {$this->writeparentcolor($value);}

   function getjsOnClick()    {return $this->readjsOnclick();}
   function setjsOnClick($value)    {$this->writejsOnclick($value);}

   function getOnClick()    {return $this->readonclick();}
   function setOnClick($value)    {$this->writeonclick($value);}

   function getOnDblClick()    {return $this->readondblclick();}
   function setOnDblClick($value)    {$this->writeondblclick($value);}

   function getjsOnDblClick()    {return $this->readjsOndblclick();}
   function setjsOnDblClick($value)    {$this->writejsOndblclick($value);}

   function getjsOnMouseUp()    {return $this->readjsOnmouseup();}
   function setjsOnMouseUp($value)    {$this->writejsOnmouseup($value);}

   function getjsOnMouseDown()    {return $this->readjsOnmousedown();}
   function setjsOnMouseDown($value)    {$this->writejsOnmousedown($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}




/**
 * MPanel creates a container that can have asigned a MobileTheme.
 * It can be used as the Panel control, but you can also manage its appearance like
 * all the Mobile controls.
 *
 * @see Panel
 */
class MPanel extends CustomMPanel
{
   function getTheme()    {return $this->readtheme();}
   function setTheme($value)    {$this->writetheme($value);}

   function getFont()    {return $this->readfont();}
   function setFont($value)    {$this->writefont($value);}

   function getParentFont()    {return $this->readparentfont();}
   function setParentFont($value)    {$this->writeparentfont($value);}

   function getColor()    {return $this->readcolor();}
   function setColor($value)    {$this->writecolor($value);}

   function getParentColor()    {return $this->readparentcolor();}
   function setParentColor($value)    {$this->writeparentcolor($value);}

   function getHidden()    {return $this->readhidden();}
   function setHidden($value)    {$this->writehidden($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}



   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      $this->_role = "rContent";
   }
    /*
   function dumpContents()
   {
      $attributes["style"] = "padding:0px;margin:0px;";
      if($this->_hidden)
         $attributes["style"] .= "visibility:hidden;";
      $attributes['id'] = $this->Name;
      $this->DumpFormatedContent($attributes);
   }
   */
}

/**
 * Base class for MButton controls.
 */
class CustomMButton extends Button
{
   protected $_theme = "";
   protected $_systemIcon = "";
   protected $_icon = "";
   protected $_iconPos = "ipLeft";
   protected $_roundedcorners = 1;
   protected $_iconshadow = 1;
   protected $_shadow = 1;
   protected $_enhancement = "enFull";
   protected $_inline = 0;

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   /**
    * Indicates if the component drop a shadow
    */
   function readShadow()    {return $this->_shadow;}
   function writeShadow($value)    {$this->_shadow = $value;}
   function defaultShadow()    {return 1;}

   /**
    * Indicates if the component's icons drops a shadow
    */
   function readIconShadow()    {return $this->_iconshadow;}
   function writeIconShadow($value)    {$this->_iconshadow = $value;}
   function defaultIconShadow()    {return 1;}

   /**
    * Indicates if the component will display rounded corners
    */
   function readRoundedCorners()    {return $this->_roundedcorners;}
   function writeRoundedCorners($value)    {$this->_roundedcorners = $value;}
   function defaultRoundedCorners()    {return 1;}

   /**
    * Sets the button in inline position (works in fluid layouts like client page)
    */
   function readInline()    {return $this->_inline;}
   function writeInline($value)    {$this->_inline = $value;}
   function defaultInline()    {return 0;}


   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * Indicate the system icon to apply to the control.
    *
    * Different system icons can be assigned to this control.
    * To use a custom icon use the Icon property instead.
    *
    * @see readIcon()
    *
    * @return string
    */
   function readSystemIcon()    {return $this->_systemIcon;}
   function writeSystemIcon($val)    {$this->_systemIcon = $val;}
   function defaultSystemIcon()    {return "";}

   /**
    * Select an image as a custom icon.
    *
    * When a image is selected as icon the SystemIcon property is not taked in consideration and the
    * custom icon indicated in this property will be rendered instead.
    *
    * @return string
    */
   function readIcon()    {return $this->_icon;}
   function writeIcon($val)    {$this->_icon = $val;}
   function defaultIcon()    {return "";}

   /**
    * Indicate the position of the icon.
    *
    * Choose the ipNoText to display just the icon with no text.
    *
    * @return string
    */
   function readIconPos()    {return $this->_iconPos;}
   function writeIconPos($val)    {$this->_iconPos = $val;}
   function defaultIconPos()    {return "ipLeft";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'click');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 150;
      $this->_height = 43;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();
      MHeader($this);
   }

   function dumpAdditionalCSS()
   {
		switch($this->_enhancement)
		{
			case "enNone":
                parent::dumpAdditionalCSS();
				break;

			case "enStructure":

                if($this->_style == "")
                {
                    // apply box style to wrap div
                    echo  $this->readCSSDescriptor()."_outer > div{ \n";
                    echo $this->_readColorCSSString();
                    echo $this->parseCSSCursor();
                    echo $this->_borderradius->readCSSString();
                    echo $this->_gradient->readCSSString();
                    echo $this->_transform->readCSSString();
                    echo $this->_boxshadow->readCSSString();
                    echo "} \n";

                    // apply text style to inner text
                    echo  $this->readCSSDescriptor()."_outer .ui-btn-inner{ \n";
                    echo $this->Textshadow->readCSSString();
                    echo $this->Font->FontString."\n";
                    echo "} \n";
                }

			case "enFull":
                echo  $this->readCSSDescriptor()."_outer > div{ \n";
                echo $this->_readCSSSize();
                echo "} \n";
				break;

		}

      //If there is a custom icon we have to create the CSS class to handle it an put it on the header
      if($this->_icon != "")
      {
         ?>
  .ui-icon-<?php echo $this->_name?> {
    background-image:url(<?php echo str_replace(' ', '%20',$this->_icon)?>);
    background-size: 18px 18px;
  }
         <?php

      }

   }

   function dumpCSS()
   {
		if($this->_enhancement == "enNone")
		{
            parent::dumpCSS();
		}
   }

   function dumpContents()
   {
      JQMDesignStart($this);
      if($this->_imagesource != "" || $this->_enhancement == "enNone")
      {
         $this->_attributes['data-role'] = "none";
      }
      else
      {
        if($this->_inline)
            $this->_attributes['data-inline'] = "true";

        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

        if( ! $this->_roundedcorners)
            $this->_attributes['data-corners'] = "false";

        if( ! $this->_iconshadow)
            $this->_attributes['data-iconshadow'] = "false";

        if( ! $this->_shadow)
            $this->_attributes['data-shadow'] = "false";


         //Get the theme string

         if($this->_theme != "")
         {
            $RealTheme=RealTheme($this);
            $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
         }

         // get the icon if any
         $this->_attributes = array_merge($this->_attributes, iconVal($this));


      }
      // dump to control with all other parameters
      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * MButton is a mobile push button control.
 * Use it as a push button on a form. Its appearance can be modified.
 *
 * @see Button
 *
 * @example JQueryMobile/basic/mbutton.php
 */
class MButton extends CustomMButton
{
   function getRoundedCorners()    {return $this->readroundedcorners();}
   function setRoundedCorners($value)    {$this->writeroundedcorners($value);}

   function getIconShadow()    {return $this->readiconshadow();}
   function setIconShadow($value)    {$this->writeiconshadow($value);}

   function getShadow()    {return $this->readshadow();}
   function setShadow($value)    {$this->writeshadow($value);}

   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   function getIcon()    {return $this->readIcon();}
   function setIcon($val)    {$this->writeIcon($val);}

   function getSystemIcon()    {return $this->readSystemIcon();}
   function setSystemIcon($val)    {$this->writeSystemIcon($val);}

   function getInline()    {return $this->readinline();}
   function setInline($val)    {$this->writeinline($val);}

   function getIconPos()    {return $this->readIconPos();}
   function setIconPos($val)    {return $this->writeIconPos($val);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}

}

/**
 * Base class for MobileTheme component
 */
class CustomMobileTheme extends Component
{

   protected $_customcolorvariation = "";
   /**
    * Indicate the name of the color variation included in your custom CSS file
    */
   function readCustomColorVariation()    {return $this->_customcolorvariation;}
   function writeCustomColorVariation($value)    {$this->_customcolorvariation = $value;}
   function defaultCustomColorVariation()    {return "";}

   protected $_customTheme = "";
   /**
    * Select a CSS file containing a valid mobile theme.
    *
    * When selecting a custom CSS file, this will prevail over the system Theme.
    *
    * @return string
    */
   function readCustomTheme()    {return $this->_customTheme;}
   function writeCustomTheme($val)    {$this->_customTheme = $val;}
   function defaultCustomTheme()    {return "";}

   protected $_theme = "thBasic";
   /**
    * Select a System theme file
    *
    * @return string
    */
   function readTheme()    {return $this->_theme;}
   function writeTheme($val)    {$this->_theme = $val;}
   function defaultTheme()    {return "thBasic";}


   protected $_colorVariation = "cvBasic";
   /**
    * Select the color variation to use
    *
    * @return string
    */
   function readColorVariation()    {return $this->_colorVariation;}
   function writeColorVariation($val)    {$this->_colorVariation = $val;}
   function defaultColorVariation()    {return "cvBasic";}

   /**
     * Prevents to dump CSS
     *
     * @internal
     */
   function dumpCSS()
   {

   }

    /**
     * Dumps the CSS code for the mobile theme.
     *
     * @internal
     */
    function dumpAdditionalCSS()
    {
        //render and dump all custom css
        if($this->_theme == "thCustom")
        {
            if( ! defined($this->_customTheme) && $this->_customTheme != "")
            {
                if(file_exists($this->_customTheme))
                {
                $content = file_get_contents($this->_customTheme);

                // prevent future collision
                $class = $this->customClassName();

                // replace all image urls
                $content = str_replace("url(images", "url(" . MOBILE_RPCL_PATH . THEME_BASIC_IMAGES, $content);

                //remove comments
                $pos = 0;
                $search = "/*";
                $final = "";
                while(strlen($content) > 0 && $pos !== false)
                {
                    $pos = strpos($content, $search);

                    if($search == "/*")
                    {
                        $final .= substr($content, 0, $pos);
                        $content = substr($content, $pos);
                        $search = "*/";
                    }
                    else
                    {
                        $search = "/*";
                        $content = substr($content, $pos + 2);
                    }
                }

                $content = $final . $content;
                $search = "{";
                $pos = 0;

                while(strlen($content) > 0 && $pos !== false)
                {
                    $pos = strpos($content, $search);

                    if($search == "{")
                    {
                        $substr = substr($content, 0, $pos - 1);
                        $content = substr($content, $pos - 1);

                        $substr = trim($substr);
                        if($substr != "")
                        {
                            echo "\n.$class ";
                            echo str_replace(',', ", .$class ", $substr);
                        }

                        $search = "}";
                    }
                    else
                    {
                        $substr = substr($content, 0, $pos + 1);
                        $content = substr($content, $pos + 1);

                        echo $substr;
                        $search = "{";
                    }
                }

                // re-override some css rules, because the specificity added by the $class overrides some default structure styles
                echo "\n.$class div.ui-slider-bg,";
                echo "\n.$class .ui-dialog .ui-header,";
                echo "\n.$class span.ui-slider-label{\n";
                echo "border: none;\n";
                echo "}\n";

                define($this->_customTheme, 1);
                }
            }

        }

    }

    /**
     * For custom themes, it returns a class name based in the custom mobile theme filepath. If the mobile theme is
     * not custom, an empty string is returned instead.
     *
     * @internal
     */
    function customClassName()
    {
        $output = "";
        if($this->_theme == "thCustom")
            $output = strtr($this->_customTheme, "./", "-_");

        return $output;
    }

   /**
    * function that returns the theming string
    *
    * @param boolean $asString
    *
    * @return string/array deppending on the value of asString
    */
   function themeVal($asString = 0)
   {

      switch($this->_colorVariation)
      {
         case "cvHigh":
            $color = "a";
            break;
         case "cvMedium";
            $color = "b";
            break;
         case "cvBasic":
            $color = "c";
            break;
         case "cvMedium2";
            $color = "d";
            break;
         case "cvAccent";
            $color = "e";
            break;
         case "cvCustom";
            $color = $this->_customcolorvariation;
            break;
         default:
            $color = "c";
            break;
      }

      $upper_class = $this->customClassName();

      if($asString)
      {
         $output = "data-theme=\"$color\"";
         if($upper_class != "")
            $output .= " data-upperclass=\"$upper_class\"";
      }
      else
      {
         $output = array("data-theme"=>$color);
         if($upper_class != "")
            $output['data-upperclass'] = $upper_class;
      }

      return $output;
   }
}

/**
 * This class handles the color variatios of the Mobile controls
 *
 * In JQuery mobile a Theme is handeled by a CSS stylesheet. Every stylesheet comes with five color variations
 * In this class we can specify a system CSS file to use or include a custom CSS file from the user.
 * With a CSS file selected we'll be able to assign a color variation to the control.
 * Controls linked to this component will use the CSS stylesheet and the color variation indicated.
 *
 * Note: if to different controls in the same form use MobileTheme components with different CSS files,
 * only one of the CSS files will be loaded
 */
class MobileTheme extends CustomMobileTheme
{

   function getCustomTheme()    {return $this->readCustomTheme();}
   function setCustomTheme($val)    {$this->writeCustomTheme($val);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   function getColorVariation()    {return $this->readColorVariation();}
   function setColorVariation($val)    {$this->writeColorVariation($val);}

   function getCustomColorVariation()    {return $this->readCustomColorVariation();}
   function setCustomColorVariation($value)    {$this->writeCustomColorVariation($value);}
}

/**
 * Base class for MEdit control
 */
class CustomMEdit extends CustomEdit
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Visual enhancement degree of the control.
    *
    * The possible values are:
    * - enFull. All styles are defined by the CSS file or the Theme property.
    * - enStructure. Control's attributes like Font or Color are taken into consideration. The CSS code from the
    * theme is only applied to the structure.
    * - enNone. No style is taken from the CSS file or the Theme property.
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsoncleartextclick = "";
   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   /**
    *  Javascript event fired when the user clicks the cleartext button on a Search Box
    */
   function readjsOnClearTextClick()    {return $this->_jsoncleartextclick;}
   function writejsOnClearTextClick($value)    {$this->_jsoncleartextclick = $value;}
   function defaultjsOnClearTextClick()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";

      $output .= bindJSEvent("jQuery('#$this->_name').next('.ui-input-clear')", $this, 'cleartextclick');
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'click');
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'dblclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');

      return $output;
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 150;
      $this->_height = 43;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsoncleartextclick);
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function dumpCSS()
   {
      if (($this->_enhancement == "enNone"))
      {
        parent::dumpCSS();
      }
      else
      {
        $Mbox = array_key_exists("data-role",$this->_attributes);

        if($Mbox && !$this->_fixedwidth && $this->isFixedSize())
          echo "width:" . $this->_width . "px;\n";

        if(!$this->_fixedheight && $this->isFixedSize())
          echo "height:" . $this->_height . "px;\n";
      }
   }

   function dumpAdditionalCSS()
   {
		switch($this->_enhancement)
		{
			case "enNone":
                parent::dumpAdditionalCSS();
				break;

			case "enStructure":

                if($this->_style == "")
                {
                    // apply box style to wrap div
                    echo  $this->readCSSDescriptor()."_outer > div{ \n";
                    echo $this->_readColorCSSString();
                    echo $this->parseCSSCursor();
                    echo $this->_borderradius->readCSSString();
                    echo $this->_gradient->readCSSString();
                    echo $this->_transform->readCSSString();
                    echo $this->_boxshadow->readCSSString();
                    echo "} \n";

                    // apply text style to inner text
                    echo  $this->readCSSDescriptor()."_outer .ui-input-text { \n";
                    echo $this->Textshadow->readCSSString();
                    echo $this->Font->FontString."\n";
                    echo "} \n";
                }
		}
   }

   function dumpContents($enableDesignMode = true)
   {
      if($enableDesignMode)
        JQMDesignStart($this);


      if($this->_enhancement == "enNone")
      {
         $this->_attributes['data-role'] = "none";
      }
      else
      {
        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

        //Get the theme string
        if($this->_theme != "")
        {
          $RealTheme=RealTheme($this);
          $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
        }
      }
      parent::dumpContents();

      if($enableDesignMode)
        JQMDesignEnd($this);
   }
}

/**
 * Standard form text input, it inherits from Edit but have specific design enhancements provided by jquery mobile.
 * Also a new type of text input is added: Search, that renders in the browser a search box
 *
 * @see Edit
 *
 * @example JQueryMobile/basic/medit.php
 */
class MEdit extends CustomMEdit
{

   /**
     *  Published properties from CustomMEdit
     */

   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

    /**
     *  Published properties from CustomEdit
     */

    function getDataField()                    { return $this->readDataField();   }
    function setDataField($value)              { $this->writeDataField($value);   }

    function getDataSource(){ return $this->readDataSource();}
    function setDataSource($value) {$this->writeDataSource($value); }

    function getCharCase(){ return $this->readCharCase();}
    function setCharCase($value){ $this->writeCharCase($value);}

    function getColor() { return $this->readColor();}
    function setColor($value){ $this->writeColor($value);}

    function getEnabled(){return $this->readEnabled();}
    function setEnabled($value){$this->writeEnabled($value);}

    function getFont(){return $this->readFont();}
    function setFont($value){$this->writeFont($value);}

    function getMaxLength() {return $this->readMaxLength(); }
    function setMaxLength($value){$this->writeMaxLength($value); }

    function getParentColor() {return $this->readParentColor();}
    function setParentColor($value){ $this->writeParentColor($value);}

    function getParentFont(){return $this->readParentFont();}
    function setParentFont($value){$this->writeParentFont($value);}

    function getParentShowHint(){return $this->readParentShowHint();}
    function setParentShowHint($value){$this->writeParentShowHint($value);}

    function getReadOnly(){return $this->readReadOnly();}
    function setReadOnly($value){$this->writeReadOnly($value);}

    function getShowHint(){return $this->readShowHint();}
    function setShowHint($value){ $this->writeShowHint($value);}

    function getStyle()             { return $this->readstyle(); }
    function setStyle($value)       { $this->writestyle($value); }

    function getTabOrder(){return $this->readTabOrder();}
    function setTabOrder($value){$this->writeTabOrder($value);}

    function getTabStop(){return $this->readTabStop(); }
    function setTabStop($value) { $this->writeTabStop($value); }

    function getText() { return($this->readText()); }
    function setText($value) { $this->writeText($value); }

    /**
     * The type of input expected.
     *
     * The possible values are:
     * - ceEmail. An email address input.
     * - cePassword. A password input.
     * - ceSearch. Search terms input.
     * - ceTelephone. A telephone number input.
     * - ceText. A textual input. Default.
     * - ceURL. An URL input.
     */
    function getInputType()                 { return $this->readType(); }
    function setInputType($value)           { $this->writeType($value); }
	function defaultInputType()       { return $this->defaultType(); }

    // Documented in the parent.
    function getVisible()                   { return $this->readVisible(); }
    function setVisible($value)             { $this->writeVisible($value); }

    // Documented in the parent.
    function getRequired() { return $this->readRequired(); }
    function setRequired($value) { $this->writeRequired($value); }

    // Documented in the parent.
    function getDataList() { return $this->readDataList(); }
    function setDataList($value) { $this->writeDataList($value); }

    // Documented in the parent.
    function getPattern() { return $this->readPattern(); }
    function setPattern($value) { $this->writePattern($value); }

    // Documented in the parent.
    function getPlaceHolder() { return $this->readPlaceHolder(); }
    function setPlaceHolder($value) { $this->writePlaceHolder($value); }


   /**
     *  Published JS events from CustomMEdit
     */
   function getjsOnClearTextClick()    {return $this->readjsOncleartextclick();}
   function setjsOnClearTextClick($value)    {$this->writejsOncleartextclick($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}

    /**
     *  Published JS events from CustomEdit
     */

    // Documented in the parent.
    function getjsOnDragOver() { return $this->readjsondragover(); }
    function setjsOnDragOver($value) { $this->writejsondragover($value); }

    // Documented in the parent.
    function getjsOnDragStart() { return $this->readjsondragstart(); }
    function setjsOnDragStart($value) { $this->writejsondragstart($value); }

    // Documented in the parent.
    function getjsOnBlur                    () { return $this->readjsOnBlur(); }
    function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

    // Documented in the parent.
    function getjsOnChange                  () { return $this->readjsOnChange(); }
    function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

    // Documented in the parent.
    function getjsOnClick                   () { return $this->readjsOnClick(); }
    function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

    // Documented in the parent.
    function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
    function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

    // Documented in the parent.
    function getjsOnFocus                   () { return $this->readjsOnFocus(); }
    function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

    // Documented in the parent.
    function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
    function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

    // Documented in the parent.
    function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
    function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

    // Documented in the parent.
    function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
    function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

    // Documented in the parent.
    function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
    function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

    // Documented in the parent.
    function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
    function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

    // Documented in the parent.
    function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
    function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

    // Documented in the parent.
    function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
    function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

    // Documented in the parent.
    function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
    function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

    // Documented in the parent.
    function getjsOnSelect                  () { return $this->readjsOnSelect(); }
    function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }


   /**
     *  Published events from CustomEdit
     */

    // Documented in the parent.
    function getOnClick                     () { return $this->readOnClick(); }
    function setOnClick                     ($value) { $this->writeOnClick($value); }

    // Documented in the parent.
    function getOnDblClick                  () { return $this->readOnDblClick(); }
    function setOnDblClick                  ($value) { $this->writeOnDblClick($value); }

    // Documented in the parent.
    function getOnSubmit                    () { return $this->readOnSubmit(); }
    function setOnSubmit                    ($value) { $this->writeOnSubmit($value); }

}

/**
 * Base class for MTextArea control
 */
class CustomMTextArea extends Memo
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 150;
      $this->_height = 100;
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'click');
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'dblclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');

      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function dumpCSS()
   {
      if($this->_enhancement == "enNone" || $this->_enhancement == "enStructure")
      {
        parent::dumpCSS();
      }
      else
      {
        if(!$this->_fixedwidth && $this->isFixedSize())
          echo "width:" . $this->_width . "px;\n";

        if(!$this->_fixedheight && $this->isFixedSize())
          echo "height:" . $this->_height . "px;\n";
      }
   }

   function dumpContents()
   {
      JQMDesignStart($this, 0, 0);

      if($this->_enhancement == "enNone")
         $this->_attributes['data-role'] = "none";
      else
      {
        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

        //Get the theme string
        if($this->_theme != "")
        {
          $RealTheme=RealTheme($this);
          $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
        }
      }
      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Standard form textareqa, it inherits from Memo but have specific design enhancements provided by jquery mobile.
 *
 * @see Memo
 */
class MTextArea extends CustomMTextArea
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}
   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}

}

/**
 * Base class for MSlider.
 */
class CustomMSlider extends CustomEdit
{
   protected $_theme = "";
   protected $_maxValue = 10;
   protected $_minValue = 0;
   protected $_tracktheme = "";
   protected $_enhancement = "enFull";
   protected $_highlight = 0;
   protected $_step = 1;

   /**
    * Number that should be added or taken from the Text each time the slider is moved.
    *
    * For example, if set to 10, moving the slider to the right one position would increase the value of the Text
    * property by 10. To the left, it would decrease it by 10.
    *
    * @return int
    */
   function readStep()    {return $this->_step;}
   function writeStep($value)    {$this->_step = $value;}
   function defaultStep()    {return 1;}

   /**
    * Fill the highlight on the track up to the slider handle position
    *
    * @return int
    */
   function readHighlight()    {return $this->_highlight;}
   function writeHighlight($value)    {$this->_highlight = $value;}
   function defaultHighlight()    {return 0;}


   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)
   {
        // as a normal range input, must have a width
        if($value == "enNone")
        {
            $this->_fixedwidth = 0;
        }
        $this->_enhancement = $value;
   }
   function defaultEnhancement()    {return "enFull";}

   protected $_jsondragend = "";
   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   /**
    * Fired when the slider knob is released after draggin it
    */
   function readjsOnDragEnd()    {return $this->_jsondragend;}
   function writejsOnDragEnd($value)    {$this->_jsondragend = $value;}
   function defaultjsOnDragEnd()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * Select a MobileTheme component to indicate the track's color variation.
    *
    * MobileTheme components indicate the color variation of a control.
    * Different controls asigned to the same MobileTheme will use the same color variation when rendered.
    *
    * @see MobileTheme
    *
    * @return object returns the MobileTheme assigned
    */
   function readTrackTheme()    {return $this->_tracktheme;}
   function writeTrackTheme($val)    {$this->_tracktheme = $this->fixupProperty($val);}
   function defaultTrackTheme()    {return "";}

   /**
    * Assign the maximun value to the slider
    *
    * @return int
    */
   function readMaxValue()    {return $this->_maxValue;}
   function writeMaxValue($val)    {$this->_maxValue = $val;}
   function defaultMaxValue()    {return 10;}

   /**
    * Assign the minimun value to the slider
    *
    * @return int
    */
   function readMinValue()    {return $this->_minValue;}
   function writeMinValue($val)    {$this->_minValue = $val;}
   function defaultMinValue()    {return 0;}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_text = 5;
      $this->_type = "range";
      //in this control the width is fixed
      $this->_fixedwidth = 1;

      $this->_height = 43;
      $this->_width = 200;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
      $this->writeTrackTheme($this->_tracktheme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();

      $this->dumpJSEvent($this->_jsondragend);
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'click');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmousecancel');

      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'dragstart');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'dragend');

      return $output;
   }

    function dumpCSS()
    {
        switch($this->_enhancement)
        {
            case "enNone":
                parent::dumpCSS();
                break;

            case "enStructure":

                if($this->_style == "")
                {
                    // apply all properties except transform
                    echo $this->parseCSSCursor();
                    echo $this->_readColorCSSString();
                    echo $this->Font->FontString;
                    echo $this->_textshadow->readCSSString();
                    echo $this->_gradient->readCSSString();
                    echo $this->_borderradius->readCSSString();
                    echo $this->_boxshadow->readCSSString();
                }
            case "enFull":

                if(!$this->_fixedheight && $this->isFixedSize())
                    echo "height:" . $this->_height . "px;\n";
                break;
        }
    }

    function dumpAdditionalCSS()
    {
        if($this->_enhancement == "enStructure")
        {
            if($this->_style == "")
            {
                 // apply box transform to entire component
                echo $this->readCSSDescriptor()."_outer {\n";
                echo $this->_transform->readCSSString();
                echo "}\n";
            }

        }
    }

   function dumpContents()
   {
      JQMDesignStart($this, 0, 0);

      if($this->_enhancement == "enNone")
         $this->_attributes['data-role'] = "none";
      else
      {
        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

        if($this->_highlight)
          $this->_attributes['data-highlight'] = true;

        //Get the theme string
        if($this->_theme != "")
        {
          $RealTheme=RealTheme($this);
          $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
        }

        //Get the track's theme string
        if($this->_tracktheme != "")
        {
          $RealTheme=RealTheme($this,"TrackTheme");
          $arraytracktheme = $RealTheme->themeVal();
          $this->_attributes["data-track-theme"]= $arraytracktheme['data-theme'];
        }
      }
      // Let stablish max and min values
      $this->_attributes["max"] = $this->_maxValue;
      $this->_attributes["min"] = $this->_minValue;

	  // Let stablish step value
      if(($this->_step != "") && ($this->_step > 0))
	      $this->_attributes["step"] = $this->_step;

      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Contol that implements an input text that will take values from a slide Bar.
 * It uses a Maximun and minimun values to create a range.
 * A default value can be also specified
 *
 * @example JQueryMobile/basic/mslider.php
 */

class MSlider extends CustomMSlider
{

   /**
    * Number that should be added or taken from the Value each time the slider is moved.
    *
    * For example, if set to 10, moving the slider to the right one position would increase the Value property by 10.
    * To the left, it would decrease it by 10.
    *
    * @return int
    */
   function getIncrement()    {return $this->readstep();}
   function setIncrement($value)    {$this->writestep($value);}
   function defaultIncrement()    {return $this->defaultStep();}

   // Documented in the parent.
   function getHighlight()    {return $this->readhighlight();}
   function setHighlight($value)    {$this->writehighlight($value);}

   // Documented in the parent.
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   // Documented in the parent.
   function getTrackTheme()    {return $this->readtracktheme();}
   function setTrackTheme($value)    {$this->writetracktheme($value);}

   // Documented in the parent.
   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   /**
    * Default value of the control.
    *
    * @return int
    */
   function getValue()    {return ($this->readText());}
   function setValue($value)    {$this->writeText($value);}
   function defaultValue()    {return 5;}

   // Documented in the parent.
   function getMaxValue()    {return $this->readMaxValue();}
   function setMaxValue($val)    {$this->writeMaxValue($val);}

   // Documented in the parent.
   function getMinValue()    {return $this->readMinValue();}
   function setMinValue($val)    {$this->writeMinValue($val);}

   // Documented in the parent.
   function getOnClick()    {return $this->readOnClick();}
   function setOnClick($value)    {$this->writeOnClick($value);}

   // Documented in the parent.
   function getOnDblClick()    {return $this->readOnDblClick();}
   function setOnDblClick($value)    {$this->writeOnDblClick($value);}

   // Documented in the parent.
   function getOnSubmit()    {return $this->readOnSubmit();}
   function setOnSubmit($value)    {$this->writeOnSubmit($value);}

   /*
   * Publish the JS events for the Edit component
   */

   // Documented in the parent.
   function getjsOnDragStart()    {return $this->readjsOndragstart();}
   function setjsOnDragStart($value)    {$this->writejsOndragstart($value);}

   // Documented in the parent.
   function getjsOnDragEnd()    {return $this->readjsOndragend();}
   function setjsOnDragEnd($value)    {$this->writejsOndragend($value);}

   // Documented in the parent.
   function getjsOnBlur()    {return $this->readjsOnBlur();}
   function setjsOnBlur($value)    {$this->writejsOnBlur($value);}

   // Documented in the parent.
   function getjsOnChange()    {return $this->readjsOnChange();}
   function setjsOnChange($value)    {$this->writejsOnChange($value);}

   // Documented in the parent.
   function getjsOnClick()    {return $this->readjsOnClick();}
   function setjsOnClick($value)    {$this->writejsOnClick($value);}

   // Documented in the parent.
   function getjsOnDblClick()    {return $this->readjsOnDblClick();}
   function setjsOnDblClick($value)    {$this->writejsOnDblClick($value);}

   // Documented in the parent.
   function getjsOnFocus()    {return $this->readjsOnFocus();}
   function setjsOnFocus($value)    {$this->writejsOnFocus($value);}

   // Documented in the parent.
   function getjsOnMouseDown()    {return $this->readjsOnMouseDown();}
   function setjsOnMouseDown($value)    {$this->writejsOnMouseDown($value);}

   // Documented in the parent.
   function getjsOnMouseUp()    {return $this->readjsOnMouseUp();}
   function setjsOnMouseUp($value)    {$this->writejsOnMouseUp($value);}

   // Documented in the parent.
   function getjsOnMouseOver()    {return $this->readjsOnMouseOver();}
   function setjsOnMouseOver($value)    {$this->writejsOnMouseOver($value);}

   // Documented in the parent.
   function getjsOnMouseMove()    {return $this->readjsOnMouseMove();}
   function setjsOnMouseMove($value)    {$this->writejsOnMouseMove($value);}

   // Documented in the parent.
   function getjsOnMouseOut()    {return $this->readjsOnMouseOut();}
   function setjsOnMouseOut($value)    {$this->writejsOnMouseOut($value);}

   // Documented in the parent.
   function getjsOnKeyPress()    {return $this->readjsOnKeyPress();}
   function setjsOnKeyPress($value)    {$this->writejsOnKeyPress($value);}

   // Documented in the parent.
   function getjsOnKeyDown()    {return $this->readjsOnKeyDown();}
   function setjsOnKeyDown($value)    {$this->writejsOnKeyDown($value);}

   // Documented in the parent.
   function getjsOnKeyUp()    {return $this->readjsOnKeyUp();}
   function setjsOnKeyUp($value)    {$this->writejsOnKeyUp($value);}

   // Documented in the parent.
   function getjsOnSelect()    {return $this->readjsOnSelect();}
   function setjsOnSelect($value)    {$this->writejsOnSelect($value);}



   /*
   * Publish the properties for the component
   */

   function getDataField()
   {
      return $this->readDataField();
   }
   function setDataField($value)
   {
      $this->writeDataField($value);
   }

   function getDataSource()
   {
      return $this->readDataSource();
   }
   function setDataSource($value)
   {
      $this->writeDataSource($value);
   }

   function getColor()
   {
      return $this->readColor();
   }
   function setColor($value)
   {
      $this->writeColor($value);
   }

   function getEnabled()
   {
      return $this->readEnabled();
   }
   function setEnabled($value)
   {
      $this->writeEnabled($value);
   }

   function getFont()
   {
      return $this->readFont();
   }
   function setFont($value)
   {
      $this->writeFont($value);
   }

   function getParentColor()
   {
      return $this->readParentColor();
   }
   function setParentColor($value)
   {
      $this->writeParentColor($value);
   }

   function getParentFont()
   {
      return $this->readParentFont();
   }
   function setParentFont($value)
   {
      $this->writeParentFont($value);
   }

   function getParentShowHint()
   {
      return $this->readParentShowHint();
   }
   function setParentShowHint($value)
   {
      $this->writeParentShowHint($value);
   }

   function getShowHint()
   {
      return $this->readShowHint();
   }
   function setShowHint($value)
   {
      $this->writeShowHint($value);
   }

   function getStyle()    {return $this->readstyle();}
   function setStyle($value)    {$this->writestyle($value);}

   function getTabOrder()
   {
      return $this->readTabOrder();
   }
   function setTabOrder($value)
   {
      $this->writeTabOrder($value);
   }

   function getTabStop()
   {
      return $this->readTabStop();
   }
   function setTabStop($value)
   {
      $this->writeTabStop($value);
   }

   function getVisible()
   {
      return $this->readVisible();
   }
   function setVisible($value)
   {
      $this->writeVisible($value);
   }

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MLink control
 */
class CustomMLink extends Label
{
   protected $_theme = "";
   protected $_systemIcon = "";
   protected $_icon = "";
   protected $_iconPos = "ipLeft";
   protected $_noajax = 0;
   protected $_transition = "";
   protected $_transitionreverse = 0;
   protected $_isbackbutton = 0;
   protected $_opendialog = 0;
   protected $_enhancement = "enFull";
   protected $_inline = 0;

   protected $_fixedwidth=0;
   protected $_fixedheight=0;

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * Indicate the system icon to apply to the control.
    *
    * Different system icons can be assigned to this control.
    * To use a custom icon use the Icon property instead.
    *
    * @see readIcon()
    *
    * @return string
    */
   function readSystemIcon()    {return $this->_systemIcon;}
   function writeSystemIcon($val)    {$this->_systemIcon = $val;}
   function defaultSystemIcon()    {return "";}

   /**
    * Select an image as a custom icon.
    *
    * When a image is selected as icon the SystemIcon property is not taked in consideration and the
    * custom icon indicated in this property will be rendered instead.
    *
    * @return string
    */
   function readIcon()    {return $this->_icon;}
   function writeIcon($val)    {$this->_icon = $val;}
   function defaultIcon()    {return "";}

   /**
    * Indicate the position of the icon.
    * Choose the ipNoText to display just the icon with no text.
    *
    * @return string
    */
   function readIconPos()    {return $this->_iconPos;}
   function writeIconPos($val){$this->_iconPos = $val;}
   function defaultIconPos()    {return "ipLeft";}

   /**
    * Prevents the linked page to be loaded using Ajax
    *
    * If this property is set to true, no Transition will apply
    *
    * @see readTransition()
    *
    * @return boolean
    */
   function readNoAjax()    {return $this->_noajax;}
   function writeNoAjax($value)    {$this->_noajax = $value;}
   function defaultNoAjax()    {return 0;}

   /**
    * Indicate the transition that is going to be used
    *
    * Transitions are executed when pages are loaded with Ajax, no efect on non local pages or when NoAjax is set to true
    *
    * @see readNoAjax()
    *
    * @return string
    */
   function readTransition()    {return $this->_transition;}
   function writeTransition($value)    {$this->_transition = $value;}
   function defaultTransition()    {return "";}

   /**
    * Forces transition to use its reverse version
    *
    * @return boolean
    */
   function readTransitionReverse()    {return $this->_transitionreverse;}
   function writeTransitionReverse($value)    {$this->_transitionreverse = $value;}
   function defaultTransitionReverse()    {return 0;}

   /**
    * The linked page will load like a dialog
    *
    * @return boolean
    */
   function readOpenDialog()    {return $this->_opendialog;}
   function writeOpenDialog($value)    {$this->_opendialog = $value;}
   function defaultOpenDialog()    {return 0;}

   /**
    * Indicates if the Link will behave like a "Back" button, so when clicked will go to the previous page
    */
   function readIsBackButton()    {return $this->_isbackbutton;}
   function writeIsBackButton($value)    {$this->_isbackbutton = $value;}
   function defaultIsBackButton()    {return 0;}

   /**
    * Sets the button in inline position (works in fluid layouts like client page)
    */
   function readInline()    {return $this->_inline;}
   function writeInline($value)    {$this->_inline = $value;}
   function defaultInline()    {return 0;}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }


   function openingLink($hint, $target, $events, $class)
   {
      if($this->_link != "")
      {
         if( ! $this->_divwrap)
            $id = " id=\"$this->_name\" ";
         else
            $id = "";
         $attributes = $this->strAttributes();
         $link = str_replace(' ', '%20',$this->_link);

		 echo "<A HREF=\"$link\"$id
         $class $hint $target $events $attributes >";
      }
   }

   function openingWrap($hint, $events, $class)
   {
      if($this->_divwrap)
      {
         echo "<div id=\"$this->_name\" $hint $class ";

         if($this->_link == "")
            echo "$events";

         echo ">";
      }
   }

   function closingWrap()
   {
      if(($this->_divwrap))
      {
         echo "</div>";
      }
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_link = "#";
      $this->_divwrap = 0;

      $this->_width = 150;
      $this->_height = 43;
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'click');
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'dblclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();
      MHeader($this);
   }

   function dumpCSS()
   {
        if($this->_enhancement == "enStructure")
        {

            parent::dumpCSS();
        }
        else
        {
            if(!$this->_fixedwidth && $this->isFixedSize())
            echo "width:" . $this->_width . "px;\n";

            if(!$this->_fixedheight && $this->isFixedSize())
              echo "height:" . $this->_height . "px;\n";
        }

        /*
        if($this->_iconPos == "ipNoText" && ($this->_systemIcon != "" || $this->_icon != ""))
        {
            echo "width: 24px;\n";
            echo "height: 24px;\n";
            echo "box-sizing: content-box;\n";
        }
        */

   }

   function dumpAdditionalCSS()
   {
        if($this->_enhancement == "enStructure")
        {
            if($this->_style == "")
            {
                echo $this->readCSSDescriptor()." .ui-btn-inner{\n";
                echo $this->Font->FontString;
                echo "}\n";
            }
        }


      //If there is a custom icon we have to create the CSS class to handle it an put it on the header
      if($this->_icon != "")
      {
      ?>
        .ui-icon-<?php echo $this->_name?> {
          background-image:url(<?php echo str_replace(' ', '%20',$this->_icon)?>);
          background-size: 18px 18px;
        }
      <?php
      }
   }

   function dumpContents()
   {
      JQMDesignStart($this);

      if($this->_link == "")
         $this->_link = "#";

      //Get the theme string
      if($this->_theme != "")
      {
        $RealTheme=RealTheme($this);
        $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
      }
      // get the icon if any
      $this->_attributes = array_merge($this->_attributes, iconVal($this));


      if($this->_inline)
         $this->_attributes['data-inline'] = "true";

      if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

      if($this->_noajax)
         $this->_attributes["rel"] = "external";

      if($this->_isbackbutton)
         $this->_attributes["data-rel"] = "back";

      if($this->_transitionreverse)
        $this->_attributes["data-direction"] = "reverse";

      if($this->_transition != "")
         $this->_attributes["data-transition"] = transitionValue($this->_transition);

      if($this->_opendialog)
         $this->_attributes["data-rel"] = "dialog";

      $this->_attributes["data-role"] = "button";

      // dump to control with all other parameters
      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Links are rendered as buttons but they don't submit forms.
 *
 * All local pages referenced in links are loaded via Ajax.
 * A transition can be stablished when clicking the Link and will be shown when the page is loaded via Ajax.
 * Ajax can also be disabled.
 *
 * @see Label
 *
 * @example JQueryMobile/basic/mlink.php
 */

class MLink extends CustomMLink
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   function getIcon()    {return $this->readIcon();}
   function setIcon($val)    {$this->writeIcon($val);}

   function getSystemIcon()    {return $this->readSystemIcon();}
   function setSystemIcon($val)    {$this->writeSystemIcon($val);}

   function getIconPos()    {return $this->readIconPos();}
   function setIconPos($val)    {return $this->writeIconPos($val);}

   function getNoAjax()    {return $this->readnoajax();}
   function setNoAjax($value)    {$this->writenoajax($value);}

   function getTransition()    {return $this->readtransition();}
   function setTransition($value)    {$this->writetransition($value);}

   function getTransitionReverse()    {return $this->readtransitionreverse();}
   function setTransitionReverse($value)    {$this->writetransitionreverse($value);}

   function getOpenDialog()    {return $this->readopendialog();}
   function setOpenDialog($value)    {$this->writeopendialog($value);}

   function getIsBackButton()    {return $this->readisbackbutton();}
   function setIsBackButton($value)    {$this->writeisbackbutton($value);}

   function getInline()    {return $this->readinline();}
   function setInline($val)    {$this->writeinline($val);}

   function defaultLink()    {return "#";}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MCollapsible control
 */
class CustomMCollapsible extends CustomMPanel
{
   protected $_enhancement = "enFull";
   protected $_iconpos="ipLeft";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   /**
    * Indicate the position of the icon.
    *
    * Choose the ipNoText to display just the icon with no text.
    *
    * @return string
    */
   function readIconPos() { return $this->_iconpos; }
   function writeIconPos($value) { $this->_iconpos=$value; }
   function defaultIconPos() { return "ipLeft"; }



   protected $_iscollapsed = 0;
   protected $_jsoncollapse = "";
   protected $_jsonexpand = "";

   /**
    * Event fired when one panel is expanded.
    *
    * Once one panel is expanded, all the others fire the OnCollapse() event
    *
    * @see readjsOnCollapse()
    */
   function readjsOnExpand()    {return $this->_jsonexpand;}
   function writejsOnExpand($value)    {$this->_jsonexpand = $value;}
   function defaultjsOnExpand()    {return "";}

   /**
    * Event fired when one panel is collapsed
    */
   function readjsOnCollapse()    {return $this->_jsoncollapse;}
   function writejsOnCollapse($value)    {$this->_jsoncollapse = $value;}
   function defaultjsOnCollapse()    {return "";}

   /**
    * Change the text on the collapsible control header
    *
    * @return string
    */
   function readCaption()    {return $this->_caption;}
   function writeCaption($value)    {$this->_caption = $value;}
   function defaultCaption()    {return "header";}

   /**
    * Indicate if the element's content is displayed collapsed
    *
    * @return boolean
    */
   function readIsCollapsed()    {return $this->_iscollapsed;}
   function writeIsCollapsed($value)    {$this->_iscollapsed = $value;}
   function defaultIsCollapsed()    {return 0;}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      $this->_role = "rCollapsible";
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsoncollapse);
      $this->dumpJSEvent($this->_jsonexpand);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'expand');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'collapse');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'click');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'dblclick');
      return $output;
   }

    function dumpCSS()
    {
        if($this->_enhancement == "enStructure")
        {
            // apply transformation to entire component
            echo $this->_transform->readCSSString();
            echo $this->_boxshadow->readCSSString();
        }


       if($this->isFixedSize())
        {
            if($this->_width != "")
                echo "width: {$this->_width}px;\n";
        }
    }



    function dumpAdditionalCSS()
    {
        if($this->_enhancement == "enStructure")
        {
            if($this->_style == "")
            {
                // header and content css descriptors
                echo $this->readCSSDescriptor()." > h1 a{\n";
                echo $this->_readColorCSSString();
                echo $this->parseCSSCursor();
                echo $this->_borderradius->readCSSString();
                echo $this->_gradient->readCSSString();
                echo "}\n";

                // apply text css
                echo $this->readCSSDescriptor()." > h1 .ui-btn-inner{\n";
                echo $this->Font->FontString."\n";
                echo $this->_textshadow->readCSSString();
                echo "}\n";
            }
        }

       // set min-height to the contents to work well in adaptable layouts
       // padding + header height = 60
       $contentMinHeight = $this->_height - 60;
       echo "{$this->readCSSDescriptor()} > .ui-collapsible-content{\n";
       echo " min-height: {$contentMinHeight}px\n";
       echo "}\n";

       // dumps Layout CSS
       $this->dumpLayoutCSS();

       // dumps Layout content CSS
       if(($this->ControlState & csDesigning) != csDesigning)
       {
          $this->Layout->dumpLayoutContents(array(), true);
       }

    }

   function dumpContents()
   {

      $attributes["id"] = $this->_name;

      if($this->isFixedSize())
        $attributes['data-fixedsize'] = "true";

      if($this->_iconpos)
        $attributes["data-iconpos"] = iconPos($this->_iconpos);

      if( ! $this->_iscollapsed)
         $attributes["data-collapsed"] = "false";

      if($this->_showhint)
         $hint = " title=\"$this->_hint\"";
      else
         $hint = "";

      $headerContent = "\t<h1$hint>$this->_caption</h1>\n";

      $this->DumpFormatedContent($attributes, $headerContent, "", -10);
   }
}

/**
 * This class renders a container with a header. By clicking in the header the content is shown/hidden.
 *
 * @example JQueryMobile/basic/mcollapsible.php
 */

class MCollapsible extends CustomMCollapsible
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getIconPos()    {return $this->readIconPos();}
   function setIconPos($val)    {return $this->writeIconPos($val);}

   function getTheme()    {return $this->readtheme();}
   function setTheme($value)    {$this->writetheme($value);}

   function getCaption()    {return $this->readcaption();}
   function setCaption($value)    {$this->writecaption($value);}

   function getIsCollapsed()    {return $this->readiscollapsed();}
   function setIsCollapsed($value)    {$this->writeiscollapsed($value);}

   function getShowHint()    {return $this->readshowhint();}
   function setShowHint($value)    {$this->writeshowhint($value);}

   function getjsOnExpand()    {return $this->readjsOnexpand();}
   function setjsOnExpand($value)    {$this->writejsOnexpand($value);}

   function getjsOnCollapse()    {return $this->readjsOncollapse();}
   function setjsOnCollapse($value)    {$this->writejsOncollapse($value);}

   function getjsOnClick()    {return $this->readjsOnclick();}
   function setjsOnClick($value)    {$this->writejsOnclick($value);}

   function getjsOnDblClick()    {return $this->readjsOndblclick();}
   function setjsOnDblClick($value)    {$this->writejsOndblclick($value);}

   function getjsOnMouseUp()    {return $this->readjsOnmouseup();}
   function setjsOnMouseUp($value)    {$this->writejsOnmouseup($value);}

   function getjsOnMouseDown()    {return $this->readjsOnmousedown();}
   function setjsOnMouseDown($value)    {$this->writejsOnmousedown($value);}

   function getFont()    {return $this->readfont();}
   function setFont($value)    {$this->writefont($value);}

   function getParentFont()    {return $this->readparentfont();}
   function setParentFont($value)    {$this->writeparentfont($value);}

   function getColor()    {return $this->readcolor();}
   function setColor($value)    {$this->writecolor($value);}

   function getParentColor()    {return $this->readparentcolor();}
   function setParentColor($value)    {$this->writeparentcolor($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MToolBar control
 */
class CustomMToolBar extends MControl
{
   protected $_selectedelement =  -1;
   protected $_items = array();
   protected $_iconpos = "ipTop";

   /**
    * Array of MLink elements
    *
    * @return array
    */
   function readItems()    {return $this->_items;}
   function writeItems($value)    {$this->_items = $value;}
   function defaultItems()    {return array();}

   /**
    * Select the element that is going to be marked as selected in the toolbar
    *
    * @return object
    */
   function readSelectedElement()    {return $this->_selectedelement;}
   function writeSelectedElement($value)    {$this->_selectedelement = $value;}
   function defaultSelectedElement()    {return -1;}

   /**
    * Indicate the position of the icon.
    *
    *
    * @return string
    */
   function readIconPos()    {return $this->_iconpos;}
   function writeIconPos($val)    {$this->_iconpos = $val;}
   function defaultIconPos()    {return "ipTop";}


   function dumpAdditionalCSS()
   {

      if( ! is_array($this->_items))
         $this->_items = array();
      //If there is a custom icon we have to create the CSS class to handle it an put it on the header
      foreach($this->_items as $i=>$v)
      {
         if(isset($v['Icon']) && $v['Icon'] != "")
         {
            ?>
   .ui-icon-<?php echo $this->_name?>_<?php echo $i?> {
    background-image:url(<?php echo $v['Icon']?>);
    background-size: 18px 18px;
  }
            <?php
         }
      }


        if($this->_enhancement == "enStructure")
        {
            // text properties
            echo $this->readCSSDescriptor()." .ui-btn-text{\n";
            echo $this->Font->FontString;
            echo $this->TextShadow->readCSSString();
            echo "}";

            // box properties
            echo $this->readCSSDescriptor()." .ui-btn{\n";
            echo $this->BorderRadius->readCSSString();
            echo $this->Gradient->readCSSString();
            echo $this->_readColorCSSString();
            echo "}";
        }
   }


   function dumpCSS()
   {
        if($this->_enhancement == "enStructure")
        {
            echo $this->Transform->readCSSString();
            echo $this->BoxShadow->readCSSString();

            // delegate other rules in dumpAdditionalCSS
        }

        echo $this->_readCSSSize();
   }

   function dumpContents()
   {
      if( ! is_array($this->_items))
         $this->_items = array();

      if(count($this->_items) > 0)
      {
         //Get the theme string
         $theme = "";
         if($this->_theme != "")
         {
            $RealTheme=RealTheme($this);
            $theme = $RealTheme->themeVal(1);
         }

         $fixedsize = ($this->isFixedSize()) ? "data-fixedsize=\"true\"" : "";

         JQMDesignStart($this, 0, 0);


         $iconpos = iconPos($this->_iconpos);
         echo "<div data-role=\"navbar\" $fixedsize data-iconpos=\"$iconpos\" id=\"$this->_name\" >\n";
         echo "\t<ul>\n";



         foreach($this->_items as $i=>$v)
         {
            if( ! isset($v['Link']) || $v['Link'] == "")
               $v['Link'] = "#";

            if(isset($v['NoAjax']) && $v['NoAjax'] == "true")
               $rel = " rel=\"external\" ";
            else
               $rel = "";

            if(isset($v['Transition']))
               $transitionValue = transitionValue($v['Transition']);
            else
               $transitionValue = '';

            if($transitionValue == '')
            {
               $transition = "";
            }
            else
            {
               $transition = " data-transition=\"" . transitionValue($v['Transition']) . "\" ";
            }

            if(isset($v['OpenDialog']) && $v['OpenDialog'] == "true")
               $dialog = " data-rel=\"dialog\" ";
            else
               $dialog = "";

            if($this->_selectedelement == $i)
               $class = " class=\"ui-btn-active\" ";
            else
               $class = "";

            if(isset($v['Icon']) && $v['Icon'] != "")
               $icon = " data-icon=\"{$this->_name}_$i\" ";
            else
               if(isset($v['SystemIcon']) && $v['SystemIcon'] != "")
                  $icon = " data-icon=\"" . systemIcon($v['SystemIcon']) . "\" ";
               else
                  $icon = "";

            echo "\t\t<li><a href=\"{$v['Link']}\" id=\"{$this->_name}_$i\"$rel$transition$dialog$class$icon$theme>{$v['Caption']}</a></li>\n";
         }

         echo "\t</ul>\n";
         echo "</div>\n";

         JQMDesignEnd($this);
      }
   }

}

/**
 * This class is a container of MLink components
 * All components included in the container will render as toolbar buttons
 *
 * @see MLink
 *
 * @example JQueryMobile/basic/mtoolbar.php
 */
class MToolBar extends CustomMToolBar
{

   function getSelectedElement()    {return $this->readselectedelement();}
   function setSelectedElement($value)    {$this->writeselectedelement($value);}

   function getItems()    {return $this->readitems();}
   function setItems($value)    {$this->writeitems($value);}

   function getIconPos()    {return $this->readiconpos();}
   function setIconPos($value)    {$this->writeiconpos($value);}

}

/**
 * Base class for MToggle control
 */
class CustomMToggle extends CustomListBox
{
   protected $_theme = "";
   protected $_tracktheme = "";
   protected $_textchecked = "on";
   protected $_valuechecked = "1";
   protected $_textunchecked = "off";
   protected $_valueunchecked = "0";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   /**
    * Indicates the value for the unchecked option
    *
    * @return string
    */
   function readValueUnchecked()    {return $this->_valueunchecked;}
   function writeValueUnchecked($value)    {$this->_valueunchecked = $value;}
   function defaultValueUnchecked()    {return "0";}

   /**
    * Indicates the text for the unchecked option
    *
    * @return string
    */
   function readTextUnchecked()    {return $this->_textunchecked;}
   function writeTextUnchecked($value)    {$this->_textunchecked = $value;}
   function defaultTextUnchecked()    {return "off";}

   /**
    * Indicates the value for the checked option
    *
    * @return string
    */
   function readValueChecked()    {return $this->_valuechecked;}
   function writeValueChecked($value)    {$this->_valuechecked = $value;}
   function defaultValueChecked()    {return "1";}

   /**
    * Indicates the text for the checked option
    *
    * @return string
    */
   function readTextChecked()    {return $this->_textchecked;}
   function writeTextChecked($value)    {$this->_textchecked = $value;}
   function defaultTextChecked()    {return "on";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * Select a MobileTheme component to indicate the track's color variation.
    *
    * MobileTheme components indicate the color variation of a control.
    * Different controls asigned to the same MobileTheme will use the same color variation when rendered.
    *
    * @see MobileTheme
    *
    * @return object returns the MobileTheme assigned
    */
   function readTrackTheme()    {return $this->_tracktheme;}
   function writeTrackTheme($val)    {$this->_tracktheme = $this->fixupProperty($val);}
   function defaultTrackTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_font->Align = "taCenter";
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'click');
      $output .= bindEvents("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'dblclick');
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'change');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name').siblings('.ui-slider')", $this, 'vmousecancel');

      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
      $this->writeTrackTheme($this->_tracktheme);

      $this->_items[$this->_valuechecked] = $this->_textchecked;
      $this->_items[$this->_valueunchecked] = $this->_textunchecked;
      $this->_itemindex = $this->_valuechecked;
   }

   function dumpHeaderCode()
   {

      parent::dumpHeaderCode();

      MHeader($this);

   }

   function dumpCSS()
   {
      if($this->_enhancement == "enNone")
      {
        parent::dumpCSS();
      }
   }

    function dumpAdditionalCSS()
    {
        if($this->_enhancement == "enStructure")
        {
            if ($this->_style=="")
            {
                // apply text css and box css to slider background
                echo $this->CSSDescriptor." + div .ui-slider-label{\n";
                echo $this->Font->FontString;
                echo $this->parseCSSCursor();
                echo $this->_borderradius->readCSSString();
                echo $this->_boxshadow->readCSSString();
                echo $this->_textshadow->readCSSString();
                echo "}\n";

                // apply background-color and gradient to active slider background
                echo $this->CSSDescriptor." + div .ui-btn-active{\n";
                echo $this->_readColorCSSString();
                echo $this->_gradient->readCSSString();
                echo "}\n";

                // apply transform to entire component
                echo $this->CSSDescriptor." + div{\n";
                echo $this->_transform->readCSSString();
                echo "}\n";
            }
        }


        if($this->isFixedSize())
        {
            echo $this->CSSDescriptor." + .ui-slider{";
            echo "width:" . $this->_width . "px;\n";
            echo "}\n";
        }
   }

    // Documented in the parent.
    function dumpContents()
    {
        if($this->_enhancement == "enNone")
        {
            $this->_attributes['data-role'] = "none";
            JQMDesignStart($this);
        }
        else
        {
            JQMDesignStart($this, -1, -1);

            if($this->isFixedSize())
                $this->_attributes['data-fixedsize'] = "true";

            //Get the theme string
            if($this->_theme != "")
            {
                $RealTheme=RealTheme($this);
                $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
            }
            //Get the track's theme string
            if($this->_tracktheme != "")
            {
                $RealTheme=RealTheme($this,"TrackTheme");
                $arraytracktheme = $RealTheme->themeVal();
                $this->_attributes["data-track-theme"]= $arraytracktheme['data-theme'];
            }

            $this->_attributes["data-role"] = "slider";
            $this->_attributes["data-class"] = $this->_name;
        }
        // dump to control with all other parameters
        parent::dumpContents();

        JQMDesignEnd($this);
    }

}

/**
 * This is a control with two values that render like an on/off switch.
 *
 * Internally is like a listBox but forced to use only two items
 *
 * @example JQueryMobile/basic/mtoggle.php
 */
class MToggle extends CustomMToggle
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTrackTheme()    {return $this->readtracktheme();}
   function setTrackTheme($value)    {$this->writetracktheme($value);}

   function getTheme()    {return $this->readtheme();}
   function setTheme($value)    {$this->writetheme($value);}

   function getValueChecked()    {return $this->readvaluechecked();}
   function setValueChecked($value)    {$this->writevaluechecked($value);}

   function getTextChecked()    {return $this->readtextchecked();}
   function setTextChecked($value)    {$this->writetextchecked($value);}

   function getValueUnchecked()    {return $this->readvalueunchecked();}
   function setValueUnchecked($value)    {$this->writevalueunchecked($value);}

   function getTextUnchecked()    {return $this->readtextunchecked();}
   function setTextUnchecked($value)    {$this->writetextunchecked($value);}

   /*
   * Publish the events
   */
   function getOnClick()    {return $this->readOnClick();}
   function setOnClick($value)    {$this->writeOnClick($value);}

   function getOnDblClick()    {return $this->readOnDblClick();}
   function setOnDblClick($value)    {$this->writeOnDblClick($value);}

   function getOnSubmit()    {return $this->readOnSubmit();}
   function setOnSubmit($value)    {$this->writeOnSubmit($value);}

   function getOnChange()    {return $this->readonchange();}
   function setOnChange($value)    {$this->writeonchange($value);}

   /*
   * Publish the JS events
   */
   function getjsOnBlur()    {return $this->readjsOnBlur();}
   function setjsOnBlur($value)    {$this->writejsOnBlur($value);}

   function getjsOnChange()    {return $this->readjsOnChange();}
   function setjsOnChange($value)    {$this->writejsOnChange($value);}

   function getjsOnClick()    {return $this->readjsOnClick();}
   function setjsOnClick($value)    {$this->writejsOnClick($value);}

   function getjsOnDblClick()    {return $this->readjsOnDblClick();}
   function setjsOnDblClick($value)    {$this->writejsOnDblClick($value);}

   function getjsOnFocus()    {return $this->readjsOnFocus();}
   function setjsOnFocus($value)    {$this->writejsOnFocus($value);}

   function getjsOnMouseDown()    {return $this->readjsOnMouseDown();}
   function setjsOnMouseDown($value)    {$this->writejsOnMouseDown($value);}

   function getjsOnMouseUp()    {return $this->readjsOnMouseUp();}
   function setjsOnMouseUp($value)    {$this->writejsOnMouseUp($value);}

   function getjsOnMouseOver()    {return $this->readjsOnMouseOver();}
   function setjsOnMouseOver($value)    {$this->writejsOnMouseOver($value);}

   function getjsOnMouseMove()    {return $this->readjsOnMouseMove();}
   function setjsOnMouseMove($value)    {$this->writejsOnMouseMove($value);}

   function getjsOnMouseOut()    {return $this->readjsOnMouseOut();}
   function setjsOnMouseOut($value)    {$this->writejsOnMouseOut($value);}

   function getjsOnKeyPress()    {return $this->readjsOnKeyPress();}
   function setjsOnKeyPress($value)    {$this->writejsOnKeyPress($value);}

   function getjsOnKeyDown()    {return $this->readjsOnKeyDown();}
   function setjsOnKeyDown($value)    {$this->writejsOnKeyDown($value);}

   function getjsOnKeyUp()    {return $this->readjsOnKeyUp();}
   function setjsOnKeyUp($value)    {$this->writejsOnKeyUp($value);}

   /*
   * Publish the properties for the Label component
   */

   function getDataField()
   {
      return $this->readDataField();
   }
   function setDataField($value)
   {
      $this->writeDataField($value);
   }

   function getDataSource()
   {
      return $this->readDataSource();
   }
   function setDataSource($value)
   {
      $this->writeDataSource($value);
   }

   function getColor()
   {
      return $this->readColor();
   }
   function setColor($value)
   {
      $this->writeColor($value);
   }

   function getEnabled()
   {
      return $this->readEnabled();
   }
   function setEnabled($value)
   {
      $this->writeEnabled($value);
   }

   function getFont()
   {
      return $this->readFont();
   }
   function setFont($value)
   {
      $this->writeFont($value);
   }

   function getParentColor()
   {
      return $this->readParentColor();
   }
   function setParentColor($value)
   {
      $this->writeParentColor($value);
   }

   function getParentFont()
   {
      return $this->readParentFont();
   }
   function setParentFont($value)
   {
      $this->writeParentFont($value);
   }

   function getParentShowHint()
   {
      return $this->readParentShowHint();
   }
   function setParentShowHint($value)
   {
      $this->writeParentShowHint($value);
   }

   function getShowHint()
   {
      return $this->readShowHint();
   }
   function setShowHint($value)
   {
      $this->writeShowHint($value);
   }

   function getStyle()    {return $this->readstyle();}
   function setStyle($value)    {$this->writestyle($value);}

   function getTabOrder()
   {
      return $this->readTabOrder();
   }
   function setTabOrder($value)
   {
      $this->writeTabOrder($value);
   }

   function getTabStop()
   {
      return $this->readTabStop();
   }
   function setTabStop($value)
   {
      $this->writeTabStop($value);
   }

   function getVisible()
   {
      return $this->readVisible();
   }
   function setVisible($value)
   {
      $this->writeVisible($value);
   }

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MRadioButton control
 */
class CustomMRadioButton extends RadioButton
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   /*
   * Write the Javascript section to the header
   */
   function dumpJavascript()
   {
      if( ! defined('RadioButtonClick'))
      {
         define('RadioButtonClick', 1);
      }
      parent::dumpJavascript();
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 200;
      $this->_height = 43;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#{$this->_name}')", $this, 'click');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#{$this->_name}_caption')", $this, 'vmousecancel');

      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

	function dumpAdditionalCSS()
	{
        switch($this->_enhancement)
        {
            case "enStructure":

                parent::dumpAdditionalCSS();

            case "enFull":

                echo  $this->readCSSDescriptor()."_caption{ \n";
                echo  $this->_readCSSSize();
                echo "} \n";

                break;

            // only css
            case "enNone":

                parent::dumpAdditionalCSS();
                break;
        }
	}

    function dumpCSS()
    {
      if($this->_enhancement == "enNone")
      {
         parent::dumpCSS();
      }
    }

   function dumpContents()
   {
    JQMDesignStart($this);
    if($this->_enhancement=="enNone")
    {
      $this->_attributes['data-role']="none";

    }
    else
    {

      if($this->isFixedSize())
           $this->_attributes['data-fixedsize'] = "true";

      //Get the theme string
      if($this->_theme != "")
      {
        $RealTheme=RealTheme($this);
        $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
      }
      $this->_attributes['data-role'] = "radiobutton";
    }
      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Standard form radio button, it inherits from RadioButton but have specific design enhancements provided by jquery mobile.
 *
 * @see RadioButton
 *
 * @example JQueryMobile/basic/mradiobutton.php
 */
class MRadioButton extends CustomMRadioButton
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MCheckBox control
 */
class CustomMCheckBox extends CheckBox
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten to use a jqueryfied version of tha Wrapper function
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function dumpJavascript()
   {
      if( ! defined('CheckBoxClick'))
      {
         define('CheckBoxClick', 1);
      }
      parent::dumpJavascript();
   }
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 200;
      $this->_height = 43;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#{$this->_name}')", $this, 'click');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'tap');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('label[for=\"{$this->_name}\"]')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function dumpCSS()
   {
    // with enNone, the style must be applied normally
    if( $this->_enhancement == "enNone")
    {
      parent::dumpCSS();
    }
   }


	function dumpAdditionalCSS()
	{
       // with enStructure, the style must be applied in label, example MCheckbox1_outer label[for=MCheckbox1]
       // Tip: JQM uses background-image gradients, this causes the background color "doesn't change" visually on this control when enStructure is setted
        if($this->_enhancement == "enFull")
        {
            echo $this->readCSSDescriptor()." + label[for={$this->Name}]{ \n";
            echo $this->_readCSSSize();
            //echo "width: {$this->_width}px;\n";
            //echo "height: {$this->_height}px;\n";
            echo "} \n";
        }
        if($this->_enhancement == "enStructure")
        {
            // apply style to adyacent label
            echo $this->readCSSDescriptor()." + label[for={$this->Name}]{ \n";
            parent::dumpCSS();
            echo "} \n";

            if($this->_style == "")
            {
                // fix to override JQM font settings
                echo $this->readCSSDescriptor()." + label[for={$this->Name}] .ui-btn-inner{ \n";
                echo $this->Font->FontString;
                echo "} \n";
            }
        }
        else
        if( $this->_enhancement == "enNone")
        {
            parent::dumpAdditionalCSS();;
        }
	}

   function dumpContents()
   {
      JQMDesignStart($this);
      if($this->_enhancement == "enNone")
      {
         $this->_attributes['data-role'] = "none";

      }
      else
      {
        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

         //Get the theme string
         if($this->_theme != "")
        {
          $RealTheme=RealTheme($this);
          $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
        }

         $this->_attributes['data-role'] = "checkbox";
      }
      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Standard form checkbox, it inherits from CheckBox but have specific design enhancements provided by jquery mobile.
 *
 * @see CheckBox
 *
 * @example JQueryMobile/basic/mcheckbox.php
 */
class MCheckBox extends CustomMCheckBox
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MComboBox control
 */
class CustomMComboBox extends ListBox
{
   protected $_theme = "";
   protected $_isnative = 0;
   protected $_systemIcon = "";
   protected $_icon = "";
   protected $_iconPos = "ipRight";
   protected $_roundedcorners = 1;
   protected $_iconshadow = 1;
   protected $_shadow = 1;
   protected $_enhancement = "enFull";
   protected $_inline = 0;

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   /**
    * Indicates if the component drop a shadow
    */
   function readShadow()    {return $this->_shadow;}
   function writeShadow($value)    {$this->_shadow = $value;}
   function defaultShadow()    {return 1;}

   /**
    * Indicates if the component's icons drops a shadow
    */
   function readIconShadow()    {return $this->_iconshadow;}
   function writeIconShadow($value)    {$this->_iconshadow = $value;}
   function defaultIconShadow()    {return 1;}

   /**
    * Indicates if the component will display rounded corners
    */
   function readRoundedCorners()    {return $this->_roundedcorners;}
   function writeRoundedCorners($value)    {$this->_roundedcorners = $value;}
   function defaultRoundedCorners()    {return 1;}


    protected $_size=1;

    function readSize() { return $this->_size; }
    function writeSize($value) { $this->_size=$value; }
    function defaultSize() { return 1; }



   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * Indicate the system icon to apply to the control.
    *
    * Different system icons can be assigned to this control.
    * To use a custom icon use the Icon property instead.
    *
    * @see readIcon()
    *
    * @return string
    */
   function readSystemIcon()    {return $this->_systemIcon;}
   function writeSystemIcon($val)    {$this->_systemIcon = $val;}
   function defaultSystemIcon()    {return "";}

   /**
    * Select an image as a custom icon.
    *
    * When a image is selected as icon the SystemIcon property is not taked in consideration and the
    * custom icon indicated in this property will be rendered instead.
    *
    * @return string
    */
   function readIcon()    {return $this->_icon;}
   function writeIcon($val)    {$this->_icon = $val;}
   function defaultIcon()    {return "";}

   /**
    * Indicate the position of the icon.
    *
    * Choose the ipNoText to display just the icon with no text.
    *
    * @return string
    */
   function readIconPos()    {return $this->_iconPos;}
   function writeIconPos($val)    {$this->_iconPos = $val;}
   function defaultIconPos()    {return "ipRight";}

   /**
    * If set to true the component's drop down menu will render in the browser native mode
    *
    * In native mode list MultiSelect is not considered and defaults to false.
    *
    * @see readMultiSelect()
    *
    * @return boolean
    */
   function readIsNative()    {return $this->_isnative;}
   function writeIsNative($value)    {$this->_isnative = $value;}
   function defaultIsNative()    {return 0;}

   /**
    * Sets the button in inline position (works in fluid layouts like client page)
    */
   function readInline()    {return $this->_inline;}
   function writeInline($value)    {$this->_inline = $value;}
   function defaultInline()    {return 0;}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_size = 1;

      $this->_width = 200;
      $this->_height = 43;
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      if($this->_isnative == 0)
         $target = "#{$this->_name}-menu";

      else
         $target = "#$this->_name";

      $output .= bindEvents("jQuery('$target')", $this, 'click');
      $output .= bindEvents("jQuery('$target')", $this, 'dblclick');
      $output .= bindEvents("jQuery('#$this->_name')", $this, 'change');
      $output .= bindJSEvent("jQuery('$target')", $this, 'tap');
      $output .= bindJSEvent("jQuery('$target')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('$target')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('$target')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('$target')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('$target')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('$target')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('$target')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('$target')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('$target')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('$target')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);

      $groups = array();

      if( ! is_array($this->_items))
         $this->_items = array();

      //Now we will populate the NestedAttributes array for every element
      foreach($this->_items as $key=>$item)
      {
         $groups[$key] = $item['Group'];

         $data = array();
         if($item['Disabled'] == "true")
            $data["disabled"] = "disabled";

         if($item['PlaceHolder'] == "true")
            $data["data-placeholder"] = "true";

         $this->_nestedattributes[$item['Value']] = $data;
      }

      //let's reorder the array based on groups so all the elements that belong to the same group are together
      array_multisort($groups, $this->_items);

   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();
      MHeader($this);
   }

   function dumpCSS()
   {
		if($this->_enhancement == "enNone")
		{
			parent::dumpCSS();
		}

   }

	function dumpAdditionalCSS()
   {

        switch($this->_enhancement)
        {
            case "enFull":

                break;

            case "enStructure":

                echo $this->readCSSDescriptor()."_outer .ui-btn {\n";
                parent::dumpCSS();
                echo "}\n";
                break;

            case "enNone":
                break;
        }


      // adjust the border width and height
		echo  $this->readCSSDescriptor()."_outer .ui-btn { \n"
				.$this->_readCSSSize()
			."} \n";


      //If there is a custom icon we have to create the CSS class to handle it an put it on the header
      if($this->_icon != "")
      {
         ?>
  .ui-icon-<?php echo $this->_name?> {
    background-image:url(<?php echo str_replace(' ', '%20',$this->_icon)?>);
    background-size: 18px 18px;
  }
         <?php
      }
	}

   function dumpContents()
   {
      JQMDesignStart($this);

      if($this->_enhancement == "enNone")
      {
         $this->_attributes['data-role'] = "none";
      }
      else
      {

        if($this->_inline)
            $this->_attributes['data-inline'] = "true";

        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

        //Get the theme string
        if($this->_theme != "")
        {
          $RealTheme=RealTheme($this);
          $this->_attributes = array_merge($this->_attributes, $RealTheme->themeVal());
        }

         // get the icon if any
         $this->_attributes = array_merge($this->_attributes, iconVal($this));

         //is native support
         if( ! $this->_isnative)
            $this->_attributes["data-native-menu"] = "false";


         if( ! $this->_roundedcorners)
            $this->_attributes['data-corners'] = "false";

         if( ! $this->_iconshadow)
            $this->_attributes['data-iconshadow'] = "false";

         if( ! $this->_shadow)
            $this->_attributes['data-shadow'] = "false";
      }
      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Standard form ListBox, it inherits from ListBox but have specific design enhancements provided by jquery mobile.
 * Control can be shown like the browser's native mode or using the jquery special enhancement
 *
 * @see ListBox
 *
 * @example JQueryMobile/basic/mcombobox.php
 */
class MComboBox extends CustomMComboBox
{

   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getRoundedCorners()    {return $this->readroundedcorners();}
   function setRoundedCorners($value)    {$this->writeroundedcorners($value);}

   function getIconShadow()    {return $this->readiconshadow();}
   function setIconShadow($value)    {$this->writeiconshadow($value);}

   function getShadow()    {return $this->readshadow();}
   function setShadow($value)    {$this->writeshadow($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   function getIcon()    {return $this->readIcon();}
   function setIcon($val)    {$this->writeIcon($val);}

   function getSystemIcon()    {return $this->readSystemIcon();}
   function setSystemIcon($val)    {$this->writeSystemIcon($val);}

   function getIconPos()    {return $this->readIconPos();}
   function setIconPos($val)    {return $this->writeIconPos($val);}

   function getIsNative()    {return $this->readIsnative();}
   function setIsNative($value)    {$this->writeisnative($value);}

   function getInline()    {return $this->readinline();}
   function setInline($val)    {$this->writeinline($val);}

   function getOnChange()    {return $this->readonchange();}
   function setOnChange($value)    {$this->writeonchange($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}

}

/**
 * Base class for MCollapsibleSe
 */
class CustomMCollapsibleSet extends FocusControl
{
   protected $_theme = "";
   protected $_panels = array();
   protected $_activelayer = "";
   protected $_enhancement = "enFull";
   protected $_iconpos="ipLeft";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}


   /**
    * Indicate the position of the icon.
    *
    * Choose the ipNoText to display just the icon with no text.
    *
    * @return string
    */
   function readIconPos() { return $this->_iconpos; }
   function writeIconPos($value) { $this->_iconpos=$value; }
   function defaultIconPos() { return "ipLeft"; }

   protected $_jsoncollapse = "";
   protected $_jsonexpand = "";

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * Event fired when one panel is expanded.
    *
    * Once one panel is expanded, all the others fire the OnCollapse() event
    *
    * @see readjsOnCollapse()
    */
   function readjsOnExpand()    {return $this->_jsonexpand;}
   function writejsOnExpand($value)    {$this->_jsonexpand = $value;}
   function defaultjsOnExpand()    {return "";}

   /**
    * Event fired when one panel is collapsed
    */
   function readjsOnCollapse()    {return $this->_jsoncollapse;}
   function writejsOnCollapse($value)    {$this->_jsoncollapse = $value;}
   function defaultjsOnCollapse()    {return "";}

   /**
    *  List of the different panels included in the MAccordion
    *
    * @return array
    */
   function readPanels()    {return $this->_panels;}
   function writePanels($value)    {$this->_panels = $value;}
   function defaultPanels()    {return array();}

   /**
    * This getter is overriden to sync the layers with the Panels
    *
    * @see Control::getLayer()
    * @return string
    *
    */
   function getActiveLayer()
   {
      // at design time, we get the first panel
      if(($this->ControlState & csDesigning) == csDesigning)
      {
        if(count($this->_panels) > 0 && !array_search($this->_activelayer, $this->_panels))
        {
          return $this->_panels[0];
        }
      }
      return $this->_activelayer;
   }
   function setActiveLayer($value) {$this->_activelayer = $value;}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csAcceptsControls=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->Layout->Type = "BOXED_LAYOUT";

      $this->_width = 300;
      $this->_height = 300;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsoncollapse);
      $this->dumpJSEvent($this->_jsonexpand);
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'expand');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'collapse');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'click');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'dblclick');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'mouseup');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'mousedown');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'tap');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('.{$this->_name}_class')", $this, 'vmousecancel');
      return $output;
   }

    function dumpCSS()
    {
        if($this->_enhancement == "enStructure")
        {
            // apply transformation to entire component
            echo $this->_transform->readCSSString();
            echo $this->_boxshadow->readCSSString();
        }

        if($this->isFixedSize())
        {
            if($this->_width != "")
                echo "width: {$this->_width}px;\n";
        }
    }

    function dumpAdditionalCSS()
    {
        if($this->_enhancement == "enStructure")
        {
            // header and content css descriptors
            if($this->_style == "")
            {
                echo $this->readCSSDescriptor()." > div > h1 a{\n";
                echo $this->_readColorCSSString();
                echo $this->parseCSSCursor();
                echo $this->_borderradius->readCSSString();
                echo $this->_gradient->readCSSString();
                echo "}\n";

                // apply text css
                echo $this->readCSSDescriptor()." > div > h1 .ui-btn-text{\n";
                echo $this->Font->FontString."\n";
                echo $this->_textshadow->readCSSString();
                echo "}\n";
            }
        }

        //TODO: prevent print this when the content is flow
       $contentMinHeight = $this->_height - count($this->_panels)*60;
       echo "{$this->readCSSDescriptor()}  .ui-collapsible-content{\n";
       echo " min-height: {$contentMinHeight}px\n";
       echo "}\n";

       if(($this->ControlState & csDesigning) != csDesigning)
       {
          $this->Layout->dumpLayoutContents(array(), true);
       }

    }

   function dumpContents()
   {

      JQMDesignStart($this, -10);

      //Get the theme string
      $theme = "";
      if($this->_theme != "")
      {
        $RealTheme=RealTheme($this);
        $theme = $RealTheme->themeVal(1);
      }



	  //Get the icon position
	  $iconpos = "data-iconpos=".iconPos($this->_iconpos);

      echo "<div data-role=\"collapsible-set\" $iconpos id=\"$this->_name\" >";

      $activelayer = $this->ActiveLayer;

      //a note about how to use the component
      if(count($this->_panels) == 0 && ($this->ControlState & csDesigning) == csDesigning)
      {
         echo "<p style=\"font-size:11px;color:black;padding:10px;\">";
         echo "This control renders a group of MCollapsible panels.<br>";
         echo "Use the property Panels to create them, one per line.<br>";
         echo "Use the property ActiveLayer to indicate the active panel where you want to add content.";
         echo "<p>";
      }

      if( ! is_array($this->_panels))
         $this->_panels = array();

      foreach($this->_panels as $k=>$layer)
      {
         if($activelayer == $layer)
            $collapsed = "data-collapsed=\"false\"";
         else
            $collapsed = "";

         //we need to include as an attribute the top offset for the inner element

         echo "<div data-role=\"collapsible\" class=\"{$this->_name}_class\" id=\"" . $this->_name . $k . "\" $collapsed $theme >\n";
         echo "<h1>$layer</h1>\n";

         if(($this->ControlState & csDesigning) != csDesigning)
         {

            echo "<div>";
            $this->callEvent('onshow', array());

            // change Active layer temporary to render the controls in Boxed Layout
            $tempLayer = $this->ActiveLayer;
            $this->ActiveLayer = $layer;
            $this->Layout->dumpLayoutContents();
            $this->ActiveLayer = $tempLayer;

            echo "</div>";
         }
         else
         {
            echo "<span style=\"font-size:11px;color:black\">";
            echo "Place the content for panel $layer  anywhere in the box.<br>";
            echo "The elements placed on the Panel will be rendered one after the other ";
            echo "ordered by its top value";
            echo "<span>";
         }
         echo "</div>";
      }

      echo "</div>";
      JQMDesignEnd($this);
   }

}

/**
 * This class renders a collection of Collapsible containers like MCollapsible
 * By clicking one of them the rest get closed
 *
 * @example JQueryMobile/basic/mcollapsibleset.php
 */
class MCollapsibleSet extends CustomMCollapsibleSet
{

   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getIconPos()    {return $this->readIconPos();}
   function setIconPos($val)    {return $this->writeIconPos($val);}

   function getTheme()    {return $this->readtheme();}
   function setTheme($value)    {$this->writetheme($value);}

   function getPanels()    {return $this->readpanels();}
   function setPanels($value)    {$this->writepanels($value);}

   function getjsOnExpand()    {return $this->readjsOnexpand();}
   function setjsOnExpand($value)    {$this->writejsOnexpand($value);}

   function getjsOnCollapse()    {return $this->readjsOncollapse();}
   function setjsOnCollapse($value)    {$this->writejsOncollapse($value);}

   function getjsOnClick()    {return $this->readjsOnclick();}
   function setjsOnClick($value)    {$this->writejsOnclick($value);}

   function getjsOnDblClick()    {return $this->readjsOndblclick();}
   function setjsOnDblClick($value)    {$this->writejsOndblclick($value);}

   function getjsOnMouseUp()    {return $this->readjsOnmouseup();}
   function setjsOnMouseUp($value)    {$this->writejsOnmouseup($value);}

   function getjsOnMouseDown()    {return $this->readjsOnmousedown();}
   function setjsOnMouseDown($value)    {$this->writejsOnmousedown($value);}

   function getFont()    {return $this->readfont();}
   function setFont($value)    {$this->writefont($value);}

   function getParentFont()    {return $this->readparentfont();}
   function setParentFont($value)    {$this->writeparentfont($value);}

   function getParentColor()    {return $this->readparentcolor();}
   function setParentColor($value)    {$this->writeparentcolor($value);}

   function getColor()    {return $this->readcolor();}
   function setColor($value)    {$this->writecolor($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

class MMappingFields extends Persistent
{
   protected $_caption = "";
   protected $_mlist = "";
   protected $_isdivider = "";
   protected $_icon = "";
   protected $_thumbnail = "";
   protected $_thumbnailhint = "";
   protected $_isicon = "";
   protected $_filtertext="";
   protected $_extrabuttonlink = "";
   protected $_extrabuttonhint = "";
   protected $_countervalue = "";
   protected $_link = "";
   protected $_parentfield = "";
   protected $_idfield = "";
   protected $_baseparentfieldvalue = "";

   public $_control = null;

   // Documented in the parent.
   function readOwner()
   {
      return ($this->_control);
   }


   /**
    * Parent value for the elements in the base level
    */
   function getBaseParentFieldValue()    {return $this->_baseparentfieldvalue;}
   function setBaseParentFieldValue($value)    {$this->_baseparentfieldvalue = $value;}
   function defaultBaseParentFieldValue()    {return "";}

   /**
    * Field that identifies the item
    */
   function getIdField()    {return $this->_idfield;}
   function setIdField($value)    {$this->_idfield = $value;}
   function defaultIdField()    {return "";}

   /**
    * Field that identifies the item's parent
    */
   function getParentField()    {return $this->_parentfield;}
   function setParentField($value)    {$this->_parentfield = $value;}
   function defaultParentField()    {return "";}

   /**
    * Link associated to the item
    */
   function getLink()    {return $this->_link;}
   function setLink($value)    {$this->_link = $value;}
   function defaultLink()    {return "";}

   /**
    * A numeric value that will be represented in a bubble on the right side of the item, like a counter
    */
   function getCounterValue()    {return $this->_countervalue;}
   function setCounterValue($value)    {$this->_countervalue = $value;}
   function defaultCounterValue()    {return "";}

   /**
    * Hint text for the extra button link
    */
   function getExtraButtonHint()    {return $this->_extrabuttonhint;}
   function setExtraButtonHint($value)    {$this->_extrabuttonhint = $value;}
   function defaultExtraButtonHint()    {return "";}

   /**
    * Secondary item's link that will render as a button
    */
   function getExtraButtonLink()    {return $this->_extrabuttonlink;}
   function setExtraButtonLink($value)    {$this->_extrabuttonlink = $value;}
   function defaultExtraButtonLink()    {return "";}

   /**
    * Indicate if the thumbnail is an icon
    */
   function getIsIcon()    {return $this->_isicon;}
   function setIsIcon($value)    {$this->_isicon = $value;}
   function defaultIsIcon()    {return "";}

   /**
    * Text for search in Lists with isFilter enabled
    */
   function getFilterText() { return $this->_filtertext; }
   function setFilterText($value) { $this->_filtertext=$value; }
   function defaultFilterText() { return ""; }

   /**
    * Thumbnail to be represented on the left side of the item
    */
   function getThumbnail()    {return $this->_thumbnail;}
   function setThumbnail($value)    {$this->_thumbnail = $value;}
   function defaultThumbnail()    {return "";}

   /**
    * Hint for the Thumbnail
    */
   function getThumbnailHint()    {return $this->_thumbnailhint;}
   function setThumbnailHint($value)    {$this->_thumbnailhint = $value;}
   function defaultThumbnailHint()    {return "";}

   /**
    * A reference to another MList element that will be rendered as a nested list
    */
   function getMList()    {return $this->_mlist;}
   function setMList($value)    {$this->_mlist = $value;}
   function defaultMList()    {return "";}

   /**
    * Right side item's icon
    */
   function getIcon()    {return $this->_icon;}
   function setIcon($value)    {$this->_icon = $value;}
   function defaultIcon()    {return "";}

   /**
    * indicates if the items is going to be rendered as a list divider
    */
   function getIsDivider()    {return $this->_isdivider;}
   function setIsDivider($value)    {$this->_isdivider = $value;}
   function defaultIsDivider()    {return "";}

   /**
    * Item's Caption
    */
   function getCaption()    {return $this->_caption;}
   function setCaption($value)    {$this->_caption = $value;}
   function defaultCaption()    {return "";}
}
/**
 * Base class for MList control
 */
class CustomMList extends MControl
{
   protected $_dividertheme = "";
   protected $_type = "tUnordered";
   protected $_systemicon = "siGear";
   protected $_icon = "";
   protected $_extrabuttontheme = "";
   protected $_iswrapped = 0;
   protected $_isfiltered = 0;
   protected $_items = array();
   protected $_countertheme = "";
   protected $_datasource = null;

   protected $_datamapping = null;

   protected $_onemptylist = "";

   /**
    * Event Triggered when the list has no values after updating a DataSet
    */
   function readOnEmptyList()    {return $this->_onemptylist;}
   function writeOnEmptyList($value)    {$this->_onemptylist = $value;}
   function defaultOnEmptyList()    {return "";}

   /**
    * When using a DataSet to fillup the List,indicate the fields that can be mapped to the DataSet columns
    *
    */
   function readDataMapping()    {return $this->_datamapping;}
   function writeDataMapping($value)    {if(is_object($value))           $this->_datamapping = $value;}
   function defaultDataMapping()    {return null;}

   /**
    * DataSource property allows you to link this control to a dataset containing
    * rows of data.
    *
    * To make it work, you must also assign DataField property with
    * the name of the column you want to use
    *
    * @return Datasource
    */
   function readDataSource()    {return $this->_datasource;}
   function writeDataSource($value)    {$this->_datasource = $this->fixupProperty($value);}
   function defaultDataSource()    {return "";}

   /**
    * Select a MobileTheme component to handle the Counter Bubble's color theme
    *
    * MobileTheme components indicate the color variation of a control.
    * Different controls asigned to the same MobileTheme will use the same color variation when rendered.
    *
    * @see MobileTheme
    *
    * @return object returns the MobileTheme assigned
    */
   function readCounterTheme()    {return $this->_countertheme;}
   function writeCounterTheme($value)    {$this->_countertheme = $this->fixupProperty($value);}
   function defaultCounterTheme()    {return "";}

   /**
    * Items to include on the list. They can be other existing MList control
    *
    * if the item includes a link to another MList control, only the Caption attribute is considered
    *
    * @return array
    */
   function readItems()    {return $this->_items;}
   function writeItems($value)    {$this->_items = $value;}
   function defaultItems()    {return array();}

   /**
    * Add a Search Filter on the top of the list.
    *
    * By entering text on the Search Filter it will dinamically show anly tha elements taht contani nthe text
    *
    * @return boolean
    */
   function readIsFiltered()    {return $this->_isfiltered;}
   function writeIsFiltered($value)    {$this->_isfiltered = $value;}
   function defaultIsFiltered()    {return 0;}

   /**
    * Indicate if the list is going to be Wrapped in a styled container
    *
    * @return boolean
    */
   function readIsWrapped()    {return $this->_iswrapped;}
   function writeIsWrapped($value)    {$this->_iswrapped = $value;}
   function defaultIsWrapped()    {return 0;}

   /**
    * Select a MobileTheme component to handle the Extra Button's color theme
    *
    * MobileTheme components indicate the color variation of a control.
    * Different controls asigned to the same MobileTheme will use the same color variation when rendered.
    *
    * @see MobileTheme
    *
    * @return object returns the MobileTheme assigned
    */
   function readExtraButtonTheme()    {return $this->_extrabuttontheme;}
   function writeExtraButtonTheme($value)    {$this->_extrabuttontheme = $this->fixupProperty($value);}
   function defaultExtraButtonTheme()    {return "";}

   /**
    * Select an image as a custom icon to display on the Items with the Extra Button.
    *
    * When a image is selected as icon the SystemIcon property is not taked in consideration and the
    * custom icon indicated in this property will be rendered instead.
    *
    * @return string
    */
   function readIcon()    {return $this->_icon;}
   function writeIcon($value)    {$this->_icon = $value;}
   function defaultIcon()    {return "";}

   /**
    *  Indicate the System icon to display on the Items with the Extra Button
    *
    * Different system icons can be assigned to this control.
    * To use a custom icon use the Icon property instead.
    *
    * @see readIcon()
    *
    * @return string
    */
   function readSystemIcon()    {return $this->_systemicon;}
   function writeSystemIcon($value)    {$this->_systemicon = $value;}
   function defaultSystemIcon()    {return "siGear";}

   /**
    * Indicate the type of list to generate
    */
   function readType()    {return $this->_type;}
   function writeType($value)    {$this->_type = $value;}
   function defaultType()    {return "tUnordered";}

   /**
    * Select a MobileTheme component to handle the divider's color theme
    *
    * MobileTheme components indicate the color variation of a control.
    * Different controls asigned to the same MobileTheme will use the same color variation when rendered.
    *
    * @see MobileTheme
    *
    * @return object returns the MobileTheme assigned
    */
   function readDividerTheme()    {return $this->_dividertheme;}
   function writeDividerTheme($value)    {$this->_dividertheme = $this->fixupProperty($value);}
   function defaultDividerTheme()    {return "";}


   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      $this->_datamapping = new MMappingFields();
      $this->_datamapping->_control = $this;

      $this->_width = 300;
      $this->_height = 300;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeDividerTheme($this->_dividertheme);
      $this->writeExtraButtonTheme($this->_extrabuttontheme);
      $this->writeCounterTheme($this->_countertheme);
      $this->writeDataSource($this->_datasource);

   }

   /**
    * Helper function that checks if a passed array has a value in the key passed
    * If so it returns its value otherwise it returns the $default value
    *
    * @param array $row The array of elements
    * @param string $field the DataMapping property to evaluate
    * @param string $default the default value to return
    *
    * @return string
    */
   function mappedFieldValue($row, $field, $default = "")
   {

      if(is_array($row) && $this->_datamapping->$field != "" && isset($row[$this->_datamapping->$field]))
      {
         return $row[$this->_datamapping->$field];
      }
      else
         return $default;
   }

   /**
    * Here we check if there is a parameter indicating that the dataset has to be refreshed
    *
    */
   function preinit()
   {
      parent::preinit();

      $key = md5($this->owner->Name . $this->Name . $this->Left . $this->Top . $this->Width . $this->Height);
      $list = $this->input->list;
      $urlid = $this->input->id;

      $mappedfields = $this->_datamapping;
      $parent_id = $mappedfields->BaseParentFieldValue;
      $filter_id = $mappedfields->ParentField;

      //check for the id param to update the dataset if any
      if((is_object($list)) && ($list->asString() == $key))
      {
         if(is_object($urlid))
            $parent_id = $urlid->asString();
      }

      if($this->_datasource != null && $this->_datasource->Dataset)
      {
         $query = $this->_datasource->Dataset;

         if($filter_id != "")
         {
            $this->_datasource->Dataset->filter = "$filter_id=" . $parent_id;
            $this->_datasource->Dataset->refresh();

         }

         $query->Open();
         //lets check if there aren't  results in the dataset and fire the OnEmptyList event
         if($query->EOF && $query->BOF && $this->_onemptylist != "")
         {
            $this->callEvent('onemptylist', array());
         }
         //$query->close();
      }
   }

  /*
   function init()
   {
      parent::init();


   }
   */

   /**
    * If we have a dataset we have to populate the items array with the results according with the datamapping values
    */
   function updateControl()
   {
       $key = md5($this->owner->Name . $this->Name . $this->Left . $this->Top . $this->Width . $this->Height);

      if($this->_datasource != null && $this->_datasource->Dataset)
      {

         $this->_items = array();

         $query = $this->_datasource->Dataset;

         $query->Open();
         $query->First();

         $k = 0;

         while( ! $query->EOF)
         {
            $fields = $query->Fields;

            $this->_items[$k]['Caption'] = $this->mappedFieldValue($fields, "Caption");
            $this->_items[$k]['isDivider'] = $this->mappedFieldValue($fields, "IsDivider", "false");
            $this->_items[$k]['Icon'] = $this->mappedFieldValue($fields, "Icon");;
            $this->_items[$k]['MList'] = $this->mappedFieldValue($fields, "MList");;
            $this->_items[$k]['Thumbnail'] = $this->mappedFieldValue($fields, "Thumbnail");
            $this->_items[$k]['ThumbnailHint'] = $this->mappedFieldValue($fields, "ThumbnailHint");
            $this->_items[$k]['isIcon'] = $this->mappedFieldValue($fields, "IsIcon", "false");
            $this->_items[$k]['ExtraButtonLink'] = $this->mappedFieldValue($fields, "ExtraButtonLink");
            $this->_items[$k]['ExtraButtonHint'] = $this->mappedFieldValue($fields, "ExtraButtonHint");
            $this->_items[$k]['CounterValue'] = $this->mappedFieldValue($fields, "CounterValue");
            $this->_items[$k]['FilterText'] = $this->mappedFieldValue($fields, "FilterText");

            $itemid = $this->mappedFieldValue($fields, "IdField");
            $itemlink = $this->mappedFieldValue($fields, "Link");

            if($itemid != "")
            {
               $url = $_SERVER['PHP_SELF'] . "?list=$key&amp;id=" . $itemid;
               // if the MPage has useAjax enabled then we have to mark the links as ajax enabled so the click will be handled by Ajax
               if($this->Owner->UseAjax == 1)
                  $this->_items[$k]['AjaxLink'] = "true";
            }
            else
               $url = "";

            $this->_items[$k]['Link'] = $itemlink != ""? $itemlink: $url;
            $query->next();
            $k++;
         }

      }
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();
      MHeader($this);
   }

   function dumpCSS()
   {
        if($this->_enhancement == "enStructure")
        {
            if($this->_style == "")
            {
                // apply outer box styles to entire list
                echo $this->_transform->readCSSString();
                echo $this->_borderradius->readCSSString();
                echo $this->_boxshadow->readCSSString();
            }
        }
   }

   function dumpAdditionalCSS()
   {

       echo $this->readCSSDescriptor()."_outer {\n";
       echo $this->_readCSSSize();
       echo "}\n";

      switch($this->_enhancement)
		{
			case "enStructure":

                if($this->_style == "")
                {
                    // apply font styles to text of lists elements
                    echo $this->readCSSDescriptor()." > li,\n";
                    echo $this->readCSSDescriptor()." > li > .ui-btn-inner > .ui-btn-text > a {\n";
                    echo $this->Font->FontString;
                    echo $this->parseCSSCursor();
                    echo "}\n";

                    // apply box color styles to list elements
                    echo $this->readCSSDescriptor()." > li{\n";
                    echo $this->_readColorCSSString();
                    echo $this->_gradient->readCSSString();
                    echo "}\n";
                }

            case "enFull":
				break;
		}

      //If there is a custom icon we have to create the CSS class to handle it an put it on the header
      if($this->_icon != "")
      {
            ?>
     .ui-icon-<?php echo $this->_name?> {
       background-image:url(<?php echo str_replace(' ', '%20',$this->_icon)?>);
       background-size: 18px 18px;
     }
            <?php
      }
   }

   /**
    * Generates the content of the MList
    */
   function fillMList()
   {

      if( ! is_array($this->_items))
         $this->_items = array();

      foreach($this->_items as $item)
      {
         // it is a divider?
         if(isset($item['IsDivider']) && $item['IsDivider'] == "true")
         {
            $divider = "data-role=\"list-divider\"";
         }
         else
         {
            $divider = "";
         }
         // custom icon
         if(isset($item['Icon']) && $item['Icon'] != "")
            $liicon = "data-icon=\"" . systemIcon($item['Icon']) . "\"";
         else
            $liicon = "";

        // filter text
        if(isset($item['FilterText']) && $item['FilterText'] != "")
            $lifiltertext = "data-filtertext=\"" . $item['FilterText'] . "\"";
         else
            $lifiltertext = "";

         echo "\t<li $liicon $lifiltertext $divider >";
         if( ! isset($item['MList']) || $item['MList'] == "")
         {

            if(isset($item['IsDivider']) && $item['IsDivider'] == "true")
            {
               echo $item['Caption'];
            }
            else
            {

               //the link
               if(isset($item['Link']) && $item['Link'] != "")
               {
                  $ajax = "";
                  if(isset($item['AjaxLink']) && $item['AjaxLink'] == 'true')
                  {
                     $ajax = " data-ajax=\"true\" rel=\"external\" ";

                  }

                  $link = str_replace(' ', '%20',$item['Link']);

                  echo "<a href=\"$link\"$ajax>";
               }

               //check for thumbnail
               if(isset($item['Thumbnail']) && $item['Thumbnail'] != "")
               {
                  //if the thumb is an icon
                  if(isset($item['IsIcon']) && $item['IsIcon'] == 'true')
                     $isicon = "class=\"ui-li-icon\"";
                  else
                     $isicon = "";

                  $thumbnail = str_replace(' ', '%20',$item['Thumbnail']);
                  $thumbnailhint = (isset($item['ThumbnailHint'])) ? $item['ThumbnailHint'] : "";
                  echo "<img $isicon src=\"{$thumbnail}\" alt=\"{$thumbnailhint}\">";
               }



               echo $item['Caption'];

               if(isset($item['Link']) && $item['Link'] != "")
                  echo "</a>";

               //the extra button
               if(isset($item['ExtraButtonLink']) && $item['ExtraButtonLink'] != "")
               {
                  echo "<a href=\"" . $item['ExtraButtonLink'] . "\">" . $item['ExtraButtonHint'] . "</a>";
               }

               //the numeric value on the right
               if(isset($item['CounterValue']) && $item['CounterValue'] != "")
                  echo "<span class=\"ui-li-count\">" . $item['CounterValue'] . "</span>";
            }
         }
         else
         {
            echo $item['Caption'];
            if(($this->ControlState & csDesigning) != csDesigning)
            {
               //Get the object und unrelate it to us so it can be dumped
               $item['MList'] = $this->fixupProperty($item['MList']);
               $visibleStatus = $item['MList']->_visible;
               $item['MList']->_visible = 1;
               $item['MList']->dumpContents();
               $item['MList']->_visible = $visibleStatus;
            }
            else
            {
               echo "<ul><li></li></ul>";
            }
         }
         echo "</li>\n";
      }

   }

   function dumpContents()
   {
      if(($this->ControlState & csDesigning) != csDesigning)
          $this->updateControl();


      JQMDesignStart($this);
      //Get the theme strings
      $theme = "";
      $dividertheme = "";
      $extrabuttontheme = "";
      $countertheme = "";

      if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

      if($this->_theme != "")
      {
        $RealTheme=RealTheme($this);
        $theme = $RealTheme->themeVal(1);
      }

      if($this->_dividertheme != "")
      {
        $RealTheme=RealTheme($this,"DividerTheme");
        $arraydividertheme = $RealTheme->themeVal();
        $dividertheme = "data-divider-theme =\"" . $arraydividertheme['data-theme'] . "\"";
      }

      //Get the extra button's theme string
      if($this->_extrabuttontheme != "")
      {
        $RealTheme=RealTheme($this,"ExtraButtonTheme");
        $arrayextrabuttontheme = $RealTheme->themeVal();
        $extrabuttontheme = "data-split-theme =\"" . $arrayextrabuttontheme['data-theme'] . "\"";
      }

      //Get the counter bubble's theme string
      if($this->_countertheme != "")
      {
        $RealTheme=RealTheme($this,"CounterTheme");
        $arraycountertheme = $RealTheme->themeVal();
        $countertheme = "data-count-theme =\"" . $arraycountertheme['data-theme'] . "\"";
      }

      // get the icon for the extra button
      if($this->_icon != "")
         $icon = "data-split-icon=\"$this->_name\"";
      else
         if($this->_systemicon != "")
            $icon = "data-split-icon=\"" . systemIcon($this->_systemicon) . "\"";

         // the type of list
      if($this->_type == "tUnordered")
         $tag = "ul";
      else
         $tag = "ol";

      //wrapped
      if($this->_iswrapped)
         $wrapped = "data-inset=\"true\"";
      else
         $wrapped = "";

      // Filter search bar
      if($this->_isfiltered)
         $filtered = "data-filter=\"true\"";
      else
         $filtered = "";

      if($this->isFixedSize())
         $fixedsize = "data-fixedsize=\"true\"";
      else
         $fixedsize = "";



      // call the OnShow event if assigned so the Items property can be changed
      if ($this->_onshow != null)
      {
            $this->callEvent('onshow', array());
      }

      echo "<$tag data-role=\"listview\" id=\"$this->_name\" $fixedsize $theme $dividertheme $extrabuttontheme $countertheme $icon $wrapped $filtered >\n";

      $this->fillMList();

      echo "</$tag>";

      JQMDesignEnd($this);
   }

}

/**
 * This control renders an ordered or unordered list of items. Allow nested MList.
 * This items can have:
 * - One link
 * - Caption that allows HTML tags
 * - A Thumbnail image
 * - An Extra Button with a secondary Link
 * - Another MList control
 *
 * @example JQueryMobile/basic/mlist.php
 */
class MList extends CustomMList
{

   function getItems()    {return $this->readitems();}
   function setItems($value)    {$this->writeitems($value);}

   function getExtraButtonTheme()    {return $this->readextrabuttontheme();}
   function setExtraButtonTheme($value)    {$this->writeextrabuttontheme($value);}

   function getIsFiltered()    {return $this->readisfiltered();}
   function setIsFiltered($value)    {$this->writeisfiltered($value);}

   function getIsWrapped()    {return $this->readiswrapped();}
   function setIsWrapped($value)    {$this->writeiswrapped($value);}

   function getIcon()    {return $this->readicon();}
   function setIcon($value)    {$this->writeicon($value);}

   function getSystemIcon()    {return $this->readsystemicon();}
   function setSystemIcon($value)    {$this->writesystemicon($value);}

   function getType()    {return $this->readtype();}
   function setType($value)    {$this->writetype($value);}

   function getDividerTheme()    {return $this->readdividertheme();}
   function setDividerTheme($value)    {$this->writedividertheme($value);}

   function getCounterTheme()    {return $this->readcountertheme();}
   function setCounterTheme($value)    {$this->writecountertheme($value);}

   function getVisible()    {return $this->readvisible();}
   function setVisible($value)    {$this->writevisible($value);}

   function getDataSource()    {return $this->readdatasource();}
   function setDataSource($value)    {$this->writedatasource($value);}

   function getDataMapping()    {return $this->readdatamapping();}
   function setDataMapping($value)    {$this->writedatamapping($value);}

   //events
   function getOnEmptyList()    {return $this->readonemptylist();}
   function setOnEmptyList($value)    {$this->writeonemptylist($value);}

	///TODO: delete this (diego)
   //function getjsOnMouseUp()    {return $this->readjsOnmouseup();}
   //function setjsOnMouseUp($value)    {$this->writejsOnmouseup($value);}

   //function getjsOnMouseDown()    {return $this->readjsOnmousedown();}
   //function setjsOnMouseDown($value)    {$this->writejsOnmousedown($value);}
}

/**
 * Base class for MInputGroup control
 */
class CustomMInputGroup extends RadioGroup
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   /*
   * Write the Javascript section to the header
   */
   function dumpJavascript()
   {
      if($this->_enabled == 1)
      {
         if( ! defined('RadioGroupClick'))
         {
            define('RadioGroupClick', 1);
         }
      }
      parent::dumpJavascript();
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 200;
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";

      $output .= bindEvents("jQuery('input[name={$this->_name}]')", $this, 'click');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'tap');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('input[name={$this->_name}]').next('label')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

	function dumpAdditionalCSS()
	{
		$style="";

		switch($this->_enhancement)
		{
			case "enNone":
				parent::dumpAdditionalCSS();
				break;

			case "enStructure":

				  if ($this->Style == "")
				  {
					  // get the Font attributes
					  $style .= $this->Font->FontString;

					  if($this->Color != "")
					  {
						  $style .= "background-color: " . $this->Color . ";\n";
					  }

					  // add the cursor to the style
					  $style .= parent::parseCSSCursor();
				  }



			case "enFull":

				  $spanstyle = $style;

				  $h = $this->Height - 2;
				  $w = $this->Width;

                  if($this->isFixedSize())
                  {
                    $style .= "height:" . $h . "px;\n" .
								"width:" . $w . "px;\n";
                  }

                  if(!$this->isFixedSize())
                  {
                    echo $this->readCSSDescriptor()."_table,".
                        $this->readCSSDescriptor()."_table td,".
                        $this->readCSSDescriptor()."_table tr,".
                        $this->readCSSDescriptor()."_table tbody".
                        "{\n";
                    echo "display: block;\n";
                    echo "height: auto";
                    echo "}\n";
                  }


				  //Add correct layout table for the grouping
				  $style .= "table-layout:fixed;";

				  if($this->readHidden())
				  {
					  if(($this->ControlState & csDesigning) != csDesigning)
					  {
						  $style .= " visibility:hidden; ";
					  }
				  }

				  // get the alignment of the Items
				  $alignment = "";

				  switch ($this->_alignment)
				  {
						  case agNone :
									 $alignment = "";
									 break;
						  case agLeft :
									 $alignment = "text-align: left;\n";
									 break;
						  case agCenter :
									 $alignment = "text-align: center;\n";
									 break;
						  case agRight :
									 $alignment = "text-align: right;\n";
									 break;
				  }

				  $tcss=$this->readCSSDescriptor().'_table';

				  echo $tcss." {\n";
				  echo "padding:0px;\n";
				  echo "border-spacing:0px;\n";
				  echo $style . "\n";
				  echo "}\n";

				if(is_array($this->_items) && count($this->_items) > 0)
				{
					$columnsWidth = $this->calculateColumnsWidth();

					if ($this->_orientation == "orHorizontal")
					{
                        $rounded = round($columnsWidth);
                        if($this->isFixedSize())
                        {
                            $spanstyle .= "width:{$rounded}px;\n";
                        }
                        $spanstyle .= "margin-right: -1px;\n";
					}

					$rowHeight = $this->calculateRowHeight();
					$itemWidth = $columnsWidth-20;

					echo $this->readCSSDescriptor()."_table label {\n";
                    if($this->isFixedSize())
                    {
                        echo "height:{$rowHeight}px;\n";
                        echo "width: {$columnsWidth}px;\n";
                        echo "overflow: hidden;\n";
                    }
					echo $alignment;
					echo "}\n";


					echo $tcss." label {\n";
					echo $spanstyle;
					echo "}\n";
				}

				break;

		}
	}

   function dumpContents()
   {
      JQMDesignStart($this);
      if($this->_enhancement == "enNone")
      {
         $this->_nestedattributes['data-role'] = "none";
      }
      else
      {
      //Get the theme string
      if($this->_theme != "")
      {
        $RealTheme=RealTheme($this);
        $this->_nestedattributes = array_merge($this->_nestedattributes, $RealTheme->themeVal());
      }
         $this->_attributes['data-role'] = "controlgroup";


        if($this->isFixedSize())
        {
            $this->_attributes['data-fixedsize'] = "true";
            $this->_nestedattributes['data-fixedsize'] = "true";
        }

      }
      if($this->_orientation == "orHorizontal")
        $this->_attributes['data-type'] = "horizontal";

      parent::dumpContents();

      JQMDesignEnd($this);
   }

    /**
     * Returns the width of each column of the control, in pixels.
     *
     * @internal
     */
   protected function calculateColumnsWidth()
    {
        $columns = $this->_columns;

        if($this->_enhancement != "enNone" && $this->_orientation == orVertical)
            $columns = 1;

        if ($columns > 0)
            return ($this->Width / $columns);
        else
            return 1;
    }

    /**
     * Returns the amount of items that will be placed in each column.
     *
     * @internal
     */
    protected function calculateItemsPerColumn()
    {
        $columns = $this->_columns;

        if($this->_enhancement != "enNone" && $this->_orientation == orVertical)
            $columns = 1;

        $numItems = count($this->items);
        if ($columns > 0)
            return ceil($numItems / $columns);
        else
            return 1;

    }

   /*
   function updateColumns($value)
   {

        if ($this->_orientation == orHorizontal)
        {
            $count = count($this->items);
            $this->_columns= ($count) ? $count : 1;
        }
        else
            $this->_columns = 1;
   }

   function writeColumns($value)
   {
      $this->updateColumns($value);
   }

    function writeOrientation($value)
    {
      $this->_orientation=$value;
      $this->updateColumns($value);
    }
    */
}

/**
 * Standard form radio button group, it inherits from RadioGroup but have specific design enhancements provided by jquery mobile.
 * Setting the Orientation in "orHorizontal" will render it as a group toggle buttons
 *
 * @example JQueryMobile/basic/mradiogroup.php
 */

class MRadioGroup extends CustomMInputGroup
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   /**
    * Indicate the orientation of the group
    * Setting the Orientation in "orHorizontal" will render it as a group toggle buttons
    */
   function getOrientation()    {return $this->readorientation();}
   function setOrientation($value)    {$this->writeorientation($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for MCheckBoxGroup control
 */
class CustomMCheckBoxGroup extends CheckListBox
{
   protected $_theme = "";
   protected $_enhancement = "enFull";

   /**
    * Establish the enhancement degree of the component:
    * <pre>
    * enFull: All styles are defined by the CSS file or the Theme attribute
    * enStructure: Component's attributes like font or color are taken in consideration. CSS only applies to structure
    * enNone: No style is taken from the CSS file or Theme attribute
    * </pre>
    */
   function readEnhancement()    {return $this->_enhancement;}
   function writeEnhancement($value)    {$this->_enhancement = $value;}
   function defaultEnhancement()    {return "enFull";}

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

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
   function writeTheme($val)    {$this->_theme = $this->fixupProperty($val, 'MobileTheme');}
   function defaultTheme()    {return "";}

   /**
    * This function is overwritten because we are going to handle the PHP events with jQuery
    */
   protected function getJSWrapperFunction($event)
   {
      $res = "";
      return $res;
   }

   /**
    * All the wrappers of PHP events get deleted so we can use jQuery instead
    */
   protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
   {
      if($jsEvent != null)
         $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"", "", $events);
   }

   /*
   * Write the Javascript section to the header
   */
   function dumpJavascript()
   {
      if($this->_enabled == 1)
      {
         if( ! defined('CheckListBoxClick'))
         {
            define('CheckListBoxClick', 1);
         }
      }
      parent::dumpJavascript();
   }

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle = "csVerySlowRedraw=1";
      $this->ControlStyle = "csRenderAlso=MobileTheme";
      $this->ControlStyle = "csDontUseWrapperDiv=1";

      $this->_width = 200;

      $this->_ismbox = true;
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";

      $output .= bindEvents("jQuery('input[id^=\"{$this->_name}_\"]')", $this, 'click');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'tap');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('input[id^=\"{$this->_name}_\"]').next('label')", $this, 'vmousecancel');
      return $output;
   }

   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }

   function loaded()
   {
      parent::loaded();
      $this->writeTheme($this->_theme);
   }

   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();

      MHeader($this);
   }

   function dumpCSS()
   {
          //Use the style attribute to prevent adding the inline style attributes to the component
          if($this->_enhancement == "enFull" && $this->_style == "")
               $this->_style = ".";

        parent::dumpCSS();

        //reset style if needed
        if($this->_style == ".")
           $this->_style = "";
   }

   function dumpAdditionalCSS()
   {
          //Use the style attribute to prevent adding the inline style attributes to the component
          if($this->_enhancement == "enFull" && $this->_style == "")
               $this->_style = ".";

        parent::dumpAdditionalCSS();

        if($this->_enhancement == "enFull" || $this->_enhancement == "enStructure")
        {
          $itemscount =  count($this->_items);
          if($this->_orientation == "orVertical")
          {

            $width  = $this->_width;
            $height = ($itemscount) ? $this->_height / $itemscount : $this->_height;
            $margin = "";
          }
          else
          {
            $width  = ($itemscount) ? $this->_width / $itemscount : $this->_width + 1;
            $width  = round($width);
            $height = $this->_height;
            $margin = "margin-right: -1px;\n";
          }

          echo $this->readCSSDescriptor()."_table .ui-checkbox label{\n";
          if($this->isFixedSize())
          {
            echo "width: {$width}px;\n";
            echo "height: {$height}px;\n";
          }
          echo $margin;
          echo "}\n";

          if(!$this->isFixedSize())
          {
            echo $this->readCSSDescriptor()."_table,".
                $this->readCSSDescriptor()."_table td,".
                $this->readCSSDescriptor()."_table tr,".
                $this->readCSSDescriptor()."_table tbody".
                "{\n";
            echo "display: block;\n";
            echo "}\n";
          }
        }

        //reset style if needed
        if($this->_style == ".")
           $this->_style = "";
   }

   function dumpContents()
   {
      JQMDesignStart($this);
      if($this->_enhancement == "enNone")
      {
         $this->_nestedattributes['data-role'] = "none";
      }
      else
      {

        //Get the theme string
        if($this->_theme != "")
        {
          $RealTheme=RealTheme($this);
          $this->_nestedattributes = array_merge($this->_nestedattributes, $RealTheme->themeVal());
        }
         $this->_attributes['data-role'] = "controlgroup";


        if($this->isFixedSize())
        {
            $this->_attributes['data-fixedsize'] = "true";
            $this->_nestedattributes['data-fixedsize'] = "true";
        }
      }
      if($this->_orientation == "orHorizontal")
         $this->_attributes['data-type'] = "horizontal";
      else
         $this->_columns = 1;

      parent::dumpContents();

      JQMDesignEnd($this);
   }
}

/**
 * Standard form checkbox group, it inherits from CheckListBox but have specific design enhancements provided by jquery mobile.
 * Setting the Orientation in "orHorizontal" will render it as a group toggle buttons
 *
 * @example JQueryMobile/basic/mcheckboxgroup.php
 */
class MCheckBoxGroup extends CustomMCheckBoxGroup
{
   function getEnhancement()    {return $this->readenhancement();}
   function setEnhancement($value)    {$this->writeenhancement($value);}

   function getTheme()    {return $this->readTheme();}
   function setTheme($val)    {$this->writeTheme($val);}

   /**
    * Indicate the orientation of the group
    * Setting the Orientation in "orHorizontal" will render it as a group toggle buttons
    */
   function getOrientation()    {return $this->readorientation();}
   function setOrientation($value)    {$this->writeorientation($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
   function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

   function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
   function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

   function getjsOnVClick()    {return $this->readjsOnvclick();}
   function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

   function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
   function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

   function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
   function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

   function getjsOnTapHold()    {return $this->readjsOntaphold();}
   function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

   function getjsOnTap()    {return $this->readjsOntap();}
   function setjsOnTap($value)    {$this->writejsOntap($value);}
}



class CustomMIFrame extends CustomMControl
{
   protected $_source = "";

   /**
    * Defines the URL of the file/document to show inside the frame.
    * The frame, when rendered, will load the contents specified by the URL
    * set on this property, it can be an URL to internet, intranet, a file
    * on your system, etc.
    *
    * <code>
    * <?php
    * //This line sets the Frame::Source property to an external document
    * $this->Frame1->Source="http://rpcl.sourceforge.net";
    * ?>
    * </code>
    *
    * @return string
    */
   function readSource()    {return $this->_source;}
   function writeSource($value)    {$this->_source = $value;}
   function defaultSource()    {return "";}


   protected $_scrolling = "fsAuto";

   /**
    * Determines if the frame is going to have scrollbars to allow the user
    * navigate through all the content.
    *
    * fsAuto will show scrollbars when needed, that is, when the content is
    * outside the viewport of the frame. fsYes will always show scrollbars
    * and fsNo won't show any.
    *
    * fsAuto - This value tells the browser to provide scrolling devices for the frame window when necessary. This is the default value.
    * fsYes  - This value tells the browser to always provide scrolling devices for the frame window.
    * fsNo   - This value tells the browser not to provide scrolling devices for the frame window.
    *
    * @return enum (fsAuto, fsYes, fsNo)
    */
   function readScrolling()    {return $this->_scrolling;}
   function writeScrolling($value)    {$this->_scrolling = $value;}
   function defaultScrolling()    {return "fsAuto";}


   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      $this->_width = 300;
      $this->_height = 300;
   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = "";
      $output .= bindEvents("jQuery('#$this->_name').contents()", $this, 'click');
      $output .= bindEvents("jQuery('#$this->_name').contents()", $this, 'dblclick');
      $output .= bindJSEvent("jQuery('#$this->_name').contents()", $this, 'mouseup');
      $output .= bindJSEvent("jQuery('#$this->_name').contents()", $this, 'mousedown');
      $output .= bindJSEvent("jQuery('#$this->_name').contents()", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name').contents()", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name').contents()", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');

      return $output;
   }

   function dumpCSS()
   {

        if($this->_style == "")
        {
             // scroll style
            switch($this->Scrolling)
            {
                case "fsYes":
                    $scrolling = "scroll";
                    break;
                case "fsNo":
                    $scrolling = "hidden";
                    break;
                default://fsAuto
                    $scrolling = "auto";
            }
            echo "border:0;\n";
            echo "overflow: ".$scrolling.";\n";
            echo "box-sizing: border-box;\n";

            // width and height style
            echo $this->_readCSSSize();

            parent::dumpCSS();
        }

   }

   function dumpContents()
   {
      JQMDesignStart($this);
      $class = ($this->_style  != "")? "class=\"$this->StyleClass\"": "";
      $src   = ($this->_source != "")? "src=\"". str_replace(' ', '%20',$this->Source) ."\"": "";

      echo "<iframe name=\"" . $this->name . "\" id=\"" . $this->name . "\" $src  $class ></iframe>\n";
      JQMDesignEnd($this);
   }
}

class MIFrame extends CustomMIFrame
{
   function getScrolling()    {return $this->readscrolling();}
   function setScrolling($value)    {$this->writescrolling($value);}

   function getSource()    {return $this->readsource();}
   function setSource($value)    {$this->writesource($value);}

   function getjsOnClick()    {return $this->readjsOnclick();}
   function setjsOnClick($value)    {$this->writejsOnclick($value);}

   function getOnClick()    {return $this->readonclick();}
   function setOnClick($value)    {$this->writeonclick($value);}

   function getOnDblClick()    {return $this->readondblclick();}
   function setOnDblClick($value)    {$this->writeondblclick($value);}

   function getjsOnDblClick()    {return $this->readjsOndblclick();}
   function setjsOnDblClick($value)    {$this->writejsOndblclick($value);}

   function getjsOnMouseUp()    {return $this->readjsOnmouseup();}
   function setjsOnMouseUp($value)    {$this->writejsOnmouseup($value);}

   function getjsOnMouseDown()    {return $this->readjsOnmousedown();}
   function setjsOnMouseDown($value)    {$this->writejsOnmousedown($value);}

   function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
   function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

   function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
   function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

   function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
   function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

   function getjsOnSwipe()    {return $this->readjsOnswipe();}
   function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}
}

/**
 * Interactive world map powered by the Google Maps service.
 *
 * You can use the Google Maps API to modify the map from JavaScript. The map object is accessible using the global
 * variable ComponentName_map.
 *
 * @link https://developers.google.com/maps/documentation/javascript/reference
 * @link wiki://MMap
 */
class MMap extends CustomMControl
{

   protected $_zoom=5;
   protected $_address="";
   protected $_maxzoom=15;
   protected $_minzoom=1;
   protected $_draggable=true;
   protected $_longitude="";
   protected $_latitude="";
   protected $_border=true;
   protected $_mapkey="";
   protected $_showcontrols=true;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->ControlStyle = "csWebEngine=webkit";
      $this->ControlStyle="csVerySlowRedraw=1";
      $this->ControlStyle = "csDontUseWrapperDiv=1";
      $this->Width = 260;
      $this->Height = 280;
   }

   // Documented below.
   function getZoom() { return $this->readzoom(); }
   function setZoom($value) { $this->writezoom($value); }

   /**
    * Zoom level, between the MaxZoom and the MinimumZoom.
    *
    * @return int
    */
   function readZoom() { return $this->_zoom; }
   function writeZoom($value) { $this->_zoom=$value; }
   function defaultZoom() { return 5; }

   /**
    * Address to be shown at the center of the map. For example: Scotts Valley, CA.
    *
    * @return string
    */
   function getAddress() { return $this->_address; }
   function setAddress($value) { $this->_address=$value; }
   function defaultAddress() { return ""; }

   /**
    * Maximum zoom level.
    *
    * @return integer
    */
   function getMaxZoom() { return $this->_maxzoom; }
   function setMaxZoom($value) { $this->_maxzoom=$value; }
   function defaultMaxZoom() { return 15; }

   /**
    * Minimum zoom level.
    *
    * @return integer
    */
   function getMinZoom() { return $this->_minzoom; }
   function setMinZoom($value) { $this->_minzoom=$value; }
   function defaultMinZoom() { return 1; }

   /**
    * Whether the map can be dragged around (true) or not (false).
    *
    * @return boolean
    */
   function getDraggable() { return $this->_draggable; }
   function setDraggable($value) { $this->_draggable=$value; }
   function defaultDraggable() { return true; }

   /**
    * Longitude to be displayed on the map.
    *
    * @return string
    */
   function getLongitude() { return $this->_longitude; }
   function setLongitude($value) { $this->_longitude=$value; }
   function defaultLongitude() { return ""; }

   /**
    * Latitude to be displayed on the map.
    *
    * @return string
    */
   function getLatitude() { return $this->_latitude; }
   function setLatitude($value) { $this->_latitude=$value; }
   function defaultLatitude() { return ""; }

   /**
    * Google Maps API key to be used (optional).
    *
    * Using an API key, you can monitor your application's Maps API usage.
    *
    * @link https://developers.google.com/maps/documentation/javascript/tutorial#api_key How to get a Google Maps API key.
    *
    * @return string
    */
   function getMapKey() { return $this->_mapkey; }
   function setMapKey($value) { $this->_mapkey=$value; }
   function defaultMapKey() { return ""; }

   /**
    * Whether to include a border around the map (true) or not (false).
    *
    * @return boolean
    */
   function getBorder() { return $this->_border; }
   function setBorder($value) { $this->_border=$value; }
   function defaultBorder() { return true; }

   /**
    * Whether to show the map controls (true) or not (false).
    *
    * @return boolean
    */
   function getShowControls() { return $this->_showcontrols; }
   function setShowControls($value) { $this->_showcontrols=$value; }
   function defaultShowControls() { return true; }


   protected $_jsonload="";
   protected $_jsontap="";
   protected $_jsontaphold="";
   protected $_jsonzoomchange="";
   protected $_jsondrag="";
   protected $_jsondragstart="";
   protected $_jsondragend="";

   /**
    * Triggered once the map has been initialized.
    */
   function getjsOnLoad() { return $this->_jsonload; }
   function setjsOnLoad($value) { $this->_jsonload=$value; }
   function defaultjsOnLoad() { return ""; }

   /**
    * Triggered after a quick touch.
    */
   function getjsOnTap() { return $this->_jsontap; }
   function setjsOnTap($value) { $this->_jsontap=$value; }
   function defaultjsOnTap() { return ""; }

   /**
    * Triggered after a touch close to 1 second.
    */
   function getjsOnTapHold() { return $this->_jsontaphold; }
   function setjsOnTapHold($value) { $this->_jsontaphold=$value; }
   function defaultjsOnTapHold() { return ""; }

   /**
    * Triggered upon zoom changes.
    */
   function getjsOnZoomChange() { return $this->_jsonzoomchange; }
   function setjsOnZoomChange($value) { $this->_jsonzoomchange=$value; }
   function defaultjsOnZoomChange() { return ""; }

   /**
    * Triggered while the map is being dragged.
    */
   function getjsOnDrag() { return $this->_jsondrag; }
   function setjsOnDrag($value) { $this->_jsondrag=$value; }
   function defaultjsOnDrag() { return ""; }

   /**
    * Triggered when the map starts being dragged.
    */
   function getjsOnDragStart() { return $this->_jsondragstart; }
   function setjsOnDragStart($value) { $this->_jsondragstart=$value; }
   function defaultjsOnDragStart() { return ""; }

   /**
    * Triggered when the map stops being dragged.
    */
   function getjsOnDragEnd() { return $this->_jsondragend; }
   function setjsOnDragEnd($value) { $this->_jsondragend=$value; }
   function defaultjsOnDragEnd() { return ""; }

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsonload);
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonzoomchange);
      $this->dumpJSEvent($this->_jsondrag);
      $this->dumpJSEvent($this->_jsondragstart);
      $this->dumpJSEvent($this->_jsondragend);
   }

   // Documented in the parent.
   function dumpHeaderCode()
   {
      parent::dumpHeaderCode();
      MHeader($this);

      // only 1 time
      if (!defined('XJQUERYMOBILEMAPS') && (($this->ControlState & csDesigning) != csDesigning) || defined('JQMDESIGNSTART'))
      {

         ?>

         <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jquery.ui.map.js" charset="UTF-8"></script>
         <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jquery.ui.map.extensions.js" charset="UTF-8"></script>
         <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jquery.ui.map.services.js" charset="UTF-8"></script>



         <?php

         // XE2 needs this to output this two times
         if(($this->ControlState & csDesigning) != csDesigning)
            define('XJQUERYMOBILEMAPS',1);
      }
   }

   // Documented in the parent.
   function dumpJavascript()
   {
      // javascript code to allow multiple instances of google maps
      if (!defined('XJQUERYMOBILEMAPSCALLBACK')) {

         ?>


 window.gmaps_mmap_callbacks = [];


 window.gmaps_mmap_loaded = function()
 {
    for(var i = 0; i < window.gmaps_mmap_callbacks.length; i++)
    {
       window.gmaps_mmap_callbacks[i]();
    }
 }

 window.gmaps_mmap_register_callback = function(callback)
 {
    if(!(callback in  window.gmaps_mmap_callbacks))
        window.gmaps_mmap_callbacks.push(callback);
 }

 // if google maps exists call load method
if(window.google)
{
    setTimeout(window.gmaps_mmap_loaded, 0);
}
else
{
    var head= document.getElementsByTagName('head')[0];
   var script= document.createElement('script');
   script.type= 'text/javascript';
   script.src= "http://maps.google.com/maps/api/js?sensor=true&callback=gmaps_mmap_loaded<?php echo ($this->_mapkey != "")? "&key=".$this->_mapkey : "" ?>";
   script.charset = "UTF-8";
   head.appendChild(script);
}
 <?php

         define('XJQUERYMOBILEMAPSCALLBACK',1);
      }

      ?>gmaps_mmap_register_callback(<?php echo $this->Name.'_load'; ?>);

<?php


         $name    = $this->Name;
         $uiName  = $this->Name."_uiMap";
         $mapName = $this->Name."_mapwrap";
         $googleMap = $this->Name."_map";


        // exposing global vars
         $output  = "var {$uiName};\n";
         $output .= "var {$mapName};\n\n";

          // javascript function to create the map when is loaded
         $output .= "function {$this->Name}_load()\n{\n";


         // ui map attributes (zoom, maxzoom and minzoom)
         $ui_params = array();
         if($this->Zoom != "")
            $ui_params['zoom'] = (int) $this->Zoom;

         if($this->MaxZoom != "")
            $ui_params['maxZoom'] = (int) $this->MaxZoom;

         if($this->MinZoom != "")
            $ui_params['minZoom'] = (int) $this->MinZoom;

		 if(!$this->ShowControls)
			$ui_params['disableDefaultUI'] = 'true';

         $ui_params['draggable'] = $this->Draggable;

         $json = json_encode($ui_params);

         $output .= "\t$uiName = jQuery(\"#$name\").gmap({$json});\n";
         $output .= "\t$googleMap = $uiName.gmap('get', 'map');\n";
         $output .= "\t$mapName = jQuery($uiName.gmap('get', 'map'));\n";

         // map lat & lng
         if($this->Latitude != "" && $this->Longitude != "") {

            $output .= "$mapName.setCenter(new google.maps.LatLng($this->Latitude, $this->Longitude));
                        $uiName.gmap('addMarker', { 'position':  '$this->Latitude,$this->Longitude'});";

         }
         else if($this->Address != "")
         {
            $output .= "$uiName.gmap('search', {'address': '$this->Address'}, function(results){
                     if(results)
                     {
                        $uiName.gmap('get', 'map').setCenter(results[0].geometry.location);

                        $uiName.gmap('addMarker',{ 'position': results[0].geometry.location});
                        /*
                        marker.openInfoWindowHtml(address);
                        */
                     }
               });\n\n";
         }

         // in design mode DONT bind the events
         if(($this->ControlState & csDesigning)!= csDesigning)
         {
            // bind events
            if($this->_jsontap != "")
               $output .= "$uiName.addEventListener('tap',$this->_jsontap);\n";

            if($this->_jsontaphold != "")
               $output .= "$uiName.addEventListener('taphold',$this->_jsontaphold);\n";

            if($this->_jsonzoomchange != "")
               $output .= "$mapName.addEventListener('zoom_changed',$this->_jsonzoomchange);\n";

            if($this->_jsondrag != "")
               $output .= "$mapName.addEventListener('drag',$this->_jsondrag);\n";

            if($this->_jsondragstart != "")
               $output .= "$mapName.addEventListener('dragstart',$this->_jsondragstart);\n";

            if($this->_jsondragend != "")
               $output .= "$mapName.addEventListener('dragend',$this->_jsondragend);\n";

            // fires the load event
            if($this->_jsonload != "")
               $output .= "$this->_jsonload();\n";
         }

      $output .= "}\n\n";


      echo $output;

      parent::dumpJavascript();
   }

    // Documented in the parent.
    function pagecreate()
    {
        /*
         * This method is overriden as empty, since the map is loaded asynchronously and the events cannot be
         * attached right after the DOM has been loaded. A Google Maps load callback function must be used instead.
         */
        return "";
    }

    // Documented in the parent.
    function dumpAdditionalCSS()
    {
        echo $this->readCSSDescriptor()."_border{ \n";
        echo "box-sizing:border-box;\n";
        echo $this->_readCSSSize();

        // fixedsize needs the height
        if(!$this->isFixedSize() && $this->_height != "")
        {
            echo "min-height: {$this->_height}px;\n";
        }


        echo "} \n";

        // with border, apply styles to outer border
        if($this->_border)
        {
            echo $this->readCSSDescriptor()."_border{ \n";
            parent::dumpCSS();
            echo "} \n";
        }
    }

    // Documented in the parent.
    function dumpCSS()
    {
        // without border, apply styles normally
        if(!$this->_border)
        {
            parent::dumpCSS();
        }
    }

   // Documented in the parent.
   function dumpContents()
   {
        JQMDesignStart($this);

        $fixedsize = ($this->isFixedSize()) ? "data-fixedsize=\"true\"" : "";

        $wrapClass = ($this->_border)
         ? "class=\"ui-map-wrap ui-bar-c ui-corner-all ui-shadow ui-map-border\""
         : "class=\"ui-map-wrap\"";

        ?>
        <div id="<?php echo $this->Name ?>_border" <?php echo $wrapClass ?> <?php echo $fixedsize ?>  >
         <div id="<?php echo $this->Name ?>" class="ui-map"></div>
        </div>
        <?php

        JQMDesignEnd($this);
   }

}


/**
 * Superproperty grouping subproperties to limit the dates that can be picked using the control.
 */
class MDateTimePickerDateTimeOptions extends Persistent
{
    public $_control = null;

    // Documented in the parent.
    function readOwner()
    {
      return ($this->_control);
    }

    protected $_aftertoday=0;

    /**
     * Whether to forbid today's and sooner dates to be selected (true) or not (false).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readAfterToday() { return $this->_aftertoday; }
    function writeAfterToday($value) { $this->_aftertoday=$value; }
    function defaultAfterToday() { return 0; }

    protected $_beforetoday=0;

    /**
     * Whether to forbid today's and later dates to be selected (true) or not (false).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readBeforeToday() { return $this->_beforetoday; }
    function writeBeforeToday($value) { $this->_beforetoday=$value; }
    function defaultBeforeToday() { return 0; }

    protected $_nottoday=0;

    /**
     * Whether to forbid today's date to be selected (true) or not (false).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readNotToday() { return $this->_nottoday; }
    function writeNotToday($value) { $this->_nottoday=$value; }
    function defaultNotToday() { return 0; }

    protected $_maxdays="";

    /**
     * Forbid dates later than <value> days past today's date.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readMaxDays() { return $this->_maxdays; }
    function writeMaxDays($value) { $this->_maxdays=$value; }
    function defaultMaxDays() { return ""; }

    protected $_mindays="";

    /**
     * Forbid dates sooner than <value> days before today's date.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readMinDays() { return $this->_mindays; }
    function writeMinDays($value) { $this->_mindays=$value; }
    function defaultMinDays() { return ""; }

    protected $_maxyear="";

    /**
     * Forbid dates in the year <value> and any later year.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modDateBox, modFlipBox, modSlideBox.
     */
    function readMaxYear() { return $this->_maxyear; }
    function writeMaxYear($value) { $this->_maxyear=$value; }
    function defaultMaxYear() { return ""; }

    protected $_minyear="";

    /**
     * Forbid dates in the year <value> and any previous year.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modDateBox, modFlipBox, modSlideBox.
     */
    function readMinYear() { return $this->_minyear; }
    function writeMinYear($value) { $this->_minyear=$value; }
    function defaultMinYear() { return ""; }

    protected $_minhour="";

    /**
     * Forbid times in the hour <value> and any previous hour.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modTimeBox, modTimeFlipBox.
     */
    function readMinHour() { return $this->_minhour; }
    function writeMinHour($value) { $this->_minhour=$value; }
    function defaultMinHour() { return ""; }

    protected $_maxhour="";

    /**
     * Forbid times in the hour <value> and any later hour.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modTimeBox, modTimeFlipBox.
     */
    function readMaxHour() { return $this->_maxhour; }
    function writeMaxHour($value) { $this->_maxhour=$value; }
    function defaultMaxHour() { return ""; }

    protected $_minutestep=1;

    /**
     * Number of minutes to add or subtract each time the user interacts with a control to change the value of the
     * minutes.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modTimeBox, modTimeFlipBox.
     */
    function readMinuteStep() { return $this->_minutestep; }
    function writeMinuteStep($value) { $this->_minutestep=$value; }
    function defaultMinuteStep() { return 1; }

    /**
     *  Direction to round Down, Up or "Standard Rounding"
     *
     *  Modes: Slide, Time, TimeFlip
     */
    protected $_minutestepround="msStandard";

    /**
     * Direction to round the minutes.
     *
     * The possible values are:
     * - msDown. Round down.
     * - msStandard. Standard rounding.
     * - msUp. Round up.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modTimeBox, modTimeFlipBox.
     */
    function readMinuteStepRound() { return $this->_minutestepround; }
    function writeMinuteStepRound($value) { $this->_minutestepround=$value; }
    function defaultMinuteStepRound() { return "msStandard"; }

    // Documented above.
    function getAfterToday() { return $this->readaftertoday(); }
    function setAfterToday($value) { $this->writeaftertoday($value); }

    // Documented above.
    function getBeforeToday() { return $this->readbeforetoday(); }
    function setBeforeToday($value) { $this->writebeforetoday($value); }

    // Documented above.
    function getNotToday() { return $this->readnottoday(); }
    function setNotToday($value) { $this->writenottoday($value); }

    // Documented above.
    function getMaxDays() { return $this->readmaxdays(); }
    function setMaxDays($value) { $this->writemaxdays($value); }

    // Documented above.
    function getMinDays() { return $this->readmindays(); }
    function setMinDays($value) { $this->writemindays($value); }

    // Documented above.
    function getMaxYear() { return $this->readmaxyear(); }
    function setMaxYear($value) { $this->writemaxyear($value); }

    // Documented above.
    function getMinYear() { return $this->readminyear(); }
    function setMinYear($value) { $this->writeminyear($value); }

    // Documented above.
    function getMinHour() { return $this->readminhour(); }
    function setMinHour($value) { $this->writeminhour($value); }

    // Documented above.
    function getMaxHour() { return $this->readmaxhour(); }
    function setMaxHour($value) { $this->writemaxhour($value); }

    // Documented above.
    function getMinuteStep() { return $this->readminutestep(); }
    function setMinuteStep($value) { $this->writeminutestep($value); }

    // Documented above.
    function getMinuteStepRound() { return $this->readminutestepround(); }
    function setMinuteStepRound($value) { $this->writeminutestepround($value); }

    /**
     * Append the configured options to the given array the way they will be output, or create a new array with them.
     */
    function parseOptions(&$options = null)
    {
      if($options == null)
        $options = array();

      // date limiting options
      $options['afterToday']    = (bool) $this->AfterToday;
      $options['beforeToday']   = (bool) $this->BeforeToday;
      $options['notToday']      = (bool) $this->NotToday;
      $options['maxDays']       = ($this->MaxDays == "") ? false : (int) $this->MaxDays;
      $options['minDays']       = ($this->MinDays == "") ? false : (int) $this->MinDays;
      $options['maxYear']       = ($this->MaxYear == "") ? false : (int) $this->MaxYear;
      $options['minYear']       = ($this->MinYear == "") ? false : (int) $this->MinYear;
      $options['minHour']       = ($this->MinHour == "") ? false : (int) $this->MinHour;
      $options['maxHour']       = ($this->MaxHour == "") ? false : (int) $this->MaxHour;
      $options['minuteStep']    = (int) $this->Minutestep;

      switch($this->MinuteStepRound)
      {
         case "msUp":         $options['minuteStepRound'] = 1;   break;
         case "msDown":       $options['minuteStepRound'] = -1;  break;
         case "msStandard":   $options['minuteStepRound'] = 0;   break;
      }

      return $options;
    }
}


/**
 * Superproperty grouping subproperties that might be localized in internationalized applications.
 *
 * @link wiki://Internationalization_and_Localization
 */
class MDateTimePickerLocalizationOptions extends Persistent
{

    public $_control = null;

    // Documented in the parent.
    function readOwner()
    {
      return ($this->_control);
    }

    protected $_overridelocalization=1;

    /**
     * Whether to configure the control to allow localization using client-side code} (0) or to forbid it, allowing server-side localization (1).
     *
     * @link http://dev.jtsage.com/jQM-DateBox2/demos/api/i18n.html Localization using client-side code
     * @link wiki://Internationalization_and_Localization#Components_Localization Server-side localization
     */
    function readOverrideLocalization() { return $this->_overridelocalization; }
    function writeOverrideLocalization($value) { $this->_overridelocalization=$value; }
    function defaultOverrideLocalization() { return 1; }

    protected $_setdatebuttonlabel="Set Date";

    /**
     * Date button label.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readSetDateButtonLabel() { return $this->_setdatebuttonlabel; }
    function writeSetDateButtonLabel($value) { $this->_setdatebuttonlabel=$value; }
    function defaultSetDateButtonLabel() { return "Set Date"; }

    protected $_settimebuttonlabel="Set Time";

    /**
     * Time button label.
     */
    function readSetTimeButtonLabel() { return $this->_settimebuttonlabel; }
    function writeSetTimeButtonLabel($value) { $this->_settimebuttonlabel=$value; }
    function defaultSetTimeButtonLabel() { return "Set Time"; }

    protected $_setdurationbuttonlabel="Set Duration";

    /**
     * Duration button label.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modDurationBox.
     */
    function readSetDurationButtonLabel() { return $this->_setdurationbuttonlabel; }
    function writeSetDurationButtonLabel($value) { $this->_setdurationbuttonlabel=$value; }
    function defaultSetDurationButtonLabel() { return "Set Duration"; }

    protected $_caltodaybuttonlabel="Jump to Today";

    /**
     * "Jump to Today" button label.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readCalTodayButtonLabel() { return $this->_caltodaybuttonlabel; }
    function writeCalTodayButtonLabel($value) { $this->_caltodaybuttonlabel=$value; }
    function defaultCalTodayButtonLabel() { return "Jump to Today"; }

    protected $_titledatedialoglabel="Set Date";

    /**
     * Date modes fallback header label.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modFlipBox, modSlideBox.
     */
    function readTitleDateDialogLabel() { return $this->_titledatedialoglabel; }
    function writeTitleDateDialogLabel($value) { $this->_titledatedialoglabel=$value; }
    function defaultTitleDateDialogLabel() { return "Set Date"; }

    protected $_titletimedialoglabel="Set Time";

    /**
     * Time modes fallback header label.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modDurationBox, modTimeBox, modTimeFlipBox.
     */
    function readTitleTimeDialogLabel() { return $this->_titletimedialoglabel; }
    function writeTitleTimeDialogLabel($value) { $this->_titletimedialoglabel=$value; }
    function defaultTitleTimeDialogLabel() { return "Set Time"; }

    protected $_timeformat="24";//12

    /**
     * Clock format, either "12" or "24".
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modTimeBox, modTimeFlipBox.
     */
    function readTimeFormat() { return $this->_timeformat; }
    function writeTimeFormat($value) { $this->_timeformat=$value; }
    function defaultTimeFormat() { return "24"; }

    protected $_headerformat="%A, %B %-d, %Y";

    /**
     * Format for the header, if used. (See Options.UseHeader)
     */
    function readHeaderFormat() { return $this->_headerformat; }
    function writeHeaderFormat($value) { $this->_headerformat=$value; }
    function defaultHeaderFormat() { return "%A, %B %-d, %Y"; }

    protected $_tooltip="Open Date Picker";

    /**
     * Tooltip to be displayed over the button to open the picker when the mouse pointer is over it for a while.
     */
    function readTooltip() { return $this->_tooltip; }
    function writeTooltip($value) { $this->_tooltip=$value; }
    function defaultTooltip() { return "Open Date Picker"; }

    protected $_nextmonth="Next Month";

    /**
     * Tooltip to be displayed over the "Next Month" button to open the picker when the mouse pointer is over it for
     * a while.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readNextMonth() { return $this->_nextmonth; }
    function writeNextMonth($value) { $this->_nextmonth=$value; }
    function defaultNextMonth() { return "Next Month"; }

    protected $_prevmonth="Previous Month";

    /**
     * Tooltip to be displayed over the "Previous Month" button to open the picker when the mouse pointer is over it
     * for a while.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readPrevMonth() { return $this->_prevmonth; }
    function writePrevMonth($value) { $this->_prevmonth=$value; }
    function defaultPrevMonth() { return "Previous Month"; }

    protected $_usearabicindic=0;

    /**
     * Whether to use Indo-Arabic numerals (false), that is: 0, 1, 2, 3, 4, 5, 6, 7, 8, 9; or to use Arabic-Indic
     * numerals instead (true).
     */
    function readUseArabicIndic() { return $this->_usearabicindic; }
    function writeUseArabicIndic($value) { $this->_usearabicindic=$value; }
    function defaultUseArabicIndic() { return 0; }

    protected $_isrtl=0;

    /**
     * Directionality of the text, whether it is left-to-right (false) or right-to-left (true).
     */
    function readIsRTL() { return $this->_isrtl; }
    function writeIsRTL($value) { $this->_isrtl=$value; }
    function defaultIsRTL() { return 0; }

    protected $_calstartday=0;

    /**
     * First day of the week.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readCalStartDay() { return $this->_calstartday; }
    function writeCalStartDay($value) { $this->_calstartday=$value; }
    function defaultCalStartDay() { return 0; }

    protected $_clearbutton="Clear";

    /**
     * "Clear" button label
     */
    function readClearButton() { return $this->_clearbutton; }
    function writeClearButton($value) { $this->_clearbutton=$value; }
    function defaultClearButton() { return "Clear"; }

    protected $_timeoutput="%k:%M";

    /**
     * Format for the returned time.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modTimeBox, modTimeFlipBox.
     */
    function readTimeOutput() { return $this->_timeoutput; }
    function writeTimeOutput($value) { $this->_timeoutput=$value; }
    function defaultTimeOutput() { return "%k:%M"; }

    protected $_durationformat="%Dd %DA, %Dl:%DM:%DS";

    /**
     * Format for the returned duration.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modDurationBox.
     */
    function readDurationFormat() { return $this->_durationformat; }
    function writeDurationFormat($value) { $this->_durationformat=$value; }
    function defaultDurationFormat() { return "%Dd %DA, %Dl:%DM:%DS"; }

    // Documented above.
    function getOverrideLocalization() { return $this->readoverridelocalization(); }
    function setOverrideLocalization($value) { $this->writeoverridelocalization($value); }

    // Documented above.
    function getSetDateButtonLabel() { return $this->readsetdatebuttonlabel(); }
    function setSetDateButtonLabel($value) { $this->writesetdatebuttonlabel($value); }

    // Documented above.
    function getSetTimeButtonLabel() { return $this->readsettimebuttonlabel(); }
    function setSetTimeButtonLabel($value) { $this->writesettimebuttonlabel($value); }

    // Documented above.
    function getSetDurationButtonLabel() { return $this->readsetdurationbuttonlabel(); }
    function setSetDurationButtonLabel($value) { $this->writesetdurationbuttonlabel($value); }

    // Documented above.
    function getCalTodayButtonLabel() { return $this->readcaltodaybuttonlabel(); }
    function setCalTodayButtonLabel($value) { $this->writecaltodaybuttonlabel($value); }

    // Documented above.
    function getTitleDateDialogLabel() { return $this->readtitledatedialoglabel(); }
    function setTitleDateDialogLabel($value) { $this->writetitledatedialoglabel($value); }

    // Documented above.
    function getTitleTimeDialogLabel() { return $this->readtitletimedialoglabel(); }
    function setTitleTimeDialogLabel($value) { $this->writetitletimedialoglabel($value); }

    // Documented above.
    function getTimeFormat() { return $this->readtimeformat(); }
    function setTimeFormat($value) { $this->writetimeformat($value); }

    // Documented above.
    function getHeaderFormat() { return $this->readheaderformat(); }
    function setHeaderFormat($value) { $this->writeheaderformat($value); }

    // Documented above.
    function getTooltip() { return $this->readtooltip(); }
    function setTooltip($value) { $this->writetooltip($value); }

    // Documented above.
    function getNextMonth() { return $this->readnextmonth(); }
    function setNextMonth($value) { $this->writenextmonth($value); }

    // Documented above.
    function getPrevMonth() { return $this->readprevmonth(); }
    function setPrevMonth($value) { $this->writeprevmonth($value); }

    // Documented above.
    function getUseArabicIndic() { return $this->readusearabicindic(); }
    function setUseArabicIndic($value) { $this->writeusearabicindic($value); }

    // Documented above.
    function getIsRTL() { return $this->readisrtl(); }
    function setIsRTL($value) { $this->writeisrtl($value); }

    // Documented above.
    function getCalStartDay() { return $this->readcalstartday(); }
    function setCalStartDay($value) { $this->writecalstartday($value); }

    // Documented above.
    function getClearButton() { return $this->readclearbutton(); }
    function setClearButton($value) { $this->writeclearbutton($value); }

    // Documented above.
    function getTimeOutput() { return $this->readTimeOutput(); }
    function setTimeOutput($value) { $this->writeTimeOutput($value); }

    // Documented above.
    function getDurationFormat() { return $this->readDurationFormat(); }
    function setDurationFormat($value) { $this->writeDurationFormat($value); }

    /**
     * Append the configured options to the given array the way they will be output, or create a new array with them.
     */
    function parseOptions(&$options = null)
    {
      if($options == null)
        $options = null;

      // Internationalization/Localization
      if($this->OverrideLocalization)
      {
        $options['overrideSetDateButtonLabel']    = $this->SetDateButtonLabel;
        $options['overrideSetTimeButtonLabel']    = $this->SetTimeButtonLabel;
        $options['overrideSetDurationButtonLabel']= $this->SetDurationButtonLabel;
        $options['overrideCalTodayButtonLabel']   = $this->CalTodayButtonLabel;
        $options['overrideTitleDateDialogLabel']  = $this->TitleDateDialogLabel;
        $options['overrideTitleTimeDialogLabel']  = $this->TitleTimeDialogLabel;
        $options['overrideTimeFormat']      = (int) $this->Timeformat;
        $options['overrideHeaderFormat']    = $this->HeaderFormat;
        $options['overrideTooltip']         = $this->Tooltip;
        $options['overrideNextMonth']       = $this->NextMonth;
        $options['overridePrevMonth']       = $this->PrevMonth;
        $options['overrideUseArabicIndic']  = (bool) $this->UseArabicIndic;
        $options['overrideIsRTL']           = (bool) $this->IsRTL;
        $options['overrideCalStartDay']     = (int)$this->CalStartDay;
        $options['overrideClearButton']     = $this->ClearButton;
        $options['overrideTimeOutput']      = $this->TimeOutput;
        $options['overrideDurationFormat']  = $this->DurationFormat;
       }

       return $options;
    }
}


/**
 * Superproperty grouping some general-purpose subproperties to configure the control.
 */
class MDateTimePickerOptions extends Persistent
{
    public $_control = null;

    // Documented in the parent.
    function readOwner()
    {
      return ($this->_control);
    }

    protected $_mode="modCalBox";

    /**
     * Type of date and time picker to be used to get user input.
     *
     * The following modes are available:
     * - modCalBox. A calendar.
     * - modDateBox. Spin editors for day, month and year.
     * - modDurationBox. Spin editors for days, hours, minutes and seconds.
     * - modFlipBox. Drag and drop slot machine-like editor for day, month and year.
     * - modSlideBox. Slide editors for day, month and year.
     * - modTimeBox. Spin editors for hours, minutes and moment of the day (AM or PM).
     * - modTimeFlipBox. Drag and drop slot machine-like editor for hours, minutes and moment of the day (AM or PM).
     */
    function readMode() { return $this->_mode; }
    function writeMode($value) { $this->_mode=$value; }
    function defaultMode() { return "modCalBox"; }

    protected $_showinline=0;

    /**
     * Whether to display a text input field with a button to open the date and time picker (0), or to display the
     * date and time picker directly, inline (1).
     */
    function readShowInline() {return $this->_showinline; }
    function writeShowInline($value) {$this->_showinline=$value;}
    function defaultShowInline() { return 0; }

     /*
     * Use the new input display style
     */
    protected $_newstyle=1;

    function readNewStyle() { return $this->_newstyle; }
    function writeNewStyle($value) { $this->_newstyle=$value; }
    function defaultNewStyle() { return 1; }

    // COMMON OPTIONS

    protected $_lockinput=1;

    /**
     * Whether to forbid manual text input in the control (1), forcing the use of the date and time picker instead,
     * or to allow manual input of raw dates and times as text (0).
     */
    function readLockInput() { return $this->_lockinput; }
    function writeLockInput($value) { $this->_lockinput=$value; }
    function defaultLockInput() { return 1; }

    protected $_enhanceinput=1;

    /**
     * Whether to enhance numeric inputs on mobile devices (1) or not (0).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modDateBox, modTimeBox.
     */
    function readEnhanceInput() { return $this->_enhanceinput; }
    function writeEnhanceInput($value) { $this->_enhanceinput=$value; }
    function defaultEnhanceInput() { return 1; }


    // DISPLAY OPTIONS

    protected $_centerhoriz=0;

    /**
     * Whether to center the picker horizonitally in alignment with the input field (1), or not (0).
     */
    function readCenterHoriz() { return $this->_centerhoriz; }
    function writeCenterHoriz($value) { $this->_centerhoriz=$value; }
    function defaultCenterHoriz() { return 0; }

    protected $_centervert=0;

    /**
     * Whether to center the picker vertically in alignment with the input field (1), or not (0).
     */
    function readCenterVert() { return $this->_centervert; }
    function writeCenterVert($value) { $this->_centervert=$value; }
    function defaultCenterVert() { return 0; }

    protected $_transition="trPop";

    /**
     * Type of transition to display the picker.
     *
     * The possible values are:
     * - trFade. Fade.
     * - trFlip. Flip.
     * - trFlow. Flow.
     * - trNone. No transition.
     * - trPop. Pop.
     * - trSlide. Slide.
     * - trSlideDown. Slide down.
     * - trSlideFade. Slide and fade.
     * - trSlideUp. Slide up.
     * - trTurn. Turn.
     */
    function readTransition() { return $this->_transition; }
    function writeTransition($value) { $this->_transition=$value; }
    function defaultTransition() { return "trPop"; }

    protected $_useanimation=1;

    /**
     * Whether to use animations (1) or not (0).
     */
    function readUseAnimation() { return $this->_useanimation; }
    function writeUseAnimation($value) { $this->_useanimation=$value; }
    function defaultUseAnimation() { return 1; }

    protected $_usemodal=0;

    /**
     * Whether to use a background color to cover all the page but the date and time picker when the latter is opened
     * (1), or not(0).
     */
    function readUseModal() { return $this->_usemodal; }
    function writeUseModal($value) { $this->_usemodal=$value; }
    function defaultUseModal() { return 0; }

    protected $_usebutton=1;

    /**
     * Whether to show a button in the input field to open the date and time picker (1) or not (0).
     */
    function readUseButton() { return $this->_usebutton; }
    function writeUseButton($value) { $this->_usebutton=$value; }
    function defaultUseButton() { return 1; }

    protected $_usefocus=0;

    /**
     * Whether to open the date and time picker when the input field gets the focus (1) or not (0).
     */
    function readUseFocus() { return $this->_usefocus; }
    function writeUseFocus($value) { $this->_usefocus=$value; }
    function defaultUseFocus() { return 0; }

    protected $_useheader=1;

    /**
     * Whether to use a header for the date and time picker (1) or not (0).
     */
    function readUseHeader() { return $this->_useheader; }
    function writeUseHeader($value) { $this->_useheader=$value; }
    function defaultUseHeader() { return 1; }

    protected $_useclearbutton=0;

    /**
     * Whether to display a button to clear the input field in the date and time picker (1) or nor (0).
     */
    function readUseClearButton() { return $this->_useclearbutton; }
    function writeUseClearButton($value) { $this->_useclearbutton=$value; }
    function defaultUseClearButton() { return 0; }

    protected $_usesetbutton=1;

    /**
     * Whether to display a button to set the date in the date and time picker (1) or not (0).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox, modDateBox, modDurationBox, modFlipBox, modSlideBox, modTimeBox, modTimeFlipBox.
     */
    function readUseSetButton() { return $this->_usesetbutton; }
    function writeUseSetButton($value) { $this->_usesetbutton=$value; }
    function defaultUseSetButton() { return 1; }

    protected $_usetodaybutton=1;

    /**
     * Whether to display a button to go to the current date in the date and time picker (1) or not (0).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readUseTodayButton() { return $this->_usetodaybutton; }
    function writeUseTodayButton($value) { $this->_usetodaybutton=$value; }
    function defaultUseTodayButton() { return 1; }

    protected $_usecollapsedbut=0;

    /**
     * In those situations where 2 buttons would be displayed in the date and time picker, whether to collapse them
     * into a single line (1) or not (0).
     */
    function readUseCollapsedBut() { return $this->_usecollapsedbut; }
    function writeUseCollapsedBut($value) { $this->_usecollapsedbut=$value; }
    function defaultUseCollapsedBut() { return 0; }

    protected $_calshowdays=1;

    /**
     * Whether to display labels with the names of the days of the week (1) or not (0).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readCalShowDays() { return $this->_calshowdays; }
    function writeCalShowDays($value) { $this->_calshowdays=$value; }
    function defaultCalShowDays() { return 1; }

    protected $_calshowweek=0;

    /**
     * Whether to display labels with the ISO number of the weeks (1) or not (0).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readCalShowWeek() { return $this->_calshowweek; }
    function writeCalShowWeek($value) { $this->_calshowweek=$value; }
    function defaultCalShowWeek() { return 0; }

    protected $_calonlymonth=0;

    /**
     * Whether to fill the slots for the days of the previous and next months when browsing a month (false), or to
     * leave their slots empty (true).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     */
    function readCalOnlyMonth() { return $this->_calonlymonth; }
    function writeCalOnlyMonth($value) { $this->_calonlymonth=$value; }
    function defaultCalOnlyMonth() { return 0; }

    protected $_calweekmode=0;

    /**
     * When a day is selected in the calendar, whether to define the selected day as the actual value (false) or to
     * change the value to the CalWeekModeDay in the same week as the selected day
     * (true).
     *
     * For example, if this property is set to true and CalWeekModeDay to 1, selecting
     * a day in the calendar will result in the Monday of the same week getting selected instead.
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     *
     * @return bool
     */
    function readCalWeekMode() { return $this->_calweekmode; }
    function writeCalWeekMode($value) { $this->_calweekmode=$value; }
    function defaultCalWeekMode() { return 0; }

    protected $_calweekmodeday=1;

    /**
     * Day of the week to be selected when CalWeekMode is enabled (true).
     *
     * The value of this property only affects the control when Options.Mode is set to one of these values:
     * modCalBox.
     *
     * @return integer
     */
    function readCalWeekModeDay() { return $this->_calweekmodeday; }
    function writeCalWeekModeDay($value) { $this->_calweekmodeday=$value; }
    function defaultCalWeekModeDay() { return 1; }

    // Documented above.
    function getLockInput() { return $this->readlockinput(); }
    function setLockInput($value) { $this->writelockinput($value); }

    // Documented above.
    function getEnhanceInput() { return $this->_enhanceinput; }
    function setEnhanceInput($value) { $this->_enhanceinput=$value; }

    // Documented above.
    function getUseModal() { return $this->readusemodal(); }
    function setUseModal($value) { $this->writeusemodal($value); }

    // Documented above.
    function getUseButton() { return $this->readusebutton(); }
    function setUseButton($value) { $this->writeusebutton($value); }

    // Documented above.
    function getUseFocus() { return $this->readusefocus(); }
    function setUseFocus($value) { $this->writeusefocus($value); }

    // Documented above.
    function getUseHeader() { return $this->readuseheader(); }
    function setUseHeader($value) { $this->writeuseheader($value); }

    // Documented above.
    function getUseClearButton() { return $this->readuseclearbutton(); }
    function setUseClearButton($value) { $this->writeuseclearbutton($value); }

    // Documented above.
    function getUseSetButton() { return $this->readusesetbutton(); }
    function setUseSetButton($value) { $this->writeusesetbutton($value); }

    // Documented above.
    function getUseTodayButton() { return $this->readusetodaybutton(); }
    function setUseTodayButton($value) { $this->writeusetodaybutton($value); }

    // Documented above.
    function getUseCollapsedBut() { return $this->readusecollapsedbut(); }
    function setUseCollapsedBut($value) { $this->writeusecollapsedbut($value); }

    // Documented above.
    function getCalShowDays() { return $this->readcalshowdays(); }
    function setCalShowDays($value) { $this->writecalshowdays($value); }

    // Documented above.
    function getCalShowWeek() { return $this->readcalshowweek(); }
    function setCalShowWeek($value) { $this->writecalshowweek($value); }

    // Documented above.
    function getCalOnlyMonth() { return $this->readcalonlymonth(); }
    function setCalOnlyMonth($value) { $this->writecalonlymonth($value); }

    // Documented above.
    function getCalWeekMode() { return $this->readcalweekmode(); }
    function setCalWeekMode($value) { $this->writecalweekmode($value); }

    // Documented above.
    function getCalWeekModeDay() { return $this->readcalweekmodeday(); }
    function setCalWeekModeDay($value) { $this->writecalweekmodeday($value); }

    // Documented above.
    function getCenterHoriz() { return $this->readcenterhoriz(); }
    function setCenterHoriz($value) { $this->writecenterhoriz($value); }

    // Documented above.
    function getCenterVert() { return $this->readcentervert(); }
    function setCenterVert($value) { $this->writecentervert($value); }

    // Documented above.
    function getTransition() { return $this->readtransition(); }
    function setTransition($value) { $this->writetransition($value); }

    // Documented above.
    function getUseAnimation() { return $this->readuseanimation(); }
    function setUseAnimation($value) { $this->writeuseanimation($value); }

    // Documented above.
    function getMode() { return $this->readmode(); }
    function setMode($value) { $this->writemode($value); }

    // Documented above.
    function getShowInline() { return $this->readshowinline(); }
    function setShowInline($value) { $this->writeshowinline($value); }

    /**
     * Append the configured options to the given array the way they will be output, or create a new array with them.
     */
    function parseOptions(&$options = null)
    {
        if($options == null)
            $options = array();

        // show the input or picker
        if($this->ShowInline)
        {
            $options['useInline'] = true;
            $options['hideInput'] = true;
            $options['useNewStyle'] = true;
        }

        // common options
        switch($this->Mode)
        {
            case "modCalBox":       $options['mode'] = "calbox";       break;
            case "modDateBox":      $options['mode'] = "datebox";      break;
            case "modTimeBox":      $options['mode'] = "timebox";      break;
            case "modFlipBox":      $options['mode'] = "flipbox";      break;
            case "modTimeFlipBox":  $options['mode'] = "timeflipbox";  break;
            case "modSlideBox":     $options['mode'] = "slidebox";     break;
            case "modDurationBox";  $options['mode'] = "durationbox";  break;
        }


        $options['lockInput']     = (bool) $this->LockInput;
        $options['enhanceInput']  = (bool) $this->EnhanceInput;

        // display options
        $options['centerHoriz']   = (bool) $this->CenterHoriz;
        $options['centerVert']    = (bool) $this->CenterVert;
        $options['transition']    = transitionValue($this->Transition);
        $options['useAnimation']  = (bool) $this->UseAnimation;
        $options['useModal']      = (bool) $this->UseModal;
        $options['useButton']     = (bool) $this->UseButton;
        $options['useFocus']      = (bool) $this->UseFocus;

        // control options
        $options['useHeader']     = (bool) $this->UseHeader;
        $options['useClearButton']= (bool) $this->UseClearButton;
        $options['useSetButton']  = (bool) $this->UseSetButton;
        $options['useTodayButton']= (bool) $this->UseTodayButton;
        $options['useCollapsedButton'] = (bool) $this->UsecollapsedBut;
        $options['calShowDays']   = (bool) $this->CalShowDays;
        $options['calShowWeek']   = (bool) $this->CalShowWeek;
        $options['calOnlyMonth']  = (bool) $this->CalonlyMonth;
        $options['calWeekMode']   = (bool) $this->CalWeekMode;
        $options['calWeekModeDay']= (int) $this->CalWeekModeDay;

        return $options;
    }
}

/**
 * Base class for mobile controls to choose dates and times.
 */
class CustomMDateTimePicker extends CustomMEdit
{

    protected $_options=null;
    protected $_datetimeoptions=null;
    protected $_localizationoptions=null;

    protected $dataoptions = array();

    // Documented in the parent.
    function __construct($aowner = null)
    {

        $this->_options = new MDateTimePickerOptions();
        $this->_options->_control = $this;

        $this->_datetimeoptions = new MDateTimePickerDateTimeOptions();
        $this->_datetimeoptions->_control = $this;

        $this->_localizationoptions = new MDateTimePickerLocalizationOptions();
        $this->_localizationoptions->_control = $this;

        parent::__construct($aowner);

        $this->_type = "date";
    }


    // SUPERPROPERTIES

    /**
     * Superproperty grouping some general-purpose subproperties to configure the control.
     */
    function readOptions() { return $this->_options; }
    function writeOptions($value) {
        if(is_object($value))
        {
            $this->_options=$value;
        }
    }
    function defaultOptions() { return null; }
    function getOptions() { return $this->readoptions(); }
    function setOptions($value) { $this->writeoptions($value); }

    /**
     * Superproperty grouping subproperties to limit the dates that can be picked using the control.
     */
    function readDateTimeOptions() { return $this->_datetimeoptions; }
    function writeDateTimeOptions($value) {
        if(is_object($value))
        {
            $this->_datetimeoptions=$value;
        }
    }
    function defaultDateTimeOptions() { return null; }
    function getDateTimeOptions() { return $this->readdatetimeoptions(); }
    function setDateTimeOptions($value) { $this->writedatetimeoptions($value); }

    /**
     * Superproperty grouping subproperties that might be localized in internationalized applications.
     *
     * @link wiki://Internationalization_and_Localization
     */
    function readLocalizationOptions() { return $this->_localizationoptions; }
    function writeLocalizationOptions($value) {
      if(is_object($value))
      {
        $this->_localizationoptions=$value;
      }
    }
    function defaultLocalizationOptions() { return null; }

    function getLocalizationOptions() { return $this->readlocalizationoptions(); }
    function setLocalizationOptions($value) { $this->writelocalizationoptions($value); }


    // PICKER EVENTS

    protected $_jsonpickeropen="";

    /**
     * Triggered when the date and time picker is opened.
     */
    function readjsOnPickerOpen() { return $this->_jsonpickeropen; }
    function writejsOnPickerOpen($value) { $this->_jsonpickeropen=$value; }
    function defaultjsOnPickerOpen() { return ""; }

    protected $_jsonpickerclose="";

    /**
     * Triggered when the date and time picker is closed.
     */
    function readjsOnPickerClose() { return $this->_jsonpickerclose; }
    function writejsOnPickerClose($value) { $this->_jsonpickerclose=$value; }
    function defaultjsOnPickerClose() { return ""; }

    // Documented in the parent.
    function dumpJsEvents()
    {
        parent::dumpJsEvents();
        $this->dumpJSEvent($this->_jsonpickeropen);
        $this->dumpJSEvent($this->_jsonpickerclose);
    }

   /**
    * Dumps the header code needed to run the plugin.
    */
    function dumpHeaderCode()
    {
            if (!defined('XDATEBOX') && (($this->ControlState & csDesigning) != csDesigning) || defined('JQMDESIGNSTART'))
            {
                ?>
                <link rel="stylesheet" type="text/javascript" href="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/css/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.css"/>
                <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.core.js" charset="UTF-8"></script>
                <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.mode.calbox.js" charset="UTF-8"></script>
                <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.mode.datebox.js" charset="UTF-8"></script>
                <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.mode.durationbox.js" charset="UTF-8"></script>
                <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.mode.flipbox.js" charset="UTF-8"></script>
                <script type="text/javascript" src="<?php echo MOBILE_RPCL_PATH ?>/jquerymobile/js/jqm-datebox-<?php echo JQM_DATEBOX_VERSION ?>.mode.slidebox.js" charset="UTF-8"></script>

                <?php

                // XE2 needs this to output this two times
                if(($this->ControlState & csDesigning) != csDesigning)
                    define('XDATEBOX',1);
            }
    }

    // Documented in the parent.
    function dumpAdditionalCSS()
    {
        //TODO: improve this to be more generic
        parent::dumpAdditionalCSS();
		echo  $this->readCSSDescriptor() . "_outer > .ui-input-text { \n";
        echo "width: 100%;\n";
        echo "height: 100%;\n";
        echo "box-sizing: border-box;\n";
        parent::dumpCSS();
        echo "} \n";

    }

    // Documented in the parent.
    function dumpCSS()
    {
        echo "margin-top:0 !important;\n";
        echo "height:100%;\n";
        echo "width:100%;\n";
    }

    /**
     * Overriden method to generate simplequoutes
     * needed to generate valid json data
     */
    function strAttributes()
    {
        $output="";

        foreach($this->_attributes as $i=>$v)
        {
            $output.="$i='$v' ";
        }

        return $output;
    }

    // Documented in the parent.
    function dumpContents()
    {
        JQMDesignStart($this);

        // getting options
        $this->_options->parseOptions($this->dataoptions);
        $this->_datetimeoptions->parseOptions($this->dataoptions);
        $this->_localizationoptions->parseOptions($this->dataoptions);

        // getting the picker events callbacks
        if(($this->ControlState & csDesigning)!= csDesigning)
        {
            // events options ///TODO: change this if you can!!
            if($this->_jsonpickeropen != "")
                $this->dataoptions['openCallback'] = $this->_jsonpickeropen;

            if($this->_jsonpickerclose != "")
                $this->dataoptions['closeCallback'] = $this->_jsonpickerclose;

            //$this->dataoptions['useNewStyle'] = true;
        }

        // setting data attributes
        $this->_attributes['data-role'] = "datebox";
        $this->_attributes['data-options'] = json_encode($this->dataoptions);

        if($this->isFixedSize())
            $this->_attributes['data-fixedsize'] = "true";

        // Pass false to disable JQMDesignStart and JQMDesignEnd in parent class
        parent::dumpContents(false);

        JQMDesignEnd($this);
    }

}

/**
 * Control to enter a date and time.
 *
 * Use its Options.Mode property to define the type of picker to be used.
 *
 * You can use its DateTimeOptions superproperty to define client-side limits for the dates and times that can be
 * selected using the picker, and LocalizationOptions to define properties which can be customized for different
 * locales.
 */
class MDateTimePicker extends CustomMDateTimePicker
{
    /**
     *  Published properties from CustomEdit
     */

    function getDataField()                    { return $this->readDataField();   }
    function setDataField($value)              { $this->writeDataField($value);   }

    function getDataSource(){ return $this->readDataSource();}
    function setDataSource($value) {$this->writeDataSource($value); }

    function getCharCase(){ return $this->readCharCase();}
    function setCharCase($value){ $this->writeCharCase($value);}

    function getColor() { return $this->readColor();}
    function setColor($value){ $this->writeColor($value);}

    function getEnabled(){return $this->readEnabled();}
    function setEnabled($value){$this->writeEnabled($value);}

    function getFont(){return $this->readFont();}
    function setFont($value){$this->writeFont($value);}

    function getParentColor() {return $this->readParentColor();}
    function setParentColor($value){ $this->writeParentColor($value);}

    function getParentFont(){return $this->readParentFont();}
    function setParentFont($value){$this->writeParentFont($value);}

    function getParentShowHint(){return $this->readParentShowHint();}
    function setParentShowHint($value){$this->writeParentShowHint($value);}

    function getReadOnly(){return $this->readReadOnly();}
    function setReadOnly($value){$this->writeReadOnly($value);}

    function getShowHint(){return $this->readShowHint();}
    function setShowHint($value){ $this->writeShowHint($value);}

    function getStyle()             { return $this->readstyle(); }
    function setStyle($value)       { $this->writestyle($value); }

    function getTabOrder(){return $this->readTabOrder();}
    function setTabOrder($value){$this->writeTabOrder($value);}

    function getTabStop(){return $this->readTabStop(); }
    function setTabStop($value) { $this->writeTabStop($value); }

    /**
     * Default date of the control.
     *
     * @return string
     */
    function getValue() { return($this->readText()); }
    function setValue($value) { $this->writeText($value); }

    // Documented in the parent.
    function getVisible()                   { return $this->readVisible(); }
    function setVisible($value)             { $this->writeVisible($value); }

    // Documented in the parent.
    function getRequired() { return $this->readRequired(); }
    function setRequired($value) { $this->writeRequired($value); }

    // Documented in the parent.
    function getDataList() { return $this->readDataList(); }
    function setDataList($value) { $this->writeDataList($value); }

    /**
     *  Published properties from CustomMDateTimePicker
     */

    // Documented in the parent.
    function getEnhancement()    {return $this->readenhancement();}
    function setEnhancement($value)    {$this->writeenhancement($value);}

    // Documented in the parent.
    function getTheme()    {return $this->readTheme();}
    function setTheme($val)    {$this->writeTheme($val);}

    // Documented in the parent.
    function getjsOnClearTextClick()    {return $this->readjsOncleartextclick();}
    function setjsOnClearTextClick($value)    {$this->writejsOncleartextclick($value);}

    // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsOnvclick();}
    function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsOnswipe();}
    function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsOntaphold();}
    function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsOntap();}
    function setjsOnTap($value)    {$this->writejsOntap($value);}

    // Documented in the parent.
    function getjsOnPickerOpen() { return $this->readjsonpickeropen(); }
    function setjsOnPickerOpen($value) { $this->writejsonpickeropen($value); }

    // Documented in the parent.
    function getjsOnPickerClose() { return $this->readjsonpickerclose(); }
    function setjsOnPickerClose($value) { $this->writejsonpickerclose($value); }

    /**
     *  Published JS events from CustomEdit
     */

    // Documented in the parent.
    function getjsOnDragOver() { return $this->readjsondragover(); }
    function setjsOnDragOver($value) { $this->writejsondragover($value); }

    // Documented in the parent.
    function getjsOnDragStart() { return $this->readjsondragstart(); }
    function setjsOnDragStart($value) { $this->writejsondragstart($value); }

    // Documented in the parent.
    function getjsOnBlur                    () { return $this->readjsOnBlur(); }
    function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

    // Documented in the parent.
    function getjsOnChange                  () { return $this->readjsOnChange(); }
    function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

    // Documented in the parent.
    function getjsOnClick                   () { return $this->readjsOnClick(); }
    function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

    // Documented in the parent.
    function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
    function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

    // Documented in the parent.
    function getjsOnFocus                   () { return $this->readjsOnFocus(); }
    function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

    // Documented in the parent.
    function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
    function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

    // Documented in the parent.
    function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
    function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

    // Documented in the parent.
    function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
    function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

    // Documented in the parent.
    function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
    function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

    // Documented in the parent.
    function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
    function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

    // Documented in the parent.
    function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
    function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

    // Documented in the parent.
    function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
    function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

    // Documented in the parent.
    function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
    function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

    // Documented in the parent.
    function getjsOnSelect                  () { return $this->readjsOnSelect(); }
    function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }


   /**
     *  Published events from CustomEdit
     */

    // Documented in the parent.
    function getOnClick                     () { return $this->readOnClick(); }
    function setOnClick                     ($value) { $this->writeOnClick($value); }

    // Documented in the parent.
    function getOnDblClick                  () { return $this->readOnDblClick(); }
    function setOnDblClick                  ($value) { $this->writeOnDblClick($value); }

    // Documented in the parent.
    function getOnSubmit                    () { return $this->readOnSubmit(); }
    function setOnSubmit                    ($value) { $this->writeOnSubmit($value); }
}

/**
 * Base class for controls implementing a canvas for mobile applications.
 */
class CustomMCanvas extends Canvas
{
   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
    */
   function readjsOnSwipeLeft()    {return $this->_jsonswipeleft;}
   function writejsOnSwipeLeft($value)    {$this->_jsonswipeleft = $value;}
   function defaultjsOnSwipeLeft()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more in 1 second.
    */
   function readjsOnSwipe()    {return $this->_jsonswipe;}
   function writejsOnSwipe($value)    {$this->_jsonswipe = $value;}
   function defaultjsOnSwipe()    {return "";}

   /**
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }
}

/**
 * Rectangular area to draw shapes.
 *
 * To use this component, just define the type of Context you want to use, and use the OnPaint JavaScript
 * event to draw on the canvas.
 *
 * There is also a global JavaScript variable that will hold the context, so you can draw on the canvas from outside the
 * OnPaint event. That variable is called ComponentName_ctx, where ComponentName is the value of the Name
 * property for the canvas component. For example, here we use the context to clear the canvas after clicking a button:
 *
 * <code>
 * function ButtonJSClick($sender, $params)
 * {
 *   ?>
 *     // Draw a black circle on the center of the canvas.
 *     canvas = $('#ComponentName')[0];
 *     ComponentName_ctx.clearRect(0, 0, canvas.width, canvas.height);
 *   <?php
 * }
 * </code>
 *
 * @link wiki://MCanvas
 */
class MCanvas extends CustomMCanvas
{
    // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsOnvclick();}
    function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsOnswipe();}
    function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsOntaphold();}
    function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsOntap();}
    function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for components implementing a canvas to draw predefined shapes.
 */
class CustomMShape extends Shape
{

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

    // Documented in the parent.
    function dumpJsEvents()
    {
        parent::dumpJsEvents();
        $this->dumpJSEvent($this->_jsontap);
        $this->dumpJSEvent($this->_jsontaphold);
        $this->dumpJSEvent($this->_jsonswipe);
        $this->dumpJSEvent($this->_jsonswipeleft);
        $this->dumpJSEvent($this->_jsonswiperight);
        $this->dumpJSEvent($this->_jsonvmouseover);
        $this->dumpJSEvent($this->_jsonvmousedown);
        $this->dumpJSEvent($this->_jsonvmousemove);
        $this->dumpJSEvent($this->_jsonvmouseup);
        $this->dumpJSEvent($this->_jsonvclick);
        $this->dumpJSEvent($this->_jsonvmousecancel);
    }
}

/**
 * Simple geometric shape drawn on a canvas.
 *
 * You can use its Brush and Pen superproperties to customize the fill color and outline color and width of the shape.
 *
 * @link wiki://MShape
 */
class MShape extends CustomMShape
{

  // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsOnvclick();}
    function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsOnswipe();}
    function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsOntaphold();}
    function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsOntap();}
    function setjsOnTap($value)    {$this->writejsOntap($value);}

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }
}

/**
 * Base class for controls to display an image in mobile applications.
 */
class CustomMImage extends Image
{

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }
}


/**
 * Control to display an image.
 *
 * Use the ImageSource property to define the path to the target image file, or its base64 code. The control provides
 * several other properties to determine the way the image is displayed within the boundaries of the control.
 */
class MImage extends CustomMImage
{

    // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsOnvclick();}
    function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsOnswipe();}
    function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsOntaphold();}
    function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsOntap();}
    function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for controls that implement a label for mobile application.
 */
class CustomMLabel extends Label
{

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->Font->Family = 'Helvetica, Arial, sans-serif';
      $this->Font->Size = '16px';
      $this->Height = 20;
   }

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}


   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }


}

/**
 * Control to display read-only text. The text, specified through the Caption property, may contain HTML code.
 *
 * @see MEdit
 */
class MLabel extends CustomMLabel
{

    // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsOnvclick();}
    function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsOnswipe();}
    function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsOntaphold();}
    function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsOntap();}
    function setjsOnTap($value)    {$this->writejsOntap($value);}
}

/**
 * Base class for components implementing a media player for mobile applications.
 */
class CustomMMedia extends Media
{

   protected $_jsonvmouseover = "";
   protected $_jsonvmousedown = "";
   protected $_jsonvmousemove = "";
   protected $_jsonvmouseup = "";
   protected $_jsonvclick = "";
   protected $_jsonvmousecancel = "";
   protected $_jsontap = "";
   protected $_jsontaphold = "";
   protected $_jsonswipe = "";
   protected $_jsonswipeleft = "";
   protected $_jsonswiperight = "";

   /**
    * Triggered on a horizontal drag of 30px or more to the right in 1 second.
    */
   function readjsOnSwipeRight()    {return $this->_jsonswiperight;}
   function writejsOnSwipeRight($value)    {$this->_jsonswiperight = $value;}
   function defaultjsOnSwipeRight()    {return "";}

   /**
    * Triggered on a horizontal drag of 30px or more to the left in 1 second.
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
    * Triggered after a touch close to 1 second.
    */
   function readjsOnTapHold()    {return $this->_jsontaphold;}
   function writejsOnTapHold($value)    {$this->_jsontaphold = $value;}
   function defaultjsOnTapHold()    {return "";}

   /**
    * Triggered after a quick touch.
    */
   function readjsOnTap()    {return $this->_jsontap;}
   function writejsOnTap($value)    {$this->_jsontap = $value;}
   function defaultjsOnTap()    {return "";}

   /**
    * Triggered when a mouse or touch down/start or move event gets cancelled instead of finished.
    *
    * @link http://alxgbsn.co.uk/2011/12/23/different-ways-to-trigger-touchcancel-in-mobile-browsers/ Different ways to trigger touchcancel in mobile browsers, by Alex Gibson
    */
   function readjsOnVMouseCancel()    {return $this->_jsonvmousecancel;}
   function writejsOnVMouseCancel($value)    {$this->_jsonvmousecancel = $value;}
   function defaultjsOnVMouseCancel()    {return "";}

   /**
    * Triggered after a click or touch.
    *
    * This event is usually dispatched after OnVMouseUp.
    */
   function readjsOnVClick()    {return $this->_jsonvclick;}
   function writejsOnVClick($value)    {$this->_jsonvclick = $value;}
   function defaultjsOnVClick()    {return "";}

   /**
    * Triggered when a click or touch finishes because the mouse button is released or the finger is not touching the screen anymore.
    *
    * This event is usually dispatched before OnVClick.
    *
    * @see readjsOnVMouseDown()
    */
   function readjsOnVMouseUp()    {return $this->_jsonvmouseup;}
   function writejsOnVMouseUp($value)    {$this->_jsonvmouseup = $value;}
   function defaultjsOnVMouseUp()    {return "";}

   /**
    * Triggered whenever the mouse cursor moves or the touched point changes.
    */
   function readjsOnVMouseMove()    {return $this->_jsonvmousemove;}
   function writejsOnVMouseMove($value)    {$this->_jsonvmousemove = $value;}
   function defaultjsOnVMouseMove()    {return "";}

   /**
    * Triggered when a mouse button is pressed or a touch on the screen starts.
    *
    * @see readjsOnVMouseUp()
    */
   function readjsOnVMouseDown()    {return $this->_jsonvmousedown;}
   function writejsOnVMouseDown($value)    {$this->_jsonvmousedown = $value;}
   function defaultjsOnVMouseDown()    {return "";}

   /**
    * Triggered when the mouse cursor or a touch on the screen moves over the control, after being out of it.
    */
   function readjsOnVMouseOver()    {return $this->_jsonvmouseover;}
   function writejsOnVMouseOver($value)    {$this->_jsonvmouseover = $value;}
   function defaultjsOnVMouseOver()    {return "";}

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate();
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'tap');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'taphold');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipe');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swipeleft');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'swiperight');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseover');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousedown');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousemove');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmouseup');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vclick');
      $output .= bindJSEvent("jQuery('#$this->_name')", $this, 'vmousecancel');
      return $output;
   }

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();
      $this->dumpJSEvent($this->_jsontap);
      $this->dumpJSEvent($this->_jsontaphold);
      $this->dumpJSEvent($this->_jsonswipe);
      $this->dumpJSEvent($this->_jsonswipeleft);
      $this->dumpJSEvent($this->_jsonswiperight);
      $this->dumpJSEvent($this->_jsonvmouseover);
      $this->dumpJSEvent($this->_jsonvmousedown);
      $this->dumpJSEvent($this->_jsonvmousemove);
      $this->dumpJSEvent($this->_jsonvmouseup);
      $this->dumpJSEvent($this->_jsonvclick);
      $this->dumpJSEvent($this->_jsonvmousecancel);
   }
}

/**
 * Media player for audio or video files.
 *
 * In order for the component to work properly, you must provide at least a list of
 * Sources, as well as the MediaType (mtVideo by default).
 *
 * The player can display client-side playback controls (defined by the web browser), or you can
 * choose not to display controls at all, and use the JavaScript API to
 * control the playback from JavaScript, using for example your own controls.
 *
 * @link wiki://MMedia
 */
class MMedia extends CustomMMedia
{
  // Documented in the parent.
    function getjsOnVMouseOver()    {return $this->readjsOnvmouseover();}
    function setjsOnVMouseOver($value)    {$this->writejsOnvmouseover($value);}

    // Documented in the parent.
    function getjsOnVMouseMove()    {return $this->readjsOnvmousemove();}
    function setjsOnVMouseMove($value)    {$this->writejsOnvmousemove($value);}

    // Documented in the parent.
    function getjsOnVMouseDown()    {return $this->readjsOnvmousedown();}
    function setjsOnVMouseDown($value)    {$this->writejsOnvmousedown($value);}

    // Documented in the parent.
    function getjsOnVClick()    {return $this->readjsOnvclick();}
    function setjsOnVClick($value)    {$this->writejsOnvclick($value);}

    // Documented in the parent.
    function getjsOnVMouseCancel()    {return $this->readjsOnvmousecancel();}
    function setjsOnVMouseCancel($value)    {$this->writejsOnvmousecancel($value);}

    // Documented in the parent.
    function getjsOnVMouseUp()    {return $this->readjsOnvmouseup();}
    function setjsOnVMouseUp($value)    {$this->writejsOnvmouseup($value);}

    // Documented in the parent.
    function getjsOnSwipeRight()    {return $this->readjsOnswiperight();}
    function setjsOnSwipeRight($value)    {$this->writejsOnswiperight($value);}

    // Documented in the parent.
    function getjsOnSwipeLeft()    {return $this->readjsOnswipeleft();}
    function setjsOnSwipeLeft($value)    {$this->writejsOnswipeleft($value);}

    // Documented in the parent.
    function getjsOnSwipe()    {return $this->readjsOnswipe();}
    function setjsOnSwipe($value)    {$this->writejsOnswipe($value);}

    // Documented in the parent.
    function getjsOnTapHold()    {return $this->readjsOntaphold();}
    function setjsOnTapHold($value)    {$this->writejsOntaphold($value);}

    // Documented in the parent.
    function getjsOnTap()    {return $this->readjsOntap();}
    function setjsOnTap($value)    {$this->writejsOntap($value);}
}

?>
