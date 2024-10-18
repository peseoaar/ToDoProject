<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ul {
            list-style-type: none;
            line-height: 2;
        }

        .tarefa_texto {
            display: inline-block;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            font-size: 16px;
            padding: 15px;
        }

        .btn-primary {
            font-size: 18px;
            padding: 12px;
            border-radius: 5px;
            background-color: #007BFF;
            border-color: #007BFF;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .card-title {
            color: #343a40;
        }

        .tarefa_texto {
            margin-right: 10px;
        }

        .list-group-item {
            background-color: #f8f9fa;
            border: 1px solid #dcdcdc;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        h1 {
            color: #343a40;
        }

        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="container text-center mt-5">
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

    <h1 class="mb-4 text-primary">To-do de <?php echo "$nome" ?></h1>

    <!-- Formulário para adicionar tarefas -->
    <div class="card p-4 mb-5">
        <h4 class="card-title text-dark">Adicionar nova tarefa</h4>
        <form action="manipular_tarefas.php" method="post" class="form-inline justify-content-center">
            <input type="text" name="tarefa" id="id_tarefa" class="form-control mr-2" placeholder="Digite sua tarefa aqui" required />
            <input type="submit" value="Adicionar" class="btn btn-primary" />
        </form>
    </div>

    <!-- Formulário para dar check nas tarefas -->
    <?php
    # buscando o id do usuario na tabela usuarios
    $sql = "SELECT id_usuario FROM usuarios WHERE nome = '$nome'";
    $result_id = $conn->query($sql);

    if ($result_id->num_rows > 0) {
        $result_id = $result_id->fetch_assoc();
        $id_usuario = $result_id['id_usuario'];
    }
    ?>

    <div class="card p-4">
        <h4 class="card-title text-dark">Tarefas</h4>
        <form action="manipular_tarefas.php" method="post">
            <ul class="list-group">
                <?php
                # buscando itens relacionados ao id do usuário
                $sql = "SELECT * FROM todos WHERE user_id = '$id_usuario'";
                $result_itens = $conn->query($sql);

                if ($result_itens->num_rows > 0) {
                    $data_id = 1;
                    while ($item = $result_itens->fetch_assoc()) {
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?php echo $item['id']; ?>">
                            <div>
                                <input type="checkbox" name="itens_selecionados[]" value="<?php echo $item['id']; ?>" <?php if($item['completado'] == 1) echo 'checked'?>/>
                                <span class="tarefa_texto"><?php echo htmlspecialchars($item['tarefa']); ?></span>
                            </div>
                            <div>
                                <input type="button" class="btn btn-warning btn-sm editar" value="Editar" />
                                <input type="button" class="btn btn-danger btn-sm excluir" value="Excluir" />
                            </div>
                        </li>
                        <?php
                        $data_id++;
                    }
                }
                ?>
            </ul>
            <div class="text-center mt-3">
                <input type="submit" id="botao_enviar" value="Salvar" class="btn btn-success" />
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            $("#botao_enviar").click(function(){
                alert("Alterações salvas!");
            });

            $(".excluir").click(function(){
                var id = $(this).parent().parent().data("id");
                var confirmacao = confirm("Tem certeza que deseja excluir este item da lista?");
                if (confirmacao) {
                    $.ajax({
                        url: "add_tarefas.php",
                        type: "POST",
                        data: { id: id },
                        success: function(response) {
                            alert('Item excluído com sucesso!');
                            location.reload(); // Atualiza a página após exclusão
                        },
                        error: function() {
                            alert('Erro ao excluir o item.');
                        }
                    });
                } else {
                    alert('Exclusão cancelada!');
                }
            });

            $(".editar").click(function() {
                var li = $(this).closest('li');
                var currentText = li.find('.tarefa_texto').text().trim();
                var itemId = li.data('id');

                if (!li.find('input[type="text"]').length) {
                    var inputField = $('<input>', {
                        type: 'text',
                        value: currentText,
                        blur: function() {
                            var newText = $(this).val();
                            if (newText.trim() !== "") {
                                li.find('.tarefa_texto').text(newText);

                                $.ajax({
                                    url: 'add_tarefas.php',
                                    method: 'POST',
                                    data: { id: itemId, newText: newText },
                                    success: function(response) {
                                        alert('Item atualizado com sucesso!');
                                    },
                                    error: function() {
                                        alert('Erro ao atualizar o item.');
                                    }
                                });
                            } else {
                                alert('O texto da tarefa não pode ser vazio!');
                            }
                        }
                    });

                    li.find('.tarefa_texto').html(inputField);
                    inputField.focus();
                }
            });
        });
    </script>
</div>
</body>
</html>
