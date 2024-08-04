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
<title>Inserir Obras</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Inserir Obras</h1>
 </div>
    <?php
      //RECEBER DADOS DO FORMULÁRIO
      $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);      

      //VERIFICA SE CLICOU NO BOTÃO CADASTRAR
      if (!empty($dados['salvar'])) {

        //Todas letras maiúscula para o titulo
        $strl = strtolower($dados['titulo']);

        $img = "obra-" . preg_replace('<\W+>', "-", $strl) . ".png";
        // echo "<pre>";
        // var_dump($dados);
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($arquivo);
        // echo "</pre>";

        //CRIAR A QUERY CADASTRAR NO BANCO DE DADOS
        $query_obra = "INSERT INTO obra (id_info, titulo_obra, desc_obra, img_obra) VALUES (:id_info, :titulo_obra, :desc_obra, :img_obra)";
        $cad_obra = $conn->prepare($query_obra);
        $cad_obra->bindParam(':id_info', $dados['infografico'], PDO::PARAM_STR);
        $cad_obra->bindParam(':titulo_obra', $dados['titulo'], PDO::PARAM_STR);
        $cad_obra->bindParam(':desc_obra', $dados['descricao'], PDO::PARAM_STR);
        $cad_obra->bindParam(':img_obra', $img, PDO::PARAM_STR);
        $cad_obra->execute();

        //VERIFICA SE FOI CADASTRADO
        if ($cad_obra->rowCount()) {
            
            //RECUPERAR ID INFOGRAFICO
            $id_info = $dados['infografico'];
            
            //DIRETÓRIO ONDE O ARQUIVO SERÁ SALVO
            $diretorio = "../assets/infograficos/$id_info/";

            // RECEBER OS ARQUIVOS DO FORMULÁRIO
            $arquivo = $_FILES['img_obra'];

            // LER O ARRAY DE ARQUIVOS
            for($cont = 0; $cont < count($arquivo['name']); $cont++){

              // RECUPERAR O NOME DA IMAGEM
              $nome_arquivo = $arquivo['name'][$cont];

              // CRIAR O ENDEREÇO DE DESTINO DAS IMAGENS
              $destino = $diretorio . $arquivo['name'][$cont];

              // ACESSA O IF QUANDO REALIZAR O UPLOAD CORRETAMENTE
              if(move_uploaded_file($arquivo['tmp_name'][$cont], $destino)){
                $query_imagem = "INSERT INTO imagens (nome_imagem, id_info) VALUES (:nome_imagem, :id_info)";
                    $cad_imagem = $conn->prepare($query_imagem);
                    $cad_imagem->bindParam(':nome_imagem', $nome_arquivo);
                    $cad_imagem->bindParam(':id_info', $id_info);
                    
                    if ($cad_imagem->execute()) {
                        $_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";;
                    } else {
                        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Imagem não cadastrada com sucesso!</p>";
                    }
                  
              }else{
                echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'>Erro: Não foi possivel cadastrar!</div>";
              }
            }
        }
      }

    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
   

    ?>

    <form name="cad_usuario" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
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
    <label for="titulo">Título obra:</label>
    <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título da obra" autocomplete="off" autofocus required>
  </div>
    <div class="form-group">
      <label for="descricao">Descrição:</label>
      <input type="text-area" class="form-control" name="descricao" id="descricao" placeholder="Descrição da Obra ou feitos" autocomplete="off" required>
    </div>    
  <div class="form-group">
    <label for="upload">Imagem:</label>
    <input type="file" class="form-control-file" name="img_obra[]" multiple="multiple" id="img_obra">
  </div>
  
  <input class="btn btn-success" type="submit" value="Cadastrar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>