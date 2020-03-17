<?php

include_once BASE_PATH . 'Session.php';

class Controller {

    private $view;
    private $layout;

    # Define o arquivo que irá conter a view do controller
    public function setView($view){
        $this->view = BASE_PATH . 'views/' . $view . '.phtml';
    }

    # define o arquivo que irá conter o layout do controller
    public function setLayout($layout){
        $this->layout = BASE_PATH . '/views/' . $layout . '.phtml';
    }

    /* Carrega a página - Todas as views ficarão inclusas dentro do layout, assim, o layout
    se mantém estático, enquanto o seu conteúdo é dinâmico */
    public function loadPage(){
        include_once($this->layout);
    }

    public function authenticate(){

        $session = Session::start();

        if(!empty($session)){

              if(Session::authenticate(array('email_usuario', 'senha_usuario'))){
                return true;
            }
        }

      return false;
    }
}