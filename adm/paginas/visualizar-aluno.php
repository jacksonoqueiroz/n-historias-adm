<?php
session_start();
ob_start();
include_once 'conexao.php';

// Incluir o arquivo com menu
include_once './include/menu.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário realizar o login para acessar a página!</div>";
    header("Location: index.php");
}
?>
<title>Lista de Alunos</title>
</head>
<body>
<?php

//echo "Página Consultar!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Lista de Alunos</h1>
  </div>
    <?php
      $query_alunos = "SELECT id, nome_aluno, telefone, foto_aluno FROM alunos ORDER BY id DESC";
      $result_alunos = $conn->prepare($query_alunos);
      $result_alunos->execute();
      ?>

      <!-- LISTA EM TABELAS -->
      <!--<table class="table img-lista lista">
        <thead>
                  <tr>
                    <th scope="col">Foto</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Ações</th>
                  </tr>
                </thead>
                <tbody>-->
                 
      <?php

      while($row_aluno = $result_alunos->fetch(PDO::FETCH_ASSOC)){
        /*echo "<pre>";
        var_dump($row_aluno);
        echo "</pre>";*/
        extract($row_aluno);
        ?> 
              
                <!--<tr>
                      <?php

                          if ((!empty($foto_aluno)) AND (file_exists("images/alunos/$id/$foto_aluno"))) {
                              ?>
                                   <td><img class="img-lista" src="images/alunos/<?php echo $row_aluno['id'] ?>/<?php echo $foto_aluno ?>"></td>
                              <?php
                          }else{
                              ?>
                                   <td><img class="img-lista" src="images/perfil/semfoto.png"></td>
                              <?php
                          }
                      ?>                   
                    <td><?php echo $nome_aluno ?></td>
                    <td><?php echo $telefone ?></td>
                </tr> -->
            
                <?php

                  //}
                ?>
            

                
        
    <!--</tbody>
              </table>-->
              <!-- FIM LISTA EM TABELAS -->

              <!-- LISTA EM CARDS -->
          <div class="row sm-2">
              <div class="card-group">
                      <div class="card">
                        <a href="#">
                      <?php

                                  if ((!empty($foto_aluno)) AND (file_exists("images/alunos/$id/$foto_aluno"))) {
                                      ?>
                                      
                                          <img class="img-thumbnail img-aluno" src="images/alunos/<?php echo $row_aluno['id'] ?>/<?php echo $foto_aluno ?>"  alt="...">
                        <?php
                                  }else{
                                      ?>
                                           <img class="img-thumbnail card" style="padding: 15px 25px 80px; margin-bottom: 15px;" src="images/perfil/semfoto.png" width="200">
                                      <?php
                                  }
                              ?>
                              </a>
                 
                        <div class="card-body">
                          <p class="card-text"><?php echo $nome_aluno ?></p>
                        </div>
                      </div>
                      
                      <?php

              }
            ?>
              </div>
          
          </div>
              <!-- FIM DA LISTA EM CARDS -->
</main>