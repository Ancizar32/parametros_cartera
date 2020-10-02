<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class conceptos_mvto_controller extends ControladorBase
{

    private $model;
    
    function __construct()
    {
        parent::__construct();
        $this->model = cargarModel('conceptos_mvto');
        $this->model->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

    }


    function index()
    {      
        $vista = cargarView("conceptos_mvto");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("conceptos_mvto");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $a = $this->model;
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $a->load_data([
            'codconcepto'=>$data['codconcepto'],
            'desconcepto'=>$data['desconcepto'],
            'aplica_tabla_porc'=>$data['aplica_tabla_porc'],
            'vlr_base'=>$data['vlr_base'],
            'aplica_cree'=>$data['aplica_cree'],
            'causa_gasto'=>$data['causa_gasto'],
            'codtipo_cuenta'=>$data['codtipo_cuenta'],
            'cruza_rodamiento'=>$data['cruza_rodamiento'],
            'cuenta_contable'=>$data['cuenta_contable'],
            'impto_finan'=>$data['impto_finan'],
            'modulo_uso'=>$data['modulo_uso'],
            'naturaleza'=>$data['naturaleza'],
            'porc_aplica'=>$data['porc_aplica'],
            'porc_aplica2'=>$data['porc_aplica2'],
            'solicita_centro_co'=>$data['solicita_centro_co'],
            'solicita_codcuenta'=>$data['solicita_codcuenta'],
            'solicita_det_fact'=>$data['solicita_det_fact'],
            'solicita_documento'=>$data['solicita_documento'],
            'solicita_tercero'=>$data['solicita_tercero'],
        ]);

        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                switch ($value['aplica_tabla_porc']) {
                    case 'S':
                        $value['aplica_tabla_porc'] = 'Si';
                        break;
                    case 'N':
                        $value['aplica_tabla_porc'] = 'No';
                        break;
                    case 'M':
                        $value['aplica_tabla_porc'] = 'Municipios';
                        break;
                    default:
                        $value['aplica_tabla_porc'] = $value['aplica_tabla_porc'];
                        break;
                }
                $value['aplica_cree'] = ($value['aplica_cree'] == 'S') ? 'Si' : 'No';
                $value['causa_gasto'] = ($value['causa_gasto'] == 'S') ? 'Si' : 'No';
                $value['cruza_rodamiento'] = ($value['cruza_rodamiento'] == 'S') ? 'Si' : 'No';
                $value['impto_finan'] = ($value['impto_finan'] == 'S') ? 'Si' : 'No';
                $value['modulo_uso'] = ($value['modulo_uso'] == 'M') ? 'Movimiento' : 'Factura';
                $value['naturaleza'] = ($value['naturaleza'] == 'C') ? 'Crédito' : 'Débito';
                $value['solicita_centro_co'] = ($value['solicita_centro_co'] == 'N') ? 'No' : 'Si';
                $value['solicita_codcuenta'] = ($value['solicita_codcuenta'] == 'N') ? 'No' : 'Si';
                $value['solicita_det_fact'] = ($value['solicita_det_fact'] == 'N') ? 'No' : 'Si';
                $value['solicita_documento'] = ($value['solicita_documento'] == 'N') ? 'No' : 'Si';
                $value['solicita_tercero'] = ($value['solicita_tercero'] == 'N') ? 'No' : 'Si';

                $dataTable['data'][] = [
                    'row_id'=>$value['codconcepto'],
                    'desconcepto'=>$value['desconcepto'],
                    'aplica_tabla_porc'=>$value['aplica_tabla_porc'],
                    'vlr_base'=>$value['vlr_base'],
                    'aplica_cree'=>$value['aplica_cree'],
                    'causa_gasto'=>$value['causa_gasto'],
                    'codtipo_cuenta'=>$value['codtipo_cuenta'],
                    'cruza_rodamiento'=>$value['cruza_rodamiento'],
                    'cuenta_contable'=>(!empty($value['cuenta_contable_text'])) ? $value['cuenta_contable'].' : '.$value['cuenta_contable_text'] : $value['cuenta_contable'],
                    'impto_finan'=>$value['impto_finan'],
                    'modulo_uso'=>$value['modulo_uso'],
                    'naturaleza'=>$value['naturaleza'],
                    'porc_aplica'=>$value['porc_aplica'],
                    'porc_aplica2'=>$value['porc_aplica2'],
                    'solicita_centro_co'=>$value['solicita_centro_co'],
                    'solicita_codcuenta'=>$value['solicita_codcuenta'],
                    'solicita_det_fact'=>$value['solicita_det_fact'],
                    'solicita_documento'=>$value['solicita_documento'],
                    'solicita_tercero'=>$value['solicita_tercero'],
                ];
            }
        
        }

        echo json_encode($dataTable);
    

    }

    function loadNextNumber(){
        $a = $this->model;
        $return['results']=[];
        $a->loadNextNumber();
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        echo json_encode($datosOdbc);
    }

    function fillOutAutocompleteCuenta()
    {
        $a = $this->model;
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_CuentaContable(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc, function(&$item){$item=utf8_encode($item);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        foreach ($datosOdbc as $key => $value) {
            $return['results'][] =  $value;
          }
        echo json_encode($return);
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

        $a->load_data([
            'codconcepto'=>$data['codconcepto'],
        ]);
        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($dataValid,function (&$entry) {$entry=utf8_encode($entry);});

        if (empty($data['codconcepto'])) {
            $success = 'error';
            $msg = 'Por favor ingrese el código del concepto.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto';
        }else if (!empty($dataValid)) {
            $success = 'error';
            $msg = 'El código de tipo de concepto '.$data['codconcepto'].' ya existe.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto';
        }else if (empty($data['codtipo_cuenta'])) {
            $success = 'error';
            $msg = 'Por favor seleccione el tipo de cuenta del concepto.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtipo_cuenta';
        }else{       
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'conceptos_mvto',
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


     public function editRecord(){
        $a = $this->model;
        $usuario = $_SESSION["session_intranet_login"];
        $return = [];
        $success = 'success';
        $msg = 'El registro fue editado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];
        $a->load_data([
            'codconcepto'=>$data['codconcepto'],
        ]);
        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($dataValid,function (&$entry) {$entry=utf8_encode($entry);});

        if (empty($data['codtipo_cuenta'])) {
            $success = 'error';
            $msg = 'Por favor seleccione el tipo de cuenta del concepto.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtipo_cuenta';
        }else{
            $a->editRecord([
                'codconcepto'=>$data['codconcepto'],
                'desconcepto'=>$data['desconcepto'],
                'aplica_tabla_porc'=>$data['aplica_tabla_porc'],
                'vlr_base'=>$data['vlr_base'],
                'aplica_cree'=>$data['aplica_cree'],
                'causa_gasto'=>$data['causa_gasto'],
                'codtipo_cuenta'=>$data['codtipo_cuenta'],
                'cruza_rodamiento'=>$data['cruza_rodamiento'],
                'impto_finan'=>$data['impto_finan'],
                'modulo_uso'=>$data['modulo_uso'],
                'naturaleza'=>$data['naturaleza'],
                'porc_aplica'=>$data['porc_aplica'],
                'porc_aplica2'=>$data['porc_aplica2'],
                'solicita_centro_co'=>$data['solicita_centro_co'],
                'solicita_codcuenta'=>$data['solicita_codcuenta'],
                'solicita_det_fact'=>$data['solicita_det_fact'],
                'solicita_documento'=>$data['solicita_documento'],
                'solicita_tercero'=>$data['solicita_tercero'],
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
            $a->load_data(['codconcepto'=>$data[0]]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            $vista = cargarView("conceptos_mvto");   
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

     public function deleteRecord(){
        $a = $this->model;
        $usrmodi = $_SESSION["session_intranet_login"];

        $return = [];
        $success = 'success';
        $msg = 'El registro fue eliminado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        foreach ($data as $key => $value) {
            $a->validRemove(['codconcepto'=>$value]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            if ($dataValid[0]['cnt']>0) {
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
            $msg = 'El registro '.$consec_error.' no se puede eliminar.';
            $icon = 'warning';
            $title = 'Error!';
        }else{
            if (count($data)>1) {
                $msg = "Los registros fueron eliminados satisfactoriamente.";
            }
            foreach ($data as $key => $value) {
                $a->deleteRecord([
                    'codconcepto'=>$value
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

   
}
