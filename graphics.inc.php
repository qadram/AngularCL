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
use_unit("controls.inc.php");

define('taNone','taNone');
define('taLeft','taLeft');
define('taCenter','taCenter');
define('taRight','taRight');
define('taJustify','taJustify');

define('fsNormal','fsNormal');
define('fsItalic','fsItalic');
define('fsOblique','fsOblique');

define('caCapitalize','caCapitalize');
define('caUpperCase','caUpperCase');
define('caLowerCase','caLowerCase');
define('caNone','caNone');

define('vaNormal','vaNormal');
define('vaSmallCaps','vaSmallCaps');

define('psDash', 'psDash');
define('psDashDot', 'psDashDot');
define('psDashDotDot', 'psDashDotDot');
define('psDot', 'psDot');
define('psSolid', 'psSolid');

define('FLOW_LAYOUT','FLOW_LAYOUT');
define('XY_LAYOUT','XY_LAYOUT');
define('ABS_XY_LAYOUT','ABS_XY_LAYOUT');
define('REL_XY_LAYOUT','REL_XY_LAYOUT');
define('GRIDBAG_LAYOUT','GRIDBAG_LAYOUT');
define('ROW_LAYOUT','ROW_LAYOUT');
define('COL_LAYOUT','COL_LAYOUT');
define('BOXED_LAYOUT','BOXED_LAYOUT');


/**
 * Layout encapsulation to allow any component to hold
 * controls and render them in very different ways
 *
 * @see FocusControl::readLayout()
 *
 */
class Layout extends Persistent
{
            public $_control=null;

            private $_type=ABS_XY_LAYOUT;

        /**
        * Type of this layout, it can be any value of the available ones:
        *
        * FLOW_LAYOUT - Controls are rendered without any layout, that is, one after another
        *
        * XY_LAYOUT - Controls are rendered in their fixed pos, but using HTML tables
        *
        * ABS_XY_LAYOUT - Controls are rendered using absolute position
        *
        * REL_XY_LAYOUT - Controls are rendered using relative positions
        *
        * GRIDBAG_LAYOUT - Controls are rendered in a grid, you can set the Rows and Cols
        *
        * ROW_LAYOUT - Controls are rendered in a single row, Cols property sets how many cells
        *
        * COL_LAYOUT - Controls are rendered in a single column, Rows property sets how many cells
        *
        * @return enum
        */
            function getType() { return $this->_type; }
            function setType($value) { $this->_type=$value; }
            function defaultType() { return ABS_XY_LAYOUT; }

            protected $_rows=5;

            function readOwner()
            {
                return($this->_control);
            }

        /**
        * Assign Layout object to another Layout object, this is done by assigning
        * all Layout properties from one object to another
        *
        * @param object $dest Destination, where the new layout settings are assigned to.
        */
        function assignTo($dest)
        {
                $dest->setCols($this->getCols());
                $dest->setRows($this->getRows());
                $dest->setType($this->getType());
                $dest->setUsePixelTrans($this->getUsePixelTrans());
        }


        /**
        * Rows for this layout, used in GRIDBAG_LAYOUT and COL_LAYOUT
        * @see getCols()
        * @return integer
        */
            function getRows() { return $this->_rows; }
            function setRows($value) { $this->_rows=$value; }
            function defaultRows() { return 5; }

            protected $_cols=5;

        /**
        * Columns for this layout, used in GRIDBAG_LAYOUT and ROW_LAYOUT
        * @see getRows()
        * @return integer
        */
            function getCols() { return $this->_cols; }
            function setCols($value) { $this->_cols=$value; }
            function defaultCols() { return 5; }

            protected $_usepixeltrans=1;

            /**
            * Specifies if the code generated should use a transparent pixel or not
            *
            * To preserve compatibility with older browsers, tables must use a transparent
            * pixel on empty cells to make the table behave correctly, on modern browsers
            * you can set this property to false.
            *
            * @return boolean
            */
            function getUsePixelTrans() { return $this->_usepixeltrans; }
            function setUsePixelTrans($value) { $this->_usepixeltrans=$value; }
            function defaultUsePixelTrans() { return 1; }

        /**
         * Whether or not the layout of the specified component should be printed.
         *
         * @internal
         */
        function canPrintLayout(&$component)
        {
            if(isset($_GET['nolayouts']))
                //
                // Only the layout of the first level of parenthood should be printed.
                //
                // This will result in the layout of controls inside containers other than the main one not being
                // printed. All controls located directly in the page will have a layout, including containers such as
                // panels; but the controls inside those other containers will not have a layout.
                //
                return $component->Owner !== $component->Parent;
            else
                return true;
        }

