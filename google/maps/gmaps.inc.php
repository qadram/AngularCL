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

require_once("rpcl/rpcl.inc.php");

use_unit("controls.inc.php");

/**
 * Interactive world map powered by the Google Maps service.
 *
 * You can use the Google Maps API to modify the map from JavaScript. The map object is accessible using the global
 * variable ComponentName_map.
 *
 * @link https://developers.google.com/maps/documentation/javascript/reference
 * @link wiki://GoogleMap
 */
class GoogleMap extends Control
{
    function __construct($aowner = null)
    {
        parent::__construct($aowner);
        $this->Width=400;
        $this->Height=300;
        $this->ControlStyle="csVerySlowRedraw=1";
    }

    private $_mapskey="";

    /**
     * Google Maps API key to be used (optional).
     *
     * Using an API key, you can monitor your application's Maps API usage.
     *
     * @link https://developers.google.com/maps/documentation/javascript/tutorial#api_key How to get a Google Maps API key.
     *
     * @return string
     */
    function getMapsKey() { return $this->_mapskey; }
    function setMapsKey($value) { $this->_mapskey=$value; }
    function defaultMapsKey() { return ""; }

    private $_address="";

    /**
     * Address to be shown at the center of the map. For example: Scotts Valley, CA.
     *
     * @return string
     */
    function getAddress() { return $this->_address; }
    function setAddress($value) { $this->_address=$value; }
    function defaultAddress() { return ""; }

    private $_zoom=8;

    /**
     * Zoom level, between the maximum and minimum zoom levels.
     *
     * Check the Google Maps API for information on what the maximum and minimum zoom levels are.
     *
     * @link https://developers.google.com/maps/documentation/javascript/reference
     *
     * @return int
     */
    function getZoom() { return $this->_zoom; }
    function setZoom($value) { $this->_zoom=$value; }
    function defaultZoom() { return 8; }

    private $_maptype='gmRoadmap';
    /**
     * Type of map. Supported types are: roadmap (gmRoadmap), satellite (gmSatellite), hybrid (gmHybrid) and terrain
     * (gmTerrain).
     *
     * @return string
     */
    function getMapType() { return $this->_maptype; }
    function setMapType($value) { $this->_maptype=$value; }
    function defaultMapType() { return 'gmRoadmap'; }

    private $_showcontrols=1;
    /**
     * Whether to show the map controls (true) or not (false).
     *
     * @return int
     */
    function getShowControls() { return $this->_showcontrols; }
    function setShowControls($value) { $this->_showcontrols=$value; }
    function defaultShowControls() { return 1; }


    // JAVASCRIPT EVENTS

    protected $_jsonload=null;
    /**
     * Triggered after the map has been initialized.
     */
    function getjsOnLoad() { return $this->_jsonload; }
    function setjsOnLoad($value) { $this->_jsonload=$value; }
    function defaultjsOnLoad() {return null;}

    protected $_jsboundschanged=null;
    /**
     * Triggered each time the map viewport bounds change.
     */
    function getjsOnBoundsChanged() { return $this->_jsboundschanged; }
    function setjsOnBoundsChanged($value) { $this->_jsboundschanged=$value; }
    function defaultjsOnBoundsChanged() {return null;}

    protected $_jsoncenterchanged=null;
    /**
     * Triggered when the map center property changes.
     */
    function getjsOnCenterChanged() { return $this->_jsoncenterchanged; }
    function setjsOnCenterChanged($value) { $this->_jsoncenterchanged=$value; }
    function defaultjsOnCenterChanged() {return null;}

    // Documented in the parent.
    function getjsOnClick() { return $this->_jsonclick; }
    function setjsOnClick($value) { $this->_jsonclick=$value; }

    // Documented in the parent.
    function getjsOnDblClick() { return $this->_jsondblclick; }
    function setjsOnDblClick($value) { $this->_jsondblclick=$value; }

    // Documented in the parent.
    function getjsOnDrag() { return $this->_jsondrag; }
    function setjsOnDrag($value) { $this->_jsondrag=$value; }

    // Documented in the parent.
    function getjsOnDragEnd() { return $this->_jsondragend; }
    function setjsOnDragEnd($value) { $this->_jsondragend=$value; }

    // Documented in the parent.
    function getjsOnDragStart() { return $this->_jsondragstart; }
    function setjsOnDragStart($value) { $this->_jsondragstart=$value; }

    protected $_jsonheadingchanged=null;
    /**
     * Triggered when the map heading property changes.
     */
    function getjsOnHeadingChanged() { return $this->_jsonheadingchanged; }
    function setjsOnHeadingChanged($value) { $this->_jsonheadingchanged=$value; }
    function defaultjsOnHeadingChanged() {return null;}

