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
use_unit("extctrls.inc.php");

/**
 * Kinds of dates and times.
 */
define ('dtkDate', 'dtkDate');
define ('dtkTime', 'dtkTime');
define ('dtkDateTime', 'dtkDateTime');
define ('dtkMonth', 'dtkMonth');
define ('dtkWeek', 'dtkWeek');
define ('dtkDateTime-Local', 'dtkDateTime-Local');

/**
 *  ProgressBar Orientation
 */
define ('pbHorizontal', 'pbHorizontal');
define ('pbVertical', 'pbVertical');

/**
* Type of ProgressBar: progress or meter
*/
define('pbsProgressBar','pbsProgressBar');
define('pbsMeterBar','pbsMeterBar');

/**
 *  TrackBar Orientation
 */
define ('trHorizontal', 'trHorizontal');
define ('trVertical', 'trVertical');



/**
 * Base class for controls implementing an input field to enter a date or time.
 */
class CustomDateTimePicker extends CustomRangeEdit
{

    // Documented in the parent.
    function __construct($aowner = null)
    {
        parent::__construct($aowner);
        $this->_width=90;
        $this->_height=35;

        $this->_type = $this->parseKindValue(dtkDate);

        $this->_maxvalue = "";
        $this->_minvalue = "";
        $this->_step = "";
    }

    /**
     * Converts a predefined value of the Kind property to its actual output value.
     *
     * For example, if 'dtkDate' is passed, it returns 'date'.
     *
     * @internal
     */
    private function parseKindValue($kind_value)
    {
        return strtolower(substr($kind_value, 3));
    }


    protected $_kind=dtkDate;

    /**
     * Kind of date and time expected, that is, its accuracy and whether it should include the timezone information
     * or not.
     *
     * These are the possible values:
     * - dtkDate. Date without time. (Default)
     * - dtkDateTime. Date and time with timezone.
     * - dtkDateTime-Local. Date and time without timezone.
     * - dtkMonth. Month and year.
     * - dtkTime. Time.
     * - dtkWeek. Week and year.
     */
    function readKind() { return $this->_kind; }
    function writeKind($value) {
        $this->_kind=$value;
        $this->_type = $this->parseKindValue($value);
    }
    function defaultKind() { return dtkDate; }

    // Documented in the parent.
    function dumpFormItems()
    {
    }


    //TODO [12/07/2012][PEREZ] I will let this code docummented because we can use it in the future
    /*
    function pagecreate()
    {
      $output  = "";
      $output .= $this->bindJSEvent('blur');
      $output .= $this->bindJSEvent('change');
      $output .= $this->bindJSEvent('click');
      $output .= $this->bindJSEvent('dblclick');
      $output .= $this->bindJSEvent('focus');
      $output .= $this->bindJSEvent('input');
      $output .= $this->bindJSEvent('keydown');
      $output .= $this->bindJSEvent('keypress');
      $output .= $this->bindJSEvent('keyup');
      $output .= $this->bindJSEvent('mousedown');
      $output .= $this->bindJSEvent('mouseover');
      $output .= $this->bindJSEvent('mouseout');
      $output .= $this->bindJSEvent('mousemove');

      return $output;
   }  */

    // Documented in the parent.
    function dumpJsEvents()
    {
        parent::dumpJsEvents();
        $this->dumpJSEvent($this->_jsonblur);
        $this->dumpJSEvent($this->_jsonchange);
        $this->dumpJSEvent($this->_jsonclick);
        $this->dumpJSEvent($this->_jsondblclick);
        $this->dumpJSEvent($this->_jsonfocus);
        $this->dumpJSEvent($this->_jsoninput);
        $this->dumpJSEvent($this->_jsonkeydown);
        $this->dumpJSEvent($this->_jsonkeypress);
        $this->dumpJSEvent($this->_jsonkeyup);
        $this->dumpJSEvent($this->_jsonmousedown);
        $this->dumpJSEvent($this->_jsonmouseup);
        $this->dumpJSEvent($this->_jsonmouseover);
        $this->dumpJSEvent($this->_jsonmousemove);

    }

}

