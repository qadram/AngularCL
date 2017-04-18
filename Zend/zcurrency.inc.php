<?php

/**
 * Zend/zcurrency.inc.php
 * 
 * Defines Zend Framework Currency component.
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
use_unit("Zend/framework/library/Zend/Currency.php");
use_unit("Zend/framework/library/Zend/Currency/CurrencyInterface.php");

// Display Options

/**
 * Display nothing to indicate the currency.
 * 
 * @const       dcNoSymbol
 */
define('dcNoSymbol', 'dcNoSymbol');

/**
 * Display currency symbol.
 * 
 * @const       dcUseSymbol
 */
define('dcUseSymbol', 'dcUseSymbol');

/**
 * Display currency short name.
 * 
 * @const       dcUseShortname
 */
define('dcUseShortname', 'dcUseShortname');

/**
 * Display currency complete name.
 * 
 * @const       dcUseName
 */
define('dcUseName', 'dcUseName');

// Position Options

/**
 * Standard position.
 * 
 * @const       pcStandard
 */
define('pcStandard', 'pcStandard');

/**
 * Right position.
 * 
 * @const       pcRight
 */
define('pcRight', 'pcRight');

/**
 * Left position.
 * 
 * @const       pcLeft
 */
define('pcLeft', 'pcLeft');

// Numeric Format Locales

define('fcAA', 'fcAA');
define('fcAF', 'fcAF');
define('fcAK', 'fcAK');
define('fcAM', 'fcAM');
define('fcAR', 'fcAR');
define('fcAZ', 'fcAZ');
define('fcBE', 'fcBE');
define('fcBG', 'fcBG');
define('fcBN', 'fcBN');
define('fcBO', 'fcBO');
define('fcBS', 'fcBS');
define('fcBYN', 'fcBYN');
define('fcCA', 'fcCA');
define('fcCCH', 'fcCCH');
define('fcCOP', 'fcCOP');
define('fcCS', 'fcCS');
define('fcCY', 'fcCY');
define('fcDA', 'fcDA');
define('fcDE', 'fcDE');
define('fcDV', 'fcDV');
define('fcDZ', 'fcDZ');
define('fcEE', 'fcEE');
define('fcEL', 'fcEL');
define('fcEN', 'fcEN');
define('fcEO', 'fcEO');
define('fcES', 'fcES');
define('fcET', 'fcET');
define('fcEU', 'fcEU');
define('fcFA', 'fcFA');
define('fcFI', 'fcFI');
define('fcFIL', 'fcFIL');
define('fcFO', 'fcFO');
define('fcFR', 'fcFR');
define('fcFUR', 'frFUR');
define('fcGA', 'fcGA');
define('fcGAA', 'fcGAA');
define('fcGEZ', 'fcGEZ');
define('fcGL', 'fcGL');
define('fcGSW', 'fcGSW');
define('fcGU', 'fcGU');
define('fcGV', 'fcGV');
define('fcHA', 'fcHA');
define('fcHAW', 'fcHAW');
define('fcHE', 'fcHE');
define('fcHI', 'fcHI');
define('fcHR', 'fcHR');
define('fcHU', 'fcHU');
define('fcHY', 'fcHY');
define('fcIA', 'fcIA');
define('fcID', 'fcID');
define('fcIG', 'fcIG');
define('fcII', 'fcII');
define('fcIN', 'fcIN');
define('fcIS', 'fcIS');
define('fcIT', 'fcIT');
define('fcIU', 'fcIU');
define('fcIW', 'fcIW');
define('fcJA', 'fcJA');
define('fcKA', 'fcKA');
define('fcKAJ', 'fcKAJ');
define('fcKAM', 'fcKAM');
define('fcKCG', 'fcKCG');
define('fcKFO', 'fcKFO');
define('fcKK', 'fcKK');
define('fcKL', 'fcKL');
define('fcKM', 'fcKM');
define('fcKN', 'fcKN');
define('fcKO', 'fcKO');
define('fcKOK', 'fcKOK');
define('fcKPE', 'fcKPE');
define('fcKU', 'fcKU');
define('fcKW', 'fcKW');
define('fcKY', 'fcKY');
define('fcLN', 'fcLN');
define('fcLO', 'fcLO');
define('fcLT', 'fcLT');
define('fcLV', 'fcLV');
define('fcMK', 'fcMK');
define('fcML', 'fcML');
define('fcMN', 'fcMN');
define('fcMO', 'fcMO');
define('fcMR', 'fcMR');
define('fcMS', 'fcMS');
define('fcMT', 'fcMT');
define('fcMY', 'fcMY');
define('fcNB', 'fcNB');
define('fcNDS', 'fcNDS');
define('fcNE', 'fcNE');
define('fcNL', 'fcNL');
define('fcNN', 'fcNN');
define('fcNR', 'fcNR');
define('fcNSO', 'fcNSO');
define('fcNY', 'fcNY');
define('fcOC', 'fcOC');
define('fcOM', 'fcOM');
define('fcOR', 'fcOR');
define('fcPA', 'fcPA');
define('fcPL', 'fcPL');
define('fcPS', 'fcPS');
define('fcPT', 'fcPT');
define('fcRO', 'fcRO');
define('fcRU', 'fcRU');
define('fcRW', 'fcRW');
define('fcSA', 'fcSA');
define('fcSE', 'fcSE');
define('fcSH', 'fcSH');
define('fcSI', 'fcSI');
define('fcSID', 'fcSID');
define('fcSK', 'fcSK');
define('fcSL', 'fcSL');
define('fcSO', 'fcSO');
define('fcSQ', 'fcSQ');
define('fcSR', 'fcSR');
define('fcST', 'fcST');
define('fcSV', 'fcSV');
define('fcSW', 'fcSW');
define('fcSYR', 'fcSYR');
define('fcTA', 'fcTA');
define('fcTE', 'fcTE');
define('fcTG', 'fcTG');
define('fcTIG', 'fcTIG');
define('fcTL', 'fcTL');
define('fcTN', 'fcTN');
define('fcTO', 'fcTO');
define('fcTR', 'fcTR');
define('fcTRV', 'fcTRV');
define('fcTS', 'fcTS');
define('fcTT', 'fcTT');
define('fcUG', 'fcUG');
define('fcUK', 'fcUK');
define('fcUR', 'fcUR');
define('fcUZ', 'fcUZ');
define('fcVE', 'fcVE');
define('fcVI', 'fcVI');
define('fcWAL', 'fcWAL');
define('fcWO', 'fcWO');
define('fcXH', 'fcXH');
define('fcYO', 'fcYO');
define('fcZH', 'fcZH');
define('fcZU', 'fcZU');

