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
 */

use_unit("db.inc.php");
use_unit("extctrls.inc.php");


define ('rkHorizontal', 'rkHorizontal');
define ('rkVertical', 'rkVertical');

/**
 * Container that repeats its child controls once for each record in the associated dataset.
 *
 * Use this container to create report-like pages by dropping data-aware components, like Labels, attached to the same
 * Datasource as this container.
 *
 * @link wiki://DBRepeater
 */
class DBRepeater extends Panel
{
            private $_kind=rkVertical;

            /**
             * Direction in which the content of the container should be repeated: either horizontal (rkHorizontal) or
             * vertical (rkVertical).
             *
             * @return string
             */
            function getKind() { return $this->_kind; }
            function setKind($value) { $this->_kind=$value; }
            function defaultKind() { return rkVertical; }

            private $_restartdataset=true;

            /**
             * Whether the associated dataset should be reset to the first record when beginning the rendering of the
             * container and its children (true) or not (false).
             *
             * @return bool
             */
            function getRestartDataset() { return $this->_restartdataset; }
            function setRestartDataset($value) { $this->_restartdataset=$value; }
            function defaultRestartDataset() { return true; }

            private $_limit=0;

            /**
             * Maximum number of records to be rendered by the container.
             *
             * @return int
             */
            function getLimit() { return $this->_limit; }
            function setLimit($value) { $this->_limit=$value; }
            function defaultLimit() { return 0; }


        private $_datasource=null;

        /**
         * Datasource component that will provide the dataset from which this container will retrieve the records to
         * generate rows or columns of data using its child controls.
         *
         * Note: You must associate the child controls of the container to the same Datasource component, and configure
         * them to display whatever field of each record you want them to. This is usually done using their DataField
         * property.
         *
         * @return Datasource
         */
        function getDataSource() { return $this->_datasource;   }
        function setDataSource($value)
        {
                $this->_datasource=$this->fixupProperty($value);
        }

        // Documented in the parent.
        function loaded()
        {
                parent::loaded();
                $this->setDataSource($this->_datasource);
        }

        // Documented in the parent.
        function dumpContents()
        {
                if (($this->ControlState & csDesigning)==csDesigning)
                {
                        parent::dumpContents();
                }
                else
                {
                        if ($this->_datasource!=null)
                        {
                                if ($this->_datasource->DataSet!=null)
                                {
                                        $ds=$this->_datasource->DataSet;

                                        if ($this->_restartdataset) $ds->first();

                                        if (!$ds->EOF)
                                        {
                                                $class = ($this->Style != "") ? "class=\"$this->StyleClass\"" : "";

                                                echo "<table id=\"{$this->_name}_table_detail\" $class>";
                                                $render=0;

                                                if ($this->_kind==rkHorizontal) echo "<tr>";
                                                while (!$ds->EOF)
                                                {
                                                        if ($this->_kind==rkVertical) echo "<tr>";
                                                        $this->callEvent('ondetail',array());
                                                        echo "<td class='td_row'>";
                                                        parent::dumpContents();
                                                        echo "</td>";
                                                        if ($this->_kind==rkVertical) echo "</tr>";
                                                        $ds->next();
                                                        $render++;
                                                        if ($this->_limit!=0)
                                                        {
                                                                if ($render>=$this->_limit) break;
                                                        }
                                                }
                                                if ($this->_kind==rkHorizontal) echo "</tr>";
                                                echo "</table>";
                                        }
                                }
                        }
                }
        }

        // Documented in the parent.
        function dumpAdditionalCSS()
        {
            parent::dumpAdditionalCSS();

            echo "#{$this->_name}_table_detail {\n";
            echo "  width:100%;\n";
            echo "  padding:0px;\n";
            echo "  border-spacing:0px;\n";
            echo "}";

            if ($this->Color != "")
            {
                echo ".td_row {\n";
                echo "background-color:$this->Color;\n";
                echo "}\n";
            }


        }

        // Documented in the parent.
        function __construct($aowner=null)
        {
                //Calls inherited constructor
                parent::__construct($aowner);

                $this->Layout->Type=XY_LAYOUT;
                $this->usetables = 1;
        }

    protected $_ondetail=null;

    /**
     * Triggered right before printing the code for each one of the records.
     */
    function getOnDetail() { return $this->_ondetail; }
    function setOnDetail($value) { $this->_ondetail=$value; }
    function defaultOnDetail() { return null; }
}
