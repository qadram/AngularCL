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

use_unit("classes.inc.php");
use_unit("controls.inc.php");
use_unit("db.inc.php");

/**
 * CustomLabel is the base class for controls that display text on a form.
 *
 * The Caption of the CustomLabel may contain HTML formatted text.
 *
 */
class CustomLabel extends GraphicControl
{
        protected $_datasource = null;
        protected $_datafield = "";
        protected $_link = "";
        protected $_linktarget = "";
        protected $_wordwrap = 1;

        protected $_onclick = null;
        protected $_ondblclick = null;


        protected $_formatasdate="";

        /**
        * This property, if set, specifies the format to apply to the Caption contents
        *
        * Use this property if the contents of the Caption are a date and you want to
        * format it according to date specifiers
        *
        * @return string
        */
        function readFormatAsDate() { return $this->_formatasdate; }
        function writeFormatAsDate($value) { $this->_formatasdate=$value; }
        function defaultFormatAsDate() { return ""; }



        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width = 75;
                $this->Height = 13;
                $this->ControlStyle="csRenderOwner=1";
                $this->ControlStyle="csRenderAlso=StyleSheet";
        }

        function init()
        {
                parent::init();

                $submitEventValue = $this->input->{$this->readJSWrapperHiddenFieldName()};

                if (is_object($submitEventValue))
                {
                        // check if the a click event has been fired
                        if ($this->_onclick != null && $submitEventValue->asString() == $this->readJSWrapperSubmitEventValue($this->_onclick))
                        {
                                $this->callEvent('onclick', array());
                        }
                        // check if the a double-click event has been fired
                        if ($this->_ondblclick != null && $submitEventValue->asString() == $this->readJSWrapperSubmitEventValue($this->_ondblclick))
                        {
                                $this->callEvent('ondblclick', array());
                        }
                }
        }

        function loaded()
        {
                parent::loaded();
                // call writeDataSource() since setDataSource() might not be implemented by the sub-class
                $this->writeDataSource($this->_datasource);

        }

        function dumpContents()
        {
                $events="";
                $alignment = "";

                if($this->Enabled==1)
                {
                        // get the string for the JS Events
                        $events = $this->readJsEvents();

                        // add or replace the JS events with the wrappers if necessary
                        $this->addJSWrapperToEvents($events, $this->_onclick,    $this->_jsonclick,    "onclick");
                        $this->addJSWrapperToEvents($events, $this->_ondblclick, $this->_jsondblclick, "ondblclick");
                }




                // get the hint attribute; returns: title="[HintText]"
                $hint = $this->HintAttribute();


                $target="";
                if (trim($this->LinkTarget)!="") $target="target=\"$this->LinkTarget\"";

                $class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";


                $draggable = ($this->_draggable) ? ' draggable="true" ' : '';
                $this->openingWrap($alignment,$hint,$events,$class, $draggable);

                $this->openingLink($alignment,$hint,$target,$events,$class, $draggable);

                if (($this->ControlState & csDesigning) != csDesigning)
                {
                        if ($this->hasValidDataField())
                        {
                                //The value to show on the field is the one from the table
                                $this->Caption = $this->readDataFieldValue();
                                // dump no hidden fields since the label is read-only
                        }
                }


                $toshow=$this->_caption;

				if (($this->ControlState & csDesigning)!=csDesigning)
                {
                	if ($this->_formatasdate!='')
                    {
       					      $time=strtotime($toshow);
       					      $toshow=date($this->_formatasdate,$time);
                    }
                }

                if ($this->_onshow != null)
                {
                        $this->callEvent('onshow', array('formattedcaption'=>$toshow));
                }
                else
                {
                        echo $toshow;
                }

                if ($this->_link != "")  echo "</a>";


                $this->closingWrap();
        }

        /**
         * Writes the initial tag for a link if a link for the control is defined.
         *
         * The link will be the content of the Link property, and the parameters ($target and $events) may contain
         * additional attribute definitions, or empty strings.
         *
         * For example, $target could contain the string "target=\"_blank\"".
         *
         * @internal
         */
        function openingLink($target,$events, $draggable)
        {
           if ($this->_link != "")  echo "<a href=\"$this->_link\" $target $events $draggable>";
        }

        /**
         * Writes the initial tag for the main division of the component if such a division is enabled
         * ($this->_divwrap).
         *
         * $alignment, $hint, $events and $class are strings optionally containing attribute definitions (they can
         * be empty).
         *
         * For example, $class could contain the string "class="\"some_class\"".
         *
         * @internal
         */
        function openingWrap($alignment,$hint,$events,$class, $draggable)
        {
                if ($this->_divwrap)
                {
                	echo "<div id=\"$this->_name\" $alignment $hint $class $draggable ";

                	if ($this->_link=="") echo "$events";

                	echo ">";
                }
                else if ($this->_style!='')
                {
                	echo "<div id=\"$this->_name\" class=\"$this->_style\" $draggable ";

                	if ($this->_link=="") echo "$events";

                	echo ">";
                }
        }

        /**
         * Writes the final tag for the main division of the component if such a division is enabled
         * ($this->_divwrap).
         *
         * @internal
         */
        function closingWrap()
        {
                if (($this->_divwrap) || ($this->_style!=''))
                {
	                echo "</div>";
                }
        }

        // Documented in the parent.
        function dumpFormItems()
        {
                // add a hidden field so we can determine which event for the label was fired
                if ($this->_onclick != null || $this->_ondblclick != null)
                {
                        $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
                        echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
                }
        }

        // Documented in the parent.
        function dumpCSS()
        {

          if ($this->Style=="")
          {
              // get the Font attributes
              echo $this->Font->FontString;

              if ((($this->ControlState & csDesigning) == csDesigning) && ($this->_designcolor != ""))
              {
                      echo "background-color: " . $this->_designcolor . ";";
              }
              else
              {
                      if ($this->_color != "")
                      {
                              echo "background-color: $this->color;\n";
                      }
              }

              echo parent::parseCSSCursor();
          }

          echo $this->_readCSSSize();

          if (!$this->_wordwrap)
          {
                  echo "white-space: nowrap;\n";
          }

          if ($this->readHidden())
          {
                if (($this->ControlState & csDesigning) != csDesigning)
                {
                        echo "visibility:hidden;\n";
                }
          }

          if ($this->_alignment != agNone)
          {
              // get the alignment of the Caption inside the <div>
              $alignment = "text-align:";
              switch ($this->_alignment)
              {
                      case agLeft :
                              $alignment .= "left";
                              break;
                      case agCenter :
                              $alignment .= "center";
                              break;
                      case agRight :
                              $alignment .= "right";
                              break;
                      case agInherit:
                              $alignment .= "inherit";
                              break;
              }
              echo $alignment . ";\n";
          }

          parent::dumpCSS();
        }

        // Documented in the parent.
        function dumpJavascript()
        {
                parent::dumpJavascript();

                if ($this->_onclick != null && !defined($this->_onclick))
                {
                        // only output the same function once;
                        // otherwise if for example two labels use the same
                        // OnClick event handler it would be outputted twice.
                        $def=$this->_onclick;
                        define($def,1);

                        // output the wrapper function
                        echo $this->getJSWrapperFunction($this->_onclick);
                }

                if ($this->_ondblclick != null && !defined($this->_ondblclick))
                {
                        $def=$this->_ondblclick;
                        define($def,1);

                        // output the wrapper function
                        echo $this->getJSWrapperFunction($this->_ondblclick);
                }
        }

        /**
        * Helper function to strip selected tags.
        * This function will also replace self-closing tags (XHTML <br /> <hr />)
        * and will work if the text contains line breaks.
        *
        * @author Bermi Ferrer @ http://www.php.net/manual/en/function.strip-tags.php
        *
        * @param string $text Text that may contain the tags to strip.
        * @param array $tags All tags that should be stripped from $text.
        * @return string Returns $text without the defined $tags.
        */
        protected function strip_selected_tags($text, $tags = array())
        {
                $args = func_get_args();
                $text = array_shift($args);
                $tags = func_num_args() > 2 ? array_diff($args,array($text))  : (array)$tags;
                foreach ($tags as $tag){
                        if( preg_match_all( '/<'.$tag.'[^>]*>([^<]*)<\/'.$tag.'>/iu', $text, $found) ){
                                $text = str_replace($found[0],$found[1],$text);
                        }
                }

                return preg_replace( '/(<('.join('|',$tags).')(\\n|\\r|.)*\/>)/iu', '', $text);
        }

        /**
        * Occurs when the user clicks the control.
        *
        * Use the OnClick event handler to respond when the user clicks the control.
        *
        * Usually OnClick occurs when the user presses and releases the left mouse button
        * with the mouse pointer over the control. This event can also occur when:
        *
        * The user selects an item in a grid, outline, list, or combo box by pressing an arrow key.
        *
        * The user presses Spacebar while a button or check box has focus.
        *
        * The user presses Enter when the active form has a default button (specified by the Default property).
        *
        * The user presses Esc when the active form has a cancel button (specified by the Cancel property).
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnClick()
        {
                return $this->_onclick;
        }
        /**
        * Occurs when the user clicks the control.
        * @param mixed $value Event handler or null to unset.
        */
        function writeOnClick($value)
        {
                $this->_onclick = $value;
        }
        function defaultOnClick()
        {
                return null;
        }

        /**
        * Occurs when the user double-clicks the control.
        *
        * Use this event to react when the user double click on the control, this event
        * is usually fired after a set of other events, like mousedown and mouseup
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnDblClick()
        {
                return $this->_ondblclick;
        }
        function writeOnDblClick($value)
        {
                $this->_ondblclick = $value;
        }
        function defaultOnDblClick()
        {
                return null;
        }

        /**
        * DataField is the fieldname to be attached to the control.
        *
        * This property allows you to show/edit information from a table column
        * using this control. To make it work, you must also assign the Datasource
        * property, which specifies the dataset that contain the fieldname to work on
        *
        * @return string
        */
        function readDataField()
        {
                return $this->_datafield;
        }
        /**
        * DataField indicates which field of the DataSource is used to fill in
        * the Caption.
        * @param string $value Data field
        */
        function writeDataField($value)
        {
                $this->_datafield = $value;
        }
        function defaultDataField()
        {
                return "";
        }

        /**
        * DataSource property allows you to link this control to a dataset containing
        * rows of data.
        *
        * To make it work, you must also assign DataField property with
        * the name of the column you want to use
        *
        * @return Datasource
        */
        function readDataSource()
        {
                return $this->_datasource;
        }
        function writeDataSource($value)
        {
                $this->_datasource=$this->fixupProperty($value);
        }
        function defaultDataSource()
        {
                return null;
        }

        /**
        * If Link is set the Caption is rendered as a link.
        *
        * Use this property if you want the label to become and HTML link so the
        * user can redirect the browser to a different page, or recall the same page
        * including some parameters
        *
        * Specify the link as an URL
        *
        * @return string
        */
        function readLink()
        {
                return $this->_link;
        }
        function writeLink($value)
        {
                $this->_link = $value;
        }
        function defaultLink()
        {
                return "";
        }

        /**
        * Target attribute when the label acts as a link.
        *
        * If Link property is set, the label will render as a link the user can
        * click. Use this property to specify the target for the contents retrieved
        * on that link.
        *
        * @link http://www.w3.org/TR/html4/present/frames.html#adef-target
        * @return string
        */
        function readLinkTarget() { return $this->_linktarget; }
        function writeLinkTarget($value) { $this->_linktarget=$value; }
        function defaultLinkTarget() { return ""; }

        /**
        * Specifies whether the label text wraps when it is too long
        * for the width of the label.
        * @return bool
        */
        function readWordWrap()
        {
                return $this->_wordwrap;
        }
        /**
        * Specifies whether the label text wraps when it is too long
        * for the width of the label.
        *
        * Note: white-space: nowrap; is applied to the <div> of the label.
        *
        * @param bool $value True if word wrap is enabled, false otherwise.
        */
        function writeWordWrap($value)
        {
                $this->_wordwrap = $value;
        }
        function defaultWordWrap()
        {
                return 1;
        }
}


/**
 * Control to display read-only text. The text, specified through the Caption property, may contain HTML code.
 *
 * @see Edit
 */
class Label extends CustomLabel
{
        /*
        * Publish the events for the Label component
        */
        function getOnClick                     () { return $this->readOnClick(); }
        function setOnClick                     ($value) { $this->writeOnClick($value); }

        function getOnDblClick                  () { return $this->readOnDblClick(); }
        function setOnDblClick                  ($value) { $this->writeOnDblClick($value); }

	    function getFormatAsDate() { return $this->readformatasdate(); }
    	function setFormatAsDate($value) { $this->writeformatasdate($value); }



