<?php

include_once BASE_PATH . '/Model.php';

class ClienteModel extends Model
{

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM cliente");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($field, $id)
    {
        try {

            $stmt = $this->connection->prepare('SELECT * from cliente where ' . $field . ' = ' . ':' . $field);
            $stmt->bindValue(':' . $field, (int)$id);
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

            $sql = "INSERT INTO cliente 
                    (nome_cliente, telefone_cliente, cpfcnpj_cliente, endereco_rua, endereco_numero, endereco_bairro, endereco_estado, endereco_cidade) VALUES
                    (:nome_cliente, :telefone_cliente, :cpfcnpj_cliente, :endereco_rua, :endereco_numero, :endereco_bairro, :endereco_estado, :endereco_cidade)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':nome_cliente' => $data['nomeCliente'],
                ':telefone_cliente' => $data['telefoneCliente'],
                ':cpfcnpj_cliente' => $data['cpfCnpjCliente'],
                ':endereco_rua' => $data['enderecoRua'],
                ':endereco_numero' => (int)$data['enderecoNumero'],
                ':endereco_bairro' => $data['enderecoBairro'],
                ':endereco_estado' => $data['enderecoEstado'],
                ':endereco_cidade' => $data['enderecoCidade']
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

            $sql = 'UPDATE cliente SET 
                      nome_cliente = :nome_cliente, 
                      telefone_cliente = :telefone_cliente, 
                      cpfcnpj_cliente = :cpfcnpj_cliente, 
                      endereco_rua  = :endereco_rua, 
                      endereco_numero = :endereco_numero, 
                      endereco_bairro = :endereco_bairro,
                      endereco_estado = :endereco_estado,
                      endereco_cidade = :endereco_cidade
				WHERE id_cliente = :id_cliente';

            $stmt = $this->connection->prepare($sql);

            $stmt->execute(array(
                ':nome_cliente' => $data['nomeCliente'],
                ':telefone_cliente' => $data['telefoneCliente'],
                ':cpfcnpj_cliente' => $data['cpfCnpjCliente'],
                ':endereco_rua' => $data['enderecoRua'],
                ':endereco_numero' => (int)$data['enderecoNumero'],
                ':endereco_bairro' => $data['enderecoBairro'],
                ':endereco_estado' => $data['enderecoEstado'],
                ':endereco_cidade' => $data['enderecoCidade'],
                ':id_cliente' => $data['idCliente']
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
                $stmt = $this->connection->prepare("DELETE FROM cliente WHERE id_cliente = :id_cliente");
                $stmt->bindValue(':id_cliente', (int)$i);
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