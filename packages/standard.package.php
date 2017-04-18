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
use_unit("designide.inc.php");//IDE functions
use_unit("templateplugins.inc.php");//Plugin functions

//Set title for this package and the path where to find the icons for the components
setPackageTitle("Standard RPCL Components");
setIconPath("./icons");

addSplashBitmap("RPCL 5.0 from Embarcadero", "h5b.png");
//Pages and DataModules are special components and must be registered that way
registerNoVisibleComponents(array("Page"), "forms.inc.php");
registerNoVisibleComponents(array("DataModule"), "forms.inc.php");

RegisterPropertiesInCategory('Visual', array('Background', 'Color', 'Cursor', 'Left', 'Font', 'Top', 'Width', 'Height', 'Visible',
                                             'Enabled', 'Caption', 'Align', 'Alignment', 'ParentColor', 'ParentFont', 'Bevel*',
                                             'Border*', 'ClientHeight', 'ClientWidth', 'Scaled', 'AutoSize', 'EditMask', 'OnShow',
                                             'OnPaint', 'OnClose', 'OnCloseQuery', 'OnResize', 'OnConstrained', 'OnActivate',
                                             'OnDeactivate', 'OnCanResize', 'OnHide'));

RegisterPropertiesInCategory('Localizable', array('Language', 'Directionality', 'Encoding', 'BiDiMode', 'Caption', 'Constraints',
                                                  'EditMask', 'Glyph', 'Height', 'Hint', 'Icon', 'ImeMode', 'ImeName', 'Left',
                                                  'ParentBiDiMode', 'ParentFont', 'Picture', 'Text', 'Top', 'Width'));
RegisterPropertiesInCategory('Legacy', array('Ctl3d', 'ParentCtl3d', 'OldCreateOrder'));
RegisterPropertiesInCategory('Layout', array('TopMargin', 'ShowHeader', 'ShowFooter', 'RightMargin', 'LeftMargin', 'Layer', 'Layout', 'FrameBorder', 'FrameSpacing', 'BottomMargin', 'Left', 'Top', 'Width', 'Height', 'TabOrder', 'TabStop', 'Align', 'Anchors', 'Constraints', 'AutoSize', 'AutoScroll', 'Scaled',
                                             'OnResize', 'OnConstrained', 'OnCanResize'));

RegisterPropertiesInCategory('Input', array('AutoScroll', 'KeyPreview', 'ReadOnly',
                                            'Enabled', 'OnClick', 'OnDblClick', 'OnShortCut', 'OnKey*', 'OnMouse*'));
RegisterPropertiesInCategory('Help and Hints', array('Help*', '*Help', 'Hint*', '*Hint'));
RegisterPropertiesInCategory('Drag, Drop and Docking', array('Drag*', 'Dock*', 'UseDockManager', 'OnDockOver', 'OnGetSiteInfo', 'OnDragOver', 'On*Drop', 'On*Drag', 'On*Dock'));
RegisterPropertiesInCategory('Action', array('IsMaster', 'IsForm', 'Action', 'Caption', 'Checked', 'Enabled', 'HelpContext', 'Hint', 'ImageIndex', 'ShortCut', 'Visible'));


//Standard Palette
registerComponents("Standard", array("Label", "Edit", "SpinEdit", "Memo", "Button", "CheckBox", "RadioButton", "ListBox", "ComboBox", "DataList"), "stdctrls.inc.php");

registerComponents("Standard", array("RadioGroup", "Panel"), "extctrls.inc.php");
registerComponents("Standard", array("ActionList"), "actnlist.inc.php");

//Folders required by components using this package
registerAsset(array("Page"), array("js", "xajax", "language", "smarty","jquery"));


//Additional Palette
registerComponents("Additional", array("Upload"), "stdctrls.inc.php");
registerComponents("Additional", array("Image", "Canvas", "MapShape", "FlashObject", "Shape", "Bevel"), "extctrls.inc.php");
registerComponents("Additional", array("CheckListBox"), "checklst.inc.php");

