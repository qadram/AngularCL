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

use_unit("controls.inc.php");

/**
 * Base class for the StyleSheet component.
 *
 * This component allows you to reference a CSS file. Then, you can apply the classes on
 * the CSS file to your components using their Style property.
 */
class CustomStyleSheet extends Component
{
        protected $_filename="";
        protected $_stylelist=array();
        protected $_inclstandard=0;
        protected $_inclid=0;
        protected $_incsubstyle=0;

        /**
         * Returns an array of CSS selectors for the given CSS file.
         *
         * @internal
         *
         * @param string  $FileName     Path to the CSS file to be parsed.
         * @param boolean $InclStandard If true, will include selectors for HTML elements. For example: 'h1' or 'p'.
         * @param boolean $InclID       If true, will also include id-based div selectors. For example: '#subheader' or '#content_box'.
         * @param boolean $InclSubStyle If true, will include composed selectors. For example: '#subheader h1' or 'p.class1.class2 span'.
         *
         * @return array  Array with styles available on $filename
         */
        function BuildStyleList($FileName, $InclStandard, $InclID, $InclSubStyle)
        {
                $array=array();

                if (($FileName === "") || (!file_exists($FileName))) return $array;
                if (($file = fopen($FileName, "r")) == false) return $array;

                // Preload File, Parse out comments
                $flag = false;
                while (!feof($file))
                {
                        $line = fgets($file, 4096);
                        $line = trim($line);
                        while ($line != "")
                        {
                                if ($flag)
                                {
                                        $pos = strpos($line, "*/");
                                        if ($pos === false) $line = "";
                                        else
                                        {
                                                $line = substr($line, $pos + 3, strlen($line));
                                                $flag = false;
                                        }
                                }
                                else
                                {
                                        $pos = strpos($line, "/*");
                                        if ($pos === false)
                                        {
                                                $lines[] = $line;
                                                $line = "";
                                        }
                                        else
                                        {
                                                $flag = true;
                                                if ($pos !== 0)
                                                {
                                                        $temp = trim(substr($line, 0, $pos));
                                                        if (!$temp==="") $lines[] = $temp;
                                                }
                                                $line = substr($line, $pos + 2, strlen($line));
                                        }
                                }
                        }
                }
                fclose($file);
                // Nothing to work with
                if ((!isset($lines)) || (count($lines) == 0)) return $array;

                // Parse lines, remove CSS Definitions
                reset($lines);
                $flag = false;
                $lines2=array();
                while (list($index, $line) = each($lines))
                {
                        while ($line!=="")
                        {
                                if ($flag)
                                {
                                        $pos = strpos($line, "}");
                                        if ($pos === false) $line = "";
                                        else
                                        {
                                                $line = trim(substr($line, $pos + 1, strlen($line)));
                                                $flag = false;
                                        }
                                }
                                else
                                {
                                        $pos = strpos($line, "{");
                                        if ($pos === false)
                                        {
                                                if (($line!=="") && (!in_array($line, $lines2)))
                                                        $lines2[] = $line;
                                                $line = "";
                                        }
                                        else
                                        {
                                                $flag = true;
                                                if ($pos !== 0)
                                                {
                                                        $temp = trim(substr($line, 0, $pos));
                                                        if ($temp!=="")
                                                                if ((!isset($lines2)) || (!in_array($temp, $lines2)))
                                                                        $lines2[] = $temp;
                                                }
                                                $line = trim(substr($line, $pos + 1, strlen($line)));
                                        }
                                }
                        }
                }
                // Nothing to work with
                if ((!isset($lines2)) || (count($lines2) == 0)) return $array;

                // Prepare style list
                reset($lines2);
                while (list(, $line) = each($lines2))
                {
                        $words = explode(",", $line);
                        reset($words);
                        while (list(, $word) = each($words))
                        {
                                $word = trim($word);
                                if ($word == "") continue;

                                if ($InclSubStyle == 0)
                                {
                                        $pos1 = strpos($word, '.');
                                        $pos2 = strpos($word, '#');
                                        if (($pos1 === 0) || ($pos2 === 0))
                                        {
                                                $prefix = $word{0};
                                                $word = trim(substr($word, 1, strlen($word)));
                                                $parts = preg_split('/[ .#]/', $word);
                                                reset($parts);
                                                $part = $prefix . trim(current($parts));
                                        }
                                        else
                                        {
                                                $parts = preg_split('/[ .#]/', $word);                                                
                                                reset($parts);
                                                $part = trim(current($parts));
                                        }
                                }
                                else
                                        $part = $word;

                                if (trim($part) == "") continue;

                                if ((isset($array)) && (in_array($part, $array))) continue;

                                $pos1 = strpos($part, '.');
                                $pos2 = strpos($part, '#');
                                if ((($InclStandard == 1) && ($pos1 === false) && ($pos2 === false))
                                  || (($InclID == 1) && ($pos2 === 0))
                                  || ($pos1 === 0)
                                  )
                                {
                                        $array[] = $part;
                                }
                        }
                }
                return $array;
        }

