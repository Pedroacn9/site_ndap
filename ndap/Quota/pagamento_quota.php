<?php
include('../login/conexao.php');

// Obter todas as modalidades da tabela "modalidades"
$sql_modalidades = "SELECT id_modalidades, tipo, preco FROM modalidades";
$result_modalidades = $mysqli->query($sql_modalidades); // Ajustado para usar $mysqli

if (!$result_modalidades) {
    die("Erro ao buscar modalidades: " . $mysqli->error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $id_modalidades = $_POST['id_modalidades'];
    $due_date = $_POST['due_date'];
    $payment_method = $_POST['payment_method'];
    $transaction_id = null;

    // Busca o preço da modalidade selecionada
    $sql_preco = "SELECT preco FROM modalidades WHERE id_modalidades = ?";
    $stmt_preco = $mysqli->prepare($sql_preco);
    $stmt_preco->bind_param('i', $id_modalidades);
    $stmt_preco->execute();
    $result_preco = $stmt_preco->get_result();

    if ($result_preco->num_rows > 0) {
        $modalidade = $result_preco->fetch_assoc();
        $amount = $modalidade['preco'];

        // Busca o id_utilizador pelo e-mail informado
        $sql_utilizador = "SELECT id_utilizador FROM utilizador WHERE email = ?";
        $stmt_utilizador = $mysqli->prepare($sql_utilizador);
        $stmt_utilizador->bind_param('s', $email);
        $stmt_utilizador->execute();
        $result_utilizador = $stmt_utilizador->get_result();

        if ($result_utilizador->num_rows > 0) {
            // O e-mail foi encontrado
            $utilizador_data = $result_utilizador->fetch_assoc();
            $id_utilizador = $utilizador_data['id_utilizador'];

            // Insere a nova quota no banco de dados
            $sql = "INSERT INTO quota (id_utilizador, amount, due_date, payment_method, transaction_id, payment_status) 
                    VALUES (?, ?, ?, ?, ?, 'pending')";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('idsss', $id_utilizador, $amount, $due_date, $payment_method, $transaction_id);

            if ($stmt->execute()) {
                $success_message = "Quota regist
                ada com sucesso.";
            } else {
                $error_message = "Erro ao registrar quota: " . $mysqli->error;
            }

            $stmt->close();
        } else {
            // E-mail não encontrado
            $error_message = "E-mail não encontrado na tabela de utilizadores.";
        }

        $stmt_utilizador->close();
    } else {
        $error_message = "Modalidade inválida.";
    }

    $stmt_preco->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento de Quota</title>
    <link rel="stylesheet" href="styles.css"> <!-- Arquivo CSS externo -->
</head>
<body>
    <div class="container">
        <h1>Registrar Pagamento de Quota</h1>

        <!-- Exibe mensagens de sucesso ou erro -->
        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <?= $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">E-mail do Utilizador:</label>
            <input type="email" id="email" name="email" required>

            <label for="id_modalidades">Modalidade:</label>
            <select id="id_modalidades" name="id_modalidades" onchange="updateAmount()" required>
                <option value="">Selecione a modalidade</option>
                <?php while ($modalidade = $result_modalidades->fetch_assoc()): ?>
                    <option value="<?= $modalidade['id_modalidades'] ?>" data-preco="<?= $modalidade['preco'] ?>">
                        <?= $modalidade['tipo'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="amount">Valor (€):</label>
            <input type="text" id="amount" name="amount" readonly>

            <label for="due_date">Data de Vencimento:</label>
            <input type="date" id="due_date" name="due_date" required>

            <label for="payment_method">Método de Pagamento:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cartao">Cartão de Crédito</option>
                <option value="paypal">PayPal</option>
                <option value="mbway">MB WAY</option>
            </select>

            <button type="submit">Registar Quota</button>
        </form>
    </div>

    <script>
        function updateAmount() {
            const modalidadeSelect = document.getElementById('id_modalidades');
            const selectedOption = modalidadeSelect.options[modalidadeSelect.selectedIndex];
            const preco = selectedOption.getAttribute('data-preco');
            document.getElementById('amount').value = preco ? parseFloat(preco).toFixed(2) : '';
        }
    </script>
</body>
</html>