        /**
        * Dump an absolute layout
        *
        * Dump all controls on the layout using absolute pixel coordinates.
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
        function dumpABSLayout($exclude=array(),$onlycss=false)
        {
            if ($this->_control!=null)
            {
                reset($this->_control->controls->items);
                while (list($k,$v)=each($this->_control->controls->items))
                {
                    if (!empty($exclude))
                    {
                            if (in_array($v->classname(),$exclude))
                            {
                                    continue;
                            }
                    }

                    $dump=false;

                    if( $v->Visible && !$v->IsLayer )
                    {
                        //dump CSS mode
                        if ($onlycss)
                        {
                            $left   =$v->Left;
                            $top    =$v->Top;
                            //$aw     =$v->Width;
                            //$ah     =$v->Height;

                            // no layouts only applies to direct template children
                            if ($this->canPrintLayout($v))
                            {
                            
                                $style   = "z-index: $k;\n";
                                $style  .= "left: ". $left."px;\n";
                                //$style  .= "width: ".$aw."px;\n";
                                //$style  .= "height: ".$ah."px";
                                $style  .= $v->_readCSSSize();
                                $style  .= "position: absolute;\n";
                                $style  .= "top: ".$top."px;\n";


                                echo $v->readCSSDescriptor()."_outer {\n";
                                echo $style."\n";
                                echo "}\n";
                            }

                            $v->showCSS();
                        }
                        else
                        {
                            //dump HTML mode
                            if( $this->_control->methodExists('getActiveLayer') )
                            {
                                $dump = ( (string)$v->Layer == (string)$this->_control->Activelayer );
                            }
                            else
                            {
                                $dump = true;
                            }
                            if ($dump)
                            {
                                
                                echo "<div id=\"".$v->_name."_outer\" >\n";
                                $v->show();
                                echo "\n</div>\n";
                            }
                        }

                    }


                }
            }
        }


        /**
        * Compares top position of two objects, for internal use
        *
        * @see dumpRELLayout
        *
        * @return integer 0=top are equals, +1 $a->Top > $b->Top, -1 $a->Top < $b->Top
        */
        function cmp_obj($a, $b)
        {
            $al = $a->Top;
            $bl = $b->Top;
            if ($al == $bl) {
                return 0;
            }
            return ($al > $bl) ? +1 : -1;
        }


