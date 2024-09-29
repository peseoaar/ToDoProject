<?php
include 'conectar.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST"){
      $nome = trim($_POST['nome']);
      $senha = trim($_POST['senha']);

      $sql = "SELECT * FROM usuarios where nome = '$nome'";
      $result = $conn->query($sql);

      if ($result->num_rows == 0){
          $sql = "INSERT INTO usuarios (nome, senha) VALUES (lower('$nome'), '$senha')";
          $result = $conn->query($sql);
          echo "usuario cadastrado com sucesso!";
          echo " Clique para voltar a area de <a href='index.html'>Login</a>";
      } else {
         echo "<a>usuario ja cadastrado! escolha outro nome</a><br>";
         echo "<a href='cadastro.html'>Cadastrar-se</a>";
      }
      $conn->close();
}
?>