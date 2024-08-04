<?php
session_start();
ob_start();
include_once 'conexao.php';

// Receber o id do infografico
 $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// echo "<pre>";
//var_dump($id);
// echo "</pre>";

// Incluir o arquivo com menu
include_once './include/menu.php';
include_once './include/head.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário realizar o login para acessar a página!</div>";
    header("Location: index.php");
}

?>
<?php


    // Recuperar o infografico por titulo
    $query_infos = "SELECT info.id AS id_info, 
                          info.nome,
                          info.titulo,
                          info.descricao, 
                          info.id_categorias, 
                          info.thumb, 
                          info.capa,
                          info.logo,
                          info.link,
                          cat.nome AS categoria 
                  FROM infograficos AS info
                  INNER JOIN categorias AS cat ON cat.id=info.id_categorias
                  WHERE info.id=:id";
    $result_infos = $conn->prepare($query_infos);
    $result_infos->bindParam(':id', $id);
    $result_infos->execute();

    // Acessa o IF quando encontrar algum registro no bd
    if(($result_infos) AND ($result_infos->rowCount() !=0 )){
        while ($row_info = $result_infos->fetch(PDO::FETCH_ASSOC)) {
          // echo "<pre>";
          // var_dump($row_info);
          // echo "</pre>";

            extract($row_info);
  ?>
 <title><?php echo mb_convert_case($nome, MB_CASE_TITLE, 'UTF-8'); ?> | <?php echo mb_convert_case($categoria, MB_CASE_TITLE, 'UTF-8');  ?>  </title>
 <link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página Cadastro!";


?>
<main role="main" class="container">
  <div class="jumbotron">
    <div class="row">
      <div class="col-8">
          <h3>Titulo: <?php echo $titulo . ' | ' . $categoria ?></h3>
      </div>
      <div class="col-2">
          <?php echo "<a class='btn btn-lg btn-warning' href='". URL ."editar-infografico?id_infografico=$id_info'>Editar</a>"; ?>
        </div>      
    </div>
  <hr>
  <?php

  if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

  ?>
   <!-- TOPO DA PÁGINA ADM -->

   <!-- CAPA -->
        <div class="row">
          <div class="col-5">
            <div class="row">
              <div class="col-2">          
                <h3>Capa:</h3>
              </div>
              <div class="col-8 titulo-adm">
                <?php echo $capa; ?>          
              </div>
            </div>      
          </div>
        </div>       
  <div class="col-6 img-adm">
      <img src="../assets/infograficos/<?php echo $id . '/' .   $capa ?>">
    <div class="edit-img">
      <a href="<?php echo 'editar-capa?id_info='. $id_info; ?>" class="btn btn-outline-warning btn-sm">
        <i class='fa fa-edit' style="font-size: 25px;"></i>
      </a>
    </div>
  </div>
  <hr>

  <!-- LOGO -->
        <div class="row">
          <div class="col-5">
            <div class="row">
              <div class="col-2">          
                <h3>Logo:</h3>
              </div>
              <div class="col-8 titulo-adm">
                <?php echo $logo; ?>          
              </div>
            </div>      
          </div>
        </div>       
  <div class="col-6 img-logo">
    <img src="../assets/infograficos/<?php echo $id . '/' .   $logo ?>">
    <div class="edit-img">
      <a href="<?php echo 'editar-logo?id_info='. $id_info; ?>" class="btn btn-outline-warning btn-sm">
        <i class='fa fa-edit' style="font-size: 25px;"></i>
      </a>
    </div>
  </div>
<hr>
  <!-- THUMB -->
        <div class="row">
          <div class="col-5">
            <div class="row">
              <div class="col-2">          
                <h3>Thumb:</h3>
              </div>
              <div class="col-8 titulo-adm">
                <?php echo $thumb; ?>          
              </div>
            </div>      
          </div>
        </div>       
  <div class="col-6 img-adm">
    <img src="../assets/infograficos/<?php echo $id . '/' .   $thumb ?>">
    <div class="edit-img">
      <a href="<?php echo 'editar-thumb?id_info='. $id_info; ?>" class="btn btn-outline-warning btn-sm">
        <i class='fa fa-edit' style="font-size: 25px;"></i>
      </a>
    </div>
  </div>
<hr>
  <!-- DESCRIÇÃO -->

    <div class="row">
      <div class="col-2">
        <h3>Descrição:</h3>
      </div>
      <div class="col-8">
        <p><?php echo $descricao ?></p>
      </div>
    </div>
    <hr>

    <?php
        }
    }else{
      $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Não tem registro!/div>";
    }

  ?>
    
    
    <!---------------CONTEUDO --->

  <div>
    <h2>Conteúdo</h2>
    <hr>
  </div>

    <?php

      if (isset($_SESSION['msg_conteudo'])) {
        echo $_SESSION['msg_conteudo'];
        unset($_SESSION['msg_conteudo']);
    }


    // Recuperar o infografico por titulo
    $query_conteudo = "SELECT id_conteudo, id_info, titulo, conteudo, referencia FROM conteudo
                  WHERE id_info= $id";
    $result_conteudo = $conn->prepare($query_conteudo);
    $result_conteudo->execute();

    // Acessa o IF quando encontrar algum registro no bd
    if(($result_conteudo) AND ($result_conteudo->rowCount() !=0 )){
        while ($row_conteudo = $result_conteudo->fetch(PDO::FETCH_ASSOC)) {
          // echo "<pre>";
          // // var_dump($row_conteudo);
          // echo "</pre>";
          extract($row_conteudo);
      ?>

    <div class="row">
      <div class="col-2">
        <h3>Título:</h3>
      </div>
    <hr>
      <div class="col-8">
        <p><?php echo $titulo ?></p>
        <input type="hidden" name="<?php echo $id_conteudo ?>">
      </div>
      <div class="col-2">
          <?php echo "<a class='btn btn-lg btn-warning' href='". URL ."editar-conteudo?id_conteudo=$id_conteudo'>Editar</a>"; ?>
        </div>
    </div>
<hr>
    <div class="row">
      <div class="col-2">
        <h3>Descrição:</h3>
      </div>
      <div class="col-8">
        <p><?php echo $conteudo ?></p>
      </div>
    </div>
<hr>
    <div class="row">
      <div class="col-2">
        <h3>Referência:</h3>
      </div>
      <div class="col-8">
        <p><?php echo $referencia ?></p>
      </div>
    </div>
<hr>
<?php
  }
    }else{
      $_SESSION['msg_conteudo'] = "<div class='alert alert-danger' id='msg_conteudo' role='alert'>Ainda não há registro de conteúdo!</div>";
    }

  ?>

    <!---------- HISTÓRIA ----------------->
    <div>
        <h2>História</h2>
      <hr>
      </div>

    <?php

      if (isset($_SESSION['msg_historia'])) {
        echo $_SESSION['msg_historia'];
        unset($_SESSION['msg_historia']);
    }


    // Recuperar o infografico por titulo
    $query_historia = "SELECT id_historia, id_info, titulo_hist, data_hist, desc_hist, ref_hist FROM historia
                  WHERE id_info= $id";
    $result_historia = $conn->prepare($query_historia);
    $result_historia->execute();

    // Acessa o IF quando encontrar algum registro no bd
    if(($result_historia) AND ($result_historia->rowCount() !=0 )){
        while ($row_historia = $result_historia->fetch(PDO::FETCH_ASSOC)) {
          // echo "<pre>";
          // // var_dump($row_conteudo);
          // echo "</pre>";
          extract($row_historia);
      ?>
      
       <div class="row">
        <div class="col-2">
          <h3>Título:</h3>
        </div>
        <div class="col-8">
          <p><?php echo $titulo_hist ?></p>
          <input type="hidden" name="<?php echo $id_historia ?>">
        </div>
        <div class="col-2">
          <?php echo "<a class='btn btn-lg btn-warning' href='". URL ."editar-historias?id_historia=$id_historia'>Editar</a>"; ?>
        </div>
      </div>
        <hr>
      <div class="row">
        <div class="col-2">
          <h3>Data:</h3>
        </div>
        <hr>
        <div class="col-8 titulo-adm">
          <p><?php echo $data_hist ?></p>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-2">
          <h3>Descrição:</h3>
        </div>
        <div class="col-8">
          <p><?php echo $desc_hist ?></p>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-2">
          <h3>Referência:</h3>
        </div>
        <div class="col-8">
          <p><?php echo $ref_hist ?></p>
        </div>
               
      </div>
     
      <hr>
    <?php
      }
        }else{
          $_SESSION['msg_historia'] = "<div class='alert alert-danger' id='msg_historia' role='alert'>Ainda não houve registro de história!</div>";
        }

      ?>
      
      <!---------------- OBRAS------------------>
        <div>
        <h2>Obras</h2>
      <hr>
      </div>

      <?php

      if (isset($_SESSION['msg_obra'])) {
        echo $_SESSION['msg_obra'];
        unset($_SESSION['msg_obra']);
    }

    // Recuperar o infografico por titulo
    $query_obra = "SELECT id_obra, id_info, titulo_obra, desc_obra, img_obra FROM obra
                  WHERE id_info= $id";
    $result_obra = $conn->prepare($query_obra);
    $result_obra->execute();

    // Acessa o IF quando encontrar algum registro no bd
    if(($result_obra) AND ($result_obra->rowCount() !=0 )){
        while ($row_obra = $result_obra->fetch(PDO::FETCH_ASSOC)) {
          // echo "<pre>";
          // // var_dump($row_conteudo);
          // echo "</pre>";
          extract($row_obra);
      ?>
      <br><br>
      <div class="row">
        <div class="col-2">
          <h3>Título:</h3>
        </div>
        <div class="col-8">
          <p><?php echo $titulo_obra ?></p>
          <input type="hidden" name="<?php echo $id_obra ?>">
        </div>
        <div class="col-2">
          <?php echo "<a class='btn btn-lg btn-warning' href='". URL ."editar-obra?id_obra=$id_obra'>Editar</a>"; ?>
        </div>        
      </div>
      <br>
      <div class="row">
      <div class="col-2">
        <h3>Descrição:</h3>
      </div>
      <div class="col-8">
        <p><?php echo $desc_obra ?></p>
      </div>
    </div>
<hr>
      <div class="row">
            <div class="row">
              <div class="col-2">          
                <h3>Imagem:</h3>
              </div>
              <div class="col-8 titulo-obra">
                <?php echo $img_obra; ?>          
              </div>
            </div> 
        </div>

  <div class="col-6 img-adm">
    <img src="../assets/infograficos/<?php echo $id . '/' .   $img_obra ?>">
    <div class="edit-img">
      <a href="<?php echo 'img-obra?id_obra='. $id_obra; ?>" class="btn btn-outline-warning btn-sm">
        <i class='fa fa-edit' style="font-size: 25px;"></i>
      </a>
    </div>
  </div>
  <hr>


       <?php
      }
        }else{
          $_SESSION['msg_obra'] = "<div class='alert alert-danger' id='msg_obra' role='alert'>Ainda não hove registro de obras!</div>";
        }

      ?>


      <!---------------- Gráficos -------------------------------->
        <div>
        <h2>Gráficos</h2>
      <hr>
      </div>

      <?php

      if (isset($_SESSION['msg_grafico'])) {
        echo $_SESSION['msg_grafico'];
        unset($_SESSION['msg_grafico']);
    }

    // Recuperar o infografico por titulo
    $query_grafico = "SELECT id_grafico, id_info, titulo, img_info FROM graficos
                  WHERE id_info= $id";
    $result_grafico = $conn->prepare($query_grafico);
    $result_grafico->execute();

    // Acessa o IF quando encontrar algum registro no bd
    if(($result_grafico) AND ($result_grafico->rowCount() !=0 )){
        while ($row_grafico = $result_grafico->fetch(PDO::FETCH_ASSOC)) {
          // echo "<pre>";
          // // var_dump($row_grafico);
          // echo "</pre>";
          extract($row_grafico);
      ?>
      <br><br>
      <div class="row">
        <div class="col-2">
          <h3>Título:</h3>
        </div>
        <div class="col-8">
          <p><?php echo $titulo ?></p>
          <input type="hidden" name="<?php echo $id_info ?>">
        </div>
        <div class="col-2">
          <?php echo "<a class='btn btn-lg btn-warning' href='". URL ."editar-grafico?id_grafico=$id_grafico'>Editar</a>"; ?>
        </div>        
      </div>
      <br>
     
<hr>
      <div class="row">
            <div class="row">
              <div class="col-2">          
                <h3>Imagem:</h3>
              </div>
              <div class="col-8 titulo-obra">
                <?php echo $img_info; ?>          
              </div>
            </div> 
        </div>

  <div class="col-6 img-adm">
    <img src="../assets/infograficos/<?php echo $id . '/' .   $img_info ?>">
    <div class="edit-img">
      <a href="<?php echo 'img-grafico?id_grafico='. $id_grafico; ?>" class="btn btn-outline-warning btn-sm">
        <i class='fa fa-edit' style="font-size: 25px;"></i>
      </a>
    </div>
  </div>
  <hr>


       <?php
      }
        }else{
          $_SESSION['msg_grafico'] = "<div class='alert alert-danger' id='msg_grafico' role='alert'>Ainda não hove registro de gráfico!</div>";
        }

      ?>


  </div>
</main>