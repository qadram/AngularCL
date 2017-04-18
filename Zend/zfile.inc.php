<?php

/**
 * Zend/zfile.inc.php
 *
 * Defines Zend Framework File component.
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
use_unit("classes.inc.php");
use_unit("Zend/framework/library/Zend/File/Transfer/Adapter/Http.php");

/**
 * Base class for the different options classes for ZFile.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.html Zend Framework Documentation
 */
class ZFileOptions extends Persistent
{

    // Owner

   /**
    * Owner.
    *
    * @var      ZFile
    */
   protected $ZFile = null;

   // Documented in the parent.
   function readOwner()
   {
      return ($this->ZFile);
   }

   // Constructor

   /**
    * Class constructor.
    *
    * @param    ZFile   $aowner Owner.
    */
   function __construct($aowner)
   {
      parent::__construct();

      $this->ZFile = $aowner;
   }

   // Enabled

   /**
    * Whether or not options are enabled.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_enabled = 'false';

   /**
    * Whether or not options are enabled.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getEnabled()    {return $this->_enabled;}

   /**
    * Setter method for $_enabled.
    *
    * @param    string  $value
    */
   function setEnabled($value)    {$this->_enabled = $value;}

   /**
    * Getter for $_enabled's default value.
    *
    * @return   string  False ('false')
    */
   function defaultEnabled()    {return 'false';}

}

/**
 * Count validator.
 *
 * This validator checks for the number of files. A minimum and maximum range can be specified. An
 * error will be thrown if either limit is crossed.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileCountValidator extends ZFileOptions
{

   // Minimum

   /**
    * Minimum amount of files.
    *
    * @var      integer
    */
   protected $_min = 1;

   /**
    * Minimum amount of files.
    */
   function getMin()    {return $this->_min;}

   /**
    * Setter method for $_min.
    *
    * @param    integer $value
    */
   function setMin($value)    {$this->_min = $value;}

   /**
    * Getter for $_min's default value.
    *
    * @return   integer 1
    */
   function defaultMin()    {return 1;}

   // Maximum

   /**
    * Maximum amount of files.
    *
    * @var      integer
    */
   protected $_max = 1;

   /**
    * Maximum amount of files.
    */
   function getMax()    {return $this->_max;}

   /**
    * Setter method for $_max.
    *
    * @param    integer $value
    */
   function setMax($value)    {$this->_max = $value;}

   /**
    * Getter for $_max's default value.
    *
    * @return   integer 1
    */
   function defaultMax()    {return 1;}

   // Getter

   /**
    * Returns validator options.
    *
    * The array will contain the following key-pair values:
    * <ul>
    *   <li>min: $_min.</li>
    *   <li>max: $_max.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpValidator()
   {
      $data = array();

      $data['min'] = $this->_min;
      $data['max'] = $this->_max;

      return $data;
   }
}

/**
 * Crc32 validator.
 *
 * This validator checks for the CRC-32 hash value of the content from a file. It is based on the
 * Hash validator and provides a convenient and simple validator that
 * only supports Crc32.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileCrc32Validator extends ZFileOptions
{

   protected $_hashes = array();
   /**
    * Hashes to be validated.
    *
    * @return array
    */
   function getHashes()    {return $this->_hashes;}
   function setHashes($value)    {$this->_hashes = $value;}
   function defaultHashes()    {return array();}

   // Getter

   /**
    * Returns validator hashes.
    *
    * @return   array
    */
   function dumpValidator()
   {
      return $this->_hashes;
   }
}

/**
 * Exclude Extension validator.
 *
 * This validator checks the extension of files. It will throw an error when a given file has a
 * defined extension.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileExcludeExtensionValidator extends ZFileOptions
{

   protected $_extensions = array();
   /**
    * Extensions to be excluded.
    *
    * @return array
    */
   function getExtensions()    {return $this->_extensions;}
   function setExtensions($value)    {$this->_extensions = $value;}
   function defaultExtensions()    {return array();}

   // Case

   /**
    * Whether or not extension checking should be case-sensitive.
    *
    * If set to true ('true'), for example, you could exclude 'jpg' extension, yet 'JPG', 'Jpg' or
    * other extensions with some letter uppercased would still be allowed.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_case = 'false';

   /**
    * Whether or not extension checking should be case-sensitive.
    *
    * If set to true ('true'), for example, you could exclude 'jpg' extension, yet 'JPG', 'Jpg' or
    * other extensions with some letter uppercased would still be allowed.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getCase()    {return $this->_case;}

   /**
    * Setter method for $_case.
    *
    * @param    string  $value
    */
   function setCase($value)    {$this->_case = $value;}

   /**
    * Getter for $_case's default value.
    *
    * @return   string  False ('false')
    */
   function defaultCase()    {return 'false';}

   /**
    * Returns extensions to be excluded, plus whether or not they should be treated as
    * case-sensitive.
    *
    * @return   array
    */
   function dumpValidator()
   {
      if($this->_case == 'true' || $this->_case == 1)
      {
         $this->_extensions['case'] = true;
      }
      else
      {
         $this->_extensions['case'] = false;
      }

      return $this->_extensions;
   }
}

/**
 * Exclude MIME Type validator.
 *
 * This validator checks the MIME type of files and throws an error if the MIME type of specified
 * file matches.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileExcludeMimeTypeValidator extends ZFileOptions
{

   // Header Check

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    *
    * @var      string
    */
   protected $_headercheck = 'false';

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    */
   function getHeaderCheck()    {return $this->_headercheck;}

   /**
    * Setter method for $_headercheck.
    *
    * @param    string  $value
    */
   function setHeaderCheck($value)    {$this->_headercheck = $value;}

   /**
    * Getter for $_headercheck's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHeaderCheck()    {return 'false';}

   protected $_mimetypes = array();
   /**
    * MIME types to be excluded.
    *
    * @return array
    */
   function getMimeTypes()    {return $this->_mimetypes;}
   function setMimeTypes($value)    {$this->_mimetypes = $value;}
   function defaultMimeTypes()    {return array();}

   /**
    * Returns MIME types to be excluded, plus whether or not file MIME type should be detremined
    * from the HTTP header data.
    *
    * @return   array
    */
   function dumpValidator()
   {
      if($this->_headercheck == 'false' || $this->_headercheck == 0)
      {
         $this->_mimetypes['headerCheck'] = false;
      }
      else
      {
         $this->_mimetypes['headerCheck'] = true;
      }
      return $this->_mimetypes;
   }
}

/**
 * Exists validator.
 *
 * This validator checks for the existence of given files in defined directories. It will throw an
 * error if a specified file does not exist in defined directories.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileExistsValidator extends ZFileOptions
{

   protected $_directories = array();
   /**
    * Paths of directories where files should be looked for.
    *
    * @return      array
    */
   function getDirectories()    {return $this->_directories;}
   function setDirectories($value)    {$this->_directories = $value;}
   function defaultDirectories()    {return array();}

   /**
    * Returns directories's paths.
    *
    * @return   array
    */
   function dumpValidator()
   {
      return $this->_directories;
   }
}

/**
 * Exclude Extension validator.
 *
 * This validator checks the extension of files. It will throw an error when a specified file has an
 * undefined extension.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileExtensionValidator extends ZFileOptions
{

   protected $_extensions = array();
   /**
    * Extensions to be allowed.
    *
    * @return array
    */
   function getExtensions()    {return $this->_extensions;}
   function setExtensions($value)    {$this->_extensions = $value;}
   function defaultExtensions()    {return array();}

   // Case

   /**
    * Whether or not extension checking should be case-sensitive.
    *
    * If set to true ('true'), for example, you could include 'jpg' extension, yet 'JPG', 'Jpg' or
    * other extensions with some letter uppercased would still be denied.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_case = 'false';

   /**
    * Whether or not extension checking should be case-sensitive.
    *
    * If set to true ('true'), for example, you could include 'jpg' extension, yet 'JPG', 'Jpg' or
    * other extensions with some letter uppercased would still be denied.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getCase()    {return $this->_case;}

   /**
    * Setter method for $_case.
    *
    * @param    string  $value
    */
   function setCase($value)    {$this->_case = $value;}

   /**
    * Getter for $_case's default value.
    *
    * @return   string  False ('false')
    */
   function defaultCase()    {return 'false';}

   /**
    * Returns extensions to be allowed, plus whether or not they should be treated as
    * case-sensitive.
    *
    * @return   array
    */
   function dumpValidator()
   {
      if($this->_case == 'false' || $this->_case == 0)
      {
         $this->_extensions['case'] = false;
      }
      else
      {
         $this->_extensions['case'] = true;
      }
      return $this->_extensions;
   }
}

