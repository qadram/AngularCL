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

/**
 * Base class for superproperties providing CSS3 features.
 */
class CSS3Property extends Persistent
{
      public $_control=null;

      // Documented in the parent.
      function readOwner()
      {
        return($this->_control);
      }

      // Documented in the parent.
      function assignTo($dest)
      {
        //TODO:
        $dest->Color=$this->_color;
      }

      /**
       * Checks whether the application is configured to output standard code, or if it should
       * look for browser compatibility instead.
       *
       * @see Application
       *
       * @internal
       */
	  function generateVendorCSSExtensions()
	  {
        global $application;

        if (($application != null) && ($application->getGenerateVendorCSSExtensions() == false))
          return false;
        else
          return true;

      }
}

define('brsDisabled','brsDisabled');
define('brsDashed','brsDashed');
define('brsDotted','brsDotted');
define('brsDouble','brsDouble');
define('brsGroove','brsGroove');
define('brsHidden','brsHidden');
define('brsInset','brsInset');
define('brsNone','brsNone');
define('brsOutset','brsOutset');
define('brsRidge','brsRidge');
define('brsSolid','brsSolid');
define('brsInherit','brsInherit');

/**
 * Superproperty to configure the style of the border around a control.
 *
 * The Border superproperty provides the following subproperties for you to customize the style of the
 * border around the control:
 * - Color. The color of the border.
 * - Style. The type of border to be used. You can used this property, among other things, to disable the
 *   effects of the superproperty (brsDisabled), inherit the value from the control's container (brsInherit)
 *   or define an invisible border (brsNone) that has effect in the way things like the background are
 *   displayed.
 * - Width. The width of the border.
 *
 * It also provides properties to customize the radius of each corner of the border: BottomLeft, BottomRight,
 * TopLeft and TopRight.
 */
class BorderRadius extends CSS3Property
{
    protected $_style=brsDisabled;

    /**
     * Type of border.
     *
     * It accepts the following values:
     * - brsDashed. Use a dashed border.
     * - brsDisabled. Disable the whole BorderRadius feature and its effects.
     * - brsDotted. Use a dotted border.
     * - brsDouble. Use a double border.
     * - brsGroove. Use a grooved border with three-dimensional feel.
     * - brsHidden. Use an invisible border.
     * - brsInherit. Configure this property with the value it has in the parent control.
     * - brsInset. Use an inset border with three-dimensional feel.
     * - brsNone. Do not use a border. Although unexistant, it might have effect in the way other aspect like the
     *   background are displayed.
     * - brsOutset. Use an outset border with three-dimensional feel.
     * - brsRidge. Use a ridged border with three-dimensional feel.
     * - brsSolid. Use a solid border.
     */
    function getStyle() { return $this->_style; }
    function setStyle($value) { $this->_style=$value; }
    function defaultStyle() { return brsDisabled; }

    protected $_width='1px';

    /**
     * Width of the border.
     */
    function getWidth() { return $this->_width; }
    function setWidth($value) { $this->_width=$value; }
    function defaultWidth() { return '1px'; }

    protected $_topleft='1px';

    /**
     * Radius of the top left corner of the border.
     */
    function getTopLeft() { return $this->_topleft; }
    function setTopLeft($value) { $this->_topleft=$value; }
    function defaultTopLeft() { return '1px'; }

    protected $_topright='1px';

    /**
     * Radius of the top right corner of the border.
     */
    function getTopRight() { return $this->_topright; }
    function setTopRight($value) { $this->_topright=$value; }
    function defaultTopRight() { return '1px'; }

    protected $_bottomleft='1px';

    /**
     * Radius of the bottom left corner of the border.
     */
    function getBottomLeft() { return $this->_bottomleft; }
    function setBottomLeft($value) { $this->_bottomleft=$value; }
    function defaultBottomLeft() { return '1px'; }

    protected $_bottomright='1px';

    /**
     * Radius of the bottom right corner of the border.
     */
    function getBottomRight() { return $this->_bottomright; }
    function setBottomRight($value) { $this->_bottomright=$value; }
    function defaultBottomRight() { return '1px'; }

