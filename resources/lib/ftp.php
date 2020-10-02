<?php

function ConectarFTP() {
//Permite conectarse al Servidor FTP
 $id_ftp = ftp_connect(IP_FTP, PORT_FTP); //Obtiene un manejador del Servidor FTP
 @ftp_login($id_ftp, FTP_USER_NAME, FTP_USER_PASS); //Se loguea al Servidor FTP
 return $id_ftp; //Devuelve el manejador a la función
}

/*
 * Descargar los documentos del servidor 145 para mostrarlos en la aplicacion 
 */
function descargar_documentos($array_documentos,$cedula,$cod_proceso) {

    //$excluidos = array();

    if ($array_documentos != '' && count((array) $array_documentos) > 0) {       
      
        $ftp_user_name = "archivo";
        $ftp_user_pass = "digital";
        $conn_id = ftp_connect(IP_FTP);
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
          

        $destino = "image_request/".$cedula."_".$cod_proceso."/";
        if (!file_exists($destino)) {
             mkdir($destino);

        }

        foreach ($array_documentos as $key1 => $value1) {
            $documentos = $value1->documentos;
            if ($documentos != '' && count((array) $documentos) > 0) {
                foreach ($documentos as $key2 => $value2) {

                    $nombre = $value2->nombre;
                    $ruta1 = $value2->ruta; 
                    $codigo = $value2->tipoproceso;

                    $ruta1T = explode("/", $ruta1);
                    unset($ruta1T[0]);
                    $ruta1 = implode("/", $ruta1T);

                    $ruta1T = explode(".", $nombre);
                    $extension = $ruta1T[1];
                    
                    $nombre=$codigo."_";                    
                    $nombre .= substr($ruta1, -16, -8);
                    $nombre .= ".$extension";
                                                    
                    $local_file = $destino.$nombre;
                    $server_file = RUTA_FTP_SERVER1 . "archivo/" . $ruta1;
                    //$server_file2 = RUTA_FTP_SERVER1 . "archivo2/" . $ruta1;

                   if (!@ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
                       // if (!@ftp_get($conn_id, $local_file, $server_file2, FTP_BINARY)) {
                            //echo "$ruta1!!!\n";
                            //printConsole("$ruta1 No encontrado!!!");
                           // array_push($excluidos, $nombre);
                       // }
                    }
                    if (!is_readable($destino . $nombre)) {
                       // echo "no es legible $nombre cedula \n";
                        
                    }                  
                }
            }
        }
    }
    else {
        $destino = "image_request/1094948057_1/";
        if (!file_exists($destino)) {
             mkdir($destino);

        }
    }
}

?>