        /**
         * Stores a list of style selectors from the stylesheet file on the Style property.
         *
         * @internal
         */
        protected function ParseCSSFile()
        {
                $this->_stylelist=$this->BuildStyleList($this->FileName, $this->_inclstandard, $this->_inclid, $this->_incsubstyle);
        }

        function dumpHeaderCode()
        {
                echo("<link rel=\"stylesheet\" href=\"" . $this->_filename . "\" type=\"text/css\" />\n");
        }

        function loaded()
        {
                $this->ParseCSSFile();
        }

        /**
         * Path to the target CSS file.
         */
        protected function readFileName()               { return $this->_filename; }
        protected function writeFileName($value)        { $this->_filename=$value; }
        function defaultFileName()                      { return ""; }

        /**
         * Whether the Styles array should include selectors for HTML elements (true) or not (false).
         *
         * If set to true, selectors for elements as 'h1' or 'p' would be available in the
         * Styles array.
         */
        protected function readIncludeStandard()        { return $this->_inclstandard; }
        protected function writeIncludeStandard($value) { $this->_inclstandard = $value; }
        function defaultIncludeStandard()               { return 0; }

        /**
         * Whether the Styles array should include id-based div selectors (true) or not (false).
         *
         * If set to true, selectors such as '#subheader' or '#content_box' would be
         * available in the Styles array.
         */
        protected function readIncludeID()              { return $this->_inclid; }
        protected function writeIncludeID($value)       { $this->_inclid = $value; }
        function defaultIncludeID()                     { return 0; }

        /**
         * Whether the Styles array should include composed selectors (true) or not (false).
         *
         * If set to true, selectors such as '#subheader h1' or 'p.class1.class2 span'
         * would be available in the Styles array.
         */
        protected function readIncludeSubStyle()        { return $this->_incsubstyle; }
        protected function writeIncludeSubStyle($value) { $this->_incsubstyle = $value; }
        function defaultIncludeSubStyle()               { return 0; }
        
        /**
         * Array of selectors available in the target CSS file.
         */
        function readStyles()                           { $this->ParseCSSFile();
                                                          return $this->_stylelist; }
        function writeStyles($value)                    { $this->_stylelist = $value; }
}

/**
 * Component to use CSS stylesheets with other components.
 *
 * This component allows you to reference a CSS file. Then, you can apply the selectors on
 * the CSS file to your components using their Style property.
 *
 * During design time, a list of possible selectors for the Style of other components will
 * be available.
 *
 * To use this component, add it to a Page and point its FileName property to a CSS
 * file. Then, you can give the Style property of any control a value consisting of a
 * whitespace-separated list of HTML classes (for which there should be a style defined in the
 * CSS file).
 *
 * @link wiki://StyleSheet
 * @example GetStylesFromStyleSheet/GetStylesFromStyleSheet.php How to use the StyleSheet component
 */
class StyleSheet extends CustomStyleSheet
{
        // Publish properties
        function getFileName()                  { return $this->readFileName(); }
        function setFileName($value)            { $this->writeFileName($value); }

        function getIncludeStandard()           { return $this->readIncludeStandard(); }
        function setIncludeStandard($value)     { $this->writeIncludeStandard($value); }

        function getIncludeID()                 { return $this->readIncludeID(); }
        function setIncludeID($value)           { $this->writeIncludeID($value); }

        function getIncludeSubStyle()           { return $this->readIncludeSubStyle(); }
        function setIncludeSubStyle($value)     { $this->writeIncludeSubStyle($value); }
}

?>