    protected $_color='#000000';

    /**
     * Color of the border.
     */
    function getColor() { return $this->_color; }
    function setColor($value) { $this->_color=$value; }
    function defaultColor() { return '#000000'; }

    /**
     * Returns a string with the CSS code for the current configuration of the BorderRadius superproperty.
     *
     * @internal
     */
    function readCSSString()
    {
      global $application;

      $style='none';
      switch($this->_style)
      {
        case 'brsDashed': $style='dashed'; break;
        case 'brsDotted': $style='dotted'; break;
        case 'brsDouble': $style='double'; break;
        case 'brsGroove': $style='groove'; break;
        case 'brsHidden': $style='hidden'; break;
        case 'brsInset': $style='inset'; break;
        case 'brsNone': $style='none'; break;
        case 'brsOutset': $style='outset'; break;
        case 'brsRidge': $style='ridge'; break;
        case 'brsSolid': $style='solid'; break;
        case 'brsInherit': $style='inherit'; break;
      }

      $result='';

      if ($this->_style!=brsDisabled)
      {
        $result="border:$style $this->_width $this->_color;\n";

        $generateVendorCSSExtensions = $this->generateVendorCSSExtensions();

        if ($generateVendorCSSExtensions)
        {
          $result.="-moz-border-radius-topleft: $this->_topleft;\n";
          $result.="-webkit-border-top-left-radius: $this->_topleft;\n";
        }
        $result.="border-top-left-radius:$this->_topleft;\n";

        if ($generateVendorCSSExtensions)
        {
          $result.="-moz-border-radius-topright: $this->_topright;\n";
          $result.="-webkit-border-top-right-radius: $this->_topright;\n";
        }
        $result.="border-top-right-radius:$this->_topright;\n";

        if ($generateVendorCSSExtensions)
        {
          $result.="-moz-border-radius-bottomright: $this->_bottomright;\n";
          $result.="-webkit-border-bottom-right-radius: $this->_bottomright;\n";
        }
        $result.="border-bottom-right-radius:$this->_bottomright;\n";

        if ($generateVendorCSSExtensions)
        {
          $result.="-moz-border-radius-bottomleft: $this->_bottomleft;\n";
          $result.="-webkit-border-bottom-left-radius: $this->_bottomleft;\n";
        }
        $result.="border-bottom-left-radius:$this->_bottomleft;\n";
      }

      return($result);
    }
}

define('gsDisabled','gsDisabled');
define('gsLinear','gsLinear');
define('gsRadial','gsRadial');

/**
 * Superproperty to configure a gradient between two colors to be used as the main color of a control.
 *
 * The Style subproperty determines the meaning of StartPosition and EndPosition.
 *
 * If the Style subproperty is given the gsDisabled value, the whole feature is disabled, and the values of
 * the rest of the subproperties will not have any effect at all.
 * 
 * If the Style subproperty is given the gsLinear value, a (horizontal) linear gradient will be used.
 * StartPosition and EndPosition will be the points between which
 * the gradient is drawn:
 * - From the left to the StartPosition, the StartColor will be drawn.
 * - From the StartPosition to the EndPosition there will be a gradient between the StartColor and the EndColor.
 * - From the EndPosition to the right, the EndColor will be drawn.
 * 
 * If the Style subproperty is given the gsRadial value, a (centered) radial gradient will be used.
 * StartPosition and EndPosition will be the points of the radius
 * between which the gradient is drawn:
 * - From the center to the StartPosition, the StartColor will be drawn.
 * - From the StartPosition to the EndPosition there will be a gradient between the StartColor and the EndColor.
 * - From the EndPosition to the right, the EndColor will be drawn.
 */
class Gradient extends CSS3Property {

    protected $_style=gsDisabled;

    /**
     * Type of gradient.
     *
     * It accepts the following values:
     * - gsDisabled. Disable the Gradient feature and its effects.
     * - gsLinear. Use a linear gradient.
     * - gsRadial. Use a radial gradient.
     */
    function getStyle() { return $this->_style; }
    function setStyle($value) { $this->_style=$value; }
    function defaultStyle() { return gsDisabled; }

