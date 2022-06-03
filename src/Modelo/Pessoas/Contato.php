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

    public function obterContato($id): array
    {

        $sql = "SELECT * FROM contatos WHERE id_contato = '{$id}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contato = $consulta->fetchAll();

        $log_contato = new Log();
        $log_contato->insereLog($id);

        return $contato;
    }

    public function insertUpdate($codigo_contato, $nomeContato, $apelidoContato, $enderecoContato): bool
    {
        if(empty($codigo_contato) || $codigo_contato == null)
        {
            $sql = "INSERT INTO contatos(nome,apelido,endereco) VALUES ('{$nomeContato}','{$apelidoContato}','{$enderecoContato}')";
        }
        else if(!empty($codigo_contato) || $codigo_contato != null)
        {
            $sql = "UPDATE contatos SET nome = '{$nomeContato}', apelido = '{$apelidoContato}', endereco = '{$enderecoContato}' WHERE id_contato = '{$codigo_contato}' LIMIT 1";
        }
        
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        
        return true;
    }
}