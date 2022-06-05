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

    $errors = false;
    $errors_mensagem = [];

    //Validação nome
    if(empty($myArray['nomeContato']))
    {
        $errors_mensagem['errors'][] = '<span class="errors_form">Nome deve ser preenchido</span>';
        $errors = true;
    }
    if(strlen($myArray['nomeContato']) > 150)
    {
        $errors_mensagem['errors'][] = '<span class="errors_form">Nome maior que 150 caracteres</span>';
        $errors = true;
    }
    //Fim validação nome
    //Validação apelido
    if(strlen($myArray['apelidoContato']) > 150)
    {
        $errors_mensagem['errors'][] = '<span class="errors_form">Nome maior que 150 caracteres</span>';
        $errors = true;
    }
    //Fim validação apelido

    //Caso tenha erros retorna a mensagem
    if($errors)
    {
        echo json_encode($errors_mensagem);
    }
    else
    {
        //retorna o contato para inserir o endereço
        $resultContato = $contato->insertUpdate($myArray['codigo_contato'],$myArray['nomeContato'],$myArray['apelidoContato']);

        if($myArray['codigo_contato'] > 0)
        {
            $contato->removerEndereco(0, $myArray['codigo_contato']);
            $contato->removerTelefone(0, $myArray['codigo_contato']);
        }
        
        
        foreach($myArray['cepContato'] as $key => $value)
        {
            $cepFormatado = str_replace(array("-", "."), '', $value);
            $resultEndereco = $contato->insertEndereco($resultContato,$cepFormatado,$myArray['enderecoContato'][$key],$myArray['numeroContato'][$key],$myArray['bairroContato'][$key],$myArray['cidadeContato'][$key],$myArray['estadoContato'][$key]);
        }

        foreach($myArray['telefoneContato'] as $key => $value)
        {
            $resultTelefone = $contato->insertTelefone($resultContato,$myArray['dddContato'][$key],$value);
        }

        if(isset($resultContato) > 0 && isset($resultEndereco) > 0 && isset($resultTelefone) > 0)
        {
            echo json_encode(1);
        }
        else
        {
            echo json_encode(0);
        }
    }

}

if(isset($_POST['removerEndereco']) && isset($_POST['endereco']))
{
    $resultContato = $contato->removerEndereco($_POST['endereco'], 0);
    echo json_encode($resultContato);
}

if(isset($_POST['removerTelefone']) && isset($_POST['telefone']))
{
    $resultContato = $contato->removerTelefone($_POST['telefone'], 0);
    echo json_encode($resultContato);
}