<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <title>Cartera</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="resources/lib/dist/img/favicon-32x32.png">

    <link href="resources/lib/plugins/jQueryUI/jquery-ui-min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap 3.3.4 -->
    <link href="resources/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="resources/lib/bootstrap/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    
    <!-- datapicker -->  
    <link href="resources/lib/bootstrap/css/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="resources/lib/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>
    <link href="resources/lib/dist/css/fileinput.min.css" rel="stylesheet" type="text/css"/>  
   
    <!-- Font Awesome Icons -->
    <link href="resources/lib/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="resources/lib/plugins/tooltipster/tooltipster.css" rel="stylesheet" type="text/css"/>
    <link href="resources/css/solicitudes.css" rel="stylesheet" type="text/css"/>
    <link href="resources/lib/preloader/css/main.css" rel="stylesheet" type="text/css"/>
    <!-- <link href="resources/lib/preloader/css/normalize.css" rel="stylesheet" type="text/css"/> -->

    <!-- Add jQuery library -->
    <!--<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>-->
  
    <!-- jQuery 2.1.4 -->   
    <script src="resources/lib/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="resources/lib/plugins/jQueryUI/jquery-ui-1.10.3.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="<?php echo RUTA_RECURSOS; ?>resources/librerias/js/amcharts/amcharts.js"></script>
    <script type="text/javascript" src="<?php echo RUTA_RECURSOS; ?>resources/librerias/js/amcharts/serial.js"></script>
    <script type="text/javascript" src="<?php echo RUTA_RECURSOS; ?>resources/librerias/js/amcharts/pie.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

    <script type="text/javascript" src="resources/js/amcharts/exporting/amexport.js"></script>
    <script type="text/javascript" src="resources/js/amcharts/exporting/filesaver.js"></script>
    <script type="text/javascript" src="resources/js/amcharts/exporting/canvg.js"></script>
    <script type="text/javascript" src="resources/js/amcharts/exporting/rgbcolor.js"></script>
    
    <!-- jquery validation  1.15.0 -->    
    <script src="resources/lib/plugins/jQuery/jquery.validate.min.js" type="text/javascript"></script>
    <script src="resources/lib/plugins/jQuery/additional-methods.min.js" type="text/javascript"></script>     
    
    <script type="text/javascript" src="resources/js/spin.js"></script>
    <script type="text/javascript" src="resources/js/bootbox.min.js"></script>
    <script src="resources/js/utils.js" type="text/javascript"></script>
    <script src="resources/js/municipios.js" type="text/javascript"></script>



    <!--validacion de jquery validater -->  
    <script src="resources/js/validaciones.js?version=<?php echo time();?>" type="text/javascript"></script>
    <script src="resources/js/datatable.js?version=<?php echo time();?>" type="text/javascript" ></script>
    <!-- Add fancyBox -->
    <script type="text/javascript" src="resources/lib/plugins/fancybox/jquery.fancybox.js?v=2.1.5"></script>
    <link rel="stylesheet" type="text/css" href="resources/lib/plugins/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
    <link rel="stylesheet" type="text/css" href="resources/lib/plugins/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
    <script type="text/javascript" src="resources/lib/plugins/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>  

    <!-- Theme style -->
    <link href="resources/lib/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link href="resources/lib/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />    

    <link href="resources/lib/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <script src="resources/lib/plugins/daterangepicker/moment.js" type="text/javascript"></script>
    <script src="resources/lib/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <link rel="stylesheet" href="resources/css/datatable.css">
    <link rel="stylesheet" href="resources/lib/loading/css/jquery.loadingModal.css">
    <link href="resources/lib/select2/css/select2.min.css" rel="stylesheet" />
    <link href="resources/lib/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
    <link href="resources/lib/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
    <script src="resources/lib/select2/js/select2.min.js"></script>
    <script src="resources/lib/loading/js/jquery.loadingModal.js"></script>
    <script src="resources/lib/jquery-ui/jquery-ui.min.js"></script>
    <script src="resources/lib/bootstrap-notify.min.js"></script>
    <script src="resources/lib/sweetalert2.all.js"></script>
    <script src="resources/lib/bootstrap4-input-clearer.min.js"></script>
    <script src="resources/lib/all.js"></script>
    <script src="resources/js/global.js"></script>


    <script src="resources/lib/contextmenu/dist/jquery.contextMenu.js"></script>
    <script src="resources/lib/contextmenu/dist/jquery.ui.position.min.js"></script>
    <link rel="stylesheet" href="resources/lib/contextmenu/dist/jquery.contextMenu.min.css">

 </head>
 