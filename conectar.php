<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "teste";

    //criar conexao
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn){
        die("Falha na Conexao: " . mysqli_connect_error());
    }

?>