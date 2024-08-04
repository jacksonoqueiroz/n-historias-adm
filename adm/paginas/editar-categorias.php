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

// Receber o id do infografico
 $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

 if (empty($id)) {
   $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Nenhum registro Encontrado com essa identificação!</div>";
 }

$query_cat = "SELECT id, nome FROM categorias WHERE id = $id LIMIT 1";
$result_cat = $conn->prepare($query_cat);
$result_cat->execute();

if(($result_cat) AND ($result_cat->rowCount() != 0)) {
    $row_cat = $result_cat->fetch(PDO::FETCH_ASSOC);
    echo "<pre>";
    var_dump($row_cat);
    echo "</pre>";
    extract($row_cat);
}else{
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Registro não Encontrado!</div>";
}

?>
<title>Editar Categorias</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Editar Categorias</h1>

    <?php

      //Receber os dados formulario
      $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      
      //Verifica se o botão ao salvar foi clicado
      if(!empty($dados['salvar'])){
        $empty_input = false;
        $dados = array_map('trim', $dados);
        if(in_array("", $dados)){
          $empty_input = true;
          echo "<br><div class='alert alert-danger' role='alert'>Preencha todos os campos!</div>";
        }

        if(!$empty_input){
          $query_up_cat =  "UPDATE categorias SET nome=:nome WHERE id=:id";
          $edit_cat = $conn->prepare($query_up_cat);
          $edit_cat->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
          $edit_cat->bindParam(':id', $id, PDO::PARAM_INT);
          if($edit_cat->execute()){
            echo "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro foi Editado!</div></div>";
            header("Location: listar-categorias");
          }else {
            echo "<br><div class='alert alert-danger' role='alert'>Erro ao Editar o registro!</div>";
          }
        }
      }

    ?>


 </div>
    <form id="edit_info" name="edit_info" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nome">Nome:</label>
    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" autocomplete="off" autofocus value="<?php 
      if(isset($dados['nome'])){
        echo $dados['nome'];
      }elseif(isset($row_cat['nome'])){ 
        echo $row_cat['nome']; 
      } 
      ?>" required>
  </div>
  
 
  <input class="btn btn-success" type="submit" value="Editar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>