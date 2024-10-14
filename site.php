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
    <script src="jquery-3.7.1.min.js"></script>
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

        <!-- formulario para add tarefas -->
        <form action="add_tarefas.php" method="post">
            <input type="text" name="tarefa" id="id_tarefa" placeholder="Digite sua tarefa aqui"/>
            <input type="submit" value="Adicionar"> <br><br>
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
            <ul>
        <?php
            # buscando itens relacionados ao id
            $sql = "SELECT * FROM todos WHERE user_id = '$id_usuario'";
            $result_itens = $conn->query($sql);

            #checkbox e itens
            if ($result_itens->num_rows > 0) {
                $data_id = 1;
                while ($item = $result_itens->fetch_assoc()) {
                    ?>
                        <div>
                            <label>
                                <li data-id="<?php echo $data_id; ?>"><input type="checkbox" name="itens_selecionados[]" value="<?php echo $item['id']; ?>"  <?php if($item['completado'] == 1) echo 'checked'?>/>
                                <?php echo htmlspecialchars($item['tarefa']); ?>
                                    <input type="button" class="editar" value=":">
                                    <input type="button" class="excluir" value="x" >
                                </li>

                            </label>
                        </div>
                    <?php
                    $data_id++;
                }
            }
        ?>
            <input type="submit" id="botao_enviar" value="Salvar">


        <!-- script jquery para apagar tarefa -->
            <script>
                $(document).ready(function() {
                    $("#botao_enviar").click(function(){
                        alert("Alteracoes salvas!")
                    });

                    $(".excluir").click(function(){
                        var id = $(this).parent().data("id");

                        // Pergunta se tem certeza que quer excluir
                        var confirmacao = confirm("Tem certeza que deseja excluir este item da lista?");
                        if (confirmacao) {
                            $.ajax({
                                url: "add_tarefas.php",
                                type: "POST",
                                data: { id: id }, // Corrigido o envio dos dados
                            });
                        } else {
                            alert('Exclusão cancelada!');
                        }
                    });
                });
            </script>


                <!-- script para editar tarefa -->
                <script>
                    $(document).ready(function() {
                        $(".editar").click(function(){
                            var item = $(this).parent();
                            var texto_atual = item.text().trim(); // Pegando o texto atual
                            item.html("<input type='text' class='novo_texto' value='" + texto_atual + "'/> <button class='salvar'>Salvar</button>");
                        });

                        $(document).on("click", ".salvar", function(){
                            var item = $(this).parent();
                            var novo_texto = item.find(".novo_texto").val();
                            var id = item.data("id");

                            $.ajax({
                               url: "add_tarefa.php",
                               type: "POST",
                               data: {novo_texto: novo_texto, id: id},
                               success: function(response) {
                                   item.html(novo_texto + " <input type='button' class='editar' value=':'>");
                               }
                            });
                        }
                    });
                </script>


                </ul>
        </form>
    </div>
</body>
</html>