        /*
        * Publish the JS events for the Label component
        */
        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }


        /*
        * Publish the properties for the Label component
        */

        function getDivWrap() { return $this->readdivwrap(); }
        function setDivWrap($value) { $this->writedivwrap($value); }



        function getAlignment()
        {
                return $this->readAlignment();
        }
        function setAlignment($value)
        {
                $this->writeAlignment($value);
        }

        function getCaption()
        {
                return $this->readCaption();
        }
        function setCaption($value)
        {
                $this->writeCaption($value);
        }

        function getColor()
        {
                return $this->readColor();
        }
        function setColor($value)
        {
                $this->writeColor($value);
        }

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

        function getDesignColor()
        {
                return $this->readDesignColor();
        }
        function setDesignColor($value)
        {
                $this->writeDesignColor($value);
        }

        function getFont()
        {
                return $this->readFont();
        }
        function setFont($value)
        {
                $this->writeFont($value);
        }

        function getLink()
        {
                return $this->readLink();
        }
        function setLink($value)
        {
                $this->writeLink($value);
        }

        function getLinkTarget()
        {
                return $this->readLinkTarget();
        }
        function setLinkTarget($value)
        {
                $this->writeLinkTarget($value);
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

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

        function getVisible()
        {
                return $this->readVisible();
        }
        function setVisible($value)
        {
                $this->writeVisible($value);
        }

        function getWordWrap()
        {
                return $this->readWordWrap();
        }
        function setWordWrap($value)
        {
                $this->writeWordWrap($value);
        }

        function getEnabled() { return $this->readenabled(); }
        function setEnabled($value) { $this->writeenabled($value); }


        // Documented in the parent.
        function getDraggable() { return $this->readdraggable(); }
        function setDraggable($value) { $this->writedraggable($value); }

        // Documented in the parent.
        function getjsOnDrag() { return $this->readjsondrag(); }
        function setjsOnDrag($value) { $this->writejsondrag($value); }

        // Documented in the parent.
        function getjsOnDragEnd() { return $this->readjsondragend(); }
        function setjsOnDragEnd($value) { $this->writejsondragend($value); }

        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDragStart() { return $this->readjsondragstart(); }
        function setjsOnDragStart($value) { $this->writejsondragstart($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }


}

// CharCase
define('ecLowerCase', 'ecLowerCase');
define('ecNormal', 'ecNormal');
define('ecUpperCase', 'ecUpperCase');

define('ceDisabled', 'ceDisabled');
define('ceOn', 'ceOn');
define('ceOff', 'ceOff');

define('ceText', 'ceText');
define('cePassword', 'cePassword');
define('ceEmail', 'ceEmail');
define('ceTelephone', 'ceTelephone');
define('ceSearch', 'ceSearch');
define('ceURL', 'ceURL');

/**
 * Base class for Edit controls.
 *
 * It allows to enter text in a single-line.
 * The Edit control only accepts plain text. All HTML tags are stripped.
 *
 */
class CustomEdit extends FocusControl
{
        protected $_onclick = null;
        protected $_ondblclick = null;
        protected $_onsubmit=null;

        protected $_jsonselect=null;

        protected $_datasource = null;
        protected $_datafield = "";
        protected $_charcase=ecNormal;

        protected $_maxlength=0;
        protected $_taborder=0;
        protected $_tabstop=1;
        protected $_text="";
        protected $_readonly=0;

        protected $_fixedwidth=0;
        protected $_fixedheight=0;

        protected $_filterinput=1;

        /**
         * Whether the input data should be filtered to prevent security issues (true) or not.
         *
         * If you set this property to false, and you do not filter the data entered by your users, you would be
         * opening the door to security issues such as cross-site scripting.
         *
         * @return boolean
         */
        function getFilterInput() { return $this->_filterinput; }
        function setFilterInput($value) { $this->_filterinput=$value; }
        function defaultFilterInput() { return 1; }

        protected $_type = ceText;

        /**
         * The type of input control, or the type of content expected in the control.
         *
         * The possible values are the following:
         * - ceEmail. An email address input.
         * - cePassword. A password input.
         * - ceSearch. Search terms input.
         * - ceTelephone. A telephone number input.
         * - ceText. A textual input. Default.
         * - ceURL. An URL input.
         *
         * In addition, a raw string for the 'type' attribute of the 'input' HTML element can be also used. For
         * example: 'range', or 'color'.
         */
        function readType() { return $this->_type; }
        function writeType($value) { $this->_type=$value; }
        function defaultType() { return ceText; }

        protected $_autocomplete=ceDisabled;

        /**
         * Whether the autocomplete feature should be enabled for the control (ceOn), disabled
         * (ceOff), or just unset (ceDisabled).
         *
         * When the feature is on (ceOn), the web browser will complete the values automatically
         * based on the values the user has entered in the past. If it is disabled (ceOff), it
         * will not.
         *
         * If the property is set to ceDisabled, it is up to the web browser whether the feature
         * is enabled or not.
         */
        function readAutocomplete() { return $this->_autocomplete; }
        function writeAutocomplete($value) { $this->_autocomplete=$value; }
        function defaultAutocomplete() { return ceDisabled; }

        // Documented in the parent.
        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width = 121;
                $this->Height = 21;
                $this->ControlStyle="csRenderOwner=1";
                $this->ControlStyle="csRenderAlso=StyleSheet";

        }

        // Documented in the parent.
        function loaded()
        {
                parent::loaded();
                // use writeDataSource() since setDataSource() might be implemented in the sub-component
                $this->writeDataSource($this->_datasource);
                $this->writeDataList($this->_datalist);
        }

        // Documented in the parent.
        function preinit()
        {
                //If there is something posted
                $this->input->disable=!$this->_filterinput;
                $submitted = $this->input->{$this->Name};
                if (!is_object($submitted)) $submitted = $this->input->{$this->Name.'_hidden'};
                $this->input->disable=false;
                if (is_object($submitted))
                {
                        //Get the value and set the text field
                        $this->_text = $submitted->asString();

                        //If there is any valid DataField attached, update it
                        $this->updateDataField($this->_text);
                }
        }

        // Documented in the parent.
        function init()
        {
                parent::init();

                $this->input->disable=!$this->_filterinput;
                $submitted = $this->input->{$this->Name};
                if (!is_object($submitted)) $submitted = $this->input->{$this->Name.'_hidden'};
                $this->input->disable=false;

                // Allow the OnSubmit event to be fired because it is not
                // a mouse or keyboard event.
                if ($this->_onsubmit != null && is_object($submitted))
                {
                        $this->callEvent('onsubmit', array());
                }

                $submitEvent = $this->input->{$this->readJSWrapperHiddenFieldName()};

                if (is_object($submitEvent) && $this->_enabled == 1)
                {
                        // check if the a click event has been fired
                        if ($this->_onclick != null && $submitEvent->asString() == $this->readJSWrapperSubmitEventValue($this->_onclick))
                        {
                                $this->callEvent('onclick', array());
                        }
                        // check if the a double-click event has been fired
                        if ($this->_ondblclick != null && $submitEvent->asString() == $this->readJSWrapperSubmitEventValue($this->_ondblclick))
                        {
                                $this->callEvent('ondblclick', array());
                        }
                }
        }

        /**
        * Get the HTMl attributes for the control that are shared by any input element.
        *
        * @internal
        *
        * @return string Inline string with attribute definitions.
        */
        protected function _getCommonAttributes()
        {
                $events = "";
                if ($this->_enabled == 1)
                {
                        // get the string for the JS Events
                        $events = $this->readJsEvents();

                        // add the OnSelect JS-Event
                        if ($this->_jsonselect != null)
                        {
                                $events .= " onselect=\"return $this->_jsonselect(event)\" ";
                        }

                        // add or replace the JS events with the wrappers if necessary
                        $this->addJSWrapperToEvents($events, $this->_onclick,    $this->_jsonclick,    "onclick");
                        $this->addJSWrapperToEvents($events, $this->_ondblclick, $this->_jsondblclick, "ondblclick");
                }

                // set enabled/disabled status
                $disabled = (!$this->_enabled) ? "disabled=\"disabled\"" : "";

                $required = ($this->_required) ? "required" : "";

                // set maxlength if bigger than 0
                $maxlength = ($this->_maxlength > 0) ? "maxlength=\"$this->_maxlength\"" : "";

                // set readonly attribute if true
                $readonly = ($this->_readonly == 1) ? "readonly" : "";

                // set tab order if tab stop set to true
                $taborder = ($this->_tabstop == 1) ? "tabindex=\"$this->_taborder\"" : "";

                $class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";

                // set draggable attribute (only draggable with published property)
                $draggable = ($this->_draggable) ? "draggable=\"true\"" : "";

                // get the hint attribute; returns: title="[HintText]"
                $hint = parent::HintAttribute();

                // get the extra attributes
                $extra=$this->strAttributes();

                $list = "";
                if (($this->ControlState & csDesigning) != csDesigning)
                {
                    $list = ($this->_datalist != null) ? "list=\"". $this->_datalist->getName() . "\"": "";
                }

                $pattern = ($this->_pattern != "") ? "pattern=\"". $this->_pattern . "\"": "";

                $placeholder = ($this->_placeholder != "") ? "placeholder=\"". $this->_placeholder . "\"": "";

                $autofocus = parent::isActiveControl();

                return "$placeholder $list $disabled $maxlength $readonly $hint $taborder $events $class $extra $pattern $required $autofocus $draggable ";
        }

        /**
         * Get the style definitions for the control that are shared by any input element.
         *
         * @internal
         *
         * @return string Returns inline CSS code.
         */
        //TODO Delete this method in the future
        protected function _getCommonStyles()        {

        }

 		/**
         * Returns the value for the 'type' attribute of the HTML 'input' element generated by this control, based
         * on the value of its Type property.
         *
         * @internal
         */
         private function parseInputType()
         {
            if ($this->type == ceTelephone)
              return strtolower(substr($this->_type, 2, 3)); //tel
            elseif ( ($this->type != ceText) && ($this->type != cePassword) && ($this->type != ceEmail) && ($this->type != ceSearch) && ($this->type != ceURL) )
              return $this->_type;
            else
             return strtolower(substr($this->_type, 2));
         }


        // Documented in the parent.
        function dumpContents()
        {

                $type = $this->parseInputType();

                $attributes = $this->_getCommonAttributes();

                if (($this->ControlState & csDesigning) != csDesigning)
                {
                        if ($this->hasValidDataField())
                        {
                                //The value to show on the field is the one from the table
                                $this->_text = $this->readDataFieldValue();

                                //Dumps hidden fields to know which is the record to update
                                $this->dumpHiddenKeyFields();
                        }
                }

                // call the OnShow event if assigned so the Text property can be changed
                if ($this->_onshow != null)
                {
                        $this->callEvent('onshow', array());
                }

                $final_value = "";
                if ($this->_text !== "")
                {
                    $avalue = $this->_text;
                    $avalue = str_replace('"','&quot;',$avalue);
                    $final_value = "value=\"$avalue\"";
                }

                echo "<input type=\"$type\" id=\"$this->_name\" name=\"$this->_name\" $final_value  $attributes />";

                if ( ( ($this->ControlState & csDesigning) != csDesigning) && ($this->_datalist != null) && ($this->_datalist->isPrinted()==false) )
                {
					          // Decode the datalist array.
                    $datalist = $this->_datalist->getItems();

                    echo "\n<datalist id=\"". $this->_datalist->getName() ."\">\n";
                    foreach ($datalist as $key => $value)
                    {
                         echo "\t <option value=\"$key\">$value</option>\n";
                    }
                    echo "</datalist>\n";

                    $this->_datalist->_setPrinted();
                }
        }

        // Documented in the parent.
        function dumpFormItems()
        {
                // add a hidden field so we can determine which event for the edit was fired
                if ($this->_onclick != null || $this->_ondblclick != null)
                {
                        $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
                        echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
                }

        		echo "<input type=\"hidden\" name=\"{$this->Name}_hidden\" value=\"$this->_text\">";
        }

        // Documented in the parent.
        function dumpJavascript()
        {
        ?>
        	function <?php echo $this->Name; ?>_updatehidden(event)
            {
            	edit=$('#<?php echo $this->Name; ?>').get(0);
                hidden=$('#<?php echo $this->Name; ?>_hidden').get(0);
                hidden.value=edit.value;
                <?php
                	if ($this->_jsonchange!='') echo "return(".$this->_jsonchange."(event));\n";
                ?>
            }
        <?php
                parent::dumpJavascript();

                if ($this->_enabled == 1)
                {
                        if ($this->_onclick != null && !defined($this->_onclick))
                        {
                                // only output the same function once;
                                // otherwise if for example two edits use the same
                                // OnClick event handler it would be outputted twice.
                                $def=$this->_onclick;
                                define($def,1);

                                // output the wrapper function
                                echo $this->getJSWrapperFunction($this->_onclick);
                        }

                        if ($this->_ondblclick != null && !defined($this->_ondblclick))
                        {
                                $def=$this->_ondblclick;
                                define($def,1);

                                // output the wrapper function
                                echo $this->getJSWrapperFunction($this->_ondblclick);
                        }

                        if ($this->_jsonselect != null)
                        {
                                $this->dumpJSEvent($this->_jsonselect);
                        }
                }
        }

        // Documented in the parent.
        function dumpCSS()
        {
                if ($this->Style=="")
                {
                      echo $this->Font->FontString;


                      if ($this->Color != "")
                      {
                          echo "background-color: " . $this->Color . ";\n";
                      }

                      // add the cursor to the style
                      if ($this->_cursor != "")
                      {
                            echo parent::parseCSSCursor();
                      }

                      // set the char case if not normal
                      if ($this->_charcase != ecNormal)
                      {
                            if ($this->_charcase == ecLowerCase)
                            {
                                    echo "text-transform: lowercase;\n";
                            }
                            else if ($this->_charcase == ecUpperCase)
                            {
                                    echo "text-transform: uppercase;\n";
                            }
                      }
                }

                if ($this->readHidden())
                {
                    if (($this->ControlState & csDesigning) != csDesigning)
                    {
                        echo "visibility:hidden;\n";
                    }
                }

                $h = $this->Height - 1;
                $w = $this->Width;

                if(!$this->_fixedwidth && $this->isFixedSize())
                  echo "width:" . $w . "px;\n";

                if(!$this->_fixedheight && $this->isFixedSize())
                  echo "height:" . $h . "px;\n";

      			parent::dumpCSS();
        }

        /**
        * Occurs when the user clicks the control.
        *
        * Use the OnClick event handler to respond when the user clicks the control.
        *
        * Usually OnClick occurs when the user presses and releases the left mouse button
        * with the mouse pointer over the control. This event can also occur when:
        *
        * The user selects an item in a grid, outline, list, or combo box by pressing an arrow key.
        *
        * The user presses Spacebar while a button or check box has focus.
        *
        * The user presses Enter when the active form has a default button (specified by the Default property).
        *
        * The user presses Esc when the active form has a cancel button (specified by the Cancel property).
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnClick()
        {
                return $this->_onclick;
        }
        /**
        * Occurs when the user clicks the control.
        * @param mixed Event handler or null if no handler is set.
        */
        function writeOnClick($value)
        {
                $this->_onclick = $value;
        }
        function defaultOnClick()
        {
                return null;
        }

        /**
        * Occurs when the user double-clicks the control.
        *
        * Use this event to react when the user double click on the control, this event
        * is usually fired after a set of other events, like mousedown and mouseup
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnDblClick()
        {
                return $this->_ondblclick;
        }
        /**
        * Occurs when the user double-clicks the control.
        * @param mixed Event handler or null if no handler is set.
        */
        function writeOnDblClick($value)
        {
                $this->_ondblclick = $value;
        }
        function defaultOnDblClick()
        {
                return null;
        }

        /**
        * Occurs when the form containing the control was submitted.
        *
        * This event is fired when the form is submitted and the control is about
        * to update itself with the information and changes made by the user in the
        * browser
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnSubmit() { return $this->_onsubmit; }
        function writeOnSubmit($value) { $this->_onsubmit=$value; }
        function defaultOnSubmit() { return null; }


        /**
        * JS Event occurs when text in the control was selected.
        *
        * Use this event to provide custom behavior with then text in the control
        * is selected
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readjsOnSelect() { return $this->_jsonselect; }
        function writejsOnSelect($value) { $this->_jsonselect=$value; }
        function defaultjsOnSelect() { return null; }


        protected $_jsoninput=null;

        /**
        * JS Event execute a JavaScript after some text has been selected in an <input> element.
        */
        function readjsOnInput() { return $this->_jsoninput; }
        function writejsOnInput($value) { $this->_jsoninput=$value; }
        function defaultjsOnInput() { return null; }



        /**
        * DataField is the fieldname to be attached to the control.
        *
        * This property allows you to show/edit information from a table column
        * using this control. To make it work, you must also assign the Datasource
        * property, which specifies the dataset that contain the fieldname to work on
        *
        * @return string
        */
        function readDataField() { return $this->_datafield; }
        /**
        * DataField indicates which field of the DataSource is used to fill in
        * the Text.
        */
        function writeDataField($value) { $this->_datafield = $value; }
        function defaultDataField() { return ""; }

        /**
        * DataSource property allows you to link this control to a dataset containing
        * rows of data.
        *
        * To make it work, you must also assign DataField property with
        * the name of the column you want to use
        *
        * @return Datasource
        */
        function readDataSource() { return $this->_datasource; }
        function writeDataSource($value)
        {
                $this->_datasource = $this->fixupProperty($value);
        }
        function defaultDataSource() { return null; }


        protected $_datalist=null;
        /**
        * The datalist element allows you to define a set of values that assist the user in providing the data you require.
        */
        function readDataList()         { return $this->_datalist; }
        function writeDataList($value)
        {
                 $this->_datalist = $this->fixupProperty($value);

        }
        function defaultDataList() { return null; }

        /**
        * Determines the case of the text within the edit control.
        * Note: When CharCase is set to ecLowerCase or ecUpperCase,
        *       the case of characters is converted as the user types them
        *       into the edit control. Changing the CharCase property to
        *       ecLowerCase or ecUpperCase changes the actual contents
        *       of the text, not just the appearance. Any case information
        *       is lost and can't be recaptured by changing CharCase to ecNormal.
        * @return enum (ecLowerCase, ecNormal, ecUpperCase)
        */
        function readCharCase() { return $this->_charcase; }
        function writeCharCase($value)
        {
                $this->_charcase=$value;
                if ($this->_charcase == ecUpperCase)
                {
                        $this->_text = strtoupper($this->_text);
                }
                else if ($this->_charcase == ecLowerCase)
                {
                        $this->_text = strtolower($this->_text);
                }
        }
        function defaultCharCase() { return ecNormal; }

        protected $_required=0;

        /**
         * If true, users must enter a value for the purposes of input validation.
         *
         * @return bool
         */
        function readRequired() { return $this->_required; }
        function writeRequired($value) { $this->_required = $value; }
        function defaultRequired() { return 0; }

        /**
        * Specifies the maximum number of characters the user can enter into
        * the edit control.
        *
        * A value of 0 indicates that there is no application-defined limit on the length.
        *
        * @return integer
        */
        function readMaxLength() { return $this->_maxlength; }
        function writeMaxLength($value) { $this->_maxlength=$value; }
        function defaultMaxLength() { return 0; }

        /**
        * Set the control to read-only mode. That way the user cannot enter
        * or change the text of the edit control.
        * @return bool
        */
        function readReadOnly() { return $this->_readonly; }
        function writeReadOnly($value) { $this->_readonly=$value; }
        function defaultReadOnly() { return 0; }

        /**
         * A number representing the position of the control in the 'focus queue'.
         *
         * For example, if a control has this property set to 4, and another control has it set to 5, the former control
         * will be reached before the latter when changing the control focus using the Tab key.
         *
         * The value of this property must be an integer between 0 and 32767.
         *
         * @return integer
         */
        function readTabOrder() { return $this->_taborder; }
        function writeTabOrder($value) { $this->_taborder=$value; }
        function defaultTabOrder() { return 0; }

        /**
        * Enable or disable the TabOrder property.
        *
        * The browser may still assign a TabOrder by itself internally.
        * This cannot be controlled by HTML.
        *
        * @return bool
        */
        function readTabStop() { return $this->_tabstop; }
        function writeTabStop($value) { $this->_tabstop=$value; }
        function defaultTabStop() { return 1; }

        /**
        * Contains the text string associated with the control.
        *
        * Use this property to specify the text the control is going to
        * store and show.
        *
        * @return string
        */
        function readText() { return $this->_text; }
        function writeText($value)
        {
                $this->_text=$value;
                //Forces case
                $this->CharCase=$this->_charcase;
        }
        function defaultText() { return ""; }

        //String, to specify valid values for the field
        protected $_pattern="";

        /**
         * A regular expression that must be matched by the content entered into the control in order for that content
         * to be considered valid.
         *
         * It must be a JavaScript regular expression.
         *
         * There is a list of common patterns at HTML5Pattern (http://html5pattern.com/).
         *
         * @link wiki://JavaScript
         */
        function readPattern() { return $this->_pattern; }
        function writePattern($value) { $this->_pattern=$value; }
        function defaultPattern() { return ""; }


        //Edit::PlaceHolder, string, to specify the placeholder text
        protected $_placeholder="";

        /**
         * Text to be displayed inside the control, until it is focused upon, which results in the text being hidden.
         */
        function readPlaceHolder() { return $this->_placeholder; }
        function writePlaceHolder($value) { $this->_placeholder=$value; }
        function defaultPlaceHolder() { return ""; }



}

