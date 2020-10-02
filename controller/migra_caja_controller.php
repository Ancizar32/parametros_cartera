<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class migra_caja_controller extends ControladorBase
{

    private $model;
    
    function __construct()
    {
        parent::__construct();
        $this->model = cargarModel('migra_caja');
        $this->model->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

    }


    function index()
    {      
        $vista = cargarView("migra_caja");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("migra_caja");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $a = $this->model;
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $query = $a->load_data([
            'codsucursal'=>$data['codsucursal'],
        ]);

        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                $dataTable['data'][] = [
                    'row_id' => trim($value['codsucursal'])."|".trim($value['fecha_cuadre']),
                    'codsucursal'=>$value['codsucursal'],
                    'dessucur'=>$value['dessucur'],                
                    'fecha_cuadre'=>$value['fecha_cuadre'],                
                ];
            }
        
        }

        echo json_encode($dataTable);    

    }

    function autocomplete_branch_office()
    {
        $a = $this->model;
        $list['results']=[];
        $term = $_POST['data']['term'];
        $a->load_branch_office(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Todas --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }

    function cargaInfo(){
        $a = $this->model;
        $data = $_POST['param'];
        $a->loadControlNumber();
        $cadena_comprb = '';
        $cadena_areas = '';
        $cadena_concep = '';
        $cadena_formap = '';
        $control_number = $a->getDatosOdbc()->getRegistroAll();
         if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione uno o varios registros de la tabla';
            $icon = 'warning';
            $title = 'Advertencia!';
        }else{
            foreach ($data as $key => $value0) {
                $codsucursal = explode("|", $value0)[0];
                $fecha_cuadre = explode("|", $value0)[1];
                $fecha_cuadre = date_format(date_create($fecha_cuadre), "m-d-Y");
                if (!empty($control_number)) {
                    foreach ($control_number as $key => $value) {
                        $a->loadInfoComprb(['numero_control'=>$value['numero_control']]);
                        $dataNumComp = $a->getDatosOdbc()->getRegistroAll();
                        if (!empty($dataNumComp)) {
                        $cadena_comprb = implode(', ', array_map(function($item){return '"'.trim($item['codtp_comprobante']).'"';}, $dataNumComp));
                        }
                        $a->loadInfoArea(['numero_suc'=>explode("|", $value0)[0]]);
                        $dataInfoArea = $a->getDatosOdbc()->getRegistroAll();
                        if (!empty($dataInfoArea)) {
                        $cadena_areas = implode(', ', array_map(function($item){return '"'.trim($item['codarea']).'"';}, $dataInfoArea));
                        }
                        $a->loadInfoConcep(['numero_control'=>$value['numero_control']]);
                        $dataNumConc = $a->getDatosOdbc()->getRegistroAll();
                        if (!empty($dataNumConc)) {
                        $cadena_concep = implode(', ', array_map(function($item){return '"'.trim($item['codconcepto']).'"';}, $dataNumConc));
                        }
                        $a->loadInfoFormaP(['numero_control'=>$value['numero_control']]);
                        $dataFormP = $a->getDatosOdbc()->getRegistroAll();
                        if (!empty($dataFormP)) {
                        $cadena_formap = implode(', ', array_map(function($item){return '"'.trim($item['codforma_pago']).'"';}, $dataFormP));
                        }

                        if ($value['modulo_interfaz'] == 'M') {
                               $c_curselinfo = $this->cargaInfoMvto([
                                    'p_comprobante'=>$value['comprobante_cont'],
                                    'p_documento'=>$value['documento_cont'],
                                    'p_numero_control'=>$value['numero_control'],
                                    'p_tipo_cuenta'=>$value['tipo_cuenta'],
                                    'p_cuenta_contra'=>$value['cuenta_contra'],
                                    'p_agrupa_info'=>$value['agrupar_info'],
                                    'p_genera_numeracion'=>$value['genera_numeracion'],
                                    'p_cuenta_anexa'=>$value['cuenta_anexa'],
                                    'p_codsucursal'=>$codsucursal,
                                    'p_fecha'=>$fecha_cuadre,
                                    'cadena_areas'=>$cadena_areas,
                                    'cadena_comprb'=>$cadena_comprb,
                                    'cadena_concep'=>$cadena_concep,
                                    'cadena_formap'=>$cadena_formap,
                               ]);
                               if (!empty($c_curselinfo)) {
                                    foreach ($c_curselinfo as $key => $value1) {
                                        $ln = strlen(trim($value1['numero_identifica']));
                                        $afecta_caja = TRUE;  
                                        $codtp_comp_no = ['8','78','114','118','130','16','64'];

                                        $a->loadInfoVldDesc([
                                            'sucursal'=>$value1['codsucursal'], 
                                            'concepto'=>$value1['codconcepto'],
                                        ]);
                                        $ide_cuenta = $a->getDatosOdbc()->getRegistroAll();
                                        $value1['codsucur_destino'] = (empty($value1['codsucur_destino'])) ? $value1['codsucursal'] : $value1['codsucur_destino']; 
                                        if ($ln == 0) {
                                            $v_nit = 0;
                                        }elseif ($ln <= 4) {
                                            $v_nit = trim($value1['numero_identifica']);
                                        }else {
                                            $v_nit = substr(trim($value1['numero_identifica']), ($ln-4), $ln);
                                        }    
                                        $afecta_caja = $this->cargaInfoAfectaCaja([
                                            'p_comprobante'=>$value1['codtp_comprobante'],
                                            'p_forma_pago'=>$value1['codforma_pago'],
                                        ]);
                                        // If valid records continue
                                        if ($afecta_caja && in_array($value1['codtp_comprobante'], $codtp_comp_no)) {
                                           $v_agrucompr = $this->cargaInfoAgrupaCompr([
                                                'p_numero_control'=>$value['numero_control'],
                                                'p_codtp_comprobante'=>$value1['codtp_comprobante'],
                                           ]); 
                                            $carga_infotipocuenta = $this->cargaInfoTipoCuenta([
                                                'p_codtp_comprobante'=>$value1['codtp_comprobante'],
                                           ]); 
                                            $v_naturaleza = $carga_infotipocuenta['nattp_comprobante'];
                                            $v_tipo_causa = $carga_infotipocuenta['tipo_causacion'];
                                            $v_codcompania = $carga_infotipocuenta['codcompania'];

                                            $carga_infocontable = $this->cargaInfoContable([
                                                'p_codconcepto'=>$value1['codconcepto'],
                                                'p_tipoidentif'=>$value1['tipo_identifica'],
                                                'p_identificac'=>$value1['numero_identifica'],
                                                'p_codsucursal'=>$value1['codsucursal'],
                                           ]); 
                                            $return[] = $carga_infocontable;                                   
                                            $return[] = $carga_infocontable;                                   
                                            $return[] = $carga_infocontable;                                   
                                        }
                                    }
                                   
                               }
                               
                        }
                        elseif ($value['modulo_interfaz'] == 'F') {
                            
                        }
                            

                    }
                }
            }
            
        }
        IBG\service_return([
            'message'=>'Retornando info',
            'data'=>$return
        ]);
        // echo $cadena_comprb;
        // echo json_encode($dataNumConc);
    }

