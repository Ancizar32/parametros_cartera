<?php

$app = $this;
$app->cargarRequest();
include_once 'texto_idioma.php';

if ($app->cargarVista("Evento") === false) {
    $app->error("No se encontro la vista inicio");
}

$vista = new vistaEvento();

if ($app->cargarModelo("Inicio") === false) {
    $app->error("No de encontro el modelo inicio");
}

$modelo = new modeloInicio();
$modelo->conectar(BD);

$album = array();

$modelo->getAlbum();
while ($reg = $modelo->getRegistro()) {
    $consec = $reg['alb_consec'];
    $album[$consec]['nombre'] = $reg['alb_nombre'];
    $album[$consec]['nombre_i'] = $reg['alb_nombre_i'];
}

$vista->asignarVariable("album", $album);

$empresa = array();
$modelo->getEmpresa();

while ($reg = $modelo->getRegistro()) {
    $empresa['direccion'] = trim($reg['emp_direccion']);
    $empresa['tel'] = trim($reg['emp_tel']);
    $empresa['cel'] = trim($reg['emp_cel']);
    $empresa['otro'] = trim($reg['emp_otro']);
    $empresa['email'] = trim($reg['emp_email']);
    $empresa['nombre'] = trim($reg['emp_nombre']);
    $empresa['texto'] = trim($reg['emp_texto']);
    $empresa['texto_i'] = trim($reg['emp_texto_i']);
    $empresa['favicon'] = trim($reg['emp_img_favicon']);
}

$vista->asignarVariable("empresa", $empresa);

$habitacion = array();

$cont = 0;
$modelo->getHabitacionPromo();
while ($reg = $modelo->getRegistro()) {

    $habitacion[$cont]['consec'] = trim($reg['hab_consec']);
    $habitacion[$cont]['img'] = trim($reg['hab_img']);
    $habitacion[$cont]['nombre'] = trim($reg['hab_nombre']);
    $habitacion[$cont]['nombre_i'] = trim($reg['hab_nombre_i']);
    $habitacion[$cont]['descripcion'] = trim($reg['hab_descripcion']);
    $habitacion[$cont]['descripcion_i'] = trim($reg['hab_descripcion_i']);
    $habitacion[$cont]['costo'] = trim($reg['hab_costo_promo']);
    $habitacion[$cont]['personas'] = ($reg['hab_personas'] == "" || $reg['hab_personas'] < 0) ? 1 : trim($reg['hab_personas']);
    $habitacion[$cont]['camas'] = trim($reg['hab_camas_spl']);
    $habitacion[$cont]['tipo'] = trim($reg['hab_tipo']);
    $servicio = explode(',', trim($reg['hab_servicios']));

    if (trim($reg['hab_tipo']) == 'h') {
        $habitacion[$cont]['tipo'] = $tex_idioma['reserva_tipo' . IDIOMA];
    } else {
        $habitacion[$cont]['tipo'] = $tex_idioma['reserva_tipo2' . IDIOMA];
    }

    $habitacion[$cont]['servicio'] = $servicio;

    $cont ++;
}

$vista->asignarVariable('habitacion', $habitacion);

$evento = array();
$cont = 0;
$modelo->getEvento();
while ($reg = $modelo->getRegistro()) {

    $evento[$cont]['img'] = trim($reg['ev_img']);
    $evento[$cont]['nombre'] = trim($reg['ev_nombre']);
    $evento[$cont]['nombre_i'] = trim($reg['ev_nombre_i']);
    $evento[$cont]['resumen'] = trim($reg['ev_resumen']);
    $evento[$cont]['resumen_i'] = trim($reg['ev_resumen_i']);
    $evento[$cont]['descripcion'] = trim($reg['ev_descripcion']);
    $evento[$cont]['descripcion_i'] = trim($reg['ev_descripcion_i']);
    $evento[$cont]['costo'] = trim($reg['ev_costo']);
    $evento[$cont]['fecha'] = trim($reg['ev_fecha']);

    $cont ++;
}

$vista->asignarVariable('evento', $evento);

$vista->asignarVariable("tex_idioma", $tex_idioma);
$vista->asignarVariable("vista", "reserva");
$vista->cargarTemplate("header");
$vista->pintarContenido();
$vista->cargarTemplate("footer");
?>
