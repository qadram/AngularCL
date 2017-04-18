<?php

/**
 * html5/html5.inc.php
 *
 * Defines HTML5 components.
 *
 * This file is part of the RPCL project.
 *
 * Copyright (c) 2004-2012 Embarcadero Technologies, Inc.
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
 * @copyright   2004-2012 Embarcadero Technologies, Inc.
 * @license     http://www.gnu.org/licenses/lgpl-2.1.txt LGPL
 *
 */

// Includes.
require_once("rpcl/rpcl.inc.php");
use_unit("extctrls.inc.php");



/**
 * Base class for components using the HTML5 geolocation feature.
 */
class CustomGeolocation extends Component
{

    protected $_enabled=true;

    /**
     * Whether the geolocation data should be retrieved as soon as the webpage is loaded
     * (true), or if data should be requested later from JavaScript instead (false).
     *
     * If you decide not to request geolocation data upon page load, you can use the following
     * functions to enable or disable geolocation data retrieval:
     * - ComponentNameRetrieve(). Performs a single request for the current position.
     * - ComponentNameActivate(). Requests the current geolocation data and watches for position changes.
     * - ComponentNameDeactivate(). Disables the watching of the position changes.
     *
     * Note: Even if you set Enabled to true, JavaScript functions will be available
     * for you to disable or enable back geolocation data retrieval.
     */
    function readEnabled() { return $this->_enabled; }
    function writeEnabled($value) { $this->_enabled=$value; }
    function defaultEnabled() { return true; }

    protected $_repeat=true;

   /**
    * Whether the geolocation data should be retrieved just once (false) or for every
    * change of position (true).
    *
    * If set to false, the OnChange event will be triggered just once, as soon as the page
    * is loaded. If set to true, the OnChange event will also be triggered upon page load,
    * but also every time the position changes. That way, you can keep track of the movement of
    * the user.
    *
    * If you decide to watch the location for changes, you can later disable it from JavaScript:
    * call ComponentNameDeactivate(). You can also enable the watching back with
    * ComponentNameActivate().
    */
    function readRepeat() { return $this->_repeat; }
    function writeRepeat($value) { $this->_repeat=$value; }
    function defaultRepeat() { return true; }

   protected $_maxage = 10000;

   /**
    * The web browser will be asked to retrieve new data if cached data is older than the value
    * of this property (in milliseconds).
    *
    * That is, if more time has passed since the last time the web browser cached user's location
    * than the time specified on this property, the web browser will be required to retrieve new
    * geolocation data instead of providing the cached data.
    *
    * If set to 0, the web browser will be asked to retrieve new geolocation data despite having
    * cached data already.
    *
    * When watching the client-side position (see Repeat property), MaxAge will
    * only affect the first data retrieval, since later updates are fetched upon position change.
    */
   function readMaxAge()    {return $this->_maxage;}
   function writeMaxAge($value)    {$this->_maxage = $value;}
   function defaultMaxAge()    {return 10000;}

   protected $_timeout = 10000;

   /**
    * Time (in milliseconds) before a position request throws a timeout error.
    *
    * If the position retrieval times out, the OnError event will be trigered instead of
    * the OnChange event. The event handler for the OnError event will get an error
    * object with the error code (error.code) set to error.TIMEOUT.
    *
    * An event handler to manage this error could look like this:
    *
    * <code>
    * function GeolocationJSError($sender, $params)
    * {
    *   ?>
    *     if(event.code === event.TIMEOUT)
    *     {
    *       alert('Geolocation data retrieval timed out!');
    *     }
    *     else
    *     {
    *       alert('Something other than a timeout failed during geolocation data retrieval.');
    *     }
    *   <?php
    * }
    * </code>
    */
   function readTimeout()    {return $this->_timeout;}
   function writeTimeout($value)    {$this->_timeout = $value;}
   function defaultTimeout()    {return 10000;}

   protected $_highaccuracy = false;

   /**
    * Whether geolocation data should have the highest accuracy possible (true) or not
    * (false).
    *
    * Setting this property to false will usually results in the web browser using the faster
    * method to retrieve the data, so you should not set this property to true unless you
    * actually need it.
    *
    * "Supported" means both "supported by the web browser (and device)" and "allowed by the user". Hence, if there
    * is only one possible retrieval method, this property will have no effect.
    *
    * On your handler for the OnChange event, retrieved data might include an accuracy
    * level in meters:
    *
    * <code>
    * function GeolocationJSChange($sender, $params)
    * {
    *   ?>
    *     if(event.coords.accuracy === null)
    *     {
    *       alert('Geolocation data accuracy is unknown.');
    *     }
    *     else
    *     {
    *       alert('Geolocation data accuracy: ' + event.coords.accuracy + 'm.');
    *     }
    *   <?php
    * }
    * </code>
    */
   function readHighAccuracy()    {return $this->_highaccuracy;}
   function writeHighAccuracy($value)    {$this->_highaccuracy = $value;}
   function defaultHighAccuracy()    {return false;}

    protected $_jsonchange = null;

