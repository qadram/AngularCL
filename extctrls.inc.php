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
use_unit("stdctrls.inc.php");
use_unit("graphics.inc.php");
use_unit("imglist.inc.php");


// Shape.ShapeType
define('stRectangle', 'stRectangle');
define('stSquare', 'stSquare');
define('stRoundRect', 'stRoundRect');
define('stRoundSquare', 'stRoundSquare');
define('stEllipse', 'stEllipse');
define('stCircle', 'stCircle');

// Bevel.Shape
define('bsBox', 'bsBox');
define('bsFrame', 'bsFrame');
define('bsTopLine', 'bsTopLine');
define('bsBottomLine', 'bsBottomLine');
define('bsLeftLine', 'bsLeftLine');
define('bsRightLine', 'bsRightLine');
define('bsSpacer', 'bsSpacer');

// Bevel.Style
define('bsLowered', 'bsLowered');
define('bsRaised', 'bsRaised');

/**
 * Control to display an image.
 *
 * Use the ImageSource property to define the path to the target image file, or its base64 code. The control provides
 * several other properties to determine the way the image is displayed within the boundaries of the control.
 *
 * @see ImageList
 */
class Image extends FocusControl
{
   protected $_onclick = null;
   protected $_oncustomize = null;


   protected $_center = 0;
   protected $_datafield = "";
   protected $_datasource = null;
   protected $_imagesource;
   protected $_link;
   protected $_linktarget;
   protected $_proportional = 0;
   protected $_stretch = 0;
   protected $_binary = 0;

   /**
    * Specifies if the information to show is binary or an url
    *
    * If true, this component will perform a request to get binary data instead
    * pointing to the image url
    *
    * @see getBinaryType()
    *
    * @return boolean
    */
   function getBinary()    {return $this->_binary;}
   function setBinary($value)    {$this->_binary = $value;}
   function defaultBinary()    {return 0;}

   protected $_binarytype = "image/jpeg";

   /**
    * Specifies the type of binary information this component is going to dump
    *
    * Use this property to specify the mime type of binary information this component
    * is going to dump
    *
    * @see getBinary()
    *
    * @return string
    */
   function getBinaryType()    {return $this->_binarytype;}
   function setBinaryType($value)    {$this->_binarytype = $value;}
   function defaultBinaryType()    {return "image/jpeg";}

   protected $_embedsvg = false;
   /**
    * The contents of the ImageSource property will be embedded into the document, replacing the <img> tag by the contents of the file.
    *
    *
    * @return boolean
    */
   function getEmbedSVG()    {return $this->_embedsvg;}
   function setEmbedSVG($value)    {$this->_embedsvg = $value;}
   function defaultEmbedSVG()    {return false;}

   function __construct($aowner = null)
   {
      //Calls inherited constructor
      parent::__construct($aowner);

      $this->Width = 105;
      $this->Height = 105;

      //For mapshapes
      $this->ControlStyle = "csAcceptsControls=1";

      $this->ControlStyle = "csRenderOwner=1";
      $this->ControlStyle = "csRenderAlso=StyleSheet";

      // Needs Webkit to be rendered on the HTML5 Buider Designer.
      $this->ControlStyle = "csWebEngine=webkit";
   }


   /**
    * Returns the absolute image path, depending if the image is stored on a
    * subfolder relative to the location of the script or in a folder outside
    * script location
    *
    * @see getImageSource()
    *
    * @return string
    */
   private function getImageSourcePath()
   {
      // check if relative
      if(substr($this->_imagesource, 0, 2) == ".." || $this->_imagesource{0} == ".")
      {
         return dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $this->_imagesource;
      }
      else
      {
         return $this->_imagesource;
      }
   }

   function loaded()
   {
      parent::loaded();

      $this->setDataSource($this->_datasource);

      if($this->_autosize)
      {

         if($this->_imagesource != "")
         {
            if(is_file($this->getImageSourcePath()))
            {
               $result = getimagesize($this->getImageSourcePath());

               if(is_array($result))
               {
                  $bordersize = 2; //($this->_border == 1)? 2 * 1: 0;

                  list($width, $height, $type, $attr) = $result;
                  $this->Width = $width + $bordersize;
                  $this->Height = $height + $bordersize;
               }
            }
         }
      }

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
      }

      $key = md5($this->owner->Name . $this->Name . $this->Left . $this->Top . $this->Width . $this->Height);
      $bimg = $this->input->bimg;

