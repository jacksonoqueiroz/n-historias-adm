<?php
session_start();
ob_start();
include_once 'conexao.php';

// Incluir o arquivo com menu
include_once './include/menu.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário realizar o login para acessar a página!</div>";
    //"<p style='color: #ff0000'>Erro: Necessário realizar o login para acessar a página!</p>";
    header("Location: index.php");
}
?>
<title>Cadastrar Categorias</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Cadastrar Categorias</h1>
 </div>
    <?php
      //RECEBER DADOS DO FORMULÁRIO
      $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);      

      //VERIFICA SE CLICOU NO BOTÃO CADASTRAR
      if (!empty($dados['salvar'])) {

        // Todas letras maiúscula para o titulo
        $str = strtoupper($dados['nome']);
        
        // echo "<pre>";
        // var_dump($dados);
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($arquivo);
        // echo "</pre>";

        //CRIAR A QUERY CADASTRAR NO BANCO DE DADOS
        $query_categorias = "INSERT INTO categorias (nome) VALUES (:nome)";
        $cad_categorias = $conn->prepare($query_categorias);
        $cad_categorias->bindParam(':nome', $str, PDO::PARAM_STR);
        $cad_categorias->execute();

        //VERIFICA SE FOI CADASTRADO
        if ($cad_categorias->rowCount()) {
              $_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";
                      
                
              }else{
                echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Erro: Não foi possivel cadastrar!</div>";
              }
            }
     
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
   

    ?>

    <form name="cad_usuario" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nome">Nome da categoria:</label>
    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome da categoria" style="text-transform: uppercase;" autocomplete="off" autofocus required>
  </div>
  <input class="btn btn-success" type="submit" value="Cadastrar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>