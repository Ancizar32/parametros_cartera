<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class tip_comp_view extends VistaBase
{

/**
* Pintar la pantalla principal
*/
function dibujar()
{
?>
<link rel="stylesheet" type="text/css" href="resources/css/contabilidad.css"/>
<link rel="stylesheet" type="text/css" href="resources/lib/DataTables/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="resources/lib/bootstrap-4.5.2/dist/css/bootstrap.min.css">
<script type="text/javascript" src="resources/lib/bootstrap-4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="resources/lib/DataTables/datatables.min.js"></script>
<div class="container-fluid" id="bodyview">
    <div class="card">
    <h5 class="card-header"><span class="text-center">Tipos de comprobante</span></h5>
        <div class="card-body">
            <div class="form-row" id="main_buttons">
                <div class="form-group col-sm-6">
                    <form class="form-inline">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="create_button"> Nuevo tipo de comprobante</a>
                            <a class="dropdown-item" href="#" id="edit_button">Editar tipo de comprobante</a>
                            <!-- <a class="dropdown-item" href="#" id="disable_button">Inhabilitar nota</a>
                            <a class="dropdown-item" href="#" id="enable_button">Habilitar nota</a> -->
                            <a class="dropdown-item" href="#" id="select_all">Seleccionar Todo</a>
                            <a class="dropdown-item" href="#" id="deselect_all">Deseleccionar Todo</a>
                          </div>
                        </div>
                        <button id="advance_search_button" type="button" class="btn btn-primary mr-4 my-2">Busqueda avanzada</button>
                    </form>
                </div>
            </div>
            <table id="table_comp" class="table table-bordered dt-responsive" style="width:100%"></table>
        </div>
    </div>
</div>


        <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div id="advanceSearch_modal_tip_comp">
             <div class="form-row">
                <div class="form-group col-sm-4">
                  <label for="codtp_comprobante_advance" class="col-form-label">Código tipo de comprobante</label>
                  <input type="text" class="form-control" id="codtp_comprobante_advance" >
                </div>
                <div class="form-group col-sm-8">
                  <label for="destp_comprobante_advance" class="col-form-label">Descripción comprobante</label>
                  <input type="text" class="form-control" id="destp_comprobante_advance" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="nattp_comprobante_advance" class="col-form-label">Naturaleza comprobante</label>
                  <div id="nattp_comprobante_advance_content">
                    <select class="form-control" id="nattp_comprobante_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="D">Debito</option>
                      <option value="C">Credito</option>
                      <option value="N">No Aplica</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-6">
                  <label for="codcompania_advance" class="col-form-label">Compañia</label>
                  <input type="text" class="form-control" id="codcompania_advance" >  
                </div> 
                <div class="form-group col-sm-2">
                  <label for="tipo_causacion_advance" class="col-form-label">Tipo de causación</label>
                  <div id="tipo_causacion_advance_content">
                    <select class="form-control" id="tipo_causacion_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="modulo_uso_advance" class="col-form-label">Modulo disponible para este comprobante</label>
                  <div id="modulo_uso_advance_content">
                    <select class="form-control" id="modulo_uso_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="M">Movimiento</option>
                      <option value="F">Causaciones</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="maneja_cons_advance" class="col-form-label">Maneja control de consecutivo (S/N)?</label>
                  <div id="maneja_cons_advance_content">
                    <select class="form-control" id="maneja_cons_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="imprime_comp_advance" class="col-form-label">Imprimir detalles de la empresa.(S/N)</label>
                  <div id="imprime_comp_advance_content">
                    <select class="form-control" id="imprime_comp_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="comp_contable_advance" class="col-form-label">Tipo de Comprobante Movimiento, Consignacion.</label>
                  <div id="comp_contable_advance_content">
                    <select class="form-control" id="comp_contable_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="C">Consignación</option>
                      <option value="M">Movimiento</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="afecta_caja_advance" class="col-form-label">Afecta efectivo en caja segun forma de pago? S/N</label>
                  <div id="afecta_caja_advance_content">
                    <select class="form-control" id="afecta_caja_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="forma_pago_advance" class="col-form-label">Forma de pago valida para este Comprogante(T-Todas)</label>
                  <input type="text" class="form-control" id="forma_pago_advance" > 
                </div>
                
             </div>
          </div>

          <div id="newRecord_modal_tip_comp">
             <div class="form-row">
                <div class="form-group col-sm-4">
                  <label for="codtp_comprobante" class="col-form-label">Código tipo de comprobante</label>
                  <input type="text" class="form-control" id="codtp_comprobante" >
                </div>
                <div class="form-group col-sm-8">
                  <label for="destp_comprobante" class="col-form-label">Descripción comprobante</label>
                  <input type="text" class="form-control" id="destp_comprobante" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="nattp_comprobante" class="col-form-label">Naturaleza comprobante</label>
                  <div id="nattp_comprobante_content">
                    <select class="form-control" id="nattp_comprobante" >
                      <option value="">-- Seleccionar --</option>
                      <option value="D">Debito</option>
                      <option value="C">Credito</option>
                      <option value="N">No Aplica</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-6">
                  <label for="codcompania" class="col-form-label">Compañia</label>
                  <div id="codcompania_content">
                    <select class="form-control" id="codcompania"></select>
                  </div>   
                </div> 
                <div class="form-group col-sm-2">
                  <label for="tipo_causacion" class="col-form-label">Tipo de causación</label>
                  <div id="tipo_causacion_content">
                    <select class="form-control" id="tipo_causacion" >
                      <option value="">-- Seleccionar --</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="modulo_uso" class="col-form-label">Modulo disponible para este comprobante</label>
                  <div id="modulo_uso_content">
                    <select class="form-control" id="modulo_uso" >
                      <option value="">-- Seleccionar --</option>
                      <option value="M">Movimiento</option>
                      <option value="F">Causaciones</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="maneja_cons" class="col-form-label">Maneja control de consecutivo (S/N)?</label>
                  <div id="maneja_cons_content">
                    <select class="form-control" id="maneja_cons" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="imprime_comp" class="col-form-label">Imprimir detalles de la empresa.(S/N)</label>
                  <div id="imprime_comp_content">
                    <select class="form-control" id="imprime_comp" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="comp_contable" class="col-form-label">Tipo de Comprobante Movimiento, Consignacion.</label>
                  <div id="comp_contable_content">
                    <select class="form-control" id="comp_contable" >
                      <option value="">-- Seleccionar --</option>
                      <option value="C">Consignación</option>
                      <option value="M">Movimiento</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="afecta_caja" class="col-form-label">Afecta efectivo en caja segun forma de pago? S/N</label>
                  <div id="afecta_caja_content">
                    <select class="form-control" id="afecta_caja" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="forma_pago" class="col-form-label">Forma de pago valida para este Comprogante(T-Todas)</label>
                  <div id="forma_pago_content">
                    <select class="form-control" id="forma_pago" ></select>
                  </div>
                </div>
             </div>
          </div>

          <div class="form-row" id="editRecord_modal_tip_comp">
             <div class="form-row">
              <div class="form-group col-sm-4">
                <label for="codtp_comprobante_edit" class="col-form-label">Código tipo de comprobante</label>
                <input type="text" class="form-control" id="codtp_comprobante_edit" readonly>
              </div>
              <div class="form-group col-sm-8">
                <label for="destp_comprobante_edit" class="col-form-label">Descripción comprobante</label>
                <input type="text" class="form-control" id="destp_comprobante_edit" >
              </div>
              <div class="form-group col-sm-4">
                <label for="nattp_comprobante_edit" class="col-form-label">Naturaleza comprobante</label>
                <div id="nattp_comprobante_edit_content">
                  <select class="form-control" id="nattp_comprobante_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="D">Debito</option>
                    <option value="C">Credito</option>
                    <option value="N">No Aplica</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codcompania_edit" class="col-form-label">Compañia</label>
                <div id="codcompania_edit_content">
                  <select class="form-control" id="codcompania_edit"></select>
                </div>   
              </div> 
              <div class="form-group col-sm-2">
                <label for="tipo_causacion_edit" class="col-form-label">Tipo de causación</label>
                <div id="tipo_causacion_edit_content">
                  <select class="form-control" id="tipo_causacion_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>
              </div> 
              <div class="form-group col-sm-4">
                <label for="modulo_uso_edit" class="col-form-label">Modulo disponible para este comprobante</label>
                <div id="modulo_uso_edit_content">
                  <select class="form-control" id="modulo_uso_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="M">Movimiento</option>
                    <option value="F">Causaciones</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="maneja_cons_edit" class="col-form-label">Maneja control de consecutivo (S/N)?</label>
                <div id="maneja_cons_edit_content">
                  <select class="form-control" id="maneja_cons_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="imprime_comp_edit" class="col-form-label">Imprimir detalles de la empresa.(S/N)</label>
                <div id="imprime_comp_edit_content">
                  <select class="form-control" id="imprime_comp_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="comp_contable_edit" class="col-form-label">Tipo de Comprobante Movimiento, Consignacion.</label>
                <div id="comp_contable_edit_content">
                  <select class="form-control" id="comp_contable_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="C">Consignación</option>
                    <option value="M">Movimiento</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="afecta_caja_edit" class="col-form-label">Afecta efectivo en caja segun forma de pago? S/N</label>
                <div id="afecta_caja_edit_content">
                  <select class="form-control" id="afecta_caja_edit" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="forma_pago_edit" class="col-form-label">Forma de pago valida para este Comprogante(T-Todas)</label>
                <div id="forma_pago_edit_content">
                  <select class="form-control" id="forma_pago_edit" ></select>
                </div>
              </div>
           </div>
         </div>

      </div>

<?php
}

static function editModal(array $param){
    $data = $param[0];
    $data['forma_pago_text'] = ($data['forma_pago'] == 'T') ? 'Todas' : $data['forma_pago_text'];
    $data['destp_comprobante'] = trim($data['destp_comprobante']);
    $data['nattp_comprobante'] = trim($data['nattp_comprobante']);
    $data['codcompania'] = trim($data['codcompania']);
    $data['tipo_causacion'] = trim($data['tipo_causacion']);
    $data['modulo_uso'] = trim($data['modulo_uso']);
    $data['maneja_cons'] = trim($data['maneja_cons']);
    $data['imprime_comp'] = trim($data['imprime_comp']);
    $data['comp_contable'] = trim($data['comp_contable']);
    $data['afecta_caja'] = trim($data['afecta_caja']);
    $data['cuenta_pagar'] = trim($data['cuenta_pagar']);
    $data['forma_pago'] = trim($data['forma_pago']);
    $data['codtp_comprobante'] = trim($data['codtp_comprobante']);

    $id = trim($data['codtp_comprobante']);

    $html =[ <<<T
        
T, $data];

    return $html;
}


}
?>