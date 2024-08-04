<?php
session_start();
ob_start();
unset($_SESSION['id'], $_SESSION['nome']);
$_SESSION['msg'] = "<div class='alert alert-success' role='alert'>Deslogado com sucesso!</div>";

header("Location: login");