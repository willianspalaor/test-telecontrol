<?php

include_once BASE_PATH . '/Model.php';
include_once BASE_PATH . '/models/ClienteModel.php';
include_once BASE_PATH . '/models/ProdutoModel.php';
include_once BASE_PATH . '/models/OrdemServicoItensModel.php';

class OrdemServicoModel extends Model
{

    public function getAll($filter = null)
    {
        $where = '';
        $order = ' ORDER BY ordem_servico.data_emissao DESC';

        switch($filter){
            case 1:
                $where = '';
                $order = ' ORDER BY ordem_servico.data_emissao DESC';
            break;

            case 2: $where = ' WHERE ordem_servico.status = "Pendente"' ;
                    $order = ' ORDER BY ordem_servico.data_emissao ASC';
            break;

            case 3: $where = ' WHERE ordem_servico.status = "Pendente"' ;
                    $order = ' ORDER BY ordem_servico.data_emissao DESC';
            break;

            case 4: $where = ' WHERE ordem_servico.status = "Finalizado"' ;
                    $order = ' ORDER BY ordem_servico.data_emissao ASC';
            break;

            case 5: $where = ' WHERE ordem_servico.status = "Finalizado"' ;
                $order = ' ORDER BY ordem_servico.data_emissao DESC';
            break;
        }

        $stmt = $this->connection->prepare("SELECT * FROM ordem_servico 
                                                        JOIN cliente ON ordem_servico.id_cliente = cliente.id_cliente
                                                        JOIN produto ON ordem_servico.id_produto = produto.id_produto" . $where  . $order);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($id)
    {
        try {

            $stmt = $this->connection->prepare("SELECT * FROM ordem_servico 
                                                                JOIN cliente ON ordem_servico.id_cliente = cliente.id_cliente
                                                                JOIN produto ON ordem_servico.id_produto = produto.id_produto
                                                           WHERE id_ordem_servico = :id_ordem_servico");

            $stmt->bindValue(':id_ordem_servico', (int)$id);
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
        $id_cliente = $data['idCliente'];
        $id_produto = $data['idProduto'];

        // Verifica se existe um cliente não cadastrado na ordem de serviço
        if(isset($data['nomeCliente']) && empty($data['idCliente'])){
            $id_cliente = (new ClienteModel())->insert($data);
        }

        // Verifica se existe um produto não cadastrado na ordem de serviço
        if(isset($data['descricaoProduto']) && empty($data['idProduto'])){
            $id_produto = (new ProdutoModel())->insert($data);
        }

        $this->connection->beginTransaction();

        try {

            $sql = "INSERT INTO ordem_servico (id_cliente, id_produto, nota_fiscal, data_compra, data_emissao, status_garantia) 
                    VALUES (:id_cliente, :id_produto, :nota_fiscal, :data_compra, :data_emissao, :status_garantia)";


            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array(
                ':id_cliente' => $id_cliente,
                ':id_produto' => $id_produto,
                ':nota_fiscal' => $data['notaFiscal'],
                ':data_compra' => $data['dataCompra'],
                ':data_emissao' => $data['dataEmissao'],
                ':status_garantia' => $data['statusGarantia']
            ));

            $lastId = $this->connection->lastInsertId();
            $this->connection->commit();
            return $lastId;

        } catch (Exception $e) {
            print($e->getMessage());
            $this->connection->rollback();
        }
    }

    public function update($data, $finalizar = false)
    {
        if($finalizar){

            $modelOrdemServicoItens = new OrdemServicoItensModel();

            $i = 1;
            while(isset($data['itemOrdemServico' . $i])){

                $dataItem = [
                    'idOrdemServico' => $data['idOrdemServico'],
                    'idItem' => $data['itemOrdemServico' . $i],
                    'quantidade' => $data['quantidade' . $i]
                ];

                $i++;
                $modelOrdemServicoItens->insert($dataItem);
            }

            $this->connection->beginTransaction();

            try {

                $sql = 'UPDATE ordem_servico SET 
                      descricao_problema = :descricao_problema, 
                      valor_servico = :valor_servico, 
                      servico_excedente  = :servico_excedente, 
                      valor_excedente = :valor_excedente, 
                      status = :status
				WHERE id_ordem_servico = :id_ordem_servico';

                $stmt = $this->connection->prepare($sql);

                $stmt->execute(array(
                    ':descricao_problema' => $data['descricaoProblema'],
                    ':valor_servico' => $this->formatFieldFloat($data['valorServico']),
                    ':servico_excedente' => $data['servicoExcedente'],
                    ':valor_excedente' => $this->formatFieldFloat($data['valorExcedente']),
                    ':status' => 'Finalizado',
                    ':id_ordem_servico' => $data['idOrdemServico']
                ));

                $this->connection->commit();

            }catch(Exception $e){
                print($e->getMessage());
                $this->connection->rollback();
            }

        }else{

            $id_cliente = $data['idCliente'];
            $id_produto = $data['idProduto'];

            // Verifica se existe um cliente não cadastrado na ordem de serviço
            if(isset($data['nomeCliente']) && empty($data['idCliente'])){
                $id_cliente = (new ClienteModel())->insert($data);
            }

            // Verifica se existe um produto não cadastrado na ordem de serviço
            if(isset($data['descricaoProduto']) && empty($data['idProduto'])){
                $id_produto = (new ProdutoModel())->insert($data);
            }

            $this->connection->beginTransaction();

            try {

                $sql = 'UPDATE ordem_servico SET 
                      id_cliente = :id_cliente, 
                      nota_fiscal = :nota_fiscal, 
                      data_compra  = :data_compra, 
                      id_produto = :id_produto, 
                      data_emissao = :data_emissao,
                      status_garantia = :status_garantia
				WHERE id_ordem_servico = :id_ordem_servico';


                $stmt = $this->connection->prepare($sql);

                $stmt->execute(array(
                    ':id_cliente' => $id_cliente,
                    ':id_produto' => $id_produto,
                    ':nota_fiscal' => $data['notaFiscal'],
                    ':data_compra' => $data['dataCompra'],
                    ':data_emissao' => $data['dataEmissao'],
                    ':status_garantia' => $data['statusGarantia'],
                    ':id_ordem_servico' => $data['idOrdemServico']
                ));

                $this->connection->commit();

            }catch(Exception $e){
                print($e->getMessage());
                $this->connection->rollback();
            }
        }
    }

    public function delete($id)
    {
        (new OrdemServicoItensModel())->delete($id);

        $this->connection->beginTransaction();

        try {

            foreach($id as $i){
                $stmt = $this->connection->prepare("DELETE FROM ordem_servico WHERE id_ordem_servico = :id_ordem_servico");
                $stmt->bindValue(':id_ordem_servico', (int)$i);
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