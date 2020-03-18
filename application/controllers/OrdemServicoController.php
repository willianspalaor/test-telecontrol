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
        }else{
            header('Location: /autenticacao');
        }
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

    public function report(){

        $ordens = $this->model->getAll();
        $valor = 0;
        $finalizados = 0;
        $pendentes = 0;

        foreach($ordens as $ordem){

            if($ordem['status'] === 'Finalizado'){
                $finalizados++;
            }else{
                $pendentes++;
            }
            $valor += $ordem['valor_servico'] + $ordem['valor_excedente'];
        }

        header('Content-Type: application/pdf');

        $pdf = new FPDF();
        $pdf->SetTitle('Relatorio');

        $pdf->AddPage();

        // Title
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,'Relatorio Ordens de Servico',0,1,'L',true);
        $pdf->Ln(4);

        // Font
        $pdf->SetFont('Times','',12);

        $pdf->MultiCell(200,5,'Quantidade de ordens finalizadas: ' . $finalizados);
        $pdf->MultiCell(200,5,'Valor Total:' . ' R$' . $valor);
        $pdf->Ln();

        $pdf->MultiCell(200,5,'Quantidade de ordens Pendentes: ' . $pendentes);
        $pdf->Ln();

        ob_clean();
        $pdf->Output("Relatorio.pdf","D");
    }

    public function imprimir($args){

        header('Content-Type: application/pdf; charset=utf-8');
        $odemServico = $this->model->getOne($args[0]);

        $pdf = new FPDF();
        $pdf->SetTitle('Relatorio');

        $pdf->AddPage();

        // Title
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,'Ordem de Servico',0,1,'L',true);
        $pdf->Ln(4);

        // Font
        $pdf->SetFont('Times','',12);

        $pdf->MultiCell(200,5,'Esse e um modelo da ordem de servico');
        $pdf->Ln();
        // Mention
        $pdf->SetFont('','I');

        ob_clean();
        $pdf->Output("ordem_de_servico_" . $odemServico['nota_fiscal'] . ".pdf","D");
    }
}