        /**
        * Dump a fixed coordinate layout using relative coordinates
        *
        * Dump all controls in the layout generating div tags using relative coordinates
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpRELLayout($exclude=array(), $onlycss=false)
            {
                if ($this->_control!=null)
                {
                    reset($this->_control->controls->items);
                    $shift = 0;

                    $arrayOfControls = $this->_control->controls->items;
                    usort($arrayOfControls, array(&$this, "cmp_obj"));

                    while (list($k,$v)=each($arrayOfControls))
                    {
                        if (!empty($exclude))
                        {
                            if (in_array($v->classname(),$exclude))
                            {
                                    continue;
                            }
                        }

                        if( $v->Visible && !$v->IsLayer )
                        {
                            if($onlycss)
                            {
                                // no layouts only applies to direct template children
                                if ($this->canPrintLayout($v))
                                {
                                    
                                    $left   =$v->Left;
                                    $top    =$v->Top;
                                    //$aw     =$v->Width;
                                    //$ah     =$v->Height;
                                    $top    = $top - $shift;
                                    $shift  = $shift + $v->Height;// what is this?

                                    $style   = "position: relative;\n";
                                    $style  .= "top: ".$top."px;\n";
                                    $style  .= "left: ".$left."px;\n";
                                    //$style  .= "height: ".$ah."px;\n";
                                    //$style  .= "width: ".$aw."px;\n";
                                    $style  .= $v->_readCSSSize();
                                    $style  .= "z-index: $k;\n";

                                    echo $v->readCSSDescriptor()."_outer {\n";
                                    echo $style."\n";
                                    echo "}\n";
                                
                                }

                                $v->showCSS();
                            }
                            else
                            {
                                if( $this->_control->methodExists('getActiveLayer') )
                                {
                                    $dump = ( (string)$v->Layer == (string)$this->_control->Activelayer );
                                }
                                else
                                {
                                    $dump = true;
                                }
                                if ($dump)
                                {
                                    echo "<div id=\"".$v->_name."_outer\" >\n";
                                    $v->show();
                                    echo "\n</div>\n";
                                }
                            }
                        }
                    }
                }
            }

        /**
        * Dump a fixed coordinate layout using tables
        *
        * Dump all controls in the layout generating tables and placing controls
        * inside the right cells.
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpXYLayout($exclude=array(), $cssonly = false)
            {
             //TODO: CHANGE THIS, this is temporary to show children css, we must to isolate CSS
              if($cssonly)
              {
                $this->dumpFlowLayout($exclude, true);
                return;
              }

                        $x=array();
                        $y=array();
                        $pos=array();
                        //Iterates through controls calling show for all of them

                        reset($this->_control->controls->items);
                        while (list($k,$v)=each($this->_control->controls->items))
                        {
                                $dump=false;

                                if( $v->Visible && !$v->IsLayer )
                                {
                                    if( $this->_control->methodExists('getActiveLayer') )
                                    {
                                        $dump = ( (string)$v->Layer == (string)$this->_control->Activelayer );
                                    }
                                    else
                                    {
                                        $dump = true;
                                    }
                                }

                                if ($dump)
                                {
                                        $left=$v->Left;
                                        $top=$v->Top;
                                        $aw=$v->Width;
                                        $ah=$v->Height;

                                        $x[]=$left;
                                        $x[]=$left+$aw;
                                        $y[]=$top;
                                        $y[]=$top+$ah;

                                        $pos[$left][$top]=$v;
                                }
                        }

                        $width=$this->_control->Width;
                        $height=$this->_control->Height;

                        $x[]=$width;
                        $y[]=$height;

                        sort($x);
                        sort($y);


                                //Dumps the inner controls
                                if ($this->_control->controls->count()>=1)
                                {
                                        $widths=array();
                                        reset($x);
                                        if ($x[0]!=0) $widths[]=$x[0];
                                        while (list($k,$v)=each($x))
                                        {
                                                if ($k<count($x)-1)
                                                {
                                                        if ($x[$k+1]-$v!=0) $widths[]=$x[$k+1]-$v;
                                                }
                                                else $widths[]="";
                                        }

                                        $heights=array();
                                        reset($y);
                                        if ($y[0]!=0) $heights[]=$y[0];
                                        while (list($k,$v)=each($y))
                                        {
                                                if ($k<count($y)-1)
                                                {
                                                        if ($y[$k+1]-$v!=0) $heights[]=$y[$k+1]-$v;
                                                }
                                                else $heights[]="";
                                        }


                                        $y=0;
                                        reset($heights);

                                        while (list($hk,$hv)=each($heights))
                                        {
                                                        if ($hv!="")
                                                        {

                                                        }
                                                        else continue;


                                                $rspan=false;

                                                $x=0;
                                                reset($widths);

                                                ob_start();
                                                while (list($k,$v)=each($widths))
                                                {
                                                        $cs=1;
                                                        $rs=1;


                                                        if (isset($pos[$x][$y]))
                                                        {
                                                                if ((!is_object($pos[$x][$y]))  && ($pos[$x][$y]==-1))
                                                                {
                                                                        $x+=$v;
                                                                        continue;
                                                                }
                                                        }

                                                        if (isset($pos[$x][$y]))
                                                        {
                                                                $control=$pos[$x][$y];
                                                        }
                                                        else $control=null;

                                                        $w=0;

                                                        if (is_object($control))
                                                        {
                                                                $w=$control->Width;
                                                                $h=$control->Height;

                                                                $tv=0;
                                                                $th=0;

                                                                $also=array();

                                                                for ($kkk=$hk;$kkk<count($heights);$kkk++)
                                                                {
                                                                        if ($heights[$kkk]!='')
                                                                        {
                                                                                $tv+=$heights[$kkk];
                                                                                if ($h>$tv)
                                                                                {
                                                                                        $rs++;
                                                                                        $pos[$x][$y+$tv]=-1;
                                                                                        $also[]=$y+$tv;
                                                                                }
                                                                                else break;
                                                                        }
                                                                }

                                                                for ($ppp=$k;$ppp<count($widths);$ppp++)
                                                                {
                                                                        if ($widths[$ppp]!='')
                                                                        {
                                                                                $th+=$widths[$ppp];

                                                                                if ($w>$th)
                                                                                {
                                                                                        $cs++;
                                                                                        $pos[$x+$th][$y]=-1;

                                                                                        reset($also);
                                                                                        while(list($ak,$av)=each($also))
                                                                                        {
                                                                                                $pos[$x+$th][$av]=-1;
                                                                                        }
                                                                                }
                                                                                else break;
                                                                        }
                                                                }
                                                        }


                                                        $width="";
                                                        if ($v!="")
                                                        {
                                                                $zv=round(($v*100)/$this->_control->Width,2);
                                                                $zv.="%";
                                                                $width=" width=\"$v\" ";
                                                        }

                                                        if ($rs!=1)
                                                        {
                                                                $rspan=true;
                                                                $rs=" rowspan=\"$rs\" ";
                                                        }
                                                        else $rs="";

                                                        if ($cs!=1)
                                                        {
                                                                $cs=" colspan=\"$cs\" ";
                                                                $width="";
                                                        }
                                                        else $cs="";

                                                        $hh="";

                                                        echo "<td $width $hh $rs $cs valign=\"top\">";

                                                        if (is_object($control))
                                                        {
                                                                echo "<div id=\"".$control->Name."_outer\">\n";
                                                                $control->show();
                                                                echo "\n</div>\n";
                                                        }
                                                        else
                                                        {
															if ($this->_usepixeltrans) echo '<img src="'.RPCL_HTTP_PATH.'/images/pixel_trans.gif" width="1" height="1" alt=" " >';
                                                        }

                                                        echo "</td>\n";
                                                        $x+=$v;
                                                }
                                                $trow=ob_get_contents();
                                                ob_end_clean();
                                                if ($hv!="")
                                                {
                                                        $zhv=round(($hv*100)/$this->_control->Height,2);
                                                        $zhv.="%";
                                                        echo "<tr height=\"$hv\">";
                                                }
                                                echo $trow;
                                                echo "</tr>\n";
                                                $y+=$hv;
                                        }
                                }
                                else
                                {
                                        echo "<tr><td>";
                                        if ($this->_usepixeltrans) echo '<img src="'. RPCL_HTTP_PATH .'/images/pixel_trans.gif" width="1" height="1"  alt=" ">';
                                        echo "</td></tr>";
                                }

                        reset($this->_control->controls->items);
                        while (list($k,$v)=each($this->_control->controls->items))
                        {
                                if (($v->Visible) && ($v->IsLayer))
                                {
                                        echo "<div id=\"".$v->Name."_outer\">\n";
                                        $v->show();
                                        echo "\n</div>\n";
                                }
                        }
            }

        /**
        * Dump a flow layout, basically, no layout at all
        *
        * This type of layout simply dumps controls in their creation order, one
        * after another.
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpFlowLayout($exclude=array(), $onlycss=false)
            {

                //Iterates through controls calling show for all of them
                reset($this->_control->controls->items);
                while (list($k,$v)=each($this->_control->controls->items))
                {
                    if (!empty($exclude))
                    {
                            if (in_array($v->classname(),$exclude))
                            {
                                    continue;
                            }
                    }

                    if( $v->Visible && !$v->IsLayer )
                    {
                        // dump CSS mode
                        if($onlycss)
                        {
                            $v->showCSS();
                        }
                        else
                        {
                            if( $this->_control->methodExists('getActiveLayer') )
                            {
                                $dump = ( (string)$v->Layer == (string)$this->_control->Activelayer );
                            }
                            else
                            {
                                $dump = true;
                            }
                            // dump HTML mode
                            if ($dump)
                            {
                                echo "<div id=\"".$v->_name."_outer\" >\n";
                                $v->show();
                                echo "\n</div>\n";
                            }
                        }
                    }
                }

            }

        /**
        * Dump the layout contents depending on the layout type.
        *
        * It checks the type it has to dump
        * and calls the appropiate method, you can also exclude certain controls to be rendered by
        * passing an array with the classnames of the components you don't want to get rendered
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpLayoutContents($exclude=array(),$onlycss=false)
            {
                if ($onlycss==false)
                {
                  if (isset($_GET['css']))
                  {
                    $onlycss=true;
                  }
                }

                switch($this->_type)
                {
                    // REMOVED TEMPORARY
                    case COL_LAYOUT: $this->dumpColLayout($exclude, $onlycss); break;
                    case ROW_LAYOUT: $this->dumpRowLayout($exclude, $onlycss); break;
                    case GRIDBAG_LAYOUT: $this->dumpGridBagLayout($exclude, $onlycss); break;
                    case ABS_XY_LAYOUT: $this->dumpABSLayout($exclude, $onlycss); break;
                    case REL_XY_LAYOUT: $this->dumpRELLayout($exclude, $onlycss); break;
                    case XY_LAYOUT: $this->dumpXYLayout($exclude, $onlycss); break;
                    case FLOW_LAYOUT: $this->dumpFlowLayout($exclude, $onlycss); break;
                    case BOXED_LAYOUT: $this->dumpBoxedLayout($exclude, $onlycss); break;
                }
            }

        /**
        * Dump a flow layout, but elements are boxed to keep their width,height and left position
        *
        * The layout orders the items by its top value
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpBoxedLayout($exclude=array(), $onlycss)
            {
              if ($this->_control!=null)
                {
                        reset($this->_control->controls->items);
                        $arrayOfControls = $this->_control->controls->items;
                        usort($arrayOfControls, array(&$this, "cmp_obj"));

                        while (list($k,$v)=each($arrayOfControls))
                        {
                                if (!empty($exclude))
                                {
                                        if (in_array($v->classname(),$exclude))
                                        {
                                                continue;
                                        }
                                }
                                $dump=false;
                                if( $v->Visible && !$v->IsLayer )
                                {
                                    // dump CSS mode
                                    if($onlycss)
                                    {
                                        // no layouts only applies to direct template children
                                         if ($this->canPrintLayout($v))
                                         {
                                         
                                            $left   = $v->Left;
                                            //$aw     = $v->Width;
                                            //$ah     = $v->Height;

                                            $style   = "z-index: $k;\n";
                                            $style  .= "left: ".$left."px;\n";
                                            //$style  .= "width: ".$aw."px;\n";
                                            //$style  .= "height: ".$ah."px;\n";
                                            $style  .= $v->_readCSSSize();
                                            $style  .= "position: relative;\n";
                                            $style  .= "padding:5px;\n";
                                            $style  .= "clear:both;\n";


                                            echo $v->readCSSDescriptor()."_outer {\n";
                                            echo $style."\n";
                                            echo "}\n";

                                            $v->showCSS();
                                         
                                         }
                                    }
                                    else
                                    {
                                        // dump HTML mode
                                        if( $this->_control->methodExists('getActiveLayer') )
                                        {
                                            $dump = ( (string)$v->Layer == (string)$this->_control->Activelayer );
                                        }
                                        else
                                        {
                                            $dump = true;
                                        }
                                        if ($dump)
                                        {
                                            echo "<div id=\"".$v->_name."_outer\" >\n";
                                            $v->show();
                                            echo "\n</div>\n";
                                        }
                                    }
                                }

                        }


                }
            }

        /**
        * Dump a table layout
        *
        * This method dump all controls inside using the cols and rows set and using
        * tables.
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpGridBagLayout($exclude=array(), $cssonly = false)
            {
                    $this->dumpGrid($exclude, $this->_cols, $this->_rows, "100%", $cssonly);
            }

        /**
        * Dump a row layout
        *
        * Dumps a 1 row layout.
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpRowLayout($exclude=array(), $cssonly = false)
            {
                    $this->dumpGrid($exclude, $this->_cols, 1, "100%", $cssonly);
            }

        /**
        * Dump a col layout
        *
        * Dumps a 1 col layout
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        */
            function dumpColLayout($exclude=array(), $cssonly = false)
            {
                    $this->dumpGrid($exclude, 1, $this->_rows, "100%", $cssonly);
            }