/**
 * Files Size validator.
 *
 * This validator checks the size of validated files. It remembers internally the size of all
 * checked files and throws an error when the sum of all specified files exceed the defined size.
 *
 * It provides both minimum and maximum values.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileFilesSizeValidator extends ZFileOptions
{

   // Minimum

   /**
    * Minimum total size for files.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    *
    * @var      integer|string
    */
   protected $_min = 0;

   /**
    * Minimum total size for files.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    */
   function getMin()    {return $this->_min;}

   /**
    * Setter method for $_min.
    *
    * @param    integer|string  $value
    */
   function setMin($value)    {$this->_min = $value;}

   /**
    * Getter for $_min's default value.
    *
    * @return   integer|string  0
    */
   function defaultMin()    {return 0;}

   // Maximum

   /**
    * Maximum total size for files.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    *
    * @var      integer|string
    */
   protected $_max = 0;

   /**
    * Maximum total size for files.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    */
   function getMax()    {return $this->_max;}

   /**
    * Setter method for $_max.
    *
    * @param    integer|string  $value
    */
   function setMax($value)    {$this->_max = $value;}

   /**
    * Getter for $_max's default value.
    *
    * @return   integer|string  1
    */
   function defaultMax()    {return 0;}


   // Byte String

   /**
    * Whether or not values for minimum and maximum total filesize
    * should be read as bytestrings, that is, with units.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * If set to true, for example, you would be able to specify '1kB' instead of 1024 for both
    * $_min and $_max properties.
    *
    * @var      string
    */
   protected $_bytestring = 'false';

   /**
    * Whether or not values for minimum and maximum total filesize
    * should be read as bytestrings, that is, with units.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * If set to true, for example, you would be able to specify '1kB' instead of 1024 for both
    * $_min and $_max properties.
    */
   function getByteString()    {return $this->_bytestring;}

   /**
    * Setter method for $_bytestring.
    *
    * @param    string  $value
    */
   function setByteString($value)    {$this->_bytestring = $value;}

   /**
    * Getter for $_bytestring's default value.
    *
    * @return   string  False ('false')
    */
   function defaultByteString()    {return 'false';}

   // Getter

   /**
    * Returns validator options.
    *
    * The array will contain the following key-pair values:
    * <ul>
    *   <li>min: $_min.</li>
    *   <li>max: $_max.</li>
    *   <li>bytestring: $_bytestring.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpValidator()
   {
      $data = array();

      $data['min'] = $this->_min;
      $data['max'] = $this->_max;
      if($this->_bytestring == 'false' || $this->_bytestring == 0)
         $data['bytestring'] = false;
      else
         $data['bytestring'] = true;

      return $data;
   }
}

/**
 * Image Size validator.
 *
 * This validator checks image size. It validates the width and height and enforces minimum and
 * maximum dimensions.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileImageSizeValidator extends ZFileOptions
{

   // Minimum Height

   /**
    * Minimum height (in pixels).
    *
    * @var      integer
    */
   protected $_minheight = 0;

   /**
    * Minimum height (in pixels).
    */
   function getMinHeight()    {return $this->_minheight;}

   /**
    * Setter method for $_minheight.
    *
    * @param    integer $value
    */
   function setMinHeight($value)    {$this->_minheight = $value;}

   /**
    * Getter for $_minheight's default value.
    *
    * @return   integer 0
    */
   function defaultMinHeight()    {return 0;}

   // Maximum Height

   /**
    * Maximum height (in pixels).
    *
    * @var      integer
    */
   protected $_maxheight = 0;

   /**
    * Maximum height (in pixels).
    */
   function getMaxHeight()    {return $this->_maxheight;}

   /**
    * Setter method for $_maxheight.
    *
    * @param    integer $value
    */
   function setMaxHeight($value)    {$this->_maxheight = $value;}

   /**
    * Getter for $_maxheight's default value.
    *
    * @return   integer 0
    */
   function defaultMaxHeight()    {return 0;}

   // Minimum Width

   /**
    * Minimum width (in pixels).
    *
    * @var      integer
    */
   protected $_minwidth = 0;

   /**
    * Minimum width (in pixels).
    */
   function getMinWidth()    {return $this->_minwidth;}

   /**
    * Setter method for $_minwidth.
    *
    * @param    integer $value
    */
   function setMinWidth($value)    {$this->_minwidth = $value;}

   /**
    * Getter for $_minwidth's default value.
    *
    * @return   integer 0
    */
   function defaultMinWidth()    {return 0;}

   // Maximum Width

   /**
    * Maximum width (in pixels).
    *
    * @var      integer
    */
   protected $_maxwidth = 0;

   /**
    * Maximum width (in pixels).
    */
   function getMaxWidth()    {return $this->_maxwidth;}

   /**
    * Setter method for $_maxwidth.
    *
    * @param    integer $value
    */
   function setMaxWidth($value)    {$this->_maxwidth = $value;}

   /**
    * Getter for $_maxwidth's default value.
    *
    * @return   integer 0
    */
   function defaultMaxWidth()    {return 0;}

   // Getter

   /**
    * Returns validator options.
    *
    * The array will contain the following key-pair values:
    * <ul>
    *   <li>minheight: $_minheight.</li>
    *   <li>maxheight: $_maxheight.</li>
    *   <li>minwidth: $_minwidth.</li>
    *   <li>maxwidth: $_maxwidth.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpValidator()
   {
      $data = array();

      $data['minheight'] = $this->_minheight;
      $data['maxheight'] = $this->_maxheight;
      $data['minwidth'] = $this->_minwidth;
      $data['maxwidth'] = $this->_maxwidth;

      return $data;
   }
}

/**
 * Is Compressed validator.
 *
 * This validator checks whether the file is compressed. It is based on the
 * MimeType validator and validates for compression archives like zip
 * or arc. You can also limit it to other archives.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileIsCompressedValidator extends ZFileOptions
{

   protected $_compressedtypes = array();
   /**
    * MIME types (of compressed archives) to be allowed.
    *
    * @return array
    */
   function getCompressedTypes()    {return $this->_compressedtypes;}
   function setCompressedTypes($value)    {$this->_compressedtypes = $value;}
   function defaultCompressedTypes()    {return array();}

   // Header Check

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    *
    * @var      string
    */
   protected $_headercheck = 'false';

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    */
   function getHeaderCheck()    {return $this->_headercheck;}

   /**
    * Setter method for $_headercheck.
    *
    * @param    string  $value
    */
   function setHeaderCheck($value)    {$this->_headercheck = $value;}

   /**
    * Getter for $_headercheck's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHeaderCheck()    {return 'false';}

   /**
    * Returns specific MIME types to be excluded (if any, defaults to compressed archives MIME
    * types), plus whether or not file MIME type should be detremined from the HTTP header data.
    *
    * @return   array
    */
   function dumpValidator()
   {
      if($this->_headercheck == 0)
      {
         $this->_compressedtypes['headerCheck'] = false;
      }
      else
      {
         $this->_compressedtypes['headerCheck'] = true;
      }

      return $this->_compressedtypes;
   }
}

/**
 * Is Image validator.
 *
 * This validator checks whether the file is an image. It is based on the
 * MimeType validator and validates for image files like JPEG or GIF.
 * You can also limit it to other image types.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileIsImageValidator extends ZFileOptions
{

   protected $_imagetypes = array();
   /**
    * MIME types (of image files) to be allowed.
    *
    * @return array
    */
   function getImageTypes()    {return $this->_imagetypes;}
   function setImageTypes($value)    {$this->_imagetypes = $value;}
   function defaultImageTypes()    {return array();}

   // Header Check

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    *
    * @var      string
    */
   protected $_headercheck = 'false';

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    */
   function getHeaderCheck()    {return $this->_headercheck;}

   /**
    * Setter method for $_headercheck.
    *
    * @param    string  $value
    */
   function setHeaderCheck($value)    {$this->_headercheck = $value;}

   /**
    * Getter for $_headercheck's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHeaderCheck()    {return 'false';}

   /**
    * Returns specific MIME types to be excluded (if any, defaults to image files MIME types), plus
    * whether or not file MIME type should be detremined from the HTTP header data.
    *
    * @return   array
    */
   function dumpValidator()
   {
      if($this->_headercheck == 0)
      {
         $this->_imagetypes['headerCheck'] = false;
      }
      else
      {
         $this->_imagetypes['headerCheck'] = true;
      }
      return $this->_imagetypes;
   }
}

// Hash Algorithms

define('ahMd2', 'ahMd2');
define('ahMd4', 'ahMd4');
define('ahMd5', 'ahMd5');
define('ahSha1', 'ahSha1');
define('ahSha224', 'ahSha224');
define('ahSha256', 'ahSha256');
define('ahSha384', 'ahSha384');
define('ahSha512', 'ahSha512');
define('ahRipemd128', 'ahRipemd128');
define('ahRipemd160', 'ahRipemd160');
define('ahRipemd256', 'ahRipemd256');
define('ahRipemd320', 'ahRipemd320');
define('ahSalsa10', 'ahSalsa10');
define('ahSalsa20', 'ahSalsa20');
define('ahWhirlpool', 'ahWhirlpool');
define('ahTiger128_3', 'ahTiger128_3');
define('ahTiger160_3', 'ahTiger160_3');
define('ahTiger192_3', 'ahTiger192_3');
define('ahTiger128_4', 'ahTiger128_4');
define('ahTiger160_4', 'ahTiger160_4');
define('ahTiger192_4', 'ahTiger192_4');
define('ahSnefru', 'ahSnefru');
define('ahSnefru256', 'ahSnefru256');
define('ahGost', 'ahGost');
define('ahAdler32', 'ahAdler32');
define('ahCrc32', 'ahCrc32');
define('ahCrc32b', 'ahCrc32b');
define('ahHaval128_3', 'ahHaval128_3');
define('ahHaval160_3', 'ahHaval160_3');
define('ahHaval192_3', 'ahHaval192_3');
define('ahHaval224_3', 'ahHaval224_3');
define('ahHaval256_3', 'ahHaval256_3');
define('ahHaval128_4', 'ahHaval128_4');
define('ahHaval160_4', 'ahHaval160_4');
define('ahHaval192_4', 'ahHaval192_4');
define('ahHaval224_4', 'ahHaval224_4');
define('ahHaval256_4', 'ahHaval256_4');
define('ahHaval128_5', 'ahHaval128_5');
define('ahHaval160_5', 'ahHaval160_5');
define('ahHaval192_5', 'ahHaval192_5');
define('ahHaval224_5', 'ahHaval224_5');
define('ahHaval256_5', 'ahHaval256_5');

/**
 * Hash validator.
 *
 * This validator checks the hash value of the content from a file. It supports multiple algorithms.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileHashValidator extends ZFileOptions
{

   protected $_hashes = array();
   /**
    * Hashes to be validated.
    *
    * @return array
    */
   function getHashes()    {return $this->_hashes;}
   function setHashes($value)    {$this->_hashes = $value;}
   function defaultHashes()    {return array();}

   // Hash Algorithm

   /**
    * Hash algorithm.
    *
    * @var      string
    */
   protected $_algorithmhash = ahMd5;

   /**
    * Hash algorithm.
    */
   function getAlgorithmHash()    {return $this->_algorithmhash;}

   /**
    * Setter method for $_algorithmhash.
    *
    * @param    string  $value
    */
   function setAlgorithmHash($value)    {$this->_algorithmhash = $value;}

   /**
    * Getter for $_algorithmhash's default value.
    *
    * @return   string  ahMd5
    */
   function defaultAlgorithmHash()    {return ahMd5;}

   // Getter

   /**
    * Returns validator hashes and chosen algorithm.
    *
    * @return   array
    */
   function dumpValidator()
   {
      $algorithm = '';
      switch($this->_algorithmhash)
      {
         case ahMd2:
            $algorithm = 'md2';
            break;
         case ahMd4:
            $algorithm = 'md4';
            break;
         case ahMd5:
            $algorithm = 'md5';
            break;
         case ahSha1:
            $algorithm = 'sha1';
            break;
         case ahSha224:
            $algorithm = 'sha224';
            break;
         case ahSha256:
            $algorithm = 'sha256';
            break;
         case ahSha384:
            $algorithm = 'sha384';
            break;
         case ahSha512:
            $algorithm = 'sha512';
            break;
         case ahRipemd128:
            $algorithm = 'ripemd128';
            break;
         case ahRipemd160:
            $algorithm = 'ripemd160';
            break;
         case ahRipemd256:
            $algorithm = 'ripemd256';
            break;
         case ahRipemd320:
            $algorithm = 'ripemd320';
            break;
         case ahSalsa10:
            $algorithm = 'salsa10';
            break;
         case ahSalsa20:
            $algorithm = 'salsa20';
            break;
         case ahWhirlpool:
            $algorithm = 'whirlpool';
            break;
         case ahTiger128_3:
            $algorithm = 'tiger128,3';
            break;
         case ahTiger160_3:
            $algorithm = 'tiger160,3';
            break;
         case ahTiger192_3:
            $algorithm = 'tiger192,3';
            break;
         case ahTiger128_4:
            $algorithm = 'tiger128,4';
            break;
         case ahTiger160_4:
            $algorithm = 'tiger160,4';
            break;
         case ahTiger192_4:
            $algorithm = 'tiger192,4';
            break;
         case ahSnefru:
            $algorithm = 'snefru';
            break;
         case ahSnefru256:
            $algorithm = 'snefru256';
            break;
         case ahGost:
            $algorithm = 'gost';
            break;
         case ahAdler32:
            $algorithm = 'adler32';
            break;
         case ahCrc32:
            $algorithm = 'crc32';
            break;
         case ahCrc32b:
            $algorithm = 'crc32b';
            break;
         case ahHaval128_3:
            $algorithm = 'haval128,3';
            break;
         case ahHaval160_3:
            $algorithm = 'haval160,3';
            break;
         case ahHaval192_3:
            $algorithm = 'haval192,3';
            break;
         case ahHaval224_3:
            $algorithm = 'haval224,3';
            break;
         case ahHaval256_3:
            $algorithm = 'haval256,3';
            break;
         case ahHaval128_4:
            $algorithm = 'haval128,4';
            break;
         case ahHaval160_4:
            $algorithm = 'haval160,4';
            break;
         case ahHaval192_4:
            $algorithm = 'haval192,4';
            break;
         case ahHaval224_4:
            $algorithm = 'haval224,4';
            break;
         case ahHaval256_4:
            $algorithm = 'haval256,4';
            break;
         case ahHaval128_5:
            $algorithm = 'haval128,5';
            break;
         case ahHaval160_5:
            $algorithm = 'haval160,5';
            break;
         case ahHaval192_5:
            $algorithm = 'haval192,5';
            break;
         case ahHaval224_5:
            $algorithm = 'haval224,5';
            break;
         case ahHaval256_5:
            $algorithm = 'haval256,5';
            break;
      }
      $this->_hashes['algorithm'] = $algorithm;

      return $this->_hashes;
   }

}

