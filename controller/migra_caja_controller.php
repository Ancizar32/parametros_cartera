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

                        if ($value['modulo_interfaz'] == 'F') {

                                $c_curinfofac = $this->cargaInfoFact([
                                    'p_codsucursal'=>$codsucursal,
                                    'p_fecha'=>$fecha_cuadre,
                                    'cadena_areas'=>$cadena_areas,
                                    'cadena_comprb'=>$cadena_comprb,
                                    'cadena_concep'=>$cadena_concep,
                               ]);
                               if (!empty($c_curinfofac)) {
                                    foreach ($c_curinfofac as $key => $reg) {
                                        $ln = strlen(trim($reg['numero_identifica'])); 
                                        if ($ln == 0) {
                                            $v_nit = 0;
                                        }elseif ($ln <= 4) {
                                            $v_nit = trim($reg['numero_identifica']);
                                        }else {
                                            $v_nit = substr(trim($reg['numero_identifica']), ($ln-4), $ln);
                                        }    

                                       $v_agrucompr = $this->cargaInfoAgrupaCompr([
                                            'p_numero_control'=>$value['numero_control'],
                                            'p_codtp_comprobante'=>$reg['codtp_comprobante'],
                                       ]); 
                                        $carga_infotipocuenta = $this->cargaInfoTipoCuenta([
                                            'p_codtp_comprobante'=>$reg['codtp_comprobante'],
                                       ]); 
                                        $v_naturaleza = $carga_infotipocuenta['nattp_comprobante'];
                                        $v_tipo_causa = $carga_infotipocuenta['tipo_causacion'];
                                        $v_codcompania = $carga_infotipocuenta['codcompania'];

                                        $carga_infocontable = $this->cargaInfoContable([
                                            'p_codconcepto'=>$reg['codconcepto'],
                                            'p_tipoidentif'=>$reg['tipo_identifica'],
                                            'p_identificac'=>$reg['numero_identifica'],
                                            'p_codsucursal'=>$reg['codsucursal'],
                                        ]); 
                                        $codcuenta = $carga_infocontable['v_codcuenta'];
                                        $causa_gasto_l = $carga_infocontable['v_causa_gasto'];


                                        $carga_infonitcomp = $this->cargaInfoNitComp([
                                            'p_codcompania'=>$v_codcompania,
                                        ]); 
                                        
                                        $tp_nitcomp = trim($carga_infonitcomp['tp_nitcomp']);
                                        $v_codnum = trim($carga_infonitcomp['v_codnum']);

                                        $v_nomterc = $this->cargaInfoDesTercero([
                                            'p_tipoidentifica'=>$reg['tipo_identifica'],
                                            'p_numeroidentifi'=>$reg['numero_identifica'],
                                        ]); 


                                        $carga_infosucursal = $this->cargaInfoSucursal([
                                            'p_sucorige'=>$reg['codsucursal'],
                                            'p_sucursal'=>$reg['codsucur_destino'],
                                        ]); 

                                        $reg['codsucur_destino'] = empty($reg['codsucur_destino']) ? $carga_infosucursal : $reg['codsucur_destino'];

                                        $v_numdocumento = trim($reg['numero_documento']);

                                        if ($value['tipo_cuenta'] == 'F') {
                                            $v_contraparte = $value['tipo_cuenta'];
                                        }elseif ($value['tipo_cuenta'] == 'S') {
                                            $v_contraparte = $this->cargaInfoCuentaSucursal([
                                                'p_codsucursal'=>$reg['codsucursal'],
                                            ]); 
                                        }elseif ($value['tipo_cuenta'] == 'P') {
                                            $carga_infocontable = $this->cargaInfoContable([
                                                'p_codconcepto'=>'902',
                                                'p_tipoidentif'=>$reg['tipo_identifica'],
                                                'p_identificac'=>$reg['numero_identifica'],
                                                'p_codsucursal'=>$reg['codsucursal'],
                                            ]); 
                                            $v_contraparte = $carga_infocontable['v_codcuenta'];
                                            $causa_gasto = $carga_infocontable['v_causa_gasto'];
                                        }

                                        if ($v_agrucompr == 'S') {
                                            $v_numnit = $tp_nitcomp;
                                            $concepDesc = $this->cargaInfoConcepto(['p_codconcepto'=>$reg['codconcepto']]);
                                            $reg['descripcion'] = trim($concepDesc['desconcepto']);
                                        }else{
                                            $reg['descripcion'] = $reg['descripcion']."/".$v_numdocumento;
                                            $v_numnit = trim($reg['numero_identifica']);
                                        } 

                                        if ($value['agrupar_info'] == 'S') {
                                               $reg['numero_comprobante']=0;
                                           }   

                                          
                                                           
                                            

                                        
                                    }
                                   
                               }
                               
                        }
                        elseif ($value['modulo_interfaz'] == 'M') {
                            $p_comprobante = $value['comprobante_cont'];
                            $p_documento = $value['documento_cont'];
                            $p_numero_control = $value['numero_control'];
                            $p_tipo_cuenta = $value['tipo_cuenta'];
                            $p_cuenta_contra = $value['cuenta_contra'];
                            $p_agrupa_info = $value['agrupar_info'];
                            $p_genera_numeracion = $value['genera_numeracion'];
                            $p_cuenta_anexa = $value['cuenta_anexa'];
                            $c_curselinfo = $this->cargaInfoMvto([
                                    'p_codsucursal'=>$codsucursal,
                                    'p_fecha'=>$fecha_cuadre,
                                    'cadena_areas'=>$cadena_areas,
                                    'cadena_comprb'=>$cadena_comprb,
                                    'cadena_concep'=>$cadena_concep,
                                    'cadena_formap'=>$cadena_formap,
                               ]);

                           if (!empty($c_curinfofac)) {
                                    foreach ($c_curinfofac as $key => $reg) {
                                        $ln = strlen(trim($reg['numero_identifica']));
                                        $afecta_caja = TRUE;  
                                        $codtp_comp_no = ['8','78','114','118','130','16','64'];

                                        $a->loadInfoVldDesc([
                                            'sucursal'=>$reg['codsucursal'], 
                                            'concepto'=>$reg['codconcepto'],
                                        ]);
                                        $ide_cuenta = $a->getDatosOdbc()->getRegistroAll();
                                        $reg['codsucur_destino'] = (empty($reg['codsucur_destino'])) ? $reg['codsucursal'] : $reg['codsucur_destino']; 
                                        if ($ln == 0) {
                                            $v_nit = 0;
                                        }elseif ($ln <= 4) {
                                            $v_nit = trim($reg['numero_identifica']);
                                        }else {
                                            $v_nit = substr(trim($reg['numero_identifica']), ($ln-4), $ln);
                                        }    
                                        $afecta_caja = $this->cargaInfoAfectaCaja([
                                            'p_comprobante'=>$reg['codtp_comprobante'],
                                            'p_forma_pago'=>$reg['codforma_pago'],
                                        ]);


                                        // If valid records continue
                                        if ($afecta_caja && !in_array($reg['codtp_comprobante'], $codtp_comp_no)) {
                                           $v_agrucompr = $this->cargaInfoAgrupaCompr([
                                                'p_numero_control'=>$value['numero_control'],
                                                'p_codtp_comprobante'=>$reg['codtp_comprobante'],
                                           ]); 
                                            $carga_infotipocuenta = $this->cargaInfoTipoCuenta([
                                                'p_codtp_comprobante'=>$reg['codtp_comprobante'],
                                           ]); 
                                            $v_naturaleza = $carga_infotipocuenta['nattp_comprobante'];
                                            $v_tipo_causa = $carga_infotipocuenta['tipo_causacion'];
                                            $v_codcompania = $carga_infotipocuenta['codcompania'];

                                            $carga_infocontable = $this->cargaInfoContable([
                                                'p_codconcepto'=>$reg['codconcepto'],
                                                'p_tipoidentif'=>$reg['tipo_identifica'],
                                                'p_identificac'=>$reg['numero_identifica'],
                                                'p_codsucursal'=>$reg['codsucursal'],
                                            ]); 
                                            $codcuenta = $carga_infocontable['v_codcuenta'];
                                            $causa_gasto_l = $carga_infocontable['v_causa_gasto'];


                                            $carga_infonitcomp = $this->cargaInfoNitComp([
                                                'p_codcompania'=>$v_codcompania,
                                            ]); 
                                            
                                            $tp_nitcomp = trim($carga_infonitcomp['tp_nitcomp']);
                                            $v_codnum = trim($carga_infonitcomp['v_codnum']);

                                            $carga_infodestercero = $this->cargaInfoDesTercero([
                                                'p_tipoidentifica'=>$reg['tipo_identifica'],
                                                'p_numeroidentifi'=>$reg['numero_identifica'],
                                            ]); 


                                            $carga_infosucursal = $this->cargaInfoSucursal([
                                                'p_sucorige'=>$reg['codsucursal'],
                                                'p_sucursal'=>$reg['codsucur_destino'],
                                            ]); 

                                            $reg['codsucur_destino'] = empty($reg['codsucur_destino']) ? $carga_infosucursal : $reg['codsucur_destino'];
                                            $v_numdocumento = trim($reg['numero_documento']);

                                            if ($value['tipo_cuenta'] == 'F') {
                                                $v_contraparte = $value['tipo_cuenta'];
                                            }elseif ($value['tipo_cuenta'] == 'S') {
                                                $v_contraparte = $this->cargaInfoCuentaSucursal([
                                                    'p_codsucursal'=>$reg['codsucursal'],
                                                ]); 
                                            }elseif ($value['tipo_cuenta'] == 'P') {
                                                $carga_infocontable = $this->cargaInfoContable([
                                                    'p_codconcepto'=>'902',
                                                    'p_tipoidentif'=>$reg['tipo_identifica'],
                                                    'p_identificac'=>$reg['numero_identifica'],
                                                    'p_codsucursal'=>$reg['codsucursal'],
                                                ]); 
                                                $v_contraparte = $carga_infocontable['v_codcuenta'];
                                                $causa_gasto = $carga_infocontable['v_causa_gasto'];
                                            }

                                            if ($v_agrucompr == 'S') {
                                                $v_numnit = $tp_nitcomp;
                                                $concepDesc = $this->cargaInfoConcepto(['p_codconcepto'=>$reg['codconcepto']]);
                                                $reg['descripcion'] = trim($concepDesc['desconcepto']);
                                            }else{
                                                $reg['descripcion'] = $reg['descripcion']."/".$v_numdocumento;
                                                $v_numnit = trim($reg['numero_identifica']);
                                            } 

                                            if ($value['agrupar_info'] == 'S') {
                                                   $reg['numero_comprobante']=0;
                                               }   


                                                           
                                            

                                        }
                                    }
                                   
                               }
                            
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

public function cargaInfoNumera(array $param)
{
    $p_fecha = isset($param['p_fecha']) ? $param['p_fecha'] : '';
    $p_codsu = isset($param['p_codsu']) ? $param['p_codsu'] : '';
    $p_nit = isset($param['p_nit']) ? $param['p_nit'] : '';
    $p_codnum = isset($param['p_codnum']) ? $param['p_codnum'] : '';
    $p_contraparte = isset($param['p_contraparte']) ? $param['p_contraparte'] : '';
    $p_consecutivo = isset($param['p_consecutivo']) ? $param['p_consecutivo'] : '';
    $p_comprobante = isset($param['p_comprobante']) ? $param['p_comprobante'] : '';
    $p_documento = isset($param['p_documento']) ? $param['p_documento'] : '';

    
}

public function cargaInfoMvto(array $param){
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
   
public function cargaInfoFact(array $param){
        $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';
        $p_fecha = isset($param['p_fecha']) ? $param['p_fecha'] : '';

        $cadena_areas = isset($param['cadena_areas']) ? $param['cadena_areas'] : '';
        $cadena_comprb = isset($param['cadena_comprb']) ? $param['cadena_comprb'] : '';
        $cadena_concep = isset($param['cadena_concep']) ? $param['cadena_concep'] : '';

        $a = $this->model;
        $query = $a->loadInfoFact([
            'p_codsucursal'=>$p_codsucursal,
            'p_fecha'=>$p_fecha,
            'cadena_areas'=>$cadena_areas,
            'cadena_comprb'=>$cadena_comprb,
            'cadena_concep'=>$cadena_concep,
        ]);
        $reg = $a->getDatosOdbc()->getRegistroAll();
        return $reg;
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

public function cargaInfoConcepto(array $param)
   {
    $p_codconcepto = isset($param['p_codconcepto']) ? $param['p_codconcepto'] : '';
    $a = $this->model;

    $a->loadInfoContableConcepto([
        'p_codconcepto'=>$p_codconcepto,
    ]);
    $p_codconcepto_data = $a->getDatosOdbc()->getRegistroAll();
    $p_codconcepto_data = !empty($p_codconcepto_data) ? $p_codconcepto_data[0] : '';

    return $p_codconcepto_data;
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
    $v_causa_gasto = trim($p_codconcepto_data[0]['causa_gasto']);

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

    
    return [
        'v_codcuenta'=>$v_codcuenta,
        'v_causa_gasto'=>$v_causa_gasto,
    ];
   }

public function cargaInfoNitComp(array $param)
   {
    $p_codcompania = isset($param['p_codcompania']) ? $param['p_codcompania'] : '';

    $a = $this->model;

    $a->loadInfoNitComp([
        'p_codcompania'=>$p_codcompania,
    ]);
    $p_codcompania_data = $a->getDatosOdbc()->getRegistroAll();
    
    $v_nitcompania = empty($p_codcompania_data) ? '890001600' : trim($p_codcompania_data[0]['v_nitcompania']);
    $t = explode("-", $v_nitcompania);
    $v_nit = $t[0];
    $v_codnum = $t[1];
    
    return [
        'tp_nitcomp' => $v_nit, 
        'v_codnum' => $v_codnum, 
    ];
   }

public function cargaInfoSucursal(array $param)
   {
    $p_sucorige = isset($param['p_sucorige']) ? $param['p_sucorige'] : '';
    $p_sucursal = isset($param['p_sucursal']) ? $param['p_sucursal'] : '';

    $a = $this->model;

    $a->loadInfoSucursal([
        'p_sucursal'=>$p_sucursal,
    ]);
    $p_sucursal_data = $a->getDatosOdbc()->getRegistroAll();
    
    $v_sucursal = (empty($p_sucursal_data)) ? trim($p_sucursal_data[0]['v_sucursal']) : trim($p_sucorige);
    
    return $v_sucursal;
   }

public function cargaInfoCuentaSucursal(array $param)
   {
    $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';

    $a = $this->model;

    $a->loadInfoSucursal([
        'p_codsucursal'=>$p_codsucursal,
    ]);
    $p_codsucursal_data = $a->getDatosOdbc()->getRegistroAll();
    
    $v_codcuenta = isset($p_sucursal_data[0]['v_codcuenta']) ? $p_sucursal_data[0]['v_codcuenta'] : '';
    
    return $v_codcuenta;
   }

public function cargaInfoDesTercero(array $param)
   {
    $p_tipoidentifica = isset($param['p_tipoidentifica']) ? trim($param['p_tipoidentifica']) : '';
    $p_numeroidentifi = isset($param['p_numeroidentifi']) ? trim($param['p_numeroidentifi']) : '';
    $v_nombres = '';
    $p_numeroidentifi_data;
    $a = $this->model;

    if ($p_tipoidentifica == 'P') {        
        $a->loadInfoDesTerceroP([
            'p_numeroidentifi'=>$p_numeroidentifi,
        ]);
        $p_numeroidentifi_data = $a->getDatosOdbc()->getRegistroAll();
        $v_nom1 = isset($p_numeroidentifi_data[0]['v_nom1']) ? $p_numeroidentifi_data[0]['v_nom1'] : '';
        $v_nom2 = isset($p_numeroidentifi_data[0]['v_nom2']) ? $p_numeroidentifi_data[0]['v_nom2'] : '';
        $v_ape1 = isset($p_numeroidentifi_data[0]['v_ape1']) ? $p_numeroidentifi_data[0]['v_ape1'] : '';
        $v_ape2 = isset($p_numeroidentifi_data[0]['v_ape2']) ? $p_numeroidentifi_data[0]['v_ape2'] : '';
        $v_razonso = isset($p_numeroidentifi_data[0]['v_razonso']) ? $p_numeroidentifi_data[0]['v_razonso'] : '';
        $v_nombres = $v_nom1." ".$v_nom2." ".$v_ape1." ".$v_ape2." ".$v_razonso;

    }elseif ($p_tipoidentifica == 'C') {
        $a->loadInfoDesTerceroC([
            'p_numeroidentifi'=>$p_numeroidentifi,
        ]);
        $p_numeroidentifi_data = $a->getDatosOdbc()->getRegistroAll();
        $v_nombres = $p_numeroidentifi_data[0]['v_nombres'];
    }elseif ($p_tipoidentifica == 'C') {
        $a->loadInfoDesTerceroI([
            'p_numeroidentifi'=>$p_numeroidentifi,
        ]);
        $p_numeroidentifi_data = $a->getDatosOdbc()->getRegistroAll();
        $v_nom1 = isset($p_numeroidentifi_data[0]['v_nom1']) ? $p_numeroidentifi_data[0]['v_nom1'] : '';
        $v_nom2 = isset($p_numeroidentifi_data[0]['v_nom2']) ? $p_numeroidentifi_data[0]['v_nom2'] : '';
        $v_ape1 = isset($p_numeroidentifi_data[0]['v_ape1']) ? $p_numeroidentifi_data[0]['v_ape1'] : '';
        $v_ape2 = isset($p_numeroidentifi_data[0]['v_ape2']) ? $p_numeroidentifi_data[0]['v_ape2'] : '';
        $v_razonso = isset($p_numeroidentifi_data[0]['v_razonso']) ? $p_numeroidentifi_data[0]['v_razonso'] : '';
        $v_nombres = $v_nom1." ".$v_nom2." ".$v_ape1." ".$v_ape2." ".$v_razonso;
    }
        
    return $v_nombres;
   }


}
