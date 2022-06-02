<?php

namespace VExpenses\Modelo;

interface Autenticavel
{
    public function podeAutenticar(string $usuario, string $senha): bool;
}
