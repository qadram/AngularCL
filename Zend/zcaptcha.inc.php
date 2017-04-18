<?php

/**
 * Zend/zcaptcha.inc.php
 * 
 * Defines Zend Framework CAPTCHA component.
 *
 * This file is part of the RPCL project.
 *
 * Copyright (c) 2004-2011 Embarcadero Technologies, Inc.
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
 * @copyright   2004-2011 Embarcadero Technologies, Inc.
 * @license     http://www.gnu.org/licenses/lgpl-2.1.txt LGPL
 * 
 */

/**
 * Include RPCL common file and necessary units.
 */
require_once("rpcl/rpcl.inc.php");
use_unit("controls.inc.php");
use_unit("Zend/zcommon/zcommon.inc.php");
use_unit("Zend/framework/library/Zend/Captcha/Word.php");
use_unit("Zend/framework/library/Zend/View.php");
use_unit("Zend/framework/library/Zend/Captcha/Dumb.php");
use_unit("Zend/framework/library/Zend/Captcha/Figlet.php");
use_unit("Zend/framework/library/Zend/Captcha/Image.php");
use_unit("Zend/framework/library/Zend/Captcha/ReCaptcha.php");

// Text Alignments

/**
 * Right alignment.
 * 
 * @const       jtRight
 */
define('jtRight', 'jtRight');

/**
 * Center alignment.
 * 
 * @const       jtCenter
 */
define('jtCenter', 'jtCenter');

/**
 * Left alignment.
 * 
 * @const       jtLeft
 */
define('jtLeft', 'jtLeft');

// Text Directions

/**
 * Right-to-left text direction.
 * 
 * @const       dtRightToLeft
 */
define('dtRightToLeft', 'dtRightToLeft');

/**
 * Left-to-right text direction.
 * 
 * @const       dtLeftToRight
 */
define('dtLeftToRight', 'dtLeftToRight');

// CAPTCHA Types

/**
 * Dumb CAPTCHA.
 *
 * This CAPTCHA provides a random string that must be typed in reverse to validate. Is not a good
 * CAPTCHA, but it can be used for testing purposes.
 * 
 * @const       ctDumb
 */
define('ctDumb', 'ctDumb');

/**
 * FIGlet.
 *
 * A FIGlet text is a string represented in ASCII art.
 *
 * @link        http://framework.zend.com/manual/en/zend.text.figlet.html Zend Framework Documentation
 * 
 * @const       ctFiglet
 */
define('ctFiglet', 'ctFiglet');

/**
 * Image CAPTCHA.
 *
 * This CAPTCHA generates a PNG image from a given text. Various skewing permutations are performed
 * on the image to make it difficult to automatically decipher its content.
 *
 * This CAPTCHA requires GD PHP extension (http://es.php.net/manual/en/book.image.php) in
 * order to work.
 * 
 * @const       ctImage
 */
define('ctImage', 'ctImage');

/**
 * reCAPTCHA.
 *
 * reCAPTCHA is a free CAPTCHA service that is used to help digitize the text of books while
 * protecting websites from bots attempting to access restricted areas.
 *
 * @link        http://en.wikipedia.org/wiki/Recaptcha Wikipedia
 * 
 * @const       ctRecaptcha
 */
define('ctRecaptcha', 'ctRecaptcha');

// reCAPTCHA Themes

/**
 * Red theme.
 * 
 * @const       trRed
 */
define('trRed', 'trRed');

/**
 * White theme.
 * 
 * @const       trWhite
 */
define('trWhite', 'trWhite');

/**
 * Black glass theme.
 * 
 * @const       trBlackGlass
 */
define('trBlackGlass', 'trBlackGlass');

/**
 * Clean theme.
 * 
 * @const       trClean
 */
define('trClean', 'trClean');

// reCAPTCHA Languages

/**
 * English.
 * 
 * @const       lrEN
 */
define('lrEN', 'lrEN');

/**
 * Dutch.
 * 
 * @const       lrNL
 */
define('lrNL', 'lrNL');

/**
 * French.
 * 
 * @const       lrFR
 */
define('lrFR', 'lrFR');

/**
 * German.
 * 
 * @const       lrDE
 */
define('lrDE', 'lrDE');

/**
 * Portuguese.
 * 
 * @const       lrPT
 */
define('lrPT', 'lrPT');

/**
 * Russian.
 * 
 * @const       lrRU
 */
define('lrRU', 'lrRU');

/**
 * Spanish.
 * 
 * @const       lrES
 */
define('lrES', 'lrES');

/**
 * Turkish.
 * 
 * @const       lrTR
 */
define('lrTR', 'lrTR');

/**
 * Component to generate CAPTCHAs.
 * 
 * @link        http://framework.zend.com/manual/en/zend.captcha.html Zend Framework Documentation
 */
class ZCaptcha extends Control
{

