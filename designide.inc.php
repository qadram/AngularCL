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

/**
 * Specifies to the IDE the title of this package, use it on a package.php and
 * set the parameter with the Title of the package to show on Component | Packages
 *
 * @see setIconPath()
 *
 * @param string $packageTitle Title of the package to be shown on the IDE
 */
function setPackageTitle($packageTitle)
{
        echo "packageTitle=$packageTitle\n";
}

/**
 * Specifies to the IDE the path to the icons for the components contained in
 * this package (relative to the RPCL path). Icons must be 16x16 bitmaps.
 *
 * @see setPackageTitle()
 *
 * @param string $iconPath Path where to find icons for this package
 */
function setIconPath($iconPath)
{
        echo "iconPath=$iconPath\n";
}

/**
 * Registers components inside the IDE and places into the right palette page,
 * it also allows the IDE to add the right unit to the source.
 *
 * Using this function, you install a component inside the IDE and allows it
 * to add the right unit to your source code using Code Insight.
 *
 * @see registerAsset(), registerComponentEditor(), registerPropertyEditor()
 *
 * @param string $page Page where to put these components
 * @param array $components Array of component class names
 * @param string $unit Unit where to find these components
 * @param string $container Valid container which can hold these components, leave empty to allow the component to work on any container
 */
function registerComponents($page,$components,$unit, $container='')
{
   echo "page=$page\n";
   reset($components);
   while (list($k,$v)=each($components))
   {
        use_unit($unit);
        $iconic=((is_subclass_of($v,'Component')) && (!is_subclass_of($v,'Control')));
        echo "$v=$unit,$iconic,$container\n";
   }
}

function registerPropertiesInCategory($category, $properties)
{
    echo "propcategory=$category\n";
   reset($properties);
   while (list($k,$v)=each($properties))
   {
        echo "propname=$v\n";
   }
}

/**
 * Registers an asset for the Deployment wizard, if your component needs extra
 * folder(s) to be added for deployment, you can use this function to notify
 * Deployment Wizard which folders do you want to get added
 *
 * <code>
 * <?php
 *     registerAsset(array("MainMenu","PopupMenu"),array("qooxdoo","dynapi"));
 * ?>
 * </code>
 *
 * @link wiki://Package_Development#Deployment_Dependencies
 *
 * @see registerComponents(), registerComponentEditor(), registerPropertyEditor()
 *
 * @param array $components Array of components you want to register the asset
 * @param array $assets Array of folders you want to get copied when this component is used
 */
function registerAsset($components, $assets)
{
        reset($components);
        while (list($k,$v)=each($components))
        {
                echo "asset=$v\n";
                reset($assets);
                while (list($c,$asset)=each($assets))
                {
                        echo "value=".$asset."\n";
                }
        }
}

/**
 * Registers a component editor to be used by a component when right clicking on it
 *
 * <code>
 * <?php
 *     registerComponentEditor("Database","DatabaseEditor","designide.inc.php");
 * ?>
 * </code>
 * @link wiki://Component_Integration_with_HTML5_Builder#Component_Editors
 *
 * @see registerComponents(), registerAsset(), registerPropertyEditor()
 *
 * @param string $classname Name of the component class for which register this editor
 * @param string $componenteditorclassname Name of the class for the component editor
 * @param string $unitname Unit where the component editor resides
 */
function registerComponentEditor($classname,$componenteditorclassname,$unitname)
{
   echo "componentclassname=$classname\n";
   echo "componenteditorname=$componenteditorclassname\n";
   echo "componenteditorunitname=$unitname\n";
}

/**
 * Registers a property editor to edit an specific property
 *
 * <code>
 * <?php
 *     registerPropertyEditor("Control","Color","TSamplePropertyEditor","native");
 * ?>
 * </code>
 * @link wiki://Property_Editors
 *
 * @see registerComponents(), registerAsset(), registerComponentEditor()
 *
 * @param string $classname It can be an ancestor, property editors are also inherited
 * @param string $property Property Name
 * @param string $propertyclassname Property Editor class name
 * @param string $unitname Unit that holds the property editor class
 */
function registerPropertyEditor($classname,$property,$propertyclassname,$unitname)
{
   echo "classname=$classname\n";
   echo "property=$property\n";
   echo "propertyeditor=$propertyclassname\n";
   echo "propertyeditorunitname=$unitname\n";
}