      // Checks if the request is for this img
      if((is_object($bimg)) && ($bimg->asString() == $key))
      {
         $this->dumpGraphic();
      }

   }

   /**
    * Dumps the graphic as binary
    *
    * If Binary is true and BinaryType has been set, this method
    * is called to dump the binary information
    *
    * @see getBinaryType(), getBinary()
    */
   function dumpGraphic()
   {
      // Graphic component that dumps binary data
      header("Content-type: $this->_binarytype");

      // Tries to prevent the browser from caching the image
      header("Pragma: no-cache");
      header("Cache-Control: no-cache, must-revalidate");// HTTP/1.1
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");// Date in the past

      if($this->hasValidDataField())
      {
         echo $this->readDataFieldValue();
      }

      exit;
   }

   function dumpContents()
   {
      if($this->_onshow != null)
      {
         $this->callEvent('onshow', array());
      }
      else
      {
         $map = "";
         if($this->controls->count() > 0)
         {
            $map = "usemap=\"#map$this->_name\"";
         }
         $draggable = ($this->_draggable) ? "draggable=\"true\"" : "";
         $events = $this->readJsEvents();
         // add or replace the JS events with the wrappers if necessary
         $this->addJSWrapperToEvents($events, $this->_onclick, $this->_jsonclick, "onclick");

         $imagecoords = false;

         // first let's get the image size
         if($this->_imagesource != "")
         {
            if(is_file($this->getImageSourcePath()))
            {
               $result = getimagesize($this->getImageSourcePath());

               if(is_array($result))
               {
                  $imagecoords = true;
               }
            }
         }

         if($this->_proportional)
         {
            if($imagecoords)
            {
               $hint = $this->HintAttribute();
               $hint .= " alt=\"". htmlspecialchars($this->_hint, ENT_QUOTES) . "\"";
            }
         }

         $hint = $this->HintAttribute();
         $hint .= " alt=\"" . htmlspecialchars($this->_hint, ENT_QUOTES) . "\"";

         $class = ($this->Style != "")? "class=\"$this->StyleClass\"": "";

         echo "<div id=\"{$this->_name}_container\" $class>";

         if($this->_link != "")
         {
            echo "<A HREF=\"" . $this->_link . "\" ";
            if($this->_linktarget != "")
               echo " target=\"" . $this->_linktarget . "\"";
            echo ">";
         }

         if(($this->ControlState & csDesigning) != csDesigning)
         {
            if($this->hasValidDataField())
            {
               $this->_imagesource = $this->readDataFieldValue();
               // no hidden field to dump since it's a read-only control
            }
         }

         $this->callEvent('oncustomize', array());

         $source = $this->_imagesource;

         if(($this->ControlState & csDesigning) != csDesigning)
         {
            if($this->_binary)
            {
               $key = md5($this->owner->Name . $this->Name . $this->Left . $this->Top . $this->Width . $this->Height);
               $url = $GLOBALS['PHP_SELF'];
               $source = "$url?bimg=$key";
            }
         }

         if( ! $this->_enabled)
            $events = '';

          // the url must be encoded to replace spaces by %20
         $source = str_replace(' ', '%20',$source);

         if($this->_embedsvg)
             echo "\n<embed id=\"$this->_name\" src=\"$source\" $class $events $draggable/>";
         else
             echo "\n<img id=\"$this->_name\" src=\"$source\" $class $hint $map $events $draggable/>";

         if($this->_link != "")
            echo "</A>";
         echo "</div>";

         if($this->controls->count() > 0)
         {
            echo "<map name=\"map$this->_name\">\n";
            reset($this->controls->items);
            while(list($k, $v) = each($this->controls->items))
            {
               if($v->Visible)
               {
                  $v->show();
               }
            }
            echo "</map>";
         }
      }
   }

   function dumpCSS()
   {
        $imgwidth = $this->Width;
        $imgheight = $this->Height;

        $cwidth = $this->Width;
        $cheight = $this->Height;
        $imagecoords = false;

         // first let's get the image size
         if($this->_imagesource != "")
         {
            if(is_file($this->getImageSourcePath()))
            {
               $result = getimagesize($this->getImageSourcePath());

               if(is_array($result))
               {
                  //list($imgwidth, $imgheight, $type, $attr) = $result;
                  list($iwidth, $iheight, $type, $attr) = $result;
                  $imagecoords = true;
               }
            }
         }

         $attr = "";

         $imgstyle = "";

         $w = $imgwidth;
         $h = $imgheight;

         if(( ! $this->_stretch) && ( ! $this->_proportional))
         {
            if($imagecoords)
            {
               $attr .= "width:". $iwidth ."px;\n";
               $attr .= "height:". $iheight ."px;\n";
            }
            else
            {
               $attr .= "width:" . $cwidth ."px;\n";
               $attr .= "height:" . $cheight ."px;\n ";
            }
         }

         if(($this->_stretch == 1) && ( ! $this->_proportional))
         {
            $attr .= "width:". $this->Width ."px;\n";
            $attr .= "height:". $this->Height ."px;\n ";
         }

         if($this->_proportional)
         {
            if($imagecoords)
            {
               $hratio = $iwidth / $iheight;
               $vratio = $iheight / $iwidth;

               $twidth = $cheight * $hratio;
               $theight = $cwidth * $vratio;

               if($twidth < $cwidth)
                  $attr .= "height:". $cheight ."px;\n ";
               else
                  $attr .= "width:" . $cwidth . "px;\n ";
            }
            else
            {
               $attr .= "width:".$this->Width."px;\n ";
               $attr .= "height:".$this->Height."px;\n ";
            }
         }

         if($this->_center == 1)
         {
            $margin = floor(($this->_height - $h) / 2);
            $imgstyle .= "margin-top: $margin"."px;\n";
         }

         if($this->Color != "")
            $imgstyle .= "background-color:$this->Color;\n";


         if($imgstyle != "")
            echo $imgstyle;



        echo $attr;

        parent::dumpCSS();
   }

   function dumpAdditionalCSS()
   {

         $divstyle = "";

         $divstyle .= $this->_readCSSSize();

         if(($this->ControlState & csDesigning) == csDesigning)
         {
            $divstyle .= "border:1px dashed gray;";
         }

         // add the cursor to the style
         if($this->_cursor != "" && $this->Style == "")
         {
            //$cr = strtolower(substr($this->_cursor, 2));
            $divstyle .= parent::parseCSSCursor();
         }

         if(( ! $this->_stretch) && ( ! $this->_proportional))
         {
            $divstyle .= "overflow: hidden;\n";
         }

         if($this->_center == 1)
         {
            $divstyle .= "text-align: center;\n";
         }

         if($this->readHidden())
         {
            if(($this->ControlState & csDesigning) != csDesigning)
            {
               $divstyle .= "visibility:hidden;\n";
            }
         }

         echo "#$this->Name" . "_container {\n";
         echo $divstyle;
         echo "}\n";
   }

   function dumpFormItems()
   {
      // add a hidden field so we can determine which event for the label was fired
      if($this->_onclick != null)
      {
         $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
         echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
      }
   }
   /**
    * Write the Javascript section to the header
    */
   function dumpJavascript()
   {
      parent::dumpJavascript();

      if($this->_onclick != null &&  ! defined($this->_onclick))
      {
         // only output the same function once;
         // otherwise if for example two buttons use the same
         // OnClick event handler it would be outputted twice.
         $def = $this->_onclick;
         define($def, 1);

         // output the wrapper function
         echo $this->getJSWrapperFunction($this->_onclick);
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
    * @return mixed
    */
   function getOnClick()    {return $this->_onclick;}
   function setOnClick($value)    {$this->_onclick = $value;}
   function defaultOnClick()    {return null;}

   /**
    * Occurs before the image tag is written to the stream sent to the client.
    * Use this event to modifiy the image source.
    * <code>
    * <?php
    *      function Image1Customize($sender, $params)
    *      {
    *               $this->Image1->ImageSource="url/test.jpg";
    *      }
    * ?>
    * </code>
    * @return mixed Event handler or null to unset.
    */
   function getOnCustomize()    {return $this->_oncustomize;}
   function setOnCustomize($value)    {$this->_oncustomize = $value;}
   function defaultOnCustomize()    {return null;}



   /**
    * Indicates whether the image is centered in the image control.
    *
    * When the image does not fit perfectly within the image control,
    * use Center to specify how the image is positioned.
    * When Center is true, the image is centered in the control.
    * When Center is false, the upper left corner of the image is positioned
    * at the upper left corner of the control.
    *
    * Note: Center has no effect if the AutoSize property is true.
    *
    * @see getAutosize()
    *
    * @return bool
    */
   function getCenter()    {return $this->_center;}
   function setCenter($value)    {$this->_center = $value;}
   function defaultCenter()    {return 0;}

   /**
    * DataField is the fieldname to be attached to the control.
    *
    * This property allows you to show/edit information from a table column
    * using this control. To make it work, you must also assign the Datasource
    * property, which specifies the dataset that contain the fieldname to work on
    *
    * @return string
    */
   function getDataField()    {return $this->_datafield;}
   function setDataField($value)    {$this->_datafield = $value;}
   function defaultDataField()    {return "";}

   /**
    * DataSource property allows you to link this control to a dataset containing
    * rows of data.
    *
    * To make it work, you must also assign DataField property with
    * the name of the column you want to use
    *
    * @return Datasource
    */
   function getDataSource()    {return $this->_datasource;}
   function setDataSource($value)    {$this->_datasource = $this->fixupProperty($value);}
   function defaultDataSource()    {return "";}

   /**
    * The source of the image to be displayed. Possible values include:
    * - The relative path to a local image located inside your project folder.
    * - An absolute URL to an image file on the network your application will be available at (e.g. the Internet).
    * - A Base64 representation of an image.
    *
    * The image filetype support might vary between browsers and operating systems. You are usually safe when using
    * JPEG, GIF or PNG.
    *
    * @link http://en.wikipedia.org/wiki/Comparison_of_web_browsers#Image_format_support Wikipedia table covering web browser image format support
    *
    * @see getImageSourcePath()
    *
    * @return string
    */
   function getImageSource()    {return $this->_imagesource;}
   function setImageSource($value)    {$this->_imagesource = $value;}

   /**
    * If Link is set, the Image will link to that URL
    *
    *
    *
    * @return string Link, if empty string the link is not used.
    */
   function getLink()    {return $this->_link;}
   function setLink($value)    {$this->_link = $value;}

   /**
    * Target attribute when the label acts as a link.
    *
    * @see getLink()
    *
    * @link http://www.w3.org/TR/html4/present/frames.html#adef-target
    * @return string The link target as defined by the HTML specs.
    */
   function getLinkTarget()    {return $this->_linktarget;}
   function setLinkTarget($value)    {$this->_linktarget = $value;}

   function getParentShowHint()    {return $this->readParentShowHint();}
   function setParentShowHint($value)    {$this->writeParentShowHint($value);}


   /**
    * Indicates whether the image should be changed, without distortion,
    * so that it fits the bounds of the image control.
    *
    * Set Proportional to true to ensure that the image can be fully displayed
    * in the image control without any distortion. When Proportional is true,
    * images that are too large to fit in the image control are scaled down
    * (while maintaining the same aspect ratio) until they fit in the image control.
    * Images that are too small are displayed normally. That is,
    * Proportional can reduce the magnification of the image, but does not increase it.
    *
    * Note: The filesize is equal even the image is scaled down.
    *
    * @see getAutosize(), getCenter()
    *
    * @return bool
    */
    function getProportional()    {return $this->_proportional;}
    function setProportional($value)    {$this->_proportional = $value;}
    function defaultProportional()    {return 0;}

    function getShowHint()    {return $this->readShowHint();}
    function setShowHint($value)    {$this->writeShowHint($value);}

    function getStyle()    {return $this->readstyle();}
    function setStyle($value)    {$this->writestyle($value);}

    function getVisible()    {return $this->readVisible();}
    function setVisible($value)    {$this->writeVisible($value);}

    function getEnabled()    {return $this->readenabled();}
    function setEnabled($value)    {$this->writeenabled($value);}

    /**
    * Indicates whether the image should be changed so that it exactly fits
    * the bounds of the image control.
    *
    * Set Stretch to true to cause the image to assume the size and shape of
    * the image control. When the image control resizes, the image resizes also.
    * Stretch resizes the height and width of the image independently. Thus,
    * unlike a simple change in magnification, Stretch can distort the image
    * if the image control is not the same shape as the image.
    *
    * To resize the control to the image rather than resizing the image to the
    * control, use the AutoSize property instead.
    *
    * The default value for Stretch is false.
    *
    * @return boolean
    */
    function getStretch()    {return $this->_stretch;}
    function setStretch($value)    {$this->_stretch = $value;}
    function defaultStretch()    {return 0;}

    function getColor() { return $this->readColor(); }
    function setColor($value) { $this->writeColor($value); }
    //function defaultColor() { return ""; }

    /*
    * Publish the JS events for the component
    */
    function getjsOnClick()    {return $this->readjsOnClick();}
    function setjsOnClick($value)    {$this->writejsOnClick($value);}

    function getjsOnDblClick()    {return $this->readjsOnDblClick();}
    function setjsOnDblClick($value)    {$this->writejsOnDblClick($value);}

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

/**
 * Base class for controls implementing an area to draw shapes.
 */
class CustomCanvas extends Control
{

   function __construct($aowner = null)
   {
      //Calls inherited constructor
      parent::__construct($aowner);
      $this->Width = 100;
      $this->Height = 100;

      // Needs Webkit to be rendered on the HTML5 Buider Designer.
      $this->ControlStyle = "csWebEngine=webkit";
   }

   protected $_notsupportedmessage = 'Your web browser does not support the HTML5 canvas element.';

   /**
    * Content to be displayed on web browsers that do not support the HTML5 canvas element.
    */
   function readNotSupportedMessage()    {return $this->_notsupportedmessage;}
   function writeNotSupportedMessage($value)    {$this->_notsupportedmessage = $value;}
   function defaultNotSupportedMessage()    {return 'Your web browser does not support the HTML5 canvas element.';}

   protected $_context = "2d";


   /**
    * Type of context to be used to draw on the canvas.
    *
    * The context is a JavaScript object used to draw on the canvas. Depending on the type of context you choose,
    * you will be able to use different methods for that task.
    *
    * The current HTML5 specification only defines a context type: 2d. Yet, alternative contexts might arise with time.
    *
    * Currently, there is an alternative context being developed (but already quite functional) for 3D drawing: experimental-webgl.
    * It takes advantage of the JavaScript API to draw 3D images on the canvas.
    *
    * @link wiki://Canvas#Documentation
    */
   function readContext()    {return $this->_context;}
   function writeContext($value)    {$this->_context = $value;}
   function defaultContext()    {return "2d";}

   /**
    * Returns the name of the JavaScript variable for the canvas context.
    *
    * @internal
    */
   function _getJavascriptVar() {
      return $this->_name . "_ctx";
   }

   // Documented in the parent.
   function dumpJavascript()
   {

      echo "var " . $this->_getJavascriptVar() . ";\n";

      parent::dumpJavascript();

   }

   // Documented in the parent.
   function pagecreate()
   {
      $output = parent::pagecreate()."\n";

       $output .= $this->_getJavascriptVar() . " = document.getElementById('" . $this->getName() . "').getContext('" . $this->getContext() . "');\n";

       //Fire the event, if any
       if ($this->_jsonpaint != '')
            $output .= $this->_jsonpaint . "(" . $this->_getJavascriptVar() .");\n";

      // bind standart events
      $output .= $this->bindJSEvent('click');
      $output .= $this->bindJSEvent('dblclick');
      $output .= $this->bindJSEvent('drag');
      $output .= $this->bindJSEvent('dragend');
      $output .= $this->bindJSEvent('dragenter');
      $output .= $this->bindJSEvent('dragleave');
      $output .= $this->bindJSEvent('dragover');
      $output .= $this->bindJSEvent('dragstart');
      $output .= $this->bindJSEvent('drop');
      $output .= $this->bindJSEvent('mousedown');
      $output .= $this->bindJSEvent('mousemove');
      $output .= $this->bindJSEvent('mouseout');
      $output .= $this->bindJSEvent('mouseover');
      $output .= $this->bindJSEvent('mouseup');
      $output .= $this->bindJSEvent('mousewheel');
      $output .= $this->bindJSEvent('scroll');
      $output .= $this->bindJSEvent('keydown');
      $output .= $this->bindJSEvent('keypress');
      $output .= $this->bindJSEvent('keyup');

      return $output;
   }

   // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();

      $this->dumpJSEvent($this->_jsonpaint);
   }

   // Documented in the parent.
   function dumpContents()
   {

      $draggable = ($this->_draggable) ? "draggable=\"true\"" : "";


      echo "<canvas id=\"$this->_name\" $draggable width=\"" . $this->Width . "\" height=\"" . $this->Height . "\">";

      //If your browser does not support canvas show the user a message
      echo $this->_notsupportedmessage;

      echo "</canvas>\n";

   }

   function dumpCSS()
   {
        // dumps a border in design time and be override by borderradius
        if(($this->ControlState & csDesigning) == csDesigning)
        {
           echo "border: thin dotted #C0C0C0;\n";
        }

        parent::dumpCSS();

        if ($this->Style=="")
        {
            // Adds the cursor to the style
            if ($this->_cursor != "")
            {
                echo parent::parseCSSCursor();
            }
        }

   }

   protected $_jsonpaint = null;

   /**
    * Event to print the JavaScript code responsible for drawing the content of the canvas.
    *
    * On the event handler, you can print JavaScript code that uses the context (available on the event variable) to draw shapes on the canvas. For example:
    *
    * <code>
    * function ComponentNameJSPaint($sender, $params)
    * {
    *   ?>
    *     // Get the context, getting the Canvas component is also convinient.
    *     canvas = $('#CanvasName')[0];
    *     context = canvas.getContext('2d');
    *
    *     // Draw a black circle on the center.
    *     context.beginPath();
    *     context.arc(canvas.width/2, canvas.height/2, 100, 0, 2 * Math.PI, false);
    *     context.fillStyle = "#000";
    *     context.fill();
    *     context.closePath();
    *   <?php
    * }
    * </code>
    *
    * The output JavaScript event is named ComponentNameJSPaint (where ComponentName is the name of the Canvas component), and is executed the first time the page loads.
    * Using the global variable for the canvas context, ComponentName_ctx, you can call that event from code like this:
    * <code>
    * ComponentNameJSPaint(ComponentName_ctx);
    * </code>
    *
    * That way, you can reload the content of the canvas as a reaction to other JavaScript events, such as a click on a Button or a change on a ComboBox.
    *
    * @link wiki://Canvas#Usage
    */
   function readjsOnPaint()    {return $this->_jsonpaint;}
   function writejsOnPaint($value)    {$this->_jsonpaint = $value;}
   function defaultjsOnPaint()    {return null;}


   /**
    * Intermal method
    *
    * This method is used to set the color for the canvas to the brush color if the brush has been modified.
    *
    */
    protected function forceBrush()
    {
            $resp = $this->_getJavascriptVar() . ".fillStyle = \"" . $this->_brush->Color . "\";\n";

            return $resp;
    }


     /**
      * Intermal method
      *
      * This method is used to set the stroke color for the canvas to the pen color if the pen has been modified.
      *
      */
      protected function forcePen()
      {

              $resp = "";

              $resp .= $this->_getJavascriptVar() . ".lineWidth = " .  $this->_pen->Width . ";\n";
              $resp .= $this->_getJavascriptVar() . ".strokeStyle = \"" . $this->_pen->Color . "\";\n";

              return $resp;
      }
   /**
     * Fills the specified rectangle on the canvas using the current brush.
     *
     * Use FillRect to fill a rectangular region using the current brush. The region
     * is filled including the top and left sides of the rectangle, but excluding the bottom and right edges.
     *
     * @param int $x1 The left point at pixel coordinates
     * @param int $y1 The top point at pixel coordinates
     * @param int $x2 The right point at pixel coordinates
     * @param int $y2 The bottom point at pixel coordinates
     */
    function fillRect($x1, $y1, $x2, $y2)
    {
            $resp = $this->forceBrush();
            $resp .= $this->_getJavascriptVar() . ".fillRect($x1, $y1, $x2 - $x1, $y2 - $y1);\n";
            return $resp;
    }

    /**
     * Draws a rectangle on the canvas.
     *
     * Use Rectangle to draw a rectangle using Pen and fill it with Brush.
     * Specify the rectangle's coordinates giving four coordinates that define the upper left
     * corner at the point (X1, Y1) and the lower right corner at the point (X2, Y2).
     *
     * To fill a rectangular region without drawing the boundary in the current pen, use FillRect.
     * To outline a rectangular region without filling it, use FrameRect or Polygon. To draw
     * a rectangle with rounded corners, use RoundRect.
     *
     * @param int $x1 The left point at pixel coordinates
     * @param int $y1 The top point at pixel coordinates
     * @param int $x2 The right point at pixel coordinates
     * @param int $y2 The bottom point at pixel coordinates
     */

    function rectangle($x1, $y1, $x2, $y2)
    {
            $resp = "";
            $resp .= $this->forceBrush();
            $resp .= $this->_getJavascriptVar() . ".fillRect($x1, $y1, $x2 - $x1 + 1, $y2 - $y1 + 1);\n";
            $resp .= $this->forcePen();
            $resp .= $this->_getJavascriptVar() . ".strokeRect($x1, $y1, $x2 - $x1 + 1, $y2 - $y1 + 1);\n";

            return $resp;
    }

    /**
     * Draws a line.
     *
     * @param array $from Starting point.
     * @param array $to   Ending point.
     */
    function drawLine($from, $to)
    {

        $resp = "";
        $resp .= $this->_getJavascriptVar() . ".beginPath();\n";
        $resp .= $this->_getJavascriptVar() . ".moveTo(" . $from[0] . ", " . $from[1] . ");\n";
        $resp .= $this->_getJavascriptVar() . ".lineTo(" . $to[0] . ", ". $to[1] . ");\n";
        $resp .= $this->_getJavascriptVar() . ".stroke();\n";
        return $resp;
    }

    /**
     * Draws an arc at a given location.
     *
     * @param int|string $x          Coordinate in the horizontal axis for the point around which the arc is drawn.
     * @param int|string $y          Coordinate in the vertical axis for the point around which the arc is drawn.
     * @param int|string $radius     Radius of the arc, that is, the distance between the central point around whoch the arc is drawn and the actual arc.
     * @param int|string $startAngle Angle from the horizontal axis at which the arc should start.
     * @param int|string $endAngle   Angle from the horizontal axis at which the arc should end.
     */
    function fillArc($x, $y, $radius, $startAngle, $endAngle)
    {
        $resp = "";
        $resp .= $this->_getJavascriptVar() . ".beginPath();\n";
        $resp .= $this->_getJavascriptVar() . ".arc($x, $y, $radius, degreesToRadians($startAngle), degreesToRadians($endAngle) );\n";
        $resp .= $this->_getJavascriptVar() . ".stroke();\n";

        return $resp;
    }

    /**
     * Draws an arc between two points.
     *
     * @param array      $source    Source point for the arc.
     * @param array      $from      Point where the arc starts.
     * @param array      $to        Point where the arc stops.
     * @param int|string $radius    Radius of the arc.
     */
    function fillArcTo($source, $from, $to, $radius)
    {
        $resp = "";
        $resp .= $this->_getJavascriptVar() . ".beginPath();\n";
        $resp .= $this->_getJavascriptVar() . ".moveTo(".$source[0] .", ". $source[1] . ");\n";
        $resp .= $this->_getJavascriptVar() . ".arcTo(". $from[0] . ", ". $from[1] . ", " . $to[0] .", ". $to[1] .", $radius);\n";
        $resp .= $this->_getJavascriptVar() . ".stroke();\n";

        return $resp;
    }

    /**
     * Draws a line followed by a quadratic curve to a point.
     *
     * @param int|string $x             Coordinate in the horizontal axis where the line starts.
     * @param int|string $y             Coordinate in the vertical axis where the line starts.
     * @param array      $startPoint    Point where the actual quadratic curve starts.
     * @param int|string $cx            Coordinate in the horizontal axis for the control point of the curve.
     * @param int|string $cy            Coordinate in the vertical axis for the control point of the curve.
     * @param array      $endPoint      Point where the curve ends.
     */
    function quadraticCurveTo ($x, $y, $startPoint, $cx, $cy, $endPoint)
    {
        $resp = "";
        $resp .= $this->_getJavascriptVar() .   ".beginPath();\n";
        $resp .= $this->_getJavascriptVar() .  ".moveTo($x, $y);\n";
        $resp .= $this->_getJavascriptVar() .  ".lineTo($startPoint[0], $startPoint[1]);\n";
        $resp .= $this->_getJavascriptVar() .  ".quadraticCurveTo($cx, $cy, $endPoint[0], $endPoint[1]);\n";
        $resp .= $this->_getJavascriptVar() . ".stroke();\n";
        return $resp;
    }

    /**
     * Draws a rectangle with rounded corners on the canvas.
     *
     * Use RoundRect to draw a rounded rectangle using Pen and fill it with Brush.
     * The rectangle will have edges defined by the points (X1,Y1), (X2,Y1), (X2,Y2), (X1,Y2),
     * but the corners will be shaved to create a rounded appearance. The curve of the rounded
     * corners matches the curvature of an ellipse with width W and height H.
     *
     * To draw an ellipse instead, use Ellipse. To draw a true rectangle, use Rectangle.
     *
     * @param int $x1 The left point at pixel coordinates
     * @param int $y1 The top point at pixel coordinates
     * @param int $x2 The right point at pixel coordinates
     * @param int $y2 The bottom point at pixel coordinates
     */
    function roundRect($sx, $sy, $ex, $ey)
    {

		    $r2d = "(Math.PI/180)";

        $r = ($this->Width > $this->Height) ? (($this->Height-20)*0.15) : (($this->Width-20)*0.15);

        $resp = "";
    		$resp .= $this->_getJavascriptVar() . ".beginPath();\n";
		    $resp .= $this->_getJavascriptVar() . ".moveTo($sx + $r, $sy);\n";
		    $resp .= $this->_getJavascriptVar() . ".lineTo($ex - $r,$sy);\n";
	  	  $resp .= $this->_getJavascriptVar() . ".arc($ex - $r,$sy + $r, $r, $r2d*270, $r2d*360,false);\n";
  			$resp .= $this->_getJavascriptVar() . ".lineTo($ex, $ey - $r); \n";
        $resp .= $this->_getJavascriptVar() . ".arc($ex - $r,$ey -$r,$r,$r2d*0,$r2d*90,false);\n";
        $resp .= $this->_getJavascriptVar() . ".lineTo($sx+$r,$ey); \n";
        $resp .= $this->_getJavascriptVar() . ".arc($sx+$r,$ey-$r,$r,$r2d*90,$r2d*180,false);\n";
        $resp .= $this->_getJavascriptVar() . ".lineTo($sx,$sy + $r);\n";
        $resp .= $this->_getJavascriptVar() . ".arc($sx + $r,$sy + $r,$r,$r2d*180,$r2d*270,false);\n";
        $resp .= $this->_getJavascriptVar() . ".closePath();\n";

        $resp .= $this->forceBrush();
        $resp .= $this->_getJavascriptVar() . ".fill();\n";

        $resp .= $this->forcePen();

        // paint
        $resp .= $this->_getJavascriptVar() . ".stroke();    \n";


        return $resp;
	  }
    /**
     *
     */
    function circle($x, $y, $radius)
    {

        $resp = $this->_getJavascriptVar() .   ".arc($x, $y, $radius, 0, 2 * Math.PI);\n";

        $resp .= $this->forceBrush();
        $resp .= $this->_getJavascriptVar() . ".fill();\n";

        $resp .= $this->forcePen();
        $resp .= $this->_getJavascriptVar() .   ".stroke();\n";

        return $resp;
    }

     /**
     *
     */
    function ellipse( $x = 0, $y= 0, $w, $h)
    {

        $kappa = .5522848;
        $ox = ($w / 2) * $kappa; // control point offset horizontal
        $oy = ($h / 2) * $kappa; // control point offset vertical
        $xe = $x + $w;           // x-end
        $ye = $y + $h;           // y-end
        $xm = $x + $w / 2;       // x-middle
        $ym = $y + $h / 2;       // y-middle

        $resp = "";

        $resp .= $this->_getJavascriptVar() .   ".beginPath();\n";

        $resp .= $this->_getJavascriptVar() .   ".moveTo($x, $ym); \n";
        $resp .= $this->_getJavascriptVar() .   ".bezierCurveTo($x, $ym - $oy, $xm - $ox, $y, $xm, $y);\n";
        $resp .= $this->_getJavascriptVar() .   ".bezierCurveTo($xm + $ox, $y, $xe, $ym - $oy, $xe, $ym);\n";
        $resp .= $this->_getJavascriptVar() .   ".bezierCurveTo($xe, $ym + $oy, $xm + $ox, $ye, $xm, $ye);\n";
        $resp .= $this->_getJavascriptVar() .   ".bezierCurveTo($xm - $ox, $ye, $x, $ym + $oy, $x, $ym);\n";
        $resp .= $this->_getJavascriptVar() .   ".closePath();\n";

        $resp .= $this->forceBrush();
        $resp .= $this->_getJavascriptVar() . ".fill();\n";

        $resp .= $this->forcePen();
        $resp .= $this->_getJavascriptVar() .   ".stroke();\n";

        return $resp;
    }

    /**
     * Draw Bevel-like rectangle using specified colors
     */
    function bevelRect($x1, $y1, $x2, $y2, $color1, $color2)
    {

        $resp = $this->forcePen();

        $resp .= $this->_getJavascriptVar() .   ".strokeStyle = \"" . $color1 . "\";\n";
        $resp .= $this->drawLine(array($x1, $y2), array($x1, $y1));
        $resp .= $this->drawLine(array($x1, $y1), array($x2, $y1));
        $resp .= $this->_getJavascriptVar() .   ".strokeStyle = \"" . $color2 . "\";\n";
        $resp .= $this->drawLine(array($x2, $y1), array($x2, $y2));
        $resp .= $this->drawLine(array($x2, $y2), array($x1, $y2));

        return $resp;
    }

    /**
     * Draw the line using specified color
     */
    function bevelLine($color, $x1, $y1, $x2, $y2)
    {
            $resp = $this->forcePen();
            $resp .= $this->_getJavascriptVar() .   ".strokeStyle = \"" . $color . "\";\n";
            $resp .= $this->drawLine(array($x1, $y1), array($x2, $y2));

            return $resp;
    }

}

/**
 * Rectangular area to draw shapes.
 *
 * To use this component, just define the type of Context you want to use, and use the
 * OnPaint JavaScript event to draw on the canvas.
 *
 * There is also a global JavaScript variable that will hold the context, so you can draw on the canvas
 * from outside the OnPaint event. That variable is called ComponentName_ctx, where ComponentName
 * is the value of the Name property for the canvas component. For example, here we use the context to
 * clear the canvas after clicking a button:
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
 * @link wiki://Canvas
 *
 * @example HTML5/CanvasColorCircles.php
 * @example HTML5/CanvasPlanets.php
 */
class Canvas extends CustomCanvas
{
    // Documented in the parent.
    function getNotSupportedMessage()    {return $this->readNotSupportedMessage();}
    function setNotSupportedMessage($value)    {$this->writeNotSupportedMessage($value);}

    function getContext()    {return $this->readcontext();}
    function setContext($value)    {$this->writecontext($value);}

    // Documented in the parent.
    function getDraggable()    {return $this->readdraggable();}
    function setDraggable($value)    {$this->writedraggable($value);}

    // Documented in the parent.
    function getjsOnPaint()    {return $this->readjsonpaint();}
    function setjsOnPaint($value)    {$this->writejsonpaint($value);}

    // Documented in the parent.
    function getjsOnClick() { return $this->readjsonclick(); }
    function setjsOnClick($value) { $this->writejsonclick($value); }

    // Documented in the parent.
    function getjsOnDblClick() { return $this->readjsondblclick(); }
    function setjsOnDblClick($value) { $this->writejsondblclick($value); }

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

    // Documented in the parent.
    function getjsOnMouseDown() { return $this->readjsonmousedown(); }
    function setjsOnMouseDown($value) { $this->writejsonmousedown($value); }

    // Documented in the parent.
    function getjsOnMouseMove() { return $this->readjsonmousemove(); }
    function setjsOnMouseMove($value) { $this->writejsonmousemove($value); }

    // Documented in the parent.
    function getjsOnMouseOut() { return $this->readjsonmouseout(); }
    function setjsOnMouseOut($value) { $this->writejsonmouseout($value); }

    // Documented in the parent.
    function getjsOnMouseOver() { return $this->readjsonmouseover(); }
    function setjsOnMouseOver($value) { $this->writejsonmouseover($value); }

    // Documented in the parent.
    function getjsOnMouseUp() { return $this->readjsonmouseup(); }
    function setjsOnMouseUp($value) { $this->writejsonmouseup($value); }

    // Documented in the parent.
    function getjsOnMouseWheel() { return $this->readjsonmousewheel(); }
    function setjsOnMouseWheel($value) { $this->writejsonmousewheel($value); }

    // Documented in the parent.
    //function getjsOnScroll() { return $this->readjsonscroll(); }
    //function setjsOnScroll($value) { $this->writejsonscroll($value); }

    // Documented in the parent.
    function getjsOnKeyDown() { return $this->readjsonkeydown(); }
    function setjsOnKeyDown($value) { $this->writejsonkeydown($value); }

    // Documented in the parent.
    function getjsOnKeyPress() { return $this->readjsonkeypress(); }
    function setjsOnKeyPress($value) { $this->writejsonkeypress($value); }

    // Documented in the parent.
    function getjsOnKeyUp() { return $this->readjsonkeyup(); }
    function setjsOnKeyUp($value) { $this->writejsonkeyup($value); }

}



define('fqLow', 'fqLow');
define('fqAutoLow', 'fqAutoLow');
define('fqAutoHigh', 'fqAutoHigh');
define('fqMedium', 'fqMedium');
define('fqHigh', 'fqHigh');
define('fqBest', 'fqBest');


/**
 * A class to encapsulate a Flash animation.
 *
 * This control may be used to include a flash animation into a page.
 * Use the property SwfFile to point to the URL of the flash file you want this
 * component to show.
 *
 */
class FlashObject extends GraphicControl
{
   protected $_swffile;

   function __construct($aowner = null)
   {
      //Calls inherited constructor
      parent::__construct($aowner);

      $this->Width = 105;
      $this->Height = 105;

   }

   function getColor()    {return $this->readcolor();}
   function setColor($value)    {$this->writecolor($value);}


   function getVisible()    {return $this->readVisible();}
   function setVisible($value)    {$this->writeVisible($value);}

   /**
    * Location of the Flash file (*.swf).
    * Path can be relative to the script or absolute.
    * @return string
    */
   function getSwfFile()    {return $this->_swffile;}
   function setSwfFile($value)    {$this->_swffile = $value;}

   protected $_active = 1;

   /**
    * Whether the Flash object should be played upon load (1) or not (0).
    */
   function getActive()    {return $this->_active;}
   function setActive($value)    {$this->_active = $value;}
   function defaultActive()    {return 1;}

   protected $_loop = 1;

   /**
    * Whether the Flash object should be played just once (0) or in a loop (1).
    */
   function getLoop()    {return $this->_loop;}
   function setLoop($value)    {$this->_loop = $value;}
   function defaultLoop()    {return 1;}

   protected $_quality = fqHigh;

   /**
    * Graphic quality the Flash object should be rendered with.
    *
    * A higher quality means that antialiasing (which smoothes graphics, making them less pixelated) is applied to
    * more elements in the Flash object. Since antialiasing requires additional processing, the higher the quality,
    * the lower the performance.
    *
    * There are four quality values that define the level of antialiasing, from none to the higher: fqLow, fqMedium,
    * fqHigh, fqBest.
    *
    * There two additional values that will adjust the level of antialiasing automatically depending on the
    * performance during the playback:
    * - fqAutoHigh. Starts with a high antialiasing, and then adjusts it based on the playback performance.
    * - fqAutoLow. Starts without antialiasing, and then adjusts it based on the playback performance.
    */
   function getQuality()    {return $this->_quality;}
   function setQuality($value)    {$this->_quality = $value;}
   function defaultQuality()    {return fqHigh;}

   function dumpContents()
   {
      if(($this->ControlState & csDesigning) == csDesigning)
      {
         $attr = "";
         if($this->_width != "")
            $attr .= " width=\"$this->_width\" ";
         if($this->_height != "")
            $attr .= " height=\"$this->_height\" ";

         $font = ($this->_parent != null)? $this->_parent->Font->FontString: "";

         $bstyle = " style=\"border: 1px dotted #000000; text-align: center; $font\" ";
         echo "<table $attr $bstyle><tr><td>" . basename($this->_swffile) . "</td></tr></table>\n";
      }
      else
      {
         $this->callEvent('onshow', array());

          //classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
          //codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
         echo "<OBJECT id=\"$this->_name\" width=\"$this->_width\" height=\"$this->_height\" data=\"$this->_swffile\">";

         if($this->_active)
            echo '<param name="play" value="true">';
         else
            echo '<param name="play" value="false">';

         if($this->_loop)
            echo '<param name="loop" value="true">';
         else
            echo '<param name="loop" value="false">';

         $quality = 'high';
         switch($this->_quality)
         {
            case fqLow: $quality = 'low';break;
            case fqAutoLow: $quality = 'autolow';break;
            case fqAutoHigh: $quality = 'autohigh';break;
            case fqMedium: $quality = 'medium';break;
            case fqHigh: $quality = 'high';break;
            case fqBest: $quality = 'best';break;
         }
         $src = str_replace(' ', '%20',$this->_swffile);
         echo "<PARAM NAME=\"quality\" VALUE=\"$quality\">";
         echo "<PARAM NAME=\"bgcolor\" VALUE=\"$this->_color\">";
         echo "<EMBED src=\"$this->_swffile\" quality=\"$quality\" bgcolor=\"$this->_color\" WIDTH=\"$this->_width\" HEIGHT=\"$this->_height\" ";
         echo " TYPE=\"application/x-shockwave-flash\" ";

         if($this->_active)
            echo ' play="true" ';
         else
            echo ' play="false" ';

         if($this->_loop)
            echo ' loop="true" ';
         else
            echo ' loop="false" ';

         echo 'PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" />';

         echo "</OBJECT>";
      }

   }
}


define('skRectangle', 'skRectangle');
define('skCircle', 'skCircle');
define('skDefault', 'skDefault');

/**
 * Shape that can be placed on an image to make the covered area interactive, so it can work as a link and provide
 * JavaScript events.
 */
class MapShape extends Control
{
   //TODO: Add more shape types

   protected $_kind = skRectangle;
   protected $_link = "#";

   protected $_onclick = null;
   protected $_ondblclick = null;

   function __construct($aowner = null)
   {
      //Calls inherited constructor
      parent::__construct($aowner);

      $this->Width = 20;
      $this->Height = 20;

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

         if($this->_ondblclick != null && $submitEventValue->asString() == $this->readJSWrapperSubmitEventValue($this->_ondblclick))
         {
            $this->callEvent('ondblclick', array());
         }
      }
   }

   function dumpJavascript()
   {
      parent::dumpJavascript();

      if($this->_onclick != null &&  ! defined($this->_onclick))
      {
         // only output the same function once;
         // otherwise if for example two labels use the same
         // OnClick event handler it would be outputted twice.
         $def = $this->_onclick;
         define($def, 1);

         // output the wrapper function
         echo $this->getJSWrapperFunction($this->_onclick);
      }

      if($this->_ondblclick != null &&  ! defined($this->_ondblclick))
      {
         $def = $this->_ondblclick;
         define($def, 1);

         // output the wrapper function
         echo $this->getJSWrapperFunction($this->_ondblclick);
      }
   }

   protected $_target = "";

   /**
    * Where to open the linked document.
    *
    * Supported values are:
    * - _blank. Open the linked document in a new tab or window.
    * - _self. Open the linked document in the same frame where it was clicked.
    * - _parent. Open the linked document in the parent frame.
    * - _top. Open the linked document in the full body of the window.
    *
    * You can additionally set the property to the identifier of an iframe to open the linked document in that iframe.
    */
   function getTarget()    {return $this->_target;}
   function setTarget($value)    {$this->_target = $value;}
   function defaultTarget()    {return "";}

   // Documented in the parent.
   function dumpContents()
   {

      if(($this->ControlState & csDesigning) == csDesigning)
      {
         $attr = "";
         if($this->_width != "")
            $attr .= " width=\"$this->_width\" ";
         if($this->_height != "")
            $attr .= " height=\"$this->_height\" ";

         $bstyle = " style=\"border: 1px dotted #000000\" ";
         echo "<table $attr $bstyle><tr><td>\n";
      }

      $l = $this->_left;
      $t = $this->_top;
      $w = $this->_left + $this->_width;
      $h = $this->_top + $this->_height;
      $centerx = $this->_left + $this->_width / 2;
      $centery = $this->_top + $this->_height / 2;
      $minimum = $this->_width >= $this->_height? $this->_width: $this->_height;
      $radius = $minimum / 2;

      $events = $this->readJsEvents();

      $shape = "";
      $coords = "";

      switch($this->_kind)
      {
         case skRectangle:
            $shape = 'rect';
            $coords = "$l,$t,$w,$h";
            break;
         case skCircle:
            $shape = 'circle';
            $coords = "$centerx,$centery,$radius";
            break;
         case skDefault:
            $shape = 'default';
            break;
         default:
            exit('Shape kind not valid.');

      }

      $target = "";
      if($this->_target != '')
         $target = ' target="' . $this->_target . '" ';

      // add or replace the JS events with the wrappers if necessary
      $this->addJSWrapperToEvents($events, $this->_onclick, $this->_jsonclick, "onclick");
      $this->addJSWrapperToEvents($events, $this->_ondblclick, $this->_jsondblclick, "ondblclick");

      //$hint = $this->Hint != "" & $this->ShowHint? $this->Hint: "";

      $hint = $this->HintAttribute();
      $hint .= " alt=\"" . htmlspecialchars($this->_hint, ENT_QUOTES) . "\"";

      $coords_final =  ($coords != "") ? "coords=\"$coords\"" : "";

      echo "<area id=\"$this->_name\" shape=\"$shape\" $coords_final $hint href=\"$this->_link\" $target $events />\n";

      if(($this->ControlState & csDesigning) == csDesigning)
      {
         echo "</table>\n";
      }




      // add a hidden field so we can determine which event for the Paintbox was fired
      if($this->_onclick != null || $this->_ondblclick != null)
      {
         $hiddenwrapperfield = $this->readJSWrapperHiddenFieldName();
         echo "<input type=\"hidden\" id=\"$hiddenwrapperfield\" name=\"$hiddenwrapperfield\" value=\"\" />";
      }


   }

   // Javascript events.

   // Documented in the parent.
   function getjsOnMouseOut()    {return $this->readjsOnMouseOut();}
   function setjsOnMouseOut($value)    {$this->writejsOnMouseOut($value);}

   // Documented in the parent.
   function getjsOnMouseOver()    {return $this->readjsOnMouseOver();}
   function setjsOnMouseOver($value)    {$this->writejsOnMouseOver($value);}

   // Documented in the parent.
   function getjsOnClick()    {return $this->readjsOnClick();}
   function setjsOnClick($value)    {$this->writejsOnClick($value);}

   // Documented in the parent.
   function getjsOnDblClick()    {return $this->readjsOnDblClick();}
   function setjsOnDblClick($value)    {$this->writejsOnDblClick($value);}


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
    */
   function getOnClick()    {return $this->_onclick;}
   function setOnClick($value)    {$this->_onclick = $value;}
   function defaultOnClick()    {return null;}

   /**
    * Triggered by a double click in the surface of the control with the mouse pointer.
    */
   function getOnDblClick()    {return $this->_ondblclick;}
   function setOnDblClick($value)    {$this->_ondblclick = $value;}
   function defaultOnDblClick()    {return null;}

   /**
    * Specifies the type of shape to create on the Image
    *
    * Use this property to change the type of shape to create inside the
    * Image. You can create a rectangle or a circle inside the control bounds
    *
    * @return enum (skRectangle, skCircle, skDefault)
    */
   function getKind()    {return $this->_kind;}
   function setKind($value)    {$this->_kind = $value;}
   function defaultKind()    {return skRectangle;}


   /**
    * The link to point the user to when clicking on the shape
    *
    * Use this property to set the link the user will go when clicking on the
    * shape.
    *
    * @return string
    */
   function getLink()    {return $this->_link;}
   function setLink($value)    {$this->_link = $value;}
   function defaultLink()    {return "#";}

   function getParentShowHint()    {return $this->readparentshowhint();}
   function setParentShowHint($value)    {$this->writeparentshowhint($value);}

   function getShowHint()    {return $this->readshowhint();}
   function setShowHint($value)    {$this->writeshowhint($value);}

}