/**
 * Input field to enter a date or time.
 *
 * You can specify the Kind of date or time to be expected in the input field, and provide a default Value.
 *
 * @link wiki://DateTimePicker
 *
 * @example Components/DateTimePicker/datetimepicker.php
 */
class DateTimePicker extends CustomDateTimePicker
{
    // Documented in the parent.
    function getKind()            { return $this->readKind(); }
    function setKind($value)      { $this->writeKind($value); }

    /**
     * Value granularity of the control, in the unit for the specified Kind of date and time.
     *
     * Provide a number of days for dates, or a number of seconds for times.
     *
     * @return integer
     */
    function getStep()            { return $this->readStep(); }
    function setStep($value)      { $this->writeStep($value); }

    /**
     * Lower limit of the range of possible values, in the unit for the specified Kind of date and time.
     *
     * Provide a number of days for dates, or a number of seconds for times.
     */
    function getMinValue()         { return $this->readMinValue(); }
    function setMinValue($value)   { $this->writeMinValue($value); }

    /**
     * Upper limit of the range of possible values, in the unit for the specified Kind of date and time.
     *
     * Provide a number of days for dates, or a number of seconds for times.
     */
    function getMaxValue()         { return $this->readMaxValue(); }
    function setMaxValue($value)   { $this->writeMaxValue($value); }

    // Documented in the parent.
    function getFont()            { return $this->readFont(); }
    function setFont($value)      { $this->writeFont($value); }

    // Documented in the parent.
    function getParentFont()      { return $this->readParentFont(); }
    function setParentFont($value){ $this->writeParentFont($value); }

    /**
     * Date defined in the control, which can be changed by the user.
     */
    function getValue()           { return $this->readText(); }
    function setValue($value)     { $this->writeText($value); }

    /*
     * Publish the JS events for the DateTimePicker component
     */

    // Documented in the parent.
    function getjsOnBlur                   () { return $this->readjsOnBlur(); }
    function setjsOnBlur                   ($value) { $this->writejsOnBlur($value); }

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
    function getjsOnInput                   ()  { return $this->readjsOnInput(); }
    function setjsOnInput                   ($value) { $this->writejsOnInput($value); }

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

}

/**
 * Base class for input controls with a customizable orientation, that can be either horizontal or vertical.
 */
class CustomTrackBar extends CustomRangeEdit
{

    protected $_orientation=trHorizontal;

    /**
     * Switches the values of the Height and the Width properties of the control.
     *
     * This method is used upon Orientation changes.
     *
     * @internal
     */
    private function switchDimensions()
    {
        if (($this->ControlState & csLoading) != csLoading)
        {
            $temp=$this->_width;
            $this->_width=$this->_height;
            $this->_height=$temp;
        }
    }

    /**
     * Orientation of the control, either horizontal (trHorizontal) or vertical (trVertical).
     *
     * @return enum
     */
    function readOrientation() { return $this->_orientation; }
    function writeOrientation($value)
    {
        //If we are inside the IDE
        if (($this->ControlState & csDesigning) == csDesigning)
        {

            global $allowupdates;

            //This mean this property is being updated in the IDE, specifically
            //so we need to switch coordinates
            if ($allowupdates)
            {
                $this->switchDimensions();
            }

        }
        else
        {
            //If not, we need to check if the value has changed before switching dimensions
            if ($this->_orientation!=$value) $this->switchDimensions();
        }
        $this->_orientation=$value;
    }
    function defaultOrientation() { return trHorizontal; }

    // Documented in parent.
    function __construct($aowner = null)
    {
        parent::__construct($aowner);
        $this->_width=121;
        $this->_height=28;

        $this->_type = "range";

        $this->_text = 0;

        $this->_parentshowhint = false;
    }