    /**
     * Triggered upon geolocation data retrieval.
     *
     * The event variable will contain these properties:
     * <ul>
     *   <li>event.coords. Object with the following data:
     *     <ul>
     *       <li>event.coords.latitude. Latitude in decimal degrees. The value range is [-90.00, +90.00]. Example: 37.051060.</li>
     *       <li>event.coords.longitude. Longitude in decimal degrees. The value range is [-180.00, +180.00]. Example: -122.014684.</li>
     *       <li>event.coords.altitude. Height of the position in meters above the WGS84 ellipsoid (http://es.wikipedia.org/wiki/WGS84). Optional.</li>
     *       <li>event.coords.accuracy. Accuracy level of the latitude and longitude coordinates in meters. Optional.</li>
     *       <li>event.coords.altitudeAccuracy. Accuracy level of the altitude coordinate in meters. Optional.</li>
     *       <li>event.coords.heading. Movement direction, specified in degrees counting clockwise relative to the true north (http://en.wikipedia.org/wiki/True_north). Optional.</li>
     *       <li>event.coords.speed. Current ground speed, specified in meters per second (http://en.wikipedia.org/wiki/Metre_per_second). Optional.</li>
     *     </ul>
     *   </li>
     *   <li>event.timestamp. DOMTimeStamp (https://developer.mozilla.org/en/DOM/DOMTimeStamp) with the POSIX time (http://en.wikipedia.org/wiki/Unix_time) at which the
     *   geolocation data was "taken" by the web browser.</li>
     * </ul>
     *
     * Note: "Optional" data is set to null if not available.
     *
     * When geolocation data cannot be retrieved, the OnError event is triggered instead.
     *
     * Sample event handler:
     *
     * <code>
     * function GeolocationJSChange($sender, $params)
     * {
     *   ?>
     *     updateMap(event.coords.latitude, event.coords.longitude); // Fictional function.
     *   <?php
     * }
     * </code>
     */
    function readjsOnChange() { return $this->_jsonchange; }
    function writejsOnChange($value) { $this->_jsonchange=$value; }
    function defaultjsOnChange()    {return null;}

    protected $_jsonerror=null;

    /**
     * Triggered after an attempt to retrieve geolocation data fails.
     *
     * The event variable will contain two properties:
     * <ul>
     *   <li>event.code. The error code. It can have any of the following values:
     *     <ul>
     *       <li>event.UNKNOWN_ERROR. The reason of the error is unknown.</li>
     *       <li>event.PERMISSION_DENIED. The web browser was not given permission to provide the geolocation data.</li>
     *       <li>event.POSITION_UNAVAILABLE. The location could not be determined.</li>
     *       <li>event.TIMEOUT. Geolocation data could not be retrieved within the specified interval. Use the Timeout property to specify that interval.</li>
     *     </ul>
     *   </li>
     *   <li>event.message. An human-readable message describing the cause of the error.</li>
     * </ul>
     *
     * An event handler to manage this error could look like this:
     *
     * <code>
     * function GeolocationJSError($sender, $params)
     * {
     *   ?>
     *     switch(event.code)
     *     {
     *       case event.UNKNOWN_ERROR:
     *         alert('Geolocation data could not be retrieved: ' + event.message);
     *         break;
     *       case event.PERMISSION_DENIED:
     *         alert('Why don't you let me find you? :(');
     *         break;
     *       case event.POSITION_UNAVAILABLE:
     *         alert('It was not possible to find your coordinates.');
     *         break;
     *       case event.TIMEOUT:
     *         alert('Geolocation data retrieval timed out!');
     *         break;
     *     }
     *   <?php
     * }
     * </code>
     */
    function readjsOnError() { return $this->_jsonerror; }
    function writejsOnError($value) { $this->_jsonerror=$value; }
    function defaultjsOnError()    {return null;}

    /**
     * Returns a string with a JavaScript array of options to be passed on the call to the Geolocation API.
     *
     * @return string JavaScript array of options for Geolocation JavaScript methods.
     *
     * @internal
     */
    private function getjsOptions()
    {

        return "{enableHighAccuracy: " . ($this->_highaccuracy ? 'true': 'false') . "," .
                       " timeout: " . $this->_timeout . "," .
                       " maximumAge: " . $this->_maxage ."}";
    }

    /**
     * Prints the code for component's JavaScript events.
     *
     * @internal
     */
   function dumpJavascript()
   {
       parent::dumpJavascript();

       $this->dumpJSEvent($this->_jsonchange);
       $this->dumpJSEvent($this->_jsonerror);

       //Component identifier for use in watchPosition function
       echo $result = "var " . $this->Name . "_ID = null;\n";

       $this->DumpJSFunctions($this->getjsOptions());
   }

   function pagecreate()
   {

        $result='';
        //The callback 'jsOnChange' is required
        if ($this->_jsonchange != "")
        {

          $result .= "function " . $this->Name . "_Init() {\n";

          $result .= "\t " . $this->Name . "_ID = navigator.geolocation.";

          $result .= ($this->_repeat) ? "watchPosition(" : "getCurrentPosition(" ;

          $result .= $this->_jsonchange;

          if ($this->_jsonerror != null)
             $result .= ", ". $this->_jsonerror;
          else
            $result .= ", null";

          $result .= ", " . $this->getjsOptions() . ");\n\n";


          $result .= "  }\n\n";

          if ($this->_enabled)
              $result .= $this->Name . "_Init();\n";
        }

        return $result;


   }

   /**
    * Prints either the provided JavaScript function definition or an empty one.
    *
    * @param  string $event JavaScript function definition, or an empty string.
    * @return string Either the provided string or an empty JavaScript function definition.
    *
    * @internal
    */
   function checkEmptyFunction($event)
   {
      if($event == "")
         return "function(){}";
      else
         return $event;
   }

    /**
     * PrintS JavaScript functions to control the component.
     *
     * It defines:
     * - ComponentNameRetrieve(). Performs a single request for the current position.
     * - ComponentNameActivate(). Requests the current geolocation data and watches for position changes.
     * - ComponentNameDeactivate(). Disables the watching of the position changes.
     *
     * @param  string JavaScript array with options for the JavaScript functions.
     *
     * @internal
     */
    function DumpJSFunctions($options)
    {

      ?>
      var <?php echo $this->Name?>Activate=function(options) {
        var options = options || <?php echo $options . "\n"?>
        <?php echo $this->Name ?>_ID = navigator.geolocation.watchPosition(<?php echo $this->checkEmptyFunction($this->_jsonchange) ?>, <?php echo $this->checkEmptyFunction($this->_jsonerror)?>,options);
        };

      var <?php echo $this->Name?>Retrieve=function(options) {
        var options = options || <?php echo $options . "\n"?>
        <?php echo $this->Name ?>_ID = navigator.geolocation.getCurrentPosition(<?php echo $this->checkEmptyFunction($this->_jsonchange) ?>, <?php echo $this->checkEmptyFunction($this->_jsonerror)?>,options);
        };

      var <?php echo $this->Name?>Deactivate=function() {
        navigator.geolocation.clearWatch(<?php echo $this->Name ?>_ID);
        <?php echo  $this->Name?>_ID = null;
      }
      <?php
   }

}


