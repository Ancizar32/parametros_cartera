<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class num_document_view extends VistaBase
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
    <h5 class="card-header"><span class="text-center">Numeraci&oacute;n de comprobante</span></h5>
        <div class="card-body">
            <div class="form-row" id="main_buttons">
                <div class="form-group col-sm-6">
                    <form class="form-inline">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="create_button"> Nueva numeraci&oacute;n de comprobante</a>
                            <a class="dropdown-item" href="#" id="edit_button">Editar numeraci&oacute;n de  comprobante</a>
                            <a class="dropdown-item" href="#" id="disable_button">Inhabilitar numeraci&oacute;n de  comprobante</a>
                            <a class="dropdown-item" href="#" id="enable_button">Habilitar numeraci&oacute;n de  comprobante</a>
                            <a class="dropdown-item" href="#" id="select_all">Seleccionar Todo</a>
                            <a class="dropdown-item" href="#" id="deselect_all">Deseleccionar Todo</a>
                          </div>
                        </div>
                        <button id="advance_search_button" type="button" class="btn btn-primary mr-4 my-2">Busqueda avanzada</button>
                    </form>
                </div>
            </div>

            <table id="table_num_doc" class="table table-bordered dt-responsive" style="width:100%"></table>

        </div>
    </div>
</div>


        <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div id="advanceSearch_modal_num_doc">
             <div class="form-row">
                <div class="form-group col-sm-4">
                  <label for="consecutivo_advance" class="col-form-label">Consecutivo</label>
                  <input type="text" class="form-control" id="consecutivo_advance" >
                </div>
                <div class="form-group col-sm-4">
                  <label class="col-form-label" for="estado_advance">Estado</label>
                    <select class="custom-select mr-sm-2" id="estado_advance">
                      <option value="" selected>Seleccione...</option>
                      <option value="A">Activo</option>
                      <option value="I">Inactivo</option>
                      <option value="D">Disponible</option>
                      <option value="U">Utilizado</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                </div>
                <div class="form-group col-sm-6">
                  <label for="codsucursal_advance" class="col-form-label">Sucursal</label>
                  <input type="text" class="form-control" id="codsucursal_advance" >
                </div>
                <div class="form-group col-sm-6">
                  <label for="codtp_comprobante_advance" class="col-form-label">Tipo de comprobante</label>
                  <input type="text" class="form-control" id="codtp_comprobante_advance" >
                </div>
                <div class="form-group col-sm-6">
                  <label for="codcaja_advance" class="col-form-label">Responsable caja</label>
                  <input type="text" class="form-control" id="codcaja_advance" >
                </div>
                <div class="form-group col-sm-6">
                  <label for="codusuario_advance" class="col-form-label">Responsable papeleria manual</label>
                  <input type="text" class="form-control" id="codusuario_advance" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="numero_inicial_advance" class="col-form-label">N&uacute;mero inicial</label>
                  <input type="number" class="form-control" id="numero_inicial_advance">
                </div>
                <div class="form-group col-sm-4">
                  <label for="numero_final_advance" class="col-form-label">N&uacute;mero final</label>
                  <input type="number" class="form-control" id="numero_final_advance">
                </div>
                <div class="form-group col-sm-4">
                  <label for="numero_actual_advance" class="col-form-label">N&uacute;mero actual</label>
                  <input type="number" class="form-control" id="numero_actual_advance">
                </div>
             </div>
          </div>

          <div id="newRecord_modal_num_doc">
           <div class="form-row">
              <div class="form-group col-sm-6">
                <label for="codsucursal" class="col-form-label">Sucursal</label>
                <div id="codsucursal_content">
                  <select class="form-control" id="codsucursal" ></select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codtp_comprobante" class="col-form-label">Tipo de comprobante</label>
                <div id="codtp_comprobante_content">
                  <select class="form-control" id="codtp_comprobante" ></select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codcaja" class="col-form-label">Responsable caja</label>
                <div id="codcaja_content">
                  <select class="form-control" id="codcaja" ></select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codusuario" class="col-form-label">Responsable papeleria manual</label>
                <div id="codusuario_content">
                  <select class="form-control" id="codusuario" ></select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="numero_inicial" class="col-form-label">N&uacute;mero inicial</label>
                <input type="number" class="form-control" id="numero_inicial">
              </div>
              <div class="form-group col-sm-4">
                <label for="numero_final" class="col-form-label">N&uacute;mero final</label>
                <input type="number" class="form-control" id="numero_final">
              </div>
              <div class="form-group col-sm-4">
                <label for="numero_actual" class="col-form-label">N&uacute;mero actual</label>
                <input type="number" class="form-control" id="numero_actual">
              </div>
           </div>
        </div>

          <div class="form-row" id="editRecord_modal_num_doc">
            <div class="form-row">
              <div class="form-group col-sm-4">
                <label for="consecutivo_edit" class="col-form-label">Consecutivo</label>
                <input type="number" class="form-control" id="consecutivo_edit" readonly></input>
              </div>
              <div class="form-group col-sm-8">
                
              </div>
              <div class="form-group col-sm-6">
                <label for="codsucursal_edit" class="col-form-label">Sucursal</label>
                <div id="codsucursal_edit_content">
                  <select class="form-control" id="codsucursal_edit" ></select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codtp_comprobante_edit" class="col-form-label">Tipo de comprobante</label>
                <div id="codtp_comprobante_edit_content">
                  <select class="form-control" id="codtp_comprobante_edit" ></select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codcaja_edit" class="col-form-label">Responsable caja</label>
                <div id="codcaja_edit_content">
                  <select class="form-control" id="codcaja_edit" ></select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="codusuario_edit" class="col-form-label">Responsable papeleria manual</label>
                <div id="codusuario_edit_content">
                  <select class="form-control" id="codusuario_edit" ></select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="numero_inicial_edit" class="col-form-label">N&uacute;mero inicial</label>
                <input type="number" class="form-control" id="numero_inicial_edit"></input>
              </div>
              <div class="form-group col-sm-4">
                <label for="numero_final_edit" class="col-form-label">N&uacute;mero final</label>
                <input type="number" class="form-control" id="numero_final_edit"></input>
              </div>
              <div class="form-group col-sm-4">
                <label for="numero_actual_edit" class="col-form-label">N&uacute;mero actual</label>
                <input type="number" class="form-control" id="numero_actual_edit"></input>
              </div>
           </div>         
          </div>
        </div>

