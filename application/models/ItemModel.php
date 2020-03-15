<?php

include_once BASE_PATH . '/Model.php';

class ItemModel extends Model
{

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM item");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($id)
    {
        try {

            $stmt = $this->connection->prepare('SELECT * from item where id_item = :id_item');
            $stmt->bindValue(':id_item', (int)$id);
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

            $sql = "INSERT INTO item (nome, descricao, quantidade, unidade) VALUES (:nome, :descricao, :quantidade, :unidade)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'],
                ':quantidade' => $data['quantidade'],
                ':unidade' => $data['unidade']
            ));

            $this->connection->commit();

        } catch (Exception $e) {
            print($e->getMessage());
            $this->connection->rollback();
        }
    }


    public function update($data)
    {
        $this->connection->beginTransaction();

        try {

            $sql = 'UPDATE item SET nome = :nome, descricao = :descricao, quantidade = :quantidade, unidade = :unidade WHERE id_item = :id_item';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'],
                ':quantidade' => $data['quantidade'],
                ':unidade' => $data['unidade'],
                ':id_item' => $data['idItem']
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
                $stmt = $this->connection->prepare("DELETE FROM item WHERE id_item = :id_item");
                $stmt->bindValue(':id_item', (int)$i);
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