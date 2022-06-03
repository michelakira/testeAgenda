<?php 


require('verifica_login.php'); 
use VExpenses\Modelo\Log;
use VExpenses\Modelo\Pessoas\Contato;

require_once 'autoload.php';

include_once('src/Includes/header.php');

$log_contato = new Log();

if(isset($_POST['botao_pesquisa']))
{
    $input_pesquisa = $_POST['pesquisa'];
    $contato = new Contato();
    $resultContato = $contato->pegarContatosPesquisa($_POST['pesquisa']);
}
else
{
    //obtem os 5 principais contatos pesquisados
    $principaisContatos = '';
    $principaisContatos = $log_contato->obterPrincipaisContatos();

    //obtem todos os contatos
    $todosContatos = '';
    $contato = new Contato();
    $todosContatos = $contato->pegarTodosContatos();
}

?>



<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand titulo-agenda">Contatos</a>
        <form class="d-flex" method="POST">
            <button class="btn btn-outline-success adicionar_contato" type="button" data-bs-toggle="modal" data-bs-target="#cadastroContato" onclick="limpar()"><i class="fa fa-plus"></i></button>
            <div class="autocomplete" style="width:300px;">
                <input class="form-control me-2" id="pesquisa" name="pesquisa" type="search" placeholder="Pesquisa" aria-label="Pesquisa" value="<?php if(!empty($input_pesquisa)) echo $input_pesquisa; ?>">
            </div>
            <button class="btn btn-outline-success" type="submit" name="botao_pesquisa" value="botao_pesquisa"><i class="fa fa-search"></i></button>
        </form>
        <div class="container-fluid contatos">
        <div class="row">
                
                <?php     
                    if(isset($principaisContatos))
                    {
                        echo '<div class="col-12 text-center titulo-agenda">Favoritos <i class="fa fa-star"></i></div>';
                        for($i = 0; $i < count($principaisContatos); $i++)
                        {
                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#cadastroContato" class="contatos_principais" onclick="editar('.$principaisContatos[$i]->id_contato.')"><div class="col-12 contatos_principais_div">'.$principaisContatos[$i]->nome."</div></a>";
                        }
                        
                    }
                    if(isset($todosContatos))
                    {
                        echo '<div class="col-12 text-center titulo-agenda">Contatos <i class="fa fa-address-card"></i></i></div>';
                        for($i = 0; $i < count($todosContatos); $i++)
                        {
                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#cadastroContato" class="contatos_principais" onclick="editar('.$todosContatos[$i]->id_contato.')"><div class="col-12 contatos_principais_div">'.$todosContatos[$i]->nome."</div></a>";
                        }
                    }
                    if(isset($_POST['botao_pesquisa']))
                    {
                        echo '<div class="col-12 text-center titulo-agenda">Contatos <i class="fa fa-address-card"></i></i></div>';
                        for($i = 0; $i < count($resultContato); $i++)
                        {
                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#cadastroContato" class="contatos_principais" onclick="editar('.$resultContato[$i]->id_contato.')"><div class="col-12 contatos_principais_div">'.$resultContato[$i]->nome."</div></a>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</nav>
<div class="modal fade" id="cadastroContato" tabindex="-1" aria-labelledby="cadastroContatoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="cadastroContatoLabel">Contato</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpar()"></button>
        </div>
        <div class="modal-body">
        <form id="formulario_dados_contato">
            <div class="col-12">
                <label for="nomeContato">Nome</label>
                <input class="form-control" type="text" id="nomeContato" name="nomeContato">
            </div>
            <div class="col-12">
                <label for="apelidoContato">Apelido</label>
                <input class="form-control" type="text" id="apelidoContato" name="apelidoContato">
            </div>
            <div class="row" id="formulario">
                <div class="col-4">
                    <label for="cepContato">CEP</label>
                    <input class="form-control" type="text" id="cepContato[]" name="cepContato[]">
                </div>
                <div class="col-8">
                    <label for="enderecoContato">Endereço</label>
                    <input class="form-control" type="text" id="enderecoContato" name="enderecoContato[]">
                </div>
                <div class="col-4">
                    <label for="numeroContato">Número</label>
                    <input class="form-control" type="text" id="numeroContato" name="numeroContato[]">
                </div>
                <div class="col-8">
                    <label for="bairroContato">Bairro</label>
                    <input class="form-control" type="text" id="bairroContato" name="bairroContato[]">
                </div>
                <div class="col-8">
                    <label for="cidadeContato">Cidade</label>
                    <input class="form-control" type="text" id="cidadeContato" name="numeroContato[]">
                </div>
                <div class="col-4">
                    <label for="estadoContato">Estado</label>
                    <input class="form-control" type="text" id="estadoContato" name="estadoContato[]">
                </div>
                <button type="button" class="btn btn-secondary" id="add-campo"> + </button>
            </div>
            <input type="hidden" id="codigo_contato" name="codigo_contato">
        </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="limpar()">Fechar</button>
            <button type="button" class="btn btn-success" onclick="salvar()">Salvar</button>
        </div>
        </div>
    </div>
</div>

<script>

    var cont = 1;
    $('#add-campo').click(function () {
        cont++;
        $('#formulario').append('<div class="" id="campo' + cont + '">' +
                                    '<div class="row">'+
                                    '<div class="col-4">'+
                                        '<label for="cepContato">CEP</label>'+
                                        '<input class="form-control" type="text" id="cepContato[]" name="cepContato[]">'+
                                    '</div>'+
                                    '<div class="col-8">'+
                                        '<label for="enderecoContato">Endereço</label>'+
                                        '<input class="form-control" type="text" id="enderecoContato[]" name="enderecoContato[]">'+
                                    '</div>'+
                                    '<div class="col-4">'+
                                        '<label for="numeroContato">Número</label>'+
                                        '<input class="form-control" type="text" id="numeroContato[]" name="numeroContato[]">'+
                                    '</div>'+
                                    '<div class="col-8">'+
                                        '<label for="bairroContato">Bairro</label>'+
                                        '<input class="form-control" type="text" id="bairroContato" name="bairroContato[]">'+
                                    '</div>'+
                                    '<div class="col-8">'+
                                        '<label for="cidadeContato">Cidade</label>'+
                                        '<input class="form-control" type="text" id="cidadeContato[]" name="numeroContato[]">'+
                                    '</div>'+
                                    '<div class="col-4">'+
                                        '<label for="estadoContato">Estado</label>'+
                                        '<input class="form-control" type="text" id="estadoContato[]" name="estadoContato[]">'+
                                    '</div>'+
                                    '<button type="button" id="' + cont + '" class="btn btn-secondary btn-apagar"> - </button>'+
                                    '</div>'+
                                '</div>');
    });

    $('form').on('click', '.btn-apagar', function () {
        var button_id = $(this).attr("id");
        $('#campo' + button_id + '').remove();
    });

    function limpar(){
        document.getElementById("formulario_dados_contato").reset();
        for(var i = 0; i <= cont; i++)
        {
            $('#campo' + i + '').remove();
        }
    }

    function editar(e){
        var contato = '';
        $.ajax({
                type: 'POST',
                datType: 'json',
                url:  'busca_contato.php',
                data: {
                    id: e,
                    obterContato: 'obterContato'
                },
                success: function(data)
                {
                    contato = JSON.parse(data);
                    $("#nomeContato").val(contato[0]['nome']);
                    $("#apelidoContato").val(contato[0]['apelido']);
                    $("#enderecoContato").val(contato[0]['endereco']);
                    $("#codigo_contato").val(contato[0]['id_contato']);
                }
            }); 
    }

    function salvar(){
        var contato = '';
        $.ajax({
                type: 'POST',
                url:  'busca_contato.php',
                data: {
                    salvar: 'salvar',
                    data: $("#formulario_dados_contato").serialize()
                },
                success: function(data)
                {
                    $('.modal').modal('hide'); 
                    //document.location.reload(true);
                }
            }); 
    }

    function autocomplete(inp, arr) {
        var currentFocus;
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(a);
            for (i = 0; i < arr.length; i++) {
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                b = document.createElement("DIV");
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                b.addEventListener("click", function(e) {
                    inp.value = this.getElementsByTagName("input")[0].value;
                    closeAllLists();
                });
                a.appendChild(b);
                }
            }
        });
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                currentFocus++;
                addActive(x);
            } else if (e.keyCode == 38) { //up
                currentFocus--;
                addActive(x);
            } else if (e.keyCode == 13) {
                e.preventDefault();
                if (currentFocus > -1) {
                if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            if (!x) return false;
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
            }
        }
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    $(document).ready(function() {
        $('#pesquisa').keyup(function()
        {
            $.ajax({
                type: 'POST',
                datType: 'json',
                url:  'busca_contato.php',
                data: {
                    nome: $("#pesquisa").val(),
                    pesquisa: 'pesquisa'
                },
                success: function(data)
                {
                    var contatos = JSON.parse(data);
                    autocomplete(document.getElementById("pesquisa"), contatos);
                    $('#part').html(data);
                }
            }); 
        });

        function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#enderecoContato").val("");
                $("#bairroContato").val("");
                $("#cidadeContato").val("");
                $("#estadoContato").val("");
            }
        
        //Quando o campo cep perde o foco.
        $("#cepContato").blur(function() {

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if(validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#enderecoContato").val("...");
                    $("#bairroContato").val("...");
                    $("#cidadeContato").val("...");
                    $("#estadoContato").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#enderecoContato").val(dados.logradouro);
                            $("#bairroContato").val(dados.bairro);
                            $("#cidadeContato").val(dados.localidade);
                            $("#estadoContato").val(dados.uf);
                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    });


</script>

<?php 
 
include_once('src/Includes/footer.php'); 

?>