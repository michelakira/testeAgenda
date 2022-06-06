<?php

use VExpenses\Modelo\Pessoas\Contato;

require_once 'autoload.php';

$contato = new Contato();

//Pesquisa pelo nome do contato
if(isset($_POST['nome']) && isset($_POST['pesquisa']))
{
    $resultContato = $contato->pegarContatos($_POST['nome']);
    echo json_encode($resultContato);
}

//Pesquisa todos contatos
if(isset($_POST['pesquisa']) && $_POST['pesquisa'] == 'obterContatos')
{
    $resultContato = $contato->pegarTodosContatos();
    echo json_encode($resultContato);
}

//Pesquisa principais contatos
if(isset($_POST['pesquisa']) && $_POST['pesquisa'] == 'obterPrincipaisContatos')
{
    $resultContato = $contato->pegarPrincipaisContatos();
    echo json_encode($resultContato);
}

//Pesquisa pelo id do contato
if(isset($_POST['id']) && isset($_POST['obterContato']))
{
    $resultContato = $contato->obterContato($_POST['id']);
    echo json_encode($resultContato);
}

//Salva o contato ou altera se já é um contato
if(isset($_POST['salvar']) && $_POST['salvar'] == 'salvar')
{

    //recebe formulario serializado
    parse_str($_POST['data'], $myArray);

    //variaveis de erro caso receba uma variavel errada
    $errors = false;
    $errors_mensagem = [];

    //Validação nome
    if(empty($myArray['nomeContato']))
    {
        $errors_mensagem['errors'][] = '<span class="errors_form">Nome deve ser preenchido<br/></span>';
        $errors = true;
    }
    if(strlen($myArray['nomeContato']) > 150)
    {
        $errors_mensagem['errors'][] = '<span class="errors_form">Nome maior que 150 caracteres<br/></span>';
        $errors = true;
    }
    //Fim validação nome
    //Validação apelido
    if(strlen($myArray['apelidoContato']) > 150)
    {
        $errors_mensagem['errors'][] = '<span class="errors_form">Nome maior que 150 caracteres<br/></span>';
        $errors = true;
    }
    //Fim validação apelido
    //validação de CEP
    foreach($myArray['cepContato'] as $key => $value)
    {
        $cepFormatado = str_replace(array("-", "."), '', $value);
        if(!empty($cepFormatado) && (!is_int(($cepFormatado * 1)) || strlen($cepFormatado) > 8))
        {
            $errors_mensagem['errors'][] = '<span class="errors_form">CEP('.$cepFormatado.') não está no formato correto<br/></span>';
            $errors = true;
        }
        if(strlen($myArray['enderecoContato'][$key]) > 150)
        {
            $errors_mensagem['errors'][] = '<span class="errors_form">Endereço do CEP ('.$cepFormatado.') é maior que 150 caracteres<br/></span>';
            $errors = true;
        }
        if(strlen($myArray['numeroContato'][$key]) > 15)
        {
            $errors_mensagem['errors'][] = '<span class="errors_form">Número do endereço do CEP ('.$cepFormatado.') é maior que 15 caracteres<br/></span>';
            $errors = true;
        }
        if(strlen($myArray['bairroContato'][$key]) > 100)
        {
            $errors_mensagem['errors'][] = '<span class="errors_form">Bairro do endereço do CEP ('.$cepFormatado.') é maior que 100 caracteres<br/></span>';
            $errors = true;
        }
        if(strlen($myArray['cidadeContato'][$key]) > 100)
        {
            $errors_mensagem['errors'][] = '<span class="errors_form">Bairro do endereço do CEP ('.$cepFormatado.') é maior que 100 caracteres<br/></span>';
            $errors = true;
        }
        if(strlen($myArray['estadoContato'][$key]) > 2)
        {
            $errors_mensagem['errors'][] = '<span class="errors_form">Unidade federal do endereço do CEP ('.$cepFormatado.') é maior que 2 caracteres<br/></span>';
            $errors = true;
        }
    }
    //Fim validação CEP
    //validação de Endereço
    foreach($myArray['telefoneContato'] as $key => $value)
    {
        if(!empty($value))
        {
            if(!is_int(($value * 1)))
            {
                $errors_mensagem['errors'][] = '<span class="errors_form">Telefone ('.$myArray['dddContato'][$key].') '.$value.' não está no formato correto<br/></span>';
                $errors = true;
            }
        }
        if(!empty($myArray['dddContato'][$key]))
        {
            if(!is_int(($myArray['dddContato'][$key] * 1)))
            {
                $errors_mensagem['errors'][] = '<span class="errors_form">Telefone ('.$myArray['dddContato'][$key].') '.$value.' não está no formato correto<br/></span>';
                $errors = true;
            }
        }
    }
    //Fim validação de Endereço

    //Caso tenha erros retorna a mensagem
    if($errors)
    {
        echo json_encode($errors_mensagem);
    }
    else
    {
        //retorna o contato para inserir o endereço
        $resultContato = $contato->insertUpdate($myArray['codigo_contato'],$myArray['nomeContato'],$myArray['apelidoContato']);

        //remove todos os endereços e telefone ligados ao contato caso ele já esteja cadastrado
        if($myArray['codigo_contato'] > 0)
        {
            $contato->removerEndereco(0, $myArray['codigo_contato']);
            $contato->removerTelefone(0, $myArray['codigo_contato']);
        }
        
        //recebe todas variaveis referente ao endereco do contato e adiciona na tabela
        foreach($myArray['cepContato'] as $key => $value)
        {
            $cepFormatado = str_replace(array("-", "."), '', $value);
            $resultEndereco = $contato->insertEndereco($resultContato,$cepFormatado,$myArray['enderecoContato'][$key],$myArray['numeroContato'][$key],$myArray['bairroContato'][$key],$myArray['cidadeContato'][$key],$myArray['estadoContato'][$key]);
        }

        //recebe todas variaveis referente ao telefone do contato e adiciona na tabela
        foreach($myArray['telefoneContato'] as $key => $value)
        {
            $resultTelefone = $contato->insertTelefone($resultContato,$myArray['dddContato'][$key],$value);
        }

        //retorna e a inclusão foi realizada e 2 se acaso apresentou algum problema
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