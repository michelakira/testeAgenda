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
    $contato = new Contato($_POST['pesquisa']);
    $resultContato = $contato->pegarContatoId($contato->pegarNome());

    
    $log_contato->insereLog($resultContato[0]->id_contato);
}
else
{
    $principaisContatos = '';
    $principaisContatos = $log_contato->obterPrincipaisContatos();
}

?>



<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand">Contatos</a>
        <form class="d-flex" method="POST">
            <button class="btn btn-outline-success adicionar_contato"><i class="fa fa-plus"></i></button>
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
                        for($i = 0; $i < count($principaisContatos); $i++)
                        {
                            echo '<a href="" class="contatos_principais"><div class="col-12 contatos_principais_div">'.$principaisContatos[$i]->nome."</div></a>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>

</nav>

<script>
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
                url:  'src/Pesquisas/busca_contato.php',
                data: {
                    nome: $("#pesquisa").val()
                },
                success: function(data)
                {
                    var contatos = JSON.parse(data);
                    autocomplete(document.getElementById("pesquisa"), contatos);
                    $('#part').html(data);
                }
            }); 
        });
    });


</script>

<script>

    
</script>

<?php 
 
include_once('src/Includes/footer.php'); 

?>