/**
 * Base class that provides the properties to define a limited range of possible values, and a predefined amount for
 * increase and decrease 'steps'.
 */
class CustomRangeEdit extends CustomEdit
{

        protected $_minvalue = "";

        /**
         * Lower limit of the range of possible values.
         *
         * @see readMaxValue()
         *
         * @return integer
         */
        function readMinValue() { return $this->_minvalue; }
        function writeMinValue($value) { $this->_minvalue=$value; }
        function defaultMinValue() { return ""; }

        protected $_maxvalue = "";

        /**
         * Upper limit of the range of possible values.
         *
         * @see readMinValue()
         *
         * @return integer
         */
        function readMaxValue() { return $this->_maxvalue; }
        function writeMaxValue($value) { $this->_maxvalue=$value; }
        function defaultMaxValue() { return ""; }

        protected $_step = "";

        /**
         * Value granularity of the control, or legal number intervals.
         *
         * It can be used together with MaxValue and MinValue to
         * limit the possible input values.
         *
         * For example, if set to 2, legal values for the control would be -2, 0, 2, 4, etc.
         *
         * @return integer
         */
        function readStep() { return $this->_step; }
        function writeStep($value) { $this->_step=$value; }
        function defaultStep() { return ""; }

        /**
         * Returns the common HTML attributes for the input element of the control.
         *
         * @return string String with the attributes definition. For example: 'min="0" max="10"'.
         *
         * @internal
         */
        protected function _getCommonAttributes()
        {

            $parentCommonAttributes = parent::_getCommonAttributes();

            // set minvalue if bigger or equal than 0
            $minvalue = ( ($this->_minvalue !== "") && (((int)$this->_minvalue) >= 0) ) ? "min=\"$this->_minvalue\"" : "";

            // set maxvalue if bigger or equal than 0
            $maxvalue = ( ($this->_maxvalue !== "") && ( ((int)$this->_maxvalue) >= 0) ) ? "max=\"$this->_maxvalue\"" : "";

            // set increment if bigger than 0
            $step = ( ($this->_step !== "") && (((int)$this->_step) > 0) ) ? "step=\"$this->_step\"" : "";

            return "$minvalue $maxvalue $step $parentCommonAttributes";
        }



}

/**
 * Base class for controls implementing an input field to enter a numeric value.
 */
class CustomSpinEdit extends CustomRangeEdit
{

    // Documented in the parent.
    function __construct($aowner = null)
    {
        parent::__construct($aowner);
        $this->_width=155;
        $this->_height=35;

        $this->_type = "number";
    }

    // Documented in the parent.
    function dumpFormItems()
    {
      //For not print the hidden field for get the component value
    }

}

/**
 * Input field to enter a numeric value.
 *
 * @link wiki://SpinEdit
 *
 * @example Components/SpinEdit/spinedit.php
 */
class SpinEdit extends CustomSpinEdit
{

    /**
     * Value of the control.
     */
    function getValue() { return $this->readText(); }
    function setValue($value) { $this->writeText($value); }
    function defaultValue() {return "";}

    // Documented in the parent.
    function getMinValue() { return $this->readMinValue(); }
    function setMinValue($value) { $this->writeMinValue($value); }

    // Documented in the parent.
    function getMaxValue() { return $this->readMaxValue(); }
    function setMaxValue($value) { $this->writeMaxValue($value); }

    /**
     * Value granularity of the control, or legal number intervals.
     *
     * It can be used together with MaxValue and MinValue to
     * limit the possible input values.
     *
     * For example, if set to 2, legal values for the control would be -2, 0, 2, 4, etc.
     *
     * @return integer
     */
    function getIncrement() { return $this->readStep(); }
    function setIncrement($value) { $this->writeStep($value); }
    function defaultIncrement() {return $this->defaultStep();}

    // Documented in the parent.
    function getRequired() { return $this->readRequired(); }
    function setRequired($value) { $this->writeRequired($value); }

     /*
      * Publish the JS events for the SpinEdit component
      */

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
    function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
    function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

    // Documented in the parent.
    function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
    function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

    // Documented in the parent.
    function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
    function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

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
    function getjsOnSelect                  () { return $this->readjsOnSelect(); }
    function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }
}

/**
 * Use an Edit object to put a standard HTML edit control on a form. Edit controls
 * are used to retrieve text that users type. Edit controls can also display text to the user.
 * When only displaying text to the user, choose an edit control to allow users to select
 * text and copy it to the Clipboard. Choose a label object if the selection capabilities
 * of an edit control are not needed.
 *
 * Edit implements the generic behavior introduced in CustomEdit. Edit publishes
 * many of the properties inherited from TCustomEdit, but does not introduce any
 * new behavior. For specialized edit controls, use other descendant classes of CustomEdit
 * or derive from it.
 */
class Edit extends CustomEdit
{
        /*
        * Publish the properties for the component
        */

        function getDataField()                    { return $this->readDataField();   }
        function setDataField($value)              { $this->writeDataField($value);   }

        function getDataSource()
        {
                return $this->readDataSource();
        }
        function setDataSource($value)
        {
                $this->writeDataSource($value);
        }

        function getCharCase()
        {
                return $this->readCharCase();
        }
        function setCharCase($value)
        {
                $this->writeCharCase($value);
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

        function getMaxLength()
        {
                return $this->readMaxLength();
        }
        function setMaxLength($value)
        {
                $this->writeMaxLength($value);
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

        function getReadOnly()
        {
                return $this->readReadOnly();
        }
        function setReadOnly($value)
        {
                $this->writeReadOnly($value);
        }

        function getShowHint()
        {
                return $this->readShowHint();
        }
        function setShowHint($value)
        {
                $this->writeShowHint($value);
        }

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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

        function getText()
        {
                return($this->readText());
        }
        function setText($value)
        {
                $this->writeText($value);
        }

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
        function defaultInputType()             { return $this->defaultType(); }

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

        /*function getIsEmail() { return $this->readIsEmail(); }
        function setIsEmail($value) { $this->writeIsEmail($value); }

        function getIsSearch() { return $this->readIsSearch(); }
        function setIsSearch($value) { $this->writeIsSearch($value); }
        */
        /*
        * Publish the events for the Edit component
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

        /*
        * Publish the JS events for the Edit component
        */

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


        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }
}

/**
 * CustomMemo is the base class for memo components, which are multiline edit boxes,
 * including Memo.
 *
 * It is inherited from CustomEdit and introduces following new properties:
 * Lines, LineSeparator, Text and WordWrap
 *
 */
class CustomMemo extends CustomEdit
{

        protected $_lines = array();

        // The $_lineseparator variable should always be double quoted!!!
        protected $_lineseparator = "\n";

        // The richeditor property is here since it is used in the loaded() function.
        // loaded() needs to know how to treat the input data.
        // Note: Do not publish this variable!
        protected $_richeditor = 0;
        protected $_asspecialchars = 0;

        /**
        * If true, this property makes the memo to process text as special chars.
        *
        * @return boolean
        */
        function getAsSpecialChars() { return $this->_asspecialchars; }
        function setAsSpecialChars($value) { $this->_asspecialchars=$value; }
        function defaultAsSpecialChars() { return 0; }

        protected $_filterinput=1;

        /**
         * Whether the input data should be filtered to prevent security issues (true) or not.
         *
         * If you set this property to false, and you do not filter the data entered by your users, you would be
         * opening the door to security issues such as cross-site scripting.
         *
         * @return boolean
         */
        function getFilterInput() { return $this->_filterinput; }
        function setFilterInput($value) { $this->_filterinput=$value; }
        function defaultFilterInput() { return 1; }


        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width = 185;
                $this->Height = 89;
        }

        function preinit()
        {
                //If there is something posted
                $this->input->disable=!$this->_filterinput;
                $submitted = $this->input->{$this->Name};
                $this->input->disable=false;
                if (is_object($submitted))
                {
                        // Escape the posted string if sent from a richeditor;
                        // otherwise all tags are stripped and plain text is written to Text
                        if ($this->_asspecialchars)
                        {
                            $this->Text = ($this->_richeditor) ? $submitted->asSpecialChars() : $submitted->asString();
                        }
                        else
                        {
                            $this->Text = $submitted->asUnsafeRaw();
                        }

                        //If there is any valid DataField attached, update it
                        $this->updateDataField($this->Text);
                }
        }

        function dumpContents()
        {
                // get the common attributes from the CustomEdit
                $attributes = $this->_getCommonAttributes();

                // maxlength has to be check with some JS; it's not supported by HTML 4.0
                if ($this->_enabled && $this->_maxlength > 0)
                {
                        if ($this->_jsonkeyup != null)
                        {
                                $attributes = str_replace("onkeyup=\"return $this->_jsonkeyup(event)\"",
                                                  "onkeyup=\"return checkMaxLength(this, event, $this->_jsonkeyup)\"",
                                                  $attributes);
                        }
                        else
                        {
                                $attributes .= " onkeyup=\"return checkMaxLength(this, event, null)\"";
                        }
                }


                // if a datasource is set then get the data from there
                if (($this->ControlState & csDesigning) != csDesigning)
                {
                        if ($this->hasValidDataField())
                        {
                                //The value to show on the field is the one from the table
                                $this->Text = $this->readDataFieldValue();
                                //Dumps hidden fields to know which is the record to update
                                $this->dumpHiddenKeyFields();
                        }
                }


                // call the OnShow event if assigned so the Lines property can be changed
                if ($this->_onshow != null)
                {
                        $this->callEvent('onshow', array());
                }

                $lines = $this->Text;

                // set the cols
                if ( ($this->_cols != "") && ((int)$this->_cols > 0))  $attributes .= " cols=\"" . (int)$this->_cols . "\" wrap=\"hard\"";

                echo "<textarea id=\"$this->_name\" name=\"$this->_name\" $attributes>$lines</textarea>";

        }


        function dumpFormItems()
        {
                // add a hidden field so we can determine which event for the memo was fired
                if ($this->_onclick != null || $this->_ondblclick != null)
                {
                        $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
                        echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
                }
        }

        function writeCharCase($value)
        {
            parent::writeCharCase($value);
            $this->updateLinesCase();
        }

        /**
        * This method updates the text of the component according to the case property
        *
        * This is an internal method is called when the text must be updated to
        * the case property
        *
        */
        function updateLinesCase()
        {
          $this->writeText($this->readText());
        }