//Property editors
registerPropertyEditor("Control", "BorderRadius.Color", "TSamplePropertyEditor", "native");
registerPropertyEditor("Control", "Color", "TSamplePropertyEditor", "native");
registerPropertyEditor("Control", "DesignColor", "TSamplePropertyEditor", "native");
registerPropertyEditor("Control", "Font.Color", "TSamplePropertyEditor", "native");
registerPropertyEditor("CustomMemo", "Lines", "TStringListPropertyEditor", "native");
registerPropertyEditor("Button", "ImageSource", "TImagePropertyEditor", "native");
registerPropertyEditor("CustomPage", "Icon", "TImagePropertyEditor", "native");
registerPropertyEditor("CustomPage", "HiddenFields", "TValueListPropertyEditor", "native");
registerPropertyEditor("CustomComboBox", "Items", "TValueListPropertyEditor", "native");
registerPropertyEditor("CustomCheckListBox", "Checked", "TValueListPropertyEditor", "native");
registerPropertyEditor("CustomCheckListBox", "Header", "TValueListPropertyEditor", "native");
registerPropertyEditor("CustomPanel", "Include", "TFilenamePropertyEditor", "native");
registerPropertyEditor("FlashObject", "SwfFile", "TFilenamePropertyEditor", "native");
registerPropertyValues("ImageFade", "Images", array('ImageList'));
registerPropertyEditor("CustomListBox", "Items", "TStringListPropertyEditor", "native");
registerPropertyEditor("CustomRadioGroup", "Items", "TStringListPropertyEditor", "native");
registerPropertyEditor("ActionList", "Actions", "TStringListPropertyEditor", "native");

//Property Values for the drop-down property editor
registerPropertyValues("CustomPage", "FrameBorder", array('fbDefault', 'fbNo', 'fbYes'));

registerPropertyValues("CustomPage", "Directionality", array('ddLeftToRight', 'ddRightToLeft'));

registerPropertyValues('CustomPage', 'Cache', array('Cache'));

registerPropertyValues('CustomPage', 'ActiveControl', array('FocusControl'));

registerBooleanProperty('Control', 'Cached');

registerPropertyValues("Control", "DataSource", array('Datasource'));
registerPropertyValues("Control", "Style", array('StyleSheet::Styles'));
registerPropertyValues("FocusControl", "Layout.Type", array(
     'ABS_XY_LAYOUT', 
     'REL_XY_LAYOUT', 
    // 'XY_LAYOUT', 
     'FLOW_LAYOUT', 
    // 'GRIDBAG_LAYOUT', 
    // 'ROW_LAYOUT', 
    // 'COL_LAYOUT'
     ));
