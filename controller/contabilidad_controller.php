<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class contabilidad_controller extends ControladorBase
{

    /**
     * se llama al constructor del padre
     */
    function __construct()
    {
        parent::__construct();
    }


    function index()
    {      
        $vista = cargarView("contabilidad");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("contabilidad");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $modelo = cargarModel('contabilidad');
        $modelo->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $modelo->load_data([
            'codigo'=>$data['codigo'],
            'codctble'=>$data['codctble'],
            'ctactble'=>$data['ctactble'],
            'ctactble2'=>$data['ctactble2'],
            'descrip'=>$data['descrip'],
            'estado'=>$data['estado'],
            'tipo'=>$data['tipo'],
        ]);
        $datosOdbc = $modelo->getDatosOdbc()->getRegistroAll();
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                if($value['estado']=='A'){
                    $estado = 'Activo';
                }elseif ($value['estado']=='I') {
                    $estado = 'Inactivo';
                }elseif ($value['estado']=='E') {
                    $estado = 'Egreso';
                }else{
                    $estado = 'Error';
                }
                $dataTable['data'][] = [
                    'row_id'=>$value['codigo'],
                    'col1'=>$value['descrip'],
                    'col2'=>(!empty(trim($value['tipo']))) ? $value['tipo'].' : '.$value['tc_nomcom'] : '',
                    'col3'=>(!empty(trim($value['codctble']))) ? $value['codctble'].' : '.$value['codctble_text'] : '',
                    'col4'=>(!empty(trim($value['ctactble']))) ? $value['ctactble'].' : '.$value['ctactble_text'] : '',
                    'col5'=>(!empty(trim($value['ctactble2']))) ? $value['ctactble2'].' : '.$value['ctactble2_text'] : '',
                    'col6'=>$estado,
                ];
            }
        
        }
        

        echo json_encode($dataTable);
    

    }

    function fillOutAutocomplete()
    {
        $a = cargarModel('contabilidad');
        $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
        $return['results']=[];
        $term = $_POST['data']['term'];
        $a->load_TipoCuenta(['term'=>$term]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
        foreach ($datosOdbc as $key => $value) {
            $return['results'][] =  $value;
          }


        echo json_encode($return);
    

    }

    function fillOutAutocompleteCuenta()
        {
            $a = cargarModel('contabilidad');
            $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
            $return['results']=[];
            $term = $_POST['data']['term'];
            $a->load_CuentaContable(['term'=>$term]);
            $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
            array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
            foreach ($datosOdbc as $key => $value) {
                $return['results'][] =  $value;
              }


            echo json_encode($return);
        

        }

    function fillOutAutocompleteTipDoc()
        {
            $a = cargarModel('contabilidad');
            $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
            $return['results']=[];
            $term = $_POST['data']['term'];
            $a->load_TipoDocumento(['term'=>$term]);
            $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
            array_unshift($datosOdbc, ['id' => '', 'text' => '-- Sin selección --']);
            foreach ($datosOdbc as $key => $value) {
                $return['results'][] =  $value;
              }

            echo json_encode($return);
        

        }


    function crear()
    {
        $a = cargarModel('contabilidad');
        $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

        $return = [];
        $success = 'success';
        $msg = 'El registro fue creado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $setFocus = '';
        $data = $_POST['param'];
        $a->load_data(['codigo'=>$data['codigo']]);

        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        if (empty($data['codigo'])) {
            $success = 'error';
            $msg = 'Por favor digite un código para nota de contabilidad.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codigo';
        }
        else if (empty($data['descrip'])) {
            $success = 'error';
            $msg = 'Por favor digite una descripción para nota de contabilidad.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'descrip';
        }
        else if (!empty($dataValid)) {
            $success = 'error';
            $msg = 'El código de nota de contabilidad '.$data['codigo'].' ya existe.';
            $icon = 'error';
            $title = 'Error!';
        }else{
            $data['estado'] = ($data['estado'] == 'true') ? 'E' : 'A';
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'s3tiptrans',
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

    public function disableRecord(){
        $a = cargarModel('contabilidad');
        $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

        $return = [];
        $success = 'success';
        $msg = 'El registro fue inhabilitado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione uno o varios registros de la tabla';
            $icon = 'warning';
            $title = 'Advertencia!';
        }else{
            if (count($data)>1) {
                $msg = "Los registros fueron inhabilitados satisfactoriamente.";
            }
            foreach ($data as $key => $value) {
                $a->disableRecord($value);
            }
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

    public function enableRecord(){
        $a = cargarModel('contabilidad');
        $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

        $return = [];
        $success = 'success';
        $msg = 'El registro fue habilitado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        if (empty($data)) {
            $success = 'error';
            $msg = 'Por favor seleccione uno o varios registros de la tabla';
            $icon = 'warning';
            $title = 'Advertencia!';
        }else{
            if (count($data)>1) {
                $msg = "Los registros fueron habilitados satisfactoriamente.";
            }
            foreach ($data as $key => $value) {
                $a->enableRecord($value);
            }
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

     public function editRecord(){
        $a = cargarModel('contabilidad');
        $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

        $return = [];
        $success = 'success';
        $msg = 'El registro fue editado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        if (empty($data['descrip_edit'])) {
            $success = 'error';
            $msg = 'Por favor digite una descripción para nota de contabilidad.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'descrip_edit';
        } else {
            $data['estado_edit'] = ($data['estado_edit'] == 'true') ? 'E' : 'A';
            $a->editRecord([
                'codctble'=>$data['codctble_edit'],
                'ctactble'=>$data['ctactble_edit'],
                'ctactble2'=>$data['ctactble2_edit'],
                'descrip'=>$data['descrip_edit'],
                'estado'=>$data['estado_edit'],
                'tipo'=>$data['tipo_edit'],
                'codigo'=>$data['codigo_edit'],
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

        $a = cargarModel('contabilidad');
        $a->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);
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
            $a->load_data(['codigo'=>$data[0]]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            $vista = cargarView("contabilidad");   
            $data = $vista::editModal([
                'codctble'=>trim($dataValid[0]['codctble']),
                'ctactble'=>trim($dataValid[0]['ctactble']),
                'ctactble2'=>trim($dataValid[0]['ctactble2']),
                'descrip'=>$dataValid[0]['descrip'],
                'estado'=>$dataValid[0]['estado'],
                'tipo'=>trim($dataValid[0]['tipo']),
                'tipo_text'=>$dataValid[0]['tc_nomcom'],
                'codigo'=>$dataValid[0]['codigo'],
                'codigo_id'=>trim($dataValid[0]['codigo']),
                'ctactble_text'=>trim($dataValid[0]['ctactble_text']),
                'ctactble2_text'=>trim($dataValid[0]['ctactble2_text']),
                'codctble_text'=>trim($dataValid[0]['codctble_text']),
            ]);  
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

}
