<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class conc_munic_controller extends ControladorBase
{

    private $model;
    
    function __construct()
    {
        parent::__construct();
        $this->model = cargarModel('conc_munic');
        $this->model->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

    }


    function index()
    {      
        $vista = cargarView("conc_munic");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("conc_munic");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $a = $this->model;
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $tales = $a->load_data([
            'codmunicipio'=>$data['codmunicipio'],
            'codconcepto'=>$data['codconcepto'],
            'base_liquidacion'=>$data['base_liquidacion'],
            'porc_aplica'=>$data['porc_aplica'],
            'aplica_ica_tercero'=>$data['aplica_ica_tercero'],
        ]);

        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                switch ($value['aplica_ica_tercero']) {
                    case 'S':
                        $value['aplica_ica_tercero'] = 'Si';
                        break;
                    case 'N':
                        $value['aplica_ica_tercero'] = 'No';
                        break;
                    default:
                        $value['aplica_ica_tercero'] = $value['aplica_ica_tercero'];
                        break;
                }

                $dataTable['data'][] = [
                    'row_id'=>$value['codconcepto'],
                    'codmunicipio'=>(!empty($value['codmunicipio_text'])) ? $value['codmunicipio']." : ".$value['codmunicipio_text'] : $value['codmunicipio'],
                    'codconcepto_text'=>$value['codconcepto_text'],
                    'base_liquidacion'=>number_format($value['base_liquidacion'], 2),
                    'porc_aplica'=> (!empty($value['porc_aplica'])) ? number_format($value['porc_aplica'])." %" : $value['porc_aplica'],
                    'aplica_ica_tercero'=>$value['aplica_ica_tercero'],                  
                ];
            }
        
        }

        echo json_encode($dataTable);
    

    }

    function fillOutAutocompleteMunicipio()
    {
        $a = $this->model;
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_Municipios(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc, function(&$item){$item=utf8_encode($item);});
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        foreach ($datosOdbc as $key => $value) {
            $return['results'][] =  $value;
          }
        echo json_encode($return);
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
            'codconcepto_unique'=>$data['codconcepto'],
            'codmunicipio'=>$data['codmunicipio'],
        ]);

        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($dataValid,function (&$entry) {$entry=utf8_encode($entry);});

        if (empty($data['codconcepto'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un concepto de movimiento.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto';
        }else if (!empty($dataValid)) {
            $success = 'error';
            $msg = 'El concepto '.$data['codconcepto'].' : '.$dataValid[0]['codconcepto_text'].' ya existe para el municipio '.$dataValid[0]['codmunicipio_text'].'.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto';
        }else{       
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'conceptos_munic',
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


        if (empty($data['codconcepto'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un concepto de movimiento.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codconcepto';
        }else{

            $a->disableRecord([
                'codmunicipio'=>$data['codmunicipio'],
                'codconcepto'=>$data['codconcepto'],
                'usrmodi' => $usuario,
                'fecmodi' => date('m-d-Y'),
                'horamod' => date('H:i:s'),

            ]);
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'conceptos_munic',
                'conten'=>$data
            ]);
            $a->createRecord($insert);
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
        $codmunicipio = $_POST['codmunicipio'];

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
            $sql = $a->load_data([
                'codconcepto_unique'=>$data[0],
                'codmunicipio'=>$codmunicipio,
            ]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
        }

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
        $codmunicipio = $_POST['codmunicipio'];


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
                'codmunicipio'=>$codmunicipio,
                'codconcepto'=>$value,
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
