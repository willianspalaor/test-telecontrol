<?php

include_once BASE_PATH . '/models/UsuarioModel.php';

class AutenticacaoController extends Controller{

    public function index() {

        if($this->authenticate()){
            header('Location: /home/');
            exit;
        }

        $this->setLayout('login-layout');
        $this->setView('autenticacao/index');
        $this->loadPage();
    }

    public function login() {

        if(empty($_POST['email_usuario']) || empty($_POST['senha_usuario'])){
            header('Location: /autenticacao');
            exit;
        }

        $params = array(
            'email_usuario' => $_POST['email_usuario'],
            'senha_usuario'	 => $_POST['senha_usuario'],
        );

        $user = (new UsuarioModel)->getOne($params);

        if($user){
            Session::create($params);
            header('Location: /home');
            exit;
        }

        header('Location: /autenticacao');
    }

    public function logout(){
        Session::destroy();
        header('Location: /autenticacao');
        exit;
    }
}