/**
 * Component to retrieve user's geolocation.
 *
 * The component requests the client's geolocation data to the web browser. This call produces
 * one of two results:
 *
 * - If the data is retrieved successfully, the OnChange event is triggered.
 * - If an error occurs, the OnError event is triggered instead.
 *
 * Use the Enabled property to determine that the component must request the geolocation data right after the
 * page is loaded (true). If set to false, the data won't be requested automatically. You can also request the data at
 * any time from JavaScript (see below).
 *
 * When the Enabled property is set to true, the value of the Repeat property will determine how ofter the
 * data is retrieved: just once (false), or every time the position changes (true).
 *
 * If you want to manually request geolocation data from JavaScript, or start the geolocation data watching, set the
 * Enabled property to false, and use the following JavaScript functions:
 * - ComponentNameRetrieve(). Performs a single request for the current position.
 * - ComponentNameActivate(). Requests the current geolocation data and watches for position changes.
 * - ComponentNameDeactivate(). Disables the watching of the position changes.
 *
 * These JavaScript functions are always available when using the Geolocation component,
 * despite the value you give to the Enabled property.
 *
 * @link wiki://Geolocation
 * @example HTML5/GeolocationHideAndSeek.php
 */
class Geolocation extends CustomGeolocation
{
   function getRepeat() { return $this->readRepeat(); }
   function setRepeat($value) { $this->writeRepeat($value); }

   function getEnabled()    {return $this->readEnabled();}
   function setEnabled($value)    {$this->writeEnabled($value);}

   function getMaxAge()    {return $this->readmaxage();}
   function setMaxAge($value)    {$this->writemaxage($value);}

   function getTimeout()    {return $this->readtimeout();}
   function setTimeout($value)    {$this->writetimeout($value);}

   function getHighAccuracy()    {return $this->readHighAccuracy();}
   function setHighAccuracy($value)    {$this->writeHighAccuracy($value);}

   function getjsOnChange()    {return $this->readjsonchange();}
   function setjsOnChange($value)    {$this->writejsonchange($value);}

   function getjsOnError() { return $this->readjsonerror(); }
   function setjsOnError($value) { $this->writejsonerror($value); }
}


// Type of media to be played.
define('mtVideo','mtVideo');
define('mtAudio','mtAudio');

// Possible values of the Preload property.
define('pNone','pNone');
define('pMetadata','pMetadata');
define('pAuto','pAuto');

// Possible values for Width and Height component
define('dfWidthAudioVideo', 320);
define('dfHeightVideo', 160);
define('dfHeightAudio', 36);

/**
 * Base class for components using the HTML5 media player.
 */
class CustomMedia extends GraphicControl
{

    function    __construct($aowner=null)
    {
        // Calls the parent constructor.
        parent::__construct($aowner);

        $this->Width = dfWidthAudioVideo;
        $this->Height = dfHeightVideo;

        // This components needs to be renreded with Webkit on the HTML5 Builder Designer.
        $this->ControlStyle = "csWebEngine=webkit";
        //$this->ControlStyle = "csSlowRedraw=1";
    }

    protected $_notsupportedmessage='Your web browser does not support the HTML5 media player (audio and video HTML5 elements).';

    /**
     * Content to be displayed on web browsers that do not support the HTML5 media player (audio and video HTML5 elements).
     *
     * For example, you could provide a link to download the media content.
     */
    function readNotSupportedMessage() { return $this->_notsupportedmessage; }
    function writeNotSupportedMessage($value) { $this->_notsupportedmessage=$value; }
    function defaultNotSupportedMessage() { return 'Your web browser does not support the HTML5 media player (audio and video HTML5 elements).'; }

    protected $_mediatype="mtVideo";

    /**
     * Type of content to be played.
     *
     * The following types are supported:
     * <ul>
     *   <li>mtAudio. Audio content.</li>
     *   <li>mtVideo. Video content.</li>
     * </ul>
     */
    function readMediaType() { return $this->_mediatype; }
    function writeMediaType($value) { $this->_mediatype=$value; }
    function defaultMediaType() { return "mtVideo"; }

    protected $_autoplay=false;

    /**
     * Whether the content should be played upon load (true), or if the user should manually
     * start the player instead (false).
     */
    function readAutoPlay() { return $this->_autoplay; }
    function writeAutoPlay($value) { $this->_autoplay=$value; }
    function defaultAutoPlay() { return false; }

    protected $_preload="pAuto";

    /**
     * Suggests the web browser how to preload the content to be played.
     *
     * Possible values are:
     * <ul>
     *   <li>pAuto. Preload as much content as possible.</li>
     *   <li>pMetadata. Preload the metadata of the content, such as the duration and dimensions (width and height).</li>
     *   <li>pNone. Do not preload the content, wait for the user to request it.</li>
     * </ul>
     *
     * Note: Web browsers are free to do as they see fits, despite the value you give to this property.
     */
    function readPreload() { return $this->_preload; }
    function writePreload($value) { $this->_preload=$value; }
    function defaultPreload() { return "pAuto"; }

    protected $_showcontrols=true;

    /**
     * Whether the media player controls should be displayed (true) or not (false).
     *
     * You might want not to display the controls if you are controlling the playback yourself from JavaScript. This would be the case, for example, if
     * you develop your own controls.
     *
     * @link wiki://Media#Control_from_JavaScript
     */
    function readShowControls() { return $this->_showcontrols; }
    function writeShowControls($value) { $this->_showcontrols=$value; }
    function defaultShowControls() { return true; }

