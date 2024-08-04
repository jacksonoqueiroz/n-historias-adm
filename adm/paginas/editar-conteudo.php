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

// Receber o id do conteúdo
 $id_conteudo = filter_input(INPUT_GET, 'id_conteudo', FILTER_SANITIZE_NUMBER_INT);

 // Receber o id do infografico
 $id_info = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


 if (empty($id_conteudo)) {
   $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Nenhum registro Encontrado com essa identificação!</div>";
 }

$query_cont = "SELECT cont.id_conteudo,
                          cont.id_info, 
                          cont.titulo,
                          cont.conteudo, 
                          cont.referencia,
                          info.nome AS nome_info 
                  FROM conteudo AS cont
                  INNER JOIN infograficos AS info ON info.id = cont.id_info
                  WHERE cont.id_conteudo = $id_conteudo LIMIT 1";
$result_cont = $conn->prepare($query_cont);
$result_cont->execute();

if(($result_cont) AND ($result_cont->rowCount() != 0)) {
    $row_cont = $result_cont->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($row_cont);
    // echo "</pre>";
    extract($row_cont);
}else{
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Registro não Encontrado!</div>";
}

?>
<title>Editar Conteúdo</title>
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
          <h1>Editar Conteúdo | </h1>        
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
          $query_up_info =  "UPDATE conteudo SET id_info=:id_info, titulo=:titulo, conteudo=:conteudo, referencia=:referencia, modifeld=NOW() WHERE id_conteudo=:id_conteudo";
          $edit_info = $conn->prepare($query_up_info);
          $edit_info->bindParam(':id_info', $id_info, PDO::PARAM_INT);
          $edit_info->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
          $edit_info->bindParam(':conteudo', $dados['conteudo'], PDO::PARAM_STR);
          $edit_info->bindParam(':referencia', $dados['referencia'], PDO::PARAM_STR);
          $edit_info->bindParam(':id_conteudo', $id_conteudo, PDO::PARAM_INT);
          if($edit_info->execute()){
            $_SESSION['msg_conteudo'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Editado!</div></div>";
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
      <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título do Infográfico" autocomplete="off" value="<?php 
      if(isset($dados['titulo'])){
        echo $dados['titulo'];
      }elseif (isset($row_cont['titulo'])){ 
        echo $row_cont['titulo']; 
      } 
      ?>" required>
    </div>
    <div class="mb-3">
      <label for="conteudo">Conteúdo:</label>
      <textarea class="form-control" name="conteudo" id="conteudo" placeholder="Conteúdo" autocomplete="off" required><?php 
      if(isset($dados['conteudo'])){
        echo $dados['conteudo'];
      }elseif (isset($row_cont['conteudo'])){ 
        echo $row_cont['conteudo']; 
      } 
      ?></textarea>
    </div>
    <div class="form-group">
      <label for="titulo">Referência:</label>
      <input type="text" class="form-control" name="referencia" id="referencia" placeholder="Referência" autocomplete="off" value="<?php 
      if(isset($dados['referencia'])){
        echo $dados['referencia'];
      }elseif (isset($row_cont['referencia'])){ 
        echo $row_cont['referencia']; 
      } 
      ?>" required>
    </div>

 
  <input class="btn btn-success" type="submit" value="Editar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>