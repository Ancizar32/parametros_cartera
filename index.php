<?php

/* Configuración global */
require_once 'config/global.php';

/* Funciones para el controlador frontal */

require_once RUTA_MVC . 'core/ControladorBase.php';
require_once RUTA_MVC . 'core/VistaBase.php';
require_once 'config/structure.php';
//echo'entraaaa';
/* Cargamos controladores y acciones */
if (isset($_REQUEST["controlador"])) {
  $controllerObj = cargarControlador($_REQUEST["controlador"]);
  lanzarAccion($controllerObj);
} else {

  $controllerObj = cargarControlador(CONTROLADOR_DEFECTO);

  lanzarAccion($controllerObj); 
}
?>