/**
 * MD5 validator.
 *
 * This validator checks for the MD5 hash value of the content from a file. It is based on the
 * Hash validator and provides a convenient and simple validator that
 * only supports MD5.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileMD5Validator extends ZFileOptions
{

   protected $_hashes = array();
   /**
    * Hashes to be validated.
    *
    * @return array
    */
   function getHashes()    {return $this->_hashes;}
   function setHashes($value)    {$this->_hashes = $value;}
   function defaultHashes()    {return array();}

   // Getter

   /**
    * Returns validator hashes.
    *
    * @return   array
    */
   function dumpValidator()
   {
      return $this->_hashes;
   }
}

/**
 * MIME Type validator.
 *
 * This validator checks the MIME type of files and throws an error if the MIME type of specified
 * file does not match.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileMimeTypeValidator extends ZFileOptions
{

   protected $_mimetypes = array();
   /**
    * MIME types to be allowed.
    *
    * @return array
    */
   function getMimeTypes()    {return $this->_mimetypes;}
   function setMimeTypes($value)    {$this->_mimetypes = $value;}
   function defaultMimeTypes()    {return array();}

   // Header Check

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    *
    * @var      string
    */
   protected $_headercheck = 'false';

   /**
    * Whether or not the HTTP header should be used to determine file MIME type.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * This option is considered unsafe, you should probably leave it set to false ('false').
    */
   function getHeaderCheck()    {return $this->_headercheck;}

   /**
    * Setter method for $_headercheck.
    *
    * @param    string  $value
    */
   function setHeaderCheck($value)    {$this->_headercheck = $value;}

   /**
    * Getter for $_headercheck's default value.
    *
    * @return   string  False ('false')
    */
   function defaultHeaderCheck()    {return 'false';}

   // Magic File

   /**
    * Path to the MIME magic file (magic.mime).
    *
    * @var      string
    */
   protected $_magicfile = '';

   /**
    * Path to the MIME magic file (magic.mime).
    */
   function getMagicFile()    {return $this->_magicfile;}

   /**
    * Setter method for $_magicfile.
    *
    * @param    string  $value
    */
   function setMagicFile($value)    {$this->_magicfile = $value;}

   /**
    * Getter for $_magicfile's default value.
    *
    * @return   string  Empty string
    */
   function defaultMagicFile()    {return '';}

   /**
    * Returns MIME types to be allowed, plus whether or not file MIME type should be detremined
    * from the HTTP header data, and the path to the MIME magic file if specified.
    *
    * @return   array
    */
   function dumpValidator()
   {
      if($this->_headercheck == 'false' || $this->_headercheck == 0)
      {
         $this->_mimetypes['headerCheck'] = false;
      }
      else
      {
         $this->_mimetypes['headerCheck'] = true;
      }

      $this->_mimetypes['magicfile'] = $this->_magicfile;

      return $this->_mimetypes;
   }

}

/**
 * Not Exists validator.
 *
 * This validator checks for the existence of given files in defined directories. It will throw an
 * error if a specified file already exists in defined directories.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileNotExistsValidator extends ZFileOptions
{

   protected $_directories = array();
   /**
    * Paths of directories where files should be looked for.
    *
    * @return array
    */
   function getDirectories()    {return $this->_directories;}
   function setDirectories($value)    {$this->_directories = $value;}
   function defaultDirectories()    {return array();}

   /**
    * Returns directories's paths.
    *
    * @return   array
    */
   function dumpValidator()
   {
      return $this->_directories;
   }

}

/**
 * SHA-1 validator.
 *
 * This validator checks for the SHA-1 hash value of the content from a file. It is based on the
 * Hash validator and provides a convenient and simple validator that
 * only supports SHA-1.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileSHA1Validator extends ZFileOptions
{

   protected $_hashes = array();

   /**
    * Hashes to be validated.
    *
    * @return array
    */
   function getHashes()    {return $this->_hashes;}
   function setHashes($value)    {$this->_hashes = $value;}
   function defaultHashes()    {return array();}

   // Getter

   /**
    * Returns validator hashes.
    *
    * @return   array
    */
   function dumpValidator()
   {
      return $this->_hashes;
   }

}

/**
 * Size validator.
 *
 * This validator is able to check files for its file size. It provides a minimum and maximum size
 * range and will throw an error when either of these thresholds are crossed.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileSizeValidator extends ZFileOptions
{

   // Minimum

   /**
    * Minimum size.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    *
    * @var      integer|string
    */
   protected $_min = 0;

   /**
    * Minimum size.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    */
   function getMin()    {return $this->_min;}

   /**
    * Setter method for $_min.
    *
    * @param    integer|string  $value
    */
   function setMin($value)    {$this->_min = $value;}

   /**
    * Getter for $_min's default value.
    *
    * @return   integer|string  0
    */
   function defaultMin()    {return 0;}

   // Maximum

   /**
    * Maximum size.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    *
    * @var      integer|string
    */
   protected $_max = 0;

   /**
    * Maximum size.
    *
    * Value will be treated as an amount of bytes. If you set $_bytestring to true ('true'),
    * value can be a string, and when so its unit will be taken into account. For example, you would
    * be able to specify '1kB' instead of 1024.
    */
   function getMax()    {return $this->_max;}

   /**
    * Setter method for $_max.
    *
    * @param    integer|string  $value
    */
   function setMax($value)    {$this->_max = $value;}

   /**
    * Getter for $_max's default value.
    *
    * @return   integer|string  1
    */
   function defaultMax()    {return 0;}


   // Byte String

   /**
    * Whether or not values for minimum and maximum filesize should be
    * read as bytestrings, that is, with units.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * If set to true, for example, you would be able to specify '1kB' instead of 1024 for both
    * $_min and $_max properties.
    *
    * @var      string
    */
   protected $_bytestring = 'false';

   /**
    * Whether or not values for minimum and maximum filesize should be
    * read as bytestrings, that is, with units.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * If set to true, for example, you would be able to specify '1kB' instead of 1024 for both
    * $_min and $_max properties.
    */
   function getByteString()    {return $this->_bytestring;}

   /**
    * Setter method for $_bytestring.
    *
    * @param    string  $value
    */
   function setByteString($value)    {$this->_bytestring = $value;}

   /**
    * Getter for $_bytestring's default value.
    *
    * @return   string  False ('false')
    */
   function defaultByteString()    {return 'false';}

   // Getter

   /**
    * Returns validator options.
    *
    * The array will contain the following key-pair values:
    * <ul>
    *   <li>min: $_min.</li>
    *   <li>max: $_max.</li>
    *   <li>bytestring: $_bytestring.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpValidator()
   {
      $data = array();

      $data['min'] = $this->_min;
      $data['max'] = $this->_max;
      if($this->_bytestring == 'false' || $this->_bytestring == 0)
      {
         $data['bytestring'] = false;
      }
      else
      {
         $data['bytestring'] = true;
      }

      return $data;
   }
}

/**
 * Word Count validator.
 *
 * This validator is able to check the number of words within files. It provides a minimum and
 * maximum count and will throw an error when either of these thresholds are crossed.
 *
 * It provides both minimum and maximum values.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.validators.html Zend Framework Documentation
 */
class ZFileWordCountValidator extends ZFileOptions
{

   // Minimum

   /**
    * Minimum amount of words.
    *
    * @var      integer
    */
   protected $_min = 0;

   /**
    * Minimum amount of words.
    */
   function getMin()    {return $this->_min;}

   /**
    * Setter method for $_min.
    *
    * @param    integer $value
    */
   function setMin($value)    {$this->_min = $value;}

   /**
    * Getter for $_min's default value.
    *
    * @return   integer 0
    */
   function defaultMin()    {return 0;}

   // Maximum

   /**
    * Maximum amount of words.
    *
    * @var      integer
    */
   protected $_max = 0;

   /**
    * Maximum amount of words.
    */
   function getMax()    {return $this->_max;}

   /**
    * Setter method for $_max.
    *
    * @param    integer $value
    */
   function setMax($value)    {$this->_max = $value;}

   /**
    * Getter for $_max's default value.
    *
    * @return   integer 0
    */
   function defaultMax()    {return 0;}

   // Getter

