<?php
$controlador='index';

/* El controlador que se ejecutara por defecto junto con su metodo por defecto */
define('CONTROLADOR_DEFECTO', $controlador);
define('ACCION_DEFECTO', 'index');
session_name("intranet");
session_start();

/*
  todos los proyectos apuntaran a la raiz de la carpeta que contiene el MVC junto con todas las funciones y librerias que se usaran
  y de esa forma reutilizar codigo y ahorrar espacio en disco.
 */
define('RUTA_IMG', 'image_request/');
define('RUTA_MVC', $_SERVER['DOCUMENT_ROOT'] . '/application/');
define('RUTA_RECURSOS', '/application/');
define("INICIAR_SIMULADOR", false);
//echo RUTA_MVC;

/* lineas para destruir la session en caso de querer volver a consultar los datos (SIMULADOR)*/
/* 
session_destroy();
session_write_close();
*/

/* Se deben usar las constantes del archivo que posee las credenciales ODBC Y FTP 'connection2.php' para la conexion a la base de datos.
  Si necesita usar mas de una conexion diferente puede agregar mas constantes */
 //define("ODBCINFORMIX", 'intranetyency'); //ifxibgdir
 //define("ODBCINFORMIX", 'ifxibgdir'); //ifxibgdir
 //define("ODBC_USERINFORMIX", USER100_1_ODBC);
 //define("ODBC_PASSINFORMIX", PASS100_1_ODBC);

 define("ODBCINFORMIX", 'copias-prueba'); //copias
 define("ODBC_USERINFORMIX", 'informix');
 define("ODBC_PASSINFORMIX", 'IbgPicas0');



?>
