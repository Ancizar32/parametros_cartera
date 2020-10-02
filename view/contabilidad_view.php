<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class contabilidad_view extends VistaBase
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
    <h5 class="card-header"><span class="text-center">Conceptos de notas de contabilidad</span></h5>
        <div class="card-body">
            <div class="form-row" id="main_buttons">
                <div class="form-group col-sm-6">
                    <form class="form-inline">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="create_button">Nueva nota</a>
                            <a class="dropdown-item" href="#" id="edit_button">Editar nota</a>
                            <a class="dropdown-item" href="#" id="disable_button">Inhabilitar nota</a>
                            <a class="dropdown-item" href="#" id="enable_button">Habilitar nota</a>
                            <a class="dropdown-item" href="#" id="select_all">Seleccionar Todo</a>
                            <a class="dropdown-item" href="#" id="deselect_all">Deseleccionar Todo</a>
                          </div>
                        </div>
                        <button id="advance_search_button" type="button" class="btn btn-primary mr-4 my-2">Busqueda avanzada</button>
                    </form>
                </div>
            </div>
            <table id="table_accounting" class="table table-bordered dt-responsive" style="width:100%"></table>
        </div>

        <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div id="advanceSearch_modal_contabilidad">
                 <div class="form-row">
                    <div class="form-group col-sm-12">
                      <label for="codigo" class="col-form-label">Código nota contable</label>
                      <input type="number" class="form-control" id="codigo_advance" >
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="descrip" class="col-form-label">Descripción nota contable</label>
                      <input type="text" class="form-control" id="descrip_advance" >
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="tipo" class="col-form-label">Tipo comprobante</label>
                      <input type="text" class="form-control" id="tipo_advance" >
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="codctble" class="col-form-label">Tipo documento contable</label>
                      <input type="text" class="form-control" id="codctble_advance" >
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="ctactble" class="col-form-label">Cuenta contable debito</label>
                      <input type="text" class="form-control" id="ctactble_advance" >
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="ctactble2" class="col-form-label">Cuenta contable credito</label>
                      <input type="text" class="form-control" id="ctactble2_advance" >
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-form-label" for="estado_advance">Estado</label>
                          <select class="custom-select mr-sm-2" id="estado_advance">
                            <option value="" selected>Seleccione...</option>
                            <option value="A">Activo</option>
                            <option value="I">Inactivo</option>
                            <option value="E">Egreso</option>
                          </select>
                    </div>
                 </div>
            </div>

            <div id="newRecord_modal_contabilidad">
               <div class="form-row">
                  <div class="form-group col-sm-12">
                    <label for="codigo" class="col-form-label">Código nota contable</label>
                    <input type="number" class="form-control" id="codigo" maxlength="3" >
                  </div>
                  <div class="form-group col-sm-12">
                    <label for="descrip" class="col-form-label">Descripción nota contable</label>
                    <input type="text" class="form-control" id="descrip" >
                  </div>
                  <div class="form-group col-sm-12">
                    <label for="tipo" class="col-form-label">Tipo comprobante contable</label>
                    <div id="miSelect">
                      <select class="form-control" id="tipo"></select>
                    </div>              
                  </div>
                  <div class="form-group col-sm-12">
                    <label for="codctble" class="col-form-label">Tipo documento contable</label>
                    <select class="form-control" id="codctble"></select>
                  </div>
                  <div class="form-group col-sm-12">
                    <label for="ctactble" class="col-form-label">Cuenta contable debito</label>
                    <select class="form-control" id="ctactble" ></select>
                  </div>
                  <div class="form-group col-sm-12">
                    <label for="ctactble2" class="col-form-label">Cuenta contable credito</label>
                    <select class="form-control" id="ctactble2" ></select>
                  </div>
                  <div class="form-group col-sm-12">
                      <input type="checkbox" id="estado">
                      <label for="estado">Egreso</label>
                  </div>
               </div>
          </div>

          <div class="form-row" id="editRecord_modal_contabilidad">
             <div class="form-row">

                <div class="form-group col-sm-12">
                  <label for="codigo_edit" class="col-form-label">Código nota contable</label>
                  <input type="text" class="form-control" id="codigo_edit" readonly>
                </div>

                <div class="form-group col-sm-12">
                  <label for="descrip_edit" class="col-form-label">Descripción nota contable</label>
                  <input type="text" class="form-control" id="descrip_edit" >
                </div>

                <div class="form-group col-sm-12">
                  <label for="tipo_edit" class="col-form-label">Tipo comprobante contable</label>
                    <div id="tipo_edit_content">
                        <select class="form-control" id="tipo_edit" ></select>
                    </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="codctble_edit" class="col-form-label">Tipo documento contable</label>
                  <div id="codctble_edit_">
                  <select class="form-control" id="codctble_edit" ></select>
                </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="ctactble_edit" class="col-form-label">Cuenta contable debito</label>
                  <div id="miSelect_ctactble_edit">
                    <select class="form-control" id="ctactble_edit" ></select>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                <label for="ctactble2_edit" class="col-form-label">Cuenta contable credito</label>
                <div id="miSelect_ctactble2_edit">
                    <select class="form-control" id="ctactble2_edit" ></select>
                </div>
                </div>

                <div class="form-group col-sm-12">
                    <input type="checkbox" id="estado_edit">
                    <label for="estado_edit">Egreso</label>
                </div>

             </div>
         </div>

        </div>

    </div>
</div>

<?php
}


static function editModal(array $param){
    $param['estado'] = ($param['estado'] == 'E') ? true : false;
    $estado = ($param['estado'] == 'E') ? "checked=\"true\"" : "";
    
    $html =[ '', $param];

    return $html;
}


}
?>