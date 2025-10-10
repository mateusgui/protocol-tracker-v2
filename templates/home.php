<?php require __DIR__ . '/components/_header.php'; ?>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<main class="conteudo-principal">
    <div class="welcome-card">
        <h2>Bem-vindo ao Protocol Tracker!</h2>
        <p>Use o menu à esquerda para navegar pelas funcionalidades do sistema.</p>
    </div>
</main>

<?php require __DIR__ . '/components/_footer.php'; ?>