        function dumpJavascript()
        {
                parent::dumpJavascript();

                // only add this function once
                if (!defined('checkMaxLength') && $this->_enabled && $this->_maxlength > 0)
                {
                        define('checkMaxLength', 1);

                        echo "
function checkMaxLength(obj, event, onKeyUpFunc){
  var maxlength = obj.getAttribute ? parseInt(obj.getAttribute(\"maxlength\")) : \"\";
  if (obj.getAttribute && obj.value.length > maxlength)
    obj.value = obj.value.substring(0, maxlength);
  if (onKeyUpFunc != null)
    onKeyUpFunc(event);
}
";
                }
        }

        /**
        * Add a new line to the Memo. Calls AddLine().
        * @param $line string The content of the new line.
        * @return integer Returns the number of lines defined.
        */
        function Add($line)
        {
                return $this->AddLine($line);
        }
        /**
        * Add a new line to the Memo
        * @param $line string The content of the new line.
        * @return integer Returns the number of lines defined.
        */
        function AddLine($line)
        {
                if ($this->CharCase==ecLowerCase) $line=strtolower($line);
                else if ($this->CharCase==ecUpperCase) $line=strtoupper($line);

                end($this->_lines);
                $this->_lines[] = $line;
                return count($this->_lines);
        }

        /**
        * Deletes all text (lines) from the memo control.
        */
        function Clear()
        {
                $this->Lines = array();
        }

        /**
        * Converts the text of the Lines property into way which can be used
        * in the HTML output.
        * Please have a look at the PHP function nl2br.
        * @return string Returns the Text property with '<br />'
        *                inserted before all newlines.
        */
        function LinesAsHTML()
        {
                return nl2br($this->Text);
        }

        /**
        * LineSeparator is used in the Text property to convert a string into
        * an array and back.
        * Note: Escaped character need to be in a double-quoted string.
        *       e.g. "\n"
        *       See <a href="http://www.php.net/manual/en/language.types.string.php">http://www.php.net/manual/en/language.types.string.php</a>
        * @link http://www.php.net/manual/en/language.types.string.php
        * @return string
        */
        function readLineSeparator() { return $this->_lineseparator; }
        function writeLineSeparator($value) { $this->_lineseparator = $value; }

        /**
        * Contains the individual lines of text in the memo control.
        * Lines is an array, so the PHP array manipulation functions may be used.
        *
        * Note: Do not manipulate the Lines property like this:
        *       $this->Memo1->Lines[] = "add new line";
        *       Various versions of PHP implement the behavior of this differently.
        *       Use following code:
        *       $lines = $this->Memo1->Lines;
        *       $lines[] = "new line";          // more lines may be added
        *       $this->Memo1->Lines = $lines;
        * @return array
        */
        function readLines()
        {
            return($this->_lines);
        }
        function writeLines($value)
        {
                if (is_array($value))
                {
                        $this->_lines = $value;
                }
                else
                {
                        $this->_lines = (empty($value)) ? array() : array($value);
                }
                $this->updateLinesCase();
        }
        function defaultLines() { return array(); }

        /**
        * Text property allows read and write the contents of Lines in a string
        * separated by LineSeparator.
        * @return string
        */
        function readText()
        {
                return(implode($this->_lineseparator, $this->getLines()));
        }
        function writeText($value)
        {
                if (empty($value))
                {
                        $this->Clear();
                }
                else
                {
                      if ($this->CharCase==ecLowerCase) $value=strtolower($value);
                      else if ($this->CharCase==ecUpperCase) $value=strtoupper($value);


                        $lines = explode("$this->_lineseparator", $value);

                        if (is_array($lines))
                        {
                                $this->_lines=$lines;
                        }
                        else
                        {
                                $this->_lines=array($value);
                        }

                }
        }

        protected $_cols = "";
  		  /**
  		   *	Set the columns
  			*
  			*/
  			function readCols() { return $this->_cols; }
  			function writeCols($value) { $this->_cols=$value; }
  			function defaultCols() { return ""; }

}


/**
 * Memo is a wrapper for an HTML multiline edit control.
 *
 * Memo publishes many of the properties inherited from CustomMemo,
 * but does not introduce any new behavior. For specialized memo controls,
 * use other descendant classes of CustomMemo (e.g. RichEdit) or derive from it.
 */
class Memo extends CustomMemo
{
        /*
        * Publish the events for the Memo component
        */
        function getOnClick                     () { return $this->readOnClick(); }
        function setOnClick                     ($value) { $this->writeOnClick($value); }

        function getOnDblClick                  () { return $this->readOnDblClick(); }
        function setOnDblClick                  ($value) { $this->writeOnDblClick($value); }

        function getOnSubmit                    () { return $this->readOnSubmit(); }
        function setOnSubmit                    ($value) { $this->writeOnSubmit($value); }

        /*
        * Publish the JS events for the Memo component
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

        function getjsOnSelect                  () { return $this->readjsOnSelect(); }
        function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }


        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }



        /*
        * Publish the properties for the Memo component
        */

        function getColor()
        {
                return $this->readColor();
        }
        function setColor($value)
        {
                $this->writeColor($value);
        }

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

        function getLines()
        {
                return($this->readLines());
        }
        function setLines($value)
        {
                $this->writeLines($value);
        }

        function getMaxLength()
        {
                return $this->readMaxLength();
        }
        function setMaxLength($value)
        {
                $this->writeMaxLength($value);
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

        function getReadOnly()
        {
                return $this->readReadOnly();
        }
        function setReadOnly($value)
        {
                $this->writeReadOnly($value);
        }

        function getShowHint()
        {
                return $this->readShowHint();
        }
        function setShowHint($value)
        {
                $this->writeShowHint($value);
        }

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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

		  function getCols()
        {
                return $this->readCols();
        }
        function setCols($value)
        {
                $this->writeCols($value);
        }
}

/**
 * Base class for Listbox controls, such as ListBox and ComboBox.
 *
 * ListBox displays a collection of items in a scrollable list.
 *
 */
class CustomListBox extends CustomMultiSelectListControl
{
        public $_items = array();
        protected $_selitems = array();

        protected $_onchange = null;
        protected $_onclick = null;
        protected $_ondblclick = null;
        protected $_onsubmit = null;

        protected $_datasource = null;
        protected $_datafield = "";
        protected $_size = 4;
        protected $_sorted = 0;
        protected $_taborder=0;
        protected $_tabstop=1;

        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Clear();

                $this->Width = 185;
                $this->Height = 89;
                $this->ControlStyle="csRenderOwner=1";
                $this->ControlStyle="csRenderAlso=StyleSheet";
        }

        function loaded()
        {
                parent::loaded();

                $this->writeDataSource($this->_datasource);
        }

        function preinit()
        {
                $submitted = $this->input->{$this->Name};

                if (is_object($submitted))
                {
                        $this->ClearSelection();
                        if ($this->_multiselect == 1)
                        {
                                $this->_selitems = $submitted->asStringArray();

                        }
                        else
                        {

                                $changed = ($this->_itemindex != $submitted->asString());
                                // the ItemIndex might be an integer or a string,
                                // so let's get a string
                                $this->_itemindex = $submitted->asString();

                                // only update the data field if the item index was changed
                                if ($changed)
                                {
                                        // following somehow does not work here:
                                        //   if (array_key_exists($this->_itemindex, $this->_items)) { $this->updateDataField($this->_items[$this->_itemindex]); }
                                        // so let's do it like this...
                                        foreach ($this->_items as $key => $item)
                                        {

                                                if ((is_array($item) && $item['Key']==$this->_itemindex) || (!is_array($item) && $key == $this->_itemindex) )
                                                {
                                                        //If there is any valid DataField attached, update it
                                                        if(is_array($item))
                                                          $value=$item['Value'];
                                                        else
                                                          $value=$item;

                                                        $this->updateDataField($value);
                                                }
                                        }
                                }
                        }
                }
        }

        function init()
        {
                parent::init();

                $submitted = $this->input->{$this->Name};

                // Allow the OnSubmit event to be fired because it is not
                // a mouse or keyboard event.
                if (is_object($submitted))
                {
                        if ($this->_onsubmit != null)
                        {
                                $this->callEvent('onsubmit', array());
                        }
                }

                $submitEvent = $this->input->{$this->readJSWrapperHiddenFieldName()};

                if (is_object($submitEvent) && $this->_enabled == 1)
                {
                        // check if the a click event has been fired
                        if ($this->_onclick != null && $submitEvent->asString() == $this->readJSWrapperSubmitEventValue($this->_onclick))
                        {
                                $this->callEvent('onclick', array());
                        }
                        // check if the a double-click event has been fired
                        if ($this->_ondblclick != null && $submitEvent->asString() == $this->readJSWrapperSubmitEventValue($this->_ondblclick))
                        {
                                $this->callEvent('ondblclick', array());
                        }
                        // check if the a change event has been fired
                        if ($this->_onchange != null && $submitEvent->asString() == $this->readJSWrapperSubmitEventValue($this->_onchange))
                        {
                                $this->callEvent('onchange', array());
                        }
                }
        }


        function dumpContents()
        {
                $events = "";
                if ($this->_enabled == 1)
                {
                        // get the string for the JS Events
                        $events = $this->readJsEvents();

                        // add or replace the JS events with the wrappers if necessary
                        $this->addJSWrapperToEvents($events, $this->_onclick,    $this->_jsonclick,    "onclick");
                        $this->addJSWrapperToEvents($events, $this->_ondblclick, $this->_jsondblclick, "ondblclick");
                        $this->addJSWrapperToEvents($events, $this->_onchange,   $this->_jsonchange,   "onchange");
                }

                // set enabled/disabled status
                $enabled = (!$this->_enabled) ? "disabled=\"disabled\"" : "";

                // multi-select
                $multiselect = ($this->_multiselect == 1) ? "multiple=\"multiple\"" : "";
                // if multi-select then the name needs to have [] to indicate it will send an array
                $name = ($this->_multiselect == 1) ? "$this->_name[]" : $this->_name;

                // set tab order if tab stop set to true
                $taborder = ($this->_tabstop == 1) ? "tabindex=\"$this->_taborder\"" : "";

                // get the hint attribute; returns: title="[HintText]"
                $hint = $this->HintAttribute();

                $class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";

                if (($this->ControlState & csDesigning) != csDesigning)
                {
                        if ($this->hasValidDataField())
                        {
                                //check if the value of the current data-field is in the itmes array as value
                                $val = $this->readDataFieldValue();
                                // get the corresponding key to the value read from the data source
                                  /*if (($key = array_search($val, $this->_items)) !== FALSE)
                                  {
                                        // if an item was found the overwrite the itemindex
                                        $this->_itemindex = $key;
                                  } */
                                foreach($this->_items as $i=>$v)
                                {

                                  if ($v==$val || (is_array($v) && $v['Value']==$val) )
                                  {
                                        // if an item was found the overwrite the itemindex
                                        if(is_array($v))
                                          $this->_itemindex=$v['Key'];
                                        else
                                          $this->_itemindex = $i;
                                  }
                                }
                                //Dumps hidden fields to know which is the record to update
                                $this->dumpHiddenKeyFields();
                        }
                }

                // call the OnShow event if assigned so the Items property can be changed
                if ($this->_onshow != null)
                {
                        $this->callEvent('onshow', array());
                }

                //get the extra attributes
                $attributes=$this->strAttributes();

                $autofocus = parent::isActiveControl();

                echo "<select name=\"$name\" id=\"$this->_name\" size=\"$this->_size\" $enabled $multiselect $taborder $hint $events $attributes $class $autofocus>";

                if (is_array($this->_items))
                {
                        reset($this->_items);
                        $opengroup="";
                        $elements=0;
                        foreach ($this->_items as $key => $value)
                        {
                          //element counter
                          $elements++;

                          //If it is an array then the value isn't the value
                          if(is_array($value))
                          {
                            $item=$value['Value'];
                            $key=$value['Key'];
                            $group=$value['Group'];
                          }
                          else
                          {
                            $item=$value;
                            $group="";
                          }

                          if ($key == $this->_itemindex || ($this->_multiselect == true && in_array($key, $this->_selitems)))
                          {
                            $this->_nestedattributes[$key]=array("selected"=>"selected");
                          }
                          //htmlentities removed
                          $item=str_replace('<','&lt;',$item);
                          $item=str_replace('>','&gt;',$item);

                          //get the extra attributes for each nested element
                          $nestedattributes=$this->strNestedAttributes($key);

                          //now the grouping
                          if($group!=$opengroup)
                          {
                            if($opengroup!="")
                              echo "</optgroup>\n";

                            echo "\n<optgroup label=\"$group\">\n";
                          }
                          $opengroup=$group;

                          echo "\t<option value=\"$key\" $nestedattributes >$item</option>\n";

                          //we must close the last optgroup if any
                          if($elements==count($this->_items) && $opengroup!="")
                            echo "</optgroup>\n";
                        }
                }
                echo "</select>";


        }

        function dumpFormItems()
        {
                // add a hidden field so we can determine which listbox fired the event
                if ($this->_onclick != null || $this->_onchange != null || $this->_ondblclick != null)
                {
                        $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
                        echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
                }
        }

        function dumpCSS()
        {
            if ($this->Style=="")
            {
                    echo $this->Font->FontString;

                    if ($this->Color != "")
                    {
                            echo "background-color: " . $this->Color . ";\n";
                    }

                    // add the cursor to the style
                    if ($this->_cursor != "")
                    {
                           echo parent::parseCSSCursor();
                    }
            }

            $h = $this->Height - 2;
            $w = $this->Width;

            //TODO: fix this with boxsizing?
            if($this->isFixedSize())
            {
                echo "height:" . $h . "px;\n";
                echo "width:" . $w . "px;\n";
            }


            if ($this->readHidden())
            {
                  if (($this->ControlState & csDesigning) != csDesigning)
                  {
                          echo "visibility:hidden;\n";
                  }
            }
            parent::dumpCSS();
        }



        /*
        * Write the Javascript section to the header
        */
        function dumpJavascript()
        {
                parent::dumpJavascript();

                if ($this->_enabled == 1)
                {
                        if ($this->_onclick != null && !defined($this->_onclick))
                        {
                                // only output the same function once;
                                // otherwise if for example two buttons use the same
                                // OnClick event handler it would be outputted twice.
                                $def=$this->_onclick;
                                define($def,1);

                                // output the wrapper function
                                echo $this->getJSWrapperFunction($this->_onclick);
                        }
                        if ($this->_ondblclick != null && !defined($this->_ondblclick))
                        {
                                $def=$this->_ondblclick;
                                define($def,1);

                                // output the wrapper function
                                echo $this->getJSWrapperFunction($this->_ondblclick);
                        }
                        if ($this->_onchange != null && !defined($this->_onchange))
                        {
                                $def=$this->_onchange;
                                define($def,1);

                                // output the wrapper function
                                echo $this->getJSWrapperFunction($this->_onchange);
                        }
                }
        }


        /**
        * Returns the number of items stored in the object
        *
        * Use this property to get the number of items the control stores, use
        * addItem() to add new items to the control and clear() to remove all of
        * them.
        *
        * @return integer
        */
        function readCount()
        {
                return count($this->_items);
        }

        /**
        * Specify which item is selected on the list
        *
        * Use this property to get/set the index of the item in the
        * control that is selected. Use it at design-time to specify the
        * default item selection and use it in run-time to get the
        * user selection.
        *
        * @return integer
        */
        function readItemIndex()
        {
                // Return the first item of the selitems only if
                // the itemindex is -1 and multiselect is enabled and there
                // are some values selected.
                if ($this->_itemindex == -1 && $this->_multiselect == 1 && $this->SelCount > 0)
                {
                        reset($this->_selitems);
                        return key($this->_selitems);
                }
                else
                {
                        return $this->_itemindex;
                }
        }
        function writeItemIndex($value)
        {
                $this->_itemindex = $value;
                // if multi-select then also add it to the selected array
                if ($this->_multiselect == 1)
                {
                        $this->writeSelected($value, true);
                }
        }
        function defaultItemIndex()
        {
                return -1;
        }

        /**
        * Adds an item to the listbox
        *
        * Use this method to add an item to the listbox, items can contain
        * object pointers and also specify the key of the item in the items array.
        *
        * @param string $item Caption of the item to add
        * @param object $object Object to add
        * @param string $itemkey Key of the item in the items array
        *
        * @return integer Number of items in the control
        */
        function AddItem($item, $object = null, $itemkey = null)
        {
                if ($object != null)
                {
                        throw new Exception('Object functionallity for ListBox is not yet implemented.');
                }

                //Set the array to the end
                end($this->_items);

                //Adds the item as the last one
                if ($itemkey != null)
                {
                        $this->_items[$itemkey] = $item;
                }
                else
                {
                        $this->_items[] = $item;
                }

                if ($this->_sorted == 1)
                {
                        $this->sortItems();
                }

                return($this->Count);
        }

        /**
        * This method clear listbox
        *
        * Use this method to clear the items in the listbox and also clear
        * the items selected.
        *
        */
        function Clear()
        {
                $this->_items = array();
                $this->_selitems = array();
        }

        /**
        * Clears selected items in the listbox
        *
        * Use this method when you want to reset the selection of items in the
        * listbox. If multiselection is enabled, all selected items become unselected.
        *
        */
        function ClearSelection()
        {
                if ($this->_multiselect == 1)
                {
                        $this->_selitems = array();
                }
                $this->_itemindex = -1;
        }

        /**
        * Select all items in the control
        *
        * Use this method to include all items in the control as selected.
        * To make it work, MultiSelect property must be set to true.
        *
        */
        function SelectAll()
        {
                if ($this->_multiselect == 1)
                {
                        $this->_selitems = array_keys($this->_items);
                }
        }

        // Documented in the parent.
        function readSelCount()
        {
                if ($this->_multiselect == 1)
                {
                        return count($this->_selitems);
                }
                else
                {
                        return ($this->_itemindex != -1) ? 1 : 0;
                }
        }
        /**
        * Determines whether the user can select more than one element at a time.
        *
        * Use this property to allow the user to select several items at once.
        *
        * <code>
        * <?php
        *       function Button1Click($sender, $params)
        *       {
        *                echo "Number of selected items:".$this->ListBox1->SelCount."<br>";
        *
        *                $items=$this->ListBox1->Items;
        *
        *                reset($items);
        *                while(list($key, $val)=each($items))
        *                {
        *                        if ($this->ListBox1->readSelected($key))
        *                        {
        *                                echo "Item selected: $key => $val<br>";
        *                        }
        *                }
        *       }
        * ?>
        * </code>
        *
        * Note: MultiSelect does not work if a data source is assigned.
        *
        * @return bool
        */
        function readMultiSelect()
        {
                return $this->_multiselect;
        }
        function writeMultiSelect($value)
        {
                if ($this->_multiselect == 1 && $value == false)
                {
                        $this->ClearSelection();
                }
                $this->_multiselect = $value;

                if ($this->_multiselect == 1)
                {
                        // unset data source if multi select is enabled
                        $this->writeDataSource(null);
                }
        }
        function defaultMultiSelect()
        {
                return 0;
        }
        /*
        * </Implementation of functions from super-class>
        */

        /**
        * Checks if $index is selected.
        * @param mixed $index Index to be checked.
        * @return bool Returns true if $index is selected.
        */
        function readSelected($index)
        {
                if ($this->_multiselect)
                {
                        return in_array($index, $this->_selitems);
                }
                else
                {
                        return $index == $this->_itemindex;
                }
        }
        /**
        * Select or unselect a specific item.
        * @param mixed $index Key or index of the item to select.
        * @param bool $value True if selected, otherwise false.
        */
        function writeSelected($index, $value)
        {
                if ($this->_multiselect == 1)
                {
                        // add it to the selitems
                        if ($value)
                        {
                                // if the index does not already exist
                                if (!in_array($index, $this->_selitems))
                                {
                                        $this->_selitems[] = $index;
                                }
                        }
                        // remove the index from the selitems
                        else
                        {
                                $this->_selitems = array_diff($this->_selitems, array($index));
                        }
                }
                else
                {
                        $this->_itemindex = ($value) ? $index : -1;
                }
        }

        /**
        * Sort the items array.
        */
        private function sortItems()
        {
                // keep the keys when sorting the array (sort does not keep the keys)
                asort($this->_items);
        }



        /**
        * Occurs when the user changed the item of the control.
        *
        * This event is fired when the contents are committed and not while the
        * value is changing. For example, on a text box, this event is not fired
        * while the user is typing, but rather when the user commits the change
        * by leaving the text box that has focus. In addition, this event is
        * executed before the code specified by onblur when the control is also
        * losing the focus.
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnChange() { return $this->_onchange; }
        function writeOnChange($value) { $this->_onchange = $value; }
        function defaultOnChange() { return null; }

        /**
        * Occurs when the user clicks the control.
        *
        * Use the OnClick event handler to respond when the user clicks the control.
        *
        * Usually OnClick occurs when the user presses and releases the left mouse button
        * with the mouse pointer over the control. This event can also occur when:
        *
        * The user selects an item in a grid, outline, list, or combo box by pressing an arrow key.
        *
        * The user presses Spacebar while a button or check box has focus.
        *
        * The user presses Enter when the active form has a default button (specified by the Default property).
        *
        * The user presses Esc when the active form has a cancel button (specified by the Cancel property).
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnClick() { return $this->_onclick; }
        /**
        * Occurs when the user clicks the control.
        * @param mixed Event handler or null if no handler is set.
        */
        function writeOnClick($value) { $this->_onclick = $value; }
        function defaultOnClick() { return null; }

        /**
        * Occurs when the user double-clicks the control.
        *
        * Use this event to react when the user double click on the control, this event
        * is usually fired after a set of other events, like mousedown and mouseup
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnDblClick() { return $this->_ondblclick; }
        /**
        * Occurs when the user double clicks the control.
        * @param mixed $value Event handler or null if no handler is set.
        */
        function writeOnDblClick($value) { $this->_ondblclick = $value; }
        function defaultOnDblClick() { return null; }

        /**
        * Occurs when the form containing the control was submitted.
        *
        * Use this event to write code that will get executed when the form
        * is submitted and the control is about to update itself with the modifications
        * the user has made on it.
        *
        * @return mixed
        */
        function readOnSubmit() { return $this->_onsubmit; }
        function writeOnSubmit($value) { $this->_onsubmit=$value; }
        function defaultOnSubmit() { return null; }


        /**
        * DataField is the fieldname to be attached to the control.
        *
        * This property allows you to show/edit information from a table column
        * using this control. To make it work, you must also assign the Datasource
        * property, which specifies the dataset that contain the fieldname to work on
        *
        * @return string
        */
        function readDataField() { return $this->_datafield; }
        function writeDataField($value) { $this->_datafield = $value; }
        function defaultDataField() { return ""; }

        /**
        * DataSource property allows you to link this control to a dataset containing
        * rows of data.
        *
        * To make it work, you must also assign DataField property with
        * the name of the column you want to use
        *
        * @return Datasource
        */
        function readDataSource() { return $this->_datasource; }
        /**
        * If a data source is assigned multi-select cannot be used.
        */
        function writeDataSource($value)
        {
                $this->_datasource = $this->fixupProperty($value);
                // if a data source is assigned then the list box can not be multi-select
                if ($value != null)
                {
                        $this->MultiSelect = 0;
                }
        }
        function defaultDataSource() { return null; }

        /**
        * Contains the strings that appear in the list box.
        *
        * Use this property to set the items that will be shown on the listbox
        * where each item has a key and a value.
        *
        * <code>
        * <?php
        *      function ListBox1BeforeShow($sender, $params)
        *      {
        *               $items=array();
        *
        *               $items['key1']='value1';
        *               $items['key2']='value2';
        *
        *               $this->ListBox1->Items=$items;
        *      }
        * ?>
        * </code>
        * @return array
        */
        function readItems() { return $this->_items; }
        function writeItems($value)
        {
                if (is_array($value))
                {
                        //This must be done this way because report SourceForge #1804137
                        //Keys from serialized arrays from the IDE are strings, if are numeric
                        //PHP is not able to find them
                        $this->_items=array();
                        reset($value);
                        while(list($key, $val)=each($value))
                        {
                                $this->_items[$key]=$val;
                        }
                }
                else
                {
                        $this->_items = (empty($value)) ? array() : array($value);
                }

                // sort the items
                if ($this->_sorted == 1)
                {
                        $this->sortItems();
                }
        }
        function defaultItems() { return array(); }

        /**
        * Size of the listbox. Size defines the number of items that are shown
        * without a need of scrolling.
        * If bigger than 1 most browsers will use Height instead. If Size equals 1
        * the listbox truns into a combobox.
        * @return integer
        */
        function readSize() { return $this->_size; }
        function writeSize($value) { $this->_size=$value; }
        function defaultSize() { return 4; }

        /**
        * Specifies whether the items in the control are arranged alphabetically.
        *
        * If this property is set, items in the control will be sorted alphabetically
        * according to the values of the items, not the keys.
        *
        * @return bool
        */
        function readSorted() { return $this->_sorted; }
        function writeSorted($value)
        {
                $this->_sorted=$value;
                if ($this->_sorted == 1)
                {
                        $this->sortItems();
                }
        }
        function defaultSorted() { return 0; }

        /**
         * A number representing the position of the control in the 'focus queue'.
         *
         * For example, if a control has this property set to 4, and another control has it set to 5, the former control
         * will be reached before the latter when changing the control focus using the Tab key.
         *
         * The value of this property must be an integer between 0 and 32767.
         *
         * @return integer
         */
        function readTabOrder() { return $this->_taborder; }
        function writeTabOrder($value) { $this->_taborder=$value; }
        function defaultTabOrder() { return 0; }

        /**
        * Enable or disable the TabOrder property. The browser may still assign
        * a TabOrder by itself internally. This cannot be controlled by HTML.
        * @return bool
        */
        function readTabStop() { return $this->_tabstop; }
        function writeTabStop($value) { $this->_tabstop=$value; }
        function defaultTabStop() { return 1; }

}


/**
 * ListBox displays a collection of items in a scrollable list.
 *
 * Use ListBox to display a scrollable list of items that users can select, add, or delete.
 * ListBox is a wrapper for the HTML listbox control. For specialized list boxes, use other
 * descendant classes of CustomListBox or derive your own class from CustomListBox.
 *
 * ListBox implements the generic behavior introduced in CustomListBox. ListBox publishes
 * many of the properties inherited from TCustomListBox, but does not introduce any new behavior.
 *
 * @example ListBox/listboxsample.php How to use ListBox control
 */
class ListBox extends CustomListBox
{
        /*
        * Publish the events
        */
        function getOnClick                     () { return $this->readOnClick(); }
        function setOnClick                     ($value) { $this->writeOnClick($value); }

        function getOnDblClick                  () { return $this->readOnDblClick(); }
        function setOnDblClick                  ($value) { $this->writeOnDblClick($value); }

        function getOnSubmit                    () { return $this->readOnSubmit(); }
        function setOnSubmit                    ($value) { $this->writeOnSubmit($value); }

        /*
        * Publish the JS events
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }


        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }


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

        function getMultiSelect()
        {
                return $this->readMultiSelect();
        }
        function setMultiSelect($value)
        {
                $this->writeMultiSelect($value);
        }

        function getItems()
        {
                return $this->readItems();
        }
        function setItems($value)
        {
                $this->writeItems($value);
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

        function getSize()
        {
                return $this->readSize();
        }
        function setSize($value)
        {
                $this->writeSize($value);
        }

        function getSorted()
        {
                return $this->readSorted();
        }
        function setSorted($value)
        {
                $this->writeSorted($value);
        }

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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
}

/**
 * A class to encapsulate a combobox control.
 *
 * Note: It is directly subclassed from CustomListBox since they are almost
 *       identical in HTML. The only differentce is that no MultiSelect is
 *       possible.
 *
 */
class ComboBox extends CustomListBox
{
        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                // size is always 1 to render a ComboBox in the browser
                $this->_size = 1;
                // no MultiSelect possible
                $this->_multiselect = 0;

                $this->Width = 185;
                $this->Height = 18;
        }

        // Documented in the parent.
        function readSelCount()
        {
                // Only one or zero items can be selected.
                return ($this->_itemindex != -1) ? 1 : 0;
        }

        // Documented in the parent.
        function readMultiSelect()
        {
                // Always return false since MultiSelect can not be used with a ComboBox.
                return 0;
        }
        function writeMultiSelect($value)
        {
                // Do nothing; MultiSelect cannot be used with a ComboBox.
        }

        /*
        * Publish the events
        */
        function getOnChange                    () { return $this->readOnChange(); }
        function setOnChange                    ($value) { $this->writeOnChange($value); }

        function getOnDblClick                  () { return $this->readOnDblClick(); }
        function setOnDblClick                  ($value) { $this->writeOnDblClick($value); }

        function getOnSubmit                    () { return $this->readOnSubmit(); }
        function setOnSubmit                    ($value) { $this->writeOnSubmit($value); }

        function getOnClick()                   { return $this->readOnClick(); }
        function setOnClick($value)             { $this->writeOnClick($value); }


        /*
        * Publish the JS events
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }


        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }


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

        function getItems()
        {
                return $this->readItems();
        }
        function setItems($value)
        {
                $this->writeItems($value);
        }

        function getItemIndex()
        {
                return $this->readItemIndex();
        }
        function setItemIndex($value)
        {
                $this->writeItemIndex($value);
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

        function getSorted()
        {
                return $this->readSorted();
        }
        function setSorted($value)
        {
                $this->writeSorted($value);
        }

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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
}


/**
 * ButtonControl is a base class for push button controls.
 *
 * This class is used as a base for Button, CheckBox and RadioButton and provides
 * the standard properties, methods an events to easily create a multistate control
 *
 * @link http://www.w3.org/TR/html401/interact/forms.html#h-17.4.1
 *
 */
class ButtonControl extends FocusControl
{
        protected $_onclick = null;
        protected $_onsubmit = null;
        protected $_jsonselect = null;

        protected $_checked = 0;
        protected $_datasource = null;
        protected $_datafield = "";
        protected $_taborder = 0;
        protected $_tabstop = 1;

        protected $_composite=false;

        // defines which property is set by the datasource
        protected $_datafieldproperty = 'Caption';


        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width = 75;
                $this->Height = 25;
                $this->ControlStyle="csRenderOwner=1";
                $this->ControlStyle="csRenderAlso=StyleSheet";
        }

        function loaded()
        {
                parent::loaded();
                $this->writeDataSource($this->_datasource);
        }

        function init()
        {
                $submitted = $this->input->{$this->Name};

                // Allow the OnSubmit event to be fired because it is not
                // a mouse or keyboard event.
                if ($this->_onsubmit != null && is_object($submitted))
                {
                        $this->callEvent('onsubmit', array());
                }

                $submitEventValue = $this->input->{$this->readJSWrapperHiddenFieldName()};

                if (is_object($submitEventValue) && $this->_enabled == 1)
                {
                      $submitEvent=$submitEventValue->asString();

					/*
                      if ($submitEvent=='')
                      {
                          $submitEventValue = $this->input->{$this->Name};

                        if (is_object($submitEventValue))
                        {
                          $submitEvent=$submitEventValue->asString();
                          if ($submitEvent==$this->Caption)
                          {
                            if ($this->_onclick!=null)
                            {
                                $submitEvent=$this->readJSWrapperSubmitEventValue($this->_onclick);
                            }
                          }
                        }
                      }
					  */

                      // check if the a click event of the current button
                      // has been fired
                      if ($this->_onclick != null && $submitEvent == $this->readJSWrapperSubmitEventValue($this->_onclick))
                      {
                              $this->callEvent('onclick', array());
                      }
                }
        }

        function dumpCSS()
        {
          if ($this->Style=="")
          {
            echo $this->Font->FontString;
            if ($this->color != "") echo "background-color: $this->color;\n";

            // add the cursor to the style
            echo parent::parseCSSCursor();
          }

          if(!$this->_composite)
            echo $this->_readCSSSize();

          if ($this->readHidden())
          {
            if (($this->ControlState & csDesigning) != csDesigning)
            {
              echo "visibility:hidden;\n";
            }
          }

          parent::dumpCSS();
        }

        /**
        * This function was introduced to be flexible with the sub-classed controls.
        * It takes all necessary info to dump the control.
        * @param string $inputType Input type such as submit, button, check, radio, etc..
        * @param string $name Name of the control
        * @param string $additionalAttributes String containing additional attributes that will be included in the <input ..> tag.
        * @param string $surroundingTags Tags that surround the <input ..> tag. Use %s to specify were the <input> tag should be placed.
        */
        function dumpContentsButtonControl($inputType, $name, $additionalAttributes = "", $surroundingTags = "%s")
        {
            //check if there is an id as extra attribute otherwise we'll use $name as id
            if(array_key_exists("id",$this->_attributes)===FALSE)
            {
				//[13/07/12][PEREZ][HI-339] The value of 'Id' is the same of 'Name' property
                if ($inputType == "radio")
                    $this->_attributes['id'] = $this->Name;
                else
                    $this->_attributes['id'] = $name;

            }

            // Append additional attributes to those provided in the call.
            // NOTE: The whitespace is needed for HTML validation.
            $additionalAttributes .= " ".$this->strAttributes();

			$events = "";
			if ($this->_enabled == 1)
			{
					// get the string for the JS Events
					$events = $this->readJsEvents();

					// add the OnSelect JS-Event
					if ($this->_jsonselect != null)
					{
							$events .= " onselect=\"return $this->_jsonselect(event)\" ";
					}

					// add or replace the JS events with the wrappers if necessary
					$this->addJSWrapperToEvents($events, $this->_onclick, $this->_jsonclick, "onclick");
			}


			// get the Caption of the button if it is data-aware
			if (($this->ControlState & csDesigning) != csDesigning)
			{
					if ($this->hasValidDataField())
					{
							// depending on the sub-class there is another property to be set by the data-source (e.g. Button = Caption; CheckBox = Checked)
							$this->{$this->_datafieldproperty} = $this->readDataFieldValue();

							//Dumps hidden fields to know which is the record to update
							$this->dumpHiddenKeyFields();
					}
			}

			// set the checked status
			$checked = ($this->_checked) ? "checked=\"checked\"" : "";

			// set enabled/disabled status
			$enabled = (!$this->_enabled) ? "disabled=\"disabled\"" : "";

			// set tab order if tab stop set to true
			$taborder = ($this->_tabstop == 1) ? "tabindex=\"$this->_taborder\"" : "";

      // set the draggable attribute
      $draggable = ($this->_draggable) ? ' draggable="true" ' : '';

			// get the hint attribute; returns: title="[HintText]"
			$hint = $this->HintAttribute();

			$class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";

			// call the OnShow event if assigned so the Caption property can be changed
			if ($this->_onshow != null)
			{
					$this->callEvent('onshow', array());
			}

			$final_value = '';
			if ($inputType != "image")
			{
				// assemble the input tag
				$avalue=$this->_caption;
				$avalue=str_replace('"','&quot;',$avalue);

				$final_value = "value=\"$avalue\"";
			}
			else
			{
			  $hint .= ($this->Caption != "") ?  " alt=\"{$this->Caption}\"": " alt=\" \""; //workaround

			}

			$autofocus = parent::isActiveControl();

			$input = "<input type=\"$inputType\" name=\"$name\" $final_value $events $checked $enabled $taborder $hint $additionalAttributes $class $autofocus $draggable/>";
			// output the control
			printf($surroundingTags, $input);

        }

        function dumpFormItems()
        {
                // add a hidden field so we can determine which button fired the OnClick event
                if ($this->_onclick != null)
                {
                        $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
                        echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
                }
        }

        /*
        * Write the Javascript section to the header
        */
        function dumpJavascript()
        {
                parent::dumpJavascript();

                if ($this->_enabled == 1)
                {
                        if ($this->_jsonselect != null)
                        {
                                $this->dumpJSEvent($this->_jsonselect);
                        }

                        if ($this->_onclick != null && !defined($this->_onclick))
                        {
                                // only output the same function once;
                                // otherwise if for example two buttons use the same
                                // OnClick event handler it would be outputted twice.
                                $def=$this->_onclick;
                                define($def,1);

                                // output the wrapper function
                                echo $this->getJSWrapperFunction($this->_onclick);
                        }
                }
        }



        /**
        * Occurs when the user clicks the control.
        *
        * Use the OnClick event handler to respond when the user clicks the control.
        *
        * Usually OnClick occurs when the user presses and releases the left mouse button
        * with the mouse pointer over the control. This event can also occur when:
        *
        * The user selects an item in a grid, outline, list, or combo box by pressing an arrow key.
        *
        * The user presses Spacebar while a button or check box has focus.
        *
        * The user presses Enter when the active form has a default button (specified by the Default property).
        *
        * The user presses Esc when the active form has a cancel button (specified by the Cancel property).
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnClick() { return $this->_onclick; }
        function writeOnClick($value) { $this->_onclick = $value; }
        function defaultOnClick() { return null; }

        function getHidden() { return $this->readhidden(); }
        function setHidden($value) { $this->writehidden($value); }

        /**
        * JS event when the control gets focus.
        *
        * Use this event to provide custom behavior with then text in the control
        * is selected
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readjsOnSelect() { return $this->_jsonselect; }
        /**
        * JS event when the control gets focus.
        * @param mixed $value Event handler or null to unset.
        */
        function writejsOnSelect($value) { $this->_jsonselect=$value; }
        function defaultjsOnSelect() { return null; }

        /**
        * Occurs when the form containing the control has been submitted.
        *
        * Use this event to react to form submissions to the server, it can be used to validate form input
        * or to perform any other action you want to do when the form is sent.
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readOnSubmit() { return $this->_onsubmit; }
        function writeOnSubmit($value) { $this->_onsubmit=$value; }
        function defaultOnSubmit() { return null; }

        /**
        * Specifies whether the control is checked.
        *
        * Use Checked to determine whether a button control is checked or to
        * set the control to a checked state. This property is boolean.
        *
        * @return bool
        */
        function readChecked() { return $this->_checked; }
        function writeChecked($value) { $this->_checked=$value; }
        function defaultChecked() { return 0; }

        /**
        * Identifies the field from which the data-aware control displays data.
        *
        * Set DataField to the field name of the field component that the control represents.
        * Access by the control to the dataset in which the field is located is provided by a DataSource component,
        * specified in the DataSource property.
        *
        * @see readDataSource()
        *
        * @return string
        */
        function readDataField() { return $this->_datafield; }
        function writeDataField($value) { $this->_datafield = $value; }
        function defaultDataField() { return ""; }

        /**
        * Links the control to a dataset.
        *
        * Specify the data source component through which the data from a dataset component
        * is provided to the control. To allow the control to represent the data for a field,
        * both the DataSource and the DataField properties must be set.
        *
        * @see readDataField()
        * @return DataSource
        */
        function readDataSource() { return $this->_datasource; }
        function writeDataSource($value)
        {
                $this->_datasource = $this->fixupProperty($value);
        }
        function defaultDataSource() { return null; }

        /**
         * A number representing the position of the control in the 'focus queue'.
         *
         * For example, if a control has this property set to 4, and another control has it set to 5, the former control
         * will be reached before the latter when changing the control focus using the Tab key.
         *
         * The value of this property must be an integer between 0 and 32767.
         *
         * @return integer
         */
        function readTabOrder() { return $this->_taborder; }
        function writeTabOrder($value) { $this->_taborder=$value; }
        function defaultTabOrder() { return 0; }

        /**
        * Enable or disable the TabOrder property. The browser may still assign
        * a TabOrder by itself internally. This cannot be controlled by HTML.
        * @return bool
        */
        function readTabStop() { return $this->_tabstop; }
        function writeTabStop($value) { $this->_tabstop=$value; }
        function defaultTabStop() { return 1; }
}


define('btSubmit', 'btSubmit');
define('btReset', 'btReset');
define('btNormal', 'btNormal');

/**
 * Push button control.
 *
 * You can use this control to let users start actions. It is completely customizable
 * through properties such as BorderRadius or Gradient, and additionally you can create image-based buttons through its
 * ImageSource property.
 *
 * @link wiki://Controls
 *
 * @example Button/button.php How to use the Button component
 */
class Button extends ButtonControl
{
        protected $_buttontype = btSubmit;
        protected $_default = 0;
        protected $_imagesource = "";

        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                // define which property is set by the datasource
                $this->_datafieldproperty = 'Caption';
        }

        function dumpContents()
        {
                // get the button type
                $buttontype = "submit";
                switch ($this->_buttontype)
                {
                        case btSubmit :
                                $buttontype = "submit";
                                break;
                        case btReset :
                                $buttontype = "reset";
                                break;
                        case btNormal :
                                $buttontype = "button";
                                break;
                }

                // Check if an imagesource is defined, if yes then let's make an
                // image input.
                $imagesrc = "";
                if ($this->_imagesource != "")
                {
                        $buttontype = "image";
                        $imagesrc = "src=\"$this->_imagesource\"";
                }

                // override the buttontype if Default is true
                if ($this->_default == 1)
                {
                        $buttontype = "submit";
                        $imagesrc = "";
                }

                // dump to control with all other parameters
                $this->dumpContentsButtonControl($buttontype, $this->_name, $imagesrc);
        }


        /**
         * Overriden method to fix the button appaerance on webkit in mac to work with height and width
         * You can skip this by setting the Autosize to true
         */
        function dumpCSS()
        {
            parent::dumpCSS();
            global $application;

            if (! ($application != null && $application->getGenerateVendorCSSExtensions() == false) )
                echo "-webkit-appearance: button;\n";
        }

        /**
         * Type of button to be used, according to the expected behavior.
         *
         * The possible values are:
         * - btSubmit. Submits the page's HTML form when clicked.
         * - btReset. Resets the input fields (Edit, ComboBox, etc.) in the page's HTML form back to their initial values.
         * - btNormal. Regular button, with no default effect (you need to define server or client-side events to add interaction to it).
         *
         * @return string
         */
        function getButtonType() { return $this->_buttontype; }
        function setButtonType($value)
        {
                $this->_buttontype = $value;
                // if ButtonType is not submit and default is set then unset default
                if ($this->_buttontype != btSubmit && $this->_default == 1)
                {
                        $this->Default = 0;
                }
        }
        function defaultButtonType() { return btSubmit; }

        /**
         * Whether the handler for the OnClick event should be executed when the Enter key is pressed (true), or not
         * (false).
         *
         * If this property is set to true, the control will behave as if the ButtonType were btSubmit, no matter
         * what its actual value is.
         *
         * @return bool
         */
        function getDefault() { return $this->_default; }
        function setDefault($value)
        {
                $this->_default=$value;
                // If set to default the ButtonType has to be submit
                if ($this->_default == 1)
                {
                        $this->ButtonType = btSubmit;
                }
        }
        function defaultDefault() { return 0; }

        /**
         * Path to an image file to be used for the surface of the control.
         *
         * To avoid distortion, make sure the Height and Width of the control matches those of the image file.
         *
         * @return string
         */
        function getImageSource() { return $this->_imagesource; }
        function setImageSource($value) { $this->_imagesource = $value; }
        function defaultImageSource() { return ""; }

        /*
        * Publish the events for the Button component
        */
        function getOnClick                   () { return $this->readOnClick(); }
        function setOnClick($value)           { $this->writeOnClick($value); }

        /*
        * Publish the JS events for the Button component
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

        function getjsOnSelect                  () { return $this->readjsOnSelect(); }
        function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }

        // Documented in the parent.
        function getDraggable() { return $this->readdraggable(); }
        function setDraggable($value) { $this->writedraggable($value); }

        // Documented in the parent.
        function getjsOnDrag() { return $this->readjsondrag(); }
        function setjsOnDrag($value) { $this->writejsondrag($value); }

        // Documented in the parent.
        function getjsOnDragEnd() { return $this->readjsondragend(); }
        function setjsOnDragEnd($value) { $this->writejsondragend($value); }

        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDragStart() { return $this->readjsondragstart(); }
        function setjsOnDragStart($value) { $this->writejsondragstart($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }


        function getCaption()
        {
                return $this->readCaption();
        }
        function setCaption($value)
        {
                $this->writeCaption($value);
        }

        function getColor()
        {
                return $this->readColor();
        }
        function setColor($value)
        {
                $this->writeColor($value);
        }

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

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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
}

/**
 * Base class for Checkbox controls.
 *
 * CheckBox represents a check box that can be on (checked) or off (unchecked).
 *
 */
class CustomCheckBox extends ButtonControl
{

        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width = 121;
                $this->Height = 21;

                // define which property is set by the datasource
                $this->_datafieldproperty = 'Checked';
                $this->_composite = true;
        }

        function preinit()
        {
                $submittedValue = $this->input->{$this->_name};

                if ($_SERVER['REQUEST_METHOD']=='POST')
                {
                   // check if the CheckBox is checked (compare against the Caption
                   // since it is submitted as value)

                   if (((is_object($submittedValue)) &&
                        ($submittedValue->asString() == $this->_caption))
                        ||
                        ( (isset($_POST[$this->_name]) ) && ($_POST[$this->_name]==$this->_caption)) )
                   {

                           $this->_checked = 1;
                           //If there is any valid DataField attached, update it
                           $this->updateDataField($this->_checked);
                   }
                   else if (($this->ControlState & csDesigning) != csDesigning)
                   {
                           $this->_checked = 0;
                           //If there is any valid DataField attached, update it
                           $this->updateDataField($this->_checked);
                   }
                }
        }

        function dumpContents()
        {

                // get the hint attribute; returns: title="[HintText]"
                $hint = $this->HintAttribute();

                $class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";

                //Let's check if the element is a Mobile element
                $MBox=array_key_exists("data-role",$this->_attributes);

                $label = "<label id=\"{$this->_name}_caption\" $hint $class for=\"$this->_name\">";
                $label.= $this->_caption;
                $label .= "</label>";
                // TODO: remove aligment, replace with css
                //if the component is a MRadioButton we just need a cell in the table
                if($MBox)
                    $format = "<tr><td style=\"height:{$this->_height}px\" >%s $label</td></tr>\r\n";
                else if( $this->_alignment == agCenter )
                    $format = "<tr><td style=\"text-align: center;\">%s</td></tr><tr><td style=\"text-align: center;\"><label for=\"$this->_name\" $hint $class>$this->_caption</label></td></tr>\r\n";
                else if( $this->_alignment == agLeft )
                    $format = "<tr><td><label for=\"$this->_name\" $hint $class>$this->_caption</label></td><td width=\"20\">%s</td></tr>\r\n";
                else
                    $format = "<tr><td width=\"20\">%s</td><td><label for=\"$this->_name\" $hint $class>$this->_caption</label></td></tr>\r\n";

                //$surroundingTags = "<table cellpadding=\"0\" cellspacing=\"0\" id=\"{$this->_name}_table\" ".(!$MBox?$style:"")." $class>\r\n$format</table>\r\n";
                $surroundingTags = "<table cellpadding=\"0\" cellspacing=\"0\" id=\"{$this->_name}_table\" $class>\r\n$format</table>\r\n";

                $surroundingTags="<span id=\"{$this->_name}_p\">%s</span>";
                $surroundingTags.="<span id=\"{$this->_name}_l\">";
                $surroundingTags.="<label for=\"$this->_name\">$this->_caption</label></span>";

                $this->dumpContentsButtonControl("checkbox", $this->_name, "", $surroundingTags);

        }

        function dumpAdditionalCSS()
        {
          $pcss=$this->readCSSDescriptor().'_p';
          $lcss=$this->readCSSDescriptor().'_l';
          $ocss=$this->readCSSDescriptor().'_outer';

          echo $pcss." {\n";
          echo "line-height:{$this->_height}px;\n";
          echo "}\n";

          echo $ocss." {\n";
          if ($this->_color!="")
          {
            echo "background-color: $this->_color;\n";
          }
          echo "}\n";

          echo $lcss." {\n";
          echo "line-height:{$this->_height}px;\n";
          echo "position:absolute;\n";
          echo "left:20px;\n";
          echo "top:-2px;\n";
          echo "z-index:-1;\n";
          echo "display:inline-block;\n";
          echo "width: 100%;\n";
          echo "text-align:left;\n";

          if ($this->readHidden())
          {
            if (($this->ControlState & csDesigning) != csDesigning)
                echo "visibility:hidden;\n";
          }


          echo "}\n";

          echo "label[for=\"$this->_name\"]\n";
          echo "{\n";
          echo $this->Font->FontString;
          echo "}\n";
        }
}

/**
 * CheckBox represents a check box that can be on (checked) or off (unchecked)
 *
 * A CheckBox component presents an option for the user. The user can check the
 * box to select the option, or uncheck it to deselect the option.
 *
 * @link http://www.w3.org/TR/html401/interact/forms.html#h-17.4.1
 *
 */
class CheckBox extends CustomCheckBox
{
        /*
        * Publish the events for the CheckBox component
        */
        function getOnClick                   () { return $this->readOnClick(); }
        function setOnClick($value)           { $this->writeOnClick($value); }

