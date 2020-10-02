<?php

/**
 * 
 * Description of index_view
 *
 * @author abril
 */
class municipios_view extends VistaBase
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
                    <h1 style="color:#000;">Departamentos/Municipios
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
                                            <select class="form-control" name="depto" id="depto">

                                            </select>
                                        </div>
                                    </div><br>
                                </div>

                                <div class="col-lg-1 col-md-6 col-xs-12">
                                    <a class="btn btn-sm btn-primary pull-right" id="buscar">
                                        <b><span class="fa fa-search"></span> Buscar</b>
                                    </a>
                                </div>

                                <div class="col-lg-1 col-md-6 col-xs-12">
                                    <a class="btn btn-sm btn-primary pull-right" id="gestionar">
                                        <b><span class="fas fa-cog"></span>Gestionar</b>
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
                $("#depto").val('<?php echo $this->depto; ?>');
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
                                <th style="text-align:center;">Codigo</th>
                                <th style="text-align:center;width: 150px;">Municipio</th>
                                <th style="text-align:center;">L.Cont.M</th>
					            <th style="text-align:center;">L.Cont.E</th>
                                <th style="text-align:center;">L.Cred.M</th>
                                <th style="text-align:center;">L.Cred.E</th>
                                <th style="text-align:center;">Fletes</th>
                                <th style="text-align:center;">Estado</th>
                                <th style="text-align:center;">Exc.Imp</th>
                            </tr>
                        </thead>
                        <tbody>';
            if ($datos != null) {
                foreach ($datos as $reg) {

                    $codigo = trim($reg['codigo']);
                    $nombre = trim($reg['nombre']);
                    $coddepto = trim($reg['coddepto']);

                    $listamad_cont = trim($reg['listamad_cont']);
                    $listaele_cont = trim($reg['listaele_cont']);
                    $listamad = trim($reg['listamad']);
                    $listaele = trim($reg['listaele']);
                    $flete = trim($reg['flete']);
                    $estado = trim($reg['estado']);
                    if ($estado == "A") {
                        $campo_estado = "Activo";
                    } else {
                        $campo_estado = "Inactivo";
                    }
                    $impuesto = trim($reg['impuesto']);
                    if ($impuesto == "S") {
                        $campo_impuesto = "Si";
                    } else {
                        $campo_impuesto = "No";
                    }
                    $array = array();
                    $array['codigo'] = $codigo;
                    $array['coddepto'] = $coddepto;
                    $array = json_encode($array);


                    $html .= "<tr>";
                    $html .= "<td style='text-align:center;'>$codigo</td>";
                    $html .= "<td style='text-align:center;'>$nombre</td>";
                    $html .= "<td style='text-align:center;'>$listamad_cont</td>";
                    $html .= "<td style='text-align:center;'>$listaele_cont</td>";
                    $html .= "<td style='text-align:center;'>$listamad</td>";
                    $html .= "<td style='text-align:center;'>$listaele</td>";
                    $html .= "<td style='text-align:center;'>$flete</td>";
                    $html .= "<td style='text-align:center;'>$campo_estado</td>";
                    $html .= "<td style='text-align:center;'>$campo_impuesto</td>";
                }
            }


            $html .= '       </tbody>
                    </table>
                </div><!-- /.box-body -->';

            return $html;
        }

        function crear_municipios()
        {
            ?>
        <form id="form_crea_municipio">
            <div class="row">
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-6">
                    <input type="hidden" name="coddepto" id="coddepto"/>
                    <label class="control-label">Codigo</label>
                    <input class="form-control" type="text" name="coddep" id="coddep" onblur="inicializarMun(event,this)" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:left; cursor:pointer;"><br>
                </div>
                <div class="col-xs-6">
                    <label class="control-label">Nombre</label>
                    <input class="form-control" type="text" name="descdep" id="descdep" style="text-align:left; cursor:pointer;"><br>
                </div>

                <div class="col-sm-8">
                    <h3 style="margin-top: 0px;">Adicionar <b>Municipios</b></h3>
                </div>
                <div class="col-sm-4">
                    <button type="button" class="add-new form-control"><i class="fa fa-plus"></i> Adicionar</button>
                </div>


                <div class="col-xs-12" style="margin-top: 2rem;overflow: auto;">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table  class="table table-bordered inputTable" style="min-width: 150%;">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Municipio</th>
                                    <th>L.Cont.M</th>
                                    <th>L.Cont.E</th>
                                    <th>L.Cred.M</th>
                                    <th>L.Cred.E</th>
                                    <th>Fletes</th>
                                    <th>Estado</th>
                                    <th>Exc.Imp.</th>
                                    <th>
                                        Acciones

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

    function verMunicipios($municipios)
    {
        $html = "<thead>
        <tr>
            <th>Código</th>
            <th style='text-align:center; width: 150px;'>Municipio</th>
            <th>L.Cont.M</th>
            <th>L.Cont.E</th>
            <th>L.Cred.M</th>
            <th>L.Cred.E</th>
            <th>Fletes</th>
            <th>Estado</th>
            <th>Exc.Imp.</th>
            <th>
                Acciones

            </th>
        </tr>
        </thead>
        <tbody>";
        if ($municipios != null) {
            //$html = "";
            foreach ($municipios as $reg) {

                $codmuni = trim($reg['codigo']);
                $nombmuni = trim($reg['nombre']);
                //$coddepto = trim($reg['coddepto']);
                $listamad_cont = trim($reg['listamad_cont']);
                $listaele_cont = trim($reg['listaele_cont']);
                $listamad = trim($reg['listamad']);
                $listaele = trim($reg['listaele']);
                $flete = trim($reg['flete']);
                $estado = trim($reg['estado']);
                $impuesto = trim($reg['impuesto']);


                $html .= "<tr>";
                $html .= "<td style='text-align:center;'>$codmuni</td>";
                $html .= "<td style='text-align:center;'>$nombmuni</td>";
                $html .= "<td style='text-align:center;'>$listamad_cont</td>";
                $html .= "<td style='text-align:center;'>$listaele_cont</td>";
                $html .= "<td style='text-align:center;'>$listamad</td>";
                $html .= "<td style='text-align:center;'>$listaele</td>";
                $html .= "<td style='text-align:center;'>$flete</td>";
                $html .= "<td style='text-align:center;'>$estado</td>";
                $html .= "<td style='text-align:center;'>$impuesto</td>";
                $html .= "<td style='text-align:center;'>
                                    <a class='add' title='Agregar' data-toggle='tooltip' data-placement='bottom' disabled='disabled'><i class='fas fa-plus-circle'></i></a>
                                    <a class='edit' onclick='seleccionar($codmuni)' data-toggle='tooltip' data-placement='bottom' title='Editar'>
                                    <i class='fas fa-pencil-alt'></i> 
                                    </a>
                                </td>";
                $html .= "</tr>";
            }
            $html .= "       </tbody>";
        }
        return $html;
    }
}
?>