/**
 * Component to work with currencies.
 * 
 * @link        http://framework.zend.com/manual/en/zend.currency.html Zend Framework Documentation
 */
class ZCurrency extends Component
{
   /**
    * Zend Framework Currency instance.
    *
    * @var      Zend_Currency
    */
   private $_currency = null;

   // Documented in the parent.
   function __construct($aowner = null)
   {
      parent::__construct($aowner);
   }

   // Documented in the parent.
   function serialize()
   {
      parent::serialize();

      $owner = $this->readOwner();

      if($owner != null)
      {
         $prefix = $owner->readNamePath() . "." . $this->_name . ".Currency.";
         if(isset($_SESSION[$prefix . "Value"]))
            $_SESSION[$prefix . "Value"] = $this->_currency->getValue();
         else
            $_SESSION[$prefix . "Value"] = $this->_value;

      }
   }

   // Documented in the parent.
   function unserialize()
   {
      parent::unserialize();

      $owner = $this->readOwner();

      if($owner != null)
      {
         $prefix = $owner->readNamePath() . "." . $this->_name . ".Currency.";

         if(isset($_SESSION[$prefix . "Value"]))
            $this->_value = $_SESSION[$prefix . "Value"];
      }
   }

   // Documented in the parent.
   function loaded()
   {
      $this->CreateCurrency();
   }

   // Precision