    /**
     * Returns true if the Orientation is vertical, false otherwise.
     *
     * @internal
     *
     * @return bool
     */
    function isOrientationVertical()
    {
        $orientation = strtolower(substr($this->_orientation,2));

        return ($orientation === "vertical");
    }


    // Documented in parent.
    function dumpCSS()
    {
        global $application;

        parent::dumpCSS();

        // the xy origin when the control is translated
        $origin = $this->_width / 2; //, 0, PHP_ROUND_HALF_ODD);
		$origin2 = round($origin); //, 0, PHP_ROUND_HALF_ODD);


        if (
            ($this->_transform->Style == tsDisabled) && ($this->isOrientationVertical() )
            ||
            ($this->_transform->Style == tsRotate) && ($this->isOrientationVertical() ) && ($this->_transform->Rotate == 0)
        )
        {
            if (($application != null) && ($application->getGenerateVendorCSSExtensions()))
            {
                $rotateValue = "90deg";
                echo "-webkit-transform: rotate($rotateValue);\n";
                echo "-moz-transform: rotate($rotateValue);\n";
                echo "-o-transform: rotate($rotateValue);\n";

                if ($this->isOrientationVertical())
                {
                    //echo "-webkit-transform-origin: {$origin}px {$origin}px;\n";
					echo "-webkit-transform-origin: {$origin2}px {$origin}px;\n";
                    echo "-moz-transform-origin: {$origin}px {$origin}px;\n";
                    echo "-o-transform-origin: {$origin}px {$origin}px;\n";
                }
            }
            echo "transform:rotate(90deg);\n";

            $this->_transform->Style = tsDisabled;
        }

        if ($this->isOrientationVertical() )
        {
            echo "width:".$this->_height."px;\n";
            echo "height:".$this->_width."px;\n";
            echo "transform-origin: {$origin}px {$origin}px;\n";
        }
        echo "margin: 0;\n";
    }


    function dumpAdditionalCSS()
    {
        if ($this instanceof ProgressBar)
            return;

        global $application;

        //Added an outer div for viewing background color in TrackBar component (Related HI-4121)
        $ocss=$this->readCSSDescriptor().'_outer';

        echo $ocss." {\n";
        if ($this->_color!="")
        {
            echo "background-color: $this->_color;\n";
            echo "width: " . ($this->_width + 4) . "px;\n";
            echo "box-sizing: border-box;\n";
            if (($application != null) && ($application->getGenerateVendorCSSExtensions()))
            {
                echo "-webkit-box-sizing: border-box;\n";
                echo "-moz-box-sizing: border-box;\n";
            }
        }
        echo "}\n";

    }


    // Documented in the parent.
    function dumpFormItems()
    {
      // Overriden to avoid printing a hidden field to get the value of the component.
    }
}

/**
 * Slider control to define a position along a continuum, the latter represented by a bar, optionally with tick marks.
 *
 * At runtime, the position of the slider may be changed by dragging it or clicking on the bar. When the control has the
 * focus, you can also move the slider using the arrow keys or the PageUp and PageDown keys.
 *
 * @link wiki//TrackBar
 *
 * @example TrackBarDemo/uSlider.php
 */
class TrackBar extends CustomTrackBar
{
    /**
     * Position the slider is at on the bar.
     *
     * It should be a number greater or equal to the value of the MinValue property, and less or equal to the value of
     * the MaxValue property.
     */
    function getPosition() { return $this->readText(); }
    function setPosition($value) { $this->writeText($value); }
    function defaultPosition() {return 0;}

    // Documented in the parent.
    function getMinValue() { return $this->readMinValue(); }
    function setMinValue($value) { $this->writeMinValue($value); }

    // Documented in the parent.
    function getMaxValue() { return $this->readMaxValue(); }
    function setMaxValue($value) { $this->writeMaxValue($value); }