<?php
}

static function createModal(){

?>
  
<?php
}

static function editModal(array $param){
    $data = $param[0];
    $data['consecutivo'] = trim($data['consecutivo']);
    $data['codsucursal'] = trim($data['codsucursal']);
    $data['codsucursal_text'] = trim($data['codsucursal_text']);
    $data['codtp_comprobante'] = trim($data['codtp_comprobante']);
    $data['codtp_comprobante_text'] = trim($data['codtp_comprobante_text']);
    $data['codcaja'] = trim($data['codcaja']);
    $data['codcaja_text'] = trim($data['codcaja_text']);
    $data['codusuario'] = trim($data['codusuario']);
    $data['codusuario_text'] = trim($data['codusuario_text']);
    $data['numero_inicial'] = trim($data['numero_inicial']);
    $data['numero_final'] = trim($data['numero_final']);
    $data['numero_actual'] = trim($data['numero_actual']);
    $data['estado'] = trim($data['estado']);
    $data['descto'] = trim($data['descto']);
    $data['usrcrea'] = trim($data['usrcrea']);
    $data['feccrea'] = trim($data['feccrea']);
    $data['horacre'] = trim($data['horacre']);
    $data['usrmodi'] = trim($data['usrmodi']);
    $data['fecmodi'] = trim($data['fecmodi']);
    $data['horamod'] = trim($data['horamod']);

    $html =[ '', $data];

    return $html;
}


}
?>