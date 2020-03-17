<?php

include_once BASE_PATH . '/Model.php';

class OrdemServicoItensModel extends Model
{

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM ordem_servico_itens");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($id)
    {
        try {

            $stmt = $this->connection->prepare('SELECT * from item where id_ordem_servico_itens = :id_ordem_servico_itens');
            $stmt->bindValue(':id_ordem_servico_itens', (int)$id);
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

            $sql = "INSERT INTO ordem_servico_itens (quantidade, id_ordem_servico, id_item) VALUES (:quantidade, :id_ordem_servico, :id_item)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':quantidade' => $data['quantidade'],
                ':id_ordem_servico' => $data['idOrdemServico'],
                ':id_item' => $data['idItem']
            ));

            $lastId = $this->connection->lastInsertId();
            $this->connection->commit();
            return $lastId;

        } catch (Exception $e) {
            print($e->getMessage());
            $this->connection->rollback();
        }
    }

}