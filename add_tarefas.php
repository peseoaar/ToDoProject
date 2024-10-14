<?php
include 'conectar.php';
session_start();
global $conn;

# pegando a tarefa digitada
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['tarefa']) && !empty($_POST['tarefa'])) {
        $tarefa = $_POST['tarefa'];
    } else {
        echo "A tarefa nao foi definida corretamente";
    }

    $usuario = $_SESSION['nome_usuario'];

    # buscando id do usuario
    $sql = "SELECT id_usuario FROM usuarios WHERE nome = '$usuario'";
    $result = $conn->query($sql);

    $sql = intval($sql);


    # busca o id do usuario na tabela 'usuarios'
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $id_usuario = $row["id_usuario"];



        # insere em 'todos' o id do usuario e o nome da tarefa
        if (!empty(trim($tarefa))){
            $sql = "INSERT INTO todos (user_id, tarefa) values ('$id_usuario', '$tarefa')";
            $result = $conn->query($sql);
        }
    }


   # enviando para o servidor se foi feito ou nao a tarefa
                  # o itens[] no name da checkbox vai salvar em um array so os itens que foram marcados

    # marca como completado se foi marcado no formulario
    if (isset($_POST['itens_selecionados'])){
        $itens = $_POST['itens_selecionados'];
        foreach ($itens as $item){
            $sql = "UPDATE todos SET completado = 1 WHERE id = '$item'";
            $result = $conn->query($sql);
        }

        # buscando todos os id das tarefas e armazenando em um array
        $sql = "SELECT id FROM todos WHERE user_id = $id_usuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $id_itens[] = $row["id"];
            }
        }

        foreach ($id_itens as $id_item){
          if(!in_array($id_item, $itens)){    # verifica quais ids nao foram selecionados
              $sql = "UPDATE todos SET completado = 0 WHERE id = $id_item";
              $result = $conn->query($sql);
          }
        }
    }

    #excluindo item
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Prepara a consulta para excluir um item
        $sql = "DELETE FROM todos WHERE id = ?";
        $stmt = $conn->prepare($sql); # prepara para a consulta sql
        $stmt->bind_param("i", $id); # bind - deixando o parametro escondido para evitar sql_injeciton
        $stmt->execute();  # stmt e usado no lugar do result em casos de sql preparados

        if ($stmt->affected_rows > 0) {
            echo "sucesso";
        } else {
            echo "erro";
        }
        exit();
    }


    # editando item
    if (isset($_POST['novo_texto'], $_POST['id'])) {
        $novo_texto = $_POST['novo_texto'];
        $id = $_POST['id'];

        $sql = "UPDATE todos SET tarefa=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $novo_texto, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "sucesso";
        } else {
            echo "erro";
        }
        exit();
    }


    header('Location: site.php');
}
?>