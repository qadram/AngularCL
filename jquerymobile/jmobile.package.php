<?php
require_once("rpcl/rpcl.inc.php");
use_unit("designide.inc.php");

setPackageTitle("jQuery Mobile Components");
//Change this setting to the path where the icons for the components reside
setIconPath("./icons");

addSplashBitmap("jQuery Mobile Components", "jqm.png");

registerNoVisibleComponents(array("MPage"),"jquerymobile/forms.inc.php");
registerPropertyValues("CustomMPage", "Theme", array("MobileTheme"));
registerPropertyValues("CustomMPage", "DefaultTransition", array('trSlide', 'trSlideUp', 'trSlideDown', 'trPop', 'trFade', 'trFlip', 'trTurn','trFlow', 'trSlideFade'));
registerBooleanProperty("CustomMPage", "ShowLoadingMessage");
registerBooleanProperty("CustomMPage", "AutoInitialize");
registerBooleanProperty("CustomMPage", "TouchOverflowEnabled");
registerPropertyValues("CustomMPage", "CssFile", array('cssBasic', 'cssCustom'));
registerPropertyEditor("CustomMPage", "CustomCssFile", "TFilenamePropertyEditor", "native");


registerPropertyValues("MSlider","Theme",array("MobileTheme"));
registerPropertyValues("MSlider","TrackTheme",array("MobileTheme"));
registerPropertyValues("MSlider", "Enhancement",array('enFull','enStructure','enNone'));
registerBooleanProperty("MSlider", "Highlight");


registerPropertyValues("MPanel", "Theme", array("MobileTheme"));

registerPropertyValues("MEdit", "Theme", array("MobileTheme"));
registerBooleanProperty("MEdit", "IsSearch");
registerPropertyValues("MEdit", "Enhancement",array('enFull','enStructure','enNone'));
registerPropertyValues("MEdit", "InputType", array('ceText', 'cePassword', 'ceEmail', 'ceTelephone', 'ceSearch', 'ceURL'));

registerPropertyValues("MTextArea", "Theme", array("MobileTheme"));
registerPropertyValues("MTextArea", "Enhancement",array('enFull','enStructure','enNone'));

registerPropertyValues("MCollapsible", "Theme", array("MobileTheme"));
registerBooleanProperty("MCollapsible", "IsCollapsed");
registerPropertyValues("MCollapsible", "Enhancement",array('enFull','enStructure'));
registerPropertyValues("MCollapsible", "IconPos", array('ipTop', 'ipBottom', 'ipLeft', 'ipRight'));



registerPropertyValues("MButton", "Theme", array("MobileTheme"));
registerPropertyValues("MButton", "SystemIcon", array('siArrowL', 'siArrowR', 'siArrowU', 'siArrowD', 'siDelete', 'siPlus', 'siMinus', 'siCheck', 'siGear', 'siRefresh', 'siForward', 'siBack', 'siGrid', 'siStar', 'siAlert', 'siInfo', 'siSearch', 'siHome'));
registerPropertyValues("MButton", "IconPos", array('ipTop', 'ipBottom', 'ipLeft', 'ipRight'));
registerPropertyEditor("MButton", "Icon", "TImagePropertyEditor", "native");
registerPropertyValues("MButton", "Enhancement",array('enFull','enStructure','enNone'));
registerBooleanProperty("MButton", "RoundedCorners");
registerBooleanProperty("MButton", "IconShadow");
registerBooleanProperty("MButton", "Shadow");
registerBooleanProperty("MButton", "Inline");

registerPropertyEditor("MobileTheme", "CustomTheme", "TFilenamePropertyEditor", "native");
registerPropertyValues("MobileTheme", "Theme", array("thBasic", "thCustom"));
registerPropertyValues("MobileTheme", "ColorVariation", array("cvHigh", "cvMedium", "cvMedium2", "cvBasic", "cvAccent","cvCustom"));

