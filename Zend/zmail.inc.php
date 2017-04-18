<?php

/**
 * Zend/zmail.inc.php
 *
 * Defines Zend Framework Mail component.
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
use_unit("Zend/zcommon/zcommon.inc.php");
use_unit("classes.inc.php");
use_unit("Zend/framework/library/Zend/Mail.php");
use_unit("Zend/framework/library/Zend/Mail/Transport/Smtp.php");
use_unit("Zend/framework/library/Zend/Mail/Transport/Sendmail.php");

/**
 * Component to send emails.
 *
 * It supports different mailing software, emails formats and almost any email feature you can think
 * of.
 *
 * @link        http://framework.zend.com/manual/en/zend.mail.html Zend Framework Documentation
 */
class ZMail extends Component
{

   /**
    * Zend Framework Mail instance.
    *
    * @var      Zend_Mail
    */
   protected $_mail = null;

   // Body Text

   /**
    * Text for the body of the email in plain-text.
    *
    * @var      string
    */
   protected $_bodytext = "";

   /**
    * Text for the body of the email in plain-text.
    */
   function getBodyText()    {return $this->_bodytext;}

   /**
    * Setter method for $_bodytext.
    *
    * @param    string  $value
    */
   function setBodyText($value)    {$this->_bodytext = $value;}

   /**
    * Getter for $_bodytext's default value.
    *
    * @return   string  Empty string
    */
   function defaultBodyText()    {return "";}

   // Body HTML

   /**
    * Text for the body of the email in HTML.
    *
    * @var      string
    */
   protected $_bodyhtml = "";

   /**
    * Text for the body of the email in HTML.
    */
   function getBodyHTML()    {return $this->_bodyhtml;}

   /**
    * Setter method for $_bodyhtml.
    *
    * @param    string  $value
    */
   function setBodyHTML($value)    {$this->_bodyhtml = $value;}

   /**
    * Getter for $_bodyhtml's default value.
    *
    * @return   string  Empty string
    */
   function defaultBodyHTML()    {return "";}

   // Subject

   /**
    * Email subject.
    *
    * @var      string
    */
   protected $_subject = "";

   /**
    * Email subject.
    */
   function getSubject()    {return $this->_subject;}

   /**
    * Setter method for $_subject.
    *
    * @param    string  $value
    */
   function setSubject($value)    {$this->_subject = $value;}

   /**
    * Getter for $_subject's default value.
    *
    * @return   string  Empty string
    */
   function defaultSubject()    {return "";}

   // From Name

   /**
    * Sender name.
    *
    * For example, for John Doe <john.doe@example.com>, this property should be set to "John Doe".
    *
    * @see $_fromemail
    *
    * @var      string
    */
   protected $_fromname = "";

   /**
    * Sender name.
    *
    * For example, for John Doe <john.doe@example.com>, this property should be set to "John Doe".
    *
    * @see $_fromemail
    */
   function getFromName()    {return $this->_fromname;}

   /**
    * Setter method for $_fromname.
    *
    * @param    string  $value
    */
   function setFromName($value)    {$this->_fromname = $value;}

   /**
    * Getter for $_fromname's default value.
    *
    * @return   string  Empty string
    */
   function defaultFromName()    {return "";}

   // From Email

   /**
    * Sender email address.
    *
    * For example, for John Doe <john.doe@example.com>, this property should be set to
    * "john.doe@example.com".
    *
    * @see $_fromname
    *
    * @var      string
    */
   protected $_fromemail = "";

   /**
    * Sender email address.
    *
    * For example, for John Doe <john.doe@example.com>, this property should be set to
    * "john.doe@example.com".
    *
    * @see $_fromname
    */
   function getFromEmail()    {return $this->_fromemail;}

   /**
    * Setter method for $_fromemail.
    *
    * @param    string  $value
    */
   function setFromEmail($value)    {$this->_fromemail = $value;}

