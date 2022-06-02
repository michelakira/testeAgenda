<?php
session_start();

use VExpenses\Service\Autenticador;
use \VExpenses\Modelo\Funcionario\Usuario;

require_once 'autoload.php';

$autenticado = '';
if(isset($_POST['login']) && $_POST['login'] == 'login')
{
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $autenticador = new Autenticador();
    $umUsuario = new Usuario(
        $usuario,
        $senha
    );


    $autenticado = $autenticador->tentaLogin($umUsuario, $usuario, $senha);

    if($autenticado == true){
        $_SESSION['usuario'] = $usuario;
        header("location: index.php");
        exit();
    }
    else
    {
        $autenticado = false;
        session_destroy();
    }
}


?>

<html lang="pt">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/bootstrap.min.js"></script>

<style>
    html,body {
        height: 100%;
    }
    body {
    display: -ms-flexbox;
    display: -webkit-box;
    display: flex;
    -ms-flex-align: center;
    -ms-flex-pack: center;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
    }
    .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
    }
    .form-signin .checkbox {
    font-weight: 400;
    }
    .form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
    }
    .form-signin .form-control:focus {
    z-index: 2;
    }
    .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    }
    .text-muted
    {
        color: red;
    }
</style>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="mb-12">
                <form method="POST" class="form-signin">
                    <h1 class="h3 mb-3 font-weight-normal">Autenticação</h1>
                    <label for="inputEmail" class="sr-only">Usuário</label>
                    <input type="text" id="inputEmail" class="form-control" name="usuario" id="usuario" placeholder="Digite o seu usuário" required autofocus>
                    <label for="inputPassword" class="sr-only">Senha</label>
                    <input type="password" id="inputPassword" class="form-control" name="senha" id="senha" placeholder="Digite a senha" required>
                    <?php
                        if(isset($autenticado) && $autenticado === false)
                        {
                            echo '<p class="mt-2 mb-3 text-muted">Usuário ou senha incorretos</p>';
                        }
                    ?>
                    
                    <button class="btn btn-lg btn-primary btn-block" name="login" id="login" type="submit" value="login">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>


