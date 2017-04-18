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
 * Converts a PHP value into a JavaScript-compatible boolean string ('true' or 'false').
 *
 * You can use it when writting PHP code that generates JavaScript code so you don't have to mess with boolean values.
 *
 * If a non-boolean value is given, it will be evaluated as boolean nonetheless.
 *
 * <code>
 * echo boolToStr(($a==1));
 * echo boolToStr(0); // Prints 'false'.
 * </code>
 *
 * @param mixed $value Value to be evaluated as a boolean.
 * @return string String to represent the given value as a boolean in JavaScript.
 */
function boolToStr( $value )
{
    return $value ? 'true' : 'false';
}

/**
 * Converts plain text into HTML code, replacing line breaks with <br> and non-HTML characters with HTML entities.
 *
 * <code>
 * echo textToHtml("this is plain-text\nIncluding áéíóú chars\n");
 * // This will produce "this is plain-text<br>Incluiding &aacute;&eacute;&iacute;&oacute;&uacute; chars<br>"
 * </code>
 *
 * @see htmlToText()
 * @link http://www.php.net/manual/en/function.nl2br.php
 *
 * @param string $text    Plain text to be translated into HTML code.
 * @param string $charset Character encoding to be used during the process. Optional.
 * @return string HTML code.
 */
function textToHtml( $text, $charset=null )
{
    if( isset($charset) )
    {
      return nl2br( htmlentities($text, ENT_QUOTES, $charset) );
    }
    else
    {
      return nl2br( htmlentities( $text ) );
    }
}

/**
 * Converts HTML code to plain text, replacing <br> with actual line breaks, and HTML entities with the characters they
 * represent.
 *
 * <code>
 * echo htmlToText("this is HTML<br />Including &aacute;&eacute;&iacute;&oacute;&uacute; chars<br />");
 * // This will produce "this is HTML\nIncluding áéíóú chars\n"
 * </code>
 *
 * @see textToHtml()
 * @link http://www.php.net/manual/en/function.html-entity-decode.php
 *
 * @param string $text HTML code to be translated into plain text.
 * @return string Plain text content.
 */
function htmlToText( $text )
{
    return html_entity_decode( str_replace( '<br />', "\r\n", $text ) );
}

/**
 * Redirects the browser to the given project unit.
 *
 * <code>
 * redirect("unit2.php");
 * </code>
 *
 * @link http://www.php.net/manual/en/function.header.php
 *
 * @param string $unitpath Path to the target unit, relative to the project root.
 */
function redirect( $unitpath )
{
    $host = $_SERVER[ 'HTTP_HOST' ];
    $uri = rtrim( dirname( $_SERVER[ 'PHP_SELF' ] ), '/\\' );
    header( 'Location: http://' . $host . $uri . '/' . $unitpath );
    exit();
}

/**
 * Checks whether the given variable is null (false) or not (true).
 *
 * This function is the equivalent for Delphi's assigned(). Note: The variable does not need to be an object.
 *
 * @param mixed $variable Variable to be checked.
 * @return boolean
 */
function assigned($variable)
{
    return($variable!=null);
}

/**
* EAbort is the exception class for errors that should not display an error message.
*
* Use EAbort to raise an exception without displaying an error message. If applications do not trap such “silent” exceptions,
* the EAbort exception is passed to the standard exception handler.
*
* The Abort procedure provides a simple, standard way to raise EAbort.
*
* @see Abort()
*
*/
class EAbort extends Exception
{
}

/**
 * Throws an exception without reporting an error.
 *
 * A special , silent exception (EAbort) is raised. It operates like any other exception, but does not display an error
 * message to the end user.
 *
 * Useful to redirect the execution flow to the end of the last exception block, and contunie with the rest of the
 * execution.
 *
 * <code>
 * <?php
 *     function CheckOperation($Operation, $ErrorEvent)
 *     {
 *         $Done = false;
 *         do
 *         {
 *             try
 *             {
 *               $this->$Operation();
 *               $Done=true;
 *             }
 *             catch (EDatabaseError $e)
 *             {
 *                 $Action=daFail;
 *                 $Action=$this->callEvent($ErrorEvent, array('Exception'=>$e, 'Action'=>$Action));
 *                 if ($Action==daFail) throw $e;
 *                 if ($Action==daAbort) Abort();
 *             }
 *         }
 *         while(!$Done);
 *     }
 * ?>
 * </code>
 *
 * @see EAbort
 */
function Abort()
{
    throw new EAbort();
}

/**
 * Extracts the JavaScript code from a string of HTML code.
 *
 * <code>
 * <?php
 *   $result = extractjscript($htmlWithJavaScript);
 *   $javaScriptCode = $result[0];
 *   $htmlCodeWithoutJavaScript = $result[1];
 * ?>
 * </code>
 *
 * @param string $html HTML document to extract the JavaScript code from.
 * @return array Simple array with two items: a string with the JavaScript code, and another string with the HTML code
 * without the JavaScript code.
 */
function extractjscript( $html )
{
    $result = '';
    $pattern = '/<script[^>]*?>.*?<\/script>/si';
    $scripts = preg_match_all( $pattern, $html, $out);
    $onlyhtml = preg_replace( $pattern, '', $html );
    $pattern = '/^<script[^>]*?>(.*?)<\/script>$/si';

    foreach( $out[ 0 ] as $script )
    {
        if( preg_match( $pattern, $script, $arr ) )
            $result .= trim( $arr[ 1 ] );
    }

    return array( $result, $onlyhtml );
}

/**
 * DBCS-friendly unserialize function, which modifies the length of all strings with the correct size.
 *
 * This function allows you to unserialize serialized objects/arrays/variables when working with double-byte character
 * sets, by fixing the length of strings to the real length before unserializing.
 *
 * If not doing it this way, unserialize will throw an error due to incorrect string lengths.
 *
 * @link http://www.php.net/manual/en/function.unserialize.php
 * @see safeunserialize()
 *
 * @param string $serializedObject String with a serialized object.
 * @return mixed
 */
function __unserialize($serializedObject)
{
    $__ret =preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $serializedObject );

    return unserialize($__ret);
}

/**
 * Alias to the PHP built-in unserialize() function, with a fallback for cases of double-byte character sets (DBCS)
 * which cannot be handled by unserialize().
 *
 * @link http://www.php.net/manual/en/function.unserialize.php
 * @see __unserialize()
 *
 * @param string $serializedObject String with a serialized object.
 * @return mixed
 */
function safeunserialize($serializedObject)
{
    $result=@unserialize($serializedObject);
    if ($result===false)
    {
        $result=__unserialize($serializedObject);
    }
    return($result);
}

/**
 * Returns a string with the GET parameters (skipping 'restore_session') the current page was called with.
 *
 * For example, if you request the page with "http://example.com/unit1.php?key1=value1&restore_session=1&key2=value2",
 * the function will return "key1=value1&key2=value2".
 */
function urlparams()
{
    $result='';
    reset($_GET);
    while(list($key, $val)=each($_GET))
    {
        if ($key=='restore_session') continue;
        if ($result!='') $result.='&';
        $result.=$key.'='.$val;
    }
    return($result);
}

?>