public function cargaInfoMvto(array $param){
        $p_comprobante = isset($param['p_comprobante']) ? $param['p_comprobante'] : '';
        $p_documento = isset($param['p_documento']) ? $param['p_documento'] : '';
        $p_numero_control = isset($param['p_numero_control']) ? $param['p_numero_control'] : '';
        $p_tipo_cuenta = isset($param['p_tipo_cuenta']) ? $param['p_tipo_cuenta'] : '';
        $p_cuenta_contra = isset($param['p_cuenta_contra']) ? $param['p_cuenta_contra'] : '';
        $p_agrupa_info = isset($param['p_agrupa_info']) ? $param['p_agrupa_info'] : '';
        $p_genera_numeracion = isset($param['p_genera_numeracion']) ? $param['p_genera_numeracion'] : '';
        $p_cuenta_anexa = isset($param['p_cuenta_anexa']) ? $param['p_cuenta_anexa'] : '';
        $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';
        $p_fecha = isset($param['p_fecha']) ? $param['p_fecha'] : '';

        $cadena_areas = isset($param['cadena_areas']) ? $param['cadena_areas'] : '';
        $cadena_comprb = isset($param['cadena_comprb']) ? $param['cadena_comprb'] : '';
        $cadena_concep = isset($param['cadena_concep']) ? $param['cadena_concep'] : '';
        $cadena_formap = isset($param['cadena_formap']) ? $param['cadena_formap'] : '';

        $a = $this->model;
        $query = $a->loadInfoMvto([
            'p_codsucursal'=>$p_codsucursal,
            'p_fecha'=>$p_fecha,
            'cadena_areas'=>$cadena_areas,
            'cadena_comprb'=>$cadena_comprb,
            'cadena_concep'=>$cadena_concep,
            'cadena_formap'=>$cadena_formap,
        ]);
        $c_curselinfo = $a->getDatosOdbc()->getRegistroAll();
        return $c_curselinfo;
    }
   