        /**
        * Dump a grid layout
        *
        * This method is used for rowlayout, collayout and grid layout.
        *
        * @param array $exclude Classnames of the controls you want to exclude from dumping
        * @param integer $cols Number of columns for the grid
        * @param integer $rows Number of rows for the grid
        * @param string $width Width for the layout
        */
            function dumpGrid($exclude=array(),$cols,$rows,$width, $cssonly = false)
            {
              //TODO: CHANGE THIS, this is temporary to show children css, we must to isolate CSS
              if($cssonly)
              {
                $this->dumpFlowLayout($exclude, true);
                return;
              }


                    $pwidth=$this->_control->Width;
                    $pheight=$this->_control->Height;

                    $cwidth = round($pwidth / $cols,0);
                    $cheight = round($pheight / $rows,0);

                    $controls=array();
                        reset($this->_control->controls->items);
                        while (list($k,$v)=each($this->_control->controls->items))
                        {
                            $col=round($v->Left / $cwidth,0);
                            $row=round($v->Top / $cheight,0);

                            $controls[$col][$row]=$v;
                        }

                    echo "<table width=\"$width\" height=\"$pheight\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
                    for($y=0;$y<=$rows-1;$y++)
                    {
                        echo "<tr>\n";
                        for($x=0;$x<=$cols-1;$x++)
                        {
                            if (isset($controls[$x][$y]))
                            {
                                $v=$controls[$x][$y];
                                if (is_object($v))
                                {
                                    $cspan="";
                                    $rspan="";

                                    $cspan = round(($v->Width / $cwidth),0);
                                    if ($cspan > 1)
                                    {
                                        //for ($xx=$x+1;$xx<=$x+$cspan;$xx++)  $controls[$xx][$y]=-1;
                                    }

                                    $rspan = round(($v->Height / $cheight),0);
                                    if ($rspan > 1)
                                    {
                                        //for ($yy=$y+1;$yy<=$y+$rspan;$yy++)  $controls[$x][$yy]=-1;
                                    }


                                    for ($xx=$x;$xx<$x+$cspan;$xx++)
                                    {
                                        for ($yy=$y;$yy<$y+$rspan;$yy++)
                                        {
                                            $controls[$xx][$yy]=-1;
                                        }
                                    }


                                    if ($cspan>1) $cspan=" colspan=\"$cspan\" ";
                                    else $cspan="";

                                    if ($rspan>1) $rspan=" rowspan=\"$rspan\" ";
                                    else $rspan="";

                                    $pw=round((100*$v->Width)/$pwidth);
                                    $pw=" width=\"$pw%\" ";

                                    $ph=round((100*$v->Height)/$pheight);
                                    $ph=" height=\"$ph%\" ";

                                    echo "<td valign=\"top\" $pw $ph $cspan $rspan>\n";
                                        echo "<div id=\"".$v->Name."_outer\" style=\"height:100%;width:100%;\">\n";
                                        $v->show();
                                        echo "\n</div>\n";
                                    echo "\n</td>\n";
                                }
                            }
                            else
                            {
                                echo "<td>&nbsp;\n";
                                echo "</td>\n";
                            }
                        }
                        echo "</tr>\n";
                    }
                    echo "</table>\n";
            }
}