    protected $_jsonidle=null;
    /**
     * Triggered when the map becomes idle (e.g. after panning or zooming).
     */
    function getjsOnIdle() { return $this->_jsonidle; }
    function setjsOnIdle($value) { $this->_jsonidle=$value; }
    function defaultjsOnIdle() {return null;}

    protected $_jsonmaptypechanged=null;
    /**
     * Triggered when the mapTypeId property changes.
     */
    function getjsOnMapTypeChanged() { return $this->_jsonmaptypechanged; }
    function setjsOnMapTypeChanged($value) { $this->_jsonmaptypechanged=$value; }
    function defaultjsOnMapTypeChanged() {return null;}

    protected $_jsonprojectionchanged=null;
    /**
     * Triggered when the map projection changes.
     */
    function getjsOnProjectionChanged() { return $this->_jsonprojectionchanged; }
    function setjsOnProjectionChanged($value) { $this->_jsonprojectionchanged=$value; }
    function defaultjsOnProjectionChanged() {return null;}

    protected $_jsonresize=null;
    function getjsOnResize() { return $this->_jsonresize; }
    function setjsOnResize($value) { $this->_jsonresize=$value; }
    function defaultjsOnResize() {return null;}

    protected $_jsonzoomchanged=null;
    /**
     * Triggered when the map zoom property changes.
     */
    function getjsOnZoomChanged() { return $this->_jsonzoomchanged; }
    function setjsOnZoomChanged($value) { $this->_jsonzoomchanged=$value; }
    function defaultjsOnZoomChanged() {return null;}

    protected $_jsonrightclick=null;
    /**
     * Triggered when the DOM 'contextmenu' event gets triggered on the map container.
     */
    function getjsOnRightClick() { return $this->_jsonrightclick; }
    function setjsOnRightClick($value) { $this->_jsonrightclick=$value; }
    function defaultjsOnRightClick() {return null;}

    // Documented in the parent.
    function getjsOnMouseOver() { return $this->_jsonmouseover; }
    function setjsOnMouseOver($value) { $this->_jsonmouseover=$value; }

    // Documented in the parent.
    function getjsOnMouseOut() { return $this->_jsonmouseout; }
    function setjsOnMouseOut($value) { $this->_jsonmouseout=$value; }

    // Documented in the parent.
    function getjsOnMouseMove() { return $this->_jsonmousemove; }
    function setjsOnMouseMove($value) { $this->_jsonmousemove=$value; }

    // Documented in the parent.
   function dumpJsEvents()
   {
      parent::dumpJsEvents();

      $this->dumpJSEvent($this->_jsonload);
      $this->dumpJSEvent($this->_jsboundschanged);
      $this->dumpJSEvent($this->_jsoncenterchanged);
      $this->dumpJSEvent($this->_jsonheadingchanged);
      $this->dumpJSEvent($this->_jsonidle);
      $this->dumpJSEvent($this->_jsonmaptypechanged);
      $this->dumpJSEvent($this->_jsonprojectionchanged);
      $this->dumpJSEvent($this->_jsonresize);
      $this->dumpJSEvent($this->_jsonzoomchanged);
      $this->dumpJSEvent($this->_jsonrightclick);
   }

    /**
     * Loads the Google Maps API with the provided key (if any).
     *
     * @internal
     */
    function dumpHeaderCode()
    {
        if(!defined('XGOOGLEMAPSAPI'))
        {
        ?>
        <script src="https://maps.googleapis.com/maps/api/js?sensor=false<?php echo ($this->_mapskey != "")? "&key=".$this->_mapskey : "" ?>" type="text/javascript"></script>
        <?php
            define('XGOOGLEMAPSAPI', 1);
        }
    }

    // Documented in the parent.
    function bindJSEvent($event, $alternatename = null)
    {
        $jsonevent = 'json'.$event;
        $realname = (!$alternatename) ? $event : $alternatename;
        if($this->{$jsonevent})
            return "\t google.maps.event.addListener({$this->Name}_map,'".$realname."', {$this->$jsonevent});\n";

        return '';
    }