   /**
    * Zend Framework CAPTCHA instance.
    *
    * Its content will be an instance of a class extending Zend_Captcha_Word, which is the
    * base class for Zend Framework CAPTCHA adapters.
    *
    * @var      Zend_Captcha_Word
    */
   private $_captcha = null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
      $this->_smushmode = new SmushMode();
      $this->_smushmode->_control = $this;
   }

   // Documented in the parent.
   function dumpContents()
   {
      parent::dumpContents();
      if(($this->ControlState & csDesigning) == csDesigning)
      {
         if($this->_captchatype == ctRecaptcha)
         {
            echo '<img height="127" width="316" src="' . RPCL_FS_PATH . '/Zend/images/recaptcha.png">';
         }
         else
         {
            $this->createCaptcha();
            $this->dumpCaptcha();
         }
      }
      else
      {

         $this->createCaptcha();
         $this->dumpCaptcha();
      }

   }

   /**
    * Renders CAPTCHAs other than reCAPTCHA.
    *
    * This method is called during the rendering of the control, from dumpContents().
    *
    * @internal
    */
   function dumpCaptcha()
   {
      if(is_object($this->_captcha))
      {
         $id = $this->_captcha->generate();
         $view = new Zend_View();
         echo $this->_captcha->render($view);
         echo '<input type="hidden" value="' . $id . '" name="' . $this->Name . '"/>';

      }
   }

   /**
    * Generator for $_captcha.
    *
    * Generates a Zend Framework CAPTCHA from those properties set for this ZCaptcha
    * instance (or defaults), and saves it to $_captcha.
    *
    * @internal
    */
   function createCaptcha()
   {
      $data = array();

      $data['wordLen'] = $this->_wordlength;
      $data['timeout'] = $this->_timeout;

      if($this->_usenumbers == 'false' || $this->_usenumbers == 0)
      {
         $data['useNumbers'] = false;
      }
      else
      {
         $data['useNumbers'] = true;
      }

      switch($this->_captchatype)
      {
         case ctFiglet:
            if($this->_handleparagraphs == 'false' || $this->_handleparagraphs == 0)
            {
               $data['handleParagraphs'] = 0;
            }
            else
            {
               $data['handleParagraphs'] = 1;
            }

            $data['outputWidth'] = $this->_outputwidth;
            switch($this->_justificationtext)
            {
               case jtRight:
                  $data['justification'] = Zend_Text_Figlet::JUSTIFICATION_RIGHT;
                  break;
               case jtCenter:
                  $data['justification'] = Zend_Text_Figlet::JUSTIFICATION_CENTER;
                  break;
               case jtLeft:
                  $data['justification'] = Zend_Text_Figlet::JUSTIFICATION_LEFT;
                  break;
            }
            switch($this->_directiontext)
            {
               case dtRightToLeft:
                  $data['rightToLeft'] = Zend_Text_Figlet::DIRECTION_RIGHT_TO_LEFT;
                  break;
               case dtLeftToRight:
                  $data['rightToLeft'] = Zend_Text_Figlet::DIRECTION_LEFT_TO_RIGHT;
                  break;
            }

            $data['rightToLeft'] = $this->_directiontext;
            $data['smushMode'] = $this->_smushmode->SmushModeList();
            $this->_captcha = new Zend_Captcha_Figlet($data);
            break;
         case ctDumb:
            $this->_captcha = new Zend_Captcha_Dumb($data);
            break;
         case ctImage:

            $data['expiration'] = $this->_expiration;
            $data['gcFreq'] = $this->_garbagefrecuency;
            if($this->_fontcaptcha == '')
            {
               $data['font'] = RPCL_FS_PATH . '/Zend/fonts/Vera.ttf';
            }
            else
            {
               $data['font'] = $this->_fontcaptcha;
            }

            $data['fontSize'] = $this->_fontsize;
            $data['height'] = $this->Height;
            $data['width'] = $this->Width;

            $data['suffix'] = $this->_suffix;
            $data['dotNoiseLevel'] = $this->_dotnoiselevel;
            $data['lineNoiseLevel'] = $this->_linenoiselevel;
            $data['imgAlt'] = $this->_imagealt;

            if(($this->ControlState & csDesigning) == csDesigning)
            {
               $tempdir = $this->guessTempFolder();
               $data['imgDir'] = $tempdir;
               $data['imgUrl'] = $tempdir;

            }
            else
            {

               if($this->_imagedir != '' && $this->_imageurl != '')
               {

                  $data['imgDir'] = $this->_imagedir;
                  $data['imgUrl'] = $this->_imageurl;
               }
            }

            if(isset($data['imgDir']) && isset($data['imgUrl']))
            {
               $this->_captcha = new Zend_Captcha_Image($data);
            }
            else
            {
               echo 'You must define the path of ImageUrl and ImageDir';
            }

            break;
         case ctRecaptcha:

            if($this->_publickey != '' && $this->_privatekey != '')
            {

               $options = array();
               switch($this->_languagerecaptcha)
               {
                  case lrEN:
                     $lang = 'en';
                     break;
                  case lrNL:
                     $lang = 'nl';
                     break;
                  case lrFR:
                     $lang = 'fr';
                     break;
                  case lrDE:
                     $lang = 'de';
                     break;
                  case lrPT:
                     $lang = 'pt';
                     break;
                  case lrRU:
                     $lang = 'ru';
                     break;
                  case lrES:
                     $lang = 'es';
                     break;
                  case lrTR:
                     $lang = 'tr';
                     break;
               }

               switch($this->_themerecaptcha)
               {
                  case trRed:
                     $theme = 'red';
                     break;
                  case trWhite:
                     $theme = 'white';
                     break;
                  case trBlackGlass:
                     $theme = 'blackglass';
                     break;
                  case trClean:
                     $theme = 'clean';
                     break;
               }
               $options['theme'] = $theme;
               $options['lang'] = $lang;

               $this->_captcha = new Zend_Captcha_ReCaptcha($options);

               $this->_captcha->setPubkey($this->_publickey);
               $this->_captcha->setPrivkey($this->_privatekey);
            }
            else
            {
               echo 'You must define PublicKey and PrivateKey';
            }
            break;

      }
   }

   // Loaded

   // Documented in the parent.
   function loaded()
   {
      parent::loaded();
      //Resolves references of Edit object and Label object
      $this->_inputcaptcha = $this->fixupProperty($this->_inputcaptcha);
   }

   // Temporal Folder

   /**
    * Guess a temporal-folder path.
    *
    * @return   string
    */
   function guessTempFolder()
   {
      if(preg_match("/^WIN/i", PHP_OS))
      {
         if(isset($_ENV['TMP']))
         {
            $temp = $_ENV['TMP'];
         }
         elseif(isset($_ENV['TEMP']))
         {
            $temp = $_ENV['TEMP'];
         }
         else
         {
            $tmpfolder = getenv('TMP');
            if(($tmpfolder === false) || ($tmpfolder == ''))
            {
               $tmpfolder = getenv('TEMP');
               if(($tmpfolder === false) || ($tmpfolder == ''))
               {
                  $tmpfolder = '/tmp/';
               }
            }
            $temp = $tmpfolder;
         }
      }
      else
      {
         $temp = '/tmp/';
      }

      return $temp;
   }

   /**
    * Check is user-submitted value for the CAPTCHA matches the real value.
    *
    * This returns true if values match, false otherwise. If no input file was set, an error message
    * will be printed, and the function will return null.
    *
    * @return   boolean
    */
   function isValid()
   {
      if($this->_captchatype == ctRecaptcha)
      {
         $this->createCaptcha();
         if($this->_captcha)
         {
            return $this->_captcha->isValid($_POST);
         }
         else
         {
            return false;
         }
      }
      else
      {
         if($this->_inputcaptcha != null)
         {
            $nameinput = $this->_inputcaptcha->getName();
            $namecaptcha = $this->Name;

            $id_captcha = $_POST[$namecaptcha];
            $input = $_POST[$nameinput];

            $data = array();
            $data['id'] = $id_captcha;
            $data['input'] = $input;
            $this->createCaptcha();
            if($this->_captcha)
            {
               return $this->_captcha->isValid($data);
            }
            else
            {
               return false;
            }
         }
         else
         {
            echo "ERROR, You must define Edit object used to validate";
         }
      }
   }

   // Input CAPTCHA

   /**
    * Edit component user must fill with CAPTCHA value.
    *
    * This property must be set for every CAPTCHA type but reCAPTCHA.
    *
    * @var      Edit
    */
   protected $_inputcaptcha = null;

   /**
    * Edit component user must fill with CAPTCHA value.
    *
    * This property must be set for every CAPTCHA type but reCAPTCHA.
    */
   function getInputCaptcha()    {return $this->_inputcaptcha;}

   /**
    * Setter method for $_inputcaptcha.
    *
    * @param    Edit    $value
    */
   function setInputCaptcha($value)    {$this->_inputcaptcha = $this->fixupProperty($value);}

   /**
    * Getter for $_inputcaptcha's default value.
    *
    * @return   Edit    Null
    */
   function defaultInputCaptcha()    {return null;}

   // Word Length

   /**
    * Length of the generated word.
    *
    * @var      integer
    */
   protected $_wordlength = 8;

   /**
    * Length of the generated word.
    */
   function getWordLength()    {return $this->_wordlength;}

   /**
    * Setter method for $_wordlength.
    *
    * @param    integer $value
    */
   function setWordLength($value)    {$this->_wordlength = $value;}

   /**
    * Getter for $_wordlength's default value.
    *
    * @return   integer 8
    */
   function defaultWordLength()    {return 8;}

   // Timeout

   /**
    * Timeout.
    *
    * Time in seconds from the creation of the session token until it is considered to be invalid.
    *
    * @var      integer
    */
   protected $_timeout = 300;

   /**
    * Timeout.
    *
    * Time in seconds from the creation of the session token until it is considered to be invalid.
    */
   function getTimeout()    {return $this->_timeout;}

   /**
    * Setter method for $_timeout.
    *
    * @param    integer $value
    */
   function setTimeout($value)    {$this->_timeout = $value;}

   /**
    * Getter for $_timeout's default value.
    *
    * @return   integer 300
    */
   function defaultTimeout()    {return 300;}

   // Use Numbers

   /**
    * Whether or not to include numbers in the generated word.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_usenumbers = 'false';

   /**
    * Whether or not to include numbers in the generated word.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getUseNumbers()    {return $this->_usenumbers;}

   /**
    * Setter method for $_usenumbers.
    *
    * @param    string  $value
    */
   function setUseNumbers($value)    {$this->_usenumbers = $value;}

   /**
    * Getter for $_usenumbers's default value.
    *
    * @return   string  False ('false')
    */
   function defaultUseNumbers()    {return 'false';}

   // Session Class

   /**
    * Alternative session namespace.
    *
    * @var      string
    */
   protected $_sessionclass = '';

   /**
    * Alternative session namespace.
    */
   function getSessionClass()    {return $this->_sessionclass;}

   /**
    * Setter method for $_sessionclass.
    *
    * @param    string  $value
    */
   function setSessionClass($value)    {$this->_sessionclass = $value;}

   /**
    * Getter for $_sessionclass's default value.
    *
    * @return   string  Empty string
    */
   function defaultSessionClass()    {return '';}

   // Output Width

   /**
    * Maximum width of the output (in pixels).
    *
    * @var      integer
    */
   protected $_outputwidth = 80;

   /**
    * Maximum width of the output (in pixels).
    */
   function getOutputWidth()    {return $this->_outputwidth;}

   /**
    * Setter method for $_outputwidth.
    *
    * @param    integer $value
    */
   function setOutputWidth($value)    {$this->_outputwidth = $value;}

   /**
    * Getter for $_outputwidth's default value.
    *
    * @return   integer 80
    */
   function defaultOutputWidth()    {return 80;}

   // Handle Paragraphs

   /**
    * Whether or not new lines are ignored and treated as white spaces.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_handleparagraphs = 'false';

   /**
    * Whether or not new lines are ignored and treated as white spaces.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getHandleParagraphs()    {return $this->_handleparagraphs;}

   /**
    * Setter method for $_handleparagraphs.
    *
    * @param    string  $value
    */
   function setHandleParagraphs($value)    {$this->_handleparagraphs = $value;}

   /**
    * Getter for $_handleparagraphs's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHandleParagraphs()    {return 'false';}

   // Text Alignment

   /**
    * Text alignment.
    *
    * Possible values are: jtRight, jtCenter, or jtLeft.
    *
    * @var      string
    */
   protected $_justificationtext = jtRight;

   /**
    * Text alignment.
    *
    * Possible values are: jtRight, jtCenter, or jtLeft.
    */
   function getJustificationText()    {return $this->_justificationtext;}

   /**
    * Setter method for $_justificationtext.
    *
    * @param    string  $value
    */
   function setJustificationText($value)    {$this->_justificationtext = $value;}

   /**
    * Getter for $_justificationtext's default value.
    *
    * @return   string  jtRight
    */
   function defaultJustificationText()    {return jtRight;}

   // Text Direction

   /**
    * Text direction.
    *
    * Possible values are: dtRightToLeft, or dtLeftToRight.
    *
    * @var      string
    */
   protected $_directiontext = dtRightToLeft;

   /**
    * Text direction.
    *
    * Possible values are: dtRightToLeft, or dtLeftToRight.
    */
   function getDirectionText()    {return $this->_directiontext;}

   /**
    * Setter method for $_directiontext.
    *
    * @param    string  $value
    */
   function setDirectionText($value)    {$this->_directiontext = $value;}

   /**
    * Getter for $_directiontext's default value.
    *
    * @return   string  dtRightToLeft
    */
   function defaultDirectionText()    {return dtRightToLeft;}

   // Expiration Time

   /**
    * Maximum time the CAPTCHA image may be kept on the filesystem.
    *
    * It is specified in seconds, 0 meaning it will not be deleted.
    *
    * @var      integer
    */
   protected $_expiration = 0;

   /**
    * Maximum time the CAPTCHA image may be kept on the filesystem.
    *
    * It is specified in seconds, 0 meaning it will not be deleted.
    */
   function getExpiration()    {return $this->_expiration;}

   /**
    * Setter method for $_expiration.
    *
    * @param    integer $value
    */
   function setExpiration($value)    {$this->_expiration = $value;}

   /**
    * Getter for $_expiration's default value.
    *
    * @return   integer 0
    */
   function defaultExpiration()    {return 0;}

   // Garbage Frequency

   /**
    * How frequently garbage collection should run.
    *
    * Garbage collection will run once every X times, where X is the value of this property.
    *
    * @var      integer
    */
   protected $_garbagefrecuency = 100;

   /**
    * How frequently garbage collection should run.
    *
    * Garbage collection will run once every X times, where X is the value of this property.
    */
   function getGarbageFrecuency()    {return $this->_garbagefrecuency;}

   /**
    * Setter method for $_garbagefrecuency.
    *
    * @param    integer $value
    */
   function setGarbageFrecuency($value)    {$this->_garbagefrecuency = $value;}

   /**
    * Getter for $_garbagefrecuency's default value.
    *
    * @return   integer 100
    */
   function defaultGarbageFrecuency()    {return 100;}

   // CAPTCHA Font

   /**
    * Font used to generate the CAPTCHA image.
    *
    * This is a required value, and it must contain the path to the font file to be used.
    *
    * @var      string
    */
   protected $_fontcaptcha = '';

   /**
    * Font used to generate the CAPTCHA image.
    *
    * This is a required value, and it must contain the path to the font file to be used.
    */
   function getFontCaptcha()    {return $this->_fontcaptcha;}

   /**
    * Setter method for $_fontcaptcha.
    *
    * @param    string  $value
    */
   function setFontCaptcha($value)    {$this->_fontcaptcha = $value;}

   /**
    * Getter for $_fontcaptcha's default value.
    *
    * @return   string  Empty string
    */
   function defaultFontCaptcha()    {return '';}

   // Font Size

   /**
    * The size of the font used to generate the CAPTCHA image (in pixels).
    *
    * @var      integer
    */
   protected $_fontsize = 24;

   /**
    * The size of the font used to generate the CAPTCHA image (in pixels).
    */
   function getFontSize()    {return $this->_fontsize;}

   /**
    * Setter method for $_fontsize.
    *
    * @param    integer $value
    */
   function setFontSize($value)    {$this->_fontsize = $value;}

   /**
    * Getter for $_fontsize's default value.
    *
    * @return   integer 24
    */
   function defaultFontSize()    {return 24;}

   // Image Directory

   /**
    * Path to the directory where CAPTCHA images should be stored.
    *
    * If an empty string is left, ./images/captcha/ (relative to the bootstrap script) will be used.
    *
    * @var      string
    */
   protected $_imagedir = '';

   /**
    * Path to the directory where CAPTCHA images should be stored.
    *
    * If an empty string is left, ./images/captcha/ (relative to the bootstrap script) will be used.
    */
   function getImageDir()    {return $this->_imagedir;}

   /**
    * Setter method for $_imagedir.
    *
    * @param    string  $value
    */
   function setImageDir($value)    {$this->_imagedir = $value;}

   /**
    * Getter for $_imagedir's default value.
    *
    * @return   string  Empty string
    */
   function defaultImageDir()    {return '';}

   // Image URL

   /**
    * Relative path to a CAPTCHA image.
    *
    * This can be used in HTML markup.
    *
    * If an empty string is left, /images/captcha/ will be used.
    *
    * @var      string
    */
   protected $_imageurl = '';

   /**
    * Relative path to a CAPTCHA image.
    *
    * This can be used in HTML markup.
    *
    * If an empty string is left, /images/captcha/ will be used.
    */
   function getImageUrl()    {return $this->_imageurl;}

   /**
    * Setter method for $_imageurl.
    *
    * @param    string  $value
    */
   function setImageUrl($value)    {$this->_imageurl = $value;}

   /**
    * Getter for $_imageurl's default value.
    *
    * @return   string  Empty string
    */
   function defaultImageUrl()    {return '';}

   // Suffix

   /**
    * Filename suffix for the CAPTCHA image (usually its extension).
    *
    * @var      string
    */
   protected $_suffix = '.png';

   /**
    * Filename suffix for the CAPTCHA image (usually its extension).
    */
   function getSuffix()    {return $this->_suffix;}

   /**
    * Setter method for $_suffix.
    *
    * @param    string  $value
    */
   function setSuffix($value)    {$this->_suffix = $value;}

   /**
    * Getter for $_suffix's default value.
    *
    * @return   string  PNG extension ('.png')
    */
   function defaultSuffix()    {return '.png';}

   // Dot Noise Level

   /**
    * Amount of dots used as noise for the image.
    *
    * @var      integer
    */
   protected $_dotnoiselevel = 100;

   /**
    * Amount of dots used as noise for the image.
    */
   function getDotNoiseLevel()    {return $this->_dotnoiselevel;}

   /**
    * Setter method for $_dotnoiselevel.
    *
    * @param    integer $value
    */
   function setDotNoiseLevel($value)    {$this->_dotnoiselevel = $value;}

   /**
    * Getter for $_dotnoiselevel's default value.
    *
    * @return   integer 100
    */
   function defaultDotNoiseLevel()    {return 100;}

   // Line Noise Level

   /**
    * Amount of lines used as noise for the image.
    *
    * @var      integer
    */
   protected $_linenoiselevel = 5;

   /**
    * Amount of lines used as noise for the image.
    */
   function getLineNoiseLevel()    {return $this->_linenoiselevel;}

   /**
    * Setter method for $_linenoiselevel.
    *
    * @param    integer $value
    */
   function setLineNoiseLevel($value)    {$this->_linenoiselevel = $value;}

   /**
    * Getter for $_linenoiselevel's default value.
    *
    * @return   integer 5
    */
   function defaultLineNoiseLevel()    {return 5;}

   // Private Key

   /**
    * Private key to be used for the reCAPTCHA service.
    *
    * @var      string
    */
   protected $_privatekey = '';

   /**
    * Private key to be used for the reCAPTCHA service.
    */
   function getPrivateKey()    {return $this->_privatekey;}

   /**
    * Setter method for $_privatekey.
    *
    * @param    string  $value
    */
   function setPrivateKey($value)    {$this->_privatekey = $value;}

   /**
    * Getter for $_privatekey's default value.
    *
    * @return   string  Empty string
    */
   function defaultPrivateKey()    {return '';}

   // Public Key

   /**
    * Public key to be used for the reCAPTCHA service.
    *
    * @var      string
    */
   protected $_publickey = '';

   /**
    * Public key to be used for the reCAPTCHA service.
    */
   function getPublicKey()    {return $this->_publickey;}

   /**
    * Setter method for $_publickey.
    *
    * @param    string  $value
    */
   function setPublicKey($value)    {$this->_publickey = $value;}

   /**
    * Getter for $_publickey's default value.
    *
    * @return   string  Empty string
    */
   function defaultPublicKey()    {return '';}

   // reCAPTCHA Theme

   /**
    * Theme to be used for the reCAPTCHA service.
    *
    * Possible values are: trRed, trBlackGlass, trWhite, or trClean.
    *
    * @var      string
    */
   protected $_themerecaptcha = trRed;

   /**
    * Theme to be used for the reCAPTCHA service.
    *
    * Possible values are: trRed, trBlackGlass, trWhite, or trClean.
    */
   function getThemeRecaptcha()    {return $this->_themerecaptcha;}

   /**
    * Setter method for $_themerecaptcha.
    *
    * @param    string  $value
    */
   function setThemeRecaptcha($value)    {$this->_themerecaptcha = $value;}

   /**
    * Getter for $_themerecaptcha's default value.
    *
    * @return   string  trRed
    */
   function defaultThemeRecaptcha()    {return trRed;}

   // reCAPTCHA Language

   /**
    * Language to be used for the reCAPTCHA service.
    *
    * Possible values are: lrEN, lrNL, lrFR, lrDE, lrPT,
    * lrRU, lrES, or lrTR.
    *
    * @var      string
    */
   protected $_languagerecaptcha = lrEN;

   /**
    * Language to be used for the reCAPTCHA service.
    *
    * Possible values are: lrEN, lrNL, lrFR, lrDE, lrPT,
    * lrRU, lrES, or lrTR.
    */
   function getLanguageRecaptcha()    {return $this->_languagerecaptcha;}

   /**
    * Setter method for $_languagerecaptcha.
    *
    * @param    string  $value
    */
   function setLanguageRecaptcha($value)    {$this->_languagerecaptcha = $value;}

   /**
    * Getter for $_languagerecaptcha's default value.
    *
    * @return   string  lrEN
    */
   function defaultLanguageRecaptcha()    {return lrEN;}

   // CAPTCHA Type

   /**
    * Type of CAPTCHA.
    *
    * Possible values are: ctDumb, ctFiglet, ctImage, or
    * ctRecaptcha.
    *
    * @var      string
    */
   protected $_captchatype = ctFiglet;

   /**
    * Type of CAPTCHA.
    *
    * Possible values are: ctDumb, ctFiglet, ctImage, or
    * ctRecaptcha.
    */
   function getCaptchaType()    {return $this->_captchatype;}

   /**
    * Setter method for $_captchatype.
    *
    * @param    string  $value
    */
   function setCaptchaType($value)    {$this->_captchatype = $value;}

   /**
    * Getter for $_captchatype's default value.
    *
    * @return   string  ctFiglet
    */
   function defaultCaptchaType()    {return ctFiglet;}

   // FIGlet Smush Mode

   /**
    * FIGlet smush mode.
    *
    * Defines how the single characters are smushed together.
    *
    * @var      SmushMode
    */
   protected $_smushmode = null;

   /**
    * FIGlet smush mode.
    *
    * Defines how the single characters are smushed together.
    */
   function getSmushMode()    {return $this->_smushmode;}

   /**
    * Setter method for $_smushmode.
    *
    * @param    SmushMode       $value
    */
   function setSmushMode($value)    {if(is_object($value))        {$this->_smushmode = $value;}}

   // Image Alternative Text

   /**
    * Image alternative text.
    *
    * @var      string
    */
   protected $_imagealt = '';

   /**
    * Image alternative text.
    */
   function getImageAlt()    {return $this->_imagealt;}

   /**
    * Setter method for $_imagealt.
    *
    * @param    string  $value
    */
   function setImageAlt($value)    {$this->_imagealt = $value;}

   /**
    * Getter for $_imagealt's default value.
    *
    * @return   string  Empty string
    */
   function defaultImageAlt()    {return '';}

}

