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

            $sql = "INSERT INTO item (descricao_item, unidade_item) VALUES (:descricao_item, :unidade_item)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':descricao_item' => $data['descricaoItem'],
                ':unidade_item' => $data['unidadeItem']
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

            $sql = 'UPDATE item SET descricao_item = :descricao_item, unidade_item = :unidade_item WHERE id_item = :id_item';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':descricao_item' => $data['descricaoItem'],
                ':unidade_item' => $data['unidadeItem'],
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