<?php
session_start();
ob_start();
include_once 'conexao.php';
?>

<?php 
// Incluir o arquivo com menu
include_once './include/menu.php';
//include_once './include/head.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário realizar o login para acessar a página!</div>";
    header("Location: index.php");
}
?>
    <!-- title -->
    <title>Slider Alunos</title>

    
<link href="./css/slider.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- favicon -->
    <!-- <link href="img/favicon.png" type="image/png" rel="icon"> -->

    </head>



<body>

    

    <?php
      $query_alunos = "SELECT id, nome_aluno, telefone, foto_aluno FROM alunos ORDER BY id DESC";
      $result_alunos = $conn->prepare($query_alunos);
      $result_alunos->execute();
      ?>
    
    <!-- SLIDER ALUNOS-->   
    <h2>Alunos</h2>

    

    <div class="slider js-slider">



        <?php

                      while($row_aluno = $result_alunos->fetch(PDO::FETCH_ASSOC)){
                        /*echo "<pre>";
                        var_dump($row_aluno);
                        echo "</pre>";*/
                        extract($row_aluno);
                        ?>                   
     <div class="card">
     <?php

                                  if ((!empty($foto_aluno)) AND (file_exists("images/alunos/$id/$foto_aluno"))) {
                                      ?>               
                                            <img class="alunos" src="images/alunos/<?php echo $row_aluno['id'] ?>/<?php echo $foto_aluno ?>" />
                                            <?php
                                  }else{
                                      ?>
                                           <img class="alunos" style="margin-top: 20px; margin-bottom: 40px;" src="images/perfil/semfoto.png">
                                      <?php
                                  }
                              ?>
                <h4 class="title"><?php echo $nome_aluno ?></h4>
                
                <a class="button">Frequência</a>

        </div>

                    <?php

                  }
                ?>
    </div>
    
    <!--FIM SLIDER ALUNOS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js" integrity="sha512-h9kKZlwV1xrIcr2LwAPZhjlkx+x62mNwuQK5PAu9d3D+JXMNlGx8akZbqpXvp0vA54rz+DrqYVrzUGDMhwKmwQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js" integrity="sha512-fDGBclS3HUysEBIKooKWFDEWWORoA20n60OwY7OSYgxGEew9s7NgDaPkj7gqQcVXnASPvZAiFW8DiytstdlGtQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="js/script.js"></script>
    
 

 