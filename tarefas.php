<?php
include 'conectar.php';
session_start();
global $conn;

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['tarefa'])){
        $tarefa = $_POST['tarefa'];
    } else {
        echo "A tarefa nao foi definida corretamente";
    }

    $usuario = $_SESSION['nome_usuario'];

    $sql = "SELECT id_usuario FROM usuarios WHERE nome = '$usuario'";
    $result = $conn->query($sql);

    $sql = intval($sql);

    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $id_usuario = $row["id_usuario"];

        $sql = "INSERT INTO todos (user_id, tarefa) values ('$id_usuario', '$tarefa')";
        $result = $conn->query($sql);
    }
    header('Location: site.php');
}
?>