   /**
    * Returns validator options.
    *
    * The array will contain the following key-pair values:
    * <ul>
    *   <li>min: $_min.</li>
    *   <li>max: $_max.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpValidator()
   {
      $data = array();

      $data['min'] = $this->_min;
      $data['max'] = $this->_max;

      return $data;
   }
}

// Decrypt Adapters

define('daOpenssl', 'daOpenssl');
define('daMcrypt', 'daMcrypt');

// Mcrypt Algorithms

define('ma3DES', 'ma3DES');
define('maArcFour_IV', 'maArcFour_IV');
define('maArcFour', 'maArcFour');
define('maBlowfish', 'maBlowfish');
define('maCast128', 'maCast128');
define('maCast256', 'maCast256');
define('maCrypt', 'maCrypt');
define('maDes', 'maDes');
define('maDesCompat', 'maDesCompat');
define('maEnigma', 'maEnigma');
define('maGost', 'maGost');
define('maIdea', 'maIdea');
define('maLoki97', 'maLoki97');
define('maMars', 'maMars');
define('maPanama', 'maPanama');
define('maRijndael128', 'maRijndael128');
define('maRijndael192', 'maRijndael192');
define('maRijndael256', 'maRijndael256');
define('maRC2', 'maRC2');
define('maRC4', 'maRC4');
define('maRC6', 'maRC6');
define('maRC6_128', 'maRC6_128');
define('maRC6_192', 'maRC6_192');
define('maRC6_256', 'maRC6_256');
define('maSafer64', 'maSafer64');
define('maSafer128', 'maSafer128');
define('maSaferPlus', 'maSaferPlus');
define('maSerpent', 'maSerpent');
define('maSerpent_128', 'maSerpent_128');
define('maSerpent_192', 'maSerpent_192');
define('maSerpent_256', 'maSerpent_256');
define('maSkipjack', 'maSkipjack');
define('maTean', 'maTean');
define('maThreeway', 'maThreeway');
define('maTripleDes', 'maTripleDes');
define('maTwoFish', 'maTwoFish');
define('maTwoFish128', 'maTwoFish128');
define('maTwoFish192', 'maTwoFish192');
define('maTwoFish256', 'maTwoFish256');
define('maWake', 'maWake');
define('maXtea', 'maXtea');

// Mcrypt Modes

define('mmECB', 'mmECB');
define('mmCBC', 'mmCBC');
define('mmCFB', 'mmCFB');
define('mmOFB', 'mmOFB');
define('mmNOFB', 'mmNOFB');
define('mmSTREAM', 'mmSTREAM');

/**
 * Decrypt filter.
 *
 * This filter can decrypt a encrypted file.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.filters.html Zend Framework Documentation
 */
class ZFileDecryptFilter extends ZFileOptions
{

   // Decrypt Adapter

   /**
    * Decrypt adapter.
    *
    * @var      string
    */
   protected $_decryptadapter = daMcrypt;

   /**
    * Decrypt adapter.
    */
   function getDecryptAdapter()    {return $this->_decryptadapter;}

   /**
    * Setter method for $_decryptadapter.
    *
    * @param    string  $value
    */
   function setDecryptAdapter($value)    {$this->_decryptadapter = $value;}

   /**
    * Getter for $_decryptadapter's default value.
    *
    * @return   string  daMcrypt
    */
   function defaultDecryptAdapter()    {return daMcrypt;}

   // Mcrypt Key

   /**
    * Mcrypt key.
    *
    * @var      string
    */
   protected $_mcryptkey = '';

   /**
    * Mcrypt key.
    */
   function getMcryptKey()    {return $this->_mcryptkey;}

   /**
    * Setter method for $_mcryptkey.
    *
    * @param    string  $value
    */
   function setMcryptKey($value)    {$this->_mcryptkey = $value;}

   /**
    * Getter for $_mcryptkey's default value.
    *
    * @return   string  Empty string
    */
   function defaultMcryptKey()    {return '';}

   // Mcrypt Algorithm

   /**
    * Mcrypt algorithm.
    *
    * @var      string
    */
   protected $_mcryptalgorithm = ma3DES;

   /**
    * Mcrypt algorithm.
    */
   function getMcryptAlgorithm()    {return $this->_mcryptalgorithm;}

   /**
    * Setter method for $_mcryptalgorithm.
    *
    * @param    string  $value
    */
   function setMcryptAlgorithm($value)    {$this->_mcryptalgorithm = $value;}

   /**
    * Getter for $_mcryptalgorithm's default value.
    *
    * @return   string  ma3DES
    */
   function defaultMcryptAlgorithm()    {return ma3DES;}

   // Mcrypt Mode

   /**
    * Mcrypt mode.
    *
    * @var      string
    */
   protected $_mcryptmode = mmECB;

   /**
    * Mcrypt mode.
    */
   function getMcryptMode()    {return $this->_mcryptmode;}

   /**
    * Setter method for $_mcryptmode.
    *
    * @param    string  $value
    */
   function setMcryptMode($value)    {$this->_mcryptmode = $value;}

   /**
    * Getter for $_mcryptmode's default value.
    *
    * @return   string  mmECB
    */
   function defaultMcryptMode()    {return mmECB;}

   // Mcrypt Vector

   /**
    * Mcrypt vector.
    *
    * @var      string
    */
   protected $_mcryptvector = '';

   /**
    * Mcrypt vector.
    */
   function getMcryptVector()    {return $this->_mcryptvector;}

   /**
    * Setter method for $_mcryptvector.
    *
    * @param    string  $value
    */
   function setMcryptVector($value)    {$this->_mcryptvector = $value;}

   /**
    * Getter for $_mcryptvector's default value.
    *
    * @return   string  Empty string
    */
   function defaultMcryptVector()    {return '';}

   // Mcrypt Salt

   /**
    * Whether or not a salt value is needed.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_mcryptsalt = 'false';

   /**
    * Whether or not a salt value is needed.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getMcryptSalt()    {return $this->_mcryptsalt;}

   /**
    * Setter method for $_mcryptsalt.
    *
    * @param    string  $value
    */
   function setMcryptSalt($value)    {$this->_mcryptsalt = $value;}

   /**
    * Getter for $_mcryptsalt's default value.
    *
    * @return   string  False ('false')
    */
   function defaultMcryptSalt()    {return 'false';}

   // OpenSSL Public Key

   /**
    * OpenSSL public key.
    *
    * @var      string
    */
   protected $_opensslpublickey = '';

   /**
    * OpenSSL public key.
    */
   function getOpensslPublicKey()    {return $this->_opensslpublickey;}

   /**
    * Setter method for $_opensslpublickey.
    *
    * @param    string  $value
    */
   function setOpensslPublicKey($value)    {$this->_opensslpublickey = $value;}

   /**
    * Getter for $_opensslpublickey's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslPublicKey()    {return '';}

   // OpenSSL Private Key

   /**
    * OpenSSL private key.
    *
    * @var      string
    */
   protected $_opensslprivatekey = '';

   /**
    * OpenSSL private key.
    */
   function getOpensslPrivateKey()    {return $this->_opensslprivatekey;}

   /**
    * Setter method for $_opensslprivatekey.
    *
    * @param    string  $value
    */
   function setOpensslPrivateKey($value)    {$this->_opensslprivatekey = $value;}

   /**
    * Getter for $_opensslprivatekey's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslPrivateKey()    {return '';}

   // OpenSSL Envelop

   /**
    * OpenSSL envelop.
    *
    * @var      string
    */
   protected $_opensslenvelope = '';

   /**
    * OpenSSL envelop.
    */
   function getOpensslEnvelope()    {return $this->_opensslenvelope;}

   /**
    * Setter method for $_opensslenvelope.
    *
    * @param    string  $value
    */
   function setOpensslEnvelope($value)    {$this->_opensslenvelope = $value;}

   /**
    * Getter for $_opensslenvelope's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslEnvelope()    {return '';}

   // OpenSSL Passphrase

   /**
    * OpenSSL passphrase.
    *
    * @var      string
    */
   protected $_opensslpassphrase = '';

   /**
    * OpenSSL passphrase.
    */
   function getOpensslPassphrase()    {return $this->_opensslpassphrase;}

   /**
    * Setter method for $_opensslpassphrase.
    *
    * @param    string  $value
    */
   function setOpensslPassphrase($value)    {$this->_opensslpassphrase = $value;}

   /**
    * Getter for $_opensslpassphrase's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslPassphrase()    {return '';}

   // Getter

   /**
    * Returns filter options.
    *
    * The array will contain the following key-pair values when $_decryptadapter is set to
    * daOpenssl:
    * <ul>
    *   <li>adapter: 'openssl'.</li>
    *   <li>public: $_opensslpublickey.</li>
    *   <li>private: $_opensslprivatekey.</li>
    *   <li>envelope: $_opensslenvelope.</li>
    *   <li>passphrase: $_opensslpassphrase.</li>
    * </ul>
    *
    * When $_decryptadapter is set to daMcrypt, the array will contain the following
    * key-pair values instead:
    * <ul>
    *   <li>adapter: 'mcrypt'.</li>
    *   <li>key: $_mcryptkey.</li>
    *   <li>algorithm: $_mcryptalgorithm.</li>
    *   <li>mode: $_mcryptmode.</li>
    *   <li>vector: $_mcryptvector.</li>
    *   <li>salt: $_mcryptsalt.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpFilter()
   {
      $data = array();

      switch($this->_decryptadapter)
      {
         case daOpenssl:
            $data['public'] = $this->_opensslpublickey;
            $data['private'] = $this->_opensslprivatekey;
            $data['envelope'] = $this->_opensslenvelope;
            $data['passphrase'] = $this->_opensslpassphrase;
            $data['adapter'] = 'openssl';
            break;
         case daMcrypt:
            $data['adapter'] = 'mcrypt';
            $data['key'] = $this->_mcryptkey;
            $algorithm = '';
            switch($this->_mcryptalgorithm)
            {
               case ma3DES:
                  $algorithm = MCRYPT_3DES;
                  break;
               case maArcFour_IV:
                  $algorithm = MCRYPT_ARCFOUR_IV;
                  break;
               case maArcFour:
                  $algorithm = MCRYPT_ARCFOUR;
                  break;
               case maBlowfish:
                  $algorithm = MCRYPT_BLOWFISH;
                  break;
               case maCast128:
                  $algorithm = MCRYPT_CAST_128;
                  break;
               case maCast256:
                  $algorithm = MCRYPT_CAST_256;
                  break;
               case maCrypt:
                  $algorithm = MCRYPT_CRYPT;
                  break;
               case maDes:
                  $algorithm = MCRYPT_DES;
                  break;
               case maDesCompat:
                  $algorithm = MCRYPT_DES_COMPAT;
                  break;
               case maEnigma:
                  $algorithm = MCRYPT_ENIGMA;
                  break;
               case maGost:
                  $algorithm = MCRYPT_GOST;
                  break;
               case maIdea:
                  $algorithm = MCRYPT_IDEA;
                  break;
               case maLoki97:
                  $algorithm = MCRYPT_LOKI97;
                  break;
               case maMars:
                  $algorithm = MCRYPT_MARS;
                  break;
               case maPanama:
                  $algorithm = MCRYPT_PANAMA;
                  break;
               case maRijndael128:
                  $algorithm = MCRYPT_RIJNDAEL_128;
                  break;
               case maRijndael192:
                  $algorithm = MCRYPT_RIJNDAEL_192;
                  break;
               case maRijndael256:
                  $algorithm = MCRYPT_RIJNDAEL_256;
                  break;
               case maRC2:
                  $algorithm = MCRYPT_RC2;
                  break;
               case maRC4:
                  $algorithm = MCRYPT_RC4;
                  break;
               case maRC6:
                  $algorithm = MCRYPT_RC6;
                  break;
               case maRC6_128:
                  $algorithm = MCRYPT_RC6_128;
                  break;
               case maRC6_192:
                  $algorithm = MCRYPT_RC6_192;
                  break;
               case maRC6_256:
                  $algorithm = MCRYPT_RC6_256;
                  break;
               case maSafer64:
                  $algorithm = MCRYPT_SAFER_64;
                  break;
               case maSafer128:
                  $algorithm = MCRYPT_SAFER_128;
                  break;
               case maSaferPlus:
                  $algorithm = MCRYPT_SAFER_PLUS;
                  break;
               case maSerpent:
                  $algorithm = MCRYPT_SERPENT;
                  break;
               case maSerpent_128:
                  $algorithm = MCRYPT_SERPENT_128;
                  break;
               case maSerpent_192:
                  $algorithm = MCRYPT_SERPENT_192;
                  break;
               case maSerpent_256:
                  $algorithm = MCRYPT_SERPENT_256;
                  break;
               case maSkipjack:
                  $algorithm = MCRYPT_SKIPJACK;
                  break;
               case maTean:
                  $algorithm = MCRYPT_TEAN;
                  break;
               case maThreeway:
                  $algorithm = MCRYPT_THREEWAY;
                  break;
               case maTripleDes:
                  $algorithm = MCRYPT_TRIPLEDES;
                  break;
               case maTwoFish:
                  $algorithm = MCRYPT_TWOFISH;
                  break;
               case maTwoFish128:
                  $algorithm = MCRYPT_TWOFISH_128;
                  break;
               case maTwoFish192:
                  $algorithm = MCRYPT_TWOFISH_192;
                  break;
               case maTwoFish256:
                  $algorithm = MCRYPT_TWOFISH_256;
                  break;
               case maWake:
                  $algorithm = MCRYPT_WAKE;
                  break;
               case maXtea:
                  $algorithm = MCRYPT_XTEA;
                  break;
            }
            $data['algorithm'] = $algorithm;

            $mode = '';
            switch($this->_mcryptmode)
            {
               case mmECB:
                  $mode = MCRYPT_MODE_ECB;
                  break;
               case mmCBC:
                  $mode = MCRYPT_MODE_CBC;
                  break;
               case mmCFB:
                  $mode = MCRYPT_MODE_CFB;
                  break;
               case mmOFB:
                  $mode = MCRYPT_MODE_OFB;
                  break;
               case mmNOFB:
                  $mode = MCRYPT_MODE_NOFB;
                  break;
               case mmSTREAM:
                  $mode = MCRYPT_MODE_STREAM;
                  break;
            }
            $data['mode'] = $mode;
            $data['vector'] = $this->_mcryptvector;
            if($this->_mcryptsalt == 'false' || $this->_mcryptsalt == 0)
            {
               $data['salt'] = false;
            }
            else
            {
               $data['salt'] = true;
            }
            break;
      }

      return $data;
   }



}

// Encrypt Adapters

define('eaOpenssl', 'eaOpenssl');
define('eaMcrypt', 'eaMcrypt');

/**
 * Encrypt filter.
 *
 * This filter can encrypt a file.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.filters.html Zend Framework Documentation
 */
class ZFileEncryptFilter extends ZFileOptions
{