/**
 * Smush mode or rule for Figlet CAPTCHAs.
 * 
 * @see ZCaptcha::$_smushmode
 * 
 * @link        http://www.jave.de/figlet/figfont.txt Documentation On Smushing Rules
 */
class SmushMode extends Persistent
{

   // Equal

   /**
    * Equal character smushing.
    *
    * Two sub-characters are smushed into a single sub-character if they are the same. This mode
    * does not smush hardblanks.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_equal = 'false';

   /**
    * Equal character smushing.
    *
    * Two sub-characters are smushed into a single sub-character if they are the same. This mode
    * does not smush hardblanks.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getEqual()    {return $this->_equal;}

   /**
    * Setter method for $_equal.
    *
    * @param    string  $value
    */
   function setEqual($value)    {$this->_equal = $value;}

   /**
    * Getter for $_equal's default value.
    *
    * @return   string  False ('false')
    */
   function defaultEqual()    {return 'false';}

   // Low Line

   /**
    * Underscore smushing.
    *
    * An underscore ("_") will be replaced by any of: "|", "/", "\", "[", "]", "{", "}", "(", ")",
    * "<" or ">".
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_lowline = 'false';

   /**
    * Underscore smushing.
    *
    * An underscore ("_") will be replaced by any of: "|", "/", "\", "[", "]", "{", "}", "(", ")",
    * "<" or ">".
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getLowLine()    {return $this->_lowline;}

   /**
    * Setter method for $_lowline.
    *
    * @param    string  $value
    */
   function setLowLine($value)    {$this->_lowline = $value;}