    /**
     * Number that should be added or taken from the Position each time the slider is moved.
     *
     * For example, if set to 10, moving the slider to the right (when Orientation is trHorizontal) one position would
     * increase the Position property by 10. To the left, it would decrease it by 10.
     */
    function getFrequency()           { return $this->readStep(); }
    function setFrequency($value)     { $this->writeStep($value); }
    function defaultFrequency()       {return $this->defaultStep();}

    // Documented in the parent.
    function getOrientation()         { return $this->readOrientation(); }
    function setOrientation($value)   { $this->writeOrientation($value); }

    // Documented in the parent.
    function getParentColor()         { return $this->readParentColor(); }
    function setParentColor($value)   { $this->writeParentColor($value); }

    // Documented in the parent.
    function getColor()         { return $this->readColor(); }
    function setColor($value)   { $this->writeColor($value); }

    // Documented in the parent.
    function getShowHint()            { return $this->readShowHint(); }
    function setShowHint($value)      { $this->writeShowHint($value); }

    // Documented in the parent.
    function getParentShowHint()      { return $this->readParentShowHint(); }
    function setParentShowHint($value){ $this->writeParentShowHint($value); }

    /*
     * Publish the JS events for the TrackBar component
     */

    // Documented in the parent.
    function getjsOnClick                   () { return $this->readjsOnClick(); }
    function setjsOnClick                   ($value) { $this->writejsOnClick($value); }

    // Documented in the parent.
    function getjsOnChange                  () { return $this->readjsOnChange(); }
    function setjsOnChange                  ($value) { $this->writejsOnChange($value); }

    // Documented in the parent.
    function getjsOnDblClick                () { return $this->readjsOnDblClick(); }
    function setjsOnDblClick                ($value) { $this->writejsOnDblClick($value); }

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
 * Base class for controls implementing a progress bar.
 */
class CustomProgressBar extends CustomTrackBar
{
        protected $_type=pbsProgressBar;

        /**
         * Whether the progress bar should display progress details (pbsProgressBar) or just show
         * that there is an ongoing progress (pbsMeterBar).
         */
        function readType() { return $this->_type; }
        function writeType($value) { $this->_type=$value; }
        function defaultType() { return pbsProgressBar; }

        function __construct($aowner=null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Width=200;
                $this->Height=17;

                $this->_type = pbsProgressBar;

                $this->_orientation = pbHorizontal;

                $this->_text = 50;

                $this->_maxvalue = 100;

                $this->_minvalue = 10;

                $this->_parentshowhint = false;

        }

        protected $_low=40;

        /**
         * A value of the control that is considered to be low.
         *
         * Any value from the MinValue to this value will be considered low, and might affect the
         * aspect of the control.
         */
        function readLow() { return $this->_low; }
        function writeLow($value) { $this->_low=$value; }
        function defaultLow() { return 40; }

        protected $_high=90;

        /**
         * A value of the control that is considered to be high.
         *
         * Any value from the MaxValue to this value will be considered high, and might affect
         * the aspect of the control.
         */
        function readHigh() { return $this->_high; }
        function writeHigh($value) { $this->_high=$value; }
        function defaultHigh() { return 90; }

        protected $_optimum=100;

        /**
         * A value of the control that is considered to be optimum.
         *
         * If this value is higher than that of the High property, that will indicate that the
         * higher the values are, the better. If the value is lower than that of the Low
         * property, that will indicate that the lower the values are, the better.
         *
         * When the value is between those of the High and Low properties, that will indicate
         * that neither low values nor high values are good.
         */
        function readOptimum() { return $this->_optimum; }
        function writeOptimum($value) { $this->_optimum=$value; }
        function defaultOptimum() { return 100; }

