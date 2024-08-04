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
 $id_info = filter_input(INPUT_GET, 'id_infografico', FILTER_SANITIZE_NUMBER_INT);

 if (empty($id_info)) {
   $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Nenhum registro Encontrado com essa identificação!</div>";
 }

$query_info = "SELECT info.id AS id_info, 
                          info.nome,
                          info.titulo,
                          info.descricao, 
                          info.id_categorias,
                          cat.nome AS categoria 
                  FROM infograficos AS info
                  INNER JOIN categorias AS cat ON cat.id=info.id_categorias
                  WHERE info.id = $id_info LIMIT 1";
$result_info = $conn->prepare($query_info);
$result_info->execute();

if(($result_info) AND ($result_info->rowCount() != 0)) {
    $row_info = $result_info->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($row_info);
    // echo "</pre>";
    extract($row_info);
}else{
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Registro não Encontrado!</div>";
}

?>
<title>Editar Infográficos</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Editar Infografico</h1>

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
          $query_up_info =  "UPDATE infograficos SET nome=:nome, titulo=:titulo, descricao=:descricao, id_categorias=:id_categorias WHERE id=:id";
          $edit_info = $conn->prepare($query_up_info);
          $edit_info->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
          $edit_info->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
          $edit_info->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
          $edit_info->bindParam(':id_categorias', $dados['id_categorias'], PDO::PARAM_INT);
          $edit_info->bindParam(':id', $id_info, PDO::PARAM_INT);
          if($edit_info->execute()){
            $_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Editado!</div></div>";
            header("Location: detalhes?id=$id_info");
          }else {
            echo "<br><div class='alert alert-danger' role='alert'>Erro ao Editar o registro!</div>";
          }
        }
      }

    ?>


 </div>
    <form id="edit_info" name="edit_info" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nome">nome:</label>
    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" autocomplete="off" autofocus value="<?php 
      if(isset($dados['nome'])){
        echo $dados['nome'];
      }elseif(isset($row_info['nome'])){ 
        echo $row_info['nome']; 
      } 
      ?>" required>
  </div>
  <div class="form-group">
      <label for="titulo">Titulo:</label>
      <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título do Infográfico" autocomplete="off" value="<?php 
      if(isset($dados['titulo'])){
        echo $dados['titulo'];
      }elseif (isset($row_info['titulo'])){ 
        echo $row_info['titulo']; 
      } 
      ?>" required>
    </div>
    <div class="mb-3">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do Infográfico" autocomplete="off" required><?php 
      if(isset($dados['descricao'])){
        echo $dados['descricao'];
      }elseif (isset($row_info['descricao'])){ 
        echo $row_info['descricao']; 
      } 
      ?></textarea>
    </div>
    <div class="form-group">
      <span id="msgAlertaCategoria"></span>
      <label for="id_categorias">Categorias:</label>
     <!-- <input type="text" class="form-control" name="id_categorias" id="id_categorias" placeholder="Id categorias" required>-->

     <select name="id_categorias" id="id_categorias" class="form-control">
      <?php
      $query = $conn->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <option value="<?php 
      if(isset($dados['id_categorias'])){
        echo $dados['id_categorias'];
      }elseif (isset($row_info['id_categorias'])){ 
        echo $row_info['id_categorias']; } 
      ?>"><?php 
      if(isset($dados['categoria'])){
        echo $dados['categoria'];
      }elseif(isset($row_info['categoria'])){ 
        echo $row_info['categoria']; } 
      ?></option>
       <?php
      
      foreach ($result as $option) {
      ?>
        <option value="<?php
        if(isset($dados['categoria'])){
        echo $dados['categoria'];
      }elseif(isset($row_info['categoria'])) {
        echo $option['id'];} ?>"><?php echo $option['nome']; ?></option>
      
      <?php
      }
      ?>
     </select>
    </div>
 
  <input class="btn btn-success" type="submit" value="Editar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>