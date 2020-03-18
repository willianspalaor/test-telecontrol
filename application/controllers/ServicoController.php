<?php

include_once BASE_PATH . '/models/ServicoModel.php';


class ServicoController extends Controller
{
    public $model;
    public $servicos;

    public function __construct()
    {
        $this->model = new ServicoModel();
        $this->servicos = $this->model->getAll();
    }

    public function index()
    {
        if($this->authenticate()){

            $this->setLayout('admin-layout');
            $this->setView('servico/read');
            $this->loadPage();
        }else{
            header('Location: /autenticacao');
        }
    }

    public function getServico()
    {
        $data = $this->model->getOne($_POST['id_servico']);
        die(json_encode($data));
    }

    public function getServicos(){
        $data = $this->model->getAll();
        die(json_encode($data));
    }

    public function create()
    {
        $this->model->insert($_POST);
        header('Location: /servico');
    }

    public function update()
    {
        $this->model->update($_POST);
        header('Location: /servico');
    }

    public function delete()
    {
        $id = explode(",", $_POST['id_servico']);
        $this->model->delete($id);
    }
}