   /**
    * Getter for $_lowline's default value.
    *
    * @return   string  False ('false')
    */
   function defaultLowLine()    {return 'false';}

   // Hierarchy

   /**
    * Hierarchy smushing.
    *
    * A hierarchy of six classes is used: "|", "/\", "[]", "{}", "()", and "<>". When two smushing
    * sub-characters come from different classes, the one from the latter class will be used.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_hierarchy = 'false';

   /**
    * Hierarchy smushing.
    *
    * A hierarchy of six classes is used: "|", "/\", "[]", "{}", "()", and "<>". When two smushing
    * sub-characters come from different classes, the one from the latter class will be used.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getHierarchy()    {return $this->_hierarchy;}

   /**
    * Setter method for $_hierarchy.
    *
    * @param    string  $value
    */
   function setHierarchy($value)    {$this->_hierarchy = $value;}

   /**
    * Getter for $_hierarchy's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHierarchy()    {return 'false';}

   // Pair

   /**
    * Opposite pair smushing.
    *
    * Smushes opposing brackets ("[]" or "]["), braces ("{}" or "}{") and parentheses ("()" or ")(")
    * together, replacing any such pair with a vertical bar ("|").
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_pair = 'false';

   /**
    * Opposite pair smushing.
    *
    * Smushes opposing brackets ("[]" or "]["), braces ("{}" or "}{") and parentheses ("()" or ")(")
    * together, replacing any such pair with a vertical bar ("|").
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getPair()    {return $this->_pair;}

   /**
    * Setter method for $_pair.
    *
    * @param    string  $value
    */
   function setPair($value)    {$this->_pair = $value;}

