<?php

/**
 * 
 * Description of index_view
 *
 * @author abril
 */
class veredas_view extends VistaBase
{
    /**
     * Pintar la pantalla principal
     */
    function dibujar()
    {
        $listado = (isset($this->listado) ? $this->listado : null);
        ?>
        <div class="row">
            <div class="col-xs-12">
                <section class="content-header">
                    <h1 style="color:#000;">Veredas
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php"><i class="fa fa-dashboard active"></i>Inicio</a></li>
                    </ol>
                </section>
                <section class="content">
                    <form id="form_deptos" method="post" enctype="multipart/form-data">
                        <div class="box box-primary">
                            <div class="box-header with-border">

                                <div class="col-lg-3 col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><b>Departamento</b></span>
                                        <div id="depto_content">
                                            <select class="form-control" name="depto" id="depto" onchange="autocomplete_vermuncipios()">

                                            </select>
                                        </div>
                                    </div><br>
                                </div>

                                <div class="col-lg-3 col-md-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><b>Municipio</b></span>
                                        <div id="municipio_content">
                                            <select class="form-control" name="municipio" id="municipio">

                                            </select>
                                        </div>
                                    </div><br>
                                </div>

                                <div class="col-lg-1 col-md-6 col-xs-12">
                                    <a class="btn btn-sm btn-primary pull-right" id="buscar">
                                        <b><span class="fa fa-search"></span> Buscar</b>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <?php echo $this->verListado($listado); ?>
                        </div>
                        <div class="box box-footer">
                        </div>
            </div>
            </form>
            </section>
        </div>


        <script>
            $(function() {
                //$("#depto").val('<?php echo $this->depto; ?>');
                //jquery datatables
                //validarFormularioCargar();
            });
        </script>
    <?php
        }
        /**
         * Pintar la tabla donde se encuentra el listado de autorizaciones
         */
        function verListado($datos)
        {

            $html = '<div class="table-responsive">
                    <table id="veredasTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Codigo</th>
                                <th style="text-align:center;">Vereda/Corregimiento</th>
                                <th style="text-align:center;">Estado</th>
                                <th style="text-align:center;">
                                <a class="btn btn-xs btn-primary" onclick="crearVereda()" data-toggle="tooltip" title="Crear">
                                    <span class="fa fa-plus-circle"aria-hidden="true"></span> 
                                </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>';
            if ($datos != null) {
                foreach ($datos as $reg) {

                    $cod_dpto = trim($reg['cod_dpto']);
                    $cod_muni = trim($reg['cod_muni']);
                    $cod_vecor = trim($reg['cod_vecor']);
                    $nom_vecor = trim($reg['nom_vecor']);
                    $estado = trim($reg['estado']);
                    $impuesto = trim($reg['impuesto']);
                    $depato =  trim($reg['depato']);
                    $nombre  =  trim($reg['nombre']);

                    if ($estado == "A") {
                        $campo_estado = "Activa";
                    } else {
                        $campo_estado = "Inactiva";
                    }

                    $array = array();
                    $array['cod_dpto'] = $cod_dpto;
                    $array['depato'] = $depato;
                    $array['cod_muni'] = $cod_muni;
                    $array['nombre'] = $nombre;
                    $array['cod_vecor'] = $cod_vecor;
                    $array['nom_vecor'] = $nom_vecor;
                    $array['estado'] = $estado;
                    $array = json_encode($array);


                    $html .= "<tr>";
                    $html .= "<td>$cod_vecor</td>";
                    $html .= "<td>$nom_vecor</td>";
                    $html .= "<td style='text-align:center;'>$campo_estado</td>";
                    $html .= "<td style='text-align:center;'>
                                <a class='btn btn-xs btn-primary' onclick='modificarVereda($array)' data-toggle='tooltip' title='Modificar'>
                                    <i class='fas fa-pencil-alt'></i> 
                                </a></td>";
                    $html .= "</tr>";
                }
            }


            $html .= '       </tbody>
                    </table>
                </div><!-- /.box-body -->';

            return $html;
        }

        function crear_veredas()
        {
            $estados = (isset($this->estados) ? $this->estados : null);
            ?>
        <form id="form_crea_veredas">
            <div class="row">
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-6">
                    <label class="control-label">Codigo</label>
                    <input class="form-control" type="text" name="cod_vecor" id="cod_vecor" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:left; cursor:pointer;" maxlength="4"><br>
                </div>
                <div class="col-xs-6">
                    <label class="control-label">Nombre</label>
                    <input class="form-control" type="text" name="nom_vecor" id="nom_vecor" style="text-align:left; cursor:pointer;"><br>
                </div>

                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Departamento</b></span>
                        <div id="depto_crea_content">
                            <select class="form-control" name="depto_crea" id="depto_crea" onchange="autocomplete_vermun()">

                            </select>
                        </div>
                    </div><br>
                </div>

                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Municipio</b></span>
                        <div id="mun_crea_content">
                            <select class="form-control" name="mun_crea" id="mun_crea">

                            </select>
                        </div>
                    </div><br>
                </div>

                <div class="col-xs-6">
                    <?php if ($estados != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Estado</b></span>
                            <select class="form-control" name="estado" id="estado">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                            foreach ($estados as $reg) {
                                                ?>
                                    <option value="<?php echo trim($reg['codigo']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>

            </div>

        </form>
        <script type="text/javascript">
            $('document').ready(function() {

            });
        </script>
<?php
    }

    function modificar_veredas()
        {
            $estados = (isset($this->estados) ? $this->estados : null);
            ?>
        <form id="form_mod_veredas">
            <div class="row">
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-6">
                    <label class="control-label">Codigo</label>
                    <input type="hidden" name="cod_dpto" id="cod_dpto" value="<?php echo $this->cod_dpto; ?>" />
                    <input type="hidden" name="cod_muni" id="cod_muni" value="<?php echo $this->cod_muni; ?>" />
                    <input class="form-control" type="text" name="cod_vecor" id="cod_vecor" value="<?php echo $this->cod_vecor; ?>" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:left; cursor:pointer;" readonly><br>
                </div>
                <div class="col-xs-6">
                    <label class="control-label">Nombre</label>
                    <input class="form-control" type="text" name="nom_vecor" id="nom_vecor" value="<?php echo $this->nom_vecor; ?>"  style="text-align:left; cursor:pointer;"><br>
                </div>

                <div class="col-xs-6">
                    <label class="control-label">Departamento</label>
                    <input class="form-control" type="text" name="depto_crea" id="depto_crea" value="<?php echo $this->depto; ?>"  style="text-align:left; cursor:pointer;" readonly><br>
                </div>

                <div class="col-xs-6">
                    <label class="control-label">Municipio</label>
                    <input class="form-control" type="text" name="mun_crea" id="mun_crea"  value="<?php echo $this->muni; ?>" style="text-align:left; cursor:pointer;" readonly><br>
                </div>

                <div class="col-xs-6">
                    <?php if ($estados != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Estado</b></span>
                            <select class="form-control" name="estado" id="estado">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                        foreach ($estados as $reg) {
                                        ?>
                                        <?PHP if ($reg['codigo'] == $this->estado) {
                                        ?>
                                            <option selected value="<?php echo trim($reg['codigo']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                        </option>
                                    <?PHP } ?>
                                    <?PHP if ($reg['codigo'] != $this->estado) {
                                                        ?>

                                    <option value="<?php echo trim($reg['codigo']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                    </option>
                                <?php } ?>
                                <?PHP } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>

            </div>

        </form>
        <script type="text/javascript">
            $('document').ready(function() {

            });
        </script>
<?php
    }
}
?>