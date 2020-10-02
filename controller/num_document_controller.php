<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class num_document_controller extends ControladorBase
{

    private $model;
    
    function __construct()
    {
        parent::__construct();
        $this->model = cargarModel('num_document');
        $this->model->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

    }


    function index()
    {      
        $vista = cargarView("num_document");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("num_document");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $a = $this->model;
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $a->load_data([
            'consecutivo'=>$data['consecutivo'],
            'codsucursal'=>$data['codsucursal'],
            'codtp_comprobante'=>$data['codtp_comprobante'],
            'codcaja'=>$data['codcaja'],
            'codusuario'=>$data['codusuario'],
            'numero_inicial'=>$data['numero_inicial'],
            'numero_final'=>$data['numero_final'],
            'numero_actual'=>$data['numero_actual'],
            'estado'=>$data['estado'],
        ]);

        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                switch ($value['estado']) {
                    case 'A':
                        $estado = 'Activo';
                        break;
                    case 'D':
                        $estado = 'Disponible';
                        break;
                    case 'I':
                        $estado = 'Inactivo';
                        break;
                    case 'U':
                        $estado = 'Utilizado';
                        break;
                    default:
                        $estado = $value['estado'];
                        break;
                }
                $dataTable['data'][] = [
                    'row_id'=>$value['consecutivo'],
                    'sucursal'=>(!empty(trim($value['codsucursal']))) ? $value['codsucursal'].' : '.$value['codsucursal_text'] : '',
                    'tip_comp'=>(!empty(trim($value['codtp_comprobante']))) ? $value['codtp_comprobante'].' : '.$value['codtp_comprobante_text'] : '',
                    'resp_caja'=>(!empty(trim($value['codcaja']))) ? $value['codcaja'].' : '.$value['codcaja_text'] : '',
                    'resp_papel'=>(!empty(trim($value['codusuario']))) ? $value['codusuario'].' : '.$value['codusuario_text'] : '',
                    'n_inicial'=>$value['numero_inicial'],
                    'n_final'=>$value['numero_final'],
                    'n_actual'=>$value['numero_actual'],
                    'estado'=>$estado,
                ];
            }
        
        }else{
           $dataTable['data'] = []; 
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
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }
    
    function autocomplete_tipos_comprobantes()
    {
        $a = $this->model;
        $list['results']=[];
        $term = $_POST['data']['term'];
        $a->load_tipos_comprobantes(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }

    function autocomplete_cod_caja()
    {
        $a = $this->model;
        $list['results']=[];
        $term = $_POST['data']['term'];
        $suc = $_POST['suc'];
        $a->load_cod_caja([
            'term'=>$term,
            'suc'=>$suc,
        ]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }
    
    function autocomplete_cod_usuario()
    {
        $a = $this->model;
        $list['results']=[];
        $term = $_POST['data']['term'];
        $suc = $_POST['suc'];
        $a->load_cod_usuario([
            'term'=>$term,
            'suc'=>$suc,
        ]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }

    function fillOutAutocompleteFormasPago()
    {
        $a = $this->model;
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_formas_pago(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        array_unshift($datosOdbc, ['id' => 'T', 'text' => 'T : Todos']);
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }

    function fillOutAutocompleteCompany()
    {
        $a = $this->model;
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_company(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        $list = ['results' => array_map(function($item){ return ['id' => $item['id'], 'text' => $item['text']];  }, $datosOdbc ) ];
        echo json_encode($list);
    }

    function createRecord()
    {
        $a = $this->model;
        $usuario = $_SESSION["session_intranet_login"];
        $return = [];
        $success = 'success';
        $msg = 'El registro fue creado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $setFocus = '';
        $data = $_POST['param'];
        $data['consecutivo'] = 0;
        $data['estado'] = 'A';
        $data['descto'] = 'N';
        $data['usrcrea'] = $usuario;
        $data['feccrea'] = date('m-d-Y');
        $data['horacre'] = date('H:i:s');

        $a->validNumStatus([
            'codtp_comprobante'=>$data['codtp_comprobante'],
            'codsucursal'=>$data['codsucursal'],
            'codcaja'=>$data['codcaja'],
            'codusuario'=>$data['codusuario'],
        ]);
        $validNumStatus = $a->getDatosOdbc()->getRegistroAll();
        if ($validNumStatus[0]['cnt']>0) {
            $data['estado'] = 'D';
        }

        $dataValid = $this->vld_asignumeracion([
                'codsucursal'=>$data['codsucursal'],
                'codusuario'=>$data['codusuario'],
                'codtp_comprobante'=>$data['codtp_comprobante'],
                'numero_inicial'=>$data['numero_inicial'],
                'numero_final'=>$data['numero_final'],
            ]);

        if (empty($data['codsucursal'])) {
            $success = 'error';
            $msg = 'Por favor seleccione una sucursal.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codsucursal';
        }else if (empty($data['codtp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtp_comprobante';
        }else if (empty($data['codcaja'])) {
            $success = 'error';
            $msg = 'Por favor seleccione responsable de caja.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codcaja';
        }else if (empty($data['numero_inicial'])) {
            $success = 'error';
            $msg = 'Por favor digite el número inicial';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_inicial';
        }else if (empty($data['numero_final'])) {
            $success = 'error';
            $msg = 'Por favor digite el número final.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_final';
        }else if (empty($data['numero_actual'])) {
            $success = 'error';
            $msg = 'Por favor digite el número actual.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_actual';
        }else if (trim($data['codtp_comprobante']) === '94' && (trim($data['codsucursal']) !== '2' &&
        trim($data['codsucursal']) !== '19')) {
            $success = 'error';
            $msg = 'Tipo de comprobante inhabilitado para esta sucursal ...Comuniquese con Contabilidad';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = '';
        }else if ($data['numero_inicial'] > $data['numero_final']) {
            $success = 'error';
            $msg = 'El número final no puede ser menor al número inicial.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_inicial';
        }else if ($data['numero_actual'] > $data['numero_inicial']) {
            $success = 'error';
            $msg = 'El numero actual no puede ser menor al inicial.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_actual';
        }else if (!$dataValid['success']) {
            $success = 'error';
            $msg = $dataValid['message'];
            $icon = 'error';
            $title = 'Error!';
        }else{       
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'numera_documentos',
                'conten'=>$data
            ]);
            $a->createRecord($insert);
        }
    
        $return = [
            'success'=> $success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'setFocus'=>$setFocus,
            'data'=>$data,
        ];
        echo json_encode($return);

    }

    public function vld_asignumeracion(array $param){
        $codsucursal = isset($param['codsucursal']) ? trim($param['codsucursal']) : '';
        $codusuario = isset($param['codusuario']) ? trim($param['codusuario']) : '';
        $codtp_comprobante = isset($param['codtp_comprobante']) ? trim($param['codtp_comprobante']) : '';
        $numero_inicial = isset($param['numero_inicial']) ? trim($param['numero_inicial']) : '';
        $numero_final = isset($param['numero_final']) ? trim($param['numero_final']) : '';

        $success = true;
        $msg = 'El registro fue creado satisfactoriamente.';

        $a = $this->model;
        $a->vld_asignumeracion([
            'codsucursal'=>$codsucursal,
            'codtp_comprobante'=>$codtp_comprobante,
            'numero_inicial'=>$numero_inicial,
            'numero_final'=>$numero_final,
        ]);

        $validNumStatus = $a->getDatosOdbc()->getRegistroAll();

        if (!empty($validNumStatus) && $codtp_comprobante != '94' && $codtp_comprobante != '95') {
            $success = false;
            $msg = 'Numeracion no puede ser re-asignada.';
        }

        if ($codtp_comprobante != '94' && $codtp_comprobante != '95' && $codtp_comprobante != '99'
        && (($numero_final - $numero_inicial) + 1 > 50) && !empty($codusuario)) {
            $success = false;
            $msg = 'La cantidad de recibos supera el numero por talonario.';
        }

        if(!empty($codusuario)){
            $ln = strlen($numero_inicial);
            $v_inicial = $numero_inicial;
            if ($ln<=1) {
                $v_inicial = $v_inicial;
            }else{
                $v_inicial = substr($v_inicial, $ln-2, $ln); 
            }

            if ($v_inicial != "01" && $v_inicial != "51") {
                
                $a->vld_consec([
                    'codsucursal'=>$codsucursal,
                    'codtp_comprobante'=>$codtp_comprobante,
                    'numero_inicial'=>$numero_inicial,
                ]);

                $validNum = $a->getDatosOdbc()->getRegistroAll();
                if ($validNum[0]['v_cnt'] == 0) {
                    $msg = "Para reasignar numeracion, se debe ingresar como incial el numero de recibo actual del talonario.";
                    $success = false;
                }
                
            }

            
        }

        return $return = [
            'success'=> $success,
            'message'=>$msg,
            'data'=>$validNumStatus,
        ];
    }


     public function editRecord(){
        $a = $this->model;
        $usuario = $_SESSION["session_intranet_login"];
        $return = [];
        $success = 'success';
        $msg = 'El registro fue editado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];
        $data['usrmodi'] = $usuario;
        $data['fecmodi'] = date('m-d-Y');
        $data['horamod'] = date('H:i:s');
        
        $a->validNumStatus([
            'codtp_comprobante'=>$data['codtp_comprobante'],
            'codsucursal'=>$data['codsucursal'],
            'codcaja'=>$data['codcaja'],
            'codusuario'=>$data['codusuario'],
        ]);
        $validNumStatus = $a->getDatosOdbc()->getRegistroAll();
        if ($validNumStatus[0]['cnt']>0) {
            $data['estado'] = 'D';
        }

        $dataValid = $this->vld_asignumeracion([
                'codsucursal'=>$data['codsucursal'],
                'codusuario'=>$data['codusuario'],
                'codtp_comprobante'=>$data['codtp_comprobante'],
                'numero_inicial'=>$data['numero_inicial'],
                'numero_final'=>$data['numero_final'],
            ]);

        if (empty($data['codsucursal'])) {
            $success = 'error';
            $msg = 'Por favor seleccione una sucursal.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codsucursal'.$data['consecutivo'];
        }else if (empty($data['codtp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtp_comprobante'.$data['consecutivo'];
        }else if (empty($data['codcaja'])) {
            $success = 'error';
            $msg = 'Por favor seleccione responsable de caja.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codcaja'.$data['consecutivo'];
        }else if (empty($data['numero_inicial'])) {
            $success = 'error';
            $msg = 'Por favor digite el número inicial';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_inicial'.$data['consecutivo'];
        }else if (empty($data['numero_final'])) {
            $success = 'error';
            $msg = 'Por favor digite el número final.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_final'.$data['consecutivo'];
        }else if (empty($data['numero_actual'])) {
            $success = 'error';
            $msg = 'Por favor digite el número actual.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_actual';
        }else if (trim($data['codtp_comprobante']) === '94' && (trim($data['codsucursal']) !== '2' &&
        trim($data['codsucursal']) !== '19')) {
            $success = 'error';
            $msg = 'Tipo de comprobante inhabilitado para esta sucursal ...Comuniquese con Contabilidad';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = '';
        }else if ($data['numero_inicial'] > $data['numero_final']) {
            $success = 'error';
            $msg = 'El número final no puede ser menor al número inicial.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_inicial';
        }else if ($data['numero_actual'] != $data['numero_inicial']) {
            $success = 'error';
            $msg = 'No se puede actualizar informacion de consecutivos usados.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'numero_actual';
        }else if (!$dataValid['success']) {
            $success = 'error';
            $msg = $dataValid['message'];
            $icon = 'error';
            $title = 'Error!';
        }else{
            $a->editRecord([
                'consecutivo'=>$data['consecutivo'],
                'codsucursal'=>$data['codsucursal'],
                'codtp_comprobante'=>$data['codtp_comprobante'],
                'codcaja'=>$data['codcaja'],
                'codusuario'=>$data['codusuario'],
                'numero_inicial'=>$data['numero_inicial'],
                'numero_final'=>$data['numero_final'],
                'numero_actual'=>$data['numero_actual'],
                'usrmodi'=>$data['usrmodi'],
                'fecmodi'=>$data['fecmodi'],
                'horamod'=>$data['horamod'],
            ]);
        }

        $return = [
            'success'=>$success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'setFocus'=>$setFocus,
            'data'=>$data,
        ];
        echo json_encode($return);
    }

    function returnEditModal()
    {       
        $return = [];
        $success = 'success';
        $msg = 'Ok';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        $a = $this->model;
        if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione un registro de la tabla para editar.';
            $icon = 'warning';
            $title = 'Advertencia!';
        } elseif (count($data)>1) {
            $success = 'error';
            $msg = 'Por favor seleccione solo un registro de la tabla para editar.';
            $icon = 'warning';
            $title = 'Advertencia!';
        } else {
            $a->load_data(['consecutivo'=>$data[0]]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            $vista = cargarView("num_document");   
            $data = $vista::editModal($dataValid);  
        }

         $return = [
            'success'=>$success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'html'=>$data,
        ];
        echo json_encode($return);       
    }

     public function disableRecord(){
        $a = $this->model;
        $usrmodi = $_SESSION["session_intranet_login"];
        $fecmodi = date('m-d-Y');
        $horamod = date('H:i:s');

        $return = [];
        $success = 'success';
        $msg = 'El registro fue inhabilitado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        foreach ($data as $key => $value) {
            $a->load_data(['consecutivo'=>$value]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            if ($dataValid[0]['numero_inicial'] != $dataValid[0]['numero_actual']) {
                $consec_error = $value;    
            }    
        }

        if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione uno o varios registros de la tabla';
            $icon = 'warning';
            $title = 'Advertencia!';
        }else if(!empty($consec_error)){
            $success = 'error';
            $msg = 'El registro '.$consec_error.' no se puede inhabilitar porque tiene consecutivos usados.';
            $icon = 'warning';
            $title = 'Error!';
        }else{
            if (count($data)>1) {
                $msg = "Los registros fueron inhabilitados satisfactoriamente.";
            }
            foreach ($data as $key => $value) {
                $a->disableRecord([
                    'consecutivo' => $value,
                    'usrmodi' => $usrmodi,
                    'fecmodi' => $fecmodi,
                    'horamod' => $horamod,
                ]);
            }
        }

        $return = [
            'success'=>$success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'data'=>$data,
        ];
        echo json_encode($return);
    }

    public function enableRecord(){
        $a = $this->model;  
        $usrmodi = $_SESSION["session_intranet_login"];
        $fecmodi = date('m-d-Y');
        $horamod = date('H:i:s');

        $return = [];
        $success = 'success';
        $msg = 'El registro fue habilitado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        foreach ($data as $key => $value) {
            $a->load_data(['consecutivo'=>$value]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            if ($dataValid[0]['estado'] !== 'I') {
                $consec_error = $value;    
            }    
        }


        if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione uno o varios registros de la tabla';
            $icon = 'warning';
            $title = 'Advertencia!';
        }else if(!empty($consec_error)){
            $success = 'error';
            $msg = 'El registro '.$consec_error.' no se puede habilitar porque no esta en estado activo.';
            $icon = 'warning';
            $title = 'Error!';
        }else{
            if (count($data)>1) {
                $msg = "Los registros fueron habilitados satisfactoriamente.";
            }
            foreach ($data as $key => $value) {
                $a->enableRecord([
                    'consecutivo' => $value,
                    'usrmodi' => $usrmodi,
                    'fecmodi' => $fecmodi,
                    'horamod' => $horamod,
                ]);
            }
        }

        $return = [
            'success'=>$success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'data'=>$data,
        ];
        echo json_encode($return);
    }

    function exportExcel(){ 
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=historial.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
    }

}
