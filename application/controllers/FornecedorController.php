<?php

include_once BASE_PATH . '/models/FornecedorModel.php';


class FornecedorController extends Controller
{
    public $model;
    public $fornecedores;

    public function __construct()
    {
        $this->model = new FornecedorModel();
        $this->fornecedores = $this->model->getAll();
    }

    public function index()
    {
        if($this->authenticate()){

            $this->setLayout('admin-layout');
            $this->setView('fornecedor/read');
            $this->loadPage();
        }

        header('Location: /autenticacao/index');
    }

    public function getFornecedor()
    {
        $data = $this->model->getOne($_POST['id_fornecedor']);
        die(json_encode($data));
    }

    public function getFornecedores(){
        $data = $this->model->getAll();
        die(json_encode($data));
    }

    public function create()
    {
        $this->model->insert($_POST);
        header('Location: /fornecedor');
    }

    public function update()
    {
        $this->model->update($_POST);
        header('Location: /fornecedor');
    }

    public function delete()
    {
        $id = explode(",", $_POST['id_fornecedor']);
        $this->model->delete($id);
    }
}
