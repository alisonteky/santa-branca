<?php
// ==============================
// CONFIGURAÇÕES
// ==============================
$destinatario = 'ti@jtu.com.br';
$assunto = 'Fale Conosco - Site Santa Branca Transportes';

// ==============================
// BLOQUEIA ACESSO DIRETO
// ==============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Acesso inválido.');
}

// ==============================
// HONEYPOT (ANTI-SPAM)
// ==============================
if (!empty($_POST['website'])) {
  exit('Envio inválido.');
}

// ==============================
// SANITIZAÇÃO
// ==============================
function limpar($valor) {
  return trim(strip_tags($valor));
}

$nome     = limpar($_POST['nome'] ?? '');
$empresa  = limpar($_POST['empresa'] ?? '');
$email    = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$tipo     = limpar($_POST['tipo'] ?? '');
$mensagem = limpar($_POST['mensagem'] ?? '');

// ==============================
// VALIDAÇÃO
// ==============================
if (!$nome || !$email || !$tipo || !$mensagem) {
  exit('Por favor, preencha todos os campos obrigatórios.');
}

// ==============================
// MENSAGEM FORMATADA
// ==============================
$corpo = "
Novo contato recebido pelo site:

Nome: $nome
Empresa: $empresa
E-mail: $email
Tipo: $tipo

Mensagem:
$mensagem
";

// ==============================
// HEADERS SEGUROS
// ==============================
$headers = [
  'From: Santa Branca Transportes <no-reply@santabrancatransportes.com.br>',
  'Reply-To: ' . $email,
  'Content-Type: text/plain; charset=UTF-8'
];

// ==============================
// ENVIO
// ==============================
if (mail($destinatario, $assunto, $corpo, implode("\r\n", $headers))) {
  header('Location: obrigado.html');
  exit;
} else {
  exit('Erro ao enviar a mensagem. Tente novamente, por favor.');
}