   /**
    * Getter for $_fromemail's default value.
    *
    * @return   string  Empty string
    */
   function defaultFromEmail()    {return "";}

   // Send Method

   /**
    * Method to be used when sending the email.
    *
    * Sendmail method will be used if no method is set.
    *
    * @var      ZMailTransport
    */
   protected $_transport = null;

   /**
    * Method to be used when sending the email.
    *
    * Sendmail method will be used if no method is set.
    */
   function getTransport()    {return $this->_transport;}

   /**
    * Setter method for $_transport.
    *
    * @param    ZMailTransport  $value
    */
   function setTransport($value)    {$this->_transport = $this->fixupProperty($value);}

   /**
    * Getter for $_transport's default value.
    *
    * @return   ZMailTransport  Null
    */
   function defaultTransport()    {return null;}

   // Loaded

   // Documented in the parent.
   function loaded()
   {
      parent::loaded();
      $this->setTransport($this->_transport);
   }

   protected $_to = array();
   /**
    * Recipients.
    *
    * The array should have key-value pairs, where each key is an email address, and its value is
    * the name of the recipient with that email address.
    *
    * @return array
    */
   function getTo()    {return $this->_to;}
   function setTo($value)    {$this->_to = $value;}
   function defaultTo()    {return array();}

   protected $_cc = array();
   /**
    * CC Recipients.
    *
    * The array should have key-value pairs, where each key is an email address, and its value is
    * the name of the recipient with that email address.
    *
    * @return array
    */
   function getCc()    {return $this->_cc;}
   function setCc($value)    {$this->_cc = $value;}
   function defaultCc()    {return array();}

   protected $_bcc = array();
   /**
    * BCC Recipients.
    *
    * The array should have key-value pairs, where each key is an email address, and its value is
    * the name of the recipient with that email address.
    *
    * @return array
    */
   function getBcc()    {return $this->_bcc;}
   function setBcc($value)    {$this->_bcc = $value;}
   function defaultBcc()    {return array();}

   protected $_attachments = array();
   /**
    * Paths to the files on the server to be attached to the email.
    *
    * It is a simple array where each item is a path to a file on the server that should be added
    * to the email as attachment. You can further customize these attachments from the
    * OnCustomizeAttachment event, where you can define their MIME Type, filename, and more.
    *
    * @return array
    */
   function getAttachments()    {return $this->_attachments;}
   function setAttachments($value)    {$this->_attachments = $value;}
   function defaultAttachments()    {return array();}

   // OnCustomizeAttachment

   /**
    * Event for attachments customization, both for the attachments themselves and for the way they
    * are handled.
    *
    * This event is triggered right before adding each attachment to your email.
    *
    * This property should either contain the name of the function to be run when the event is
    * triggered (without brackets), or be set to null.
    *
    * Its second parameter, $params, will contain a one-item array with the path to one of the
    * attachments defined in the Attachments property (so, for each attachment there
    * will be a call to this event). Use the provided path (or the key of the item on the array)
    * to identify the attachment in each call.
    *
    * The event should return an empty array, or an array of key-value pairs where each key is
    * the name of an option, and the value is the value you want for that option. These are the
    * options you can set, all of them optional:
    * <ul>
    *   <li>body: Attachment binary content. It would replace the content of the file pointed
    *   by the path of the attachment, which was set in the Attachments property.</li>
    *   <li>mimetype: File MIME type. Zend Framework MIME constants (http://framework.zend.com/manual/en/zend.mime.mime.html#zend.mime.mime.static) are allowed.</li>
    *   <li>disposition: The way the content will be attached to the email, either inside the
    *   message itself ('inline') or as a normal separated attachment ('attachment').</li>
    *   <li>encoding: Attachment encoding Zend Framework MIME constants (http://framework.zend.com/manual/en/zend.mime.mime.html#zend.mime.mime.static) are allowed.</li>
    *   <li>filename: Display name for the attached file.</li>
    * </ul>
    *
    * Take a look at this sample function for the event:
    *
    * <code>
    *  function zmCustomCustomizeAttachment($sender, $params)
    *  {
    *   $result=array();
    *   list($key, $attachment)=each($params);
    *   if ($attachment=='index.php')
    *   {
    *       $result['mimetype']=Zend_Mime::TYPE_TEXT;
    *       $result['disposition']=Zend_Mime::DISPOSITION_INLINE;
    *   }
    *   return($result);
    *  }
    * </code>
    *
    * @var      string
    */
   protected $_oncustomizeattachment = null;