registerPropertyValues("Chart", "ChartType", array('ctHorizontalChart', 'ctLineChart', 'ctPieChart', 'ctVerticalChart'));
registerPropertyValues("Control", "Align", array('alNone', 'alTop', 'alBottom', 'alLeft', 'alRight', 'alClient', 'alCustom'));
registerPropertyValues("Control", "Font.Align", array('taNone', 'taLeft', 'taRight', 'taCenter', 'taJustify'));
registerPropertyValues("Control", "Font.Case", array('caCapitalize', 'caUpperCase', 'caLowerCase', 'caNone'));
registerPropertyValues("Control", "Font.Style", array('fsNormal', 'fsItalic', 'fsOblique'));
registerPropertyValues("Control", "Font.Variant", array('vaNormal', 'vaSmallCaps'));
registerPropertyValues("Control", "Font.Weight", array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900'));
//CSS3 Properties
registerPropertyValues("Control", "BorderRadius.Style", array('brsDisabled', 'brsDashed', 'brsDotted', 'brsDouble', 'brsGroove', 'brsHidden', 'brsInset', 'brsNone', 'brsOutset', 'brsRidge', 'brsSolid', 'brsInherit'));
registerPropertyValues("Control", "Gradient.Style", array('gsDisabled', 'gsLinear', 'gsRadial'));
registerPropertyEditor("Control", "Gradient.StartColor", "TSamplePropertyEditor", "native");
registerPropertyEditor("Control", "Gradient.EndColor", "TSamplePropertyEditor", "native");
registerPropertyValues("Control", "Transform.Style", array('tsDisabled', 'tsAll', 'tsRotate', 'tsScale', 'tsSkew', 'tsTranslate'));
registerPropertyValues("Control", "TextShadow.Style", array('tssDisabled', 'tssEnabled'));
registerPropertyEditor("Control", "TextShadow.Color", "TSamplePropertyEditor", "native");
registerPropertyValues("Control", "BoxShadow.Style", array('bssDisabled', 'bssEnabled'));
registerPropertyValues("Control", "BoxShadow.Inset", array('bsiNo', 'bsiYes'));
registerPropertyEditor("Control", "BoxShadow.Color", "TSamplePropertyEditor", "native");
registerPropertyValues("Panel", "BackgroundPosition", array('bpLeftTop', 'bpLeftCenter', 'bpLeftBottom', 'bpRightTop', 'bpRightCenter','bpRightBottom','bpCenterTop','bpCenterCenter','bpCenterBottom'));


registerPropertyValues("ButtonControl", "ButtonType", array('btSubmit', 'btReset', 'btNormal'));
registerPropertyValues("Control", "Cursor", array('crNone', 'crAuto', 'crCrossHair', 'crDefault', 'crMove', 'crPointer', 'crText', 'crWait', 'crHelp', 'crProgress', 'crE-Resize', 'crNE-Resize', 'crN-Resize', 'crNW-Resize', 'crW-Resize', 'crSW-Resize', 'crS-Resize', 'crSE-Resize'));
registerPropertyValues("Control", "Alignment", array('agNone', 'agLeft', 'agCenter', 'agRight', 'agInherit'));
registerPropertyValues("CustomEdit", "CharCase", array('ecLowerCase', 'ecNormal', 'ecUpperCase'));
registerPropertyValues("CustomEdit", "DataList", array('DataList'));

registerPropertyValues("Edit", "InputType", array('ceText', 'cePassword', 'ceEmail', 'ceTelephone', 'ceSearch', 'ceURL'));

registerPropertyEditor("DataList", "Items", "TValueListPropertyEditor", "native");

registerPropertyEditor("Shape","Pen.Color","TSamplePropertyEditor","native");
registerPropertyValues("Shape","Shape",array('stRectangle', 'stSquare', 'stRoundRect', 'stRoundSquare', 'stEllipse', 'stCircle'));
registerPropertyEditor("Shape","Brush.Color","TSamplePropertyEditor","native");

registerPropertyValues("Bevel","Shape",array('bsBox', 'bsFrame', 'bsTopLine', 'bsBottomLine', 'bsLeftLine', 'bsRightLine', 'bsSpacer'));
registerPropertyValues("Bevel","BevelStyle",array('bsLowered', 'bsRaised'));

registerPropertyValues("MapShape","Kind",array('skRectangle','skCircle','skDefault'));

registerPropertyValues("CustomCheckBox", "State", array("cbChecked", "cbUnchecked"));
registerPropertyValues("CheckListBox", "Orientation", array('orHorizontal', 'orVertical'));
registerPropertyValues("CustomRadioGroup", "Orientation", array('orHorizontal', 'orVertical'));

registerPropertyValues("FlashObject", "Quality", array('fqLow', 'fqAutoLow', 'fqAutoHigh', 'fqMedium', 'fqHigh', 'fqBest'));

registerBooleanProperty("FlashObject", "Active");
registerBooleanProperty("FlashObject", "Loop");
registerBooleanProperty("Control", "DivWrap");
registerBooleanProperty("Frame", "Borders");
registerBooleanProperty("Image", "Binary");
registerBooleanProperty("Control", "Autosize");



//Register the values for the dropdown of the TemplateEngine property
//See also templateplugins.inc.php, smartytemplate.inc.php
global $TemplateManager;
registerPropertyValues("CustomPage", "TemplateEngine", $TemplateManager->getEngines());

registerBooleanProperty("CustomPage", "GenerateDocument");
registerBooleanProperty("CustomPage", "GenerateTable");

//Register boolean properties to be handled correctly by the IDE
registerBooleanProperty("Control", "Visible");
registerBooleanProperty("Control", "Hidden");
registerBooleanProperty("CustomEdit", "FilterInput");
registerBooleanProperty("CustomMemo", "FilterInput");
registerBooleanProperty("CustomCheckBox", "Checked");
registerBooleanProperty("Control", "ParentFont");
registerBooleanProperty("Control", "ParentColor");
registerBooleanProperty("Control", "ParentShowHint");
registerBooleanProperty("Control", "ShowHint");
registerBooleanProperty("Control", "Layout.UsePixelTrans");

registerBooleanProperty("CustomPage", "IsForm");
registerBooleanProperty("CustomPage", "ShowFooter");
registerBooleanProperty("CustomPage", "ShowHeader");
registerBooleanProperty("CustomPage", "UseAjax");
registerBooleanProperty("CustomPage", "UseAjaxDebug");
registerBooleanProperty("CustomLabel", "WordWrap");
registerBooleanProperty("Image", "Autosize");
registerBooleanProperty("Image", "Center");
registerBooleanProperty("Image", "Proportional");
registerBooleanProperty("Image", "EmbedSVG");
registerBooleanProperty("CustomEdit", "Enabled");
registerBooleanProperty("CustomEdit", "ReadOnly");
registerBooleanProperty("CustomEdit", "TabStop");


registerBooleanProperty("CustomEdit", "Required");

registerBooleanProperty("CustomMemo", "RichEditor");
registerBooleanProperty("CustomMemo", "WordWrap");

registerBooleanProperty("ButtonControl", "Checked");
registerBooleanProperty("ButtonControl", "Default");
registerBooleanProperty("ButtonControl", "Enabled");
registerBooleanProperty("ButtonControl", "TabStop");
registerBooleanProperty("CustomLabel", "Enabled");
registerBooleanProperty("CustomListBox", "Enabled");
registerBooleanProperty("CustomListBox", "MultiSelect");
registerBooleanProperty("CustomListBox", "Sorted");
registerBooleanProperty("CustomListBox", "TabStop");
registerBooleanProperty("Image", "Enabled");
registerBooleanPropertY("Image", "Stretch");
registerBooleanProperty("CustomRadioGroup", "Enabled");
registerBooleanProperty("CustomRadioGroup", "TabStop");
registerBooleanProperty("Panel", "IsLayer");
registerBooleanProperty("Panel", "Draggable");
registerBooleanProperty("Canvas", "Draggable");
registerBooleanProperty("Image", "Draggable");
registerBooleanProperty("Media", "Draggable");
registerBooleanProperty("Label", "Draggable");
registerBooleanProperty("Button", "Draggable");
registerBooleanProperty("RadioButton", "Draggable");
registerBooleanProperty("CheckBox", "Draggable");


registerBooleanProperty("Control", "Enabled");
registerBooleanProperty("Control", "AutoSize");

registerDropDatafield(array("Label", "Edit", "Memo", "ListBox", "ComboBox", "Button", "CheckBox", "RadioButton", "RadioGroup", "Image"));

registerPropertyEditor("CustomCheckListBox", "Items", "TStringListPropertyEditor", "native");
registerPropertyEditor("CustomCheckListBox", "HeaderBackgroundColor", "TSamplePropertyEditor", "native");
registerPropertyEditor("CustomCheckListBox", "HeaderColor", "TSamplePropertyEditor", "native");
registerPropertyValues("CustomLabel", "LinkTarget", array('_blank', '_self', '_parent', '_top'));


//Register available encodings

registerPropertyValues("CustomPage", "Encoding", array(
                                                       'Arabic (ASMO 708)          |ASMO-708',
                                                       'Arabic (DOS)               |DOS-720',
                                                       'Arabic (ISO)               |iso-8859-6',
                                                       'Arabic (Windows)           |windows-1256',
                                                       'Baltic (Windows)           |windows-1257',
                                                       'Central European (DOS)     |ibm852',
                                                       'Central European (ISO)     |iso-8859-2',
                                                       'Central European (Windows) |windows-1250',
                                                       'Chinese Simplified (GB2312)|gb2312',
                                                       'Chinese Simplified (HZ)    |hz-gb-2312',
                                                       'Chinese Traditional (Big5) |big5',
                                                       'Cyrillic (DOS)             |cp866',
                                                       'Cyrillic (ISO)             |iso-8859-5',
                                                       'Cyrillic (KOI8-R)          |koi8-r',
                                                       'Cyrillic (Windows)         |windows-1251',
                                                       'Greek (ISO)                |iso-8859-7',
                                                       'Greek (Windows)            |windows-1253',
                                                       'Hebrew (DOS)               |DOS-862',
                                                       'Hebrew (ISO-Logical)       |iso-8859-8-i',
                                                       'Hebrew (ISO-Visual)        |iso-8859-8',
                                                       'Hebrew (Windows)           |windows-1255',
                                                       'Japanese (EUC)             |euc-jp',
                                                       'Japanese (Shift-JIS)       |shift_jis',
                                                       'Korean (EUC)               |euc-kr',
                                                       'Thai (Windows)             |windows-874',
                                                       'Turkish (Windows)          |windows-1254',
                                                       'Ukraine (KOI8-U)           |koi8-ru',
                                                       'Unicode (UTF-8)            |utf-8',
                                                       'Vietnamese (Windows)       |windows-1258',
                                                       'Western European (ISO)     |iso-8859-1'));

//Register values for the Language property of the CustomPage component
registerPropertyValues("CustomPage", "Language", array('(default)',
                                                       'Afrikaans',
                                                       'Albanian',
                                                       'Arabic (Algeria)',
                                                       'Arabic (Bahrain)',
                                                       'Arabic (Egypt)',
                                                       'Arabic (Iraq)',
                                                       'Arabic (Jordan)',
                                                       'Arabic (Kuwait)',
                                                       'Arabic (Lebanon)',
                                                       'Arabic (libya)',
                                                       'Arabic (Morocco)',
                                                       'Arabic (Oman)',
                                                       'Arabic (Qatar)',
                                                       'Arabic (Saudi Arabia)',
                                                       'Arabic (Syria)',
                                                       'Arabic (Tunisia)',
                                                       'Arabic (U.A.E.)',
                                                       'Arabic (Yemen)',
                                                       'Arabic',
                                                       'Armenian',
                                                       'Assamese',
                                                       'Azeri',
                                                       'Basque',
                                                       'Belarusian',
                                                       'Bengali',
                                                       'Bulgarian',
                                                       'Catalan',
                                                       'Chinese (China)',
                                                       'Chinese (Hong Kong SAR)',
                                                       'Chinese (Macau SAR)',
                                                       'Chinese (Singapore)',
                                                       'Chinese (Taiwan)',
                                                       'Chinese',
                                                       'Croatian',
                                                       'Czech',
                                                       'Danish',
                                                       'Divehi',
                                                       'Dutch (Belgium)',
                                                       'Dutch (Netherlands)',
                                                       'English (Australia)',
                                                       'English (Belize)',
                                                       'English (Canada)',
                                                       'English (Ireland)',
                                                       'English (Jamaica)',
                                                       'English (New Zealand)',
                                                       'English (Philippines)',
                                                       'English (South Africa)',
                                                       'English (Trinidad)',
                                                       'English (United Kingdom)',
                                                       'English (United States)',
                                                       'English (Zimbabwe)',
                                                       'English',
                                                       'Estonian',
                                                       'Faeroese',
                                                       'Farsi',
                                                       'Finnish',
                                                       'French (Belgium)',
                                                       'French (Canada)',
                                                       'French (Luxembourg)',
                                                       'French (Monaco)',
                                                       'French (Switzerland)',
                                                       'French (France)',
                                                       'FYRO Macedonian',
                                                       'Gaelic',
                                                       'Georgian',
                                                       'German (Austria)',
                                                       'German (Liechtenstein)',
                                                       'German (lexumbourg)',
                                                       'German (Switzerland)',
                                                       'German (Germany)',
                                                       'Greek',
                                                       'Gujarati',
                                                       'Hebrew',
                                                       'Hindi',
                                                       'Hungarian',
                                                       'Icelandic',
                                                       'Indonesian',
                                                       'Italian (Switzerland)',
                                                       'Italian (Italy)',
                                                       'Japanese',
                                                       'Kannada',
                                                       'Kazakh',
                                                       'Konkani',
                                                       'Korean',
                                                       'Kyrgyz',
                                                       'Latvian',
                                                       'Lithuanian',
                                                       'Malay',
                                                       'Malayalam',
                                                       'Maltese',
                                                       'Marathi',
                                                       'Mongolian (Cyrillic)',
                                                       'Nepali (India)',
                                                       'Norwegian (Bokmal)',
                                                       'Norwegian (Nynorsk)',
                                                       'Norwegian (Bokmal)',
                                                       'Oriya',
                                                       'Polish',
                                                       'Portuguese (Brazil)',
                                                       'Portuguese (Portugal)',
                                                       'Punjabi',
                                                       'Rhaeto-Romanic',
                                                       'Romanian (Moldova)',
                                                       'Romanian',
                                                       'Russian (Moldova)',
                                                       'Russian',
                                                       'Sanskrit',
                                                       'Serbian',
                                                       'Slovak',
                                                       'Slovenian',
                                                       'Sorbian',
                                                       'Spanish (Argentina)',
                                                       'Spanish (Bolivia)',
                                                       'Spanish (Chile)',
                                                       'Spanish (Colombia)',
                                                       'Spanish (Costa Rica)',
                                                       'Spanish (Dominican Republic)',
                                                       'Spanish (Ecuador)',
                                                       'Spanish (El Salvador)',
                                                       'Spanish (Guatemala)',
                                                       'Spanish (Honduras)',
                                                       'Spanish (Mexico)',
                                                       'Spanish (Nicaragua)',
                                                       'Spanish (Panama)',
                                                       'Spanish (Paraguay)',
                                                       'Spanish (Peru)',
                                                       'Spanish (Puerto Rico)',
                                                       'Spanish (United States)',
                                                       'Spanish (Uruguay)',
                                                       'Spanish (Venezuela)',
                                                       'Spanish (Traditional Sort)',
                                                       'Sutu',
                                                       'Swahili',
                                                       'Swedish (Finland)',
                                                       'Swedish',
                                                       'Syriac',
                                                       'Tamil',
                                                       'Tatar',
                                                       'Telugu',
                                                       'Thai',
                                                       'Tsonga',
                                                       'Tswana',
                                                       'Turkish',
                                                       'Ukrainian',
                                                       'Urdu',
                                                       'Uzbek',
                                                       'Vietnamese',
                                                       'Xhosa',
                                                       'Yiddish',
                                                       'Zulu'
                                                       ));
?>