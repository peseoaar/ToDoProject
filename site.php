<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>To-do</title>
    <style>
        ul{list-style-type: none; line-height: 2;}
    </style>
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

        <!-- formulario para add tarefas -->
        <form action="add_tarefas.php" method="post">
            <input type="text" name="tarefa" id="id_tarefa" placeholder="Digite sua tarefa aqui"/><br><br>
            <input type="submit" value="Adicionar">
        </form>

        <!-- formulario para dar check(V) nas tarefas -->
        <?php
            # buscando o id do usuario na tabela usuarios para poder buscar os itens realcionados a este usuario na tabela todos
            $sql = "SELECT id_usuario FROM usuarios WHERE nome = '$nome'";
            $result_id = $conn->query($sql);

            if ($result_id->num_rows > 0){
                $result_id = $result_id->fetch_assoc();
                $id_usuario = $result_id['id_usuario'];
            }
        ?>

        <form action="add_tarefas.php" method="post">
        <?php
            # buscando itens relacionados ao id
            $sql = "SELECT * FROM todos WHERE user_id = '$id_usuario'";
            $result_itens = $conn->query($sql);

            #checkbox e itens
            if ($result_itens->num_rows > 0) {
                while ($item = $result_itens->fetch_assoc()) {
                    ?>
                        <div>
                            <label>
                                <input type="checkbox" name="itens_selecionados[]" value="<?php echo $item['id']; ?>" <?php if($item['completado'] == 1) echo 'checked'?>/>
                                <?php echo htmlspecialchars($item['tarefa']); ?>
                                <input type="button" value="x">
                            </label>
                        </div>
                    <?php
                }
            }
        ?>
            <input type="submit" value="Salvar">
        </form>
    </div>
</body>
</html>