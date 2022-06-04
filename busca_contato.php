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

    parse_str($_POST['data'], $myArray);

    //retorna o contato para inserir o endereço
    $resultContato = $contato->insertUpdate($myArray['codigo_contato'],$myArray['nomeContato'],$myArray['apelidoContato']);
    
    foreach($myArray['cepContato'] as $key => $value)
    {
        $resultEndereco = $contato->insertEndereco($resultContato,$value,$myArray['enderecoContato'][$key],$myArray['numeroContato'][$key],$myArray['bairroContato'][$key],$myArray['cidadeContato'][$key],$myArray['estadoContato'][$key]);
    }

    foreach($myArray['telefoneContato'] as $key => $value)
    {
        $resultTelefone = $contato->insertTelefone($resultContato,$myArray['dddContato'][$key],$value);
    }

    if($resultContato > 0 && $resultEndereco > 0 && $resultTelefone > 0)
    {
        echo json_encode("1");
    }
    else
    {
        echo json_encode("0");
    }

}

if(isset($_POST['removerEndereco']) && isset($_POST['endereco']))
{
    $resultContato = $contato->removerEndereco($_POST['endereco']);
    echo json_encode($resultContato);
}

if(isset($_POST['removerTelefone']) && isset($_POST['telefone']))
{
    $resultContato = $contato->removerTelefone($_POST['telefone']);
    echo json_encode($resultContato);
}