   /**
    * Event for attachments customization, both for the attachments themselves and for the way they
    * are handled.
    *
    * This event is triggered right before adding each attachment to your email.
    *
    * This property should either contain the name of the function to be run when the event is
    * triggered (without brackets), or be set to null.
    *
    * Its second parameter, $params, will contain a one-item array with a key-value pair. This pair
    * will match one of your attachments as defined in $_attachments (so, for each
    * attachment there will be a call to this event). Use provided data to identify the
    * attachment in each call.
    *
    * The event should then return an array also with key-value pairs. You can either return an
    * empty array, or use it to set some options for the attachment the call was made for. These
    * are all the options you can set, all of them optional:
    * <ul>
    *   <li>body: Attachment binary content. It would replace the content of the file pointed
    *   by the path of the attachment, which was set in $_attachments.</li>
    *   <li>mimetype: File MIME type. Zend Framework MIME constants (http://framework.zend.com/manual/en/zend.mime.mime.html#zend.mime.mime.static) are allowed.</li>
    *   <li>disposition: The way the content will be attached to the email, either inside the
    *   message itself ('inline') or as a normal separated attachment ('attachment').</li>
    *   <li>encoding: Attachment encoding Zend Framework MIME constants (http://framework.zend.com/manual/en/zend.mime.mime.html#zend.mime.mime.static) are allowed.</li>
    *   <li>filename: Display name for the attached file.</li>
    * </ul>
    *
    * Take a look at this sample function for the event:
    *
    * <code>
    *  function zmCustomCustomizeAttachment($sender, $params)
    *  {
    *   $result=array();
    *   list($key, $attachment)=each($params);
    *   if ($attachment=='index.php')
    *   {
    *       $result['mimetype']=Zend_Mime::TYPE_TEXT;
    *       $result['disposition']=Zend_Mime::DISPOSITION_INLINE;
    *   }
    *   return($result);
    *  }
    * </code>
    */
   function getOnCustomizeAttachment()    {return $this->_oncustomizeattachment;}

   /**
    * Setter method for $_oncustomizeattachment.
    *
    * @param    string  $value
    */
   function setOnCustomizeAttachment($value)    {$this->_oncustomizeattachment = $value;}

   /**
    * Getter for $_oncustomizeattachment's default value.
    *
    * @return   string  Null
    */
   function defaultOnCustomizeAttachment()    {return null;}

   protected $_headers = array();
   /**
    * Custom headers for the email.
    *
    * It is a simple array, and each item is a string with the following syntax:
    * 'header name=value'.
    *
    * You can safely set the same header several times to different values, so all those values are
    * used.
    *
    * @return array
    */
   function getHeaders()    {return $this->_headers;}
   function setHeaders($value)    {$this->_headers = $value;}
   function defaultHeaders()    {return array();}