public function cargaInfoAfectaCaja(array $param)
   {
    $p_comprobante = isset($param['p_comprobante']) ? $param['p_comprobante'] : '';
    $p_forma_pago = isset($param['p_forma_pago']) ? $param['p_forma_pago'] : '';
    $flag_afecta = FALSE;
    $a = $this->model;

    $a->loadCajaComprobante(['p_comprobante'=>$p_comprobante]);
    $v_caja_comprobante_data = $a->getDatosOdbc()->getRegistroAll();
    $v_caja_comprobante = isset($v_caja_comprobante_data[0]['v_caja_comprobante']) ? $v_caja_comprobante_data[0]['v_caja_comprobante'] : 'S'; 

    $a->loadCajaFormapago(['p_forma_pago'=>$p_forma_pago]);
    $v_caja_formapago_data = $a->getDatosOdbc()->getRegistroAll();
    $v_caja_formapago = isset($v_caja_formapago_data[0]['v_caja_formapago']) ? $v_caja_formapago_data[0]['v_caja_formapago'] : 'S' ;

    if ($v_caja_comprobante == 'N' && $v_caja_formapago == 'N') {
        $flag_afecta = FALSE;
    }else{
        $flag_afecta = TRUE;
    }
    return $flag_afecta;
   }

public function cargaInfoAgrupaCompr(array $param)
   {
    $p_numero_control = isset($param['p_numero_control']) ? $param['p_numero_control'] : '';
    $p_codtp_comprobante = isset($param['p_codtp_comprobante']) ? $param['p_codtp_comprobante'] : '';
    $a = $this->model;

    $a->loadInfoAgrupaCompr([
        'p_numero_control'=>$p_numero_control,
        'p_codtp_comprobante'=>$p_codtp_comprobante,
    ]);
    $v_agrupa_concepto_data = $a->getDatosOdbc()->getRegistroAll();
    $v_agrupa_concepto = isset($v_agrupa_concepto_data[0]['v_agrupa_concepto']) ? $v_agrupa_concepto_data[0]['v_agrupa_concepto'] : 'S'; 


    return $v_agrupa_concepto;
   }

public function cargaInfoTipoCuenta(array $param)
   {
    $p_codtp_comprobante = isset($param['p_codtp_comprobante']) ? $param['p_codtp_comprobante'] : '';
    $a = $this->model;

    $a->loadInfoTipoCuenta([
        'p_codtp_comprobante'=>$p_codtp_comprobante,
    ]);
    $tp = $a->getDatosOdbc()->getRegistroAll();
    $res['nattp_comprobante'] = isset($tp[0]['nattp_comprobante']) ? $tp[0]['nattp_comprobante'] : 'C'; 
    $res['tipo_causacion'] = isset($tp[0]['tipo_causacion']) ? $tp[0]['tipo_causacion'] : '1'; 
    $res['codcompania'] = isset($tp[0]['codcompania']) ? $tp[0]['codcompania'] : '01'; 


    return $res;
   }

public function cargaInfoContable(array $param)
   {
    $p_codconcepto = isset($param['p_codconcepto']) ? $param['p_codconcepto'] : '';
    $p_tipoidentif = isset($param['p_tipoidentif']) ? $param['p_tipoidentif'] : '';
    $p_identificac = isset($param['p_identificac']) ? $param['p_identificac'] : '';
    $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';

    $a = $this->model;

    $a->loadInfoContableConcepto([
        'p_codconcepto'=>$p_codconcepto,
    ]);
    $p_codconcepto_data = $a->getDatosOdbc()->getRegistroAll();
    if (empty($p_codconcepto_data))
        throw new IBG\APIException("Codigo de concepto %s no existe", [$p_codconcepto]);
        
    $v_codcuenta = trim($p_codconcepto_data[0]['cuenta_contable']);

    if(empty($v_codcuenta) && $p_tipoidentif == 'P'){
        $a->loadInfoContableCodCuenta([
            'p_identificac'=>$p_identificac,
        ]);
        $p_identificac_data = $a->getDatosOdbc()->getRegistroAll();
        $v_codcuenta = trim($p_identificac_data[0]['v_codcuenta']);
    }

    if(empty($v_codcuenta) ){
        $a->loadInfoContableCodCuentaBySuc([
            'p_codsucursal'=>$p_codsucursal,
        ]);
        $p_identificac_data = $a->getDatosOdbc()->getRegistroAll();
        $v_codcuenta = trim($p_identificac_data[0]['v_codcuenta']);
    }

    if (empty($v_codcuenta))
        throw new IBG\APIException("Codigo de concepto %s no tiene cuenta asociada.", [$p_codconcepto]);

    
    return $param;
   }


}