    protected $_startcolor='#866400';

    /**
     * Color value for the first one of the two color in the gradient.
     */
    function getStartColor() { return $this->_startcolor; }
    function setStartColor($value) { $this->_startcolor=$value; }
    function defaultStartColor() { return '#866400'; }

    protected $_startposition = "0%";

    /**
     * Position where the gradient between the StartColor and the EndColor starts.
     *
     * For linear gradients (Style = gsLinear), a value of 0% would be the left side, while a value of 100% would be the right side.
     * 
     * For radial gradients (Style = gsRadial), a value of 0% would be the center, while a value of 100% would be the outside.
     */
    function getStartPosition() { return $this->_startposition; }
    function setStartPosition($value) { $this->_startposition=$value; }
    function defaultStartPosition() { return "0%"; }

    protected $_endposition="100%";

    /**
     * Position where the gradient between the StartColor and the EndColor ends.
     *
     * For linear gradients (Style = gsLinear), a value of 0% would be the left side, while a value of 100% would be the right side.
     * 
     * For radial gradients (Style = gsRadial), a value of 0% would be the center, while a value of 100% would be the outside.
     */
    function getEndPosition() { return $this->_endposition; }
    function setEndPosition($value) { $this->_endposition = $value; }
    function defaultEndPosition() { return "100%"; }

    protected $_endcolor="#FF4E28";

    /**
     * Color value for the second one of the two color in the gradient.
     */
    function getEndColor() { return $this->_endcolor; }
    function setEndColor($value) { $this->_endcolor=$value; }
    function defaultEndColor() { return "#FF4E28"; }

    /**
     * Returns a string with the CSS code for the current configuration of the Gradient superproperty.
     *
     * @internal
     */
    function readCSSString()
    {

      global $application;

      $result='';

      if ($this->_style!=gsDisabled)
      {
        $style='linear';

        if ($this->_style=='gsRadial')
            $style='radial';

        if ($this->generateVendorCSSExtensions())
        {

            if ($this->_style == gsLinear) 
            {
                $result .=  "background: $this->_startcolor; /* Old browsers */ \n" .
                            "background: -moz-linear-gradient(left,  $this->_startcolor $this->_startposition, $this->_endcolor $this->_endposition); /* FF3.6+ */ \n" . 
                            "background: -webkit-gradient(linear, left top, right top, color-stop($this->_startposition, $this->_startcolor), color-stop($this->_endposition, $this->_endcolor)); /* Chrome,Safari4+ */ \n" . 
                            "background: -webkit-linear-gradient(left,  $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* Chrome10+,Safari5.1+ */ \n" .
                            "background: -o-linear-gradient(left,  $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* Opera 11.10+ */ \n" .
                            "background: -ms-linear-gradient(left,  $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* IE10+ */ \n ".
                            "background: linear-gradient(to right,  $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* W3C */ \n" .
                            "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='$this->_startcolor', endColorstr='$this->_endcolor',GradientType=1 ); /* IE6-9 */ \n";
                

            }
            else
            {
                $result .=  "background: $this->_startcolor; /* Old browsers */ \n " .
                            "background: -moz-radial-gradient(center, ellipse cover, $this->_startcolor $this->_startposition, $this->_endcolor $this->_endposition); /* FF3.6+ */ \n".                           
                            "background: -webkit-radial-gradient(center, ellipse cover, $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* Chrome10+,Safari5.1+ */ \n" .
                            "background: -o-radial-gradient(center, ellipse cover, $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* Opera 12+ */ \n" .
                            "background: -ms-radial-gradient(center, ellipse cover, $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* IE10+ */ \n".
                            "background: radial-gradient(ellipse at center, $this->_startcolor $this->_startposition,$this->_endcolor $this->_endposition); /* W3C */ \n".
                            "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='$this->_startcolor', endColorstr='$this->_endcolor',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */ \n";



            }
        }

      }

      return($result);
    }

}

