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

// Receber o id_infografico da URL
$id_grafico = filter_input(INPUT_GET, 'id_grafico', FILTER_SANITIZE_NUMBER_INT);
// echo "<pre>";
// var_dump($id_obra);
// echo "</pre>";
//Acessa o IF quando não existe ID
if (empty($id_grafico)) {
    $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Registro não Encontrado!</div>";
}else{
  //Query para recuperar os dados do registro
  $query_info = "SELECT grafico.id_grafico, 
                        grafico.id_info, 
                        grafico.img_info,
                        info.id,
                        info.nome AS nome
                        FROM graficos AS grafico
                        INNER JOIN infograficos AS info ON info.id = grafico.id_info
                        WHERE id_grafico=:id_grafico LIMIT 1";
  $result_info = $conn->prepare($query_info);
  $result_info->bindParam(':id_grafico', $id_grafico, PDO::PARAM_INT);
  $result_info->execute();

  //Verificar se encontrou o registro no banco de dados
  if(($result_info) AND ($result_info->rowCount() != 0)){
    $row_info = $result_info->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($row_info);
    // echo "</pre>";
    extract($row_info);
  }else{
    $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Registro não Encontrado!</div>";
    // header("Location: detalhes?id=$id_info");
  }

}

?>
<title>Gráfico Imagem | <?php echo $nome ?> </title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Editar Imagem Gráfico | <?php echo $nome ?></h1>
 </div>

  <?php
  // Receber os dados do formulário
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  

  //Verificar se o botão foi clicado
  if (!empty($dados['salvar'])) {
    //Receber a imagem
    $arquivo = $_FILES['img_info'];
    // echo "<pre>";
    // var_dump($arquivo);
    // echo "</pre>";
    // Verificar está sendo enviado a imagem
    if ((isset($arquivo['name'])) AND (!empty($arquivo['name']))) {
      //Criar a Query  editar no banco de dados
      $query_edit_img = "UPDATE graficos SET img_info=:img_info, modifield=NOW() WHERE id_grafico=:id_grafico";
      $edit_img = $conn->prepare($query_edit_img);
      $edit_img->bindParam(':img_info', $arquivo['name'], PDO:: PARAM_STR);
      $edit_img->bindParam(':id_grafico', $id_grafico, PDO:: PARAM_INT);

      //Verificar se editou com sucesso
      if ($edit_img->execute()) {
        //Diretório onde o arquivo será salvo
        $diretorio = "../assets/infograficos/$id_info/";

        //Verifica se o diretório exite
        // if ((!file_exists($diretorio)) AND (!is_dir($diretorio)) {
        //   // Criar o diretorio
        //   mkdir($diretorio, 0755);
        // }

        //Upload do arquivo
        $nome_arquivo = $arquivo['name'];
        if (move_uploaded_file($arquivo['tmp_name'], $diretorio . $nome_arquivo)) {
          //Verifica se existe o nome da imagem no banco e a imagem salva no banco é diferente do nome da imagem que está sendo enviado
          if (((!empty($row_info['img_info'])) or ($row_info['img_info'] != null)) 
            AND ($row_info['img_info'] != $arquivo['name'])) {
            // Exclui a imagem
            $endereco_imagem = "../assets/infograficos/$id_info/" . $row_info['img_info'];
            if (file_exists($endereco_imagem)) {
              unlink($endereco_imagem);
            }
          }


          $_SESSION['msg_graficos'] = "<div class='alert alert-success' id='msg-success' role='alert'>Imagem editado!</div>";
          header("Location: detalhes?id=$id_info");
        }else{
          echo "<div class='alert alert-danger' id='msg-success' role='alert'>A imagem não foi editado!</div>";
        }

      }else{
        echo "<div class='alert alert-danger' id='msg-success' role='alert'>A imagem não foi editado!</div>";
      }

    }else{
      echo "<div class='alert alert-danger' id='msg-success' role='alert'>Selecione um arquivo!</div>";
    }
  }
  ?>
   

    <form name="editar_grafico" method="POST" action="" enctype="multipart/form-data">
      <div class="form-group">
        <label for="imagem">Imagem Gráficos:</label>
        <input type="file" class="form-control" name="img_info" id="img_info">
      </div>
      <input class="btn btn-success" type="submit" value="Editar" name="salvar">
    </form>
     
    </main>

    <script src="js/custom.js"></script>