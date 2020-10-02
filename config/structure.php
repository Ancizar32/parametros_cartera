<?php

namespace IBG {

class APIException extends \Exception {

    private $details=[];
    
    public function __construct($message, $args, $code=0, Exception $previous = null) {

            
        $this->details = [];
        
        parent::__construct(vsprintf($message, $args), $code, $previous);

    }
    
    public function addDetails($item){
        $this->details[] = $item;
    }
    
    public function countDetails(){
        return count($this->details);
    }
    
    public function getDetails(){
        return $this->details;
    }
    
    public function addAllDetails(array $details){
        foreach($details as $detail){
            $this->details[] = $detail;
        }   
    }
    
}


set_exception_handler(function(\Throwable $exception){

    global $_output_filepath, $_is_service;

    $log = [
        "id" => uniqid('', true),
        "Timestamp" => (string) date("Y-m-d H:i:s"),
        "Message" => $exception->getMessage (),
        "Previous" => $exception->getPrevious (),
        "Code" => (string) $exception->getCode (),
        "File" => $exception->getFile (),
        "Line" => (string)$exception->getLine (),
        "TraceAsString" => $exception->getTraceAsString (),
        "Class" => get_class($exception),
    ];

    $log['details'] = [];

    if($_is_service == '1') {
        if ($exception instanceof myException) {
            foreach($exception->getDetails() as $value){
                $log['details'][]  = $value;
            }
        }
        $ret = ['success'=>false, 'title'=>'Error!', 'icon'=>'error', 'message'=>$exception->getMessage(), 'detail'=>$log];
        // echo $ret;
        echo json_encode($ret, JSON_PRETTY_PRINT);
        file_put_contents($_output_filepath, json_encode($ret));
        exit(0);
    } else {
        // echo $ret;
        echo json_encode($ret, JSON_PRETTY_PRINT);
        file_put_contents($_output_filepath, json_encode($log));
        exit(1);
    }

});

function service_return(array $param){
    $success = isset($param['success']) ? $param['success'] : true;
    $title = isset($param['title']) ? $param['title'] : 'Genial!';
    $icon = isset($param['icon']) ? $param['icon'] : 'success';
    $message = isset($param['message']) ? $param['message'] : 'success';
    $data = isset($param['data']) ? $param['data'] : [];

    $ret = ['success'=>$success, 'title'=>$title, 'icon'=>$icon, 'message'=>$message, 'detail'=>$data];

    $raw_bytes = json_encode($ret,JSON_PRETTY_PRINT);
    
    echo $raw_bytes;

    exit(0);
}

function rlog(...$data){
    $user = 'ANCIZAR_LOPEZ';
    $_app_name = 'parametros_cartera';
    foreach($data as $item){
        file_put_contents('debug.log',"\n".date('c').':'.$user.':['.$_app_name."]:".var_export($item,true),FILE_APPEND);
    }
}




global $_output_filepath;
global $_is_service;

$_output_filepath = "log.json";
$_is_service = "1";
   

}


?>