        function getOnSubmit                  () { return $this->readOnSubmit(); }
        function setOnSubmit                  ($value) { $this->writeOnSubmit($value); }

        /*
        * Publish the JS events for the CheckBox component
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

        function getjsOnSelect                  () { return $this->readjsOnSelect(); }
        function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }

        // Documented in the parent.
        function getDraggable() { return $this->readdraggable(); }
        function setDraggable($value) { $this->writedraggable($value); }

        // Documented in the parent.
        function getjsOnDrag() { return $this->readjsondrag(); }
        function setjsOnDrag($value) { $this->writejsondrag($value); }

        // Documented in the parent.
        function getjsOnDragEnd() { return $this->readjsondragend(); }
        function setjsOnDragEnd($value) { $this->writejsondragend($value); }

        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDragStart() { return $this->readjsondragstart(); }
        function setjsOnDragStart($value) { $this->writejsondragstart($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }


        /*
        * Publish the properties for the CheckBox component
        */

        function getAlignment()
        {
                return $this->readAlignment();
        }
        function setAlignment($value)
        {
                $this->writeAlignment($value);
        }

        function getCaption()
        {
                return $this->readCaption();
        }
        function setCaption($value)
        {
                $this->writeCaption($value);
        }

        function getChecked()
        {
                return $this->readChecked();
        }
        function setChecked($value)
        {
                $this->writeChecked($value);
        }

