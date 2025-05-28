<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Conexão com o banco
$host = "127.0.0.1";
$port = "13360";
$user = "aluno4";
$pass = "+OMssWU7YAg="; 
$db = "fasiclin";

$conn = new mysqli("$host:$port", $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Erro na conexão"]));
}

// Recebe os dados JSON
$data = json_decode(file_get_contents("php://input"), true);
$cpf = $data["cpf"] ?? null;
$dataNasc = $data["data_nasc"] ?? null;

if (!$cpf || !$dataNasc) {
    echo json_encode(["success" => false, "message" => "CPF e data de nascimento obrigatórios"]);
    exit;
}

// Consulta no banco
$sql = "SELECT * FROM PESSOAFIS WHERE CPFPESSOA = ? AND DATANASCPES = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cpf, $dataNasc);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => true, "message" => "Usuário encontrado"]);
} else {
    echo json_encode(["success" => false, "message" => "Usuário não encontrado"]);
}

$conn->close();
?>
