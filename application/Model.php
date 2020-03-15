<?php

include_once BASE_PATH . '/configs/Connection.php';


class Model
{
    public $connection;

    public function __construct(){
        # Recupera a conexão que foi criada em configs/Connection.
        $this->connection = Connection::getInstance();
    }
}