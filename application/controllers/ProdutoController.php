<?php

include_once BASE_PATH . '/models/ProdutoModel.php';
include_once BASE_PATH . '/helpers/vendor/autoload.php';

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
        }else{
            header('Location: /autenticacao');
        }
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

        if(isset($_FILES['fileImport']['tmp_name'])){

            try{

                $tmpName = $_FILES['fileImport']['tmp_name'];
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(TRUE);
                $spreadsheet = $reader->load($tmpName);

                $worksheet = $spreadsheet->getActiveSheet();
                // Get the highest row and column numbers referenced in the worksheet
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                $rows = array();

                for ($row = 2; $row <= $highestRow; ++$row) {
                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                        if($value !== null){
                            $rows[$col] = $value;
                        }
                    }

                    $data = [
                        'descricaoProduto' => $rows[1],
                        'referenciaProduto' => $rows[2],
                        'garantiaProduto' => $rows[3],
                        'tensaoProduto' => $rows[4]
                    ];

                    $this->model->insert($data);
                }

                header('Location: /produto');


            }catch(Exception $e){
                print $e->getMessage();
            }

        }
    }
}
