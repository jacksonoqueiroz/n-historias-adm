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
 <title>Listar Infográficos</title>
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
                <th scope="col">Título</th>
                <th scope="col">Categorias</th>
                <th scope="col">Capa</th>
                <th scope="col">Thumbnail</th>
                <th scope="col">Ação</th>
              </tr>
            </thead>

            <tbody>
  <?php
    // Recuperar os infograficos do BD
    $query_infos = "SELECT info.id AS id_info,
                          info.titulo, 
                          info.nome, 
                          info.descricao, 
                          info.id_categorias, 
                          info.thumb, 
                          info.capa,
                          info.logo,
                          info.link,
                          cat.nome AS categoria 
                  FROM infograficos AS info
                  INNER JOIN categorias AS cat ON cat.id=info.id_categorias ORDER BY id_categorias ASC";
    $result_infos = $conn->prepare($query_infos);
    $result_infos->execute();

    // Acessa o IF quando encontrar algum infografico no banco
    if(($result_infos) AND ($result_infos->rowCount() !=0 )){
      while($row_info = $result_infos->fetch(PDO::FETCH_ASSOC)){
        // echo "<pre>";
        // var_dump($row_info);
        // echo "</pre>";
         extract($row_info);
        ?> 

              <tr>                
                <td><?php echo $nome ?></td>
                <td><?php echo $categoria ?></td>
                <td><?php echo $capa ?></td>
                <td><?php echo $thumb ?></td>
                <td><a href="detalhes?id=<?php echo $id_info ?>">Detalhes | </a>
                  <a href="infograficos?id=<?php echo $id_info ?>">Infográficos</a></td>
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