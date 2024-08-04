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
<title>Cadastro de Infográficos</title>
<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>
<body>
<?php

//echo "Página de contato!";

?>
<main role="main" class="container">
  <div class="jumbotron">
    <h1>Cadastro de Infográfico</h1>
 </div>
    <?php
      //RECEBER DADOS DO FORMULÁRIO
      $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);      

      //VERIFICA SE CLICOU NO BOTÃO CADASTRAR
      if (!empty($dados['salvar'])) {

        // Todas letras maiúscula para o titulo
        $str = strtoupper($dados['nome']);
        $strl = strtolower($dados['nome']);
        $plm = ucwords(strtolower($dados['nome']));
        // Letra minúscula para o nome para ser usando para o link
        $capa = "capa-" . preg_replace('<\W+>', "-", $strl) . ".png";
        $thumb = "thumb-" . preg_replace('<\W+>', "-", $strl) . ".png";
        $logo = "logo-" . preg_replace('<\W+>', "-", $strl) . ".png";
        //Criando um link automático
        $link = preg_replace('<\W+>', "-", $strl);
        
        // echo "<pre>";
        // var_dump($dados);
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($arquivo);
        // echo "</pre>";

        //CRIAR A QUERY CADASTRAR NO BANCO DE DADOS
        $query_info = "INSERT INTO infograficos (titulo, nome, descricao, id_categorias, thumb, capa, logo, link) VALUES (:titulo, :nome, :descricao, :id_categorias, :thumb, :capa, :logo, :link)";
        $cad_info = $conn->prepare($query_info);
        $cad_info->bindParam(':titulo', $plm, PDO::PARAM_STR);
        $cad_info->bindParam(':nome', $plm, PDO::PARAM_STR);
        $cad_info->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
        $cad_info->bindParam(':id_categorias', $dados['categorias'], PDO::PARAM_STR);
        $cad_info->bindParam(':thumb', $thumb, PDO::PARAM_STR);
        $cad_info->bindParam(':capa', $capa, PDO::PARAM_STR);
        $cad_info->bindParam(':logo', $logo, PDO::PARAM_STR);
        $cad_info->bindParam(':link', $link, PDO::PARAM_STR);
        $cad_info->execute();

        //VERIFICA SE FOI CADASTRADO
        if ($cad_info->rowCount()) {
            
            //RECUPERAR ULTIMO ID INSERIDO NO BANCO DE DADOS
            $ultimo_id = $conn->lastInsertId();
            
            //DIRETÓRIO ONDE O ARQUIVO SERÁ SALVO
            $diretorio = "../assets/infograficos/$ultimo_id/";

            //CRIAR O DIRETORIO
            mkdir($diretorio, 0755);

            // RECEBER OS ARQUIVOS DO FORMULÁRIO
            $arquivo = $_FILES['imagens'];

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
                    $cad_imagem->bindParam(':id_info', $ultimo_id);
                    
                    if ($cad_imagem->execute()) {
                        $_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:36px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";
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
    <label for="nome">Título:</label>
    <input type="text" class="form-control" name="nome" id="nome" placeholder="Título" autocomplete="off" autofocus required>
  </div>
    <div class="mb-3">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" name="descricao" id="descricao" placeholder="Descrição do Infográfico" autocomplete="off" required></textarea>
    </div>
    <div class="form-group">
      <span id="msgAlertaCategoria"></span>
      <label for="id_categorias">Categorias:</label>
     <!-- <input type="text" class="form-control" name="id_categorias" id="id_categorias" placeholder="Id categorias" required>-->

     <select name="categorias" id="categorias" class="form-control" required>
      <?php
      $query = $conn->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
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
    <label for="upload">Imagem:</label>
    <input type="file" class="form-control-file" name="imagens[]" multiple="multiple" id="imagens">
  </div>
  <!--<div class="form-group">
      <label for="link">Link:</label>
      <input type="text" class="form-control" name="link" id="link" placeholder="Link" autocomplete="off" required>
    </div>-->

  <input class="btn btn-success" type="submit" value="Cadastrar" name="salvar">
  
</form>
 
</main>

<script src="js/custom.js"></script>