/**
 * Font encapsulates all properties required to represent a font on the browser.
 *
 * Font describes font characteristics used when displaying text. Font defines a set
 * of characters by specifying the height, font name (typeface), attributes (such as bold or
 * italic) and so on.
 *
 * @see Control::readFont()
 *
 */
class Font extends Persistent
{
        protected $_family="Tahoma";
        protected $_size="11px";
        protected $_color="";
        protected $_weight="";
        protected $_align="taNone";
        protected $_style="";
        protected $_case="";
        protected $_variant="";
        protected $_lineheight="";

        public $_control=null;

        private $_updatecounter = 0;

        /**
        * Assign Font object to another Font object, this is done by assigning
        * all Font properties from one object to another
        *
        * @param object $dest Destination, where the new font settings are assigned to.
        */
        function assignTo($dest)
        {
                // make sure modified() is not always called while assigning new values
                $dest->startUpdate();

                $dest->setFamily($this->getFamily());
                $dest->setSize($this->getSize());
                $dest->setColor($this->getColor());
                $dest->setAlign($this->getAlign());
                $dest->setStyle($this->getStyle());
                $dest->setCase($this->getCase());
                $dest->setLineHeight($this->getLineHeight());
                $dest->setVariant($this->getVariant());
                $dest->setWeight($this->getWeight());

                $dest->endUpdate();
        }