define('bpLeftTop', 'bpLeftTop');
define('bpLeftCenter', 'bpLeftCenter');
define('bpLeftBottom', 'bpLeftBottom');
define('bpRightTop', 'bpRightTop');
define('bpRightCenter', 'bpRightCenter');
define('bpRightBottom', 'bpRightBottom');
define('bpCenterTop', 'bpCenterTop');
define('bpCenterCenter', 'bpCenterCenter');
define('bpCenterBottom', 'bpCenterBottom');

/**
 * Base class for containers that can be used as grouping areas for controls.
 */
class CustomPanel extends CustomControl
{
   protected $_include = "";

   protected $_background = "";
   protected $_backgroundrepeat = "";
   protected $_backgroundposition = "";

   function __construct($aowner = null)
   {
      //Calls inherited constructor
      parent::__construct($aowner);
      $this->ControlStyle = "csAcceptsControls=1";

      $this->ControlStyle = "csRenderOwner=1";
      $this->ControlStyle = "csRenderAlso=StyleSheet";
   }

   protected $_activelayer = 0;

   /**
    * Currently active layer in this container, so only the controls with their Layer property set to the active
    * layer defined here are visible.
    *
    * @see Control::getLayer()
    *
    * @return string
    */
   function getActiveLayer()    {return $this->_activelayer;}
   function setActiveLayer($value)    {$this->_activelayer = $value;}
   function defaultActiveLayer()    {return 0;}

