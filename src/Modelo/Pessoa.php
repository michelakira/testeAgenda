<?php

namespace VExpenses\Modelo;

abstract class Pessoa
{
    use AcessoPropriedades;

    protected $usuario;

    public function __construct(string $usuario)
    {
        $this->usuario = $usuario;
    }

    public function recuperaNome(): string
    {
        return $this->usuario;
    }

}