   /**
    * Getter for $_pair's default value.
    *
    * @return   string  False ('false')
    */
   function defaultPair()    {return 'false';}

   // Big X

   /**
    * Big X smushing.
    *
    * Smushes "/\" into "|", "\/" into "Y", and "><" into "X". Note that "<>" is not smushed in any
    * way by this mode.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_bigx = 'false';

   /**
    * Big X smushing.
    *
    * Smushes "/\" into "|", "\/" into "Y", and "><" into "X". Note that "<>" is not smushed in any
    * way by this mode.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getBigX()    {return $this->_bigx;}

   /**
    * Setter method for $_bigx.
    *
    * @param    string  $value
    */
   function setBigX($value)    {$this->_bigx = $value;}

   /**
    * Getter for $_bigx's default value.
    *
    * @return   string  False ('false')
    */
   function defaultBigX()    {return 'false';}

   // Hardblank

   /**
    * Hardblank smushing.
    *
    * Smushes two hardblanks together, replacing them with a single hardblank.
    *
    * Check Documentation On Smushing Rules (http://www.jave.de/figlet/figfont.txt) for
    * additional information about hardblanks.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_hardblank = 'false';

   /**
    * Hardblank smushing.
    *
    * Smushes two hardblanks together, replacing them with a single hardblank.
    *
    * Check Documentation On Smushing Rules (http://www.jave.de/figlet/figfont.txt) for
    * additional information about hardblanks.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getHardBlank()    {return $this->_hardblank;}

   /**
    * Setter method for $_hardblank.
    *
    * @param    string  $value
    */
   function setHardBlank($value)    {$this->_hardblank = $value;}

