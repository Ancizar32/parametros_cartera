<?php

/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Abril
 */
class municipios_controller extends ControladorBase
{

    //Se declara la variable que nos permitira acceder a los metodos del modelo
    private $modeloInformix = null;
    /**
     * se llama al constructor del padre
     */
    function __construct()
    {
        parent::__construct();
        $this->modeloInformix = cargarModel('municipios');
        $this->modeloInformix->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
    }

    /**
     * Permite cargar la informacion necesaria para la vista principal
     */
    function index()
    {
        $coddpt = (isset($_REQUEST['depto']) ? trim($_REQUEST['depto']) : "");
        $municipios = $this->filtrar_municipios($coddpt);

        $datos_vista['listado'] = $municipios;
        $vista = cargarView("municipios");
        $vista->asignarVariable($datos_vista);
        $vista->dibujar();
    }

    function filtrar_municipios($coddpt)
    {
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->filtrar_municipios($coddpt);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();

        $municipios = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $array =  array();
            $array['coddepto'] = trim($reg['coddpe']);
            $array['codigo'] = trim($reg['codigo']);
            $array['nombre'] = utf8_encode(ucwords(strtolower(trim($reg['nombre']))));
            $array['listaele'] = trim($reg['electronica']);
            $array['listamad'] = trim($reg['madera']);
            $array['listaele_cont'] = trim($reg['electr_cont']);
            $array['listamad_cont'] = trim($reg['madera_cont']);
            $array['flete'] = trim($reg['fletes']);
            $estado = trim($reg['estado']);
            $impuesto = trim($reg['impuesto']);
            $array['estado'] = $estado;
            $array['impuesto'] = $impuesto;
            array_push($municipios, $array);
        }
        return $municipios;
    }

    /**
     * Esta función permite refrescar la tabla con los filtros de busqueda
     */
    function refrescarTablaMunicipios()
    {
        $coddpt = (isset($_REQUEST['depto']) ? trim($_REQUEST['depto']) : "");
        //Se consultan las zonas en la base de datos
        $listado = $this->filtrar_municipios($coddpt);
        $vista = cargarView("municipios");
        echo $vista->verListado($listado);
    }

    /**
     * Esta función permite inicializar el selector de departamentos y permite realizar la busqueda 
     * de las coincidencias cuando se ingresa una cadena de busqueda
     */
    function fillOutAutocomplete()
    {
        $modeloInformix = $this->modeloInformix;
        $return['results'] = [];
        $term = $_POST['data']['term'];
        $term = strtoupper($term);
        $modeloInformix->getDepartamentos(['term' => $term]);
        $datosOdbc = $modeloInformix->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc, function (&$item) {
            $item = utf8_encode(ucwords(strtolower(trim($item))));
        });
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);

        foreach ($datosOdbc as $key => $value) {
            $return['results'][] =  $value;
        }
        echo json_encode($return);
    }

    /**
     * Esta función inicializa el modal de crear municipio
     */
    function crear_municipio()
    {
        $vista = cargarView("municipios");
        $vista->crear_municipios();
    }

    /**
     * Este metodo permite obtener la información del departamento
     */
    function obtInfoDepartamento()
    {
        $coddpt = (isset($_REQUEST['depto']) ? trim($_REQUEST['depto']) : "");
        $descdep = $this->get_descdep($coddpt);
        //var_dump($listado);
        $vista = cargarView("municipios");
        if ($descdep != "") {
            //Se consultan las zonas en la base de datos
            $listado = $this->filtrar_municipios($coddpt);
            //var_dump($listado);
            $vista = cargarView("municipios");
            $datos_respuesta['bandera'] = 0;
            $datos_respuesta['nombre'] = $descdep;
            $datos_respuesta['coddpt'] = $coddpt;
            $datos_respuesta['lista'] = $vista->verMunicipios($listado);
            $response['objetojson'] = $datos_respuesta;
        } else {
            $datos_respuesta['bandera'] = 1;
            $datos_respuesta['coddpt'] = "";
            $datos_respuesta['lista'] = $vista->verMunicipios(null);
            $response['objetojson'] = $datos_respuesta;
        }
        echo json_encode($response);
    }

    /**
     * Esta función permite obtener la descripcion de un departamento
     */
    function get_descdep($coddpt)
    {
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->get_nombredepto($coddpt);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $descdep = utf8_encode(ucwords(strtolower(trim($reg['depato']))));
        return  $descdep;
    }

    /**
     * Esta función se encarga de crear o modificar un departamento o municipio 
     */
    function procesar_depto()
    {
        $coddpt = (isset($_REQUEST['coddep']) ? trim($_REQUEST['coddep']) : "");
        $coddepto = (isset($_REQUEST['coddepto']) ? trim($_REQUEST['coddepto']) : "");
        $descdep = (isset($_REQUEST['descdep']) ? trim($_REQUEST['descdep']) : "");
        $municipios = (isset($_REQUEST['municipios']) ? trim($_REQUEST['municipios']) : "");
        $modificados = (isset($_REQUEST['modificados']) ? trim($_REQUEST['modificados']) : "");

        $ubicaciones = json_decode($municipios, true);
        //Si es un departamento nuevo se crea con sus respectivos municipios
        if ($coddepto == "") {
            foreach ($ubicaciones as $detalle) {
                $this->insert_municipios($coddpt, $descdep, $detalle);
            }
        } else {
            //Si la lista de modificados es diferente de vacia de deben actualizar los registros
            $ubicaciones_mod = json_decode($modificados, true);
            if (isset($ubicaciones_mod)) {
                foreach ($ubicaciones_mod as $detalle) {
                    $this->update_municipios($coddpt, $descdep, $detalle);
                }
            }
            //Se consultan los municipios actuales en la base de datos
            $ubica_actual = $this->filtrar_municipios($coddpt);
            //Se crea un foreach que permita comparar los elementos de la lista de objetos actual y de la nueva
            $ubica_nuevas = $this->comparar_ubicaciones($ubicaciones, $ubica_actual);
            //Se ingresan los nuevos municipios asociados al departamento
            if (isset($ubica_nuevas)) {
                foreach ($ubica_nuevas as $detalle) {
                    $this->insert_municipios($coddpt, $descdep, $detalle);
                }
            }
        }
        
        $datos_respuesta['bandera'] = 0;
            $response['objetojson'] = $datos_respuesta;
            echo json_encode($response);
    }

    /**
     * Este metodo permite actualizar un municipio
     */
    function update_municipios($coddpt, $descdep, $detalle)
    {
        $usuario = $_SESSION["session_intranet_login"];
        $array_mod = array();
        $codmun = $detalle['codmuni'];
        $depato = strtoupper($descdep);
        $nombre =  $detalle['nombmuni'];
        $array_mod['depato'] = "'$depato'";
        $array_mod['nombre'] = "'$nombre'";
        $array_mod['electronica'] =  $detalle['listaele'];
        $array_mod['madera'] = $detalle['listamad'];
        $array_mod['electr_cont'] = $detalle['listaele_cont'];
        $array_mod['madera_cont'] = $detalle['listamad_cont'];
        $array_mod['fletes'] = $detalle['flete'];
        $estado = strtoupper(trim($detalle['estado']));
        if ($estado != 'A' && $estado != 'I') {
            $estado = 'I';
        }
        $array_mod['estado'] = "'$estado'";
        $impuesto = strtoupper(trim($detalle['impuesto']));
        if ($impuesto != 'S' && $impuesto != 'N') {
            $impuesto = 'N';
        }
        $array_mod['impuesto'] = "'$impuesto'";
        $array_mod['usrmod'] = "'$usuario'";
        $array_mod['fecmod'] = "today";

        $modeloInformix = $this->modeloInformix;
        $modeloInformix->update_municipio($coddpt, $codmun, $array_mod);
    }

    /**
     * Esta función permite comparar las ubicaciones e identificar las diferencias
     */
    function comparar_ubicaciones($a, $b)
    {
        $difference = array();
        foreach ($a as $key => $value) {
            if (is_array($value)) {
                if (!isset($b[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($b[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = array_diff($value, $b[$key]);
                    if ($new_diff != FALSE) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!isset($b[$key]) || $b[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }

    /**
     * Este metodo permite actualizar un municipio
     */
    function insert_municipios($coddpt, $descdep, $detalle)
    {
        $usuario = $_SESSION["session_intranet_login"];
        $array_insert = array();
        $array_insert['coddpe'] = $coddpt;
        $codmun = $detalle['codmuni'];
        $array_insert['codigo'] = $codmun;
        $depato = strtoupper($descdep);
        $nombre =  $detalle['nombmuni'];
        $array_insert['depato'] = "'$depato'";
        $array_insert['nombre'] = "'$nombre'";
        $array_insert['electronica'] =  $detalle['listaele'];
        $array_insert['madera'] = $detalle['listamad'];
        $array_insert['electr_cont'] = $detalle['listaele_cont'];
        $array_insert['madera_cont'] = $detalle['listamad_cont'];
        $array_insert['fletes'] = $detalle['flete'];
        $estado = strtoupper($detalle['estado']);
        if ($estado != 'A' && $estado != 'I') {
            $estado = 'I';
        }
        $array_insert['estado'] = "'$estado'";
        $impuesto = strtoupper($detalle['impuesto']);
        if ($impuesto != 'S' && $impuesto != 'N') {
            $impuesto = 'N';
        }
        $constante_no = "'N'";
        $array_insert['impuesto'] = "'$impuesto'";
        $array_insert['usrcrea'] = "'$usuario'";
        $array_insert['feccrea'] = "today";

        $array_insert['imp_mad_pg_corto_plazo'] = $constante_no;
        $array_insert['imp_mad_pg_plazo'] = $constante_no;
        $array_insert['imp_mad_pg_unico'] = $constante_no;
        $array_insert['imp_elec_pg_corto_plazo'] = $constante_no;
        $array_insert['imp_elec_pg_plazo'] = $constante_no;
        $array_insert['imp_elec_pg_unico'] = $constante_no;
        $array_insert['imp_colch_pg_corto_plazo'] = $constante_no;
        $array_insert['imp_colch_pg_plazo'] = $constante_no;
        $array_insert['imp_colch_pg_unico'] = $constante_no;
        $array_insert['imp_otros_pg_corto_plazo'] = $constante_no;
        $array_insert['imp_otros_pg_plazo'] = $constante_no;
        $array_insert['imp_otros_pg_unico'] = $constante_no;
        $array_insert['cnt_mujeres'] = $constante_no;
        $array_insert['cnt_mujeres'] = $constante_no;

        $modeloInformix = $this->modeloInformix;
        $modeloInformix->insert_municipios($array_insert);
    }
}