   /**
    * Amount Precision.
    *
    * Amount of precision numbers to be used with amounts in current currency. For example, if set
    * to 2, "1" amount would be displayed as "1.00".
    *
    * @var      integer
    */
   protected $_precisioncurrency = 2;

   /**
    * Amount Precision.
    *
    * Amount of precision numbers to be used with amounts in current currency. For example, if set
    * to 2, "1" amount would be displayed as "1.00".
    */
   function getPrecisionCurrency()    {return $this->_precisioncurrency;}

   /**
    * Setter method for $_precisioncurrency.
    *
    * @param    integer $value
    */
   function setPrecisionCurrency($value)    {$this->_precisioncurrency = $value;}

   /**
    * Getter for $_precisioncurrency's default value.
    *
    * @return   integer 2
    */
   function defaultPrecisionCurrency()    {return 2;}

   // Short Name

   /**
    * Currency name abbreviation.
    *
    * @var      string
    */
   protected $_shortname = 'USD';

   /**
    * Currency name abbreviation.
    */
   function getShortName()    {return $this->_shortname;}

   /**
    * Setter method for $_shortname.
    *
    * @param    string  $value
    */
   function setShortName($value)    {$this->_shortname = $value;}

   /**
    * Getter for $_shortname's default value.
    *
    * @return   string  'USD'
    */
   function defaultShortName()    {return 'USD';}

   // Display

   /**
    * How the currency will be represented when displaying the money amount.
    *
    * Possible values are: dcNoSymbol, dcUseSymbol, dcUseShortname, or
    * dcUseName.
    *
    * @var      string
    */
   protected $_displaycurrency = dcNoSymbol;

   /**
    * How the currency will be represented when displaying the money amount.
    *
    * Possible values are: dcNoSymbol, dcUseSymbol, dcUseShortname, or
    * dcUseName.
    */
   function getDisplayCurrency()    {return $this->_displaycurrency;}

   /**
    * Setter method for $_displaycurrency.
    *
    * @param    string  $value
    */
   function setDisplayCurrency($value)    {$this->_displaycurrency = $value;}

   /**
    * Getter for $_displaycurrency's default value.
    *
    * @return   string  dcNoSymbol
    */
   function defaultDisplayCurrency()    {return dcNoSymbol;}

   // Name

   /**
    * Currency complete name.
    *
    * @var      string
    */
   protected $_namecurrency = 'Dollar';

   /**
    * Currency complete name.
    */
   function getNameCurrency()    {return $this->_namecurrency;}

   /**
    * Setter method for $_namecurrency.
    *
    * @param    string  $value
    */
   function setNameCurrency($value)    {$this->_namecurrency = $value;}

   /**
    * Getter for $_namecurrency's default value.
    *
    * @return   string  'Dollar'
    */
   function defaultNameCurrency()    {return 'Dollar';}

   // Position

   /**
    * Currency representation position.
    *
    * Possible values are: pcStandard, pcRight, or pcLeft.
    *
    * @see      $_displaycurrency
    *
    * @var      string
    */
   protected $_positioncurrency = pcStandard;

   /**
    * Currency representation position.
    *
    * Possible values are: pcStandard, pcRight, or pcLeft.
    *
    * @see      $_displaycurrency
    */
   function getPositionCurrency()    {return $this->_positioncurrency;}

   /**
    * Setter method for $_positioncurrency.
    *
    * @param    string  $value
    */
   function setPositionCurrency($value)    {$this->_positioncurrency = $value;}

   /**
    * Getter for $_positioncurrency's default value.
    *
    * @return   string  pcStandard
    */
   function defaultPositionCurrency()    {return pcStandard;}

   // Symbol

   /**
    * Currency symbol.
    *
    * @var      string
    */
   protected $_symbolcurrency = '$';

   /**
    * Currency symbol.
    */
   function getSymbolCurrency()    {return $this->_symbolcurrency;}

   /**
    * Setter method for $_symbolcurrency.
    *
    * @param    string  $value
    */
   function setSymbolCurrency($value)    {$this->_symbolcurrency = $value;}