   /**
    * Sends an email with provided settings.
    *
    * This method also triggers OnCustomizeAttachment event right
    * before adding each attachment.
    */
   function send()
   {
      $mail = new Zend_Mail();

      if(trim($this->_fromemail) != '')
         $mail->setFrom($this->_fromemail, $this->_fromname);

      if(trim($this->_bodytext) != '')
         $mail->setBodyText($this->_bodytext);

      if(trim($this->_bodyhtml) != '')
         $mail->setBodyHtml($this->_bodyhtml);

      if(trim($this->_subject) != '')
         $mail->setSubject($this->_subject);

      if(count($this->_to) >= 1)
      {
         reset($this->_to);
         while(list($email, $name) = each($this->_to)) $mail->addTo($email, $name);
      }

      if(count($this->_cc) >= 1)
      {
         reset($this->_cc);
         while(list($email, $name) = each($this->_cc)) $mail->addCc($email, $name);
      }

      if(count($this->_bcc) >= 1)
      {
         reset($this->_bcc);
         while(list($email, $name) = each($this->_bcc)) $mail->addBcc($email, $name);
      }

      if(count($this->_attachments) >= 1)
      {
         reset($this->_attachments);
         while(list($key, $path) = each($this->_attachments))
         {
            $contents = '';
            $mimetype = Zend_Mime::TYPE_OCTETSTREAM;
            $disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
            $encoding = Zend_Mime::ENCODING_BASE64;
            $filename = basename($path);
            $params = array();

            if($this->_oncustomizeattachment != null)
            {
               $params = $this->callEvent('oncustomizeattachment', array($key=>$path));
            }

            if(isset($params['body']))
               $body = $params['body'];
            else
               $body = file_get_contents($path);

            if(isset($params['mimetype']))
               $mimetype = $params['mimetype'];
            if(isset($params['disposition']))
               $disposition = $params['disposition'];
            if(isset($params['encoding']))
               $encoding = $params['encoding'];
            if(isset($params['filename']))
               $filename = $params['filename'];

            $mail->createAttachment($body, $mimetype, $disposition, $encoding, $filename);
         }
      }

      if(count($this->_headers) >= 1)
      {
         $keys = array();
         reset($this->_headers);
         while(list($key, $line) = each($this->_headers))
         {
            $parts = explode('=', $line);
            $header = $parts[0];
            $value = $parts[1];
            $append = array_key_exists($header, $keys);
            $mail->addHeader($header, $value, $append);
            $keys[$header] = 1;
         }

      }

      $transport = null;

      if(is_object($this->_transport))
         $transport = $this->_transport->readTransport();

      $mail->send($transport);
   }

   /**
    * Returns an instance of Zend_Mail with provided settings.
    *
    * @return   Zend_Mail
    */
   function createMail()
   {
      $mail = new Zend_Mail();

      $this->setTransport($this->fixupProperty($this->_transport));
      $transport = null;
      if(is_object($this->_transport))
      {
         $transport = $this->_transport->readTransport();
         Zend_Mail::setDefaultTransport($transport);
      }

      if(trim($this->_fromemail) != '')
         $mail->setFrom($this->_fromemail, $this->_fromname);

      if(trim($this->_bodytext) != '')
         $mail->setBodyText($this->_bodytext);

      if(trim($this->_bodyhtml) != '')
         $mail->setBodyHtml($this->_bodyhtml);

      if(trim($this->_subject) != '')
         $mail->setSubject($this->_subject);

      if(count($this->_to) >= 1)
      {
         reset($this->_to);
         while(list($email, $name) = each($this->_to)) $mail->addTo($email, $name);
      }

      if(count($this->_cc) >= 1)
      {
         reset($this->_cc);
         while(list($email, $name) = each($this->_cc)) $mail->addCc($email, $name);
      }

      if(count($this->_bcc) >= 1)
      {
         reset($this->_bcc);
         while(list($email, $name) = each($this->_bcc)) $mail->addBcc($email, $name);
      }
      return $mail;
   }
}

// Authentication Methods

/**
 * No authentication required.
 *
 * @const       saNone
 */
define('saNone', 'saNone');

/**
 * Plain authentication method.
 *
 * @const       saPlain
 */
define('saPlain', 'saPlain');

/**
 * Login authentication method.
 *
 * @const       saLogin
 */
define('saLogin', 'saLogin');