   /**
    * CSS background property.
    *
    * For example, to define a background image, use "url('path/to/the/image')"; for a color, you can use web color
    * names (red, green, etc.), hexadecimal notation (#000000 for black) or short hexadecimal notation (#000 for black).
    *
    * @link http://en.wikipedia.org/wiki/Web_colors Web colors
    *
    * @see readBackgroundRepeat(), readBackgroundPosition()
    *
    * @return string
    */
   function readBackground()    {return $this->_background;}
   function writeBackground($value)    {$this->_background = $value;}
   function defaultBackground()    {return "";}

   /**
    * CSS background-repeat property, which determines how the image brackground is repeated inside the control.
    *
    * The possible values are:
    * - repeat. The image is repeated both horizontally and vertically.
    * - repeat-x. The image is repeated horizontally only.
    * - repeat-y. The image is repeated vertically only.
    * - no-repeat. The image is not repeated.
    *
    * @see readBackground(), readBackgroundPosition()
    *
    * @return string
    */
   function readBackgroundRepeat()    {return $this->_backgroundrepeat;}
   function writeBackgroundRepeat($value)    {$this->_backgroundrepeat = $value;}
   function defaultBackgroundRepeat()    {return "";}

   /**
    * CSS background-position property, which determines how the image brackground is positioned inside the control.
    *
    * Check the CSS documentation resources for possible values.
    *
    * @link wiki://CSS
    *
    * @see readBackground(), readBackgroundRepeat()
    *
    * @return string
    */
   function readBackgroundPosition()    {return $this->_backgroundposition;}
   function writeBackgroundPosition($value)    {$this->_backgroundposition = $value;}
   function defaultBackgroundPosition()    {return "";}

