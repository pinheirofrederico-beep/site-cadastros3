<?php
require_once 'db.php';

// Pegando os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];
$acao = $_POST['tipo_acao'];

if ($acao == "cadastro") {
    // Criptografia de senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Evita SQL Injection usando prepare
    $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $senhaHash);
    
    if ($stmt->execute()) {
        // Cadastro feito com sucesso? Vai para notícias
        header("Location: noticias.html");
        exit();
    } else {
        echo "Erro ao cadastrar: E-mail já existe ou erro no banco.";
    }

} else {
    // Lógica de Login
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user['senha'])) {
            // Senha correta? Vai para notícias
            header("Location: noticias.html");
            exit();
        } else {
            echo "Senha incorreta! <a href='index.html'>Tentar novamente</a>";
        }
    } else {
        echo "Usuário não cadastrado! <a href='index.html'>Cadastre-se aqui</a>";
    }
}

$stmt->close();
$conn->close();
?>
