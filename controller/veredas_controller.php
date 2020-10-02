<?php

/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Abril
 */
class veredas_controller extends ControladorBase
{

    //Se declara la variable que nos permitira acceder a los metodos del modelo
    private $modeloInformix = null;
    /**
     * se llama al constructor del padre
     */
    function __construct()
    {
        parent::__construct();
        $this->modeloInformix = cargarModel('veredas');
        $this->modeloInformix->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
    }

    /**
     * Permite cargar la informacion necesaria para la vista principal
     */
    function index()
    {
        $coddpt = (isset($_REQUEST['depto']) ? trim($_REQUEST['depto']) : "");
        $codMun = (isset($_REQUEST['muni']) ? trim($_REQUEST['muni']) : "");
        $veredas = $this->filtrar_veredas($coddpt, $codMun);
        
        $datos_vista['listado'] = $veredas;
        $vista = cargarView("veredas");
        $vista->asignarVariable($datos_vista);
        $vista->dibujar();
    }

    function filtrar_veredas($coddpt, $codMun)
    {
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->filtrar_veredas($coddpt, $codMun);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();

        $municipios = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $array =  array();
            $array['cod_dpto'] = trim($reg['cod_dpto']);
            $array['depato'] = utf8_encode(ucwords(strtolower(trim($reg['depato']))));
            $array['cod_muni'] = trim($reg['cod_muni']);
            $array['nombre'] = trim($reg['nombre']);
            $array['cod_vecor'] = trim($reg['cod_vecor']);
            $array['nom_vecor'] = utf8_encode(ucwords(strtolower(trim($reg['nom_vecor']))));
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
    function refrescarTablaVeredas()
    {
        $coddpt = (isset($_REQUEST['depto']) ? trim($_REQUEST['depto']) : "");
        $codMun = (isset($_REQUEST['muni']) ? trim($_REQUEST['muni']) : "");
        //Se consultan las zonas en la base de datos
        $veredas = $this->filtrar_veredas($coddpt, $codMun);
        $vista = cargarView("veredas");
        echo $vista->verListado($veredas);
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
     * Esta función permite inicializar el selector de departamentos y permite realizar la busqueda 
     * de las coincidencias cuando se ingresa una cadena de busqueda
     */
    function autocomplete_municipios()
    {
        $modeloInformix = $this->modeloInformix;
        $return['results'] = [];
        $term = $_POST['data']['term'];
        $depto = $_POST['params'];
        $term = ucwords(strtolower($term));
        $modeloInformix->get_municipios(['term' => $term, 'depto' => $depto]);
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
    function crear_vereda()
    {
        $estados = array();
        array_push($estados, array('codigo' => "A", 'nombre' => "Activa"));
        array_push($estados, array('codigo' => "I", 'nombre' => "Inactiva"));
        
        $datos_vista['estados'] = $estados;
        $vista = cargarView("veredas");
        $vista->asignarVariable($datos_vista);
        $vista->crear_veredas();
    }
    
    function mod_vereda(){
        $detalle = (isset($_REQUEST['detalle']) ? trim($_REQUEST['detalle']) : "");
        $info_vereda = json_decode($detalle, true);
        $estados = array();
        array_push($estados, array('codigo' => "A", 'nombre' => "Activa"));
        array_push($estados, array('codigo' => "I", 'nombre' => "Inactiva"));

        $datos_vista['cod_dpto'] = $info_vereda['cod_dpto'];
        $datos_vista['depto'] = $info_vereda['depato'];
        $datos_vista['cod_muni'] = $info_vereda['cod_muni'];
        $datos_vista['muni'] = $info_vereda['nombre'];
        $datos_vista['cod_vecor'] = $info_vereda['cod_vecor'];
        $datos_vista['nom_vecor'] = $info_vereda['nom_vecor'];
        $datos_vista['estado'] = $info_vereda['estado'];
        $datos_vista['estados'] = $estados;
        $vista = cargarView("veredas");
        $vista->asignarVariable($datos_vista);
        $vista->modificar_veredas();
    }

    /**
     * Este metodo permite actualizar un municipio
     */
    function update_veredas()
    {
        $cod_dpto = (isset($_REQUEST['cod_dpto']) ? trim($_REQUEST['cod_dpto']) : "");
        $cod_muni = (isset($_REQUEST['cod_muni']) ? trim($_REQUEST['cod_muni']) : "");
        $cod_vecor = (isset($_REQUEST['cod_vecor']) ? trim($_REQUEST['cod_vecor']) : "");
        $nom_vecor = (isset($_REQUEST['nom_vecor']) ? trim($_REQUEST['nom_vecor']) : "");
        $estado = (isset($_REQUEST['estado']) ? trim($_REQUEST['estado']) : "");

        $array_mod = array();
        $nom_vecor = utf8_encode(ucwords(strtolower(trim($nom_vecor))));
        $array_mod['nom_vecor'] = "'$nom_vecor'" ;
        $array_mod['estado'] = "'$estado'";
        
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->update_veredas($cod_dpto, $cod_muni,$cod_vecor, $array_mod);

        $datos_respuesta['bandera'] = 0;
        $response['objetojson'] = $datos_respuesta;
        echo json_encode($response);
    }


    /**
     * Este metodo permite actualizar un municipio
     */
    function insert_veredas()
    {
        $cod_dpto = (isset($_REQUEST['depto']) ? trim($_REQUEST['depto']) : "");
        $cod_muni = (isset($_REQUEST['muni']) ? trim($_REQUEST['muni']) : "");
        $estado = (isset($_REQUEST['estado']) ? trim($_REQUEST['estado']) : "");
        $cod_vecor = (isset($_REQUEST['cod_vecor']) ? trim($_REQUEST['cod_vecor']) : "");
        $nom_vecor = (isset($_REQUEST['nom_vecor']) ? trim($_REQUEST['nom_vecor']) : "");
        
        //se valida que el codigo de la vereda no exista
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->validarcrear_veredas($cod_dpto, $cod_muni, $cod_vecor);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $cont = $reg['cont'];
        //echo'contador'. $cont;
        if($cont > 0){
            $datos_respuesta['bandera'] = 1;
            $datos_respuesta['mensaje'] = "La vereda ya existe";
            $response['objetojson'] = $datos_respuesta;
            echo json_encode($response);
            return;
        }

        $array_insert = array();
        $array_insert['cod_vecor'] = "'$cod_vecor'";
        $nom_vecor = utf8_encode(ucwords(strtolower(trim($nom_vecor))));
        $array_insert['nom_vecor'] = "'$nom_vecor'" ;
        $array_insert['cod_dpto'] = "'$cod_dpto'";
        $array_insert['cod_muni'] = "'$cod_muni'";
        $array_insert['estado'] = "'$estado'";
        $array_insert['impuesto'] = "''";
        $modeloInformix->insert_veredas($array_insert);

        $datos_respuesta['bandera'] = 0;
        $response['objetojson'] = $datos_respuesta;
        echo json_encode($response);
    }
}