    protected $_loop=true;

    /**
     * Whether the playback should continue from the beginning once it finishes (true),
     * or if it should stop instead (false).
     */
    function readLoop() { return $this->_loop; }
    function writeLoop($value) { $this->_loop=$value; }
    function defaultLoop() { return true; }

    protected $_sources = array();

    /**
     * List of the different versions of the content to be played, for wider web browser support.
     *
     * Each item might have the following properties:
     * <ul>
     *   <li>Source. URL pointing to the media file.</li>
     *   <li>Mediatype. MIME type (http://en.wikipedia.org/wiki/MIME) of the media file for audio (http://en.wikipedia.org/wiki/Internet_media_type#Type_audio) or video (http://en.wikipedia.org/wiki/Internet_media_type#Type_video). For example: mp4.</li>
     *   <li>Codecs. Optional. Comma-separated list of codecs used to encode the media file. For example: vp8.0, vorbis.</li>
     * </ul>
     *
     * All items are supposed to point to the same media (that is, the same song or the same
     * movie), each of them in a different format (combination of MIME type and codecs). Web
     * browsers will play the first file on the list they support.
     */
    function readSources() { return $this->_sources; }
    function writeSources($value) { $this->_sources=$value; }
    function defaultSources() { return array(); }

    protected $_loadingimage="";

    /**
     * Image to be displayed while the content of the media player is being loaded.
     */
    function readLoadingImage() { return $this->_loadingimage; }
    function writeLoadingImage($value) { $this->_loadingimage=$value; }
    function defaultLoadingImage() { return ""; }

    protected $_muted=false;

    /**
     * Whether the media player should be muted by default (true) or not (false).
     */
    function readMuted() { return $this->_muted; }
    function writeMuted($value) { $this->_muted=$value; }
    function defaultMuted() { return false; }

    /**
     * Parses the MediaType of the player, as well as the Mediatype and Codecs
     * properties of an item from the Sources array, and returns the proper value for the
     * type HTML attribute of the target source element.
     *
     * @internal
     */
    protected function parseMediaType($element, $media_type, $codecs)
    {

      $ext = $element . "/";

      if (stripos($media_type, "og") !== false) $ext .= "ogg"; // For .ogv and .ogg MIME types.
      elseif (stripos($media_type, "webm") !== false) $ext .= "webm";
      elseif (stripos($media_type, "mp4") !== false) $ext .= "mp4";


      elseif (($element == "audio") && (stripos($media_type, "Mpeg") !== false)) $ext .= "mp3";
      elseif (stripos($media_type, "wav") !== false) $ext .= "wav";

      else
         //The user has specified a MediaType that is not in the property editor
         $ext = $media_type;

      if ($codecs != "")
        $ext .= ";codecs='$codecs'";


      return $ext;

    }

    /**
     * Parses the Preload property, and returns the proper value for the HTML preload
     * attribute of the HTML audio or video element.
     *
     * @internal
     */
    protected function parsePreload($value)
    {
        switch ($value)
        {
          case pNone: return 'none';
          case pMetadata: return 'metadata';
          case pAuto: return 'auto';
          default: return 'auto';
        }
    }

    function dumpContents()
    {
        $element = $this->_mediatype == 'mtVideo' ? "video":"audio";

        echo "<$element id=\"$this->_name\" " ;

                if ($this->_showcontrols) echo " controls ";

                if (($this->_autoplay) && ($this->ControlState & csDesigning) != csDesigning)
                {
                    echo " autoplay ";
                }

                if ($this->_loadingimage!="") echo " poster=\"$this->_loadingimage\" ";

                if ($this->_loop) echo " loop ";

                if ($this->_preload) echo " preload=\"{$this->parsePreload($this->_preload)}\" ";

                if ($this->_muted) echo " muted ";



                echo " >\n";

                  // Decode the sources array.
                  if ($this->_sources)
                  {
                     foreach ($this->_sources as $source)
                     {
                        $src =  str_replace(' ', '%20', $source['Source']);

                        echo "<source src=\"$src\" ";

                        if ($source['Mediatype'] != "")
                        {
                          $MediaType = $this->parseMediaType($element, $source['Mediatype'], $source['Codecs']);
                          echo " type=\"$MediaType\" ";
                        }

                        echo " />\n";
                     }

                  }
                  echo $this->_notsupportedmessage . "\n";

        echo "</$element>";

    }

    function dumpCSS()
    {
        if ($this->_hidden) echo "visibility:hidden;\n";
        echo $this->_readCSSSize();

        parent::dumpCSS();
    }

   function pagecreate()
   {
      // bind standart events
      $output  = parent::pagecreate();

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

      // bind media events
      $output .= $this->bindJSEvent('abort');
      $output .= $this->bindJSEvent('canplaythrough');
      $output .= $this->bindJSEvent('durationchange');
      $output .= $this->bindJSEvent('emptied');
      $output .= $this->bindJSEvent('ended');
      $output .= $this->bindJSEvent('error');
      $output .= $this->bindJSEvent('loadeddata');
      $output .= $this->bindJSEvent('loadedmetadata');
      $output .= $this->bindJSEvent('loadstart');
      $output .= $this->bindJSEvent('pause');
      $output .= $this->bindJSEvent('play');
      $output .= $this->bindJSEvent('playing');
      $output .= $this->bindJSEvent('progress');
      $output .= $this->bindJSEvent('ratechange');
      $output .= $this->bindJSEvent('readystatechange');
      $output .= $this->bindJSEvent('seeked');
      $output .= $this->bindJSEvent('seeking');
      $output .= $this->bindJSEvent('stalled');
      $output .= $this->bindJSEvent('suspend');
      $output .= $this->bindJSEvent('timeupdate');
      $output .= $this->bindJSEvent('volumechange');
      $output .= $this->bindJSEvent('waiting');

      return $output;
   }

