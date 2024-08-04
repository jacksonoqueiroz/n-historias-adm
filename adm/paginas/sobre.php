<?php
session_start();
ob_start();
include_once 'conexao.php';

// Incluir o arquivo com menu
include_once './include/menu.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Necessário realizar o login para acessar a página!</p>";
    header("Location: index.php");
}
?>
<title>Sobre</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<?php

//echo "Página sobre a empresa!";
?>

<main role="main" class="container">
  <div class="jumbotron">
    <h1>Sobre</h1>
    <p>Cadastro online sistema para cadastrar, consultar, editar, e deletar.</p>
  </div>
</main>