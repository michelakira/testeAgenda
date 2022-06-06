<?php 


require('verifica_login.php'); 

use VExpenses\Modelo\Pessoas\Contato;

require_once 'autoload.php';

include_once('src/Includes/header.php');



if(isset($_POST['botao_pesquisa']) && $_POST['botao_pesquisa'] == 'botao_pesquisa')
{
    $input_pesquisa = $_POST['pesquisa'];
    $contato = new Contato();
    $resultContato = $contato->pegarContatosPesquisa($_POST['pesquisa']);
}
else if(isset($_POST['botao_sair']) && $_POST['botao_sair'] == 'botao_sair')
{
    session_destroy();
    header("location: autenticacao.php");
}


?>



<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <div class="col-2">
        <a href="index.php" class="navbar-brand titulo-agenda">Contatos</a><a class="btn adicionar_contato" type="button" data-bs-toggle="modal" data-bs-target="#cadastroContato" onclick="limpar()"><i class="fa fa-plus"></i></a>
        </div>
        <div class="col-8">
        <form method="POST">

            <div class="autocomplete d-flex" >
                <input class="form-control d-flex" id="pesquisa" name="pesquisa" type="search" placeholder="Pesquisa" aria-label="Pesquisa" value="<?php if(!empty($input_pesquisa)) echo $input_pesquisa; ?>">
                <button class="btn bt_pesquisa" type="submit" name="botao_pesquisa" value="botao_pesquisa"><i class="fa fa-search"></i></button>
                <button class="btn bt_pesquisa" type="submit" name="botao_sair" value="botao_sair">Sair</button>
            </div>
    
        </form>
        </div>
        <div class="container-fluid contatos">
        <div class="row">
                
                <?php     
                    if(isset($_POST['botao_pesquisa']))
                    {
                        echo '<div class="col-12 text-center titulo-agenda">Contatos <i class="fa fa-address-card"></i></div>';
                        for($i = 0; $i < count($resultContato); $i++)
                        {
                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#cadastroContato" class="contatos_principais" onclick="editar('.$resultContato[$i]->id_contato.')"><div class="col-12 contatos_principais_div"><span class="alfabeto_contato">'.substr(strtoupper($resultContato[$i]->nome),0,1).'</span> '.$resultContato[$i]->nome."</div></a>";
                        }
                    }
                    if(!isset($_POST['botao_pesquisa']))
                    {
                        echo '<div class="col-12 text-center titulo-agenda">Favoritos <i class="fa fa-star"></i></div><div class="container-fluid" id="principais_contatos"></div>';
                        echo '<div class="col-12 text-center titulo-agenda">Contatos <i class="fa fa-address-card"></i></div>
                        <div class="container-fluid" id="todos_contatos"></div>';
                    }
                ?>
                
            </div>
        </div>
    </div>
</nav>

<!-- Modal de inclusão e alteração-->
<div class="modal fade" id="cadastroContato" tabindex="-1" aria-labelledby="cadastroContatoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="cadastroContatoLabel">Contato</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpar()"></button>
        </div>
        <div class="modal-body">
            <div class="col-12" id="errors">
            </div>
        <form id="formulario_dados_contato">
            <div class="col-12">
                <label for="nomeContato">Nome</label>
                <input class="form-control" type="text" id="nomeContato" name="nomeContato" maxlength="150" placeholder="Digite o nome do contato" onkeypress="return apenasLetras(event,this);" required>
            </div>
            <div class="col-12">
                <label for="apelidoContato">Apelido</label>
                <input class="form-control" type="text" id="apelidoContato" name="apelidoContato" placeholder="Digite o apelido do contato" onkeypress="return apenasLetras(event,this);" maxlength="150">
            </div>
            <div class="row" id="formulario">
                <hr>
                <div class="col-11">
                    <div class="row">
                        <div class="col-4">
                            <label for="cepContato">CEP</label>
                            <input class="form-control" type="text" id="cepContato" name="cepContato[]" maxlength="8" onblur="carregaCep(this,1)">
                        </div>
                        <div class="col-8">
                            <label for="enderecoContato">Endereço</label>
                            <input class="form-control" type="text" id="enderecoContato1" name="enderecoContato[]" readonly="readonly">
                        </div>
                        <div class="col-4">
                            <label for="numeroContato">Número</label>
                            <input class="form-control" type="text" id="numeroContato1" name="numeroContato[]" readonly="readonly">
                        </div>
                        <div class="col-8">
                            <label for="bairroContato">Bairro</label>
                            <input class="form-control" type="text" id="bairroContato1" name="bairroContato[]" readonly="readonly">
                        </div>
                        <div class="col-8">
                            <label for="cidadeContato">Cidade</label>
                            <input class="form-control" type="text" id="cidadeContato1" name="cidadeContato[]" readonly="readonly">
                        </div>
                        <div class="col-4">
                            <label for="estadoContato">Estado</label>
                            <input class="form-control" type="text" id="estadoContato1" name="estadoContato[]" readonly="readonly">
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-secondary add-campo" id="add-campo"> + </button>
                </div>
            </div>
            <div class="row" id="formulario_telefone">
                <hr>
                <div class="col-3">
                    <label for="dddContato">DDD</label>
                    <input class="form-control" type="number" id="dddContato" name="dddContato[]" maxlength="2" onkeypress="return apenasNumeros(event,this,'ddd');">
                </div>
                <div class="col-8">
                    <label for="telefoneContato">Telefone</label>
                    <input class="form-control" type="number" id="telefoneContato" name="telefoneContato[]" maxlength="9" onkeypress="return apenasNumeros(event,this,'tel');">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-secondary add-campo-telefone" id="add-campo-telefone"> + </button>
                </div>    
            </div>
            <input type="hidden" id="codigo_contato" name="codigo_contato">
        </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="limpar()"><span style="font-weight:bold;">Fechar</span></button>
            <button type="submit" class="btn bt_salvar_form" onclick="salvar()"><span style="font-weight:bold;">Salvar</span></button>
        </div>
        </div>
    </div>
</div>

<!-- Toast de aviso-->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99999">
    <div class="toast hide">
        <div class="toast-header">
            <strong class="me-auto">Contatos</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toast_msg">
        </div>
    </div>
</div>

<?php 
require_once('src/Includes/js_agenda.php');
include_once('src/Includes/footer.php'); 

?>