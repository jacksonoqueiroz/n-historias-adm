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
 $id_obra = filter_input(INPUT_GET, 'id_obra', FILTER_SANITIZE_NUMBER_INT);

 // Receber o id do infografico
 $id_info = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


 if (empty($id_obra)) {
   $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Nenhum registro Encontrado com essa identificação!</div>";
 }

$query_obra = "SELECT obra.id_obra,
                          obra.id_info, 
                          obra.titulo_obra,
                          obra.desc_obra, 
                          info.nome AS nome_info 
                  FROM obra AS obra
                  INNER JOIN infograficos AS info ON info.id = obra.id_info
                  WHERE obra.id_obra = $id_obra LIMIT 1";
$result_obra = $conn->prepare($query_obra);
$result_obra->execute();

if(($result_obra) AND ($result_obra->rowCount() != 0)) {
    $row_obra = $result_obra->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($row_obra);
    // echo "</pre>";
    extract($row_obra);
}else{
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Registro não Encontrado!</div>";
}

?>
<title>Editar Obra</title>
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
          <h1>Editar Obra | </h1>        
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
          $query_up_info =  "UPDATE obra SET id_info=:id_info, titulo_obra=:titulo_obra, desc_obra=:desc_obra, modifield=NOW() WHERE id_obra=:id_obra";
          $edit_info = $conn->prepare($query_up_info);
          $edit_info->bindParam(':id_info', $id_info, PDO::PARAM_INT);
          $edit_info->bindParam(':titulo_obra', $dados['titulo_obra'], PDO::PARAM_STR);
          $edit_info->bindParam(':desc_obra', $dados['desc_obra'], PDO::PARAM_STR);
          $edit_info->bindParam(':id_obra', $id_obra, PDO::PARAM_INT);
          if($edit_info->execute()){
            $_SESSION['msg_obra'] = "<ddiv class='alert alert-success id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Editado!</div></div>";
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
      <input type="text" class="form-control" name="titulo_obra" id="titulo_obra" placeholder="Título da Obra" autocomplete="off" value="<?php 
      if(isset($dados['titulo_obra'])){
        echo $dados['titulo_obra'];
      }elseif (isset($row_obra['titulo_obra'])){ 
        echo $row_obra['titulo_obra']; 
      } 
      ?>" required>
    </div>    
    <div class="mb-3">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" name="desc_obra" id="desc_obra" placeholder="Descrição" autocomplete="off" required><?php 
      if(isset($dados['desc_obra'])){
        echo $dados['desc_obra'];
      }elseif (isset($row_obra['desc_obra'])){ 
        echo $row_obra['desc_obra']; 
      } 
      ?></textarea>
    </div>

 
  <input class="btn btn-success" type="submit" value="Editar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>