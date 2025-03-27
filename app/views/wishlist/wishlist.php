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

<style>
.wishlist-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.wishlist-title {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.wishlist-empty {
    text-align: center;
    padding: 50px;
    background: #f8f9fa;
    border-radius: 8px;
}

.wishlist-items {
    display: grid;
    gap: 20px;
}

.wishlist-item {
    display: grid;
    grid-template-columns: 200px 1fr auto;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.item-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.item-details h3 {
    margin: 0;
    color: #333;
}

.item-price {
    font-size: 1.2em;
    font-weight: bold;
    color: #2c5282;
}

.item-description {
    color: #666;
}

.item-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
}

.btn-primary {
    background: #2c5282;
    color: white;
}

.btn-info {
    background: #4299e1;
    color: white;
}

.btn-danger {
    background: #e53e3e;
    color: white;
}

.btn:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .wishlist-item {
        grid-template-columns: 1fr;
    }
    
    .item-image img {
        height: 150px;
    }
}
</style>