<?php
session_start();

    echo "<h1>To-Do List</h1>";

    if (isset($_SESSION['nome_usuario'])){
        $nome = $_SESSION['nome_usuario'];
        echo "<h3>Bem-vindo " . $nome . "</h3>";
    } else {
        echo "Erro ao fazer login";
    }


?>