/**
 * Register values to be shown for a dropdown property, this function provides
 * you a way to offer possibilities to the component user to setup a property
 *
 * <code>
 * <?php
 *     registerPropertyValues("DBPaginator","Orientation",array('noHorizontal','noVertical'));
 *     registerPropertyValues("Datasource","DataSet",array('DataSet'));
 * ?>
 * </code>
 * @link wiki://Property_Editors
 *
 * @see registerBooleanProperty()
 *
 * @param string $classname Name of the class for which component we want to register these values
 * @param string $property Property name for which register this values
 * @param array $values Array of valid values will be shown in the Object Inspector
 */
function registerPropertyValues($classname,$property,$values)
{
   echo "classname=$classname\n";
   echo "property=$property\n";

   reset($values);
   while (list($k,$v)=each($values))
   {
        echo "value=$v\n";
   }
}

/**
 * Registers a boolean property, so the Object Inspector offers a true/false dropdown
 *
 * <code>
 * <?php
 *     registerBooleanProperty("Control","Visible");
 * ?>
 * </code>
 * @see registerPropertyValues()
 * @param string $classname Name of the component class for which register this property
 * @param string $property Property name
 */
function registerBooleanProperty($classname,$property)
{
   $values=array('false','true');

   echo "classname=$classname\n";
   echo "property=$property\n";

   reset($values);
   while (list($k,$v)=each($values))
   {
        echo "value=$v\n";
   }
}

/**
 * Registers a password property, so the Object Inspector doesn't show the value
 * showing asterisks instead
 *
 * <code>
 * <?php
 *     registerPasswordProperty("CustomConnection","UserPassword");
 * ?>
 * </code>
 * @param string $classname Name of the component class for which register this property
 * @param string $property Name of the property to be password like
 */
function registerPasswordProperty($classname,$property)
{
   echo "classname=$classname\n";
   echo "property=$property\n";
   echo "value=password_protected\n";
}

/**
 * Register a component to be available but not visible on the Tool Palette
 *
 * <code>
 * <?php
 *        registerNoVisibleComponents(array("Page"),"forms.inc.php");
 *        registerNoVisibleComponents(array("DataModule"),"forms.inc.php");
 * ?>
 * </code>
 * @see registerComponents()
 *
 * @param array $components Array of component class names that are going to be no visible
 * @param string $unit Unit where to find those components
 */
function registerNoVisibleComponents($components,$unit)
{
   echo "page=no\n";
   reset($components);
   while (list($k,$v)=each($components))
   {
        echo "$v=$unit\n";
   }
}

function addSplashBitmap($caption,$bitmap)
{
    echo "splashcaption=$caption\n";
    echo "splashbitmap=$bitmap\n";
}

function registerDropDatasource($components)
{
   reset($components);
   while (list($k,$v)=each($components))
   {
        echo "multiline=$v\n";
   }
}

function registerDropDatafield($components)
{
   reset($components);
   while (list($k,$v)=each($components))
   {
        echo "singleline=$v\n";
   }
}



/**
 * Base class for component editors
 *
 */
class ComponentEditor extends Object
{
        public $component=null;

        /**
         * Return here an array of items to show when right clicking a component
         *
         * Use this method to return the IDE the array of options to show when the
         * user right clicks a component.
         * Each element on the array will become an item on the popup menu shown.
         * If you want to perform an specific action when clicking on an option,
         * use the executeVerb method.
         *
         * @return array
         */
        function getVerbs()
        {

        }

        /**
         * Depending on the verb, perform any action you want
         *
         * This method is called by the IDE when the user selects an option
         * of the popup menu shown when the user right clicks on it.
         *
         * The option the user selects is specified on the $verb param and
         * you must use the getVerbs method to tell the IDE which options to
         * show.
         *
         * @param integer $verb Index of the verb the IDE wants to execute
         */
        function executeVerb($verb)
        {

        }

}

/**
 * Database component editor, to show right-click menu options
 *
 * This componenteditor is used by the Database component, when right clicking on it,
 * to show a set of options to be used from the IDE, like create a data dictionary.
*/

class DatabaseEditor extends ComponentEditor
{
        function getVerbs()
        {
                //echo "Create Dictionary\n";
        }

        function executeVerb($verb)
        {
                switch($verb)
                {
                        case 0:
                                $this->component->ControlState=0;
                                $this->component->open();
                                if ($this->component->createDictionaryTable())
                                {
                                        echo "Dictionary created";
                                }
                                else
                                {
                                    echo "Error creating Dictionary. Please check the connection settings and the Dictionary property.";
                                }
                                break;
                }
        }
}

class CustomMediaEditor extends ComponentEditor
{
        function getVerbs()
        {
                echo "Enable autoplay\n";
        }

        function executeVerb($verb)
        {
                switch($verb)
                {
                        case 0: echo "Enable autoplay";
                                $this->component->setAutoplay(true);


                                break;
						//echo "Dictionary created";
                }
        }
}





?>