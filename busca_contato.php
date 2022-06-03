<?php

use VExpenses\Modelo\Pessoas\Contato;

require_once 'autoload.php';

$contato = new Contato();

if(isset($_POST['nome']) && isset($_POST['pesquisa']))
{
    $resultContato = $contato->pegarContatos($_POST['nome']);
    echo json_encode($resultContato);
}

if(isset($_POST['id']) && isset($_POST['obterContato']))
{
    $resultContato = $contato->obterContato($_POST['id']);
    echo json_encode($resultContato);
}

if(isset($_POST['salvar']) && $_POST['salvar'] == 'salvar')
{
    $resultContato = $contato->insertUpdate($_POST['codigo_contato'],$_POST['nomeContato'],$_POST['apelidoContato'],$_POST['enderecoContato']);
    echo json_encode($resultContato);
}