    function dumpJsEvents()
    {
        // parent dumps standard events
        parent::dumpJsEvents();


        $this->dumpJSEvent($this->_jsonabort);
        $this->dumpJSEvent($this->_jsoncanplay);
        $this->dumpJSEvent($this->_jsoncanplaythrough);
        $this->dumpJSEvent($this->_jsondurationchange);
        $this->dumpJSEvent($this->_jsonemptied);
        $this->dumpJSEvent($this->_jsonended);
        $this->dumpJSEvent($this->_jsonerror);
        $this->dumpJSEvent($this->_jsonloadeddata);
        $this->dumpJSEvent($this->_jsonloadedmetadata);
        $this->dumpJSEvent($this->_jsonloadstart);
        $this->dumpJSEvent($this->_jsonpause);
        $this->dumpJSEvent($this->_jsonplay);
        $this->dumpJSEvent($this->_jsonplaying);
        $this->dumpJSEvent($this->_jsonprogress);
        $this->dumpJSEvent($this->_jsonratechange);
        $this->dumpJSEvent($this->_jsonreadystatechange);
        $this->dumpJSEvent($this->_jsonseeked);
        $this->dumpJSEvent($this->_jsonseeking);
        $this->dumpJSEvent($this->_jsonstalled);
        $this->dumpJSEvent($this->_jsonsuspend);
        $this->dumpJSEvent($this->_jsontimeupdate);
        $this->dumpJSEvent($this->_jsonvolumechange);
        $this->dumpJSEvent($this->_jsonwaiting);
    }

    protected $_jsonabort=null;

    /**
     * The data retrieval of the media from the source stopped before the download was complete.
     *
     * The networkState property of the component will have the value NETWORK_EMPTY if the download had
     * not yet stated, and NETWORK_IDLE if it had but was stopped.
     */
    function readjsOnAbort() { return $this->_jsonabort; }
    function writejsOnAbort($value) { $this->_jsonabort=$value; }
    function defaultjsOnAbort() { return null; }

    protected $_jsoncanplay=null;

    /**
     * Enough data of the media was retrieved to play a bit.
     *
     */
    function readjsOnCanPlay() { return $this->_jsoncanplay; }
    function writejsOnCanPlay($value) { $this->_jsoncanplay=$value; }
    function defaultjsOnCanPlay() { return null; }

    protected $_jsoncanplaythrough=null;

    /**
     * The web browser determined that it can play though all the media without stopping for downloading more
     * data.
     */
    function readjsOnCanPlayThrough() { return $this->_jsoncanplaythrough; }
    function writejsOnCanPlayThrough($value) { $this->_jsoncanplaythrough=$value; }
    function defaultjsOnCanPlayThrough() { return null; }

    protected $_jsondurationchange=null;

    /**
     * The duration of the media changed.
     */
    function readjsOnDurationChange() { return $this->_jsondurationchange; }
    function writejsOnDurationChange($value) { $this->_jsondurationchange=$value; }
    function defaultjsOnDurationChange() { return null; }

    protected $_jsonemptied=null;

    /**
     * The media component was reset to its initial, uninitialized state.
     *
     * This event is triggered when you call the load() method of the component, for example, or when you change its
     * src property.
     */
    function readjsOnEmptied() { return $this->_jsonemptied; }
    function writejsOnEmptied($value) { $this->_jsonemptied=$value; }
    function defaultjsOnEmptied() { return null; }

    protected $_jsonended=null;

    /**
     * The end of the media has been reached.
     */
    function readjsOnEnded() { return $this->_jsonended; }
    function writejsOnEnded($value) { $this->_jsonended=$value; }
    function defaultjsOnEnded() { return null; }

    protected $_jsonerror=null;

    /**
     * The component failed to load properly because none of the specified sources can be used.
     */
    function readjsOnError() { return $this->_jsonerror; }
    function writejsOnError($value) { $this->_jsonerror=$value; }
    function defaultjsOnError() { return null; }

    protected $_jsonloadeddata=null;

    /**
     * The webbrowser can render a frame of the media at the current playback position for the first time.
     *
     * This event is usually triggered between OnLoadedMetaData and OnCanPlay.
     */
    function readjsOnLoadedData() { return $this->_jsonloadeddata; }
    function writejsOnLoadedData($value) { $this->_jsonloadeddata=$value; }
    function defaultjsOnLoadedData() { return null; }

    protected $_jsonloadedmetadata=null;

    /**
     * Enough data of the media has been retrieved to determine its duration (and dimentions if a video).
     *
     * This event is usually followed by OnLoadedData.
     */
    function readjsOnLoadedMetaData() { return $this->_jsonloadedmetadata; }
    function writejsOnLoadedMetaData($value) { $this->_jsonloadedmetadata=$value; }
    function defaultjsOnLoadedMetaData() { return null; }

    protected $_jsonloadstart=null;

    /**
     * The load of the media started.
     */
    function readjsOnLoadStart() { return $this->_jsonloadstart; }
    function writejsOnLoadStart($value) { $this->_jsonloadstart=$value; }
    function defaultjsOnLoadStart() { return null; }

    protected $_jsonpause=null;

    /**
     * The playback of the media has been paused.
     */
    function readjsOnPause() { return $this->_jsonpause; }
    function writejsOnPause($value) { $this->_jsonpause=$value; }
    function defaultjsOnPause() { return null; }

    protected $_jsonplay=null;

    /**
     * The playback of the media was requested to be initiated.
     *
     * This event might be trigegred as soon as the play() method is executed, or when the Media component is loaded
     * and it is configured to initiate the playback automatically.
     */
    function readjsOnPlay() { return $this->_jsonplay; }
    function writejsOnPlay($value) { $this->_jsonplay=$value; }
    function defaultjsOnPlay() { return null; }

