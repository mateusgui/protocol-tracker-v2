<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Dashboard Remessas</h2>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../components/_footer.php'; ?>