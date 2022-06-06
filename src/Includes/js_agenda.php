<script>
    //Remover Contato
    function removerContato(contato, nome){

        var resultado = confirm("Deseja excluir o contato: " + nome + " ?");
        if (resultado == true) {
            $.ajax({
                type: 'POST',
                datType: 'json',
                url:  'busca_contato.php',
                data: {
                    remover: 'remover_contato',
                    contato: contato
                },
                success: function(data)
                {
                    if(data == '0')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Erro ao excluir contato</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                    else if(data == '1')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Exclusão de contato com sucesso</br></p>');
                            $('.toast').toast('show');
                            $('#cadastroContato').modal('hide');
                        }); 
                        limpar();
                        todosContatos();
                        todosPrincipaisContatos();
                    }

                        
                }
            });
        }



    }

    //Importar Contatos Vexpenses
    function importarContatosVexpenses(){
        $.ajax({
                type: 'POST',
                datType: 'json',
                url:  'busca_contato.php',
                data: {
                    integracao: 'team-members'
                },
                success: function(data)
                {
                    if(data == '0')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Erro ao importar dados da API VExpenses</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                    else if(data == '1')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Contatos importados da API VExpenses com sucesso</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                }
        });
    }

    //carrega contatos no load da pagina
    $(window).on('load', function () {
        todosContatos();
        todosPrincipaisContatos();
    });

    //função para retornar todos os contatos
    function todosContatos(){
        $.ajax({
                type: 'POST',
                datType: 'json',
                url:  'busca_contato.php',
                data: {
                    pesquisa: 'obterContatos'
                },
                success: function(data)
                {
                    var contatos = JSON.parse(data);
                    $('#todos_contatos').html('');
                    var letra = '';
                    var conteudoContato = '';
                    for(var i = 0; i <= contatos.length; i++)
                    {
                        
                        cabecarioLetra = ''+
                            '<a href="#" data-toggle="collapse" data-target="#contatos_'+letra+'" aria-expanded="true" class="col-12 contatos_letra_alfabeto_link">'+
                                '<div id="contatos_div_'+letra+'" class="col-12 contatos_letra_alfabeto text-center">'+letra+'</div>'+
                            '</a>';

                        if(i === contatos.length)
                        {
                            $('#todos_contatos').append(cabecarioLetra);
                            $('#todos_contatos').append('<div class="collapse show" id="contatos_'+letra+'">'+conteudoContato+'</div>');
                            break;
                        }
                        if(letra != contatos[i].nome.charAt(0).toUpperCase() && i>0)
                        {
                            
                            $('#todos_contatos').append(cabecarioLetra);
                            $('#todos_contatos').append('<div class="collapse show" id="contatos_'+letra+'">'+conteudoContato+'</div>');
                            $('#todos_contatos').append('</div>');
                            conteudoContato = '';

                        }

                        conteudoContato += '<a href="#" data-bs-toggle="modal" data-bs-target="#cadastroContato" class="contatos_principais" onclick="editar('+contatos[i].id_contato+')"><div class="col-12 contatos_principais_div"><span class="alfabeto_contato">'+contatos[i].nome.charAt(0).toUpperCase()+'</span> '+contatos[i].nome+'</div></a>';

                        letra = contatos[i].nome.charAt(0).toUpperCase();
                            
                        
                    }
                }
        });
    }

    //função para retornar todos os contatos
    function todosPrincipaisContatos(){
        $.ajax({
                type: 'POST',
                datType: 'json',
                url:  'busca_contato.php',
                data: {
                    pesquisa: 'obterPrincipaisContatos'
                },
                success: function(data)
                {
                    var contatos = JSON.parse(data);
                    $('#principais_contatos').html('');
                    for(var i = 0; i < contatos.length; i++)
                    {
                    
                        $('#principais_contatos').append('<a href="#" data-bs-toggle="modal" data-bs-target="#cadastroContato" class="contatos_principais" onclick="editar('+contatos[i].id_contato+')"><div class="col-12 contatos_principais_div"><span class="alfabeto_contato">'+contatos[i].nome.charAt(0).toUpperCase()+'</span> '+contatos[i].nome+"</div></a>");
                        
                    }
                }
        });
    }

    //função para aceitar somente numeros no input
    function apenasNumeros(e, t, tipo) {
        if(t.value.length==9 && tipo == 'tel')
        {
            return false;
        }
        if(t.value.length==2 && tipo == 'ddd')
        {
            return false;
        }
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            } else if (e) {
                var charCode = e.which;
            } else {
                return true;
            }
            if (charCode > 47 && charCode < 58)
                return true;
            else
                return false;
        } catch (err) {
            alert(err.Description);
        }
    }

    //função para aceitar somente letras no input
    function apenasLetras(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            } else if (e) {
                var charCode = e.which;
            } else {
                return true;
            }
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32)
                return true;
            else
                return false;
        } catch (err) {
            alert(err.Description);
        }
    }

    //adicionar novos telefones
    var cont_tel = 1;
    $('#add-campo-telefone').click(function () {
        cont_tel++;
        $('#formulario_telefone').append('<div class="" id="campo_telefone' + cont_tel + '">' +
                                            '<div class="row">'+
                                                '<div class="col-3">'+
                                                    '<label for="dddContato">DDD</label>'+
                                                    '<input class="form-control" type="number" id="dddContato'+cont_tel+'" maxlength="2" name="dddContato[]" onkeypress="return apenasNumeros(event,this,\'ddd\');">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="telefoneContato">Telefone</label>'+
                                                    '<input class="form-control" type="number" id="telefoneContato'+cont_tel+'" maxlength="9" name="telefoneContato[]" onkeypress="return apenasNumeros(event,this,\'tel\');">'+
                                                '</div>'+
                                                '<div class="col-1">'+
                                                    '<button type="button" id="' + cont_tel + '" class="btn btn-secondary btn-apagar-telefone"> - </button>'+
                                                '</div>' +
                                            '</div>'+
                                        '</div>');
    });

    //remover novos telefones
    $('form').on('click', '.btn-apagar-telefone', function () {
        var button_id = $(this).attr("id");
        $('#campo_telefone' + button_id + '').remove();
    });

    //adicionar novos endereços
    var cont = 1;
    $('#add-campo').click(function () {
        cont++;
        $('#formulario').append('<div class="" id="campo' + cont + '">' +
                                    '<hr>'+
                                    '<div class="row">'+
                                        '<div class="col-11">'+
                                            '<div class="row">'+
                                                '<div class="col-4">'+
                                                    '<label for="cepContato">CEP</label>'+
                                                    '<input class="form-control" type="text" id="cepContato'+cont+'" name="cepContato[]" maxlength="8" onblur="carregaCep(this,' + cont + ')">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="enderecoContato">Endereço</label>'+
                                                    '<input class="form-control" type="text" id="enderecoContato'+cont+'" name="enderecoContato[]" maxlength="150" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-4">'+
                                                    '<label for="numeroContato">Número</label>'+
                                                    '<input class="form-control" type="text" id="numeroContato'+cont+'" name="numeroContato[]" maxlength="15" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="bairroContato">Bairro</label>'+
                                                    '<input class="form-control" type="text" id="bairroContato'+cont+'" name="bairroContato[]" maxlength="100" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="cidadeContato">Cidade</label>'+
                                                    '<input class="form-control" type="text" id="cidadeContato'+cont+'" name="cidadeContato[]" maxlength="100" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-4">'+
                                                    '<label for="estadoContato">Estado</label>'+
                                                    '<input class="form-control" type="text" id="estadoContato'+cont+'" name="estadoContato[]" maxlength="2" readonly="readonly">'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-1">'+
                                            '<button type="button" id="' + cont + '" class="btn btn-secondary btn-apagar"> - </button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>');
        $(document).ready(function() {
            $("#cepContato"+ cont).mask("99.999-999");
        });
    });

    //remover novos endereços
    $('form').on('click', '.btn-apagar', function () {
        var button_id = $(this).attr("id");
        $('#campo' + button_id + '').remove();
    });

    //faz a limpeza do formulario para adicionar novos contatos
    function limpar(){
        document.getElementById("formulario_dados_contato").reset();
        $('#errors').html('');
        todosPrincipaisContatos();
        $("#codigo_contato").val('');
        for(var i = 0; i <= cont; i++)
        {
            $('#campo' + i + '').remove();
        }
        for(var i = 0; i <= cont_tel; i++)
        {
            $('#campo_telefone' + i + '').remove();
        }
        $('#botao_remover').remove();
    }

    //Deixa todos o campos do endereço menos o CEP somente para leitura
    function readyOnlyEnderecoOff(numero)
    {
        $("#enderecoContato"+numero).attr("readonly", true); 
        $("#bairroContato"+numero).attr("readonly", true); 
        $("#cidadeContato"+numero).attr("readonly", true); 
        $("#estadoContato"+numero).attr("readonly", true); 
        $("#numeroContato"+numero).attr("readonly", true); 
    }

    //Atribui os dados do contato para edição
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
                    
                    $("#nomeContato").val(contato[0][0]['nome']);
                    $("#apelidoContato").val(contato[0][0]['apelido']);
                    $("#codigo_contato").val(contato[0][0]['id_contato']);

                    for(var i = 0; i < contato[1].length; i++)
                    {
                        cont++;
                        if(i == 0)
                        {
                            $("#cepContato").val(contato[1][i]['cep']);
                            $("#cepContato").mask("99.999-999", contato[1][i]['cep']);
                            $("#enderecoContato1").val(contato[1][i]['endereco']);
                            $("#numeroContato1").val(contato[1][i]['numero']);
                            $("#bairroContato1").val(contato[1][i]['bairro']);
                            $("#cidadeContato1").val(contato[1][i]['cidade']);
                            $("#estadoContato1").val(contato[1][i]['estado']);
                        }
                        else
                        {
                            $('#formulario').append('<div class="" id="campo' + i + '">' +
                                    '<hr>'+
                                    '<div class="row">'+
                                        '<div class="col-11">'+
                                            '<div class="row">'+
                                                '<div class="col-4">'+
                                                    '<label for="cepContato">CEP</label>'+
                                                    '<input class="form-control" type="text" id="cepContato'+(i+1)+'" name="cepContato[]" maxlength="8" onblur="carregaCep(this,' + (i+1) + ')" value="'+contato[1][i]['cep']+'">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="enderecoContato">Endereço</label>'+
                                                    '<input class="form-control" type="text" id="enderecoContato'+(i+1)+'" name="enderecoContato[]" maxlength="150" value="'+contato[1][i]['endereco']+'" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-4">'+
                                                    '<label for="numeroContato">Número</label>'+
                                                    '<input class="form-control" type="text" id="numeroContato'+(i+1)+'" name="numeroContato[]" maxlength="15" value="'+contato[1][i]['numero']+'" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="bairroContato">Bairro</label>'+
                                                    '<input class="form-control" type="text" id="bairroContato'+(i+1)+'" name="bairroContato[]" maxlength="100" value="'+contato[1][i]['bairro']+'" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="cidadeContato">Cidade</label>'+
                                                    '<input class="form-control" type="text" id="cidadeContato'+(i+1)+'" name="cidadeContato[]" maxlength="100" value="'+contato[1][i]['cidade']+'" readonly="readonly">'+
                                                '</div>'+
                                                '<div class="col-4">'+
                                                    '<label for="estadoContato">Estado</label>'+
                                                    '<input class="form-control" type="text" id="estadoContato'+(i+1)+'" name="estadoContato[]" maxlength="2" value="'+contato[1][i]['estado']+'" readonly="readonly">'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-1">'+
                                            '<button type="button" id="' + i + '" class="btn btn-secondary btn-apagar" onclick="removerEndereco('+contato[1][i]['id_endereco']+')"> - </button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>');
                                
                                $("#cepContato"+(i+1)).val(contato[1][i]['cep']);
                                $("#cepContato"+(i+1)).mask("99.999-999", contato[1][i]['cep']);
                        }
                    }

                    for(var i = 0; i < contato[2].length; i++)
                    {
                        cont_tel++;
                        if(i == 0)
                        {
                            $("#dddContato").val(contato[2][i]['ddd']);
                            $("#telefoneContato").val(contato[2][i]['numero']);
                        }
                        else
                        {
                            $('#formulario_telefone').append('<div class="" id="campo_telefone' + i + '">' +
                                            '<div class="row">'+
                                                '<div class="col-3">'+
                                                    '<label for="dddContato">DDD</label>'+
                                                    '<input class="form-control" type="number" id="dddContato'+i+'" name="dddContato[]" maxlength="2" value="'+contato[2][i]['ddd']+'" onkeypress="return apenasNumeros(event,this,\'ddd\');">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<label for="telefoneContato">Telefone</label>'+
                                                    '<input class="form-control" type="number" id="telefoneContato'+i+'" name="telefoneContato[]" maxlength="9" value="'+contato[2][i]['numero']+'" onkeypress="return apenasNumeros(event,this,\'tel\');">'+
                                                '</div>'+
                                                '<div class="col-1">'+
                                                    '<button type="button" id="' + i + '" class="btn btn-secondary btn-apagar-telefone" onclick="removerTelefone('+contato[2][i]['id_telefone']+')"> - </button>'+
                                                '</div>' +
                                            '</div>'+
                                        '</div>');
                        }
                    }

                    $('#botoes_formulario_contato').append('<button id="botao_remover" type="button" class="btn btn-danger" onclick="removerContato('+contato[0][0]['id_contato']+', \''+contato[0][0]['nome']+'\')"><span style="font-weight:bold;">Remover</span></button>');
                }
            }); 
    }

    //Remove endereço do contato
    function removerEndereco(endereco){

        $.ajax({
                type: 'POST',
                url:  'busca_contato.php',
                data: {
                    removerEndereco: 'removerEndereco',
                    endereco: endereco
                },
                success: function(data)
                {
                    if(data == '0')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Erro ao remover endereço contato</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                    else if(data == '1')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Endereço de contato removido</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                }
            }); 
    }

    //Remove telefone do contato
    function removerTelefone(telefone){

        $.ajax({
                type: 'POST',
                url:  'busca_contato.php',
                data: {
                    removerTelefone: 'removerTelefone',
                    telefone: telefone
                },
                success: function(data)
                {
                    if(data == '0')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Erro ao remover telefone contato</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                    else if(data == '1')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Telefone de contato removido</br></p>');
                            $('.toast').toast('show');
                        }); 
                    }
                }
            }); 
        }

    //Salvar ou alterar os contatatos
    function salvar(){

        $errors = false;
        $('#errors').html('');
        if($("#nomeContato").val() == '')
        {
            $('#errors').append('<span class="errors_form">Nome deve ser preenchido</br></span>');
            $errors = true;
        }
        if($("#nomeContato").val().length > 150)
        {
            $('#errors').append('<span class="errors_form">Nome maior que 150 caracteres</br></span>');
            $errors = true;
        }
        if($("#apelidoContato").val().length > 150)
        {
            $('#errors').append('<span class="errors_form">Apelido maior que 150 caracteres</br></span>');
            $errors = true;
        }


        if($errors == true)
        {
            return;
        }

        $.ajax({
                type: 'POST',
                dataType: 'json',
                url:  'busca_contato.php',
                data: {
                    salvar: 'salvar',
                    data: $("#formulario_dados_contato").serialize()
                },
                success: function(data)
                {                    
                    if(typeof data.errors !== 'undefined')
                    {
                        for(var i = 0; i < data.errors.length; i++)
                        {
                            $('#errors').append(data.errors[i]);
                        }
                    }
                    else if(data == '0')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Erro ao salvar contato</br></p>');
                            $('.toast').toast('show');
                            $('#cadastroContato').modal('hide');
                        });
                        limpar();
                        todosContatos();
                    }
                    else if(data == '1')
                    {
                        $(document).ready(function(){
                            $('#toast_msg').html('<p>Contato salvo com sucesso</br></p>');
                            $('.toast').toast('show');
                            $('#cadastroContato').modal('hide');
                            $("#cepContato").mask("99.999-999");
                        });
                        limpar();
                        todosContatos();
                        todosPrincipaisContatos();
                    }
                    
                }
            }); 
    }

    //Função autocomplete do campos pesquisar
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

        $("#cadastroContato").modal({
            show: false,
            backdrop: 'static'
        });
        
        //atribui mascara de CEP
        $("#cepContato").mask("99.999-999");

        //Realiza a pesquisa e retorna os pesquisados
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
                }
            }); 
        });
    });

    // Limpa valores do formulário de cep.
    function limpa_formulário_cep(numero) {
                $("#enderecoContato"+numero).val("");
                $("#bairroContato"+numero).val("");
                $("#numeroContato"+numero).val("");
                $("#cidadeContato"+numero).val("");
                $("#estadoContato"+numero).val("");
            }
    
    //atribui onready aos formulario de cep
    function readyOnlyEndereco(numero)
    {
        $("#enderecoContato"+numero).attr("readonly", true); 
        $("#bairroContato"+numero).attr("readonly", true); 
        $("#cidadeContato"+numero).attr("readonly", true); 
        $("#estadoContato"+numero).attr("readonly", true); 
        $("#numeroContato"+numero).attr("readonly", true); 
    }

    //Quando o campo cep perde o foco.
    function carregaCep(CEP, numero) {

        //Nova variável "cep" somente com dígitos.
        var cep = CEP.value.replace(/\D/g, '');
        $('#errors').html('');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#enderecoContato"+numero).val("...");
                $("#bairroContato"+numero).val("...");
                $("#cidadeContato"+numero).val("...");
                $("#numeroContato"+numero).val("...");
                $("#estadoContato"+numero).val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#enderecoContato"+numero).val(dados.logradouro);
                        $("#bairroContato"+numero).val(dados.bairro);
                        $("#cidadeContato"+numero).val(dados.localidade);
                        $("#estadoContato"+numero).val(dados.uf);
                        $("#numeroContato"+numero).val('');
                        if(dados.logradouro == '')
                        {
                            $("#enderecoContato"+numero).attr("readonly", false); 
                        }
                        if(dados.bairro == '')
                        {
                            $("#bairroContato"+numero).attr("readonly", false); 
                        }
                        if(dados.localidade == '')
                        {
                            $("#cidadeContato"+numero).attr("readonly", false); 
                        }
                        if(dados.uf == '')
                        {
                            $("#estadoContato"+numero).attr("readonly", false); 
                        }
                        $("#numeroContato"+numero).attr("readonly", false); 
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep(numero);
                        readyOnlyEndereco(numero);
                        $('#errors').append('<span class="errors_form">CEP('+cep+') não encontrado</br></span>');
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep(numero);
                readyOnlyEndereco(numero);
                $('#errors').append('<span class="errors_form">CEP no formato inválido</br></span>');
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep(numero);
        }
    };



</script>