    protected $_jsonplaying=null;

    /**
     * The playback of the media has been initiated.
     */
    function readjsOnPlaying() { return $this->_jsonplaying; }
    function writejsOnPlaying($value) { $this->_jsonplaying=$value; }
    function defaultjsOnPlaying() { return null; }

    protected $_jsonprogress=null;

    /**
     * The web browser has retrieved media data.
     *
     * This event is triggered every time media data is downloaded.
     */
    function readjsOnProgress() { return $this->_jsonprogress; }
    function writejsOnProgress($value) { $this->_jsonprogress=$value; }
    function defaultjsOnProgress() { return null; }

    protected $_jsonratechange=null;

    /**
     * The playbackRate property of the Media component has changed.
     */
    function readjsOnRateChange() { return $this->_jsonratechange; }
    function writejsOnRateChange($value) { $this->_jsonratechange=$value; }
    function defaultjsOnRateChange() { return null; }

    protected $_jsonreadystatechange=null;

    /**
     * The readyState property of the Media component has changed.
     */
    function readjsOnReadyStateChange() { return $this->_jsonreadystatechange; }
    function writejsOnReadyStateChange($value) { $this->_jsonreadystatechange=$value; }
    function defaultjsOnReadyStateChange() { return null; }

    protected $_jsonseeked=null;

    /**
     * A seek operation on the Media component ended.
     */
    function readjsOnSeeked() { return $this->_jsonseeked; }
    function writejsOnSeeked($value) { $this->_jsonseeked=$value; }
    function defaultjsOnSeeked() { return null; }

    protected $_jsonseeking=null;

    /**
     * A seek operation on the Media component started.
     */
    function readjsOnSeeking() { return $this->_jsonseeking; }
    function writejsOnSeeking($value) { $this->_jsonseeking=$value; }
    function defaultjsOnSeeking() { return null; }

    protected $_jsonstalled=null;

    /**
     * The web browser is trying to retrieve the media data, but the server stopped sending data for some time
     * without closing the connection.
     */
    function readjsOnStalled() { return $this->_jsonstalled; }
    function writejsOnStalled($value) { $this->_jsonstalled=$value; }
    function defaultjsOnStalled() { return null; }

    protected $_jsonsuspend=null;

    /**
     * The web browser stopped downloading data of the media, generally in orer to save bandwidth.
     */
    function readjsOnSuspend() { return $this->_jsonsuspend; }
    function writejsOnSuspend($value) { $this->_jsonsuspend=$value; }
    function defaultjsOnSuspend() { return null; }

    protected $_jsontimeupdate=null;

    /**
     * The current position in the playback changed.
     *
     * This event can be triggered because of the normal playback, for example, or because of a a seeking operation.
     */
    function readjsOnTimeUpdate() { return $this->_jsontimeupdate; }
    function writejsOnTimeUpdate($value) { $this->_jsontimeupdate=$value; }
    function defaultjsOnTimeUpdate() { return null; }

    protected $_jsonvolumechange=null;

    /**
     * The playback volume has been changed.
     */
    function readjsOnVolumeChange() { return $this->_jsonvolumechange; }
    function writejsOnVolumeChange($value) { $this->_jsonvolumechange=$value; }
    function defaultjsOnVolumeChange() { return null; }

    protected $_jsonwaiting=null;

    /**
     * The web browser is waiting for enough data to be downloaded to start or resume the media playback.
     */
    function readjsOnWaiting() { return $this->_jsonwaiting; }
    function writejsOnWaiting($value) { $this->_jsonwaiting=$value; }
    function defaultjsOnWaiting() { return null; }
}

/**
 * Media player for audio or video files.
 *
 * In order for the component to work properly, you must provide at least a list of
 * Sources, as well as the MediaType (mtVideo by default).
 *
 * The player can display client-side playback controls (defined by the web browser), or you can
 * choose not to display controls at all, and use the JavaScript API
 * to control the playback from JavaScript, using for example your own controls.
 *
 * @link wiki://Media
 * @example HTML5/MediaAnimals.php
 * @example HTML5/MediaHomeCinema.php
 */
class Media extends CustomMedia {

    // Documented in the parent.
    function getMuted() { return $this->readMuted(); }
    function setMuted($value) { $this->writeMuted($value); }

    // Documented in the parent.
    function getAutoPlay() { return $this->readAutoPlay(); }
    function setAutoPlay($value) { $this->writeAutoPlay($value); }

    // Documented in the parent.
    function getShowControls() { return $this->readShowControls(); }
    function setShowControls($value) { $this->writeShowControls($value); }

    // Documented in the parent.
    function getLoop() { return $this->readLoop(); }
    function setLoop($value) { $this->writeLoop($value); }

    // Documented in the parent.
    function getNotSupportedMessage() { return $this->readNotSupportedMessage(); }
    function setNotSupportedMessage($value) { $this->writeNotSupportedMessage($value); }

    // Documented in the parent.
    function getMediaType() { return $this->readMediaType(); }
    function setMediaType($value) { $this->writeMediaType($value); }

    // Documented in the parent.
    function getPreload() { return $this->readPreload(); }
    function setPreload($value) { $this->writePreload($value); }

    // Documented in the parent.
    function getLoadingImage() { return $this->readLoadingImage(); }
    function setLoadingImage($value) { $this->writeLoadingImage($value); }

    // Documented in the parent.
    function getSources() { return $this->readSources(); }
    function setSources($value) { $this->writeSources($value); }

    // Documented in the parent.
    function getHidden() { return $this->_hidden; }
    function setHidden($value) { $this->_hidden=$value; }

    // Documented in the parent.
    function getDraggable()    {return $this->readdraggable();}
    function setDraggable($value)    {$this->writedraggable($value);}