        function getColor()
        {
                return $this->readColor();
        }
        function setColor($value)
        {
                $this->writeColor($value);
        }

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

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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
}

/**
 * Use RadioButton to add an indipendent radio button to a form.
 *
 * Use RadioButton to add a radio button to a form. Radio buttons present a set of
 * mutually exclusive options to the user- that is, only one radio button in a set
 * can be selected at a time. When the user selects a radio button, the previously
 * selected radio button becomes unselected. Radio buttons are frequently grouped
 * in a radio group box (RadioGroup). Add the group box to the form first, then
 * get the radio buttons from the Component palette and put them into the group box.
 *
 * Use the Group property to specify which RadioButtons belong to the same group,
 * and that way, only one could be selected at a time.
 *
 */
class RadioButton extends ButtonControl
{
        protected $_group = '';

        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width = 121;
                $this->Height = 21;

                // define which property is set by the datasource
                $this->_datafieldproperty = 'Checked';
                $this->_composite=true;
        }

        function preinit()
        {

          if ($_SERVER['REQUEST_METHOD']=='POST')
          {
                // get the group-name, if non is set then get the name of the RadioButton
                $groupname = ($this->_group != '') ? $this->_group : $this->_name;
                //RAD 282301
                if (is_numeric($groupname)) $groupname='g'.$groupname;

                $submittedValue = $this->input->{$groupname};

                // check if the RadioButton is checked (compare against the Caption
                // since it is submitted as value)
                if (((is_object($submittedValue)) && ($submittedValue->asString() == $this->_caption))
                    ||
                    ((isset($_POST[$this->_name])) && ($_POST[$this->_name]==$this->_caption))
                    )
                {
                        $this->_checked = 1;
                        //If there is any valid DataField attached, update it
                        $this->updateDataField($this->_checked);
                }
                else if (($this->ControlState & csDesigning) != csDesigning)
                {
                        $this->_checked = 0;

                        //If there is any valid DataField attached, update it
                        $this->updateDataField($this->_checked);
                }
            }
        }