   // Encrypt Adapter

   /**
    * Encrypt adapter.
    *
    * @var      string
    */
   protected $_encryptadapter = eaMcrypt;

   /**
    * Encrypt adapter.
    */
   function getEncryptAdapter()    {return $this->_encryptadapter;}

   /**
    * Setter method for $_encryptadapter.
    *
    * @param    string  $value
    */
   function setEncryptAdapter($value)    {$this->_encryptadapter = $value;}

   /**
    * Getter for $_encryptadapter's default value.
    *
    * @return   string  eaMcrypt
    */
   function defaultEncryptAdapter()    {return eaMcrypt;}

   // Mcrypt Key

   /**
    * Mcrypt key.
    *
    * @var      string
    */
   protected $_mcryptkey = '';

   /**
    * Mcrypt key.
    */
   function getMcryptKey()    {return $this->_mcryptkey;}

   /**
    * Setter method for $_mcryptkey.
    *
    * @param    string  $value
    */
   function setMcryptKey($value)    {$this->_mcryptkey = $value;}

   /**
    * Getter for $_mcryptkey's default value.
    *
    * @return   string  Empty string
    */
   function defaultMcryptKey()    {return '';}

   // Mcrypt Algorithm

   /**
    * Mcrypt algorithm.
    *
    * @var      string
    */
   protected $_mcryptalgorithm = ma3DES;

   /**
    * Mcrypt algorithm.
    */
   function getMcryptAlgorithm()    {return $this->_mcryptalgorithm;}

   /**
    * Setter method for $_mcryptalgorithm.
    *
    * @param    string  $value
    */
   function setMcryptAlgorithm($value)    {$this->_mcryptalgorithm = $value;}

   /**
    * Getter for $_mcryptalgorithm's default value.
    *
    * @return   string  ma3DES
    */
   function defaultMcryptAlgorithm()    {return ma3DES;}

   // Mcrypt Mode

   /**
    * Mcrypt mode.
    *
    * @var      string
    */
   protected $_mcryptmode = mmECB;

   /**
    * Mcrypt mode.
    */
   function getMcryptMode()    {return $this->_mcryptmode;}

   /**
    * Setter method for $_mcryptmode.
    *
    * @param    string  $value
    */
   function setMcryptMode($value)    {$this->_mcryptmode = $value;}

   /**
    * Getter for $_mcryptmode's default value.
    *
    * @return   string  mmECB
    */
   function defaultMcryptMode()    {return mmECB;}

   // Mcrypt Vector

   /**
    * Mcrypt vector.
    *
    * @var      string
    */
   protected $_mcryptvector = '';

   /**
    * Mcrypt vector.
    */
   function getMcryptVector()    {return $this->_mcryptvector;}

   /**
    * Setter method for $_mcryptvector.
    *
    * @param    string  $value
    */
   function setMcryptVector($value)    {$this->_mcryptvector = $value;}

   /**
    * Getter for $_mcryptvector's default value.
    *
    * @return   string  Empty string
    */
   function defaultMcryptVector()    {return '';}

   // Mcrypt Salt

   /**
    * Whether or not a salt value is needed.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_mcryptsalt = 'false';

   /**
    * Whether or not a salt value is needed.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getMcryptSalt()    {return $this->_mcryptsalt;}

   /**
    * Setter method for $_mcryptsalt.
    *
    * @param    string  $value
    */
   function setMcryptSalt($value)    {$this->_mcryptsalt = $value;}

   /**
    * Getter for $_mcryptsalt's default value.
    *
    * @return   string  False ('false')
    */
   function defaultMcryptSalt()    {return 'false';}

   // OpenSSL Public Key

   /**
    * OpenSSL public key.
    *
    * @var      string
    */
   protected $_opensslpublickey = '';

   /**
    * OpenSSL public key.
    */
   function getOpensslPublicKey()    {return $this->_opensslpublickey;}

   /**
    * Setter method for $_opensslpublickey.
    *
    * @param    string  $value
    */
   function setOpensslPublicKey($value)    {$this->_opensslpublickey = $value;}

   /**
    * Getter for $_opensslpublickey's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslPublicKey()    {return '';}

   // OpenSSL Private Key

   /**
    * OpenSSL private key.
    *
    * @var      string
    */
   protected $_opensslprivatekey = '';

   /**
    * OpenSSL private key.
    */
   function getOpensslPrivateKey()    {return $this->_opensslprivatekey;}

   /**
    * Setter method for $_opensslprivatekey.
    *
    * @param    string  $value
    */
   function setOpensslPrivateKey($value)    {$this->_opensslprivatekey = $value;}

   /**
    * Getter for $_opensslprivatekey's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslPrivateKey()    {return '';}

   // OpenSSL Envelop

   /**
    * OpenSSL envelop.
    *
    * @var      string
    */
   protected $_opensslenvelope = '';

   /**
    * OpenSSL envelop.
    */
   function getOpensslEnvelope()    {return $this->_opensslenvelope;}

   /**
    * Setter method for $_opensslenvelope.
    *
    * @param    string  $value
    */
   function setOpensslEnvelope($value)    {$this->_opensslenvelope = $value;}

   /**
    * Getter for $_opensslenvelope's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslEnvelope()    {return '';}

   // OpenSSL Passphrase

   /**
    * OpenSSL passphrase.
    *
    * @var      string
    */
   protected $_opensslpassphrase = '';

   /**
    * OpenSSL passphrase.
    */
   function getOpensslPassphrase()    {return $this->_opensslpassphrase;}

   /**
    * Setter method for $_opensslpassphrase.
    *
    * @param    string  $value
    */
   function setOpensslPassphrase($value)    {$this->_opensslpassphrase = $value;}

   /**
    * Getter for $_opensslpassphrase's default value.
    *
    * @return   string  Empty string
    */
   function defaultOpensslPassphrase()    {return '';}

   // Getter