/**
 * CRAM-MD5 authentication method.
 *
 * @const       saCRAM_MD5
 */
define('saCRAM_MD5', 'saCRAM_MD5');

// Security Protocols

/**
 * No security protocol.
 *
 * @const       spNone
 */
define('spNone','spNone');

/**
 * TLS security protocol.
 *
 * @const       spTLS
 */
define('spTLS','spTLS');

/**
 * SSL security protocol.
 *
 * @const       spSSL
 */
define('spSSL','spSSL');

/**
 * Base class for transport methods.
 *
 * {@internal Inherit from this class if you want to create your own transport method.}}
 *
 */
class ZMailTransport extends Component
{
}

/**
 * Transport method to send emails from an SMTP server.
 *
 * @link        http://framework.zend.com/manual/en/zend.mail.sending.html Zend Framework Documentation
 */
class ZMailTransportSMTP extends ZMailTransport
{

   // Host

   /**
    * SMTP server address.
    *
    * @see $_port
    *
    * @var      string
    */
   protected $_host = "127.0.0.1";

   /**
    * SMTP server address.
    *
    * @see $_port
    */
   function getHost()    {return $this->_host;}

   /**
    * Setter method for $_host.
    *
    * @param    string  $value
    */
   function setHost($value)    {$this->_host = $value;}

   /**
    * Getter for $_host's default value.
    *
    * @return   string  Local host ('127.0.0.1')
    */
   function defaultHost()    {return "127.0.0.1";}

   // Authentication Method

   /**
    * Authentication method.
    *
    * Possible values are: saNone, saPlain, saLogin, or saCRAM_MD5.
    *
    * @var      string
    */
   protected $_authentication = saNone;

   /**
    * Authentication method.
    *
    * Possible values are: saNone, saPlain, saLogin, or saCRAM_MD5.
    */
   function getAuthentication()    {return $this->_authentication;}

   /**
    * Setter method for $_authentication.
    *
    * @param    string  $value
    */
   function setAuthentication($value)    {$this->_authentication = $value;}

   /**
    * Getter for $_authentication's default value.
    *
    * @return   string  saNone
    */
   function defaultAuthentication()    {return saNone;}

   // Username

   /**
    * Username for user authentication.
    *
    * This property is only needed when $_authentication is not set to
    * saNone.
    *
    * @see $_userpassword
    *
    * @var      string
    */
   protected $_username = "";

   /**
    * Username for user authentication.
    *
    * This property is only needed when $_authentication is not set to
    * saNone.
    *
    * @see $_userpassword
    */
   function getUserName()    {return $this->_username;}

   /**
    * Setter method for $_username.
    *
    * @param    string  $value
    */
   function setUserName($value)    {$this->_username = $value;}

   /**
    * Getter for $_username's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserName()    {return "";}

   // Password

   /**
    * User password.
    *
    * This property is only needed when $_authentication is not set to
    * saNone.
    *
    * @see $_username
    *
    * @var      string
    */
   protected $_userpassword = "";

   /**
    * User password.
    *
    * This property is only needed when $_authentication is not set to
    * saNone.
    *
    * @see $_username
    */
   function getUserPassword()    {return $this->_userpassword;}

   /**
    * Setter method for $_userpassword.
    *
    * @param    string  $value
    */
   function setUserPassword($value)    {$this->_userpassword = $value;}

   /**
    * Getter for $_userpassword's default value.
    *
    * @return   string  Empty string
    */
   function defaultUserPassword()    {return "";}

   // Security Protocol

   /**
    * Security protocol.
    *
    * Possible values are: spNone, spSSL, or spTLS.
    *
    * @var      string
    */
   protected $_secureprotocol = spNone;

   /**
    * Security protocol.
    *
    * Possible values are: spNone, spSSL, or spTLS.
    */
   function getSecureProtocol()    {return $this->_secureprotocol;}