        function dumpContents()
        {
                $hint = $this->HintAttribute();

                $class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";

                // get the group-name, if non is set then get the name of the RadioButton
                $groupname = ($this->_group != '') ? $this->_group : $this->_name;

                if (is_numeric($groupname)) $groupname='g'.$groupname;

                //data-role --> Mobile
                $Mbox=array_key_exists("data-role",$this->_attributes);

                if(!$Mbox)
                {
                  $surroundingTags = "<table id=\"{$this->_name}_table\" $class><tr><td >\n";
                  $surroundingTags .= "%s\n";
                  $surroundingTags .= "</td><td >\n";
                  $surroundingTags .= ($this->Owner != null) ? "<span id=\"{$this->_name}_caption\" onclick=\"return RadioButtonClick(document.forms[0].$groupname, '$this->_caption');\" $hint $class>" : "<span>";
                  $surroundingTags .= $this->_caption;
                  $surroundingTags .= "</span>\n";
                }
                else
                {
                  $surroundingTags = "<table id=\"{$this->_name}_table\" $class><tr>";
                  $surroundingTags.= "<td>%s\n";
                  // Add some JS to the Caption (OnClick).
                  $surroundingTags .= ($this->Owner != null) ? "<label id=\"{$this->_name}_caption\" $hint $class for=\"$this->_name\">" : "<label>";
                  $surroundingTags .= $this->_caption;
                  $surroundingTags .= "</label>\n";
                  //add the id as an extra attribute
                  $this->_attributes['id']=$this->_name;
                }

                $surroundingTags .= "</td></tr></table>\n";


                $this->dumpContentsButtonControl("radio", $groupname, "", $surroundingTags);
        }

        function dumpAdditionalCSS()
        {
             $Mbox=array_key_exists("data-role",$this->_attributes);

            $pcss=$this->readCSSDescriptor().'_p';
            $lcss=$this->readCSSDescriptor().'_l';
            $ocss=$this->readCSSDescriptor().'_outer';
            $tcss=$this->readCSSDescriptor().'_table';

            echo $pcss." {\n";
            echo "line-height:{$this->_height}px;\n";
            echo "}\n";

            echo $ocss." {\n";
            if ($this->_color!="")
            {
              echo "background-color: $this->_color;\n";
            }
            echo "}\n";

            echo $lcss." {\n";
            echo "line-height:{$this->_height}px;\n";
            echo "position:absolute;\n";
            echo "left:20px;\n";
            echo "top:-2px;\n";
            echo "z-index:-1;\n";
            echo "display:inline-block;\n";
            echo "width: 100%;\n";
            echo "text-align:left;\n";
            echo "}\n";

            echo "label[for=\"$this->_name\"]\n";
            echo "{\n";
            echo $this->Font->FontString;
            echo "}\n";

            //style for table
            $style = "";
            if ($this->Style=="")
            {
                    $style .= $this->Font->FontString;

                    if ($this->color != "")
                    {
                            $style .= "background-color: ".$this->color.";\n";
                    }

                    // add the cursor to the style

                    if ($this->_cursor != "")
                    {
                          $style .= parent::parseCSSCursor();
                    }
            }

            $height = $this->Height - 1;
            $width = $this->Width;

            $style .= $this->_readCSSSize();

            if ($this->readHidden())
            {
                    if (($this->ControlState & csDesigning) != csDesigning)
                    {
                            $style .= "visibility:hidden;\n";
                    }
            }

            // get the alignment of the Caption
            $alignment = "";
            if(!$Mbox)
            {
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


            }

            echo $tcss." {\n";
            echo $style;
            echo "}\n";

            echo $tcss." td {\n";
            echo $this->_readCSSSize();
            echo "width: 20px;\n";
            echo "padding:0px;\n";
            echo "border-spacing:0px;\n";
            echo $alignment;

            if ($Mbox)
              echo "vertical-align: top\n";
            echo "}\n";

            parent::dumpAdditionalCSS();

        }

        /*
        * Write the Javascript section to the header
        */
        function dumpJavascript()
        {
                parent::dumpJavascript();

                // only output the function once
                if (!defined('RadioButtonClick'))
                {
                        define('RadioButtonClick', 1);
                        // Since all names are the same for the same group we
                        // have to check with the value attribute.
                        echo "
function RadioButtonClick(elem, caption)
{
   if (typeof(elem.length) == 'undefined') {
     elem.checked = true;
     return (typeof(elem.onclick) == 'function') ? elem.onclick() : false;
   } else {
     for(var i = 0; i < elem.length; i++) {
       if (elem[i].value == caption) {
         elem[i].checked = true;
         return (typeof(elem[i].onclick) == 'function') ? elem[i].onclick() : false;
       }
     }
   }
   return false;
}
";
                }
        }


        /**
        * Group where the RadioButton belongs to.
        *
        * If group is empty the name of the RadioButton is used, but usually that
        * is not the desired behavior.
        *
        * @return string
        */
        function getGroup()
        {
                return $this->_group;
        }
        function setGroup($value)
        {
                $this->_group = $value;
        }
        function defaultGroup() { return ''; }


        /*
        * Publish the events for the CheckBox component
        */
        function getOnClick                   () { return $this->readOnClick(); }
        function setOnClick($value)           { $this->writeOnClick($value); }

        function getOnSubmit                  () { return $this->readOnSubmit(); }
        function setOnSubmit                  ($value) { $this->writeOnSubmit($value); }

        /*
        * Publish the JS events for the CheckBox component
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

        function getjsOnSelect                  () { return $this->readjsOnSelect(); }
        function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }

        // Documented in the parent.
        function getDraggable() { return $this->readdraggable(); }
        function setDraggable($value) { $this->writedraggable($value); }

        // Documented in the parent.
        function getjsOnDrag() { return $this->readjsondrag(); }
        function setjsOnDrag($value) { $this->writejsondrag($value); }

        // Documented in the parent.
        function getjsOnDragEnd() { return $this->readjsondragend(); }
        function setjsOnDragEnd($value) { $this->writejsondragend($value); }

        // Documented in the parent.
        function getjsOnDragEnter() { return $this->readjsondragenter(); }
        function setjsOnDragEnter($value) { $this->writejsondragenter($value); }

        // Documented in the parent.
        function getjsOnDragLeave() { return $this->readjsondragleave(); }
        function setjsOnDragLeave($value) { $this->writejsondragleave($value); }

        // Documented in the parent.
        function getjsOnDragOver() { return $this->readjsondragover(); }
        function setjsOnDragOver($value) { $this->writejsondragover($value); }

        // Documented in the parent.
        function getjsOnDragStart() { return $this->readjsondragstart(); }
        function setjsOnDragStart($value) { $this->writejsondragstart($value); }

        // Documented in the parent.
        function getjsOnDrop() { return $this->readjsondrop(); }
        function setjsOnDrop($value) { $this->writejsondrop($value); }


        /*
        * Publish the properties for the CheckBox component
        */

        function getAlignment()
        {
                return $this->readAlignment();
        }
        function setAlignment($value)
        {
                $this->writeAlignment($value);
        }

        function getCaption()
        {
                return $this->readCaption();
        }
        function setCaption($value)
        {
                $this->writeCaption($value);
        }

        function getChecked()
        {
                return $this->readChecked();
        }
        function setChecked($value)
        {
                $this->writeChecked($value);
        }

        function getColor()
        {
                return $this->readColor();
        }
        function setColor($value)
        {
                $this->writeColor($value);
        }

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

        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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
}

/**
* Base class for Upload class
*
* Upload component allows the user to upload a file to the server.
*
* @link http://www.w3.org/TR/html401/interact/forms.html#file-select
*/
class CustomUpload extends FocusControl
{

        protected $_onsubmit=null;
        protected $_onclick=null;
        protected $_ondblclick=null;
        protected $_onuploaded=null;

        protected $_jsonselect=null;

        protected $_charcase=ecNormal;
        protected $_maxlength=0;
        protected $_accept='';
        protected $_size="";
        protected $_taborder=0;
        protected $_tabstop=1;
        protected $_readonly=0;

        protected $_filetmpname = null;
        protected $_filename = null;
        protected $_filesize = null;
        protected $_filetype = null;
        protected $_filesubtype = null;

        /* if this data can be obtain by getimagesize these vars will be set*/
        protected $_graphic_width = 0;
        protected $_graphic_height = 0;
        protected $_graphic_typ = 0;



        function __construct($aowner = null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->writeMultiFormData($aowner);

                $this->Width = 260;
                $this->Height = 21;

                $this->ControlStyle='csRenderOwner=1';
                $this->ControlStyle='csRenderAlso=StyleSheet';
        }

        /**
         * This function tries to find the next page object owning this component
         * to set the formencoding to multipart/form-data
         *
         * This is an internal method used by this component to set the form
         * encoding to the right value so files can be uploaded.
         *
         */
        protected function writeMultiFormData($aowner)
        {
           if( ! is_object($aowner)) {return;}
           if($aowner->inheritsFrom('Page'))
           {
               $aowner->writeFormEncoding('multipart/form-data');
           } else {
               $this->setMultiFormData($aowner->owner);
           }
        }


        /**
         * Wrapper function for the php intern move_upload_file function
         *
         * Use this method to move the uploaded file to a location of your choice.
         *
         * @param string $destination Path where you want to move the file
         * @param boolean $autoExt if true, the file extension will be appended
         * @return mixed  destination if file was moved successfully, else false will be returned
         */
        function moveUploadedFile($destination, $autoExt=false)
        {
            if($autoExt) $destination .= '.' . $this->getFileExt();
            if (move_uploaded_file($this->_filetmpname, $destination))
            {
                return $destination;
            } else {
                return false;
            }
        }

        /**
         * Wrapper function for the php intern is_uploaded_file function
         *
         * Use this method to check if the file uploaded was correctly uploaded
         *
         * @return boolean if true, file was uploaded ok. False in any other case.
         */
        function isUploadedFile()
        {
                return is_uploaded_file($this->_filetmpname);
        }

        /**
        * Returns the error message the upload process caused, empty string if no error
        *
        * Use this method to get the error message caused by the upload operation, if any.
        * If no error message is returned, the operation was successfully completed.
        *
        * @return string
        */
        function errorMessage()
        {
                if(!isset($_FILES[$this->Name]))
                        return "Unknown error";

                $errorCode=$_FILES[$this->Name]["error"];
                $error="";
                switch($errorCode)
                {
                        case 0:
                                return;
                        case UPLOAD_ERR_PARTIAL:
                             $error= "File was not completly uploaded.";
                             break;
                        case UPLOAD_ERR_NO_FILE:
                             $error= "No file to upload specified.";
                             break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                             $error= "No temp folder find in server.";
                             break;
                        case UPLOAD_ERR_CANT_WRITE:
                             $error= "Temp file filed to write to disc";
                             break;
                        default:
                             $error= "Temp file failed to write to disc";
                }

                return $error;

        }

        function preinit()
        {
            $this->fetchFileData();
        }

        function init()
        {

                $this->fetchFileData();

                parent::init();

                // Allow the OnSubmit event to be fired because it is not
                // a mouse or keyboard event.
                // Event is fired when a valid file was uploaded
                if ($this->_filetmpname)
                {
                        $this->callEvent('onsubmit', array());
                        if($this->_onuploaded) $this->callEvent('onuploaded',array());
                }
        }

        /**
         * Sets filename and types uploaded by this component
         *
         * This method updates internal properties like filename, filesize, etc.
         * If the file is a graphic, also graphic properties are updated.
         */
        protected function fetchFileData()
        {
            /*get it only once*/
            if($this->_filetmpname) return;

            if(!isset($_FILES[$this->Name])) return;

            $this->_filetmpname = $_FILES[$this->Name]['tmp_name'];

            if( $this->_filetmpname != '' && is_readable ($this->_filetmpname))
            {
                $this->_filename    = $_FILES[$this->Name]['name'];
                $this->_filesize    = $_FILES[$this->Name]['size'];
                $pos                = strpos ($_FILES[$this->Name]['type'] , '/');
                $this->_filetype    = substr( $_FILES[$this->Name]['type'], 0, $pos);
                $this->_filesubtype = substr( $_FILES[$this->Name]['type'], ($pos+1) );

                if($this->_filetype == 'image')
                {
                    $size = getimagesize($this->_filetmpname);
                    if(sizeof($size) >= 3)
                    {
                        $this->_graphic_width  = $size[0];
                        $this->_graphic_height = $size[1];
                        $this->_graphic_typ    = $size[2];
                    }
                }
            }
        }