        /**
         * Returns the common HTML attributes for the input element of the control.
         *
         * @return string String with the attributes definition. For example: 'min="0" max="10"'.
         *
         * @internal
         */
        protected function _getCommonAttributes()
        {
            $minvalue = $step = $low = $high = $optimum = "";

            // set maxvalue if bigger or equal than 0
            $maxvalue =  (((int)$this->_maxvalue) >= 0) ? "max=\"$this->_maxvalue\"" : "";

            if ($this->_type == pbsMeterBar)
            {
                  // set minvalue if bigger or equal than 0
                  $minvalue =  ( ((int)$this->_minvalue) >= 0) ? "min=\"$this->_minvalue\"" : "";

                  $low = (((int)$this->_low) >= 0) ? "low=\"$this->_low\"" : "";

                  $high = (((int)$this->_high) >= 0) ? "high=\"$this->_high\"" : "";

                  $optimum = (((int)$this->_optimum) >= 0) ? "optimum=\"$this->_optimum\"" : "";
            }

            // get the hint attribute; returns: title="[HintText]"
            $hint = parent::HintAttribute();

            return "$minvalue $maxvalue $low $high $optimum $hint";
        }

        // Documented in the parent.
        function dumpContents()
        {

                $attributes = $this->_getCommonAttributes();

                //TODO [Perez][JLeon] Do we leave this method?
                // call the OnShow event if assigned so the Text property can be changed
                if ($this->_onshow != null)
                {
                        $this->callEvent('onshow', array());
                }

                $avalue = $this->_text;
                $avalue=str_replace('"','&quot;',$avalue);
                $type = "progress";
                if ($this->_type == pbsMeterBar)
                  $type = "meter";
                echo "<$type id=\"$this->_name\" value=\"$avalue\" $attributes>$this->_text</$type>";

        }

}

/**
 * Progress bar to provide users with visual feedback about the progress of a procedure within your application.
 *
 * @link wiki://ProgressBar
 *
 * @example ProgressBar/uProgressBar.php How ProgressBar work
 * @example ProgressBar/uProgressBar.xml.php How ProgressBar work (form)
 */
class ProgressBar extends CustomProgressBar
{
    // Documented in the parent.
    function getType()              { return $this->readType(); }
    function setType($value)        { $this->writeType($value); }

    /**
     * Value between the Min (no progress) and Max (finished process) values representing the current progress.
     *
     * The progress bar will be filled from one of the sides (the one represented by the Min value) to the given
     * position.
     *
     * It should be a number greater or equal to the value of the Min property, and less or equal to the value of the
     * Max property.
     */
    function getPosition()          { return $this->readText(); }
    function setPosition($value)    { $this->writeText($value); }
    function defaultPosition()      { return 50;}

    // Documented in the parent.
    function getOrientation()       { return $this->readOrientation(); }
    function setOrientation($value) { $this->writeOrientation($value); }

    /**
     * Lower limit of the range of possible positions.
     *
     * Use this property together with the Max property to establish the range of possible
     * positions of the progress bar.
     */
    function getMin()               { return $this->readMinValue(); }
    function setMin($value)         { $this->writeMinValue($value); }
    function defaultMin()           { return 10; }


    /**
     * Upper limit of the range of possible positions.
     *
     * Use this property together with the Min property to establish the range of possible
     * positions of the progress bar.
     */
    function getMax()                   { return $this->readMaxValue(); }
    function setMax($value)             { $this->writeMaxValue($value); }
    function defaultMax()               { return 100; }

    // Documented in the parent.
    function getLow()                   { return $this->readLow(); }
    function setLow($value)             { $this->writeLow($value); }

    // Documented in the parent.
    function getHigh()                  { return $this->readHigh(); }
    function setHigh($value)            { $this->writeHigh($value); }

    // Documented in the parent.
    function getOptimum()               { return $this->readOptimum(); }
    function setOptimum($value)         { $this->writeOptimum($value); }

    // Documented in the parent.
    function getShowHint()              { return $this->readShowHint(); }
    function setShowHint($value)        { $this->writeShowHint($value); }