    /*
     * Publish the JS standard events for the Media component
     */

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



    /*
     * Publish the JS media events for the Media component
     */

    // Documented in the parent.
    function getjsOnAbort() { return $this->readjsonabort(); }
    function setjsOnAbort($value) { $this->writejsonabort($value); }

    // Documented in the parent.
    function getjsOnCanPlay() { return $this->readjsoncanplay(); }
    function setjsOnCanPlay($value) { $this->writejsoncanplay($value); }

    // Documented in the parent.
    function getjsOnCanPlayThrough() { return $this->readjsoncanplaythrough(); }
    function setjsOnCanPlayThrough($value) { $this->writejsoncanplaythrough($value); }

    // Documented in the parent.
    function getjsOnDurationChange() { return $this->readjsondurationchange(); }
    function setjsOnDurationChange($value) { $this->writejsondurationchange($value); }

    // Documented in the parent.
    function getjsOnEmptied() { return $this->readjsonemptied(); }
    function setjsOnEmptied($value) { $this->writejsonemptied($value); }

    // Documented in the parent.
    function getjsOnEnded() { return $this->readjsonended(); }
    function setjsOnEnded($value) { $this->writejsonended($value); }

    // Documented in the parent.
    function getjsOnError() { return $this->readjsonerror(); }
    function setjsOnError($value) { $this->writejsonerror($value); }

    // Documented in the parent.
    function getjsOnLoadedData() { return $this->readjsonloadeddata(); }
    function setjsOnLoadedData($value) { $this->writejsonloadeddata($value); }

    // Documented in the parent.
    function getjsOnLoadedMetaData() { return $this->readjsonloadedmetadata(); }
    function setjsOnLoadedMetaData($value) { $this->writejsonloadedmetadata($value); }

    // Documented in the parent.
    function getjsOnLoadStart() { return $this->readjsonloadstart(); }
    function setjsOnLoadStart($value) { $this->writejsonloadstart($value); }

    // Documented in the parent.
    function getjsOnPause() { return $this->readjsonpause(); }
    function setjsOnPause($value) { $this->writejsonpause($value); }

    // Documented in the parent.
    function getjsOnPlay() { return $this->readjsonplay(); }
    function setjsOnPlay($value) { $this->writejsonplay($value); }

    // Documented in the parent.
    function getjsOnPlaying() { return $this->readjsonplaying(); }
    function setjsOnPlaying($value) { $this->writejsonplaying($value); }

    // Documented in the parent.
    function getjsOnProgress() { return $this->readjsonprogress(); }
    function setjsOnProgress($value) { $this->writejsonprogress($value); }

    // Documented in the parent.
    function getjsOnRateChange() { return $this->readjsonratechange(); }
    function setjsOnRateChange($value) { $this->writejsonratechange($value); }

    // Documented in the parent.
    function getjsOnReadyStateChange() { return $this->readjsonreadystatechange(); }
    function setjsOnReadyStateChange($value) { $this->writejsonreadystatechange($value); }

    // Documented in the parent.
    function getjsOnSeeked() { return $this->readjsonseeked(); }
    function setjsOnSeeked($value) { $this->writejsonseeked($value); }

    // Documented in the parent.
    function getjsOnSeeking() { return $this->readjsonseeking(); }
    function setjsOnSeeking($value) { $this->writejsonseeking($value); }

    // Documented in the parent.
    function getjsOnStalled() { return $this->readjsonstalled(); }
    function setjsOnStalled($value) { $this->writejsonstalled($value); }

    // Documented in the parent.
    function getjsOnSuspend() { return $this->readjsonsuspend(); }
    function setjsOnSuspend($value) { $this->writejsonsuspend($value); }

    // Documented in the parent.
    function getjsOnTimeUpdate() { return $this->readjsontimeupdate(); }
    function setjsOnTimeUpdate($value) { $this->writejsontimeupdate($value); }

    // Documented in the parent.
    function getjsOnVolumeChange() { return $this->readjsonvolumechange(); }
    function setjsOnVolumeChange($value) { $this->writejsonvolumechange($value); }

    // Documented in the parent.
    function getjsOnWaiting() { return $this->readjsonwaiting(); }
    function setjsOnWaiting($value) { $this->writejsonwaiting($value); }
}

/**
 * Base class for components that allow you to define a series of animations.
 */
class CustomAnimation extends Component
{
    // Documented in the parent.
    function __construct($aowner = null)
    {
        //Calls inherited constructor
        parent::__construct($aowner);
    }


    protected $_items = array();