   /**
    * Getter for $_hardblank's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHardBlank()    {return 'false';}

   // Kern

   /**
    * Kerning.
    *
    * Characters are moved closer together until they touch.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_kern = 'false';

   /**
    * Kerning.
    *
    * Characters are moved closer together until they touch.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getKern()    {return $this->_kern;}

   /**
    * Setter method for $_kern.
    *
    * @param    string  $value
    */
   function setKern($value)    {$this->_kern = $value;}

   /**
    * Getter for $_kern's default value.
    *
    * @return   string  False ('false')
    */
   function defaultKern()    {return 'false';}

   // Smush

   /**
    * Smushing.
    *
    * Characters are moved one step closer after they touch.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    *
    * @var      string
    */
   protected $_smush = 'false';

   /**
    * Smushing.
    *
    * Characters are moved one step closer after they touch.
    *
    * Enable this smushing mode with true ('true') or disable it with false ('false').
    */
   function getSmush()    {return $this->_smush;}

   /**
    * Setter method for $_smush.
    *
    * @param    string  $value
    */
   function setSmush($value)    {$this->_smush = $value;}

   /**
    * Getter for $_smush's default value.
    *
    * @return   string  False ('false')
    */
   function defaultSmush()    {return 'false';}

   /**
    * Owner.
    *
    * @var      ZCaptcha
    */
   public $_control = null;
   