    function getParentShowHint()        { return $this->readParentShowHint(); }
    function setParentShowHint($value)  { $this->writeParentShowHint($value); }

    /*
     * Publish the JS events for the ProgressBar component
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
 * Base class for controls implementing an input field to select a web color.
 */
class CustomColor extends CustomEdit
{

    // Documented in the parent.
    function __construct($aowner=null)
    {
          //Calls inherited constructor
          parent::__construct($aowner);

          $this->Width=35;
          $this->Height=20;

          $this->_type = "color";
    }

    // Documented in the parent.
    protected function _getCommonAttributes()
    {
         // indicates either that the control's input data is particularly sensitive
         $autocomplete = ($this->_autocomplete != ceDisabled) ? "autocomplete=\"" . strtolower(substr($this->_autocomplete, 2)) . "\"" : "";

         return "$autocomplete";
    }

    // Documented in the parent.
    // This method is replaced to validate in HTML5.
    function dumpContents()
    {
        $attributes =  $this->_getCommonAttributes();

        $value = ($this->_text != "") ? "value=\"" . $this->_text . "\"" : "";

        // call the OnShow event if assigned so the Text property can be changed
        if ($this->_onshow != null)
        {
            $this->callEvent('onshow', array());
        }

        echo "<input type=\"$this->_type\" id=\"$this->_name\" name=\"$this->_name\" $value $attributes />";
    }

    // Documented in the parent.
    function dumpFormItems()
    {
    }

    // Documented in the parent.
    function pagecreate()
    {
      $output  = "";
      $output .= $this->bindJSEvent('click');
      $output .= $this->bindJSEvent('blur');
      $output .= $this->bindJSEvent('change');
      $output .= $this->bindJSEvent('dblclick');
      $output .= $this->bindJSEvent('focus');
      $output .= $this->bindJSEvent('keydown');
      $output .= $this->bindJSEvent('keypress');
      $output .= $this->bindJSEvent('keyup');
      $output .= $this->bindJSEvent('mousedown');
      $output .= $this->bindJSEvent('mouseover');
      $output .= $this->bindJSEvent('mouseout');
      $output .= $this->bindJSEvent('mousemove');
      $output .= $this->bindJSEvent('input');
      return $output;
    }

    // Documented in the parent.
    function dumpJsEvents()
    {
        parent::dumpJsEvents();
        $this->dumpJSEvent($this->_jsonclick);
        $this->dumpJSEvent($this->_jsonblur);
        $this->dumpJSEvent($this->_jsonchange);
        $this->dumpJSEvent($this->_jsondblclick);
        $this->dumpJSEvent($this->_jsonfocus);
        $this->dumpJSEvent($this->_jsonkeydown);
        $this->dumpJSEvent($this->_jsonkeypress);
        $this->dumpJSEvent($this->_jsonkeyup);
        $this->dumpJSEvent($this->_jsonmousedown);
        $this->dumpJSEvent($this->_jsonmouseup);
        $this->dumpJSEvent($this->_jsonmouseover);
        $this->dumpJSEvent($this->_jsonmousemove);
        $this->dumpJSEvent($this->_jsoninput);

    }
}

/**
 * Input field to select a web color.
 *
 * @link wiki://ColorPicker
 *
 * @example Components/ColorPicker/colorpicker.php
 */
class ColorPicker extends CustomColor
{

    /**
     * Color defined in the control, which can be changed by the user.
     */
    function getValue() { return $this->readText(); }
    function setValue($value) { $this->writeText($value); }

    // Documented in the parent.
    function getAutocomplete() { return $this->readAutocomplete(); }
    function setAutocomplete($value) { $this->writeAutocomplete($value); }

    /*
     * Publish the JS events for the ColorPicker component
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
    function getjsOnInput                   ()  { return $this->readjsOnInput(); }
    function setjsOnInput                   ($value) { $this->writejsOnInput($value); }

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