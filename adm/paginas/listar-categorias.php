<?php
session_start();
ob_start();
include_once 'conexao.php';

// Incluir o arquivo com menu
include_once './include/menu.php';
include_once './include/head.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Necessário realizar o login para acessar a página!</div>";
    header("Location: index.php");
}

?>
 <title>Listar Categorias</title>
 <link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página Cadastro!";


?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Lista</h1>
  </div>

        <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Nome</th>
                <th scope="col">Categorias</th>
                <th scope="col">Ação</th>
              </tr>
            </thead>

            <tbody>
  <?php
    // Recuperar os infograficos do BD
    $query_cat = "SELECT id ,nome FROM categorias ORDER BY id ASC";
    $result_cat = $conn->prepare($query_cat);
    $result_cat->execute();

    // Acessa o IF quando encontrar algum infografico no banco
    if(($result_cat) AND ($result_cat->rowCount() !=0 )){
      while($row_cat = $result_cat->fetch(PDO::FETCH_ASSOC)){
        // echo "<pre>";
        // var_dump($row_info);
        // echo "</pre>";
         extract($row_cat);
        ?> 

              <tr>                
                <td><?php echo $nome ?></td>
                <td><?php echo $id ?></td>
                <td><a href="editar-categorias?id=<?php echo $id ?>">Editar | </a>
                  <a href="excluir?id=<?php echo $id ?>">Excluir</a></td>
              </tr>
             
        <?php

      }
    }else{
      $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Erro: Nenhum Registro encontrado!</div>";
    }
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
  ?>
   
            </tbody>
          </table>

</main>