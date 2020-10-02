<?php

/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Abril
 */
class zonas_controller extends ControladorBase
{

    //Se declara la variable que nos permitira acceder a los metodos del modelo
    private $modeloInformix = null;

    /**
     * se llama al constructor del padre
     */
    function __construct()
    {
        parent::__construct();
        $this->modeloInformix = cargarModel('zonas');
        $this->modeloInformix->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
    }

    /**
     * Permite cargar la informacion necesaria para la vista principal
     */
    function index()
    {
        $usuario = $_SESSION["session_intranet_cedula"];
        $sucurBusqueda = (isset($_REQUEST['sucursalb']) ? trim($_REQUEST['sucursalb']) : "");
        $sucursal = $_SESSION["session_intranet_suc"];
        $codigopt = (isset($_REQUEST['zonab']) ? trim($_REQUEST['zonab']) : "");
        $cobradorb = (isset($_REQUEST['cobradorb']) ? trim($_REQUEST['cobradorb']) : "");

        //Se validan los cargos para la sucursal 
        if ($sucursal == 1) {
            $cargos = "01, 03, 20, 22, 23";
            //Se consultan las sucursales
            $sucursal = null;
        } else {
            $cargos = "20, 22, 23";
            if($sucurBusqueda =="")
            $sucurBusqueda = $sucursal;
        }
        
        //Se consultan las zonas en la base de datos
        $listado = $this->filtrar($sucurBusqueda, $codigopt, $cobradorb);

        //Se consultan las sucursales
        $sucursales = $this->get_sucursales($sucursal);

        //Se consultan las zonas
        $zonas = $this->get_zonas($sucursal);

        //Se consultan los cobradores si el usurio que ejecuta la accion es de la sucursal
        //direccion general se recuperan las personas con codigo de cargo ( 01, 03 ) de lo contrario las personas con cargo ( 20, 22, 23 )
        $cargos = "01, 03, 20, 22, 23";
        $cobradores = $this->get_cobradores($cargos, $sucursal);
        

        $datos_vista['listado'] = $listado;
        $datos_vista['sucursales'] = $sucursales;
        $datos_vista['zonas'] = $zonas;
        $datos_vista['cobradores'] = $cobradores;
        $datos_vista['sucursalb'] = $sucurBusqueda;
        $datos_vista['zonab'] = $codigopt;
        $datos_vista['cobradorb'] = $cobradorb;
        $vista = cargarView("zonas");
        $vista->asignarVariable($datos_vista);
        $vista->cargarTemplate("head");
        $vista->cargarTemplate("menu");
        $vista->dibujar();
        $vista->cargarTemplate("foot");
    }

    /**
     * Esta función permite filtrar las zonas en la base de datos 
     *
     * @param [type] $sucurBusqueda
     * @param [type] $codigopt
     * @param [type] $cobradorb
     * @return array una lista con el resultado de la busqueda
     */
    function filtrar($sucurBusqueda, $codigopt, $cobradorb){
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->filtrar_punto_venta($sucurBusqueda, $codigopt, $cobradorb);
        $datosOdbcInformix = $modeloInformix->getDatosOdbc();
        $listado = array();
        while ($reg = $datosOdbcInformix->getRegistro()) {

            $sucur = trim($reg['sucur']);
            $codzona = trim($reg['codigopt']);
            $desczona = utf8_encode(ucwords(strtolower(trim($reg['descptov']))));
            $depto = utf8_encode(ucwords(strtolower(trim($reg['depto']))));
            $cobrador = utf8_encode(ucwords(strtolower(trim($reg['cobrador']))));
            $listamad_cont = trim($reg['listamad_cont']);
            $listaele_cont = trim($reg['listaele_cont']);
            $listamad = trim($reg['listamad']);
            $listaele = trim($reg['listaele']);
            $coddepto = trim($reg['coddepto']);
            $codsucur = trim($reg['codsucur']);

            array_push($listado, array(
                'sucursal' => $sucur, 'desczona' => $desczona, 'depto' => $depto,
                'listamad_cont' => $listamad_cont, 'listaele_cont' => $listaele_cont, 'listamad' => $listamad, 'listaele' => $listaele,
                'codzona' => $codzona, 'coddepto'=> $coddepto, 'cobrador'=> $cobrador, 'codsucur' => $codsucur 
            ));
        }
        return $listado;
    }

