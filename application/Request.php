<?php


# Classe que irá lidar com os parâmetros da URL (requisição)
class Request {

    private $controller;
    private $action;
    private $args;

    public function __construct() {

        # Recupera a URL
        $url = $_SERVER['REQUEST_URI'];

        # Se não existe url, encerra o processo
        if (!isset($url)) return false;

        # Separa os segmentos da URL (Ex de rota: "/fornecedor/inserir" - Controller:Fornecedor, Método:Inserir)
        $segments = explode("/", $url);
        array_shift($segments);

        # Atribui os nomes do arquivo(controller), da ação(método), e armazena os parâmetros(argumentos) em um array.
        $this->controller = ($controller = array_shift($segments)) ? $controller : "home";
        $this->action     = ($action = array_shift($segments)) ? $action : "index";
        $this->args 	  = (is_array($segments)) ? $segments : array();

        return true;
    }

    public function getController(){
        return $this->controller;
    }

    public function getAction(){
        return $this->action;
    }

    public function getArgs(){
        return $this->args;
    }
}