registerPropertyValues("MLink", "Theme", array("MobileTheme"));
registerPropertyValues("MLink", "SystemIcon", array('siArrowL', 'siArrowR', 'siArrowU', 'siArrowD', 'siDelete', 'siPlus', 'siMinus', 'siCheck', 'siGear', 'siRefresh', 'siForward', 'siBack', 'siGrid', 'siStar', 'siAlert', 'siInfo', 'siSearch', 'siHome'));
registerPropertyValues("MLink", "IconPos", array('ipTop', 'ipBottom', 'ipLeft', 'ipRight'));
registerPropertyEditor("MLink", "Icon", "TImagePropertyEditor", "native");
registerPropertyValues("MLink", "Transition", array('trSlide', 'trSlideUp', 'trSlideDown', 'trPop', 'trFade', 'trFlip', 'trTurn','trFlow', 'trSlideFade'));
registerBooleanProperty("MLink", "NoAjax");
registerBooleanProperty("MLink", "OpenDialog");
registerBooleanProperty("MLink", "IsBackButton");
registerPropertyValues("MLink", "Enhancement",array('enFull','enStructure'));
registerBooleanProperty("MLink","TransitionReverse");
registerBooleanProperty("MLink", "Inline");

registerPropertyValues("MControl", "Theme", array("MobileTheme"));
registerPropertyValues("MControl", "Enhancement",array('enFull','enStructure'));

registerPropertyEditor("MToolBar", "Items", "TMToolBarPropertyEditor","native");
registerPropertyValues("MToolBar", "IconPos", array('ipTop', 'ipBottom', 'ipLeft', 'ipRight'));

registerPropertyValues("MToggle", "Theme", array("MobileTheme"));
registerPropertyValues("MToggle", "TrackTheme", array("MobileTheme"));
registerPropertyValues("MToggle", "Enhancement",array('enFull','enStructure','enNone'));

registerPropertyValues("MRadioButton", "Theme", array("MobileTheme"));
registerPropertyValues("MRadioButton", "Enhancement",array('enFull','enStructure','enNone'));

registerPropertyValues("MCheckBox", "Theme", array("MobileTheme"));
registerPropertyValues("MCheckBox", "Enhancement",array('enFull','enStructure','enNone'));


registerPropertyValues("MComboBox", "Theme", array("MobileTheme"));
registerPropertyValues("MComboBox", "SystemIcon", array('siArrowL', 'siArrowR', 'siArrowU', 'siArrowD', 'siDelete', 'siPlus', 'siMinus', 'siCheck', 'siGear', 'siRefresh', 'siForward', 'siBack', 'siGrid', 'siStar', 'siAlert', 'siInfo', 'siSearch', 'siHome'));
registerPropertyValues("MComboBox", "IconPos", array('ipTop', 'ipBottom', 'ipLeft', 'ipRight'));
registerPropertyEditor("MComboBox", "Icon", "TImagePropertyEditor", "native");
registerPropertyEditor("MComboBox", "Items", "TMComboBoxPropertyEditor", "native");
registerBooleanProperty("MComboBox", "IsNative");
registerPropertyValues("MComboBox", "Enhancement",array('enFull','enStructure','enNone'));
registerBooleanProperty("MComboBox", "RoundedCorners");
registerBooleanProperty("MComboBox", "IconShadow");
registerBooleanProperty("MComboBox", "Shadow");
registerBooleanProperty("MComboBox", "Inline");

registerPropertyValues("MCollapsibleSet", "Theme", array("MobileTheme"));
registerPropertyEditor("MCollapsibleSet", "Panels", "TStringListPropertyEditor", "native");
registerPropertyValues("MCollapsibleSet", "Enhancement",array('enFull','enStructure'));
registerPropertyValues("MCollapsibleSet", "IconPos", array('ipTop', 'ipBottom', 'ipLeft', 'ipRight'));


