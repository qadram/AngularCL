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
 * This class is used by the template engine to work with Smarty templates.
 *
 * This class inherits from PageTemplate and implements the required methods to make
 * the template engine work with Smarty templates.
 *
 * You don't need to use this class directly, as the template engine takes care of
 * everything to show up your template. You can check this class if you plan to develop
 * your own template engine as it provides everything needed to implement PageTemplate
 * methods.
 *
 * @link http://smarty.php.net/
 *
 */
class SmartyTemplate extends PageTemplate
{
        public $_smarty=null;
        public $_headercode='';
        public $_startform='';
        public $_endform='';
        public $_mobilebodycode='';

        /**
         * Clears the template cache.
         */
        function clear()
        {
            $this->_smarty->clear_all_cache();
        }

        // Documented in the parent.
        function initialize()
        {
                require_once("smarty/libs/SmartyBC.class.php");

                $this->_smarty = new SmartyBC;

                $this->_smarty->left_delimiter='{%';
                $this->_smarty->right_delimiter='%}';
                $this->_smarty->template_dir = '';

                if ( preg_match( "/^WIN/i", PHP_OS ) )
                {
                     if ( isset( $_ENV['TMP'] ) )
                     {
                         $this->_smarty->compile_dir = $_ENV['TMP'];
                     }
                     elseif( isset( $_ENV['TEMP'] ) )
                     {
                         $this->_smarty->compile_dir = $_ENV['TEMP'];
                     }
                     else
                     {
                        $tmpfolder=getenv('TMP');
                        if (($tmpfolder===false) || ($tmpfolder==''))
                        {
                          $tmpfolder=getenv('TEMP');
                          if (($tmpfolder===false) || ($tmpfolder==''))
                          {
                            $tmpfolder='/tmp';
                          }
                        }

                         $this->_smarty->compile_dir = $tmpfolder;
                     }
                 }
                 else
                 {
                     $this->_smarty->compile_dir = '/tmp';
                 }

                 $this->_smarty->cache_dir=$this->_smarty->compile_dir;
        }

        /**
        * This method gets the code for the header section of the template
        *
        * This method is called by the template engine to get the header code for
        * the template
        *
        * @return string
        */
        function headercode()
        {
            if ($this->_headercode=="")
            {
                $form=$this->owner;

                ob_start();
                $form->callEvent('onshowheader',array());
                $form->dumpHeaderInitCode('&nolayouts=1');
                $contents=ob_get_contents();
                ob_end_clean();
                $this->_headercode=$contents;
            }
            return($this->_headercode);
        }

        /**
        * This method gets the code for the starting form
        *
        * This method is called by the template engine to get the starting code for the
        * form.
        *
        * @return string
        */
        function startform()
        {
            if ($this->_startform=="")
            {
                $form=$this->owner;

                $this->_startform=$form->readStartForm().$form->dumpChildrenFormItems(true);
            }
            return($this->_startform);
        }

        /**
         * Assigns $_mobilebodycode the code for the body of the page if it is a child of CustomMPage (e.g. MPage).
         */
        function mobileBodyCode()
        {
            if ($this->_mobilebodycode=='')
            {
                $form=$this->owner;
                //Must be here, so the target of the form is set properly
                if ($form->inheritsfrom('CustomMPage'))
                {
                        ob_start();
                        $form->dumpStartBody('','','&nolayouts=1');
                        $this->_mobilebodycode=ob_get_contents();
                        ob_end_clean();
                }
            }
        }

        /**
        * This method calls the EndForm property of the Page and return its value
        *
        * Use this method to get the end part of the form declaration used by the
        * page, actually </form>
        *
        * @return string
        */
        function endform()
        {
            if ($this->_endform=="")
            {
                $form=$this->owner;
                $this->_endform=$form->readEndForm();
            }
            return($this->_endform);
        }

        function assignComponents()
        {
                $form=$this->owner;

                $this->_smarty->assign('HeaderCode', $this->headercode());
                //Must be before dumping the startform, as it sets the target if the action is empty
                $this->_smarty->assign('MobileBodyCode', $this->mobileBodyCode());
                $this->_smarty->assign('StartForm', $this->startform());
                $this->_smarty->assign('EndForm', $this->endform());

                reset($form->controls->items);
                while (list($k,$v)=each($form->controls->items))
                {
                        $dump = false;

                        if( $v->Visible && !$v->IsLayer )
                        {
                            if( $v->Parent->methodExists('getActiveLayer') )
                            {
                                $dump = ( (string)$v->Layer == (string)$v->Parent->Activelayer );
                            }
                            else
                            {
                                $dump = true;
                            }
                        }

                        if ($dump)
                        {
                            if ($v->inheritsFrom('QControl')) $style .=' width:'.$v->Width.'px;height:'.$v->Height.'px; ';
                            $code="<div id=\"".$v->Name."_outer\" class=\"inline\">\n";
                            $code.=$v->show(true);
                            $code.="\n</div>\n";
                            $this->_smarty->assign($v->Name, $code);
                        }
                }
        }

        function dumpTemplate()
        {
                $form=$this->owner;
                $tpl=dirname($form->lastresourceread).'/'.basename($form->lastresourceread,'.php');
                $file=$this->FileName;
                $file=str_replace('*',$tpl,$file);
                $this->_smarty->display($file);
        }
}

