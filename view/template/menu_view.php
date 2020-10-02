<?php

$cargo = $_SESSION["session_intranet_cod_cargo_dir"];
$area = $_SESSION["session_intranet_area_dir"];
$nivel = $_SESSION["session_intranet_nivel"];

?>
<body class="skin-blue sidebar-mini " >

    <div id="loader-wrapper">
      <div id="loader"></div>

        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>



        <header class="main-header">

            <!-- Logo -->
            <a href="index.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">IBG</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">Parametros Cartera</span>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar" >
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="resources/img/logotipo.ico" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p><br>Ivan Botero Gomez</p>
                    </div>
                </div>
                                
                <ul  class="sidebar-menu" id="ulmenu" data-id="">
                    <li class="header">Menu Principal</li>
                    <li class="treeview">
                        <a href="index.php?controlador=zonas">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>&nbsp;Zonas</span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="#" id="municipios">
                            <i class="fas fa-map-marked-alt"></i>
                            <span>&nbsp;Municipios</span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="#" id="veredas">
                            <i class="fas fa-map-pin"></i>
                            <span>&nbsp;Veredas</span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="#" id="accounting_notes">
                            <i class="fas fa-file-invoice fa-lg"></i>
                            <span>&nbsp;Conceptos de notas de contabilidad</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#" id="comp_types">
                            <i class="fas fa-clipboard-list fa-lg"></i>
                            <span>&nbsp;Tipos de comprobantes</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#" id="num_docs">
                            <i class="fas fa-list-ol fa-lg"></i>
                            <span>&nbsp;Numeraci&oacute;n de comprobantes</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#" id="concept_mvto">
                            <i class="fas fa-file-invoice fa-lg"></i>
                            <span>&nbsp;Conceptos de movimientos</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#" id="grupo_concepto">
                            <i class="fas fa-layer-group fa-lg"></i>
                            <span>&nbsp;Grupo de conceptos</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#" id="conc_munic_index">
                            <i class="far fa-map fa-lg"></i>
                            <span>&nbsp;Conceptos por municipios</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#" id="migra_caja_index">
                            <i class="far fa-clone fa-lg"></i>
                            <span>&nbsp;Migración Cajas</span>
                        </a>
                    </li>
                </ul>
    
            </section>
            <!-- /.sidebar -->
        </aside>