    /**
     * Esta función permite refrescar la tabla con los filtros de busqueda
     */
    function refrescarTabla()
    {
        $codigopt = (isset($_REQUEST['zonab']) ? trim($_REQUEST['zonab']) : "");
        $cobradorb = (isset($_REQUEST['cobradorb']) ? trim($_REQUEST['cobradorb']) : "");
        $sucurBusqueda = (isset($_REQUEST['sucursalb']) ? trim($_REQUEST['sucursalb']) : "");
        //Se consultan las zonas en la base de datos
        $listado = $this->filtrar($sucurBusqueda, $codigopt, $cobradorb);
        $vista = cargarView("zonas");
        echo $vista->verListado($listado);
    }
    /**
     * Esta función permite consultar las sucursales ¿
     *
     * @param [type] $sucursal
     * @return array una lista con las sucursales
     */
    function get_sucursales($sucursal){
        //Se consultan las sucursales
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getSucursales($sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $sucursales = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $nombre = utf8_encode(ucwords(strtolower(trim($reg['nombre']))));
            $codigo = trim($reg['codigo']);
            array_push($sucursales, array('nombre' => $nombre, 'codigo' => $codigo));
        }
        return $sucursales;
    }

    /**
     * Esta función permite ontener los codigos de las zonas en el sistema
     *
     * @param [type] $sucursal
     * @return array una lista con los codigos de las zonas
     */
    function get_zonas($sucursal){
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getCodigoZonas($sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $zonas = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $codigo = trim($reg['codigopt']);
            array_push($zonas, array('codigopt' => $codigo));
        }
        return $zonas;
    }

    /**
     * Esta función permite obtener los municipios de un departamento
     */
    function refrescar_zonas()
    {
        $sucursal = $_SESSION["session_intranet_suc"];
        if($sucursal == 1){
            $sucursal = null;
        }
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getCodigoZonas($sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $zonas = '<option value="" style="background-color: #E6E6E6"> - Todos -</option>';
        while ($reg = $datOdbcInformix->getRegistro()) {
            $codigo = trim($reg['codigopt']);
            array_push($zonas, array('codigopt' => $codigo));
            $zonas .= "<option value='$codigo'>$codigo</option>";
        }
        echo $zonas;
    }

    /**
     * Esta función permite obtener los cobradores en la base de datos 
     *
     * @param [type] $cargos
     * @param [type] $sucursal
     * @return array una lista con los cobradores
     */
    function get_cobradores($cargos, $sucursal){
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getCobradores($cargos, $sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $cobradores = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $nombre = utf8_encode(ucwords(strtolower(trim($reg['nombre']))));
            $cedula = trim($reg['cedula']);
            array_push($cobradores, array('nombre' => $nombre, 'cedula' => $cedula));
        }
        return $cobradores;
    }

    /**
     * Esta función permite inicializar el modal de la pantalla de crear con las validaciones correspondientes a la sucursal
     */
    function crear()
    {
        $sucursal = $_SESSION["session_intranet_suc"];
        if ($sucursal == 1) {
            $cargos = "01, 03, 20, 22, 23";
            //Se consultan las sucursales
            $sucursal = null;
        } else {
            $cargos = "20, 22, 23";
        }
        //Se consultan los cobradores
        $cobradores = $this->get_cobradores($cargos, $sucursal);
        //Se consultan las sucursales
        $sucursales = $this->get_sucursales($sucursal);
        
        // se consultan los departamentos
        $departamentos = $this->get_departamentos();

        $datos_vista['sucursales'] = $sucursales;
        $datos_vista['cobradores'] = $cobradores;
        $datos_vista['departamentos'] = $departamentos;

        $vista = cargarView("zonas");
        $vista->asignarVariable($datos_vista);
        $vista->crear();
    }

    /**
     * Esta funciónj permite obtener la lista de departamentos
     *
     * @return array una lista con los departamentos
     */
    function get_departamentos(){
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getDepartamentos();
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        //$departamentos = '<option value="" style="background-color: #E6E6E6"> - Todos -</option>';
        $departamentos = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $nombre = utf8_encode(ucwords(strtolower(trim($reg['depato']))));
            //echo'nomre'.$nombre;
            $codigo = trim($reg['coddpe']);
            //$departamentos .= '<option value="$codigo">$nombre</option>';
            array_push($departamentos, array('nombre' => $nombre, 'codigo' => $codigo));
        }
        return $departamentos;
    }

