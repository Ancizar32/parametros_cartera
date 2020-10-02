<?php

/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Abril
 */
class index_controller extends ControladorBase
{

    /**
     * se llama al constructor del padre
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Permite cargar la informacion necesaria para la vista principal
     */
    function index()
    {
        $vista = cargarView("index");
        //$vista->asignarVariable($datos_vista);
        $vista->cargarTemplate("head");
        $vista->cargarTemplate("menu");
        $vista->dibujar();
        $vista->cargarTemplate("foot");
    }


    
}