   /**
    * Returns filter options.
    *
    * The array will contain the following key-pair values when $_decryptadapter is set to
    * daOpenssl:
    * <ul>
    *   <li>adapter: 'openssl'.</li>
    *   <li>public: $_opensslpublickey.</li>
    *   <li>private: $_opensslprivatekey.</li>
    *   <li>envelope: $_opensslenvelope.</li>
    *   <li>passphrase: $_opensslpassphrase.</li>
    * </ul>
    *
    * When $_decryptadapter is set to daMcrypt, the array will contain the following
    * key-pair values instead:
    * <ul>
    *   <li>adapter: 'mcrypt'.</li>
    *   <li>key: $_mcryptkey.</li>
    *   <li>algorithm: $_mcryptalgorithm.</li>
    *   <li>mode: $_mcryptmode.</li>
    *   <li>vector: $_mcryptvector.</li>
    *   <li>salt: $_mcryptsalt.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpFilter()
   {
      $data = array();

      switch($this->_encryptadapter)
      {
         case eaOpenssl:
            $data['public'] = $this->_opensslpublickey;
            $data['private'] = $this->_opensslprivatekey;
            $data['envelope'] = $this->_opensslenvelope;
            $data['passphrase'] = $this->_opensslpassphrase;
            $data['adapter'] = 'openssl';
            break;
         case eaMcrypt:
            $data['adapter'] = 'mcrypt';
            $data['key'] = $this->_mcryptkey;
            $algorithm = '';
            switch($this->_mcryptalgorithm)
            {
               case ma3DES:
                  $algorithm = MCRYPT_3DES;
                  break;
               case maArcFour_IV:
                  $algorithm = MCRYPT_ARCFOUR_IV;
                  break;
               case maArcFour:
                  $algorithm = MCRYPT_ARCFOUR;
                  break;
               case maBlowfish:
                  $algorithm = MCRYPT_BLOWFISH;
                  break;
               case maCast128:
                  $algorithm = MCRYPT_CAST_128;
                  break;
               case maCast256:
                  $algorithm = MCRYPT_CAST_256;
                  break;
               case maCrypt:
                  $algorithm = MCRYPT_CRYPT;
                  break;
               case maDes:
                  $algorithm = MCRYPT_DES;
                  break;
               case maDesCompat:
                  $algorithm = MCRYPT_DES_COMPAT;
                  break;
               case maEnigma:
                  $algorithm = MCRYPT_ENIGMA;
                  break;
               case maGost:
                  $algorithm = MCRYPT_GOST;
                  break;
               case maIdea:
                  $algorithm = MCRYPT_IDEA;
                  break;
               case maLoki97:
                  $algorithm = MCRYPT_LOKI97;
                  break;
               case maMars:
                  $algorithm = MCRYPT_MARS;
                  break;
               case maPanama:
                  $algorithm = MCRYPT_PANAMA;
                  break;
               case maRijndael128:
                  $algorithm = MCRYPT_RIJNDAEL_128;
                  break;
               case maRijndael192:
                  $algorithm = MCRYPT_RIJNDAEL_192;
                  break;
               case maRijndael256:
                  $algorithm = MCRYPT_RIJNDAEL_256;
                  break;
               case maRC2:
                  $algorithm = MCRYPT_RC2;
                  break;
               case maRC4:
                  $algorithm = MCRYPT_RC4;
                  break;
               case maRC6:
                  $algorithm = MCRYPT_RC6;
                  break;
               case maRC6_128:
                  $algorithm = MCRYPT_RC6_128;
                  break;
               case maRC6_192:
                  $algorithm = MCRYPT_RC6_192;
                  break;
               case maRC6_256:
                  $algorithm = MCRYPT_RC6_256;
                  break;
               case maSafer64:
                  $algorithm = MCRYPT_SAFER_64;
                  break;
               case maSafer128:
                  $algorithm = MCRYPT_SAFER_128;
                  break;
               case maSaferPlus:
                  $algorithm = MCRYPT_SAFER_PLUS;
                  break;
               case maSerpent:
                  $algorithm = MCRYPT_SERPENT;
                  break;
               case maSerpent_128:
                  $algorithm = MCRYPT_SERPENT_128;
                  break;
               case maSerpent_192:
                  $algorithm = MCRYPT_SERPENT_192;
                  break;
               case maSerpent_256:
                  $algorithm = MCRYPT_SERPENT_256;
                  break;
               case maSkipjack:
                  $algorithm = MCRYPT_SKIPJACK;
                  break;
               case maTean:
                  $algorithm = MCRYPT_TEAN;
                  break;
               case maThreeway:
                  $algorithm = MCRYPT_THREEWAY;
                  break;
               case maTripleDes:
                  $algorithm = MCRYPT_TRIPLEDES;
                  break;
               case maTwoFish:
                  $algorithm = MCRYPT_TWOFISH;
                  break;
               case maTwoFish128:
                  $algorithm = MCRYPT_TWOFISH_128;
                  break;
               case maTwoFish192:
                  $algorithm = MCRYPT_TWOFISH_192;
                  break;
               case maTwoFish256:
                  $algorithm = MCRYPT_TWOFISH_256;
                  break;
               case maWake:
                  $algorithm = MCRYPT_WAKE;
                  break;
               case maXtea:
                  $algorithm = MCRYPT_XTEA;
                  break;
            }
            $data['algorithm'] = $algorithm;

            $mode = '';
            switch($this->_mcryptmode)
            {
               case mmECB:
                  $mode = MCRYPT_MODE_ECB;
                  break;
               case mmCBC:
                  $mode = MCRYPT_MODE_CBC;
                  break;
               case mmCFB:
                  $mode = MCRYPT_MODE_CFB;
                  break;
               case mmOFB:
                  $mode = MCRYPT_MODE_OFB;
                  break;
               case mmNOFB:
                  $mode = MCRYPT_MODE_NOFB;
                  break;
               case mmSTREAM:
                  $mode = MCRYPT_MODE_STREAM;
                  break;
            }
            $data['mode'] = $mode;
            $data['vector'] = $this->_mcryptvector;
            if($this->_mcryptsalt == 'false' || $this->_mcryptsalt == 0)
            {
               $data['salt'] = false;
            }
            else
            {
               $data['salt'] = true;
            }
            break;
      }

      return $data;
   }
}

/**
 * Lowercase filter.
 *
 * This filter can lowercase the content of a text file.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.filters.html Zend Framework Documentation
 */
class ZFileLowerCaseFilter extends ZFileOptions
{

   // Encoding

   /**
    * Character encoding.
    *
    * @var      string
    */
   protected $_encoding = '';

   /**
    * Character encoding.
    */
   function getEncoding()    {return $this->_encoding;}

   /**
    * Setter method for $_encoding.
    *
    * @param    string  $value
    */
   function setEncoding($value)    {$this->_encoding = $value;}

   /**
    * Getter for $_encoding's default value.
    *
    * @return   string  Empty string
    */
   function defaultEncoding()    {return '';}

   // Getter

   /**
    * Returns file encoding.
    *
    * @return   string
    */
   function dumpFilter()
   {
      return $this->_encoding;
   }

}

/**
 * Uppercase filter.
 *
 * This filter can uppercase the content of a text file.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.filters.html Zend Framework Documentation
 */
class ZFileUpperCaseFilter extends ZFileOptions
{

   // Encoding

   /**
    * Character encoding.
    *
    * @var      string
    */
   protected $_encoding = '';

   /**
    * Character encoding.
    */
   function getEncoding()    {return $this->_encoding;}

   /**
    * Setter method for $_encoding.
    *
    * @param    string  $value
    */
   function setEncoding($value)    {$this->_encoding = $value;}

   /**
    * Getter for $_encoding's default value.
    *
    * @return   string  Empty string
    */
   function defaultEncoding()    {return '';}

   // Getter

   /**
    * Returns file encoding.
    *
    * @return   string
    */
   function dumpFilter()
   {
      return $this->_encoding;
   }
}

/**
 * Rename filter.
 *
 * This filter can rename files, change the location and even force overwriting of existing files.
 *
 * @link        http://framework.zend.com/manual/en/zend.file.transfer.filters.html Zend Framework Documentation
 */
class ZFileRenameFilter extends ZFileOptions
{

   // Target

   /**
    * Target filepath.
    *
    * @var      string
    */
   protected $_target = '';

   /**
    * Target filepath.
    */
   function getTarget()    {return $this->_target;}

   /**
    * Setter method for $_target.
    *
    * @param    string  $value
    */
   function setTarget($value)    {$this->_target = $value;}

   /**
    * Getter for $_target's default value.
    *
    * @return   string  Empty string
    */
   function defaultTarget()    {return '';}

   // Source

   /**
    * Source filepath.
    *
    * @var      string
    */
   protected $_source = '';

   /**
    * Source filepath.
    */
   function getSource()    {return $this->_source;}

   /**
    * Setter method for $_source.
    *
    * @param    string  $value
    */
   function setSource($value)    {$this->_source = $value;}

   /**
    * Getter for $_source's default value.
    *
    * @return   string  Empty string
    */
   function defaultSource()    {return '';}

   // Overwrite

   /**
    * Whether or not to allow overwriting a previous file located at the
    * target path.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_overwrite = 'false';

   /**
    * Whether or not to allow overwriting a previous file located at the
    * target path.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getOverwrite()    {return $this->_overwrite;}

   /**
    * Setter method for $_overwrite.
    *
    * @param    string  $value
    */
   function setOverwrite($value)    {$this->_overwrite = $value;}

   /**
    * Getter for $_overwrite's default value.
    *
    * @return   string  False ('false')
    */
   function defaultOverwrite()    {return 'false';}

   // Getter

   /**
    * Returns filter options.
    *
    * The array will contain the following key-pair values:
    * <ul>
    *   <li>target: $_target.</li>
    *   <li>source: $_source.</li>
    *   <li>overwrite: $_overwrite.</li>
    * </ul>
    *
    * @return   array
    */
   function dumpFilter()
   {
      $data = array();

      $data['target'] = $this->_target;
      $data['source'] = $this->_source;
      if($this->_overwrite == 'false' || $this->_overwrite == 0)
      {
         $data['overwrite'] = false;
      }
      else
      {
         $data['overwrite'] = true;
      }

      return $data;
   }

}

/**
 * Component to work with uploaded files.
 *
 * It lets you apply some validation rules and filters to an uploaded file.
 *
 * @link        http://framework.zend.com/manual/en/zend.feed.writer.html Zend Framework Documentation
 */
class ZFile extends Component
{
   /**
    * Zend Framework File Transfer Adapter HTTP instance.
    *
    * @var      Zend_File_Transfer_Adapter_Http
    */
   private $_adapter = null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);

      //Validator properties
      $this->_countvalidator = new ZFileCountValidator($this);
      $this->_crc32validator = new ZFileCrc32Validator($this);
      $this->_excludeextensionvalidator = new ZFileExcludeExtensionValidator($this);
      $this->_excludemimetypevalidator = new ZFileExcludeMimeTypeValidator($this);
      $this->_existsvalidator = new ZFileExistsValidator($this);
      $this->_extensionvalidator = new ZFileExtensionValidator($this);
      $this->_filessizevalidator = new ZFileSizeValidator($this);
      $this->_imagesizevalidator = new ZFileImageSizeValidator($this);
      $this->_iscompressedvalidator = new ZFileIsCompressedValidator($this);
      $this->_isimagevalidator = new ZFileIsImageValidator($this);
      $this->_hashvalidator = new ZFileHashValidator($this);
      $this->_md5validator = new ZFileMD5Validator($this);
      $this->_mimetypevalidator = new ZFileMimeTypeValidator($this);
      $this->_notexistsvalidator = new ZFileNotExistsValidator($this);
      $this->_sha1validator = new ZFileSHA1Validator($this);
      $this->_sizevalidator = new ZFileSizeValidator($this);
      $this->_wordcountvalidator = new ZFileWordCountValidator($this);

      //Filter properties
      $this->_decryptfilter = new ZFileDecryptFilter($this);
      $this->_encryptfilter = new ZFileEncryptFilter($this);
      $this->_lowercasefilter = new ZFileLowerCaseFilter($this);
      $this->_uppercasefilter = new ZFileUpperCaseFilter($this);
      $this->_renamefilter = new ZFileRenameFilter($this);

   }