    /**
     * Esta función permite inicializar el modal de modificar una zona
     */
    function modificar()
    {
        $detalle = (isset($_REQUEST['detalle']) ? trim($_REQUEST['detalle']) : "");
        $detalle = json_decode($detalle, true);
        $codigopt = $detalle['codzona'];
        $sucurBusqueda = $detalle['codsucur'];
        $sucursal = $_SESSION["session_intranet_suc"];
        $modeloInformix = $this->modeloInformix;
        if ($sucursal == 1) {
            $cargos = "01, 03, 20, 22, 23";
            //Se consultan las sucursales
            $sucursal = null;
        } else {
            $cargos = "20, 22, 23";
        }
        
        //Se consultan los cobradores
        $cobradores = $this->get_cobradores($cargos, $sucursal);
        //Se consultan las sucursales
        $sucursales = $this->get_sucursales($sucursal);
        // se consultan los departamentos
        $departamentos = $this->get_departamentos();

        //Se cosulta la información correspondiente a la zona
        $modeloInformix->getZona($codigopt, $sucurBusqueda);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $zona = $datOdbcInformix->getRegistro();
        $desczona = utf8_encode(ucwords(strtolower(trim($zona['descptov']))));
        $cobrador = trim($zona['cobrador']);

        //se recuperan los departamentos, municipios y veredas asociadas a las zonas
        $ubicaciones =$this->get_ubicaciones($codigopt, $sucurBusqueda);
        
        //var_dump($ubicaciones);
        //echo 'cobrador '.$cobrador;
        $datos_vista['codigo'] = $codigopt;
        $datos_vista['sucursal'] = $sucurBusqueda;
        $datos_vista['sucursales'] = $sucursales;
        $datos_vista['descripcion'] = $desczona;
        $datos_vista['cobrador'] = $cobrador;
        $datos_vista['cobradores'] = $cobradores;
        $datos_vista['departamentos'] = $departamentos;
        $datos_vista['ubicaciones'] = $ubicaciones;
        //var_dump($datos_vista);
        $vista = cargarView("zonas");
        $vista->asignarVariable($datos_vista);
        $vista->modificar();
    }

