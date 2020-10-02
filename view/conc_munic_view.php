<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class conc_munic_view extends VistaBase
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
    <h5 class="card-header"><span class="text-center">Conceptos por municipio</span></h5>
        <div class="card-body">
            <div class="form-row" id="main_buttons">
                <div class="form-group col-sm-6">
                    <form class="form-inline">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="create_button">Agregar concepto al municipio</a>
                            <a class="dropdown-item" href="#" id="edit_button">Editar concepto</a>
                            <a class="dropdown-item" href="#" id="disable_button">Eliminar concepto del municipio</a>
                            <a class="dropdown-item" href="#" id="select_all">Seleccionar Todo</a>
                            <a class="dropdown-item" href="#" id="deselect_all">Deseleccionar Todo</a>
                          </div>
                        </div>
                        <button id="advance_search_button" type="button" class="btn btn-primary mr-4 my-2">Busqueda avanzada</button>
                    </form>
                </div>

                <div class="form-group col-sm-6">
                  <label for="codmunicipio" class="col-form-label">Departamentos / Municipios</label>
                  <div id="codmunicipio_content">
                    <select class="form-control" id="codmunicipio" ></select>
                  </div>                    
                </div>

            <table id="table_conc_munic" class="table table-bordered dt-responsive" style="width:100%"></table>

        </div>
    </div>
</div>


        <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

          <div id="advanceSearch_modal_conc_munic">
            <div class="form-row">
                <div class="form-group col-sm-12">
                  <label for="codconcepto_advance" class="col-form-label">Concepto</label>
                    <input type="text" class="form-control" id="codconcepto_advance">
                </div>
                <div class="form-group col-sm-12">
                  <label for="base_liquidacion_advance" class="col-form-label">Base de liquidacion del concepto</label>
                  <input class="form-control" type="number" id="base_liquidacion_advance">
                </div>
                <div class="form-group col-sm-12">
                  <label for="porc_aplica_advance" class="col-form-label">Porcentaje a aplicar</label>
                  <input class="form-control" type="number" id="porc_aplica_advance">
                </div> 
                <div class="form-group col-sm-12">
                  <label for="aplica_ica_tercero_advance" class="col-form-label">Aplicar con base en IyC del tercero S/N?</label>
                  <div id="aplica_ica_tercero_advance_content">
                    <select class="form-control" id="aplica_ica_tercero_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
             </div>
          </div>

          <div id="newRecord_modal_conc_munic">
             <div class="form-row">
                <div class="form-group col-sm-12">
                  <label for="codconcepto" class="col-form-label">Concepto</label>
                  <div id="codconcepto_content">
                    <select class="form-control" id="codconcepto"></select>
                  </div>   
                </div>
                <div class="form-group col-sm-12">
                  <label for="base_liquidacion" class="col-form-label">Base de liquidacion del concepto</label>
                  <input class="form-control" type="number" id="base_liquidacion">
                </div>
                <div class="form-group col-sm-12">
                  <label for="porc_aplica" class="col-form-label">Porcentaje a aplicar</label>
                  <input class="form-control" type="number" id="porc_aplica">
                </div> 
                <div class="form-group col-sm-12">
                  <label for="aplica_ica_tercero" class="col-form-label">Aplicar con base en IyC del tercero S/N?</label>
                  <div id="aplica_ica_tercero_content">
                    <select class="form-control" id="aplica_ica_tercero" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
             </div>
          </div>

          <div class="form-row" id="editRecord_modal_conc_munic">
            <div class="form-row">
                <div class="form-group col-sm-12">
                  <label for="codconcepto_edit" class="col-form-label">Concepto</label>
                  <div id="codconcepto_edit_content">
                    <select class="form-control" id="codconcepto_edit"></select>
                  </div>   
                </div>
                <div class="form-group col-sm-12">
                  <label for="base_liquidacion_edit" class="col-form-label">Base de liquidacion del concepto</label>
                  <input class="form-control" type="number" id="base_liquidacion_edit">
                </div>
                <div class="form-group col-sm-12">
                  <label for="porc_aplica_edit" class="col-form-label">Porcentaje a aplicar</label>
                  <input class="form-control" type="number" id="porc_aplica_edit">
                </div> 
                <div class="form-group col-sm-12">
                  <label for="aplica_ica_tercero_edit" class="col-form-label">Aplicar con base en IyC del tercero S/N?</label>
                  <div id="aplica_ica_tercero_edit_content">
                    <select class="form-control" id="aplica_ica_tercero_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
             </div>
          </div>

        </div>

<?
}


}
?>