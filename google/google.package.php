<?php
  require_once("rpcl/rpcl.inc.php");
  use_unit("designide.inc.php");

    setPackageTitle("Google Components");
  //Change this setting to the path where the icons for the components reside
  setIconPath("./icons");
  
  addSplashBitmap("Google Components", "google.png");
  registerPropertyValues("GoogleMap","MapType",array('gmRoadmap', 'gmHybrid','gmTerrain', 'gmSatellite'));
  registerBooleanProperty("GoogleMap", "ShowControls");   

  //Change yourunit.inc.php to the php file which contains the component code
  registerComponents("Google",array("GoogleMap"),"google/maps/gmaps.inc.php");
?>
