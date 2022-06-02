<?php

namespace VExpenses\Modelo\Pessoas;

use VExpenses\Conexao\Conexao;
use VExpenses\Modelo\Autenticavel;
use VExpenses\Modelo\Pessoa;

class Usuario extends Pessoa implements Autenticavel
{
    protected $senha;

    public function __construct(string $usuario, string $senha)
    {
        parent::__construct($usuario, $senha);
        $this->senha = $senha;
    }

    public function podeAutenticar(string $usuario, string $senha): bool
    {

        $sql = "SELECT * FROM usuarios WHERE usuario = '{$usuario}' AND senha = '{$senha}'";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $usuario = $consulta->fetchAll();

        if(count($usuario) > 0)
        return true;
        else
        return false;
    }

    public function pegarTodosUsuarios(): array
    {

        $sql = "SELECT * FROM usuarios";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $usuario = $consulta->fetchAll();
        return $usuario;
    }
}
