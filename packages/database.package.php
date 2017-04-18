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
   require_once("rpcl/rpcl.inc.php");
   use_unit("designide.inc.php");

   setPackageTitle("Database RPCL Components");
   setIconPath("./icons");

   addSplashBitmap("RPCL 5.0 from Embarcadero", "h5b.png");

   registerComponents("Data Access",array("Database"),"dbtables.inc.php");
   registerComponents("Data Access",array("Datasource"),"db.inc.php");
   registerComponents("Data Access",array("Table","Query","StoredProc"),"dbtables.inc.php");

   registerComponents("Data Controls",array("DBRepeater"),"dbctrls.inc.php");

   registerPropertyValues("Datasource","DataSet",array('DataSet'));
   registerPropertyValues("DBDataSet","Database",array('Database'));
   registerPropertyValues("DBDataSet","MasterSource",array('Datasource'));

    registerComponentEditor("Database","DatabaseEditor","designide.inc.php");
    registerPropertyEditor("Database", "ConnectionParams", "TValueListPropertyEditor", "native");
    registerPropertyEditor("Database", "DatabaseOptions", "TValueListPropertyEditor", "native");


   registerPropertyValues("DBRepeater","Kind",array('rkHorizontal','rkVertical'));
   registerPropertyValues("DBDataSet","Order",array('asc','desc'));
   registerBooleanProperty("DBRepeater","RestartDataset");
   registerBooleanProperty("CustomTable","HasAutoInc");
   registerBooleanProperty("DataSet","Active");
   registerPropertyEditor("DataSet","MasterFields","TValueListPropertyEditor","native");

   registerDropDatasource(array("DBRepeater"));

   registerPropertyEditor("Query","SQL","TStringListPropertyEditor","native");
   registerPropertyEditor("DBDataSet","Params","TStringListPropertyEditor","native");

   registerBooleanProperty("CustomConnection","Connected");
   registerBooleanProperty("Database","Debug");
   registerBooleanProperty("Database","HostTranslation");
   registerPasswordProperty("CustomConnection","UserPassword");

   registerPropertyValues("Database","DriverName",array(
                                                       // 'cubrid',
                                                       // 'dblib',
                                                        'firebird',
                                                        'ibm',
                                                        'informix',
                                                        'interbase',
                                                        'mysql',
                                                        'oci',
                                                        'odbc',
                                                        'pgsql',
                                                        'sqlite',
                                                        'sqlsrv',
                                                       // '4d',
                                                        ));
?>