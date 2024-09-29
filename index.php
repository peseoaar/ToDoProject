<?php
include 'conectar.php';
global $conn;
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nome = mysqli_escape_string($conn, $_POST["nome"]);
    $senha = mysqli_escape_string($conn, $_POST["senha"]);

    $sql = "SELECT * from usuarios where nome='$nome' and senha='$senha'";
    $result = $conn->query($sql);

    # caso o usuario seja encontrado
    if ($result->num_rows > 0){
        $user = $result->fetch_assoc(); # fetch_assoc retorna um array com as infos da query(busca)
        if ($user['nome'] == $nome && $user['senha'] == $senha){
            $_SESSION['nome_usuario'] = $nome; # salva o nome de usuario em uma sessao global

            header('location: site.php');
            exit();
        }
    } else {
        echo "usuario nao encontrado";
    }

    $conn->close();
}
?>