    /**
     * Array of animations.
     *
     * Each item (animation) of the array is an associative array with the following (optional) fields:
     * <ul>
     *   <li>Caption. A valid identifier (http://www.w3.org/TR/css3-syntax/#characters) for the animation. You will need it if you define more than one animation in this
     *   instance of the component (only one animation without identifier is allowed).</li>
     *   <li>Animation Duration. Duration of the animation. For example: '2s' (2 seconds).</li>
     *   <li>Iteration Count. Number of times the animation should be repeated before it finishes. You can use an integer
     *   number (for example, '3'), or 'infinite'.</li>
     *   <li>Animation Timing. Speed curve of the animation. The following values are supported:
     *     <ul>
     *       <li>ease. The animation goes slower during the start and the end.</li>
     *       <li>linear. The speed of the animation is the same from the start to the end.</li>
     *       <li>ease-in. The animation goes slower during the start.</li>
     *       <li>ease-out. The animation goes slower during the end.</li>
     *       <li>ease-in-out. The animation goes slower during the start and the end.</li>
     *     </ul>
     *   </li>
     *   <li>Fill Mode. When are the effects of the animation apparent. The following values are supported:
     *     <ul>
     *       <li>none. The effects of the animation are only apparent during the animation.</li>
     *       <li>forwards. The last keyframe of the animation is still applied once the animation finishes.</li>
     *     </ul>
     *   </li>
     *   <li>Start Rotate. Initial angle. For example: '90deg' for a right angle.</li>
     *   <li>End Rotate. Final angle.</li>
     *   <li>Start Scale. Initial scale. It must be specified as a floating-point value, where '1' is the normal scale. For example: '0.5' for half the size of the control.</li>
     *   <li>End Scale. Final scale.</li>
     *   <li>Start Skew. Initial horizontal skew angle. For example: '60deg'.</li>
     *   <li>End Skew. Final horizontal skew angle.</li>
     *   <li>Start Translate. Initial horizontal translation. For example: '80px'.</li>
     *   <li>End Translate. Final horizontal translation.</li>
     *   <li>Start Color. Initial color. For example: 'green'. It can be specified as a web color (http://en.wikipedia.org/wiki/Web_colors).</li>
     *   <li>End Color. Final color.</li>
     * </ul>
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


   // Documented in the parent.
   function dumpAdditionalCSS()
   {

        if (is_array($this->_items) && count($this->_items> 0) )
        {

            foreach ($this->_items as $animations)
            {
                $caption                        = $this->Name . "_" . (isset($animations['Caption']) ? $animations['Caption'] : "animation-name");
                $animation_duration             = isset($animations['Animation Duration']) ? $animations['Animation Duration'] : "0s";
                $iteration_count                = isset($animations['Iteration Count']) ? $animations['Iteration Count'] : "1";
                $animation_timing_function      = isset($animations['Animation Timing']) ? $animations['Animation Timing'] : "ease";
                $animation_fill_mode            = isset($animations['Fill Mode']) ? $animations['Fill Mode'] : "none";

                $css_animation_name = $caption . "_cssAnimation";

                /*
                [ <'animation-name'> || <'animation-duration'> || <'animation-timing-function'> || <'animation-delay'> ||
                   <'animation-iteration-count'> || <'animation-direction'> || <'animation-fill-mode'> ]
                 [, [<'animation-name'> || <'animation-duration'> || <'animation-timing-function'> || <'animation-delay'> ||
                     <'animation-iteration-count'> || <'animation-direction'> || <'animation-fill-mode'>] ]*
                */

                $animation_data = "$css_animation_name $animation_duration $iteration_count $animation_timing_function $animation_fill_mode;\n";

                echo AnimationCSS::getStandardAnimation($caption, $animation_data);

                $transform_from = "";

                $transform_to   = "";

                $start_rotate = $animations['Start Rotate'];
                $end_rotate   = $animations['End Rotate'];

                if (!empty($start_rotate) || !empty($end_rotate))
                {
                    $transform_from .= "rotate($start_rotate) ";
                    $transform_to .= "rotate($end_rotate) ";
                }

                $start_scale = $animations['Start Scale'];
                $end_scale = $animations['End Scale'];

                if (!empty($start_scale) || !empty($end_scale))
                {
                    $transform_from .= "scale($start_scale) ";
                    $transform_to   .= "scale($end_scale) ";
                }

                $start_skew = $animations['Start Skew'];
                $start_skew .= (stristr($start_skew, 'deg')) ? "" : "deg";

                $end_skew = $animations['End Skew'];
                $end_skew .= (stristr($end_skew, 'deg')) ? "" : "deg";

                if ( ($start_skew != "0deg") || ($end_skew != "0deg") )
                {
                    $transform_from .= "skew($start_skew) ";
                    $transform_to .= "skew($end_skew) ";
                }

                $start_translate = $animations['Start Translate'];
                $end_translate = $animations['End Translate'];

                //if (!empty($start_translate) || !empty($end_translate))
                if ( ($start_translate !="0px") || ($end_translate != "0px"))
                {
                    $transform_from .= "translate($start_translate)";
                    $transform_to   .= "translate($end_translate)";
                }

                $back_ground_from = "";

                if ($animations['Start Color'] != "")
                    $back_ground_from .= "background: " . $animations['Start Color'] . ";";

                $back_ground_to = "";

                if ($animations['End Color'] != "")
                    $back_ground_to .= "background: " . $animations['End Color'] . ";";

                if (!empty($transform_from) || !empty($transform_to))
                {
                    $transform_from .= ";";
                    $transform_to .= ";";

                    echo "@-webkit-keyframes $css_animation_name {\n" .
                         "\t from { $back_ground_from -webkit-transform: $transform_from }\n" .
                         "\t to   { $back_ground_to -webkit-transform: $transform_to }\n" .
                         "}\n";

                    echo "@-moz-keyframes $css_animation_name {\n" .
                         "\t from { $back_ground_from -moz-transform: $transform_from }\n" .
                         "\t to   { $back_ground_to -moz-transform: $transform_to }\n" .
                         "}\n";

                    echo "@-o-keyframes $css_animation_name {\n" .
                         "\t from { $back_ground_from -o-transform: $transform_from }\n" .
                         "\t to   { $back_ground_to -o-transform: $transform_to }\n" .
                         "}\n";

                    echo "@-ms-keyframes $css_animation_name {\n" .
                         "\t from { $back_ground_from -ms-transform: $transform_from }\n" .
                         "\t to   { $back_ground_to -ms-transform: $transform_to }\n" .
                         "}\n";

                    echo "@keyframes $css_animation_name {\n" .
                         "\t from { $back_ground_from transform: $transform_from }\n" .
                         "\t to   { $back_ground_to   transform: $transform_to }\n" .
                         "}\n";
                }

            }
        }
   }
}

/**
 * Component to define animations that can later be assigned to controls, either using their Animations property or from
 * JavaScript.
 *
 * The animations are defined using the Items property, where you can define several animations.
 *
 * @link wiki://Animations
 * @example Components/Animation/index.php
 */
class Animation extends CustomAnimation
{
    function getItems()       { return $this->readItems(); }
    function setItems($value) { $this->writeItems($value); }

}
