<?php

include_once BASE_PATH . '/Model.php';

class UsuarioModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function getOne($params)
    {

        try {

            $stmt = $this->connection->prepare('SELECT * FROM usuario WHERE email_usuario = :email_usuario AND senha_usuario = :senha_usuario');
            $stmt->bindValue(':email_usuario', $params['email_usuario']);
            $stmt->bindValue(':senha_usuario', $params['senha_usuario']);
            $stmt->execute();
            return $stmt->fetch();
        }
        catch(Exception $e) {
            print($e->getMessage());
        }

        return [];
    }

}