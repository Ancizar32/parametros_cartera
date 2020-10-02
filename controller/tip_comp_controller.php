<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class tip_comp_controller extends ControladorBase
{

    private $model;
    
    function __construct()
    {
        parent::__construct();
        $this->model = cargarModel('tip_comp');
        $this->model->odbc(ODBCINFORMIX, ODBC_USERINFORMIX, ODBC_PASSINFORMIX, '', INICIAR_SIMULADOR);

    }


    function index()
    {      
        $vista = cargarView("tip_comp");     
        $vista->dibujar();        
    }

    function createModal()
    {      
        $vista = cargarView("tip_comp");   
        $vista::createModal();         
    }

    function reloadTable()
    {
        $a = $this->model;
        $data = $_POST['param'];
        $dataTable['data'] = [];
        $a->load_data([
            'codtp_comprobante'=>$data['codtp_comprobante'],
            'destp_comprobante'=>$data['destp_comprobante'],
            'nattp_comprobante'=>$data['nattp_comprobante'],
            'codcompania'=>$data['codcompania'],
            'tipo_causacion'=>$data['tipo_causacion'],
            'modulo_uso'=>$data['modulo_uso'],
            'maneja_cons'=>$data['maneja_cons'],
            'imprime_comp'=>$data['imprime_comp'],
            'comp_contable'=>$data['comp_contable'],
            'afecta_caja'=>$data['afecta_caja'],
            'forma_pago'=>($data['forma_pago'] == 'Todas') ? 'T' : $data['forma_pago'],
        ]);
        $datosOdbc = $a->getDatosOdbc()->getRegistroAll();
        array_walk_recursive($datosOdbc,function (&$entry) {$entry=utf8_encode($entry);});
        if (!empty($datosOdbc)) {
            foreach ($datosOdbc as $key => $value) {
                switch ($value['nattp_comprobante']) {
                    case 'D':
                        $natt_c = 'Debito';
                        break;
                    case 'C':
                        $natt_c = 'Crédito';
                        break;
                    default:
                        $natt_c = 'No Aplica';
                        break;
                }
                if($value['forma_pago']=='T'){$value['forma_pago_text']='Todas';}
                $dataTable['data'][] = [
                    'row_id'=>$value['codtp_comprobante'],
                    'col1'=>$value['destp_comprobante'],
                    'col2'=>$natt_c,
                    'col3'=>(!empty(trim($value['codcompania']))) ? $value['codcompania'].' : '.$value['codcompania_text'] : '',
                    'col4'=>$value['tipo_causacion'],
                    'col5'=>($value['modulo_uso'] == 'M') ? 'Movimiento' : 'Causación',
                    'col6'=>($value['maneja_cons'] == 'S') ? 'Si' : 'No',
                    'col7'=>($value['imprime_comp'] == 'S') ? 'Si' : 'No',
                    'col8'=>($value['comp_contable'] == 'C') ? 'Consignación' : 'Movimiento',
                    'col9'=>($value['afecta_caja'] == 'S') ? 'Si' : 'No',
                    'col10'=>(!empty(trim($value['forma_pago']))) ? $value['forma_pago'].' : '.$value['forma_pago_text'] : '',
                ];
            }
        
        }
        

        echo json_encode($dataTable);
    

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

    function crear()
    {
        $a = $this->model;

        $return = [];
        $success = 'success';
        $msg = 'El registro fue creado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $setFocus = '';
        $data = $_POST['param'];
        $a->load_data(['codtp_comprobante'=>$data['codtp_comprobante']]);

        $dataValid = $a->getDatosOdbc()->getRegistroAll();
        if (empty($data['codtp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor digite un código para tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtp_comprobante';
        }else if (!empty($dataValid)) {
            $success = 'error';
            $msg = 'El código de tipo de comprobante '.$data['codtp_comprobante'].' ya existe.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtp_comprobante';
        }else if (empty($data['destp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor digite una descripción para tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'destp_comprobante';
        }else if (empty($data['nattp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor seleccione una naturaleza de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'nattp_comprobante';
        }else if (empty($data['tipo_causacion'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un tipo de causación.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'tipo_causacion';
        }else if (empty($data['modulo_uso'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un modulo disponible para este comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'modulo_uso';
        }else if (empty($data['maneja_cons'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si maneja control de consecutivo. (S/N).';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'maneja_cons';
        }else if (empty($data['imprime_comp'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si imprimir detalles de la empresa.(S/N).';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'imprime_comp';
        }else if (empty($data['comp_contable'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'comp_contable';
        }else if (empty($data['afecta_caja'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si afecta efectivo en caja segun forma de pago. (S/N).';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'afecta_caja';
        }else{
            $insert = $a->crearSentenciaInsert([
                'tabla'=>'tipos_comprobantes',
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

     public function editRecord(){
        $a = $this->model;

        $return = [];
        $success = 'success';
        $msg = 'El registro fue editado satisfactoriamente.';
        $icon = 'success';
        $title = 'Genial!';
        $data = $_POST['param'];

        if (empty($data['codtp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor digite un código para tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtp_comprobante'.$data['codtp_comprobante'];
        }else if (!empty($dataValid)) {
            $success = 'error';
            $msg = 'El código de tipo de comprobante '.$data['codtp_comprobante'].' ya existe.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'codtp_comprobante'.$data['codtp_comprobante'];
        }else if (empty($data['destp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor digite una descripción para tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'destp_comprobante'.$data['codtp_comprobante'];
        }else if (empty($data['nattp_comprobante'])) {
            $success = 'error';
            $msg = 'Por favor seleccione una naturaleza de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'nattp_comprobante'.$data['codtp_comprobante'];
        }else if (empty($data['tipo_causacion'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un tipo de causación.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'tipo_causacion'.$data['codtp_comprobante'];
        }else if (empty($data['modulo_uso'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un modulo disponible para este comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'modulo_uso'.$data['codtp_comprobante'];
        }else if (empty($data['maneja_cons'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si maneja control de consecutivo. (S/N).';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'maneja_cons'.$data['codtp_comprobante'];
        }else if (empty($data['imprime_comp'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si imprimir detalles de la empresa.(S/N).';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'imprime_comp'.$data['codtp_comprobante'];
        }else if (empty($data['comp_contable'])) {
            $success = 'error';
            $msg = 'Por favor seleccione un tipo de comprobante.';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'comp_contable'.$data['codtp_comprobante'];
        }else if (empty($data['afecta_caja'])) {
            $success = 'error';
            $msg = 'Por favor seleccione si afecta efectivo en caja segun forma de pago. (S/N).';
            $icon = 'error';
            $title = 'Error!';
            $setFocus = 'afecta_caja'.$data['codtp_comprobante'];
        }else{
            $a->editRecord([
                'destp_comprobante'=>$data['destp_comprobante'],
                'nattp_comprobante'=>$data['nattp_comprobante'],
                'codcompania'=>$data['codcompania'],
                'tipo_causacion'=>$data['tipo_causacion'],
                'modulo_uso'=>$data['modulo_uso'],
                'maneja_cons'=>$data['maneja_cons'],
                'imprime_comp'=>$data['imprime_comp'],
                'comp_contable'=>$data['comp_contable'],
                'afecta_caja'=>$data['afecta_caja'],
                'cuenta_pagar'=>$data['cuenta_pagar'],
                'forma_pago'=>$data['forma_pago'],
                'codtp_comprobante'=>$data['codtp_comprobante'],
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
            $a->load_data(['codtp_comprobante'=>$data[0]]);
            $dataValid = $a->getDatosOdbc()->getRegistroAll();
            $vista = cargarView("tip_comp");   
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

}
