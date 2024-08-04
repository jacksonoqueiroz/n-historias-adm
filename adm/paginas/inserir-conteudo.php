
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
?>
<title>Inserir Conteúdo</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Inserir Conteúdo</h1>
 </div>
    <?php
      //RECEBER DADOS DO FORMULÁRIO
      $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);      

      //VERIFICA SE CLICOU NO BOTÃO CADASTRAR
      if (!empty($dados['salvar'])) {

            
        // echo "<pre>";
        // var_dump($dados);
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($arquivo);
        // echo "</pre>";

        //CRIAR A QUERY CADASTRAR NO BANCO DE DADOS
        $query_conteudo = "INSERT INTO conteudo (id_info, titulo, conteudo, referencia) VALUES (:id_info, :titulo, :conteudo, :referencia)";
        $cad_conteudo = $conn->prepare($query_conteudo);
        $cad_conteudo->bindParam(':id_info', $dados['infografico'], PDO::PARAM_STR);
        $cad_conteudo->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
        $cad_conteudo->bindParam(':conteudo', $dados['conteudo'], PDO::PARAM_STR);
        $cad_conteudo->bindParam(':referencia', $dados['referencia'], PDO::PARAM_STR);    
        
        $cad_conteudo->execute();

        //VERIFICA SE FOI CADASTRADO
        if ($cad_conteudo->rowCount()) {
            
            $_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";;
                    
              }else{
                echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Erro: Não foi possivel cadastrar!</div>";
              }
            
     }

    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
   

    ?>

    <form name="cad_usuario" method="POST" action="" enctype="multipart/form-data">
      <div class="form-group">
      <span id="msgAlertaCategoria"></span>
      <label for="id_info">Infográficos:</label>
     <!-- <input type="text" class="form-control" name="id_categorias" id="id_categorias" placeholder="Id categorias" required>-->

     <select name="infografico" id="infografico" class="form-control" required>
      <?php
      $query = $conn->query("SELECT id, nome FROM infograficos ORDER BY nome ASC");
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <option value="">Selecione...</option>
       <?php
      
      foreach ($result as $option) {
      ?>
        <option value="<?php echo $option['id']; ?>"><?php echo $option['nome']; ?></option>
      <?php
      }
      ?>
     </select>
    </div>
  <div class="form-group">
    <label for="nome">Título:</label>
    <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título" autocomplete="off" autofocus required>
  </div>
    <div class="form-group">
      <label for="descricao">Conteúdo:</label>
      <input type="text-area" class="form-control" name="conteudo" id="conteudo" placeholder="Conteúdo do Infográfico" autocomplete="off" required>
    </div>
    <div class="form-group">
      <label for="descricao">Referência:</label>
      <input type="text-area" class="form-control" name="referencia" id="referencia" placeholder="Rerefência" autocomplete="off" required>
    </div>   
  <input class="btn btn-success" type="submit" value="Cadastrar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>