define('tsDisabled','tsDisabled');
define('tsAll','tsAll');
define('tsRotate','tsRotate');
define('tsScale','tsScale');
define('tsSkew','tsSkew');
define('tsTranslate','tsTranslate');


/**
 * Superproperty to configure a series of transformations to be applied to a control.
 *
 * The Style subproperty of the Transform superproperty determines the way the rest of the subproperties
 * work. The following values are available:
 * - tsDisabled. The whole transformation feature is disabled.
 * - tsAll. All the subproperties are applied to the control.
 * - tsRotation. Apply rotation subproperties only (Rotate).
 * - tsScale. Apply scaling subproperties only (ScaleX and ScaleY).
 * - tsSkew. Apply skewing subproperties only (SkewX and SkewY).
 * - tsTranslate. Apply translation subproperties only (TranslateX and TranslateY).
 *
 * The rest of the subproperties of the Transform superproperty are:
 * - Rotate. Rotation angle.
 * - ScaleX. Scaling rate in the horizontal axis. Set to 1.0 to keep the original size.
 * - ScaleX. Scaling rate in the vertical axis. Set to 1.0 to keep the original size.
 * - SkewX. Skewing angle in the horizontal axis.
 * - SkewY. Skewing angle in the vertical axis.
 * - TranslateX. Translation in the horizontal axis. Negative values translate to the top, positive values
 *   to the bottom.
 * - TranslateY. Translation in the vertical axis. Negative values translate to the left, positive values
 *   to the right.
 */
class Transform extends CSS3Property {

    protected $_style=tsDisabled;

    /**
     * Type of transformation.
     *
     * It accepts the following values:
     * - tsDisabled. The whole transformation feature is disabled.
     * - tsAll. All the subproperties are applied.
     * - tsRotation. Apply rotation subproperties only (Rotate).
     * - tsScale. Apply scaling subproperties only (ScaleX and ScaleY).
     * - tsSkew. Apply skewing subproperties only (SkewX and SkewY).
     * - tsTranslate. Apply translation subproperties only (TranslateX and TranslateY).
     */
    function getStyle() { return $this->_style; }
    function setStyle($value) { $this->_style=$value; }
    function defaultStyle() { return tsDisabled; }

    protected $_rotate="20deg";

    /**
     * Rotation angle.
     */
    function getRotate() { return $this->_rotate; }
    function setRotate($value) { $this->_rotate=$value; }
    function defaultRotate() { return "20deg"; }

    /**
     * Returns the CSS Transform function for rotation with the configured values.
     *
     * @internal
     */
    private function _getRotate() {return "rotate($this->_rotate)";}

    protected $_scalex="0.800";

    /**
     * Scaling rate in the horizontal axis.
     *
     * Set it to 1.0 to keep the original size.
     */
    function getScaleX() { return $this->_scalex; }
    function setScaleX($value) { $this->_scalex=$value; }
    function defaultScaleX() { return "0.800"; }

    protected $_scaley="1.500";

    /**
     * Scaling rate in the vertical axis.
     *
     * Set it to 1.0 to keep the original size.
     */
    function getScaleY() { return $this->_scaley; }
    function setScaleY($value) { $this->_scaley=$value; }
    function defaultScaleY() { return "1.500"; }

    /**
     * Returns the CSS Transform function for scaling with the configured values.
     *
     * @internal
     */
    private function _getScaleXY() {return "scale($this->_scalex, $this->_scaley)";}

    protected $_skewx="-19deg";

    /**
     * Skewing angle in the horizontal axis.
     */
    function getSkewX() { return $this->_skewx; }
    function setSkewX($value) { $this->_skewx=$value; }
    function defaultSkewX() { return "-19deg"; }

    protected $_skewy="17deg";

    /**
     * Skewing angle in the vertical axis.
     */
    function getSkewY() { return $this->_skewy; }
    function setSkewY($value) { $this->_skewy=$value; }
    function defaultSkewY() { return "17deg"; }

    /**
     * Returns the CSS Transform function for skewing with the configured values.
     *
     * @internal
     */
    private function _getSkewXY() {return "skew($this->_skewx, $this->_skewy)";}

    protected $_translatex="21px";

