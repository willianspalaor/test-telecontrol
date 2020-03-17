<?php

include_once BASE_PATH . '/Model.php';

class FornecedorModel extends Model
{

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM fornecedor");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($id)
    {
        try {

            $stmt = $this->connection->prepare('SELECT * from fornecedor where id_fornecedor = :id_fornecedor');
            $stmt->bindValue(':id_fornecedor', (int)$id);
            $stmt->execute();
            return $stmt->fetch();
        }
        catch(Exception $e) {
            print($e->getMessage());
        }

        return [];
    }

    public function insert($data)
    {
        $this->connection->beginTransaction();

        try {

            $sql = "INSERT INTO fornecedor 
                    (nome_fantasia, razao_social, cnpj, endereco_rua, endereco_numero, endereco_bairro) VALUES
                    (:nome_fantasia, :razao_social, :cnpj, :endereco_rua, :endereco_numero, :endereco_bairro)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome_fantasia' => $data['nomeFantasia'],
                ':razao_social' => $data['razaoSocial'],
                ':cnpj' => $data['cnpj'],
                ':endereco_rua' => $data['enderecoRua'],
                ':endereco_numero' => (int)$data['enderecoNumero'],
                ':endereco_bairro' => $data['enderecoBairro']
            ));

            $lastId = $this->connection->lastInsertId();
            $this->connection->commit();
            return $lastId;

        } catch (Exception $e) {
            print($e->getMessage());
            $this->connection->rollback();
        }
    }


    public function update($data)
    {
        $this->connection->beginTransaction();

        try {

            $sql = 'UPDATE fornecedor SET 
                      nome_fantasia = :nome_fantasia, 
                      razao_social = :razao_social, 
                      cnpj = :cnpj, 
                      endereco_rua  = :endereco_rua, 
                      endereco_numero = :endereco_numero, 
                      endereco_bairro = :endereco_bairro
				WHERE id_fornecedor = :id_fornecedor';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome_fantasia' => $data['nomeFantasia'],
                ':razao_social' => $data['razaoSocial'],
                ':cnpj' => $data['cnpj'],
                ':endereco_rua' => $data['enderecoRua'],
                ':endereco_numero' => (int)$data['enderecoNumero'],
                ':endereco_bairro' => $data['enderecoBairro'],
                ':id_fornecedor' => $data['idFornecedor'],
            ));

            $this->connection->commit();

        }catch(Exception $e){
            print($e->getMessage());
            $this->connection->rollback();
        }
    }

    public function delete($id)
    {
        $this->connection->beginTransaction();

        try {

            foreach($id as $i){
                $stmt = $this->connection->prepare("DELETE FROM fornecedor WHERE id_fornecedor = :id_fornecedor");
                $stmt->bindValue(':id_fornecedor', (int)$i);
                $stmt->execute();
            }

            $this->connection->commit();
        }
        catch(Exception $e) {
            print($e->getMEssage()());
            $this->connection->rollback();
        }
    }
}