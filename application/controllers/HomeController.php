<?php


class HomeController extends Controller
{
    public function index()
    {
        if($this->authenticate()){

            $this->setLayout('admin-layout');
            $this->setView('home/index');
            $this->loadPage();
            exit;
        }

        header('Location: /autenticacao/index');
    }

}