    /**
     * Translation in the horizontal axis.
     *
     * Negative values translate to the top, while positive values translate to the bottom.
     */
    function getTranslateX() { return $this->_translatex; }
    function setTranslateX($value) { $this->_translatex=$value; }
    function defaultTranslateX() { return "21px"; }

    protected $_translatey="60px";

    /**
     * Translation in the vertical axis.
     *
     * Negative values translate to the left, while positive values translate to the right.
     */
    function getTranslateY() { return $this->_translatey; }
    function setTranslateY($value) { $this->_translatey=$value; }
    function defaultTranslateY() { return "60px"; }

    /**
     * Returns the CSS Transform function for translation with the configured values.
     *
     * @internal
     */
    private function _getTranslateXY() {return "translate($this->_translatex, $this->_translatey)";}

    /**
     * Returns a string with the CSS code for the current configuration of the Transform superproperty.
     *
     * @internal
     */
    function readCSSString()
    {
      global $application;

      $result='';

      if ($this->_style!=tsDisabled)
      {
          $style='';

          switch($this->_style)
          {
            case 'tsRotate': $style = $this->_getRotate(); break;
            case 'tsScale': $style = $this->_getScaleXY(); break;
            case 'tsSkew': $style = $this->_getSkewXY(); break;
            case 'tsTranslate': $style = $this->_getTranslateXY(); break;
            case 'tsAll': $style = "{$this->_getRotate()} {$this->_getScaleXY()} {$this->_getSkewXY()} {$this->_getTranslateXY()}"; break;
          }

          $result .= "transform: $style ;\n";

          if ($this->generateVendorCSSExtensions())
          {
            $result .= "-webkit-transform: $style ;\n" .
                       "-moz-transform: $style ;\n" .
                       "-o-transform: $style ;\n" .
                       "-ms-transform: $style ;\n";
          }
      }

      return($result);
    }

}

define('tssDisabled','tssDisabled');
define('tssEnabled','tssEnabled');

/**
 * Superproperty to configure a shadow to be drawn for the text in a control.
 *
 * The Style subproperty defines whether the feature is enabled (tssEnabled) or disabled (tssDisabled).
 *
 * The rest of the subproperties of the TextShadow superproperty are:
 * - BlurRadius. Radius for the blur effect on the shadow. Increase this value to increase the blur level.
 * - Color. Color of the shadow.
 * - HorizontalPosition. The position in the horizontal axis for the left side of the shadow. Negative values
 *   translate to the top, positive values to the bottom.
 * - VerticalPosition. The position in the vertical axis for the top side of the shadow. Negative values
 *   translate to the right, positive values to the left.
 */
class TextShadow extends CSS3Property {

    protected $_style=tssDisabled;

    /**
     * Whether the TextShadow feature is enabled (tssEnabled) or disabled (tssDisabled).
     */
    function getStyle() { return $this->_style; }
    function setStyle($value) { $this->_style=$value; }
    function defaultStyle() { return tssDisabled; }

    protected $_horizontalposition = "27px";
    
    /**
     * Position in the horizontal axis for the left side of the shadow.
     *
     * Negative values translate to the top, while positive values translate to the bottom.
     */
    function getHorizontalPosition() { return $this->_horizontalposition; }
    function setHorizontalPosition($value) { $this->_horizontalposition=$value; }
    function defaultHorizontalPosition() { return "27px"; }

    protected $_verticalposition="9px";

    /**
     * Position in the vertical axis for the left side of the shadow.
     *
     * Negative values translate to the right, while positive values translate to the left.
     */
    function getVerticalPosition() { return $this->_verticalposition; }
    function setVerticalPosition($value) { $this->_verticalposition=$value; }
    function defaultVerticalPosition() { return "9px"; }

    protected $_blurradius="9px";

    /**
     * Radius for the blur effect on the shadow.
     *
     * Increase this value to increase the blur level.
     */
    function getBlurRadius() { return $this->_blurradius; }
    function setBlurRadius($value) { $this->_blurradius=$value; }
    function defaultBlurRadius() { return "9px"; }

    protected $_color="#eb12eb";