   /**
    * Something to be included during the rendering of the component, such as another server-side script.
    *
    * You can define anything supported by the include() (http://php.net/manual/en/function.include.php) function.
    *
    * @return string
    */
   function readInclude()    {return $this->_include;}
   function writeInclude($value)    {$this->_include = $value;}
   function defaultInclude()    {return "";}

   /**
    * Whether to use an HTML table between the main div of the container and its content (1), or not (0).
    *
    * @internal
    */
   public $usetables = 0;

   /**
    * Prints the contents of the container using the defined Layout, and wrapping them around a table if
    * $this->usetables is not 0.
    *
    * @internal
    */
   function dumpLayoutContents()
   {
        if($this->usetables)
        {
            $class="";
            echo "<table id=\"{$this->_name}_table\">\n";
            echo "  <tr>\n";

            if((($this->ControlState & csDesigning) == csDesigning) || ($this->controls->count() == 0))
            {
                echo "<td $class><span $class>$this->Caption</span>\n";
            }
            else
            {
                echo "<td>\n";
            }
        }

        if(($this->ControlState & csDesigning) != csDesigning)
        {
            $this->callEvent('onshow', array());
            $this->Layout->dumpLayoutContents();
        }

        if($this->usetables)
        {
            echo "    </td>\n";
            echo " </tr>\n";
            echo " </table>\n";
        }

   }

    // Documented in the parent.
    function dumpContents()
    {

        if($this->_include != "")
        {
            include($this->_include);
        }
        else
        {

            $class = ($this->Style != "")? "class=\"$this->StyleClass\"": "";
            $hint = $this->HintAttribute();
            $draggable = ($this->_draggable) ? "draggable=\"true\"" : "";
            $events = $this->readJsEvents();

            echo "<div id=\"$this->_name\" $hint $class $draggable $events >\n";
            echo $this->dumpLayoutContents();
            echo "</div>\n";
        }

   }

   /**
    * Prints the CSS code for the table that wraps the contents of the container, if any.
    *
    * @see dumpLayoutContents()
    *
    * @internal
    */
   protected function dumpLayoutCSS()
   {

        if($this->usetables)
        {
            echo "{$this->readCSSDescriptor()}_table {\n";

            ///TODO: temporary fix, the design mode shows a scroll
            if(($this->ControlState & csDesigning) == csDesigning)
                echo "height: " . ($this->_height-2). "px;\n";
            else
            {
                echo $this->_readCSSSize();
            }

            echo "border-spacing: 0;\n";
            echo "border: 0;\n";
            echo "border-collapse: collapse;\n";
            echo "box-sizing: border-box;\n";
            echo "}\n";
        }

   }

   function dumpAdditionalCSS()
   {
        parent::dumpAdditionalCSS();

        $this->dumpLayoutCSS();

        if($this->_include != "")
        {
         //include($this->_include);
        }
        else
        {
            if(($this->ControlState & csDesigning) != csDesigning)
            {
                $this->Layout->dumpLayoutContents();
            }

            // only design font visualization (fix to see in IDE)
            if(($this->ControlState & csDesigning) == csDesigning)
            {
                echo "table{\n";
                echo $this->Font->FontString;
                echo "}\n";
            }
        }
   }

   /**
    * Returns the value of the BackgroundPosition as a valid CSS value for the
    * 'background-position' style attribute.
    *
    * @internal
    */
   protected function parseBackgroundPosition()
   {
        switch($this->_backgroundposition)
        {
            case bpLeftTop:      return 'left top';
            case bpLeftCenter:   return 'left center';
            case bpLeftBottom:   return 'left bottom';

            case bpRightTop:     return 'right top';
            case bpRightCenter:  return 'right center';
            case bpRightBottom:  return 'right bottom';

            case bpCenterTop:    return 'center top';
            case bpCenterCenter: return 'center center';
            case bpCenterBottom: return 'center bottom';

            default: return $this->_backgroundposition;
        }
   }