        function readOwner()
        {
                return($this->_control);
        }

        /**
        * Call startUpdate() when multiple properties of the Font are updated at
        * the same time. Once finished updating, call endUpdate().
        * It prevents the updating of the control where the Font is assigned to
        * until the endUpdate() function is called.
        */
        function startUpdate()
        {
                $this->_updatecounter++;
        }

        /**
        * Re-enables the notification mechanism to the control.
        * Note: endUpdate() has to be called as many times as startUpdate() was
        *       called on the same Font object.
        */
        function endUpdate()
        {
                $this->_updatecounter--;
                // let's just make sure that if the endUpdate() is called too many times
                // that the $this->_updatecounter is valid and the font is updated
                if ($this->_updatecounter < 0)
                {
                        $this->_updatecounter = 0;
                }
                // when finished updating call the modified() function to notify the control.
                if ($this->_updatecounter == 0)
                {
                        $this->modified();
                }
        }

        /**
        * Indicates if the Font object is in update mode. If true, the control
        * where the Font is assigned to will not be notified when a property changes.
        * @return bool
        */
        function isUpdating()
        {
                return $this->_updatecounter != 0;
        }

        /**
         * Check if the font has been modified to set to false the parentfont
         * property of the control, if any
         */
        function modified()
        {
                if (!$this->isUpdating() && $this->_control!=null  && ($this->_control->_controlstate & csLoading) != csLoading && $this->_control->_name != "")
                {
                        $f=new Font();
                        $fstring=$f->readFontString();

                        $tstring=$this->readFontString();


                        if ($this->_control->ParentFont)
                        {
                                $parent=$this->_control->Parent;
                                if ($parent!=null) $fstring=$parent->Font->readFontString();
                        }

                        // check if font changed and if the ParentFont can be reset
                        if ($fstring!=$tstring && $this->_control->DoParentReset)
                        {
                                $c=$this->_control;
                                $c->ParentFont = 0;
                        }

                        if ($this->_control->methodExists("updateChildrenFonts"))
                        {
                                $this->_control->updateChildrenFonts();
                        }
                }
        }


        /**
        * Font list to be used to render this font, this should be an HTML font
        * family specifier
        *
        * @link http://www.w3.org/TR/REC-CSS2/fonts.html#font-family-prop
        *
        * @return string
        */
        function getFamily() { return $this->_family;   }
        function setFamily($value) { $this->_family=$value; $this->modified(); }
        function defaultFamily() { return "Tahoma";   }

        /**
        * Size to be used to render this font, you can use a unit specifier, for example
        * px, or em
        *
        * @link http://www.w3.org/TR/REC-CSS2/fonts.html#font-size-props
        *
        * @return string
        */
        function getSize() { return $this->_size;       }
        function setSize($value) { $this->_size=$value; $this->modified(); }
        function defaultSize() { return "11px";       }

        /**
        * Height for this font, this correspond to the line paragraph
        *
        * @link http://www.w3.org/TR/REC-CSS2/visudet.html#propdef-line-height
        *
        * @return string
        */
        function getLineHeight() { return $this->_lineheight;       }
        function setLineHeight($value) { $this->_lineheight=$value; $this->modified(); }
        function defaultLineHeight() { return "";       }

        /**
        * Style to be used to render this font, can be one of these values:
        * <pre>
        * fsNormal - No changes applied to the font face
        * fsItalic - Text is rendered in Italic
        * fsOblique - Text is rendered in Oblique
        * </pre>
        * @return string
        */
        function getStyle() { return $this->_style;       }
        function setStyle($value) { $this->_style=$value; $this->modified(); }
        function defaultStyle() { return "";       }

        /**
        * Case conversion to be used to render this font, it allows you to set
        * a modifier to the case the user will see without affecting the information
        *
        * @link http://www.w3.org/TR/REC-CSS2/text.html#propdef-text-transform
        *
        * @return string
        */
        function getCase() { return $this->_case;       }
        function setCase($value) { $this->_case=$value; $this->modified(); }
        function defaultCase() { return "";       }

        /**
        * Variant conversion to be used to render this font
        *
        * @link http://www.w3.org/TR/REC-CSS2/fonts.html#propdef-font-variant
        *
        * @return string
        */
        function getVariant() { return $this->_variant;       }
        function setVariant($value) { $this->_variant=$value; $this->modified(); }
        function defaultVariant() { return "";       }

        /**
        * Color for this font, it should be an HTML valid color, i.e. #FF0000
        * @return string
        */
        function getColor() { return $this->_color;       }
        function setColor($value) { $this->_color=$value; $this->modified(); }
        function defaultColor() { return "";       }