class VCLTemplate extends SmartyTemplate
{
	/**
    * Replaces all tags in the template and return the template source code
    *
    * This method is called by the template engine before interpret it, that is,
    * before start running the template, the method is called so it replaces all <component:php>
    * tags by the corresponding code.
    *
    * It also inserts the header, startform and endform code.
    *
    * @return string
    */
    function replacetags($tpl_source, $smarty)
    {

        $form=$this->owner;

        $headercode=$this->headercode();

        $startform=$this->startform()."{%php%} global $".$form->Name."; {%/php%}";
        $endform=$this->endform();

        $results=preg_match("/<body([^<>]*)>/",$tpl_source,$matches);
        if (count($matches)>=1)
        {
            $body=$matches[0];
            if ($form->inheritsfrom('CustomMPage'))
            {
                $tpl_source=str_replace($body,$this->_mobilebodycode.$startform,$tpl_source);
            }
            else
            {
                $tpl_source=str_replace($body,"<body id=\"$form->Name\">".$startform,$tpl_source);
            }
        }

        $results=preg_match("/<\/body([^<>]*)>/",$tpl_source,$matches);
        if (count($matches)>=1)
        {
            $body=$matches[0];
            if ($form->inheritsfrom('CustomMPage'))
            {
                ob_start();
                $form->dumpEndBody();
                $endbody=ob_get_contents();
                ob_end_clean();
                $tpl_source=str_replace($body,$endform.$endbody,$tpl_source);
                //$tpl_source=str_replace($body,$bodycode.$startform,$tpl_source);
            }
            else
            {
                $tpl_source=str_replace($body,$endform.$body,$tpl_source);
            }
        }

        $results=preg_match("/<\/head([^<>]*)>/",$tpl_source,$matches);
        if (count($matches)>=1)
        {
            $body=$matches[0];
            $tpl_source=str_replace($body,$headercode.$body,$tpl_source);
        }

        //Process here all <php> tags
        reset($form->controls->items);
        while (list($k,$v)=each($form->controls->items))
        {
            $dump = false;

            if( $v->Visible && !$v->IsLayer )
            {
                if( $v->Parent->methodExists('getActiveLayer') )
                {
                    $dump = ( (string)$v->Layer == (string)$v->Parent->Activelayer );
                }
                else
                {
                    $dump = true;
                }
            }

            if ($dump)
            {
                $expr='/<'.strtolower($v->classname()).':php id="'.$v->Name.'"[\s\w\/<>=\\\"]([^<>]*)>(.*?)<\/'.strtolower($v->classname()).':php>/sm';
                $expr='/<h5b:'.$v->classname().' +id="'.$v->Name.'"(.*?)\/>/sm';

                $results=preg_match($expr,$tpl_source,$matches);


                if (count($matches)>=2)
                {
                    $style=trim(substr($matches[1],0,strlen($matches[1])));

                    if (isset($v->ControlStyle['csTemplateOutput']))
                    {
                        $code=$v->show(true);
                    }
                    else
                    {
                        $code="{%php%}global $".$v->owner->Name."; $".$v->owner->Name."->".$v->Name."->show();{%/php%}";
                        if ($style!='')
                        {
                        	if ($v->inheritsFrom('Control'))
                        	{
                            	if (!$v->isFixedSize())
                                {
                            		$values=substr(strtolower($style),7,strlen(style)-6);
                               		$st=explode(';',$values);
                                    $style ='';
                                    reset($st);
                                    while(list($key, $val)=each($st))
                                    {
                                    	if (strpos($val,'left:')===false)
                                        {
                                        	if (strpos($val,'width:')===false)
                                            {
                                        		if (strpos($val,'top:')===false)
                                                {
                                                	if (strpos($val,'height:')===false)
                                                    {
                                                    	$style.=$val.';';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                        	}
                        }
                        
                        $styleAttribute = ($style != "") ? " style=\"$style\"" : "";    
                        
                        if ($v->inheritsFrom('Control'))
                        {
                        	if ($v->DivWrap) $code="<div id=\"".$v->Name."_outer\" class=\"inline\"$styleAttribute>".$code."</div>";
                        }
                        else $code="<div id=\"".$v->Name."_outer\" class=\"inline\"$styleAttribute>".$code."</div>";
                    }

                    $tpl_source=preg_replace($expr,$code,$tpl_source);
                }
            }
        }

        reset($form->components->items);
        while (list($k,$v)=each($form->components->items))
        {
            $dump = false;

            if (($v->inheritsFrom('Component')) && (!$v->inheritsFrom('Control')))
            {
                    $dump = true;
            }

            if ($dump)
            {
                $expr='/<'.strtolower($v->classname()).':php id="'.$v->Name.'"[\s\w\/<>=\\\"]([^<>]*)>(.*?)<\/'.strtolower($v->classname()).':php>/sm';
                $expr='/<h5b:'.$v->classname().' +id="'.$v->Name.'"(.*?)\/>/sm';
                $results=preg_match($expr,$tpl_source,$matches);
                if (count($matches)>=2)
                {
                    $code="";
                    $tpl_source=preg_replace($expr,$code,$tpl_source);
                }
            }
        }

        return($tpl_source);
    }

    function assignComponents()
    {
        parent::assignComponents();
        $this->_smarty->registerFilter('pre',array($this,'replacetags'));
    }
}

class RPCLTemplate extends VCLTemplate
{
}

//Template registration
global $TemplateManager;
$TemplateManager->registerTemplate('SmartyTemplate','smartytemplate.inc.php');
$TemplateManager->registerTemplate('VCLTemplate','smartytemplate.inc.php');
$TemplateManager->registerTemplate('RPCLTemplate','smartytemplate.inc.php');

?>