    function dumpCSS()
    {
        // show dotted border at design time
        if(($this->ControlState & csDesigning) == csDesigning)
        {
            if($this->Style == "")
            {
               echo "border: 1px dotted #000000;\n";
            }
        }
        // position
        if($this->_islayer)
        {
            echo "position: absolute;\n";
            echo "visibility: hidden;\n";
            echo "top: " . $this->_top . "px;\n";
            echo "left:" . $this->_left . "px;\n";
        }

        echo $this->_readCSSSize();

        // fix borders, spacings and margins
        echo "box-sizing: border-box;\n";
        echo "margin: 0;\n";
        echo "padding: 0;\n";

        if($this->_hidden)
        {
          echo "visibility: hidden;\n";
        }

        // dump style
        if($this->Style == "")
        {
            if($this->Color != "")
                echo "background-color:$this->Color;\n";
            if($this->Background != "")
                echo "background:$this->Background;\n";

            if($this->BackgroundRepeat != "")
                echo "background-repeat: $this->BackgroundRepeat;\n";
            if($this->BackgroundPosition != "")
                echo "background-position: {$this->parseBackgroundPosition()};\n";

            if ($this->_cursor != "")
            {
                echo $this->parseCSSCursor();
            }

            // only design font visualization
            if(($this->ControlState & csDesigning) == csDesigning)
            {
                echo $this->Font->FontString;
            }

            parent::dumpCSS();
        }
    }


}


/**
 * A container to group controls together, with properties and methods to help you manage the placement of its child
 * controls.
 *
 * When you change the position of the container, the position of the contained components changes too. It can be
 * also useful when child components inherit properties from it, like Font or
 * Color.
 *
 * You can use this container, for example, to implement a tool bar.
 */
class Panel extends CustomPanel
{
    // Documented in the parent.
    function __construct($aowner = null)
    {
        //Calls inherited constructor
        parent::__construct($aowner);
    }

    // Documented in the parent.
    function getFont()    {return $this->readFont();}
    function setFont($value)    {$this->writeFont($value);}

    // Documented in the parent.
    function getParentFont()    {return $this->readParentFont();}
    function setParentFont($value)    {$this->writeParentFont($value);}

    // Documented in the parent.
    function getParentColor()    {return $this->readParentColor();}
    function setParentColor($value)    {$this->writeParentColor($value);}

    // Documented in the parent.
    function getParentShowHint()    {return $this->readParentShowHint();}
    function setParentShowHint($value)    {$this->writeParentShowHint($value);}

    // Documented in the parent.
    function getShowHint()    {return $this->readShowHint();}
    function setShowHint($value)    {$this->writeShowHint($value);}

    // Documented in the parent.
    function getAlignment()    {return $this->readAlignment();}
    function setAlignment($value)    {$this->writeAlignment($value);}

    // Documented in the parent.
    function getCaption()    {return $this->readCaption();}
    function setCaption($value)    {$this->writeCaption($value);}

    // Documented in the parent.
    function getColor()    {return $this->readColor();}
    function setColor($value)    {$this->writeColor($value);}

    // Documented in the parent.
    function getVisible()    {return $this->readVisible();}
    function setVisible($value)    {$this->writeVisible($value);}

    // Documented in the parent.
    function getBackground()    {return $this->readBackground();}
    function setBackground($value)    {$this->writeBackground($value);}

    // Documented in the parent.
    function getBackgroundRepeat()    {return $this->readBackgroundRepeat();}
    function setBackgroundRepeat($value)    {$this->writeBackgroundRepeat($value);}

    // Documented in the parent.
    function getBackgroundPosition()    {return $this->readBackgroundPosition();}
    function setBackgroundPosition($value)    {$this->writeBackgroundPosition($value);}

    // Documented in the parent.
    function getLayout()    {return $this->readLayout();}
    function setLayout($value)    {$this->writeLayout($value);}

    // Documented in the parent.
    function getInclude()    {return $this->readInclude();}
    function setInclude($value)    {$this->writeInclude($value);}

    // Documented in the parent.
    function getIsLayer()    {return $this->readIsLayer();}
    function setIsLayer($value)    {$this->writeIsLayer($value);}

    // Documented in the parent.
    function getStyle()    {return $this->readstyle();}
    function setStyle($value)    {$this->writestyle($value);}

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

define('orHorizontal', 'orHorizontal');
define('orVertical', 'orVertical');

/**
 * CustomRadioGroup is the base class for radio-group components.
 * When the user checks a radio button, all other radio buttons in its group become unchecked.
 *
 */
class CustomRadioGroup extends FocusControl
{
   protected $_onclick = null;
   protected $_onsubmit = null;

   protected $_datasource = null;
   protected $_datafield = "";
   protected $_itemindex =  -1;
   protected $_items = array();
   protected $_orientation = orVertical;
   protected $_taborder = 0;
   protected $_tabstop = 1;
   protected $_columns = 1;

   function __construct($aowner = null)
   {
      //Calls inherited constructor
      parent::__construct($aowner);

      $this->Clear();

      $this->Width = 185;
      $this->Height = 89;

      $this->ControlStyle = "csRenderOwner=1";
      $this->ControlStyle = "csRenderAlso=StyleSheet";
   }

   function loaded()
   {
      parent::loaded();
      $this->writeDataSource($this->_datasource);
   }

    function preinit()
    {
        $submitted = $this->input->{$this->Name};

        if(is_object($submitted))
        {
            $changed = ($this->_itemindex != $submitted->asString());
            // the ItemIndex might be an integer or a string,
            // so let's get a string
            $this->_itemindex = $submitted->asString();

            // only update the data field if the item index was changed
            if($changed)
            {
                // following somehow does not work here:
                //   if (array_key_exists($this->_itemindex, $this->_items)) { $this->updateDataField($this->_items[$this->_itemindex]); }
                // so let's do it like this...
                foreach($this->_items as $key=>$item)
                {
                    if($key == $this->_itemindex)
                    {
                        //If there is any valid DataField attached, update it
                        $this->updateDataField($item);
                    }
            }
         }
      }
    }

    function init()
    {
      parent::init();

      $submitted = $this->input->{$this->Name};

      if(is_object($submitted))
      {
         // Allow the OnSubmit event to be fired because it is not
         // a mouse or keyboard event.
         if($this->_onsubmit != null)
         {
            $this->callEvent('onsubmit', array());
         }

      }

      $submitEvent = $this->input->{$this->readJSWrapperHiddenFieldName()};

      if (is_object($submitEvent) && $this->_enabled == 1)
      {
         // check if the a click event has been fired
         if($this->_onclick != null && $submitEvent->asString() == $this->readJSWrapperSubmitEventValue($this->_onclick))
         {
            $this->callEvent('onclick', array());
         }
      }
    }


    function dumpAdditionalCSS()
    {
        $style = "";
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

        $spanstyle = $style;

        $h = $this->Height - 2;
        $w = $this->Width;

        // TODO: fix with boxsizing
        if($this->isFixedSize())
        {
            $style .= "height:" . $h . "px;\n" .
                      "width:" . $w . "px;\n";
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

  			  if ($this->_orientation == orHorizontal)
  			  {
  				    $spanstyle .= "width:{$columnsWidth}px;";
  			  }

              $rowHeight =  $this->calculateRowHeight();
  			  $itemWidth = $columnsWidth-20;

  				echo ".td_with_radio {\n";
  				echo "height:{$rowHeight}px;\n";
  				echo "width: 20px;\n";
  				echo "}\n";

  				echo ".td_with_span {\n";
  				echo "overflow:hidden;\n";
  				echo "white-space:nowrap;\n";
  				echo "width: {$itemWidth}px;\n";
  				echo "height:{$rowHeight}px;\n";
  				echo $alignment;
  				echo "}\n";

  				echo $tcss." span {\n";
  				echo "white-space:nowrap;\n";
  				echo $spanstyle;
  				echo "}\n";
  		}

    }

    /**
     * Returns the width of each column of the control, in pixels.
     *
     * @internal
     */
    protected function calculateColumnsWidth()
    {
        if ($this->_columns > 0)
            return ($this->Width / $this->_columns);
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
        $numItems = count($this->items);
        if ($this->_columns > 0)
            return ceil($numItems / $this->_columns);
        else
            return 1;

    }

    /**
     * Returns the height of each row of the control, in pixels.
     *
     * @internal
     */
    protected function calculateRowHeight()
    {
      return $this->Height / $this->calculateItemsPerColumn();
    }

    function dumpContents()
    {
      $events = "";
      if($this->_enabled == 1)
      {
         // get the string for the JS Events
         $events = $this->readJsEvents();

         // add or replace the JS events with the wrappers if necessary
         $this->addJSWrapperToEvents($events, $this->_onclick, $this->_jsonclick, "onclick");
      }

      $h = $this->Height - 2;
      $w = $this->Width;

      // set enabled/disabled status
      $enabled = ( ! $this->_enabled)? "disabled=\"disabled\"": "";

      // set tab order if tab stop set to true
      $taborder = ($this->_tabstop == 1)? "tabindex=\"$this->_taborder\"": "";

      // get the hint attribute; returns: title="[HintText]"
      $hint = $this->HintAttribute();

      if(($this->ControlState & csDesigning) != csDesigning)
      {
         if($this->hasValidDataField())
         {
            //check if the value of the current data-field is in the itmes array as value
            $val = $this->readDataFieldValue();

            // get the corresponding key to the value read from the data source
            if(($key = array_search($val, $this->_items)) !== FALSE)
            {
               // if an item was found the overwrite the itemindex
               $this->_itemindex = $key;
            }

            //Dumps hidden fields to know which is the record to update
            $this->dumpHiddenKeyFields();
         }
      }

      $class = ($this->Style != "")? "class=\"$this->StyleClass\"": "";

      // call the OnShow event if assigned so the Items property can be changed
      if($this->_onshow != null)
      {
         $this->callEvent('onshow', array());
      }

      $hinttext = $this->_hint != $this->defaultHint() && $this->ShowHint == true? $this->_hint: $this->defaultHint();

      //get the extra attributes for the component and its children
      $nestedattributes = $this->strNestedAttributes();
      $attributes = $this->strAttributes();

      //let's check if it is a MRadioButtonGroup
      $Mbox = array_key_exists("data-role", $this->_attributes);

      echo "<table id=\"{$this->_name}_table\"  title=\"$hinttext\"  $class $attributes>";

      if(is_array($this->_items) && count($this->_items) > 0)
      {
         $index = 0;

         //Avoid div by 0
         $numItems = count($this->items);
         //if ($this->_orientation == orHorizontal)
         //      $this->_columns = $numItems;

         $columnsWidth = $this->calculateColumnsWidth(); //$w / $this->_columns;

         $itemsPerColumn = $this->calculateItemsPerColumn(); //ceil($numItems / $this->_columns);

         $rowHeight = $h / $itemsPerColumn;

         $itemsPerRow = ceil($numItems / $itemsPerColumn);

         $itemWidth = $columnsWidth - 20;

         for($row = 0; $row < $itemsPerColumn; ++$row)
         {
            echo "<tr>\n";

            for($column = 0; $column < $itemsPerRow; ++$column)
            {
               if($Mbox && $this->_enhancement != "enNone")
                  echo "<td class=\"ui-corner-all\"  >";  //TODO -oPerez -cDelete -ACommented because it is not used in CSS code echo "<td class=\"td_with_radio_mobile\"  >";
               else
                  echo "<td class=\"td_with_radio\" >";

               //do we have more items to place in this <td>?
               $key = $row + ($itemsPerColumn * $column);

               if ( $key < $numItems)
               {
                  $item = $this->_items[$key];
                  // add the checked attribut if the itemindex is the current item
                  $checked = ($this->_itemindex == $key)? "checked=\"checked\"": "";
                  // only allow an OnClick if enabled
                  $itemclick = ($this->_enabled == 1 && $this->Owner != null)? "onclick=\"return RadioGroupClick(document.forms[0].$this->_name, $index);\"": "";

                  echo "<input type=\"radio\" id=\"{$this->_name}_{$key}\" name=\"$this->_name\" value=\"$key\" $events $checked $enabled $taborder $hinttext $class $nestedattributes />\n";
                  $itemWidth = $columnsWidth - 20;
                  //ie needs cell style just in a span inside <td>, firefox needs them in the <td> amazing...
                  if($Mbox && $this->_enhancement != "enNone")
                  {
                     echo "<label  for=\"{$this->_name}_{$key}\" id=\"{$this->_name}_{$key}_caption\" $hinttext  $class>$item</label>\n";
                  }
                  else
                  {
                     echo "</td>";
                     echo "<td class=\"td_with_span\"  >\n";
                     echo "<span id=\"{$this->_name}_{$key}_caption\"  $itemclick $hinttext $class>$item</span>\n";
                  }
               }
               echo "</td>\n";
               $index++;
            }
            echo "</tr>\n";
         }

      }
      echo "</table>";

   }