    /**
     * Color of the shadow.
     */
    function getColor() { return $this->_color; }
    function setColor($value) { $this->_color=$value; }
    function defaultColor() { return "#eb12eb"; }

    /**
     * Returns a string with the CSS code for the current configuration of the TextShadow superproperty.
     *
     * @internal
     */
    function readCSSString()
    {

      $result='';

      if ($this->_style != tssDisabled)
      {
          $result = "text-shadow:$this->_horizontalposition $this->_verticalposition $this->_blurradius $this->_color;\n";
      }

      return($result);
    }

}


define('bssDisabled','bssDisabled');
define('bssEnabled','bssEnabled');

define('bsiNo','bsiNo');
define('bsiYes','bsiYes');

/**
 * Superproperty to configure a shadow to be drawn around a control.
 *
 * The Style subproperty defines whether the feature is enabled (bssEnabled) or disabled (bssDisabled).
 *
 * The rest of the subproperties of the BoxShadow superproperty are:
 * - BlurRadius. Radius for the blur effect on the shadow. Increase this value to increase the blur level.
 * - Color. Color of the shadow.
 * - HorizontalPosition. The position in the horizontal axis for the left side of the shadow. Negative values
 *   translate to the top, positive values to the bottom.
 * - Inset. Whether the shadow should be printed inside the control (bsiYes) or outside (bsiNo).
 * - VerticalPosition. The position in the vertical axis for the top side of the shadow. Negative values
 *   translate to the right, positive values to the left.
 */
class BoxShadow extends CSS3Property {

    protected $_style=bssDisabled;

    /**
     * Whether the BoxShadow feature is enabled (tssEnabled) or disabled (tssDisabled).
     */
    function getStyle() { return $this->_style; }
    function setStyle($value) { $this->_style=$value; }
    function defaultStyle() { return bssDisabled; }

    protected $_horizontalposition="10px";

    /**
     * Position in the horizontal axis for the left side of the shadow.
     *
     * Negative values translate to the top, while positive values translate to the bottom.
     */
    function getHorizontalPosition() { return $this->_horizontalposition; }
    function setHorizontalPosition($value) { $this->_horizontalposition=$value; }
    function defaultHorizontalPosition() { return "10px"; }

    protected $_verticalposition="10px";

    /**
     * Position in the vertical axis for the left side of the shadow.
     *
     * Negative values translate to the right, while positive values translate to the left.
     */
    function getVerticalPosition() { return $this->_verticalposition; }
    function setVerticalPosition($value) { $this->_verticalposition=$value; }
    function defaultVerticalPosition() { return "10px"; }

    protected $_blurradius="5px";

    /**
     * Radius for the blur effect on the shadow.
     *
     * Increase this value to increase the blur level.
     */
    function getBlurRadius() { return $this->_blurradius; }
    function setBlurRadius($value) { $this->_blurradius=$value; }
    function defaultBlurRadius() { return "5px"; }

    protected $_color="#000000";

    /**
     * Color of the shadow.
     */
    function getColor() { return $this->_color; }
    function setColor($value) { $this->_color=$value; }
    function defaultColor() { return "#000000"; }

    protected $_inset="bsiNo";

    /**
     * Whether the shadow should be printed inside the control (bsiYes) or outside (bsiNo).
     */
    function getInset() { return $this->_inset; }
    function setInset($value) { $this->_inset=$value; }
    function defaultInset() { return "bsiNo"; }

    /**
     * Returns a string with the CSS code for the current configuration of the BoxShadow superproperty.
     *
     * @internal
     */
    function readCSSString()
    {

      $result='';

      if ($this->_style != bssDisabled)
      {
          $inset = '';
          if ($this->_inset=="bsiYes") $inset = "inset";

          $result = "box-shadow:$inset $this->_horizontalposition $this->_verticalposition $this->_blurradius $this->_color;\n";

          if ($this->generateVendorCSSExtensions())
              $result .= "-webkit-box-shadow:$inset $this->_horizontalposition $this->_verticalposition $this->_blurradius $this->_color;\n";
      }
      return($result);
    }
}

?>