<?php

include_once BASE_PATH . '/models/ClienteModel.php';


class ClienteController extends Controller
{
    public $model;
    public $clientes;

    public function __construct()
    {
        $this->model = new ClienteModel();
        $this->clientes = $this->model->getAll();
    }

    public function index()
    {
        if($this->authenticate()){

            $this->setLayout('admin-layout');
            $this->setView('cliente/read');
            $this->loadPage();
        }else{
            header('Location: /autenticacao');
        }
    }

    public function getCliente()
    {
        if(empty($_POST)){
            die('Nenhum parâmetro passado');
        }

        if(!empty($_POST['id_cliente'])){
            $data = $this->model->getOne('id_cliente', $_POST['id_cliente']);
        }else{
            $data = $this->model->getOne('cpfcnpj_cliente', $_POST['cpfcnpj_cliente']);
        }

        die(json_encode($data));
    }

    public function getClientes(){
        $data = $this->model->getAll();
        die(json_encode($data));
    }

    public function create()
    {
        $this->model->insert($_POST);
        header('Location: /cliente');
    }

    public function update()
    {
        $this->model->update($_POST);
        header('Location: /cliente');
    }

    public function delete()
    {
        $id = explode(",", $_POST['id_cliente']);
        $this->model->delete($id);
    }
}
