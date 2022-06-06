<?php

namespace VExpenses\Modelo\Pessoas;

use VExpenses\Conexao\Conexao;
use VExpenses\Modelo\Pessoa;
use \PDO;
use VExpenses\Modelo\Log;

class Contato extends Pessoa
{

    public function pegarContatoId($nome): array
    {

        $sql = "SELECT id_contato FROM contatos WHERE nome = '{$nome}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contato = $consulta->fetchAll();
        return $contato;
    }

    public function pegarContatos($nome): array
    {

        $sql = "SELECT nome FROM contatos WHERE nome LIKE '%{$nome}%'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contato = $consulta->fetchAll(PDO::FETCH_COLUMN);
        return $contato;
    }

    public function pegarContatosPesquisa($nome): array
    {

        $sql = "SELECT id_contato, nome FROM contatos WHERE nome LIKE '%{$nome}%'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contato = $consulta->fetchAll();
        return $contato;
    }

    public function pegarTodosContatos(): array
    {

        $sql = "SELECT id_contato, nome FROM contatos ORDER BY nome";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contato = $consulta->fetchAll();
        return $contato;
    }

    public function pegarPrincipaisContatos(): array
    {

        $log_contato = new Log();
        $principaisContatos = $log_contato->obterPrincipaisContatos();
        return $principaisContatos;
    }

    public function obterContato($id): array
    {
        $todasinformacoes = array();

        $sql = "SELECT * FROM contatos WHERE id_contato = '{$id}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contato = $consulta->fetchAll();
        array_push($todasinformacoes, $contato);

        $sql = "SELECT * FROM enderecos WHERE id_contato = '{$id}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $enderecos = $consulta->fetchAll();
        array_push($todasinformacoes, $enderecos);

        $sql = "SELECT * FROM telefones WHERE id_contato = '{$id}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $telefones = $consulta->fetchAll();
        array_push($todasinformacoes, $telefones);

        $log_contato = new Log();
        $log_contato->insereLog($id);


        return $todasinformacoes;
    }

    public function insertUpdate($codigo_contato, $nomeContato, $apelidoContato): int
    {
        if(empty($codigo_contato) || $codigo_contato == null)
        {
            $sql = "INSERT INTO contatos(nome,apelido) VALUES ('{$nomeContato}','{$apelidoContato}')";
        }
        else if(!empty($codigo_contato) || $codigo_contato != null)
        {
            $sql = "UPDATE contatos SET nome = '{$nomeContato}', apelido = '{$apelidoContato}' WHERE id_contato = '{$codigo_contato}' LIMIT 1";
        }
        
		$consulta = Conexao::prepare($sql);
		$resultado = $consulta->execute();
        
        if($resultado)
        {
            if(empty($codigo_contato) || $codigo_contato == null)
            {
                $consultaId = Conexao::prepare("SELECT MAX(id_contato) AS id_contato FROM contatos LIMIT 1");
                $consultaId->execute();
                $contatoId = $consultaId->fetchAll(PDO::FETCH_COLUMN);
                $codigo_contato = $contatoId[0];
            }
            return $codigo_contato;
        }
        return 0;
    }

    public function insertEndereco($id_contato, $cep, $endereco, $numero, $bairro, $cidade, $estado): int
    {

        $sql = "INSERT INTO enderecos(id_contato,cep,endereco,numero,bairro,cidade,estado) VALUES ('{$id_contato}','{$cep}','{$endereco}','{$numero}','{$bairro}','{$cidade}','{$estado}')";

		$consulta = Conexao::prepare($sql);
		$resultado = $consulta->execute();


        if($resultado)
        {
            return 1;
        }
        return 0;

    }

    public function removerEndereco($id_endereco, $id_contato): int
    {
        if($id_contato > 0)
        {
            $whereOp = " id_contato = '{$id_contato}' ";
        }
        else
        {
            $whereOp = " id_endereco = '{$id_endereco}' ";
        }

        $sql = "DELETE FROM enderecos WHERE {$whereOp} ";

		$consulta = Conexao::prepare($sql);
		$resultado = $consulta->execute();


        if($resultado)
        {
            return 1;
        }
        return 0;

    }

    public function insertTelefone($id_contato, $ddd, $numero): int
    {

        $sql = "INSERT INTO telefones(id_contato,ddd,numero) VALUES ('{$id_contato}','{$ddd}','{$numero}')";

		$consulta = Conexao::prepare($sql);
		$resultado = $consulta->execute();


        if($resultado)
        {
            return 1;
        }
        return 0;

    }

    public function removerTelefone($id_telefone, $id_contato): int
    {
        if($id_contato > 0)
        {
            $whereOp = " id_contato = '{$id_contato}' ";
        }
        else
        {
            $whereOp = " id_telefone = '{$id_telefone}' ";
        }

        $sql = "DELETE FROM telefones WHERE {$whereOp} ";

		$consulta = Conexao::prepare($sql);
		$resultado = $consulta->execute();


        if($resultado)
        {
            return 1;
        }
        return 0;

    }
}