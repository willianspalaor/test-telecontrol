<?php

# Classe que irá lidar com as requisições e irá incluir o arquivo referente a requisição e chamar o método passado

class Router {

    public static function run(Request $request) {


        # Recupera os parâmetros da requisição que foram inseridos na classe Request
        $controller = $request->getController();
        $action = $request->getAction();
        $args = (array) $request->getArgs();

        # Monta o nome e o caminho do arquivo que terá o controller da requisição ( Os nomes estão no padrão Camel case)
        $controller = ucfirst($controller) . 'Controller';
        $src = BASE_PATH . 'controllers/' . $controller . '.php';

        # Se o arquivo existir
        if(file_exists($src)){

            # Inclui o arquivo da requisição e cria o objeto
            include_once $src;
            $controller = new $controller();

            # Verifica se é possível chamar o controller e o método
            if(is_callable(array($controller, $action))) {

                # Execute o método da classe que foi passada (Ex: Executa o método inserir da classe Fornecedor))
                call_user_func(array($controller, $action), $args);
            }
        }
    }
}