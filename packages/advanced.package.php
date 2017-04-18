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
	use_unit("designide.inc.php");

	setPackageTitle("Advanced RPCL Components");
	setIconPath("./icons");

  addSplashBitmap("RPCL 5.0 from Embarcadero", "h5b.png");

	registerComponents("Advanced",array("ImageList"),"imglist.inc.php");
	registerComponents("Advanced",array("TrackBar", "ProgressBar", "DateTimePicker", "ColorPicker"),"comctrls.inc.php");

  registerComponents("Additional", array("Geolocation", "Media", "Animation"), "html5.inc.php");

  registerComponents("Additional",array("Pager"),"pager.inc.php");

	//Folders required by components using this package
	registerPropertyValues("ProgressBar","Type",array('pbsProgressBar','pbsMeterBar'));

	registerPropertyValues("ProgressBar","Orientation",array('pbHorizontal','pbVertical'));

	registerPropertyValues("TrackBar","Orientation",array('trHorizontal','trVertical'));

	registerPropertyValues("CustomColor","Autocomplete",array('ceDisabled', 'ceOn','ceOff'));
    registerPropertyEditor("CustomColor", "Value", "TSamplePropertyEditor", "native");

	registerPropertyValues("Pager","CSSFile",array(	'badoo.css','blue.css','digg.css','flickr.css','gray.css','gray2.css',
													'green-black.css','green.css','jogger.css','meneame.css','msdn.css',
													'sabrosus.css','technorati.css','viciao2k3.css','yahoo.css','yahoo2.css',
													'yellow.css','youtube.css'));


	registerPropertyValues("DateTimePicker","Kind",array('dtkDate','dtkTime', 'dtkDateTime', 'dtkMonth', 'dtkWeek', 'dtkDateTime-Local'));

	registerDropDatafield(array("SpinEdit"));

  //Component Geolocation
  registerBooleanProperty("CustomGeolocation", "Enabled");
  registerBooleanProperty("CustomGeolocation", "HighAccuracy");
  registerBooleanProperty("CustomGeolocation", "Repeat");
    registerBooleanProperty("TrackBar", "Draggable");

  //Component CustomMedia
  registerBooleanProperty("CustomMedia", "Enabled");
  registerBooleanProperty("CustomMedia", "AutoPlay");
  registerBooleanProperty("CustomMedia", "ShowControls");
  registerBooleanProperty("CustomMedia", "Loop");
  registerBooleanProperty("CustomMedia", "Hidden");
  registerBooleanProperty("CustomMedia", "Muted");
  registerPropertyValues("CustomMedia", "MediaType", array("mtVideo", "mtAudio"));
  registerPropertyValues("CustomMedia", "Preload", array("pNone", "pMetadata", "pAuto"));

  registerPropertyEditor("CustomMedia","LoadingImage","TFilenamePropertyEditor","native");
  registerPropertyEditor("CustomMedia","Sources","THTML5CustomMediaPropertyEditors","native");

  //Animation
  registerPropertyEditor("CustomAnimation","Items","THTML5CustomMediaPropertyEditors","native");