   /**
    * Generator for $_adapter.
    *
    * Generates a Zend Framework File Transfer Adapter HTTP
    * object from those properties set for this ZFile instance (or defaults), and saves it
    * to $_adapter.
    */
   function CreateFile()
   {
      $this->_adapter = new Zend_File_Transfer_Adapter_Http();

      if(($this->ControlState & csDesigning) == csDesigning)

      {
         $this->_destination = $this->guessTempFolder();
         $this->_destination.="/";
      }
      else{

        $this->_adapter->setDestination($this->_destination);
      }

      $options = array();
      if($this->_ignorenofile == 0 || $this->_ignorenofile=='false')
      {
         $options['ignoreNoFile'] = 'false';
      }
      else
      {
         $options['ignoreNoFile'] = 'true';
      }

      $this->_adapter->setOptions($options);

      $validators = array();
      if($this->_countvalidator->getEnabled() == 1)
      {
         $validators['Count'] = $this->_countvalidator->dumpValidator();
      }
      if($this->_crc32validator->getEnabled() == 1)
      {
         $validators['Crc32'] = $this->_crc32validator->dumpValidator();
      }
      if($this->_excludeextensionvalidator->getEnabled() == 1)
      {
         $validators['ExcludeExtension'] = $this->_excludeextensionvalidator->dumpValidator();
      }

      if($this->_excludemimetypevalidator->getEnabled() == 1)
      {
         $validators['ExcludeMimeType'] = $this->_excludemimetypevalidator->dumpValidator();
      }

      if($this->_existsvalidator->getEnabled() == 1)
      {
         $validators['Exists'] = $this->_existsvalidator->dumpValidator();
      }

      if($this->_extensionvalidator->getEnabled() == 1)
      {
         $validators['Extension'] = $this->_extensionvalidator->dumpValidator();
      }

      if($this->_filessizevalidator->getEnabled() == 1)
      {
         $validators['FilesSize'] = $this->_filessizevalidator->dumpValidator();
      }

      if($this->_imagesizevalidator->getEnabled() == 1)
      {
         $validators['ImageSize'] = $this->_imagesizevalidator->dumpValidator();
      }

      if($this->_iscompressedvalidator->getEnabled() == 1)
      {
         $validators['IsCompressed'] = $this->_iscompressedvalidator->dumpValidator();
      }

      if($this->_isimagevalidator->getEnabled() == 1)
      {
         $validators['IsImage'] = $this->_isimagevalidator->dumpValidator();
      }

      if($this->_hashvalidator->getEnabled() == 1)
      {
         $validators['Hash'] = $this->_hashvalidator->dumpValidator();
      }

      if($this->_md5validator->getEnabled() == 1)
      {
         $validators['Md5'] = $this->_md5validator->dumpValidator();
      }

      if($this->_mimetypevalidator->getEnabled() == 1)
      {
         $validators['MimeType'] = $this->_mimetypevalidator->dumpValidator();
      }

      if($this->_notexistsvalidator->getEnabled() == 1)
      {
         $validators['NotExists'] = $this->_notexistsvalidator->dumpValidator();
      }

      if($this->_sha1validator->getEnabled() == 1)
      {
         $validators['Sha1'] = $this->_sha1validator->dumpValidator();
      }

      if($this->_sizevalidator->getEnabled() == 1)
      {
         $validators['Size'] = $this->_sizevalidator->dumpValidator();
      }

      if($this->_wordcountvalidator->getEnabled() == 1)
      {
         $validators['WordCount'] = $this->_wordcountvalidator->dumpValidator();
      }

      $filters = array();

      if($this->_decryptfilter->getEnabled() == 1)
      {
         $filters['Decrypt'] = $this->_decryptfilter->dumpFilter();
      }

      if($this->_encryptfilter->getEnabled() == 1)
      {
         $filters['Encrypt'] = $this->_encryptfilter->dumpFilter();
      }

      if($this->_lowercasefilter->getEnabled() == 1)
      {
         $filters['LowerCase'] = $this->_lowercasefilter->dumpFilter();
      }

      if($this->_uppercasefilter->getEnabled() == 1)
      {
         $filters['UpperCase'] = $this->_uppercasefilter->dumpFilter();
      }

      if($this->RenameFilter->getEnabled() == 1)
      {
         $filters['Rename'] = $this->_renamefilter->dumpFilter();
      }

      $this->_adapter->setValidators($validators);
      $this->_adapter->setFilters($filters);

   }

   /**
    * Checks if files pass validators's rules.
    *
    * @return   boolean
    */
   function isValid()
   {
      return $this->_adapter->isValid();
   }

   /**
    * Checks if files have been uploaded (client-side).
    *
    * @return   boolean
    */
   function isUploaded()
   {
      return $this->_adapter->isUploaded();
   }

   /**
    * Checks if files have already been received (server-side).
    *
    * @return   boolean
    */
   function isReceived()
   {
      return $this->_adapter->isReceived();
   }

   /**
    * Returns the messages generated from filters and validators.
    *
    * @return   array
    */
   function findMessages()
   {
      return $this->_adapter->getMessages();
   }

   /**
    * Retrieves the filename of transferred files.
    *
    * @return   array
    */
   function findFileName()
   {
      return $this->_adapter->getFileName();
   }

   /**
    * Retrieve additional internal file information for files.
    *
    * @return   array
    */
   function findFileInfo()
   {
      return $this->_adapter->getFileInfo();
   }

   /**
    * Returns files sizes.
    *
    * @return   array
    */
   function findFileSize()
   {
      return $this->_adapter->getFileSize();
   }

   /**
    * Returns files hashes.
    *
    * @param    string  $algorithmhash  Hash algorithm.
    * @return   array
    */
   function findHash($algorithmhash)
   {
      return $this->_adapter->getHash($algorithmhash);
   }

   /**
    * Returns transferred files MIME types.
    *
    * @return   array
    */
   function findMimeType()
   {
      return $this->_adapter->getMimeType();

   }

   // Count

   /**
    * Count validator.
    *
    * @var      ZFileCountValidator
    */
   protected $_countvalidator = null;

   /**
    * Count validator.
    */
   function getCountValidator()    {return $this->_countvalidator;}

   /**
    * Setter method for $_countvalidator.
    *
    * @param    ZFileCountValidator     $value
    */
   function setCountValidator($value)    {if(is_object($value))        {$this->_countvalidator = $value;}}

   /**
    * Getter for $_countvalidator's default value.
    *
    * @return   ZFileCountValidator     Null
    */
   function defaultCountValidator()    {return null;}

   // Crc32

   /**
    * CRC-32 validator.
    *
    * @var      ZFileCrc32Validator
    */
   protected $_crc32validator = null;

   /**
    * CRC-32 validator.
    */
   function getCrc32Validator()    {return $this->_crc32validator;}

   /**
    * Setter method for $_crc32validator.
    *
    * @param    ZFileCrc32Validator     $value
    */
   function setCrc32Validator($value)    {if(is_object($value))        {$this->_crc32validator = $value;}}

   /**
    * Getter for $_crc32validator's default value.
    *
    * @return   ZFileCrc32Validator     Null
    */
   function defaultCrc32Validator()    {return null;}

   // Exclude Extension

   /**
    * Exclude Extension validator.
    *
    * @var      ZFileExcludeExtensionValidator
    */
   protected $_excludeextensionvalidator = null;

   /**
    * Exclude Extension validator.
    */
   function getExcludeExtensionValidator()    {return $this->_excludeextensionvalidator;}

   /**
    * Setter method for $_excludeextensionvalidator.
    *
    * @param    ZFileExcludeExtensionValidator  $value
    */
   function setExcludeExtensionValidator($value)    {if(is_object($value))        {$this->_excludeextensionvalidator = $value;}}

   /**
    * Getter for $_excludeextensionvalidator's default value.
    *
    * @return   ZFileExcludeExtensionValidator  Null
    */
   function defaultExcludeExtensionValidator()    {return null;}

   // Exclude MIME Type

   /**
    * Exclude MIME Type validator.
    *
    * @var      ZFileExcludeMimeTypeValidator
    */
   protected $_excludemimetypevalidator = null;

   /**
    * Exclude MIME Type validator.
    */
   function getExcludeMimeTypeValidator()    {return $this->_excludemimetypevalidator;}

   /**
    * Setter method for $_excludemimetypevalidator.
    *
    * @param    ZFileExcludeMimeTypeValidator   $value
    */
   function setExcludeMimeTypeValidator($value)    {if(is_object($value))        {$this->_excludemimetypevalidator = $value;}}

   /**
    * Getter for $_excludemimetypevalidator's default value.
    *
    * @return   ZFileExcludeMimeTypeValidator   Null
    */
   function defaultExcludeMimeTypeValidator()    {return null;}

   // Exists

   /**
    * Exists validator.
    *
    * @var      ZFileExistsValidator
    */
   protected $_existsvalidator = null;

   /**
    * Exists validator.
    */
   function getExistsValidator()    {return $this->_existsvalidator;}

   /**
    * Setter method for $_existsvalidator.
    *
    * @param    ZFileExistsValidator    $value
    */
   function setExistsValidator($value)    {if(is_object($value))        {$this->_existsvalidator = $value;}}

   /**
    * Getter for $_existsvalidator's default value.
    *
    * @return   ZFileExistsValidator    Null
    */
   function defaultExistsValidator()    {return null;}

   // Extension

   /**
    * Extension validator.
    *
    * @var      ZFileExtensionValidator
    */
   protected $_extensionvalidator = null;

   /**
    * Extension validator.
    */
   function getExtensionValidator()    {return $this->_extensionvalidator;}

   /**
    * Setter method for $_extensionvalidator.
    *
    * @param    ZFileExtensionValidator $value
    */
   function setExtensionValidator($value)    {if(is_object($value))        {$this->_extensionvalidator = $value;}}

   /**
    * Getter for $_extensionvalidator's default value.
    *
    * @return   ZFileExtensionValidator Null
    */
   function defaultExtensionValidator()    {return null;}

   // File Size

   /**
    * File Size validator.
    *
    * @var      ZFileFilesSizeValidator
    */
   protected $_filessizevalidator = null;

   /**
    * File Size validator.
    */
   function getFilesSizeValidator()    {return $this->_filessizevalidator;}

   /**
    * Setter method for $_countvalidator.
    *
    * @param    ZFileFilesSizeValidator $value
    */
   function setFilesSizeValidator($value)    {if(is_object($value))        {$this->_filessizevalidator = $value;}}

   /**
    * Getter for $_countvalidator's default value.
    *
    * @return   ZFileFilesSizeValidator Null
    */
   function defaultFilesSizeValidator()    {return null;}

   // Image Size

   /**
    * Image Size validator.
    *
    * @var      ZFileImageSizeValidator
    */
   protected $_imagesizevalidator = null;

   /**
    * Image Size validator.
    */
   function getImageSizeValidator()    {return $this->_imagesizevalidator;}

   /**
    * Setter method for $_imagesizevalidator.
    *
    * @param    ZFileImageSizeValidator $value
    */
   function setImageSizeValidator($value)    {if(is_object($value))        {$this->_imagesizevalidator = $value;}}

   /**
    * Getter for $_imagesizevalidator's default value.
    *
    * @return   ZFileImageSizeValidator Null
    */
   function defaultImageSizeValidator()    {return null;}