    // Documented in the parent.
    function pagecreate()
    {
        $output = parent::pagecreate();
        $output .= $this->initializeGoogleMaps();
        $output .= $this->bindJSEvent('boundschanged', 'bounds_changed');//
        $output .= $this->bindJSEvent('centerchanged', 'center_changed');//
        $output .= $this->bindJSEvent('click');
        $output .= $this->bindJSEvent('dblclick');

        $output .= $this->bindJSEvent('drag');
        $output .= $this->bindJSEvent('dragend');
        $output .= $this->bindJSEvent('dragstart');

        $output .= $this->bindJSEvent('headingchanged', 'heading_changed');//
        $output .= $this->bindJSEvent('idle');//
        $output .= $this->bindJSEvent('maptypechanged', 'maptypeid_changed');//
        $output .= $this->bindJSEvent('mousemove');
        $output .= $this->bindJSEvent('mouseout');
        $output .= $this->bindJSEvent('mouseover');
        $output .= $this->bindJSEvent('projectionchanged', 'projection_changed');//
        $output .= $this->bindJSEvent('resize');//
        $output .= $this->bindJSEvent('rightclick');//
        //$output .= $this->bindJSEvent('tilesloaded');//
        //$output .= $this->bindJSEvent('tilt_changed', 'tilt_changed');//
        $output .= $this->bindJSEvent('zoomchanged', 'zoom_changed');//

        return $output;
    }

    /**
     * Returns the component settings in JSON, to be used to initialize the JavaScript object.
     *
     * @see initializeGoogleMaps()
     *
     * @return string
     *
     * @internal
     */
    protected function parseMapOptions()
    {
        $options = array();
        $options['zoom'] = (int) $this->_zoom;
        $options['address'] = $this->_address;
        $options['disableDefaultUI'] = (bool) !$this->_showcontrols;
        $options['maptype'] = $this->parseMapType();
        return json_encode($options);
    }

    /**
     * Returns the MapType property formatted to be passed to the Google Maps JavaScript object.
     *
     * @see parseMapOptions()
     *
     * @return string
     *
     * @internal
     */
    protected function parseMapType()
    {
        switch ($this->_maptype) {

            case 'gmHybrid':
                $type = 'HYBRID';
                break;

            case 'gmTerrain':
                $type = 'TERRAIN';
                break;

            case 'gmSatellite':
                $type = 'SATELLITE';
                break;

            case 'gmRoadmap':
            default:
                $type = 'ROADMAP';
                break;
        }

        return "google.maps.MapTypeId.".$type;
    }

    /**
     * Returns a string with the JavaScript code to initialize the Google Maps JavaScript object.
     *
     * @return string
     *
     * @internal
     */
    protected function initializeGoogleMaps()
    {
        $id      = $this->Name;
        $options = $this->parseMapOptions();
        $jsload  = ($this->jsonload) ? $this->jsonload : 'null';

        return "\t{$this->Name}_map = initializeGoogleMaps('$id', $options,  $jsload);\n";
    }

    // Documented in the parent.
    function dumpCSS()
    {
        parent::dumpCSS();
        echo "height: {$this->_height}px;\n";
        echo "width: {$this->_width}px;\n";
    }

    /**
     * Prints a JavaScript function to initialize the Google Maps JavaScript object defined by the component property
     * values.
     *
     * When called, the JavaScript function returns the JavaScript object.
     *
     * @see initializeGoogleMaps()
     *
     * @internal
     */
    function dumpJavascript()
    {
        parent::dumpJavascript();

        // dumps javascript instance
        echo "var {$this->Name}_map;\n";

        if(!defined('XGOOGLEMAPSJAVASCRIPT'))
        {
        ?>
function initializeGoogleMaps(id, options, callback)
{
    var mapOptions = {
      zoom: options.zoom,
      center: new google.maps.LatLng(0, 0),
      disableDefaultUI: options.disableDefaultUI,
      mapTypeId: eval(options.maptype)
    };

    // load the map
    map = new google.maps.Map(document.getElementById(id),
        mapOptions);

    if(options.address && options.address != "")
    {
        var geocoder = new google.maps.Geocoder(),
            geocoderRequest = {address: options.address};

        geocoder.geocode(geocoderRequest, function(geocoderResult, geocoderStatus){
            if (geocoderStatus == google.maps.GeocoderStatus.OK)
            {
                map.setCenter(geocoderResult[0].geometry.location);

                marker = new google.maps.Marker({
                  map: map
                });
                marker.setPosition(geocoderResult[0].geometry.location);
            }
        });
    }

    // calls OnLoad event if exists
    if(callback) setTimeout(callback, 0);

    return map;
}

        <?php
            define('XGOOGLEMAPSJAVASCRIPT', 1);
        }
        ?>



        <?php
    }

    // Documented in the parent.
    function dumpContents()
    {
        echo "<div id=\"".$this->Name."\"></div>";

        if(($this->ControlState & csDesigning) == csDesigning)
        {
            echo "<script>";
            $this->dumpJavascript();
            echo $this->initializeGoogleMaps();
            echo "</script>";
        }

        $this->callEvent('onshow', array());
    }
}

?>