registerPropertyValues("MList", "DividerTheme", array("MobileTheme"));
registerPropertyValues("MList", "ExtraButtonTheme", array("MobileTheme"));
registerPropertyValues("MList", "CounterTheme", array("MobileTheme"));
registerPropertyValues("MList", "SystemIcon", array('siArrowL', 'siArrowR', 'siArrowU', 'siArrowD', 'siDelete', 'siPlus', 'siMinus', 'siCheck', 'siGear', 'siRefresh', 'siForward', 'siBack', 'siGrid', 'siStar', 'siAlert', 'siInfo', 'siSearch', 'siHome'));
registerPropertyEditor("MList", "Icon", "TImagePropertyEditor", "native");
registerPropertyValues("MList", "Type", array('tUnordered', 'tOrdered'));
registerBooleanProperty("MList", "IsFiltered");
registerBooleanProperty("MList", "IsWrapped");
registerPropertyEditor("MList", "Items", "TMListPropertyEditor", "native");


registerPropertyValues("CustomMInputGroup", "Theme", array("MobileTheme"));
registerPropertyValues("CustomMInputGroup", "Orientation", array('orVertical', 'orHorizontal'));
registerPropertyValues("CustomMInputGroup", "Enhancement",array('enFull','enStructure','enNone'));

registerPropertyValues("MCheckBoxGroup", "Theme", array("MobileTheme"));
registerPropertyValues("MCheckBoxGroup", "Orientation", array('orVertical', 'orHorizontal'));
registerPropertyValues("MCheckBoxGroup", "Enhancement",array('enFull','enStructure','enNone'));

registerPropertyValues("MIFrame", "Scrolling", array('fsAuto', 'fsYes','fsNo'));
registerBooleanProperty("MIFrame", "Borders");

registerBooleanProperty("MMap", "Border");
registerBooleanProperty("MMap", "Draggable");
registerBooleanProperty("MMap", "ShowControls");

registerPropertyValues("MDateTimePicker","Theme",array("MobileTheme"));
registerPropertyValues("MDateTimePicker", "Enhancement",array('enFull','enStructure','enNone'));
registerPropertyValues("MDateTimePicker", "Options.Mode", array('modCalBox', 'modDateBox', 'modTimeBox', 'modFlipBox', 'modTimeFlipBox', 'modSlideBox', 'modDurationBox'));
registerBooleanProperty("MDateTimePicker","Options.ShowInline");
registerBooleanProperty("MDateTimePicker", "Options.LockInput");
registerBooleanProperty("MDateTimePicker", "Options.EnhanceInput");
registerBooleanProperty("MDateTimePicker", "Options.CenterHoriz");
registerBooleanProperty("MDateTimePicker", "Options.CenterVert");
registerPropertyValues("MDateTimePicker", "Options.Transition", array('trSlide', 'trSlideUp', 'trSlideDown', 'trPop', 'trFade', 'trFlip', 'trTurn','trFlow', 'trSlideFade'));
registerBooleanProperty("MDateTimePicker", "Options.UseAnimation");
registerBooleanProperty("MDateTimePicker", "Options.ShowInline");
registerBooleanProperty("MDateTimePicker", "Options.UseModal");
registerBooleanProperty("MDateTimePicker", "Options.UseButton");
registerBooleanProperty("MDateTimePicker", "Options.UseFocus");
registerBooleanProperty("MDateTimePicker", "Options.UseHeader");
registerBooleanProperty("MDateTimePicker", "Options.UseClearButton");
registerBooleanProperty("MDateTimePicker", "Options.UseSetButton");
registerBooleanProperty("MDateTimePicker", "Options.UseTodayButton");
registerBooleanProperty("MDateTimePicker", "Options.UseCollapsedBut");
registerBooleanProperty("MDateTimePicker", "Options.CalShowDays");
registerBooleanProperty("MDateTimePicker", "Options.CalShowWeek");
registerBooleanProperty("MDateTimePicker", "Options.CalOnlyMonth");
registerBooleanProperty("MDateTimePicker", "Options.CalWeekMode");
registerBooleanProperty("MDateTimePicker", "DateTimeOptions.AfterToday");
registerBooleanProperty("MDateTimePicker", "DateTimeOptions.BeforeToday");
registerBooleanProperty("MDateTimePicker", "DateTimeOptions.NotToday");
registerBooleanProperty("MDateTimePicker", "LocalizationOptions.OverrideLocalization");
registerBooleanProperty("MDateTimePicker", "LocalizationOptions.UseArabicIndic");
registerBooleanProperty("MDateTimePicker", "LocalizationOptions.IsRTL");
registerPropertyValues("MDateTimePicker", "LocalizationOptions.TimeFormat", array("24", "12"));
registerPropertyValues("MDateTimePicker", "LocalizationOptions.MinuteStepRound", array("msUp", "msDown", "msStandard"));

