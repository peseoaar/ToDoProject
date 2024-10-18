
# To-do List PHP 

Este é um projeto simples de uma **To-do list** (lista de tarefas) criado com **PHP**, **MySQL**, **Bootstrap** e **jQuery**. A aplicação permite que os usuários se registrem, façam login e gerenciem suas tarefas, podendo adicionar, editar, marcar como completas e excluir itens.

## Funcionalidades

- **Autenticação de Usuário**: Usuários podem se registrar e fazer login.
- **Adicionar Tarefas**: Usuários podem adicionar novas tarefas à sua lista.
- **Editar Tarefas**: Usuários podem editar tarefas já adicionadas.
- **Excluir Tarefas**: Usuários podem excluir tarefas da lista.
- **Marcar Tarefas como Concluídas**: Usuários podem marcar tarefas como concluídas.
- **Responsivo**: A interface se adapta a diferentes tamanhos de tela usando Bootstrap.



## Requisitos

Para rodar este projeto, você precisará ter:

- **PHP** (versão 7.x ou superior)
- **MySQL** ou **MariaDB**
- Um **servidor web** (como Apache ou Nginx)

## Configuração

1. **Clone o repositório**:

   ```bash
   git clone https://github.com/peseoaar/ToDoProject.git
   cd todo-list
   ```

2. **Configurar o Banco de Dados**:

    - Crie um banco de dados no MySQL com o nome `todolist`.
    - Importe o script SQL para criar as tabelas necessárias:

   ```sql
   CREATE TABLE `usuarios` (
       `id_usuario` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
       `nome` VARCHAR(255) NOT NULL,
       `senha` VARCHAR(255) NOT NULL
   );

   CREATE TABLE `todos` (
       `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
       `tarefa` TEXT NOT NULL,
       `completado` BOOLEAN DEFAULT 0,
       `user_id` INT NOT NULL,
       FOREIGN KEY (`user_id`) REFERENCES `usuarios`(`id_usuario`)
   );
   ```

3. **Configuração do arquivo de conexão**:

   Abra o arquivo `conectar.php` e altere as informações de conexão com o banco de dados:

   ```php
   <?php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "todolist";

   $conn = new mysqli($servername, $username, $password, $dbname);

   if ($conn->connect_error) {
       die("Falha na conexão: " . $conn->connect_error);
   }
   ?>
   ```

4. **Rodando o projeto**:

    - Coloque o projeto no diretório do seu servidor web.
    - Acesse a aplicação pelo navegador em `http://localhost/seu-projeto`.

## Uso

1. **Login e Registro**:
    - A página inicial exibirá um formulário de login.
    - Se você ainda não tiver uma conta, pode se registrar.

2. **Gerenciar Tarefas**:
    - Adicione novas tarefas digitando no campo "Digite sua tarefa aqui".
    - Clique em "Adicionar" para salvar.
    - Marque as tarefas como concluídas utilizando a caixa de seleção.
    - Edite ou exclua tarefas clicando nos botões correspondentes.

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para enviar **pull requests** ou abrir **issues**.


---

**Autor**: Pedro  
**Contato**: pedroaar20022@gmail.com  
**LinkedIn**: [Pedro LinkedIn](https://www.linkedin.com/in/pedro-augusto-694450207/)
