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
 $id_historia = filter_input(INPUT_GET, 'id_historia', FILTER_SANITIZE_NUMBER_INT);

 // Receber o id do infografico
 $id_info = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


 if (empty($id_historia)) {
   $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Nenhum registro Encontrado com essa identificação!</div>";
 }

$query_hist = "SELECT hist.id_historia,
                          hist.id_info, 
                          hist.titulo_hist,
                          hist.data_hist,
                          hist.desc_hist, 
                          hist.ref_hist,
                          info.nome AS nome_info 
                  FROM historia AS hist
                  INNER JOIN infograficos AS info ON info.id = hist.id_info
                  WHERE hist.id_historia = $id_historia LIMIT 1";
$result_hist = $conn->prepare($query_hist);
$result_hist->execute();

if(($result_hist) AND ($result_hist->rowCount() != 0)) {
    $row_hist = $result_hist->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($row_hist);
    // echo "</pre>";
    extract($row_hist);
}else{
  $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Registro não Encontrado!</div>";
}

?>
<title>Editar História</title>
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
          <h1>Editar Historia | </h1>        
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
          $query_up_info =  "UPDATE historia SET id_info=:id_info, titulo_hist=:titulo_hist, data_hist=:data_hist, desc_hist=:desc_hist, ref_hist=:ref_hist, modifeld=NOW() WHERE id_historia=:id_historia";
          $edit_info = $conn->prepare($query_up_info);
          $edit_info->bindParam(':id_info', $id_info, PDO::PARAM_INT);
          $edit_info->bindParam(':titulo_hist', $dados['titulo_hist'], PDO::PARAM_STR);
          $edit_info->bindParam(':data_hist', $dados['data_hist'], PDO::PARAM_STR);
          $edit_info->bindParam(':desc_hist', $dados['desc_hist'], PDO::PARAM_STR);
          $edit_info->bindParam(':ref_hist', $dados['ref_hist'], PDO::PARAM_STR);
          $edit_info->bindParam(':id_historia', $id_historia, PDO::PARAM_INT);
          if($edit_info->execute()){
            $_SESSION['msg_historia'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Editado!</div></div>";
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
      <input type="text" class="form-control" name="titulo_hist" id="titulo_hist" placeholder="Título da História" autocomplete="off" value="<?php 
      if(isset($dados['titulo_hist'])){
        echo $dados['titulo_hist'];
      }elseif (isset($row_hist['titulo_hist'])){ 
        echo $row_hist['titulo_hist']; 
      } 
      ?>" required>
    </div>
    <div class="mb-3">
      <label for="conteudo">Data História:</label>
      <input type="text" class="form-control" name="data_hist" id="data_hist" placeholder="Data História" autocomplete="off" value="<?php 
      if(isset($dados['data_hist'])){
        echo $dados['data_hist'];
      }elseif (isset($row_hist['data_hist'])){ 
        echo $row_hist['data_hist']; 
      } 
      ?>" required>
    </div>
    <div class="mb-3">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" name="desc_hist" id="desc_hist" placeholder="Descrição" autocomplete="off" required><?php 
      if(isset($dados['desc_hist'])){
        echo $dados['desc_hist'];
      }elseif (isset($row_hist['desc_hist'])){ 
        echo $row_hist['desc_hist']; 
      } 
      ?></textarea>
    </div>
    <div class="mb-3">
      <label for="conteudo">Referência:</label>
      <input type="text" class="form-control" name="ref_hist" id="ref_hist" placeholder="Referência" autocomplete="off" value="<?php 
      if(isset($dados['ref_hist'])){
        echo $dados['ref_hist'];
      }elseif (isset($row_hist['ref_hist'])){ 
        echo $row_hist['ref_hist']; 
      } 
      ?>" required>
    </div>

 
  <input class="btn btn-success" type="submit" value="Editar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>