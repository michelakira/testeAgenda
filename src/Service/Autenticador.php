<?php

namespace VExpenses\Service;

use VExpenses\Modelo\Autenticavel;

class Autenticador
{
    public function tentaLogin(Autenticavel $autenticavel,string $usuario, string $senha): bool
    {
        if ($autenticavel->podeAutenticar($usuario,$senha)) {
            return true;
        } else {
            return false;
        }
    }
}
