<?php

include_once BASE_PATH . '/configs/Connection.php';


class Model
{
    public $connection;

    public function __construct(){
        # Recupera a conexão que foi criada em configs/Connection.
        $this->connection = Connection::getInstance();
    }

    public function formatFieldFloat($param){

        $pos = strpos($param, ',');

        if($pos){
            $valor = str_replace(',', '', $param);
            $pos = strpos($param, '.');
            return substr($valor,0,$pos-1);
        }

        return $param;
    }
}