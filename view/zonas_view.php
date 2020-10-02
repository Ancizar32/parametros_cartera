<?php

/**
 * 
 * Description of index_view
 *
 * @author abril
 */
class zonas_view extends VistaBase
{


    /**
     * Pintar la pantalla principal
     */
    function dibujar()
    {
        $zonas = (isset($this->zonas) ? $this->zonas : null);
        $sucursales = (isset($this->sucursales) ? $this->sucursales : null);
        $cobradores = (isset($this->cobradores) ? $this->cobradores : null);
        $listado = (isset($this->listado) ? $this->listado : null);
        ?>

        <div class="content-wrapper" id="index_zonas">
            <div class="row">
                <div class="col-xs-12">
                    <section class="content-header">
                        <h1 style="color:#000;">Zonas
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fa fa-dashboard active"></i>Inicio</a></li>
                        </ol>
                    </section>
                    <section class="content">
                        <form id="form_zonas" method="post" enctype="multipart/form-data">
                            <div class="box box-primary">
                                <div class="box-header with-border">

                                    <div class="row">
                                        <div class="col-xs-12" style="text-align:rigth">
                                            <h1 style="color:#000;" class="box-title"><strong>Filtrar Registros</strong></h1>
                                        </div>
                                        <div class="col-xs-12">&nbsp;</div>
                                        <br>

                                        <div class="col-lg-2 col-md-6 col-xs-12">
                                            <?php if ($zonas != null) { ?>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><b>Zona</b></span>
                                                    <select class="form-control" name="zonab" id="zonab">
                                                        <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                                        <?php
                                                                    foreach ($zonas as $reg) {
                                                                        ?>
                                                            <option value="<?php echo trim($reg['codigopt']); ?>">
                                                                <?php echo trim($reg['codigopt']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div><br>
                                            <?php } ?>
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-xs-12">
                                            <?php if ($sucursales != null) { ?>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><b>Sucursal</b></span>
                                                    <select class="form-control" name="sucursalb" id="sucursalb">
                                                        <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                                        <?php
                                                                    foreach ($sucursales as $reg) {
                                                                        ?>
                                                            <option value="<?php echo trim($reg['codigo']); ?>">
                                                                <?php echo trim($reg['nombre']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div><br>
                                            <?php } ?>
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-xs-12">
                                            <?php if ($cobradores != null) { ?>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><b>Cobrador</b></span>
                                                    <select class="form-control" name="cobradorb" id="cobradorb">
                                                        <option value="" style="background-color: #E6E6E6"> - Todos -</option>
                                                        <?php
                                                                    foreach ($cobradores as $reg) {
                                                                        ?>
                                                            <option value="<?php echo trim($reg['cedula']); ?>">
                                                                <?php echo trim($reg['nombre']); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div><br>
                                            <?php } ?>
                                        </div>

                                        <div class="col-lg-1 col-md-6 col-xs-12">
                                            <a class="btn btn-sm btn-primary pull-right" onclick="realizarBusqueda();">
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
            </div>
        </div>
        <script>
            $(function() {
                $("#zonab").val('<?php echo $this->zonab; ?>');
                $("#sucursalb").val('<?php echo $this->sucursalb; ?>');
                $("#cobradorb").val('<?php echo $this->cobradorb; ?>');
                inicializar_datatable("example1");
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
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align:center;">Zona</th>
                                <th style="text-align:center;">Sucursal</th>
                                <th style="text-align:center;">Depto</th>
                                <th style="text-align:center;">Cobrador/Vda</th>
                                <th style="text-align:center;">L.Cont.M</th>
					            <th style="text-align:center;">L.Cont.E</th>
                                <th style="text-align:center;">L.Cred.M</th>
                                <th style="text-align:center;">L.Cred.E</th>
                                <th style="text-align:center;">
                                <a class="btn btn-xs btn-primary" onclick="crear()" data-toggle="tooltip" title="Crear">
                                    <span class="fa fa-plus-circle"aria-hidden="true"></span> 
                                </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>';
            if ($datos != null) {
                foreach ($datos as $reg) {

                    $codzona = trim($reg['codzona']);
                    $sucursal = trim($reg['sucursal']);
                    $desczona = trim($reg['desczona']);
                    $depto = trim($reg['depto']);
                    $cobrador = trim($reg['cobrador']);
                    $listamad_cont = trim($reg['listamad_cont']);
                    $listaele_cont = trim($reg['listaele_cont']);
                    $listamad = trim($reg['listamad']);
                    $listaele = trim($reg['listaele']);
                    $coddepto = trim($reg['coddepto']);
                    $codsucur = trim($reg['codsucur']);

                    $array = array();
                    $array['codzona'] = $codzona;
                    $array['codsucur'] = $codsucur;
                    $array['coddepto'] = $coddepto;
                    $array['desczona'] = $desczona;
                    $array = json_encode($array);
                    //echo'vec'.$cod_vecor;

                    $html .= "<tr>";
                    $html .= "<td>$desczona</td>";
                    $html .= "<td><b>$sucursal</b></td>";
                    $html .= "<td>$depto</td>";
                    $html .= "<td>$cobrador</td>";
                    $html .= "<td style='text-align:center;'>$listamad_cont</td>";
                    $html .= "<td style='text-align:center;'>$listaele_cont</td>";
                    $html .= "<td style='text-align:center;'>$listamad</td>";
                    $html .= "<td style='text-align:center;'>$listaele</td>";

                    $html .= "<td style='text-align:center;'>
                                <a class='btn btn-xs btn-primary' onclick='modificar($array)' data-toggle='tooltip' title='Modificar'>
                                    <i class='fas fa-pencil-alt'></i> 
                                </a>
                            </td>";
                    $html .= "</tr>";
                }
            }


            $html .= '       </tbody>
                    </table>
                </div><!-- /.box-body -->';

            return $html;
        }

        function crear()
        {
            $sucursales = (isset($this->sucursales) ? $this->sucursales : null);
            $cobradores = (isset($this->cobradores) ? $this->cobradores : null);
            $departamentos = (isset($this->departamentos) ? $this->departamentos : null);
            ?>
        <form id="form_crea">
            <div class="row">
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-6">
                    <input type="hidden" name="metodo" id="metodo" value="ingresar" />
                    <input type="hidden" name="controlador" id="controlador" value="zonas" />
                    <label class="control-label">Codigo</label>
                    <input class="form-control" type="text" name="codzona" id="codzona" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:left; cursor:pointer;"><br>
                </div>
                <div class="col-xs-6">
                    <label class="control-label">Descripción</label>
                    <input class="form-control" type="text" name="desczona" id="desczona" style="text-align:left; cursor:pointer;"><br>
                </div>
                <div class="col-xs-6">
                    <?php if ($sucursales != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Sucursal</b></span>
                            <select class="form-control" name="sucursal" id="sucursal">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                            foreach ($sucursales as $reg) {
                                                ?>
                                    <option value="<?php echo trim($reg['codigo']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>
                <div class="col-xs-6">
                    <?php if ($cobradores != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Cobrador</b></span>
                            <select class="form-control" name="cobrador" id="cobrador">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                            foreach ($cobradores as $reg) {
                                                ?>
                                    <option value="<?php echo trim($reg['cedula']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>

                <div class="col-xs-12">
                    <h2 style="text-align: center;">Adicionar <b>Munucipios</b></h2>
                </div>

                <div class="col-xs-6">
                    <?php if ($departamentos != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Departamento</b></span>
                            <select class="form-control" name="departamento" id="departamento" onchange="getMunicipios()">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                            foreach ($departamentos as $reg) {
                                                ?>
                                    <option value="<?php echo trim($reg['codigo']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>

                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Municipio</b></span>
                        <select class="form-control" name="municipio" id="municipio" onchange="getVeredas()">

                        </select>
                    </div><br>
                </div>

                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Vereda</b></span>
                        <select class="form-control" name="vereda" id="vereda">

                        </select>
                    </div><br>
                </div>

                <div class="col-sm-4">
                        <button type="button" class="add-new form-control"><i class="fa fa-plus"></i> Adicionar</button>
                </div>

                <div class="col-xs-12">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered inputTable">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Depto</th>
                                    <th>Código</th>
                                    <th>Municipio</th>
                                    <th>Código</th>
                                    <th>Vereda</th>
                                    <th>
                                        Acciones
                                        <!--<button type="button" class="add-new form-control"><i class="material-icons">&#xE03B;</i></button>-->
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </form>
        <script type="text/javascript">
            $('document').ready(function() {

            });
        </script>
    <?php
        }

        function modificar()
        {
            $sucursales = (isset($this->sucursales) ? $this->sucursales : null);
            $cobradores = (isset($this->cobradores) ? $this->cobradores : null);
            $departamentos = (isset($this->departamentos) ? $this->departamentos : null);
            $ubicaciones = (isset($this->ubicaciones) ? $this->ubicaciones : null);
            ?>
        <form id="form_modi">
            <div class="row">
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-6">
                    <input type="hidden" name="metodo" id="metodo" value="ingresar" />
                    <input type="hidden" name="controlador" id="controlador" value="zonas" />
                    <label class="control-label">Codigo</label>
                    <input class="form-control" type="text" name="codzona" id="codzona" value="<?php echo $this->codigo; ?>" style="text-align:left; cursor:pointer;" readonly><br>
                </div>
                <div class="col-xs-6">
                    <label class="control-label">Descripción</label>
                    <input class="form-control" type="text" name="desczona" id="desczona" value="<?php echo $this->descripcion; ?>" style="text-align:left; cursor:pointer;"><br>
                </div>
                <div class="col-xs-6">
                    <?php if ($sucursales != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Sucursal</b></span>
                            <select class="form-control" name="sucursal" id="sucursal">
                                <?php
                                            foreach ($sucursales as $reg) {
                                                ?>
                                    <?PHP if ($reg['codigo'] == $this->sucursal) {
                                                        ?>
                                        <option selected value="<?php echo trim($reg['codigo']); ?>">
                                            <?php echo trim($reg['nombre']); ?>
                                        </option>
                                    <?PHP } ?>
                                    <?PHP if ($reg['codigo'] != $this->sucursal) {
                                                        ?>
                                        <option value="<?php echo trim($reg['codigo']); ?>">
                                            <?php echo trim($reg['nombre']); ?>
                                        </option>
                                    <?PHP } ?>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>
                <div class="col-xs-6">
                    <?php if ($cobradores != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Cobrador</b></span>
                            <select class="form-control" name="cobrador" id="cobrador">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                            foreach ($cobradores as $reg) {
                                                ?>
                                    <?PHP if ($reg['cedula'] == $this->cobrador) {
                                                        ?>
                                        <option selected value="<?php echo trim($reg['cedula']); ?>">
                                            <?php echo trim($reg['nombre']); ?>
                                        </option>
                                    <?PHP } ?>
                                    <?PHP if ($reg['cedula'] != $this->cobrador) {
                                                        ?>
                                        <option value="<?php echo trim($reg['cedula']); ?>">
                                            <?php echo trim($reg['nombre']); ?>
                                        </option>
                                    <?PHP } ?>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>


                
                <div class="col-xs-12">
                    <h2 style="text-align: center;">Adicionar <b>Munucipios</b></h2>
                </div>
                

                <div class="col-xs-6">
                    <?php if ($departamentos != null) { ?>
                        <div class="input-group">
                            <span class="input-group-addon"><b>Departamento</b></span>
                            <select class="form-control" name="departamento" id="departamento" onchange="getMunicipios()">
                                <option value="" style="background-color: #E6E6E6"> - Todas -</option>
                                <?php
                                            foreach ($departamentos as $reg) {
                                                ?>
                                    <option value="<?php echo trim($reg['codigo']); ?>">
                                        <?php echo trim($reg['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div><br>
                    <?php } ?>
                </div>

                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Municipio</b></span>
                        <select class="form-control" name="municipio" id="municipio" onchange="getVeredas()">

                        </select>
                    </div><br>
                </div>

                <div class="col-xs-6">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Vereda</b></span>
                        <select class="form-control" name="vereda" id="vereda">

                        </select>
                    </div><br>
                </div>

                <div class="col-sm-4">
                        <button type="button" class="add-new form-control"><i class="fa fa-plus"></i> Adicionar</button>
                </div>

                <div class="col-xs-12">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered inputTable">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Depto</th>
                                    <th>Código</th>
                                    <th>Municipio</th>
                                    <th>Código</th>
                                    <th>Vereda</th>
                                    <th>
                                        Acciones
                                        <!--<button type="button" class="add-new form-control"><i class="material-icons">&#xE03B;</i></button>-->
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $this->verUbicaciones($ubicaciones); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </form>
        <script type="text/javascript">
            $('document').ready(function() {

            });
        </script>
<?php
    }

    function verUbicaciones($ubicaciones)
    {
        if ($ubicaciones != null) {
            $html = "";
            foreach ($ubicaciones as $reg) {

                $codigo = trim($reg['codigo']);
                $coddepto = trim($reg['coddepto']);
                $nomdepto = trim($reg['nomdepto']);
                $codmuni = trim($reg['codmuni']);
                $nombmuni = trim($reg['nombmuni']);
                $codver = trim($reg['codver']);
                $nomver = trim($reg['nomver']);

                $array = array();
                $array['codigo'] = $codigo;
                $array['codver'] = $codver;
                $array['coddepto'] = $coddepto;
                $array['codmuni'] = $codmuni;
                $array = json_encode($array);

                $html .= "<tr>";
                $html .= "<td>$coddepto</td>";
                $html .= "<td>$nomdepto</td>";
                $html .= "<td>$codmuni</td>";
                $html .= "<td>$nombmuni</td>";
                $html .= "<td>$codver</td>";
                $html .= "<td>$nomver</td>";
                $html .= "<td style='text-align:center;'>
                                    <a class='delete' onclick='seleccionar($array)' data-toggle='tooltip' data-placement='bottom' title='Eliminar'>
                                    <i class='far fa-trash-alt'></i> 
                                    </a>
                                </td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
?>