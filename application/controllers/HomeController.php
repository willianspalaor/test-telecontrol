<?php


class HomeController extends Controller
{
    public function index()
    {
        session_start();
        $this->setView('home/index');
        $this->loadPage();
    }

}