        /**
        * Get the common HTML tag attributes of a Upload control.
        * @return string Returns a string with the attributes.
        */
        protected function _getCommonAttributes()
        {
                $events = '';
                //JS not supported yet
                if ($this->_enabled == 1)
                {
                        // get the string for the JS Events
                        $events = $this->readJsEvents();

                        // add the OnSelect JS-Event
                        if ($this->_jsonselect != null)
                        {
                                $events .= " onselect=\"return $this->_jsonselect(event)\" ";
                        }

                        // add or replace the JS events with the wrappers if necessary
                        $this->addJSWrapperToEvents($events, $this->_onclick,    $this->_jsonclick,    "onclick");
                        $this->addJSWrapperToEvents($events, $this->_ondblclick, $this->_jsondblclick, "ondblclick");
                }

                // set the accepted filetypes
                $accept = ($this->_accept != '') ? 'accept="' . $this->_accept . '"' : '';
                // set the input form size
                $size = ($this->_size > 0 ) ? 'size="'. $this->_size .'"' : '';

                // set maxlength if bigger than 0
                $maxlength = ($this->_maxlength > 0) ? "maxlength=\"$this->_maxlength\"" : "";


                // set tab order if tab stop set to true
                $taborder = ($this->_tabstop == 1) ? 'tabindex="' . $this->_taborder .'"' : '';

                $class = ($this->Style != '') ? 'class="'. $this->StyleClass .'"' : '';

                // get the hint attribute; returns: title="[HintText]"
                $hint = $this->HintAttribute();

                return $accept. ' ' .$size. ' ' .$maxlength .' '. $taborder .' '. $hint .' '. $events .' '. $class;
        }

        /**
         * Get the style definitions for the control that are shared by any input element.
         *
         * @internal
         *
         * @return string Returns inline CSS code.
         */
        protected function _getCommonStyles()
        {
                $style = '';
                if ($this->Style=='')
                {
                        $style .= $this->Font->FontString;

                        if ($this->Color != '')
                        {
                                $style .= 'background-color: '. $this->Color .';\n';
                        }

                        // add the cursor to the style
                        $style .= parent::parseCSSCursor();


                        // set the char case if not normal
                        if ($this->_charcase != ecNormal)
                        {
                                if ($this->_charcase == ecLowerCase)
                                {
                                        $style .= 'text-transform: lowercase;\n';
                                }
                                else if ($this->_charcase == ecUpperCase)
                                {
                                        $style .= 'text-transform: uppercase;\n';
                                }
                        }
                }

                $h = $this->Height - 1;
                $w = $this->Width;
                $style .= $this->_readCSSSize();

                return $style;
        }

        function dumpCSS()
        {
                $style = $this->_getCommonStyles();

                if ($this->readHidden())
                {
					   if (($this->ControlState & csDesigning) != csDesigning)
						{
								$style.=" visibility:hidden; ";
						}
				}

                if ($style != '')
                    echo $style;

        }

        function dumpContents()
        {

                $attributes = $this->_getCommonAttributes();

                // call the OnShow event if assigned so the Text property can be changed
                if ($this->_onshow != null)
                {
                        $this->callEvent('onshow', array());
                }

                echo '<input type="file" id="'. $this->_name .'" name="'. $this->_name .'" '. $attributes .'/>';

                // add a hidden field so we can determine which event for the edit was fired
                if ($this->_onclick != null || $this->_ondblclick != null)
                {
                        $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
                        echo '<input type="hidden" id="'. $hiddenwrapperfield .'" name="'. $hiddenwrapperfield .'" value="" />';
                }
        }

        /**
        * Determines if the uploaded file is a text file
        *
        * @return boolean
        */
        function isText()
        {
            if($this->_filetype == 'text')          return true;
            else                                    return false;
        }

        /**
        * Determines if the uploaded file is a graphic file
        *
        * @return boolean
        */
        function isImage()
        {
            if($this->_filetype == 'image')         return true;
            else                                    return false;
        }

        /**
        * Determines if the uploaded file is a video file
        *
        * @return boolean
        */
        function isVideo()
        {
            if($this->_filetype == 'video')         return true;
            else                                    return false;
        }

        /**
        * Determines if the uploaded file is an application
        *
        * @return boolean
        */
        function isApplication()
        {
            if($this->_filetype == 'application')   return true;
            else                                    return false;
        }


        /**
        * Determines if the uploaded file is a GIF file
        *
        * @return boolean
        */
        function isGIF()
        {
            if($this->_graphic_typ == 1)            return true;
            else                                    return false;
        }

        /**
        * Determines if the uploaded file is a JPEG file
        *
        * @return boolean
        */
        function isJPEG()
        {
            if($this->_graphic_typ == 2)            return true;
            else                                    return false;
        }

        /**
        * Determines if the uploaded file is a PNG file
        *
        * @return boolean
        */
        function isPNG()
        {
            if($this->_graphic_typ == 3)            return true;
            else                                    return false;
        }

        /**
         * Returns the extension of the uploaded file
         *
         * Once the file is uploaded, it returns the extension of the file.
         *
         * @return string
         */
        function readFileExt()
        {
            switch($this->_graphic_typ){
                case 0:
                    return substr( $this->_filename, (strrpos($this->_filename, '.')+1) );
                case 1:
                    return 'gif';
                case 2:
                    return 'jpg';
                case 3:
                    return 'png';
                case 4:
                    return 'swf';
                default:
                    return '';
            }
        }


        /**
        * Occurs when the form containing the control was submitted.
        *
        * Use this event to write code that will get executed when the form
        * is submitted and the control is about to update itself with the modifications
        * the user has made on it.
        *
        * @return mixed
        */
        function readOnSubmit() { return $this->_onsubmit; }
        function writeOnSubmit($value) { $this->_onsubmit=$value; }
        function defaultOnSubmit() { return null; }


        /**
        * JS Event occurs when text in the control was selected.
        *
        * Use this event to provide custom behavior with then text in the control
        * is selected
        *
        * @return mixed Returns the event handler or null if no handler is set.
        */
        function readjsOnSelect() { return $this->_jsonselect; }
        function writejsOnSelect($value) { $this->_jsonselect=$value; }
        function defaultjsOnSelect() { return null; }



        /**
        * Specifies the maximum number of characters the user can enter into
        * the edit control. A value of 0 indicates that there is no
        * application-defined limit on the length.
        * @return integer
        */
        function readMaxLength() { return $this->_maxlength; }
        function writeMaxLength($value) { $this->_maxlength=$value; }
        function defaultMaxLength() { return 0; }

        /**
         * Comma-separated list of MIME types (http://www.iana.org/assignments/media-types/index.html) that
         * are considered valid for the file to be uploaded.
         *
         * You can define just the first part of the MIME type. For example: 'audio/*', 'image/*', or 'video/*'.
         *
         * The list of accepted MIME types might be passed by the web browser to the file browser when the user
         * selects the file to be uploaded, so that the file browser filters out non-matching entries when
         * navigating the filesystem.
         *
         * This property can only be used to improve the end-user expecience, and server-side validation must be
         * implemented nonetheless.
         *
         * @return string
         */
        function readAccept() { return $this->_accept; }
        function writeAccept($value) { $this->_accept=$value; }
        function defaultAccept() { return null; }

        /**
         * Specifies the input size the text field
         *
         * Use this property to set the number of characters the control must
         * resize to. Some browsers don't accept the size of the upload component
         * specified in pixels, so you need to set this property.
         *
         * @return integer
         */
        function readSize() { return $this->_size; }
        function writeSize($value) { $this->_size=$value; }
        function defaultSize() { return ""; }


        /**
        * Set the control to read-only mode. That way the user cannot enter
        * or change the text of the edit control.
        * @return bool
        */
        function readReadOnly() { return $this->_readonly; }
        function writeReadOnly($value) { $this->_readonly=$value; }
        function defaultReadOnly() { return 0; }

        /**
         * A number representing the position of the control in the 'focus queue'.
         *
         * For example, if a control has this property set to 4, and another control has it set to 5, the former control
         * will be reached before the latter when changing the control focus using the Tab key.
         *
         * The value of this property must be an integer between 0 and 32767.
         *
         * @return integer
         */
        function readTabOrder() { return $this->_taborder; }
        function writeTabOrder($value) { $this->_taborder=$value; }
        function defaultTabOrder() { return 0; }

        /**
        * Enable or disable the TabOrder property. The browser may still assign
        * a TabOrder by itself internally. This cannot be controlled by HTML.
        * @return bool
        */
        function readTabStop() { return $this->_tabstop; }
        function writeTabStop($value) {$this->_tabstop=$value;}
        function defaultTabStop() {return 1;}

        /**
        * onUploaded is a callback that will be executed when a file is uploaded
        */
        function getOnUploaded() { return $this->_onuploaded; }
        function setOnUploaded($value) {$this->_onuploaded=$value; }
        function defaultOnUploaded() { return null; }


        /**
        * This is the temporal filename of the uploaded file
        *
        * Use this property to know the temporal filename of the file just uploaded.
        *
        * @return integer
        */
        function readFileTmpName() {return $this->_filetmpname;}

        /**
        * This is the filename of the uploaded file
        *
        * Use this property to know the name of the file just uploaded.
        *
        * @return string
        */
        function readFileName() { return $this->_filename;}

        /**
        * This is the filesize of the uploaded file
        *
        * Use this property to know the sizew of the file just uploaded.
        *
        * @return string
        */
        function readFileSize() { return $this->_filesize;}

        /**
        * This is the filetype of the uploaded file
        *
        * Use this property to know the type of the file just uploaded.
        *
        * @return string
        */
        function readFileType() { return $this->_filetype;}

        /**
        * This is the subtype of the uploaded file
        *
        * Use this property to know the subtype of the file just uploaded.
        *
        * @return string
        */
        function readFileSubType() { return $this->_filesubtype;}

        /**
        * This is the width of the uploaded graphic
        *
        * Use this property to know the width of the graphic file just uploaded.
        *
        * @return integer
        */
        function readGraphicWidth() { return $this->_graphic_width;}

        /**
        * This is the height of the uploaded graphic
        *
        * Use this property to know the height of the graphic file just uploaded.
        *
        * @return integer
        */
        function readGraphicHeight() { return $this->_graphic_height;}


}


/**
* Allows the user to upload a file to the server.
*
* This component provides you a field where the user can select a local file on their
* computer and when the form is submitted, the file will be uploaded to the server.
*
* <code>
* <?php
*    function Button1Click($sender, $params)
*    {
*       $this->Memo1->AddLine ( 'FileTmpName: ' . $this->Upload1->FileTmpName);
*       $this->Memo1->AddLine ( 'FileName: ' . $this->Upload1->FileName);
*       $this->Memo1->AddLine ( 'FileSize: ' . $this->Upload1->FileSize);
*       $this->Memo1->AddLine ( 'FileType: ' . $this->Upload1->FileType);
*       $this->Memo1->AddLine ( 'FileSubType : ' .  $this->Upload1->FileSubType);
*       $this->Memo1->AddLine ( 'GraphicWidth: ' . $this->Upload1->GraphicWidth);
*       $this->Memo1->AddLine ( 'GraphicHeihgt: ' . $this->Upload1->GraphicHeight);
*       if($this->Upload1->isGIF()) $tmp = ' is gif';
*       if($this->Upload1->isJPEG()) $tmp = ' is jpeg';
*       if($this->Upload1->isPNG()) $tmp = ' is png';
*       $this->Memo1->AddLine ( 'File Ext: ' . $this->Upload1->FileExt . $tmp);
*    }
* ?>
* </code>
* @link http://www.w3.org/TR/html401/interact/forms.html#file-select
*/
class Upload extends CustomUpload
{
        /*
        * Publish the events for the Edit component
        */

        function getOnSubmit                    () { return $this->readOnSubmit(); }
        function setOnSubmit                    ($value) { $this->writeOnSubmit($value); }

        /*
        * Publish the JS events for the Upload component
        */
        function getjsOnBlur                    () { return $this->readjsOnBlur(); }
        function setjsOnBlur                    ($value) { $this->writejsOnBlur($value); }

        function getjsOnChange                  () { return $this->readjsOnChange(); }
        function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

        function getjsOnClick                   () { return $this->readjsOnClick(); }
        function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

        function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
        function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

        function getjsOnFocus                   () { return $this->readjsOnFocus(); }
        function setjsOnFocus                   ($value) { $this->writejsOnFocus($value); }

        function getjsOnMouseDown               () { return $this->readjsOnMouseDown(); }
        function setjsOnMouseDown               ($value) { $this->writejsOnMouseDown($value); }

        function getjsOnMouseUp                 () { return $this->readjsOnMouseUp(); }
        function setjsOnMouseUp                 ($value) { $this->writejsOnMouseUp($value); }

        function getjsOnMouseOver               () { return $this->readjsOnMouseOver(); }
        function setjsOnMouseOver               ($value) { $this->writejsOnMouseOver($value); }

        function getjsOnMouseMove               () { return $this->readjsOnMouseMove(); }
        function setjsOnMouseMove               ($value) { $this->writejsOnMouseMove($value); }

        function getjsOnMouseOut                () { return $this->readjsOnMouseOut(); }
        function setjsOnMouseOut                ($value) { $this->writejsOnMouseOut($value); }

        function getjsOnKeyPress                () { return $this->readjsOnKeyPress(); }
        function setjsOnKeyPress                ($value) { $this->writejsOnKeyPress($value); }

        function getjsOnKeyDown                 () { return $this->readjsOnKeyDown(); }
        function setjsOnKeyDown                 ($value) { $this->writejsOnKeyDown($value); }

        function getjsOnKeyUp                   () { return $this->readjsOnKeyUp(); }
        function setjsOnKeyUp                   ($value) { $this->writejsOnKeyUp($value); }

        function getjsOnSelect                  () { return $this->readjsOnSelect(); }
        function setjsOnSelect                  ($value) { $this->writejsOnSelect($value); }

        // Documented in the parent.
        function getAccept()             { return $this->readAccept(); }
        function setAccept($value)       { $this->writeAccept($value); }

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

        function getMaxLength()
        {
                return $this->readMaxLength();
        }
        function setMaxLength($value)
        {
                $this->writeMaxLength($value);
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

        function getReadOnly()
        {
                return $this->readReadOnly();
        }
        function setReadOnly($value)
        {
                $this->writeReadOnly($value);
        }

        function getShowHint()
        {
                return $this->readShowHint();
        }
        function setShowHint($value)
        {
                $this->writeShowHint($value);
        }

        function getSize()             { return $this->readSize(); }
        function setSize($value)       { $this->writeSize($value); }


        function getStyle()             { return $this->readstyle(); }
        function setStyle($value)       { $this->writestyle($value); }

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
}

/**
 * Base class for components providing a list of key-value pairs.
 */
class CustomDataList extends Component
{


    // Documented in the parent.
    function __construct($aowner = null)
    {
        //Calls inherited constructor
        parent::__construct($aowner);
    }

    protected $_items = array();

    /**
     * List of key-value pairs.
     *
     * @return array
     */
   function readItems()    {return $this->_items;}
   function writeItems($value)
   {
      if(is_array($value))
      {
         $this->_items = $value;
      }
      else
      {
         $this->_items = (empty($value)) ? array(): array($value);
      }
   }
   function defaultItems()    {return array();}

   private $_printed = false;

   /**
    * Returns true if the datalist has been already printed.
    *
    * @internal
    */
   function isPrinted() { return $this->_printed; }

   /**
    * Call this method to indicate that the datalist has been already printed, so isPrinted() returns false from then
    * on and you can avoid printing the content of the datalist twice in the same page.
    *
    * @internal
    */
   function _setPrinted()
   {
      $this->_printed = true;
   }



}

/**
 * Non-visual list of key-value pairs that can be attached to input controls to provide suggestions.
 *
 * When a user starts writting into an input control with a DataList attached, as the user writes, the suggestions in
 * the DataList matching what has been typed will be displayed, usually below the control, so the user can easily
 * select one of the suggestions instead of typing the whole input.
 */
class DataList extends CustomDataList
{
    // Documented in the parent.
    function getItems()       { return $this->readItems(); }
    function setItems($value) { $this->writeItems($value); }

}

?>