   /**
    * Getter for $_symbolcurrency's default value.
    *
    * @return   string  '$'
    */
   function defaultSymbolCurrency()    {return '$';}

   // Format

   /**
    * Locale to guess format from.
    *
    * @var      string
    */
   protected $_formatcurrency = fcEN;

   /**
    * Locale to guess format from.
    */
   function getFormatCurrency()    {return $this->_formatcurrency;}

   /**
    * Setter method for $_formatcurrency.
    *
    * @param    string  $value
    */
   function setFormatCurrency($value)    {$this->_formatcurrency = $value;}

   /**
    * Getter for $_formatcurrency's default value.
    *
    * @return   string  fcEN
    */
   function defaultFormatCurrency()    {return fcEN;}

   // Alternative Format

   /**
    * Alternative locale to gues format from.
    *
    * @var      string
    */
   protected $_formatcurrencyalt = '';

   /**
    * Alternative locale to gues format from.
    */
   function getFormatCurrencyAlt()    {return $this->_formatcurrencyalt;}

   /**
    * Setter method for $_formatcurrencyalt.
    *
    * @param    string  $value
    */
   function setFormatCurrencyAlt($value)    {$this->_formatcurrencyalt = $value;}

   /**
    * Getter for $_formatcurrencyalt's default value.
    *
    * @return   string  Empty string
    */
   function defaultFormatCurrencyAlt()    {return '';}

   // Locale

   /**
    * Currency locale.
    *
    * @var      string
    */
   protected $_localecurrency = 'en_US';

   /**
    * Currency locale.
    */
   function getLocaleCurrency()    {return $this->_localecurrency;}

   /**
    * Setter method for $_localecurrency.
    *
    * @param    string  $value
    */
   function setLocaleCurrency($value)    {$this->_localecurrency = $value;}

   /**
    * Getter for $_localecurrency's default value.
    *
    * @return   string  'en_US'
    */
   function defaultLocaleCurrency()    {return 'en_US';}

   // Amount

   /**
    * Money amount to be displayed.
    *
    * @var      float|integer
    */
   protected $_value = 0;

   /**
    * Money amount to be displayed.
    *
    * @return   float|integer   $_value
    */
   function getValue()    {return $this->_value;}

   /**
    * Setter method for $_value.
    *
    * @param    float|integer   $value
    */
   function setValue($value)    {$this->_value = $value;}

   /**
    * Getter for $_value's default value.
    *
    * @return   integer         0
    */
   function defaultValue()    {return 0;}

   /**
    * Generator for $_currency.
    *
    * Generates a Zend Framework Currency object from those properties set for
    * this ZCurrency instance (or defaults), and saves it to $_currency.
    */
   function CreateCurrency()
   {
      $data = array();
      $service = new SimpleExchange();
      switch($this->_displaycurrency)
      {
         case dcNoSymbol:
            $data['display'] = Zend_Currency::NO_SYMBOL;
            break;
            /*           case dcUseSymbol:
            $data['display']=Zend_Currency::USE_SYMBOL;
            break;
            case dcUseShortname:
            $data['display']=Zend_Currency::USE_SHORTNAME;
            break;*/
         case dcUseName:
            $data['display'] = Zend_Currency::USE_NAME;
            break;
      }

      switch($this->_positioncurrency)
      {
         case pcStandard:
            $data['position'] = Zend_Currency::STANDARD;
            break;
         case pcRight:
            $data['position'] = Zend_Currency::RIGHT;
            break;
         case pcLeft:
            $data['position'] = Zend_Currency::LEFT;
            break;
      }

      if($this->_formatcurrencyalt == '')
      {
         $data['format'] = strtolower(str_replace('fc', '', $this->_formatcurrency));

      }
      else
      {
         $data['format'] = $this->_formatcurrencyalt;
      }

      $data['name'] = $this->_namecurrency;

      $data['precision'] = $this->_precisioncurrency;

      $data['value'] = $this->_value;

      $data['symbol'] = $this->_symbolcurrency;

      $data['currency'] = $this->_shortname;
      $data['service'] = $service;

      if($this->_localecurrency != '')
      {
         $data['locale'] = $this->_localecurrency;
         $this->_currency = new Zend_Currency($data, $this->_localecurrency);
      }
      else
      {
        $this->_currency = new Zend_Currency($data);
      }

      

   }

