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
        $this->setView('cliente/read');
        $this->loadPage();
    }

    public function getCliente()
    {
        $data = $this->model->getOne($_POST['id_cliente']);
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