   // Is Compressed

   /**
    * Is Compressed validator.
    *
    * @var      ZFileIsCompressedValidator
    */
   protected $_iscompressedvalidator = null;

   /**
    * Is Compressed validator.
    */
   function getIsCompressedValidator()    {return $this->_iscompressedvalidator;}

   /**
    * Setter method for $_countvalidator.
    *
    * @param    ZFileIsCompressedValidator      $value
    */
   function setIsCompressedValidator($value)    {if(is_object($value))        {$this->_iscompressedvalidator = $value;}}

   /**
    * Getter for $_countvalidator's default value.
    *
    * @return   ZFileIsCompressedValidator      Null
    */
   function defaultIsCompressedValidator()    {return null;}

   // Is Image

   /**
    * Is Image validator.
    *
    * @var      ZFileIsImageValidator
    */
   protected $_isimagevalidator = null;

   /**
    * Is Image validator.
    */
   function getIsImageValidator()    {return $this->_isimagevalidator;}

   /**
    * Setter method for $_isimagevalidator.
    *
    * @param    ZFileIsImageValidator   $value
    */
   function setIsImageValidator($value)    {if(is_object($value))        {$this->_isimagevalidator = $value;}}

   /**
    * Getter for $_isimagevalidator's default value.
    *
    * @return   ZFileIsImageValidator   Null
    */
   function defaultIsImageValidator()    {return null;}

   // Hash

   /**
    * Hash validator.
    *
    * @var      ZFileHashValidator
    */
   protected $_hashvalidator = null;

   /**
    * Hash validator.
    */
   function getHashValidator()    {return $this->_hashvalidator;}

   /**
    * Setter method for $_hashvalidator.
    *
    * @param    ZFileHashValidator      $value
    */
   function setHashValidator($value)    {if(is_object($value))        {$this->_hashvalidator = $value;}}

   /**
    * Getter for $_hashvalidator's default value.
    *
    * @return   ZFileHashValidator      Null
    */
   function defaultHashValidator()    {return null;}

   // MD5

   /**
    * MD5 validator.
    *
    * @var      ZFileMD5Validator
    */
   protected $_md5validator = null;

   /**
    * MD5 validator.
    */
   function getMD5Validator()    {return $this->_md5validator;}

   /**
    * Setter method for $_countvalidator.
    *
    * @param    ZFileMD5Validator       $value
    */
   function setMD5Validator($value)    {if(is_object($value))        {$this->_md5validator = $value;}}

   /**
    * Getter for $_countvalidator's default value.
    *
    * @return   ZFileMD5Validator       Null
    */
   function defaultMD5Validator()    {return null;}

   // MIME Type

   /**
    * MIME Type validator.
    *
    * @var      ZFileMimeTypeValidator
    */
   protected $_mimetypevalidator = null;

   /**
    * MIME Type validator.
    */
   function getMimeTypeValidator()    {return $this->_mimetypevalidator;}

   /**
    * Setter method for $_mimetypevalidator.
    *
    * @param    ZFileMimeTypeValidator  $value
    */
   function setMimeTypeValidator($value)    {if(is_object($value))        {$this->_mimetypevalidator = $value;}}

   /**
    * Getter for $_mimetypevalidator's default value.
    *
    * @return   ZFileMimeTypeValidator  Null
    */
   function defaultMimeTypeValidator()    {return null;}

   // Not Exists

   /**
    * Not Exists validator.
    *
    * @var      ZFileNotExistsValidator
    */
   protected $_notexistsvalidator = null;

   /**
    * Not Exists validator.
    */
   function getNotExistsValidator()    {return $this->_notexistsvalidator;}

   /**
    * Setter method for $_notexistsvalidator.
    *
    * @param    ZFileNotExistsValidator $value
    */
   function setNotExistsValidator($value)    {if(is_object($value))        {$this->_notexistsvalidator = $value;}}

   /**
    * Getter for $_notexistsvalidator's default value.
    *
    * @return   ZFileNotExistsValidator Null
    */
   function defaultNotExistsValidator()    {return null;}

   // SHA-1

   /**
    * SHA-1 validator.
    *
    * @var      ZFileSHA1Validator
    */
   protected $_sha1validator = null;

   /**
    * SHA-1 validator.
    */
   function getSHA1Validator()    {return $this->_sha1validator;}

   /**
    * Setter method for $_countvalidator.
    *
    * @param    ZFileSHA1Validator      $value
    */
   function setSHA1Validator($value)    {if(is_object($value))        {$this->_sha1validator = $value;}}

   /**
    * Getter for $_countvalidator's default value.
    *
    * @return   ZFileSHA1Validator      Null
    */
   function defaultSHA1Validator()    {return null;}

   // Size

   /**
    * Size validator.
    *
    * @var      ZFileSizeValidator
    */
   protected $_sizevalidator = null;

   /**
    * Size validator.
    */
   function getSizeValidator()    {return $this->_sizevalidator;}

   /**
    * Setter method for $_sizevalidator.
    *
    * @param    ZFileSizeValidator      $value
    */
   function setSizeValidator($value)    {if(is_object($value))        {$this->_sizevalidator = $value;}}

   /**
    * Getter for $_sizevalidator's default value.
    *
    * @return   ZFileSizeValidator      Null
    */
   function defaultSizeValidator()    {return null;}

   // Word Count

   /**
    * Word Count validator.
    *
    * @var      ZFileWordCountValidator
    */
   protected $_wordcountvalidator = null;

   /**
    * Word Count validator.
    */
   function getWordCountValidator()    {return $this->_wordcountvalidator;}

   /**
    * Setter method for $_countvalidator.
    *
    * @param    ZFileWordCountValidator $value
    */
   function setWordCountValidator($value)    {if(is_object($value))        {$this->_wordcountvalidator = $value;}}

   /**
    * Getter for $_countvalidator's default value.
    *
    * @return   ZFileWordCountValidator Null
    */
   function defaultWordCountValidator()    {return null;}

   // Decrypt

   /**
    * Decrypt filter.
    *
    * @var      ZFileDecryptFilter
    */
   protected $_decryptfilter = null;

   /**
    * Decrypt filter.
    */
   function getDecryptFilter()    {return $this->_decryptfilter;}

   /**
    * Setter method for $_decryptfilter.
    *
    * @param    ZFileDecryptFilter      $value
    */
   function setDecryptFilter($value)    {if(is_object($value))        {$this->_decryptfilter = $value;}}

   /**
    * Getter for $_decryptfilter's default value.
    *
    * @return   ZFileDecryptFilter      Null
    */
   function defaultDecryptFilter()    {return null;}

   // Encrypt

   /**
    * Encrypt filter.
    *
    * @var      ZFileEncryptFilter
    */
   protected $_encryptfilter = null;

   /**
    * Encrypt filter.
    */
   function getEncryptFilter()    {return $this->_encryptfilter;}

   /**
    * Setter method for $_encryptfilter.
    *
    * @param    ZFileEncryptFilter      $value
    */
   function setEncryptFilter($value)    {if(is_object($value))        {$this->_encryptfilter = $value;}}

   /**
    * Getter for $_encryptfilter's default value.
    *
    * @return   ZFileEncryptFilter      Null
    */
   function defaultEncryptFilter()    {return null;}

   // Lowercase

   /**
    * Lowercase filter.
    *
    * @var      ZFileLowerCaseFilter
    */
   protected $_lowercasefilter = null;

   /**
    * Lowercase filter.
    */
   function getLowerCaseFilter()    {return $this->_lowercasefilter;}

   /**
    * Setter method for $_lowercasefilter.
    *
    * @param    ZFileLowerCaseFilter    $value
    */
   function setLowerCaseFilter($value)    {if(is_object($value))        {$this->_lowercasefilter = $value;}}

   /**
    * Getter for $_lowercasefilter's default value.
    *
    * @return   ZFileLowerCaseFilter    Null
    */
   function defaultLowerCaseFilter()    {return null;}

   // Uppercase

   /**
    * Uppercase filter.
    *
    * @var      ZFileUpperCaseFilter
    */
   protected $_uppercasefilter = null;

   /**
    * Uppercase filter.
    */
   function getUpperCaseFilter()    {return $this->_uppercasefilter;}

   /**
    * Setter method for $_uppercasefilter.
    *
    * @param    ZFileUpperCaseFilter    $value
    */
   function setUpperCaseFilter($value)    {if(is_object($value))        {$this->_uppercasefilter = $value;}}

   /**
    * Getter for $_uppercasefilter's default value.
    *
    * @return   ZFileUpperCaseFilter    Null
    */
   function defaultUpperCaseFilter()    {return null;}

   // Rename

   /**
    * Rename filter.
    *
    * @var      ZFileRenameFilter
    */
   protected $_renamefilter = null;

   /**
    * Rename filter.
    */
   function getRenameFilter()    {return $this->_renamefilter;}

   /**
    * Setter method for $_renamefilter.
    *
    * @param    ZFileRenameFilter       $value
    */
   function setRenameFilter($value)    {if(is_object($value))        {$this->_renamefilter = $value;}}

   /**
    * Getter for $_renamefilter's default value.
    *
    * @return   ZFileRenameFilter       Null
    */
   function defaultRenameFilter()    {return null;}

   // Destination

   /**
    * Path to uploads directory.
    *
    * @var      string
    */
   protected $_destination = '';

   /**
    * Path to uploads directory.
    */
   function getDestination()    {return $this->_destination;}

   /**
    * Setter method for $_destination.
    *
    * @param    string  $value
    */
   function setDestination($value)    {$this->_destination = $value;}

   /**
    * Getter for $_destination's default value.
    *
    * @return   string  Empty string
    */
   function defaultDestination()    {return '';}

   // Ignore File

   /**
    * Whether or not validator will ignore files that have not been uploaded through the form.
    *
    * It can be set either to true ('true') or to false ('false').
    *
    * @var      string
    */
   protected $_ignorenofile = 'false';

   /**
    * Whether or not validator will ignore files that have not been uploaded through the form.
    *
    * It can be set either to true ('true') or to false ('false').
    */
   function getIgnoreNoFile()    {return $this->_ignorenofile;}

   /**
    * Setter method for $_ignorenofile.
    *
    * @param    string  $value
    */
   function setIgnoreNoFile($value)    {$this->_ignorenofile = $value;}

   /**
    * Getter for $_ignorenofile's default value.
    *
    * @return   string  False ('false')
    */
   function defaultIgnoreNoFile()    {return 'false';}

   // Documented in the parent.
   function loaded()
   {
      $this->CreateFile();
   }

   /**
    * Receive the file from the client (perform the upload).
    *
    * Returns true is everything works normal, false otherwise.
    *
    * @return   boolean
    */
   function receive()
   {
      return $this->_adapter->receive();
   }

   /**
    * Guesses a temporal-folder path.
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
}

?>
