<?php

include_once BASE_PATH . '/Model.php';

class ServicoModel extends Model
{

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM servico");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($id)
    {
        try {

            $stmt = $this->connection->prepare('SELECT * from servico where id_servico = :id_servico');
            $stmt->bindValue(':id_servico', (int)$id);
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

            $sql = "INSERT INTO servico (nome, descricao, valor) VALUES (:nome, :descricao, :valor)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'],
                ':valor' => $data['valor']
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

            $sql = 'UPDATE servico SET nome = :nome, descricao = :descricao, valor = :valor WHERE id_servico = :id_servico';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome' => $data['nome'],
                ':descricao' => $data['descricao'],
                ':valor' => $data['valor'],
                ':id_servico' => $data['idServico']
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
                $stmt = $this->connection->prepare("DELETE FROM servico WHERE id_servico = :id_servico");
                $stmt->bindValue(':id_servico', (int)$i);
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