registerPropertyValues("MLabel", "Theme", array("MobileTheme"));


registerBooleanProperty("MAccelerometer", "Active");

registerBooleanProperty("MCompass", "Active");

registerBooleanProperty("MGeolocation", "Active");
registerBooleanProperty("MGeolocation", "HighAccuracy");

registerBooleanProperty("MCamera", "AllowEdit");
registerPropertyValues("MCamera", "DestinationType", array('dtDataUrl', 'dtFileUri'));
registerPropertyValues("MCamera", "SourceType", array('stPhotoLibrary', 'stCamera','stSavedPhotoAlbum'));

registerBooleanProperty("MContacts", "Multiple");
registerPropertyEditor("MContacts", "Fields", "TStringListPropertyEditor", "native");

registerPropertyValues("MDBTransaction", "DB", array("MDB"));

registerPropertyValues("MFileSystem", "Type", array('fsPersistent', 'fsTemporary'));

registerPropertyEditor("MFileTransfer", "ExtraParameters", "TValueListPropertyEditor", "native");

registerPropertyValues("MCapture", "Type", array('mcAudio', 'mcVideo','mcImage'));

registerPropertyValues("MNotification", "Type", array('ntAlert','ntConfirm'));
registerPropertyEditor("MNotification", "ButtonLabels", "TStringListPropertyEditor", "native");

registerPropertyEditor("MConnection", "ConnectionTypes", "TValueListPropertyEditor", "native");

registerPropertyValues("MDevice", "Display", array('dsName','dsPhonegap','dsPlatform','dsUuid','dsVersion'));

//Change yourunit.inc.php to the php file which contains the component code

registerComponents("Mobile", array("MIFrame", "MButton", "MPanel", "MCheckBoxGroup", "MRadioGroup", "MobileTheme", "MEdit", "MTextArea", "MSlider", "MLink", "MCollapsible", "MToolBar", "MToggle", "MRadioButton", "MCheckBox", "MComboBox", "MCollapsibleSet", "MList","MMap","MDateTimePicker", "MCanvas", "MShape", "MImage", "MLabel", "MMedia"), "jquerymobile/jmobile.inc.php", 'MPage');
registerComponents("Mobile Hardware", array("MAccelerometer","MCamera","MCompass","MGeolocation","MContacts","MPageEvents","MPageExtraEvents","MDB","MDBTransaction","MFileReader","MFileWriter","MFileEntry","MDirectoryEntry","MDirectoryReader","MFileSystem","MFileTransfer","MCapture","MNotification","MConnection","MDevice"), "jquerymobile/phonegap.inc.php",'MPage');
registerAsset(array("MButton", "MPanel", "MCheckBoxGroup", "MRadioGroup", "MobileTheme", "MEdit", "MTextArea", "MSlider", "MLink", "MCollapsible", "MToolBar", "MToggle", "MRadioButton", "MCheckBox", "MComboBox", "MCollapsibleSet", "MList"), array("jquery", "jquerymobile"));

?>