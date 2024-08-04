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

// Receber o id do obra
 $id_grafico = filter_input(INPUT_GET, 'id_grafico', FILTER_SANITIZE_NUMBER_INT);

 // Receber o id do infografico
 $id_info = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


 if (empty($id_grafico)) {
   $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Nenhum registro Encontrado com essa identificação!</div>";
 }

$query_grafico = "SELECT grafico.id_grafico,
                          grafico.id_info, 
                          grafico.titulo,
                          info.nome AS nome_info 
                  FROM graficos AS grafico
                  INNER JOIN infograficos AS info ON info.id = grafico.id_info
                  WHERE grafico.id_grafico = $id_grafico LIMIT 1";
$result_grafico = $conn->prepare($query_grafico);
$result_grafico->execute();

if(($result_grafico) AND ($result_grafico->rowCount() != 0)) {
    $row_grafico = $result_grafico->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($row_grafico);
    // echo "</pre>";
    extract($row_grafico);
}else{
  $_SESSION['msg_'] = "<div class='alert alert-danger' role='alert'>Erro: Registro não Encontrado!</div>";
}

?>
<title>Editar Gráficos</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <div class="row">
      <div class="col-4">
          <h1>Editar Gráficos | </h1>        
      </div>
      <div class="col-5">
          <h2 style="margin-top: 5px;"><?php echo $nome_info ?></h2>        
      </div>
    </div>

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
          $query_up_info =  "UPDATE graficos SET id_info=:id_info, titulo=:titulo,  modifield=NOW() WHERE id_grafico=:id_grafico";
          $edit_info = $conn->prepare($query_up_info);
          $edit_info->bindParam(':id_info', $id_info, PDO::PARAM_INT);
          $edit_info->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
          $edit_info->bindParam(':id_grafico', $id_grafico, PDO::PARAM_INT);
          if($edit_info->execute()){
            $_SESSION['msg_grafico'] = "<ddiv class='alert alert-success id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Editado!</div></div>";
            header("Location: detalhes?id=$id_info");
          }else {
            echo "<br><div class='alert alert-danger' role='alert'>Erro ao Editar o registro!</div>";
          }
        }
      }

    ?>


 </div>
    <form id="edit_cont" name="edit_cont" method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" id="<?php echo $id_info ?>">
  <div class="form-group">
      <label for="titulo">Titulo:</label>
      <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título Gráfico" autocomplete="off" value="<?php 
      if(isset($dados['titulo'])){
        echo $dados['titulo'];
      }elseif (isset($row_grafico['titulo'])){ 
        echo $row_grafico['titulo']; 
      } 
      ?>" required>
    </div>    
    
 
  <input class="btn btn-success" type="submit" value="Editar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>