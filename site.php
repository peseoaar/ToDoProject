<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>To-do</title>
</head>
<body>
    <div style="text-align: center; margin: 0 auto;">
        <?php
        include 'conectar.php';
        session_start();
        global $conn;

        if (isset($_SESSION['nome_usuario'])) {
            $nome = $_SESSION['nome_usuario'];

        } else {
            echo "Erro ao acessar a página";
            exit();
        }
        ?>

        <h1>To-do de <?php echo "$nome" ?></h1><br>
        <h2>Suas tarefas</h2>

        <form action="tarefas.php" method="post">
            <input type="text" name="tarefa" id="id_tarefa" placeholder="Digite sua tarefa aqui"><br><br>
            <input type="submit" value="Adicionar">
        </form>

        <?php
            $id_usuario = "SELECT id_usuario FROM usuarios WHERE nome = '$nome'";
            $result_id = $conn->query($id_usuario);

            if ($result_id->num_rows > 0){
                $result_id = $result_id->fetch_assoc();
                $id_usuario = $result_id['id_usuario'];
            }

            $tarefas = "SELECT * FROM todos WHERE user_id = '$id_usuario'";
            $result_tarefas = $conn->query($tarefas);

            if ($result_tarefas->num_rows > 0){
                echo "<ul>";
                while ($tarefas = $result_tarefas->fetch_assoc()){
                    echo "<li>" . htmlspecialchars($tarefas['tarefa']) . "</li>"; # funcao para converter os caracteres para o html consegui ler
                }
                echo "</ul>";
            }
        ?>

    </div>
</body>
</html>



