<?php

include_once BASE_PATH . '/models/OrdemServicoModel.php';
include_once BASE_PATH . '/models/ItemModel.php';
include_once BASE_PATH . '/models/ServicoModel.php';
include_once BASE_PATH . 'helpers/fpdf/fpdf.php';

class OrdemServicoController extends Controller
{

    public $model;
    public $ordensServico;
    public $itens;
    public $servicos;

    public function __construct()
    {
        $this->model = new OrdemServicoModel();
        $this->ordensServico = $this->model->getAll();
        $this->itens = (new ItemModel())->getAll();
        $this->servicos = (new ServicoModel())->getAll();
    }

    public function index($filter)
    {
        if($this->authenticate()){

            if(!empty($filter)){
                $this->ordensServico = $this->model->getAll($filter[0]);
            }

            $this->setLayout('admin-layout');
            $this->setView('ordem-servico/read');
            $this->loadPage();
        }

        header('Location: /autenticacao/index');
    }

    public function getOrdemServico()
    {
        $data = $this->model->getOne($_POST['id_ordem_servico']);
        die(json_encode($data));
    }

    public function getOrdensServico(){
        $data = $this->model->getAll();
        die(json_encode($data));
    }

    public function create()
    {
        $this->model->insert($_POST);
        header('Location: /ordemServico');
    }

    public function update()
    {
        $this->model->update($_POST);
        header('Location: /ordemServico');
    }

    public function delete()
    {
        $id = explode(",", $_POST['id_ordem_servico']);
        $this->model->delete($id);
    }

    public function finish(){
        $this->model->update($_POST, true);
        header('Location: /ordemServico');
    }

    public function imprimir($args){

        $odemServico = $this->model->getOne($args[0]);
        header('Content-Type: application/pdf');

        $pdf= new FPDF("P","pt","A4");

        $pdf->AddPage();
        $pdf->SetFont('arial','B',12);
        $pdf->Cell(0,5,"Ordem de Servico",0,1,'L');
        $pdf->Ln(8);


        ob_clean();
        $pdf->Output("ordem_de_servico_" . $odemServico['nota_fiscal'] . ".pdf","D");
    }
}
