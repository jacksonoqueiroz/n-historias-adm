<?php

//Incluir a conexão
include_once "./conexao.php";

// Receber os dados do Javascript
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//
id(!empty($dados['texto_pesquisar'])){
  $retorna = ['status' => true, 'dados' => $dados['texto_pesquisar']];
}else{
  $retorna = ['status' => false, 'msg' => "<p style='color: #f00;'>Erro: Nenhum registro encontrado!</p>"];

}



echo json_encode($retorna);