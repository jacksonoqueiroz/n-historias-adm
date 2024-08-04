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
<title>Consultar</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<?php

//echo "Página Consultar!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Consultar</h1>
    <hr>
    <div class="row">
      <div class="col-sm">
        <p class="lead">Listar</p>
          <?php echo "<a class='btn btn-lg btn-primary' href='". URL ."listar-infograficos'>Lista Geral</a>"; ?>
      </div>
      <div class="col-sm">
        <p class="lead">Pesquisar</p>
          <?php echo "<a class='btn btn-lg btn-primary' href='". URL ."pesquisar-alunos'>Pesquisar</a>"; ?>
      </div>
      <div class="col-sm">
        <p class="lead">Detalhes</p>
          <?php echo "<a class='btn btn-lg btn-primary' href='". URL ."listar-infográficos'>Detalhes</a>"; ?>
      </div>
    
    </div>
    


  </div>
</main>