   function dumpFormItems()
   {
      // add a hidden field so we can determine which radiogroup fired the event
      if($this->_onclick != null)
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

      if($this->_enabled == 1)
      {
         if($this->_onclick != null &&  ! defined($this->_onclick))
         {
            // only output the same function once;
            // otherwise if for example two radio groups use the same
            // OnClick event handler it would be outputted twice.
            $def = $this->_onclick;
            define($def, 1);

            // output the wrapper function
            echo $this->getJSWrapperFunction($this->_onclick);
         }

         // only output the function once
         if( ! defined('RadioGroupClick'))
         {
            define('RadioGroupClick', 1);

            echo "
function RadioGroupClick(elem, index)
{
   if (!elem.disabled) {
     if (typeof(elem.length) == 'undefined') {
       elem.checked = true;
       return (typeof(elem.onclick) == 'function') ? elem.onclick() : false;
     } else {
       if (index >= 0 && index < elem.length) {
         elem[index].checked = true;
         return (typeof(elem[index].onclick) == 'function') ? elem[index].onclick() : false;
       }
     }
   }
   return false;
}
";
         }
      }
   }


   /**
    * number of itens in the radio group, it returns the count of the internal
    * items array
    *
    * @return integer
    */
   function readCount()
   {
      return count($this->_items);
   }

   /**
    * Adds an item to the radio group control.
    *
    * @param mixed $item Value of item to add.
    * @param mixed $object Object to assign to the $item. is_object() is used to
    *                      test if $object is an object.
    * @param mixed $itemkey Key of the item in the array. Default key is used if null.
    * @return integer Return the number of items in the list.
    */
   function AddItem($item, $object = null, $itemkey = null)
   {
      if($object != null)
      {
         throw new Exception('Object functionallity for RadioGroup is not yet implemented.');
      }

      //Set the array to the end
      end($this->_items);

      //Adds the item as the last one
      if($itemkey != null)
      {
         $this->_items[$itemkey] = $item;
      }
      else
      {
         $this->_items[] = $item;
      }

      return ($this->Count);
   }

   /**
    * Deletes all of the items from the list control.
    */
   function Clear()
   {
      $this->_items = array();
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
   function readOnClick()    {return $this->_onclick;}
   function writeOnClick($value)    {$this->_onclick = $value;}
   function defaultOnClick()    {return null;}

   /**
    * Occurs when the control was submitted.
    *
    * Use this event to react when the form is submitted and the control
    * is about to update its contents using the user input
    *
    * @return mixed Returns the event handler or null if no handler is set.
    */
   function readOnSubmit()    {return $this->_onsubmit;}
   function writeOnSubmit($value)    {$this->_onsubmit = $value;}
   function defaultOnSubmit()    {return null;}

   /**
    * DataField is the fieldname to be attached to the control.
    *
    * This property allows you to show/edit information from a table column
    * using this control. To make it work, you must also assign the Datasource
    * property, which specifies the dataset that contain the fieldname to work on
    *
    * @return string
    */
   function readDataField()    {return $this->_datafield;}
   function writeDataField($value)    {$this->_datafield = $value;}
   function defaultDataField()    {return "";}

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
   function writeDataSource($value)
   {
      $this->_datasource = $this->fixupProperty($value);
   }
   function defaultDataSource()    {return null;}

   /**
    * Returns the value of the ItemIndex property.
    *
    * Use this property to get/set the index of the radio button in the
    * radio group that is selected. Use it at design-time to specify the
    * default radio button selection and use it in run-time to get the
    * user selection.
    *
    * @return mixed Return the ItemIndex of the list.
    */
   function readItemIndex()    {return $this->_itemindex;}
   function writeItemIndex($value)    {$this->_itemindex = $value;}
   function defaultItemIndex()    {return -1;}

   /**
    * Contains the strings that appear in the radio group, use the AddItem
    * method to add a new one or assign a new structure array
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
         $this->_items = (empty($value))? array(): array($value);
      }
   }
   function defaultItems()    {return array();}

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
   function readTabOrder()    {return $this->_taborder;}
   function writeTabOrder($value)    {$this->_taborder = $value;}
   function defaultTabOrder()    {return 0;}

   /**
    * Enable or disable the TabOrder property. The browser may still assign
    * a TabOrder by itself internally. This cannot be controlled by HTML.
    * @return bool
    */
   function readTabStop()    {return $this->_tabstop;}
   function writeTabStop($value)    {$this->_tabstop = $value;}
   function defaultTabStop()    {return 1;}


    /**
     * Updates the value of the Columns property.
     *
     * @internal
     */
    protected function updateColumns($value)
    {
        if ($this->_orientation == orVertical)
        {
            if ($value > 0)
              $this->_columns = $value;
            else
              $this->_columns = 1;
        }
        else
        {
            $columns = count($this->_items);

            if ($columns > 0)
              $this->_columns = $columns;
            else
              $this->_columns = 1;
        }

    }

   /**
    * Sets the radiogroup layout to use this number of columns.
    */
   function getColumns()            {return $this->_columns;}
   function setColumns($value){$this->writeColumns($value);}
   function writeColumns($value){

        // in loading time doesn't do nothing
        if (($this->ControlState & csLoading) == csLoading)
        {
            $this->_columns = $value;
        }
        else
        {
            $this->updateColumns($value);
        }
    }
   function defaultColumns()        {return 1;}

   /**
    * Indicate the orientation of the items
    */
   function readOrientation()           { return $this->_orientation;}
   function writeOrientation($value)    {

      $this->_orientation=$value;

      // in loading time doesn't do nothing
        if (($this->ControlState & csLoading) == csLoading)
        {
            //...
        }
        else
        {
            global $allowupdates;

            // at design we only update the columns if the orientation property has been setted
            if (($this->ControlState & csDesigning) == csDesigning && !$allowupdates)
            {
                return;
            }
            // at runtime (normal execution) update the columns
            else
            {
                if($this->_orientation == orVertical)
                    $this->updateColumns(1);
                else
                    $this->updateColumns($this->_columns);
            }
        }
    }

   function defaultOrientation(){ return orVertical;}
}

/**
 * RadioGroup represents a group of radio buttons that function together.
 *
 * A RadioGroup object is a special group box that contains only radio buttons.
 * Radio buttons that are placed directly in the same control component are said
 * to be "grouped". When the user checks a radio button, all other radio buttons
 * in its group become unchecked. Hence, two radio buttons on a form can be
 * checked at the same time only if they are placed in separate containers,
 * such as group boxes.
 *
 * To add radio buttons to a TRadioGroup, edit the Items property in the Object
 * Inspector. Each string in Items makes a radio button appear in the group box
 * with the string as its caption. The value of the ItemIndex property determines
 * which radio button is currently selected.
 *
 * Display the radio buttons in a single column or in multiple columns by setting the
 * Columns property.
 *
 */
class RadioGroup extends CustomRadioGroup
{

     function getOrientation()            {return $this->readOrientation();}
     function setOrientation($value)      {$this->writeOrientation($value);}

    /*
    * Publish the events for the CheckBox component
    */
    function getOnClick()    {return $this->readOnClick();}
    function setOnClick($value)    {$this->writeOnClick($value);}

    function getOnSubmit()    {return $this->readOnSubmit();}
    function setOnSubmit($value)    {$this->writeOnSubmit($value);}

    /*
    * Publish the JS events for the CheckBox component
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


   function getItemIndex()
   {
      return $this->readItemIndex();
   }
   function setItemIndex($value)
   {
      $this->writeItemIndex($value);
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

   function getStyle()              {return $this->readstyle();}
   function setStyle($value)        {$this->writestyle($value);}

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
 * Simple geometric shape drawn on a canvas.
 *
 * You can use its Brush and Pen superproperties to customize the fill color and outline color and width of the shape.
 *
 * @link wiki://Shape
 *
 * @example Shape/Shape.php
 */
class Shape extends Canvas
{
    protected $_brush = null;
    protected $_pen = null;

    // Documented in the parent.
    function __construct($aowner=null)
    {
        //Calls inherited constructor
        parent::__construct($aowner);

        $this->Width=65;
        $this->Height=65;
        $this->_pen=new Pen();
        $this->_pen->_control=$this;
        $this->_brush=new Brush();
        $this->_brush->_control=$this;

    }

    // Documented in the parent.
    function dumpContents()
    {
        echo parent::dumpContents();

        if(($this->ControlState & csDesigning) == csDesigning)
        {
            echo "<script>\n";
            echo "var " . $this->_getJavascriptVar() . " = document.getElementById('" . $this->getName() . "').getContext('" . $this->getContext() . "');\n";
            echo $this->_paintJS() . "\n";
            echo "</script>";
        }
    }


    /**
     * Prints the JavaScript code to draw in the canvas the shapes defined by the control.
     *
     * @see dumpContents()
     *
     * @internal
     */
    function _paintJS()
    {

        $penwidth = max($this->Pen->Width, 1);

        switch ($this->_shape)
        {
            case stCircle:
            case stSquare:
            case stRoundSquare:
                // need to center the shape
                $size = min($this->Width, $this->Height) / 2 - $penwidth * 4;
                $xc= ($this->Width/2);
                $yc= ($this->Height/2);
                $x1 = $xc - $size;
                $y1 = $yc - $size;
                $x2= $xc + $size;
                $y2= $yc + $size;
                break;
            default:
                $x1=$penwidth;
                $y1=$penwidth;
                $x2=max($this->Width, 2) - $penwidth * 2;
                $y2=max($this->Height, 2) - $penwidth * 2;
                $size=max($x2, $y2);
                break;
        };

        $w = max($this->Width, 1);
        $h = max($this->Height, 1);

        // need to center the shape
        $size = min($this->Width, $this->Height) / 2 - $penwidth * 4;
        $result = "";
        switch ($this->_shape)
        {
            case stRectangle:
            case stSquare:
                $result .= $this->FillRect($x1, $y1, $x2, $y2);
                $result .= $this->Rectangle($x1, $y1, $x2, $y2);
                break;
            case stRoundRect:
            case stRoundSquare:
                if ($w < $h) $s = $w;
                else $s = $h;

                $result .= $this->RoundRect($x1, $y1, $x2, $y2);
                break;
            case stCircle:
                $xc = $this->Width / 2;
                $yc = $this->Height / 2;
                if ($w < $h)  $radius = ($w/2)-5;
                else  $radius = ($h/2)-5;

                $result .= $this->Circle($xc, $yc, $radius);
                break;
            case stEllipse:
                $x = 5;
                $y = 5;
                $result .= $this->Ellipse($x, $y, $this->Width-10, $this->Height-10);
                break;
        }
        return $result;
    }

