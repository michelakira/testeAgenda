<?php

namespace VExpenses\Modelo;

use VExpenses\Conexao\Conexao;

class Log
{
    use AcessoPropriedades;


    public function insereLog(int $cod_contato): bool
    {
        $sql = "INSERT INTO log_contato(id_contato, dt_pesquisa) values ('{$cod_contato}','".date("Y-m-d H:i:s")."')";
		$consulta = Conexao::prepare($sql);
		$result = $consulta->execute();

        if($result)
        {
            return true;
        }
        return false;
    }

    public function obterPrincipaisContatos(): array
    {
        $sql = "SELECT 
                        COUNT(log_contato.id_contato) AS total,
                        contatos.id_contato,
                        contatos.nome 
                    FROM 
                        contatos INNER JOIN log_contato ON (contatos.id_contato = log_contato.id_contato)
                    GROUP BY log_contato.id_contato
                    ORDER BY 1 DESC
                    LIMIT 5";
		$consulta = Conexao::prepare($sql);
		$consulta->execute();
        $contatos = $consulta->fetchAll();
        return $contatos;
    }

}