    /**
     * Esta función permite obtener las ubicaciones asociadas a una zona
     *
     * @param [type] $codigopt
     * @param [type] $sucurBusqueda
     * @return array una lista con las ubicaciones
     */
    function get_ubicaciones($codigopt, $sucurBusqueda){
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getZonas($codigopt, $sucurBusqueda);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $ubicaciones = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $codigo = trim($reg['codigopt']);
            $coddepto = trim($reg['coddepto']);
            $nomdepto = utf8_encode(ucwords(strtolower(trim($reg['depto']))));
            $codmuni = trim($reg['municipi']);
            $nombmuni = utf8_encode(ucwords(strtolower(trim($reg['nombre']))));
            $codver = trim($reg['cod_vecor']);
            $nomver = utf8_encode(ucwords(strtolower(trim($reg['vereda']))));
            if ($codver == "") {
                $codver = "N/A";
                $nomver = "N/A";
            }
            //Se inicializan el arreglo de ubicaciones
            array_push($ubicaciones, array('codigo' => $codigo, 'coddepto' => $coddepto, 'nomdepto' => $nomdepto, 'codmuni' => $codmuni, 
            'nombmuni' => $nombmuni, 'codver' => $codver, 'nomver' => $nomver));
        }
        return $ubicaciones;
    }

    /**
     * Esta función permite obtener los municipios de un departamento
     */
    function get_municipios()
    {
        //echo'entra';
        $coddpto = (isset($_REQUEST['coddpto']) ? trim($_REQUEST['coddpto']) : "");
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getMunicipios($coddpto);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $municipios = '<option value="" style="background-color: #E6E6E6"> - Todos -</option>';
        while ($reg = $datOdbcInformix->getRegistro()) {
            $nombre = utf8_encode(ucwords(strtolower(trim($reg['nombre']))));
            $codigo = trim($reg['codigo']);
            $municipios .= "<option value='$codigo'>$nombre</option>";
        }
        //var_dump($municipios);
        echo $municipios;
    }

    /**
     * Esta función permite obtener las veredas de un departamento y municipio
     */
    function get_veredas()
    {
        echo 'entra al controlod';
        $coddpto = (isset($_REQUEST['coddpto']) ? trim($_REQUEST['coddpto']) : "");
        $codmun = (isset($_REQUEST['codmun']) ? trim($_REQUEST['codmun']) : "");
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getVereda($coddpto, $codmun);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $veredas = '<option value="" style="background-color: #E6E6E6"> - Todos -</option>';
        while ($reg = $datOdbcInformix->getRegistro()) {
            $nombre = utf8_encode(ucwords(strtolower(trim($reg['nom_vecor']))));
            $codigo = trim($reg['cod_vecor']);
            $veredas .= "<option value='$codigo'>$nombre</option>";
        }
        //var_dump($veredas);
        echo $veredas;
    }

    /**
     * Esta función permite validar el codigo y la sucursal de la zona
     */
    function validar_zona()
    {
        $codzona = (isset($_REQUEST['codzona']) ? trim($_REQUEST['codzona']) : "");
        $sucursal = (isset($_REQUEST['sucursal']) ? trim($_REQUEST['sucursal']) : "");
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->get_existe_zona($codzona, $sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $cont = trim($reg['cont']);
        if ($cont != 0) {
            $cont =  1;
        }
        $datos_respuesta['contador'] = $cont;
        $response['objetojson'] = $datos_respuesta;
        echo json_encode($response);
    }

    /**
     * Esta función permite ingresar una zona
     */
    function ingresar()
    {
        $codzona = (isset($_REQUEST['codzona']) ? trim($_REQUEST['codzona']) : "");
        $desczona = (isset($_REQUEST['desczona']) ? trim($_REQUEST['desczona']) : "");
        $sucursal = (isset($_REQUEST['sucursal']) ? trim($_REQUEST['sucursal']) : "");
        $cobrador = (isset($_REQUEST['cobrador']) ? trim($_REQUEST['cobrador']) : "");
        $municipios = (isset($_REQUEST['municipios']) ? trim($_REQUEST['municipios']) : "");
        $ubicaciones = json_decode($municipios);
        //Antes de crear se debe verificar que no exista el mismo codigo para una sucursal 
        //var_dump($ubicaciones );
        $this->insert_zonas($codzona, $desczona, $sucursal, $cobrador, $ubicaciones);
        $datos_respuesta['bandera'] = 0;
        $response['objetojson'] = $datos_respuesta;
        echo json_encode($response);
    }

    /**
     * Esta función permite realizar el insert en la tabla de zonas
     */
    function insert_zonas($codzona, $desczona, $sucursal, $cobrador, $ubicaciones)
    {
        $modeloInformix = $this->modeloInformix;
        $usuario = $_SESSION["session_intranet_login"];
        foreach ($ubicaciones as $ubicacion) {
            $ubicacion = (array) $ubicacion;
            $coddep = $ubicacion["coddep"];
            $codmun = $ubicacion["codmun"];
            $codver = $ubicacion["codver"];
            $array = array();
            $array['codigopt'] = $codzona;
            $array['descptov'] = "'$desczona'";
            $array['codsucur'] = $sucursal;
            //se consulta la información referente a madera y electronica de los municipios
            $modeloInformix->get_madera_electronica($coddep, $codmun);
            $datOdbcInformix = $modeloInformix->getDatosOdbc();
            $reg = $datOdbcInformix->getRegistro();
            //$array['listagen'] = "";
            $array['listaele'] = trim($reg['electronica']);
            $array['listamad'] = trim($reg['madera']);
            $array['listaele_cont'] = trim($reg['electr_cont']);
            $array['listamad_cont'] = trim($reg['madera_cont']);

            //$array['distanci'] = "";
            $array['coddepto'] = "'$coddep'";
            $array['municipi'] = "'$codmun'";
            if ($codver != "N/A") {
                $array['cod_vecor'] = "'$codver'";
            }
            $array['cobrador'] = $cobrador;

            $array['tipopvta'] = "'Z'";
            $array['tipo_cobro'] = 0;
            //campos de auditoria
            $array['usrcrea'] = "'$usuario'";
            $array['feccrea'] = "today";
            $array['horacre'] = "current";
            // Se debe validar que al momento de ingresar no exista una zona con la misma ubicación
            $modeloInformix->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
            $modeloInformix->get_existe_zona_ubic($codzona, $sucursal, $coddep, $codmun, $codver);
            $datOdbcInformix = $modeloInformix->getDatosOdbc();
            $reg = $datOdbcInformix->getRegistro();
            $cont = trim($reg['cont']);
            if ($cont == 0) {
                $modeloInformix->insert_zonas($array);
            }
        }
    }

    /**
     * Actualiza la informacion de los recuados cargados en el sistema    
     */
    function actualizar()
    {
        $codzona = (isset($_REQUEST['codzona']) ? trim($_REQUEST['codzona']) : "");
        $desczona = (isset($_REQUEST['desczona']) ? trim($_REQUEST['desczona']) : "");
        $sucursal = (isset($_REQUEST['sucursal']) ? trim($_REQUEST['sucursal']) : "");
        $cobrador = (isset($_REQUEST['cobrador']) ? trim($_REQUEST['cobrador']) : "");
        $municipios = (isset($_REQUEST['municipios']) ? trim($_REQUEST['municipios']) : "");
        $eliminados = (isset($_REQUEST['eliminados']) ? trim($_REQUEST['eliminados']) : "");

        $usuario = $_SESSION["session_intranet_login"];
        $ubicaciones = json_decode($municipios, true);
        $zonaseliminadas = json_decode($eliminados, true);
        //Se deben comparar los nuevos elementos de la lista de ubicaciones para crearlos 
        $modeloInformix = $this->modeloInformix;
        //Se realiza la consulta de ubicaciones actuales de una zona para determinar caules son las ubicaciones nuevas
        $ubica_actual =  $this->getUbicaciones($codzona, $sucursal);
        // se crea un foreach que permita comparar los elementos de la lista de objetos actual y de la nueva
        $ubica_nuevas = $this->comparar_ubicaciones($ubicaciones, $ubica_actual);
        $this->insert_zonas($codzona, $desczona, $sucursal, $cobrador, $ubica_nuevas);
        $erroresEliminar= 0;
        if(isset($zonaseliminadas)){
            foreach ($zonaseliminadas as $detalle) {
                $resultado = $this->eliminar($detalle, $sucursal);
                if($resultado != 0){
                    $erroresEliminar++;
                }
            }
        }
        //var_dump($zonaseliminadas);
        
        //Se crear el update de las zonas que corresponde a la descripción y el cobrador; la sucursal y el codigo no es 
        //posible modificarla por que son los identificadores de la tabla
        $array = array();
        $array['descptov'] = "'$desczona'";
        $array['cobrador'] = $cobrador;
        //campos de auditoria
        $array['usrmodi'] = "'$usuario'";
        $array['fecmodi'] = "today";
        $array['horamod'] = "current";
        $modeloInformix->update_zonas($array, $codzona, $sucursal);

        if($erroresEliminar !=0 ){
            //Si existen registros no se puede eliminar el registro
            $datos_respuesta['bandera'] = 1;
            $datos_respuesta['mensaje'] = "Esta Zona posee Cuentas Vigentes...Revise por Favor...";
            $response['objetojson'] = $datos_respuesta;
            echo json_encode($response);
        }else{
            $datos_respuesta['bandera'] = 0;
            $response['objetojson'] = $datos_respuesta;
            echo json_encode($response);
        }
        
    }

    /**
     * Esta función permite obtener las ubicaciones de una zona y una sucursal
     */
    function getUbicaciones($codzona, $sucursal){
        $modeloInformix = $this->modeloInformix;
        $modeloInformix->getZonas($codzona, $sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $ubica_actual = array();
        while ($reg = $datOdbcInformix->getRegistro()) {
            $coddepto = trim($reg['coddepto']);
            $codmuni = trim($reg['municipi']);
            $codver = trim($reg['cod_vecor']);
            if ($codver == "") {
                $codver = "N/A";
            }
            //Se inicializan el arreglo de ubicaciones
            array_push($ubica_actual, array('coddep' => $coddepto, 'codmun' => $codmuni, 'codver' => $codver,));
        }
        return $ubica_actual;
    }

    /**
     * Esta función permite comparar las ubicaciones e identificar las diferencias
     */
    function comparar_ubicaciones($a, $b){
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
     * Esta función permite eliminar una ubicacion correspondiente a una zona
     */
    function eliminar($detalle, $sucursal){

        //$detalle = (isset($_REQUEST['detalle']) ? trim($_REQUEST['detalle']) : "");
        //$detalle = json_decode($detalle, true);
        //var_dump($detalle);
        
        $codzona = $detalle['codigo'];
        $coddep = $detalle['coddepto']; 
        $codmun = $detalle['codmuni'];
        $codver = $detalle['codver'];

        $condiciones = array("codigopt" => $codzona, "codsucur" => $sucursal, "coddepto" => "'$coddep'", "municipi" => "'$codmun'");
        if($codver!="N/A"){
            $condiciones['cod_vecor'] = "'$codver'";
        }

        $modeloInformix = $this->modeloInformix;
        //antes de eliminar se debe validar que la zona no exista en s3anactas
        //primero se consulta la fecha de cierre para la sucursal
        $modeloInformix->obtener_fecha_cierre($sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $feccie = trim($reg['feccie']);

        //se consulta que no existan registros con saldo en s3anactas
        $modeloInformix->validar_eliminar_zona($codzona,$sucursal,$feccie,$coddep,$codmun,$codver);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $cont = trim($reg['cont']);
        if ($cont == 0) {
            $modeloInformix->delete_zonas($condiciones);   
        }
        return $cont;
    }

    /***
     * Esta función permite eliminar todas las ubicaciones correspondientes a una zona 
     */
    function eliminarTodas(){

        $codzona = (isset($_REQUEST['codzona']) ? trim($_REQUEST['codzona']) : "");
        $sucursal = (isset($_REQUEST['sucursal']) ? trim($_REQUEST['sucursal']) : "");
        $condiciones = array("codigopt" => $codzona, "codsucur" => $sucursal);

        $modeloInformix = $this->modeloInformix;
        //antes de eliminar se debe validar que la zona no exista en s3anactas
        //primero se consulta la fecha de cierre para la sucursal
        $modeloInformix->obtener_fecha_cierre($sucursal);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $feccie = trim($reg['feccie']);

        //se consulta que no existan registros con saldo en s3anactas
        $modeloInformix->validar_eliminar_zonas($codzona,$sucursal,$feccie);
        $datOdbcInformix = $modeloInformix->getDatosOdbc();
        $reg = $datOdbcInformix->getRegistro();
        $cont = trim($reg['cont']);
        if ($cont != 0) {
            //Si existen registros no se puede eliminar el registro
            $datos_respuesta['bandera'] = 1;
            $datos_respuesta['mensaje'] = "Esta Zona posee Cuentas Vigentes...Revise por Favor...";
            $response['objetojson'] = $datos_respuesta;
            echo json_encode($response);
        }else{
            $modeloInformix->delete_zonas($condiciones);
            $datos_respuesta['bandera'] = 0;
            $response['objetojson'] = $datos_respuesta;
            echo json_encode($response);
        }
    }
}
