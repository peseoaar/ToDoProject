<?php
include 'conectar.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST"){
      $nome = $_POST['nome'];
      $senha = $_POST['senha'];

      $sql = "SELECT * FROM usuarios where nome = '$nome'";
      $result = $conn->query($sql);

      if ($result->num_rows == 0){
          $sql = "INSERT INTO usuarios (nome, senha) VALUES ('$nome', '$senha')";
          $result = $conn->query($sql);
          echo "usuario cadastrado com sucesso!";
      } else {
         echo "<a>usuario ja cadastrado! escolha outro nome</a><br>";
         echo "<a href='cadastro.html'>Cadastrar-se</a>";
      }
      $conn->close();
}
?>