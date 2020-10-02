<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class grupo_concepto_controller extends ControladorBase
{

    private $model;
    
    function __construct()
    {
        parent::__construct();
        $this->model = cargarModel('grupo_concepto');
        $this->model->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

    }


    function index()
    {      
        $vista = cargarView("grupo_concepto");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("grupo_concepto");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $a = $this->model;
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $tales = $a->load_data([
            'codconcepto'=>$data['codconcepto'],
            'codconcepto_base'=>$data['codconcepto_base'],
            'tp_tercero'=>$data['tp_tercero'],
            'tp_regimen'=>$data['tp_regimen'],
            'g_contribuyente'=>$data['g_contribuyente'],
            'auto_retenedor'=>$data['auto_retenedor'],
            'ext_reteica'=>$data['ext_reteica'],
            'auto_reteica'=>$data['auto_reteica'],
        ]);

        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                switch ($value['tp_tercero']) {
                    case 'N':
                        $value['tp_tercero'] = 'Natural';
                        break;
                    case 'J':
                        $value['tp_tercero'] = 'Jurídico';
                        break;
                    case 'T':
                        $value['tp_tercero'] = 'Todos';
                        break;
                    default:
                        $value['tp_tercero'] = $value['tp_tercero'];
                        break;
                }

                switch ($value['tp_regimen']) {
                    case 'C':
                        $value['tp_regimen'] = 'Común';
                        break;
                    case 'S':
                        $value['tp_regimen'] = 'Simplificado';
                        break;
                    case 'T':
                        $value['tp_regimen'] = 'Todos';
                        break;
                    default:
                        $value['tp_regimen'] = $value['tp_regimen'];
                        break;
                }

                switch ($value['g_contribuyente']) {
                    case 'S':
                        $value['g_contribuyente'] = 'Si';
                        break;
                    case 'N':
                        $value['g_contribuyente'] = 'No';
                        break;
                    case 'T':
                        $value['g_contribuyente'] = 'Todos';
                        break;
                    default:
                        $value['g_contribuyente'] = $value['g_contribuyente'];
                        break;
                }

                switch ($value['auto_retenedor']) {
                    case 'S':
                        $value['auto_retenedor'] = 'Si';
                        break;
                    case 'N':
                        $value['auto_retenedor'] = 'No';
                        break;
                    case 'T':
                        $value['auto_retenedor'] = 'Todos';
                        break;
                    default:
                        $value['auto_retenedor'] = $value['auto_retenedor'];
                        break;
                }

                switch ($value['ext_reteica']) {
                    case 'S':
                        $value['ext_reteica'] = 'Si';
                        break;
                    case 'N':
                        $value['ext_reteica'] = 'No';
                        break;
                    case 'T':
                        $value['ext_reteica'] = 'Todos';
                        break;
                    default:
                        $value['ext_reteica'] = $value['ext_reteica'];
                        break;
                }

                switch ($value['auto_reteica']) {
                    case 'S':
                        $value['auto_reteica'] = 'Si';
                        break;
                    case 'N':
                        $value['auto_reteica'] = 'No';
                        break;
                    case 'T':
                        $value['auto_reteica'] = 'Todos';
                        break;
                    default:
                        $value['auto_reteica'] = $value['auto_reteica'];
                        break;
                }

                $dataTable['data'][] = [
                    'row_id'=>$value['codconcepto_base'],
                    'codconcepto'=>(!empty($value['codconcepto_text'])) ? $value['codconcepto']." : ".$value['codconcepto_text'] : $value['codconcepto'],
                    'codconcepto_base_text'=>$value['codconcepto_base_text'],
                    'tp_tercero'=>$value['tp_tercero'],
                    'tp_regimen'=>$value['tp_regimen'],
                    'g_contribuyente'=>$value['g_contribuyente'],
                    'auto_retenedor'=>$value['auto_retenedor'],
                    'ext_reteica'=>$value['ext_reteica'],
                    'auto_reteica'=>$value['auto_reteica'],                   
                ];
            }
        
        }

        echo json_encode($dataTable);
    

    }

    function fillOutAutocompleteConceptoMvto()
    {
        $a = $this->model;
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_ConceptoMvto(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc, function(&$item){$item=utf8_encode($item);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        foreach ($datosOdbc as $key => $value) {
            $return['results'][] =  $value;
          }
        echo json_encode($return);
    }

    function fillOutAutocompleteConceptoMvtoGroup()
    {
        $a = $this->model;
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_ConceptoMvtoGroup(['term'=>$term]);
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
        $msg = 'El registro fue agregado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $setFocus = '';
        $data = $_POST['param'];
        $data['estado'] = 'A';
        $data['usrcrea'] = $usuario;
        $data['feccrea'] = date('m-d-Y');
        $data['horacre'] = date('H:i:s');

        $a->load_data([
            'codconcepto_base_unique'=>$data['codconcepto_base'],
            'codconcepto'=>$data['codconcepto'],
        ]);

        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($dataValid,function (&$entry) {$entry=utf8_encode($entry);});

        if (empty($data['codconcepto'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un concepto de movimiento.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto';
        }else if (empty($data['codconcepto_base'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un concepto base.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto_base';
        }else if (empty($data['tp_tercero'])) {
            $success = 'error';
            $msg = 'Por favor especifique un tercero.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'tp_tercero';
        }else if (empty($data['tp_regimen'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un régimen.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'tp_regimen';
        }else if (empty($data['g_contribuyente'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si es gran contribuyente.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'g_contribuyente';
        }else if (empty($data['auto_retenedor'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si es auto-retenedor.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'auto_retenedor';
        }else if (empty($data['ext_reteica'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si esta exento de reteica.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'ext_reteica';
        }else if (empty($data['auto_reteica'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si es auto-reteica.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'auto_reteica';
        }else if (!empty($dataValid)) {
            $success = 'error';
            $msg = 'El concepto '.$data['codconcepto_base'].' : '.$dataValid[0]['codconcepto_base_text'].' ya existe para el grupo '.$data['codconcepto'].' : '.$dataValid[0]['codconcepto_text'].'.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto_base';
        }else{       
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'grupo_concepto',
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
        $data['estado'] = 'A';
        $data['usrcrea'] = $usuario;
        $data['feccrea'] = date('m-d-Y');
        $data['horacre'] = date('H:i:s');

        $dataT = $a->load_data([
            'codconcepto'=>$data['codconcepto'],
        ]);

        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($dataValid,function (&$entry) {$entry=utf8_encode($entry);});


        if (empty($data['tp_tercero'])) {
            $success = 'error';
            $msg = 'Por favor especifique un tercero.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'tp_tercero';
        }else if (empty($data['tp_regimen'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un régimen.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'tp_regimen';
        }else if (empty($data['g_contribuyente'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si es gran contribuyente.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'g_contribuyente';
        }else if (empty($data['auto_retenedor'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si es auto-retenedor.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'auto_retenedor';
        }else if (empty($data['ext_reteica'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si esta exento de reteica.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'ext_reteica';
        }else if (empty($data['auto_reteica'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si es auto-reteica.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'auto_reteica';
        }else{
           // $success = 'error';
            array_walk($dataValid, function($item) use($data, $a, $usuario){
                $data['codconcepto_base'] = $item['codconcepto_base'];

                $a->disableRecord([
                    'codconcepto'=>$item['codconcepto'],
                    'codconcepto_base'=>$item['codconcepto_base'],
                    'usrmodi' => $usuario,
                    'fecmodi' => date('m-d-Y'),
                    'horamod' => date('H:i:s'),

                ]);

                $insert = $a->crearSentenciaInsert([
                    'tabla'=>'grupo_concepto',
                    'conten'=>$data
                ]);

                $a->createRecord($insert);
            });
            
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

        $codconcepto = $_POST['codconcepto'];

        $a = $this->model;

            $a->load_data([
                'codconcepto'=>$codconcepto,
            ]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
        

         $return = [
            'success'=>$success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'details'=>$dataValid,
        ];
        echo json_encode($return);       
    }

     public function deleteRecord(){
        $a = $this->model;
        $usuario = $_SESSION["session_intranet_login"];

        $return = [];
        $success = 'success';
        $msg = 'El registro fue eliminado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];
        $codconcepto = $_POST['codconcepto'];


        if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione uno o varios registros de la tabla';
            $icon = 'warning';
            $title = 'Advertencia!';
        }else{
            if (count($data)>1) {
                $msg = "Los registros fueron eliminados satisfactoriamente.";
            }
            foreach ($data as $key => $value) {
                $val = $a->disableRecord([
                'codconcepto'=>$codconcepto,
                'codconcepto_base'=>$value,
                'usrmodi' => $usuario,
                'fecmodi' => date('m-d-Y'),
                'horamod' => date('H:i:s'),

            ]);
            }
        }

        $return = [
            'success'=>$success,
            'message'=>$msg,
            'icon'=>$icon,
            'title'=>$title,
            'data'=>$val,
        ];
        echo json_encode($return);
    }

   
}
