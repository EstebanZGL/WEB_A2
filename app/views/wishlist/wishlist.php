<?php
// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit();
}

// En-tête de la page
?>
<div class="wishlist-container">
    <h1 class="wishlist-title">Ma Liste de Souhaits</h1>

    <?php if (empty($wishlistItems)): ?>
        <link rel="stylesheet" href="public/css/wishlist.css">
        <div class="wishlist-empty">
            <p>Votre liste de souhaits est vide.</p>
            <a href="/offres" class="btn btn-primary">Découvrir nos offres</a>
        </div>
    <?php else: ?>
        <div class="wishlist-items">
            <?php foreach ($wishlistItems as $item): ?>
                <div class="wishlist-item">
                    <div class="item-image">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['titre']) ?>">
                    </div>
                    <div class="item-details">
                        <h3><?= htmlspecialchars($item['titre']) ?></h3>
                        <p class="item-price"><?= number_format($item['prix'], 2, ',', ' ') ?> €</p>
                        <p class="item-description"><?= htmlspecialchars($item['description']) ?></p>
                    </div>
                    <div class="item-actions">
                        <a href="/offres/details/<?= $item['id'] ?>" class="btn btn-info">Voir détails</a>
                        <form action="/wishlist/remove" method="POST" class="remove-form">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-danger">Retirer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