        /**
         * Alignment of the text.
         *
         * @return string
         */
        function getAlign() { return $this->_align;       }
        function setAlign($value) { $this->_align=$value; $this->modified(); }
        function defaultAlign() { return taNone;       }

        /**
        * Specifies the weight (boldness) for this font
        * @return enum
        */
        function getWeight() { return $this->_weight;   }
        function setWeight($value) { $this->_weight=$value; $this->modified(); }
        function defaultWeight() { return "";       }

        /**
         * Returns an style string to be asigned to the tag, it uses all the
         * Font properties to create an style string to be used with an HTML tag
         *
         * @return string
         */
        function readFontString()
        {
            $styles = $this->fontStyles();
            $result = "";
            
            if($this->_family != '')
                $result .=" font-family: $this->_family;\n";
                
            if($this->_size != '')
                $result .=" font-size: $this->_size;\n";
            
            if ($styles != '')
              $result .= "$styles";

            return($result);
        }


        /**
        * Helper function that renders in a string just the styling properties of the Font
        */
        function fontStyles()
        {
                /*
                if ($this->_control!=null)
                {
                        if ($this->_control->ParentFont)
                        {
                                $parent=$this->_control->Parent;
                                if ($parent!=null) return($parent->Font->readFontString());
                        }
                }
                */

                $textalign="";
                switch($this->_align)
                {
                        case taLeft: $textalign="text-align: left;\n"; break;
                        case taRight: $textalign="text-align: right;\n"; break;
                        case taCenter: $textalign="text-align: center;\n"; break;
                        case taJustify: $textalign="text-align: justify;\n"; break;
                }

                $fontstyle="";
                switch($this->_style)
                {
                        case fsNormal: $fontstyle="font-style: normal;\n"; break;
                        case fsItalic: $fontstyle="font-style: italic;\n"; break;
                        case fsOblique: $fontstyle="font-style: oblique;\n"; break;
                }
                $fontvariant="";
                switch($this->_variant)
                {
                        case vaNormal: $fontvariant="font-variant: normal;\n"; break;
                        case vaSmallCaps: $fontvariant="font-variant: small-caps;\n"; break;
                }

                $texttransform="";
                switch($this->_case)
                {
                        case caCapitalize: $texttransform="text-transform: capitalize;\n"; break;
                        case caUpperCase: $texttransform="text-transform: uppercase;\n"; break;
                        case caLowerCase: $texttransform="text-transform: lowercase;\n"; break;
                        case caNone: $texttransform="text-transform: none;\n"; break;
                }

                $color="";
                if ($this->_color!="") $color="color: $this->_color;\n";

                $lineheight="";
                if ($this->_lineheight!="") $lineheight="line-height: $this->_lineheight;\n";

                $fontweight="";
                if ($this->_weight!="") $fontweight="font-weight: $this->_weight;\n";

                return " $color$fontweight$textalign$fontstyle$lineheight$fontvariant$texttransform ";
        }

        /**
         * Returns a string with the CSS code for the current Font configuration.
         */
        function readCSSString()
        {
                $textalign="";
                switch($this->_align)
                {
                        case taLeft: $textalign="left "; break;
                        case taRight: $textalign="right "; break;
                        case taCenter: $textalign="center "; break;
                        case taJustify: $textalign="justify "; break;
                }

                $fontstyle="";
                switch($this->_style)
                {
                        case fsNormal: $fontstyle="normal "; break;
                        case fsItalic: $fontstyle="italic "; break;
                        case fsOblique: $fontstyle="oblique "; break;
                }

                // changed to fix RAID#282144
                $fontvariant="";
                switch($this->_variant)
                {
                        case vaNormal: $fontvariant="normal "; break;
                        case vaSmallCaps: $fontvariant="small-caps "; break;
                }

                $texttransform="";
                switch($this->_case)
                {
                        case caCapitalize: $texttransform="capitalize "; break;
                        case caUpperCase: $texttransform="uppercase "; break;
                        case caLowerCase: $texttransform="lowercase "; break;
                        case caNone: $texttransform="none "; break;
                }

                $color="";
                if ($this->_color!="") $color="$this->_color ";

                $lineheight="";
                if ($this->_lineheight!="") $lineheight="$this->_lineheight ";

                $fontweight="";
                if ($this->_weight!="") $fontweight="$this->_weight ";


                $result="$this->_family $this->_size $color$fontweight$textalign$fontstyle$lineheight$fontvariant$texttransform ";
                return($result);
        }
}

/**
 * Pen is used to draw lines or outline shapes on a canvas.
 *
 * Use Pen to describe the attributes of a pen when drawing something to a canvas (Canvas).
 * Pen encapsulates the pen properties that are selected into the canvas.
 *
 * <code>
 * <?php
 *   function PaintBox1Paint($sender, $params)
 *   {
 *    $this->PaintBox1->Canvas->Pen->Color="#FF0000";
 *    $this->PaintBox1->Canvas->Line(0,0,100,100);
 *
 *    $this->PaintBox1->Canvas->Brush->Color="#00FF00";
 *    $this->PaintBox1->Canvas->Rectangle(100,100,200,200);
 *
 *    $this->PaintBox1->Canvas->TextOut(50,50, "RPCL Canvas");
 *   }
 * ?>
 * </code>
 *
 * @see Canvas::getPen()
 *
 */
