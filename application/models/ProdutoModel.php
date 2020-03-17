<?php

include_once BASE_PATH . '/Model.php';

class ProdutoModel extends Model
{

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM produto");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($field, $id)
    {
        try {

            $stmt = $this->connection->prepare('SELECT * from produto where ' . $field . ' = ' . ':' . $field);
            $stmt->bindValue(':' . $field, $id);
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

            $sql = "INSERT INTO produto (descricao_produto, referencia_produto, tensao_produto, garantia_produto) 
                    VALUES (:descricao_produto, :referencia_produto, :tensao_produto, :garantia_produto)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':descricao_produto' => $data['descricaoProduto'],
                ':referencia_produto' => $data['referenciaProduto'],
                ':tensao_produto' => $data['tensaoProduto'],
                ':garantia_produto' => $data['garantiaProduto']
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

            $sql = 'UPDATE produto SET descricao_produto = :descricao_produto, 
                                       referencia_produto = :referencia_produto, 
                                       tensao_produto = :tensao_produto, 
                                       garantia_produto = :garantia_produto 
                   WHERE id_produto = :id_produto';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':descricao_produto' => $data['descricaoProduto'],
                ':referencia_produto' => $data['referenciaProduto'],
                ':tensao_produto' => $data['tensaoProduto'],
                ':garantia_produto' => $data['garantiaProduto'],
                ':id_produto' => $data['idProduto']
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
                $stmt = $this->connection->prepare("DELETE FROM produto WHERE id_produto = :id_produto");
                $stmt->bindValue(':id_produto', (int)$i);
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