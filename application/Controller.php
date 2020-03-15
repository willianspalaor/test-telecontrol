<?php

class Controller {

    private $view;

    # Define o arquivo que irá conter a view do controller
    public function setView($view){
        $this->view = BASE_PATH . 'views/' . $view . '.phtml';
    }

    /* Carrega a página - Todas as views ficarão inclusas dentro do layout, assim, o layout
    se mantém estático, enquanto o seu conteúdo é dinâmico */
    public function loadPage(){
        include_once(BASE_PATH . 'views/layout.phtml');
    }
}