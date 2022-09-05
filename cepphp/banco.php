<?php
//-------------------------------------BUSCA CEP INTEGRANDO ---------------------------------

class Localiz
{
    private $pdo;
    // CONEXAO COM O BANCO DE DADOS
    public function __construct($dbname, $host, $user, $senha)
    {
        try {
            $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $host, $user, $senha);
        } catch (PDOException $e) {
            echo "Erro com banco de dados: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erro generico: " . $e->getMessage();
            exit();
        }
    }

    public function findByCep($cep)
    {
        $cmd = $this->pdo->prepare("SELECT * from busca WHERE cep = :ce");
        $cmd->bindValue(":ce", $cep);
        $cmd->execute();
        if ($cmd->rowCount() > 0) // condicional se o cep existe no banco de dados
        {
           $data = $cmd->fetch(PDO::FETCH_ASSOC);

           $_SESSION['flash'] = 'Dados ja cadastrados';
           return $data;

        } else {

            return false;
        }
    }
    // FUNCAO PARA BUSCAR OS DADOS E COLOCAR NA TELA DIREITA
    public function buscarDados()
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM busca ORDER BY cep,logradouro,complemento,bairro,localidade,uf,ibge");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    // FUNCAO DE CADASTRAR LOCAIS NO BANCO DE DADOS

    public function salvarLoc($cep, $logradouro, $bairro, $localidade, $uf, $ibge, $complemento = null)
    {
       
        // ANTES DE LOCALIZAR VERIFICAR SE JA TEM O LOCAL
        // LOCALIZA
        $cmd = $this->pdo->prepare("SELECT id from busca WHERE cep = :ce");
        $cmd->bindValue(":ce", $cep);
        $cmd->execute();
        if ($cmd->rowCount() > 0) // condicional se o cep existe no banco de dados
        {
            return false;
        } else
        // nao foi encontrado o cep

        {
            $cmd = $this->pdo->prepare("INSERT INTO busca (cep,logradouro,complemento,bairro,localidade,uf,ibge) 
                                                        VALUES (:ce,:lo,:co,:ba,:lc,:uf,:ib)");
            $cmd->bindValue(":ce", $cep);
            $cmd->bindValue(":lo", $logradouro);
            $cmd->bindValue(":co", $complemento);
            $cmd->bindValue(":ba", $bairro);
            $cmd->bindValue(":lc", $localidade);
            $cmd->bindValue(":uf", $uf);
            $cmd->bindValue(":ib", $ibge);
            $cmd->execute();
            return true;
        }
    }

    public function deleteLoc($id)
    {
        $sql = $this->pdo->prepare('DELETE FROM busca WHERE id = :id');
        $sql->bindValue(':id', $id);
        $sql->execute();

        return true;
        }
    }