   /**
    * Copy smushing configuration into given SmushMode.
    *
    * @param    SmushMode       $dest
    */
   function assignTo($dest)
   {
      $dest->setEqual($this->getEqual());
      $dest->setLowLine($this->getLowLine());
      $dest->setHierarchy($this->getHierarchy());
      $dest->setPair($this->getPair());
      $dest->setBigX($this->getBigX());
      $dest->setHardBlank($this->getHardBlank());
      $dest->setKern($this->getKern());
      $dest->setSmush($this->getSmush());
   }

   // Documented in the parent.
   function readOwner()
   {
      return ($this->_control);
   }

   // Class constructor.
   // TODO: consider using this constructor to assign a passed owner to the $_control variable, or
   // delete the constructor is variable will be assigned through a public property.

   // Documented in the parent.
   function __construct()
   {
      parent::__construct();
   }

   /**
    * Get code for defined smushing modes.
    *
    * @return   integer
    */
   function SmushModeList()
   {
      $smush = 0;

      if($this->_equal == 'true' || $this->_equal == '1')
      {
         $smush |= Zend_Text_Figlet::SM_EQUAL;
      }
      if($this->_lowline == 'true' || $this->_lowline == '1')
      {
         $smush |= Zend_Text_Figlet::SM_LOWLINE;
      }
      if($this->_hierarchy == 'true' || $this->_hierarchy == '1')
      {
         $smush |= Zend_Text_Figlet::SM_HIERARCHY;
      }
      if($this->_pair == 'true' || $this->_pair == '1')
      {
         $smush |= Zend_Text_Figlet::SM_PAIR;
      }
      if($this->_bigx == 'true' || $this->_bigx == '1')
      {
         $smush |= Zend_Text_Figlet::SM_BIGX;
      }
      if($this->_hardblank == 'true' || $this->_hardblank == '1')
      {
         $smush |= Zend_Text_Figlet::SM_HARDBLANK;
      }
      if($this->_kern == 'true' || $this->_kern == '1')
      {
         $smush |= Zend_Text_Figlet::SM_KERN;
      }
      if($this->_smush == 'true' || $this->_smush == '1')
      {
         $smush |= Zend_Text_Figlet::SM_SMUSH;
      }
      if($smush == 0)
      {
         $smush = -1;
      }
      return $smush;

   }
}
?>