    /**
     * Prints the JavaScript code.
     *
     * @internal
     */
    function pagecreate()
    {
        $result = parent::pagecreate();
        $result .= $this->_paintJS();
        return $result;
    }


    protected $_shape=stRectangle;

    /**
     * Shape to be drawn on the camvas.
     *
     * The following values are possible:
     * - stCircle. Circle.
     * - stEllipse. Ellipse.
     * - stRectangle. Rectangle. (default)
     * - stRoundRect. Rectangle with rounded corners.
     * - stRoundSquare. Square with rounded corners.
     * - stSquare. Square.
     *
     * @return enum
     */
    function getShape()                    { return $this->_shape; }
    function setShape($value)             { $this->_shape=$value; }
    function defaultShape()                 { return stRectangle; }


    /**
     * Style of the shape's outline.
     *
     * @see getBrush()
     */
    function getPen()            { return $this->_pen;       }
    function setPen($value)     { if (is_object($value)) $this->_pen=$value; }


    /**
     * Style of the sahpe's fill.
     *
     * @see getPen()
     */
    function getBrush()          { return $this->_brush;       }
    function setBrush($value)   { if (is_object($value)) $this->_brush=$value; }


    // Documented in the parent.
    function getVisible()           { return $this->readVisible(); }
    function setVisible($value)     { $this->writeVisible($value); }

}

/**
 * An outline to create beveled boxes, frames, or lines. The bevel can look raised or lowered.
 *
 * @link wiki://Bevel
 *
 * @example Components/Bevel/bevel.php
 *
 * @see Shape
 */
class Bevel extends Canvas
{
    protected $_pen = null;

    // Documented in the parent.
    function __construct($aowner=null)
    {
        //Calls inherited constructor
        parent::__construct($aowner);

        $this->_pen=new Pen();
        $this->_pen->_control=$this;
    }

    protected $_shape=bsBox;

    /**
     * Shape of the bevel.
     *
     * The following values are supported:
     * - bsBox. A box.
     * - bsFrame. A frame.
     * - bsTopLine. A single line along the top side of the control.
     * - bsBottomLine. A single line along the bottom side of the control.
     * - bsLeftLine. A single line along the left side of the control.
     * - bsRightLine. A single line along the right side of the control.
     * - bsSpacer. Nothing is drawn.
     *
     * @see getBevelStyle()
     *
     * @return enum
     */
     function getShape()            { return $this->_shape; }
     function setShape($value)     { $this->_shape=$value; }
     function defaultShape()         { return bsBox; }


    protected $_bevelstyle=bsLowered;

    /**
     * Appearance of the bevel, either with a lowered effect (bsLowered) or a raised effect (bsRaised).
     *
     * This property has no effect for Shape=bsSpacer.
     *
     * You can toggle this property using a server-side event to toggle the enabled/disabled feels that the lowered and
     * raised effects provide in a bevel:
     *
     * <code>
     * function Button1Click($sender, $params)
     * {
     *     if($this->Bevel1->BevelStyle === bsRaised)
     *         $this->Bevel1->BevelStyle = bsLowered;
     *     else
     *         $this->Bevel1->BevelStyle = bsRaised;
     * }
     * </code>
     *
     * @see getShape()
     *
     * @return enum
     */
    function getBevelStyle()          { return $this->_bevelstyle; }
    function setBevelStyle($value)    { $this->_bevelstyle=$value; }
    function defaultBevelStyle()      { return bsLowered; }

    // Documented in the parent.
    function getVisible()             { return $this->readVisible(); }
    function setVisible($value)       { $this->writeVisible($value); }

    // Documented in the parent.
    function dumpContents()
    {
        echo parent::dumpContents();

        if(($this->ControlState & csDesigning) == csDesigning)
        {
            echo "<script>\n";
            echo "var " . $this->_getJavascriptVar() . " = document.getElementById('" . $this->getName() . "').getContext('" . $this->getContext() . "');\n";
            echo $this->_paintJS() . "\n";
            echo "</script>";
        }
    }

    /**
     * Prints the JavaScript code to draw in the canvas the shapes defined by the control.
     *
     * @see dumpContents()
     *
     * @internal
     */
    function _paintJS()
    {
        $result = "";

        $w = max($this->Width, 1);
        $h = max($this->Height, 1);

        if ($this->_bevelstyle == bsLowered)
        {
            $color1 = "#000000";
            $color2 = "#EEEEEE";
        }
        else
        {
            $color1 = "#EEEEEE";
            $color2 = "#000000";
        };

        switch ($this->_shape)
        {
            case bsFrame:
                $temp = $color1;
                $color1 = $color2;
                $result .= $this->BevelRect(1, 1, $w - 1, $h - 1, $color1, $color2);
                $color2 = $temp;
                $color1 = $temp;
                $result .= $this->BevelRect(0, 0, $w - 2, $h - 2, $color1, $color2);
                break;
            case bsTopLine:
                $result .= $this->BevelLine($color1, 0, 0, $w, 0);
                $result .= $this->BevelLine($color2, 0, 1, $w, 1);
                break;
            case bsBottomLine:
                $result .= $this->BevelLine($color1, 0, $h - 2, $w, $h - 2);
                $result .= $this->BevelLine($color2, 0, $h - 1, $w, $h - 1);
                break;
            case bsLeftLine:
                $result .= $this->BevelLine($color1, 0, 0, 0, $h);
                $result .= $this->BevelLine($color2, 1, 0, 1, $h);
                break;
            case bsRightLine:
                $result .= $this->BevelLine($color1, $w - 2, 0, $w - 2, $h);
                $result .= $this->BevelLine($color2, $w - 1, 0, $w - 1, $h);
                break;
            case bsSpacer:
                break;
            default: // bsBox
                $result .= $this->BevelRect(0, 0, $w - 1, $h - 1, $color1, $color2);
                break;
        }

        return $result;
    }

    /**
     * Prints the JavaScript code for the component.
     *
     * @internal
     */
    function pagecreate()
    {
            $result = parent::pagecreate();

            $result .= $this->_paintJS();
            return $result;
    }

}

/**
 * Timer encapsulates the javascript timer functions.
 *
 * Timer is used to simplify calling the javascript timer functions settimeout() and cleartimeout(),
 * and to simplify processing the timer events. Use one timer component for each timer in the application.
 *
 * The execution of the timer occurs through its OnTimer event. Timer has an Interval property
 * that determines how often the timer's OnTimer event occurs. Interval corresponds to the parameter
 * for the javascript settimeout() function.
 *
 * @link http://developer.mozilla.org/en/docs/DOM:window.setTimeout
 * @link http://developer.mozilla.org/en/docs/DOM:window.clearTimeout
 */
class Timer extends Component
{
   protected $_interval = 1000;
   protected $_enabled = true;
   protected $_jsontimer = null;

   function dumpJavascript()
   {
      parent::dumpJavascript();

      if(($this->ControlState & csDesigning) == csDesigning)
         Break;

      if(($this->_enabled) && ($this->_jsontimer != null))
      {
         $this->dumpJSEvent($this->_jsontimer);

         echo "  var " . $this->Name . "_TimerID = null;\n";
         echo "  var " . $this->Name . "_OnLoad = null;\n";
         echo "\n\n";

         echo "  function addEvent(obj, evType, fn)\n";
         echo "  { if (obj.addEventListener)\n";
         echo "    { obj.addEventListener(evType, fn, false);\n";
         echo "      return true;\n";
         echo "    }\n";
         echo "    else if (obj.attachEvent)\n";
         echo "    { var r = obj.attachEvent(\"on\"+evType, fn);\n";
         echo "      return r;\n";
         echo "    } else {\n";
         echo "      return false;\n";
         echo "    }\n";
         echo "  }\n\n";

         echo "  function " . $this->Name . "_InitTimer()\n";
         echo "  {  if (" . $this->Name . "_OnLoad != null) " . $this->Name . "_OnLoad();\n";
         echo "     " . $this->Name . "_DisableTimer();\n";
         echo "     " . $this->Name . "_EnableTimer();\n";
         echo "  }\n\n";

         echo "  function " . $this->Name . "_DisableTimer()\n";
         echo "  {  if (" . $this->Name . "_TimerID)\n";
         echo "     { clearTimeout(" . $this->Name . "_TimerID); \n";
         echo "       " . $this->Name . "_TimerID  = null;\n";
         echo "     }\n";
         echo "  }\n\n";

         echo "  function " . $this->Name . "_Event()\n";
         echo "  { \n";
         echo "  var event = event || window.event; \n";
         echo "  if (" . $this->Name . "_TimerID)\n";
         echo "    {  " . $this->Name . "_DisableTimer();\n";
         echo "       " . $this->_jsontimer . "(event);\n";
         echo "       " . $this->Name . "_EnableTimer();\n";
         echo "    }\n";
         echo "  }\n\n";

         echo "  function " . $this->Name . "_EnableTimer()\n";
         echo "  { " . $this->Name . "_TimerID = self.setTimeout(\"" . $this->Name . "_Event()\", $this->_interval);\n";
         echo "  }\n\n";

         echo "  if (window.onload) " . $this->Name . "_OnLoad=window.onload;\n";
         echo "  addEvent(window, 'load', " . $this->Name . "_InitTimer);\n";
      }
   }

   /**
    * Controls whether the timer generates OnTimer events periodically, so you can react
    * to them programatically
    *
    * Use Enabled to enable or disable the timer. If Enabled is true, the timer responds normally.
    * If Enabled is false, the timer does not generate OnTimer events. The default is true.
    *
    * @return boolean
    */
   function getEnabled()    {return $this->_enabled;}
   function setEnabled($value)    {$this->_enabled = $value;}
   function defaultEnabled()    {return true;}


   /**
    * Determines the amount of time, in milliseconds, that passes before
    * the timer component initiates another OnTimer event.
    *
    * Interval determines how frequently the OnTimer event occurs. Each time
    * the specified interval passes, the OnTimer event occurs.
    *
    * Use Interval to specify any cardinal value as the interval between
    * OnTimer events. The default value is 1000 (one second).
    *
    * Note: A 0 value is valid, however the timer will not call an OnTimer event for a value of 0.
    *
    * @see getjsOnTimer()
    *
    * @return integer
    */
   function getInterval()    {return $this->_interval;}
   function setInterval($value)    {$this->_interval = $value;}
   function defaultInterval()    {return 1000;}

   /**
    * Occurs when a specified amount of time, determined by the Interval
    * property, has passed.
    *
    * Write an OnTimer event handler to execute an action at regular intervals.
    * The Interval property of a timer determines how frequently the OnTimer event
    * occurs. Each time the specified interval passes, the OnTimer event occurs.
    *
    * @see getInterval()
    *
    * @return mixed
    */
   function getjsOnTimer()    {return $this->_jsontimer;}
   function setjsOnTimer($value)    {$this->_jsontimer = $value;}
   function defaultjsOnTimer()    {return null;}
}
