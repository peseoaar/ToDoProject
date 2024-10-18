<?php
include 'conectar.php';
session_start();
global $conn;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['tarefa']) && !empty($_POST['tarefa'])) {
        $tarefa = $_POST['tarefa'];
    } else {
        echo "A tarefa não foi definida corretamente";
    }

    $usuario = $_SESSION['nome_usuario'];

    # Buscando id do usuário
    $sql = "SELECT id_usuario FROM usuarios WHERE nome = '$usuario'";
    $result = $conn->query($sql);

    $sql = intval($sql);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_usuario = $row["id_usuario"];

        # Insere em 'todos' o id do usuário e o nome da tarefa
        if (!empty(trim($tarefa))) {
            $sql = "INSERT INTO todos (user_id, tarefa) VALUES ('$id_usuario', '$tarefa')";
            $result = $conn->query($sql);
        }
    }


    if (isset($_POST['itens_selecionados'])) {
        $itens = $_POST['itens_selecionados'];
        foreach ($itens as $item) {
            $sql = "UPDATE todos SET completado = 1 WHERE id = '$item'";
            $result = $conn->query($sql);
        }


        $sql = "SELECT id FROM todos WHERE user_id = $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_itens[] = $row["id"];
            }
        }

        foreach ($id_itens as $id_item) {
            if (!in_array($id_item, $itens)) {
                $sql = "UPDATE todos SET completado = 0 WHERE id = $id_item";
                $result = $conn->query($sql);
            }
        }
    }

    # Exclusão de item
    if (isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        // Prepara a consulta para excluir um item
        $sql = "DELETE FROM todos WHERE id = ?";
        $stmtDelete = $conn->prepare($sql); # variável separada para o delete
        $stmtDelete->bind_param("i", $id);   # Bind para esconder
        $stmtDelete->execute();

        if ($stmtDelete->affected_rows > 0) {
            echo "sucesso";
        } else {
            echo "erro";
        }
        exit();
    }

    # Editando item
    if (isset($_POST['id']) && isset($_POST['newText'])) {
        $id = intval($_POST['id']);
        $newText = trim($_POST['newText']);


        if ($id <= 0 || empty($newText)) {
            echo "Dados inválidos";
            exit();
        }


        $sql = "UPDATE todos SET tarefa = ? WHERE id = ?";
        $stmtEdit = $conn->prepare($sql);  // uma variável separada para o UPDATE
        $stmtEdit->bind_param("si", $newText, $id);  // Bind params: "s" para string e "i" para inteiro


        $stmtEdit->execute();


        if ($stmtEdit->affected_rows > 0) {
            echo "sucesso";
        } else {
            echo "Nenhuma linha foi atualizada.";
        }


        $stmtEdit->close();
        exit();
    }

    header('Location: site.php');
}
?>