   /**
    * Increases current $_value by given amount.
    *
    * For example, if $_value is 20, and you call add(30), $_value will be 50.
    * 
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    */
   function add($value)
   {

      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      $this->_currency->add($aux);
   }

   /**
    * Decreases current $_value by given amount.
    *
    * For example, if $_value is 50, and you call sub(20), $_value will be
    * 30.
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    */
   function sub($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      $this->_currency->sub($aux);
   }

   /**
    * Divides $_value by given amount.
    *
    * For example, if $_value is 50, and you call div(5), $_value will be
    * 10.
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    */
   function div($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      $this->_currency->div($aux);
   }

   /**
    * Multiplies $_value by given amount.
    *
    * For example, if $_value is 5, and you call mul(10), $_value will be
    * 50.
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    */
   function mul($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      $this->_currency->mul($aux);
   }

   /**
    * Divides $_value by given amount, and sets $_value to the remainder.
    *
    * For example, if $_value is 19, and you call mod(5), $_value will be
    * 4 (19 = 3 * 5 + 4).
    * 
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    */
   function mod($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      $this->_currency->mod($aux);
   }

   /**
    * Compares $_value and given amount.
    *
    * <ul>
    *   <li>When both values are equal, it returns 0.</li>
    *   <li>When $_value is greater, it returns 1.</li>
    *   <li>When given amount is greater, it returns -1.</li>
    * </ul>
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    * @return   integer
    */
   function compare($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      return $this->_currency->compare($aux);
   }

   /**
    * Check if $_value and given amount are equal.
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    * @return   boolean
    */
   function isEqual($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      return $this->_currency->equals($aux);
   }

   /**
    * Check if $_value is greater than given amount.
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    * @return   boolean
    */
   function isMore($value)
   {
      if($value instanceof ZCurrency)
      {

         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      return $this->_currency->isMore($aux);
   }

   /**
    * Check if given value is greater than $_value.
    *
    * @param    float|integer|ZCurrency|Zend_Currency   $value
    * @return   boolean
    */
   function isLess($value)
   {
      if($value instanceof ZCurrency)
      {
         $aux = $value->ZendCurrencyObject();
      }
      else
      {
         $aux = $value;
      }
      return $this->_currency->isLess($aux);
   }

   /**
    * Returns a string representation of $_value.
    *
    * The string will be formetted as set in this instance of ZCurrency.
    *
    * @return   string
    */
   function asString()
   {
      return $this->_currency->toString();
   }

   /**
    * Returns $_currency.
    *
    * If $_currency was null, it will be created with CreateCurrency() and then
    * returned.
    *
    * @return   Zend_Currency
    */
   function ZendCurrencyObject()
   {
      if(!isset($this->_currency))
      {
          $this->CreateCurrency();
      }
      return $this->_currency;
   }
}

/**
 * Simple currency exchange service.
 *
 * {@internal This class should serve as an example on how to implement a currency exchange service
 * of your own. For example, you can use this code as a base to develop a service that gets the
 * exchange values from a database.}}
 */
class SimpleExchange implements Zend_Currency_CurrencyInterface
{
   /**
    * Returns the exchange value between given currencies.
    *
    * @param    string          $from   Original currency short name.
    * @param    string          $to     Target currency short name.
    * @return   float
    * @throws   Exception       Original or target currency is not suported.
    */
   public function getRate($from, $to)
   {
      if($from !== "USD" && $from !== "EUR")
      {
         throw new Exception('We can only exchange USD');

      }
      switch($from)
      {
         case 'USD':
            switch($to)
            {
               case 'EUR':
                  return 2;
               case 'JPE':
                  return 0.7;
            }
            break;
         case 'EUR':
            switch($to)
            {
               case 'USD':
                  return 0.5;
               case 'JPE':
                  return 1.3;
            }
            break;
      }

      throw new Exception('Unable to exchange $to');
   }
}

?>