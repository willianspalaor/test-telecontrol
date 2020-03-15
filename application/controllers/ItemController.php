<?php

include_once BASE_PATH . '/models/ItemModel.php';


class ItemController extends Controller
{
    public $model;
    public $itens;

    public function __construct()
    {
        $this->model = new ItemModel();
        $this->itens = $this->model->getAll();
    }

    public function index()
    {
        $this->setView('item/read');
        $this->loadPage();
    }

    public function getItem()
    {
        $data = $this->model->getOne($_POST['id_item']);
        die(json_encode($data));
    }

    public function create()
    {
        $this->model->insert($_POST);
        header('Location: /item');
    }

    public function update()
    {
        $this->model->update($_POST);
        header('Location: /item');
    }

    public function delete()
    {
        $id = explode(",", $_POST['id_item']);
        $this->model->delete($id);
    }
}
