<?php

include_once BASE_PATH . '/models/ProdutoModel.php';


class ProdutoController extends Controller
{
    public $model;
    public $produtos;

    public function __construct()
    {
        $this->model = new produtoModel();
        $this->produtos = $this->model->getAll();
    }

    public function index()
    {
        if($this->authenticate()){

            $this->setLayout('admin-layout');
            $this->setView('produto/read');
            $this->loadPage();
        }

        header('Location: /autenticacao/index');
    }

    public function getProduto()
    {
        if(empty($_POST)){
            die('Nenhum parâmetro passado');
        }

        if(!empty($_POST['id_produto'])){
            $data = $this->model->getOne('id_produto', $_POST['id_produto']);
        }else{
            $data = $this->model->getOne('referencia_produto', $_POST['referencia_produto']);
        }

        die(json_encode($data));
    }

    public function getProdutos(){
        $data = $this->model->getAll();
        die(json_encode($data));
    }

    public function create()
    {
        $this->model->insert($_POST);
        header('Location: /produto');
    }

    public function update()
    {
        $this->model->update($_POST);
        header('Location: /produto');
    }

    public function delete()
    {
        $id = explode(",", $_POST['id_produto']);
        $this->model->delete($id);
    }

    public function import(){

        var_dump($_FILES);
        var_dump($_POST); die();
    }
}
