<?php
require_once 'db.php';

$email = $_POST['email'];
$senha = $_POST['senha'];
$acao = $_POST['tipo_acao'];

if ($acao == "cadastro") {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $senhaHash);
    
    if ($stmt->execute()) {
        echo "Sucesso! <a href='index.html'>Logar</a>";
    } else {
        echo "Erro ao cadastrar. Talvez e-mail já exista.";
    }
} else {
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user['senha'])) {
            echo "Login realizado!";
        } else {
            echo "Senha inválida.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
}
?>