class Pen extends Persistent
{
        protected $_color="#000000";
        protected $_width="1";
//        protected $_style=psSolid;
        protected $_modified=0;
        public $_control=null;

        function readOwner()
        {
            return($this->_control);
        }

        function assignTo($dest)
        {
                $dest->Color=$this->Color;
                $dest->Width=$this->Width;
//                $dest->Style=$this->Style;
        }

        /**
        * Set this Pen as being modified
        */
        function modified()             { $this->_modified=1; }

        /**
        * Returns true if the properties of the Pen has been modified
        *
        * @return boolean
        */
        function isModified()           { return $this->_modified; }

        /**
        * Sets the modified flag to 0
        */
        function resetModified()        { $this->_modified = 0; }

        /**
        * Determines the color used to draw lines on the canvas.
        *
        * Set Color to change the color used to draw lines or outline shapes.
        *
        * @return string
        */
        function getColor()             { return $this->_color; }
        function setColor($value)       { $this->_color=$value; $this->modified(); }
        function defaultColor()         { return "#000000"; }

        /**
        * Specifies the width of the pen in pixels.
        *
        * Use Width to give the line greater weight. If you attempt to set Width to a
        * value less than 0, the new value is ignored.
        *
        * @return integer
        */
        function getWidth()             { return $this->_width; }
        function setWidth($value)       { $this->_width=$value; $this->modified(); }
        function defaultWidth()         { return "1"; }

        //TODO: Style property
        //Style property
//        function getStyle()             { return $this->_style; }
//        function setStyle($value)       { $this->_style=$value; }
//        function defaultStyle()         { return psSolid; $this->modified(); }
}

/**
 * Brush represents the color and pattern used to fill solid shapes.
 *
 * Brush encapsulates several properties to hold all the attributes to fill solid shapes,
 * such as rectangles and ellipses, with a color or pattern.
 *
 * @see Canvas::getBrush()
 */
class Brush extends Persistent
{
        protected $_color="#FFFFFF";
        protected $_modified=0;
        public $_control=null;

        function readOwner()
        {
            return($this->_control);
        }

        function assignTo($dest)
        {
                $dest->Color=$this->Color;
        }

        /**
        * Mark the brush as modified.
        *
        * This method marks the brush as modified by setting an internal flag to 1
        *
        * @see isModified()
        * @see resetModified()
        */
        function modified()             { $this->_modified=1; }

        /**
        * Returns the status of the internal flag for modified state
        *
        * This function returns the status of the internal flag that marks this brush as modified
        *
        * @see modified()
        * @see resetModified()
        *
        * @return integer
        */
        function isModified()           { return $this->_modified; }

        /**
        * Mark the brush as not modified.
        *
        * This method resets the internal flag to specify it has not been modified
        *
        * @see isModified()
        * @see modified()
        */
        function resetModified()        { $this->_modified = 0; }

        /**
        * Indicates the color of the brush.
        *
        * The Color property determines the color of the brush. This is the color
        * that is used to draw the pattern.
        *
        * @return string
        */
        function getColor()             { return $this->_color; }
        function setColor($value)       { $this->_color=$value; $this->modified(); }
        function defaultColor() { return "";       }
}

/**
 * Create color based on HEX RGB mask
 *
 * This function creates a color using an hexadecimal RGB mask, the mask can be prefixed with #
 * and it returns the color resource.
 *
 * @param resource $img Image resource
 * @param string $hexColor Color in HTML format
 * @return int
 *
 */
function colorFromHex($img, $hexColor)
{
        while (strlen($hexColor) > 6) { $hexColor = substr($hexColor, 1);  };
        sscanf($hexColor, "%2x%2x%2x", $red, $green, $blue);
        return ImageColorAllocate($img, $red, $green, $blue);
}

/**
 * Create Pen based on PenStyle
 *
 * This function creates an array depending on the pen style to represent the
 * pattern for such pen.
 *
 * @param resource $img Image resource to work with
 * @param string $penStyle Style of the pen to create
 * @param string $baseColor Base color to use to create the pen
 * @param string $bgColor Background color to use to create the pen
 * @return array
 */
function createPenStyle($img, $penStyle, $baseColor, $bgColor)
{
        $b  = ColorFromHex($img, $bgColor);
        $w  = ColorFromHex($img, $baseColor);

        switch ($penStyle)
        {
                case psDash:
                        return array($w, $w, $w, $w, $b, $b, $b, $b);
                        break;
                case psDashDot:
                        return array($w, $w, $w, $w, $b, $b, $w, $b, $b);
                        break;
                case psDot:
                        return array($w, $b, $b, $w, $b, $b);
                        break;
                case psDashDotDot:
                        return array($w, $w, $w, $w, $b, $w, $b, $w, $b);
                        break;
                default:
                  //psSolid
                        return array($w);
                        break;
        }
}


?>