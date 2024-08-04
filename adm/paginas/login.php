<?php
session_start();
ob_start();
include_once 'conexao.php';
?>

    <link href="css/signin.css" rel="stylesheet">
 <title>Login</title>
</head>
<body class="text-center">
<?php

//echo "Página Login!";

    //Exemplo criptografar a senha
    //echo password_hash(1234, PASSWORD_DEFAULT);

    ?>
    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendLogin'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id, nome, usuario, senha_usuario 
                        FROM usuarios 
                        WHERE usuario =:usuario  
                        LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
        $result_usuario->execute();

        if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_usuario);
            if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])){
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['nome'] = $row_usuario['nome'];
                header("Location: home");
            }else{
                $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Usuário ou senha inválida!</div>";
            }
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro: Usuário ou senha inválida!</div>";
        }

        
    }

    
    ?>

    <form class="form-signin" method="POST" action="">
        <h1 class="h3 mb-3 font-weight-normal">Login</h1>
        <?php
        if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    
        }  
        ?>
        <input type="email" id="inputEmail" class="form-control" name="usuario" placeholder="Digite o usuário" value="<?php if(isset($dados['usuario'])){ echo $dados['usuario']; } ?>"><br>

        <label for="inputPassword" class="sr-only">Senha</label>
        <input type="password" id="inputPassword" class="form-control" name="senha_usuario" placeholder="Digite a senha" value="<?php if(isset($dados['senha_usuario'])){ echo $dados['senha_usuario']; } ?>">

        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Acessar" name="SendLogin">
    </form>

    
   