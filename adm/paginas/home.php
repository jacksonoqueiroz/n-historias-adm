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
 <title>Home</title>
 <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<?php

//echo "Página home!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Gerênciar Infográficos</h1>
    <div class="row cadastro">
      
    </div>
  </div>
</main>