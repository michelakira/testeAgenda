<?php

namespace VExpenses\Modelo\Pessoas;

use VExpenses\Conexao\Conexao;
use VExpenses\Modelo\Pessoa;

class Contato extends Pessoa
{
    protected $nome;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function pegarNome(): string
    {
        return $this->nome;
    }

    public function pegarContatoId($nome): array
    {

        $sql = "SELECT id_contato FROM contatos WHERE nome = '{$nome}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $usuario = $consulta->fetchAll();
        return $usuario;
    }
}