   /**
    * Setter method for $_secureprotocol.
    *
    * @param    string  $value
    */
   function setSecureProtocol($value)    {$this->_secureprotocol = $value;}

   /**
    * Getter for $_secureprotocol's default value.
    *
    * @return   string  spNone
    */
   function defaultSecureProtocol()    {return spNone;}

   // Port

   /**
    * Port number where SMTP server can be reached.
    *
    * @see $_host
    *
    * @var      string
    */
   protected $_port = '';

   /**
    * Port number where SMTP server can be reached.
    *
    * @see $_host
    */
   function getPort()    {return $this->_port;}

   /**
    * Setter method for $_port.
    *
    * @param    string  $value
    */
   function setPort($value)    {$this->_port = $value;}

   /**
    * Getter for $_port's default value.
    *
    * @return   string  Empty string
    */
   function defaultPort()    {return '';}

   /**
    * Returns an instance of Zend_Mail_Transport_Smtp with provided settings.
    *
    * This method is called from both ZMail::send() and ZMail::createMail(), you
    * will rarely need to manually call it.
    *
    * @return Zend_Mail_Transport_Smtp
    *
    * @internal
    */
   function readTransport()
   {
      $config = array();

      switch($this->_authentication)
      {
         case saPlain: $config['auth'] = 'plain';break;
         case saLogin: $config['auth'] = 'login';break;
         case saCRAM_MD5: $config['auth'] = 'crammd5';break;
      }


      if($this->_username != '')
         $config['username'] = $this->_username;
      if($this->_userpassword != '')
         $config['password'] = $this->_userpassword;

      switch($this->_secureprotocol)
      {
          case spSSL: $config['ssl']='ssl';break;
          case spTLS: $config['ssl']='tls';break;
      }

      if($this->_port!='')
      {
          $config['port'] = $this->_port;
      }

      $result = new Zend_Mail_Transport_Smtp($this->_host, $config);

      return ($result);
   }

}

/**
 * Transport method to send emails through Sendmail.
 *
 * @link        http://framework.zend.com/manual/en/zend.mail.introduction.html#zend.mail.introduction.sendmail Zend Framework Documentation
 */
class ZMailTransportSendmail extends ZMailTransport
{

   // Parameters

   /**
    * Additional parameters.
    *
    * ZMailTransportSendmail is basically a wrapper for mail() PHP function. This
    * property is the equivalent to the fifth parameter to that function, additional_parameters,
    * so check PHP Documentation (http://php.net/manual/en/function.mail.php) for additional
    * information.
    *
    * It is common to use this property to set the sender email address through Sendmail -f
    * option. For example: '-finfo@example.com'.
    *
    * @var      string
    */
   protected $_parameters = "";

   /**
    * Additional parameters.
    *
    * ZMailTransportSendmail is basically a wrapper for mail() PHP function. This
    * property is the equivalent to the fifth parameter to that function, additional_parameters,
    * so check PHP Documentation (http://php.net/manual/en/function.mail.php) for additional
    * information.
    *
    * It is common to use this property to set the sender email address through Sendmail -f
    * option. For example: '-finfo@example.com'.
    */
   function getParameters()    {return $this->_parameters;}

   /**
    * Setter method for $_parameters.
    *
    * @param    string  $value
    */
   function setParameters($value)    {$this->_parameters = $value;}

   /**
    * Getter for $_parameters's default value.
    *
    * @return   string  Empty string
    */
   function defaultParameters()    {return "";}

   /**
    * Returns an instance of Zend_Mail_Transport_Sendmail with provided settings.
    *
    * This method is called from both ZMail::send() and ZMail::createMail(), you
    * will rarely need to manually call it.
    *
    * @return Zend_Mail_Transport_Sendmail
    *
    * @internal
    */
   function readTransport()
   {
      $result = new Zend_Mail_Transport_Sendmail($this->_parameters);
      return ($result);
   }

}

?>
